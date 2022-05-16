<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas_model extends CI_Model {

	private $table 			= 'kelas k';
	private $primaryKey		= 'md5(kelas_id)';
	private $columnOrder	= ['kelas_id', NULL];
	private $columnSearch	= ['kelas_id', 'nama_kelas', 'tingkat_kelas'];
	private $orderBy		= ['kelas_id' => 'DESC'];

	private function _setLimit()
	{
		$limit = $this->input->post('length') + 1 + $this->input->post('start');
		$this->db->limit($limit);
	}

	private function _setBuilder()
	{
		$this->db->join('tingkat_kelas tk', 'tk.tingkat_kelas_id = k.id_tingkat_kelas', 'left');
		$this->db->where('delete_at', NULL);
		$this->db->order_by('id_tingkat_kelas', 'asc');
		$this->db->order_by('urutan_kelas', 'asc');
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
			// $row[]	= $field->tingkat_kelas;
			$row[]	= $field->nama_kelas;
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
		// $button		.= '<li><a href="javascript:void(0)" onclick="edit_data(' . "'" . md5($field->kelas_id) . "'" . ')">Edit</a></li>';
		// $button		.= '<li class="divider"></li>';
		$button		.= '<li><a href="javascript:void(0)" onclick="delete_data(' . "'" . md5($field->kelas_id) . "'" . ')">Hapus</a></li>';
		$button		.= '</ul>';
		$button		.= '</div>';
		$button		.= '</div>';

		$button		.= '<input type="hidden" name="nama_kelas_'. md5($field->kelas_id) .'" value="'. $field->nama_kelas .'">';
		$button		.= '<input type="hidden" name="id_tingkat_kelas_'. md5($field->kelas_id) .'" value="'. $field->id_tingkat_kelas .'">';

		return $button;
	}

	private function _setKelas()
	{
		$column_order   = ['k.kelas_id'];
		$column_search  = ['k.kelas_id', 'k.nama_kelas', 'tk.tingkat_kelas', 'uwk.no_induk', 'uwk.full_name', 'us.no_induk', 'us.full_name'];
		$order_by		= ['k.kelas_id' => 'desc'];

		$this->db->join('tingkat_kelas tk', 'tk.tingkat_kelas_id = k.id_tingkat_kelas', 'left');
		$this->db->join('wali_kelas wk', 'wk.id_kelas = k.kelas_id', 'left');
		$this->db->join('user uwk', 'uwk.user_id = wk.id_user', 'left');
		$this->db->join('siswa s', 's.id_kelas = k.kelas_id', 'left');
		$this->db->join('user us', 'us.user_id = s.id_user', 'left');
		$this->db->where('k.delete_at', NULL);
		$this->db->order_by('k.id_tingkat_kelas', 'asc');
		$this->db->order_by('k.urutan_kelas', 'asc');
		$this->db->group_by('k.kelas_id');
		$this->_setLimit();
		$this->db->from($this->table);
		$this->include->setDataTables($column_order, $column_search, $order_by);
	}

	public function getKelas()
	{
		return array(
			'result' 			=> $this->include->getResult($this->_setKelas()),
			'recordsTotal' 		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_setKelas())->num_rows(),
		);
	}

}

/* End of file Kelas_model.php */
/* Location: ./application/models/Kelas_model.php */