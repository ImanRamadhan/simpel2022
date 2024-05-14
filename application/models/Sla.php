<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Sla extends CI_Model
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
		
		$this->db->from('desk_sla');
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
		$this->db->from('desk_sla');
		//$this->db->where('deleted', 0);

		return $this->db->count_all_results();
	}

	

	/*
	Get number of rows
	*/
	public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters, 0, 0, 'desk_sla.id', 'asc', TRUE);
	}

	/*
	Perform a search on items
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_sla.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(desk_sla.id) as count');
		}
		else
		{
		
			$this->db->select('desk_sla.*');
			$this->db->select('desk_categories.desc AS komoditi');
			$this->db->select('desk_klasifikasi.nama as klasifikasi');
			$this->db->select('desk_subklasifikasi.subklasifikasi');
		}

		$this->db->from('desk_sla');
		$this->db->join('desk_categories','desk_categories.id = desk_sla.komoditi_id','left');
		$this->db->join('desk_klasifikasi','desk_klasifikasi.id = desk_sla.klasifikasi_id','left');
		$this->db->join('desk_subklasifikasi','desk_subklasifikasi.id = desk_sla.subklasifikasi_id','left');
		//$this->db->where('deleted', 0);
		
		if(!empty($search))
		{
			$this->db->group_start();
				$this->db->like('desk_klasifikasi.nama', $search);
				$this->db->or_like('desk_subklasifikasi.subklasifikasi', $search);
				$this->db->or_like('desk_categories.desc', $search);
			$this->db->group_end();
		}
		
		if(!empty($filters['info']))
		{
			$this->db->where('info', $filters['info']);
		}
		
		if(!empty($filters['komoditi_id']))
		{
			$this->db->where('komoditi_id', $filters['komoditi_id']);
		}
		
		if(!empty($filters['klasifikasi_id']))
		{
			$this->db->where('klasifikasi_id', $filters['klasifikasi_id']);
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
		$this->db->from('desk_sla');
		

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
		$this->db->select('desk_sla.*');
		
		$this->db->from('desk_sla');
		
		$this->db->where('desk_sla.id', $item_id);

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
			foreach($this->db->list_fields('desk_sla') as $field)
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
		
		if(!$item_id || !$this->exists($item_id, TRUE))
		{
			if($this->db->insert('desk_sla', $item_data))
			{
				$item_data['id'] = $this->db->insert_id();

				return TRUE;
			}

			return FALSE;
		}

		$this->db->where('id', $item_id);

		return $this->db->update('desk_sla', $item_data);
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
		//$success = $this->db->update('desk_sla', array('deleted' => 1));
		$success = $this->db->delete('desk_sla');

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
		//return $this->db->delete('desk_sla');;
		//return $this->db->update('desk_sla', array('deleted' => 0));
	}

	/*
	Deletes a list of items
	*/
	public function delete_list($item_ids)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		
		$this->db->where_in('id', $item_ids);
		//$success = $this->db->update('desk_sla', array('deleted' => 1));
		$success = $this->db->delete('desk_sla');

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}
	
	

	
	public function get_subklasifikasi()
	{
		$this->db->select('id, klasifikasi, subklasifikasi');
		$this->db->from('desk_subklasifikasi');
		$this->db->where('deleted', 0);
		//$this->db->distinct();
		$this->db->order_by('subklasifikasi', 'asc');

		return $this->db->get();
	}

	public function get_klasifikasi($status = 1)
	{
		$this->db->select('nama');
		$this->db->from('desk_klasifikasi');
		$this->db->where('deleted', 0);
		
		if($status)
			$this->db->where('status', $status);
		//$this->db->distinct();
		$this->db->order_by('nama', 'asc');

		return $this->db->get();
	}
	
	public function get_commodities()
	{
		$this->db->select('id, name, desc');
		$this->db->from('desk_categories');
		//$this->db->where('deleted', 0);
		//$this->db->distinct();
		$this->db->order_by('desc', 'asc');

		return $this->db->get();
	}
	
	public function get_subkla($kla)
	{
		
		$this->db->from('desk_subklasifikasi');
		$this->db->where('klasifikasi', $kla);
		$this->db->order_by('subklasifikasi', 'asc');

		$query =  $this->db->get();
		return $query->result();
	}
	
	public function get_sla($idkomoditi, $info, $klasifikasi, $subklasifikasi)
	{
		$this->db->select('sla');
		$this->db->from('desk_sla');
		$this->db->where('info', $info);
		$this->db->where('komoditi_id', $idkomoditi);
		$this->db->where('klasifikasi_id', $klasifikasi);
		$this->db->where('subklasifikasi_id', $subklasifikasi);
		$query = $this->db->get();
		if($query->num_rows() == 1)
		{
			return $query->row()->sla;
		}
		return 0;
		
	}

}
?>