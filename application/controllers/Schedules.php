<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedules extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		# Jadwal Pelajaran
		logged_in();

		if ($this->session->user_type_id == 3) {
			redirect(site_url());
		}

		$this->load->model('Jadwal_model', 'jadwal');
		$this->load->model('Tapel_model', 'tapel');

	}

	private $folder 		= 'Master';
	private $title 			= 'Jadwal Pelajaran';
	private $table 			= 'jadwal_pelajaran';
	private $primaryKey		= 'md5(jadwal_pelajaran_id)';

	public function index()
	{
		$tapel = $this->db->get_where('tahun_pelajaran', ['is_aktif' => 'Y'])->row();
		$days = array(
			'1' => 'Senin',
			'2'	=> 'Selasa',
			'3'	=> 'Rabu',
			'4'	=> 'Kamis',
			'5'	=> 'Jumat',
			'6'	=> 'Sabtu',
		);
		
		if ($this->session->user_type_id == 1) {
			$data = [
				'title' 				=> $this->title,
				'tahun_pelajaran_id'	=> @$tapel->tahun_pelajaran_id,
				'tahun_pelajaran'		=> $this->mall->get_tapel(),
				'kelas'					=> $this->mall->get_kelas(),
				'hari'					=> $days,
			];

			$this->include->content('master/index_jadwal', $data);
		} else {

			$data = [
				'title' 	=> 'Jadwal Mengajar',
				'tapel'		=> @$tapel->tahun_pelajaran,
				'jadwal'	=> $this->_jadwalMengajar($tapel),
			];

			$this->include->content('guru/jadwal_guru', $data);
		}
	}

	# JADWAL PELAJARAN (ADMINISTRATOR)

	public function showDataTables()
	{
		$data = $this->jadwal->getDataTables();
		echo json_encode($data);
	}

	private function _getData($id)
	{
		return $this->jadwal->getRow($id);
	}

	private function _updateData($data, $id)
	{
		return $this->db->update($this->table, $data, [$this->primaryKey => $id]);
	}

	public function getKelas($id_tahun_pelajaran = NULL)
	{
		$mapel 	= $this->db->count_all_results('mata_pelajaran');

		foreach ($this->db->get('kelas')->result() as $row) {
			$kelas = $this->db->where([
				'id_tahun_pelajaran' 	=> $id_tahun_pelajaran,
				'id_kelas'				=> $row->kelas_id,
			])->get($this->table)->num_rows();

			if ($mapel == $kelas) {
				$id_kelas[] = $row->kelas_id;
			}
		}

		$query = $this->db->where_not_in('kelas_id', @$id_kelas)->where('delete_at', NULL)->order_by('id_tingkat_kelas', 'asc')->order_by('urutan_kelas', 'asc')->get('kelas')->result();
		echo json_encode($query);

	}

	public function saveData()
	{
		if (!$this->input->post('id_tahun_pelajaran')) {
			show_404();
		}

		$query 	= $this->db->get_where($this->table, [
			'id_tahun_pelajaran' 	=> $this->input->post('id_tahun_pelajaran'),
			'id_kelas'				=> $this->input->post('id_kelas')
		]);


		$mapel 	= $this->db->count_all_results('mata_pelajaran');

		foreach ($query->result() as $row) {
			$mapel_id[] = $row->id_mata_pelajaran;
		}

		if ($query->num_rows() != $mapel) {
			foreach ($this->db->where_not_in('mapel_id', @$mapel_id)->get_where('mata_pelajaran', ['delete_at' => NULL])->result() as $row) {
				$data[] = array(
					'id_tahun_pelajaran' 	=> $this->input->post('id_tahun_pelajaran'),
					'id_kelas'				=> $this->input->post('id_kelas'),
					'id_mata_pelajaran'		=> @$row->mapel_id,
				);
			}

			if (isset($data)) {
				$this->db->insert_batch($this->table, $data);

				$output = array(
					'status' 	=> TRUE,
					'message'	=> 'Berhasil Menampilkan Mata Pelajaran',
				);

				echo json_encode($output);
			} else {
				echo json_encode(['status' => TRUE]);
			}

		} else {
			echo json_encode(['status' => TRUE]);
		}

	}

	public function changeGuru()
	{
		$id 	= $this->input->post('jadwal_pelajaran_id');
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		$validate = $this->_checkJadwal($query, $this->input->post('id_user'), $query->hari, $query->mulai, $query->selesai);

		if ($validate) {
			$output['alert'] 	= 'error';
			$output['message'] = $validate;
		} else {
			$data = array('id_user' => $this->input->post('id_user') ? $this->input->post('id_user') : NULL);
			$this->_updateData($data, $id);
		}


		$output['status'] = TRUE;

		echo json_encode($output);
	}

	public function changeHari()
	{
		$id 	= $this->input->post('jadwal_pelajaran_id');
		$query 	= $this->_getData($id);

		if (!$query) {
			show_404();
		}

		$validate = $this->_checkJadwal($query, $query->id_user, $this->input->post('hari'), $query->mulai, $query->selesai);

		if ($validate) {
			$output['alert'] 	= 'error';
			$output['message'] = $validate;
		} else {
			$data['hari'] = $this->input->post('hari') ? $this->input->post('hari') : NULL;
			if ($this->input->post('hari') == NULL) {
				$data['mulai'] 		= NULL;
				$data['selesai'] 	= NULL;
			}
			$this->_updateData($data, $id);
		}

		$output['status'] = TRUE;

		echo json_encode($output);
	}

	public function changeMulai()
	{
		$id 	= $this->input->post('jadwal_pelajaran_id');
		$query 	= $this->_getData($id);

		if (!$query) {
			show_404();
		}

		$validate  = $this->_checkJadwal($query, $query->id_user, $query->hari, $this->input->post('mulai'), $query->selesai);

		if ($validate) {
			$output['alert']  	= 'error';
			$output['message']  = $validate;
		} else {
			$data['mulai'] = $this->input->post('mulai') ? $this->input->post('mulai') : NULL;

			if ($this->input->post('mulai')) {
				$data['sort'] = $this->jadwal->substrJam($this->input->post('mulai'));
			} else {
				$data['selesai'] = NULL;
			}
			$this->_updateData($data, $id);
		}


		$output['status'] = TRUE;

		echo json_encode($output);
	}

	public function changeSelesai()
	{
		$id 	= $this->input->post('jadwal_pelajaran_id');
		$query 	= $this->_getData($id);

		if (!$query) {
			show_404();
		}

		$validate = $this->_checkJadwal($query, $query->id_user, $query->hari, $query->mulai, $this->input->post('selesai'));

		if ($validate) {
			$output['alert'] 	= 'error';
			$output['message'] 	= $validate;
		} else {
			if ($this->_checkMulai($query)) {
				$data['mulai'] 		= NULL;
				$data['selesai']	= NULL;
				$output['alert'] 	= 'error';
				$output['message'] 	= $this->title . ' Bentrok';
			} else {
				$data = array('selesai' => $this->input->post('selesai') ? $this->input->post('selesai') : NULL);
				if ($this->input->post('selesai')) {
					$output['alert'] 	= 'success';
					$output['message'] 	= 'Berhasil Mengatur Jadwal Pelajaran';
				}
			}

			$this->_updateData($data, $id);
		}

		$output['status'] = TRUE;

		echo json_encode($output);
	}

	private function _checkMulai($field)
	{
		$query = $this->jadwal->getJamKelas($field->id_tahun_pelajaran, $field->id_kelas);

		if ($query > 0) {
			foreach ($query as $row) {
				$jam1 	= $this->jadwal->substrJam($row->mulai);
				$jam2 	= $this->jadwal->substrJam($row->selesai);
				$jam3 	= $this->jadwal->substrJam($field->mulai);

				if ($jam1 < $jam3 && $jam2 > $jam3) {
					$data[] = $field->mulai;
				}

			}

			return @$data ? $data : FALSE;
		} else {
			return FALSE;
		}
	}

	private function _checkJadwal($field, $guru, $hari, $mulai, $selesai)
	{
		$query = $this->db->get_where($this->table, [
			'id_tahun_pelajaran'	=> $field->id_tahun_pelajaran,
			'id_user'				=> $guru,
			'hari'					=> $hari,
			'mulai'					=> $mulai,
			'selesai'				=> $selesai
		])->num_rows();

		$jam_mulai 		= $this->jadwal->substrJam($mulai);
		$jam_selesai 	= $this->jadwal->substrJam($selesai);
		$validate1 		= $mulai != NULL && $selesai != NULL && $mulai == $selesai ? TRUE : FALSE;
		$validate2 		= $mulai != NULL && $selesai != NULL && $jam_selesai < $jam_mulai ? TRUE : FALSE;
		$validate3 		= $query && $mulai != NULL && $guru != NULL ? TRUE : FALSE; 

		if ($validate1 || $validate2) {
			return 'Jam Pelajaran Tidak Valid';
		} else {
			return $validate3 ? $this->title . ' Bentrok' : FALSE;
		}
	}

	public function deleteData($id = NULL)
	{
		# Hapus Jadwal Pelajaran

		$query = $this->_getData($id);


		if (!$query && !$this->input->post('id')) {
			show_404();
		} else {

			if ($query) {

				// $this->db->delete($this->table, [$this->primaryKey => $id]);
				$this->db->update($this->table, ['delete_at' => date('Y-m-d H:i:s')], [$this->primaryKey => $id]);

				$sub_jadwal = $this->db->get_where($this->table, ['sub_id' => $query->jadwal_pelajaran_id])->row();
				if ($sub_jadwal) {
					$this->db->update($this->table, ['delete_at' => date('Y-m-d H:i:s')], ['jadwal_pelajaran_id' => $sub_jadwal->jadwal_pelajaran_id]);
				}

			} else {
				foreach ($this->input->post('id') as $row) {
					// $this->db->delete($this->table, ['jadwal_pelajaran_id' => $row]);
					$this->db->update($this->table, ['delete_at' => date('Y-m-d H:i:s')], ['jadwal_pelajaran_id' => $row]);
				}
			}

			$output = [
				'status' => TRUE,
				'message'=> 'Berhasil Menghapus ' . $this->title,
			];

			echo json_encode($output);
		}
	}

	public function resetData($id = NULL)
	{
		# Reset Jadwal Pelajaran

		$query 	= $this->_getData($id);

		if (!$query) {
			show_404();
		}

		$data = array(
			'id_user' 	=> NULL,
			'hari'		=> NULL,
			'mulai'		=> NULL,
			'selesai'	=> NULL 
		);

		$this->_updateData($data, $id);

		echo json_encode(['status' => TRUE]);
	}

	public function add_sub_jadwal($jadwal_pelajaran_id)
	{
		// Revisi 13/10/2021 (Pak MT)
		
		$query 	= $this->_getData($jadwal_pelajaran_id);

		if ($query) {
			$data = array(
				'id_tahun_pelajaran' 	=> $query->id_tahun_pelajaran,
				'id_kelas' 				=> $query->id_kelas,
				'id_user' 				=> $query->id_user,
				'id_mata_pelajaran' 	=> $query->id_mata_pelajaran,
				'sub_id' 				=> $query->jadwal_pelajaran_id
			);

			$action = $this->db->insert('jadwal_pelajaran', $data);

			$output = array(
				'status' 	=> $action,
				'message' 	=> 'Berhasil Menambah Jadwal Pelajaran',
			);


			echo json_encode($output);
		} else {
			echo json_encode(['status' => FALSE]);
		}


	}

	# JADWAL MENGAJAR (GURU)
	
	private function _jadwalMengajar($tapel)
	{
		$this->load->model('Jadwal_model', 'jadwal');

		$html = '<div class="box-header with-border">';
		$html .= '<h3 class="box-title">Daftar Jadwal Mengajar</h3>';
		$html .= '</div>';
		$html .= '<input type="hidden" name="title" value="Input Kehadiran">';
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
			$html .= '<th width="40%">Kode - Mata Pelajaran</th>';
			$html .= '<th>Kelas</th>';
			$html .= '<th width="20%" class="text-center">Jam Pelajaran</th>';
			$html .= '<th width="15%" class="text-center">Presensi Siswa</th>';
			// $html .= '<th width="10%" class="text-center">Presensi</th>';
			// $html .= '<th width="5%" class="text-center">Input</th>';
			$html .= '</tr>';
			$query = $this->jadwal->getData(NULL, @$tapel->tahun_pelajaran_id, NULL, $this->session->user_id, $i);
			$activation = @$query && @$tapel->tahun_pelajaran_id && @$tapel->semester ? TRUE : FALSE;
			if ($activation) {
				$no = 1;
				foreach ($query as $row) {
					$kode 		= $row->kode_mapel ? $row->kode_mapel : '#';
					$mulai 		= $this->_getJampel($row->mulai);
					$selesai	= $this->_getJampel($row->selesai);
					$html .= '<input type="hidden" name="ijp_'. $row->jadwal_pelajaran_id .'" value="'. $row->jadwal_pelajaran_id . '">';
					$html .= '<tr>';
					$html .= '<td class="text-center" width="5%">'. $no++ .'</td>';
					$html .= '<td width="40%">'. $kode . ' - ' . $row->nama_mapel .'</td>';
					$html .= '<td>'. $row->nama_kelas .'</td>';
					$html .= '<td width="20%" class="text-center">'. $mulai . ' - ' . $selesai .'</td>';
					$html .= '<td width="10%" class="text-center">';
					$html .= '<a href="'. site_url('teacher/detail/' . md5($row->jadwal_pelajaran_id)) .'" class="btn btn-sm" style="'. $button .'"><i class="fa fa-folder-open"></i></a>';
					$html .= '</td>';
					// $html .= '<td width="10%" class="text-center">';
					// $html .= '<a href="'. site_url('teacher/subject/' . md5($row->jadwal_pelajaran_id)) .'" class="btn btn-sm" style="'. $button .'"><i class="fa fa-users"></i></a>';
					// $html .= '</td>';
					// $html .= '<td width="5%" class="text-center">';
					// $html .= '<a href="'. site_url('teacher/add/' . md5($row->jadwal_pelajaran_id)) .'" class="btn btn-sm" style="'. $button .'"><i class="fa fa-calendar-plus-o"></i></a>';
					// $html .= '</td>';
					$html .= '</tr>';
				}
			} else {
				$html .= '<tr>';
				$html .= '<td colspan="5"></td>';
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

	# Laporan Kehadiran Siswa (Administrator)
	
	// public function detail($id = NULL)
	// {
	// 	$query = $this->_getData($id);

	// 	if (!$query) {
	// 		show_404();
	// 	}

	// 	$data = [
	// 		'title' 	=> 'Jadwal Pelajaran',
	// 		'header'	=> 'Presensi',
	// 		'content'	=> $this->_detailJadwal($query),
	// 		'month'		=> $this->include->moths(),
	// 	];

	// 	$this->include->content('master/index_kehadiran_siswa', $data);
	// }

	private function _detailJadwal($field)
	{
		$tapel = $this->db->get_where('tahun_pelajaran', ['is_aktif' => 'Y'])->row();

		$query1 = $this->db->join('mata_pelajaran mp', 'mp.mapel_id = jp.id_mata_pelajaran', 'left')->get_where('jadwal_pelajaran jp', [
			'id_tahun_pelajaran' 	=> $field->id_tahun_pelajaran,
			'id_kelas' 				=>  $field->id_kelas
		])->result();

		$mapel = '<div class="form-group">';
		// $mapel .= '<label for="id_jadwal_pelajaran">Mata Pelajaran</label>';
		// $mapel .= '<select name="id_jadwal_pelajaran" id="id_jadwal_pelajaran" class="form-control select2">';
		// $mapel .= '<option value="">-- Mata Pelajaran --</option>';
		// foreach ($query1 as $row) {
		// 	$selected = $row->jadwal_pelajaran_id == $field->jadwal_pelajaran_id ? 'selected' : '';
		// 	$mapel .= '<option value="'. md5($row->jadwal_pelajaran_id) .'" '. $selected .'>'. $row->nama_mapel .'</option>';
		// }
		// $mapel .= '</select>';
		// $mapel .= '<small class="help-block"></small>';
		$mapel .= '</div>';

		$callout = '<input type="hidden" name="id_jadwal_pelajaran" value="'. md5(@$field->jadwal_pelajaran_id) .'">';
		// $callout .= '<input type="text" name="id_kelas" value="'. md5($field->id_kelas) .'">';
		$callout .= '<input type="hidden" name="id_kelas" value="">';
		$callout .= '<input type="hidden" name="kelas_id" value="">';
		$callout .= '<input type="hidden" name="id_tahun_pelajaran" value="'. md5($field->id_tahun_pelajaran) .'">';
		$callout .= '<input type="hidden" name="semester" value="'. @$tapel->semester .'">';
		
        $callout .= '<b>Tahun Pelajaran : '. $field->tahun_pelajaran .'</b>';
        $callout .= '<b id="mapel">Mata Pelajaran : '. $field->nama_mapel .'</b>';
        $callout .= '<b>Kelas : '. $field->nama_kelas .'</b>';
        $callout .= '<style type="text/css">.callout{border-left: 3px solid #00A65A; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE; border-bottom: 1px solid #EEEEEE;} .callout b{display: block;}</style>';
        foreach ($query1 as $row) {
        	$callout .= '<input type="hidden" name="mapel_'. md5($row->jadwal_pelajaran_id) .'" value="Mata Pelajaran : '. $row->nama_mapel .'">';
        }

        $query2 = $this->db->join('siswa s', 's.id_user = u.user_id', 'left')->get_where('user u', [
        	'user_type_id' 	=> 3,
        	'id_kelas'		=> $field->id_kelas,
        	'delete_at'		=> NULL
        ])->result();

        $siswa = '<div class="form-group">';
        // $siswa .= '<label for="user_id">Siswa</label>';
        $siswa .= '<select name="user_id" id="user_id" class="form-control select2">';
        $siswa .= '<option value="">-- Pilih Siswa --</option>';
	        foreach ($query2 as $row) {
	        	$siswa .= '<option value="'. md5($row->user_id) .'">'. $row->no_induk .' - '. $row->full_name .'</option>';
	        }
        $siswa .= '</select>';
        $siswa .= '<small class="help-block"></small>';
        $siswa .= '</div>';

        $arrSem = array(
        	'1' => '1 (Ganjil)',
        	'2'	=> '2 (Genap)',
        );

		$semester = '<div class="form-group">';
		$semester .= '<label for="id_semester">Semester</label>';
        $semester .= '<select name="id_semester" id="id_semester" class="form-control select2">';
        $semester .= '<option value="">-- Semester --</option>';
	        foreach ($arrSem as $key => $value) {
	        	$selected = $key == @$tapel->semester ? 'selected' : '';
	        	$semester .= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
	        }
        $semester .= '</select>';
        $semester .= '<small class="help-block"></small>';
        $semester .= '</div>';

		return array(
			'mapel' 	=> $mapel,
			'callout'	=> $callout,
			// 'siswa'		=> $siswa,
			'select'	=> $siswa,
			'semester'	=> $semester,
			// 'opt_siswa'	=> $query2,
			'siswa'		=> $query2,
			'id_kelas'	=> md5(@$field->id_kelas),
			'id_tahun_pelajaran' => md5(@$field->tahun_pelajaran_id),
			'hari'		=> @$field->hari
		);
	}

	public function showAttendance()
	{
		$this->load->model('Siswa_model', 'siswa');
		$data = $this->siswa->getMapel();
		echo json_encode($data);
	}

	public function showPresensi()
	{
		# Menampilkan Kehadiran Berdasarkan Siswa
		# Jadwal Pelajaran -> Detail
		
		$this->load->model('Siswa_model', 'siswa');
		$data = $this->siswa->getPresensi();
		echo json_encode($data);
	}

	public function changeStatus($id = NULL)
	{
		# Edit Status
		# Jadwal Mengajar -> Kehadiran -> Edit
		
		$query = $this->db->get_where('presensi', ['presensi_id' => $this->input->post('id_presensi')])->row();

		if (!$query) {
			show_404();
		}

		$this->db->update('presensi', ['status' => $this->input->post('status')], ['presensi_id' => $this->input->post('id_presensi')]);
		echo json_encode(['status' => TRUE]);
	}

	public function presence($id = NULL)
	{
		# Jadwal Pelajaran > Presensi
		# Administrator
		
		$query 	= $this->_getData($id);

		if (!$query) {
			show_404();
		}

		// $data = array(
		// 	'folder' 	=> 'Jadwal Pelajaran', 
		// 	'title' 	=> 'Presensi',
		// 	'id'		=> $id,
		// 	'content'	=> $this->_detailJadwal($query),
		// );

		// $this->include->content('master/index_presensi_siswa', $data);
		
		$data = array(
			'folder' 	=> 'Jadwal Pelajaran',
			'title'	 	=> 'Presensi Siswa',
			'content'	=> $this->_detailJadwal($query),
			'tanggal' 	=> $this->tapel->get_date($query->hari)['date'],
		);
		
		$this->include->content('guru/detail_jadwal_mengajar_r2', $data);

	}
	
}

/* End of file Schedules.php */
/* Location: ./application/controllers/Schedules.php */
