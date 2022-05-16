<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_model extends CI_Model {

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
	];
	
	private $orderBy		= ['user_id' => 'DESC'];

	private function _setJoin()
	{
		$this->db->join('user_type ut', 'ut.user_type_id = u.user_type_id', 'left');
		$this->db->join('siswa s', 's.id_user = u.user_id', 'left');
		$this->db->join('kelas k', 'k.kelas_id = s.id_kelas', 'left');
		$this->db->join('tingkat_kelas tk', 'tk.tingkat_kelas_id = k.id_tingkat_kelas', 'left');
	}

	private function _setWhere()
	{
		$this->db->where('u.delete_at', NULL);
		$this->db->where('u.user_type_id', 3);

		if ($this->input->post('id_tingkat_kelas')) {
			$this->db->where('md5(k.id_tingkat_kelas)', $this->input->post('id_tingkat_kelas'));
		}

		if ($this->input->post('id_kelas')) {
			$this->db->where('md5(s.id_kelas)', $this->input->post('id_kelas'));
		}

		if ($this->input->post('is_aktif')) {
			$this->db->where('s.is_aktif', $this->input->post('is_aktif'));
		} else {
			$this->db->where('s.is_aktif', 1);
		}

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
		$this->db->order_by('u.full_name', 'asc');
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
			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]	= $this->include->null($field->no_induk);
			$row[]	= $field->full_name;
			$row[]	= $field->gender == 'L' ? 'Laki-Laki' : 'Perempuan';
			$row[]	= $field->tempat_lahir . ', ' . date('d-m-Y', strtotime($field->tanggal_lahir));
			// $row[]	= $this->include->null($field->agama);
			$row[]	= $field->id_kelas ? $field->nama_kelas : '-';

			$row[]	= $this->include->statusSiswa($field->is_aktif);
			$row[]	= $this->_getButton($field);
			$data[]	= $row;
		}

		$count = $this->_countData();

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder())->num_rows(),
			'data' 				=> $data,

			'jumlah_siswa'		=> $count['jumlah_siswa'],
			'laki_laki'			=> $count['laki_laki'],
			'perempuan'			=> $count['perempuan'],
		];

		return $setData;
	}

	public function _countData()
	{
		$this->_setJoin();
		$this->_setWhere();
		$query = $this->db->get($this->table)->result();

		foreach ($query as $row) {
			$jumlah_siswa[] = $row->user_id;

			if ($row->gender == 'L') {
				$laki_laki[] = $row->user_id;
			}

			if ($row->gender == 'P') {
				$perempuan[] = $row->user_id;
			}
		}

		return array(
			'jumlah_siswa' 	=> @$jumlah_siswa ? count($jumlah_siswa) : 0,
			'laki_laki' 	=> @$laki_laki ? count($laki_laki) : 0,
			'perempuan' 	=> @$perempuan ? count($perempuan) : 0,
		);
	}

	public function getData($id = NULL)
	{
		$this->_setJoin();

		if ($id) {
			return $this->db->get_where($this->table, [$this->primaryKey => $id])->row();
		} else {
			return $this->db->get($this->table)->result();
		}
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

	# DATA TABLE PRESENSI
	# ROLE GURU DAN SISWA

	private $table1 = 'presensi';

	private function _queryPresensi()
	{
		$col_order 	= ['presensi_id'];
		$col_search = ['presensi_id'];
		$order_by 	= ['presensi_id' => 'DESC'];

		// if ($this->input->post('id_jadwal_pelajaran')) {
			$this->db->where('md5(id_jadwal_pelajaran)', $this->input->post('id_jadwal_pelajaran'));
		// }	
		if ($this->input->post('id_user')) {
			$this->db->where('id_user', $this->input->post('id_user'));
		}
		if ($this->input->post('semester')) {
			$this->db->where('semester', $this->input->post('semester'));
		}
		$this->db->where('delete_at', NULL);
		$this->_setLimit();
		$this->db->from($this->table1);
		$this->include->setDataTables($col_order, $col_search, $order_by);
	}

	public function getPresensi($week)
	{
		$query 	= $this->include->getResult($this->_queryPresensi());
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query as $field) {
			$start++;
			$row 	= array();
			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]	= $this->include->date($field->tanggal);
			
			$row[]  = '<p style="text-align: center; font-weight: bold;">'. $this->include->presensi($field->status) .'</p>';

			// if ($this->input->post('id')) {
			// 	$row[]	= $this->_setInput($this->input->post('id_user'), $this->input->post('id_jadwal_pelajaran'), $this->input->post('semester'), $this->input->post('id'), $field->presensi_id, $field->status);
			// } else {
			// 	$row[]  = '<p style="text-align: center; font-weight: bold;">'. $this->include->presensi($field->status) .'</p>';
			// }

			$data[]	= $row;
		}


		if ($week > 0) {

			$kehadiran = $this->db->get_where('presensi', [
				'md5(id_jadwal_pelajaran)' => $this->input->post('id_jadwal_pelajaran'),
				'id_user' => $this->input->post('id_user'),
				'semester' => $this->input->post('semester'),
				'status' => 1,
				'delete_at' => NULL
			])->num_rows();

			$hitung 		= $kehadiran / $week * 100;
			$persentase 	= intval($hitung);
		} else {
			$persentase 	= 0;
		}


		if ($persentase >= 90) {
			$progress_bar = 'progress-bar-green';
		} elseif ($persentase >= 70) {
			$progress_bar = 'progress-bar-yellow';
		} else {
			$progress_bar = 'progress-bar-red';
		}

		$progress = '<div class="clearfix">';
		$progress .= '<span class="pull-left">Kehadiran</span>';
		$progress .= '<small class="pull-right">'. 	$persentase .'%</small>';
		$progress .= '</div>';
		$progress .= '<div class="progress">';
		$progress .= '<div class="progress-bar '. $progress_bar .' progress-bar-striped" role="progressbar" aria-valuenow="'. $persentase .'" aria-valuemin="0" aria-valuemax="100" style="width: '. $persentase .'%">';
		$progress .= '<span class="sr-only">'. $persentase .'% Complete</span>';
		$progress .= '</div>';
		$progress .= '</div>';


		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table1),
			'recordsFiltered' 	=> $this->db->get($this->_queryPresensi())->num_rows(),
			'data' 				=> $data,
			'kehadiran'			=> $progress,
		];

		return $setData;
	}

	private function _queryTanggalInput()
	{
		$col_order 	= ['presensi_id'];
		$col_search = ['presensi_id'];
		$order_by 	= ['presensi_id' => 'ASC'];

		$this->db->where('md5(id_jadwal_pelajaran)', $this->input->post('id_jadwal_pelajaran'));
		$this->db->where('semester', $this->input->post('semester'));
		if ($this->input->post('presensi_id')) {
			$this->db->where('md5(presensi_id)', $this->input->post('presensi_id'));
		}
		$this->db->order_by('tanggal', 'desc');
		$this->db->group_by('tanggal');
		$this->_setLimit();
		$this->db->from($this->table1);
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
			$button .= '<li><a href="javascript:void(0)" onclick="edit_tanggal(' . "'" . md5($field->presensi_id) . "'" . ')">Edit</a></li>';
			$button .= '<li class="divider"></li>';
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

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->_queryTanggalInput()),
			'recordsFiltered' 	=> count($data),
			'data' 				=> $data,
		];

		return $setData;
	}

	# DATA TABLE KEHADIRAN SISWA
	# ROLE GURU
	
	private function _queryKehadiran()
	{
		$this->db->join('siswa s', 's.id_user = u.user_id', 'left');
		$this->db->where('md5(s.id_kelas)', $this->input->post('id_kelas'));
		if ($this->input->post('id_user')) {
			$this->db->where('md5(u.user_id)', $this->input->post('id_user'));
		}
		$this->db->where('u.delete_at', NULL);
		$this->db->order_by('u.full_name', 'asc');
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnSearch, $this->columnSearch, $this->orderBy);
	}

	public function getKehadiran()
	{
		$query 	= $this->include->getResult($this->_queryKehadiran());
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;

		$hadir 	= array();
		$sakit 	= array();
		$izin  	= array();
		$alpa  	= array();

		foreach ($query as $field) {
			$start++;
			$row 	= array();
			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]	= $this->include->null($field->no_induk);
			$row[]	= $field->full_name;
			$row[]	= $field->gender == 'L' ? 'Laki-Laki' : 'Perempuan';
			if ($this->input->post('id')) {

				foreach ($this->include->opsiPresensi() as $key => $value) {
					$num_rows = $this->db->get_where($this->table1, [
							'md5(id_jadwal_pelajaran)'	=> $this->input->post('id_jadwal_pelajaran'),
							'id_user'					=> $field->user_id,
							'semester'					=> $this->input->post('semester'),
							'status'					=> $key,
						])->num_rows();

					$row[] = '<p style="text-align: center;">'. $num_rows  .'</p>';
				}

				$row[]	=  '<p style="text-align: center;">'. $this->_btnDetail($field) .'</p>';

			} else {
				$select = $this->_setInput($field->user_id, $this->input->post('id_jadwal_pelajaran'), $this->input->post('semester'), NULL, NULL, NULL);

				$row[]	= $select['option'];

				$status = $select['status'];

				if ($status == 1) {
					$hadir[] = $status;
				} elseif ($status == 2) {
					$sakit[] = $status;
				} elseif ($status == 3) {
					$izin[] = $status;
				} elseif ($status == 4) {
					$alpa[] = $status;
				}
			}
			$data[]	= $row;
		}

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_queryKehadiran())->num_rows(),
			'data' 				=> $data,
			'hadir'				=> count($hadir),
			'sakit'				=> count($sakit),
			'izin'				=> count($izin),
			'alpa'				=> count($alpa),
		];

		return $setData;
	}

	private function _setInput($id_user, $id_jadwal_pelajaran, $semester, $id = NULL, $presensi_id = NULL, $status_id = NULL)
	{
		# Role Guru
		# Terdapat Pada Halaman Beranda -> Kehadiran & Jadwal Mengajar -> Kehadiran -> Edit 

		$presensi 		= $this->rowPresensi(NULL, $id_jadwal_pelajaran, $id_user, $semester, $id);
		$id_presensi 	= @$presensi_id ? $presensi_id : $id_user;
		$status 		= $status_id ? @$status_id : @$presensi->status;

		if ($status) {
			$input  = '<input type="hidden" name="user_id_'. $id_presensi .'" value="' . $id_presensi . '">';
			$input  .= '<input type="hidden" name="presensi_id_'. $id_presensi .'" value="' . @$presensi_id . '">';
			$input  .= '<input type="hidden" name="id_jadwal_pelajaran_'. $id_presensi .'" value="' . $id_jadwal_pelajaran . '">';
	    	$input	.= '<select class="form-control id_user" style="width: 100%;" name="status_'. $id_presensi .'" onchange="change_status(' . $id_presensi . ')">';
	        foreach ($this->include->opsiPresensi() as $key => $value) {
				$selected = $key == $status ? 'selected' : '';
	        	$input	.= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
	        }
	        $input	.= '</select>';
	        $input 	.= '<script>$(function() {$(".id_user").select2()});</script>';
		} else {
			$input  = '<p style="font-weight: bold; text-align: center;">BELUM PRESENSI</p>';
		}

		return array(
			'option' => $input,
			'status' => $status
		);


      	// return $input;
	}

	private function _btnDetail($field)
	{
		# MENAMPILKAN PRESENSI BERDASARKAN SISWA 
		# Jadwal Mengajar -> Kehadiran -> Detail
		
		$no_induk 	= $field->no_induk ? $field->no_induk . ' - ' : '';

		$button = '<button type="button" class="btn btn-sm" style="background-color: #00A65A; color: #FFFFFF;" onclick="show_presensi(' . $field->user_id . ')"><i class="fa fa-folder"></i></button>';
		$button .= '<input type="hidden" id="title" value="Detail Presensi">';
		$button .= '<input type="hidden" name="name_'. $field->user_id .'" value="'. $no_induk . $field->full_name .'">';
		return $button;
	}

	public function rowPresensi($tanggal = NULL, $id_jadwal_pelajaran, $id_user, $semester = NULL, $id = NULL)
	{
		# CEK PRESENSI HARI INI
		
		$date = $tanggal ? date('Y-m-d', strtotime($tanggal)) : date('Y-m-d');
		
		if (!$id) {
			$this->db->where('DATE(tanggal)', $date);
		}
		$this->db->where('md5(id_jadwal_pelajaran)', $id_jadwal_pelajaran);
		$this->db->where('id_user', $id_user);
		if ($semester) {
			$this->db->where('semester', $semester);
		}
		$query = $this->db->get($this->table1)->row();
		return @$query ? $query : FALSE;

	}

	# DATATABLE ANGGOTA KELAS
	# Bagian Siswa (Beranda -> Kelas)

	private function _queryAnggotaKelas()
	{
		$this->db->join('siswa s', 's.id_user = u.user_id', 'left');
		$this->db->where('s.id_kelas', $this->input->post('id_kelas'));
		$this->db->where('u.delete_at', NULL);
		$this->_setLimit();
		$this->db->order_by('u.full_name', 'asc');
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnSearch, $this->columnSearch, $this->orderBy);
	}

	public function getAnggotaKelas()
	{
		$query 	= $this->include->getResult($this->_queryAnggotaKelas());
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query as $field) {
			$start++;
			$row 	= array();
			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]	= $this->include->null($field->no_induk);
			$row[]	= $field->full_name;
			$row[]	= $field->gender == 'L' ? 'Laki-Laki' : 'Perempuan';
			$data[]	= $row;
		}

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_queryAnggotaKelas())->num_rows(),
			'data' 				=> $data,
		];

		return $setData;
	}

	# DATA TABLE INPUT KEHADIRAN
	# ROLE GURU (Jadwal Mengajar -> Input)
	
	private function _queryInputKehadiran()
	{
		$this->db->join('siswa s', 's.id_user = u.user_id', 'left');
		$this->db->where('md5(s.id_kelas)', $this->input->post('id_kelas'));
		if ($this->input->post('id_user')) {
			$this->db->where('md5(u.user_id)', $this->input->post('id_user'));
		}
		$this->_setLimit();
		$this->db->order_by('u.full_name', 'asc');
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnSearch, $this->columnSearch, $this->orderBy);
	}

	public function getInputKehadiran()
	{
		$query 	= $this->include->getResult($this->_queryKehadiran());
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
			$row[]	= $this->_setInputKehadiran($this->input->post('tanggal'), $this->input->post('id_jadwal_pelajaran'), $field->user_id, $this->input->post('semester'));
			$data[]	= $row;
		}

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_queryKehadiran())->num_rows(),
			'data' 				=> $data,

			'sZeroRecords'		=> $this->input->post('tanggal') ? 'TIDAK DITEMUKAN' : 'HARUS MEMILIH TANGGAL',
		];

		return $setData;
	}

	private function _setInputKehadiran($tanggal, $id_jadwal_pelajaran, $id_user, $semester)
	{
		$query = $this->db->get_where($this->table1, [
			'DATE(tanggal)'	=> date('Y-m-d', strtotime($tanggal)),
			'md5(id_jadwal_pelajaran)' => $id_jadwal_pelajaran,
			'id_user'	=> $id_user,
			'semester'	=> $semester
		])->row();

		$input  = '<input type="hidden" name="user_id_'. $id_user .'" value="' . $id_user . '">';

		if (isset($query->status)) {
	    	$input	.= $this->_selectStatusKehadiran($id_user, $query->status);
		} else {
			$input	.= '<p style="font-weight: bold; text-align: center;">BELUM PRESENSI</p>';
		}
		
      	return $input;
	}

	private function _selectStatusKehadiran($id_user, $status)
	{
    	$select	= '<select class="form-control" style="width: 100%;" name="status_'. $id_user .'" id="status_'. $id_user .'" onchange="change_status(' . $id_user . ')">';
        foreach ($this->include->opsiPresensi() as $key => $value) {
			$selected = $key == $status ? 'selected' : '';
        	$select	.= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
        }
        $select	.= '</select>';
        $select .= '<script>$(function() {$("#status_'. $id_user  .'").select2()});</script>';
        return $select;
	}

	# Laporan Kehadiran Siswa Berdasarkan Mata Pelajaran
	
	private function _queryMapel()
	{
		$this->db->join('siswa s', 's.id_user = u.user_id', 'left');
		$this->db->where('md5(s.id_kelas)', $this->input->post('id_kelas'));
		if ($this->input->post('id_user')) {
			$this->db->where('md5(u.user_id)', $this->input->post('id_user'));
		}
		$this->_setLimit();
		$this->db->order_by('u.full_name', 'asc');
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnSearch, $this->columnSearch, $this->orderBy);
	}

	public function getMapel()
	{
		$query 	= $this->include->getResult($this->_queryKehadiran());
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query as $field) {
			$start++;
			$row 	= array();
			$row[]  = $no++;
			$row[]	= $this->include->null($field->no_induk);
			$row[]	= $field->full_name;
			$row[]	= $field->gender;
			foreach ($this->include->opsiPresensi() as $key => $value) {
				$num_rows = $this->_getLapMapel($this->input->post('id_tahun_pelajaran'), $this->input->post('id_jadwal_pelajaran'), $field->user_id, $this->input->post('semester'), $key);

				$row[] = '<p class="text-center">'. $num_rows  .'</p>';
			}
			$row[]	= $this->_btnDetail($field);
			$data[]	= $row;
		}

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_queryKehadiran())->num_rows(),
			'data' 				=> $data,
		];

		return $setData;
	}

	private function _getLapMapel($id_tahun_pelajaran, $id_jadwal_pelajaran = NULL, $id_user, $semester = NULL, $status)
	{
		$this->db->join('jadwal_pelajaran jp', 'jp.jadwal_pelajaran_id = p.id_jadwal_pelajaran', 'left');

		if ($id_jadwal_pelajaran) {
			$this->db->where('md5(p.id_jadwal_pelajaran)', $id_jadwal_pelajaran);
		} else {
			$this->db->where('md5(jp.id_tahun_pelajaran)', $id_tahun_pelajaran);
		}
		$this->db->where('p.id_user', $id_user);
		if ($semester) {
			$this->db->where('p.semester', $semester);
		}
		$this->db->where('p.status', $status);
		return $this->db->get('presensi p')->num_rows();
	}

	# DATA TABLE LULUSAN
	
	private $table2 = 'lulusan l';
	
	public function _queryLulusan()
	{
		$col_order 	= ['id_lulusan'];
		$col_search = ['id_lulusan', 'no_induk', 'full_name'];
		$order_by 	= ['id_lulusan' => 'DESC'];

		$this->db->join('siswa s', 's.siswa_id = l.id_siswa', 'left');
		$this->db->join('user u', 'u.user_id = s.id_user', 'left');
		if ($this->input->post('id_tahun_pelajaran')) {
			$this->db->where('md5(l.id_tahun_pelajaran)', $this->input->post('id_tahun_pelajaran'));
		}
		$this->_setLimit();
		$this->db->from($this->table2);
		$this->include->setDataTables($col_order, $col_search, $order_by);
	}

	public function getLulusan()
	{
		$query 	= $this->include->getResult($this->_queryLulusan());
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query as $field) {
			$start++;

			$select 	= '<div style="text-align: center;">';
			$select		.= '<select name="itp_'. md5($field->id_lulusan) .'" class="form-control angkatan" onchange="change_angkatan(' . "'" . md5($field->id_lulusan) . "'" . ')">';
			$select		.= '<option value="">-- Tahun Pelajaran --</option>';
			foreach ($this->db->get('tahun_pelajaran')->result() as $key) {
				$selected = $key->tahun_pelajaran_id == $field->id_tahun_pelajaran ? 'selected' : '';
				$select	.= '<option value="'. $key->tahun_pelajaran_id .'" '. $selected .'>'. $key->tahun_pelajaran .'</option>';
			}
			$select		.= '</select>';
			$select		.= '</div>';
			$select 	.= '<script>$(function() {$(".angkatan").select2()});</script>';

			$row 	= array();
			$row[]  = $no++;
			$row[]	= $this->include->null($field->no_induk);
			$row[]	= $field->full_name;
			$row[]	= $field->gender == 'L' ? 'Laki-Laki' : 'Perempuan';
			$row[]	= $field->tempat_lahir . ', ' . date('d-m-Y', strtotime($field->tanggal_lahir));
			$row[]	= $this->include->null($field->agama);
			$row[]	= $select;
			$row[]	= $this->_getButton($field);
			$data[]	= $row;
		}

		$count = $this->_countLulusan($this->input->post('id_tahun_pelajaran'));

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_queryLulusan())->num_rows(),
			'data' 				=> $data,
			'lulusan'			=> $count['lulusan'],
			'laki_laki'			=> $count['laki_laki'],
			'perempuan'			=> $count['perempuan'],
		];

		return $setData;
	}

	private function _countLulusan($id_tahun_pelajaran)
	{
		$this->db->join('siswa s', 's.siswa_id = l.id_siswa', 'left');
		$this->db->join('user u', 'u.user_id = s.id_user', 'left');
		if ($id_tahun_pelajaran) {
			$this->db->where('md5(l.id_tahun_pelajaran)', $id_tahun_pelajaran);
		}
		$query = $this->db->get($this->table2)->result();

		foreach ($query as $row) {
			$lulusan[] = $row->user_id;

			if ($row->gender == 'L') {
				$laki_laki[] = $row->user_id;
			}

			if ($row->gender == 'P') {
				$perempuan[] = $row->user_id;
			}
		}

		$data = array(
			'lulusan' 	=> @$lulusan ? count($lulusan) : 0,
			'laki_laki' => @$laki_laki ? count($laki_laki) : 0,
			'perempuan' => @$perempuan ? count($perempuan) : 0,
		);

		return $data;
	}

	public function get_siswa_kelas($kelas_id)
	{
		$this->db->join('user', 'user.user_id = siswa.id_user', 'left');
		$this->db->where('md5(siswa.id_kelas)', $kelas_id);
		$this->db->where('user.user_type_id', 3);
		$this->db->where('user.delete_at', NULL);
		return $this->db->get('siswa')->result();
	}

	# PRESENSI KELAS
	
	private function _setPresensiKelas()
	{
		$list_fields = array_merge($this->db->list_fields('siswa'), $this->db->list_fields('user'));

		$this->db->join('user', 'user.user_id = siswa.id_user', 'left');
		$this->db->where('md5(id_kelas)', $this->input->post('id_kelas'));
		$this->db->order_by('full_name', 'asc');
		$this->_setLimit();
		$this->db->from('siswa');
		$this->include->setDataTables(array_unique($list_fields), array_unique($list_fields), ['siswa_id' => 'desc']);
	}

	public function getPresensiKelas($id_user = null, $status = null)
	{
		return array(
			'result' 			=> $this->include->getResult($this->_setPresensiKelas()),
			'recordsTotal' 		=> $this->db->count_all_results('siswa'),
			'recordsFiltered' 	=> $this->db->get($this->_setPresensiKelas())->num_rows(),
			'statusKehadiran'   => $this->_selectStatusKehadiran($id_user, $status),
		);
	}

	public function countPresensiKelas($data)
	{
		$this->db->where('id_user', $data['id_user']);
		$this->db->where('id_tapel', $data['id_tapel']);
		$this->db->where('semester', $data['semester']);
		$this->db->where('status', $data['status']);
		$this->db->where('delete_at', NULL);

		if (@$data['tgl_awal'] != null && @$data['tgl_akhir'] != null) {
		    $this->db->where('DATE(tanggal) between "' . $data['tgl_awal'] . '" AND "' . $data['tgl_akhir'] . '"');
		} elseif (@$data['tgl_awal'] != null) {
		    $this->db->where('DATE(tanggal)', $data['tgl_awal']);
		} elseif (@$data['tgl_akhir'] != null) {
		    $this->db->where('DATE(tanggal)', $data['tgl_akhir']);
		}

		$query = $this->db->get('presensi');
		return $query->num_rows();
	}

	public function getJmlSiswa($id_kelas = null)
	{
		$this->db->join('user', 'user.user_id = siswa.id_user', 'left');
		if ($id_kelas) {
			if (is_array($id_kelas)) {
				$this->db->where_in('id_kelas', $id_kelas);
			} else {
				$this->db->where('id_kelas', $id_kelas);
			}
		}
		$query = $this->db->get('siswa')->result();

		$arr_siswa = array(); 
		$jml_siswa = array();
		$laki_laki = array();
		$perempuan = array();
		foreach ($query as $row) {

			$arr_siswa[] = array(
				'id_siswa' 		=> $row->user_id,
				'nama_lengkap'	=> $row->no_induk .' - '. $row->full_name,
			);

			$jml_siswa[] = $row->siswa_id;
			if ($row->gender == 'L') {
				$laki_laki[] = $row->siswa_id;
			}
			if ($row->gender == 'P') {
				$perempuan[] = $row->siswa_id;
			}
		}

		return array(
			'siswa'		=> $arr_siswa,
			'total' 	=> $id_kelas ? count($jml_siswa) : 0, 
			'laki_laki' => $id_kelas ? count($laki_laki) : 0, 
			'perempuan' => $id_kelas ? count($perempuan) : 0,
		);
	}

}

/* End of file Siswa_model.php */
/* Location: ./application/models/Siswa_model.php */