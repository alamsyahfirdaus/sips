<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hours extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		# Jam Pelajaran
		logged_in();
		user_access();

	}

	private $folder 		= 'Master';
	private $title 			= 'Jam Pelajaran';
	private $table 			= 'jam_pelajaran';
	private $primaryKey		= 'md5(jam_pelajaran_id)';

	public function index()
	{
		$data = [
			'folder'	=> $this->folder,
			'title' 	=> $this->title,
		];

		$this->include->content('master/index_jampel', $data);
	}

	public function showDataTables()
	{
		$this->load->model('Jampel_model', 'jampel');
		$data = $this->jampel->getDataTables();
		echo json_encode($data);
	}

	private function _getData($id)
	{
		return $this->db->get_where($this->table, [$this->primaryKey => $id])->row();
	}

	public function saveData()
	{
		$id 	= $this->input->post('jam_pelajaran_id');
		$query 	= $this->_getData($id);

		if (@$query) {
			$jam_pelajaran = $query->jam_pelajaran != $this->input->post('jam_pelajaran') ? "|is_unique[jam_pelajaran.jam_pelajaran]" : "";
		} else {
			$jam_pelajaran = '|is_unique[jam_pelajaran.jam_pelajaran]';
		}

		// $hour_check = $this->_hour_check($this->input->post('jam_pelajaran'));

		$this->form_validation->set_error_delimiters('', '');
	    $this->form_validation->set_rules('jam_pelajaran', 'Jam Pelajaran', 'trim|required|min_length[4]|max_length[5]|callback_hour_check' . $jam_pelajaran);

		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('valid_url', '{field} tidak valid');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');
		$this->form_validation->set_message('min_length', '{field} minimal {param} karakter');
		$this->form_validation->set_message('max_length', '{field} maksimal {param} karakter');


		if ($this->form_validation->run() == FALSE) {

			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'jam_pelajaran'   => form_error('jam_pelajaran'),
			    )
			];

		} else {
			$data = array(
				'jam_pelajaran' => htmlspecialchars($this->input->post('jam_pelajaran')),
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

	public function hour_check()
	{
		if ($this->input->post('jam_pelajaran')) {
			$explode = explode(':', $this->input->post('jam_pelajaran'));
			$jam 	 = $explode[0];	
			$menit 	 = $explode[1];
			$jampel  = $jam . $menit;
			$min 	 = '0700';
			$max 	 = '1200';

			if ($jampel < $min) {
				$this->form_validation->set_message('hour_check', 'Minimal Jam 07:00');
	            return FALSE;
			} elseif ($jampel > $max) {
				$this->form_validation->set_message('hour_check', 'Maksimal Jam 12:00');
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	public function deleteData($id)
	{
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		$this->db->delete($this->table, [$this->primaryKey => $id]);
		
		// $this->db->update($this->table, ['delete_at' => date('Y-m-d H:i:s')], [$this->primaryKey => $id]);

		$output = array(
			'status' 	=> TRUE,
			'message' 	=> 'Berhasil Menghapus ' . $this->title,
		);

		echo json_encode($output);
	}

}

/* End of file Hours.php */
/* Location: ./application/controllers/master/Hours.php */
