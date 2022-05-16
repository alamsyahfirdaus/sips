<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();
		user_access();

		$this->load->model('Menu_model', 'menu');

	}

	private $folder = 'Pengaturan';
	private $title 	= 'Menu';

	public function index()
	{
		$data = [
			'folder'	=> $this->folder,
			'title' 	=> $this->title,
			'sub_title' => 'Jenis Pengguna',
		];

		$this->include->content('setting/index_role', $data);
	}

	public function showRole()
	{
		$this->load->model('Role_model', 'role');
		$return = $this->role->getDataTables();
		echo json_encode($return);
	}

	public function access($user_type_id = NULL)
	{
		$query = $this->_getUserType($user_type_id);

		$data = [
			'folder'		=> $this->folder,
			'title' 		=> $this->title,
			'sub_title'		=> $query->type_name,
			'header'		=> $this->title . ' ' . $query->type_name,
			'user_type_id'  => $query->user_type_id,
			'menu'			=> $this->db->get('menu')->result(),
		];

		$this->include->content('setting/index_menu', $data);
	}

	public function showAccessMenu($user_type_id = NULL)
	{
		$query  = $this->_getUserType($user_type_id);
		$return = $this->menu->getDataTables($query->user_type_id);
		echo json_encode($return);
	}

	public function changeMenu()
	{
		$query      	= $this->db->get_where('sub_menu', ['menu_id' => $this->input->post('menu_id')]);
		$num_rows   	= $query->num_rows();
		$result     	= $query->result();
		$user_type_id = $this->_getUserType($this->input->post('user_type_id'))->user_type_id;
		$rows_menu  	= $this->menu->checkedMenu($this->input->post('menu_id'), $user_type_id);

		if ($num_rows) {

		    foreach ($result as $sm) {

		      $sub_menu_id  = $sm->sub_menu_id;
		      $this->_userAccess($user_type_id, $sub_menu_id, $rows_menu);

		    }
		    
		}

		$output = array(
			'status' 	=> TRUE,
			'uti'	 	=> $user_type_id,
		);

		echo json_encode($output);

	}

	public function changeSubMenu()
	{
	    $user_type_id = $this->_getUserType($this->input->post('user_type_id'))->user_type_id;
	    $sub_menu_id  = $this->input->post('sub_menu_id');
	    $this->_userAccess($user_type_id, $sub_menu_id);

	    $output = array(
	    	'status' 	=> TRUE,
	    	'uti'	 	=> $user_type_id,
	    );

	    echo json_encode($output);
	}

	private function _userAccess($user_type_id, $sub_menu_id, $rows_menu = NULL)
	{
		$data = [
			'user_type_id' 	=> $user_type_id,
			'sub_menu_id'	=> $sub_menu_id, 
		];

		$query 			= $this->db->get_where('user_access', $data);
		$rows_sub_menu 	= $query->num_rows();
		$user_access_id = @$query->row()->user_access_id;

		if ($rows_menu || $rows_sub_menu) {
			$this->db->delete('user_access', ['user_access_id' => $user_access_id]);
		} else {
			$this->db->insert('user_access', $data);
		}
	}

	private function _getUserType($id)
	{
		$query = $this->db->get_where('user_type', ['md5(user_type_id)' => $id])->row();

		if (!$query) {
			show_404();
		} else {
			return $query;
		}
	}

	public function sortMenu($menu_id = NULL)
	{
		$query = $this->db->get_where('menu', ['md5(menu_id)' => $menu_id])->row();

		if (!$query) {
			show_404();
		}

		$this->db->update('menu', ['sort' => time()], ['menu_id' => $query->menu_id]);

		echo json_encode(['status' => TRUE]);
	}

	public function sortSubMenu($sub_menu_id = NULL)
	{
		$query = $this->db->get_where('sub_menu', ['md5(sub_menu_id)' => $sub_menu_id])->row();

		if (!$query) {
			show_404();
		}

		$this->db->update('sub_menu', ['sort' => time()], ['sub_menu_id' => $query->sub_menu_id]);

		echo json_encode(['status' => TRUE]);
	}

	public function deleteSubMenu($sub_menu_id = NULL)
	{
		$query = $this->db->get_where('sub_menu', ['md5(sub_menu_id)' => $sub_menu_id])->row();

		if (!$query) {
			show_404();
		}

		$this->db->delete('sub_menu', ['sub_menu_id' => $query->sub_menu_id]);

		$output = array(
			'status' 	=> TRUE,
			'message'	=> 'Berhasil Menghapus Sub Menu'
		);

		echo json_encode($output);
	}

	public function deleteMenu($menu_id = NULL)
	{
		$query = $this->db->get_where('menu', ['md5(menu_id)' => $menu_id])->row();

		if (!$query) {
			show_404();
		}

		$this->db->delete('menu', ['menu_id' => $query->menu_id]);

		$output = array(
			'status' 	=> TRUE,
			'message'	=> 'Berhasil Menghapus Menu'
		);

		echo json_encode($output);
	}

	public function getDataMenu($menu_id = NULL)
	{
		$query = $this->db->get_where('menu', ['md5(menu_id)' => $menu_id])->row();

		if (!$query) {
			show_404();
		}

		$data = array(
			'menu' 		=> @$query->menu,
			'icon'	 	=> @$query->icon,
			'url_menu' 	=> @$query->url_menu,
		);

		echo json_encode($data);
	}

	public function saveMenu()
	{
		$query 		= $this->db->get_where('menu', ['md5(menu_id)' => $this->input->post('menu_id')])->row();

		if (@$query) {
			$menu 	= $query->menu != $this->input->post('menu') ? "|is_unique[menu.menu]" : "";
			$rows 	= $this->db->get_where('sub_menu', ['menu_id' => $query->menu_id])->num_rows();
			$url 	= $rows > 0 ? "" : "|required";
		} else {
			$menu = '|is_unique[menu.menu]';
			$url  = '';
		}

		$this->form_validation->set_error_delimiters('', '');
	    $this->form_validation->set_rules('menu', 'Menu', 'trim|required' . $menu);
	    $this->form_validation->set_rules('icon', 'Icon', 'trim|required');
	    $this->form_validation->set_rules('url_menu', 'Url', 'trim|valid_url' . $url);


		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('valid_url', '{field} tidak valid');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');

		if ($this->form_validation->run() == FALSE) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'menu'     		=> form_error('menu'),
			        'icon'     		=> form_error('icon'),
			        'url_menu'     	=> form_error('url_menu'),
			    )
			];

		} else {
			$data = array(
				'menu' 		=> ucwords($this->input->post('menu')),
				'icon'		=> $this->input->post('icon'),
				'url_menu' 	=> $this->input->post('url_menu') ? $this->input->post('url_menu') : NULL,
			);

			if (@$query) {
				$this->db->update('menu', $data, ['menu_id' => $query->menu_id]);
				$output['message'] 	= 'Berhasil Mengubah Menu';
			} else {
				$data['sort'] = time();
				$this->db->insert('menu', $data);
				$output['message'] 	= 'Berhasil Menambah Menu';
			}

			$output['status'] 	= TRUE;
		}

		echo json_encode($output);
	}

	public function getMenu()
	{
		$query = $this->db->get('menu')->result();
		echo json_encode($query);
	}

	public function getDataSubMenu($sub_menu_id = NULL)
	{
		$query = $this->db->get_where('sub_menu', ['md5(sub_menu_id)' => $sub_menu_id])->row();

		if (!$query) {
			show_404();
		}

		$data = array(
			'sub_menu' 	=> @$query->sub_menu,
			'menu_id'	=> @$query->menu_id,
			'url' 		=> @$query->url,
		);

		echo json_encode($data);
	}

	public function saveSubMenu()
	{
		$query = $this->db->get_where('sub_menu', ['md5(sub_menu_id)' => $this->input->post('sub_menu_id')])->row();

		// if (@$query) {
		// 	$sub_menu = $query->sub_menu != $this->input->post('sub_menu') ? "|is_unique[sub_menu.sub_menu]" : "";
		// } else {
		// 	$sub_menu = '|is_unique[sub_menu.sub_menu]';
		// }

		$this->form_validation->set_error_delimiters('', '');
	    $this->form_validation->set_rules('sub_menu', 'Sub Menu', 'trim|required' . @$sub_menu);
	    $this->form_validation->set_rules('url', 'Url', 'trim|required|valid_url');
	    $this->form_validation->set_rules('id_menu', 'Menu', 'trim|required');


		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('valid_url', '{field} tidak valid');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');

		if ($this->form_validation->run() == FALSE) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'sub_menu'     	=> form_error('sub_menu'),
			        'url'     		=> form_error('url'),
			        'id_menu'   	=> form_error('id_menu'),
			    )
			];

		} else {
			$data = array(
				'sub_menu' 		=> ucwords($this->input->post('sub_menu')),
				'url' 			=> $this->input->post('url'),
				'menu_id'		=> $this->input->post('id_menu'),
			);

			if (@$query) {
				$this->db->update('sub_menu', $data, ['sub_menu_id' => $query->sub_menu_id]);
				$output['message'] 	= 'Berhasil Mengubah Sub Menu';
			} else {
				$data['sort'] = time();
				$this->db->insert('sub_menu', $data);
				$output['message'] 	= 'Berhasil Menambah Sub Menu';
			}

			$output['status'] = TRUE;
		}

		echo json_encode($output);
	}
}

/* End of file Menu.php */
/* Location: ./application/controllers/setting/Menu.php */
