<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Rujukan extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('rujukan');
	}

	public function index() {}

	public function setup_search(&$data)
	{

		$cities_array = get_filters();
		$data['cities'] = $cities_array;


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
		foreach ($categories->result() as $cat) {
			$categories_array[$cat->id] = $cat->desc;
		}
		$data['categories'] = $categories_array;
	}

	public function list_masuk()
	{
		$data['title'] = 'Rujukan Masuk';
		$data['table_headers'] = $this->xss_clean(get_rujukanmasuk_tickets_manage_table_headers());

		$data['city_filter'] = $this->input->get('kota');
		$data['tgl1'] = $this->input->get('tgl1');
		$data['tgl2'] = $this->input->get('tgl2');
		$data['status'] = $this->input->get('status') == null ? '0' : $this->input->get('status');

		$data['status_filter'] = '';

		$data['filters'] = array();

		$this->setup_search($data);

		$this->load->view('rujukan/manage_masuk', $data);
	}

	public function list_keluar()
	{
		$data['title'] = 'Rujukan Keluar';
		$data['table_headers'] = $this->xss_clean(get_rujukankeluar_tickets_manage_table_headers());

		$data['tgl1'] = $this->input->get('tgl1');
		$data['tgl2'] = $this->input->get('tgl2');
		$data['status'] = $this->input->get('status');
		$data['filters'] = array();
		$data['status_filter'] = '';

		$this->setup_search($data);

		$this->load->view('rujukan/manage_keluar', $data);
	}

	public function list_keluar_saya()
	{
		$data['title'] = 'Rujukan Keluar Saya';
		$data['table_headers'] = $this->xss_clean(get_rujukankeluarsaya_tickets_manage_table_headers());

		$data['filters'] = array();
		$data['status_filter'] = '';

		$this->setup_search($data);

		$this->load->view('rujukan/manage_keluar_saya', $data);
	}

	public function list_status_closed()
	{
		$data['title'] = 'Rujukan Status Closed';
		$data['table_headers'] = $this->xss_clean(get_rujukanstatusclosed_tickets_manage_table_headers());

		$data['filters'] = array();
		$data['status_filter'] = '';

		$this->setup_search($data);

		$this->load->view('rujukan/manage_status_closed', $data);
	}

	public function list_status_fb()
	{
		$data['title'] = 'Rujukan Status Feedback';
		$data['table_headers'] = $this->xss_clean(get_rujukanstatusfb_tickets_manage_table_headers());

		$data['filters'] = array();
		$data['status_filter'] = '';

		$this->setup_search($data);

		$this->load->view('rujukan/manage_status_fb', $data);
	}

	public function list_status_tl()
	{
		$data['title'] = 'Rujukan Status TL Pengaduan';
		$data['table_headers'] = $this->xss_clean(get_rujukanstatustl_tickets_manage_table_headers());

		$data['filters'] = array();
		$data['status_filter'] = '';

		$this->setup_search($data);

		$this->load->view('rujukan/manage_status_tl', $data);
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

		//$filters = array();
		$filters = array(
			'kota' => $kota,
			'tgl1' => $tgl1,
			'tgl2' => $tgl2
		);
		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);



		$items = $this->Ticket->search($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Ticket->get_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach ($items->result() as $item) {
			$data_rows[] = $this->xss_clean(get_ticket_data_row($item, ++$no));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters));
	}


	public function get_row($row_id) {}

	public function view($item_id = -1)
	{
		$item_id = (int)$item_id;
		//$item_id = base64_decode($item_id);

		$item_info = $this->Ticket->get_info($item_id);
		if (empty($item_info->id)) {
			redirect('/NotFound404/view/rujukan', 'refresh');
		}

		if (!checkAuthorize($item_info)) {
			redirect('/NoAuth401/view/rujukan', 'refresh');
		}

		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;

		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach (get_object_vars($ppid_info) as $property => $value) {
			$ppid_info->$property = $this->xss_clean($value);
		}
		$data['ppid_info'] = $ppid_info;

		$data['rujukans'] = $this->Ticket->get_rujukans(
			$item_info->direktorat,
			$item_info->direktorat2,
			$item_info->direktorat3,
			$item_info->direktorat4,
			$item_info->direktorat5 /*, $item_info->direktorat6, $item_info->direktorat7*/
		);


		$data['replies'] = $this->Ticket->get_replies($item_id);
		$data['replies_cnt'] = count($data['replies']);

		$referer = $this->input->get('ref');
		if ($referer == 'rujukan_masuk')
			$data['url_back'] = site_url('rujukan/list_masuk');
		elseif ($referer == 'rujukan_keluar')
			$data['url_back'] = site_url('rujukan/list_keluar');
		elseif ($referer == 'rujukan_keluar_saya')
			$data['url_back'] = site_url('rujukan/list_keluar_saya');
		elseif ($referer == 'rujukan_status_closed')
			$data['url_back'] = site_url('rujukan/list_status_closed');
		elseif ($referer == 'rujukan_status_tl')
			$data['url_back'] = site_url('rujukan/list_status_tl');
		elseif ($referer == 'rujukan_status_fb')
			$data['url_back'] = site_url('rujukan/list_status_fb');
		elseif ($referer == 'layanan_saya')
			$data['url_back'] = site_url('tickets/list_my');
		elseif ($referer == 'layanan_yanblik')
			$data['url_back'] = site_url('tickets/list_yanblik');
		elseif ($referer == 'notifikasi')
			$data['url_back'] = site_url('notifications');
		else
			$data['url_back'] = site_url('tickets/list_all');

		$data['upload_url'] = site_url('tickets/send_reply');


		$attachments = $this->Ticket->get_attachments($item_info->trackid, 0); //attachment pengaduan
		$array = array();
		foreach ($attachments->result() as $row) {
			$array[] = array(
				//'saved_name' => $row->saved_name,
				//'att_id' => $row->att_id,
				'real_name' => $row->real_name,
				//'url' => base_url().'uploads/files/'.$row->saved_name
				'url' => site_url('downloads/download_attachment_ticket?att_id=' . $row->att_id . '&trackid=' . $item_info->trackid)
			);
		}
		$data['att_pengaduan'] = $array;
		/*print('<pre>');
		print_r($data['att_pengaduan']);
		print('</pre>');
		exit;*/

		$attachments2 = $this->Ticket->get_attachments($item_info->trackid, 1); //attachment jawaban
		$array2 = array();
		foreach ($attachments2->result() as $row) {
			$array2[] = array(
				//'saved_name' => $row->saved_name,
				//'att_id' => $row->att_id,
				'real_name' => $row->real_name,
				//'url' => base_url().'uploads/files/'.$row->saved_name,
				'url' => site_url('downloads/download_attachment_ticket?att_id=' . $row->att_id . '&trackid=' . $item_info->trackid)
			);
		}
		$data['att_jawaban'] = $array2;

		if ($item_info->is_rujuk)
			$this->Notification->mark_as_read($item_info->id, $this->session->id);

		$data['upload_config'] = $this->config->item('upload_setting');

		$this->load->view('rujukan/view', $data);
	}

	public function edit($item_id = -1) {}

	public function create() {}

	public function save($item_id = -1) {}

	public function delete() {}

	public function confirm_tl_yes($id = 0, $item_id = -1, $dir_id = -1)
	{
		$data['url_post'] = site_url('rujukan/save_tl_yes/' . $id . '/' . $item_id . '/' . $dir_id);
		$data['message'] = 'Apakah Anda ingin mengubah status rujukan menjadi Sudah TL?';
		$this->load->view('rujukan/confirm_change_status', $data);
	}

	public function save_tl_yes($id = 0, $item_id = -1, $dir_id = -1)
	{
		if (!empty($item_id)) {
			$id = (int)$id;
			$item_id = (int)$item_id;
			$dir_id = (int)$dir_id;

			$item_data = array(
				'status_rujuk' . $id => 1,
				'tl_date' . $id => date('Y-m-d H:i:s')
			);

			if ($this->Rujuk->save($item_data, $item_id, $dir_id)) {

				$flash_msg = array(
					'success' => TRUE,
					'message' => '<i class="fa fa-check-circle" aria-hidden="true"></i> Status rujukan berhasil diubah'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			} else {
				$flash_msg = array(
					'success' => FALSE,
					'message' => '<i class="fa fa-times-circle" aria-hidden="true"></i> Status rujukan gagal diubah'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			}
			redirect('tickets/view/' . $item_id);
		}
	}

	public function confirm_tl_no($id = 0, $item_id = -1, $dir_id = -1)
	{
		$data['url_post'] = site_url('rujukan/save_tl_no/' . $id . '/' . $item_id . '/' . $dir_id);
		$data['message'] = 'Apakah Anda ingin mengubah status rujukan menjadi Belum TL?';
		$this->load->view('rujukan/confirm_change_status', $data);
	}

	public function save_tl_no($id = 0, $item_id = -1, $dir_id = -1)
	{
		if (!empty($item_id)) {
			$id = (int)$id;
			$item_id = (int)$item_id;
			$dir_id = (int)$dir_id;

			$item_data = array(
				'status_rujuk' . $id => 0,
				'tl_date' . $id => NULL
			);

			if ($this->Rujuk->save($item_data, $item_id, $dir_id)) {

				$flash_msg = array(
					'success' => TRUE,
					'message' => '<i class="fa fa-check-circle" aria-hidden="true"></i> Status layanan berhasil diubah menjadi Belum TL'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			} else {
				$flash_msg = array(
					'success' => FALSE,
					'message' => '<i class="fa fa-times-circle" aria-hidden="true"></i> Status layanan gagal diubah menjadi Belum TL'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			}
			redirect('tickets/view/' . $item_id);
		}
	}
}
