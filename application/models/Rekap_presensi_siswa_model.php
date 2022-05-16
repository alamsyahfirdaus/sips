<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_presensi_siswa_model extends CI_Model {

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

	public function getDataTables($weeks)
	{
		$query 	= $this->include->getResult($this->_setBuilder());
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;

			// $hadir 	= 0;
			// $sakit 	= 0;
			// $izin  	= 0;
			// $alpa  	= 0;

		$laki_laki 	= array();
		$perempuan 	= array();

		foreach ($query as $field) {
			$start++;
			$row 	= array();
			$row[]  = $no++;
			$row[]	= $this->include->null($field->no_induk);
			$row[]	= $field->full_name;
			$row[]	= $field->gender == 'L' ? 'Laki-Laki' : 'Perempuan';

			if ($field->gender == 'L') {
				$laki_laki[] = $field->user_id;
			} elseif ($field->gender == 'P') {
				$perempuan[] = $field->user_id;
			}

			foreach ($this->include->opsiPresensi() as $key => $value) {
				$num_rows = $this->_count_presensi($field->user_id, $key);

				$row[] = '<p style="text-align: center;">'. $num_rows  .'</p>';

				// if ($key == 1) {
				// 	$hadir += $num_rows;
				// } elseif ($key == 2) {
				// 	$sakit += $num_rows;
				// } elseif ($key == 3) {
				// 	$izin += $num_rows;
				// } elseif ($key == 4) {
				// 	$alpa += $num_rows;
				// }

			}

			$kehadiran 		= $this->_count_presensi($field->user_id, 1);
			$hitung 		= $kehadiran >= 1 && $weeks >= 1 ? $kehadiran / $weeks * 100 : 0;
			$persentase 	= intval($hitung) >= 100 ? 100 : intval($hitung);

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
			$progress .= '<div class="progress-bar '. $progress_bar .'" role="progressbar" aria-valuenow="'. $persentase .'" aria-valuemin="0" aria-valuemax="100" style="width: '. $persentase .'%">';
			$progress .= '<span class="sr-only">'. $persentase .'% Complete</span>';
			$progress .= '</div>';
			$progress .= '</div>';
			
			$row[] = $progress;

			$data[]	= $row;
		}

		return [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder())->num_rows(),
			'data' 				=> $data,
			// 'hadir'				=> $hadir,
			// 'sakit'				=> $sakit,
			// 'izin'				=> $izin,
			// 'alpa'				=> $alpa,
			'jml_siswa'			=> count($data),
			'laki_laki'			=> count($laki_laki),
			'perempuan'			=> count($perempuan),
		];

	}

	private function _count_presensi($id_user, $status)
	{
		$this->db->where('md5(id_jadwal_pelajaran)', $this->input->post('id_jadwal_pelajaran'));
		$this->db->where('id_user', $id_user);
		$this->db->where('semester', $this->input->post('semester'));
		$this->db->where('status', $status);
		$this->db->where('delete_at', NULL);

		if ($this->input->post('tanggal')) {
			$this->db->where('tanggal', $this->input->post('tanggal'));
		}

		if ($this->input->post('bulan')) {
			$this->db->where('MONTH(tanggal)', $this->input->post('bulan'));
		}

		if ($this->input->post('tgl_awal') && $this->input->post('tgl_akhir')) {
		    $this->db->where('DATE(tanggal) between "' . $this->input->post('tgl_awal') . '" and "' . $this->input->post('tgl_akhir') . '"');
		} elseif ($this->input->post('tgl_awal')) {
		    $this->db->where('DATE(tanggal)', $this->input->post('tgl_awal'));
		} elseif ($this->input->post('tgl_akhir')) {
		    $this->db->where('DATE(tanggal)', $this->input->post('tgl_akhir'));
		}

		return $this->db->get('presensi')->num_rows();
	}

}

/* End of file Rekap_presensi_siswa_model.php */
/* Location: ./application/models/Rekap_presensi_siswa_model.php */