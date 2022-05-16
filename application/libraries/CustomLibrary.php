<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomLibrary
{
	protected $ci;

	public function __construct()
	{
        $this->ci =& get_instance();
        date_default_timezone_set('Asia/Jakarta');
	}

	public function content($content, $data = NULL)
	{
		$section = array('content' => $this->ci->load->view('content/' . $content, $data, TRUE));
		return $this->ci->load->view('section/page', $section);
	}

	public function topnav($content, $data = NULL)
	{
		$section = array('content' => $this->ci->load->view('content/' . $content, $data, TRUE));
		return $this->ci->load->view('section/page_topnav', $section);
	}

	# DataTables

	public function setDataTables($col_order, $col_search, $order_by)
	{
		$i = 0;
		foreach ($col_search as $row) {
			if(@$_POST['search']['value']) {

				if($i === 0) {
					$this->ci->db->group_start();
					$this->ci->db->like($row, $_POST['search']['value']);
				} else {
					$this->ci->db->or_like($row, $_POST['search']['value']);
				}

				if(count($col_search) - 1 == $i)
					$this->ci->db->group_end();
			}
			$i++;
		}
		if(@$_POST['order']) {
			$this->ci->db->order_by($col_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if(@$order_by) {
			$this->ci->db->order_by(key($order_by), $order_by[key($order_by)]);
		}
	}

	private function _getPaging()
	{
		if($this->ci->input->post('length') != -1)
		$this->ci->db->limit($this->ci->input->post('length'), $this->ci->input->post('start'));
	}

	private $resultSet;

	public function getResult($bulider)
	{
		$this->_getPaging();
		$this->resultSet = $bulider;
		return $this->ci->db->get()->result();
	}

	#End DataTables

	public function datetime($date)
	{
	    if ($date) {
	        $datetime = $date;
	    } else {
	    	return '-';
	    }

	    $moths = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

	    $year 		= substr($datetime, 0, 4);
	    $month 		= substr($datetime, 5, 2);
	    $date  	 	= substr($datetime, 8, 2);
	    $hour   	= substr($datetime, 11, 2);
	    $minute   	= substr($datetime, 14, 2);
	    $second   	= substr($datetime, 17, 2);
	    $substr	= substr($date, 0, 1) == 0 ? substr($date, 1) : $date;

	    $result 	= $substr . " " . $moths[(int) $month - 1] . " " . $year . " " . $hour . ":" . $minute . ":" . $second;
	    return ($result);
	}

	public function date($datetime)
	{
		if ($datetime) {
		    $date = $datetime;
		} else {
			return '-';
		}

	    $moths = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

	    $year 	= substr($date, 0, 4);
	    $moth 	= substr($date, 5, 2);
	    $date 	= substr($date, 8, 2);

	    $substr	= substr($date, 0, 1) == 0 ? substr($date, 1) : $date;

	    $result = $substr . " " . $moths[(int) $moth - 1] . " " . $year;
	    return ($result);
	}

	public function null($value)
	{
		return $value ? $value : '-';
	}

	public function image($image)
	{
		return $image ? $image : 'blank.png';
	}

	public function clock($clock)
	{
		return strlen($clock) == 4 ? '0' . $clock : $clock;
	}

	public function days($day)
	{
		switch ($day) {
	        case '0':
	            return "Minggu";
	            break;
	        case '1':
	            return "Senin";
	            break;
	        case '2':
	            return "Selasa";
	            break;
	        case '3':
	            return "Rabu";
	            break;
	        case '4':
	            return "Kamis";
	            break;
	        case '5':
	            return "Jumat";
	            break;
	        case '6':
	            return "Sabtu";
	            break;
	        default:
	            return "-";
	            break;
	    }

	}

	public function moths()
	{
	    return array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	}

	public function presensi($status)
	{
		switch ($status) {
	        case '1':
	            return "Hadir";
	            break;
	        case '2':
	            return "Sakit";
	            break;
	        case '3':
	            return "Izin";
	            break;
	        case '4':
	            return "Tanpa Keterangan";
	            break;
	        default:
	            return "-";
	            break;
	    }
	}

	public function semester($smt)
	{
		switch ($smt) {
	        case '1':
	            return "Ganjil";
	            break;
	        case '2':
	            return "Genap";
	            break;
	        default:
	            return "-";
	            break;
	    }
	}

	public function gender()
	{
		return array(
			'L' => 'Laki-Laki', 
			'P' => 'Perempuan'
		);
	}

	public function agama()
	{
		return array('Islam', 'Kristen', 'Hindu', 'Buddha', 'Konghucu');
	}

	public function status_guru()
	{
		return array('GTY/PTY', 'Guru Bantu', 'Guru Honorer');
	}

	public function opsiPresensi()
	{
		return array(
			'1' => 'Hadir', 	
			'2' => 'Sakit', 
			'3' => 'Izin',
			'4' => 'Tanpa Keterangan',
		);
	}

	public function bulanSmt($id_semester)
	{
		if ($id_semester == 1) {
			return array("Juli", "Agustus", "September", "Oktober", "November", "Desember");
		} elseif ($id_semester == 2) {
			return array("Januari", "Februari", "Maret", "April", "Mei", "Juni");
		}
	}

	public function intBulanSmt($id_semester, $month)
	{
		if ($id_semester == 1) {
			switch ($month) {
		        case '1':
		            return '7';
		            break;
		        case '2':
		            return '8';
		            break;
		        case '3':
		            return '9';
		            break;
		        case '4':
		            return '10';
		            break;
		        case '5':
		            return '11';
		            break;
	            case '6':
	                return '12';
	                break;
		        default:
		            return '0';
		            break;
		    }
		} elseif ($id_semester == 2) {
			return $month;
		}
	}

	public function statusSiswa($status = null)
	{
		if ($status) {
			
			if ($status == 1) {
				$status_name = 'Aktif';
			} elseif ($status == 2) {
				$status_name = 'Tidak Aktif';
			} elseif ($status == 3) {
				$status_name = 'Lulus';
			} elseif ($status == 4) {
				$status = 'Drop Out';
			} else {
				$status_name = '-';
			}

			return $status_name;

		} else {
			return array(
				'1' => 'Aktif', 
				'2' => 'Tidak Aktif', 
				'3' => 'Lulus', 
				'4' => 'Droup Out'
			);

		}
	}

}

/* End of file CustomLibrary.php */
/* Location: ./application/libraries/CustomLibrary.php */
