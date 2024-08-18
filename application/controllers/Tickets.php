<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");


class Tickets extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('tickets');
	}

	public function index()
	{
		redirect('tickets/list_all');
	}

	public function setup_search(&$data)
	{

		$data['cities'] = get_filters();

		/*$data['fields'] = array(
			'id_layanan' => 'ID Pengaduan',
			'nama_pelapor' => 'Nama Pelapor',
			'email' => 'Email Pelapor',
			'isu_topik' => 'Isu Topik',
			'isi_pengaduan' => 'Isi Pengaduan',
			'no_telp' => 'No. Telp'
		
		);*/

		$data['datasources'] = array(
			'' => 'ALL',
			'LAYANAN' => 'Layanan',
			'SP4N' => 'SP4N Lapor',
			'PPID' => 'PPID'
		);

		$data['fields'] = array(
			'trackid' => 'ID Layanan',
			'cust_nama' => 'Nama Pelapor',
			'cust_email' => 'Email Pelapor',
			'isu_topik' => 'Isu Topik',
			'isi_layanan' => 'Isi Layanan',
			'cust_telp' => 'No. Telp Pelapor',
			'jawaban' => 'Jawaban',
			'penerima' => 'Kode Petugas',
			'klasifikasi' => 'Klasifikasi',
			'subklasifikasi' => 'Subklasifikasi',
			'perusahaan_instansi' => 'Perusahaan/Instansi'
		);

		$categories = $this->Ticket->get_categories();
		$categories_array = array('' => 'ALL');
		foreach ($categories->result() as $cat) {
			$categories_array[$cat->id] = $cat->desc;
		}
		$data['categories'] = $categories_array;

		$jobs = $this->Ticket->get_profesi();
		$jobs_array = array('' => 'ALL');
		foreach ($jobs->result() as $cat) {
			$jobs_array[$cat->id] = $cat->name;
		}
		$data['jobs'] = $jobs_array;

		$mekanism = $this->Ticket->get_mekanisme();
		$mekanism_array = array('' => 'ALL');
		foreach ($mekanism->result() as $cat) {
			$mekanism_array[$cat->nama] = $cat->nama;
		}
		$data['mekanism'] = $mekanism_array;
	}

	public function list_all()
	{
		/*$param = $this->input->get('page');
		if(isset($param))
		{
			if($param == 'pusat')
				$this->session->set_flashdata('page', 'pusat');	
			elseif($param == 'cc')
				$this->session->set_flashdata('page', 'cc');	
			elseif($param == 'balai')
				$this->session->set_flashdata('page', 'balai');
			elseif($param == 'all')
				$this->session->set_flashdata('page', 'all');
		}*/

		$data['city_filter'] = $this->input->get('kota');
		$data['tgl1'] = $this->input->get('tgl1');
		$data['tgl2'] = $this->input->get('tgl2');
		$data['fb_filter'] = $this->input->get('fb');
		$data['tl_filter'] = $this->input->get('tl');
		$data['status_filter'] = $this->input->get('status');

		//$data['field'] = $this->input->get('field');
		//$data['keyword'] = $this->input->get('keyword');

		$data['title'] = 'Layanan';
		$data['table_headers'] = $this->xss_clean(get_tickets_manage_table_headers());

		$data['filters'] = array();
		//$data['status_filter'] = '';

		$this->setup_search($data);

		$this->load->view('tickets/manage', $data);
	}

	public function list_yanblik()
	{
		$data['title'] = 'Layanan Publik';
		$data['table_headers'] = $this->xss_clean(get_yanblik_tickets_manage_table_headers());

		$data['filters'] = array();
		$data['status_filter'] = '';

		if (is_pusat()) {
			$kota = $this->input->get('kota');
			if (!empty($kota))
				$this->session->tickets_city = $kota;
		}

		$this->setup_search($data);

		$this->load->view('tickets/manage_yablik', $data);
	}

	public function list_verifikasi()
	{
		$data['title'] = 'Layanan Verifikasi Saya';
		$data['table_headers'] = $this->xss_clean(get_verified_tickets_manage_table_headers());

		$data['filters'] = array();
		$data['status_filter'] = '';

		$this->setup_search($data);

		$this->load->view('tickets/manage_verifikasi', $data);
	}

	public function list_my()
	{
		$data['title'] = 'Layanan Saya';
		$data['table_headers'] = $this->xss_clean(get_my_tickets_manage_table_headers());

		$data['tgl1'] = $this->input->get('tgl1');
		$data['tgl2'] = $this->input->get('tgl2');

		$data['fb_filter'] = $this->input->get('fb');
		$data['status_filter'] = $this->input->get('status');

		$data['filters'] = array();


		$this->setup_search($data);

		$this->load->view('tickets/manage_my', $data);
	}

	public function list_sla()
	{
		$data['title'] = 'Layanan Belum di-TL';
		$data['table_headers'] = $this->xss_clean(get_my_tickets_manage_table_headers());

		$data['city_filter'] = $this->input->get('kota');
		$data['tgl1'] = $this->input->get('tgl1');
		$data['tgl2'] = $this->input->get('tgl2');

		$data['tl_filter'] = $this->input->get('tl');
		//$data['status_filter'] = $this->input->get('status');
		$data['sla_filter'] = $this->input->get('sla');

		$data['filters'] = array();

		$this->setup_search($data);

		$this->load->view('tickets/manage_sla', $data);
	}

	public function list_sla_perform()
	{
		$data['title'] = 'Layanan Belum di-TL';
		$data['table_headers'] = $this->xss_clean(get_my_tickets_manage_table_headers());

		$data['city_filter'] = $this->input->get('kota');
		$data['tgl1'] = $this->input->get('tgl1');
		$data['tgl2'] = $this->input->get('tgl2');

		$data['tl_filter'] = $this->input->get('tl');
		//$data['status_filter'] = $this->input->get('status');
		$data['sla_filter'] = $this->input->get('sla');

		$data['filters'] = array();

		$this->setup_search($data);

		$this->load->view('tickets/manage_sla_perform', $data);
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
		$sla = $this->input->get('sla[]');

		$filters = array(
			'tgl1' => $tgl1,
			'tgl2' => $tgl2,
			'field' => $field,
			'keyword' => $keyword,
			'kategori' => $kategori,
			'jenis' => $jenis,
			'status' => $status,
			'tl' => $tl,
			'fb' => $fb,
			'is_rujuk' => $is_rujuk,
			'is_verified' => $is_verified,
			'iden_profesi' => $iden_profesi,
			'submited_via' => $submited_via,
			'sla' => $sla
		);
		if (is_pusat())
			$filters['kota'] = $kota;

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
		$item_id = (int)$item_id;
		//$item_id = base64_decode($item_id);

		$item_info = $this->Ticket->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;

		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach (get_object_vars($ppid_info) as $property => $value) {
			$ppid_info->$property = $this->xss_clean($value);
		}
		$data['ppid_info'] = $ppid_info;

		$rujukan_info = $this->Ticket->get_rujukan_info($item_id);
		foreach (get_object_vars($rujukan_info) as $property => $value) {
			$rujukan_info->$property = $this->xss_clean($value);
		}
		$data['rujukan_info'] = $rujukan_info;

		//$data['rujukans'] = $this->Ticket->get_rujukans($item_info->direktorat, $item_info->direktorat2,
		//$item_info->direktorat3, $item_info->direktorat4, $item_info->direktorat5 /*, $item_info->direktorat6, $item_info->direktorat7*/);

		//$data['rujukans'] = $this->Ticket->get_rujukans($item_id);

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

		if ($item_info->jenis == 'PPID') {
			$attachments_ppidtl = $this->Ticket->get_attachments_ppidtl($item_info->trackid, 0);
			$data["att_ppidtl"] = $attachments_ppidtl->row();
		}

		$this->load->view('tickets/view', $data);
	}

	public function create()
	{
		redirect('drafts/create');
	}

	public function edit($item_id = -1)
	{

		$item_info = $this->Ticket->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;

		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach (get_object_vars($ppid_info) as $property => $value) {
			$ppid_info->$property = $this->xss_clean($value);
		}
		$data['ppid_info'] = $ppid_info;

		//$data['rujukans'] = $this->Ticket->get_rujukans($item_id);

		$data['page_title'] = 'Ubah Data ' . $item_info->trackid;
		$data['answered_via'] = get_sarana();
		$data['submited_via'] = get_sarana();
		$data['countries'] = get_countries();
		$data['provinces'] = get_provinces();
		$data['provinces2'] = get_provinces();
		$data['profesi'] = get_profesi();
		$data['products'] = get_products();

		$data['range_age'] = get_range_age();

		//$data['products'] = get_products_sla($item_info->info);
		$data['sumberdata'] = array('' => '', 'SP4N' => 'SP4N', 'PPID' => 'PPID');

		/*$tgl_cut_off = '2020-10-01';
		
		if($item_info->tglpengaduan <= $tgl_cut_off)
		{
			$data['klasifikasi'] = get_klasifikasi();
		}
		else
			$data['klasifikasi'] = get_klasifikasi_sla($item_info->kategori, $item_info->info);*/

		$data['klasifikasi'] = get_klasifikasi();
		$data['subklasifikasi'] = get_subklasifikasi2($item_info->klasifikasi);

		//$data['dir_rujukan'] = get_direktorat_rujukan();
		if ($this->session->city != 'PUSAT') {
			$data['dir_rujukan'] = get_direktorat_rujukan_for_balai($this->session->city);
		} else {
			$data['dir_rujukan'] = get_direktorat_rujukan();
			$data['dir_rujukan2'] = get_direktorat_rujukan2();
		}

		$data['sla'] = get_sla(); //7
		$data['kabs'] = get_cities($item_info->iden_provinsi);
		/*
		if(!empty($item_info->klasifikasi))
		{
			$array = array(0 => '');
			
			if($item_info->tglpengaduan <= $tgl_cut_off)
				$subkla = $this->Ticket->get_subklasifikasi();
			else
				$subkla = $this->Ticket->get_subklasifikasi_sla($item_info->kategori, $item_info->info, $item_info->klasifikasi_id);
			
			foreach($subkla as $k)
			{
				$array[$k->id] = $k->subklasifikasi;
			}
			
			//print_r($array);
			//exit;
			$data['subklasifikasi'] = $array;
		}
		else
			$data['subklasifikasi'] = array(0 => '');*/

		$data['back_url'] = site_url('tickets/list_all');
		$data['upload_url'] = site_url('tickets/do_upload');

		//$data['commodities_p'] = print_komoditi('P');
		//$data['commodities_i'] = print_komoditi('I');

		$data['subkla'] = get_subklasifikasi_json();

		$data['upload_config'] = $this->config->item('upload_setting');
		$data['mode'] = 'EDIT';

		if ($item_info->jenis == 'PPID' && empty($ppid_info->alasan_keberatan))
			$this->load->view('ppid/form', $data);
		elseif ($item_info->jenis == 'PPID' && !empty($ppid_info->alasan_keberatan))
			$this->load->view('ppid/form_keberatan', $data);
		else
			$this->load->view('tickets/form', $data);
	}

	public function confirm_status_closed($item_id = -1)
	{
		$data['url_post'] = site_url('tickets/save_status_closed/' . $item_id);
		$data['message'] = 'Apakah Anda ingin mengubah status layanan menjadi Closed?';
		$this->load->view('tickets/confirm_change_status', $data);
	}

	public function save_status_closed($item_id = -1)
	{
		if (!empty($item_id)) {
			$item_id = (int)$item_id;

			if ($this->Ticket->change_status($item_id, '3')) {
				$flash_msg = array(
					'success' => TRUE,
					'message' => '<i class="fa fa-check-circle" aria-hidden="true"></i> Status layanan berhasil diubah menjadi Closed'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			} else {
				$flash_msg = array(
					'success' => FALSE,
					'message' => '<i class="fa fa-times-circle" aria-hidden="true"></i> Status layanan gagal diubah menjadi Closed'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			}
			redirect('tickets/view/' . $item_id);
		}
	}

	public function confirm_status_open($item_id = -1)
	{
		$data['url_post'] = site_url('tickets/save_status_open/' . $item_id);
		$data['message'] = 'Apakah Anda ingin mengubah status layanan menjadi Open?';
		$this->load->view('tickets/confirm_change_status', $data);
	}

	public function save_status_open($item_id = -1)
	{
		if (!empty($item_id)) {
			$item_id = (int)$item_id;

			if ($this->Ticket->change_status($item_id, '0')) {

				$flash_msg = array(
					'success' => TRUE,
					'message' => '<i class="fa fa-check-circle" aria-hidden="true"></i> Status layanan berhasil diubah menjadi Open'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			} else {
				$flash_msg = array(
					'success' => FALSE,
					'message' => '<i class="fa fa-times-circle" aria-hidden="true"></i> Status layanan gagal diubah menjadi Open'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			}
			redirect('tickets/view/' . $item_id);
		}
	}

	public function confirm_tl_yes($item_id = -1)
	{
		$data['url_post'] = site_url('tickets/save_tl_yes/' . $item_id);
		$data['message'] = 'Apakah Anda ingin mengubah status TL menjadi Sudah?';
		$this->load->view('tickets/confirm_change_tl_yes', $data);
	}

	public function save_tl_yes($item_id = -1)
	{
		if (!empty($item_id)) {
			$item_id = (int)$item_id;
			$tgl = $this->input->post('tl_date');

			$item_data = array(
				'tl' => 1,
				'tl_date' => convert_date1($tgl) . ' ' . date('H:i:s')
			);

			if ($this->Ticket->change_tl($item_data, $item_id)) {

				$flash_msg = array(
					'success' => TRUE,
					'message' => '<i class="fa fa-check-circle" aria-hidden="true"></i> Status TL berhasil diubah menjadi Sudah'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			} else {
				$flash_msg = array(
					'success' => FALSE,
					'message' => '<i class="fa fa-times-circle" aria-hidden="true"></i> Status TL gagal diubah'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			}
			redirect('tickets/view/' . $item_id);
		}
	}

	public function confirm_tl_no($item_id = -1)
	{
		$data['url_post'] = site_url('tickets/save_tl_no/' . $item_id);
		$data['message'] = 'Apakah Anda ingin mengubah status TL menjadi Belum?';
		$this->load->view('tickets/confirm_change_tl', $data);
	}

	public function update_hk($trackid = -1)
	{
		if ($this->Ticket->update_hk($trackid))
			echo 'OK';
	}

	public function save_tl_no($item_id = -1)
	{
		if (!empty($item_id)) {
			$item_id = (int)$item_id;

			$item_data = array(
				'tl' => 0
			);

			if ($this->Ticket->change_tl($item_data, $item_id)) {

				$flash_msg = array(
					'success' => TRUE,
					'message' => '<i class="fa fa-check-circle" aria-hidden="true"></i> Status TL berhasil diubah menjadi Belum'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			} else {
				$flash_msg = array(
					'success' => FALSE,
					'message' => '<i class="fa fa-times-circle" aria-hidden="true"></i> Status TL gagal diubah'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			}
			redirect('tickets/view/' . $item_id);
		}
	}

	public function confirm_fb_no($item_id = -1)
	{
		$data['url_post'] = site_url('tickets/save_fb_no/' . $item_id);
		$data['message'] = 'Apakah Anda ingin mengubah status Feedback menjadi Belum?';
		$this->load->view('tickets/confirm_change_fb', $data);
	}

	public function save_fb_no($item_id = -1)
	{
		if (!empty($item_id)) {
			$item_id = (int)$item_id;

			$item_data = array(
				'fb' => 0
			);

			if ($this->Ticket->change_fb($item_data, $item_id)) {

				$flash_msg = array(
					'success' => TRUE,
					'message' => '<i class="fa fa-check-circle" aria-hidden="true"></i> Status FB berhasil diubah menjadi Belum'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			} else {
				$flash_msg = array(
					'success' => FALSE,
					'message' => '<i class="fa fa-times-circle" aria-hidden="true"></i> Status FB gagal diubah'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			}
			redirect('tickets/view/' . $item_id);
		}
	}

	public function confirm_upload_signed_formulir($id, $ticketid)
	{
		$data['url_post'] = site_url('tickets/save_signed_formulir/' . $id . '/' . $ticketid);
		$data['message'] = 'Silakan upload bukti formulir PPID yang sudah ditandatangani';
		$data['upload_url'] = site_url('tickets/upload_signed_formulir/' . $id . '/' . $ticketid);
		$data['id'] = $id;
		$data['ticketid'] = $ticketid;
		$this->load->view('tickets/confirm_upload_signed_formulir', $data);
	}

	public function upload_signed_formulir($id, $ticket_id)
	{
		$this->load->library('upload', $this->config->item('upload_setting'));

		$files = $_FILES;
		$cpt = count($_FILES['file']['name']);

		for ($i = 0; $i < $cpt; $i++) {
			$name = $files['file']['name'][$i];
			$_FILES['file']['name'] = $name;
			$_FILES['file']['type'] = $files['file']['type'][$i];
			$_FILES['file']['tmp_name'] = $files['file']['tmp_name'][$i];
			$_FILES['file']['error'] = $files['file']['error'][$i];
			$_FILES['file']['size'] = $files['file']['size'][$i];

			if ($this->upload->do_upload('file')) {
				$data = $this->upload->data();
				$att_data = array(
					'saved_name' => $data['file_name'],
					'real_name' => $data['orig_name'],
					'size' => $files['file']['size'][$i],
					'ticket_id' => $ticket_id,
					'mode' => $this->input->post('mode')
				);

				if ($this->Ticket->save_attachment_ppidtl($att_data)) {
					$id = $att_data['id'];
					echo json_encode(array('id' => $id, 'url' => base_url() . 'uploads/files/' . $att_data['saved_name'], 'error' => 0, 'message' => ''));
				}
			} else {
				echo json_encode(array('error' => 1, 'message' => $this->upload->display_errors()));
			}
		}
	}

	public function confirm_fb_yes($item_id = -1)
	{
		$data['url_post'] = site_url('tickets/save_fb_yes/' . $item_id);
		$data['message'] = 'Apakah Anda ingin mengubah status Feedback menjadi Sudah?';
		$this->load->view('tickets/confirm_change_fb_yes', $data);
	}

	public function save_fb_yes($item_id = -1)
	{
		if (!empty($item_id)) {
			$item_id = (int)$item_id;
			$tgl = $this->input->post('fb_date');
			$fb_isi = $this->input->post('fb_isi');

			$item_data = array(
				'fb' => 1,
				'fb_date' => convert_date1($tgl) . ' ' . date('H:i:s'),
				'fb_isi' => $fb_isi
			);

			if ($this->Ticket->change_fb($item_data, $item_id)) {

				$flash_msg = array(
					'success' => TRUE,
					'message' => '<i class="fa fa-check-circle" aria-hidden="true"></i> Status FB berhasil diubah menjadi Sudah'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			} else {
				$flash_msg = array(
					'success' => FALSE,
					'message' => '<i class="fa fa-times-circle" aria-hidden="true"></i> Status FB gagal diubah'
				);
				$this->session->set_flashdata('flash', $flash_msg);
			}
			redirect('tickets/view/' . $item_id);
		}
	}

	public function save($item_id = -1)
	{
		$item_info = $this->Ticket->get_info($item_id);

		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}

		$old_date = explode('-', $item_info->tglpengaduan);
		$today = explode('/', date('d/m/Y'));

		$locked = lockedForEdit($today, $old_date);
		if ($this->session->user == 'admin_pusat')
			$locked = false;

		if ($locked) {
			$message = 'Tanggal pengaduan di bulan lalu dapat diubah sebelum tanggal 10 setiap bulannya';

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
			exit;
		}

		$iden_nama = $this->input->post('iden_nama');
		$iden_jk =  $this->input->post('iden_jk');
		$iden_instansi =  $this->input->post('iden_instansi');
		$iden_jenis =  $this->input->post('iden_jenis');
		$iden_alamat =  $this->input->post('iden_alamat');
		$iden_email =  $this->input->post('iden_email');
		$iden_negara =  $this->input->post('iden_negara');
		$iden_provinsi =  $this->input->post('iden_provinsi');
		$iden_provinsi2 =  $this->input->post('iden_provinsi2');
		$iden_kota =  $this->input->post('iden_kota');
		$iden_kota2 =  $this->input->post('iden_kota2');

		if (strtolower($iden_negara) != 'indonesia') {
			$iden_provinsi = $iden_provinsi2;
			$iden_kota = $iden_kota2;
		}

		$iden_telp =  $this->input->post('iden_telp');
		$iden_fax =  $this->input->post('iden_fax');
		$iden_profesi =  $this->input->post('iden_profesi');
		$usia = $this->input->post('usia');
		$prod_nama =  $this->input->post('prod_nama');
		$prod_generik =  $this->input->post('prod_generik');
		$prod_pabrik =  $this->input->post('prod_pabrik');
		$prod_noreg =  $this->input->post('prod_noreg');
		$prod_nobatch =  $this->input->post('prod_nobatch');
		$prod_alamat =  $this->input->post('prod_alamat');
		$prod_kota =  $this->input->post('prod_kota');
		$prod_negara =  $this->input->post('prod_negara');
		$prod_provinsi =  $this->input->post('prod_provinsi');
		$prod_provinsi2 =  $this->input->post('prod_provinsi2');

		if (strtolower($prod_negara) != 'indonesia') {
			$prod_provinsi = $prod_provinsi2;
		}

		$prod_kadaluarsa =  $this->input->post('prod_kadaluarsa');
		$prod_diperoleh =  $this->input->post('prod_diperoleh');
		$prod_diperoleh_tgl =  $this->input->post('prod_diperoleh_tgl');
		$prod_digunakan_tgl =  $this->input->post('prod_digunakan_tgl');
		$isu_topik =  $this->input->post('isu_topik');
		$prod_masalah =  $this->input->post('prod_masalah');
		$info =  $this->input->post('info');
		$penerima =  $this->input->post('penerima');
		$kategori =  $this->input->post('kategori');
		$submited_via =  $this->input->post('submited_via');
		$jenis =  $this->input->post('jenis');
		$shift =  $this->input->post('shift');

		$klasifikasi =  $this->input->post('klasifikasi');
		$subklasifikasi = $this->input->post('subklasifikasi');

		$sla = $this->input->post('sla');

		$is_rujuk =  $this->input->post('is_rujuk');
		$dir1 =  $this->input->post('dir1');
		$dir2 =  $this->input->post('dir2');
		$dir3 =  $this->input->post('dir3');
		$dir4 =  $this->input->post('dir4');
		$dir5 =  $this->input->post('dir5');

		if (empty($dir1)) $dir1 = 0;
		if (empty($dir2)) $dir2 = 0;
		if (empty($dir3)) $dir3 = 0;
		if (empty($dir4)) $dir4 = 0;
		if (empty($dir5)) $dir5 = 0;

		// 
		$sla1 =  $this->input->post('sla1');
		$sla2 =  $this->input->post('sla2');
		$sla3 =  $this->input->post('sla3');
		$sla4 =  $this->input->post('sla4');
		$sla5 =  $this->input->post('sla5');

		if ($is_rujuk == '0') {
			$dir1 = 0;
			$dir2 = 0;
			$dir3 = 0;
			$dir4 = 0;
			$dir5 = 0;

			$sla1 = 0;
			$sla2 = 0;
			$sla3 = 0;
			$sla4 = 0;
			$sla5 = 0;

			$sla = 1;
		}

		$jawaban =  $this->input->post('jawaban');
		$keterangan =  $this->input->post('keterangan');
		$petugas_entry =  $this->input->post('petugas_entry');
		$penjawab =  $this->input->post('penjawab');
		$answered_via =  $this->input->post('answered_via');

		$tipe_medsos =  $this->input->post('tipe_medsos');
		if (!empty($submited_via) && $submited_via != 'Medsos')
			$tipe_medsos = '';

		if ($jenis == 'PPID') {
			//ppid data
			$tgl_diterima = $this->input->post('tgl_diterima');
			if (!empty($tgl_diterima))
				$tgl_diterima = convert_date1($tgl_diterima);

			$diterima_via = $this->input->post('diterima_via');
			$no_ktp = $this->input->post('no_ktp');
			$rincian =  $this->input->post('rincian');
			$tujuan =  $this->input->post('tujuan');
			$cara_memperoleh_info = $this->input->post('cara_memperoleh_info');

			if (!empty($cara_memperoleh_info)) {
				$cara_memperoleh_info = implode(',', $cara_memperoleh_info);
			}

			$cara_mendapat_salinan = $this->input->post('cara_mendapat_salinan');

			if (!empty($cara_mendapat_salinan)) {
				$cara_mendapat_salinan = implode(',', $cara_mendapat_salinan);
			}

			$tgl_pemberitahuan_tertulis = $this->input->post('tgl_pemberitahuan_tertulis');
			if (!empty($tgl_pemberitahuan_tertulis))
				$tgl_pemberitahuan_tertulis = convert_date1($tgl_pemberitahuan_tertulis);

			$penguasaan_kami = $this->input->post('penguasaan_kami');
			$penguasaan_kami_teks = $this->input->post('penguasaan_kami_teks');
			$penguasaan_badan_lain = $this->input->post('penguasaan_badan_lain');
			$nama_badan_lain = $this->input->post('nama_badan_lain');

			$bentuk_fisik = $this->input->post('bentuk_fisik');

			if (!empty($bentuk_fisik)) {
				$bentuk_fisik = implode(',', $bentuk_fisik);
			}

			$penyalinan = $this->input->post('penyalinan');
			$biaya_penyalinan_lbr = !empty($this->input->post('biaya_penyalinan_lbr')) ? $this->input->post('biaya_penyalinan_lbr') : 0;
			$biaya_penyalinan = !empty($this->input->post('biaya_penyalinan')) ? $this->input->post('biaya_penyalinan') : 0;

			$pengiriman = $this->input->post('pengiriman');
			$biaya_pengiriman = !empty($this->input->post('biaya_pengiriman')) ? $this->input->post('biaya_pengiriman') : 0;
			$lain_lain = $this->input->post('lain_lain');
			$biaya_lain = !empty($this->input->post('biaya_lain')) ? $this->input->post('biaya_lain') : 0;

			$biaya_total = ($biaya_penyalinan * $biaya_penyalinan_lbr) + $biaya_pengiriman + $biaya_lain;

			$nama_pejabat_ppid = $this->input->post('nama_pejabat_ppid');

			$waktu_penyediaan = $this->input->post('waktu_penyediaan');
			$waktu_penyediaan2 = $this->input->post('waktu_penyediaan2');
			$penghitaman = $this->input->post('penghitaman');
			$info_blm_dikuasai = $this->input->post('info_blm_dikuasai');
			$info_blm_didoc = $this->input->post('info_blm_didoc');
			$pengecualian_pasal17 = $this->input->post('pengecualian_pasal17');
			$pengecualian_pasal_lain = $this->input->post('pengecualian_pasal_lain');
			$pasal17_huruf = $this->input->post('pasal17_huruf');
			$pasal_lain_uu = $this->input->post('pasal_lain_uu');
			$konsekuensi = $this->input->post('konsekuensi');

			$tt_tgl = $this->input->post('tt_tgl');
			if (!empty($tt_tgl))
				$tt_tgl = convert_date1($tt_tgl);
			$tt_nomor = $this->input->post('tt_nomor');
			$tt_lampiran = $this->input->post('tt_lampiran');
			$tt_perihal = $this->input->post('tt_perihal');
			$tt_isi = $this->input->post('tt_isi');
			$tt_pejabat = $this->input->post('tt_pejabat');

			$keputusan = $this->input->post('keputusan');

			$kuasa_nama = $this->input->post('kuasa_nama');
			$kuasa_alamat = $this->input->post('kuasa_alamat');
			$kuasa_telp = $this->input->post('kuasa_telp');
			$kuasa_email = $this->input->post('kuasa_email');
			$alasan_keberatan = $this->input->post('alasan_keberatan');

			if (!empty($alasan_keberatan)) {
				$alasan_keberatan = implode(',', $alasan_keberatan);
			}


			$kasus_posisi = $this->input->post('kasus_posisi');
			$tgl_tanggapan = $this->input->post('tgl_tanggapan');
			if (!empty($tgl_tanggapan))
				$tgl_tanggapan = convert_date1($tgl_tanggapan);

			$nama_petugas = $this->input->post('nama_petugas');
			$keberatan_tgl = $this->input->post('keberatan_tgl');
			if (!empty($keberatan_tgl))
				$keberatan_tgl = convert_date1($keberatan_tgl);

			$keberatan_no = $this->input->post('keberatan_no');
			$keberatan_lampiran = $this->input->post('keberatan_lampiran');
			$keberatan_perihal = $this->input->post('keberatan_perihal');
			$keberatan_isi_surat = $this->input->post('keberatan_isi_surat');
			$keberatan_nama_pejabat = $this->input->post('keberatan_nama_pejabat');
		}

		if ($jenis == 'PPID') {
			$ppid_data = array(
				'tgl_diterima' => $tgl_diterima,
				'diterima_via' => $diterima_via,
				'no_ktp' => $no_ktp,
				'rincian' => $rincian,
				'tujuan' => $tujuan,
				'cara_memperoleh_info' => $cara_memperoleh_info,
				'cara_mendapat_salinan' => $cara_mendapat_salinan,

				'tgl_pemberitahuan_tertulis' => $tgl_pemberitahuan_tertulis,
				'penguasaan_kami' => $penguasaan_kami,
				'penguasaan_kami_teks' => $penguasaan_kami_teks,
				'penguasaan_badan_lain' => $penguasaan_badan_lain,
				'nama_badan_lain' => $nama_badan_lain,
				'bentuk_fisik' => $bentuk_fisik,
				'penyalinan' => $penyalinan,
				'biaya_penyalinan' => $biaya_penyalinan,
				'biaya_penyalinan_lbr' => $biaya_penyalinan_lbr,
				'pengiriman' => $pengiriman,
				'biaya_pengiriman' => $biaya_pengiriman,
				'lain_lain' => $lain_lain,
				'biaya_lain' => $biaya_lain,
				'biaya_total' => $biaya_total,
				'waktu_penyediaan' => $waktu_penyediaan,
				'waktu_penyediaan2' => $waktu_penyediaan2,
				'penghitaman' => $penghitaman,
				'info_blm_dikuasai' => $info_blm_dikuasai,
				'info_blm_didoc' => $info_blm_didoc,
				'pengecualian_pasal17' => $pengecualian_pasal17,
				'pengecualian_pasal_lain' => $pengecualian_pasal_lain,
				'pasal17_huruf' => $pasal17_huruf,
				'pasal_lain_uu' => $pasal_lain_uu,
				'konsekuensi' => $konsekuensi,
				'tt_tgl' => $tt_tgl,
				'tt_nomor' => $tt_nomor,
				'tt_lampiran' => $tt_lampiran,
				'tt_perihal' => $tt_perihal,
				'tt_isi' => $tt_isi,
				'tt_pejabat' => $tt_pejabat,
				'keputusan' => $keputusan,
				'nama_pejabat_ppid' => $nama_pejabat_ppid,
				'kuasa_nama' => $kuasa_nama,
				'kuasa_alamat' => $kuasa_alamat,
				'kuasa_telp' => $kuasa_telp,
				'kuasa_email' => $kuasa_email,
				'alasan_keberatan' => $alasan_keberatan,
				'kasus_posisi' => $kasus_posisi,
				'tgl_tanggapan' => $tgl_tanggapan,
				'nama_petugas' => $nama_petugas,
				'keberatan_tgl' => $keberatan_tgl,
				'keberatan_no' => $keberatan_no,
				'keberatan_lampiran' => $keberatan_lampiran,
				'keberatan_perihal' => $keberatan_perihal,
				'keberatan_isi_surat' => $keberatan_isi_surat,
				'keberatan_nama_pejabat' => $keberatan_nama_pejabat
			);
		}

		//Save item data
		$item_data = array(
			'iden_nama' => $iden_nama,
			'iden_jk' => $iden_jk,
			'iden_instansi' => $iden_instansi,
			'iden_jenis' => $iden_jenis,
			'iden_alamat' => $iden_alamat,
			'iden_email' => $iden_email,
			'iden_negara' => $iden_negara,
			'iden_provinsi' => $iden_provinsi,
			//'iden_provinsi2' =>                
			'iden_kota' => $iden_kota,
			'iden_telp' => $iden_telp,
			'iden_fax' => $iden_fax,
			'iden_profesi' => $iden_profesi,
			'usia' => $usia,
			'prod_nama' => $prod_nama,
			'prod_generik' => $prod_generik,
			'prod_pabrik' => $prod_pabrik,
			'prod_noreg' => $prod_noreg,
			'prod_nobatch' => $prod_nobatch,
			'prod_alamat' => $prod_alamat,
			'prod_kota' => $prod_kota,
			'prod_provinsi' => $prod_provinsi,
			'prod_negara' => $prod_negara,
			'prod_kadaluarsa' => $prod_kadaluarsa,
			'prod_diperoleh' => $prod_diperoleh,
			'prod_diperoleh_tgl' => $prod_diperoleh_tgl,
			'prod_digunakan_tgl' => $prod_digunakan_tgl,
			'isu_topik' => $isu_topik,
			'prod_masalah' => $prod_masalah,
			'info' => $info,
			'penerima' => $penerima,
			'kategori' => $kategori,
			'submited_via' => $submited_via,
			'jenis' => $jenis,
			'shift' => $shift,
			'klasifikasi' => $klasifikasi,
			'subklasifikasi' => $subklasifikasi,
			'sla' => $sla,
			'is_rujuk' => $is_rujuk,

			'direktorat' => $dir1,
			'direktorat2' => $dir2,
			'direktorat3' => $dir3,
			'direktorat4' => $dir4,
			'direktorat5' => $dir5,

			'd1_prioritas' => $sla1,
			'd2_prioritas' => $sla2,
			'd3_prioritas' => $sla3,
			'd4_prioritas' => $sla4,
			'd5_prioritas' => $sla5,

			'jawaban' =>  $jawaban,
			'keterangan' =>  $keterangan,
			'petugas_entry' =>  $petugas_entry,
			'penjawab' =>  $penjawab,
			'answered_via' =>  $answered_via,
			'tipe_medsos' => $tipe_medsos

		);

		$item_info = $this->Ticket->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}

		if ($this->Ticket->save($item_data, $item_id)) {
			$item_info2 = $this->Ticket->get_info($item_id);
			log_layanan($item_info, $item_info2);
			$message = 'Data berhasil disimpan';
			if ($jenis == 'PPID')
				$this->Ticket->save_ppid($ppid_data, $item_id);

			if ($is_rujuk == '1') {
				$rujukan_data = array(
					'rid' => $item_id
				);

				if (isset($item_data['direktorat']) && $item_data['direktorat'] != "" && $item_data['direktorat'] != 0) {
					$rujukan_data['tgl_rujuk1'] = date("Y-m-d H:i:s");
				}

				if (isset($item_data['direktorat2']) && $item_data['direktorat2'] != "" && $item_data['direktorat2'] != 0) {
					$rujukan_data['tgl_rujuk2'] = date("Y-m-d H:i:s");
				}
				if (isset($item_data['direktorat3']) && $item_data['direktorat3'] != "" && $item_data['direktorat3'] != 0) {
					$rujukan_data['tgl_rujuk3'] = date("Y-m-d H:i:s");
				}
				if (isset($item_data['direktorat4']) && $item_data['direktorat4'] != "" && $item_data['direktorat4'] != 0) {
					$rujukan_data['tgl_rujuk4'] = date("Y-m-d H:i:s");
				}
				if (isset($item_data['direktorat5']) && $item_data['direktorat5'] != "" && $item_data['direktorat5'] != 0) {
					$rujukan_data['tgl_rujuk5'] = date("Y-m-d H:i:s");
				}


				$this->Ticket->save_rujukan($rujukan_data, $item_id);
			} else {
				//remove from rujukan from layanan
			}

			// handle current notifs, is exist any
			$this->handleNotifikasi($item_info);

			echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
		} else // failure
		{
			$message = 'Data gagal disimpan';

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
		}
	}

	private function handleNotifikasi($item_info)
	{
		$list_directorate = [];

		$dir1 =  $this->input->post('dir1');
		$dir2 =  $this->input->post('dir2');
		$dir3 =  $this->input->post('dir3');
		$dir4 =  $this->input->post('dir4');
		$dir5 =  $this->input->post('dir5');

		if (!empty($dir1) && $dir1 != $item_info->direktorat) {
			array_push($list_directorate, $dir1);
		}

		if (!empty($dir2) && $dir2 != $item_info->direktorat2) {
			array_push($list_directorate, $dir2);
		}
		if (!empty($dir3) && $dir3 != $item_info->direktorat3) {
			array_push($list_directorate, $dir3);
		}
		if (!empty($dir4) && $dir4 != $item_info->direktorat4) {
			array_push($list_directorate, $dir4);
		}
		if (!empty($dir5) && $dir5 != $item_info->direktorat5) {
			array_push($list_directorate, $dir5);
		}

		if (count($list_directorate) == 0) {
			return;
		}

		// extract list of user eligible to be sent by notif
		$user_need_notified = $this->User->get_users_in_dir($list_directorate);

		// echo json_encode($user_need_notified->result());

		$timestamp = date('Y-m-d H:i:s');
		// save notif to eligible user
		foreach ($user_need_notified->result() as $user) {
			$data = array(
				'trackid' => $item_info->trackid,
				'isu_topik' => $item_info->isu_topik
			);
			$email_body = $this->load->view('mail/ticket_assigned_to_you', $data, TRUE);
			$data = array(
				"title" => 'Rujukan',
				"message" => $email_body,
				"ticket_id" => $item_info->trackid,
				"user_id" => $user->id,
				"created_date" => date('Y-m-d H:i:s')
			);
			$this->Notification->save($data);

			if (strlen($user->no_hp) >= 10) {
				//insert sms
				$konten = '[SIMPELLPK]Yth. Bpk/Ibu ' . $user->name . ', Terdapat rujukan untuk Anda dengan ID ' . $item_info->trackid;
				$sms_data = array(
					'no_tujuan' => $user->no_hp,
					'konten' => $konten,
					'created_date' => $timestamp,
					'ticket_id' => $item_info->trackid,
					'is_sent' => 0
				);
				$this->Draft->insert_sms($sms_data);
			}
		}
	}


	public function save_($item_id = -1)
	{


		$item_info = $this->Ticket->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}



		$old_date = explode('-', $item_info->tglpengaduan);
		$today = explode('/', date('d/m/Y'));

		$locked = lockedForEdit($today, $old_date);

		if ($locked) {
			$message = 'Tanggal pengaduan di bulan lalu dapat diubah sebelum tanggal 10 setiap bulannya';

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
			exit;
		}

		$iden_nama = $this->input->post('iden_nama');
		$iden_jk =  $this->input->post('iden_jk');
		$iden_instansi =  $this->input->post('iden_instansi');
		$iden_jenis =  $this->input->post('iden_jenis');
		$iden_alamat =  $this->input->post('iden_alamat');
		$iden_email =  $this->input->post('iden_email');
		$iden_negara =  $this->input->post('iden_negara');
		$iden_provinsi =  $this->input->post('iden_provinsi');
		$iden_provinsi2 =  $this->input->post('iden_provinsi2');
		$iden_kota =  $this->input->post('iden_kota');
		$iden_kota2 =  $this->input->post('iden_kota2');

		if (strtolower($iden_negara) != 'indonesia') {
			$iden_provinsi = $iden_provinsi2;
			$iden_kota = $iden_kota2;
		}

		$iden_telp =  $this->input->post('iden_telp');
		$iden_fax =  $this->input->post('iden_fax');
		$iden_profesi =  $this->input->post('iden_profesi');
		$usia = $this->input->post('usia');
		$prod_nama =  $this->input->post('prod_nama');
		$prod_generik =  $this->input->post('prod_generik');
		$prod_pabrik =  $this->input->post('prod_pabrik');
		$prod_noreg =  $this->input->post('prod_noreg');
		$prod_nobatch =  $this->input->post('prod_nobatch');
		$prod_alamat =  $this->input->post('prod_alamat');
		$prod_kota =  $this->input->post('prod_kota');
		$prod_negara =  $this->input->post('prod_negara');
		$prod_provinsi =  $this->input->post('prod_provinsi');
		$prod_provinsi2 =  $this->input->post('prod_provinsi2');

		if (strtolower($prod_negara) != 'indonesia') {
			$prod_provinsi = $prod_provinsi2;
		}

		$prod_kadaluarsa =  $this->input->post('prod_kadaluarsa');
		$prod_diperoleh =  $this->input->post('prod_diperoleh');
		$prod_diperoleh_tgl =  $this->input->post('prod_diperoleh_tgl');
		$prod_digunakan_tgl =  $this->input->post('prod_digunakan_tgl');
		$isu_topik =  $this->input->post('isu_topik');
		$prod_masalah =  $this->input->post('prod_masalah');
		$info =  $this->input->post('info');
		$penerima =  $this->input->post('penerima');
		$kategori =  $this->input->post('kategori');
		$submited_via =  $this->input->post('submited_via');
		$jenis =  $this->input->post('jenis');
		$shift =  $this->input->post('shift');

		$klasifikasi =  $this->input->post('klasifikasi');
		$subklasifikasi = $this->input->post('subklasifikasi');

		//$klasifikasi_id =  $this->input->post('klasifikasi_id');
		//$subklasifikasi_id = $this->input->post('subklasifikasi_id');

		/*$kla_info = $this->Klasifikasi->get_info($klasifikasi_id);
		$klasifikasi = $kla_info->nama;
		
		$subkla_info = $this->Subklasifikasi->get_info($subklasifikasi_id);
		$subklasifikasi = $subkla_info->subklasifikasi;*/

		$sla = $this->input->post('sla');

		$is_rujuk =  $this->input->post('is_rujuk');
		$dir1 =  $this->input->post('dir1');
		$dir2 =  $this->input->post('dir2');
		$dir3 =  $this->input->post('dir3');
		$dir4 =  $this->input->post('dir4');
		$dir5 =  $this->input->post('dir5');

		if (empty($dir1)) $dir1 = 0;
		if (empty($dir2)) $dir2 = 0;
		if (empty($dir3)) $dir3 = 0;
		if (empty($dir4)) $dir4 = 0;
		if (empty($dir5)) $dir5 = 0;

		// 
		$sla1 =  $this->input->post('sla1');
		$sla2 =  $this->input->post('sla2');
		$sla3 =  $this->input->post('sla3');
		$sla4 =  $this->input->post('sla4');
		$sla5 =  $this->input->post('sla5');

		if ($is_rujuk == '0') {
			$dir1 = 0;
			$dir2 = 0;
			$dir3 = 0;
			$dir4 = 0;
			$dir5 = 0;

			$sla1 = 0;
			$sla2 = 0;
			$sla3 = 0;
			$sla4 = 0;
			$sla5 = 0;

			$sla = 1;
		}




		$jawaban =  $this->input->post('jawaban');
		$keterangan =  $this->input->post('keterangan');
		$petugas_entry =  $this->input->post('petugas_entry');
		$penjawab =  $this->input->post('penjawab');
		$answered_via =  $this->input->post('answered_via');



		$tipe_medsos =  $this->input->post('tipe_medsos');
		if (!empty($submited_via) && $submited_via != 'Medsos')
			$tipe_medsos = '';

		//ppid data
		$tgl_diterima = $this->input->post('tgl_diterima');
		if (!empty($tgl_diterima))
			$tgl_diterima = convert_date1($tgl_diterima);

		$diterima_via = $this->input->post('diterima_via');
		$no_ktp = $this->input->post('no_ktp');
		$rincian =  $this->input->post('rincian');
		$tujuan =  $this->input->post('tujuan');
		$cara_memperoleh_info = $this->input->post('cara_memperoleh_info');

		if (!empty($cara_memperoleh_info)) {
			$cara_memperoleh_info = implode(',', $cara_memperoleh_info);
		}

		$cara_mendapat_salinan = $this->input->post('cara_mendapat_salinan');

		if (!empty($cara_mendapat_salinan)) {
			$cara_mendapat_salinan = implode(',', $cara_mendapat_salinan);
		}

		$tgl_pemberitahuan_tertulis = $this->input->post('tgl_pemberitahuan_tertulis');
		if (!empty($tgl_pemberitahuan_tertulis))
			$tgl_pemberitahuan_tertulis = convert_date1($tgl_pemberitahuan_tertulis);

		$penguasaan_kami = $this->input->post('penguasaan_kami');
		$penguasaan_kami_teks = $this->input->post('penguasaan_kami_teks');
		$penguasaan_badan_lain = $this->input->post('penguasaan_badan_lain');
		$nama_badan_lain = $this->input->post('nama_badan_lain');

		$bentuk_fisik = $this->input->post('bentuk_fisik');

		if (!empty($bentuk_fisik)) {
			$bentuk_fisik = implode(',', $bentuk_fisik);
		}

		$penyalinan = $this->input->post('penyalinan');
		$biaya_penyalinan_lbr = !empty($this->input->post('biaya_penyalinan_lbr')) ? $this->input->post('biaya_penyalinan_lbr') : 0;
		$biaya_penyalinan = !empty($this->input->post('biaya_penyalinan')) ? $this->input->post('biaya_penyalinan') : 0;

		$pengiriman = $this->input->post('pengiriman');
		$biaya_pengiriman = !empty($this->input->post('biaya_pengiriman')) ? $this->input->post('biaya_pengiriman') : 0;
		$lain_lain = $this->input->post('lain_lain');
		$biaya_lain = !empty($this->input->post('biaya_lain')) ? $this->input->post('biaya_lain') : 0;

		$biaya_total = ($biaya_penyalinan * $biaya_penyalinan_lbr) + $biaya_pengiriman + $biaya_lain;

		$nama_pejabat_ppid = $this->input->post('nama_pejabat_ppid');

		$waktu_penyediaan = $this->input->post('waktu_penyediaan');
		$waktu_penyediaan2 = $this->input->post('waktu_penyediaan2');
		$penghitaman = $this->input->post('penghitaman');
		$info_blm_dikuasai = $this->input->post('info_blm_dikuasai');
		$info_blm_didoc = $this->input->post('info_blm_didoc');
		$pengecualian_pasal17 = $this->input->post('pengecualian_pasal17');
		$pengecualian_pasal_lain = $this->input->post('pengecualian_pasal_lain');
		$pasal17_huruf = $this->input->post('pasal17_huruf');
		$pasal_lain_uu = $this->input->post('pasal_lain_uu');
		$konsekuensi = $this->input->post('konsekuensi');

		$tt_tgl = $this->input->post('tt_tgl');
		if (!empty($tt_tgl))
			$tt_tgl = convert_date1($tt_tgl);
		$tt_nomor = $this->input->post('tt_nomor');
		$tt_lampiran = $this->input->post('tt_lampiran');
		$tt_perihal = $this->input->post('tt_perihal');
		$tt_isi = $this->input->post('tt_isi');
		$tt_pejabat = $this->input->post('tt_pejabat');

		$keputusan = $this->input->post('keputusan');

		//$no_reg_keberatan = $this->input->post('no_reg_keberatan');
		$kuasa_nama = $this->input->post('kuasa_nama');
		$kuasa_alamat = $this->input->post('kuasa_alamat');
		$kuasa_telp = $this->input->post('kuasa_telp');
		$kuasa_email = $this->input->post('kuasa_email');
		$alasan_keberatan = $this->input->post('alasan_keberatan');

		if (!empty($alasan_keberatan)) {
			$alasan_keberatan = implode(',', $alasan_keberatan);
		}


		$kasus_posisi = $this->input->post('kasus_posisi');
		$tgl_tanggapan = $this->input->post('tgl_tanggapan');
		if (!empty($tgl_tanggapan))
			$tgl_tanggapan = convert_date1($tgl_tanggapan);

		$nama_petugas = $this->input->post('nama_petugas');
		$keberatan_tgl = $this->input->post('keberatan_tgl');
		if (!empty($keberatan_tgl))
			$keberatan_tgl = convert_date1($keberatan_tgl);

		$keberatan_no = $this->input->post('keberatan_no');
		$keberatan_lampiran = $this->input->post('keberatan_lampiran');
		$keberatan_perihal = $this->input->post('keberatan_perihal');
		$keberatan_isi_surat = $this->input->post('keberatan_isi_surat');
		$keberatan_nama_pejabat = $this->input->post('keberatan_nama_pejabat');



		//get sla
		/*if($jenis == 'PPID')
		{
			//$sla = 14;
			if($info == 'I')
				$sla = 17;
			if(!empty($kuasa_nama))
				$sla = 30;
		}
		else
		{
			//$sla = $this->Sla->get_sla($kategori, $info, $klasifikasi_id, $subklasifikasi_id);
			$sla = 7;
		}*/

		/*$sla = 5; //default
		if($item_info->category == '1') //Layanan
		{
			if($item_info->is_rujuk == '1')
			{
				//$sla = max($rujukan_info->sla1, $rujukan_info->sla2, $rujukan_info->sla3, $rujukan_info->sla4, $rujukan_info->sla5);
			}
			else
			{
				$sla = 5;
			}
		}
		elseif($item_info->category == '2') //PPID
		{
			$sla = 17;
		}
		elseif($item_info->category == '3') //Keberatan
		{
			$sla = 30;
		}
		elseif($item_info->category == '4') //Sengketa
		{
			$sla = 30;
		}*/

		$ppid_data = array(
			'tgl_diterima' => $tgl_diterima,
			'diterima_via' => $diterima_via,
			'no_ktp' => $no_ktp,
			'rincian' => $rincian,
			'tujuan' => $tujuan,
			'cara_memperoleh_info' => $cara_memperoleh_info,
			'cara_mendapat_salinan' => $cara_mendapat_salinan,

			'tgl_pemberitahuan_tertulis' => $tgl_pemberitahuan_tertulis,
			'penguasaan_kami' => $penguasaan_kami,
			'penguasaan_kami_teks' => $penguasaan_kami_teks,
			'penguasaan_badan_lain' => $penguasaan_badan_lain,
			'nama_badan_lain' => $nama_badan_lain,
			'bentuk_fisik' => $bentuk_fisik,
			'penyalinan' => $penyalinan,
			'biaya_penyalinan' => $biaya_penyalinan,
			'biaya_penyalinan_lbr' => $biaya_penyalinan_lbr,
			'pengiriman' => $pengiriman,
			'biaya_pengiriman' => $biaya_pengiriman,
			'lain_lain' => $lain_lain,
			'biaya_lain' => $biaya_lain,
			'biaya_total' => $biaya_total,
			'waktu_penyediaan' => $waktu_penyediaan,
			'waktu_penyediaan2' => $waktu_penyediaan2,
			'penghitaman' => $penghitaman,
			'info_blm_dikuasai' => $info_blm_dikuasai,
			'info_blm_didoc' => $info_blm_didoc,
			'pengecualian_pasal17' => $pengecualian_pasal17,
			'pengecualian_pasal_lain' => $pengecualian_pasal_lain,
			'pasal17_huruf' => $pasal17_huruf,
			'pasal_lain_uu' => $pasal_lain_uu,
			'konsekuensi' => $konsekuensi,
			'tt_tgl' => $tt_tgl,
			'tt_nomor' => $tt_nomor,
			'tt_lampiran' => $tt_lampiran,
			'tt_perihal' => $tt_perihal,
			'tt_isi' => $tt_isi,
			'tt_pejabat' => $tt_pejabat,
			'keputusan' => $keputusan,
			'nama_pejabat_ppid' => $nama_pejabat_ppid,
			//'no_reg_keberatan' => $no_reg_keberatan,
			'kuasa_nama' => $kuasa_nama,
			'kuasa_alamat' => $kuasa_alamat,
			'kuasa_telp' => $kuasa_telp,
			'kuasa_email' => $kuasa_email,
			'alasan_keberatan' => $alasan_keberatan,
			'kasus_posisi' => $kasus_posisi,
			'tgl_tanggapan' => $tgl_tanggapan,
			'nama_petugas' => $nama_petugas,
			'keberatan_tgl' => $keberatan_tgl,
			'keberatan_no' => $keberatan_no,
			'keberatan_lampiran' => $keberatan_lampiran,
			'keberatan_perihal' => $keberatan_perihal,
			'keberatan_isi_surat' => $keberatan_isi_surat,
			'keberatan_nama_pejabat' => $keberatan_nama_pejabat
		);



		//Save item data
		$item_data = array(
			'iden_nama' => $iden_nama,
			'iden_jk' => $iden_jk,
			'iden_instansi' => $iden_instansi,
			'iden_jenis' => $iden_jenis,
			'iden_alamat' => $iden_alamat,
			'iden_email' => $iden_email,
			'iden_negara' => $iden_negara,
			'iden_provinsi' => $iden_provinsi,
			//'iden_provinsi2' =>                
			'iden_kota' => $iden_kota,
			'iden_telp' => $iden_telp,
			'iden_fax' => $iden_fax,
			'iden_profesi' => $iden_profesi,
			'usia' => $usia,
			'prod_nama' => $prod_nama,
			'prod_generik' => $prod_generik,
			'prod_pabrik' => $prod_pabrik,
			'prod_noreg' => $prod_noreg,
			'prod_nobatch' => $prod_nobatch,
			'prod_alamat' => $prod_alamat,
			'prod_kota' => $prod_kota,
			'prod_provinsi' => $prod_provinsi,
			'prod_negara' => $prod_negara,
			'prod_kadaluarsa' => $prod_kadaluarsa,
			'prod_diperoleh' => $prod_diperoleh,
			'prod_diperoleh_tgl' => $prod_diperoleh_tgl,
			'prod_digunakan_tgl' => $prod_digunakan_tgl,
			'isu_topik' => $isu_topik,
			'prod_masalah' => $prod_masalah,
			'info' => $info,
			'penerima' => $penerima,
			'kategori' => $kategori,
			'submited_via' => $submited_via,
			'jenis' => $jenis,
			'shift' => $shift,
			'klasifikasi' => $klasifikasi,
			'subklasifikasi' => $subklasifikasi,
			//'klasifikasi_id' => $klasifikasi_id,
			//'subklasifikasi_id' => $subklasifikasi_id,
			'sla' => $sla,
			'is_rujuk' => $is_rujuk,

			'direktorat' => $dir1,
			'direktorat2' => $dir2,
			'direktorat3' => $dir3,
			'direktorat4' => $dir4,
			'direktorat5' => $dir5,

			'd1_prioritas' => $sla1,
			'd2_prioritas' => $sla2,
			'd3_prioritas' => $sla3,
			'd4_prioritas' => $sla4,
			'd5_prioritas' => $sla5,

			'jawaban' =>  $jawaban,
			'keterangan' =>  $keterangan,
			'petugas_entry' =>  $petugas_entry,
			'penjawab' =>  $penjawab,
			'answered_via' =>  $answered_via,
			'tipe_medsos' => $tipe_medsos

		);

		$item_info = $this->Ticket->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}

		$item_data['history'] = $item_info->history;
		$item_data['history'] .= '<li class="smaller">Pada ' . date('Y-m-d H:i:s') . ' layanan diubah oleh ' . $this->session->name . '</li>';

		$oldarrayticket = (array) $this->Ticket->get_info_ticket($item_id);
		$oldarrayppid = (array) $this->Ticket->get_info_ppid($item_id);

		if ($this->Ticket->save($item_data, $item_id)) {
			/*$success = TRUE;
			
			if($item_id == -1)
			{
				$message = $this->xss_clean($this->lang->line('items_successful_adding') . ' ' . $item_id);

				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
			}
			else
			{*/
			$message = 'Data berhasil disimpan';
			$this->Ticket->save_ppid($ppid_data, $item_id);

			logChangesTicket($oldarrayticket, $oldarrayppid, $item_id);

			if ($is_rujuk == '1') {
				$rujukan_data = array(
					'rid' => $item_id
				);
				$this->Ticket->save_rujukan($rujukan_data, $item_id);

				/*
					//rujukan 1
					if($dir1 != $item_info->direktorat)
						$this->Ticket->update_rujukan($new_data, $dir1, $sla1, 1);
					else
					{
						if($sla1 != $item_info->d1_prioritas)
						{
							$this->Ticket->update_rujukan_sla($item_id, $dir1, 1, $sla1);
						}
					}
					
					//rujukan 2
					if($dir2 != $item_info->direktorat2)
						$this->Ticket->update_rujukan($new_data, $dir2, $sla2, 2);
					else
					{
						if($sla2 != $item_info->d2_prioritas)
							$this->Ticket->update_rujukan_sla($item_id, $dir2, 2, $sla2);
					}
					
					//rujukan 3
					if($dir3 != $item_info->direktorat3)
						$this->Ticket->update_rujukan($new_data, $dir3, $sla3, 3);
					else
					{
						if($sla3 != $item_info->d3_prioritas)
							$this->Ticket->update_rujukan_sla($item_id, $dir3, 3, $sla3);
					}
					
					//rujukan 4
					if($dir4 != $item_info->direktorat4)
						$this->Ticket->update_rujukan($new_data, $dir4, $sla4, 4);
					else
					{
						if($sla4 != $item_info->d4_prioritas)
							$this->Ticket->update_rujukan_sla($item_id, $dir4, 4, $sla4);
					}
					
					//rujukan 5
					if($dir5 != $item_info->direktorat5)
						$this->Ticket->update_rujukan($new_data, $dir5, $sla5, 5);
					else
					{
						if($sla5 != $item_info->d5_prioritas)
							$this->Ticket->update_rujukan_sla($item_id, $dir5, 5, $sla5);
					}
					*/
			} else {
				//remove from rujukan from layanan
				/*if($item_info->direktorat) $this->Ticket->delete_rujukan($item_id, $item_info->direktorat, 1);
					if($item_info->direktorat2) $this->Ticket->delete_rujukan($item_id, $item_info->direktorat2, 2);
					if($item_info->direktorat3) $this->Ticket->delete_rujukan($item_id, $item_info->direktorat3, 3);
					if($item_info->direktorat4) $this->Ticket->delete_rujukan($item_id, $item_info->direktorat4, 4);
					if($item_info->direktorat5) $this->Ticket->delete_rujukan($item_id, $item_info->direktorat5, 5);*/
			}

			echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
			//}
		} else // failure
		{
			$message = 'Data gagal disimpan';

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
		}
	}

	public function delete_rujukan()
	{
		$item_id = $this->input->post('sid');
		$dir_id = $this->input->post('dir_id');

		if ($this->Ticket->remove_rujukan($item_id, $dir_id)) {
			echo json_encode(array('error' => 0, 'message' => 'Berhasil menghapus rujukan'));
		} else {
			echo json_encode(array('error' => 1, 'message' => 'Gagal menghapus rujukan'));
		}
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

	public function close()
	{
		$items_to_close = $this->input->post('ids');

		if ($this->Ticket->close_list($items_to_close)) {
			$message = $this->lang->line('tickets_successful_closed') . ' ' . count($items_to_close) . ' ' . $this->lang->line('tickets_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		} else {
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_cannot_be_closed')));
		}
	}

	public function verify()
	{
		$items_to_verify = $this->input->post('ids');

		if ($this->Ticket->verify_list($items_to_verify)) {
			$message = $this->lang->line('tickets_successful_verified') . ' ' . count($items_to_verify) . ' ' . $this->lang->line('tickets_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		} else {
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_cannot_be_verified')));
		}
	}

	public function get_kab($prov_id = 0)
	{

		$prov_id = $this->xss_clean($prov_id);
		$kabs = $this->Ticket->get_kab($prov_id);

		echo json_encode($kabs);
	}

	public function get_subklasifikasi($kla = 0)
	{

		$kla = $this->xss_clean($kla);
		$subklasifikasi = $this->Ticket->get_subklasifikasi($kla);

		echo json_encode($subklasifikasi);
	}

	public function get_subklasifikasi2($kla = '')
	{

		$kla = $this->xss_clean($kla);
		$subklasifikasi = $this->Ticket->get_subklasifikasi2($kla);

		echo json_encode($subklasifikasi);
	}

	public function get_unitkerjas($kota = '')
	{

		if ($kota == 'ALL') {
			echo json_encode(array());
			return;
		}

		$kota = $this->xss_clean($kota);
		$unitkerjas = $this->Ticket->get_unitkerjas($kota);

		echo json_encode($unitkerjas);
	}

	public function get_komoditi_sla()
	{

		$info = $this->input->get('info');
		$products = $this->Ticket->get_products_sla($info);

		$array_wewenang = array();
		$array_nonwewenang = array();

		foreach ($products->result() as $prod) {

			if ($prod->wewenang) {
				$array_wewenang[] = array(
					'id' => $prod->id,
					'desc' => $prod->desc
				);
			} else {
				$array_nonwewenang[] = array(
					'id' => $prod->id,
					'desc' => $prod->desc
				);
			}
		}

		$array = array(
			array(
				'label' => 'Wewenang',
				'komoditi' => $array_wewenang
			),
			array(
				'label' => 'Bukan Wewenang',
				'komoditi' => $array_nonwewenang
			),
		);

		echo json_encode($array);
	}

	public function get_subklasifikasi_sla()
	{
		$cat = $this->input->get('cat');
		$info = $this->input->get('info');
		$klasifikasi = $this->input->get('klasifikasi');

		//$kla = $this->xss_clean($kla);
		$subklasifikasi = $this->Ticket->get_subklasifikasi_sla($cat, $info, $klasifikasi);

		echo json_encode($subklasifikasi);
	}

	public function get_sla()
	{
		$cat = $this->input->get('cat');
		$info = $this->input->get('info');
		$klasifikasi = $this->input->get('klasifikasi');
		$subklasifikasi = $this->input->get('subklasifikasi');

		//$kla = $this->xss_clean($kla);
		$sla = $this->Ticket->get_sla($cat, $info, $klasifikasi, $subklasifikasi);

		echo json_encode($sla);
	}

	public function get_klasifikasi_sla()
	{
		$cat = $this->input->get('cat');
		$info = $this->input->get('info');

		//$cat = $this->xss_clean($cat);


		$klasifikasi = $this->Ticket->get_klasifikasi_sla($cat, $info);

		echo json_encode($klasifikasi);
	}

	public function export_excel()
	{
		$tgl1 = $this->input->get('tgl1');
		$tgl2 = $this->input->get('tgl2');
		$kota = $this->input->get('kota');
		$search = $this->input->get('search');


		if (!empty($tgl1) && !empty($tgl2) && !empty($kota)) {
			require_once APPPATH . 'third_party/PHPExcel.php';
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
			$sheet = $objPHPExcel->getActiveSheet();


			$start_index = 1;


			$filters = array(
				'tgl1' => $tgl1,
				'tgl2' => $tgl2,
				'kota' => $kota
			);
			$tickets = $this->Ticket->search($search, $filters);
			$start_index = 3;
			$i = $start_index;
			//header
			$sheet->setCellValue('A' . $i, 'NO.');
			$sheet->setCellValue('B' . $i, 'ID');
			$sheet->setCellValue('C' . $i, 'NAMA KONSUMEN');
			$i++;
			$no = 0;
			foreach ($tickets->result() as $row) {
				$no++;
				$sheet->setCellValue('A' . $i, $no);
				$sheet->setCellValue('B' . $i, $row->trackid);
				$sheet->setCellValue('C' . $i, $row->iden_nama);
				$i++;
			}

			//set auto width
			foreach (range('B', 'C') as $letter) {
				//$sheet->getColumnDimension($letter)->setAutoSize(true);
				$sheet->getColumnDimension($letter)->setWidth(10);
			}

			//set wrap
			foreach (array('B', 'C') as $letter) {
				$sheet->getStyle($letter . $start_index . ":" . $letter . $sheet->getHighestRow())->getAlignment()->setWrapText(true);
			}

			//set border
			$sheet->getStyle("A" . $start_index . ":" . $sheet->getHighestColumn() . $sheet->getHighestRow())->applyFromArray(
				array(
					'borders' => array(
						'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => '000000')
						)
					)
				)
			);

			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="export_' . $kota . '_' . $tgl1 . '_s.d_' . $tgl2 . '.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			unset($objPHPExcel);
		}
	}

	public function test_excel()
	{
		require_once APPPATH . 'third_party/PHPExcel.php';

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'Ini di A No 1 ya')
			->setCellValue('B2', 'kalau ini B 2')
			->setCellValue('C1', 'Ini di C1')
			->setCellValue('D2', 'Terakhir di D 2');
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="contoh01.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		unset($objPHPExcel);
	}

	public function do_upload()
	{
		$ticketid = $this->input->post('ticketid');

		if ($ticketid) {
			$this->load->library('upload', $this->config->item('upload_setting'));
			if ($this->upload->do_upload('file')) {
				$data = $this->upload->data();
				$att_data = array(
					'saved_name' => $data['file_name'],
					'real_name' => $data['orig_name'],
					'size' => $data['file_size'],
					//'file_type' => $data['file_type'],
					'ticket_id' => $ticketid,
					'mode' => $this->input->post('mode')
				);

				if ($this->Ticket->save_attachment($att_data)) {
					$id = $att_data['id'];
					echo json_encode(array('id' => $id, 'url' => base_url() . 'uploads/files/' . $att_data['saved_name'], 'error' => 0, 'message' => ''));
				}
			} else {
				echo json_encode(array('error' => 1, 'message' => $this->upload->display_errors()));
			}
		}
	}

	public function upload_lamp_pengaduan()
	{
		$ticketid = $this->input->post('ticketid');

		if ($ticketid) {
			$this->load->library('upload', $this->config->item('upload_setting'));

			$jumlah_berkas = count($_FILES['file']['name']);
			for ($i = 0; $i < $jumlah_berkas; $i++) {
				if (!empty($_FILES['file']['name'][$i])) {
					$_FILES['file_']['name'] = $_FILES['file']['name'][$i];
					$_FILES['file_']['type'] = $_FILES['file']['type'][$i];
					$_FILES['file_']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
					$_FILES['file_']['error'] = $_FILES['file']['error'][$i];
					$_FILES['file_']['size'] = $_FILES['file']['size'][$i];


					if ($this->upload->do_upload('file_')) {
						$data = $this->upload->data();
						$att_data = array(
							'saved_name' => $data['file_name'],
							'real_name' => $data['orig_name'],
							//'size' => $data['file_size'],
							'size' => $_FILES['file_']['size'],
							//'file_type' => $data['file_type'],
							'ticket_id' => $ticketid,
							'mode' => $this->input->post('mode')
						);

						if ($this->Ticket->save_attachment($att_data)) {
							$id = $att_data['id'];
							echo json_encode(array('id' => $id, 'url' => base_url() . 'uploads/files/' . $att_data['saved_name'], 'error' => 0, 'message' => ''));
						}
					} else {
						echo json_encode(array('error' => 1, 'message' => $this->upload->display_errors()));
					}
				}
			}
		}
	}

	public function upload_lamp_jawaban()
	{
		$ticketid = $this->input->post('ticketid');

		if ($ticketid) {
			$this->load->library('upload', $this->config->item('upload_setting'));

			$jumlah_berkas = count($_FILES['file2']['name']);
			for ($i = 0; $i < $jumlah_berkas; $i++) {
				if (!empty($_FILES['file2']['name'][$i])) {
					$_FILES['file2_']['name'] = $_FILES['file2']['name'][$i];
					$_FILES['file2_']['type'] = $_FILES['file2']['type'][$i];
					$_FILES['file2_']['tmp_name'] = $_FILES['file2']['tmp_name'][$i];
					$_FILES['file2_']['error'] = $_FILES['file2']['error'][$i];
					$_FILES['file2_']['size'] = $_FILES['file2']['size'][$i];


					if ($this->upload->do_upload('file2_')) {
						$data = $this->upload->data();
						$att_data = array(
							'saved_name' => $data['file_name'],
							'real_name' => $data['orig_name'],
							//'size' => $data['file_size'],
							'size' => $_FILES['file2_']['size'],
							//'file_type' => $data['file_type'],
							'ticket_id' => $ticketid,
							'mode' => $this->input->post('mode')
						);

						if ($this->Ticket->save_attachment($att_data)) {
							$id = $att_data['id'];
							echo json_encode(array('id' => $id, 'url' => base_url() . 'uploads/files/' . $att_data['saved_name'], 'error' => 0, 'message' => ''));
						}
					} else {
						echo json_encode(array('error' => 1, 'message' => $this->upload->display_errors()));
					}
				}
			}
		}
	}

	public function send_reply_only()
	{
		$ticketid = $this->input->post('ticketid');
		$id = $this->input->post('id');
		$message = $this->input->post('message');
		if (!empty($ticketid) && !empty($id)) {
			$timestamp = date('Y-m-d H:i:s');
			$new_data = array(
				'replyto' => $id,
				'name' => $this->session->name,
				'dt' => $timestamp,
				'staffid' => $this->session->id,
				'message' => $message
			);
			if ($this->Ticket->save_reply($new_data)) {
				$this->Ticket->save_last_replier($id);
				//echo json_encode(array('id' => $ticketid, 'url' =>'', 'error'=>0,'message'=>''));

				//$item_info = $this->Ticket->get_info_by_trackid($ticketid);
				$item_info = $this->Ticket->get_info($id);
				foreach (get_object_vars($item_info) as $property => $value) {
					$item_info->$property = $this->xss_clean($value);
				}

				/*if($item_info->direktorat == $this->session->direktoratid)
				{
					$arr = array(
						'r1_status' => 1
					);
					$this->Ticket->save2($arr, $id);
				}
				if($item_info->direktorat2 == $this->session->direktoratid)
				{
					$arr = array(
						'r2_status' => 1
					);
					$this->Ticket->save2($arr, $id);
				}
				if($item_info->direktorat3 == $this->session->direktoratid)
				{
					$arr = array(
						'r3_status' => 1
					);
					$this->Ticket->save2($arr, $id);
				}
				if($item_info->direktorat4 == $this->session->direktoratid)
				{
					$arr = array(
						'r4_status' => 1
					);
					$this->Ticket->save2($arr, $id);
				}
				if($item_info->direktorat5 == $this->session->direktoratid)
				{
					$arr = array(
						'r5_status' => 1
					);
					$this->Ticket->save2($arr, $id);
				}*/



				$dir_array = array(
					$item_info->direktorat,
					$item_info->direktorat2,
					$item_info->direktorat3,
					$item_info->direktorat4,
					$item_info->direktorat5
				);

				$owner_dir = $this->User->get_direktoratid($item_info->owner);
				if ($owner_dir > 0)
					array_push($dir_array, $owner_dir);

				$users_data = $this->User->get_users_in_dir($dir_array);
				foreach ($users_data->result() as $row) {
					if ($row->id == $this->session->id)
						continue;

					//insert notifications
					$notif_data = array(
						'ticket_id' => $ticketid,
						'title' => 'Balasan dari ' . $this->session->name,
						'message' => $message,
						'created_date' => $timestamp,
						'user_id' => $row->id
					);

					$this->Notification->save($notif_data);
				}

				if ($item_info->owner != $this->session->id) {

					$notif_data = array(
						'ticket_id' => $item_info->id,
						'user_id' => $item_info->owner,
						'is_read' => 0
					);
					$this->Notification->save_notif($notif_data);
				}
			}
		}
	}

	public function send_reply()
	{
		$ticketid = $this->input->post('ticketid');
		$id = $this->input->post('id');
		$message = $this->input->post('message');
		if (!empty($ticketid) && !empty($id)) {


			$this->load->library('upload', $this->config->item('upload_setting'));

			$files = $_FILES;
			$cpt = count($_FILES['file']['name']);
			$str_filenames = "";

			for ($i = 0; $i < $cpt; $i++) {
				$name = $files['file']['name'][$i];
				$_FILES['file']['name'] = $name;
				$_FILES['file']['type'] = $files['file']['type'][$i];
				$_FILES['file']['tmp_name'] = $files['file']['tmp_name'][$i];
				$_FILES['file']['error'] = $files['file']['error'][$i];
				$_FILES['file']['size'] = $files['file']['size'][$i];

				if ($this->upload->do_upload('file')) {
					$data = $this->upload->data();
					$att_data = array(
						'saved_name' => $data['file_name'],
						'real_name' => $data['orig_name'],
						//'size' => $data['file_size'],
						'size' => $_FILES['file']['size'],
						//'file_type' => $data['file_type'],
						'ticket_id' => $ticketid,
						'mode' => $this->input->post('mode')
					);

					if ($this->Ticket->save_attachment2($att_data)) {
						$att_id = $att_data['id'];
						$newline = $att_id . "#" . $_FILES['file']['name'] . ",";
						$str_filenames .= $newline;
						//echo json_encode(array('id' => $id, 'url' => base_url().'uploads/files/'.$att_data['saved_name'], 'error'=>0,'message'=>''));
					}
				}
				/*else
				{
					echo json_encode(array('error' => 1, 'message' => $this->upload->display_errors()));
				}*/
			}
			$timestamp = date('Y-m-d H:i:s');

			//insert reply
			$new_data = array(
				'replyto' => $id,
				'name' => $this->session->name,
				'dt' => $timestamp,
				'staffid' => $this->session->id,
				'attachments' => $str_filenames,
				'message' => $message
			);
			if ($this->Ticket->save_reply($new_data)) {
				$this->Ticket->save_last_replier($id);

				$item_info = $this->Ticket->get_info_by_trackid($ticketid);
				foreach (get_object_vars($item_info) as $property => $value) {
					$item_info->$property = $this->xss_clean($value);
				}

				$dir_array = array(
					$item_info->direktorat,
					$item_info->direktorat2,
					$item_info->direktorat3,
					$item_info->direktorat4,
					$item_info->direktorat5
				);

				$owner_dir = $this->User->get_direktoratid($item_info->owner);
				if ($owner_dir > 0)
					array_push($dir_array, $owner_dir);

				$users_data = $this->User->get_users_in_dir($dir_array);
				foreach ($users_data->result() as $row) {
					if ($row->id == $this->session->id)
						continue;

					//insert notifications
					$notif_data = array(
						'ticket_id' => $ticketid,
						'title' => 'Balasan dari ' . $this->session->name,
						'message' => $message,
						'created_date' => $timestamp,
						'user_id' => $row->id
					);

					$this->Notification->save($notif_data);
				}

				if ($item_info->owner != $this->session->id) {

					$notif_data = array(
						'ticket_id' => $item_info->id,
						'user_id' => $item_info->owner,
						'is_read' => 0
					);
					$this->Notification->save_notif($notif_data);
				}


				echo json_encode(array('id' => $ticketid, 'url' => '', 'error' => 0, 'message' => ''));
			}
		}
	}

	public function download_file($att_id, $att_name)
	{
		$this->load->helper('download');
		$saved_name = $this->Ticket->get_att_file($att_id, $att_name);
		//echo $saved_name;

		force_download(APPPATH . '../public/uploads/files/' . $saved_name, NULL);
	}

	public function get_attachments() //mode 0 = pertanyaan, 1 = jawaban, 2 = replies
	{
		$item_id = $this->input->get('ticketid');
		$mode = $this->input->get('mode');
		$files = $this->Ticket->get_attachments($item_id, $mode);
		//print_r($files->result());
		$files_array = array();

		foreach ($files->result() as $f) {
			$files_array[] = array(
				'att_id' => $f->att_id,
				'name' => $f->real_name,
				'size' => $f->size,
				'url' => site_url('downloads/download_attachment_ticket?att_id=' . $f->att_id . '&trackid=' . $f->ticket_id),
				'mode' => $f->mode
			);
		}

		echo json_encode($files_array);
	}


	public function delete_file()
	{
		$draftid = $this->input->post('ticketid');
		$mode = $this->input->post('mode');
		$att_id = $this->input->post('att_id');

		$att_id = (int)$att_id;

		$draft_info = $this->Ticket->get_attachment_info($att_id);
		foreach (get_object_vars($draft_info) as $property => $value) {
			$draft_info->$property = $this->xss_clean($value);
		}

		if (!empty($draft_info->saved_name)) {

			$config = $this->config->item('upload_setting');
			$path = $config['upload_path'] . $draft_info->saved_name;
			//$path = APPPATH.'../public/uploads/files/'.$draft_info->saved_name;
			unlink($path);

			if ($this->Ticket->delete_attachment($att_id)) {
				echo json_encode(array('error' => 0, 'message' => 'Berhasil menghapus file'));
			} else {
				echo json_encode(array('error' => 1, 'message' => 'Gagal menghapus file'));
			}
		}
	}

	public function print_pdf($item_id = -1)
	{
		$item_id = (int)$item_id;

		$item_info = $this->Ticket->get_info($item_id);

		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;

		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach (get_object_vars($ppid_info) as $property => $value) {
			$ppid_info->$property = $this->xss_clean($value);
		}
		$data['ppid_info'] = $ppid_info;

		$this->load->helper(array('dompdf', 'file'));

		$html = $this->load->view('tickets/print_pdf', $data, TRUE);
		$filename = rand(0, 1000);
		pdf_create($html, $filename);

		//$this->load->view('ppid/print_ppid_form1', $data);
	}

	public function suggest_issue_topic()
	{
		$suggestions = $this->xss_clean($this->Ticket->get_issue_topic_suggestions($this->input->get('term')));

		echo json_encode($suggestions);
	}

	public function suggest_unit_teknis()
	{
		//$suggestions = $this->xss_clean($this->Ticket->get_unit_teknis_suggestions($this->input->get('term')));
		$suggestions = $this->xss_clean($this->Ticket->get_unit_teknis_suggestions($this->input->get('term'), $this->input->get('item_id')));
		echo json_encode($suggestions);
	}
}
