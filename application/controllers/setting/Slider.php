<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();

		if ($this->session->user_type_id != 1) {
			redirect(site_url());
		}

	}

	public function index()
	{
		$data = [
			'folder'	=> 'Pengaturan',
			'title' 	=> 'Slide Gambar',
		];

		$this->include->content('setting/index_slider', $data);
	}

	public function showImageSlider()
	{
		$this->load->model('Slider_model', 'sm');
		$data = $this->sm->getDataTables();
		echo json_encode($data);

	}

	public function saveSlider()
	{
		$query = $this->db->get_where('image_slider', ['id_slider' => $this->input->post('id')])->row();
		$this->_do_upload();

		if ($this->upload->do_upload('gambar')) {
		    if (@$query->gambar) {
		        unlink(IMAGE . $query->gambar);
		    }

		    $data['gambar'] = $this->upload->data('file_name');
		}

		if (!@$query) {
			$num_rows = $this->db->get_where('image_slider', ['is_aktif' => 'Y'])->num_rows();
			$data['is_aktif'] = $num_rows ? 'N' : 'Y';
			$this->db->insert('image_slider', $data);
			$output['message'] = 'Berhasil Menambah Gambar';
		} else {
			$this->db->update('image_slider', $data, ['id_slider' => $query->id_slider]);
			$output['message'] = 'Berhasil Mengubah Gambar';
		}

		$output['status'] = TRUE;
		echo json_encode($output);
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

	public function deleteSlider($id = NULL)
	{
		$query 	= $this->db->get_where('image_slider', ['md5(id_slider)' => $id])->row();

		if (!@$query) {
			show_404();
		}
		
		if (@$query->gambar) {
			unlink(IMAGE . $query->gambar);
		}

		$this->db->delete('image_slider', ['id_slider' => $query->id_slider]);
		$output['status'] 	= TRUE;
		$output['message'] 	= 'Berhasil Menghapus Gambar';
		echo json_encode($output);
	}

	public function changeStatus($id = NULL)
	{
		$query 	= $this->db->get_where('image_slider', ['md5(id_slider)' => $id])->row();

		if (!$query) {
			show_404();
		}

		$is_aktif = $query->is_aktif == 'Y' ? 'N' : 'Y';

		$data['is_aktif'] = $query->is_aktif == 'Y' ? 'N' : 'Y';

		if ($query->is_aktif == 'N') {
			$data['sort'] = time();
		} else {
			$data['sort'] = NULL;
		}

		if ($this->db->affected_rows()) {
			$query 	= $this->db->get_where('image_slider', ['md5(id_slider)' => $id])->row();
			if ($query->is_aktif == 'N') {
				$output['message'] = 'Berhasil Mengaktifkan Gambar';
			} else {
				$output['message'] = 'Berhasil Menonaktifkan Gambar';
			}
		}

		$this->db->update('image_slider', $data, ['id_slider' => $query->id_slider]);
		$output['status'] 	= TRUE;
		echo json_encode($output);
	}

}

/* End of file Slider.php */
/* Location: ./application/controllers/setting/Slider.php */
