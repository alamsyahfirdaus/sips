<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru_model extends CI_Model {

	private $table 			= 'user u';
	private $primaryKey		= 'md5(user_id)';
	private $columnOrder	= ['user_id', NULL];

	private $columnSearch	= [
		'user_id', 
		'no_induk', 
		'full_name', 
		'tempat_lahir',
		'tanggal_lahir',
		'agama',
		'gender',
		'email',
		'phone',
		'status_guru',
	];
	
	private $orderBy		= ['user_id' => 'DESC'];

	private function _setWhere()
	{
		$this->db->where('u.delete_at', NULL);
		$this->db->where('u.user_type_id', 2);
	}

	private function _setLimit()
	{
		$limit = $this->input->post('length') + 1 + $this->input->post('start');
		$this->db->limit($limit);
	}

	private function _setBuilder()
	{
		$this->_setWhere();
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
			$row[]	= $this->include->null($field->no_induk);
			$row[]	= $field->full_name;
			$row[]	= $field->gender == 'L' ? 'Laki-Laki' : 'Perempuan';
			$row[]	= $field->tempat_lahir . ', ' . date('d-m-Y', strtotime($field->tanggal_lahir));
			$row[]	= $field->status_guru;
			// $row[]	= $this->include->null($field->agama);
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
		$button		.= '<li><a href="'. site_url('user/detail/' . md5($field->user_id)) .'">Detail</a></li>';
		$button		.= '<li class="divider"></li>';
		$button		.= '<li><a href="'. site_url('user/edit/' . md5($field->user_id)) .'">Edit</a></li>';
		$button		.= '<li class="divider"></li>';
		$button		.= '<li><a href="javascript:void(0)" onclick="delete_data(' . "'" . md5($field->user_id) . "'" . ')">Hapus</a></li>';
		$button		.= '</ul>';
		$button		.= '</div>';
		$button		.= '</div>';

		return $button;
	}

}

/* End of file Guru_model.php */
/* Location: ./application/models/Guru_model.php */