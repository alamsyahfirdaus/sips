<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Announcement extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();
		user_access();

	}

	# PENGUMUMAN

	private $folder 		= 'Pengaturan';
	private $title 			= 'Pengumuman';
	private $table 			= 'pengumuman';
	private $primaryKey		= 'md5(id_pengumuman)';

	public function index()
	{
		$data = [
			'folder'	=> $this->folder,
			'title' 	=> $this->title,
		];

		$this->include->content('setting/index_pengumuman', $data);
	}

	public function showDataTables()
	{
		$this->load->model('Pengumuman_model', 'pengumuman');
		$data = $this->pengumuman->getDataTables();
		echo json_encode($data);
	}

	private function _getData($id)
	{
		return $this->db->get_where($this->table, [$this->primaryKey => $id])->row();
	}

	public function changeStatus($id = NULL)
	{
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		$is_aktif = $query->is_aktif == 'Y' ? 'N' : 'Y';
		$this->db->update($this->table, ['is_aktif' => $is_aktif], [$this->primaryKey => $id]);
		if ($query->is_aktif == 'N') {
			$output['message'] = 'Berhasil Mengaktifkan Pengumuman';
		} else {
			$output['message'] = 'Berhasil Menonaktifkan Pengumuman';
		}
		$output['status'] 	= TRUE;
		echo json_encode($output);
	}

	public function deleteData($id)
	{
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		if (@$query->gambar) {
			unlink(IMAGE . $query->gambar);
		}

		$this->db->delete($this->table, [$this->primaryKey => $id]);

		$output = array(
			'status' 	=> TRUE,
			'message' 	=> 'Berhasil Menghapus ' . $this->title,
		);

		echo json_encode($output);
	}

	public function addedit($id = NULL)
	{
		$query = $this->_getData($id);

		$jenis_pengguna = $this->db->where_not_in('user_type_id', 1)->get('user_type')->result();

		$data = [
			'folder'	=> $this->folder,
			'title' 	=> $this->title,
			'header' 	=> @$query ? 'Edit' : 'Tambah',
			'row'		=> @$query,
			'user_type'	=> $jenis_pengguna,
		];

		if (!$this->input->post('judul1')) {
			$this->include->content('setting/addedit_pengumuman', $data);
		} else {
			$this->_save($query);
		}

	}

	private function _do_upload()
	{
        $config['upload_path']   = 	UPLOAD_PATH;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|GIF|JPG|PNG|JPEG|BMP|';
        $config['max_size']    	 = 10000;
        $config['max_width']   	 = 10000;
        $config['max_height']  	 = 10000;
        $config['file_name']     = time();
        $this->upload->initialize($config);
	}

	public function getImage($id = NULL)
	{
		$query 	= $this->_getData($id);

		if ($query) {
			$output['status'] 	= TRUE;
			$output['image']	= @$query->gambar ? TRUE : FALSE;
			$output['url']		= ''. site_url(IMAGE . $this->include->image($query->gambar)) .'';
		} else {
			$output['status'] = FALSE;
		}

		echo json_encode($output);
	}

	public function changeFoto($id = NULL)
	{
		$query 	= $this->_getData($id);

		if (!@$query) {
			show_404();
		}

		$this->_do_upload();

		if ($this->upload->do_upload('foto')) {
		    if (@$query->gambar) {
		        unlink(IMAGE . $query->gambar);
		    }

		    $this->db->update($this->table, ['gambar' => $this->upload->data('file_name')], [$this->primaryKey => $id]);
		}

		$output['status'] = TRUE;
		$output['message'] 	= 'Berhasil Mengubah Gambar';
		echo json_encode($output);

	}

	public function deleteFoto($id = NULL)
	{
		$query 	= $this->_getData($id);

		if (!@$query) {
			show_404();
		}
		
		if (@$query->gambar) {
			unlink(IMAGE . $query->gambar);
		}

		$this->db->update($this->table, ['gambar' => NULL], [$this->primaryKey => $id]);
		$output['status'] 	= TRUE;
		$output['message'] 	= 'Berhasil Menghapus Gambar';
		echo json_encode($output);
	}

	private function _save($query = NULL)
	{
		# Simpan Pengumuman
		
		$data = array(
			'judul' 		=> $this->input->post('judul1'),
			'pengumuman'	=> $this->input->post('pengumuman1') ? $this->input->post('pengumuman1') : NULL,
			'user_type_id'  => $this->input->post('user_type_id1') ? $this->input->post('user_type_id1') : NULL,
		);

		$this->_do_upload();

		if ($this->upload->do_upload('gambar')) {
		    if (@$query->gambar) {
		        unlink(IMAGE . $query->gambar);
		    }

		    $data['gambar'] = $this->upload->data('file_name');
		}

		if (!@$query) {
			$data['tanggal']	= date('Y-m-d');
			$data['is_aktif']	= 'N';
			$this->db->insert($this->table, $data);
			$id = md5($this->db->insert_id());
			$this->session->set_flashdata('success', 'Berhasil Menambah ' . $this->title);
		} else {
			$data['tanggal'] = date('Y-m-d', strtotime($this->input->post('tanggal')));
			$id = md5($query->id_pengumuman);
			$this->db->update($this->table, $data, [$this->primaryKey => $id]);
			if ($this->db->affected_rows()) {
				$this->session->set_flashdata('success', 'Berhasil Mengubah ' . $this->title);
			}
		}
		
		redirect('setting/announcement');
	}

}

/* End of file Announcement.php */
/* Location: ./application/controllers/setting/Announcement.php */
