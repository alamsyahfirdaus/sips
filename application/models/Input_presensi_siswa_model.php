<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Input_presensi_siswa_model extends CI_Model {

	private $table 			= 'user u';
	private $columnOrder	= ['user_id', NULL];
	private $columnSearch	= ['user_id'];
	private $orderBy		= ['user_id' => 'DESC'];

	private function _setLimit()
	{
		$limit = $this->input->post('length') + 1 + $this->input->post('start');
		$this->db->limit($limit);
	}

	private function _setBuilder()
	{
		$this->db->join('siswa s', 's.id_user = u.user_id', 'left');
		if ($this->input->post('user_id')) {
			$this->db->where('md5(u.user_id)', $this->input->post('user_id'));
		}
		$this->db->where('md5(s.id_kelas)', $this->input->post('id_kelas'));
		$this->db->where('u.user_type_id', 3);
		$this->db->where('u.delete_at', NULL);
		$this->db->order_by('u.full_name', 'asc');
		$this->db->group_by('u.user_id');
		$this->_setLimit();
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnSearch, $this->columnSearch, $this->orderBy);
	}

	public function getDataTables($dates)
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
			$row[]	= $this->_setInput($field->user_id);		

			$data[]	= $row;
		}

		$tanggal = array();
		foreach ($dates as $key => $value) {
			$tanggal[] = array(
				'key_tgl' 	=> $key,
				'val_tgl' 	=> $value,
			);
		}

		return [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder())->num_rows(),
			'data' 				=> $data,
			'tanggal'			=> $tanggal,
		];

	}

	private function _setInput($id_user)
	{
		$query = $this->db->get_where('presensi', [
			'DATE(tanggal)'				=> date('Y-m-d', strtotime($this->input->post('tanggal'))),
			'md5(id_jadwal_pelajaran)' 	=> $this->input->post('id_jadwal_pelajaran'),
			'id_user'					=> $id_user,
			'semester'					=> $this->input->post('semester'),
			'delete_at'					=> NULL
		])->row();


		$input  = '<input type="hidden" name="user_id_'. $id_user .'" value="' . $id_user . '">';

		if ($query) {
			$status = isset($query->status) ? $query->status : 0;
			
	    	$input	.= '<select class="form-control status" style="width: 100%;" name="status_'. $id_user .'" onchange="change_status(' . $id_user . ')">';
	        foreach ($this->include->opsiPresensi() as $key => $value) {
				$selected = $key == $status ? 'selected' : '';
	        	$input	.= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
	        }
	        $input	.= '</select>';
	        $input 	.= '<script>$(function() {$(".status").select2()});</script>';
		} else {
			$input	.= '<p style="font-weight: bold; text-align: center;">BELUM PRESENSI</p>';
		}

      	return $input;
      	
	}

	# TANGGAL INPUT PRESENSI SISWA
	
	private $presensi = 'presensi';

	private function _queryTanggalInput()
	{
		$table 		= 'presensi';
		$col_order 	= ['presensi_id'];
		$col_search = ['presensi_id'];
		$order_by 	= ['presensi_id' => 'DESC'];

		$this->db->where('md5(id_jadwal_pelajaran)', $this->input->post('id_jadwal_pelajaran'));
		$this->db->where('semester', $this->input->post('semester'));
		$this->db->where('delete_at', NULL);

		if ($this->input->post('presensi_id')) {
			$this->db->where('md5(presensi_id)', $this->input->post('presensi_id'));
		}
		
		if ($this->input->post('keterangan')) {
			$this->db->where('md5(keterangan)', $this->input->post('keterangan'));
		} else {
			$this->db->where('id_user !=', NULL);
		}

		$this->db->order_by('tanggal', 'desc');
		$this->db->group_by('tanggal');
		$this->_setLimit();
		$this->db->from($table);
		$this->include->setDataTables($col_order, $col_search, $order_by);
	}

	public function getTanggalInput()
	{
		$query 	= $this->include->getResult($this->_queryTanggalInput());
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query as $field) {
			$start++;
			$row 	= array();
			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			
			if ($this->input->post('presensi_id')) {
				$tanggal = '<input type="hidden" id="tanggal_old_'. $field->presensi_id .'" value="'. date('m/d/Y', strtotime($field->tanggal)) .'">';
				$tanggal .= '<div class="form-group">';
				$tanggal .= '<input type="text" id="tanggal_new_'. $field->presensi_id .'" class="form-control" value="'. date('m/d/Y', strtotime($field->tanggal)) .'">';
				$tanggal .= '<script type="text/javascript">$("#tanggal_new_'. $field->presensi_id .'").datepicker({autoclose: true})</script>';
				$tanggal .= '<small class="help-block" id="error-tanggal_new_'. $field->presensi_id .'" style="display: none; color: #DD4B39;"></small>';
				$tanggal .= '<div>';

				$row[]	= $tanggal;
			} else {
				$row[]	= '<p style="text-align: left;">'. $this->include->date($field->tanggal) .'</p>';
			}
			
       
       		$button = '<div style="text-align: center;">';
			$button .= '<div class="btn-group">';
			// $button .= '<button type="button" class="btn btn-sm" style="background-color: #00A65A; color: #FFFFFF; font-weight: bold; font-family: serif;"><i class="fa fa-cogs"></i></button>';
			$button .= '<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" style="background-color: #00A65A; color: #FFFFFF; font-weight: bold;"> Aksi ';
			$button .= '<span class="caret"></span>';
			$button .= '<span class="sr-only">Toggle Dropdown</span>';
			$button .= '</button>';
			$button .= '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
			// $button .= '<li><a href="javascript:void(0)" onclick="edit_tanggal(' . "'" . md5($field->presensi_id) . "'" . ')">Edit</a></li>';
			// $button .= '<li class="divider"></li>';
			$button .= '<li><a href="javascript:void(0)" onclick="delete_tanggal(' . "'" . md5($field->presensi_id) . "'" . ')">Hapus</a></li>';
			$button .= '</ul>';
			$button .= '</div>';
			$button .= '</div>';

			if ($this->input->post('presensi_id')) {
				$row[] = '<div style="text-align: center;"><button type="button" onclick="save_tanggal(' . $field->presensi_id . ')" class="btn btn-success btn-sm" style="font-weight: bold;"><i class="fa fa-save"></i> Simpan</button></div>';
			} else {
				$row[] = $button;
			}


			$data[]	= $row;
		}

		return [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->_queryTanggalInput()),
			'recordsFiltered' 	=> $this->db->get($this->_queryTanggalInput())->num_rows(),
			'data' 				=> $data,
		];

	}

}

/* End of file Input_presensi_siswa_model.php */
/* Location: ./application/models/Input_presensi_siswa_model.php */