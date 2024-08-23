<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Calendar extends CI_Model
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
		$this->db->from('desk_holiday');

		return $this->db->count_all_results();
	}

	public function get_exist_date($date)
	{
		$this->db->from('desk_holiday');
		$this->db->where('tgl', $date);

		return ($this->db->get()->num_rows() == 1);
	}
	

	/*
	Get number of rows
	*/
	public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters, 0, 0, 'desk_holiday.id', 'asc', TRUE);
	}

	/*
	Perform a search on items
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_holiday.tgl', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(desk_holiday.id) as count');
		}
		else
		{
		
			$this->db->select('desk_holiday.*');
			//$this->db->select("date_format(tgl, '%d/%m/%Y') as tgl_fmt");
		}

        $this->db->from('desk_holiday');
        
        //$this->db->order_by('desk_holiday.tgl', 'asc');
		if(!empty($filters['thn']))
		{
			$this->db->where('year(desk_holiday.tgl)',$filters['thn']);
		}
		
		if(!empty($filters['bln']))
		{
			$this->db->where('month(desk_holiday.tgl)',$filters['bln']);
		}
		
		if(!empty($search))
		{
			$this->db->group_start();
				$this->db->like('desk_holiday.keterangan', $search);
				//$this->db->or_like('custom2', $search);
			$this->db->group_end();
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
		$this->db->from('desk_holiday');

		// order by name of item
        $this->db->where('year(desk_holiday.tgl),year(curdate())');
        $this->db->order_by('desk_holiday.tgl', 'asc');

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
		$this->db->select('desk_holiday.*');
		$this->db->select("date_format(tgl, '%d/%m/%Y') as tgl_fmt");
		$this->db->from('desk_holiday');
		
		$this->db->where('desk_holiday.id', $item_id);

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
			foreach($this->db->list_fields('desk_holiday') as $field)
			{
				$item_obj->$field = '';
			}
			$item_obj->tgl_fmt = '';

			return $item_obj;
		}
	}

	

	
	

	/*
	Inserts or updates a item
	*/
	public function save(&$item_data, $item_id = FALSE)
	{
		if($item_id == -1 /*|| !$this->exists($item_id, TRUE)*/)
		{
			if($this->db->insert('desk_holiday', $item_data))
			{
				$item_data['id'] = $this->db->insert_id();

				return TRUE;
			}

			return FALSE;
		}

		$this->db->where('id', $item_id);

		return $this->db->update('desk_holiday', $item_data);
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
		$success = $this->db->update('desk_holiday', array('deleted' => 1));
		

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

		return $this->db->update('desk_holiday', array('deleted' => 0));
	}

	/*
	Deletes a list of items
	*/
	public function delete_list($item_ids)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		
		$this->db->where_in('id', $item_ids);
		$success = $this->db->update('desk_holiday', array('deleted' => 1));

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
    }
}
?>
