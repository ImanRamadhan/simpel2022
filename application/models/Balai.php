<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Balai class
 * for Admin
 */

class Balai extends CI_Model
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
		
		$this->db->from('desk_balai');
		$this->db->where('id', $item_id);
		if($ignore_deleted == FALSE)
		{
			$this->db->where('deleted', $deleted);
		}

		return ($this->db->get()->num_rows() == 1);
	}

	

	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('desk_balai');
		$this->db->where('deleted', 0);

		return $this->db->count_all_results();
	}

	

	/*
	Get number of rows
	*/
	public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters, 0, 0, 'desk_balai.id', 'asc', TRUE);
	}

	/*
	Perform a search on items
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_balai.nama_balai', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(desk_balai.id) as count');
		}
		else
		{
		
			$this->db->select('desk_balai.*');
			
		}

		$this->db->from('desk_balai');
		$this->db->where('deleted', 0);
		
		if(!empty($filters))
		{
			$this->db->where_in('tipe_balai', $filters);
		}
		
		if(!empty($search))
		{
			$this->db->group_start();
				$this->db->like('desk_balai.nama_balai', $search);
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
		$this->db->from('desk_balai');
		

		$this->db->where('desk_balai.deleted', 0);

		// order by name of item
		//$this->db->order_by('desk_balai.nama_balai', 'asc');

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
		$this->db->select('desk_balai.*');
		
		$this->db->from('desk_balai');
		
		$this->db->where('desk_balai.id', $item_id);

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
			foreach($this->db->list_fields('desk_balai') as $field)
			{
				$item_obj->$field = '';
			}

			return $item_obj;
		}
	}

	public function get_address($item_id)
	{
		
		if($item_id == 'PUSAT' || $item_id == 'UNIT_TEKNIS')
		{
			$item_obj = new stdClass();
			$item_obj->nama_balai = 'PUSAT';
			$item_obj->tipe_balai = 0;
			$item_obj->kode_prefix = 'PST';
			$item_obj->alamat = 'Jl. Percetakan Negara No.23 Jakarta Pusat 10560';
			$item_obj->no_telp = '021-4263333';
			$item_obj->no_fax = '021-4209221';
			$item_obj->email = 'ppid@pom.go.id';
			$item_obj->kop = 'BADAN PENGAWAS OBAT DAN MAKANAN';
			return $item_obj;
		}
		
		
		$this->db->select('desk_balai.*');
		$this->db->from('desk_balai');
		$this->db->where('desk_balai.nama_balai', $item_id);

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
			foreach($this->db->list_fields('desk_balai') as $field)
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
			if($this->db->insert('desk_balai', $item_data))
			{
				$item_data['id'] = $this->db->insert_id();

				return TRUE;
			}

			return FALSE;
		}

		$this->db->where('id', $item_id);

		return $this->db->update('desk_balai', $item_data);
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
		$success = $this->db->update('desk_balai', array('deleted' => 1));
		

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

		return $this->db->update('desk_balai', array('deleted' => 0));
	}

	/*
	Deletes a list of items
	*/
	public function delete_list($item_ids)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		
		$this->db->where_in('id', $item_ids);
		$success = $this->db->update('desk_balai', array('deleted' => 1));

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}
	
	/*
	Checks if balai name exists
	*/
	public function check_balai_exists($balai, $id = '')
	{
		// if the balai is empty return like it is not existing
		if(empty($balai))
		{
			return FALSE;
		}

		$this->db->from('desk_balai');
		$this->db->where('desk_balai.nama_balai', $balai);
		$this->db->where('desk_balai.deleted', 0);

		if(!empty($id))
		{
			$this->db->where('desk_balai.id !=', $id);
		}

		return ($this->db->get()->num_rows() == 1);
	}
	
	/*
	Checks if prefix code exists
	*/
	public function check_prefix_exists($code, $id = '')
	{
		// if the balai is empty return like it is not existing
		if(empty($code))
		{
			return FALSE;
		}
		
		if($code == 'pst' || $code == 'uts')
		{
			return TRUE;
		}

		$this->db->from('desk_balai');
		$this->db->where('desk_balai.kode_prefix', $code);
		//$this->db->where('desk_balai.deleted', 0);

		if(!empty($id))
		{
			//$this->db->where('desk_balai.id !=', $id);
		}

		return ($this->db->get()->num_rows() == 1);
	}

	
	public function get_balais()
	{
		$this->db->select('nama_balai');
		$this->db->from('desk_balai');
		$this->db->where('deleted', 0);
		//$this->db->distinct();
		$this->db->order_by('nama_balai', 'asc');

		return $this->db->get();
	}

	public function get_prefix($balai)
	{
		$this->db->select('kode_prefix');
		$this->db->from('desk_balai');
		$this->db->where('deleted', 0);
		$this->db->where('nama_balai', $balai);
		
		$query = $this->db->get();

		return $query->row()->kode_prefix;
	}
	
	public function get_prefix_unit_teknis($dir_id)
	{
		$this->db->select('kode_prefix');
		$this->db->from('desk_direktorat');
		$this->db->where('deleted', 0);
		$this->db->where('id', $dir_id);
		
		$query = $this->db->get();
		$kode = $query->row()->kode_prefix;
		if(empty($kode))
			$kode = 'XXX';
		
		return $kode;
	}

}
?>
