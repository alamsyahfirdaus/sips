<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_presensi_kelas_model extends CI_Model {

	private function _setBuilder()
	{
		$list_fields = array_merge($this->db->list_fields('siswa'), $this->db->list_fields('user'));
		$field_data  = array_unique($list_fields);
		$primary_key = $this->db->list_fields('siswa')[0];
		$order_by 	 = array($primary_key => 'desc');
		$limit 		 = $this->input->post('length') + 1 + $this->input->post('start');

		$this->db->select($field_data);
		$this->db->join('user', 'user.user_id = siswa.id_user', 'left');
		$this->db->where('md5(id_kelas)', $this->input->post('id_kelas'));
		if ($this->input->post('id_user')) {
			$this->db->where('user_id', $this->input->post('id_user'));
		}
		$this->db->order_by('full_name', 'asc');
		$this->db->limit($limit);
		$this->db->from('siswa');
		$this->include->setDataTables($field_data, $field_data, $order_by);
	}

	public function getBuilder()
	{
		return array(
			'result' 			=> $this->include->getResult($this->_setBuilder()),
			'recordsTotal' 		=> $this->db->count_all_results('siswa'),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder())->num_rows(),
		);
	}

	public function getDataTables()
	{
		$this->load->model('Tapel_model', 'tahun_pelajaran');
		$this->load->model('Siswa_model', 'siswa');
		$this->load->model('Lap_presensi_siswa_model', 'lpsm');

		$bulider 	= $this->getBuilder();
		$data 		= array();
		$start 		= $this->input->post('start');
		$no  		= $start > 0 ? $start + 1 : 1;

		$id_kelas   = array();

		$arr_siswa 	= array();
		$arr_hadir 	= array();
		$arr_color 	= array();

		$jadwal_pelajaran = $this->db->get_where('jadwal_pelajaran', ['jadwal_pelajaran_id' => $this->input->post('id_jadwal_pelajaran')])->row();
		$id_jadwal_pelajaran = isset($jadwal_pelajaran->jadwal_pelajaran_id) ? $jadwal_pelajaran->jadwal_pelajaran_id : null;
		$id_mata_pelajaran 	 = isset($jadwal_pelajaran->jadwal_pelajaran_id) ? $jadwal_pelajaran->id_mata_pelajaran : null;

		$arr1_presensi_siswa = array(
			'id_jadwal_pelajaran'   => $id_jadwal_pelajaran,
			'id_tahun_pelajaran'  	=> $this->input->post('id_tahun_pelajaran'),
			'id_tapel'  			=> $this->input->post('id_tahun_pelajaran'),
			'semester' 				=> $this->input->post('semester'),
			'tgl_awal'  			=> $this->input->post('tgl_awal'),
			'tgl_akhir' 			=> $this->input->post('tgl_akhir'), 
		);

		$presensi_mapel = $this->lpsm->countPresensiMapel(array_merge($arr1_presensi_siswa, ['id_kelas' => $this->input->post('id_kelas')]));

		$mata_pelajaran = array();

		foreach ($presensi_mapel['mata_pelajaran'] as $key) {
			if (!$key->sub_id) {
				$mata_pelajaran[] = array(
					'id_mata_pelajaran' => $key->id_mata_pelajaran,
					'mata_pelajaran' 	=> $key->nama_mapel,
				);
			}
		}

		# DATATABLE START

		foreach ($bulider['result'] as $field) {
			$start++;
			$row 	= array();

			if ($field->gender == 'L') {
				$jenis_kelamin = 'Laki-Laki';
			} elseif ($field->gender == 'P') {
				$jenis_kelamin = 'Perempuan';
			} else {
				$jenis_kelamin = '-';
			}

			$arr2_presensi_siswa = array(
				'id_user'   		 => $field->user_id,
				'id_kelas' 			 => $field->id_kelas,
				'id_mata_pelajaran'	 => $id_mata_pelajaran,
			);

			$row[]	= '<div style="text-align: center;">'. $no++ .'</div>';
			$row[]	= '<div style="text-align: left;">'. $field->no_induk .'</div>';
			$row[]	= '<div style="text-align: left;">'. $field->full_name .'</div>';
			$row[]	= '<div style="text-align: left;">'. $jenis_kelamin .'</div>';
			foreach ($this->include->opsiPresensi() as $key => $value) {
				$count_presensi = $this->_count_presensi(array_merge($arr1_presensi_siswa, $arr2_presensi_siswa, ['status' => $key]));
				$row[]	= '<div style="text-align: center;">'. $count_presensi .'</div>';
			}

			$kehadiran 		= $this->_count_presensi(array_merge($arr1_presensi_siswa, $arr2_presensi_siswa, ['status' => 1]));
			$jml_hari  		= count($this->tahun_pelajaran->getListTanggal());
			$total_pekan 	= $id_jadwal_pelajaran != null ? $this->lpsm->total_pekan(array_merge($arr1_presensi_siswa, $arr2_presensi_siswa, ['status' => 1])) : $jml_hari;
			$perhitungan    = $kehadiran >= 1 && $total_pekan >= 1 ? $kehadiran / $total_pekan * 100 : 0;
			$persentase 	= intval($perhitungan) >= 100 ? 100 : round($perhitungan);

			if ($persentase >= 90) {
				$progress_bar 	= 'progress-bar-green';
				$fillColor 		= '#00A65A';
			} elseif ($persentase >= 70) {
				$progress_bar 	= 'progress-bar-yellow';
				$fillColor 		= '#F39C12';
			} else {
				$progress_bar 	= 'progress-bar-red';
				$fillColor 		= '#DD4B39';
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
			
			$row[]	= '<div style="text-align: center;">'. $progress .'</div>';

			$id_kelas[] 	= $field->id_kelas;
			$arr_siswa[] 	= $field->full_name;
			$arr_hadir[] 	= $kehadiran;
			$arr_color[] 	= $fillColor;

			$data[]	= $row;
		}

		# DATATABLE END

		$siswa = $this->siswa->getJmlSiswa($id_kelas);

		# REKAP PRESENSI MAPEL
		
		$arr_mapel 	= array();

		if (!$this->input->post('id_jadwal_pelajaran') && $this->input->post('id_user')) {

			$tb_mapel = '<table class="table">';

			$no_mapel = 1;

			foreach ($mata_pelajaran as $key) {

				$mapel = array(
					'id_kelas'  		=> $this->input->post('id_kelas'),
					'id_user' 			=> $this->input->post('id_user'),
					'status' 			=> 1,
					'id_mata_pelajaran' => $key['id_mata_pelajaran']
				);

				$get_presensi 	= $this->lpsm->countPresensiMapel(array_merge($arr1_presensi_siswa, $mapel));
				$hadir 			= $get_presensi['presensi_siswa'];
				$week_mapel 	= $get_presensi['total_pekan'];

				$hitung_mapel 	= $hadir >= 1 && $week_mapel >= 1 ? $hadir / $week_mapel * 100 : 0;
				$persen_mapel 	= intval($hitung_mapel) >= 100 ? 100 : round($hitung_mapel);

				if ($persen_mapel >= 90) {
					$progress_bar_mapel = 'progress-bar-green';
					$color_mapel = '#00A65A';
				} elseif ($persen_mapel >= 70) {
					$progress_bar_mapel = 'progress-bar-yellow';
					$color_mapel = '#F39C12';
				} else {
					$progress_bar_mapel = 'progress-bar-red';
					$color_mapel = '#DD4B39';
				}

				$arr_mapel[] = array(
					'value' 		=> $hadir,
					'color' 		=> $color_mapel,
					'highlight' 	=> $color_mapel,
					'label'			=> $key['mata_pelajaran'],
				);

				$progress_mapel = '<div class="clearfix">';
				$progress_mapel .= '<span class="pull-left">'. $key['mata_pelajaran'] .'</span>';
				$progress_mapel .= '<small class="pull-right">'. 	$persen_mapel .'%</small>';
				$progress_mapel .= '</div>';
				$progress_mapel .= '<div class="progress">';
				$progress_mapel .= '<div class="progress-bar '. $progress_bar_mapel .' progress-bar-striped" role="progressbar" aria-valuenow="'. $persen_mapel .'" aria-valuemin="0" aria-valuemax="100" style="width: '. $persen_mapel .'%">';
				$progress_mapel .= '<span class="sr-only">'. $persen_mapel .'% Complete</span>';
				$progress_mapel .= '</div>';
				$progress_mapel .= '</div>';

				$border_top = $no_mapel == 1 ? 'border-top: none; padding-top: 0px' : '';

				$tb_mapel .= '<tr><td style="'. $border_top .'">'. $progress_mapel .'</td></tr>';

				$no_mapel++;

			}

			$tb_mapel .= '</table>';

		}

		$get_jadpel = $this->db->join('mata_pelajaran', 'mata_pelajaran.mapel_id = jadwal_pelajaran.id_mata_pelajaran', 'left')->get_where('jadwal_pelajaran', ['md5(id_kelas)' => $this->input->post('id_kelas')])->result();
		$id_jadpel  = array();
		foreach ($get_jadpel as $key) {
			$id_jadpel[] = array(
				'id_mata_pelajaran'  => $key->jadwal_pelajaran_id,
				'mata_pelajaran' 	 => $key->kode_mapel .' - '. $key->nama_mapel,
			);
		}


		return array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $bulider['recordsTotal'],
			'recordsFiltered' 	=> $bulider['recordsFiltered'],
			'data' 				=> $data,
			'siswa'				=> $siswa['siswa'],
			'jml_siswa'			=> $siswa['total'],
			'laki_laki'			=> $siswa['laki_laki'],
			'perempuan'			=> $siswa['perempuan'],
			'sZeroRecords'		=> $this->input->post('id_kelas') ? 'TIDAK DITEMUKAN' : 'HARUS MEMILIH KELAS',
			'arr_siswa'			=> $arr_siswa,
			'arr_hadir'			=> $arr_hadir,
			'arr_color'			=> $arr_color,
			'mata_pelajaran'	=> $id_jadpel,
			'arr_mapel'			=> count($arr_mapel) > 0 ? $arr_mapel : false,
			'tb_mapel'			=> isset($tb_mapel) ? $tb_mapel : false,
		);

	}

	private function _count_presensi($data)
	{
		$query = $this->db->get_where('jadwal_pelajaran', ['jadwal_pelajaran_id' => $data['id_jadwal_pelajaran']])->row();
		$jadwal_pelajaran_id = isset($query->jadwal_pelajaran_id) ? $query->jadwal_pelajaran_id : 0;
		$id_jadwal_pelajaran = $this->_jadwal_pelajaran($jadwal_pelajaran_id);

		if ($jadwal_pelajaran_id > 0) {
			$this->db->where_in('id_jadwal_pelajaran', $id_jadwal_pelajaran);
		} else {
			$this->db->where('md5(id_tapel)', $data['id_tapel']);
		}

		$this->db->where('id_user', $data['id_user']);
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
		return $jadwal_pelajaran_id > 0 ? $query->num_rows() / count($id_jadwal_pelajaran) : $query->num_rows();
	}

	private function _jadwal_pelajaran($id_jadwal_pelajaran)
	{
		$data = array();
		foreach ($this->db->get_where('jadwal_pelajaran', ['sub_id' => $id_jadwal_pelajaran])->result() as $row) {
			$data[] = $row->jadwal_pelajaran_id;
		}
		return array_unique(array_merge([$id_jadwal_pelajaran], $data));
	}

}

/* End of file Rekap_presensi_kelas_model.php */
/* Location: ./application/models/Rekap_presensi_kelas_model.php */