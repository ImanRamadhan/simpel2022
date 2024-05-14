<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

/* class Master SLA */

class Slas extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('slas');
		//$this->load->model('Sla');
		//$this->load->model('Subklasifikasi');
	}
	
	public function index()
	{
		$data['table_headers'] = $this->xss_clean(get_sla_manage_table_headers());
		
		$komoditi_array = array(0 => 'Pilih');
		$commodities_records = $this->Sla->get_commodities();
		foreach($commodities_records->result() as $row)
		{
			$komoditi_array[$row->id] = $row->desc;
		}
		$data['commodities'] = $komoditi_array;
		
		$klasifikasi_array = array(0 => 'Pilih');
		$records = $this->Subklasifikasi->get_klasifikasi();
		foreach($records->result() as $row)
		{
			$klasifikasi_array[$row->id] = $row->nama;
		}
		
		$data['klasifikasis'] = $klasifikasi_array;
		
		$data['filters'] = array();
		
		$data['status_filter'] = '';	
		$this->load->view('sla/manage', $data);
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
		
		$info = $this->input->get('info');
		$komoditi_id = $this->input->get('komoditi_id');
		$klasifikasi_id = $this->input->get('klasifikasi_id');

		//$filters = array();
		$filters = array(
						'klasifikasi_id' => $klasifikasi_id,
						'komoditi_id' => $komoditi_id,
						'info' => $info
						);
		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);
		
		

		$items = $this->Sla->search($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Sla->get_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach($items->result() as $item)
		{
			$data_rows[] = $this->xss_clean(get_sla_data_row($item, ++$no));
			
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters ));
	}

	/*
	Gets one row for a standard manage table. This is called using AJAX to update one row.
	*/
	public function get_row($row_id)
	{
		$item = $this->Sla->get_info($row_id);

		$data_row = $this->xss_clean(get_sla_data_row($item));

		echo json_encode($data_row);
	}

	public function view($item_id = -1)
	{
		
		$item_info = $this->Sla->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		
		$klasifikasi_array = array();
		$records = $this->Subklasifikasi->get_klasifikasi();
		foreach($records->result() as $row)
		{
			$klasifikasi_array[$row->id] = $row->nama;
		}
		
		$data['klasifikasis'] = $klasifikasi_array;
		
		$subklasifikasi_array = array(0 => '');
		$records_sub = $this->Sla->get_subklasifikasi();
		
		foreach($records_sub->result() as $row)
		{
			$subklasifikasi_array[$row->id] = $row->subklasifikasi;
		}
		
		$data['subklasifikasis'] = $subklasifikasi_array;
		
		
		$komoditi_array = array();
		$commodities_records = $this->Sla->get_commodities();
		foreach($commodities_records->result() as $row)
		{
			$komoditi_array[$row->id] = $row->desc;
		}
		$data['commodities'] = $komoditi_array;
		
		$data['page_title'] = 'Ubah Data';
		$data['edit'] = 1;
		
		

		$this->load->view('sla/form', $data);
	}

	public function create($item_id = -1)
	{
		
		$item_info = $this->Sla->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		
		$klasifikasi_array = array(0 => '');
		$records = $this->Subklasifikasi->get_klasifikasi();
		foreach($records->result() as $row)
		{
			$klasifikasi_array[$row->id] = $row->nama;
		}
		
		$data['klasifikasis'] = $klasifikasi_array;
		
		$subklasifikasi_array = array(0 => '');
		$records_sub = $this->Sla->get_subklasifikasi();
		
		foreach($records_sub->result() as $row)
		{
			$subklasifikasi_array[$row->id] = $row->subklasifikasi;
		}
		
		$data['subklasifikasis'] = $subklasifikasi_array;
		
		$komoditi_array = array();
		$commodities_records = $this->Sla->get_commodities();
		foreach($commodities_records->result() as $row)
		{
			$komoditi_array[$row->id] = $row->desc;
		}
		
		$data['commodities'] = $komoditi_array;
		
		
		
		$data['page_title'] = 'Tambah Data';
		$data['edit'] = 0;

		$this->load->view('sla/form', $data);
	}

	public function save($item_id = -1)
	{
		
		//Save item data
		$item_data = array(
			'klasifikasi_id' => $this->input->post('klasifikasi_id'),
			'subklasifikasi_id' => $this->input->post('subklasifikasi_id'),
			'info' => $this->input->post('info'),
			'komoditi_id' => $this->input->post('komoditi_id'),
			'sla' => $this->input->post('sla')
		);
		
		if($this->Sla->save($item_data, $item_id))
		{
			if($item_id == -1)
			{
				$message = $this->xss_clean($this->lang->line('items_successful_adding'));

				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_data['id']));
			}
			else
			{
				$message = $this->xss_clean($this->lang->line('items_successful_updating') . ' ');

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

		if($this->Sla->delete_list($items_to_delete))
		{
			$message = $this->lang->line('items_successful_deleted') . ' ' . count($items_to_delete) . ' ' . $this->lang->line('items_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_cannot_be_deleted')));
		}
	}

	public function get_subkla($kla = 0)
	{
		
		$kla = $this->xss_clean($kla);
		$subklasifikasi = $this->Sla->get_subkla($kla);

		echo json_encode($subklasifikasi);
	}
	
}
?>
