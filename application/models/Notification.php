<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Notification extends CI_Model
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
		
		$this->db->from('desk_profesi');
		$this->db->where('id', $item_id);
		if($ignore_deleted == FALSE)
		{
			//$this->db->where('deleted', $deleted);
		}

		return ($this->db->get()->num_rows() == 1);
	}

	

	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('desk_profesi');
		$this->db->where('deleted', 0);

		return $this->db->count_all_results();
	}

	

	/*
	Get number of rows
	*/
	public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters, 0, 0, 'desk_profesi.id', 'asc', TRUE);
	}

	/*
	Perform a search on items
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_notifikasi.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(desk_notifikasi.id) as count');
		}
		else
		{
		
			$this->db->select('desk_notifikasi.*');
			
		}

		$this->db->from('desk_notifikasi');
		$this->db->where('user_id', $this->session->id);
		
		if(!empty($search))
		{
			$this->db->group_start();
				$this->db->like('desk_notifikasi.title', $search);
				$this->db->or_like('desk_notifikasi.ticket_id', $search);
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
		$this->db->from('desk_notifikasi');
		

		//$this->db->where('desk_kota.deleted', 0);

		// order by name of item
		//$this->db->order_by('desk_kota.nama_kota', 'asc');

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
		$this->db->select('desk_notifikasi.*');
		
		$this->db->from('desk_notifikasi');
		
		$this->db->where('desk_notifikasi.id', $item_id);

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
			foreach($this->db->list_fields('desk_notifikasi') as $field)
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
		if(!$item_id)
		{
			if($this->db->insert('desk_notifikasi', $item_data))
			{
				$item_data['id'] = $this->db->insert_id();

				return TRUE;
			}

			return FALSE;
		}

		$this->db->where('id', $item_id);

		return $this->db->update('desk_notifikasi', $item_data);
		//return $this->db->insert('desk_notifikasi', $item_data);
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
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		
		$this->db->where('id', $item_id);
		//$success = $this->db->update('desk_notifikasi', array('deleted' => 1));
		$success = $this->db->delete('desk_notifikasi');

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	/*
	Undeletes one item
	*/
	public function undelete($item_id)
	{
		//$this->db->where('id', $item_id);

		//return $this->db->update('desk_notifikasi', array('deleted' => 0));
	}

	/*
	Deletes a list of items
	*/
	public function delete_list($item_ids)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		
		$this->db->where_in('id', $item_ids);
		//$success = $this->db->update('desk_notifikasi', array('deleted' => 1));
		$success = $this->db->delete('desk_notifikasi');

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}
	
	public function get_notifications($user_id, $unread = FALSE, $limit = 0, $count_only = FALSE)
	{
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(desk_notifikasi.id) as count');
		}
		
		$this->db->from('desk_notifikasi');
		$this->db->where('user_id', $user_id);
		if($unread)
			$this->db->where('is_read', 0);
		if($limit > 0)
			$this->db->limit($limit);
		
		if($count_only == TRUE)
		{
			return $this->db->get()->row()->count;
		}
		
		$this->db->order_by('id','desc');
		
		return $this->db->get();
	}
	
	public function save_notif($data)
	{
		$this->db->replace('desk_notifikasi_flag', $data);
	}
	
	public function mark_as_read($ticket_id, $user_id)
	{
		$this->db->from('desk_notifikasi_flag');
		$this->db->where('user_id', $user_id);
		$this->db->where('ticket_id', $ticket_id);
		
		return $this->db->update('desk_notifikasi_flag', array('is_read' => 1));
	}

}
?>
