<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");


class Drafts extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('drafts');
	}

	public function index()
	{
		redirect('drafts/list_all');
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
		foreach ($categories->result() as $cat) {
			$categories_array[$cat->id] = $cat->desc;
		}
		$data['categories'] = $categories_array;
	}

	public function list_all()
	{
		$data['title'] = 'Layanan';
		$data['table_headers'] = $this->xss_clean(get_drafts_manage_table_headers());

		$data['filters'] = array();
		$data['status_filter'] = '';

		//$this->setup_search($data);

		$this->load->view('drafts/manage', $data);
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


		//$tgl1 = $this->input->get('tgl1');
		//$tgl2 = $this->input->get('tgl2');

		//$filters = array();
		$filters = array(

			//'tgl1' => $tgl1,
			//'tgl2' => $tgl2
		);
		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);



		$items = $this->Draft->search($search, $filters, $limit, $offset, $sort, $order);


		$total_rows = $this->Draft->get_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach ($items->result() as $item) {
			$data_rows[] = $this->xss_clean(get_draft_data_row($item, ++$no));
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

	public function view($item_id = -1) {}

	public function copy_from($item_id = -1)
	{
		$data['page_title'] = 'Tambah Data';


		$item_info = $this->Ticket->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}

		//reset-start
		$item_info->tglpengaduan = date('d/m/Y');
		$item_info->tglpengaduan_fmt = date('d/m/Y');
		$item_info->waktu = date('H:i:s');
		$item_info->kota = $this->session->city;
		$item_info->is_rujuk = '0';
		$item_info->direktorat = 0;
		$item_info->direktorat2 = 0;
		$item_info->direktorat3 = 0;
		$item_info->direktorat4 = 0;
		$item_info->direktorat5 = 0;
		$item_info->id = -1;
		//reset-end

		$data['item_info'] = $item_info;

		$ppid_info = $this->Draft->get_ppid_info($item_id);
		foreach (get_object_vars($ppid_info) as $property => $value) {
			$ppid_info->$property = $this->xss_clean($value);
		}
		$data['ppid_info'] = $ppid_info;

		$data['answered_via'] = get_sarana();
		$data['submited_via'] = get_sarana();
		$data['countries'] = get_countries();
		$data['provinces'] = get_provinces();
		$data['provinces2'] = get_provinces();

		$data['profesi'] = get_profesi();
		$data['products'] = get_products();
		$data['sumberdata'] = array('' => '', 'SP4N' => 'SP4N', 'PPID' => 'PPID');
		$data['klasifikasi'] = get_klasifikasi();
		$data['dir_rujukan'] = get_direktorat_rujukan();
		$data['subklasifikasi'] = array('' => '');

		$data['kabs'] = get_cities($item_info->iden_provinsi);
		$data['sla'] = get_sla();

		$data['upload_url'] = site_url('drafts/do_upload');
		$data['back_url'] = site_url('drafts');
		$this->load->view('drafts/form', $data);
	}

	public function edit($item_id = -1)
	{

		$item_info = $this->Draft->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;

		$ppid_info = $this->Draft->get_ppid_info($item_id);
		foreach (get_object_vars($ppid_info) as $property => $value) {
			$ppid_info->$property = $this->xss_clean($value);
		}
		$data['ppid_info'] = $ppid_info;



		$data['page_title'] = 'Ubah Data (Draft)';
		$data['answered_via'] = get_sarana();
		$data['submited_via'] = get_sarana();
		$data['countries'] = get_countries();
		$data['provinces'] = get_provinces();
		$data['provinces2'] = get_provinces();

		$data['profesi'] = get_profesi();
		$data['products'] = get_products();

		$data['range_age'] = get_range_age();

		$data['sumberdata'] = array('' => '','SP4N' => 'SP4N','PPID'=>'PPID');
		//$data['klasifikasi'] = get_klasifikasi_sla($item_info->kategori, $item_info->info);
		$data['klasifikasi'] = get_klasifikasi();
		$data['subklasifikasi'] = get_subklasifikasi2($item_info->klasifikasi);
		$data['dir_rujukan'] = get_direktorat_rujukan();

		$data['kabs'] = get_cities($item_info->iden_provinsi);
		$data['sla'] = get_sla();
		//$data['sla'] = array();
		/*
		if(!empty($item_info->klasifikasi))
		{
			$array = array();
			$subkla = $this->Ticket->get_subklasifikasi_sla($item_info->kategori, $item_info->info, $item_info->klasifikasi_id);
			
			foreach($subkla as $k)
			{
				$array[$k->id] = $k->subklasifikasi;
			}
			$data['subklasifikasi'] = $array;
		}
		else
			$data['subklasifikasi'] = array(0 => '');*/



		$data['back_url'] = site_url('drafts');
		$data['upload_url'] = site_url('drafts/do_upload');

		$data['delete_file_url'] = site_url('drafts/delete_file');

		//$data['commodities_p'] = print_komoditi('P');
		//$data['commodities_i'] = print_komoditi('I');

		$data['upload_config'] = $this->config->item('upload_draft_setting');
		$data['subkla'] = get_subklasifikasi_json();

		$data['mode'] = 'EDIT';

		$formtype = $item_info->category;

		if ($formtype == '1') {
			$data['page_title'] = 'Ubah Data (Draft)';
			$this->load->view('drafts/form', $data);
		} elseif ($formtype == '2') {
			$data['page_title'] = 'Ubah Data Permintaan Informasi Publik (Draft)';
			$this->load->view('drafts/ppid/form', $data);
		} elseif ($formtype == '3') {
			$data['page_title'] = 'Ubah Data Pengajuan Keberatan (Draft)';
			$this->load->view('drafts/keberatan/form', $data);
		}
		/*elseif($formtype == '4')
		{
			$data['page_title'] = 'Ubah Data Sengketa (Draft)';
			$this->load->view('drafts/sengketa/form', $data);
		}*/ else
			$this->load->view('drafts/form', $data);
	}

	public function create_ppid($item_id = -1)
	{
		$data['page_title'] = 'Tambah Permintaan Informasi Publik';
		$data['mode'] = 'ADD';

		$item_info = $this->Draft->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}

		$item_info->tglpengaduan = date('d/m/Y');
		$item_info->tglpengaduan_fmt = date('d/m/Y');
		$item_info->waktu = date('H:i:s');
		$item_info->sla = 17;

		$data['item_info'] = $item_info;

		$ppid_info = $this->Draft->get_ppid_info($item_id);
		foreach (get_object_vars($ppid_info) as $property => $value) {
			$ppid_info->$property = $this->xss_clean($value);
		}
		$data['ppid_info'] = $ppid_info;

		//$data['rujukans'] = $this->Draft->get_rujukans($item_id);

		$data['answered_via'] = get_sarana();
		$data['submited_via'] = get_sarana();
		$data['countries'] = get_countries();
		$data['provinces'] = get_provinces();
		$data['provinces2'] = get_provinces();

		$data['profesi'] = get_profesi();
		$data['range_age'] = get_range_age();
		$data['products'] = get_products();
		//$data['products'] = get_products_sla('P');
		$data['sumberdata'] = array('' => '', 'SP4N' => 'SP4N', 'PPID' => 'PPID');

		$data['klasifikasi'] = get_klasifikasi();
		//$data['klasifikasi'] = array();


		//$data['dir_rujukan'] = get_direktorat_rujukan();
		if ($this->session->city != 'PUSAT') {
			$data['dir_rujukan'] = get_direktorat_rujukan_for_balai($this->session->city);
		} else {
			$data['dir_rujukan'] = get_direktorat_rujukan();
			$data['dir_rujukan2'] = get_direktorat_rujukan2();
		}

		$data['subklasifikasi'] = array('' => '');

		$data['kabs'] = get_cities($item_info->iden_provinsi);
		$data['sla'] = get_sla();

		$data['upload_url'] = site_url('drafts/do_upload');
		$data['back_url'] = site_url('drafts');


		$data['subkla'] = get_subklasifikasi_json();


		$data['upload_config'] = $this->config->item('upload_draft_setting');


		$this->load->view('drafts/ppid/form', $data);
	}

	public function create_keberatan($item_id = -1)
	{
		$data['page_title'] = 'Tambah Pengajuan Keberatan';
		$data['mode'] = 'ADD';

		$item_info = $this->Draft->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}

		$item_info->tglpengaduan = date('d/m/Y');
		$item_info->tglpengaduan_fmt = date('d/m/Y');
		$item_info->waktu = date('H:i:s');
		$item_info->sla = 30;

		$data['item_info'] = $item_info;

		$ppid_info = $this->Draft->get_ppid_info($item_id);
		foreach (get_object_vars($ppid_info) as $property => $value) {
			$ppid_info->$property = $this->xss_clean($value);
		}
		$data['ppid_info'] = $ppid_info;

		//$data['rujukans'] = $this->Draft->get_rujukans($item_id);

		$data['answered_via'] = get_sarana();
		$data['submited_via'] = get_sarana();
		$data['countries'] = get_countries();
		$data['provinces'] = get_provinces();
		$data['provinces2'] = get_provinces();

		$data['profesi'] = get_profesi();


		$data['products'] = get_products();
		//$data['products'] = get_products_sla('P');

		$data['range_age'] = get_range_age();
		$data['sumberdata'] = array('' => '','SP4N' => 'SP4N','PPID'=>'PPID');
		
		$data['klasifikasi'] = get_klasifikasi();
		//$data['klasifikasi'] = array();


		//$data['dir_rujukan'] = get_direktorat_rujukan();
		if ($this->session->city != 'PUSAT') {
			$data['dir_rujukan'] = get_direktorat_rujukan_for_balai($this->session->city);
		} else {
			$data['dir_rujukan'] = get_direktorat_rujukan();
			$data['dir_rujukan2'] = get_direktorat_rujukan2();
		}

		$data['subklasifikasi'] = array('' => '');

		$data['kabs'] = get_cities($item_info->iden_provinsi);
		$data['sla'] = get_sla();

		$data['upload_url'] = site_url('drafts/do_upload');
		$data['back_url'] = site_url('drafts');


		$data['subkla'] = get_subklasifikasi_json();


		$data['upload_config'] = $this->config->item('upload_draft_setting');


		$this->load->view('drafts/keberatan/form', $data);
	}

	public function create($item_id = -1)
	{

		$data['page_title'] = 'Tambah Layanan';
		$data['mode'] = 'ADD';

		$item_info = $this->Draft->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}

		$item_info->tglpengaduan = date('d/m/Y');
		$item_info->tglpengaduan_fmt = date('d/m/Y');
		$item_info->waktu = date('H:i:s');
		$item_info->sla = 1;

		$data['item_info'] = $item_info;

		$ppid_info = $this->Draft->get_ppid_info($item_id);
		foreach (get_object_vars($ppid_info) as $property => $value) {
			$ppid_info->$property = $this->xss_clean($value);
		}
		$data['ppid_info'] = $ppid_info;



		//$data['rujukans'] = $this->Draft->get_rujukans($item_id);

		$data['answered_via'] = get_sarana();
		$data['submited_via'] = get_sarana();
		$data['countries'] = get_countries();
		$data['provinces'] = get_provinces();
		$data['provinces2'] = get_provinces();

		$data['profesi'] = get_profesi();


		$data['products'] = get_products();
		//$data['products'] = get_products_sla('P');
		$data['range_age'] = get_range_age();
		
		
		$data['sumberdata'] = array('' => '','SP4N' => 'SP4N' /*,'PPID'=>'PPID'*/);
		$data['klasifikasi'] = get_klasifikasi();
		//$data['klasifikasi'] = array();


		//$data['dir_rujukan'] = get_direktorat_rujukan();
		if ($this->session->city != 'PUSAT') {
			$data['dir_rujukan'] = get_direktorat_rujukan_for_balai($this->session->city);
		} else {
			$data['dir_rujukan'] = get_direktorat_rujukan();
			$data['dir_rujukan2'] = get_direktorat_rujukan2();
		}

		$data['subklasifikasi'] = array('' => '');

		$data['kabs'] = get_cities($item_info->iden_provinsi);
		$data['sla'] = get_sla();

		$data['upload_url'] = site_url('drafts/do_upload');
		$data['back_url'] = site_url('drafts');


		$data['subkla'] = get_subklasifikasi_json();


		$data['upload_config'] = $this->config->item('upload_draft_setting');

		$data['sla1'] = 0;
		$data['sla2'] = 0;
		$data['sla3'] = 0;
		$data['sla4'] = 0;
		$data['sla5'] = 0;


		$this->load->view('drafts/form', $data);
	}

	public function uploadxls()
	{
		$data['mode'] = 'ADD';
		$data['page_title'] = 'Tambah Data via Upload';
		$this->load->view('drafts/upload_xls', $data);
	}

	function create_sengketa($item_id = -1)
	{
		$data['page_title'] = 'Tambah Data Sengketa';
		$data['mode'] = 'ADD';

		$item_info = $this->Draft->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;

		$ppid_info = $this->Draft->get_ppid_info($item_id);
		foreach (get_object_vars($ppid_info) as $property => $value) {
			$ppid_info->$property = $this->xss_clean($value);
		}
		$data['ppid_info'] = $ppid_info;

		$data['rujukans'] = $this->Draft->get_rujukans($item_id);

		$data['countries'] = get_countries();
		$data['provinces'] = get_provinces();
		$data['provinces2'] = get_provinces();
		$data['kabs'] = get_cities($item_info->iden_provinsi);

		$data['profesi'] = get_profesi();


		$data['back_url'] = site_url('drafts');

		$this->load->view('drafts/sengketa/form', $data);
	}

	public function save($item_id = -1)
	{



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
		$kategori_lainnya = $this->input->post('kategori_lainnya');
		$submited_via =  $this->input->post('submited_via');
		$jenis =  $this->input->post('jenis');
		$shift =  $this->input->post('shift');

		$klasifikasi =  $this->input->post('klasifikasi');
		$subklasifikasi = $this->input->post('subklasifikasi');
		/*
		$klasifikasi_id =  $this->input->post('klasifikasi_id');
		$subklasifikasi_id = $this->input->post('subklasifikasi_id');
		
		$kla_info = $this->Klasifikasi->get_info($klasifikasi_id);
		$klasifikasi = $kla_info->nama;
		
		$subkla_info = $this->Subklasifikasi->get_info($subklasifikasi_id);
		$subklasifikasi = $subkla_info->subklasifikasi;*/

		$sla =  $this->input->post('sla');

		$is_rujuk =  $this->input->post('is_rujuk');
		$dir1 =  $this->input->post('dir1');
		$dir2 =  $this->input->post('dir2');
		$dir3 =  $this->input->post('dir3');
		$dir4 =  $this->input->post('dir4');
		$dir5 =  $this->input->post('dir5');

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

			//jika tdk dirujuk SLA = 1 hk
			$sla = 1;
		}


		$jawaban =  $this->input->post('jawaban');
		$keterangan =  $this->input->post('keterangan');
		$petugas_entry =  $this->input->post('petugas_entry');
		$penjawab =  $this->input->post('penjawab');
		$answered_via =  $this->input->post('answered_via');

		//ppid data
		/*$tgl_diterima = $this->input->post('tgl_diterima');
		if(!empty($tgl_diterima))
			$tgl_diterima = convert_date1($tgl_diterima);
		
		$diterima_via = $this->input->post('diterima_via');
		$no_ktp = $this->input->post('no_ktp');
		$rincian =  $this->input->post('rincian');
		$tujuan =  $this->input->post('tujuan');
		$cara_memperoleh_info = $this->input->post('cara_memperoleh_info');
		
		if(!empty($cara_memperoleh_info))
		{
			$cara_memperoleh_info = implode(',', $cara_memperoleh_info);
		}
		
		$cara_mendapat_salinan = $this->input->post('cara_mendapat_salinan');
		
		if(!empty($cara_mendapat_salinan))
		{
			$cara_mendapat_salinan = implode(',', $cara_mendapat_salinan);
		}
		
		$tgl_pemberitahuan_tertulis = $this->input->post('tgl_pemberitahuan_tertulis');
		if(!empty($tgl_pemberitahuan_tertulis))
			$tgl_pemberitahuan_tertulis = convert_date1($tgl_pemberitahuan_tertulis);
		
		$penguasaan_kami = $this->input->post('penguasaan_kami');
		$penguasaan_kami_teks = $this->input->post('penguasaan_kami_teks');
		$penguasaan_badan_lain = $this->input->post('penguasaan_badan_lain');
		$nama_badan_lain = $this->input->post('nama_badan_lain');
		
		$bentuk_fisik = $this->input->post('bentuk_fisik');
		
		if(!empty($bentuk_fisik))
		{
			$bentuk_fisik = implode(',', $bentuk_fisik);
		}
		
		$penyalinan = $this->input->post('penyalinan');
		$biaya_penyalinan_lbr = !empty($this->input->post('biaya_penyalinan_lbr'))?$this->input->post('biaya_penyalinan_lbr'):0;
		$biaya_penyalinan = !empty($this->input->post('biaya_penyalinan'))?$this->input->post('biaya_penyalinan'):0;
		
		$pengiriman = $this->input->post('pengiriman');
		$biaya_pengiriman = !empty($this->input->post('biaya_pengiriman'))?$this->input->post('biaya_pengiriman'):0;
		$lain_lain = $this->input->post('lain_lain');
		$biaya_lain = !empty($this->input->post('biaya_lain'))?$this->input->post('biaya_lain'):0;
		
		$biaya_total = ($biaya_penyalinan * $biaya_penyalinan_lbr) + $biaya_pengiriman + $biaya_lain;
		
		$waktu_penyediaan = $this->input->post('waktu_penyediaan');
		$waktu_penyediaan2 = $this->input->post('waktu_penyediaan2');
		$penghitaman = $this->input->post('penghitaman');
		
		$nama_pejabat_ppid = $this->input->post('nama_pejabat_ppid');
		
		$info_blm_dikuasai = $this->input->post('info_blm_dikuasai');
		$info_blm_didoc = $this->input->post('info_blm_didoc');
		$pengecualian_pasal17 = $this->input->post('pengecualian_pasal17');
		$pengecualian_pasal_lain = $this->input->post('pengecualian_pasal_lain');
		$pasal17_huruf = $this->input->post('pasal17_huruf');
		$pasal_lain_uu = $this->input->post('pasal_lain_uu');
		$konsekuensi = $this->input->post('konsekuensi');
		
		$tt_tgl = $this->input->post('tt_tgl');
		if(!empty($tt_tgl))
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
				
		if(!empty($alasan_keberatan))
		{
			$alasan_keberatan = implode(',', $alasan_keberatan);
		}
		
		
		$kasus_posisi = $this->input->post('kasus_posisi');
		$tgl_tanggapan = $this->input->post('tgl_tanggapan');
		if(!empty($tgl_tanggapan))
			$tgl_tanggapan = convert_date1($tgl_tanggapan);
		
		$nama_petugas = $this->input->post('nama_petugas');
		$keberatan_tgl = $this->input->post('keberatan_tgl');
		if(!empty($keberatan_tgl))
			$keberatan_tgl = convert_date1($keberatan_tgl);
		
		$keberatan_no = $this->input->post('keberatan_no');
		$keberatan_lampiran = $this->input->post('keberatan_lampiran');
		$keberatan_perihal = $this->input->post('keberatan_perihal');
		$keberatan_isi_surat = $this->input->post('keberatan_isi_surat');
		$keberatan_nama_pejabat = $this->input->post('keberatan_nama_pejabat');
		
		$alasan_ditolak = $this->input->post('alasan_ditolak');*/

		//$formtype = $this->input->post('formtype'); // 1 = Layanan, 2 = PPID, 3 = Keberatan, 4 = Sengketa
		//if(empty($formtype))$formtype = 1;
		$formtype = 1;

		/*$ppid_data = array(
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
			'keberatan_nama_pejabat' => $keberatan_nama_pejabat,
			'alasan_ditolak' => $alasan_ditolak
			
			
		);*/




		$tipe_medsos =  $this->input->post('tipe_medsos');
		if (!empty($submited_via) && $submited_via != 'Medsos')
			$tipe_medsos = '';


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
			'prod_kadaluarsa' => convert_date1($prod_kadaluarsa),
			'prod_diperoleh' => $prod_diperoleh,
			'prod_diperoleh_tgl' => convert_date1($prod_diperoleh_tgl),
			'prod_digunakan_tgl' => convert_date1($prod_digunakan_tgl),
			'isu_topik' => $isu_topik,
			'prod_masalah' => $prod_masalah,
			'info' => $info,
			'penerima' => $penerima,
			'kategori' => $kategori,
			'kategori_lainnya' => $kategori_lainnya,
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
			//'ppid_rincian' => $ppid_rincian,
			//'ppid_tujuan' => $ppid_tujuan,
			'tipe_medsos' => $tipe_medsos,
			'category' => $formtype
		);

		$item_info = $this->Draft->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		//$data['item_info'] = $item_info;

		$item_data['id'] = $item_id;



		if ($item_id == -1) {
			//$item_data['trackid'] = $this->Draft->generate_ticketid($this->session->city, 'DRF', date('Y-m-d'));
			$item_data['trackid'] = '';
			$item_data['owner'] = $this->session->id;
			$item_data['kota'] = $this->session->city;
			$item_data['dt'] = date('Y-m-d H:i:s');
			$item_data['tglpengaduan'] = date('Y-m-d');
			$item_data['waktu'] = date('H:i:s');
			//$item_data['info'] = 'P';
			$item_data['owner_dir'] = $this->session->direktoratid;
		}


		//for balai
		if ($this->session->city != 'PUSAT') {
			$tglpengaduan = $this->input->post('tglpengaduan');
			if (!empty($tglpengaduan))
				$tglpengaduan = convert_date1($tglpengaduan);

			$waktu = $this->input->post('waktu');

			$item_data['tglpengaduan'] = $tglpengaduan;
			$item_data['waktu'] = $waktu;
			//$item_data['trackid'] = $this->Draft->generate_ticketid($this->session->city, 'DRF', $tglpengaduan);
			$item_data['trackid'] = '';
		}

		$send = $this->input->post('send');



		if ($this->Draft->save($item_data, $item_id)) {

			$message = 'Data berhasil disimpan';

			//$ppid_data['id'] = $item_data['id'];
			//if(!empty($jenis) && $jenis == 'PPID')
			if (1) {
				//if(empty($tgl_diterima))
				//	$ppid_data['tgl_diterima'] = date('Y-m-d');

				//$this->Draft->save_ppid($ppid_data, $item_data['id']);

			}

			echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_data['id']));
		} else // failure
		{
			$message = 'Data gagal disimpan';

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => $item_id));
		}
	}

	public function save_keberatan($item_id = -1)
	{



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
		/*
		$klasifikasi_id =  $this->input->post('klasifikasi_id');
		$subklasifikasi_id = $this->input->post('subklasifikasi_id');
		
		$kla_info = $this->Klasifikasi->get_info($klasifikasi_id);
		$klasifikasi = $kla_info->nama;
		
		$subkla_info = $this->Subklasifikasi->get_info($subklasifikasi_id);
		$subklasifikasi = $subkla_info->subklasifikasi;*/

		$sla =  $this->input->post('sla');

		$is_rujuk =  $this->input->post('is_rujuk');
		$dir1 =  $this->input->post('dir1');
		$dir2 =  $this->input->post('dir2');
		$dir3 =  $this->input->post('dir3');
		$dir4 =  $this->input->post('dir4');
		$dir5 =  $this->input->post('dir5');

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
		}


		$jawaban =  $this->input->post('jawaban');
		$keterangan =  $this->input->post('keterangan');
		$petugas_entry =  $this->input->post('petugas_entry');
		$penjawab =  $this->input->post('penjawab');
		$answered_via =  $this->input->post('answered_via');

		//ppid data
		/*$tgl_diterima = $this->input->post('tgl_diterima');
		if(!empty($tgl_diterima))
			$tgl_diterima = convert_date1($tgl_diterima);
		
		$diterima_via = $this->input->post('diterima_via');
		$no_ktp = $this->input->post('no_ktp');
		$rincian =  $this->input->post('rincian');
		$tujuan =  $this->input->post('tujuan');
		$cara_memperoleh_info = $this->input->post('cara_memperoleh_info');
		
		if(!empty($cara_memperoleh_info))
		{
			$cara_memperoleh_info = implode(',', $cara_memperoleh_info);
		}
		
		$cara_mendapat_salinan = $this->input->post('cara_mendapat_salinan');
		
		if(!empty($cara_mendapat_salinan))
		{
			$cara_mendapat_salinan = implode(',', $cara_mendapat_salinan);
		}
		
		$tgl_pemberitahuan_tertulis = $this->input->post('tgl_pemberitahuan_tertulis');
		if(!empty($tgl_pemberitahuan_tertulis))
			$tgl_pemberitahuan_tertulis = convert_date1($tgl_pemberitahuan_tertulis);
		
		$penguasaan_kami = $this->input->post('penguasaan_kami');
		$penguasaan_kami_teks = $this->input->post('penguasaan_kami_teks');
		$penguasaan_badan_lain = $this->input->post('penguasaan_badan_lain');
		$nama_badan_lain = $this->input->post('nama_badan_lain');
		
		$bentuk_fisik = $this->input->post('bentuk_fisik');
		
		if(!empty($bentuk_fisik))
		{
			$bentuk_fisik = implode(',', $bentuk_fisik);
		}
		
		$penyalinan = $this->input->post('penyalinan');
		$biaya_penyalinan_lbr = !empty($this->input->post('biaya_penyalinan_lbr'))?$this->input->post('biaya_penyalinan_lbr'):0;
		$biaya_penyalinan = !empty($this->input->post('biaya_penyalinan'))?$this->input->post('biaya_penyalinan'):0;
		
		$pengiriman = $this->input->post('pengiriman');
		$biaya_pengiriman = !empty($this->input->post('biaya_pengiriman'))?$this->input->post('biaya_pengiriman'):0;
		$lain_lain = $this->input->post('lain_lain');
		$biaya_lain = !empty($this->input->post('biaya_lain'))?$this->input->post('biaya_lain'):0;
		
		$biaya_total = ($biaya_penyalinan * $biaya_penyalinan_lbr) + $biaya_pengiriman + $biaya_lain;
		
		$waktu_penyediaan = $this->input->post('waktu_penyediaan');
		$waktu_penyediaan2 = $this->input->post('waktu_penyediaan2');
		$penghitaman = $this->input->post('penghitaman');
		
		$nama_pejabat_ppid = $this->input->post('nama_pejabat_ppid');
		
		$info_blm_dikuasai = $this->input->post('info_blm_dikuasai');
		$info_blm_didoc = $this->input->post('info_blm_didoc');
		$pengecualian_pasal17 = $this->input->post('pengecualian_pasal17');
		$pengecualian_pasal_lain = $this->input->post('pengecualian_pasal_lain');
		$pasal17_huruf = $this->input->post('pasal17_huruf');
		$pasal_lain_uu = $this->input->post('pasal_lain_uu');
		$konsekuensi = $this->input->post('konsekuensi');
		
		$tt_tgl = $this->input->post('tt_tgl');
		if(!empty($tt_tgl))
			$tt_tgl = convert_date1($tt_tgl);
		
		$tt_nomor = $this->input->post('tt_nomor');
		$tt_lampiran = $this->input->post('tt_lampiran');
		$tt_perihal = $this->input->post('tt_perihal');
		$tt_isi = $this->input->post('tt_isi');
		$tt_pejabat = $this->input->post('tt_pejabat');
		
		$keputusan = $this->input->post('keputusan');
		$alasan_ditolak = $this->input->post('alasan_ditolak');*/

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



		//$formtype = $this->input->post('formtype'); // 1 = Layanan, 2 = PPID, 3 = Keberatan, 4 = Sengketa
		//if(empty($formtype))$formtype = 1;
		$formtype = 3;

		$ppid_data = array(
			/*'tgl_diterima' => $tgl_diterima,
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
			'alasan_ditolak' => $alasan_ditolak,*/


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




		$tipe_medsos =  $this->input->post('tipe_medsos');
		if (!empty($submited_via) && $submited_via != 'Medsos')
			$tipe_medsos = '';


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
			'prod_kadaluarsa' => convert_date1($prod_kadaluarsa),
			'prod_diperoleh' => $prod_diperoleh,
			'prod_diperoleh_tgl' => convert_date1($prod_diperoleh_tgl),
			'prod_digunakan_tgl' => convert_date1($prod_digunakan_tgl),
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
			//'ppid_rincian' => $ppid_rincian,
			//'ppid_tujuan' => $ppid_tujuan,
			'tipe_medsos' => $tipe_medsos,
			'category' => $formtype
		);

		$item_info = $this->Draft->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		//$data['item_info'] = $item_info;

		$item_data['id'] = $item_id;



		if ($item_id == -1) {
			//$item_data['trackid'] = $this->Draft->generate_ticketid($this->session->city, 'DRF', date('Y-m-d'));
			$item_data['trackid'] = '';
			$item_data['owner'] = $this->session->id;
			$item_data['kota'] = $this->session->city;
			$item_data['dt'] = date('Y-m-d H:i:s');
			$item_data['tglpengaduan'] = date('Y-m-d');
			$item_data['waktu'] = date('H:i:s');
			//$item_data['info'] = 'P';
			$item_data['owner_dir'] = $this->session->direktoratid;
		}


		//for balai
		if ($this->session->city != 'PUSAT') {
			$tglpengaduan = $this->input->post('tglpengaduan');
			if (!empty($tglpengaduan))
				$tglpengaduan = convert_date1($tglpengaduan);

			$waktu = $this->input->post('waktu');

			$item_data['tglpengaduan'] = $tglpengaduan;
			$item_data['waktu'] = $waktu;
			//$item_data['trackid'] = $this->Draft->generate_ticketid($this->session->city, 'DRF', $tglpengaduan);
			$item_data['trackid'] = '';
		}

		$send = $this->input->post('send');



		if ($this->Draft->save($item_data, $item_id)) {

			$message = 'Data berhasil disimpan';

			$ppid_data['id'] = $item_data['id'];
			//if(!empty($jenis) && $jenis == 'PPID')
			if (1) {
				//if(empty($tgl_diterima))
				//	$ppid_data['tgl_diterima'] = date('Y-m-d');

				$this->Draft->save_ppid($ppid_data, $item_data['id']);
			}

			echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_data['id']));
		} else // failure
		{
			$message = 'Data gagal disimpan';

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => $item_id));
		}
	}

	public function save_ppid($item_id = -1)
	{
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
		/*
		$klasifikasi_id =  $this->input->post('klasifikasi_id');
		$subklasifikasi_id = $this->input->post('subklasifikasi_id');
		
		$kla_info = $this->Klasifikasi->get_info($klasifikasi_id);
		$klasifikasi = $kla_info->nama;
		
		$subkla_info = $this->Subklasifikasi->get_info($subklasifikasi_id);
		$subklasifikasi = $subkla_info->subklasifikasi;*/

		$sla =  $this->input->post('sla');

		$is_rujuk =  $this->input->post('is_rujuk');
		$dir1 =  $this->input->post('dir1');
		$dir2 =  $this->input->post('dir2');
		$dir3 =  $this->input->post('dir3');
		$dir4 =  $this->input->post('dir4');
		$dir5 =  $this->input->post('dir5');

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
		}


		$jawaban =  $this->input->post('jawaban');
		$keterangan =  $this->input->post('keterangan');
		$petugas_entry =  $this->input->post('petugas_entry');
		$penjawab =  $this->input->post('penjawab');
		$answered_via =  $this->input->post('answered_via');

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

		$waktu_penyediaan = $this->input->post('waktu_penyediaan');
		$waktu_penyediaan2 = $this->input->post('waktu_penyediaan2');
		$penghitaman = $this->input->post('penghitaman');

		$nama_pejabat_ppid = $this->input->post('nama_pejabat_ppid');

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
		$alasan_ditolak = $this->input->post('alasan_ditolak');
		$is_lengkap = $this->input->post('is_lengkap');

		/*
		//$no_reg_keberatan = $this->input->post('no_reg_keberatan');
		$kuasa_nama = $this->input->post('kuasa_nama');
		$kuasa_alamat = $this->input->post('kuasa_alamat');
		$kuasa_telp = $this->input->post('kuasa_telp');
		$kuasa_email = $this->input->post('kuasa_email');
		$alasan_keberatan = $this->input->post('alasan_keberatan');
				
		if(!empty($alasan_keberatan))
		{
			$alasan_keberatan = implode(',', $alasan_keberatan);
		}
		
		
		$kasus_posisi = $this->input->post('kasus_posisi');
		$tgl_tanggapan = $this->input->post('tgl_tanggapan');
		if(!empty($tgl_tanggapan))
			$tgl_tanggapan = convert_date1($tgl_tanggapan);
		
		$nama_petugas = $this->input->post('nama_petugas');
		$keberatan_tgl = $this->input->post('keberatan_tgl');
		if(!empty($keberatan_tgl))
			$keberatan_tgl = convert_date1($keberatan_tgl);
		
		$keberatan_no = $this->input->post('keberatan_no');
		$keberatan_lampiran = $this->input->post('keberatan_lampiran');
		$keberatan_perihal = $this->input->post('keberatan_perihal');
		$keberatan_isi_surat = $this->input->post('keberatan_isi_surat');
		$keberatan_nama_pejabat = $this->input->post('keberatan_nama_pejabat');
		*/


		//$formtype = $this->input->post('formtype'); // 1 = Layanan, 2 = PPID, 3 = Keberatan, 4 = Sengketa
		//if(empty($formtype))$formtype = 1;
		$formtype = 2;

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
			'alasan_ditolak' => $alasan_ditolak,
			'is_lengkap'	=> $is_lengkap

			/*//'no_reg_keberatan' => $no_reg_keberatan,
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
			'keberatan_nama_pejabat' => $keberatan_nama_pejabat,*/
		);

		$tipe_medsos =  $this->input->post('tipe_medsos');
		if (!empty($submited_via) && $submited_via != 'Medsos')
			$tipe_medsos = '';


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
			'prod_kadaluarsa' => convert_date1($prod_kadaluarsa),
			'prod_diperoleh' => $prod_diperoleh,
			'prod_diperoleh_tgl' => convert_date1($prod_diperoleh_tgl),
			'prod_digunakan_tgl' => convert_date1($prod_digunakan_tgl),
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
			//'ppid_rincian' => $ppid_rincian,
			//'ppid_tujuan' => $ppid_tujuan,
			'tipe_medsos' => $tipe_medsos,
			'category' => $formtype
		);

		$item_info = $this->Draft->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		//$data['item_info'] = $item_info;

		$item_data['id'] = $item_id;



		if ($item_id == -1) {
			//$item_data['trackid'] = $this->Draft->generate_ticketid($this->session->city, 'DRF', date('Y-m-d'));
			$item_data['trackid'] = '';
			$item_data['owner'] = $this->session->id;
			$item_data['kota'] = $this->session->city;
			$item_data['dt'] = date('Y-m-d H:i:s');
			$item_data['tglpengaduan'] = date('Y-m-d');
			$item_data['waktu'] = date('H:i:s');
			//$item_data['info'] = 'P';
			$item_data['owner_dir'] = $this->session->direktoratid;
		}


		//for balai
		if ($this->session->city != 'PUSAT') {
			$tglpengaduan = $this->input->post('tglpengaduan');
			if (!empty($tglpengaduan))
				$tglpengaduan = convert_date1($tglpengaduan);

			$waktu = $this->input->post('waktu');

			$item_data['tglpengaduan'] = $tglpengaduan;
			$item_data['waktu'] = $waktu;
			//$item_data['trackid'] = $this->Draft->generate_ticketid($this->session->city, 'DRF', $tglpengaduan);
			$item_data['trackid'] = '';
		}

		$send = $this->input->post('send');
		if ($this->Draft->save($item_data, $item_id)) {

			$message = 'Data berhasil disimpan';

			$ppid_data['id'] = $item_data['id'];
			//if(!empty($jenis) && $jenis == 'PPID')
			if (1) {
				//if(empty($tgl_diterima))
				//	$ppid_data['tgl_diterima'] = date('Y-m-d');

				$this->Draft->save_ppid($ppid_data, $item_data['id']);
			}

			echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_data['id']));
		} else // failure
		{
			$message = 'Data gagal disimpan';

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => $item_id));
		}
	}

	public function save_________($item_id = -1)
	{



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
		/*
		$klasifikasi_id =  $this->input->post('klasifikasi_id');
		$subklasifikasi_id = $this->input->post('subklasifikasi_id');
		
		$kla_info = $this->Klasifikasi->get_info($klasifikasi_id);
		$klasifikasi = $kla_info->nama;
		
		$subkla_info = $this->Subklasifikasi->get_info($subklasifikasi_id);
		$subklasifikasi = $subkla_info->subklasifikasi;*/

		$sla =  $this->input->post('sla');

		$is_rujuk =  $this->input->post('is_rujuk');
		$dir1 =  $this->input->post('dir1');
		$dir2 =  $this->input->post('dir2');
		$dir3 =  $this->input->post('dir3');
		$dir4 =  $this->input->post('dir4');
		$dir5 =  $this->input->post('dir5');

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
		}


		$jawaban =  $this->input->post('jawaban');
		$keterangan =  $this->input->post('keterangan');
		$petugas_entry =  $this->input->post('petugas_entry');
		$penjawab =  $this->input->post('penjawab');
		$answered_via =  $this->input->post('answered_via');

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

		$waktu_penyediaan = $this->input->post('waktu_penyediaan');
		$waktu_penyediaan2 = $this->input->post('waktu_penyediaan2');
		$penghitaman = $this->input->post('penghitaman');

		$nama_pejabat_ppid = $this->input->post('nama_pejabat_ppid');

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

		$alasan_ditolak = $this->input->post('alasan_ditolak');

		$formtype = $this->input->post('formtype'); // 1 = Layanan, 2 = PPID, 3 = Keberatan, 4 = Sengketa
		if (empty($formtype)) $formtype = 1;

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
			'keberatan_nama_pejabat' => $keberatan_nama_pejabat,
			'alasan_ditolak' => $alasan_ditolak


		);




		$tipe_medsos =  $this->input->post('tipe_medsos');
		if (!empty($submited_via) && $submited_via != 'Medsos')
			$tipe_medsos = '';


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
			'prod_kadaluarsa' => convert_date1($prod_kadaluarsa),
			'prod_diperoleh' => $prod_diperoleh,
			'prod_diperoleh_tgl' => convert_date1($prod_diperoleh_tgl),
			'prod_digunakan_tgl' => convert_date1($prod_digunakan_tgl),
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
			//'ppid_rincian' => $ppid_rincian,
			//'ppid_tujuan' => $ppid_tujuan,
			'tipe_medsos' => $tipe_medsos,
			'category' => $formtype
		);

		$item_info = $this->Draft->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		//$data['item_info'] = $item_info;

		$item_data['id'] = $item_id;



		if ($item_id == -1) {
			//$item_data['trackid'] = $this->Draft->generate_ticketid($this->session->city, 'DRF', date('Y-m-d'));
			$item_data['trackid'] = '';
			$item_data['owner'] = $this->session->id;
			$item_data['kota'] = $this->session->city;
			$item_data['dt'] = date('Y-m-d H:i:s');
			$item_data['tglpengaduan'] = date('Y-m-d');
			$item_data['waktu'] = date('H:i:s');
			//$item_data['info'] = 'P';
			$item_data['owner_dir'] = $this->session->direktoratid;
		}


		//for balai
		if ($this->session->city != 'PUSAT') {
			$tglpengaduan = $this->input->post('tglpengaduan');
			if (!empty($tglpengaduan))
				$tglpengaduan = convert_date1($tglpengaduan);

			$waktu = $this->input->post('waktu');

			$item_data['tglpengaduan'] = $tglpengaduan;
			$item_data['waktu'] = $waktu;
			//$item_data['trackid'] = $this->Draft->generate_ticketid($this->session->city, 'DRF', $tglpengaduan);
			$item_data['trackid'] = '';
		}

		$send = $this->input->post('send');



		if ($this->Draft->save($item_data, $item_id)) {

			$message = 'Data berhasil disimpan';

			$ppid_data['id'] = $item_data['id'];
			//if(!empty($jenis) && $jenis == 'PPID')
			if (1) {
				//if(empty($tgl_diterima))
				//	$ppid_data['tgl_diterima'] = date('Y-m-d');

				$this->Draft->save_ppid($ppid_data, $item_data['id']);
			}

			echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_data['id']));
		} else // failure
		{
			$message = 'Data gagal disimpan';

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => $item_id));
		}
	}

	public function save_sengketa($item_id = -1)
	{
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

		$sengketa_perihal =  $this->input->post('sengketa_perihal');
		$sengketa_alasan =  $this->input->post('sengketa_alasan');

		$ppid_data = array(
			'sengketa_perihal' => $sengketa_perihal,
			'sengketa_alasan' => $sengketa_alasan

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
			'usia' => $usia

		);

		$item_info = $this->Draft->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		//$data['item_info'] = $item_info;

		$item_data['id'] = $item_id;

		if ($item_id == -1) {
			//$item_data['trackid'] = $this->Draft->generate_ticketid($this->session->city, 'DRF', date('Y-m-d'));
			$item_data['trackid'] = '';
			$item_data['owner'] = $this->session->id;
			$item_data['kota'] = $this->session->city;
			$item_data['dt'] = date('Y-m-d H:i:s');
			$item_data['tglpengaduan'] = date('Y-m-d');
			$item_data['waktu'] = date('H:i:s');
			//$item_data['info'] = 'P';
			$item_data['owner_dir'] = $this->session->direktoratid;
		}




		$send = $this->input->post('send');



		if ($this->Draft->save($item_data, $item_id)) {

			$message = 'Data berhasil disimpan';

			$ppid_data['id'] = $item_data['id'];
			//if(!empty($jenis) && $jenis == 'PPID')
			if (1) {
				//if(empty($tgl_diterima))
				//	$ppid_data['tgl_diterima'] = date('Y-m-d');

				$this->Draft->save_ppid($ppid_data, $item_data['id']);
			}

			echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_data['id']));
		} else // failure
		{
			$message = 'Data gagal disimpan';

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => $item_id));
		}
	}

	public function confirm_send($item_id = -1)
	{
		$data['item_id'] = $item_id;
		$this->load->view('drafts/confirm_send', $data);
	}

	public function send_ticket($item_id = -1)
	{
		$item_info = $this->Draft->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;

		/*$rujukan_info = $this->Draft->get_rujukan_info($item_id);
		foreach(get_object_vars($rujukan_info) as $property => $value)
		{
			$rujukan_info->$property = $this->xss_clean($value);
		}
		$data['rujukan_info'] = $rujukan_info;*/

		$tglpengaduan = $item_info->tglpengaduan;

		$prefix = 'XXX';

		if ($item_info->kota == 'PUSAT')
			$prefix = 'PST';
		else if ($item_info->kota == 'UNIT TEKNIS')
			$prefix = $this->Balai->get_prefix_unit_teknis($item_info->owner_dir);
		else
			$prefix = $this->Balai->get_prefix($item_info->kota);

		$item_data['trackid'] = $this->Draft->generate_ticketid($item_info->kota, $prefix, $tglpengaduan);
		$item_data['history'] = '<li class="smaller">Pada ' . date('Y-m-d H:i:s') . ' layanan dibuat oleh ' . $this->session->name . '</li>';


		/*//get sla	
		if($item_info->jenis == 'PPID')
		{
			//$item_data['sla'] = 14;
			if($item_info->info == 'I')
				$item_data['sla'] = 17;
			elseif($item_info->info == 'P')
				$item_data['sla'] = 30;
		}
		else
		{
			//$item_data['sla'] = $this->Sla->get_sla($item_info->kategori, $item_info->info, $item_info->klasifikasi_id, $item_info->subklasifikasi_id);
			$item_data['sla'] = 7;
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
		}
		$item_data['sla'] = $sla;*/

		$item_data['direktorat'] = $item_info->direktorat;
		$item_data['direktorat2'] = $item_info->direktorat2;
		$item_data['direktorat3'] = $item_info->direktorat3;
		$item_data['direktorat4'] = $item_info->direktorat4;
		$item_data['direktorat5'] = $item_info->direktorat5;

		$item_data['d1_prioritas'] = $item_info->d1_prioritas;
		$item_data['d2_prioritas'] = $item_info->d2_prioritas;
		$item_data['d3_prioritas'] = $item_info->d3_prioritas;
		$item_data['d4_prioritas'] = $item_info->d4_prioritas;
		$item_data['d5_prioritas'] = $item_info->d5_prioritas;

		$item_data['tglpengaduan'] = $tglpengaduan;

		if ($this->Draft->save_to_ticket($item_data, $item_id)) {
			if ($item_info->is_rujuk == '1') {
				$data = array(
					'trackid' => $item_data['trackid'],
					'isu_topik' => $item_info->isu_topik
				);
				$email_body = $this->load->view('mail/ticket_assigned_to_you', $data, TRUE);
				$timestamp = date('Y-m-d H:i:s');

				$dir_array = array();
				array_push($dir_array, $item_info->direktorat);
				array_push($dir_array, $item_info->direktorat2);
				array_push($dir_array, $item_info->direktorat3);
				array_push($dir_array, $item_info->direktorat4);
				array_push($dir_array, $item_info->direktorat5);


				//print_r($dir_array);
				$users_data = $this->User->get_users_in_dir($dir_array);
				//print_r($users_data->result());
				//exit;

				foreach ($users_data->result() as $row) {
					//insert emails
					$email_data = array(
						'mail_title' => 'Rujukan ditujukan kepada Anda',
						'mail_to' => $row->email,
						'mail_body' => $email_body,
						'created_date' => $timestamp,
						'ticket_id' => $item_data['trackid'],
						'status' => 0
					);
					$this->Draft->insert_mail($email_data);

					//insert notifications
					$notif_data = array(
						'ticket_id' => $item_data['trackid'],
						'title' => 'Rujukan',
						'message' => $email_body,
						'created_date' => $timestamp,
						'user_id' => $row->id
					);

					$this->Notification->save($notif_data);

					//if(!empty($row->no_hp))
					if (strlen($row->no_hp) >= 10) {
						//insert sms
						$konten = '[SIMPELLPK]Yth. Bpk/Ibu ' . $row->name . ', Terdapat rujukan untuk Anda dengan ID ' . $item_data['trackid'];
						$sms_data = array(
							'no_tujuan' => $row->no_hp,
							'konten' => $konten,
							'created_date' => $timestamp,
							'ticket_id' => $item_data['trackid'],
							'is_sent' => 0
						);
						$this->Draft->insert_sms($sms_data);
					}
				}
			}


			$this->session->set_flashdata('flash', array('success' => TRUE, 'message' => '<i class="fa fa-check-circle" aria-hidden="true"></i> Data berhasil dikirim dengan ID layanan ' . $item_data['trackid']));
			echo json_encode(array('success' => TRUE, 'message' => 'Data berhasil dikirim', 'id' => $item_data['id']));
		} else {
			$this->session->set_flashdata('flash', array('success' => FALSE, 'message' => 'Data gagal dikirim', 'id' => $item_id));
			echo json_encode(array('success' => FALSE, 'message' => 'Data gagal dikirim', 'id' => $item_id));
		}



		//redirect('drafts/list_all');
	}

	public function delete()
	{
		$items_to_delete = $this->input->post('ids');

		if ($this->Draft->delete_list($items_to_delete)) {
			$message = $this->lang->line('items_successful_deleted') . ' ' . count($items_to_delete) . ' ' . $this->lang->line('items_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		} else {
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_cannot_be_deleted')));
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


	public function upload_lamp_pengaduan()
	{
		$draftid = $this->input->post('draftid');

		if ($draftid) {
			$this->load->library('upload', $this->config->item('upload_draft_setting'));

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
							'draft_id' => $draftid,
							'mode' => $this->input->post('mode')
						);

						if ($this->Draft->save_attachment($att_data)) {
							$id = $att_data['id'];
							echo json_encode(array('id' => $id, 'url' => base_url() . 'uploads/drafts/' . $att_data['saved_name'], 'error' => 0, 'message' => ''));
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
		$draftid = $this->input->post('draftid');

		if ($draftid) {
			$this->load->library('upload', $this->config->item('upload_draft_setting'));

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
							'draft_id' => $draftid,
							'mode' => $this->input->post('mode')
						);

						if ($this->Draft->save_attachment($att_data)) {
							$id = $att_data['id'];
							echo json_encode(array('id' => $id, 'url' => base_url() . 'uploads/drafts/' . $att_data['saved_name'], 'error' => 0, 'message' => ''));
						}
					} else {
						echo json_encode(array('error' => 1, 'message' => $this->upload->display_errors()));
					}
				}
			}
		}
	}

	public function get_attachments() //mode 0 = pertanyaan, 1 = jawaban, 2 = replies
	{
		$item_id = $this->input->get('draftid');
		$mode = $this->input->get('mode');
		$files = $this->Draft->get_attachments($item_id, $mode);
		//print_r($files->result());
		$files_array = array();

		foreach ($files->result() as $f) {
			$files_array[] = array(
				'att_id' => $f->att_id,
				'name' => $f->real_name,
				'size' => $f->size,
				//'url' => base_url().'uploads/drafts/'.$f->saved_name,
				'url' => site_url('downloads/download_attachment_draft?att_id=' . $f->att_id . '&trackid=' . $f->draft_id),
				'mode' => $f->mode
			);
		}

		echo json_encode($files_array);
	}

	public function delete_rujukan()
	{
		$item_id = $this->input->post('sid');
		$dir_id = $this->input->post('dir_id');

		if ($this->Draft->remove_rujukan($item_id, $dir_id)) {
			echo json_encode(array('error' => 0, 'message' => 'Berhasil menghapus rujukan'));
		} else {
			echo json_encode(array('error' => 1, 'message' => 'Gagal menghapus rujukan'));
		}
	}

	public function delete_file()
	{
		$draftid = $this->input->post('ticketid');
		$mode = $this->input->post('mode');
		$att_id = $this->input->post('att_id');

		$att_id = (int)$att_id;

		$draft_info = $this->Draft->get_attachment_info($att_id);
		foreach (get_object_vars($draft_info) as $property => $value) {
			$draft_info->$property = $this->xss_clean($value);
		}

		if (!empty($draft_info->saved_name)) {


			//$path = APPPATH.'../public/uploads/drafts/'.$draft_info->saved_name;
			$config = $this->config->item('upload_draft_setting');
			$path = $config['upload_path'] . $draft_info->saved_name;
			unlink($path);

			if ($this->Draft->delete_attachment($att_id)) {
				echo json_encode(array('error' => 0, 'message' => 'Berhasil menghapus file'));
			} else {
				echo json_encode(array('error' => 1, 'message' => 'Gagal menghapus file'));
			}
		}
	}

	public function suggest_unit_teknis()
	{
		//$suggestions = $this->xss_clean($this->Ticket->get_unit_teknis_suggestions($this->input->get('term')));
		$suggestions = $this->xss_clean($this->Draft->get_unit_teknis_suggestions($this->input->get('term'), $this->input->get('item_id')));
		echo json_encode($suggestions);
	}
}
