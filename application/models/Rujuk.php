<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Rujuk class
 */

class Rujuk extends CI_Model
{
	/**
	 * Determines whether the city specified city has access the specific module.
	 */
	public function has_grant($permission_id, $user_id)
	{
		//if no module_id is null, allow access
		if($permission_id == NULL)
		{
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
		if(ctype_digit($item_id) && substr($item_id, 0, 1) != '0')
		{
			$this->db->from('items');
			$this->db->where('item_id', (int) $item_id);
			if($ignore_deleted == FALSE)
			{
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
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(desk_rujuk.sid) as count');
		}
		else
		{
		
			$this->db->select('desk_rujuk.*');
			
		}

		$this->db->from('desk_direktorat');
		$this->db->where('deleted', 0);
		
		if(!empty($filters['kota']))
		{
			$this->db->where('kota', $filters['kota']);
		}
		
		if(!empty($search))
		{
			/*$this->db->group_start();
				$this->db->like('desk_direktorat.name', $search);
				$this->db->or_like('desk_direktorat.kota', $search);
			$this->db->group_end();*/
		}
		
		
		// get_found_rows case
		if($count_only == TRUE)
		{
			return $this->db->get()->row()->count;
		}

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
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

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}
	
	
	/*
	Gets information about a particular item
	*/
	public function get_info($item_id)
	{
		$this->db->select('desk_rujuk.*');
		
		$this->db->from('desk_rujuk');
		
		$this->db->where('desk_rujuk.sid', $item_id);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields('desk_rujuk') as $field)
			{
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

		$this->db->where('rid', $item_id);
		return $this->db->update('desk_rujukan', $item_data);
	}

	/*
	Updates multiple items at once
	*/
	public function update_multiple($item_data, $item_ids)
	{
	}

	/*
	Deletes one item
	*/
	public function delete($item_id)
	{
		
	}

	/*
	Undeletes one item
	*/
	public function undelete($item_id)
	{
		
	}

	/*
	Deletes a list of items
	*/
	public function delete_list($item_ids)
	{
		
	}
	


}
?>
