<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

/* class Notifikasi */

class Notifications extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('notifications');
	}
	
	public function index()
	{
		$data['table_headers'] = $this->xss_clean(get_notifikasi_manage_table_headers());
		
		$data['filters'] = array();
		
		$data['status_filter'] = '';	
		$this->load->view('notifikasi/manage', $data);
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
		
		

		$items = $this->Notification->search($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Notification->get_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach($items->result() as $item)
		{
			$data_rows[] = $this->xss_clean(get_notifikasi_data_row($item, ++$no));
			
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters ));
	}
	

	

	/*
	Gets one row for a standard manage table. This is called using AJAX to update one row.
	*/
	public function get_row($row_id)
	{
		$item = $this->Notification->get_info($row_id);

		$data_row = $this->xss_clean(get_notifikasi_data_row($item));

		echo json_encode($data_row);
	}

	public function view($item_id = -1)
	{
		
		$item_info = $this->Notification->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		$data['page_title'] = 'Detail Notifikasi';
		$data['ticket_id'] = $item_info->ticket_id;
		if(!empty($item_info->ticket_id))
		{
			$ticket_info = $this->Ticket->get_info_by_trackid($item_info->ticket_id);
			$data['ticket_id'] = anchor('tickets/view/'.$ticket_info->id.'?ref=notifikasi', $ticket_info->trackid);
		}
		
		if(!$item_info->is_read)
		{
			$item_data = array(
				'is_read' => 1
			);
			$this->Notification->save($item_data, $item_id);
		}

		$this->load->view('notifikasi/form', $data);
	}

	public function create($item_id = -1)
	{
		
		/*$item_info = $this->Notification->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		$data['page_title'] = 'Tambah Data';

		$this->load->view('notifikasi/form', $data);*/
	}

	public function save($item_id = -1)
	{
		
		/*
		$item_data = array(
			'nama' => $this->input->post('nama')
		);
		
		if($this->Notification->save($item_data, $item_id))
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
		}*/
	}
	
	

	public function delete()
	{
		$items_to_delete = $this->input->post('ids');

		if($this->Notification->delete_list($items_to_delete))
		{
			$message = $this->lang->line('items_successful_deleted') . ' ' . count($items_to_delete) . ' ' . $this->lang->line('items_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_cannot_be_deleted')));
		}
	}

	
	
	

	
}
?>
