<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Monitorings extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('monitorings');

	}
	
	public function index()
	{
	}
	
	public function setup_search(&$data)
	{
		//$cities_array = get_filters();
		//$data['cities'] = $cities_array;
	}
	
	public function monbalai()
	{
		$data['title'] = 'Rujukan Masuk';
		$data['table_headers'] = $this->xss_clean(get_monbalai_manage_table_headers());
		
		$data['filters'] = array(
			);
		$data['status_filter'] = '';	
		
		$this->setup_search($data);
		
		$this->load->view('monitoring/manage_monbalai', $data);
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
		
		

		$items = $this->Ticket->search_monbalai($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Ticket->get_monbalai_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach($items->result() as $item)
		{
			$data_rows[] = $this->xss_clean(get_monbalai_data_row($item, ++$no));
			
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters ));
	}
	

	public function get_row($row_id)
	{
	}

	public function view($item_id = -1)
	{
	}
	
	public function edit($item_id = -1)
	{
	}

	public function create()
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
