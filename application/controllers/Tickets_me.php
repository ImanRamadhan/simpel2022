<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Tickets_me extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('tickets_me');

	}
	
	public function index()
	{
		redirect('tickets_me/list_all');
	}
	
	public function setup_search(&$data)
	{
		
		$data['cities'] = get_filters();
		
		$data['fields'] = array(
			'id_layanan' => 'ID Pengaduan',
			'nama_pelapor' => 'Nama Pelapor',
			'email' => 'Email Pelapor',
			'isu_topik' => 'Isu Topik',
			'isi_pengaduan' => 'Isi Pengaduan',
			'no_telp' => 'No. Telp'
		
		);
		
		$data['datasources'] = array(
			'' => '',
			'SP4N' => 'SP4N',
			'PPID' => 'PPID'
		);
		
		$categories = $this->Ticket->get_categories();
		$categories_array = array('0' => '');
		foreach($categories->result() as $cat)
		{
			$categories_array[$cat->id] = $cat->desc;
		}
		$data['categories'] = $categories_array;
		
	}
	
	public function list_all()
	{
		$data['title'] = 'Layanan Saya';
		$data['table_headers'] = $this->xss_clean(get_tickets_manage_table_headers());
		
		$data['filters'] = array(
			);
		$data['status_filter'] = '';	
		
		$this->setup_search($data);
		
		$this->load->view('tickets_me/manage', $data);
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
		
		$kota = $this->input->get('kota');
		$tgl1 = $this->input->get('tgl1');
		$tgl2 = $this->input->get('tgl2');
		
		$status = $this->input->get('status[]');
		$tl = $this->input->get('tl[]');
		$fb = $this->input->get('fb[]');
		
		$field = $this->input->get('field');
		$keyword = $this->input->get('keyword');
		$kategori = $this->input->get('kategori');
		$jenis = $this->input->get('jenis');
		
		$status = $this->input->get('status[]');
		$tl = $this->input->get('tl[]');
		$fb = $this->input->get('fb[]');
		$is_rujuk = $this->input->get('is_rujuk[]');
		$is_verified = $this->input->get('is_verified[]');
		
		$iden_profesi = $this->input->get('iden_profesi');
		$submited_via = $this->input->get('submited_via');

		//$filters = array();
		$filters = array(
						'tgl1' => $tgl1,
						'tgl2' => $tgl2,
						'status' => $status,
						'tl' => $tl,
						'fb' => $fb,
						'field' => $field,
						'keyword' => $keyword,
						'kategori' => $kategori,
						'jenis' => $jenis,
						'is_rujuk' => $is_rujuk,
						'is_verified' => $is_verified,
						'iden_profesi' => $iden_profesi,
						'submited_via' => $submited_via
						);
						
		if(is_pusat())
			$filters['kota'] = $kota;
		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);
		
		

		$items = $this->Ticket->search_mytickets($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Ticket->get_mytickets_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach($items->result() as $item)
		{
			$data_rows[] = $this->xss_clean(get_my_ticket_data_row($item, ++$no));
			
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

	public function create()
	{
	}

	public function save($item_id = -1)
	{
	}

	public function delete()
	{
	
		$items_to_delete = $this->input->post('ids');

		if ($this->Ticket->delete_list($items_to_delete)) {
			$message = $this->lang->line('items_successful_deleted') . ' ' . count($items_to_delete) . ' ' . $this->lang->line('items_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		} else {
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_cannot_be_deleted')));
		}

	}
	
}
?>
