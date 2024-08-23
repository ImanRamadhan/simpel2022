<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Calendars extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('calendars');
		$this->load->model('Calendar');

	}
	
	public function index()
	{
		$data['table_headers'] = $this->xss_clean(get_calendar_manage_table_headers());
		$data['title'] = 'Pengaturan hari libur';
		$data['thns'] = array();
		for($i=2017; $i<2025; $i++)
		{
			$data['thns'][$i] = $i;
		}
		$data['blns'] = array('' => 'ALL');
		for($i=1; $i<=12; $i++)
		{
			$data['blns'][$i] = to_bulan($i);
		}
		$this->load->view('calendar/manage', $data);
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
		
		$thn = $this->input->get('thn');
		$bln = $this->input->get('bln');

		//$filters = array();
		$filters = array(
						'thn' => $thn,
						'bln' => $bln
						);
		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);
		
		

		$items = $this->Calendar->search($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Calendar->get_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach($items->result() as $item)
		{
			$data_rows[] = $this->xss_clean(get_calendar_data_row($item, ++$no));
			
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters ));
	}
	

	

	/*
	Gets one row for a standard manage table. This is called using AJAX to update one row.
	*/
	public function get_row($row_id)
	{
		$item = $this->Dept->get_info($row_id);

		$data_row = $this->xss_clean(get_dept_data_row($item));

		echo json_encode($data_row);
	}

	public function view($item_id = -1)
	{
		
		$item_info = $this->Calendar->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		$data['page_title'] = 'Ubah Data Calendar';

		$this->load->view('calendar/form', $data);
	}
	
	public function create()
	{
		
		$item_info = $this->Calendar->get_info(0);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		$data['page_title'] = 'Tambah Hari Libur';

		$this->load->view('calendar/form', $data);
	}

	public function save($item_id = -1)
	{
		$tgl_input = $this->input->post('tgl');
		$tgl = date("Y-m-d", strtotime($tgl_input));
		//Save item data
		$item_data = array(
			'tgl' => $tgl,
			'keterangan' => $this->input->post('keterangan'),
		);

		if($this->Calendar->get_exist_date($tgl))
		{
			$message = "Tanggal Input ".$tgl_input." Sudah ada pada database !";
			
			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));

			return;
		}
		
		if($this->Calendar->save($item_data, $item_id))
		{
			$success = TRUE;
			
			if($item_id == -1)
			{
				$message = $this->xss_clean($this->lang->line('items_successful_adding') . ' ' . $item_data['keterangan']);

				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
			}
			else
			{
				$message = $this->xss_clean($this->lang->line('items_successful_updating') . ' ' . $item_data['keterangan']);

				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
			}
		}
		else // failure
		{
			$message = $this->xss_clean($this->lang->line('items_error_adding_updating') . ' ' . $item_data['name']);
			
			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
		}
	}
	
	

	public function delete()
	{
		$items_to_delete = $this->input->post('ids');

		if($this->Dept->delete_list($items_to_delete))
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
