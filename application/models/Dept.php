<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Dept class
 */

class Dept extends CI_Model
{
	/**
	 * Determines whether the city specified city has access the specific module.
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

	/*
	Determines if a given item_id is an item
	*/
	public function exists($item_id, $ignore_deleted = FALSE, $deleted = FALSE)
	{
		// check if $item_id is a number and not a string starting with 0
		// because cases like 00012345 will be seen as a number where it is a barcode
		if (ctype_digit($item_id) && substr($item_id, 0, 1) != '0') {
			$this->db->from('items');
			$this->db->where('item_id', (int) $item_id);
			if ($ignore_deleted == FALSE) {
				$this->db->where('deleted', $deleted);
			}

			return ($this->db->get()->num_rows() == 1);
		}

		return FALSE;
	}



	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('desk_direktorat');
		$this->db->where('deleted', 0);

		return $this->db->count_all_results();
	}



	/*
	Get number of rows
	*/
	public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters, 0, 0, 'desk_direktorat.id', 'asc', TRUE);
	}

	/*
	Perform a search on items
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_direktorat.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_direktorat.id) as count');
		} else {

			$this->db->select('desk_direktorat.*');
		}

		$this->db->from('desk_direktorat');
		$this->db->where('deleted', 0);

		if (!empty($filters['kota'])) {
			$this->db->where('kota', $filters['kota']);
		}

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_direktorat.name', $search);
			$this->db->or_like('desk_direktorat.kota', $search);
			$this->db->or_like('desk_direktorat.kode_prefix', $search);
			$this->db->group_end();
		}


		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}

	/*
	Returns all the items
	*/
	public function get_all($stock_location_id = -1, $rows = 0, $limit_from = 0)
	{
		$this->db->from('t_standards');
		//$this->db->join('suppliers', 'suppliers.person_id = items.supplier_id', 'left');

		$this->db->where('t_standards.deleted', 0);

		// order by name of item
		$this->db->order_by('t_standards.sarana', 'asc');

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}


	/*
	Gets information about a particular item
	*/
	public function get_info($item_id)
	{
		$this->db->select('desk_direktorat.*');

		$this->db->from('desk_direktorat');

		$this->db->where('desk_direktorat.id', $item_id);

		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach ($this->db->list_fields('desk_direktorat') as $field) {
				$item_obj->$field = '';
			}

			return $item_obj;
		}
	}






	/*
	Inserts or updates a item
	*/
	public function save(&$item_data, $item_id = FALSE)
	{
		if ($item_id == -1 /*|| !$this->exists($item_id, TRUE)*/) {
			if ($this->db->insert('desk_direktorat', $item_data)) {
				$item_data['id'] = $this->db->insert_id();

				return TRUE;
			}

			return FALSE;
		}

		$this->db->where('id', $item_id);

		return $this->db->update('desk_direktorat', $item_data);
	}

	/*
	Updates multiple items at once
	*/
	public function update_multiple($item_data, $item_ids)
	{
		//$this->db->where_in('item_id', explode(':', $item_ids));

		//return $this->db->update('items', $item_data);
	}

	/*
	Deletes one item
	*/
	public function delete($item_id)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->where('id', $item_id);
		$success = $this->db->update('desk_direktorat', array('deleted' => 1));


		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	/*
	Undeletes one item
	*/
	public function undelete($item_id)
	{
		$this->db->where('id', $item_id);

		return $this->db->update('desk_direktorat', array('deleted' => 0));
	}

	/*
	Deletes a list of items
	*/
	public function delete_list($item_ids)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();


		$this->db->where_in('id', $item_ids);
		$success = $this->db->update('desk_direktorat', array('deleted' => 1));

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	public function get_dept_name_by_id($id)
	{
		if ($id == 0) return '';

		$this->db->select('name');
		$this->db->from('desk_direktorat');
		$this->db->where('id', $id);


		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row()->name;
		}

		return '';
	}

	public function get_units($kota = '')
	{

		//$kota = str_replace('_', ' ', $kota);

		$this->db->from('desk_direktorat');
		if (!empty($kota))
			$this->db->where('kota', $kota);
		$this->db->where('deleted', 0);

		$this->db->order_by('name', 'asc');

		$query =  $this->db->get();
		return $query->result();
	}

	/*
	public function get_cities()
	{
		$this->db->select('nama_kota');
		$this->db->from('desk_kota');
		$this->db->where('deleted', 0);
		$this->db->distinct();
		$this->db->order_by('nama_kota', 'asc');

		return $this->db->get();
	}*/

	public function get_direktorat($kota)
	{
		$this->db->select('name, id');
		$this->db->from('desk_direktorat');
		if ($kota != '') {
			$this->db->where('kota', $kota);
		}

		$this->db->where('deleted', 0);
		$this->db->order_by('name', 'asc');

		return $this->db->get();
	}
}
