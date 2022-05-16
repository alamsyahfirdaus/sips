<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wakel_model extends CI_Model {

	private $table 			= 'wali_kelas wk';
	private $primaryKey		= 'md5(wk.wali_kelas_id)';
	private $columnOrder	= ['wali_kelas_id', NULL];
	private $columnSearch	= ['wali_kelas_id', 'full_name', 'nama_kelas'];
	private $orderBy		= ['wali_kelas_id' => 'DESC'];

	private function _setJoin()
	{
		$this->db->join('user u', 'u.user_id = wk.id_user', 'left');
		$this->db->join('kelas k', 'k.kelas_id = wk.id_kelas', 'left');
	}

	private function _setWhere()
	{
		$this->db->where('wk.id_tahun_pelajaran', $this->input->post('itp'));
		$this->db->where('k.delete_at', NULL);
		$this->db->order_by('k.id_tingkat_kelas', 'asc');
		$this->db->order_by('k.urutan_kelas', 'asc');
	}

	private function _setLimit()
	{
		$limit = $this->input->post('length') + 1 + $this->input->post('start');
		$this->db->limit($limit);
	}

	private function _setBuilder()
	{
		$this->_setJoin();
		$this->_setWhere();
		$this->_setLimit();
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnOrder, $this->columnSearch, $this->orderBy);
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
			$row[]	= $this->_getGuru($field, $this->input->post('itp'));
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
		$button		.= '<li><a href="javascript:void(0)" onclick="delete_data(' . "'" . md5($field->wali_kelas_id) . "'" . ')">Hapus</a></li>';
		$button		.= '</ul>';
		$button		.= '</div>';
		$button		.= '</div>';

		return $button;
	}

	private function _getGuru($field, $id_tahun_pelajaran)
	{
		$query = $this->_getWakel($id_tahun_pelajaran, $field->id_user);

		$input		= '<select class="form-control id_user" style="width: 100%;" name="id_user_'. $field->wali_kelas_id .'" onchange="change_wakel('. $field->wali_kelas_id .')">';
		$input		.= '<option value="">-- Wali Kelas --</option>';
		foreach ($query as $row) {
			$selected = $field->id_user == $row->user_id ? 'selected' : '';
			$input		.= '<option value="'. $row->user_id .'" '. $selected .'>'. $row->no_induk .' - '. $row->full_name .'</option>';
		}
		$input		.= '</select>';
		$input 		.= '<script>$(function() {$(".id_user").select2()});</script>';

		return $input;
	}

	private function _getWakel($id_tahun_pelajaran, $user_id = NULL)
	{
		$tapel 	= $this->_getArrayTapel($id_tahun_pelajaran);
		$this->db->where_not_in('user_id', $tapel['user_id']);
		$this->db->where('user_type_id', 2);
		if ($user_id) {
			$this->db->or_where('user_id', $user_id);
		}
		return $this->db->get('user')->result();
	}


	private function _getArrayTapel($id_tahun_pelajaran)
	{
		$query = $this->db->get_where($this->table, ['wk.id_tahun_pelajaran' => $id_tahun_pelajaran])->result();
		foreach ($query as $row) {
			if ($row->id_user) {
				$id_user[] 	= $row->id_user;
			}
			if ($row->id_kelas) {
				$id_kelas[] = $row->id_kelas;
			}
		}

		$data = array(
			'user_id' 	=> @$id_user,
			'kelas_id' 	=> @$id_kelas,
		);

		return $data;
	}
	
	
	# DATATABLE SISWA (WALI KELAS)
	
	private $table1 		= 'user u';
	private $primaryKey1	= 'md5(user_id)';
	private $columnOrder1	= ['user_id', NULL];
	private $columnSearch1	= ['user_id'];
	
	private $orderBy1		= ['user_id' => 'DESC'];

	private function _setJoin1()
	{
		$this->db->join('siswa s', 's.id_user = u.user_id', 'left');
		$this->db->join('kelas k', 'k.kelas_id = s.id_kelas', 'left');
		$this->db->join('tingkat_kelas tk', 'tk.tingkat_kelas_id = k.id_tingkat_kelas', 'left');
	}

	private function _setWhere1()
	{
		$this->db->where('u.user_type_id', 3);
		$this->db->where('md5(s.id_kelas)', $this->input->post('id_kelas'));

		if ($this->input->post('id_user')) {
			$this->db->where('md5(u.user_id)', $this->input->post('id_user'));
		}
	}

	private function _setBuilder1()
	{
		$this->_setJoin1();
		$this->_setWhere1();
		$this->_setLimit();
		$this->db->order_by('u.full_name', 'asc');
		$this->db->from($this->table1);
		$this->include->setDataTables($this->columnOrder1, $this->columnSearch1, $this->orderBy1);
	}

	public function getSiswa()
	{
		$query 	= $this->include->getResult($this->_setBuilder1());
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
			$row[]	= $this->include->null($field->agama);
			$row[]	= '<div class="text-center"><a href="'. site_url('teacher/see/') . md5($field->user_id) .'" class="btn btn-sm" style="background-color: #00A65A; color: #FFFFFF; font-weight: bold; font-family: serif;"><i class="glyphicon glyphicon-user"></i></a></div>';
			$data[]	= $row;
		}

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table1),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder1())->num_rows(),
			'data' 				=> $data,
		];

		return $setData;
	}

	# DATATABLE GURU PIKET (MASTER > GURU PIKET)

	private function _setGuruPiket($table)
	{
		$column_order   = ['gp.id_guru_piket'];
		$column_search  = ['gp.id_guru_piket', 'u.no_induk', 'u.full_name'];
		$order_by		= ['gp.id_guru_piket' => 'desc'];

		$this->db->select('gp.id_guru_piket, gp.hari, dgp.id_detail_guru_piket, u.no_induk, u.full_name');
		$this->db->join('guru_piket dgp', 'dgp.id_detail_guru_piket = gp.id_guru_piket', 'left');
		$this->db->join('user u', 'u.user_id = dgp.id_user', 'left');
		$this->db->where('md5(gp.id_tahun_pelajaran)', $this->input->post('id_tahun_pelajaran'));
		$this->db->where('gp.id_detail_guru_piket', NULL);
		$this->db->order_by('gp.hari', 'asc');
		$this->db->group_by('gp.id_guru_piket');
		$this->_setLimit();
		$this->db->from($table);
		$this->include->setDataTables($column_order, $column_search, $order_by);
	}

	public function getGuruPiket()
	{
		$table =  'guru_piket gp';

		return array(
			'result' 			=> $this->include->getResult($this->_setGuruPiket($table)),
			'recordsTotal' 		=> $this->db->count_all_results($table),
			'recordsFiltered' 	=> $this->db->get($this->_setGuruPiket($table))->num_rows(),
		);
	}

}

/* End of file Wakel_model.php */
/* Location: ./application/models/Wakel_model.php */