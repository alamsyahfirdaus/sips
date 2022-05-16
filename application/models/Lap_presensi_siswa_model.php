<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_presensi_siswa_model extends CI_Model {

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
		$this->db->join('siswa s', 's.id_user = u.user_id', 'left');
		$this->db->join('kelas k', 'k.kelas_id = s.id_kelas', 'left');
	}

	private function _setWhere()
	{
		$this->db->where('u.user_type_id', 3);
		$this->db->where('s.is_aktif', 1);
		$this->db->where('md5(s.id_kelas)', $this->input->post('id_kelas'));
		$this->db->where('u.delete_at', NULL);

		if ($this->input->post('id_user')) {
			$this->db->where('u.user_id', $this->input->post('id_user'));
		}
	}

	private function _setBuilder()
	{
		$this->_setJoin();
		$this->_setWhere();
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

		$laki_laki 	= array();
		$perempuan 	= array();

		$jadwal_pelajaran = array(
			'id_tahun_pelajaran' 	=> $this->input->post('id_tahun_pelajaran'),
			'semester'				=> $this->input->post('semester'),
			'tgl_awal'				=> $this->input->post('tgl_awal'),
			'tgl_akhir'				=> $this->input->post('tgl_akhir'),
		);

		$id_kelas 		= array('id_kelas' => $this->input->post('id_kelas'));
		$mata_pelajaran = array();

		foreach ($this->_jadwal_pelajaran(array_merge($jadwal_pelajaran, $id_kelas)) as $key) {
			if (!$key->sub_id) {
				$mata_pelajaran[] = array(
					'id_mata_pelajaran' => $key->id_mata_pelajaran,
					'mata_pelajaran' 	=> $key->nama_mapel,
				);
			}
		}

		$siswa 			= array();
		$nama_lengkap 	= array();
		$arr_hadir 		= array();
		$arr_color 		= array();

		foreach ($query as $field) {
			$start++;
			$row 	= array();

			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]	= $this->include->null($field->no_induk);

			$full_name = $this->session->user_id == 1 ? $field->full_name : '<a href="'. site_url('teacher/see/') . md5($field->user_id) .'" style="color: #00A65A;">'. $field->full_name .'</a>';

			$row[]	= $full_name;
			$row[]	= $field->gender == 'L' ? 'Laki-Laki' : 'Perempuan';

			if ($field->gender == 'L') {
				$laki_laki[] = $field->user_id;
			} elseif ($field->gender == 'P') {
				$perempuan[] = $field->user_id;
			}

			$presensi = array(
				'id_user' 	=> $field->user_id,
				'id_kelas'  => $field->id_kelas,
				'id_mata_pelajaran' => $this->input->post('id_mata_pelajaran'),
			);

			foreach ($this->include->opsiPresensi() as $key => $value) {
				$presensi_id = $this->get_presensi(array_merge($jadwal_pelajaran, $presensi, ['status' => $key]));

				$row[] = '<p class="text-center">'. $presensi_id .'</p>';
			}

			$kehadiran 		= $this->get_presensi(array_merge($jadwal_pelajaran, $presensi, ['status' => 1]));
			$total_pekan    = $this->total_pekan(array_merge($jadwal_pelajaran, $presensi));
			$hitung 		= $kehadiran >= 1 && $total_pekan >= 1 ? $kehadiran / $total_pekan * 100 : 0;
			$persentase 	= intval($hitung) >= 100 ? 100 : intval($hitung);

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
			
			$row[] = $progress;


			$siswa[] = array(
				'id_siswa' 		=> $field->user_id,
				'nama_lengkap'	=> $field->no_induk .' - '. $field->full_name,
			);

			$nama_lengkap[] = $field->full_name;
			$arr_hadir[] 	= $kehadiran;
			$arr_color[] 	= $fillColor;
			
			$data[]	= $row;
		}

		$arr_mapel 	= array();

		if (!$this->input->post('id_mata_pelajaran') && $this->input->post('id_user')) {

			$tb_mapel = '<table class="table">';

			$no_mapel = 1;

			foreach ($mata_pelajaran as $key) {

				$mapel = array(
					'id_kelas'  		=> $this->input->post('id_kelas'),
					'id_user' 			=> $this->input->post('id_user'),
					'status' 			=> 1,
					'id_mata_pelajaran' => $key['id_mata_pelajaran']
				);

				$hadir 		= $this->get_presensi(array_merge($jadwal_pelajaran, $mapel));
				$week_mapel = $this->total_pekan(array_merge($jadwal_pelajaran, $mapel));

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

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder())->num_rows(),
			'data' 				=> $data,
			'sZeroRecords'		=> $this->input->post('id_kelas') ? 'TIDAK DITEMUKAN' : 'HARUS MEMILIH KELAS',
			'jml_siswa'			=> count($data),
			'laki_laki'			=> count($laki_laki),
			'perempuan'			=> count($perempuan),
			'mata_pelajaran'	=> $mata_pelajaran,
			'siswa'				=> $siswa,
			'arr_hadir'			=> $arr_hadir,
			'arr_siswa'			=> $nama_lengkap,
			'arr_mapel'			=> count($arr_mapel) > 0 ? $arr_mapel : false,
			'tb_mapel'			=> isset($tb_mapel) ? $tb_mapel : false,
			'arr_color'			=> $arr_color,
		];

		return $setData;
	}

	public function get_presensi($data)
	{
		$jadwal_pelajaran 		= $this->_jadwal_pelajaran($data);
		$jadwal_pelajaran_id 	= array();

		foreach ($jadwal_pelajaran as $key) {
			if (!$key->sub_id) {
				$jadwal_pelajaran_id[] = $key->jadwal_pelajaran_id;
			}
		}

		$presensi_id = 0;

		foreach ($jadwal_pelajaran_id as $key) {
			$sub_id 	= $this->_sub_jadwal($key);
			$presensi 	= $this->_count_presensi($data, $sub_id);
			$presensi_id += $presensi / count($sub_id);
		}

		return $presensi_id;
	}

	private function _count_presensi($data, $jadwal_pelajaran_id)
	{
		$this->db->where_in('id_jadwal_pelajaran', $jadwal_pelajaran_id);
		$this->db->where('id_user', @$data['id_user']);
		$this->db->where('semester', @$data['semester']);
		$this->db->where('status', @$data['status']);
		$this->db->where('delete_at', NULL);

		if (@$data['tgl_awal'] != null && @$data['tgl_akhir'] != null) {
		    $this->db->where('DATE(tanggal) between "' . $data['tgl_awal'] . '" AND "' . $data['tgl_akhir'] . '"');
		} elseif (@$data['tgl_awal'] != null) {
		    $this->db->where('DATE(tanggal)', $data['tgl_awal']);
		} elseif (@$data['tgl_akhir'] != null) {
		    $this->db->where('DATE(tanggal)', $data['tgl_akhir']);
		}

		$presensi = $this->db->get('presensi');
		return $presensi->num_rows();
	}

	private function _sub_jadwal($id_jadwal_pelajaran)
	{
		$query = $this->db->get_where('jadwal_pelajaran', [
			'sub_id'	 => $id_jadwal_pelajaran,
			'delete_at'	 => NULL,
		])->result();

		$sub_id = array();
		foreach ($query as $key) {
			$sub_id[] = $key->jadwal_pelajaran_id;
		}

		$jpi = array($id_jadwal_pelajaran);

		return array_merge($jpi, $sub_id);
	}

	private function _jadwal_pelajaran($data)
	{
		$this->db->join('mata_pelajaran mp', 'mp.mapel_id = jp.id_mata_pelajaran', 'left');
		$this->db->where('md5(id_tahun_pelajaran)', $data['id_tahun_pelajaran']);
		if (is_numeric(@$data['id_kelas'])) {
			$this->db->where('jp.id_kelas', @$data['id_kelas']);
		} else {
			$this->db->where('md5(jp.id_kelas)', @$data['id_kelas']);
		}
		if (@$data['id_mata_pelajaran'] != null) {
			$this->db->where('jp.id_mata_pelajaran', $data['id_mata_pelajaran']);
		}
		$this->db->where('jp.delete_at', NULL);
		$query = $this->db->get('jadwal_pelajaran jp');
		return $query->result();
	}

	public function total_pekan($data)
	{
		$weeks = 0;

		foreach ($this->_jadwal_pelajaran($data) as $key) {
			if (!$key->sub_id) {
				$pekan 	= $this->_get_pekan($key->hari, $key->jadwal_pelajaran_id);
				$sub_id = $this->_sub_jadwal($key->jadwal_pelajaran_id);
				$weeks += $pekan / count($sub_id);
			}
		}

		return $weeks;

	}

	private function _get_pekan($hari, $jadwal_pelajaran_id)
	{
		$this->load->model('Tapel_model', 'tapel');

		$query = $this->db->get_where('jadwal_pelajaran', [
			'sub_id'	 => $jadwal_pelajaran_id,
			'delete_at'	 => NULL,
		])->result();

		if (count($query) > 0) {
			$sub_id = 0;
			foreach ($query as $key) {
				$row = $this->tapel->get_date($key->hari, $key->jadwal_pelajaran_id);
				$sub_id += $row['week'];
			}

			$get = $this->tapel->get_date($hari, $jadwal_pelajaran_id);
			return $get['week'] + $sub_id;

		} else {
			$get = $this->tapel->get_date($hari, $jadwal_pelajaran_id);
			return $get['week'];
		}
	}

	public function countPresensiMapel($data)
	{
		$presensi_siswa = array_unique($data);

		return array(
			'mata_pelajaran'   => $this->_jadwal_pelajaran($presensi_siswa),
			'presensi_siswa'   => $this->get_presensi($presensi_siswa),
			'total_pekan'	   => $this->total_pekan($presensi_siswa),
		);
	}

}

/* End of file Lap_presensi_siswa_model.php */
/* Location: ./application/models/Lap_presensi_siswa_model.php */