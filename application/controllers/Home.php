<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();

		$this->load->model('Jadwal_model', 'jadwal');
		$this->load->model('Siswa_model', 'siswa');
		$this->load->model('Tapel_model', 'tahun_pelajaran');

	}

	private $table = 'user';
	private $color = '#00A65A';

	public function index()
	{	
		$tapel = $this->db->get_where('tahun_pelajaran', ['is_aktif' => 'Y'])->row();

		$data['title']				= 'Beranda';
		$data['row']				= $this->mall->getSession();
		$data['tapel']				= @$tapel->tahun_pelajaran;
		$data['pengumuman']			= $this->_pengumuman();
		$data['id_tahun_pelajaran'] = isset($tapel->tahun_pelajaran_id) ? $tapel->tahun_pelajaran_id : 0;
		$data['semester'] 			= isset($tapel->tahun_pelajaran_id) ? $tapel->semester : 0;

		$sub_menu  = $this->db->get_where('sub_menu', ['sub_menu' => 'Pengumuman'])->row();
		$hak_akses = $this->db->get_where('user_access', ['sub_menu_id' => @$sub_menu->sub_menu_id])->num_rows();
		$data['col'] 	= $hak_akses > 0 ? 'col-md-8' : 'col-md-12';
		$data['style'] 	= $hak_akses < 1 ? 'style="display: none;"' : '';

		if ($this->session->user_type_id == 1) {

			$pengguna = $this->_countUser(1) + $this->_countUser(2) + $this->_countSiswa();

			$data['pengguna']		= $pengguna;
			$data['adminstrator']	= $this->_countUser(1);
			$data['guru']			= $this->_countUser(2);
			$data['siswa']			= $this->_countSiswa();
			
			$this->include->content('home_view', $data);

		} elseif ($this->session->user_type_id == 2) {

			$data['jadwal_mengajar'] = $this->_jadwalGuru($tapel);
			$this->include->content('guru/home_guru', $data);

		} elseif ($this->session->user_type_id == 3) {
			$siswa = $this->siswa->getData(md5($this->session->user_id));
			$data['jadwal_pelajaran'] = $this->_jadwalSiswa($tapel, $siswa);

			$this->include->topnav('siswa/home_siswa', $data);
			
		} elseif ($this->session->user_type_id == 4) {
			$this->include->topnav('guru/home_guru_piket', $data);
		}
	}

	private function _countUser($user_type_id)
	{
		return $this->db->get_where($this->table, [
			'user_type_id'	=> $user_type_id,
			'delete_at'		=> NULL,
		])->num_rows();
	}

	private function _countSiswa()
	{
		return $this->db->get_where('siswa', [
			'id_kelas !='	=> NULL,
			'is_aktif'		=> 1
		])->num_rows();
	}

	public function profile()
	{
		if ($this->session->user_type_id == 1) {
			$query = $this->mall->getSession();
			$data = array(
				'title' 		=> 'Beranda', 
				'sub_title' 	=> 'Profile',
				'row'			=> $query,
				'guru_piket'	=> $this->db->get_where('guru_piket', ['id_user' => $query->user_id])->result(),
			);
			
			$this->include->content('profile/index_profile', $data);

		} elseif ($this->session->user_type_id == 2 || $this->session->user_type_id == 4) {
			redirect('teacher');
		} elseif ($this->session->user_type_id == 3) {
			redirect('student');
		} else {
			redirect(site_url());
		}

	}

	public function changePassword()
	{
		$query = $this->mall->getSession();

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('old_password', 'Password Lama', 'trim|required');
		$this->form_validation->set_rules('new_password1', 'Password Baru', 'trim|required|min_length[8]');

		$this->form_validation->set_rules('new_password2', 'Konfirmasi Password', 'trim|required|min_length[8]|matches[new_password1]', ['matches' => 'Konfirmasi Password salah']);

		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('min_length', '{field} minimal {param} karakter');

		$old_password = $query->password != sha1($this->input->post('old_password')) ? TRUE : FALSE;
		// $new_password = $query->password == sha1($this->input->post('new_password1')) ? TRUE : FALSE;

		if ($this->form_validation->run() == FALSE || $old_password) {
			$output = [
				'status'	=> FALSE,
				'errors'	=> array(
					'old_password' 	=> $old_password ? 'Password Lama salah' : form_error('old_password'),
					'new_password1' => form_error('new_password1'),
					'new_password2' => form_error('new_password2')
				),
			];
		} else {
			$this->db->update('user', ['password' => sha1($this->input->post('new_password1'))], ['user_id' => $query->user_id]);

			$output = [
				'status' 	=> TRUE,
				'message'	=> 'Berhasil Mengubah Password',
			];

		}

		echo json_encode($output);
	}

	public function update()
	{
		# Edit Profile

		$data = array(
			'title' 	=> 'Beranda', 
			'sub_title' => 'Profile',
			'header'	=> 'Edit Profile',
			'row'		=> $this->mall->getSession(),
		);

		if ($this->session->user_type_id == 1) {
			redirect('user/edit/' . md5($this->session->user_id));
		} elseif ($this->session->user_type_id == 2) {
			$this->include->content('profile/edit_profile', $data);
		} else {
			redirect(site_url());
		}
		
	}

	public function saveProfile()
	{
		$query 		= $this->mall->getSession();
		$no_induk 	= $query->no_induk != $this->input->post('no_induk') ? "|is_unique[user.no_induk]" : "";
		$email 		= $query->email != $this->input->post('email') ? "|is_unique[user.email]" : "";
		$phone 		= $query->phone != $this->input->post('phone') ? "|is_unique[user.phone]" : "";
		$substr 	= substr($this->input->post('phone'), 0, 1) == '-' ? 'No. Handphone tidak valid' : FALSE;
		$field 		= $query->user_type_id == 3 ? 'Nomor Induk Siswa' : 'NUPTK';
		$required 	= $query->user_type_id != 1 ? '|required' : '';

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('no_induk', $field, 'trim|numeric' . $required . $no_induk);
		$this->form_validation->set_rules('full_name', 'Nama', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email' . $email);
	    $this->form_validation->set_rules('phone', 'No. Handphone', 'trim|required|min_length[11]' . $phone, ['min_length' => 'No. Handphone minimal {param} angka']);

		$this->form_validation->set_rules('gender', 'Jenis Kelamin', 'trim|required|alpha');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim|required|alpha_numeric_spaces');
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim|required');
		// $this->form_validation->set_rules('agama', 'Agama', 'trim|required|alpha');
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim');

		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');
		$this->form_validation->set_message('valid_email', '{field} tidak valid');
		$this->form_validation->set_message('alpha', '{field} tidak valid');
		$this->form_validation->set_message('numeric', '{field} harus berisi angka');
		$this->form_validation->set_message('alpha_numeric_spaces', '{field} tidak valid');


		if ($this->form_validation->run() == FALSE || $substr) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			    	'no_induk'   		=> form_error('no_induk'),
			    	'full_name'   		=> form_error('full_name'),
			    	'gender'   			=> form_error('gender'),
			    	'tempat_lahir'   	=> form_error('tempat_lahir'),
			    	'tanggal_lahir'   	=> form_error('tanggal_lahir'),
			    	// 'agama'   			=> form_error('agama'),
			    	'alamat'   			=> form_error('alamat'),
			        'email'   			=> form_error('email'),
			        'phone'  			=> $substr ? $substr : form_error('phone'),
			    )
			];

		} else {

			$data = array(
				'no_induk'		=> $this->input->post('no_induk') ? htmlspecialchars($this->input->post('no_induk')) : NULL,
				'full_name' 	=> htmlspecialchars(ucwords(strtolower($this->input->post('full_name')))),
				'gender'		=> $this->input->post('gender') ? $this->input->post('gender') : NULL,
				'tempat_lahir'	=> $this->input->post('tempat_lahir') ? htmlspecialchars(ucwords(strtolower($this->input->post('tempat_lahir')))) : NULL,
				'tanggal_lahir'	=> $this->input->post('tanggal_lahir') ? date('Y-m-d', strtotime($this->input->post('tanggal_lahir'))) : NULL,
				'agama'			=> $this->input->post('agama') ? $this->input->post('agama') : NULL,
				'alamat'		=> $this->input->post('alamat') ? htmlspecialchars($this->input->post('alamat')) : NULL,
				'email'			=> $this->input->post('email') ? htmlspecialchars($this->input->post('email')) : NULL,
				'phone'			=> $this->input->post('phone') ? htmlspecialchars($this->input->post('phone')) : NULL,
			);

			$this->db->update($this->table, $data, ['user_id' => $this->session->user_id]);

			if ($this->db->affected_rows()) {
				$output['message'] = 'Berhasil Mengubah Profile';
			}

			$output['status'] = TRUE;
			
		}

		echo json_encode($output);
	}

	private function _getJampel($id)
	{
		$query = $this->db->get_where('jam_pelajaran', ['jam_pelajaran_id' => $id])->row();
		return @$query ? $this->include->clock($query->jam_pelajaran) : '#';
	}

	# Jadwal Pelajaran Siswa

	private function _jadwalSiswa($tapel, $siswa)
	{

		$query 		= $this->jadwal->getData(NULL, @$tapel->tahun_pelajaran_id, @$siswa->id_kelas, NULL, date('w'));
		$activation = @$query && @$tapel->tahun_pelajaran_id && @$tapel->semester ? TRUE : FALSE;

		$html = '<div class="box-header with-border">';
		$html .= '<h3 class="box-title"><i class="fa fa-calendar-check-o"></i> Jadwal Pelajaran</h3>';
		$html .= '</div>';
		$html .= '<input type="hidden" id="title" value="Daftar Siswa">';
		$html .= '<input type="hidden" name="kelas" value="Kelas : '. @$siswa->nama_kelas .'">';
		$html .= '<input type="hidden" name="id_kelas" value="'. @$siswa->id_kelas .'">';
		$html .= '<div class="box-body">';
		$html .= ' <div class="callout" style="border-left: 3px solid '. $this->color .'; border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">';
		$html .= '<h4 style="font-family: serif;">'. $this->include->days(date('w')) . ', ' . $this->include->date(date('Y-m-d')) .'</h4>';
		$html .= '<div class="table-responsive">';
		$html .= '<table class="table table-condensed" style="width: 100%">';
		$html .= '<tr>';
		$html .= '<th class="text-center" width="5%">No</th>';
		$html .= '<th width="35%">Kode - Mata Pelajaran</th>';
		$html .= '<th>Guru</th>';
		$html .= '<th width="15%" class="text-center">Jam Pelajaran</th>';
		$html .= '<th width="10%" class="text-center">Keterangan</th>';
		$html .= '</tr>';

		if ($activation) {
			$no = 1;
			foreach ($query as $row) {

				$kode 		= $row->kode_mapel ? $row->kode_mapel : '#';
				$mulai 		= $this->_getJampel($row->mulai);
				$selesai	= $this->_getJampel($row->selesai);
				$html .= '<tr>';
				$html .= '<td class="text-center" width="5%">'. $no++ .'</td>';
				$html .= '<td width="30%">'. $kode . ' - ' . $row->nama_mapel .'</td>';
				$html .= '<td>'. $this->include->null($row->full_name) .'</td>';
				$html .= '<td width="15%" class="text-center">'. $mulai . ' - ' . $selesai .'</td>';
				$html .= '<th width="25%" class="text-center">'. $this->_kehadiran($row->jadwal_pelajaran_id, $siswa->user_id) .'</th>';
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
		$html .= '<button type="button" class="btn btn-sm" style="background-color: '. $this->color .'; color: #FFFFFF; font-weight: bold; font-family: serif;" onclick="anggota_kelas('. @$siswa->id_kelas .')"><i class="fa fa-users"></i> Daftar Siswa</button>';
		$html .= '</div>';

		return $html;
	}

	private function _kehadiran($id_jadwal_pelajaran, $id_user)
	{
		$query = $this->db->get_where('presensi', [
			'DATE(tanggal)'			=> date('Y-m-d'),
			'id_jadwal_pelajaran'	=> $id_jadwal_pelajaran,
			'id_user'				=> $id_user,
		])->row();

		return @$query ? $this->include->presensi($query->status) : 'BELUM PRESENSI';
	}

	# Jadwal  Menjagar Guru
	
	private function _jadwalGuru($tapel)
	{

		$query 		= $this->jadwal->getData(NULL, @$tapel->tahun_pelajaran_id, NULL, $this->session->user_id, date('w'));
		$activation = @$query && @$tapel->tahun_pelajaran_id && @$tapel->semester ? TRUE : FALSE;

		$html = '<div class="box-header with-border">';
		$html .= '<h3 class="box-title"><i class="fa fa-calendar-check-o"></i> Jadwal Mengajar</h3>';
		$html .= '</div>';
		$html .= '<div class="box-body">';
		$html .= ' <div class="callout" style="border-left: 3px solid '. $this->color .'; border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">';
		$html .= '<h4 style="font-family: serif;">'. $this->include->days(date('w')) . ', ' . $this->include->date(date('Y-m-d')) .'</h4>';
		$html .= '<div class="table-responsive">';
		$html .= '<table class="table table-condensed" style="width: 100%">';
		$html .= '<tr>';
		$html .= '<th class="text-center" width="5%">No</th>';
		$html .= '<th width="30%">Kode - Mata Pelajaran</th>';
		$html .= '<th>Kelas</th>';
		$html .= '<th width="20%" class="text-center">Jam Pelajaran</th>';
		$html .= '<th width="10%" class="text-center">Presensi<span style="color: #FFFFFF;">_</span>Siswa</th>';
		$html .= '</tr>';

		if ($activation) {
			$no = 1;
			foreach ($query as $row) {
				$kode 		= $row->kode_mapel ? $row->kode_mapel : '#';
				$mulai 		= $this->_getJampel($row->mulai);
				$selesai	= $this->_getJampel($row->selesai);
				$html .= '<tr>';
				$html .= '<td class="text-center" width="5%">'. $no++ .'</td>';
				$html .= '<td width="40%">'. $kode . ' - ' . $row->nama_mapel .'</td>';
				$html .= '<td>'. $row->nama_kelas .'</td>';
				$html .= '<td width="20%" class="text-center">'. $mulai . ' - ' . $selesai .'</td>';
				$html .= '<td width="25%" class="text-center">';
				$html .= '<a href="'. site_url('teacher/presence/' . md5($row->jadwal_pelajaran_id)) .'" class="btn btn-sm btn-social" style="background-color: '. $this->color .'; color: #FFFFFF; text-decoration: none; font-weight: bold;"><i class="fa fa-calendar-plus-o" style="border-right: '. $this->color .';"></i> '. $this->_getStatus($row->jadwal_pelajaran_id, @$tapel->semester) .'</a>';
				$html .= '</td>';
				$html .= '</tr>';
			}
		} else {
			$html .= '<tr>';
			$html .= '<th colspan="5" class="text-center"></th>';
			$html .= '</tr>';
		}
		$html .= '</table>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	private function _getStatus($id_jadwal_pelajaran, $semester)
	{
		$query = $this->db->get_where('presensi', [
			'tanggal'				=> date('Y-m-d'),
			'id_jadwal_pelajaran'	=> $id_jadwal_pelajaran,
			'semester'				=> $semester,
		])->num_rows();

		return $query > 0 ? 'SUDAH PRESENSI' : 'BELUM PRESENSI';
	}

	# Pengumuman
	
	private function _pengumuman()
	{
		$query = $this->db->where('user_type_id', $this->session->user_type_id)->or_where('user_type_id', NULL)->where('is_aktif', 'Y')->order_by('id_pengumuman', 'desc')->limit(5)->get('pengumuman')->result();

		$callout = '<style type="text/css">#callout:hover{background-color: #E7E7E7;}</style>';

		if ($query) {


			foreach ($query as $row) {
				$days 	= $this->include->days(date('w', strtotime($row->tanggal)));
				
				$callout .= '<div class="callout" style="border-left: 3px solid '. $this->color .'; border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">';
				$callout .= '<h4 id="callout"><a href="javascript:void(0)" onclick="lihat_pengumuman('. $row->id_pengumuman .')" style="color: #333333; text-decoration: none; font-family: serif;">'. $row->judul .'</a></h4>';
				$callout .= '<p>'. $days . ', ' . $this->include->date($row->tanggal) .'</p>';
				$callout .= '</div>';
				$callout .= '<input type="hidden" name="judul_'. $row->id_pengumuman .'" value="' . $row->judul . '">';
				$callout .= '<input type="hidden" name="gambar_'. $row->id_pengumuman .'" value="' . $row->gambar . '">';
				$callout .= '<input type="hidden" name="img_src_'. $row->id_pengumuman .'" value="' . site_url(IMAGE . $this->include->image($row->gambar)) . '">';
				$callout .= '<input type="hidden" name="pengumuman_'. $row->id_pengumuman .'" value="' . $row->pengumuman . '">';
			}
		} else {
			$callout .= '<p class="text-center"></p>';
			$callout .= '<div class="box-footer"></div>';
		}


		return $callout;
	}

	public function showJadwal()
	{
		# JADWAL HARI INI (ADMINISTRATOR)
		
		$tapel = $this->db->get_where('tahun_pelajaran', ['is_aktif' => 'Y'])->row();
		
		$data = $this->jadwal->getDataTables1(@$tapel->tahun_pelajaran_id, @$tapel->semester);
		echo json_encode($data);
	}

	# INPUT KEHADIRAN SISWA (ADMINISTRATOR)
	
	public function getDetailJadwal($id_jadwal_pelajaran)
	{
		# Home -> Input (Modal) -> Pilih Siswa

		$query 		= $this->jadwal->getRow($id_jadwal_pelajaran);
		$tapel 		= $this->db->get_where('tahun_pelajaran', ['is_aktif' => 'Y'])->row();
		$id_kelas 	= @$query->id_kelas;
		$nama_mapel	= @$query->nama_mapel;
		$nama_kelas = @$query->nama_kelas;
		
		$this->db->join('siswa s', 's.id_user = u.user_id', 'left');
		$this->db->where('user_type_id', 3);
		$this->db->where('id_kelas', $id_kelas);
		$query = $this->db->get('user u')->result();

		$option[] = '<option value="">-- Pilih Siswa --</option>';
		foreach ($query as $row) {
			$option[]  = '<option value="'. md5($row->user_id) .'">'. $row->full_name .'</option>';
		}

		$callout = '<b>Mata Pelajaran : '. $nama_mapel .'</b>';
		$callout .= '<b>Kelas : '. $nama_kelas .'</b>';
		$callout .= '<b>Semester : '. @$tapel->semester . ' / '. $this->include->semester(@$tapel->semester) .'</b>';

		$output = array(
			'option' 		=> $option,
			'callout'	 	=> $callout,
		);

		echo json_encode($output);
	}

	public function presence($id = null)
	{
		# Beranda -> Presensi Siswa

		if ($this->session->user_type_id == 1) {

			// Administrator

			$jadwal_pelajaran = $this->jadwal->getRow($id);

			if (!$jadwal_pelajaran) {
				show_404();
			}

			$data = array(
				'folder' 	=> 'Beranda', 
				'title' 	=> 'Presensi Siswa',
				'id'		=> $id,
				'content'	=> $this->_detailJadwal($jadwal_pelajaran),
			);

			$this->include->content('guru/kehadiran_siswa', $data);

		} else {

			$tahun_pelajaran 	= $this->tahun_pelajaran->getRowActive();
			$id_tahun_pelajaran = isset($tahun_pelajaran->tahun_pelajaran_id) ? $tahun_pelajaran->tahun_pelajaran_id : 0;

			$wali_kelas = $this->db->get_where('wali_kelas', [
				'id_user' => $this->session->user_id,
				'id_tahun_pelajaran' => $id_tahun_pelajaran,
			])->row();

			if ($this->session->user_type_id != 4 && empty($wali_kelas->wali_kelas_id)) {
				redirect(site_url());
			}

			$guru_piket = $this->mall->get_guru_piket();
			
			// Guru Piket & Wali Kelas
			
			$kelas 		= $this->db->get_where('kelas', ['md5(kelas_id)' => $id])->row();
			$id_kelas 	= isset($kelas->kelas_id) ? $kelas->kelas_id : 0;

			$data = array(
				'title'					=> 'Presensi Siswa',
				'kelas'					=> $this->mall->get_kelas(),
				'tanggal'				=> $this->tahun_pelajaran->getListTanggal(),
				'id_tahun_pelajaran'	=> $id_tahun_pelajaran,
				'tahun_pelajaran'		=> isset($tahun_pelajaran->tahun_pelajaran_id) ? $tahun_pelajaran->tahun_pelajaran : null,
				'semester'				=> isset($tahun_pelajaran->tahun_pelajaran_id) ? $tahun_pelajaran->semester : 0,
				'id_kelas'				=> $id_kelas,
			);

			if ($this->input->server('REQUEST_METHOD') != 'POST') {
				if ($this->session->user_type_id == 4) {
					$data['id_guru_piket'] = isset($guru_piket->id_guru_piket) ? $guru_piket->id_guru_piket : 0;
					$this->include->topnav('guru/presensi_kelas', $data);
				} else {
					$data['folder'] = 'Wali Kelas';
					$data['id_wali_kelas'] = isset($wali_kelas->wali_kelas_id) ? $wali_kelas->wali_kelas_id : 0;
					$this->include->content('guru/presensi_kelas', $data);
				}

			} else {
				$this->_add_presensi_kelas($id_kelas);
			}
		}
		
	}

	private function _detailJadwal($jadwal)
	{
		$tapel = $this->db->get_where('tahun_pelajaran', ['is_aktif' => 'Y'])->row();
		$siswa = $this->_getSiswa(@$jadwal->id_kelas);

		$callout = '<input type="hidden" name="id_jadwal_pelajaran" value="'. md5(@$jadwal->jadwal_pelajaran_id) .'">';
		$callout .= '<input type="hidden" name="semester" value="'. @$tapel->semester .'">';
		$callout .= '<input type="hidden" name="id_kelas" value="'. md5(@$jadwal->id_kelas) .'">';
		$callout .= '<input type="hidden" name="kelas_id" value="">';
		$callout .= '<b>Mata Pelajaran : '. $this->include->null(@$jadwal->nama_mapel) .'</b>';
		$callout .= '<b>Kelas : '. $this->include->null(@$jadwal->nama_kelas) .'</b>';
		$callout .= '<b>Semester : '. @$tapel->semester . ' / '. $this->include->semester(@$tapel->semester) .'</b>';
		$callout .= '<style type="text/css">.callout{border-left: 3px solid #00A65A; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE; border-bottom: 1px solid #EEEEEE;} .callout b{display: block;}</style>';


		$select = '<select name="user_id" id="user_id" class="form-control select2">';
		$select .= '<option value="">-- Pilih Siswa --</option>';
		foreach ($siswa['result'] as $row) {
			$select .= '<option value="'. md5($row->user_id) .'">'. $row->no_induk .' - '. $row->full_name .'</option>';
		}
		$select .= '</select>';

		return array(
			'callout' 	=> $callout,
			'select'	=> $select,
			'tapel'		=> @$tapel->tahun_pelajaran,
			'siswa'		=> @$siswa['result'],
			'id_kelas'	=> md5(@$jadwal->id_kelas)
		);
	}

	private function _getSiswa($id_kelas, $user_id = NULL)
	{
		$this->db->join('siswa s', 's.id_user = u.user_id', 'left');
		$this->db->where('user_type_id', 3);
		$this->db->where('delete_at', NULL);
		$this->db->where('id_kelas', $id_kelas);
		if ($user_id) {
			$this->db->where_not_in('user_id', $user_id);
		}
		$query = $this->db->get('user u');

		return array(
			'num_rows' => $query->num_rows(),
			'result'   => $query->result(),
		);
	}

	public function showListKelas($id_tahun_pelajaran)
	{
		$this->load->model('Kelas_model', 'kelas');
		$bulider = $this->kelas->getKelas();

		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($bulider['result'] as $field) {
			$start++;
			$row 	= array();

			$wali_kelas = $this->db->join('user', 'user.user_id = wali_kelas.id_user', 'left')->get_where('wali_kelas', [
				'id_kelas' => $field->kelas_id,
				'md5(id_tahun_pelajaran)' => $id_tahun_pelajaran,
			])->row();

			if (isset($wali_kelas->wali_kelas_id)) {
				$nama_wali_kelas = $wali_kelas->id_user ? $wali_kelas->no_induk .' - '. $wali_kelas->full_name : '-';
			} else {
				$nama_wali_kelas = '-';
			}

			$siswa = $this->db->get_where('siswa', ['id_kelas' => $field->kelas_id])->num_rows();
			$anggota_kelas = $siswa > 0 ? $siswa . ' Orang' : '-';

			$presensi_siswa = $this->db->join('siswa', 'siswa.id_user = presensi.id_user', 'left')->where([
				'tanggal'		 => date('Y-m-d'),
				'md5(id_tapel)'	 => $id_tahun_pelajaran,
				'semester'		 => $this->input->post('semester'),
				'id_kelas' 		 => $field->kelas_id,
			])->group_by('presensi_id')->get('presensi')->num_rows();

			$status_presensi = $presensi_siswa > 0 ? '<b>SUDAH<span style="color: #00A65A;">_</span>PRESENSI</b>' : '<b>BELUM<span style="color: #00A65A;">_</span>PRESENSI</b>';
			$button = '<a href="'. site_url('presences/' . md5($field->kelas_id)) .'"  class="btn btn-sm btn-social" style="background-color: #00A65A; color: #FFFFFF;"><i class="fa fa-calendar-plus-o" style="border-right: #00A65A;"></i> '.  $status_presensi .'</a>';


			$row[]	= '<div style="text-align: center;">'. $no++ .'</div>';
			$row[]	= '<div style="text-align: left;">'. $field->nama_kelas .'</div>';
			$row[]	= '<div style="text-align: left;">'. $nama_wali_kelas .'</div>';
			$row[]	= '<div style="text-align: left;">'. $anggota_kelas .'</div>';
			$row[]	= '<div style="text-align: center;">'. $button .'</div>';

			$data[]	= $row;
		}

		echo json_encode([
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $bulider['recordsTotal'],
			'recordsFiltered' 	=> $bulider['recordsFiltered'],
			'data' 				=> $data,
		]);
	}

	public function showPresensiKelas($id_tahun_pelajaran)
	{
		$bulider = $this->siswa->getPresensiKelas();

		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($bulider['result'] as $field) {
			$start++;
			$row 	= array();
			
			if ($field->gender == 'L') {
				$jenis_kelamin = 'Laki-Laki';
			} elseif ($field->gender == 'P') {
				$jenis_kelamin = 'Perempuan';
			} else {
				$jenis_kelamin = '-';
			}

			$query = $this->db->get_where('presensi', [
				'tanggal'		 => $this->input->post('tanggal'),
				'id_user' 		 => $field->user_id,
				'md5(id_tapel)'  => $id_tahun_pelajaran,
				'semester'  	 => $this->input->post('semester'),
			])->row();

			$status_kehadiran = '<input type="hidden" name="id_user_'. $field->user_id .'" value="'. $field->user_id .'">';
			if (isset($query->presensi_id)) {
				$status_kehadiran .= $this->siswa->getPresensiKelas($field->user_id, $query->status)['statusKehadiran'];
			} else {

				$presensi_siswa = $this->db->join('siswa', 'siswa.id_user = presensi.id_user', 'left')->where([
					'tanggal'		 => $this->input->post('tanggal'),
					'md5(id_tapel)'	 => $id_tahun_pelajaran,
					'semester'		 => $this->input->post('semester'),
					'id_kelas' 		 => $field->id_kelas,
				])->group_by('presensi_id')->get('presensi')->num_rows();

				$checkbox = '<div class="checkbox">';
				$checkbox .= '<label>';
				$checkbox .= '<input type="checkbox" value="1" name="status_'. $field->user_id .'" onclick="change_status(' . $field->user_id . ')"> Presensi Siswa';
				$checkbox .= '</label>';
				$checkbox .= '</div>';

				$status_kehadiran .= $presensi_siswa > 0 ? $checkbox : '<b style="text-align: center;">BELUM PRESENSI</b>';
			}

			$row[]	= '<div style="text-align: center;">'. $no++ .'</div>';
			$row[]	= '<div style="text-align: left;">'. $field->no_induk .'</div>';
			$row[]	= '<div style="text-align: left;">'. $field->full_name .'</div>';
			$row[]	= '<div style="text-align: left;">'. $jenis_kelamin .'</div>';
			$row[]	= '<div style="text-align: left;">'. $status_kehadiran .'</div>';

			$data[]	= $row;
		}

		echo json_encode([
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $bulider['recordsTotal'],
			'recordsFiltered' 	=> $bulider['recordsFiltered'],
			'data' 				=> $data,
			'sZeroRecords'		=> $this->input->post('id_kelas') ? 'TIDAK DITEMUKAN' : 'HARUS MEMILIH KELAS',
		]);
	}

	private function _add_presensi_kelas($id_kelas)
	{
		$fields = ['tanggal', 'id_tahun_pelajaran', 'semester'];

		for ($i=0; $i < count($fields); $i++) {
			$this->form_validation->set_rules($fields[$i], '', 'trim|required');
		}

		if ($this->form_validation->run() == FALSE) {
			show_404();
		} else {
			$presensi_siswa = $this->db->join('siswa', 'siswa.id_user = presensi.id_user', 'left')->where([
				'tanggal'		 => $this->input->post('tanggal'),
				'id_tapel'	 	 => $this->input->post('id_tahun_pelajaran'),
				'semester'		 => $this->input->post('semester'),
				'id_kelas' 		 => $id_kelas,
			])->group_by('presensi_id')->get('presensi')->num_rows();

			if ($presensi_siswa < 1) {

				$siswa = $this->db->get_where('siswa', ['id_kelas' => $id_kelas])->result();
				foreach ($siswa as $s) {
					$data = array(
						'tanggal' 	=> $this->input->post('tanggal'), 
						'id_user' 	=> $s->id_user, 
						'id_tapel' 	=> $this->input->post('id_tahun_pelajaran'),
						'semester' 	=> $this->input->post('semester'),
						'status'	=> 1,
					);

					$this->db->insert('presensi', $data);
				}

				echo json_encode(['status' => TRUE]);
			} else {
				echo json_encode(['status' => FALSE]);
			}
		}
	}

	public function changeStatuskehadiran()
	{
		$fields = ['tanggal', 'id_user', 'id_tapel', 'semester', 'status'];

		for ($i=0; $i < count($fields); $i++) {
			$this->form_validation->set_rules($fields[$i], '', 'trim|required');
			if ($i <= 3) {
				$data[$fields[$i]] = htmlspecialchars($this->input->post($fields[$i]));
			}
		}

		if ($this->form_validation->run() == FALSE) {
			
		} else {
			$query = $this->db->get_where('presensi', $data)->row();
			if (isset($query->presensi_id)) {
				$this->db->update('presensi', ['status' => htmlspecialchars($this->input->post('status'))], ['presensi_id' => $query->presensi_id]);
			} else {
				$this->db->insert('presensi', array_merge($data, ['status' => htmlspecialchars($this->input->post('status'))]));
			}
			echo json_encode(['status' => TRUE]);
		}
	}

	public function attendances()
	{
		if ($this->session->user_type_id != 4) {
			redirect(site_url());
		}

		$tahun_pelajaran = $this->tahun_pelajaran->getRowActive();

		$data = array(
			'title' 				=> 'Presensi Siswa',
			'kelas'					=> $this->mall->get_kelas(),
			'id_tahun_pelajaran'	=> isset($tahun_pelajaran->tahun_pelajaran_id) ? $tahun_pelajaran->tahun_pelajaran_id : 0,
			'tahun_pelajaran'		=> isset($tahun_pelajaran->tahun_pelajaran_id) ? $tahun_pelajaran->tahun_pelajaran : null,
			'semester'				=> isset($tahun_pelajaran->tahun_pelajaran_id) ? $tahun_pelajaran->semester : 0,
		);


		$this->include->topnav('guru/rekap_presensi_guru_piket', $data);
	}

	public function showRekapPresensiKelas($id_tahun_pelajaran)
	{
		$bulider = $this->siswa->getPresensiKelas();

		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;

		$id_kelas  = array();
		$arr_siswa = array();
		$arr_hadir = array();
		$arr_color = array();

		foreach ($bulider['result'] as $field) {
			$start++;
			$row 	= array();
			
			if ($field->gender == 'L') {
				$jenis_kelamin = 'Laki-Laki';
			} elseif ($field->gender == 'P') {
				$jenis_kelamin = 'Perempuan';
			} else {
				$jenis_kelamin = '-';
			}

			$row[]	= '<div style="text-align: center;">'. $no++ .'</div>';
			$row[]	= '<div style="text-align: left;">'. $field->no_induk .'</div>';
			$row[]	= '<div style="text-align: left;">'. $field->full_name .'</div>';
			$row[]	= '<div style="text-align: left;">'. $jenis_kelamin .'</div>';

			$presensi_siswa = array(
				'id_user'   => $field->user_id,
				'id_tapel'  => $this->input->post('id_tapel'),
				'semester'  => $this->input->post('semester'),
				'tgl_awal'  => $this->input->post('tgl_awal'),
				'tgl_akhir' => $this->input->post('tgl_akhir'),
			);

			foreach ($this->include->opsiPresensi() as $key => $value) {
				$count_presensi = $this->siswa->countPresensiKelas(array_merge($presensi_siswa, ['status' => $key]));
				$row[]	= '<div style="text-align: center;">'. $count_presensi .'</div>';
			}

			$jml_hadir 		= $this->siswa->countPresensiKelas(array_merge($presensi_siswa, ['status' => 1]));
			$jml_hari  		= count($this->tahun_pelajaran->getListTanggal());
			$perhitungan    = $jml_hadir >= 1 && $jml_hari >= 1 ? $jml_hadir / $jml_hari * 100 : 0;
			$persentase 	= intval($perhitungan) >= 100 ? 100 : round($perhitungan);

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

			$row[]	= '<div style="text-align: center;">'. $progress .'</div>';

			$id_kelas[]		= $field->id_kelas;
			$arr_siswa[] 	= $field->full_name;
			$arr_hadir[] 	= $jml_hadir;
			$arr_color[] 	= $fillColor;

			$data[]	= $row;
		}

		$jml_siswa = $this->siswa->getJmlSiswa($id_kelas);

		echo json_encode([
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $bulider['recordsTotal'],
			'recordsFiltered' 	=> $bulider['recordsFiltered'],
			'data' 				=> $data,
			'jml_siswa'			=> $jml_siswa['total'],
			'laki_laki'			=> $jml_siswa['laki_laki'],
			'perempuan'			=> $jml_siswa['perempuan'],
			'sZeroRecords'		=> $this->input->post('id_kelas') ? 'TIDAK DITEMUKAN' : 'HARUS MEMILIH KELAS',
			'arr_siswa'			=> $arr_siswa,
			'arr_hadir'			=> $arr_hadir,
			'arr_color'			=> $arr_color,
		]);
	}

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
