<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Interviews extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('interviews');
		$this->load->model('Interview');
	}
	
	public function index()
	{
		$data['table_headers'] = $this->xss_clean(get_interviews_manage_table_headers());
		
		$data['filters'] = array(
			
			);
		$data['status_filter'] = '';	
		$this->load->view('admin/interviews/manage', $data);
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
		$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		$filters = array_merge($filters, $filledup);
		
		

		$items = $this->Interview->search($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Interview->get_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach($items->result() as $item)
		{
			$data_rows[] = $this->xss_clean(get_interview_data_row($item, ++$no));
			
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters ));
	}
	

	

	/*
	Gets one row for a standard manage table. This is called using AJAX to update one row.
	*/
	public function get_row($row_id)
	{
		$item = $this->Interview->get_info($row_id);

		$data_row = $this->xss_clean(get_interview_data_row($item));

		echo json_encode($data_row);
	}

	public function view($item_id = -1)
	{
		
		$item_info = $this->Interview->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		
		$no_urut = array();
		for($i=1; $i<=20;$i++)
			$no_urut[$i] = $i;
		
		$data['no_uruts'] =  $no_urut;

		$this->load->view('admin/interviews/form', $data);
	}

	

	public function save($item_id = -1)
	{
		
		//Save item data
		$item_data = array(
			'pertanyaan' => $this->input->post('pertanyaan'),
			'w_status' => $this->input->post('w_status'),			
			'no_urut' => $this->input->post('no_urut')
		);
		
		if($this->Interview->save($item_data, $item_id))
		{
			$success = TRUE;
			
			if($item_id == -1)
			{
				$message = $this->xss_clean($this->lang->line('items_successful_adding'));

				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
			}
			else
			{
				$message = $this->xss_clean($this->lang->line('items_successful_updating'));

				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
			}
		}
		else // failure
		{
			$message = $this->xss_clean($this->lang->line('items_error_adding_updating'));
			
			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
		}
	}
	
	

	public function delete()
	{
		$items_to_delete = $this->input->post('ids');

		if($this->Interview->delete_list($items_to_delete))
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
