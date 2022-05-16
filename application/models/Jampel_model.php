<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jampel_model extends CI_Model {

	private $table 			= 'jam_pelajaran';
	private $primaryKey		= 'md5(jam_pelajaran_id)';
	private $columnOrder	= ['jam_pelajaran_id', NULL];
	private $columnSearch	= ['jam_pelajaran_id', 'jam_pelajaran',];
	private $orderBy		= ['jam_pelajaran_id' => 'ASC'];

	private function _setLimit()
	{
		$limit = $this->input->post('length') + 1 + $this->input->post('start');
		$this->db->limit($limit);
	}

	private function _setBuilder()
	{
		$this->db->where('delete_at', NULL);
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
			$row[]	= $this->include->clock($field->jam_pelajaran);
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
		$button		.= '<li><a href="javascript:void(0)" onclick="edit_data(' . "'" . md5($field->jam_pelajaran_id) . "'" . ')">Edit</a></li>';
		$button		.= '<li class="divider"></li>';
		$button		.= '<li><a href="javascript:void(0)" onclick="delete_data(' . "'" . md5($field->jam_pelajaran_id) . "'" . ')">Hapus</a></li>';
		$button		.= '</ul>';
		$button		.= '</div>';
		$button		.= '</div>';

		$button		.= '<input type="hidden" name="jam_pelajaran_'. md5($field->jam_pelajaran_id) .'" value="'. $field->jam_pelajaran .'">';

		return $button;
	}

}

/* End of file Jampel_model.php */
/* Location: ./application/models/Jampel_model.php */