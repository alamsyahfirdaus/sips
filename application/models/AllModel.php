<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AllModel extends CI_Model {

	public function getSession()
	{
		$this->db->select('u.*');
		$this->db->select('ut.type_name');
		$this->db->join('user_type ut', 'ut.user_type_id = u.user_type_id', 'left');
		$this->db->where('u.user_id', $this->session->user_id);
		return $this->db->get('user u')->row();
	}

	public function getLogo()
	{
		$query = $this->db->get_where('image_slider', ['id_slider' => 1])->row();
		return base_url(IMAGE . $this->include->image(@$query->gambar));
	}


	public function getSubMenu($user_type_id, $menu_id)
	{
	    $this->db->join('sub_menu sm', 'sm.sub_menu_id = ua.sub_menu_id', 'left');
	    $this->db->where('ua.user_type_id', $user_type_id);
	    $this->db->where('sm.menu_id', $menu_id);
	    $this->db->order_by('sm.sort', 'asc');
	    $query 	= $this->db->get('user_access ua');
	    $data 	= array(
	        'num_rows' => $query->num_rows() > 0 ? TRUE : FALSE,
	        'result'   => $query->result(),
	    );

	    return $data;
	}

	public function get_kelas()
	{
		$this->db->where('delete_at', NULL);
		$this->db->order_by('id_tingkat_kelas', 'asc');
		$this->db->order_by('urutan_kelas', 'asc');
		$query = $this->db->get('kelas');

		return $query->result();
	}

	public function get_tapel()
	{
		$this->db->where('delete_at', NULL);
		$query = $this->db->get('tahun_pelajaran');

		return $query->result();
	}

	public function get_guru_piket()
	{
		$this->load->model('Tapel_model', 'tahun_pelajaran');
		$tahun_pelajaran 	= $this->tahun_pelajaran->getRowActive();
		$id_tahun_pelajaran = isset($tahun_pelajaran->tahun_pelajaran_id) ? $tahun_pelajaran->tahun_pelajaran_id : 0;
		$id_user 			= $this->session->user_id;

		$this->db->select('gp.id_guru_piket, dgp.id_detail_guru_piket, gp.id_tahun_pelajaran, gp.hari, dgp.id_user');
		$this->db->join('guru_piket gp', 'gp.id_guru_piket = dgp.id_detail_guru_piket', 'left');
		$this->db->where('gp.id_tahun_pelajaran', $id_tahun_pelajaran);
		$this->db->where('dgp.id_user', $id_user);
		$query = $this->db->get('guru_piket dgp');
		return $query->row();
	}

}

/* End of file AllModel.php */
/* Location: ./application/models/AllModel.php */