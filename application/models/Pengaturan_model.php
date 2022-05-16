<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_model extends CI_Model {

	private $table 			= 'pengaturan p';
	private $primaryKey		= 'md5(id_pengaturan)';
	private $columnOrder	= ['id_pengaturan', NULL];
	private $columnSearch	= ['id_pengaturan', 'nama_pengaturan', 'pengaturan'];
	private $orderBy		= ['id_pengaturan' => 'ASC'];

	private function _setLimit()
	{
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
			$row[]	= '<div>'. $field->nama_pengaturan  .'</div>';
			$row[]	= $field->pengaturan ? '<div style="text-align: justify;">'. $field->pengaturan . '</div>' : '<div>-</div>';
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
		if ($field->id_pengaturan == 3) {
			$button		.= '<li><a href="'. site_url('setting/other/detail/' . md5($field->id_pengaturan)) .'">Detail</a></li>';
		} else {
			$button		.= '<li><a href="'. site_url('setting/other/edit/' . md5($field->id_pengaturan)) .'">Edit</a></li>';
		}
		$button		.= '</ul>';
		$button		.= '</div>';
		$button		.= '</div>';

		return $button;
	}

	public function getRecover()
	{
		$table 			= 'list_tables';
		$column_order	= ['id', NULL];
		$column_search	= ['id', 'table', 'title'];
		$order_by		= ['id' => 'ASC'];

		$this->db->where_not_in('id', [3, 5]);
		$this->_setLimit();
		$this->db->from($table);
		$datatables = $this->include->setDataTables($column_order, $column_search, $order_by);

		return array(
			'builder' 	=> $this->include->getResult($datatables),
			'total'		=> $this->db->count_all_results($table),
		);
	}

}

/* End of file Pengaturan_model.php */
/* Location: ./application/models/Pengaturan_model.php */