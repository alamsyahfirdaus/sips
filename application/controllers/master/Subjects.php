<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subjects extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		# Mata Pelajaran
		logged_in();
		user_access();

	}

	private $folder 		= 'Master';
	private $title 			= 'Mata Pelajaran';
	private $table 			= 'mata_pelajaran';
	private $primaryKey		= 'md5(mapel_id)';

	public function index()
	{
		$data = [
			'folder'	=> $this->folder,
			'title' 	=> $this->title,
		];

		$this->include->content('master/index_mapel', $data);
	}

	public function showDataTables()
	{
		$this->load->model('Mapel_model', 'mapel');
		$data = $this->mapel->getDataTables();
		echo json_encode($data);
	}

	private function _getData($id)
	{
		return $this->db->get_where($this->table, [$this->primaryKey => $id])->row();
	}

	public function saveData()
	{
		$id 	= $this->input->post('mapel_id');
		$query 	= $this->_getData($id);

		if (@$query) {
			$kode_mapel = $query->kode_mapel != $this->input->post('kode_mapel') ? "|is_unique[mata_pelajaran.kode_mapel]" : "";
		} else {
			$kode_mapel = '|is_unique[mata_pelajaran.kode_mapel]';
		}

		$this->form_validation->set_error_delimiters('', '');
	    $this->form_validation->set_rules('kode_mapel', 'Kode Mapel', 'trim|alpha_numeric_spaces' . $kode_mapel);
	    $this->form_validation->set_rules('nama_mapel', 'Mata Pelajaran', 'trim|required|alpha_numeric_spaces');


		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('alpha_numeric_spaces', '{field} tidak valid');
		$this->form_validation->set_message('valid_url', '{field} tidak valid');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');

		if ($this->form_validation->run() == FALSE) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'kode_mapel'   => form_error('kode_mapel'),
			        'nama_mapel'   => form_error('nama_mapel'),
			    )
			];

		} else {
			$data = array(
				'kode_mapel' 	=> $this->input->post('kode_mapel') ? htmlspecialchars(strtoupper($this->input->post('kode_mapel'))) : NULL,
				'nama_mapel'	=> htmlspecialchars(ucwords($this->input->post('nama_mapel')))
			);

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

	public function deleteData($id)
	{
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		// $this->db->delete($this->table, [$this->primaryKey => $id]);
		
		$this->db->update($this->table, ['delete_at' => date('Y-m-d H:i:s')], [$this->primaryKey => $id]);

		$output = array(
			'status' 	=> TRUE,
			'message' 	=> 'Berhasil Menghapus ' . $this->title,
		);

		echo json_encode($output);
	}
}

/* End of file Subjects.php */
/* Location: ./application/controllers/master/Subjects.php */
