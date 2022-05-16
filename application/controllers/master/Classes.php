<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classes extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		# Kelas
		logged_in();
		user_access();

	}

	private $folder 		= 'Master';
	private $title 			= 'Kelas';
	private $table 			= 'kelas';
	private $primaryKey		= 'md5(kelas_id)';

	public function index()
	{
		$data = [
			'folder'	=> $this->folder,
			'title' 	=> $this->title,
		];

		$this->include->content('master/index_kelas', $data);
	}

	public function showDataTables()
	{
		$this->load->model('Kelas_model', 'kelas');
		$data = $this->kelas->getDataTables();
		echo json_encode($data);
	}

	private function _getData($id)
	{
		return $this->db->get_where($this->table, [$this->primaryKey => $id])->row();
	}

	// public function saveData()
	// {
	// 	$id 	= $this->input->post('kelas_id');
	// 	$query 	= $this->_getData($id);

	// 	if (@$query) {
	// 		$nama_kelas = $query->nama_kelas != $this->input->post('nama_kelas') ? "|is_unique[kelas.nama_kelas]" : "";
	// 	} else {
	// 		$nama_kelas = '|is_unique[kelas.nama_kelas]';
	// 	}

	// 	$this->form_validation->set_error_delimiters('', '');
	//     $this->form_validation->set_rules('nama_kelas', 'Kelas', 'trim|required' . $nama_kelas);
	//     $this->form_validation->set_rules('id_tingkat_kelas', 'Tingkat Kelas', 'trim|required');


	// 	$this->form_validation->set_message('required', '{field} harus diisi');
	// 	$this->form_validation->set_message('alpha_numeric_spaces', '{field} tidak valid');
	// 	$this->form_validation->set_message('valid_url', '{field} tidak valid');
	// 	$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');

	// 	if ($this->form_validation->run() == FALSE) {
	// 		$output = [
	// 		    'status' => FALSE,
	// 		    'errors' => array(
	// 		        'nama_kelas'   			=> form_error('nama_kelas'),
	// 		        'id_tingkat_kelas'   	=> form_error('id_tingkat_kelas'),
	// 		    )
	// 		];

	// 	} else {
	// 		$data = array(
	// 			'nama_kelas' 		=> htmlspecialchars($this->input->post('nama_kelas')),
	// 			'id_tingkat_kelas'	=> $this->input->post('id_tingkat_kelas'),
	// 		);

	// 		if (@$query) {
	// 			$this->db->update($this->table, $data, [$this->primaryKey => $id]);
	// 			if ($this->db->affected_rows()) {
	// 				$output['message'] = 'Berhasil Mengubah ' . $this->title;
	// 			}
	// 		} else {
	// 			$this->db->insert($this->table, $data);
	// 			$output['message'] = 'Berhasil Menambah ' . $this->title;
	// 		}

	// 		$output['status'] = TRUE;
	// 	}

	// 	echo json_encode($output);
	// }

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

	// Revisi 20/09/2021 (Pak MT)

	public function addData()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_tingkat_kelas', 'Kelas', 'trim|required|alpha_numeric_spaces');
		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('alpha_numeric_spaces', '{field} tidak valid');

		if ($this->form_validation->run() == FALSE) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'id_tingkat_kelas' => form_error('id_tingkat_kelas'),
			    )
			];

		} else {

			$query1 = $this->db->join('tingkat_kelas', 'tingkat_kelas.tingkat_kelas_id = kelas.id_tingkat_kelas', 'left')->get_where('kelas', ['md5(id_tingkat_kelas)' => $this->input->post('id_tingkat_kelas')]);
			$query2 = $this->db->get_where('tingkat_kelas', ['md5(tingkat_kelas_id)' => $this->input->post('id_tingkat_kelas')])->row();

			if (!$query1->num_rows()) {
				$data['nama_kelas'] = $query2->tingkat_kelas;
			} else {
				$alphabet = $this->_nama_kelas($query1->result());
				$data['nama_kelas'] 	= $query2->tingkat_kelas .' - '. $alphabet;
				$data['urutan_kelas'] 	= $query1->num_rows() + 1;
			}

			$data['id_tingkat_kelas'] = $query2->tingkat_kelas_id;
			$this->db->insert($this->table, $data);

			$output = array(
				'status' 	=> TRUE,
				'message'	=> 'Berhasil Menambah ' . $this->title
			);
		}

		echo json_encode($output);

	}

	private function _nama_kelas($bulider)
	{
		$number = 1;
		foreach ($bulider as $key) {
			$nama_kelas = $key->tingkat_kelas .' - '. $this->number_to_alphabet($number);
			$this->db->update($this->table, [
				'nama_kelas' 	=> $nama_kelas,
				'urutan_kelas'	=> $number,
			], ['kelas_id' => $key->kelas_id]);
			$number++;
		}

		$alphabet = count($bulider) + 1;
		return $this->number_to_alphabet($alphabet);
	}

	private function number_to_alphabet($number) {
		$number = intval($number);
	    if ($number <= 0) {
	       return '';
	    }
	    $alphabet = '';
	    while($number != 0) {
	       $p = ($number - 1) % 26;
	       $number = intval(($number - $p) / 26);
	       $alphabet = chr(65 + $p) . $alphabet;
	   }
	   return $alphabet;
	}

	public function sort_nama_kelas($id_tingkat_kelas)
	{
		// Urutkan Nama Kelas
		
		$query = $this->db->join('tingkat_kelas', 'tingkat_kelas.tingkat_kelas_id = kelas.id_tingkat_kelas', 'left')->get_where('kelas', ['md5(id_tingkat_kelas)' => $id_tingkat_kelas])->result();

		if (count($query) > 0) {
			if (count($query) > 1) {
				$this->_nama_kelas($query);
			} else {
				foreach ($query as $key) {
					$this->db->update($this->table, [
						'nama_kelas' 	=> $key->tingkat_kelas,
						'urutan_kelas'	=> NULL,
					], ['kelas_id' => $key->kelas_id]);
				}
			}
			$status = 1;
		} else {
			$status = 0;
		}

		echo json_encode(['status' => $status]);
	}

	// public function getKelas($id_tingkat_kelas, $id_kelas = NULL)
	// {
	// 	// Revisi 16/09/2021 (Pak MT)
		
	// 	$this->form_validation->set_rules('id_tingkat_kelas', 'Tingkat Kelas', 'trim|required');
	// 	$this->form_validation->set_rules('id_kelas', 'Kelas', 'trim|required');

	// 	if ($this->form_validation->run() == FALSE) {
	// 		show_404();
	// 	} else {

	// 		$query = $this->db->get_where('tingkat_kelas', ['tingkat_kelas_id' => $id_tingkat_kelas])->row();

	// 		$select = '<label for="nama_kelas">Kelas</label>';
	// 		$select .= '<select name="nama_kelas" id="nama_kelas" class="form-control">';
	// 		$select .= '<option value="">-- Kelas --</option>';

	// 		if ($query) {

	// 			$kelas = $this->db->get_where('kelas', ['id_tingkat_kelas' => $query->tingkat_kelas_id])->num_rows();

	// 			if ($kelas < 1) {
	// 				$data[$query->tingkat_kelas] = $query->tingkat_kelas . ' (Satu Kelas)';
	// 			} else {
	// 				$number   	= $kelas + 1;
	// 				$alpha 		= $this->number_to_alphabet($number);
	// 				$range 		= $query->tingkat_kelas . ' - ' . $alpha;
					
	// 				$data[$range] = $range;
	// 			}

	// 			foreach ($data as $key => $value) {
	// 				$select .= '<option value="'. $key .'">'. $value .'</option>';
	// 			}

	// 		}

	// 		$select .= '</select>';
	// 		$select .= '<small class="help-block" id="error-nama_kelas"></small>';
	// 		$select .= '<script>$(function() {$("#nama_kelas").select2();});</script>';

	// 		echo json_encode(['kelas' => $select]);

	// 	}

	// }

}

/* End of file Classes.php */
/* Location: ./application/controllers/master/Classes.php */
