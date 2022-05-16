<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Years extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		# Tahun Pelajaran
		logged_in();
		user_access();

		$this->load->model('Tapel_model', 'tapel');

	}

	private $folder 		= 'Master';
	private $title 			= 'Tahun Pelajaran';
	private $table 			= 'tahun_pelajaran';
	private $primaryKey		= 'md5(tahun_pelajaran_id)';

	public function index()
	{
		$data = [
			'folder'	=> $this->folder,
			'title' 	=> $this->title,
		];

		$this->include->content('master/index_tapel', $data);
	}

	public function showDataTables()
	{
		$data = $this->tapel->getDataTables();
		echo json_encode($data);
	}

	private function _getData($id)
	{
		return $this->db->get_where($this->table, [$this->primaryKey => $id])->row();
	}

	public function saveData()
	{
		$id 	= $this->input->post('tahun_pelajaran_id');
		$query 	= $this->_getData($id);

		if (@$query) {
			$tapel = $query->tahun_pelajaran != $this->input->post('tahun_pelajaran') ? "|is_unique[tahun_pelajaran.tahun_pelajaran]" : "";
		} else {
			$tapel = '|is_unique[tahun_pelajaran.tahun_pelajaran]';
		}

		$this->form_validation->set_error_delimiters('', '');
	    $this->form_validation->set_rules('tahun_pelajaran', 'Tahun Pelajaran', 'trim|required|numeric' . $tapel);

		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('numeric', '{field} harus berisi angka');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');

		if ($this->form_validation->run() == FALSE) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'tahun_pelajaran'   => form_error('tahun_pelajaran'),
			    )
			];

		} else {
			// $data = array(
			// 	'tahun_pelajaran' 	=> htmlspecialchars($this->input->post('tahun_pelajaran')),
			// );

			if (@$query) {
				$data['tahun_pelajaran'] = htmlspecialchars($this->input->post('tahun_pelajaran'));
				
				$this->db->update($this->table, $data, [$this->primaryKey => $id]);
				if ($this->db->affected_rows()) {
					$output['message'] = 'Berhasil Mengubah ' . $this->title;
				}
			} else {
				$first  = $this->input->post('tahun_pelajaran');
				$last 	= $first + 1;
				$tahun_pelajaran = $first .'/'. $last;

				$data['tahun_pelajaran'] = $tahun_pelajaran;
				$data['is_aktif'] 	= 'N';

				$this->db->insert($this->table, $data);
				$output['message'] 	= 'Berhasil Menambah ' . $this->title;
			}

			$output['status'] = TRUE;
		}

		echo json_encode($output);
	}

	public function deleteData($id = NULL)
	{
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		// $this->db->delete($this->table, [$this->primaryKey => $id]);
		
		$this->db->update($this->table, [
			'is_aktif' 	=> NULL,
			'delete_at' => date('Y-m-d H:i:s'),
		], [$this->primaryKey => $id]);

		$output = array(
			'status' 	=> TRUE,
			'message' 	=> 'Berhasil Menghapus ' . $this->title,
		);

		echo json_encode($output);
	}

	public function changeStatus($id = NULL)
	{
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		if (@$query->is_aktif == 'Y') {
			$this->db->update($this->table, [
				'semester' => NULL,	
				'is_aktif' => 'N',
				'tanggal_mulai' => NULL,
				'tanggal_selesai' => NULL,
			], [$this->primaryKey => $id]);
		} else {
			$this->db->update($this->table, [
				'semester' => NULL,
				'is_aktif' => 'N'
			], ['is_aktif' => 'Y']);
			$this->db->update($this->table, ['is_aktif' => 'Y'], [$this->primaryKey => $id]);
		}

		if ($this->db->affected_rows()) {
			$query = $this->_getData($id);
			if ($query->is_aktif == 'Y') {
				$output['message'] = 'Berhasil Mengaktifkan Tahun Pelajaran';
			} else {
				$output['message'] = 'Berhasil Menonaktifkan Tahun Pelajaran';
			}
		}


		$output['status'] 	= TRUE;
		echo json_encode($output);
	}

	public function changeSemester($id = NULL)
	{
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		$data['semester'] = $this->input->post('semester') ? $this->input->post('semester') : NULL;

		$get = $this->tapel->get_tanggal_semester($query->tahun_pelajaran_id, $this->input->post('semester'));

		if ($get) {
			$data['tanggal_mulai'] 		=  $get['tanggal_mulai'];
			$data['tanggal_selesai'] 	=  $get['tanggal_selesai'];
		} else {
			$data['tanggal_mulai'] 		=  NULL;
			$data['tanggal_selesai'] 	=  NULL;
		}

		$this->db->update($this->table, $data, [$this->primaryKey => $id]);
		if ($this->input->post('semester')) {
			$output['message'] = 'Berhasil Mengubah Semester';
		}
		$output['status'] = TRUE;
		echo json_encode($output);
	}

	public function addData()
	{
		// Revisi 16/09/2021 (Pak MT)
		
		if (!$this->input->post('tahun_pelajaran_id')) {
			show_404();
		}

		$query = $this->db->order_by('tahun_pelajaran_id', 'desc')->get('tahun_pelajaran', 1)->row();

		$tahun_pelajaran = explode('/', $query->tahun_pelajaran);

		$first 	= $tahun_pelajaran[0] + 1;
		$last 	= $tahun_pelajaran[1] + 1;

		$data = array(
			'tahun_pelajaran' 	=> $first .'/'. $last,
			'is_aktif'			=> 'N', 
		);

		$this->db->insert('tahun_pelajaran', $data);

		$output = array(
			'status' 	=> TRUE,
			'message'	=> 'Berhasil Menambah ' . $this->title,
		);

		echo json_encode($output);
	}

	public function changeTanggal($id)
	{
		// Revisi 13/10/2021 (Pak MT)
		
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		if ($this->input->post('tanggal_mulai')) {
			if ($this->input->post('tanggal_selesai')) {

				$tanggal_mulai 		= $this->_substrTanggal($this->input->post('tanggal_mulai'));
				$tanggal_selesai 	= $this->_substrTanggal($this->input->post('tanggal_selesai'));

				if ($tanggal_selesai <= $tanggal_mulai) {
					$output = array(
						'status' 	=> false,
						'message'	=> 'Tanggal Tidak Valid'
					);
				} else {
					$data = array(
						'tanggal_mulai' 	=> $this->input->post('tanggal_mulai'), 
						'tanggal_selesai' 	=> $this->input->post('tanggal_selesai'), 
					);

					$this->_tanggal_semester($query->tahun_pelajaran_id, $query->semester, $this->input->post('tanggal_mulai'), $this->input->post('tanggal_selesai'));

					$output = array(
						'status' 	=> true,
						'message'	=> 'Berhasil Mengatur Tanggal'
					);
				}

			} else {
				$data = array(
					'tanggal_mulai' 	=> $this->input->post('tanggal_mulai'), 
					'tanggal_selesai' 	=> $this->input->post('tanggal_selesai') ? $this->input->post('tanggal_selesai') : NULL, 
				);
				$output = array('status' => true);
			}
			
		} else {
			$data = array(
				'tanggal_mulai' 	=> NULL, 
				'tanggal_selesai' 	=> NULL, 
			);
			$output = array('status' => false);
		}

		if (isset($data)) {
			$this->db->update('tahun_pelajaran', $data, [$this->primaryKey => $id]);
		}

		echo json_encode($output);
	}

	private function _substrTanggal($tanggal)
	{
		$explode 	= explode('-', $tanggal);
		$year 		= $explode[0];
		$month 		= $explode[1];
		$day 		= $explode[2];
		$date 		= $year . $month . $day;
		return intval($date);
	}

	private function _tanggal_semester($itp, $smt, $tgl_1 = null, $tgl_2 = null)
	{
		$tanggal_mulai 		= $tgl_1 ? $tgl_1 : '#';
		$tanggal_selesai 	= $tgl_2 ? $tgl_2 : '#';
		$tanggal_semester 	= $tgl_1 .'/'. $tgl_2;

		$query = $this->tapel->get_tanggal_semester($itp, $smt);

		if ($query) {
			return $this->db->update('tanggal_semester', ['tanggal_semester' => $tanggal_semester], ['id_tanggal_semester' => $query['id_tanggal_semester']]);
		} else {
			$data = array(
				'id_tahun_pelajaran' => $itp,
				'id_semester'		 => $smt,
				'tanggal_semester'	 => $tanggal_semester
			);

			return $this->db->insert('tanggal_semester', $data);
		}

	}

	public function tanggal()
	{
		$tanggal_mulai 		= date('Y-m-d');
		$tanggal_selesai 	= date('Y-m-d', strtotime('+16 week'));

		$from_date 	= new DateTime($tanggal_mulai);
		$to_date 	= new DateTime($tanggal_selesai);

		$tanggal = array();
		for ($date = $from_date; $date <= $to_date; $date->modify('+1 day')) {
			if ('Monday' == $date->format('l')) {
				$tanggal[$date->format('Y-m-d')] = $date->format('Y-m-d');
			}
		}

		$id = array();
		for ($i=1; $i <= 16; $i++) { 
			$id[] = $i;
		}

		$data = array_combine($id, $tanggal);


		echo json_encode($data);
	}

}

/* End of file Years.php */
/* Location: ./application/controllers/master/Years.php */
