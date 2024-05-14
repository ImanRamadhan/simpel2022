<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Draft Ticket class
 * 
 */

class Draft extends CI_Model
{
	/**
	 * Determines whether the employee specified employee has access the specific module.
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
		$this->db->from('desk_tickets');
		$this->db->where_in('info', array('P','I'));

		return $this->db->count_all_results();
	}

	

	/*
	Get number of rows
	*/
	public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	/*
	Perform a search on items
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_drafts.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(desk_drafts.id) as count');
		}
		else
		{
			
			$this->db->select('desk_drafts.id, trackid, lastchange, iden_nama, isu_topik, category');
			//$this->db->select('desk_categories.desc as jenis_produk');
			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
			
		}
		
		$this->db->from('desk_drafts');
		//$this->db->join('desk_categories','desk_categories.id = desk_drafts.kategori','left');
		$this->db->where('owner', $this->session->id);
		$this->db->where('is_sent', '0');
		//$this->db->where_in('info', array('P','I'));
		
		/*
		if(!empty($filters['tgl1']) && !empty($filters['tgl2']))
		{
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}
		*/
		
		
		
		
		if(!empty($search))
		{
			//$this->db->group_start();
				//$this->db->like('desk_drafts.trackid', $search);
				//$this->db->or_like('custom2', $search);
			//$this->db->group_end();
		}
		
		
		
		// get_found_rows case
		if($count_only == TRUE)
		{
			return $this->db->get()->row()->count;
		}

		
		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}
		
		

		return $this->db->get();
	}
	
	/*
	Get number of rows
	*/
	public function get_rujukan_found_rows($search, $filters)
	{
		return $this->search_rujukan($search, $filters, 0, 0, 'desk_rujuk_drafts.sid', 'asc', TRUE);
	}
	
	/*
	Perform a search on items
	*/
	public function search_rujukan($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_rujuk_drafts.sid', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(desk_rujuk_drafts.sid) as count');
		}
		else
		{
			
			$this->db->select('desk_rujuk_drafts.*');
			$this->db->select('desk_direktorat.name as unit_name, kota');
			//$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
			
		}
		
		$this->db->from('desk_rujuk_drafts');
		$this->db->join('desk_direktorat','desk_direktorat.id = desk_rujuk_drafts.dir_id');
		//$this->db->where('owner', $this->session->id);
		//$this->db->where('is_sent', '0');
		//$this->db->where_in('info', array('P','I'));
		
		/*
		if(!empty($filters['tgl1']) && !empty($filters['tgl2']))
		{
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}
		*/
		
		
		
		
		if(!empty($search))
		{
			//$this->db->group_start();
				//$this->db->like('desk_drafts.trackid', $search);
				//$this->db->or_like('custom2', $search);
			//$this->db->group_end();
		}
		
		
		
		// get_found_rows case
		if($count_only == TRUE)
		{
			return $this->db->get()->row()->count;
		}

		
		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}
		
		

		return $this->db->get();
	}
	
	
	public function apply_filter(&$db, $kota)
	{
		switch($kota)
		{
			case 'ALL':
				break;
			case 'PUSAT':
				$db->where('kota', 'PUSAT');
				break;
			default:
				$db->where('kota', $kota);		
		}
	}
	

	/*
	Returns all the items
	*/
	public function get_all($stock_location_id = -1, $rows = 0, $limit_from = 0)
	{
		$this->db->from('desk_tickets');
		//$this->db->join('suppliers', 'suppliers.person_id = items.supplier_id', 'left');

		//$this->db->where('t_standards.deleted', 0);

		// order by name of item
		$this->db->order_by('desk_tickets.id', 'desc');

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
		/*$this->db->select('desk_tickets.*');
		$this->db->select('desk_categories.desc as jenis_produk');
		$this->db->select('desk_profesi.name as profesi');
		$this->db->select('a.name as created_by');
		
		$this->db->from('desk_tickets');
		$this->db->join('desk_categories','desk_tickets.kategori=desk_categories.id','left');
		$this->db->join('desk_profesi','desk_tickets.iden_profesi=desk_profesi.id','left');
		$this->db->join('desk_users a','desk_tickets.owner=a.id','left');
		
		$this->db->where('desk_tickets.id', $item_id);
		
		$query = $this->db->get();*/
		
		$item_id = (int)$item_id;
		
		$sql = "SELECT a.* ";
		$sql .= ", date_format(a.prod_kadaluarsa,'%d/%m/%Y') as prod_kadaluarsa_fmt";
		$sql .= ", date_format(a.prod_diperoleh_tgl,'%d/%m/%Y') as prod_diperoleh_tgl_fmt";
		$sql .= ", date_format(a.prod_digunakan_tgl,'%d/%m/%Y') as prod_digunakan_tgl_fmt";
		$sql .= ", date_format(a.tglpengaduan,'%d/%m/%Y') as tglpengaduan_fmt";
		$sql .= ", date_format(a.lastchange,'%d/%m/%Y %H:%i:%s') as lastchange_fmt";

		//$sql .= ", date_format(a.dt,'%d/%m/%Y') as dt_fmt";
		$sql .= ", b.desc as jenis_produk";
		$sql .= ", c.name as profesi";
		$sql .= ", d.name as created_by";
		$sql .= ", e.name as verified_by";
		
		$sql .= " FROM (SELECT * FROM desk_drafts WHERE id=$item_id) a";
		$sql .= " LEFT JOIN desk_categories b ON a.kategori = b.id";
		$sql .= " LEFT JOIN desk_profesi c ON a.iden_profesi = c.id";
		$sql .= " LEFT JOIN desk_users d ON a.owner = d.id";
		$sql .= " LEFT JOIN desk_users e ON a.verified_by = e.id";
		
		
		$query = $this->db->query($sql);

		if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields('desk_drafts') as $field)
			{
				$item_obj->$field = '';
			}
			
			$item_obj->created_by = '';
			$item_obj->prod_kadaluarsa_fmt = '';
			$item_obj->prod_diperoleh_tgl_fmt = '';
			$item_obj->prod_digunakan_tgl_fmt = '';
			$item_obj->tglpengaduan_fmt = '';
			$item_obj->lastchange_fmt = '';
			$item_obj->is_rujuk = '0';
			$item_obj->shift = '1';
			$item_obj->info = 'P';

			return $item_obj;
		}
	}
	
	public function get_ppid_info($item_id)
	{
		$this->db->select('desk_ppid_drafts.*,');
		$this->db->select("date_format(tgl_diterima,'%d/%m/%Y') as tgl_diterima_fmt,");
		$this->db->select("date_format(tt_tgl,'%d/%m/%Y') as tt_tgl_fmt,");
		$this->db->select("date_format(tgl_tanggapan,'%d/%m/%Y') as tgl_tanggapan_fmt,");
		$this->db->select("date_format(keberatan_tgl,'%d/%m/%Y') as keberatan_tgl_fmt");
		$this->db->select("date_format(tgl_pemberitahuan_tertulis,'%d/%m/%Y') as tgl_pemberitahuan_tertulis_fmt");
		$this->db->from('desk_ppid_drafts');
		$this->db->where('desk_ppid_drafts.id', $item_id);

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
			foreach($this->db->list_fields('desk_ppid_drafts') as $field)
			{
				$item_obj->$field = '';
			}
			
			$item_obj->keberatan_tgl_fmt = date('d/m/Y');
			$item_obj->tt_tgl_fmt = date('d/m/Y');
			$item_obj->tgl_tanggapan_fmt = date('d/m/Y');
			$item_obj->tgl_diterima_fmt = date('d/m/Y');
			$item_obj->tgl_pemberitahuan_tertulis_fmt = date('d/m/Y');

			return $item_obj;
		}
	}
	
	
	public function remove_all_rujukans($item_id)
	{
		$this->db->where('sid', $item_id);
		$success = $this->db->delete('desk_rujuk_drafts');
		
		$item_data = array(
			'direktorat' => 0,
			'direktorat2' => 0,
			'direktorat3' => 0,
			'direktorat4' => 0,
			'direktorat5' => 0
		);
		
		$this->db->where('id', $item_id);		
		$this->db->update('desk_drafts', $item_data);
	}
	
	public function remove_rujukan($item_id, $dir_id)
	{
		$this->db->where('sid', $item_id);
		$this->db->where('dir_id', $dir_id);
		$success = $this->db->delete('desk_rujuk_drafts');
		
		
		$this->db->from('desk_rujuk_drafts');
		$this->db->where('sid', (int) $item_id);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$array = array();
			foreach($query->result_array() as $row)
			{
				$array[] = $row;
			}
			
			$this->db->where('sid', $item_id);
			$success = $this->db->delete('desk_rujuk_drafts');
			
			$array_dir = array(
					'direktorat' => 0,
					'direktorat2' => 0,
					'direktorat3' => 0,
					'direktorat4' => 0,
					'direktorat5' => 0
				
				);
				
			$this->db->where('id', $item_id);		
			$this->db->update('desk_drafts', $array_dir);
				
			
			//urutkan no_urut
			for($i = 0; $i<count($array); $i++)
			{
				$arr = $array[$i];
				$arr['no_urut'] = $i+1;
				$this->db->insert('desk_rujuk_drafts', $arr);
				
				
				if($arr['no_urut'] == 1)
				{
					$this->db->where('id', $item_id);		
					$this->db->update('desk_drafts', array('direktorat' => $arr['dir_id']));
				}
				elseif($arr['no_urut'] == 2)
				{
					$this->db->where('id', $item_id);		
					$this->db->update('desk_drafts', array('direktorat2' => $arr['dir_id']));
				}
				elseif($arr['no_urut'] == 3)
				{
					$this->db->where('id', $item_id);		
					$this->db->update('desk_drafts', array('direktorat3' => $arr['dir_id']));
				}
				elseif($arr['no_urut'] == 4)
				{
					$this->db->where('id', $item_id);		
					$this->db->update('desk_drafts', array('direktorat4' => $arr['dir_id']));
				}
				elseif($arr['no_urut'] == 5)
				{
					$this->db->where('id', $item_id);		
					$this->db->update('desk_drafts', array('direktorat5' => $arr['dir_id']));
				}
				
			}
		}
		
		return TRUE;
		
	}
	
	public function get_rujukan_info_($item_id)
	{
		$this->db->select('desk_rujukan_drafts.*,');
		//$this->db->select("date_format(tgl_diterima,'%d/%m/%Y') as tgl_diterima_fmt,");
		//$this->db->select("date_format(tt_tgl,'%d/%m/%Y') as tt_tgl_fmt,");
		//$this->db->select("date_format(tgl_tanggapan,'%d/%m/%Y') as tgl_tanggapan_fmt,");
		//$this->db->select("date_format(keberatan_tgl,'%d/%m/%Y') as keberatan_tgl_fmt");
		//$this->db->select("date_format(tgl_pemberitahuan_tertulis,'%d/%m/%Y') as tgl_pemberitahuan_tertulis_fmt");
		$this->db->from('desk_rujukan_drafts');
		$this->db->where('desk_rujukan_drafts.id', $item_id);

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
			foreach($this->db->list_fields('desk_rujukan_drafts') as $field)
			{
				$item_obj->$field = '';
			}
			
			/*$item_obj->keberatan_tgl_fmt = date('d/m/Y');
			$item_obj->tt_tgl_fmt = date('d/m/Y');
			$item_obj->tgl_tanggapan_fmt = date('d/m/Y');
			$item_obj->tgl_diterima_fmt = date('d/m/Y');
			$item_obj->tgl_pemberitahuan_tertulis_fmt = date('d/m/Y');*/

			return $item_obj;
		}
	}

	public function create_draft(&$item_data)
	{
		$this->db->set('is_rujuk',"'0'",FALSE);
		$this->db->set('shift',"'1'", FALSE);
		if($this->db->insert('desk_drafts', $item_data))
		{
			$item_data['id'] = $this->db->insert_id();

			return $item_data['id'];
		}
		
		return 0;
	}

	/*
	Updates a draft
	*/
	public function save(&$item_data, $item_id = FALSE)
	{
		$item_data2 = $item_data;
		//enum data type
		$enum_array = array('id', 'is_rujuk', 'info', 'shift', 'is_sent', 'status');
		foreach($enum_array as $enum)
		{
			if (array_key_exists($enum, $item_data2))
			{
				$this->db->set($enum, "'".$item_data2[$enum]."'", FALSE);
				unset($item_data2[$enum]);
			}
		}
		
		foreach($item_data2 as $k => $v)
		{
			$this->db->set($k, $v);
		}
		
		
		if($item_id == -1)
		{
				
			if($this->db->insert('desk_drafts'))
			{
				$item_data['id'] = $this->db->insert_id();

				return TRUE;
			}

			return FALSE;
		}

		$this->db->where('id', $item_id);		
		return $this->db->update('desk_drafts');
	}
	
	
	public function save_ppid(&$item_data, $item_id)
	{
		$this->db->from('desk_ppid_drafts');
		$this->db->where('id', (int) $item_id);
		
		if($this->db->get()->num_rows() == 1)
		{
			$this->db->where('id', $item_id);		
			return $this->db->update('desk_ppid_drafts', $item_data);
		}
		
		return $this->db->insert('desk_ppid_drafts', $item_data);
	}
	
	public function save_to_ticket(&$item_data, $item_id = FALSE)
	{
		$this->db->from('desk_drafts');
		$this->db->where('id', $item_id);
		$query = $this->db->get();
		
		if($query->num_rows() == 1)
		{
			$row = $query->row_array();
			
			$row = array_merge($row, $item_data);
			
			/*if($row['is_rujuk'] == '0')
			{
				$row['tl'] = 1;
				$row['fb'] = 1;
			}*/
			
			
			$fields_exclude = array('id', 'is_rujuk', 'info', 'shift', 'is_sent', 'status');
			
			foreach($this->db->list_fields('desk_drafts') as $field)
			{
				if(!in_array($field, $fields_exclude))
				{
					$val = $row[$field];//empty($row[$field])?'':$row[$field];
					//print($field . ' => '.$val ."<br />");
				
					$this->db->set($field, $val);
				}
			}
			
			//print_r($row);
			//exit;
			
			$config = $this->config->item('upload_setting');
			$config2 = $this->config->item('upload_draft_setting');
			
			//enum patch
			$this->db->set('is_rujuk', "'".$row['is_rujuk']."'", FALSE);
			$this->db->set('info', "'".$row['info']."'", FALSE);
			$this->db->set('shift', "'".$row['shift']."'", FALSE);
			$this->db->set('is_sent', "'1'", FALSE);
			$this->db->set('status', "'0'", FALSE);
			
			
			
			if($this->db->insert('desk_tickets'))
			{
				$item_data['id'] = $this->db->insert_id();
				
				
				$this->db->where('id', $item_id);
				$this->db->set('is_sent', '1');
				$this->db->update('desk_drafts');
				
				//move attachments from drafts to tickets
				
				$this->db->from('desk_attachments_drafts');
				$this->db->where('draft_id', $item_id);
				$query = $this->db->get();
				$array = array();
				if($query->num_rows() > 0)
				{
					foreach($query->result() as $row)
					{
						$array[] = array(
							'saved_name' => $row->saved_name,
							'real_name' => $row->real_name,
							'size' => $row->size,
							'mode' => $row->mode,
							'ticket_id' => $item_data['trackid']
						);
						//copy(APPPATH.'../public/uploads/drafts/'.$row->saved_name, APPPATH.'../public/uploads/files/'.$row->saved_name);
						//unlink(APPPATH.'../public/uploads/drafts/'.$row->saved_name);
						
						@copy($config2['upload_path'].$row->saved_name, $config['upload_path'].$row->saved_name);
						@unlink($config2['upload_path'].$row->saved_name);
					}
					
					$this->db->insert_batch('desk_attachments_tickets', $array);
				}
				
				//move ppid
				$this->db->from('desk_ppid_drafts');
				$this->db->where('id', (int) $item_id);
				$query = $this->db->get();
				if($query->num_rows() == 1)
				{	
					$array = $query->row_array();
					$array['id'] = $item_data['id']; //update with ticket id
					$this->db->insert('desk_ppid', $array);
				}
				
				//insert rujukan
				if($row['is_rujuk'] == '1')
				{
					$rujukan_array = array(
						'rid' => $item_data['id']
					);
					$this->db->insert('desk_rujukan', $rujukan_array);
				}
				
				
				
				//end insert rujukan
				

				return TRUE;
			}
			
			
		}
		
		
		
		

		return FALSE;
		
	}
	
	
	
	public function generate_ticketid_($kota, $prefix, $tglpengaduan)
	{
		$ticketid = '';
				
		$this->db->select('SUBSTRING(trackid,14) AS counter');
		if($prefix == 'DRF')
			$this->db->from('desk_drafts');
		else
			$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan', $tglpengaduan);
		$this->db->where('kota', $kota);
		$this->db->order_by('id','desc');
		$this->db->limit(1); 
		
		$query = $this->db->get();
		
		$counter = ($query->num_rows() == 1)? (int)$query->row()->counter + 1 : 1;
				
		$date = explode('-', $tglpengaduan);
		$newdate = $date[2].$date[1].$date[0];
		$ticketid = $prefix.'-'.$newdate.'-'.str_pad($counter,'3','0',STR_PAD_LEFT);
		
		return $ticketid;
	}
	
	
	
	public function generate_ticketid($kota, $prefix, $tglpengaduan)
	{
		$this->db->trans_start();
		
		$this->db->select('*');
		$this->db->from('desk_counter');
		$this->db->where('tgl', $tglpengaduan);
		$this->db->where('prefix', $prefix);
		
		$query = $this->db->get();
		$ticketid = '';
		
		if($query->num_rows() == 1)
		{
			$counter = $query->row()->counter + 1;
			
			$date = explode('-', $tglpengaduan);
			$newdate = $date[2].$date[1].$date[0];
			$ticketid = $prefix.'-'.$newdate.'-'.str_pad($counter,'3','0',STR_PAD_LEFT);
			
			$this->db->where('tgl', $tglpengaduan);
			$this->db->where('prefix', $prefix);
			$this->db->update("desk_counter", array('counter'=>$counter));
		}
		else
		{
			$counter = 1;
			$date = explode('-', $tglpengaduan);
			$newdate = $date[2].$date[1].$date[0];
			$ticketid = $prefix.'-'.$newdate.'-'.str_pad($counter,'3','0',STR_PAD_LEFT);
			$this->db->insert("desk_counter", array('counter'=>$counter, 'prefix' =>  $prefix, 'tgl'=>$tglpengaduan));
		}
		$this->db->trans_complete();
		return $ticketid;
	}

	/*
	Updates multiple items at once
	*/
	public function update_multiple($item_data, $item_ids)
	{
		$this->db->where_in('id', explode(':', $item_ids));

		return $this->db->update('desk_drafts', $item_data);
	}

	/*
	Deletes one item
	*/
	public function delete($item_id)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		
		$item_id = (int)$item_id;

		
		$this->db->where('id', $item_id);
		$success = $this->db->delete('desk_drafts');
		
		$this->db->where('id', $item_id);
		$success = $this->db->delete('desk_ppid_drafts');
		
		$this->db->where('sid', $item_id);
		$success = $this->db->delete('desk_rujuk_drafts');

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
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
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		
		$this->db->where_in('id', $item_ids);
		$success = $this->db->delete('desk_drafts');
		

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	public function get_attachments($item_id, $mode)
	{
		$this->db->from('desk_attachments_drafts');
		$this->db->where('draft_id', $item_id);
		$this->db->where('mode', $mode);
		
		return $this->db->get();
	}
	
	public function get_attachment_info($item_id)
	{
		
		$this->db->from('desk_attachments_drafts');
		$this->db->where('att_id', $item_id);
		
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
			foreach($this->db->list_fields('desk_attachments_drafts') as $field)
			{
				$item_obj->$field = '';
			}
			return $item_obj;
		}
	}
	
	public function delete_attachments($att_id, $draft_id, $mode)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		//$this->db->trans_start();
		
		$this->db->where('att_id', $att_id);
		$this->db->where('draft_id', $draft_id);
		$this->db->where('mode', $mode);
		$success = $this->db->delete('desk_attachments_drafts');

		//$this->db->trans_complete();
		//$success &= $this->db->trans_status();

		return $success;
	}
	
	public function delete_attachment($att_id)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		//$this->db->trans_start();
		
		$this->db->where('att_id', $att_id);
		$success = $this->db->delete('desk_attachments_drafts');

		//$this->db->trans_complete();
		//$success &= $this->db->trans_status();

		return $success;
	}
	
	public function save_attachment(&$item_data)
	{
		if($this->db->insert('desk_attachments_drafts', $item_data))
		{
			$item_data['id'] = $this->db->insert_id();

			return TRUE;
		}

		return FALSE;
	}
	
	public function insert_mail(&$item_data)
	{
		if($this->db->insert('desk_email', $item_data))
		{
			$item_data['id'] = $this->db->insert_id();

			return TRUE;
		}

		return FALSE;
	}
	
	public function insert_sms(&$item_data)
	{
		if($this->db->insert('desk_sms', $item_data))
		{
			$item_data['id'] = $this->db->insert_id();

			return TRUE;
		}

		return FALSE;
	}
	
	
	public function get_unit_teknis_suggestions($search, $item_id = -1)
	{
		
		
		$suggestions = array();
		//$this->db->distinct();
		$this->db->select('id, name, kota');
		$this->db->from('desk_direktorat');
		$this->db->where("id NOT IN (SELECT dir_id FROM desk_rujuk_drafts WHERE sid = $item_id)", NULL, FALSE);
		
		$this->db->group_start();
			$this->db->like('name', $search);
			$this->db->or_like('kota', $search);
		$this->db->group_end();
		$this->db->order_by('kota,name', 'asc');
		//$this->db->limit(50,0);
		$suggestions[] = array('text' => 'PUSAT | ULPK Badan POM', 'id' => 1);
		foreach($this->db->get()->result() as $row)
		{
			$suggestions[] = array('text' => $row->kota . ' | ' . $row->name, 'id'=> $row->id);
		}

		return $suggestions;
	}
	
	public function save_bulk($data){
		//return $this->db->insert_batch('desk_drafts',$data);
		$this->db->trans_start(); 
		$this->db->trans_strict(FALSE); 

		for($i =0; $i< count($data); $i++){
			$this->db->insert('desk_drafts', $data[$i]);  
			$insert_id = $this->db->insert_id();

			if($data[$i]['is_rujuk'] == '1'){
				$datarujukan = array();
				$datarujukan['rid'] = $insert_id;
				
				$this->db->insert('desk_rujukan', $datarujukan);  
			}
		}

		$this->db->trans_complete(); 
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} 
		else {
			$this->db->trans_commit();
			return TRUE;
		}
	}
}
?>
