<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Tickets_sla_perform extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('tickets_sla');

	}
	
	public function index()
	{
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
		
		$tl = $this->input->get('tl');
		$sla = $this->input->get('sla');
		
		

		//$filters = array();
		$filters = array(
						'tgl1' => $tgl1,
						'tgl2' => $tgl2,
						'tl' => $tl,
						'sla' => $sla
						);
						
		if(is_pusat())
			$filters['kota'] = $kota;
		elseif(is_unit_teknis())
			$filters['kota'] = 'UNIT TEKNIS';
		
		//print_r($filters);exit;
		
		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);
		
		

		$items = $this->Ticket->search_sla_tickets_perform($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Ticket->get_sla_tickets_perform_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach($items->result() as $item)
		{
			$data_rows[] = $this->xss_clean(get_ticket_data_row($item, ++$no));
			
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters ));
	}
	
	/*
	Gets one row for a standard manage table. This is called using AJAX to update one row.
	*/
	public function get_row($row_id)
	{
		$item = $this->Ticket->get_info($row_id);

		$data_row = $this->xss_clean(get_yanblik_ticket_data_row($item));

		echo json_encode($data_row);
	}

	public function view($item_id = -1)
	{
		$item_id = (int)$item_id;
		//$item_id = base64_decode($item_id);
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		
		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		$data['ppid_info'] = $ppid_info;
		
		$rujukan_info = $this->Ticket->get_rujukan_info($item_id);
		foreach(get_object_vars($rujukan_info) as $property => $value)
		{
			$rujukan_info->$property = $this->xss_clean($value);
		}
		$data['rujukan_info'] = $rujukan_info;
		
		//$data['rujukans'] = $this->Ticket->get_rujukans($item_info->direktorat, $item_info->direktorat2,
		//$item_info->direktorat3, $item_info->direktorat4, $item_info->direktorat5 /*, $item_info->direktorat6, $item_info->direktorat7*/);
		
		//$data['rujukans'] = $this->Ticket->get_rujukans($item_id);
		
		$data['replies'] = $this->Ticket->get_replies($item_id);
		$data['replies_cnt'] = count($data['replies']);
		
		$referer = $this->input->get('ref');
		if($referer == 'rujukan_masuk')
			$data['url_back'] = site_url('rujukan/list_masuk');
		elseif($referer == 'rujukan_keluar')
			$data['url_back'] = site_url('rujukan/list_keluar');
		elseif($referer == 'rujukan_keluar_saya')
			$data['url_back'] = site_url('rujukan/list_keluar_saya');
		elseif($referer == 'rujukan_status_closed')
			$data['url_back'] = site_url('rujukan/list_status_closed');
		elseif($referer == 'rujukan_status_tl')
			$data['url_back'] = site_url('rujukan/list_status_tl');
		elseif($referer == 'rujukan_status_fb')
			$data['url_back'] = site_url('rujukan/list_status_fb');
		elseif($referer == 'layanan_saya')
			$data['url_back'] = site_url('tickets/list_my');
		elseif($referer == 'layanan_yanblik')
			$data['url_back'] = site_url('tickets/list_yanblik');
		elseif($referer == 'notifikasi')
			$data['url_back'] = site_url('notifications');
		else	
			$data['url_back'] = site_url('tickets/list_all');
		
		$data['upload_url'] = site_url('tickets/send_reply');
		
		
		$attachments = $this->Ticket->get_attachments($item_info->trackid,0); //attachment pengaduan
		$array = array();
		foreach($attachments->result() as $row)
		{
			$array[] = array(
				//'saved_name' => $row->saved_name,
				//'att_id' => $row->att_id,
				'real_name' => $row->real_name,
				//'url' => base_url().'uploads/files/'.$row->saved_name
				'url' => site_url('downloads/download_attachment_ticket?att_id='.$row->att_id.'&trackid='.$item_info->trackid)
			);
		}
		$data['att_pengaduan'] = $array;
		/*print('<pre>');
		print_r($data['att_pengaduan']);
		print('</pre>');
		exit;*/
		
		$attachments2 = $this->Ticket->get_attachments($item_info->trackid,1); //attachment jawaban
		$array2 = array();
		foreach($attachments2->result() as $row)
		{
			$array2[] = array(
				//'saved_name' => $row->saved_name,
				//'att_id' => $row->att_id,
				'real_name' => $row->real_name,
				//'url' => base_url().'uploads/files/'.$row->saved_name,
				'url' => site_url('downloads/download_attachment_ticket?att_id='.$row->att_id.'&trackid='.$item_info->trackid)
			);
		}
		$data['att_jawaban'] = $array2;
		
		if($item_info->is_rujuk)
			$this->Notification->mark_as_read($item_info->id, $this->session->id);
		
		$data['upload_config'] = $this->config->item('upload_setting');
		
		$this->load->view('tickets/view', $data);
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
