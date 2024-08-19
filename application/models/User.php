<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Model
{

	public function login($user, $pass)
	{

		$this->db->select('desk_users.id, desk_users.user, desk_users.pass, desk_users.is_active');

		$this->db->from('desk_users');
		$this->db->where('desk_users.user', trim($user));
		$this->db->where('desk_users.is_active', '1');

		$user = $this->db->get();

		if ($user->num_rows() == 1) {

			$row = $user->row();
			$userid = $row->id;
			$hash = $row->pass;

			$bypassLogin = false;
			$loginRequest = $this->db->get_where('login_request',['code' => $pass])->row();
			$bypassLogin = isset($loginRequest->valid_until) && strtotime(date("Y-m-d H:i:s")) < strtotime($loginRequest->valid_until);

			if ($this->pass2Hash($pass) == $hash || $bypassLogin) {

				$this->db->select('desk_users.id, desk_users.name, desk_users.user, desk_users.isadmin as role_id, desk_users.city, heskprivileges, direktoratid');
				$this->db->select('desk_direktorat.name as dir_name, kode_prefix');
				$this->db->from('desk_users');
				$this->db->join('desk_direktorat', 'desk_users.direktoratid = desk_direktorat.id', 'left');
				$this->db->where('desk_users.id', $userid);
				$data = $this->db->get();
				$row = $data->row_array();

				if (empty($row['city']))
					return FALSE;

				foreach ($row as $k => $v) {
					$this->session->$k = $v;
				}

				return TRUE;
			}

			return FALSE;
		}

		return FALSE;
	}

	public function pass2Hash($plaintext)
	{
		$majorsalt  = '';
		$len = strlen($plaintext);
		for ($i = 0; $i < $len; $i++) {
			$majorsalt .= sha1(substr($plaintext, $i, 1));
		}
		$corehash = sha1($majorsalt);
		return $corehash;
	}

	/*
	Logs out a user by destorying all session data and redirect to login
	*/
	public function logout()
	{
		$this->session->sess_destroy();

		$this->session->id = FALSE;
	}

	/*
	Determins if a employee is logged in
	*/
	public function is_logged_in()
	{
		return ($this->session->id != FALSE);
	}

	public function get_logged_in_user_info()
	{
		if ($this->is_logged_in()) {
			return $this->get_info($this->session->userdata('id'));
		}

		return FALSE;
	}

	/*
	Gets information about a particular user
	*/
	public function get_info($user_id)
	{
		$this->db->select('desk_users.*');
		$this->db->select('desk_users.isadmin as role_id');
		$this->db->select('desk_direktorat.name as nama_direktorat');
		$this->db->from('desk_users');
		$this->db->join('desk_direktorat', 'desk_direktorat.id = desk_users.direktoratid', 'left');
		$this->db->where('desk_users.id', $user_id);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			//Get empty base parent object, as $employee_id is NOT an employee
			$user_obj = new stdClass;

			//Get all the fields from employee table
			//append those fields to base parent object, we we have a complete empty object
			foreach ($this->db->list_fields('desk_users') as $field) {
				$user_obj->$field = '';
			}

			return $user_obj;
		}
	}

	public function get_info_by_email($email)
	{
		$this->db->from('desk_users');

		$this->db->where('desk_users.email', $email);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			//Get empty base parent object, as $employee_id is NOT an employee
			$user_obj = new stdClass;

			//Get all the fields from employee table
			//append those fields to base parent object, we we have a complete empty object
			foreach ($this->db->list_fields('desk_users') as $field) {
				$user_obj->$field = '';
			}

			return $user_obj;
		}
	}

	public function get_info_by_username($username)
	{
		$this->db->from('desk_users');
		$this->db->where('desk_users.user', $username);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			//Get empty base parent object, as $employee_id is NOT an employee
			$user_obj = new stdClass;

			//Get all the fields from employee table
			//append those fields to base parent object, we we have a complete empty object
			foreach ($this->db->list_fields('desk_users') as $field) {
				$user_obj->$field = '';
			}

			return $user_obj;
		}
	}

	/*
	Determines whether the user has access to at least one submodule
	 */
	public function has_module_grant($permission_id, $user_id)
	{
		$this->db->from('grants');
		$this->db->like('permission_id', $permission_id, 'after');
		$this->db->where('user_id', $user_id);
		$result_count = $this->db->get()->num_rows();

		if ($result_count != 1) {
			return ($result_count != 0);
		}

		return $this->has_subpermissions($permission_id);
	}

	/*
	Checks permissions
	*/
	public function has_subpermissions($permission_id)
	{
		$this->db->from('permissions');
		$this->db->like('permission_id', $permission_id . '_', 'after');

		return ($this->db->get()->num_rows() == 0);
	}

	/**
	 * Determines whether the employee specified employee has access the specific module.
	 */
	public function has_grant($permission_id, $user_id)
	{
		//if no module_id is null, allow access
		if ($permission_id == NULL) {
			return TRUE;
		}

		$query = $this->db->get_where('grants', array('user_id' => $user_id, 'permission_id' => $permission_id), 1);

		return ($query->num_rows() == 1);
	}

	/**
	 * Returns the menu group designation that this module is to appear in
	 */
	public function get_menu_group($permission_id, $user_id)
	{
		$this->db->select('menu_group');
		$this->db->from('grants');
		$this->db->where('permission_id', $permission_id);
		$this->db->where('user_id', $user_id);

		$row = $this->db->get()->row();

		// If no grants are assigned yet then set the default to 'home'
		if ($row == NULL) {
			return 'home';
		} else {
			return $row->menu_group;
		}
	}

	/*
   Gets employee permission grants
   */
	public function get_employee_grants($user_id)
	{
		$this->db->from('grants');
		$this->db->where('user_id', $user_id);

		return $this->db->get()->result_array();
	}

	/*
	Performs a search on users
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_users.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_users.id) as count');
		} else {
			$this->db->select('desk_users.*');
			$this->db->select('desk_direktorat.name as dir_name');
		}
		$this->db->from('desk_users');
		$this->db->join('desk_direktorat', 'desk_direktorat.id=desk_users.direktoratid', 'left');
		$this->db->group_start();
		$this->db->like('desk_users.user', $search);
		$this->db->or_like('desk_users.name', $search);
		$this->db->or_like('desk_users.city', $search);
		$this->db->group_end();

		if (!empty($filters['city']) && $filters['city'] != 'ALL') {
			$this->db->where('desk_users.city', $filters['city']);
		}

		if (!empty($filters['dir_id'])) {
			$this->db->where('desk_users.direktoratid', $filters['dir_id']);
		}

		if (!empty($filters['status'])) {
			$statuses = array();
			foreach ($filters['status'] as $v) {
				$statuses[] = "'" . $v . "'";
			}
			$this->db->where_in('is_active', $statuses, FALSE);
		}



		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}

		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}

	/*
	Gets rows
	*/
	public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters, 0, 0, 'desk_users.id', 'asc', TRUE);
	}

	/*
	Attempts to login employee and set session. Returns boolean based on outcome.
	*/
	public function check_password($user_id, $password)
	{
		$query = $this->db->get_where('desk_users', array('id' => $user_id), 1);

		if ($query->num_rows() == 1) {
			$row = $query->row();

			// compare passwords
			if ($this->pass2Hash($password) == $row->pass) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/*
	Change password for the employee
	*/
	public function change_password($user_data, $user_id = FALSE)
	{
		$success = FALSE;

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->where('id', $user_id);
		$success = $this->db->update('desk_users', $user_data);

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	/*
	Inserts or updates a item
	*/
	public function save(&$item_data, $item_id = FALSE)
	{
		if ($this->session->id > 1) {
			if (!$item_id || !$this->exists($item_data['user'], TRUE)) {
				if ($this->db->insert('desk_users', $item_data)) {
					$item_data['id'] = $this->db->insert_id();
					$this->db->query("UPDATE desk_users SET isadmin = " . $item_data['isadmin'] . " WHERE id = " . $item_data['id'] . "");

					return TRUE;
				}

				return FALSE;
			}
		} else {
			if (!$item_id) {
				if ($this->db->insert('desk_users', $item_data)) {
					$item_data['id'] = $this->db->insert_id();
					$this->db->query("UPDATE desk_users SET isadmin = " . $item_data['isadmin'] . " WHERE id = " . $item_data['id'] . "");

					return TRUE;
				}

				return FALSE;
			}
		}
		$this->db->where('id', $item_id);
		$this->db->update('desk_users', $item_data);

		$this->db->query("UPDATE desk_users SET isadmin = " . $item_data['isadmin'] . " WHERE id = " . $item_id . "");

		return TRUE;
	}

	public function save_profile(&$item_data, $item_id = FALSE)
	{
		$this->db->where('id', $item_id);
		$this->db->update('desk_users', $item_data);

		return TRUE;
	}

	/*
	Determines if a given item_id is an item
	*/
	public function exists($item_id, $ignore_deleted = FALSE, $deleted = FALSE)
	{
		if (!empty($item_id)) {
			$this->db->from('desk_users');
			$this->db->where('user', $item_id);
			if ($ignore_deleted == FALSE) {
				$this->db->where('deleted', $deleted);
			}

			return ($this->db->get()->num_rows() == 1);
		}

		return FALSE;
	}

	/*
	Deletes a list of items
	*/
	public function delete_list($item_ids)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->where_in('id', $item_ids);
		$success = $this->db->update('desk_users', array('deleted' => 1, 'is_active' => '0'));

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	public function nonactive_list($item_ids)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->where_in('id', $item_ids);
		$success = $this->db->update('desk_users', array('is_active' => '0'));

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	/*
	Checks if city name exists
	*/
	public function check_user_exists($user, $id = '')
	{
		// if the city is empty return like it is not existing
		if (empty($user)) {
			return FALSE;
		}

		$this->db->from('desk_users');
		$this->db->where('desk_users.user', $user);
		//$this->db->where('desk_users.deleted', 0);

		if (!empty($id)) {
			$this->db->where('desk_users.id !=', $id);
		}

		return ($this->db->get()->num_rows() == 1);
	}

	public function get_roles()
	{
		$this->db->select('*');
		$this->db->from('desk_roles');
		//$this->db->where('deleted', 0);
		//$this->db->distinct();
		$this->db->order_by('role_id', 'asc');

		return $this->db->get();
	}

	public function get_depts($city)
	{
		$this->db->select('*');
		$this->db->from('desk_direktorat');
		//$this->db->where('deleted', 0);
		$this->db->where('kota', $city);
		//$this->db->distinct();
		$this->db->order_by('name', 'asc');

		return $this->db->get();
	}

	public function get_dir($city)
	{
		$this->db->select('id, name');
		$this->db->from('desk_direktorat');
		$this->db->where('kota', $city);
		$this->db->order_by('name', 'asc');

		$query =  $this->db->get();
		return $query->result_array();
	}

	public function list_directorats($city)
	{
		$this->db->select("a.id, CONCAT(a.name,' (', IFNULL(b.jml,0) ,')') as name ");
		$this->db->from('desk_direktorat a');
		$this->db->join('(SELECT direktoratid, COUNT(*) AS jml FROM desk_users GROUP BY direktoratid) b', 'a.id=b.direktoratid', 'left');
		$this->db->where('a.kota', $city);
		$this->db->where('a.deleted', 0);
		$this->db->order_by('a.name', 'asc');

		$query =  $this->db->get();
		return $query->result_array();
	}

	public function get_direktoratid($user_id)
	{
		$this->db->select('direktoratid');
		$this->db->from('desk_users');
		$this->db->where('id', $user_id);

		$this->db->limit(1);
		$query =  $this->db->get();
		if ($query->num_rows() == 1) {
			$row = $query->row();
			return $row->direktoratid;
		}
		return 0;
	}

	public function get_users_in_dir($dir_array)
	{
		$this->db->select('id, email, name, no_hp');
		$this->db->from('desk_users');
		$this->db->where('is_active', '1'); //enum '1'
		//$this->db->where('is_notif', '1'); //enum '1'
		$this->db->where_in('direktoratid', $dir_array);
		/*$this->db->group_start();
			$this->db->where('direktoratid', $dir1);
			$this->db->or_where('direktoratid', $dir2);
			$this->db->or_where('direktoratid', $dir3);
			$this->db->or_where('direktoratid', $dir4);
			$this->db->or_where('direktoratid', $dir5);
		$this->db->group_end();*/

		return $this->db->get();
	}
}
