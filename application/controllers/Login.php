<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

	}

	public function index()
	{	
		logged_out();
		
		$data = array(
			'slider' 	=> $this->_getSlider(),
			'logo'		=> $this->_getLogo(),
		);

		$this->form_validation->set_rules('no_induk', '', 'trim|required');
		$this->form_validation->set_rules('password', '', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('auth/index_login', $data);
		} else {
			$this->_login();
		}
	}

	private function _login()
	{
		$no_induk 	= $this->_get_data([
			'no_induk' 	=> $this->input->post('no_induk'),
			'password' 	=> sha1($this->input->post('password')),
			'delete_at' => NULL,
		]);

		$email 		= $this->_get_data([
			'email' 	=> $this->input->post('no_induk'),
			'password' 	=> sha1($this->input->post('password')),
			'delete_at' => NULL,
		]);

		if ($no_induk) {
			$query 	= $no_induk;
		} elseif ($email) {
			$query 	= $email;
		} else {
			$query 	= NULL;
		}

		if ($query) {

			if ($query->user_type_id == 3 && $this->_checkSiswa($query->user_id)) {

				$output = array(
					'status' 	=> FALSE,
					'message' 	=> 'Login Gagal',
				);

			} else {
				$this->db->update('user', ['last_active' => date('Y-m-d H:i:s')], ['user_id' => $query->user_id]);

				$this->session->set_userdata([
					'user_id' 		=> $query->user_id,
					'user_type_id' 	=> $query->user_type_id
				]);

				$output = array(
					'status' 	=> TRUE,
					'message'	=> 'Login Berhasil Sebagai ' . $query->type_name
				);
			}
		} else {
			$output = array(
				'status' 	=> FALSE,
				'message' 	=> 'Username dan Password tidak sesuai',
			);
		}

		echo json_encode($output);
	}

	private function _getSlider()
	{
		$this->db->where('is_aktif', 'Y');
		$this->db->order_by('sort', 'asc');
		return $this->db->get('image_slider');
	}

	private function _getLogo()
	{
		$query = $this->db->get_where('image_slider', ['id_slider' => 1])->row();
		return base_url(IMAGE . $this->include->image(@$query->gambar));
	}

	private function _get_data($where)
	{
		$this->db->join('user_type', 'user_type.user_type_id = user.user_type_id', 'left');
		$this->db->where($where);
		return $this->db->get('user')->row();
	}

	private function _checkSiswa($user_id)
	{
		$this->load->model('Siswa_model', 'siswa');
		$query = $this->siswa->getData(md5($user_id));
		if (@$query) {
			return @$query->id_kelas && $query->is_aktif == 1 ? FALSE : TRUE;
		} else {
			return FALSE;
		}
	}

	public function recovery()
	{
		logged_out();

		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('auth/forgot_password', ['logo' => $this->_getLogo()]);
		} else {
			$this->_forgot_password();
		}
		
	}

	private function _forgot_password()
	{
		$query = $this->_get_data([
			'email' 	=> $this->input->post('email'),
			'delete_at' => NULL,
		]);

		if ($query) {

			$email = [
				'to' 		=> $query->email,
				'subject' 	=> 'Reset Password',
				'message' 	=> 'Hi, ' . $query->full_name . '. <br><a href="' . site_url('reset/' . md5($query->user_id) . '/' . md5($query->email)) . '">Reset Password</a>',
			];

			$send_email = $this->_send_email($email);

			if ($send_email) {
				$return = [
					'type' 		=> '#00A65A', #alert-success
					'message' 	=> 'Silakan Cek Email Anda!'
				];
			} else {
				$return = [
					'type' 		=> '#DC3545', #alert-danger
					'message' 	=> 'Reset Password Gagal!'
				];
			}

			$return['status'] = TRUE;

		} else {
			$return = [
				'status' 	=> FALSE,
				'errors'	=> array('email' => 'Email belum terdaftar')
			];
		}

		echo json_encode($return);

	}

	private function _send_email($data)
	{
		$website 	= WEBSITE .' '. TITLE;
		$smtp_host 	= SMTP_HOST;
		$smtp_user 	= SMTP_USER;
		$smtp_pass 	= SMTP_PASS;

		$config = [
			'protocol' 	=> 'smtp', 
			'smtp_host' => $smtp_host,
			'smtp_user' => $smtp_user,
			'smtp_pass' => $smtp_pass,
			'smtp_port' => '465',
			'mailtype'	=> 'html',
			'chaset'	=> 'utf-8',
			'newline'	=> "\r\n"
		];

		$this->load->library('email', $config);
		$this->email->initialize($config);
		
		$this->email->from($smtp_user, $website);
		$this->email->to($data['to']);
		$this->email->subject($data['subject']);
		$this->email->message($data['message']);

		// return $this->email->send() ? TRUE : show_error($this->email->print_debugger());
		return $this->email->send() ? TRUE : FALSE;
	}

	public function reset($user_id = NULL, $email = NULL)
	{
		$where = [
			'md5(user_id)' 	=> $user_id,
			'md5(email)'	=> $email,
			'delete_at'		=> NULL,
		];

		$query = $this->_get_data($where);

		if ($query) {
			$data = array(
				'user_id' 	=> sha1($query->user_id),
				'logo'		=> $this->_getLogo(),
			);
			$this->load->view('auth/reset_password', $data);
		} else {
			redirect('login/recovery');
		}

	}

	public function update()
	{
		# Change Password

		$this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('password2', 'Konfirmasi Password', 'trim|required|min_length[8]|matches[password1]');

		if ($this->form_validation->run() == FALSE) {
			redirect('login/recovery');
		} else {
			$user_id 	= $this->input->post('passconf');
			$password 	= $this->input->post('password1');
			$this->db->update('user', ['password' => sha1($password)], ['sha1(user_id)' => $user_id]);

			$return = [
				'status' 	=> TRUE,
				'message'	=> 'Silakan Login!'
			];

			echo json_encode($return);
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}


	# DASHBOARD // FRONT END // LANDING PAGE
	
	public function dashboard()
	{
		logged_out();
		
		$data = array(
			'user_type' => $this->db->get('user_type')->result(), 
		);

		$this->load->view('content/landing_page', $data);
	}

	# LOGIN SEBAGAI
	
	public function role()
	{
		logged_in();

		$query = $this->_get_data(['user_id' => $this->session->user_id]);

		if ($query->user_type_id != 3) {

			if ($this->input->server('REQUEST_METHOD') == 'POST') {

				$user_type = $this->db->get_where('user_type', ['md5(user_type_id)' => $this->input->post('user_type_id')])->row();

				if (isset($user_type->user_type_id)) {
					$this->session->unset_userdata('user_type_id');
					$this->session->set_userdata('user_type_id', $user_type->user_type_id);
				}

				redirect('home');

			} else {
				$guru_piket = $this->db->get_where('guru_piket', ['id_user' => $query->user_id])->result();

				if (count($guru_piket) > 0) {

					$user_type_id = array($this->session->user_type_id, 2, 4);

					$data = array(
						'logo' => $this->_getLogo(),
						'role' => $this->db->where_in('user_type_id', array_unique($user_type_id))->get('user_type')->result(),
					);

					$this->load->view('auth/index_role', $data);

				} else {
					redirect('home');
				}
			}
		} else {
			redirect('home');
		}

	}

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */
