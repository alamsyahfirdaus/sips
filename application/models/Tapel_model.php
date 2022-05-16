<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tapel_model extends CI_Model {

	private $table 			= 'tahun_pelajaran';
	private $columnOrder	= ['tahun_pelajaran_id', NULL];
	private $columnSearch	= ['tahun_pelajaran_id', 'tahun_pelajaran', 'semester'];
	private $orderBy		= ['tahun_pelajaran_id' => 'DESC'];

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
			$row[]  = $this->_getField($no++);
			$row[]	= $this->_getStatus($field);
			$row[]	= $this->_getSemester($field);
			$row[]	= $this->_getTanggal($field);

			$button	= $this->_getButton($field);
			$row[]	= $this->_getField($button);
			
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
		// $button		.= '<li><a href="javascript:void(0)" onclick="edit_data(' . "'" . md5($field->tahun_pelajaran_id) . "'" . ')">Edit</a></li>';
		// $button		.= '<li class="divider"></li>';
		$button		.= '<li><a href="javascript:void(0)" onclick="delete_data(' . "'" . md5($field->tahun_pelajaran_id) . "'" . ')">Hapus</a></li>';
		$button		.= '</ul>';
		$button		.= '</div>';
		$button		.= '</div>';

		$button		.= '<input type="hidden" name="tahun_pelajaran_'. md5($field->tahun_pelajaran_id) .'" value="'. $field->tahun_pelajaran .'">';

		return $button;
	}

	private function _getStatus($field)
	{
		$status = array(
			'Y' => 'Aktif',
			'N'	=> 'Tidak Aktif',
		);

		$disabled = $field->is_aktif == 'N' ? 'readonly disabled' : '';

	    $input 	= '<table class="table table-striped">';
	    $input 	.= '<thead>';
	    $input 	.= '<tr>';
	    $input 	.= '<td>'. $field->tahun_pelajaran .'</td>';
	    $input 	.= '<td style="width: 50%;">';
	    $input	.= '<select class="form-control semester" style="width: 100%;" name="status_'. md5($field->tahun_pelajaran_id) .'" onchange="change_status(' . "'" . md5($field->tahun_pelajaran_id) . "'" . ')">';
	    foreach ($status as $key => $value) {
	    	$selected = $field->is_aktif == $key ? 'selected' : '';
	    	$input	.= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
	    }
	    $input	.= '</select>';
	    $input 	.= '</td>';
	    $input 	.= '</tr>';
	    $input 	.= '</thead>';
	    $input 	.= '</table>';
	    $input	.= '<script>$(function() {$(".semester").select2()});</script>';

		return $input;
	}

	private function _getSemester($field)
	{
		$semester = array(
			'1' => '1 (Ganjil)',
			'2'	=> '2 (Genap)',
		);

		$disabled = $field->is_aktif == 'N' ? 'readonly disabled' : '';

	    $input 	= '<table class="table table-striped">';
	    $input 	.= '<thead>';
	    $input 	.= '<tr>';
	    $input 	.= '<td>';
	    $input	.= '<select class="form-control semester" style="width: 100%;" name="semester_'. md5($field->tahun_pelajaran_id) .'" onchange="change_semester(' . "'" . md5($field->tahun_pelajaran_id) . "'" . ')" '. $disabled .'>';
	    $input		.= '<option value="">-- Semester --</option>';
	    foreach ($semester as $key => $value) {
	    	$selected = $field->semester == $key ? 'selected' : '';
	    	$input	.= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
	    }
	    $input	.= '</select>';
	    $input 	.= '</td>';
	    $input 	.= '</tr>';
	    $input 	.= '</thead>';
	    $input 	.= '</table>';
	    $input	.= '<script>$(function() {$(".semester").select2()});</script>';

		return $input;
	}

	private function _getField($value)
	{
		$field 	= '<table class="table table-striped">';
		$field 	.= '<thead>';
		$field 	.= '<tr>';
		$field 	.= '<td>'. $value .'</td>';
		$field 	.= '</tr>';
		$field 	.= '</thead>';
		$field 	.= '</table>';

		return $field;
	}

	private function _getTanggal($field)
	{	
		$tanggal 	= $this->_tgl_mulai_selesai($field->tahun_pelajaran, $field->semester);
		$disabled1 	= $field->semester == NULL ? 'readonly disabled' : '';
		$disabled2 	= $field->tanggal_mulai == NULL ? 'readonly disabled' : '';	

	    $input 	= '<table class="table table-striped">';
	    $input 	.= '<thead>';
	    $input 	.= '<tr>';
	    $input 	.= '<td style="width: 50%;">';
	    $input	.= '<select class="form-control tanggal" style="width: 100%;" name="tanggal_mulai_'. md5($field->tahun_pelajaran_id) .'" onchange="change_tanggal(' . "'" . md5($field->tahun_pelajaran_id) . "'" . ')" '. $disabled1 .'>';
	    $input		.= '<option value="">-- Tanggal Mulai --</option>';
	    foreach ($tanggal as $key => $value) {
	    	$selected = $field->tanggal_mulai == $key ? 'selected' : '';
	    	$input	.= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
	    }
	    $input	.= '</select>';
	    $input 	.= '</td>';
	    $input 	.= '<td style="width: 50%;">';
	    $input	.= '<select class="form-control tanggal" style="width: 100%;" name="tanggal_selesai_'. md5($field->tahun_pelajaran_id) .'" onchange="change_tanggal(' . "'" . md5($field->tahun_pelajaran_id) . "'" . ')" '. $disabled2 .'>';
	    $input		.= '<option value="">-- Tanggal Selesai --</option>';
	    foreach ($tanggal as $key => $value) {
	    	$selected = $field->tanggal_selesai == $key ? 'selected' : '';
	    	$input	.= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
	    }
	    $input	.= '</select>';
	    $input 	.= '</td>';
	    $input 	.= '</tr>';
	    $input 	.= '</thead>';
	    $input 	.= '</table>';
	    $input	.= '<script>$(function() {$(".tanggal").select2()});</script>';

		return $input;
	}

	private function _tgl_mulai_selesai($tahun_pelajaran, $semester)
	{
		$arr_thn 		= explode('/', $tahun_pelajaran);
		$thn_mulai 		= $arr_thn[0];
		$thn_selesai 	= $arr_thn[1];

		if ($semester == 1) {
			$tanggal_mulai  	= $thn_mulai .'-07-01';
			$tanggal_selesai 	= $thn_selesai .'-01-01';
		} elseif ($semester == 2) {
			$tanggal_mulai  	= $thn_selesai .'-01-01';
			$tanggal_selesai 	= $thn_selesai .'-07-01';
		} else {
			$tanggal_mulai  	= $thn_mulai .'-01-01';
			$tanggal_selesai 	= $thn_selesai .'-12-01';
		}

		$from_date	= new DateTime($tanggal_mulai);
		$to_date	= new DateTime(date('Y-m-t', strtotime($tanggal_selesai)));

		$data = array();
		for ($date = $from_date; $date <= $to_date; $date->modify('+1 day')) {
			$data[$date->format('Y-m-d')] = $this->include->date($date->format('Y-m-d'));
		}

		return $data;
	}

	public function get_date($day, $id_jadwal_pelajaran = null, $semester = null)
	{
		$query = $this->getRowActive();

		if ($query) {

			$from_date 	= new DateTime(@$query->tanggal_mulai);
			$to_date 	= new DateTime(@$query->tanggal_selesai);

			$week = array();
			$data = array();
			for ($date = $from_date; $date <= $to_date; $date->modify('+1 day')) {
				if ($day == $date->format('w')) {
					$week[] = $date->format('w');
					$data[$date->format('Y-m-d')] = $this->include->date($date->format('Y-m-d'));
				}
			}

			$smt = $semester ? $semester : $query->semester;

			$tte = $this->_get_tgl_tidak_efektif($data, $id_jadwal_pelajaran, $smt);

			return array(
				'week' => $week > 0 ? count($tte) : false,
				'date' => $data > 0 ? $tte : false,
			);
		} else {
			return false;
		}
	}

	private function _get_tgl_tidak_efektif($tanggal, $id_jadwal_pelajaran, $semester)
	{
		$query = $this->db->get_where('presensi', [
			'id_jadwal_pelajaran' 	=> $id_jadwal_pelajaran,
			'semester' 				=> $semester,
			'keterangan'		  	=> $id_jadwal_pelajaran
		])->result();

		if (count($query) > 0) {
			$presensi_id = array();
			foreach ($query as $row) {
				$presensi_id[$row->tanggal] = $row->tanggal;
			}

			foreach ($presensi_id as $key => $value) {
				unset($tanggal[$key]);
			}

			return $tanggal;
		} else {
			return $tanggal;
		}

	}

	public function get_tanggal_semester($itp, $smt)
	{
		$query = $this->db->get_where('tanggal_semester', [
			'id_tahun_pelajaran' 	=> $itp,
			'id_semester' 			=> $smt
		])->row();

		if ($query) {
			
			$explode  				= explode('/', @$query->tanggal_semester);
			$tanggal_mulai 			= @$explode[0];
			$tanggal_selesai 		= @$explode[1];

			return array(
				'id_tanggal_semester'  => $query->id_tanggal_semester,
				'tanggal_mulai'		   => $tanggal_mulai,
				'tanggal_selesai' 	   => $tanggal_selesai
			);

		} else {
			return false;
		}
	}

	public function get_weeks($id_tahun_pelajaran, $smt)
	{
		$get 	= $this->db->get_where($this->table, ['md5(tahun_pelajaran_id)' => $id_tahun_pelajaran])->row();
		$query 	= $this->get_tanggal_semester(@$get->tahun_pelajaran_id, $smt);
		if ($query) {
			$date1 = new DateTime($query['tanggal_mulai']);
			$date2 = new DateTime($query['tanggal_selesai']);
			$difference_in_weeks = $date1->diff($date2)->days / 7;
			return round($difference_in_weeks);
		} else {
			return false;
		}
	}

	public function tgl_to_int($tanggal)
	{
		$explode	= explode('-', $tanggal);
		$year		= $explode[0];
		$month		= $explode[1];
		$day		= $explode[2];
		$ymd 		= $year . $month . $day;
		return $ymd;
	}

	public function getListTanggal()
	{
		$query = $this->getRowActive();

		$data = array();

		if (isset($query->tahun_pelajaran_id)) {
			$from_date 	= new DateTime($query->tanggal_mulai);
			$to_date 	= new DateTime($query->tanggal_selesai);
			for ($date = $from_date; $date <= $to_date; $date->modify('+1 day')) {
				if (date('w', strtotime($date->format('Y-m-d'))) >= 1) {
					$data[$date->format('Y-m-d')] = $this->include->date($date->format('Y-m-d'));
				}
			}
		}

		return $data;
	}

	public function getRowActive()
	{
		return $this->db->get_where($this->table, ['is_aktif' => 'Y'])->row();
	}

}

/* End of file Tapel_model.php */
/* Location: ./application/models/Tapel_model.php */