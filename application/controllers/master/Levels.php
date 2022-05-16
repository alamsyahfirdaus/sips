<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Levels extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		# Tingkat Kelas
		logged_in();
		user_access();

	}

	private $folder 		= 'Master';
	private $title 			= 'Tingkat Kelas';
	private $table 			= 'tingkat_kelas';
	private $primaryKey		= 'md5(tingkat_kelas_id)';

	public function index()
	{
		$data = [
			'folder'	=> $this->folder,
			'title' 	=> $this->title,
		];

		$this->include->content('master/index_tingkat_kelas', $data);
	}

	public function showDataTables()
	{
		$this->load->model('Tingkat_model', 'tingkat');
		$data = $this->tingkat->getDataTables();
		echo json_encode($data);
	}

	private function _getData($id)
	{
		return $this->db->get_where($this->table, [$this->primaryKey => $id])->row();
	}

	public function saveData()
	{
		$id 	= $this->input->post('tingkat_kelas_id');
		$query 	= $this->_getData($id);

		if (@$query) {
			$tingkat_kelas = $query->tingkat_kelas != $this->input->post('tingkat_kelas') ? "|is_unique[tingkat_kelas.tingkat_kelas]" : "";
		} else {
			$tingkat_kelas = '|is_unique[tingkat_kelas.tingkat_kelas]';
		}

		$this->form_validation->set_error_delimiters('', '');
	    $this->form_validation->set_rules('tingkat_kelas', 'Tingkat Kelas', 'trim|required' . $tingkat_kelas);
		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');

		if ($this->form_validation->run() == FALSE) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'tingkat_kelas'   => form_error('tingkat_kelas'),
			    )
			];

		} else {
			$data = array('tingkat_kelas' => htmlspecialchars(ucwords($this->input->post('tingkat_kelas'))));

			if (@$query) {
				$this->db->update($this->table, $data, [$this->primaryKey => $id]);
				if ($this->db->affected_rows()) {
					$output['message'] = 'Berhasil Mengubah ' . $this->title;
				}
			} else {
				$this->db->insert($this->table, $data);
				$output['message'] = 'Berhasil Menambah ' . $this->title;
			}

			$output['status'] = TRUE;
		}

		echo json_encode($output);
	}

	// public function deleteData($id)
	// {
	// 	$query = $this->_getData($id);

	// 	if (!$query) {
	// 		show_404();
	// 	}

	// 	$this->db->delete($this->table, [$this->primaryKey => $id]);

	// 	$output = array(
	// 		'status' 	=> TRUE,
	// 		'message' 	=> 'Berhasil Menghapus ' . $this->title,
	// 	);

	// 	echo json_encode($output);
	// }
}

/* End of file Levels.php */
/* Location: ./application/controllers/master/Levels.php */
