<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Picketteacher extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();
		user_access();
	}

	private $folder 		= 'Master';
	private $title 			= 'Guru Piket';
	private $table 			= 'guru_piket';
	private $primaryKey		= 'md5(id_guru_piket)';

	public function index()
	{
		$data = [
			'folder'	=> $this->folder,
			'title' 	=> $this->title,
		];

		$this->include->content('master/index_guru_piket', $data);
	}

	public function showDataTables()
	{
		$this->load->model('Wakel_model', 'gurupiket');
		$bulider = $this->gurupiket->getGuruPiket();

		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($bulider['result'] as $field) {
			$start++;
			$row 	= array();

			$row[]	= '<div style="text-align: center;">'. $no++ .'</div>';
			$row[]	= '<div style="text-align: left;">'. $this->include->days($field->hari) .'</div>';
			$row[]	= '<div style="text-align: left;">'. $this->_listGuruPiket($field->id_guru_piket) .'</div>';
			$row[]	= '<div style="text-align: center;"><button class="btn btn-success btn-sm" onclick="add_guru_piket('. "'" . md5($field->id_guru_piket) . "'" .');"><i class="fa fa-user-plus"></i></button></div>';

			// if ($this->input->post('id_guru_piket')) {
			// 	$select	= '<div class="form-group" style="display: block;">';
			// 	$select	.= '<select class="form-control" style="width: 100%;" name="id_user_'. md5($field->id_guru_piket) .'" id="id_user_'. $field->id_guru_piket .'">';
			// 	$select	.= '<option value="">-- Guru Piket --</option>';
			// 	foreach ($this->_getGuruPiket($this->input->post('id_tahun_pelajaran'), $field->id_user) as $gp) {
			// 		$selected = $gp->user_id == $field->user_id ? 'selected' : '';
			// 		$select .= '<option value="'. $gp->user_id .'" '. $selected .'>'. $gp->no_induk .' - '. $gp->full_name .'</option>';
			// 	}
			// 	$select	.= '</select>';
			// 	$select	.= '<small class="help-block"></small>';
			// 	$select	.= '</div>';
			// 	$select .= '<script>$(function() {$("#id_user_'. $field->id_guru_piket .'").select2()});</script>';

			// 	$row[]	= $select;
			// } else {
			// 	$row[]	= $field->id_user ? $field->no_induk .' - '. $field->full_name : '-';
			// }

			// $button 	= '<div style="text-align: center;">';
			// $button		.= '<div class="btn-group">';
			// $button		.= ''. BTN_ACTION .'';
			// $button		.= '<span class="caret"></span>';
			// $button		.= '<span class="sr-only">Toggle Dropdown</span>';
			// $button		.= '</button>';
			// $button		.= '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
			// if ($this->input->post('id_guru_piket')) {
			// 	$button		.= '<li><a href="javascript:void(0)" onclick="save_data(' . "'" . md5($field->id_guru_piket) . "'" . ')">Simpan</a></li>';
			// 	$button		.= '<li class="divider"></li>';
			// 	$button		.= '<li><a href="javascript:void(0)" onclick="edit_data(' . "'" . md5($field->id_guru_piket) . "'" . ')">Batal</a></li>';
			// } else {
			// 	$button		.= '<li><a href="javascript:void(0)" onclick="edit_data(' . "'" . md5($field->id_guru_piket) . "'" . ')">Edit</a></li>';
			// }
			// $button		.= '</ul>';
			// $button		.= '</div>';
			// $button		.= '</div>';

			// $btn_save = '<span onclick="save_data(' . "'" . md5($field->id_guru_piket) . "'" . ');">'. BTN_SUBMIT .'</span>';

			// $row[]	= $button;

			$data[]	= $row;
		}

		$add_days = $this->db->get_where('guru_piket', ['md5(id_tahun_pelajaran)' => $this->input->post('id_tahun_pelajaran')])->num_rows();

		echo json_encode([
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $bulider['recordsTotal'],
			'recordsFiltered' 	=> $bulider['recordsFiltered'],
			'data' 				=> $data,
			'addDays'			=> !$this->input->post('id_tahun_pelajaran') || $add_days > 0 ? true : false,
		]);
	}

	private function _listGuruPiket($id_guru_piket)
	{
		$query = $this->db->join('user', 'user.user_id = guru_piket.id_user', 'left')->get_where('guru_piket', ['id_detail_guru_piket' => $id_guru_piket])->result();

		$no = 1;

		$table 	= '<table class="table" style="width: 100%;">';
		foreach ($query as $row) {
			$border = $no == 1 ? 'border-top: none; padding-top: 0px;' : '';
			$table 	.= '<tr>';
			$table 	.= '<td style="'. $border .'">'. $row->full_name .'</td>';
			$table 	.= '<td style="width: 5%; '. $border .'"><button class="btn btn-danger btn-sm" onclick="delete_data(' . "'" . md5($row->id_guru_piket) . "'" . ')"><i class="fa fa-user-times"></i></button></td>';
			$table 	.= '</tr>';

			$no++;
		}
		$table 	.= '</table>';

		return count($query) > 0 ? $table : '-';
	}

	// private function _getGuruPiket($id_tahun_pelajaran, $id_user = null)
	// {
	// 	$id_user_gp = array();
	// 	foreach ($this->db->get_where('guru_piket', ['md5(id_tahun_pelajaran)' => $id_tahun_pelajaran])->result() as $gp) {
	// 		if ($gp->id_user) {
	// 			$id_user_gp[] = $gp->id_user;
	// 		}
	// 	}
	// 	if (count($id_user_gp) > 0) {
	// 		$this->db->where_not_in('user_id', $id_user_gp);
	// 	}
	// 	$this->db->where('user_type_id', 2);
	// 	if ($id_user) {
	// 		$this->db->or_where('user_id', $id_user);
	// 	}
	// 	return $this->db->get('user')->result();
	// }
	
	public function getGuruPiket($id_guru_piket = null)
	{
		$query = $this->db->get_where('guru_piket', ['md5(id_guru_piket)' => $id_guru_piket])->row();

		if (empty($query->id_guru_piket)) {
			show_404();
		}

		$guru_piket = $this->db->where('id_detail_guru_piket', $query->id_guru_piket)->get('guru_piket')->result();

		$id_user = array();
		foreach ($guru_piket as $row) {
			if ($row->id_user) {
				$id_user[] = $row->id_user;
			}
		}

		if (count($id_user) > 0) {
			$this->db->where_not_in('user_id', $id_user);
		}

		$this->db->where('user_type_id', 2);
		$user = $this->db->get('user')->result();

		$data = array();
		foreach ($user as $row) {
			$data[] = array(
				'id_guru_piket' => $row->user_id, 
				'guru_piket' => $row->full_name, 
			);
		}

		echo json_encode($data);
	}

	public function add()
	{
		$query = $this->db->get_where('tahun_pelajaran', ['md5(tahun_pelajaran_id)' => $this->input->post('id_tahun_pelajaran')])->row();
		if (isset($query->tahun_pelajaran_id)) {
			for ($i=1; $i <= 6; $i++) { 
				$data = array(
					'id_tahun_pelajaran' => $query->tahun_pelajaran_id,
					'hari' => $i 
				);
				$this->db->insert($this->table, $data);
			}
			echo json_encode([
				'status' 	=> TRUE,
				'message'	=> 'Berhasil Menambah Guru Piket',
			]);
		} else {
			if ($this->input->is_ajax_request()) {
				echo json_encode(['status' => FALSE]);
			} else {
				redirect('master/picketteacher');
			}
		}
	}

	public function update($id_guru_piket = null)
	{
		$query = $this->db->get_where('guru_piket', ['md5(id_guru_piket)' => $id_guru_piket])->row();

		if (isset($query->id_guru_piket) && $this->input->post('id_user')) {
			$this->db->update('guru_piket', ['id_user' => $this->input->post('id_user')], ['id_guru_piket' => $query->id_guru_piket]);
			echo json_encode([
				'status' 	=> TRUE,
				'message'	=> 'Berhasil Mengubah Guru Piket',
			]);
		} else {
			if ($this->input->is_ajax_request()) {
				echo json_encode(['status' => FALSE]);
			} else {
				redirect('master/picketteacher');
			}
		}
	}

	public function delete($id = null)
	{
		$query = $this->db->get_where($this->table, [$this->primaryKey => $id])->row();

		if (!$query) {
			show_404();
		}

		$this->db->delete($this->table, [$this->primaryKey => $id]);

		$output = array(
			'status' 	=> TRUE,
			'message' 	=> 'Berhasil Menghapus ' . $this->title,
		);

		echo json_encode($output);
	}

	public function addGuruPiket()
	{
		$query = $this->db->get_where('guru_piket', ['md5(id_guru_piket)' => $this->input->post('id_guru_piket')])->row();

		if (empty($query->id_guru_piket)) {
			show_404();
		}

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_user', 'Guru Piket', 'trim|required');
		$this->form_validation->set_message('required', '{field} harus diisi');

		if ($this->form_validation->run() == FALSE) {
			echo json_encode([
				'status' => FALSE,
				'errors' => array('id_user' => form_error('id_user'))
			]);
		} else {
			$this->db->insert('guru_piket', [
				'id_detail_guru_piket' => $query->id_guru_piket,
				'id_user' => htmlspecialchars($this->input->post('id_user'))
			]);

			echo json_encode([
				'status' 	=> TRUE,
				'message'	=> 'Berhasil Menambah Guru Piket',
			]);
		}


	}

}

/* End of file Picketteacher.php */
/* Location: ./application/controllers/master/Picketteacher.php */
