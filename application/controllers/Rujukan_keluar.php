<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Rujukan_keluar extends Secure_Controller
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
		$status = $this->input->get('status');

		//$filters = array();
		$filters = array(
						'tgl1' => $tgl1,
						'tgl2' => $tgl2,
						'status' => $status
						);
		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);
		
		

		$items = $this->Ticket->search_rujukan_keluar($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Ticket->get_rujukan_keluar_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach($items->result() as $item)
		{
			$data_rows[] = $this->xss_clean(get_rujukankeluar_ticket_data_row($item, ++$no));
			//$data_rows[] = $this->xss_clean(get_rujukankeluar_ticket_data_row($item));
			//$data_rows[] = $item->trackid;
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters ));
	}
	

	

	/*
	Gets one row for a standard manage table. This is called using AJAX to update one row.
	*/
	public function get_row($row_id)
	{
		
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
