<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Other extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();
		user_access();

	}

	# PENGUMUMAN

	private $folder 		= 'Pengaturan';
	private $title 			= 'Lainnya';
	private $table 			= 'pengaturan';
	private $primaryKey		= 'md5(id_pengaturan)';

	public function index()
	{
		$data = [
			'folder'	=> $this->folder,
			'title' 	=> $this->title,
		];

		$this->include->content('setting/index_pengaturan', $data);
	}

	public function showDataTables()
	{
		$this->load->model('Pengaturan_model', 'pengaturan');
		$data = $this->pengaturan->getDataTables();
		echo json_encode($data);
	}

	private function _getData($id)
	{
		return $this->db->get_where($this->table, [$this->primaryKey => $id])->row();
	}

	public function edit($id = NULL)
	{
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		$data = [
			'folder'	=> $this->folder,
			'title' 	=> $this->title,
			'header' 	=> 'Edit',
			'row'		=> $query,
		];

		if (!$this->input->post('nama_pengaturan')) {
			$this->include->content('setting/edit_pengaturan', $data);
		} else {
			$this->db->update($this->table, ['pengaturan' => $this->input->post('pengaturan')], [$this->primaryKey => $id]);
			if ($this->db->affected_rows()) {
				$this->session->set_flashdata('success', 'Berhasil Mengubah ' . $this->folder);
			}
			redirect('setting/other');
		}

	}

}

/* End of file Other.php */
/* Location: ./application/controllers/setting/Other.php */
