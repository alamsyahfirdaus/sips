<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recover extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();
		user_access();

	}

	public function index()
	{
		$data = [
			'folder'	=> 'Pengaturan',
			'title' 	=> 'Pemulihan',
		];

		$this->include->content('setting/index_pemulihan', $data);
	}

	public function show_recover()
	{
		$this->load->model('Pengaturan_model', 'setting');

		$query  = $this->setting->getRecover();
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query['builder'] as $field) {
			$start++;
			$row 	= array();
			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]	= $field->title;
			$row[]	= $this->_get_bidang($field);
			$data[]	= $row;
		}

		$output = array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $query['total'],
			'recordsFiltered' 	=> count($data),
			'data' 				=> $data,
		);

		echo json_encode($output);

	}

	private function _get_bidang($field)
	{
		$table 			= $field->table;
		$primary_key 	= $this->db->list_fields($table)[0];
		$name 			= $this->db->list_fields($table)[1];

		if ($field->id != 8) {
			$bulider = $this->db->where('delete_at !=', NULL)->get($table)->result_array();
		} else {
			// Presensi Siswa
			$type 	 = $this->db->list_fields($table)[2];
			$bulider = $this->db->where('delete_at !=', NULL)->group_by($type)->group_by($name)->get($table)->result_array();
		}

		if (count($bulider) > 0) {
			$number  = 1;
			$alpa 	 = 'A';
			$result  = '<table class="table">';
			foreach ($bulider as $row) {

				$bt = $number == 1 ? 'border-top: 0px;' : '';

				$button 	= '<div style="text-align: center;">';
				$button		.= '<div class="btn-group">';
				$button 	.= '<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" style="background-color: #00A65A; color: #FFFFFF; font-weight: bold;"><i class="fa fa-cogs"></i>';
				$button		.= '<span class="sr-only">Toggle Dropdown</span>';
				$button		.= '</button>';
				$button		.= '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
				$button		.= '<li><a href="javascript:void(0)" onclick="kembalikan_data('. $row[$primary_key] .')">Kembalikan</a></li>';
				$button		.= '<li class="divider"></li>';
				$button		.= '<li><a href="javascript:void(0)" onclick="hapus_permanen('. $row[$primary_key] .')">Hapus Permanen</a></li>';
				$button		.= '</ul>';
				$button		.= '</div>';
				$button		.= '</div>';

				$button		.= '<input type="hidden" id="tb_'. $row[$primary_key] .'" value="'. $table .'">';
				$button		.= '<input type="hidden" id="pk_'. $row[$primary_key] .'" value="'. $primary_key .'">';
				$button		.= '<input type="hidden" id="name_'. $row[$primary_key] .'" value="'. $field->title .'">';
				$button		.= '<input type="hidden" id="type_'. $row[$primary_key] .'" value="'. $field->id .'">';

				$query = $this->db->get_where($table, [$primary_key => $row[$primary_key]])->row();

				$result .= '<tr>';
				if ($field->id == 2) {
					$bidang = $query->kode_mapel .' - '. $query->nama_mapel;
				} elseif ($field->id == 6) {
					$no_induk 	= isset($query->no_induk) ? $query->no_induk : '#';
					$bidang 	= '<a href="'. site_url('user/detail/' . md5($query->user_id)) .'" style="color: #00A65A;">'. $no_induk .' - '. $query->full_name .'</a></li>';
				} elseif ($field->id == 7) {
					$bidang 	= '<a href="javascript:void(0)" onclick="jadwal_pelajaran(' . "'" . md5($row[$primary_key]) . "'" . ')" style="color: #00A65A;">'. $field->title .' - '. $alpa .'</a></li>';
				} elseif ($field->id == 8) {
					$bidang 	= '<a href="javascript:void(0)" onclick="presensi_siswa(' . "'" . md5($row[$primary_key]) . "'" . ')" style="color: #00A65A;">'. $field->title .' - '. $alpa .'</a></li>';
				} else {
					$bidang = $row[$name];
				}
				$result .= '<td style="padding-left: 0px; '. $bt .'">'. $bidang .'</td>';
				$result .= '<td style="width: 25%; text-align: center; '. $bt .'">'. $button .'</td>';
				$result .= '</tr>';

				$number++;
				$alpa++;
			}

			$result .= '</table>';

			return $result;

		} else {
			return '-';
		}

	}

	public function update_delete()
	{
		$this->form_validation->set_rules('tb', 'Table', 'trim|required');
		$this->form_validation->set_rules('pk', 'Primary Key', 'trim|required');
		$this->form_validation->set_rules('id', 'Id', 'trim|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('type', 'Type', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			show_404();
		} else {
			$table 			= $this->input->post('tb');
			$primary_key 	= $this->input->post('pk');
			$id 			= $this->input->post('id');
			$name 			= $this->input->post('name');
			$type 			= $this->input->post('type');
			$query 			= $this->db->get_where($table, [$primary_key => $id])->row();

			if ($query) {
				if ($type == 8) {

					$presensi_siswa = $this->db->get_where('presensi', [
						'id_jadwal_pelajaran'  	=> $query->id_jadwal_pelajaran,
						'tanggal'			 	=> $query->tanggal
					])->result_array();

					if (count($presensi_siswa) > 0) {
						foreach ($presensi_siswa as $key) {
							if ($this->input->post('update_at')) {
								$action = $this->db->update($table, ['delete_at' => NULL], [$primary_key => $key[$primary_key]]);
							} elseif ($this->input->post('delete_at')) {
								$action = $this->db->delete($table, [$primary_key => $key[$primary_key]]);
							}
						}
					} else {
						$action = FALSE;
					}

				} elseif ($this->input->post('update_at')) {
					$action = $this->db->update($table, ['delete_at' => NULL], [$primary_key => $id]);
				} elseif ($this->input->post('delete_at')) {
					$action = $this->db->delete($table, [$primary_key => $id]);
				} else {
					$action = FALSE;
				}

				if ($this->input->post('update_at')) {
					$message = 'Berhasil Mengembalikan ' . $name; 
				} elseif ($this->input->post('delete_at')) {
					$message = 'Berhasil Menghapus Permanen ' . $name; 
				} else {
					$message = NULL;
				}

				$output = array(
					'status' 	=> $action,
					'message'	=> $message
				);

				echo json_encode($output);
			}
		}
	}

	public function jadwal_pelajaran($id_jadwal_pelajaran)
	{
		$query = $this->db
		->join('kelas', 'kelas.kelas_id = jadwal_pelajaran.id_kelas', 'left')
		->join('mata_pelajaran', 'mata_pelajaran.mapel_id = jadwal_pelajaran.id_mata_pelajaran', 'left')
		->join('user', 'user.user_id = jadwal_pelajaran.id_user', 'left')
		->get_where('jadwal_pelajaran', ['md5(jadwal_pelajaran_id)' => $id_jadwal_pelajaran])->row();

		if (!$query) {
			show_404();
		}

		$content = '<ul class="list-group list-group-unbordered">';
		$content .= '<li class="list-group-item">';
        $content .= '<b>Mata Pelajaran</b><a class="pull-right">'. $query->kode_mapel .' - '. $query->nama_mapel .'</a>';
        $content .= '</li>';
        $content .= '<li class="list-group-item">';
        $content .= '<b>Kelas</b><a class="pull-right">'. $query->nama_kelas .'</a>';
        $content .= '</li>';
        $content .= '<li class="list-group-item">';
        $content .= '<b>Guru</b><a class="pull-right">'. $query->full_name .'</a>';
        $content .= '</li>';
        $content .= '<li class="list-group-item">';
        $content .= '<b>Hari</b><a class="pull-right">'. $this->include->days($query->hari) .'</a>';
        $content .= '</li>';

        $jam_mulai 		= $this->db->get_where('jam_pelajaran', ['jam_pelajaran_id' => $query->mulai])->row();
        $jam_selesai 	= $this->db->get_where('jam_pelajaran', ['jam_pelajaran_id' => $query->selesai])->row();
        $mulai 			= isset($jam_mulai->jam_pelajaran_id) ? $jam_mulai->jam_pelajaran : '#';
        $selesai 		= isset($jam_selesai->jam_pelajaran_id) ? $jam_selesai->jam_pelajaran : '#';
        $jam_pelajaran  = $mulai . ' - ' . $selesai;

        $content .= '<li class="list-group-item">';
        $content .= '<b>Jam Pelajaran</b><a class="pull-right">'. $jam_pelajaran .'</a>';
        $content .= '</li>';
        $content .= '</ul>';

		echo json_encode($content);
	}

	public function presensi_siswa($id_presensi_siswa)
	{
		$query = $this->db
		->join('jadwal_pelajaran', 'jadwal_pelajaran.jadwal_pelajaran_id = presensi.id_jadwal_pelajaran', 'left')
		->join('kelas', 'kelas.kelas_id = jadwal_pelajaran.id_kelas', 'left')
		->join('mata_pelajaran', 'mata_pelajaran.mapel_id = jadwal_pelajaran.id_mata_pelajaran', 'left')
		->join('user', 'user.user_id = jadwal_pelajaran.id_user', 'left')
		->get_where('presensi', ['md5(presensi_id)' => $id_presensi_siswa])->row();


		if (!$query) {
			show_404();
		}

		$content = '<ul class="list-group list-group-unbordered">';
		$content .= '<li class="list-group-item">';
        $content .= '<b>Mata Pelajaran</b><a class="pull-right">'. $query->kode_mapel .' - '. $query->nama_mapel .'</a>';
        $content .= '</li>';
        $content .= '<li class="list-group-item">';
        $content .= '<b>Kelas</b><a class="pull-right">'. $query->nama_kelas .'</a>';
        $content .= '</li>';
        $content .= '<li class="list-group-item">';
        $content .= '<b>Guru</b><a class="pull-right">'. $query->full_name .'</a>';
        $content .= '</li>';
        $content .= '<li class="list-group-item">';
        $content .= '<b>Tanggal</b><a class="pull-right">'. $this->include->date($query->tanggal) .'</a>';
        $content .= '</li>';
        $content .= '</ul>';

		echo json_encode($content);
	}

}

/* End of file Recover.php */
/* Location: ./application/controllers/setting/Recover.php */
