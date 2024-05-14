<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Rujukan_keluar_saya extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('rujukan');

	}
	
	public function index()
	{
		//redirect('rujukan/list_all');
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
		
		
		$tgl1 = $this->input->get('tgl1');
		$tgl2 = $this->input->get('tgl2');

		//$filters = array();
		$filters = array(
						
						'tgl1' => $tgl1,
						'tgl2' => $tgl2
						);
		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);
		
		

		$items = $this->Ticket->search_rujukan_keluar_saya($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Ticket->get_rujukan_keluar_saya_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach($items->result() as $item)
		{
			$data_rows[] = $this->xss_clean(get_rujukankeluarsaya_ticket_data_row($item, ++$no));
			
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters ));
	}
	

	

	/*
	Gets one row for a standard manage table. This is called using AJAX to update one row.
	*/
	public function get_row($row_id)
	{
		$item = $this->Ticket->get_info($row_id);

		$data_row = $this->xss_clean(get_ticket_data_row($item));

		echo json_encode($data_row);
	}

	public function view($item_id = -1)
	{
		
		
	}
	
	public function edit($item_id = -1)
	{
	}

	public function save($item_id = -1)
	{
	}

	public function delete()
	{
	}
	
}
?>
