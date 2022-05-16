<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengumuman_model extends CI_Model {

	private $table 			= 'pengumuman p';
	private $primaryKey		= 'md5(id_pengumuman)';
	private $columnOrder	= ['id_pengumuman', NULL];
	private $columnSearch	= ['id_pengumuman', 'judul', 'type_name', 'pengumuman'];
	private $orderBy		= ['id_pengumuman' => 'DESC'];

	private function _setLimit()
	{
		$this->db->join('user_type ut', 'ut.user_type_id = p.user_type_id', 'left');
		$limit = $this->input->post('length') + 1 + $this->input->post('start');
		$this->db->limit($limit);
	}

	private function _setBuilder()
	{
		$this->_setLimit();
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
			$row[]	= '<p class="text-justify">'. $field->judul .'</p>';
			$row[]	= $field->gambar ? '<p class="text-center"><img class="profile-user-img img-responsive" src="'. site_url(IMAGE . $this->include->image($field->gambar)) .'" alt="User profile picture"></p>' : '<p class="text-center">-</p>';
			$row[]	= $field->user_type_id ? $field->type_name : 'Semua Pengguna';
			$row[]	= $this->include->date($field->tanggal);
			$row[]	= $this->_getStatus($field);
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
		$button		.= '<li><a href="'. site_url('setting/announcement/addedit/' . md5($field->id_pengumuman)) .'">Edit</a></li>';
		$button		.= '<li class="divider"></li>';
		$button		.= '<li><a href="javascript:void(0)" onclick="delete_data(' . "'" . md5($field->id_pengumuman) . "'" . ')">Hapus</a></li>';
		$button		.= '</ul>';
		$button		.= '</div>';
		$button		.= '</div>';

		return $button;
	}

	private function _getStatus($field)
	{
		$status = array(
			'Y' => 'Aktif',
			'N'	=> 'Tidak Aktif',
		);

	    $input	= '<select class="form-control status" style="width: 100%;" onchange="change_status(' . "'" . md5($field->id_pengumuman) . "'" . ')">';
	    foreach ($status as $key => $value) {
	    	$selected = $field->is_aktif == $key ? 'selected' : '';
	    	$input	.= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
	    }
	    $input	.= '</select>';
	    $input	.= '<script>$(function() {$(".status").select2()});</script>';

		return $input;
	}

}

/* End of file Pengumuman_model.php */
/* Location: ./application/models/Pengumuman_model.php */