<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Homeroom extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		# Wali Kelas
		logged_in();
		if ($this->session->user_type_id != 1) {
			redirect(site_url());
		}
	}

	private $folder 		= 'Master';
	private $title 			= 'Wali Kelas';
	private $table 			= 'wali_kelas';
	private $primaryKey		= 'md5(wali_kelas_id)';

	public function index()
	{
		$data = [
			'folder' 	=> $this->folder,
			'title' 	=> $this->title,
			'tapel'		=> $this->db->get_where('tahun_pelajaran', ['is_aktif' => 'Y'])->row(),
		];

		$this->include->content('master/index_wali_kelas', $data);
	}

	public function showDataTables()
	{
		$this->load->model('Wakel_model', 'wakel');
		$data = $this->wakel->getDataTables();
		echo json_encode($data);
	}

	private function _getData($id = NULL)
	{
		return $this->db->get_where($this->table, [$this->primaryKey => $id])->row();
	}

	public function deleteData($id)
	{
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		$this->db->delete($this->table, [$this->primaryKey => $id]);

		$output = array(
			'status' 	=> TRUE,
			'message' 	=> 'Berhasil Menghapus ' . $this->title,
		);

		echo json_encode($output);
	}

	public function checkKelas($id_tahun_pelajaran = NULL)
	{
		$kelas 		= $this->db->count_all_results('kelas');
		$wali_kelas = $this->db->get_where($this->table, ['id_tahun_pelajaran' => $id_tahun_pelajaran])->num_rows();

		if (!$id_tahun_pelajaran) {
			$button = FALSE;
		} else {
			$button = $kelas != $wali_kelas ? TRUE : FALSE;
		}

        $output = array(
        	'status' 	=> TRUE,
        	'button'	=> $button, 
        );

        echo json_encode($output);

	}

	public function generateWakel($id_tahun_pelajaran = NULL)
	{
		foreach ($this->db->get_where($this->table, ['id_tahun_pelajaran' => $id_tahun_pelajaran])->result() as $row) {
			$kelas_id[] = $row->id_kelas;
		}

		foreach ($this->db->where_not_in('kelas_id', @$kelas_id)->get('kelas')->result() as $row) {
			$data[] = array(
				'id_tahun_pelajaran' => $id_tahun_pelajaran,
				'id_kelas'	=> $row->kelas_id, 
			);
		}

		$this->db->insert_batch($this->table, $data);

		$output = array(
			'status' 	=> TRUE,
			'message'	=> 'Berhasil Menampilkan Kelas',
		);

		echo json_encode($output);
	}

	public function changeWakel()
	{
		$id 	= $this->input->post('wali_kelas_id');
		$query 	= $this->db->get_where($this->table, ['wali_kelas_id' => $id])->row();

		if (!$query) {
			show_404();
		}

		$data = array('id_user' => $this->input->post('id_user') ? $this->input->post('id_user') : NULL);
		$this->db->update($this->table, $data, ['wali_kelas_id' => $id]);

		if ($this->input->post('id_user')) {
			$output['message'] = 'Berhasil Mengatur Wali Kelas';
		}

		$output['status'] = TRUE;

		echo json_encode($output);
	}

}

/* End of file Homeroom.php */
/* Location: ./application/controllers/master/Homeroom.php */
