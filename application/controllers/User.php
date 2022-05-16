<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();

		if ($this->session->user_type_id == 3) {
			redirect(site_url());
		}

		$this->load->model('Siswa_model', 'siswa');

	}

	private $folder 		= 'Pengguna';
	private $table 			= 'user';
	private $primaryKey		= 'md5(user_id)';

	public function index()
	{
		redirect('home/profile');
	}

	public function administration()
	{
		$admin = $this->db->get_where('user', ['user_type_id' => 1])->num_rows();

		$data = array(
			'folder' 		=> $this->folder, 
			'title' 		=> 'Administrator',
			'user_type_id'	=> 1,
			'jumlah'		=> $admin,
		);
		
		$this->include->content('user/index_admin', $data);
	}

	public function showAdmin()
	{
		$this->load->model('Admin_model', 'admin');
		$data = $this->admin->getDataTables();
		echo json_encode($data);
	}

	public function teacher()
	{
		$data = array(
			'folder' 		=> $this->folder, 
			'title' 		=> 'Guru',
			'user_type_id'	=> 2
		);
		
		$this->include->content('user/index_guru', $data);

	}

	public function showGuru()
	{
		$this->load->model('Guru_model', 'guru');
		$data = $this->guru->getDataTables();
		echo json_encode($data);
	}

	public function student()
	{
		$data = array(
			'folder' 		=> $this->folder, 
			'title' 		=> 'Siswa',
			'user_type_id'	=> 3,
			'tingkat_kelas'	=> $this->db->get('tingkat_kelas')->result(),
			'status'		=> $this->include->statusSiswa(),
		);
		
		$this->include->content('user/index_siswa_r1', $data);
	}

	public function showSiswa()
	{
		$data = $this->siswa->getDataTables();
		echo json_encode($data);
	}

	private function _getData($id)
	{
		$this->db->join('user_type', 'user_type.user_type_id = user.user_type_id', 'left');
		$this->db->where($this->primaryKey, $id);
		return $this->db->get($this->table)->row();
	}

	public function delete($id = NULL)
	{
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		// $this->db->delete($this->table, [$this->primaryKey => $id]);
		
		$this->db->update($this->table, ['delete_at' => date('Y-m-d H:i:s')], [$this->primaryKey => $id]);

		$output = array(
			'status' 	=> TRUE,
			'message' 	=> 'Berhasil Menghapus ' . $query->type_name,
		);

		echo json_encode($output);
	}

	public function detail($id = NULL)
	{
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		$siswa = $this->siswa->getData($id);

		$data = array(
			'folder' 		=> $this->folder, 
			'title' 		=> @$query->type_name,
			'header'		=> 'Detail',
			'row'			=> $query->user_type_id == 3 ? $siswa : $query,
		);

		if ($query->user_type_id == 3) {
			$this->include->content('user/detail_siswa', $data);
		} else {
			$this->include->content('user/detail_user', $data);
		}
		
	}

	public function add($id = NULL)
	{

		$query = $this->db->get_where('user_type', ['md5(user_type_id)' => $id])->row();

		if (!$query) {
			show_404();
		}

		$data = array(
			'folder' 		=> $this->folder, 
			'title' 		=> @$query->type_name,
			'header'		=> 'Tambah',
			'user_type_id'	=> $id,
			// 'agama'			=> 'Islam',
			'kelas'			=> $this->_setKelas(),
		);

		if ($query->user_type_id == 1) {
			# Administrator
			$this->include->content('user/addedit_admin_r1', $data);
		} elseif ($query->user_type_id == 2) {
			# Guru
			$this->include->content('user/addedit_guru', $data);
		} elseif ($query->user_type_id == 3) {
			# Siswa
			$this->include->content('user/addedit_siswa', $data);
		}
	}

	public function edit($id = NULL)
	{
		$query = $this->_getData($id);

		if (!$query) {
			show_404();
		}

		$siswa = $this->siswa->getData($id);

		$data = array(
			'folder' 		=> $this->folder, 
			'title' 		=> @$query->type_name,
			'header'		=> 'Edit',
			'id'			=> $id,
			'row'			=> $query->user_type_id == 3 ? $siswa : $query,
			'agama'			=> @$query->agama ? $query->agama : 'Islam',
			'kelas'			=> $this->_setKelas($siswa->user_id, $siswa->id_kelas),
		);
		
		if ($query->user_type_id == 1) {
			# Administrator
			$this->include->content('user/addedit_admin_r1', $data);
		} elseif ($query->user_type_id == 2) {
			# Guru
			$this->include->content('user/addedit_guru', $data);
		} elseif ($query->user_type_id == 3) {
			# Siswa
			$this->include->content('user/addedit_siswa', $data);
		}
	}

	public function save($id = NULL)
	{
		# Simpan Pengguna
		
		$user_type 	= $this->db->get_where('user_type', ['md5(user_type_id)' => $this->input->post('user_type_id')])->row();
		$query 	  	= $this->_getData($id);

		if (isset($user_type->user_type_id)) {
			$no_induk 	= "|is_unique[user.no_induk]";
			$email 		= "|is_unique[user.email]";
			$phone 		= "|is_unique[user.phone]";

			$user_type_id = $user_type->user_type_id;
			$type_name 	  = $user_type->type_name;
		} elseif (isset($query->user_id)) {
			$no_induk 	= $query->no_induk != $this->input->post('no_induk') ? "|is_unique[user.no_induk]" : "";
			$email 		= $query->email != $this->input->post('email') ? "|is_unique[user.email]" : "";
			$phone 		= $query->phone != $this->input->post('phone') ? "|is_unique[user.phone]" : "";

			$user_type_id = $query->user_type_id;
			$type_name 	  = $query->type_name;
		} else {
			show_404();
		}




		// if (@$user_type || @$query) {

		// 	if (@$user_type) {
		// 		$no_induk 	= "|is_unique[user.no_induk]";
		// 		$email 		= "|is_unique[user.email]";
		// 		$phone 		= "|is_unique[user.phone]";

		// 		$user_type_id = $user_type->user_type_id;
		// 		$type_name 	  = $user_type->type_name;
		// 	} else {
		// 		$no_induk 	= $query->no_induk != $this->input->post('no_induk') ? "|is_unique[user.no_induk]" : "";
		// 		$email 		= $query->email != $this->input->post('email') ? "|is_unique[user.email]" : "";
		// 		$phone 		= $query->phone != $this->input->post('phone') ? "|is_unique[user.phone]" : "";

		// 		$user_type_id = $query->user_type_id;
		// 		$type_name 	  = $query->type_name;
		// 	}
		// } else {
		// 	show_404();
		// }

		$substr 	= substr($this->input->post('phone'), 0, 1) == '-' ? 'No. Handphone tidak valid' : FALSE;
		$field 		= $user_type_id == 3 ? 'Nomor Induk Siswa' : 'NUPTK';
		$required 	= $user_type_id != 1 ? '|required' : '';

		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('no_induk', $field, 'trim|numeric' . $no_induk);
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email' . $email);
		$this->form_validation->set_rules('full_name', 'Nama', 'trim|required');
		$this->form_validation->set_rules('gender', 'Jenis Kelamin', 'trim|alpha' . $required);
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim|alpha_numeric_spaces' . $required);
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim' . $required);
		// $this->form_validation->set_rules('agama', 'Agama', 'trim' . $required);
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim');
	    $this->form_validation->set_rules('phone', 'No. Handphone', 'trim|min_length[11]' . $phone, ['min_length' => 'No. Handphone minimal {param} angka']);
		$this->form_validation->set_rules('password1', 'Password', 'trim|min_length[6]');
		$this->form_validation->set_rules('password2', 'Konfirmasi Password', 'trim|min_length[6]');
		if ($user_type_id == 2) {
			$this->form_validation->set_rules('status_guru', 'Status Kepegawaian', 'trim' . $required);
		}
		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('alpha', '{field} tidak valid');
		$this->form_validation->set_message('numeric', '{field} harus berisi angka');
		$this->form_validation->set_message('alpha_numeric_spaces', '{field} tidak valid');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');
		$this->form_validation->set_message('min_length', '{field} minimal {param} karakter');
		$this->form_validation->set_message('valid_email', '{field} tidak valid');

		if ($this->form_validation->run() == FALSE || $substr) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			    	'no_induk'			=> $this->input->post('no_induk') ? form_error('no_induk') : '',
			        'email'   			=> form_error('email'),
			        'full_name' 		=> form_error('full_name'),
			        'gender' 			=> form_error('gender'),
			        'tempat_lahir' 		=> form_error('tempat_lahir'),
			        'tanggal_lahir' 	=> form_error('tanggal_lahir'),
			        // 'agama' 			=> form_error('agama'),
			        'status_guru' 		=> form_error('status_guru'),
			        'alamat' 			=> form_error('alamat'),
			        'phone'   			=> $substr ? $substr : form_error('phone'),
			        'password1'  		=> form_error('password1'),
			        'password2'  		=> form_error('password2'),
			    )
			];

		} else {
			$data = array(
				'no_induk'		=> $this->input->post('no_induk') ? htmlspecialchars($this->input->post('no_induk')) : NULL,
				'full_name' 	=> htmlspecialchars(ucwords(strtolower($this->input->post('full_name')))),
				'email'			=> $this->input->post('email') ? htmlspecialchars($this->input->post('email')) : NULL,
				'phone'			=> $this->input->post('phone') ? htmlspecialchars($this->input->post('phone')) : NULL,
				'gender'		=> $this->input->post('gender') ? $this->input->post('gender') : NULL,
				'tempat_lahir'	=> $this->input->post('tempat_lahir') ? htmlspecialchars(ucwords(strtolower($this->input->post('tempat_lahir')))) : NULL,
				'tanggal_lahir'	=> $this->input->post('tanggal_lahir') ? date('Y-m-d', strtotime($this->input->post('tanggal_lahir'))) : NULL,
				'agama'			=> $this->input->post('agama') ? $this->input->post('agama') : NULL,
				'user_type_id'  => $user_type_id,
				'alamat'		=> $this->input->post('alamat') ? htmlspecialchars($this->input->post('alamat')) : NULL,
				'status_guru'	=> $this->input->post('status_guru') ? htmlspecialchars($this->input->post('status_guru')) : NULL,
			);

			$this->_do_upload();

			if ($this->upload->do_upload('profile_pic')) {
			    if (@$query->profile_pic) {
			        unlink(IMAGE . $query->profile_pic);
			    }

			    $data['profile_pic'] = $this->upload->data('file_name');
			}

			if (@$user_type) {

				$password = $this->input->post('password1') ? $this->input->post('password1') : date('dmY', strtotime($this->input->post('tanggal_lahir')));

				$data['password'] = sha1($password);

				$data['last_active'] = NULL;
				$data['date_created'] = date('Y-m-d H:i:s');
				$this->db->insert($this->table, $data);
				$user_id = $this->db->insert_id();
				$output['message'] = 'Berhasil Menambah ' . $type_name;
			} else {
				if ($this->input->post('password1')) {
					$data['password'] = sha1($this->input->post('password1'));
				}

				$this->db->update($this->table, $data, [$this->primaryKey => $id]);
				$user_id = $query->user_id;
				$output['message'] = 'Berhasil Mengubah ' . $type_name;
			}

			if ($user_type_id == 3) {
				$output['status'] = $this->_saveSiswa($user_id);
			} else {
				$output['status'] = TRUE;
			}

		}

		echo json_encode($output);
	}

	private function _saveSiswa($id_user)
	{
		$query = $this->db->get_where('siswa', ['id_user' => $id_user])->row();

		if (@$query) {
			// if (!$query->id_kelas && $query->is_aktif == 2 && $this->input->post('id_kelas') != date('Ymd')) {
			// 	$this->db->delete('lulusan', ['id_siswa' => $query->siswa_id]);
			// }

			// $data = [
			// 	'id_kelas' 	=> $this->input->post('id_kelas') != date('Ymd') ? $this->input->post('id_kelas') : NULL,
			// 	'is_aktif'	=> $this->input->post('id_kelas') != date('Ymd') ? 1 : 2,
			// ];
			
			$data = array('id_kelas' => $this->input->post('id_kelas'));

			if ($this->input->post('is_aktif')) {
				$data['is_aktif'] = $this->input->post('is_aktif');
			}

			return $this->db->update('siswa', $data, ['siswa_id' => $query->siswa_id]);
		} else {
			$data = [
				'id_user'	=> $id_user,
				'id_kelas' 	=> $this->input->post('id_kelas'),
				'is_aktif'	=> 1,
			];

			return $this->db->insert('siswa', $data);
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

	private function _setKelas($id_user = NULL, $id_kelas = NULL)
	{
		foreach ($this->db->order_by('id_tingkat_kelas', 'asc')->order_by('urutan_kelas', 'asc')->get('kelas')->result() as $row) {
			$kelas_id[] = $row->kelas_id;
		}

		$select = '<option value="">Kelas</option>';

		if ($id_user && $id_kelas == NULL) {
			foreach (array_merge($kelas_id, [date('Ymd')]) as $key => $value) {
				$query 		= $this->db->get_where('kelas', ['kelas_id' => $value])->row();
				$nama_kelas = @$query ? $query->nama_kelas : 'Lulus';
				$selected 	= $value == date('Ymd') ? 'selected' : '';
				$select .= '<option value="'. $value .'" '. $selected .'>'. $nama_kelas .'</option>';
			}
		} else {
			foreach ($this->mall->get_kelas() as $row) {
				$selected 	= $row->kelas_id == @$id_kelas ? 'selected' : '';
				$select .= '<option value="'. $row->kelas_id .'" '. $selected .'>'. $row->nama_kelas .'</option>';
			}
		}

		return $select;
	}

	public function getImage($id = NULL)
	{
		$query 	= $this->_getData($id);

		if ($query) {
			$output['status'] 		= TRUE;
			$output['profile_pic']	= @$query->profile_pic ? TRUE : FALSE;
			$output['url']			= ''. site_url(IMAGE . $this->include->image($query->profile_pic)) .'';
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
		    if (@$query->profile_pic) {
		        unlink(IMAGE . $query->profile_pic);
		    }

		    $this->db->update($this->table, ['profile_pic' => $this->upload->data('file_name')], [$this->primaryKey => $id]);
		}

		if ($this->input->post('action')) {
			$this->session->set_flashdata('success', 'Berhasil Mengubah Foto');
			redirect($this->input->post('action'));
		} else {
			$output['status'] = TRUE;
			$output['message'] 	= 'Berhasil Mengubah Foto';
			echo json_encode($output);
		}

	}

	public function deleteFoto($id = NULL)
	{
		$query 	= $this->_getData($id);

		if (!@$query) {
			show_404();
		}
		
		if (@$query->profile_pic) {
			unlink(IMAGE . $query->profile_pic);
		}

		$this->db->update($this->table, ['profile_pic' => NULL], [$this->primaryKey => $id]);

		if ($this->input->post('action')) {
			$this->session->set_flashdata('success', 'Berhasil Manghapus Foto');
			redirect($this->input->post('action'));
		} else {
			$output['status'] 	= TRUE;
			$output['message'] 	= 'Berhasil Menghapus Foto';
			echo json_encode($output);
		}
	}

	public function update($id = NULL)
	{
		# Update Biodata Ayah, Ibu & Wali
		
		$query = $this->siswa->getData($id);

		if (!@$query || @$query->user_type_id != 3) {
			show_404();
		}

		$data = array(
			'folder' 		=> $this->folder, 
			'title' 		=> 'Siswa',
			'header'		=> 'Edit',
			'row'			=> $query,
			// 'pendidikan'	=> ['SD/Sederajat', 'SMP/Sederajat', 'SMA/Sederajat', 'Diploma 3', 'Diploma 4', 'Strata 1' ,'Strata 2', 'Strata 3', 'Tidak Sekolah'],
			'pendidikan'	=> ['SD/Sederajat', 'SMP/Sederajat', 'SMA/Sederajat', 'Diploma 3', 'Diploma 4', 'Strata 1', 'Tidak Sekolah'],
			// 'penghasilan'	=> ['<1 Juta', '1-2 Juta', '2-3 Juta', '3-4 Juta', '4-5 Juta', '>5 Juta', 'Tanpa Penghasilan'],
			'penghasilan'	=> ['<1 Juta', '1-2 Juta', '2-3 Juta', '>3 Juta', 'Tanpa Penghasilan'],
			'pekerjaan'		=> ['Tidak Bekerja', 'Pensiunan', 'Pegawai Negeri Sipil', 'TNI/POLRI', 'Karyawan Swasta', 'Petani', 'Nelayan', 'Buruh Harian Lepas', 'Pedagang', 'Wiraswasta', 'Lainnya'],
			'pekerjaan_ibu'		=> ['Tidak Bekerja', 'Ibu Rumah Tangga', 'Pensiunan', 'Pegawai Negeri Sipil', 'TNI/POLRI', 'Karyawan Swasta', 'Petani', 'Nelayan', 'Buruh Harian Lepas', 'Pedagang', 'Wiraswasta', 'Lainnya'],
		);

		$this->include->content('user/edit_biodata_ortu', $data);
	}

	public function saveOrangTuaWali($id = NULL)
	{
		# Simpan Biodata Ayah, Ibu & Wali
		
		$query = $this->db->get_where('siswa', ['md5(siswa_id)' => $id])->row();

		$nohp_ayah 	= @$query->nohp_ayah != $this->input->post('nohp_ayah') ? "|is_unique[siswa.nohp_ayah]" : "";
		$nohp_ibu 	= @$query->nohp_ibu != $this->input->post('nohp_ibu') ? "|is_unique[siswa.nohp_ibu]" : "";
		$nohp_wali 	= @$query->nohp_wali != $this->input->post('nohp_wali') ? "|is_unique[siswa.nohp_wali]" : "";

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('nohp_ayah', 'No. Handphone', 'trim|min_length[11]' . $nohp_ayah);
		$this->form_validation->set_rules('nohp_ibu', 'No. Handphone', 'trim|min_length[11]' . $nohp_ibu);
		$this->form_validation->set_rules('nohp_wali', 'No. Handphone', 'trim|min_length[11]' . $nohp_wali);
		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');
		$this->form_validation->set_message('min_length', '{field} minimal {param} angka');

		$substr_ayah 	= substr($this->input->post('nohp_ayah'), 0, 1) == '-' ? 'No. Handphone tidak valid' : FALSE;
		$substr_ibu 	= substr($this->input->post('nohp_ibu'), 0, 1) == '-' ? 'No. Handphone tidak valid' : FALSE;
		$substr_wali 	= substr($this->input->post('nohp_wali'), 0, 1) == '-' ? 'No. Handphone tidak valid' : FALSE;

		if ($this->form_validation->run() == FALSE || $substr_ayah || $substr_ibu || $substr_wali) {
			
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'nohp_ayah' 	=> $substr_ayah ? $substr_ayah : form_error('nohp_ayah'),
			        'nohp_ibu' 		=> $substr_ibu ? $substr_ibu : form_error('nohp_ibu'),
			        'nohp_wali' 	=> $substr_wali ? $substr_wali : form_error('nohp_wali'),
			    )
			];

		} else {
			$data = [
				'nama_ayah' 		=> $this->input->post('nama_ayah') ? htmlspecialchars($this->input->post('nama_ayah')) : NULL,
				'pendidikan_ayah'	=> $this->input->post('pendidikan_ayah') ? htmlspecialchars($this->input->post('pendidikan_ayah')) : NULL,
				'pekerjaan_ayah'	=> $this->input->post('pekerjaan_ayah') ? htmlspecialchars($this->input->post('pekerjaan_ayah')) : NULL,
				'penghasilan_ayah'	=> $this->input->post('penghasilan_ayah') ? htmlspecialchars($this->input->post('penghasilan_ayah')) : NULL,
				'nohp_ayah'			=> $this->input->post('nohp_ayah') ? htmlspecialchars($this->input->post('nohp_ayah')) : NULL,
				'alamat_ayah'		=> $this->input->post('alamat_ayah') ? htmlspecialchars($this->input->post('alamat_ayah')) : NULL,
				'nama_ibu' 			=> $this->input->post('nama_ibu') ? htmlspecialchars($this->input->post('nama_ibu')) : NULL,
				'pendidikan_ibu'	=> $this->input->post('pendidikan_ibu') ? htmlspecialchars($this->input->post('pendidikan_ibu')) : NULL,
				'pekerjaan_ibu'		=> $this->input->post('pekerjaan_ibu') ? htmlspecialchars($this->input->post('pekerjaan_ibu')) : NULL,
				'penghasilan_ibu'	=> $this->input->post('penghasilan_ibu') ? htmlspecialchars($this->input->post('penghasilan_ibu')) : NULL,
				'nohp_ibu'			=> $this->input->post('nohp_ibu') ? htmlspecialchars($this->input->post('nohp_ibu')) : NULL,
				'alamat_ibu'		=> $this->input->post('alamat_ibu') ? htmlspecialchars($this->input->post('alamat_ibu')) : NULL,
				'nama_wali' 		=> $this->input->post('nama_wali') ? htmlspecialchars($this->input->post('nama_wali')) : NULL,
				'pendidikan_wali'	=> $this->input->post('pendidikan_wali') ? htmlspecialchars($this->input->post('pendidikan_wali')) : NULL,
				'pekerjaan_wali'	=> $this->input->post('pekerjaan_wali') ? htmlspecialchars($this->input->post('pekerjaan_wali')) : NULL,
				'penghasilan_wali'	=> $this->input->post('penghasilan_wali') ? htmlspecialchars($this->input->post('penghasilan_wali')) : NULL,
				'nohp_wali'			=> $this->input->post('nohp_wali') ? htmlspecialchars($this->input->post('nohp_wali')) : NULL,
				'alamat_wali'		=> $this->input->post('alamat_wali') ? htmlspecialchars($this->input->post('alamat_wali')) : NULL,
			];

			$this->db->update('siswa', $data, ['siswa_id' => $query->siswa_id]);

			if ($this->db->affected_rows()) {
				$output['message']	= 'Berhasil Mengubah Orang Tua/Wali';
			}

			$output['status'] 	= TRUE;
		}

		echo json_encode($output);
	}

	# FUNGSI-FUNGSI UNTUK SISWA

	public function getKelas($id_tingkat_kelas = NULL)
	{
		$query = $this->db->where_in('md5(id_tingkat_kelas)', [@$id_tingkat_kelas])->get('kelas')->result();
		echo json_encode($query);
	}

	public function getNaikKelas($id_kelas = NULL)
	{
		$query = $this->db->get_where('kelas', ['md5(kelas_id)' => $id_kelas])->row();

		if (isset($query->kelas_id)) {
			$id_tingkat_kelas 	= $query->id_tingkat_kelas + 1;

			$output = array(
				'id_tingkat_kelas'	=> $query->id_tingkat_kelas,
				'select2' 			=> $this->db->order_by('id_tingkat_kelas', 'asc')->order_by('urutan_kelas', 'asc')->get_where('kelas', [
					'id_tingkat_kelas' => $id_tingkat_kelas,
					'delete_at' => NULL	
				])->result(), 
			);

			echo json_encode($output);
		}
	}

	public function updateKelas()
	{
		if ($this->input->post('kelas_id')) {
			$siswa = $this->db->get_where('siswa', ['md5(id_kelas)' => $this->input->post('id_kelas')])->result();
		} else {
			$siswa = $this->db->join('kelas k', 'k.kelas_id = s.id_kelas', 'left')->where('k.id_tingkat_kelas', 3)->get('siswa s')->result();
		}

		foreach ($siswa as $key) {
			if ($this->input->post('kelas_id')) {
				$data['id_kelas']  = $this->input->post('kelas_id');
			}
			$data['is_aktif']  = $this->input->post('kelas_id') ? 1 : 3; // 1 = Aktif 3 = Lulus
			$this->db->update('siswa', $data, ['siswa_id' => $key->siswa_id]);
		}

		$output['status'] 	= TRUE;
		$output['message'] 	= 'Berhasil Mengubah Kelas';
		echo json_encode($output);

	}

	public function insert_batch()
	{
		// for ($i=7; $i <= 30; $i++) {
		// 	$no_induk = date('i') . rand();

		// 	$user = array(
		// 		'no_induk' 		=> substr($no_induk, 0, 10),
		// 		'password'		=> sha1(time()),
		// 		'full_name' 	=> 'Siswa' . $i,
		// 		'gender'		=> $i % 2 == 0 ? 'L' : 'P',
		// 		'tempat_lahir'	=> 'Tasikmalaya',
		// 		'tanggal_lahir' => date('Y-m-d'),
		// 		'user_type_id'  => '3',
		// 		'date_created'	=> date('Y-m-d H:i:s')
		// 	);

		// 	$inserted = $this->db->insert('user', $user);

		// 	if ($inserted) {
		// 		$siswa = array(
		// 			'id_user' 	=> $this->db->insert_id(),
		// 			'id_kelas' 	=> '1',
		// 			'is_aktif'	=> '1'
		// 		);

		// 		$this->db->insert('siswa', $siswa);
		// 	}

		// }
		
	}

	// private function _checkKelas($id)
	// {
	// 	$query = $this->db->get_where('tingkat_kelas', ['md5(tingkat_kelas_id)' => $id])->row();
	// 	 if (@$query->tingkat_kelas_id) {
	// 		 $this->db->where('id_tingkat_kelas >', $query->tingkat_kelas_id);
	// 	 }
	// 	 $this->db->where_not_in('id_tingkat_kelas', [@$query->tingkat_kelas_id]);
	// 	 return $this->db->get('kelas');
	// }


	// public function validateKelas()
	// {
	// 	$this->form_validation->set_error_delimiters('', '');
	//     $this->form_validation->set_rules('id_kelas', 'Kelas', 'trim|required');
	//     $this->form_validation->set_rules('id_tingkat_kelas', 'Tingkat Kelas', 'trim|required');
	// 	$this->form_validation->set_message('required', '{field} harus diisi');

	// 	if ($this->form_validation->run() == FALSE) {
	// 		$output = [
	// 		    'status' => FALSE,
	// 		    'errors' => array(
	// 		        'id_kelas'   			=> form_error('id_kelas'),
	// 		        'id_tingkat_kelas'   	=> form_error('id_tingkat_kelas'),
	// 		    )
	// 		];

	// 	} else {
	// 		$query = $this->_checkKelas($this->input->post('id_tingkat_kelas'));

	// 		$output = [
	// 			'status' 		=> TRUE,
	// 			// 'result'		=> $query->num_rows() ? date('Ymd') : 1,
	// 			'result'		=> date('Ymd'),
	// 		];

	// 	}

	// 	echo json_encode($output);
	// }

	// public function getTapel($id_tingkat_kelas = NULL)
	// {
	// 	# Get Tahun Pelajaran // Update Kelas // Naik Kelas
		
	// 	$query = $this->_checkKelas($id_tingkat_kelas);

	// 	// if ($query->num_rows() > 0) {
	// 		$output = array([
	// 			'tahun_pelajaran_id' 	=> date('Ymd'),
	// 			'tahun_pelajaran'		=> 'Belum Lulus',
	// 		]);
	// 	// } else {
	// 	// 	$output = $this->db->get('tahun_pelajaran')->result();
	// 	// }
		
		
	// 	echo json_encode($output);
	// }

	// public function graduates()
	// {
	// 	$data = array(
	// 		'folder' 		=> $this->folder, 
	// 		'title' 		=> 'Siswa',
	// 		'header'		=> 'Lulusan',
	// 		'tahun_pelajaran'	=> $this->db->get('tahun_pelajaran')->result(),
	// 	);
		
	// 	$this->include->content('user/index_lulusan_siswa', $data);
	// }

	// public function showLulusan()
	// {
	// 	$data = $this->siswa->getLulusan();
	// 	echo json_encode($data);
	// }

	// public function changeAngkatan($id = NULL)
	// {
	// 	$query = $this->db->get_where('lulusan', ['md5(id_lulusan)' => $id])->row();

	// 	if (!@$query) {
	// 		show_404();
	// 	}

	// 	$id_tahun_pelajaran = $this->input->post('itp') ? $this->input->post('itp') : NULL;
	// 	$this->db->update('lulusan', ['id_tahun_pelajaran' => $id_tahun_pelajaran], ['id_lulusan' => $query->id_lulusan]);
	// 	echo json_encode(['status' => TRUE]);
	// }

}

/* End of file User.php */
/* Location: ./application/controllers/User.php */
