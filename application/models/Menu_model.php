<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

	private $table 			= 'menu m';
	private $columnOrder	= ['m.menu_id', NULL];
	private $columnSearch	= ['m.menu_id', 'menu', 'icon', 'url_menu', 'sub_menu', 'url'];
	private $orderBy		= ['m.menu_id' => 'ASC'];

	private function _setJoin()
	{	
		$this->db->select('m.*');
		$this->db->join('sub_menu sm', 'sm.menu_id = m.menu_id', 'left');
		$this->db->group_by('m.menu_id');
		$this->db->order_by('m.sort', 'asc');
	}

	private function _setLimit()
	{
		$limit = $this->input->post('length') + 1 + $this->input->post('start');
		$this->db->limit($limit);
	}

	private function _setBuilder()
	{
		$this->_setJoin();
		$this->_setLimit();
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnSearch, $this->columnSearch, $this->orderBy);
	}

	public function getDataTables($user_type_id)
	{
		$query 	= $this->include->getResult($this->_setBuilder());
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query as $field) {
			$start++;
			$row 	= array();
			// $row[]  = $no++;
			$row[]  = $this->_getField($no++);
			$row[]	= $this->_setMenu($field, $user_type_id);
			$row[]	= $this->_setSubMenu($field->menu_id, $user_type_id);
			$data[]	= $row;
		}

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder())->num_rows(),
			'data' 				=> $data,
		];

		return $setData;
	}

	private function _getField($value)
	{
		$field 	= '<table class="table table-striped">';
		$field 	.= '<thead>';
		$field 	.= '<tr>';
		$field 	.= '<td>'. $value .'</td>';
		$field 	.= '</tr>';
		$field 	.= '</thead>';
		$field 	.= '</table>';

		return $field;
	}

	private function _setMenu($field, $user_type_id)
	{

	    $menu = '<table class="table table-striped">';
	    $menu .= '<tr>';

	    if ($field->url_menu) {
		    $menu .= '<td style="width: 5%;"><i class="fa fa-check-square" style="color: #337AB7;"></i></td>';
	    } else {
			$checked  	= $this->checkedMenu($field->menu_id, $user_type_id);
			$sub_menu  	= $this->_querySubMenu($field->menu_id);

			if ($checked) {
		    	// if ($user_type_id == 1 && $checked == $sub_menu['num_rows']) {
		    	// 	$menu .= '<td style="width: 5%;"><i class="fa fa-check-square" style="color: #337AB7;"></i></td>';
		    	// } else {
		    		$menu .= '<td style="width: 5%;"><input type="checkbox" onclick="checked_menu('. $field->menu_id . ')" id="menu_'. $field->menu_id .'" value="' . $field->menu_id . '" checked></td>';
		    	// }
			} else {
			    $menu .= '<td style="width: 5%;"><input type="checkbox" onclick="checked_menu('. $field->menu_id . ')" id="menu_'. $field->menu_id .'" value="' . $field->menu_id . '"></td>';
			}

	    }

	    if ($user_type_id == 1) {
		    $menu .= '<td><a href="javascript:void(0)" style="color: #337AB7;" onclick="sort_menu(' . "'" . md5($field->menu_id) . "'" . ')" title="Sort Menu">'. $field->menu .'</a></td>';

		    // $menu .= '<td style="text-align: right;"><a href="javascript:void(0)" onclick="delete_menu(' . "'" . md5($field->menu_id) . "'" . ')" title="Hapus"><i class="fa fa-trash"></i></a></td>';

		    $menu .= '<td style="text-align: right;">';
		    $menu .= $this->_btnMenu($field->menu_id);
		    $menu .= '</td>';
	    } else {
		    $menu .= '<td>'. $field->menu .'</td>';
	    }

	    $menu .= '</tr>';
	    $menu .= '</table>';
		return $menu;
	}

	private function _btnMenu($menu_id)
	{
		$button 	= '<div class="btn-group">';
		$button		.= '<button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i></button>';
		$button		.= '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
		$button		.= '<li><a href="javascript:void(0)" onclick="edit_menu(' . "'" . md5($menu_id) . "'" . ');">Edit</a></li>';
		$button		.= '<li class="divider"></li>';
		$button		.= '<li><a href="javascript:void(0)" onclick="delete_menu(' . "'" . md5($menu_id) . "'" . ')">Hapus</a></li>';
		$button		.= '</ul>';
		$button		.= '</div>';

		return $button;
	}

	private function _setSubMenu($menu_id, $user_type_id)
	{
		$query 		= $this->_querySubMenu($menu_id);

	    $sub_menu 	= '<table class="table table-striped">';
	    
	    if ($query['num_rows']) {

			foreach ($query['result'] as $field) {
				$checked = $this->_checkedSubMenu($field->sub_menu_id, $user_type_id);

			    $sub_menu .= '<tr>';

			    if ($checked) {
			    	// if ($user_type_id == 1) {
					   //  $sub_menu .= '<td style="width: 5%;"><i class="fa fa-check-square" style="color: #337AB7;"></i></td>';
			    	// } else {
					    $sub_menu .= '<td style="width: 5%;"><input type="checkbox" onclick="checked_sub_menu('. $field->sub_menu_id . ')" class="sub_menu_'. $field->menu_id .'" value="' . $field->sub_menu_id . '" checked></td>';
			    	// }
			    } else {
				    $sub_menu .= '<td style="width: 5%;"><input type="checkbox" onclick="checked_sub_menu('. $field->sub_menu_id . ')" class="sub_menu_'. $field->menu_id .'" value="' . $field->sub_menu_id . '"></td>';
			    }

			    if ($user_type_id == 1) {
				    $sub_menu .= '<td><a href="javascript:void(0)" style="color: #337AB7;" onclick="sort_sub_menu(' . "'" . md5($field->sub_menu_id) . "'" . ')" title="Sort Sub Menu">'. $field->sub_menu .'</a></td>';

				    // $sub_menu .= '<td style="text-align: right;"><a href="javascript:void(0)" onclick="delete_sub_menu(' . "'" . md5($field->sub_menu_id) . "'" . ')" title="Hapus"><i class="fa fa-trash"></i></a></td>';

				    $sub_menu .= '<td style="text-align: right;">';
				    $sub_menu .= $this->_btnSubMenu($field->sub_menu_id);
				    $sub_menu .= '</td>';

			    } else {
				    $sub_menu .= '<td>'. $field->sub_menu .'</td>';
			    }
			    $sub_menu .= '</tr>';

			}
	    } else {
	    	$sub_menu .= '<tr><td colspan="3">-</td></tr>';
	    }

	    $sub_menu .= '</table>';
		return $sub_menu;
	}

	private function _btnSubMenu($sub_menu_id)
	{
		$button 	= '<div class="btn-group">';
		$button		.= '<button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i></button>';
		$button		.= '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
		$button		.= '<li><a href="javascript:void(0)" onclick="edit_sub_menu(' . "'" . md5($sub_menu_id) . "'" . ');">Edit</a></li>';
		$button		.= '<li class="divider"></li>';
		$button		.= '<li><a href="javascript:void(0)" onclick="delete_sub_menu(' . "'" . md5($sub_menu_id) . "'" . ')">Hapus</a></li>';
		$button		.= '</ul>';
		$button		.= '</div>';

		return $button;
	}

	public function checkedMenu($menu_id, $user_type_id)
	{
		$this->db->join('sub_menu sm', 'sm.sub_menu_id = ua.sub_menu_id', 'left');
		$this->db->where('sm.menu_id', $menu_id);
		$this->db->where('ua.user_type_id', $user_type_id);
		return $this->db->get('user_access ua')->num_rows();
	}

	private function _checkedSubMenu($sub_menu_id, $user_type_id)
	{
		$this->db->where('sub_menu_id', $sub_menu_id);
		$this->db->where('user_type_id', $user_type_id);
		return $this->db->get('user_access')->num_rows();
	}

	private function _querySubMenu($menu_id)
	{
		$this->db->where('menu_id', $menu_id);
		$this->db->order_by('sort', 'asc');
		$query = $this->db->get('sub_menu');

		$data = [
			'num_rows' 	=> $query->num_rows(),
			'result'	=> $query->result() 
		];

		return $data;
	}

}

/* End of file Menu_model.php */
/* Location: ./application/models/Menu_model.php */