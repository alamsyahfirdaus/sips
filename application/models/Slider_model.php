<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider_model extends CI_Model {

	private $table 			= 'image_slider';
	private $primaryKey		= 'md5(id_slider)';
	private $columnOrder	= ['id_slider', NULL];
	private $columnSearch	= ['id_slider'];
	private $orderBy		= ['id_slider' => 'DESC'];

	private function _setLimit()
	{
		$limit = $this->input->post('length') + 1 + $this->input->post('start');
		$this->db->limit($limit);
	}

	private function _setBuilder()
	{
		$this->_setLimit();
		$this->db->order_by('sort', 'desc');
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnSearch, $this->columnSearch, $this->orderBy);
	}

	public function getDataTables()
	{
		$query 	= $this->include->getResult($this->_setBuilder());
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query as $field) {
			$start++;
			$row 	= array();
			$row[]  = $no++;
			$row[]	= '<p class="text-center"><img class="profile-user-img img-responsive" src="'. site_url(IMAGE . $this->include->image($field->gambar)) .'" alt="User profile picture"></p';
			$row[]	= $field->id_slider == 1 ? '<input type="text" disabled="" value="Logo Sekolah" class="form-control">' : $this->_getStatus($field);
			$row[]	= $this->_getButton($field);
			$data[]	= $row;
		}

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder())->num_rows(),
			'data' 				=> $data,
		];

		return $setData;
	}

	private function _getButton($field)
	{

		$button 	= '<div style="text-align: center;">';
		$button		.= '<div class="btn-group">';
		$button		.= ''. BTN_ACTION .'';
		$button		.= '<span class="caret"></span>';
		$button		.= '<span class="sr-only">Toggle Dropdown</span>';
		$button		.= '</button>';
		$button		.= '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
		$button		.= '<li><a href="javascript:void(0)" onclick="edit_data(' . "'" . $field->id_slider . "'" . ')">Edit</a></li>';

		$is_aktif 	= $this->db->get_where($this->table, ['is_aktif' => 'Y'])->num_rows();
		$num_rows 	= $is_aktif == 1 && $field->is_aktif == 'Y' ? TRUE : FALSE;

		if ($field->id_slider != 1 && !$num_rows) {
			# 1 = LOGO SEKOLAH
			$button		.= '<li class="divider"></li>';
			$button		.= '<li><a href="javascript:void(0)" onclick="delete_data(' . "'" . md5($field->id_slider) . "'" . ')">Hapus</a></li>';
		}

		$button		.= '</ul>';
		$button		.= '</div>';
		$button		.= '</div>';
		$button		.= '<input type="hidden" name="image_'. $field->id_slider .'" value="'. site_url(IMAGE . $this->include->image($field->gambar)) .'">';

		return $button;
	}

	private function _getStatus($field)
	{
		$status = array(
			'Y' => 'Aktif',
			'N'	=> 'Tidak Aktif',
		);

		$is_aktif 	= $this->db->get_where($this->table, ['is_aktif' => 'Y'])->num_rows();
		$disabled 	= $is_aktif == 1 && $field->is_aktif == 'Y' ? 'disabled' : '';

	    $input	= '<select class="form-control status" style="width: 100%;" onchange="change_status(' . "'" . md5($field->id_slider) . "'" . ')" '. $disabled .'>';
	    foreach ($status as $key => $value) {
	    	$selected = $field->is_aktif == $key ? 'selected' : '';
	    	$input	.= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
	    }
	    $input	.= '</select>';
	    $input	.= '<script>$(function() {$(".status").select2()});</script>';

		return $input;
	}


}

/* End of file Slider_model.php */
/* Location: ./application/models/Slider_model.php */