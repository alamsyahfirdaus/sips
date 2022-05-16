<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();

		if ($this->session->user_type_id != 3) {
			redirect(site_url());
		}

		$this->load->model('Siswa_model', 'siswa');
		$this->load->model('Jadwal_model', 'jadwal');
		$this->load->model('Tapel_model', 'tapel');

	}

	public function index()
	{

		$data = [
			'folder' 	=> 'Beranda',
			'title'		=> 'Profile',
			'row' 		=> $this->_getData(),
		];

		$this->include->topnav('siswa/profile_siswa', $data);
	}

	private function _getData()
	{
		$user_id = $this->session->user_id;
		return $this->siswa->getData(md5($user_id));
	}

	public function schedule()
	{
		$tapel = $this->db->get_where('tahun_pelajaran', ['is_aktif' => 'Y'])->row();
		$siswa = $this->_getData();


		$rekap = array(
			'id_tahun_pelajaran' 	=> md5(@$tapel->tahun_pelajaran_id),
			'semester'				=> @$tapel->semester,
			'id_user' 				=> @$siswa->user_id,
			'id_kelas'  			=> @$siswa->id_kelas,
		);

		$data['folder']				= 'Pembelajaran';
		$data['title']				= 'Jadwal Pelajaran';
		$data['tapel']				= @$tapel->tahun_pelajaran;
		$data['jadwal_pelajaran'] 	= $this->_getJadwal($tapel);
		$data['id_tahun_pelajaran'] = @$tapel->tahun_pelajaran_id;
		$data['semester'] 			= @$tapel->semester;
		$data['id_kelas'] 			= @$siswa->id_kelas;
		$data['rekap'] 				= $this->_getRekap($rekap);


		$this->include->topnav('siswa/jadwal_siswa', $data);
	}

	private function _getJadwal($tapel)
	{
		$siswa = $this->_getData();

		$html = '<div class="box-header with-border">';
		$html .= '<h3 class="box-title">Daftar Jadwal Pelajaran</h3>';
		$html .= '</div>';
		$html .= '<input type="hidden" id="title" value="Detail Presensi">';
		$html .= '<input type="hidden" id="jadwal_pelajaran_id" name="id_jadwal_pelajaran" value="">';
		$html .= '<input type="hidden" name="id_user" value="'. @$siswa->id_user .'">';
		$html .= '<input type="hidden" name="id_sem" value="'. @$tapel->semester .'">';
		$html .= '<input type="hidden" name="kelas" value="Kelas : '. @$siswa->nama_kelas .'">';
		$html .= '<input type="hidden" name="semester" value="Semester : '. @$tapel->semester . ' / '. $this->include->semester(@$tapel->semester) .'">';

		for ($i=1; $i < 7; $i++) { 
			$border = date('w') == $i ? 'border-left: 3px solid #00A65A;' : 'border-left: 3px solid #EEEEEE;';
			$button = date('w') == $i ? 'class="btn btn-sm" style="background-color: #00A65A; color: #FFFFFF;"' : 'class="btn btn-sm btn-default"';
			$days 	= date('Y-m-d', strtotime('+'.  ($i - date('w')) .' days'));
			$date 	= @$days ? ', ' . $this->include->date($days) : '';

			$html .= '<div class="box-body">';
			$html .= ' <div class="callout" style="'. $border .' border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">';
			$html .= '<h4 style="font-family: serif;">'. $this->include->days($i) . $date .'</h4>';
			$html .= '<div class="table-responsive">';
			$html .= '<table class="table table-condensed" style="width: 100%">';
			$html .= '<tr>';
			$html .= '<th class="text-center" width="5%">No</th>';
			$html .= '<th width="35%">Kode - Mata Pelajaran</th>';
			$html .= '<th>Guru</th>';
			$html .= '<th width="15%" class="text-center">Jam Pelajaran</th>';
			$html .= '<th width="5%" class="text-center">Presensi</th>';
			$html .= '</tr>';
			$query = $this->jadwal->getData(NULL, @$tapel->tahun_pelajaran_id, @$siswa->id_kelas, NULL, $i);
			$activation = @$query && @$tapel->tahun_pelajaran_id && @$tapel->semester ? TRUE : FALSE;
			if ($activation) {
				$no = 1;
				foreach ($query as $row) {
					$kode 		= $row->kode_mapel ? $row->kode_mapel : '#';
					$mulai 		= $this->_getJampel($row->mulai);
					$selesai	= $this->_getJampel($row->selesai);
					$html .= '<tr>';
					$html .= '<input type="hidden" name="mapel_'. md5($row->jadwal_pelajaran_id) .'" value="Mata Pelajaran : ' . $row->nama_mapel .'">';
					$html .= '<td class="text-center" width="5%">'. $no++ .'</td>';
					$html .= '<td width="35%">'. $kode . ' - ' . $row->nama_mapel .'</td>';
					$html .= '<td>'. $row->full_name .'</td>';
					$html .= '<td width="15%" class="text-center">'. $mulai . ' - ' . $selesai .'</td>';
					$html .= '<td width="5%" class="text-center">';
					$html .= '<button type="button" '. $button .' onclick="list_presensi(' . "'" . md5($row->jadwal_pelajaran_id) . "'" . ')"><i class="fa fa-folder-open"></i></button>';
					$html .= '</td>';
					$html .= '</tr>';
				}
			} else {
				$html .= '<tr>';
				$html .= '<td colspan="5" class="text-center"></td>';
				$html .= '</tr>';

			}
			$html .= '</table>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}

		if (@$tapel->tahun_pelajaran_id && @$tapel->semester) {
			$html .= '<div class="box-footer">';
			$html .= '<button type="button" class="btn btn-sm" style="background-color: #00A65A; color: #FFFFFF; font-weight: bold;" onclick="rekap_presensi()"><i class="fa fa-folder-open"></i> Lihat Presensi</button>';
			$html .= '</div>';
		}

		return $html;
	}

	private function _getJampel($id)
	{
		$query = $this->db->get_where('jam_pelajaran', ['jam_pelajaran_id' => $id])->row();
		return @$query ? $this->include->clock($query->jam_pelajaran) : '#';
	}

	public function showPresensi()
	{
		$jadwal_pelajaran = $this->jadwal->getRow($this->input->post('id_jadwal_pelajaran'));
		$total_pekan 	  = $this->tapel->get_date(@$jadwal_pelajaran->hari, @$jadwal_pelajaran->jadwal_pelajaran_id, $this->input->post('semester'));
		$week = $jadwal_pelajaran ? $total_pekan['week'] : 0;

		$data = $this->siswa->getPresensi($week);
		echo json_encode($data);
	}

	public function showAngotaKelas()
	{
		$data = $this->siswa->getAnggotaKelas();
		echo json_encode($data);
	}

	private function _getRekap($data)
	{
		$this->load->model('Lap_presensi_siswa_model', 'lps');

		$hadir = 0;
		$sakit = 0;
		$izin  = 0;
		$alpa  = 0;

		foreach ($this->include->opsiPresensi() as $key => $value) {
			$presensi = $this->lps->get_presensi(array_merge($data, ['status' => $key]));

			if ($key == 1) {
				$hadir += $presensi;
			} elseif ($key == 2) {
				$sakit += $presensi;
			} elseif ($key == 3) {
				$izin += $presensi;
			} elseif ($key == 4) {
				$alpa += $presensi;
			}

		}

		$total_pekan 	= $this->lps->total_pekan($data);
		$hitung 		= $hadir >= 1 && $total_pekan >= 1 ? $hadir / $total_pekan * 100 : 0;
		$persentase 	= intval($hitung) >= 100 ? 100 : round($hitung);

		if ($persentase >= 90) {
			$progress_bar 	= 'progress-bar-green';
			$fillColor 		= '#00A65A';
		} elseif ($persentase >= 70) {
			$progress_bar 	= 'progress-bar-yellow';
			$fillColor 		= '#F39C12';
		} else {
			$progress_bar 	= 'progress-bar-red';
			$fillColor 		= '#DD4B39';
		}
		
		$progress = '<div class="clearfix">';
		$progress .= '<span class="pull-left">Kehadiran</span>';
		$progress .= '<small class="pull-right">'. 	$persentase .'%</small>';
		$progress .= '</div>';
		$progress .= '<div class="progress">';
		$progress .= '<div class="progress-bar '. $progress_bar .' progress-bar-striped" role="progressbar" aria-valuenow="'. $persentase .'" aria-valuemin="0" aria-valuemax="100" style="width: '. $persentase .'%">';
		$progress .= '<span class="sr-only">'. $persentase .'% Complete</span>';
		$progress .= '</div>';
		$progress .= '</div>';

		return array(
			'1' 		=> $hadir, 
			'2' 		=> $sakit, 
			'3' 		=> $izin, 
			'4' 		=> $alpa,
			'kehadiran' => $progress
		);


	}


	# FUNGSI UPDATE PROFILE
	
	public function edit()
	{
		# Edit Profile 
		
		$data = [
			'folder' 	=> 'Beranda',
			'title'		=> 'Profile',
			'header'	=> 'Edit',
			'row' 		=> $this->_getData(),
		];

		$this->include->topnav('siswa/edit_profile_siswa', $data);
	}

	public function saveProfile($user_id = NULL)
	{
		$query 	= $this->_getData();

		$email 	= $query->email != $this->input->post('email') ? "|is_unique[user.email]" : "";
		$phone 	= $query->phone != $this->input->post('phone') ? "|is_unique[user.phone]" : "";
		$substr = substr($this->input->post('phone'), 0, 1) == '-' ? 'No. Handphone tidak valid' : FALSE;

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email' . $email);
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim|required|alpha_numeric_spaces');
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim');
	    $this->form_validation->set_rules('phone', 'No. Handphone', 'trim|min_length[11]' . $phone, ['min_length' => 'No. Handphone minimal {param} angka']);

		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');
		$this->form_validation->set_message('valid_email', '{field} tidak valid');
		$this->form_validation->set_message('alpha', '{field} tidak valid');
		$this->form_validation->set_message('numeric', '{field} harus berisi angka');
		$this->form_validation->set_message('alpha_numeric_spaces', '{field} tidak valid');


		if ($this->form_validation->run() == FALSE || $substr) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'email'  		=> form_error('email'),
			        'tempat_lahir'  => form_error('tempat_lahir'),
			        'phone'  		=> $substr ? $substr : form_error('phone'),
			    )
			];

		} else {
			$data = array(
				'full_name' 	=> htmlspecialchars(ucwords($this->input->post('full_name'))),
				'gender'		=> htmlspecialchars($this->input->post('gender')),
				'tempat_lahir'	=> htmlspecialchars(ucwords($this->input->post('tempat_lahir'))),
				'tanggal_lahir'	=> date('Y-m-d', strtotime($this->input->post('tanggal_lahir'))),
				'agama'			=> $this->input->post('agama') ? htmlspecialchars($this->input->post('agama')) : NULL,
				'alamat'		=> $this->input->post('alamat') ? htmlspecialchars($this->input->post('alamat')) : NULL,
				'email'			=> htmlspecialchars($this->input->post('email')),
				'phone'			=> htmlspecialchars($this->input->post('phone'))
			);

			$this->db->update('user', $data, ['md5(user_id)' => $user_id]);

			if ($this->db->affected_rows()) {
				$output['message'] = 'Berhasil Mengubah Profile';
			}

			$output['status'] = TRUE;
			
		}

		echo json_encode($output);
	}

	public function parents()
	{
		# Edit Orang Tua/Wali
		
		$data = [
			'folder' 	=> 'Beranda',
			'title'		=> 'Profile',
			'header'	=> 'Edit',
			'row' 		=> $this->_getData(),
			// 'pendidikan'	=> ['SD/Sederajat', 'SMP/Sederajat', 'SMA/Sederajat', 'Diploma 3', 'Diploma 4', 'Strata 1' ,'Strata 2', 'Strata 3', 'Tidak Sekolah'],
			'pendidikan'	=> ['SD/Sederajat', 'SMP/Sederajat', 'SMA/Sederajat', 'Diploma 3', 'Diploma 4', 'Strata 1', 'Tidak Sekolah'],
			'penghasilan'	=> ['<1 Juta', '1-2 Juta', '2-3 Juta', '>3 Juta', 'Tanpa Penghasilan'],
			// 'penghasilan'	=> ['<1 Juta', '1-2 Juta', '2-3 Juta', '3-4 Juta', '4-5 Juta', '>5 Juta', 'Tanpa Penghasilan'],
			'pekerjaan'		=> ['Tidak Bekerja', 'Pensiunan', 'Pegawai Negeri Sipil', 'TNI/POLRI', 'Karyawan Swasta', 'Petani', 'Nelayan', 'Buruh Harian Lepas', 'Pedagang', 'Wiraswasta', 'Lainnya'],
			'pekerjaan_ibu'		=> ['Tidak Bekerja', 'Ibu Rumah Tangga', 'Pensiunan', 'Pegawai Negeri Sipil', 'TNI/POLRI', 'Karyawan Swasta', 'Petani', 'Nelayan', 'Buruh Harian Lepas', 'Pedagang', 'Wiraswasta', 'Lainnya'],
		];

		$this->include->topnav('siswa/edit_ortu_siswa', $data);
	}

	public function saveParents($id_siswa = NULL)
	{
		# Simpan Edit Orang Tua/Wali
		
		$query = $this->db->get_where('siswa', ['md5(siswa_id)' => $id_siswa])->row();

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

	public function editFoto($user_id = NULL)
	{
		# Simpan Edit Foto
		
		$query 	= $this->_getData();

		if (!@$query) {
			show_404();
		}

		$this->_do_upload();

		if ($this->upload->do_upload('foto')) {
		    if (@$query->profile_pic) {
		        unlink(IMAGE . $query->profile_pic);
		    }

		    $this->db->update('user', ['profile_pic' => $this->upload->data('file_name')], ['md5(user_id)' => $user_id]);
		}

		$this->session->set_flashdata('success', 'Berhasil Mengubah Foto');
		redirect('student');
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

	public function deleteFoto($user_id = NULL)
	{
		$query 	= $this->_getData();

		if (!@$query) {
			show_404();
		}
		
		if (@$query->profile_pic) {
			unlink(IMAGE . $query->profile_pic);
		}

		$this->db->update('user', ['profile_pic' => NULL], ['md5(user_id)' => $user_id]);

		$this->session->set_flashdata('success', 'Berhasil Menghapus Foto');
		redirect('student');
	}

	public function editPassword($user_id = NULL)
	{
		$query 	= $this->_getData();

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('old_password', 'Password Lama', 'trim|required');
		$this->form_validation->set_rules('new_password1', 'Password Baru', 'trim|required|min_length[8]');

		$this->form_validation->set_rules('new_password2', 'Konfirmasi Password', 'trim|required|min_length[8]|matches[new_password1]', ['matches' => 'Konfirmasi Password salah']);

		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('min_length', '{field} minimal {param} karakter');

		$old_password = $query->password != sha1($this->input->post('old_password')) ? TRUE : FALSE;
		$new_password = $query->password == sha1($this->input->post('new_password1')) ? TRUE : FALSE;

		if ($this->form_validation->run() == FALSE || $old_password || $new_password) {
			$output = [
				'status'	=> FALSE,
				'errors'	=> array(
					'old_password' 	=> $old_password ? 'Password Lama salah' : form_error('old_password'),
					'new_password1' => $new_password ? 'Password Baru tidak boleh sama' : form_error('new_password1'),
					'new_password2' => form_error('new_password2')
				),
			];
		} else {
			$this->db->update('user', ['password' => sha1($this->input->post('new_password1'))], ['md5(user_id)' => $user_id]);

			$output = [
				'status' 	=> TRUE,
				'message'	=> 'Berhasil Mengubah Password',
			];

		}

		echo json_encode($output);
	}

}

/* End of file Student.php */
/* Location: ./application/controllers/Student.php */
