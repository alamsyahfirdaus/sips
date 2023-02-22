<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();

		if ($this->session->user_type_id == 3) {
			redirect(site_url());
		}

		$this->load->model('Input_presensi_siswa_model', 'ipsm');
		$this->load->model('Rekap_presensi_siswa_model', 'rpsm');
		$this->load->model('Tapel_model', 'tapel');
		$this->load->model('Siswa_model', 'siswa');
		$this->load->model('Jadwal_model', 'jadwal');

	}

	public function index()
	{
		$query = $this->mall->getSession();

		$data = array(
			'title' 		=> 'Beranda', 
			'sub_title' 	=> 'Profile',
			'row'			=> $query,
			'guru_piket'	=> $this->db->get_where('guru_piket', ['id_user' => $query->user_id])->result(),
		);
		
		$this->include->content('profile/index_profile', $data);
	}

	private function _getTapel()
	{
		$query = $this->db->get_where('tahun_pelajaran', ['is_aktif' => 'Y'])->row();
		return @$query ? $query : FALSE; 
	}

	private function _getJadwal($id)
	{
		$this->load->model('Jadwal_model', 'jadwal');
		$query = $this->jadwal->getRow($id);
		return isset($query->jadwal_pelajaran_id) ? $query : FALSE; 
	}

	# BAGIAN PENGELOLAAAN KEHADIRAN
	
	public function presence($id = NULL)
	{
		# Beranda -> Presensi
		
		$jadwal_pelajaran = $this->_getJadwal($id);

		if (!$jadwal_pelajaran) {
			redirect(site_url());
		}

		$data = array(
			'folder' 	=> 'Beranda', 
			'title' 	=> 'Presensi Siswa',
			'id'		=> $id,
			'content'	=> $this->_detailJadwal($jadwal_pelajaran),
		);

		$this->include->content('guru/kehadiran_siswa', $data);
	}

	private function _detailJadwal($jadwal)
	{
		$tapel = $this->_getTapel();
		$siswa = $this->_getSiswa(@$jadwal->id_kelas);

		$callout = '<input type="hidden" name="id_jadwal_pelajaran" value="'. md5(@$jadwal->jadwal_pelajaran_id) .'">';
		$callout .= '<input type="hidden" name="semester" value="'. @$tapel->semester .'">';
		// $callout .= '<input type="hidden" name="id_kelas" value="'. md5(@$jadwal->id_kelas) .'">';
		$callout .= '<input type="hidden" name="id_kelas" value="">';
		$callout .= '<input type="hidden" name="kelas_id" value="">';
		$callout .= '<b>Mata Pelajaran : '. $this->include->null(@$jadwal->nama_mapel) .'</b>';
		$callout .= '<b>Kelas : '. $this->include->null(@$jadwal->nama_kelas) .'</b>';
		$callout .= '<b>Semester : '. @$tapel->semester . ' / '. $this->include->semester(@$tapel->semester) .'</b>';
		$callout .= '<style type="text/css">.callout{border-left: 3px solid #00A65A; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE; border-bottom: 1px solid #EEEEEE;} .callout b{display: block;}</style>';


		$select = '<select name="user_id" id="user_id" class="form-control select2">';
		$select .= '<option value="">-- Pilih Siswa --</option>';
		foreach ($siswa['result'] as $row) {
			$select .= '<option value="'. md5($row->user_id) .'">'. $row->no_induk .' - '. $row->full_name .'</option>';
		}
		$select .= '</select>';

		return array(
			'callout' 	=> $callout,
			'select'	=> $select,
			'tapel'		=> @$tapel->tahun_pelajaran,
			'siswa'		=> @$siswa['result'],
			'id_kelas'	=> md5(@$jadwal->id_kelas),
			'id_tahun_pelajaran' => md5(@$tapel->tahun_pelajaran_id),
		);
	}

	public function showKehadiran()
	{
		# Menampilkan Kehadiran 
		# Beranda Kehadiran

		$data 	= $this->siswa->getKehadiran();
		echo json_encode($data);
	}

	private function _getSiswa($id_kelas, $user_id = NULL)
	{
		$this->db->join('siswa s', 's.id_user = u.user_id', 'left');
		$this->db->where('user_type_id', 3);
		$this->db->where('delete_at', NULL);
		$this->db->where('id_kelas', $id_kelas);
		if ($user_id) {
			$this->db->where_not_in('user_id', $user_id);
		}
		$query = $this->db->get('user u');

		return array(
			'num_rows' => $query->num_rows(),
			'result'   => $query->result(),
		);
	}

	public function changeAll($id = NULL)
	{
		# Semua Siswa Hadir
		
		$query 	= $this->_getJadwal($id);
		$tapel  = $this->_getTapel();

		if (!$query) {
			show_404();
		}

		$tanggal 	= $this->input->post('tanggal') ? $this->input->post('tanggal') : NULL;
		$kelas 		= $this->_getSiswa($query->id_kelas);
		$presensi 	= $this->_checkDate($tanggal, $id);

		foreach ($presensi['result'] as $row) {
			$id_user[] = $row->id_user;
		}

		$siswa 		= $this->_getSiswa($query->id_kelas, @$id_user);


		$num_rows 	= $kelas['num_rows'] != $presensi['num_rows'];

		if ($num_rows) {
			foreach ($siswa['result'] as $row) {
				$data = array(
					'tanggal' 				=> $this->input->post('tanggal') ? date('Y-m-d', strtotime($this->input->post('tanggal'))) : date('Y-m-d'),
					'id_jadwal_pelajaran '	=> $query->jadwal_pelajaran_id,
					'id_user'				=> $row->user_id,
					'semester'				=> $tapel->semester,
				);

				if ($row->delete_at == NULL) {
					$data['status'] = 1;
				} else {
					$data['status'] = NULL;
				}

				$this->db->insert('presensi', $data);
			}

			$output = array(
				'status' 	=> TRUE,
				'message' 	=> 'Berhasil Melakukan Presensi Siswa',
			);

		}  else {
			$output['status'] = FALSE;
		}

		echo json_encode($output);
	}

	private function _checkDate($tanggal = NULL, $id_jadwal_pelajaran = NULL)
	{
		# Cek Kehadiran Berdasarkan Hari Mengajar (Hari Ini)
		
		$date = $tanggal ? date('Y-m-d', strtotime($tanggal)) : date('Y-m-d');
		
		$query = $this->db->get_where('presensi', [
				'DATE(tanggal)'				=> $date,
				'md5(id_jadwal_pelajaran)'	=> $id_jadwal_pelajaran,
			]);

		return array(
			'num_rows' 	=> $query->num_rows(),
			'result'	=> $query->result(), 
		);
	}

	public function getDate($id = NULL)
	{
		$query = $this->_getJadwal($id);

		if (!$query) {
			show_404();
		}


		$tanggal 	= $this->input->post('tanggal') ? $this->input->post('tanggal') : NULL;
		$kelas 		= $this->_getSiswa($query->id_kelas);
		$presensi 	= $this->_checkDate($tanggal, $id);
		$tgl_input 	= $kelas['num_rows'] == $presensi['num_rows'] ? TRUE : FALSE;

		if ($tgl_input) {
			$output = array(
				'status' 	=> TRUE,
				'message'	=> 'Tanggal sudah diinput'
			);
		} else {
			$output['status'] = FALSE;
		}
		
		echo json_encode($output);
	}

	public function addStatus($id = NULL)
	{
		# Menambahkan Status Kehadiran
		# Beranda -> Kehadiran
		
		

		$query 		= $this->_getJadwal($id);
		$tapel  	= $this->_getTapel();
		$tanggal 	= $this->input->post('tanggal') ? $this->input->post('tanggal') : NULL;
		$presensi 	= $this->siswa->rowPresensi($tanggal, $id, $this->input->post('id_user'), @$tapel->semester, NULL);

		if (!$query) {
			show_404();
		}

		$data = array(
			'id_jadwal_pelajaran '	=> @$query->jadwal_pelajaran_id,
			'id_user'				=> $this->input->post('id_user'),
			'semester'				=> @$tapel->semester,
			'status'				=> $this->input->post('status') ? $this->input->post('status') : NULL,
		);

		if ($presensi) {
			if ($this->input->post('status')) {
				$this->db->update('presensi', $data, ['presensi_id' => $presensi->presensi_id]);
			} else {
				$this->db->delete('presensi', ['presensi_id' => $presensi->presensi_id]);
			}
		} else {
			$data['tanggal'] = $this->input->post('tanggal') ? date('Y-m-d', strtotime($this->input->post('tanggal'))) : date('Y-m-d');
			$this->db->insert('presensi', $data);
		}

		echo json_encode(['status' => TRUE]);
	}

	public function subject($id = NULL)
	{
		# Menampilkan Presensi
		# Jadwal Mengajar -> Presensi
		
		$query = $this->_getJadwal($id);

		if (!$query) {
			show_404();
		}

		$data = array(
			'folder' 	=> 'Jadwal Mengajar', 
			'title' 	=> 'Presensi',
			'content'	=> $this->_detailJadwal($query),
		);

		$this->include->content('guru/rekap_presensi_siswa', $data);
	}

	public function add($id = NULL)
	{
		# Input Presensi
		
		$query = $this->_getJadwal($id);

		if (!$query) {
			show_404();
		}

		$data = array(
			'folder' 	=> 'Jadwal Mengajar', 
			'title' 	=> 'Input',
			'id'		=> $id,
			'content'	=> $this->_detailJadwal($query),
		);

		$this->include->content('guru/input_kehadiran_siswa', $data);
	}

	public function showInputKehadiran()
	{
		# Menampilkan Input Kehadiran 
		# Jadwal Mengajar -> Input

		
		$data 	= $this->siswa->getInputKehadiran();
		echo json_encode($data);
	}

	public function showTanggalInput()
	{
		# Menampilkan Tanggal Input Kehadiran 
		# Jadwal Mengajar -> Lihat Tanggal

		
		$data 	= $this->siswa->getTanggalInput();
		echo json_encode($data);
	}

	public function UpdateDate($id = NULL)
	{
		# Update Tanggal Presensi Siswa
		# Jadwal Mengajar -> Lihat Tanggal
		
		$query = $this->db->get_where('presensi', [
			'tanggal'					=> date('Y-m-d', strtotime($this->input->post('tanggal_old'))),
			'md5(id_jadwal_pelajaran)'	=> $this->input->post('id_jadwal_pelajaran'),
			'semester'					=> $this->input->post('semester')
		])->result();

		foreach ($query as $row) {
			$this->db->update('presensi', ['tanggal' => date('Y-m-d', strtotime($this->input->post('tanggal_new')))], ['presensi_id' => $row->presensi_id]);
		}

		echo json_encode(['status' => TRUE]);
	}

	# BAGIAN WALI KELAS 

	public function classes()
	{
		$query = $this->_checkWakel();

		$semester = $query['semester'] ? $query['semester'] . ' / '. $this->include->semester($query['semester']) : '-';
		$jadwal_pelajaran = $this->db->join('mata_pelajaran', 'mata_pelajaran.mapel_id = jadwal_pelajaran.id_mata_pelajaran', 'left')->get_where('jadwal_pelajaran', ['id_kelas' => $query['id_kelas']])->result();

		$data = array(
			'folder' 				=> 'Wali Kelas', 
			'title' 				=> 'Wali Kelas', 
			'nama_kelas'			=> $query['nama_kelas'],
			'id_kelas'				=> md5($query['id_kelas']),
			'semester'				=> $semester,
			'siswa'					=> $this->_getSiswa($query['id_kelas']),
			'id_semester'			=> $query['semester'],
			'tahun_pelajaran'		=> $this->db->get('tahun_pelajaran')->result(),
			'tahun_pelajaran_id'	=> $query['id_tahun_pelajaran'],
			'tapel'					=> $query['tahun_pelajaran'],
			'jadwal_pelajaran'		=> $jadwal_pelajaran,

		);

		$this->include->content('guru/kelas_wakel_r4', $data);
	}

	public function showKelasWakel()
	{
		# Daftar Siswa (Wali Kelas)
		
		$this->load->model('Wakel_model', 'wakel');
		$data = $this->wakel->getSiswa();
		echo json_encode($data);
	}

	private function _checkWakel()
	{
		$tapel  = $this->_getTapel();
		$wakel  = $this->db->get_where('wali_kelas', [
		  'id_tahun_pelajaran'  	=> @$tapel->tahun_pelajaran_id,
		  'id_user' 				=> $this->session->user_id,
		])->row();

		if (@$wakel) {
			$kelas = $this->db->get_where('kelas', ['kelas_id' => $wakel->id_kelas])->row();

			return array(
				'id_tahun_pelajaran' 	=> $tapel->tahun_pelajaran_id,
				'tahun_pelajaran' 		=> $tapel->tahun_pelajaran,
				'semester' 				=> $tapel->semester,
				'id_kelas' 				=> $kelas->kelas_id,
				'nama_kelas' 			=> $kelas->nama_kelas,
			);
		} else {
			redirect(site_url());
		}
	}

	public function schedule()
	{
		$query = $this->_checkWakel();

		$data = array(
			'folder' 		=> 'Wali Kelas', 
			'title' 		=> 'Jadwal Pelajaran',
			'tapel'			=> $query['tahun_pelajaran'],
			'jadwal_siswa'	=> $this->_getJadwalSiswa($query),
		);

		$this->include->content('guru/jadwal_wakel', $data);
	}

	private function _getJadwalSiswa($field)
	{
		$this->load->model('Jadwal_model', 'jadwal');

		$html = '<div class="box-header with-border">';
		$html .= '<h3 class="box-title">Daftar Jadwal Pelajaran</h3>';
		$html .= '</div>';
		for ($i=1; $i < 7; $i++) { 
			$border = date('w') == $i ? 'border-left: 3px solid #00A65A;' : 'border-left: 3px solid #EEEEEE;';
			$button = date('w') == $i ? 'background-color: #00A65A; color: #FFFFFF;' : 'background-color: #E7E7E7; color: #333333; border: 1px solid #ADADAD;';
			$days 	= date('Y-m-d', strtotime('+'.  ($i - date('w')) .' days'));
			$date 	= @$days ? ', ' . $this->include->date($days) : '';

			$html .= '<div class="box-body">';
			$html .= ' <div class="callout" style="'. $border .' border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">';
			$html .= '<h4 style="font-family: serif;">'. $this->include->days($i) . $date .'</h4>';
			$html .= '<div class="table-responsive">';
			$html .= '<table class="table table-condensed" style="width: 100%">';
			$html .= '<tr>';
			$html .= '<th class="text-center" width="5%">No</th>';
			$html .= '<th width="35%">Kode - Mata Pelajaran</th>';
			$html .= '<th>Guru</th>';
			$html .= '<th>Kelas</th>';
			$html .= '<th width="15%" class="text-center">Jam Pelajaran</th>';
			$html .= '<th width="5%" class="text-center">Presensi</th>';
			$html .= '</tr>';
			$query = $this->jadwal->getData(NULL, @$field['id_tahun_pelajaran'], @$field['id_kelas'], NULL, $i);
			$activation = @$query && @$field['id_tahun_pelajaran'] && @$field['semester'] ? TRUE : FALSE;
			if ($activation) {
				$no = 1;
				foreach ($query as $row) {
					$kode 		= $row->kode_mapel ? $row->kode_mapel : '#';
					$mulai 		= $this->_getJampel($row->mulai);
					$selesai	= $this->_getJampel($row->selesai);
					$html .= '<tr>';
					$html .= '<input type="hidden" name="mapel_'. md5($row->jadwal_pelajaran_id) .'" value="Mata Pelajaran : ' . $row->nama_mapel .'">';
					$html .= '<td class="text-center" width="5%">'. $no++ .'</td>';
					$html .= '<td width="35%">'. $kode . ' - ' . $row->nama_mapel .'</td>';
					$html .= '<td>'. $row->full_name .'</td>';
					$html .= '<td>'. @$field['nama_kelas'] .'</td>';
					$html .= '<td width="15%" class="text-center">'. $mulai . ' - ' . $selesai .'</td>';
					$html .= '<td width="5%" class="text-center">';
					$html .= '<a href="'. site_url('teacher/attendance/' . md5($row->jadwal_pelajaran_id)) .'" class="btn btn-sm" style="'. $button .'"><i class="fa fa-users"></i></a>';
					$html .= '</td>';
					$html .= '</tr>';
				}
			} else {
				$html .= '<tr>';
				$html .= '<td colspan="6"></td>';
				$html .= '</tr>';

			}
			$html .= '</table>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}

		return $html;
	}

	private function _getJampel($id)
	{
		$query = $this->db->get_where('jam_pelajaran', ['jam_pelajaran_id' => $id])->row();
		return @$query ? $this->include->clock($query->jam_pelajaran) : '#';
	}

	public function attendance($id = NULL)
	{
		# Kehadiran Siswa (Wali Kelas)
		# Wali Kelas -> Jadwal Pelajaran -> Kehadiran
		
		$query = $this->_getJadwal($id);

		if (!$query) {
			show_404();
		}

		$data = array(
			'folder' 	=> 'Wali Kelas', 
			'title' 	=> 'Jadwal Pelajaran',
			'header'	=> 'Presensi',
			'content'	=> $this->_detailJadwal($query),
		);

		$this->include->content('guru/rekap_presensi_siswa', $data);
	}

	public function see($user_id = NULL)
	{
		# Detail Siswa
		# Wali Kelas > Kelas > Detail
		
		
		$query = $this->siswa->getData($user_id);

		if (!$query) {
			show_404();
		}

		$data = array(
			'folder' 		=> 'Wali Kelas', 
			'title' 		=> 'Kelas',
			'header'		=> 'Detail',
			'row'			=> $query,
		);

		$this->include->content('guru/detail_siswa_wakel', $data);

	}

	public function update($user_id = NULL)
	{
		# Detail Siswa
		# Wali Kelas > Kelas > Detail > Edit (Siswa)
		
		
		$query = $this->siswa->getData($user_id);

		if (!$query) {
			show_404();
		}

		$data = array(
			'folder' 		=> 'Wali Kelas', 
			'title' 		=> 'Kelas',
			'header'		=> 'Detail',
			'id'			=> $user_id,
			'row'			=> $query,
			'agama'			=> @$query->agama ? $query->agama : 'Islam',
		);

		$this->include->content('guru/edit_siswa_wakel', $data);

	}

	public function parents($user_id = NULL)
	{
		# Update Biodata Ayah, Ibu & Wali
		
		
		$query = $this->siswa->getData($user_id);

		if (!@$query || @$query->user_type_id != 3) {
			show_404();
		}

		$data = array(
			'folder' 		=> 'Wali Kelas', 
			'title' 		=> 'Kelas',
			'header'		=> 'Detail',
			'row'			=> $query,
			// 'pendidikan'	=> ['SD/Sederajat', 'SMP/Sederajat', 'SMA/Sederajat', 'Diploma 3', 'Diploma 4', 'Strata 1' ,'Strata 2', 'Strata 3', 'Tidak Sekolah'],
			'pendidikan'	=> ['SD/Sederajat', 'SMP/Sederajat', 'SMA/Sederajat', 'Diploma 3', 'Diploma 4', 'Strata 1', 'Tidak Sekolah'],
			'penghasilan'	=> ['<1 Juta', '1-2 Juta', '2-3 Juta', '>3 Juta', 'Tanpa Penghasilan'],
			// 'penghasilan'	=> ['<1 Juta', '1-2 Juta', '2-3 Juta', '3-4 Juta', '4-5 Juta', '>5 Juta', 'Tanpa Penghasilan'],
			'pekerjaan'		=> ['Tidak Bekerja', 'Pensiunan', 'Pegawai Negeri Sipil', 'TNI/POLRI', 'Karyawan Swasta', 'Petani', 'Nelayan', 'Buruh Harian Lepas', 'Pedagang', 'Wiraswasta', 'Lainnya'],
			'pekerjaan_ibu'		=> ['Tidak Bekerja', 'Ibu Rumah Tangga', 'Pensiunan', 'Pegawai Negeri Sipil', 'TNI/POLRI', 'Karyawan Swasta', 'Petani', 'Nelayan', 'Buruh Harian Lepas', 'Pedagang', 'Wiraswasta', 'Lainnya'],
		);

		$this->include->content('guru/edit_parents_wakel', $data);
	}

	public function detail($id = NULL)
	{
		# Detail Jadwal Mengajar Guru
		# Jadwal Mengajar > Presensi Siswa
		
		$query = $this->_getJadwal($id);

		if (!$query) {
			show_404();
		}
		
		$data = array(
			'folder' 	=> 'Jadwal Mengajar',
			'title'	 	=> 'Presensi Siswa',
			'content'	=> $this->_detailJadwal($query),
		);
		
		$this->include->content('guru/detail_jadwal_mengajar_r2', $data);
	}

	public function deletePresence($presensi_id = NULL)
	{
		// HAPUS TANGGAL PRESENSI BERDASARKAN TANGGAL & JADWAL PELAJARAN
		
		$query = $this->db->get_where('presensi', ['md5(presensi_id)' => $presensi_id])->row();

		if (!$query) {
			show_404();
		}

		$query1 = $this->db->get_where('presensi', [
			'tanggal'	=> $query->tanggal,
			'id_jadwal_pelajaran'	=> $query->id_jadwal_pelajaran
		])->result();

		foreach ($query1 as $row) {
			if ($this->session->user_type_id == 1) {
				$this->db->update('presensi', ['delete_at' => date('Y-m-d H:i:s')], ['presensi_id' => $row->presensi_id]);
			} else {
				$this->db->delete('presensi', ['presensi_id' => $row->presensi_id]);
			}
		}

		$output = [
			'status' => TRUE,
			'message'=> 'Berhasil Menghapus Tanggal Presensi',
		];

		echo json_encode($output);
	}

	# REVISI 19/09/2021

	public function show_input_presensi_siswa()
	{
		$query 		= $this->_getJadwal($this->input->post('id_jadwal_pelajaran'));

		if (!$query) {
			show_404();
		}

		$arr_date 	= $this->tapel->get_date(@$query->hari, @$query->jadwal_pelajaran_id, $this->input->post('semester'));
		$date    	= $arr_date['date'];

		$data = $this->ipsm->getDataTables($date);
		echo json_encode($data);
	}

	public function show_tanggal_input_presensi_siswa()
	{
		$data = $this->ipsm->getTanggalInput();
		echo json_encode($data);
	}

	public function show_rekap_presensi_siswa()
	{
		$query 	= $this->_getJadwal($this->input->post('id_jadwal_pelajaran'));

		if (!$query) {
			show_404();
		}

		$arr_week 	= $this->tapel->get_date(@$query->hari, @$query->jadwal_pelajaran_id, $this->input->post('semester'));
		$week    	= $arr_week['week'];

		$data = $this->rpsm->getDataTables($week);
		echo json_encode($data);
	}

	public function get_tgl_by_jadwal($semester, $id_jadwal_pelajaran)
	{
		$query = $this->db->group_by('tanggal')->order_by('tanggal', 'desc')->get_where('presensi', [
			'md5(id_jadwal_pelajaran)'	=> $id_jadwal_pelajaran,
			'semester'					=> $semester,
		])->result();

		if (count($query) > 0) {
			foreach ($query as $key) {
				$data[] = array(
					'id_tgl' 	=> $key->tanggal, 
					'tanggal' 	=> $this->include->date($key->tanggal), 
				);
			}

			echo json_encode($data);
		}
	}

	public function add_tgl_tidak_efektif($id_jadwal_pelajaran)
	{
		// Revisi 18/10/2021
		
		$query 	= $this->_getJadwal($id_jadwal_pelajaran);

		if (empty($query->jadwal_pelajaran_id)) {
			show_404();
		}

		$query1 = $this->db->get_where('presensi', [
			'tanggal'				=> $this->input->post('tanggal'),
			'id_jadwal_pelajaran' 	=> $query->jadwal_pelajaran_id,
			'semester' 				=> $this->input->post('semester'),
			'keterangan'			=> NULL,
		])->result();

		if (count($query1) > 0) {
			foreach ($query1 as $key) {
				$this->db->delete('presensi', ['presensi_id' => $key->presensi_id]);
			}
		}

		$data = array(
			'tanggal'				=> $this->input->post('tanggal'),
			'id_jadwal_pelajaran' 	=> $query->jadwal_pelajaran_id,
			'semester' 				=> $this->input->post('semester'),
			'status' 				=> $this->tapel->tgl_to_int($this->input->post('tanggal')),
			'keterangan'			=> $query->jadwal_pelajaran_id 
		);

		$query2 = $this->db->get_where('presensi', $data)->result();

		if (count($query2) < 1) {
			$this->db->insert('presensi', $data);
		}

		$output = array(
			'status' 	=> TRUE,
			'message' 	=> 'Berhasil Menambah Tanggal Tidak Efektif',
		);

		echo json_encode($output);

	}

}

/* End of file Teacher.php */
/* Location: ./application/controllers/Teacher.php */
