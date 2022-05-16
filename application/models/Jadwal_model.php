<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_model extends CI_Model {

	private $table 			= 'jadwal_pelajaran jp';
	private $primaryKey		= 'md5(jadwal_pelajaran_id)';
	private $columnOrder	= ['jadwal_pelajaran_id', NULL];
	private $columnSearch	= ['jadwal_pelajaran_id', 'kode_mapel', 'nama_mapel', 'full_name', 'nama_kelas'];
	private $orderBy		= ['jadwal_pelajaran_id' => 'DESC'];

	private function _setJoin()
	{
		$this->db->join('mata_pelajaran mp', 'mp.mapel_id = jp.id_mata_pelajaran', 'left');
		$this->db->join('user u', 'u.user_id = jp.id_user', 'left');
		$this->db->join('tahun_pelajaran tp', 'tp.tahun_pelajaran_id = jp.id_tahun_pelajaran', 'left');
		$this->db->join('kelas k', 'k.kelas_id = jp.id_kelas', 'left');
		$this->db->join('tingkat_kelas tk', 'tk.tingkat_kelas_id = k.id_tingkat_kelas', 'left');
	}

	private function _setWhere()
	{
		$this->db->where('jp.id_tahun_pelajaran', $this->input->post('itp'));
		$this->db->where('jp.delete_at', NULL);

		if ($this->input->post('itp') && $this->input->post('id_kelas')) {
			$this->db->where('jp.id_kelas', $this->input->post('id_kelas'));
		}

		if ($this->input->post('hari')) {
			$this->db->where('jp.hari', $this->input->post('hari'));
		}

		$this->db->order_by('jp.id_kelas', 'asc');
		// $this->db->order_by('jp.hari', 'asc');
		// $this->db->order_by('jp.sort', 'asc');
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
			$kode 	= $field->kode_mapel ? $field->kode_mapel : '#';
			$mapel 	= '<a href="'. site_url('schedules/detail/' . md5($field->jadwal_pelajaran_id)) .'" style="font-weight: bold; text-decoration: none; color: #337AB7;">'. $field->nama_mapel .'</a>';
			$row[]	= $this->_getField($no++);
			$row[]	= $this->_getField($kode .' - '. $field->nama_mapel);
			$row[]	= $this->_getField($field->nama_kelas);
			$row[]	= $this->_getGuru($field);
			$row[]	= $this->_getDays($field);
			$row[]	= $this->_getJam($field);
			$row[]	= $this->_getButton($field);
			// $checkbox = '<p class="text-center"><input type="checkbox" class="data-check" value="' . $field->jadwal_pelajaran_id . '"><input type="hidden" name="jadwal_pelajaran_id[]" value="'. $field->jadwal_pelajaran_id .'"></p>';
			// $row[]	= $this->_getField($checkbox);
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
		$button 	= '<table class="table table-striped">';
		$button 	.= '<thead>';
		$button 	.= '<tr>';
		$button 	.= '<td>';
		$button 	.= '<div style="text-align: center;">';
		$button		.= '<div class="btn-group">';
		$button		.= ''. BTN_ACTION .'';
		$button		.= '<span class="caret"></span>';
		$button		.= '<span class="sr-only">Toggle Dropdown</span>';
		$button		.= '</button>';
		$button		.= '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
		// $button		.= '<li><a href="javascript:void(0)" onclick="edit_data(' . "'" . md5($field->jadwal_pelajaran_id) . "'" . ')">Reset</a></li>';
		// $button		.= '<li class="divider"></li>';
		$button		.= '<li><a href="'. site_url('schedules/presence/' . md5($field->jadwal_pelajaran_id)) .'">Presensi Siswa</a></li>';
		$button		.= '<li class="divider"></li>';
		// $button		.= '<li><a href="'. site_url('schedules/detail/' . md5($field->jadwal_pelajaran_id)) .'">Presensi</a></li>';
		
		$query = $this->db->get_where($this->table, ['sub_id' => $field->jadwal_pelajaran_id])->row();

		if (empty($query->jadwal_pelajaran_id) && !$field->sub_id) {
			$button		.= '<li><a href="javascript:void(0)" onclick="sub_jadwal(' . "'" . md5($field->jadwal_pelajaran_id) . "'" . ')">Tambah Jadwal</a></li>';
			$button		.= '<li class="divider"></li>';
		}

		// $button		.= '<li class="divider"></li>';
		$button		.= '<li><a href="javascript:void(0)" onclick="delete_data(' . "'" . md5($field->jadwal_pelajaran_id) . "'" . ')">Hapus Jadwal</a></li>';
		$button		.= '</ul>';
		$button		.= '</div>';
		$button		.= '</div>';
		$button 	.= '</td>';
		$button 	.= '</tr>';
		$button 	.= '</thead>';
		$button 	.= '</table>';

		return $button;
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

	private function _getGuru($field)
	{
		$query 	= $this->db->get_where('user', [
			'user_type_id' 	=> 2,
			'delete_at'		=> NULL
		])->result();

		$input 	= '<table class="table table-striped">';
		$input 	.= '<thead>';
		$input 	.= '<tr>';
		$input 	.= '<td>';
		$input	.= '<select class="form-control id_user" style="width: 100%;" name="id_user_'. md5($field->jadwal_pelajaran_id) .'" onchange="change_guru(' . "'" . md5($field->jadwal_pelajaran_id) . "'" . ')">';
		$input	.= '<option value="">-- Guru --</option>';
		foreach ($query as $row) {
			$selected = $field->id_user == $row->user_id ? 'selected' : '';
			$input	.= '<option value="'. $row->user_id .'" '. $selected .'>'. $row->no_induk .' - '. $row->full_name .'</option>';
		}
		$input	.= '</select>';
		$input 	.= '</td>';
		$input 	.= '</tr>';
		$input 	.= '</thead>';
		$input 	.= '</table>';
		$input 	.= '<script>$(function() {$(".id_user").select2()});</script>';

		return $input;
	}

	private function _getDays($field)
	{
		$days = array(
			'1' => 'Senin',
			'2'	=> 'Selasa',
			'3'	=> 'Rabu',
			'4'	=> 'Kamis',
			'5'	=> 'Jumat',
			'6'	=> 'Sabtu',
		);
		$input 	= '<table class="table table-striped">';
		$input 	.= '<thead>';
		$input 	.= '<tr>';
		$input 	.= '<td>';
		$input	.= '<select class="form-control days" style="width: 100%;" name="hari_'. md5($field->jadwal_pelajaran_id) .'" onchange="change_hari(' . "'" . md5($field->jadwal_pelajaran_id) . "'" . ')">';
		$input	.= '<option value="">-- Hari --</option>';
		foreach ($days as $key => $value) {
			$selected = $field->hari == $key ? 'selected' : '';
			$input	.= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
		}
		$input	.= '</select>';
		$input 	.= '</td>';
		$input 	.= '</tr>';
		$input 	.= '</thead>';
		$input 	.= '</table>';
		$input	.= '<script>$(function() {$(".days").select2()});</script>';

		return $input;
	}

	private function _getJam($field)
	{
	    if ($field->mulai != NULL) {
	    	$this->_checkJampel($field->mulai, ['mulai' => NULL], $field->jadwal_pelajaran_id);
	    } elseif ($field->selesai != NULL) {
	    	$this->_checkJampel($field->selesai, ['selesai' => NULL], $field->jadwal_pelajaran_id);
	    }


	    $mulai 		= $field->hari == NULL ? 'readonly disabled' : '';
	    $selesai 	= $field->mulai == NULL ? 'readonly disabled' : '';
	    $jam		= $this->_getMulaiSelesai($field);

	    $input 	= '<table class="table table-striped">';
	    $input 	.= '<thead>';
	    $input 	.= '<tr>';
	    $input 	.= '<td>';
	    $input	.= '<select class="form-control jam" style="width: 100%;" name="mulai_'. md5($field->jadwal_pelajaran_id) .'" onchange="change_mulai(' . "'" . md5($field->jadwal_pelajaran_id) . "'" . ')" '. $mulai .'>';
	    $input		.= '<option value="">-- Jam Mulai --</option>';
	    foreach ($this->db->where_not_in('jam_pelajaran_id', @$jam['mulai'])->or_where('jam_pelajaran_id', $field->mulai)->get('jam_pelajaran')->result() as $row) {
	    	$selected = $field->mulai == $row->jam_pelajaran_id ? 'selected' : '';
	    	$input		.= '<option value="'. $row->jam_pelajaran_id .'" '. $selected .'>'. $this->include->clock($row->jam_pelajaran) .'</option>';
	    }
	    $input	.= '</select>';
	    $input 	.= '</td>';
	    $input 	.= '<td class="text-center" style="font-weight: bold;">-</td>';
	    $input 	.= '<td>';
	    $input	.= '<select class="form-control jam" style="width: 100%;" name="selesai_'. md5($field->jadwal_pelajaran_id) .'" onchange="change_selesai(' . "'" . md5($field->jadwal_pelajaran_id) . "'" . ')" '. $selesai .'>';
	    $input		.= '<option value="">-- Jam Selesai --</option>';
	    foreach ($this->db->where_not_in('jam_pelajaran_id', @$jam['selesai'])->or_where('jam_pelajaran_id', $field->selesai)->get('jam_pelajaran')->result() as $row) {
	    	$selected = $field->selesai == $row->jam_pelajaran_id ? 'selected' : '';
	    	$input		.= '<option value="'. $row->jam_pelajaran_id .'" '. $selected .'>'. $this->include->clock($row->jam_pelajaran) .'</option>';
	    }
	    $input	.= '</select>';
	    $input 	.= '</td>';
	    $input 	.= '</tr>';
	    $input 	.= '</thead>';
	    $input 	.= '</table>';
	    $input 	.= '<script>$(function() {$(".jam").select2()});</script>';

		return $input;
	}

	private function _checkJampel($jam_pelajaran_id, $data, $jadwal_pelajaran_id)
	{
		$num_rows = $this->db->get_where('jam_pelajaran', ['jam_pelajaran_id' => $jam_pelajaran_id])->num_rows();

		if ($num_rows < 1) {
			return $this->db->update($this->table, $data, ['jadwal_pelajaran_id' => $jadwal_pelajaran_id]);
		} else {
			return FALSE;
		}

	}

	private function _getMulaiSelesai($field)
	{
		$this->db->where('id_tahun_pelajaran', $field->id_tahun_pelajaran);
		$this->db->where('id_kelas', $field->id_kelas);
		if ($field->hari) {
			$this->db->where('hari', $field->hari);
		}
		$query 		= $this->db->get($this->table);
		$interval 	= $this->_getIntervalJam($field->id_tahun_pelajaran, $field->id_kelas);

		if ($query->num_rows()) {
			foreach ($query->result() as $row) {
				if ($row->mulai) {
					$mulai[]	= $row->mulai;

					$jam1 	= $this->substrJam($field->mulai);
					$jam2 	= $this->substrJam($row->mulai);

					if (@$field->jadwal_pelajaran_id == $row->jadwal_pelajaran_id || $jam2 < $jam1) {
						$musel[]	= $row->mulai;
					}
				}
				if ($row->selesai) {

					$selesai[]	= $row->selesai;
				}

			}

			if (@$mulai && @$interval) {
				$data['mulai'] = array_merge($mulai, $interval);
			} elseif (@$mulai) {
				$data['mulai'] = $mulai;
			} else {
				$data['mulai'] = FALSE;
			}


			if (@$mulai && @$selesai && @$interval) {
				$data['selesai'] = array_merge($mulai, $selesai, $interval);
			} elseif (@$mulai && @$selesai) {
				$data['selesai'] = array_merge($mulai, $selesai);
			} elseif (@$musel) {
				$data['selesai'] = $musel;
			} else {
				$data['selesai'] = FALSE;
			}

			return $data;
		} else {
			return FALSE;
		}
	}

	public function getJamKelas($id_tahun_pelajaran, $id_kelas)
	{
		$this->db->where('id_tahun_pelajaran', $id_tahun_pelajaran);
		$this->db->where('id_kelas', $id_kelas);
		$this->db->where('hari !=', NULL);
		$this->db->where('mulai !=', NULL);
		$this->db->where('selesai !=', NULL);
		return $this->db->get($this->table)->result();
	}

	private function _getIntervalJam($id_tahun_pelajaran, $id_kelas)
	{
		$query = $this->getJamKelas($id_tahun_pelajaran, $id_kelas);

		if ($query > 0) {

			foreach ($query as $row) {

				foreach ($this->db->where_not_in('jam_pelajaran_id', [$row->mulai, $row->selesai])->get('jam_pelajaran')->result() as $key) {
					$jam1 	= $this->substrJam($row->mulai);
					$jam2 	= $this->substrJam($row->selesai);

					$jam3 	= $this->substrJam($key->jam_pelajaran_id);

					if ($jam1 < $jam3 && $jam2 > $jam3) {
						$data[] = $key->jam_pelajaran_id;
					}

				}

			}

			return @$data ? $data : FALSE;

		} else {
			return FALSE;
		}
	}

	public function substrJam($id)
	{
		$query = $this->db->get_where('jam_pelajaran', ['jam_pelajaran_id' => $id])->row();

		if (@$query->jam_pelajaran) {
			if (strlen($query->jam_pelajaran) == 4) {
				$jam 	= substr(@$query->jam_pelajaran, 0, 1);
				$menit	= substr(@$query->jam_pelajaran, 2);
			} elseif (strlen($query->jam_pelajaran) == 5) {
				$jam 	= substr(@$query->jam_pelajaran, 0, 2);
				$menit	= substr(@$query->jam_pelajaran, 3); 
			}

			return $jam . $menit;
		}

	}

	public function getData($id = NULL, $id_tahun_pelajaran = NULL, $id_kelas = NULL, $id_user = NULL, $hari = NULL)
	{
		# Query Export Jadwal Pelajaran

		$this->_setJoin();
		if ($id) {
			$this->db->where_in('jp.jadwal_pelajaran_id', $id);
		}
		if ($id_tahun_pelajaran) {
			$this->db->where('jp.id_tahun_pelajaran', $id_tahun_pelajaran);
		}
		if ($id_kelas) {
			$this->db->where('jp.id_kelas', $id_kelas);
		}
		if ($id_user) {
			$this->db->where('jp.id_user', $id_user);
		}
		if ($hari != NULL) {
			$this->db->where('jp.hari', $hari);
		}
		
		$this->db->where('jp.delete_at', NULL);

		$this->db->order_by('jp.sort', 'asc');
		return $this->db->get($this->table)->result();
	}

	public function getRow($id)
	{
		$this->_setJoin();
		$this->db->where($this->primaryKey, $id);
		return $this->db->get($this->table)->row();
	}

	# JADWAL HARI INI (ADMINISTRATOR)
	
	private function _setBuilder1($id_tahun_pelajaran = NULL)
	{
		$this->_setJoin();
		$this->db->where('jp.id_tahun_pelajaran', $id_tahun_pelajaran);
		$this->db->where('jp.hari', date('w'));
		$this->db->where('jp.delete_at', NULL);
		$this->_setLimit();
		$this->db->order_by('jp.sort', 'asc');
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnSearch, $this->columnSearch, $this->orderBy);
	}

	public function getDataTables1($id_tahun_pelajaran, $semester)
	{
		$query 	= $this->include->getResult($this->_setBuilder1($id_tahun_pelajaran));
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query as $field) {
			$start++;
			$row 	= array();
			$kode 	= $field->kode_mapel ? $field->kode_mapel : '#';
			$row[]	= '<p class="text-center">'.  $no++ .'</p>';
			$row[]	= $kode .' - '. $field->nama_mapel;
			$row[]	= $field->nama_kelas;
			$row[]	= $this->include->null($field->full_name);
			$row[]	= $this->_getJampel($field->mulai) .' - '. $this->_getJampel($field->selesai);

			$num_rows = $this->db->get_where('presensi', [
				'tanggal'				=> date('Y-m-d'),
				'id_jadwal_pelajaran'	=> $field->jadwal_pelajaran_id,
				'semester'				=> $semester
			])->num_rows();

			$kehadiran = $num_rows > 0 ? '<b>SUDAH<span style="color: #00A65A;">_</span>PRESENSI</b>' : '<b>BELUM<span style="color: #00A65A;">_</span>PRESENSI</b>';

			// $row[]	= '<p class="text-center">'.  $kehadiran .'</p>';

			// $input = '<input type="hidden" name="id_kelas_'. md5($field->jadwal_pelajaran_id) .'" value="'. md5($field->id_kelas) .'">';
			// $input .= '<p class="text-center"><a href="javascript:void(0)" onclick="add_data(' . "'" . md5($field->jadwal_pelajaran_id) . "'" . ')" class="btn btn-sm" style="background-color: #00A65A; color: #FFFFFF;" title="Input Presensi"><i class="fa fa-calendar-plus-o"></i></a></p>';
			
			// $row[]	= $input;
			
			$row[]	= '<p class="text-center"><a href="'. site_url('home/presence/' . md5($field->jadwal_pelajaran_id)) .'"  class="btn btn-sm btn-social" style="background-color: #00A65A; color: #FFFFFF;"><i class="fa fa-calendar-plus-o" style="border-right: #00A65A;"></i> '.  $kehadiran .'</a></p>';
			$data[]	= $row;
		}

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder1($id_tahun_pelajaran))->num_rows(),
			'data' 				=> $data,
		];

		return $setData;
	}

	private function _getJampel($id)
	{
		$query = $this->db->get_where('jam_pelajaran', ['jam_pelajaran_id' => $id])->row();
		return @$query ? $this->include->clock($query->jam_pelajaran) : '#';
	}

}

/* End of file Jadwal_model.php */
/* Location: ./application/models/Jadwal_model.php */