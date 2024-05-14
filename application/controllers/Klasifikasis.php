<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

/* class Klasifikasi */

class Klasifikasis extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('klasifikasis');
		$this->load->model('Klasifikasi');
	}
	
	public function index()
	{
		$data['table_headers'] = $this->xss_clean(get_klasifikasi_manage_table_headers());
		
		$data['filters'] = array();
		
		$data['status_filter'] = '';	
		$this->load->view('klasifikasi/manage', $data);
	}

	/*
	Returns Items table data rows. This will be called with AJAX.
	*/
	public function search()
	{
		$search = $this->input->get('search');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort = $this->input->get('sort');
		$order = $this->input->get('order');

		//$filters = array();
		$filters = array(
						
						);
		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);
		
		

		$items = $this->Klasifikasi->search($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Klasifikasi->get_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach($items->result() as $item)
		{
			$data_rows[] = $this->xss_clean(get_klasifikasi_data_row($item, ++$no));
			
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters ));
	}
	

	

	/*
	Gets one row for a standard manage table. This is called using AJAX to update one row.
	*/
	public function get_row($row_id)
	{
		$item = $this->Klasifikasi->get_info($row_id);

		$data_row = $this->xss_clean(get_klasifikasi_data_row($item));

		echo json_encode($data_row);
	}

	public function view($item_id = -1)
	{
		
		$item_info = $this->Klasifikasi->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		$data['page_title'] = 'Ubah Data';
		$data['edit'] = 1;

		$this->load->view('klasifikasi/form', $data);
	}

	public function create($item_id = -1)
	{
		
		$item_info = $this->Klasifikasi->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		$data['page_title'] = 'Tambah Data';
		$data['edit'] = 0;

		$this->load->view('klasifikasi/form', $data);
	}

	public function save($item_id = -1)
	{
		
		//Save item data
		$item_data = array(
			'nama' => $this->input->post('nama'),
			'status' => $this->input->post('status')
		);
		
		if($this->Klasifikasi->save($item_data, $item_id))
		{
			if($item_id == -1)
			{
				$message = $this->xss_clean($this->lang->line('items_successful_adding') . ' ' . $item_data['nama']);

				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_data['id']));
			}
			else
			{
				$message = $this->xss_clean($this->lang->line('items_successful_updating') . ' ' . $item_data['nama']);

				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
			}
		}
		else // failure
		{
			$message = $this->xss_clean($this->lang->line('items_error_adding_updating') . ' ' . $item_data['id']);
			
			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
		}
	}
	
	

	public function delete()
	{
		$items_to_delete = $this->input->post('ids');

		if($this->Klasifikasi->delete_list($items_to_delete))
		{
			$message = $this->lang->line('items_successful_deleted') . ' ' . count($items_to_delete) . ' ' . $this->lang->line('items_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_cannot_be_deleted')));
		}
	}

	
	public function ajax_check_klasifikasi()
	{
		$exists = $this->Klasifikasi->check_klasifikasi_exists(strtolower($this->input->post('nama')));

		echo !$exists ? 'true' : 'false';
	}
	

	
}
?>
