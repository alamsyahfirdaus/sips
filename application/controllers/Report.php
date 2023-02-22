<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();

		$this->load->model('Lap_presensi_siswa_model', 'lps');
		$this->load->model('Tapel_model', 'tapel');

	}

	public function index()
	{
		# REKAP PRESENSI SISWA // ADMINISTRATOR

		$tapel = $this->db->get_where('tahun_pelajaran', ['is_aktif' => 'Y'])->row();

		$data = [
			'folder'				=> 'Rekap',
			'title' 				=> 'Presensi Siswa',
			'tahun_pelajaran'		=> $this->mall->get_tapel(),
			'tahun_pelajaran_id'	=> @$tapel->tahun_pelajaran_id,
			'semester'				=> ['1' => '1 (Ganjil)', '2' => '2 (Genap)'],
			'id_semester'			=> @$tapel->semester,
			'kelas'					=> $this->mall->get_kelas(),

		];

		$this->include->content('laporan/laporan_presensi_siswa_r2', $data);

	}

	# MULAI FUNGSI LAPORAN PRESENSI SISWA

	// public function presence()
	// {	
	// 	$tapel = $this->db->get_where('tahun_pelajaran', ['is_aktif' => 'Y'])->row();
	// 	$data = [
	// 		'folder'				=> 'Laporan',
	// 		'title' 				=> 'Presensi Siswa',
	// 		'tahun_pelajaran'		=> $this->db->get('tahun_pelajaran')->result(),
	// 		'tahun_pelajaran_id'	=> @$tapel->tahun_pelajaran_id,
	// 		'semester'				=> ['1' => '1 (Ganjil)', '2' => '2 (Genap)'],
	// 		'id_semester'			=> @$tapel->semester,
	// 		'kelas'					=> $this->db->get('kelas')->result(),
	// 	];

	// 	$this->include->content('laporan/laporan_presensi_siswa', $data);
	// }

	public function showPresence()
	{
		$this->load->model('Rekap_presensi_kelas_model', 'rpkm');
		$data  = $this->rpkm->getDataTables();

		// $data  = $this->lps->getDataTables();
		echo json_encode($data);
	}

	// public function getMapel($id_tahun_pelajaran = NULL, $id_kelas = NULL)
	// {
	// 	$query = $this->lps->get_mata_pelajaran($id_tahun_pelajaran, $id_kelas);
	// 	echo json_encode($query);
	// }

	public function rps()
	{
		# Laporan Presensi Per Semester
		
		$this->load->library('Pdf');

		$tapel = $this->db->get_where('tahun_pelajaran', ['md5(tahun_pelajaran_id)' => $this->input->post('id_tahun_pelajaran')])->row();

		if (!@$tapel->tahun_pelajaran_id) {
			redirect('schedules');
		}

		$kelas 		= $this->db->get_where('kelas', ['md5(kelas_id)' => $this->input->post('id_kelas')])->row();
		$semester   = $this->input->post('id_semester');

		$data = [
			'title'				=> LOGO_SM . ' ' . TITLE,
			'query'				=> $this->_query_siswa($this->input->post('id_user'), $this->input->post('id_kelas')),
			'tapel'				=> $tapel->tahun_pelajaran,
			'semester'			=> $semester . ' / ' . $this->include->semester($semester),
			'kelas'				=> $kelas->nama_kelas,
			'thead'				=> $this->_content_rps($semester, $this->input->post('id_kelas')),
			'id_tahun_pelajaran'=> $tapel->tahun_pelajaran_id,
			'id_semester'		=> $semester,
		];

		$this->load->view('content/laporan/presensi_per_semester', $data);
	}

	private function _query_siswa($id_user = NULL, $id_kelas = NULL)
	{
		$this->db->join('siswa s', 's.id_user = u.user_id', 'left');
		$this->db->where('u.user_type_id', 3);
		$this->db->where('u.delete_at', NULL);
		// if ($id_user) {
		// 	$this->db->where('md5(u.user_id)', $id_user);
		// }
		$this->db->where('md5(s.id_kelas)', $id_kelas);
		return $this->db->get('user u')->result();
	}

	public function _content_rps($semester, $id_kelas, $id_user = NULL)
	{
		$bulan 		= $this->include->bulanSmt($semester);

        $content = '<tr style="font-weight: bold;">';
        $content .= '<th rowspan="3" width="5%" style="text-align: center; margin-top: 20px;">No</th>';
        $content .= '<th rowspan="3" width="10%" style="text-align: center;">NIS</th>';
        $content .= '<th rowspan="3" width="10%" style="text-align: center;">Nama</th>';
        $content .= '<th rowspan="3" width="5%" style="text-align: center;">JK</th>';
        $content .= '<th colspan="24" width="60%" style="text-align: center; font-weight: bold;">Bulan</th>';
        $content .= '<th rowspan="2" colspan="4" width="10%" style="text-align: center;">Jumlah</th>';
        $content .= '</tr>';
        $content .= '<tr>';
        for ($i = 0; $i <= 6; $i++) { 
	        $content .= '<th colspan="4" style="text-align: center; font-weight: bold;">'. $bulan[$i] .'</th>';
        }
        $content .= '</tr>';
        $content .= '<tr>';
        for ($i = 1; $i <= 7; $i++) { 
	        foreach (['H','S','I','A'] as $key) {
		        $content .= '<th style="text-align: center; font-weight: bold;">'. $key .'</th>';
	        }
        }
        $content .= '</tr>';

        return $content;
	}

	public function student($id_user = NULL)
	{
		# Laporan Presensi Per Siswa
		
		$this->load->library('Pdf');

		$query = $this->db->join('siswa s', 's.id_user = u.user_id', 'left')->join('kelas k', 'k.kelas_id = s.id_kelas', 'left')->get_where('user u', ['md5(u.user_id)' => $id_user])->row();

		if (!$query || !$this->input->post('id_tahun_pelajaran')) {
			redirect('report/presence');
		}

		$tapel 	 	= $this->db->get_where('tahun_pelajaran', ['md5(tahun_pelajaran_id)' => $this->input->post('id_tahun_pelajaran')])->row();
		$semester   = $this->input->post('id_semester');
		$jadwal_pelajaran = $this->db->order_by('sort', 'asc')->get_where('jadwal_pelajaran', [
			'md5(id_tahun_pelajaran)' => $this->input->post('id_tahun_pelajaran'),
			'id_kelas'				  => $query->id_kelas,
		])->result();

		$data = [
			'title'			=> LOGO_SM . ' ' . TITLE,
			'query'			=> $jadwal_pelajaran,
			'thead'			=> $this->_content_student($semester),
			'tapel'			=> $tapel->tahun_pelajaran,
			'semester'		=> $semester . ' / ' . $this->include->semester($semester),
			'id_semester'	=> $semester,
			'kelas'			=> $query->nama_kelas,
			'nis'			=> $this->include->null($query->no_induk),
			'nama'			=> $query->full_name,
			'user_id'		=> $query->user_id,
			'jenis_kelamin'	=> $query->gender == 'L' ? 'Laki-Laki' : 'Perempuan',
		];

		$this->load->view('content/laporan/presensi_per_siswa', $data);
	}

	public function _content_student($semester)
	{
		# Rekap Presensi Per Siswa
		
		$bulan = $this->include->bulanSmt($semester);

        $content = '<tr style="font-weight: bold;">';
        $content .= '<th rowspan="3" width="5%" style="text-align: center; margin-top: 20px;">No</th>';
        $content .= '<th rowspan="3" width="25%" style="text-align: center;">Kode - Mata Pelajaran</th>';
        $content .= '<th colspan="24" width="60%" style="text-align: center; font-weight: bold;">Bulan</th>';
        $content .= '<th rowspan="2" colspan="4" width="10%" style="text-align: center;">Jumlah</th>';
        $content .= '</tr>';
        $content .= '<tr>';
        for ($i = 1; $i <= 6; $i++) { 
	        $content .= '<th colspan="4" style="text-align: center; font-weight: bold;">'. $bulan[$i] .'</th>';
        }
        $content .= '</tr>';
        $content .= '<tr>';
        for ($i = 1; $i <= 7; $i++) { 
	        foreach (['H','S','I','A'] as $key) {
		        $content .= '<th style="text-align: center; font-weight: bold;">'. $key .'</th>';
	        }
        }
        $content .= '</tr>';

        return $content;
	}

	public function showDetailPresence()
	{
		$this->load->model('Lap_presensi_siswa_model', 'lps');
		$data = $this->lps->getDetailPresensi();
		echo json_encode($data);
	}

	public function changeStatus($id = NULL)
	{
		$query = $this->db->get_where('presensi', ['presensi_id' => $this->input->post('presensi_id')])->row();

		if (!$query) {
			show_404();
		}

		$this->db->update('presensi', ['status' => $this->input->post('status')], ['presensi_id' => $query->presensi_id]);
		$output = array(
			'status' 	=> TRUE,
			'message' 	=> 'Berhasil Mengubah Presensi Siswa',
		);
		echo json_encode($output);
	}

	public function checkMapel($id_tahun_pelajaran = NULL)
	{
		$query = $this->db->get_where('jadwal_pelajaran', [
			'md5(id_tahun_pelajaran)' => $id_tahun_pelajaran,
			'md5(id_kelas)'			  => $this->input->post('id_kelas'),
		])->num_rows();

		$output = $query > 0 ? TRUE : FALSE;
		echo json_encode(['status' => $output]);
	}

	# SELESAI FUNGSI LAPORAN PRESENSI SISWA
	
	# LAPORAN PRESENSI GURU (JADWAL MENGAJAR > PRINT)
	
	public function rpsg()
	{
		$this->load->library('Pdf');

		$id_jadwal_pelajaran 	= $this->input->post('ijp');
		$id_kelas 				= $this->input->post('kls');
		$semester 				= $this->input->post('smt');

		$query = $this->db->join('mata_pelajaran mp', 'mp.mapel_id = jp.id_mata_pelajaran', 'left')->join('kelas k', 'k.kelas_id = jp.id_kelas', 'left')->join('tahun_pelajaran tp', 'tp.tahun_pelajaran_id = jp.id_tahun_pelajaran', 'left')->get_where('jadwal_pelajaran jp', ['md5(jp.jadwal_pelajaran_id)' => $id_jadwal_pelajaran])->row();

		$data = [
			'title'					=> LOGO_SM . ' ' . TITLE,
			'query'					=> $this->_query_siswa(NULL, $id_kelas),
			'tapel'					=> $query->tahun_pelajaran,
			'mapel'					=> $query->nama_mapel,
			'semester'				=> $semester . ' / ' . $this->include->semester($semester),
			'kelas'					=> $query->nama_kelas,
			'thead'					=> $this->_content_rps($semester, $id_kelas),
			'id_semester'			=> $semester,
			'id_jadwal_pelajaran' 	=> $query->jadwal_pelajaran_id,
		];

		$this->load->view('content/laporan/ppsg', $data);
	}

	# REVISI 18/09/2021

	// public function getTglPresensi($id_tahun_pelajaran)
	// {
	// 	$query = $this->db->join('jadwal_pelajaran', 'jadwal_pelajaran.jadwal_pelajaran_id = presensi.id_jadwal_pelajaran', 'left')->group_by('tanggal')->order_by('tanggal', 'desc')->get_where('presensi', [
	// 		'md5(id_tahun_pelajaran)'	=> $id_tahun_pelajaran,
	// 		'md5(id_kelas)'				=> $this->input->get('id_kelas'),
	// 		'semester'					=> $this->input->get('semester'),
	// 	])->result();

	// 	if (count($query) > 0) {
	// 		foreach ($query as $key) {
	// 			$data[] = array(
	// 				'id_tgl' 	=> $key->tanggal, 
	// 				'tanggal' 	=> $this->include->date($key->tanggal), 
	// 			);
	// 		}

	// 		echo json_encode($data);
	// 	}

	// }

	// public function getBlnPresensi($semester, $id_tahun_pelajaran)
	// {
	// 	$tapel  	= $this->db->get_where('tahun_pelajaran', ['md5(tahun_pelajaran_id)' => $id_tahun_pelajaran])->row();
	// 	$check_smt  = $semester == 1 || $semester == 2 ? TRUE : FALSE;

	// 	if ($tapel && $check_smt) {
	// 		$explode 	= explode('/', $tapel->tahun_pelajaran);
	// 		$first 		= $explode[0];
	// 		$last 		= $explode[1];
	// 		$tahun 		= $semester == 1 ? $explode[0] : $explode[1];

	// 		$bulan 	= $this->include->bulanSmt($semester);
	// 		$no 	= 1;

	// 		foreach ($bulan as $key) {
	// 			$data[] = array(
	// 				'id_bln' => $this->include->intBulanSmt($semester, $no),
	// 				'bulan'  => $key .' '. $tahun,
	// 			);

	// 			$no++;
	// 		}

	// 		echo json_encode($data);
	// 	}

	// }

	// public function get_siswa($kelas_id = null)
	// {
	// 	$this->load->model('Siswa_model', 'siswa');
	// 	$query = $this->siswa->get_siswa_kelas($kelas_id);
	// 	if (count($query) > 0) {
	// 		foreach ($query as $key) {
	// 			$data[] = array(
	// 				'id_siswa' 		=> md5($key->id_user),
	// 				'nama_lengkap'  => $key->full_name
	// 			);
	// 		}

	// 		echo json_encode($data);
	// 	}
	// }

}

/* End of file Report.php */
/* Location: ./application/controllers/Report.php */
