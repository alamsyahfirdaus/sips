<?php

	function logged_in()
	{
		$ci =& get_instance();
		if (!$ci->session->user_id) {
			redirect('login');
		} 
	}

	function logged_out()
	{
		$ci =& get_instance();
		if ($ci->session->user_id) {
			redirect('home');
		}
	}

	function user_access()
	{
		$ci =& get_instance();

		$query1 = $ci->db->get_where('sub_menu', ['route' => $ci->uri->segment(1)])->row();

		if (isset($query1->sub_menu_id)) {
			$query2 = $ci->db->get_where('sub_menu', ['url' => $query1->url])->row();
			if (isset($query2->sub_menu_id)) {
				$user_access = $ci->db->get_where('user_access', [
					'user_type_id' 	=> $ci->session->user_type_id,
					'sub_menu_id'	=> $query2->sub_menu_id,
				])->num_rows();
				if ($user_access < 1) {
					redirect('home');
				}
			} else {
				redirect('home');
			}
		} else {
			$segment1 		= $ci->uri->segment(1);
			$segment2		= $ci->uri->segment(2);
			$uri_segment 	= $segment1 . '/' . $segment2;
			$sub_menu 		= $ci->db->get_where('sub_menu', ['url' => $uri_segment])->row();
			if (isset($sub_menu->sub_menu_id)) {
				$user_access	= $ci->db->get_where('user_access', [
					'user_type_id' 	=> $ci->session->user_type_id,
					'sub_menu_id'	=> $sub_menu->sub_menu_id,
				])->num_rows();
				if ($user_access < 1) {
					redirect('home');
				}
			} else {
				redirect('home');
			}
		}
	}

/* End of file auth_helper.php */
/* Location: ./application/helpers/auth_helper.php */
