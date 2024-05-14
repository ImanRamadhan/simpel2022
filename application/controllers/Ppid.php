<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Ppid extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('ppid');

	}
	
	public function index()
	{
	}
	
	public function setup_search(&$data)
	{
		$cities = $this->City->get_cities();
		$cities_array = array(
			'ALL' => 'ALL', 
			'PUSAT' => 'PUSAT', 
			'CC' => 'CC', 
			'UNIT TEKNIS' => 'UNIT TEKNIS',
			'BALAI' => 'BALAI',
			'PUSAT_BALAI' => 'PUSAT + BALAI',
			'PUSAT_CC' => 'PUSAT + CC',
			'PUSAT_UNIT_TEKNIS' => 'PUSAT + UNIT_TEKNIS',
			'PUSAT_CC_BALAI' => 'PUSAT + CC + BALAI',
			'PUSAT_CC_UNIT_TEKNIS' => 'PUSAT + CC + UNIT TEKNIS',
			'PUSAT_CC_UNIT_TEKNIS_BALAI' => 'PUSAT + CC + UNIT TEKNIS + BALAI'
		);
		
		
		foreach($cities->result() as $city)
		{
			$cities_array[$city->nama_kota] = $city->nama_kota;
		}
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
		foreach($categories->result() as $cat)
		{
			$categories_array[$cat->id] = $cat->desc;
		}
		$data['categories'] = $categories_array;
		
	}
	
	public function list_all()
	{
		$data['title'] = 'Layanan PPID';
		$data['table_headers'] = $this->xss_clean(get_ppids_manage_table_headers());
		
		$data['filters'] = array(
			);
		$data['status_filter'] = '';	
		
		$this->setup_search($data);
		
		$this->load->view('ppid/manage', $data);
	}
	
	public function list_keberatan()
	{
		$data['title'] = 'Layanan Keberatan PPID';
		$data['table_headers'] = $this->xss_clean(get_ppids_keberatan_manage_table_headers());
		
		$data['filters'] = array(
			);
		$data['status_filter'] = '';	
		
		$this->setup_search($data);
		
		$this->load->view('ppid/manage_keberatan', $data);
	}
	
	public function download_form1($item_id)
	{
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(APPPATH.'templates/ppid/1. Formulir_Permohonan_Informasi_Publik.docx');
		$templateProcessor->setValue('ticketid', $item_info->trackid);
		$templateProcessor->setValue('nama', $item_info->iden_nama);
		$templateProcessor->setValue('alamat', $item_info->iden_alamat);
		
		$telp = $item_info->iden_telp;
		$email = $item_info->iden_email;
		
		if(!empty($telp) && !empty($email))
			$templateProcessor->setValue('telp', $telp . '/' . $email);
		if(!empty($telp) && empty($email))
			$templateProcessor->setValue('telp', $telp);
		if(empty($telp) && !empty($email))
			$templateProcessor->setValue('telp', $email);
		else
			$templateProcessor->setValue('telp','');
		
		$pertanyaan = strip_tags($item_info->prod_masalah);
		
		
		$templateProcessor->setValue('pertanyaan', $pertanyaan);
		$templateProcessor->setValue('pekerjaan', $item_info->profesi);
		$templateProcessor->setValue('nama2', $item_info->iden_nama);
		
		
		$tanggal = date('d').' '.to_bulan(date('n')).' '.date('Y');
		
		
		$templateProcessor->setValue('tanggal', $tanggal);
		
		header('Content-Type: application/msword');
        header('Content-Disposition: attachment;filename=Formulir_Permohonan_Informasi_Publik.docx'); 
		header('Cache-Control: max-age=0');
		
		//$templateProcessor->saveAs('MyWordFile.docx');
		$templateProcessor->saveAs('php://output');
	}
	
	public function download_form2($ticketid)
	{
		
	}
	
	public function download_form3($ticketid)
	{
		
	}
	
	public function download_form4($ticketid)
	{
		
	}
	
	public function download_form5($ticketid)
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
		
		$type = $this->input->get('type');
		
		if(empty($kota))
			$kota = $this->session->city;

		//$filters = array();
		$filters = array(
						'kota' => $kota,
						'tgl1' => $tgl1,
						'tgl2' => $tgl2,
						'type' => $type
						);
		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);
		
		

		$items = $this->Ticket->search_ppid($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Ticket->get_ppid_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach($items->result() as $item)
		{
			if($type == 'P')
			{
				$data_rows[] = $this->xss_clean(get_ppid_data_row($item, ++$no));
			}
			elseif($type == 'K')
			{
				$data_rows[] = $this->xss_clean(get_ppid_keberatan_data_row($item, ++$no));
			}
			
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
		
		$item_id = (int)$item_id;
		
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
		
		$data['rujukans'] = $this->Ticket->get_rujukans($item_info->direktorat, $item_info->direktorat2,
		$item_info->direktorat3, $item_info->direktorat4, $item_info->direktorat5 /*, $item_info->direktorat6, $item_info->direktorat7*/);
		
		
		$data['replies'] = $this->Ticket->get_replies($item_id);
		$data['replies_cnt'] = count($data['replies']);
		
		/*$referer = $this->input->get('ref');
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
		else	
			$data['url_back'] = site_url('tickets/list_all');*/
		
		$data['url_back'] = site_url('ppid/list_all');
		
		$data['upload_url'] = site_url('tickets/send_reply');
		
		
		$attachments = $this->Ticket->get_attachments($item_info->trackid,0); //attachment pengaduan
		$array = array();
		foreach($attachments->result() as $row)
		{
			$array[] = array(
				//'saved_name' => $row->saved_name,
				//'att_id' => $row->att_id,
				'real_name' => $row->real_name,
				'url' => base_url().'uploads/files/'.$row->saved_name
				//'url' => site_url('downloads/download_attachment_ticket?att_id='.$row->att_id.'&trackid='.$item_info->trackid)
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
				//'url' => base_url().'uploads/files/'.$row->saved_name
				'url' => site_url('downloads/download_attachment_ticket?att_id='.$row->att_id.'&trackid='.$item_info->trackid)
			);
		}
		$data['att_jawaban'] = $array2;
		
		$data['upload_config'] = $this->config->item('upload_setting');

		$this->load->view('ppid/view', $data);
	}
	
	public function edit($item_id = -1)
	{
		
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
		
		//$data['rujukans'] = $this->Ticket->get_rujukans($item_id);
		
		$data['page_title'] = 'Ubah Data '.$item_info->trackid;
		$data['answered_via'] = get_sarana();
		$data['submited_via'] = get_sarana();
		$data['countries'] = get_countries();
		$data['provinces'] = get_provinces();
		$data['provinces2'] = get_provinces();
		$data['profesi'] = get_profesi();
		$data['products'] = get_products();
		//$data['products'] = get_products_sla($item_info->info);
		$data['sumberdata'] = array('' => '','SP4N' => 'SP4N','PPID'=>'PPID');
		
		/*$tgl_cut_off = '2020-10-01';
		
		if($item_info->tglpengaduan <= $tgl_cut_off)
		{
			$data['klasifikasi'] = get_klasifikasi();
		}
		else
			$data['klasifikasi'] = get_klasifikasi_sla($item_info->kategori, $item_info->info);*/
		
		$data['klasifikasi'] = get_klasifikasi();
		$data['subklasifikasi'] = get_subklasifikasi2($item_info->klasifikasi);
		
		$data['dir_rujukan'] = get_direktorat_rujukan();
		
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
		
		$data['back_url'] = site_url('ppid/list_all');
		$data['upload_url'] = site_url('ppid/do_upload');
		
		//$data['commodities_p'] = print_komoditi('P');
		//$data['commodities_i'] = print_komoditi('I');
		
		$data['subkla'] = get_subklasifikasi_json();
		
		$data['upload_config'] = $this->config->item('upload_setting');
		$data['mode'] = 'EDIT';
		
		if($item_info->jenis == 'PPID' && empty($ppid_info->alasan_keberatan))
			$this->load->view('ppid/form', $data);
		elseif($item_info->jenis == 'PPID' && !empty($ppid_info->alasan_keberatan))
			$this->load->view('ppid/form_keberatan', $data);
		
	}

	public function create()
	{
		redirect('drafts/create');
	}
	
	public function save($item_id = -1)
	{
		
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		
		
		$old_date = explode('-',$item_info->tglpengaduan);
		$today = explode('/',date('d/m/Y'));
		
		$locked = lockedForEdit($today, $old_date);
		
		if($locked)
		{
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
		
		if(strtolower($iden_negara)!='indonesia')
		{
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
		
		if(strtolower($prod_negara)!='indonesia')
		{
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
		
		if(empty($dir1))$dir1 = 0;
		if(empty($dir2))$dir2 = 0;
		if(empty($dir3))$dir3 = 0;
		if(empty($dir4))$dir4 = 0;
		if(empty($dir5))$dir5 = 0;
		
		// 
		$sla1 =  $this->input->post('sla1');
		$sla2 =  $this->input->post('sla2');
		$sla3 =  $this->input->post('sla3');
		$sla4 =  $this->input->post('sla4');
		$sla5 =  $this->input->post('sla5');
		
		if($is_rujuk == '0')
		{
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
		if(!empty($submited_via) && $submited_via != 'Medsos')
			$tipe_medsos = '';
		
		
		//ppid data
		$tgl_diterima = $this->input->post('tgl_diterima');
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
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		$item_data['history'] = $item_info->history;
		$item_data['history'] .= '<li class="smaller">Pada '.date('Y-m-d H:i:s').' layanan diubah oleh '.$this->session->name.'</li>';
		
		//$oldarrayticket = (array) $this->Ticket->get_info_ticket($item_id);
		//$oldarrayppid = (array) $this->Ticket->get_info_ppid($item_id);
		
		if($this->Ticket->save($item_data, $item_id))
		{
			/*$success = TRUE;
			
			if($item_id == -1)
			{
				$message = $this->xss_clean($this->lang->line('items_successful_adding') . ' ' . $item_id);

				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
			}
			else
			{*/
				$ppid_info = $this->Ticket->get_ppid_info($item_id);
				
				$message = 'Data berhasil disimpan';
				$this->Ticket->save_ppid($ppid_data, $item_id);
				
				$item_info2 = $this->Ticket->get_info($item_id);		
				log_layanan($item_info, $item_info2);
				
				
				$ppid_info2 = $this->Ticket->get_ppid_info($item_id);
				log_ppid($ppid_info, $ppid_info2);
				
				if($is_rujuk == '1')
				{
					$rujukan_data = array(
						'rid' => $item_id
					);
					$this->Ticket->save_rujukan($rujukan_data, $item_id);
					
					
					
					
					
					
				}
				else
				{
					//remove from rujukan from layanan
					/*if($item_info->direktorat) $this->Ticket->delete_rujukan($item_id, $item_info->direktorat, 1);
					if($item_info->direktorat2) $this->Ticket->delete_rujukan($item_id, $item_info->direktorat2, 2);
					if($item_info->direktorat3) $this->Ticket->delete_rujukan($item_id, $item_info->direktorat3, 3);
					if($item_info->direktorat4) $this->Ticket->delete_rujukan($item_id, $item_info->direktorat4, 4);
					if($item_info->direktorat5) $this->Ticket->delete_rujukan($item_id, $item_info->direktorat5, 5);*/
				}

				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
			//}
		}
		else // failure
		{
			$message = 'Data gagal disimpan';
			
			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
		}
	}

	
	
	

	public function delete()
	{
		/*$items_to_delete = $this->input->post('ids');

		if($this->City->delete_list($items_to_delete))
		{
			$message = $this->lang->line('items_successful_deleted') . ' ' . count($items_to_delete) . ' ' . $this->lang->line('items_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_cannot_be_deleted')));
		}*/
	}
	
	
	public function print_ppid_form1($item_id = -1)
	{
		$item_id = (int)$item_id;
		
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
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		$data['balai_info'] = $balai_info;
		
		
		
		
		$this->load->helper(array('dompdf', 'file'));
		
		$html = $this->load->view('ppid/print_ppid_form1', $data, TRUE);
		$filename = rand(0,1000);
		pdf_create($html, $filename);
		
		//$this->load->view('ppid/print_ppid_form1', $data);
	}
	
	public function print_ppid_form2($item_id = -1)
	{
		$item_id = (int)$item_id;
		
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
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		$data['balai_info'] = $balai_info;
		
		$this->load->helper(array('dompdf', 'file'));
		
		$html = $this->load->view('ppid/print_ppid_form2', $data, TRUE);
		$filename = rand(0,1000);
		pdf_create($html, $filename);
		
		//$this->load->view('ppid/print_ppid_form1', $data);
	}

	public function print_ppid_form3($item_id = -1)
	{
		$item_id = (int)$item_id;
		
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
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		$data['balai_info'] = $balai_info;
		
		$this->load->helper(array('dompdf', 'file'));
		
		$html = $this->load->view('ppid/print_ppid_form3', $data, TRUE);
		$filename = rand(0,1000);
		pdf_create($html, $filename);
		
		//$this->load->view('ppid/print_ppid_form1', $data);
	}
	
	public function print_ppid_form4($item_id = -1)
	{
		$item_id = (int)$item_id;
		
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
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		$data['balai_info'] = $balai_info;
		
		$this->load->helper(array('dompdf', 'file'));
		
		$html = $this->load->view('ppid/print_ppid_form4', $data, TRUE);
		$filename = rand(0,1000);
		pdf_create($html, $filename);
		
		//$this->load->view('ppid/print_ppid_form1', $data);
	}
	
	public function print_ppid_form5($item_id = -1)
	{
		$item_id = (int)$item_id;
		
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
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		$data['balai_info'] = $balai_info;
		
		$ppid_setting = $this->config->item('ppid_setting');
		$kop_path = $ppid_setting['kop_path'];
		
		$data['kop_image'] = file_exists($kop_path.$this->session->city.'.png')?$this->session->city.'.png':'PUSAT.png';
		//$data['kop_image'] = 'PANGKAL PINANG.png';
		
		$this->load->helper(array('dompdf', 'file'));
		
		$html = $this->load->view('ppid/print_ppid_form5', $data, TRUE);
		$filename = rand(0,1000);
		pdf_create($html, $filename);
		
		//$this->load->view('ppid/print_ppid_form1', $data);
	}
	
	public function print_ppid_form6($item_id = -1)
	{
		$item_id = (int)$item_id;
		
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
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		$data['balai_info'] = $balai_info;
		
		$this->load->helper(array('dompdf', 'file'));
		
		$html = $this->load->view('ppid/print_ppid_form6', $data, TRUE);
		$filename = rand(0,1000);
		pdf_create($html, $filename);
		
		//$this->load->view('ppid/print_ppid_form1', $data);
	}
	
	public function print_ppid_form7($item_id = -1)
	{
		$item_id = (int)$item_id;
		
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
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		$data['balai_info'] = $balai_info;
		
		$ppid_setting = $this->config->item('ppid_setting');
		$kop_path = $ppid_setting['kop_path'];
		
		$data['kop_image'] = file_exists($kop_path.$this->session->city.'.png')?$this->session->city.'.png':'PUSAT.png';
		
		$this->load->helper(array('dompdf', 'file'));
		
		$html = $this->load->view('ppid/print_ppid_form7', $data, TRUE);
		$filename = rand(0,1000);
		pdf_create($html, $filename);
		
		//$this->load->view('ppid/print_ppid_form1', $data);
	}
	
	public function ppid_word1($item_id = -1)
	{
		$item_id = (int)$item_id;
		//$item_id = 331355;
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		
		$ppid_setting = $this->config->item('ppid_setting');
		$template_path = $ppid_setting['template_path'];
		
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template_path.'FORM1.docx');
		$templateProcessor->setValue('simpelid',$item_info->trackid);
		$templateProcessor->setValue('nama',$item_info->iden_nama);
		$templateProcessor->setValue('alamat',$item_info->iden_alamat);
		$templateProcessor->setValue('pekerjaan',$item_info->profesi);
		$templateProcessor->setValue('telp',$item_info->iden_telp.' / '.$item_info->iden_email);
		
		$templateProcessor->setValue('rincian',$ppid_info->rincian);
		$templateProcessor->setValue('tujuan',$ppid_info->tujuan);
		
		
		$cara_memperoleh = explode(',', $ppid_info->cara_memperoleh_info);
		$cara_memperoleh_melihat = '<w:t xml:space="preserve">  </w:t>';
		$cara_memperoleh_mendapatkan = '<w:t xml:space="preserve">  </w:t>';
		foreach($cara_memperoleh as $o)
		{
			if($o == "1")
				$cara_memperoleh_melihat = 'X';
			elseif($o == "2")
				$cara_memperoleh_mendapatkan = 'X';
		}
		$templateProcessor->setValue('a',$cara_memperoleh_melihat);
		$templateProcessor->setValue('b',$cara_memperoleh_mendapatkan);
		
		$cara_mendapat_salinan = explode(',', $ppid_info->cara_mendapat_salinan);
		$cara_mendapat_mengambil = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_kurir = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_pos = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_faksimili = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_email = '<w:t xml:space="preserve">  </w:t>';
		
		foreach($cara_mendapat_salinan as $o)
		{
			if($o == '1')
				$cara_mendapat_mengambil = 'X';
			elseif($o == '2')
				$cara_mendapat_kurir = 'X';
			elseif($o == '3')
				$cara_mendapat_pos = 'X';
			elseif($o == '5')
				$cara_mendapat_faksimili = 'X';
			elseif($o == '4')
				$cara_mendapat_email = 'X';
		}
		
		$templateProcessor->setValue('a1',$cara_mendapat_mengambil);
		$templateProcessor->setValue('a2',$cara_mendapat_kurir);
		$templateProcessor->setValue('a3',$cara_mendapat_pos);
		$templateProcessor->setValue('a4',$cara_mendapat_faksimili);
		$templateProcessor->setValue('a5',$cara_mendapat_email);
		
		
		$templateProcessor->setValue('tempat', get_city_location());
		$templateProcessor->setValue('tgl', convert_date2($item_info->tglpengaduan));
		
		$templateProcessor->setValue('pemohon',$item_info->iden_nama);
		$templateProcessor->setValue('penerima',$item_info->penerima);
		
		
		if($this->session->city == 'PUSAT' || $this->session->city == 'UNIT TEKNIS')
		{
			$templateProcessor->setValue('kop_nama','BADAN PENGAWAS OBAT DAN MAKANAN');
			$templateProcessor->setValue('kop_alamat','Jl. Percetakan Negara No.23 Jakarta Pusat 10560');
			$templateProcessor->setValue('kop_telp','021-4263333');
			$templateProcessor->setValue('kop_email','ppid@pom.go.id');
			$templateProcessor->setValue('kop_faq','021-4209221');
		}
		else
		{
			$templateProcessor->setValue('kop_nama',$balai_info->kop);
			$templateProcessor->setValue('kop_alamat',$balai_info->alamat);
			$templateProcessor->setValue('kop_telp',$balai_info->no_telp);
			$templateProcessor->setValue('kop_email',$balai_info->email);
			$templateProcessor->setValue('kop_faq',$balai_info->no_faq);
		}
		
		header("Content-Disposition: attachment; filename=Formulir_Permintaan_Informasi_Publik_$item_info->trackid.docx");

		$templateProcessor->saveAs('php://output');
		
		
	}
	
	public function ppid_word3($item_id = -1)
	{
		$item_id = (int)$item_id;
		//$item_id = 331355;
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		
		$ppid_setting = $this->config->item('ppid_setting');
		$template_path = $ppid_setting['template_path'];
		
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template_path.'FORM3.docx');
		$templateProcessor->setValue('simpelid',$item_info->trackid);
		$templateProcessor->setValue('nama',$item_info->iden_nama);
		$templateProcessor->setValue('alamat',$item_info->iden_alamat);
		//$templateProcessor->setValue('pekerjaan',$item_info->profesi);
		$templateProcessor->setValue('telp',$item_info->iden_telp.' / '.$item_info->iden_email);
		
		$templateProcessor->setValue('rincian',$ppid_info->rincian);
		//$templateProcessor->setValue('tujuan',$ppid_info->tujuan);
		
		
		
		
		
		$templateProcessor->setValue('tempat', get_city_location());
		$templateProcessor->setValue('tgl', convert_date2($item_info->tglpengaduan));
		
		$templateProcessor->setValue('pemohon',$item_info->iden_nama);
		$templateProcessor->setValue('penerima',$item_info->penerima);
		
		
		if($this->session->city == 'PUSAT' || $this->session->city == 'UNIT TEKNIS')
		{
			$templateProcessor->setValue('kop_nama','BADAN PENGAWAS OBAT DAN MAKANAN');
			$templateProcessor->setValue('kop_alamat','Jl. Percetakan Negara No.23 Jakarta Pusat 10560');
			$templateProcessor->setValue('kop_telp','021-4263333');
			$templateProcessor->setValue('kop_email','ppid@pom.go.id');
			$templateProcessor->setValue('kop_faq','021-4209221');
		}
		else
		{
			$templateProcessor->setValue('kop_nama',$balai_info->kop);
			$templateProcessor->setValue('kop_alamat',$balai_info->alamat);
			$templateProcessor->setValue('kop_telp',$balai_info->no_telp);
			$templateProcessor->setValue('kop_email',$balai_info->email);
			$templateProcessor->setValue('kop_faq',$balai_info->no_faq);
		}
		
		header("Content-Disposition: attachment; filename=Formulir_Ketidaklengkapan_Permintaan_Informasi_Publik_$item_info->trackid.docx");

		$templateProcessor->saveAs('php://output');
		
		
	}
	
	public function ppid_word4($item_id = -1)
	{
		$item_id = (int)$item_id;
		//$item_id = 331355;
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		
		$ppid_setting = $this->config->item('ppid_setting');
		$template_path = $ppid_setting['template_path'];
		
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template_path.'FORM4.docx');
		$templateProcessor->setValue('simpelid',$item_info->trackid);
		$templateProcessor->setValue('nama',$item_info->iden_nama);
		$templateProcessor->setValue('alamat',$item_info->iden_alamat);
		$templateProcessor->setValue('telp',$item_info->iden_telp.' / '.$item_info->iden_email);
		$templateProcessor->setValue('tempat', get_city_location());
		$templateProcessor->setValue('tgl', convert_date2($item_info->tglpengaduan));
		
		//$templateProcessor->setValue('rincian',$ppid_info->rincian);
		//$templateProcessor->setValue('tujuan',$ppid_info->tujuan);
		
		
		/*$cara_memperoleh = explode(',', $ppid_info->cara_memperoleh_info);
		$cara_memperoleh_melihat = '<w:t xml:space="preserve">  </w:t>';
		$cara_memperoleh_mendapatkan = '<w:t xml:space="preserve">  </w:t>';
		foreach($cara_memperoleh as $o)
		{
			if($o == "1")
				$cara_memperoleh_melihat = 'X';
			elseif($o == "2")
				$cara_memperoleh_mendapatkan = 'X';
		}
		$templateProcessor->setValue('a',$cara_memperoleh_melihat);
		$templateProcessor->setValue('b',$cara_memperoleh_mendapatkan);
		
		$cara_mendapat_salinan = explode(',', $ppid_info->cara_mendapat_salinan);
		$cara_mendapat_mengambil = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_kurir = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_pos = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_faksimili = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_email = '<w:t xml:space="preserve">  </w:t>';
		
		foreach($cara_mendapat_salinan as $o)
		{
			if($o == '1')
				$cara_mendapat_mengambil = 'X';
			elseif($o == '2')
				$cara_mendapat_kurir = 'X';
			elseif($o == '3')
				$cara_mendapat_pos = 'X';
			elseif($o == '4')
				$cara_mendapat_faksimili = 'X';
			elseif($o == '5')
				$cara_mendapat_email = 'X';
		}
		
		$templateProcessor->setValue('a1',$cara_mendapat_mengambil);
		$templateProcessor->setValue('a2',$cara_mendapat_kurir);
		$templateProcessor->setValue('a3',$cara_mendapat_pos);
		$templateProcessor->setValue('a4',$cara_mendapat_faksimili);
		$templateProcessor->setValue('a5',$cara_mendapat_email);
		
		
		$templateProcessor->setValue('tempat', get_city_location());
		$templateProcessor->setValue('tgl', convert_date2($item_info->tglpengaduan));
		
		$templateProcessor->setValue('pemohon',$item_info->iden_nama);
		$templateProcessor->setValue('penerima',$item_info->penerima);
		*/
		
		
		if($this->session->city == 'PUSAT' || $this->session->city == 'UNIT TEKNIS')
		{
			$templateProcessor->setValue('kop_nama','BADAN PENGAWAS OBAT DAN MAKANAN');
			$templateProcessor->setValue('kop_alamat','Jl. Percetakan Negara No.23 Jakarta Pusat 10560');
			$templateProcessor->setValue('kop_telp','021-4263333');
			$templateProcessor->setValue('kop_email','ppid@pom.go.id');
			$templateProcessor->setValue('kop_faq','021-4209221');
		}
		else
		{
			$templateProcessor->setValue('kop_nama',$balai_info->kop);
			$templateProcessor->setValue('kop_alamat',$balai_info->alamat);
			$templateProcessor->setValue('kop_telp',$balai_info->no_telp);
			$templateProcessor->setValue('kop_email',$balai_info->email);
			$templateProcessor->setValue('kop_faq',$balai_info->no_faq);
		}
		
		header("Content-Disposition: attachment; filename=Pemberitahuan_Tertulis_$item_info->trackid.docx");

		$templateProcessor->saveAs('php://output');
		
		
	}
	
	public function ppid_word5($item_id = -1)
	{
		$item_id = (int)$item_id;
		//$item_id = 331355;
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		
		$ppid_setting = $this->config->item('ppid_setting');
		$template_path = $ppid_setting['template_path'];
		
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template_path.'FORM5.docx');
		$templateProcessor->setValue('simpelid',$item_info->trackid);
		$templateProcessor->setValue('nama',$item_info->iden_nama);
		$templateProcessor->setValue('alamat',$item_info->iden_alamat);
		$templateProcessor->setValue('telp',$item_info->iden_telp.' / '.$item_info->iden_email);
		$templateProcessor->setValue('tempat', get_city_location());
		$templateProcessor->setValue('tgl', convert_date2($item_info->tglpengaduan));
		
		$templateProcessor->setValue('rincian',$ppid_info->rincian);
		//$templateProcessor->setValue('tujuan',$ppid_info->tujuan);
		
		
		/*$cara_memperoleh = explode(',', $ppid_info->cara_memperoleh_info);
		$cara_memperoleh_melihat = '<w:t xml:space="preserve">  </w:t>';
		$cara_memperoleh_mendapatkan = '<w:t xml:space="preserve">  </w:t>';
		foreach($cara_memperoleh as $o)
		{
			if($o == "1")
				$cara_memperoleh_melihat = 'X';
			elseif($o == "2")
				$cara_memperoleh_mendapatkan = 'X';
		}
		$templateProcessor->setValue('a',$cara_memperoleh_melihat);
		$templateProcessor->setValue('b',$cara_memperoleh_mendapatkan);
		
		$cara_mendapat_salinan = explode(',', $ppid_info->cara_mendapat_salinan);
		$cara_mendapat_mengambil = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_kurir = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_pos = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_faksimili = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_email = '<w:t xml:space="preserve">  </w:t>';
		
		foreach($cara_mendapat_salinan as $o)
		{
			if($o == '1')
				$cara_mendapat_mengambil = 'X';
			elseif($o == '2')
				$cara_mendapat_kurir = 'X';
			elseif($o == '3')
				$cara_mendapat_pos = 'X';
			elseif($o == '4')
				$cara_mendapat_faksimili = 'X';
			elseif($o == '5')
				$cara_mendapat_email = 'X';
		}
		
		$templateProcessor->setValue('a1',$cara_mendapat_mengambil);
		$templateProcessor->setValue('a2',$cara_mendapat_kurir);
		$templateProcessor->setValue('a3',$cara_mendapat_pos);
		$templateProcessor->setValue('a4',$cara_mendapat_faksimili);
		$templateProcessor->setValue('a5',$cara_mendapat_email);
		
		
		$templateProcessor->setValue('tempat', get_city_location());
		$templateProcessor->setValue('tgl', convert_date2($item_info->tglpengaduan));
		
		$templateProcessor->setValue('pemohon',$item_info->iden_nama);
		$templateProcessor->setValue('penerima',$item_info->penerima);
		*/
		
		
		if($this->session->city == 'PUSAT' || $this->session->city == 'UNIT TEKNIS')
		{
			$templateProcessor->setValue('kop_nama','BADAN PENGAWAS OBAT DAN MAKANAN');
			$templateProcessor->setValue('kop_alamat','Jl. Percetakan Negara No.23 Jakarta Pusat 10560');
			$templateProcessor->setValue('kop_telp','021-4263333');
			$templateProcessor->setValue('kop_email','ppid@pom.go.id');
			$templateProcessor->setValue('kop_faq','021-4209221');
		}
		else
		{
			$templateProcessor->setValue('kop_nama',$balai_info->kop);
			$templateProcessor->setValue('kop_alamat',$balai_info->alamat);
			$templateProcessor->setValue('kop_telp',$balai_info->no_telp);
			$templateProcessor->setValue('kop_email',$balai_info->email);
			$templateProcessor->setValue('kop_faq',$balai_info->no_faq);
		}
		
		header("Content-Disposition: attachment; filename=SK_Penolakan_$item_info->trackid.docx");

		$templateProcessor->saveAs('php://output');
		
		
	}
	
	public function ppid_word6($item_id = -1)
	{
		$item_id = (int)$item_id;
		//$item_id = 331355;
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		
		$ppid_setting = $this->config->item('ppid_setting');
		$template_path = $ppid_setting['template_path'];
		
		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template_path.'FORM6.docx');
		$templateProcessor->setValue('simpelid',$item_info->trackid);
		$templateProcessor->setValue('nama',$item_info->iden_nama);
		$templateProcessor->setValue('alamat',$item_info->iden_alamat);
		$templateProcessor->setValue('telp',$item_info->iden_telp.' / '.$item_info->iden_email);
		$templateProcessor->setValue('tempat', get_city_location());
		$templateProcessor->setValue('tgl', convert_date2($item_info->tglpengaduan));
		
		$templateProcessor->setValue('nomor',$ppid_info->keberatan_no);
		$templateProcessor->setValue('tujuan',$ppid_info->tujuan);
		$templateProcessor->setValue('kuasa_nama',$ppid_info->kuasa_nama);
		$templateProcessor->setValue('kuasa_alamat',$ppid_info->kuasa_alamat);
		$templateProcessor->setValue('kuasa_telp',$ppid_info->kuasa_telp.' / '.$ppid_info->kuasa_email);
		
		/*$cara_memperoleh = explode(',', $ppid_info->cara_memperoleh_info);
		$cara_memperoleh_melihat = '<w:t xml:space="preserve">  </w:t>';
		$cara_memperoleh_mendapatkan = '<w:t xml:space="preserve">  </w:t>';
		foreach($cara_memperoleh as $o)
		{
			if($o == "1")
				$cara_memperoleh_melihat = 'X';
			elseif($o == "2")
				$cara_memperoleh_mendapatkan = 'X';
		}
		$templateProcessor->setValue('a',$cara_memperoleh_melihat);
		$templateProcessor->setValue('b',$cara_memperoleh_mendapatkan);
		
		$cara_mendapat_salinan = explode(',', $ppid_info->cara_mendapat_salinan);
		$cara_mendapat_mengambil = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_kurir = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_pos = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_faksimili = '<w:t xml:space="preserve">  </w:t>';
		$cara_mendapat_email = '<w:t xml:space="preserve">  </w:t>';
		
		foreach($cara_mendapat_salinan as $o)
		{
			if($o == '1')
				$cara_mendapat_mengambil = 'X';
			elseif($o == '2')
				$cara_mendapat_kurir = 'X';
			elseif($o == '3')
				$cara_mendapat_pos = 'X';
			elseif($o == '4')
				$cara_mendapat_faksimili = 'X';
			elseif($o == '5')
				$cara_mendapat_email = 'X';
		}
		
		$templateProcessor->setValue('a1',$cara_mendapat_mengambil);
		$templateProcessor->setValue('a2',$cara_mendapat_kurir);
		$templateProcessor->setValue('a3',$cara_mendapat_pos);
		$templateProcessor->setValue('a4',$cara_mendapat_faksimili);
		$templateProcessor->setValue('a5',$cara_mendapat_email);
		
		
		$templateProcessor->setValue('tempat', get_city_location());
		$templateProcessor->setValue('tgl', convert_date2($item_info->tglpengaduan));
		
		$templateProcessor->setValue('pemohon',$item_info->iden_nama);
		$templateProcessor->setValue('penerima',$item_info->penerima);
		*/
		
		
		if($this->session->city == 'PUSAT' || $this->session->city == 'UNIT TEKNIS')
		{
			$templateProcessor->setValue('kop_nama','BADAN PENGAWAS OBAT DAN MAKANAN');
			$templateProcessor->setValue('kop_alamat','Jl. Percetakan Negara No.23 Jakarta Pusat 10560');
			$templateProcessor->setValue('kop_telp','021-4263333');
			$templateProcessor->setValue('kop_email','ppid@pom.go.id');
			$templateProcessor->setValue('kop_faq','021-4209221');
		}
		else
		{
			$templateProcessor->setValue('kop_nama',$balai_info->kop);
			$templateProcessor->setValue('kop_alamat',$balai_info->alamat);
			$templateProcessor->setValue('kop_telp',$balai_info->no_telp);
			$templateProcessor->setValue('kop_email',$balai_info->email);
			$templateProcessor->setValue('kop_faq',$balai_info->no_faq);
		}
		
		header("Content-Disposition: attachment; filename=Formulir_Pernyataan_Keberatan_$item_info->trackid.docx");

		$templateProcessor->saveAs('php://output');
		
		
	}
	
	public function print_ppid_word1($item_id = -1)
	{
		$item_id = (int)$item_id;
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$phpWord->setDefaultFontSize(11);
		$phpWord->setDefaultFontName('Times New Roman');
		$phpWord->setDefaultParagraphStyle(
			array(
				'align'      => 'left',//'both',
				'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6),
				'spacing'    => 1.15,
				)
			);
	
		$section = $phpWord->addSection(array(
			'headerHeight'=> 5,
			'footerHeight' => 5,
			'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(21),
			'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(33),
		));
		$header = $section->addHeader();
		$header->firstPage();
		
		$styleTable = array('borderSize' => 0, 'borderColor' => 'ffffff', 'cellMargin' => 0, 'marginTop' => 3, 'cellSpacing' => -1);
		$styleTable2 = array('borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 20, 'cellSpacing' => 2, 'align' => 'center');
		$fontStyleHeader = array('bold' => true, 'align' => 'center', 'size' => 14);
		$fontStyle = array('bold' => true);
		$header->addTextBreak(2);
		$table = $header->addTable($styleTable);
		$table->addRow();
		
		
		$table->addCell(1750)->addImage('../public/assets/images/bpom.png', 
			array(
				//'width' => 550, 
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
				'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2)),
				//'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.1336)),
				'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0)),
				'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0)),
				'wrappingStyle' => 'infront'
			));
			
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		
		$textrun = $table->addCell(8000)->addTextRun(array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
		));
		$textrun->addText($balai_info->kop, $fontStyleHeader);
		$textrun->addTextBreak();
		$textrun->addText($balai_info->alamat);
		$textrun->addTextBreak();
		$textrun->addText("Tlp. ".$balai_info->no_telp." / Fax. ".$balai_info->no_fax);
		$textrun->addTextBreak();
		$textrun->addText("Email : ".$balai_info->email);
		
		
		$section->addTextBreak(2);
		$textrun = $section->addTextRun(array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
		));
		$textrun->addText("FORMULIR PERMOHONAN INFORMASI", $fontStyle);
		$textrun->addTextBreak();
		$textrun->addText("No. Pendaftaran: ".$item_info->trackid, $fontStyle);
		
		$section->addTextBreak(1);
		
		$arr = array();
		if(!empty($item_info->iden_telp))
			array_push($arr, $item_info->iden_telp);
		
		if(!empty($item_info->iden_email))
			array_push($arr, $item_info->iden_email);
		
		$telp_email = '';
		if(count($arr) > 0)
			$telp_email = implode('/', $arr);
		
		$table = $section->addTable($styleTable);
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Nama"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->iden_nama));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Alamat"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->iden_alamat));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Pekerjaan"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->profesi));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("No.Telp/Email"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($telp_email));
		
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Rincian Informasi yang Dibutuhkan"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($ppid_info->rincian));
		
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Tujuan Penggunaan Informasi"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($ppid_info->tujuan));
		
		$section->addTextBreak(1);
		
		$table = $section->addTable($styleTable);
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Cara Memperoleh Informasi**"));
		$table->addCell(200)->addText(" : ");
		$textrun = $table->addCell(7000)->addTextRun();
		
		
		/*$textrun->addText("1 ");
		$textrun->addCheckBox('cb1',"Melihat/membaca/mendengarkan/mencatat");
		$textrun->addTextBreak();
		$textrun->addText("2 ");
		$textrun->addCheckBox('cb2',"Mendapatkan salinan informasi (hardcopy/softcopy) ***");*/
		
		//$textrun = $cell->addTextRun();
		//$textrun->addFormField('checkbox')->setValue(true);
		
		//$textrun->addFormField('checkbox')->setValue('<w:sym w:font="Wingdings" w:char="F0FE"/>');
		//$textrun->addText('B');
		
		$cara_memperoleh_info = $ppid_info->cara_memperoleh_info;
		$cara_array = array();
		if(!empty($cara_memperoleh_info))
		{
			$cara = explode(',', $cara_memperoleh_info);
			foreach($cara as $obj)
			{
				$cara_array[$obj] = $obj;
			}
			
		}
		
		$cara_memperoleh_array = array(
			'1' => 'Melihat/membaca/mendengarkan/mencatat',
			'2' => 'Mendapatkan salinan dokumen (hardcopy/softcopy)'
		);
		
		foreach($cara_memperoleh_array as $k => $v)
		{
			$textrun->addText($k." ");
			if(array_key_exists($k, $cara_array))
				$textrun->addFormField('checkbox')->setValue(true);
			else
				$textrun->addFormField('checkbox');
			
			$textrun->addText($v);
			$textrun->addTextBreak();	
		}
		
		
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Cara Mendapatkan Salinan Informasi"));
		$table->addCell(200)->addText(" : ");
		$textrun = $table->addCell(7000)->addTextRun();
		
		$cara_mendapat_salinan = $ppid_info->cara_mendapat_salinan;
		$cara_salinan_array = array();
		if(!empty($cara_mendapat_salinan))
		{
			$cara = explode(',', $cara_mendapat_salinan);
			foreach($cara as $obj)
			{
				$cara_salinan_array[$obj] = $obj;
			}
			
		}
		
		$cara_mendapat_array = array(
			'1' => 'Mengambil langsung',
			'2' => 'Kurir',
			'3' => 'Pos',
			'4'	=> 'Email',
			'5'	=> 'Faksimili'
		);
		
		foreach($cara_mendapat_array as $k => $v)
		{
			$textrun->addText($k." ");
			if(array_key_exists($k, $cara_salinan_array))
				$textrun->addFormField('checkbox')->setValue(true);
			else
				$textrun->addFormField('checkbox');
			
			$textrun->addText($v);
			$textrun->addTextBreak();	
		}
		
		
		/*$textrun->addText("1 ");
		$textrun->addCheckBox('cb1',"Mengambil Langsung");
		$textrun->addTextBreak();
		$textrun->addText("2 ");
		$textrun->addCheckBox('cb2',"Kurir");
		$textrun->addTextBreak();
		$textrun->addText("3 ");
		$textrun->addCheckBox('cb2',"Pos");
		$textrun->addTextBreak();
		$textrun->addText("4 ");
		$textrun->addCheckBox('cb2',"Email");
		$textrun->addTextBreak();
		$textrun->addText("5 ");
		$textrun->addCheckBox('cb2',"Faksimili");*/
		
		$section->addTextBreak(2);
		$section->addText(get_city_location().', '.convert_date3($item_info->tglpengaduan),null, array('align' => 'center'));
		
		$table = $section->addTable($styleTable);
		$table->addRow();
		$textrun = $table->addCell(5000)->addTextRun(array('align' => 'center'));
		$textrun->addText("Petugas Pelayanan Informasi", array('bold' => true));
		$textrun->addTextBreak();
		$textrun->addText("Penerima Permohonan", array('bold' => true));
		$table->addCell(5000)->addText("Pemohon Informasi", array('bold' => true ), array('align' => 'center'));
		
		$table->addRow();
		/*if($item_info->penerima == "Oke Dwiraswati")
		{
		$table->addCell(5000)->addImage('../public/assets/images/ttd1.png', 
			array(
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
				'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.8)),
				//'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.1336)),
				'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-0.9)),
				'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.1)),
				//'wrappingStyle' => 'infront'
			));
		}
		else
		{
			$table->addCell(5000)->addText("");
		}*/
		$table->addCell(5000)->addText("");
		$textrun = $table->addCell(5000)->addTextRun();
		$textrun->addText("");
		$textrun->addTextBreak(4);
		$table->addRow();
		
		$table->addCell(5000)->addText($item_info->penerima, $fontStyle, array('align' => 'center'));
		$table->addCell(5000)->addText($item_info->iden_nama, $fontStyle, array('align' => 'center'));
		
		$section->addTextBreak(2);
		
		$styleTable = array('borderSize' => 0, 'borderColor' => 'ffffff', 'cellMargin' => 0,  'cellSpacing' => 0);
		
		$table = $section->addTable($styleTable);
		$table->addRow();
		$table->addCell(1000)->addText(htmlspecialchars("*"));
		$table->addCell(9000)->addText(htmlspecialchars("Diisi oleh petugas berdasarkan nomor registrasi permohonan Informasi Publik"));
		$table->addRow();
		$table->addCell(1000)->addText(htmlspecialchars("**"));
		$table->addCell(9000)->addText(htmlspecialchars("Pilih salah satu dengan memberi tanda"));
		$table->addRow();
		$table->addCell(1000)->addText(htmlspecialchars("***"));
		$table->addCell(9000)->addText(htmlspecialchars("Coret yang tidak perlu"));
		
		$section->addPageBreak();
		
		$textrun = $section->addTextRun(array('align' => 'center'));
		$textrun->addText("Hak-hak Pemohon Informasi", array('bold' => true));
		$textrun->addTextBreak();
		$textrun->addText("Berdasarkan Undang-undang Keterbukaan Informasi Publik No. 14/2008", array('bold' => true));
		
		$section->addTextBreak();
		
		$table = $section->addTable($styleTable);
		$table->addRow();
		$table->addCell(800)->addText("I.");
		$textrun = $table->addCell(9000)->addTextRun(array('align' => 'both'));
		$textrun->addText("Pemohon Informasi berhak untuk meminta seluruh informasi yang berada di Badan Publik kecuali ", array('bold' => true));
		$textrun->addText("(a) informasi yang apabila dibuka dan diberikan kepada pemohon informasi dapat: Menghambat proses penegakan hukum; Mengganggu kepentingan perlindungan hak atas kekayaan intelektual dan perlindungan dari persaingan usaha tidak sehat; Membahayakan pertahanan dan keamanan Negara; Mengungkap kekayaan alam Indonesia; Merugikan ketahanan ekonomi nasional; Merugikan kepentingan hubungan luar negeri; Mengungkap isi akta otentik yang bersifat pribadi dan kemauan terakhir ataupun wasiat seseorang; Mengungkap rahasia pribadi; Memorandum atau surat-surat antar Badan Publik atau intra Badan Publik yang menurut sifatnya  dirahasiakan kecuali atas putusan Komisi Informasi atau Pengadilan; Informasi yang tidak boleh diungkapkan berdasarkan Undang-undang. (b) Badan Publik juga dapat tidak memberikan informasi yang belum dikuasai atau didokumentasikan.");
		
		$table->addRow();
		$table->addCell(800)->addText("II.");
		$textrun = $table->addCell(9000)->addTextRun(array('align' => 'both'));
		$textrun->addText("PASTIKAN ANDA MENDAPAT TANDA TERIMA PERMINTAAN INFORMASI BERUPA NOMOR PENDAFTARAN KE PETUGAS INFORMASI/PPID. ", array('bold' => true));
		$textrun->addText("Bila tanda terima tidak diberikan tanyakan kepada petugas informasi alasannya, mungkin permintaan informasi anda kurang lengkap.");
		
		$table->addRow();
		$table->addCell(800)->addText("III.");
		$textrun = $table->addCell(9000)->addTextRun(array('align' => 'both'));
		$textrun->addText("Pemohon Informasi berhak untuk mendapatkan ");
		$textrun->addText("pemberitahuan tertulis ", array('bold' => true));
		$textrun->addText("atas diterima atau tidaknya permohonan informasi dalan jangka waktu ");
		$textrun->addText("10 (sepuluh) hari kerja ", array('bold' => true));
		$textrun->addText("sejak diterimanya permohonan informasi oleh Badan Publik. Badan Publik dapat memperpanjang waktu untuk memberi jawaban tertulis ");
		$textrun->addText("1 x 7 hari kerja", array('bold' => true));
		$textrun->addText(", dalam hal: informasi yang diminta belum dikuasai/didokumentasikan/belum dapat diputuskan apakah informasi yang diminta termasuk informasi yang dikecualikan atau tidak.");
		
		$table->addRow();
		$table->addCell(800)->addText("IV.");
		$textrun = $table->addCell(9000)->addTextRun(array('align' => 'both'));
		$textrun->addText("Biaya ", array('bold' => true));
		$textrun->addText("yang dikenakan bagi permintaan atas salinan informasi berdasarkan surat keputusan Pimpinan Badan Publik adalah (diisi sesuai dengan surat keputusan Pimpinan Badan Publik)");
		
		$table->addRow();
		$table->addCell(800)->addText("V.");
		$textrun = $table->addCell(9000)->addTextRun(array('align' => 'both'));
		$textrun->addText("Apabila");
		$textrun->addText(" Pemohon Informasi tidak puas dengan keputusan Badan Publik (misal: menolak permintaan Anda atau memberikan hanya sebagian yang diminta), ", array('bold' => true));
		$textrun->addText("maka pemohon informasi dapat mengajukan ");
		$textrun->addText("keberatan ", array('bold' => true));
		$textrun->addText("kepada ");
		$textrun->addText("atasan PPID ", array('bold' => true));
		$textrun->addText("dalam jangka waktu ");
		$textrun->addText("30 (tiga puluh) hari kerja ", array('bold' => true));
		$textrun->addText("sejak permohonan informasi ditolak/ditemukannya alasan keberatan lainnya. Atasan PPID wajib memberikan tanggapan tertulis atas keberatan yang diajukan Pemohon Informasi selambat-lambatnya ");
		$textrun->addText("30 (tiga puluh) hari kerja ", array('bold' => true));
		$textrun->addText("sejak diterima/dicatatnya pengajuan keberatan dalam register keberatan.");
		
		$table->addRow();
		$table->addCell(800)->addText("VI.");
		$textrun = $table->addCell(9000)->addTextRun(array('align' => 'both'));
		$textrun->addText("Apabila Pemohon Informasi tidak puas dengan keputusan Atasan PPID, maka pemohon informasi dapat mengajukan ");
		$textrun->addText("keberatan ", array('bold' => true));
		$textrun->addText("kepada ");
		$textrun->addText("Komisi Informasi ", array('bold' => true));
		$textrun->addText("dalam jangka waktu ");
		$textrun->addText("14 (empat belas) hari kerja ", array('bold' => true));
		$textrun->addText("sejak diterimanya keputusan atasan PPID oleh Pemohon Informasi Publik.");
		
		
		
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename="Formulir_permohonan_informasi.docx"');

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('php://output');
		
	}
	
	public function print_ppid_word2($item_id = -1)
	{
		$item_id = (int)$item_id;
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$phpWord->setDefaultFontSize(11);
		$phpWord->setDefaultFontName('Times New Roman');
		$phpWord->setDefaultParagraphStyle(
			array(
				'align'      => 'left',//'both',
				'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6),
				'spacing'    => 1.15,
				)
			);
	
		$section = $phpWord->addSection(array(
			'headerHeight'=> 5,
			'footerHeight' => 5,
			'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(21),
			'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(33),
		));
		$header = $section->addHeader();
		$header->firstPage();
		
		$styleTable = array('borderSize' => 0, 'borderColor' => 'ffffff', 'cellMargin' => 0, 'marginTop' => 3, 'cellSpacing' => -1);
		$styleTable2 = array('borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 20, 'cellSpacing' => 2, 'align' => 'center');
		$fontStyleHeader = array('bold' => true, 'align' => 'center', 'size' => 14);
		$fontStyle = array('bold' => true);
		$header->addTextBreak(2);
		$table = $header->addTable($styleTable);
		$table->addRow();
		
		
		$table->addCell(1750)->addImage('../public/assets/images/bpom.png', 
			array(
				//'width' => 550, 
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
				'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2)),
				//'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.1336)),
				'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0)),
				'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0)),
				'wrappingStyle' => 'infront'
			));
			
		
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		
		$textrun = $table->addCell(8000)->addTextRun(array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
		));
		$textrun->addText($balai_info->kop, $fontStyleHeader);
		$textrun->addTextBreak();
		$textrun->addText($balai_info->alamat);
		$textrun->addTextBreak();
		$textrun->addText("Tlp. ".$balai_info->no_telp." / Fax. ".$balai_info->no_fax);
		$textrun->addTextBreak();
		$textrun->addText("Email : ".$balai_info->email);
		
		
		$section->addTextBreak(2);
		$textrun = $section->addTextRun(array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
		));
		$textrun->addText("TANDA TERIMA PERMOHONAN INFORMASI", $fontStyle);
		$textrun->addTextBreak();
		$textrun->addText("No. Pendaftaran: ".$item_info->trackid, $fontStyle);
		
		$section->addTextBreak(1);
		
		$arr = array();
		if(!empty($item_info->iden_telp))
			array_push($arr, $item_info->iden_telp);
		
		if(!empty($item_info->iden_email))
			array_push($arr, $item_info->iden_email);
		
		$telp_email = '';
		if(count($arr) > 0)
			$telp_email = implode('/', $arr);
		
		$table = $section->addTable($styleTable);
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Nomor Registrasi"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->trackid));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Nama Pemohon"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->iden_nama));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("No. KTP"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($ppid_info->no_ktp));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Diterima Tanggal"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars(convert_date3($ppid_info->tgl_diterima)));
		
		$section->addTextBreak(2);
		
		$table = $section->addTable($styleTable);
		$table->addRow();
		$table->addCell(5000)->addText("Pemohon Informasi Publik", array('bold' => true), array('align' => 'center'));
		$table->addCell(5000)->addText("Petugas PPID", array('bold' => true ), array('align' => 'center'));
		
		/*$table->addRow();
		$textrun = $table->addCell(5000)->addTextRun();
		$textrun->addText("");
		$textrun->addTextBreak(4);
		$table->addCell(5000)->addImage('../public/assets/images/ttd1.png', 
			array(
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
				'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.8)),
				//'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.1336)),
				'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-0.9)),
				'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.1)),
				//'wrappingStyle' => 'infront'
			));
		$table->addRow();
		$table->addCell(5000)->addText($item_info->iden_nama, $fontStyle, array('align' => 'center'));
		$table->addCell(5000)->addText("Oke Dwiraswati", $fontStyle, array('align' => 'center'));*/
		$table->addRow();
		
		$table->addCell(5000)->addText("");
		
		$textrun = $table->addCell(5000)->addTextRun();
		$textrun->addText("");
		$textrun->addTextBreak(4);
		$table->addRow();
		
		$table->addCell(5000)->addText($item_info->iden_nama, $fontStyle, array('align' => 'center'));
		$table->addCell(5000)->addText($item_info->penerima, $fontStyle, array('align' => 'center'));
		
		
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename="Formulir_tanda_terima.docx"');

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('php://output');
		
	}
	
	public function print_ppid_word3($item_id = -1)
	{
		
		$item_id = (int)$item_id;
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		//$data['item_info'] = $item_info;
		//$this->load->helper(array('dompdf', 'file'));
		//$html = $this->load->view('ppid/print_ppid_form5_word', $data, TRUE);
		
		
		
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$phpWord->setDefaultFontSize(11);
		$phpWord->setDefaultFontName('Times New Roman');
		$phpWord->setDefaultParagraphStyle(
			array(
				'align'      => 'left',//'both',
				'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6),
				'spacing'    => 1.15,
				)
			);
	
		$section = $phpWord->addSection(array(
			'headerHeight'=> 5,
			'footerHeight' => 5,
			'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(21),
			'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(33),
		));
		$header = $section->addHeader();
		$header->firstPage();
		
		$styleTable = array('borderSize' => 0, 'borderColor' => 'ffffff', 'cellMargin' => 0, 'marginTop' => 3, 'cellSpacing' => -1);
		$styleTable2 = array('borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 20, 'cellSpacing' => 2, 'align' => 'center');
		$fontStyleHeader = array('bold' => true, 'align' => 'center', 'size' => 14);
		$fontStyle = array('bold' => true, 'align' => 'center');
		$header->addTextBreak(2);
		$table = $header->addTable($styleTable);
		$table->addRow();
		
		
		$table->addCell(1750)->addImage('../public/assets/images/bpom.png', 
			array(
				//'width' => 550, 
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
				'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2)),
				//'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.1336)),
				'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0)),
				'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0)),
				'wrappingStyle' => 'infront'
			));
			
		
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		
		$textrun = $table->addCell(8000)->addTextRun(array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
		));
		$textrun->addText($balai_info->kop, $fontStyleHeader);
		$textrun->addTextBreak();
		$textrun->addText($balai_info->alamat);
		$textrun->addTextBreak();
		$textrun->addText("Tlp. ".$balai_info->no_telp." / Fax. ".$balai_info->no_fax);
		$textrun->addTextBreak();
		$textrun->addText("Email : ".$balai_info->email);
		
		
		$section->addTextBreak(2);
		$textrun = $section->addTextRun(array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
		));
		$textrun->addText("SURAT PEMBERITAHUAN PPID TENTANG PENOLAKAN PERMOHONAN INFORMASI", $fontStyle);
		$textrun->addTextBreak();
		$textrun->addText("No. Pendaftaran: ".$item_info->trackid, $fontStyle);
		
		$section->addTextBreak(1);
		
		$arr = array();
		if(!empty($item_info->iden_telp))
			array_push($arr, $item_info->iden_telp);
		
		if(!empty($item_info->iden_email))
			array_push($arr, $item_info->iden_email);
		
		$telp_email = '';
		if(count($arr) > 0)
			$telp_email = implode('/', $arr);
		
		$table = $section->addTable($styleTable);
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Nama"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->iden_nama));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Alamat"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->iden_alamat));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("No.Telp/Email"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($telp_email));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Rincian Informasi yang Dibutuhkan"), array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::START,
		));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($ppid_info->rincian));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Tujuan Penggunaan Informasi"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($ppid_info->tujuan));
		
		$section->addText("PPID memutuskan bahwa informasi yang dimohon adalah:");
		//$section->addTextBreak();
		
		$table = $section->addTable($styleTable2);
		$table->addRow();
		$table->addCell(4500)->addText(htmlspecialchars("INFORMASI YANG DIKECUALIKAN"), $fontStyle, array('spaceAfter' => 6, 'spaceBefore' => 6, 'align' => 'center'));
		$section->addTextBreak();
		
		$table = $section->addTable($styleTable);
		$table->addRow();
		$table->addCell(2750,  array('vMerge' => 'restart', 'valign' => 'top'))->addText(htmlspecialchars("Pengecualian Informasi didasarkan pada alasan"));
		$table->addCell(100, array('vMerge' => 'restart', 'valign' => 'top'))->addText(htmlspecialchars(":"));
		
		if($ppid_info->pengecualian_pasal17)
		{
			$table->addCell(100)->addFormField('checkbox')->setValue(true);
			$textrun = $table->addCell(7000)->addTextRun();
			$textrun->addText("Pasal 17 Huruf ".$ppid_info->pasal17_huruf." UU KIP");
		}
		else
		{
			$table->addCell(100)->addCheckBox('chkBox1');
			$table->addCell(7000)->addText(htmlspecialchars("Pasal 17 Huruf    UU KIP"));
		}
		$table->addRow();
		$table->addCell(null, array('vMerge' => 'continue'));
		$table->addCell(null, array('vMerge' => 'continue'));
		//$table->addCell(100)->addCheckBox('chkBox2');
		if($ppid_info->pengecualian_pasal_lain)
			$table->addCell(100)->addFormField('checkbox')->setValue(true);
		else
			$table->addCell(100)->addCheckBox('chkBox2');
		$table->addCell(7000)->addText(htmlspecialchars($ppid_info->pasal_lain_uu));
		
		$section->addText(htmlspecialchars("Bahwa berdasarkan Pasal-Pasal di atas, membuka informasi tersebut dapat menimbulkan konsekuensi sebagai berikut:"));
		//$section->addText(htmlspecialchars("Apabila membuka informasi publik tersebut pada butir 1 (satu) Rincian informasi yang Dibutuhkan, dapat mengganggu kepentingan perlindungan dari persaingan usaha tidak sehat."), array('underline' => 'single'));
		$section->addText(htmlspecialchars($ppid_info->konsekuensi), array('underline' => 'single'));
		$section->addText(htmlspecialchars("Dengan demikian menyatakan bahwa:"));
		
		$table = $section->addTable($styleTable2);
		$table->addRow();
		$table->addCell(4500)->addText(htmlspecialchars("PERMOHONAN INFORMASI DITOLAK"), $fontStyle, array('spaceAfter' => 6, 'spaceBefore' => 6, 'align' => 'center'));
		$section->addTextBreak();
		$section->addText(htmlspecialchars("Jika Pemohon Informasi keberatan atas penolakan ini maka Pemohon Informasi dapat mengajukan keberatan kepada Atasan PPID selambat-lambatnya 30 (tiga puluh) hari kerja sejak menerima Surat Pemberitahuan ini."));
		
		
		$section->addText(get_city_location().", ".convert_date3($ppid_info->tgl_pemberitahuan_tertulis), array(), array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
		));
		$textrun = $section->addTextRun(array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
		));
		$textrun->addText("Pejabat Pengelola lnformasi dan Dokumentasi (PPID)", array('bold' => true));
		$textrun->addTextBreak();
		$textrun->addText("Bidang Pelayanan Informasi", array('bold' => true));
		$textrun->addTextBreak(6);
		$textrun->addText($ppid_info->nama_pejabat_ppid);
		
		
		
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename="Formulir_penolakan.docx"');

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('php://output');
	}
	
	public function print_ppid_word4($item_id = -1)
	{
		$item_id = (int)$item_id;
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$phpWord->setDefaultFontSize(11);
		$phpWord->setDefaultFontName('Times New Roman');
		
		$phpWord->setDefaultParagraphStyle(
			array(
				'align'      => 'left',//'both',
				'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6),
				'spacing'    => 1.15,
				)
			);
	
		$section = $phpWord->addSection(array(
			'headerHeight'=> 5,
			'footerHeight' => 5,
			//'paperSize' => 'A4'
			'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(21),
			'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(33),
		));
		
		$header = $section->addHeader();
		$header->firstPage();
		
		$styleTable = array('borderSize' => 0, 'borderColor' => 'ffffff', 'cellMargin' => 0, 'marginTop' => 3, 'cellSpacing' => -1);
		$styleTable2 = array('borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 20, 'cellSpacing' => 2, 'align' => 'center');
		$fontStyleHeader = array('bold' => true, 'align' => 'center', 'size' => 14);
		$fontStyle = array('bold' => true);
		$header->addTextBreak(1);
		$table = $header->addTable($styleTable);
		$table->addRow();
		
		
		$table->addCell(1750)->addImage('../public/assets/images/bpom.png', 
			array(
				//'width' => 550, 
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
				'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2)),
				//'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.1336)),
				'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0)),
				'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0)),
				'wrappingStyle' => 'infront'
			));
			
		
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		
		$textrun = $table->addCell(8000)->addTextRun(array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
		));
		$textrun->addText($balai_info->kop, $fontStyleHeader);
		$textrun->addTextBreak();
		$textrun->addText($balai_info->alamat);
		$textrun->addTextBreak();
		$textrun->addText("Tlp. ".$balai_info->no_telp." / Fax. ".$balai_info->no_fax);
		$textrun->addTextBreak();
		$textrun->addText("Email : ".$balai_info->email);
		
		
		$section->addTextBreak();
		$textrun = $section->addTextRun(array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
		));
		$textrun->addText("PEMBERITAHUAN TERTULIS", $fontStyle);
		
		$section->addTextBreak(1);
		$section->addText(sprintf("Berdasarkan permohonan informasi pada tanggal %s dengan nomor pendaftaran %s Kami menyampaikan kepada Saudara/i:", convert_date3($ppid_info->tgl_diterima), $item_info->trackid));
		
		$arr = array();
		if(!empty($item_info->iden_telp))
			array_push($arr, $item_info->iden_telp);
		
		if(!empty($item_info->iden_email))
			array_push($arr, $item_info->iden_email);
		
		$telp_email = '';
		if(count($arr) > 0)
			$telp_email = implode('/', $arr);
		
		$table = $section->addTable($styleTable);
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Nama"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->iden_nama));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Alamat"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->iden_alamat));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("No.Telp/Email"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($telp_email));
		
		$section->addText("Pemberitahuan sebagai berikut:");
		//$section->addTextBreak();
		$section->addText("A. Informasi dapat diberikan", array('bold' => true ));
		
		
		$table = $section->addTable($styleTable2);
		$table->addRow();
		$table->addCell(200)->addText(htmlspecialchars("No."), array('bold' => true ), array('align' => 'center' ));
		$table->addCell(4000)->addText(htmlspecialchars("Hal-hal terkait Informasi Publik"), array('bold' => true ), array('align' => 'center' ));
		$table->addCell(5000, array('gridSpan' => 2))->addText(htmlspecialchars("Keterangan"), array('bold' => true ), array('align' => 'center' ));
		
		$table->addRow();
		$table->addCell(200)->addText(htmlspecialchars("1."));
		$table->addCell(4000)->addText(htmlspecialchars("Penguasaan Informasi Publik"));
		//$table->addCell(200)->addCheckBox('chkBox1');
		//$table->addCell(5000, array('gridSpan' => 2))->addText(htmlspecialchars(""));
		$cell = $table->addCell(5000, array('gridSpan' => 2));
		$table2 = $cell->addTable();
		$table2->addRow();
		$textrun = $table2->addCell(5000)->addTextRun();
		if($ppid_info->penguasaan_kami)
		{
			$textrun->addFormField('checkbox')->setValue(true);
			$textrun->addText("Kami:");
			$textrun->addTextBreak();
			$textrun->addText($ppid_info->penguasaan_kami_teks);
		}
		else
		{
			$textrun->addFormField('checkbox');
			$textrun->addText("Kami:");
			$textrun->addText("");
		}
		
		//$cell->addCheckBox('chkBox5', 'Kami:');
		//$cell->addText("Produk kosmetik yang tidak memiliki izin edar dan ditarik dari peredaran yang merupakan hasil pengawasan Badan POM.");
		
		$table2->addRow();
		//$cell = $table2->addCell(5000);
		//$cell->addCheckBox('chkBox6', 'Badan Publik Lain');
		$textrun = $table2->addCell(5000)->addTextRun();
		if($ppid_info->penguasaan_badan_lain)
		{
			$textrun->addFormField('checkbox')->setValue(true);
			$textrun->addText("Badan Publik Lain:");
			$textrun->addTextBreak();
			$textrun->addText($ppid_info->nama_badan_lain);
		}
		else
		{
			$textrun->addFormField('checkbox');
			$textrun->addText("Badan Publik Lain:");
		}
		
		$bentuk_fisik = $ppid_info->bentuk_fisik;
		$bentuk_fisik_tokens = array();
		if(!empty($bentuk_fisik))
		{
			$tokens = explode(',', $bentuk_fisik);
			foreach($tokens as $obj)
			{
				$bentuk_fisik_tokens[$obj] = $obj;
			}
			
		}
		
		$table->addRow();
		$table->addCell(200)->addText(htmlspecialchars("2."));
		$table->addCell(4000)->addText(htmlspecialchars("Bentuk fisik yang tersedia"));
		$cell = $table->addCell(5000, array('gridSpan' => 2));
		$table2 = $cell->addTable();
		$table2->addRow();
		//$cell = $table2->addCell(5000);
		//$cell->addCheckBox('chkBox5', 'Softcopy (termasuk rekaman)');
		$textrun = $table2->addCell(5000)->addTextRun();
		if(array_key_exists('1', $bentuk_fisik_tokens))
		{
			$textrun->addFormField('checkbox')->setValue(true);
			
		}
		else
		{
			$textrun->addFormField('checkbox');
		}
		$textrun->addText("Softcopy (termasuk rekaman)");
		
		
		
		
		
		$table2->addRow();
		$textrun = $table2->addCell(5000)->addTextRun();
		if(array_key_exists('2', $bentuk_fisik_tokens))
		{
			$textrun->addFormField('checkbox')->setValue(true);
			
		}
		else
		{
			$textrun->addFormField('checkbox');
		}
		$textrun->addText("Hardcopy/salinan tertulis");
		//$cell = $table2->addCell(5000);
		//$cell->addCheckBox('chkBox6', 'Hardcopy/salinan tertulis');
		//$cell->addTable()->addRow()->addCell();
		//$cell->addCheckBox('chkBox5', 'Softcopy (rermasuk rekaman)');
		//$cell->addRow();
		//$cell->addCell(200)->addCheckBox('chkBox6', 'Softcopy (rermasuk rekaman)');
		
		$table->addRow();
		$table->addCell(200)->addText(htmlspecialchars("3."));
		$table->addCell(4000)->addText(htmlspecialchars("Biaya yang dibutuhkan"));
		//$table->addCell(1500)->addCheckBox('chkBox1', 'Penyalinan');
		$textrun = $table->addCell(1500)->addTextRun();
		if($ppid_info->penyalinan)
		{
			$textrun->addFormField('checkbox')->setValue(true);
		}
		else
		{
			$textrun->addFormField('checkbox');
		}
		$textrun->addText("Penyalinan");
		
		$table->addCell(5000)->addText(htmlspecialchars("Rp. ".$ppid_info->biaya_penyalinan." x  ".$ppid_info->biaya_penyalinan_lbr." (jml lembaran) = Rp. ".$ppid_info->biaya_penyalinan*$ppid_info->biaya_penyalinan_lbr));
		
		$table->addRow();
		$table->addCell(200)->addText(htmlspecialchars(""));
		$table->addCell(4000)->addText(htmlspecialchars(""));
		//$table->addCell(1500)->addCheckBox('chkBox1', 'Pengiriman');
		$textrun = $table->addCell(1500)->addTextRun();
		if($ppid_info->pengiriman)
		{
			$textrun->addFormField('checkbox')->setValue(true);
		}
		else
		{
			$textrun->addFormField('checkbox');
		}
		$textrun->addText("Pengiriman");
		$table->addCell(5000)->addText(htmlspecialchars("Rp. ".$ppid_info->biaya_pengiriman));
		
		$table->addRow();
		$table->addCell(200)->addText(htmlspecialchars(""));
		$table->addCell(4000)->addText(htmlspecialchars(""));
		//$table->addCell(1500)->addCheckBox('chkBox1', 'Lain-lain');
		$textrun = $table->addCell(1500)->addTextRun();
		if($ppid_info->lain_lain)
		{
			$textrun->addFormField('checkbox')->setValue(true);
		}
		else
		{
			$textrun->addFormField('checkbox');
		}
		$textrun->addText("Lain-lain");
		$table->addCell(5000)->addText(htmlspecialchars("Rp. ".$ppid_info->biaya_lain));
		
		$table->addRow();
		$table->addCell(200)->addText(htmlspecialchars(""));
		$table->addCell(4000)->addText(htmlspecialchars(""));
		$table->addCell(1500)->addText("Jumlah");
		$table->addCell(5000)->addText(htmlspecialchars("Rp. ".($ppid_info->biaya_penyalinan*$ppid_info->biaya_penyalinan_lbr+$ppid_info->biaya_pengiriman+$ppid_info->biaya_lain)));
		
		$table->addRow();
		$table->addCell(200)->addText(htmlspecialchars("4."));
		$table->addCell(4000)->addText(htmlspecialchars("Waktu penyediaan"));
		$table->addCell(5000, array('gridSpan' => 2))->addText(htmlspecialchars($ppid_info->waktu_penyediaan." Hari Kerja"));
		
		$table->addRow();
		$table->addCell(200)->addText(htmlspecialchars("5."));
		$textrun = $table->addCell(5000, array('gridSpan' => 3))->addTextRun();
		$textrun->addText("Penjelasan penghitaman/pengaburan Informasi yang dimohon**** (tambahkan kertas bila perlu)");
		$textrun->addTextBreak();
		$textrun->addTextBreak();
		$textrun->addText($ppid_info->penghitaman);
		//$textrun->addText("Berdasarkan Peraturan Kepala Badan POM Nomor 6 Tahun 2017 tentang Daftar Informasi Publik yang Dikecualikan di Lingkungan Badan POM, data produk kosmetik yang tidak memiliki izin edar dari Badan POM tetapi masih beredar di pasaran, merupakan informasi yang dikecualikan(termasuk Kategori Data dan Informasi terkait Pengawasan Obat dan Makanan), oleh karena itu, data tersebut tidak dapat kami berikan.");
		
		
		$section->addText("B. Informasi tidak dapat diberikan karena:", array('bold' => true ));
		$textrun = $section->addTextRun();
		//$textrun->addCheckBox('cb1'," Informasi yang diminta belum dikuasai");
		if($ppid_info->info_blm_dikuasai)
		{
			$textrun->addFormField('checkbox')->setValue(true);
		}
		else
		{
			$textrun->addFormField('checkbox');
		}
		$textrun->addText(" Informasi yang diminta belum dikuasai");
		$textrun->addTextBreak();
		//$textrun->addCheckBox('cb2'," Informasi yang diminta belum didokumentasikan");
		if($ppid_info->info_blm_didoc)
		{
			$textrun->addFormField('checkbox')->setValue(true);
		}
		else
		{
			$textrun->addFormField('checkbox');
		}
		$textrun->addText(" Informasi yang diminta belum didokumentasikan");
		
		$textrun->addTextBreak();
		$textrun->addText("Penyediaan informasi yang belum didokumentasikan dilakukan dalam jangka waktu ");
		
		if(!empty($ppid_info->waktu_penyediaan2))
		{
			$textrun->addText($ppid_info->waktu_penyediaan2);
		}
		else
		{
			$textrun->addText("-");
		}
		
		$textrun->addText(" Hari Kerja*****");
		
		$table = $section->addTable($styleTable);
		
		$table->addRow();
		$table->addCell(3000)->addText("");
		$textrun = $table->addCell(6000)->addTextRun( array('align' => 'center'));
		$textrun->addText(get_city_location().", ".convert_date3($item_info->tglpengaduan));
		$textrun->addTextBreak();
		$textrun->addText("Pejabat Pengelola lnformasi dan Dokumentasi (PPID)", array('bold' => true));
		$textrun->addTextBreak();
		$textrun->addText("Bidang Pelayanan Informasi", array('bold' => true ));
		
		$textrun->addTextBreak(5);
		$textrun->addText($ppid_info->nama_pejabat_ppid, array('bold' => true ));
		
		$section->addTextBreak(2);
		
		$table = $section->addTable($styleTable);
		$table->addRow();
		$table->addCell(1000)->addText(htmlspecialchars("*"));
		$table->addCell(9000)->addText(htmlspecialchars("Diisi sesuai dengan nomor pendaftaran pada formulir permohonan."));
		$table->addRow();
		$table->addCell(1000)->addText(htmlspecialchars("**"));
		$table->addCell(9000)->addText(htmlspecialchars("Pilih salah satu dengan memberi tanda ceklis."));
		$table->addRow();
		$table->addCell(1000)->addText(htmlspecialchars("***"));
		$table->addCell(9000)->addText(htmlspecialchars("Biaya penyalinan (fotokopi atau disket) dan/atau biaya pengiriman (khusus kurir dan pos) sesuai dengan standar biaya yang telah ditetapkan."));
		$table->addRow();
		$table->addCell(1000)->addText(htmlspecialchars("****"));
		$table->addCell(9000)->addText(htmlspecialchars("Jika ada penghitaman informasi dalam suatu dokumen, maka diberikan alasan penghitamannya."));
		$table->addRow();
		$table->addCell(1000)->addText(htmlspecialchars("*****"));
		$table->addCell(9000)->addText(htmlspecialchars("Diisi dengan keterangan waktu yang jelas untuk menyediakan informasi yang diminta."));
		
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename="Pemberitahuan_tertulis.docx"');

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('php://output');
	}
	
	public function print_ppid_word5($item_id = -1)
	{
		
		$item_id = (int)$item_id;
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		//$data['item_info'] = $item_info;
		
		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		
		
		
		//$this->load->helper(array('dompdf', 'file'));
		//$html = $this->load->view('ppid/print_ppid_form5_word', $data, TRUE);
		
		
		
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$phpWord->setDefaultFontSize(11);
		$phpWord->setDefaultFontName('Arial');
		$phpWord->setDefaultParagraphStyle(
			array(
				'align'      => 'both',
				'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6),
				'spacing'    => 1.15,
				)
			);
	
		$section = $phpWord->addSection(array(
			'headerHeight'=> 5,
			'footerHeight' => 5,
			'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(21),
			'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(33),
		));
		$header = $section->addHeader();
		$header->firstPage();
		$header->addImage('../public/assets/images/kop1.png', 
			array(
				//'width' => 550, 
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
				'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(15)),
				//'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.1336)),
				'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-1.5)),
				'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.5)),
				'wrappingStyle' => 'infront'
			));
			
		$footer = $section->addFooter();
		$footer->firstPage();
		$footer->addImage('../public/assets/images/footer1.png', 
			array(
				
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
				'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(17)),
				'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(5)),
				'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-2)),
				'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-4.5)),
				'wrappingStyle' => 'infront'
			));
		
		$section->addTextBreak(4);
		$kota = ($this->session->city == 'PUSAT')?'Jakarta':ucfirst(strtolower($this->session->city));
		$section->addText($kota.", ".convert_date3(date('Y-m-d')), array(), array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::END,
		));
		
		$phpWord->addParagraphStyle(
			'rightTab',
			array('tabs' => array(new \PhpOffice\PhpWord\Style\Tab('left', 1500)))
		);
		$section->addText(htmlspecialchars("Nomor\t: ".$ppid_info->tt_nomor), null, 'rightTab');
		$section->addText(htmlspecialchars("Lampiran\t: ".$ppid_info->tt_lampiran), null, 'rightTab');
		$section->addText(htmlspecialchars("Perihal\t: Tanggapan Permohonan Informasi Publik"), null, 'rightTab');
		
		$section->addTextBreak(1);
		$section->addText('Yth.');
		$section->addText('Sdr/i '.$item_info->iden_nama);
		$section->addText('di tempat');
		
		$section->addTextBreak(1);
		
		$section->addText("Sehubungan dengan permohonan informasi Saudara melalui Pejabat Pengelola Informasi dan Dokumentasi (PPID) Badan POM perihal ".$ppid_info->tt_perihal.", bersama ini kami sampaikan hal-hal sebagai berikut:");
		
		$predefinedMultilevelStyle = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER);
		
		$section->addListItem($ppid_info->tt_isi, 0, null, $predefinedMultilevelStyle);
		
		//$section->addListItem("Berdasarkan Peraturan Kepala Badan POM Nomor 6 Tahun 2017 tentang Daftar Informasi Publik yang Dikecualikan di Lingkungan Badan POM, data produk kosmetik yang tidak memiliki izin edar dari Badan POM tetapi masih beredar di pasaran, merupakan informasi yang dikecualikan (termasuk Kategori Data dan Informasi terkait Pengawasan Obat dan Makanan), oleh karena itu, data tersebut tidak dapat kami berikan.", 0, null, $predefinedMultilevelStyle);
		//$section->addListItem("Sebagai bentuk perlindungan konsumen, jika masyarakat mengetahui adanya peredaran produk kosmetik yang tidak memiliki izin edar Badan POM, dapat mengadukannya ke Contact Center HaloBPOM 1500533 atau ke Unit Layanan Pengaduan Konsumen (ULPK) Badan POM di seluruh Indonesia.", 0, null, $predefinedMultilevelStyle);
		
		
		
		$section->addTextBreak(1);
		$section->addText("Demikian kami sampaikan, atas perhatiannya diucapkan terima kasih.");
		$section->addTextBreak(1);
		
		$phpWord->addParagraphStyle(
			'tab2',
			array('tabs' => array(new \PhpOffice\PhpWord\Style\Tab('left', 5500)))
		);
		$section->addText(htmlspecialchars("\tKoordinator PPID Badan POM"), null, 'tab2');
		$section->addTextBreak(3);
		$section->addText(htmlspecialchars("\t ".$ppid_info->tt_pejabat), null, 'tab2');
		
		
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename="Tanggapan_Tertulis.docx"');

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('php://output');
	}
	
	public function print_ppid_word6($item_id = -1)
	{
		$item_id = (int)$item_id;
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$phpWord->setDefaultFontSize(11);
		$phpWord->setDefaultFontName('Times New Roman');
		$phpWord->setDefaultParagraphStyle(
			array(
				'align'      => 'left',//'both',
				'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6),
				'spacing'    => 1.15,
				)
			);
	
		$section = $phpWord->addSection(array(
			'headerHeight'=> 5,
			'footerHeight' => 5,
			'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(21),
			'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(33),
		));
		$header = $section->addHeader();
		$header->firstPage();
		
		$styleTable = array('borderSize' => 0, 'borderColor' => 'ffffff', 'cellMargin' => 0, 'marginTop' => 3, 'cellSpacing' => -1);
		$styleTable2 = array('borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 20, 'cellSpacing' => 2, 'align' => 'center');
		$fontStyleHeader = array('bold' => true, 'align' => 'center', 'size' => 14);
		$fontStyle = array('bold' => true);
		$header->addTextBreak(2);
		$table = $header->addTable($styleTable);
		$table->addRow();
		
		
		$table->addCell(1750)->addImage('../public/assets/images/bpom.png', 
			array(
				//'width' => 550, 
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
				'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2)),
				//'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.1336)),
				'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0)),
				'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0)),
				'wrappingStyle' => 'infront'
			));
			
		
		
		$balai_info = $this->Balai->get_address($this->session->city);
		foreach(get_object_vars($balai_info) as $property => $value)
		{
			$balai_info->$property = $this->xss_clean($value);
		}
		
		$textrun = $table->addCell(8000)->addTextRun(array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
		));
		$textrun->addText($balai_info->kop, $fontStyleHeader);
		$textrun->addTextBreak();
		$textrun->addText($balai_info->alamat);
		$textrun->addTextBreak();
		$textrun->addText("Tlp. ".$balai_info->no_telp." / Fax. ".$balai_info->no_fax);
		$textrun->addTextBreak();
		$textrun->addText("Email : ".$balai_info->email);
		
		
		$section->addTextBreak(2);
		$textrun = $section->addTextRun(array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
		));
		$textrun->addText("PERNYATAAN KEBERATAN ATAS PERMOHONAN INFORMASI", $fontStyle);
		$section->addTextBreak(1);
		
		$section->addText("A. INFORMASI PENGAJU KEBERATAN", array('bold' => true));
		
		$arr = array();
		if(!empty($item_info->iden_telp))
			array_push($arr, $item_info->iden_telp);
		
		if(!empty($item_info->iden_email))
			array_push($arr, $item_info->iden_email);
		
		$telp_email = '';
		if(count($arr) > 0)
			$telp_email = implode('/', $arr);
		
		$table = $section->addTable($styleTable);
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Nomor Registrasi Keberatan"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($ppid_info->no_reg_keberatan));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Nomor Pendaftaran Permohonan Informasi"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->trackid));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Tujuan Penggunaan Informasi"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($ppid_info->tujuan));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Identitas Pemohon"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars(""));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Nama"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->iden_nama));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Alamat"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->iden_alamat));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Nomor Telepon/Email"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($telp_email));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Identitas Kuasa Pemohon"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars(""));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Nama"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->iden_nama));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Alamat"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($item_info->iden_alamat));
		$table->addRow();
		$table->addCell(2750)->addText(htmlspecialchars("Nomor Telepon/Email"));
		$table->addCell(200)->addText(" : ");
		$table->addCell(7000)->addText(htmlspecialchars($telp_email));
		
		
		$section->addTextBreak(1);
		
		$textrun = $section->addTextRun();
		$textrun->addText("B. ALASAN PENGAJUAN KEBERATAN", array('bold' => true));
		$textrun->addTextBreak();
		
		$alasan_keberatan = $ppid_info->alasan_keberatan;
		$alasan_array = array();
		if(!empty($alasan_keberatan))
		{
			$tokens = explode(',', $alasan_keberatan);
			foreach($tokens as $obj)
			{
				$alasan_array[$obj] = $obj;
			}
			
		}
		
		$alasan_keberatan_array = array(
			'a' => 'a. Permohonan Informasi Ditolak',
			'b' => 'b. Informasi berkala tidak disediakan',
			'c' => 'c. Permintaan informasi tidak ditanggapi',
			'd' => 'd. Permintaan informasi ditanggapi tidak sebagaimana yang diminta',
			'e' => 'e. Permintaan informasi tidak dipenuhi',
			'f' => 'f. Biaya yang dikenakan tidak wajar',
			'g' => 'g. Informasi disampaikan melebihi jangka waktu yang ditentukan'
			
			
		);
		
		
		
		foreach($alasan_keberatan_array as $k => $v)
		{
			$textrun->addText(" ");
			if(array_key_exists($k, $alasan_array))
				$textrun->addFormField('checkbox')->setValue(true);
			else
				$textrun->addFormField('checkbox');
			$textrun->addText(" ");
			$textrun->addText($v);
			$textrun->addTextBreak();	
		}
		
		
		$textrun = $section->addTextRun();
		$textrun->addText("C. KASUS POSISI", array('bold' => true));
		$textrun->addTextBreak();
		$textrun->addText($ppid_info->kasus_posisi);
		$section->addTextBreak(2);
		$textrun->addText("D. HARI/TANGGAL TANGGAPAN ATAS KEBERATAN AKAN DIBERIKAN : ", array('bold' => true));
		$textrun->addText($ppid_info->tgl_tanggapan);
		$textrun->addTextBreak();
		$textrun->addText("Demikian pengajuan keberatan ini saya sampaikan, atas perhatian dan tanggapannya, saya ucapkan terima kasih");
		
		$textrun = $section->addTextRun(array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
		));
		
		$textrun->addText(get_city_location().", ".convert_date3($item_info->tglpengaduan));
		
		$table = $section->addTable($styleTable);
		$table->addRow();
		$textrun = $table->addCell(5000)->addTextRun(array('align' => 'center'));
		$textrun->addText("Petugas Informasi", array('bold' => true));
		$textrun->addTextBreak();
		$textrun->addText("Penerima Keberatan", array('bold' => true));
		$table->addCell(5000)->addText("Pengaju Keberatan", array('bold' => true ), array('align' => 'center'));
		
		$table->addRow();
		/*
		if($item_info->petugas_entry == "Oke Dwiraswati")
		{
		$table->addCell(5000)->addImage('../public/assets/images/ttd1.png', 
			array(
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
				'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.8)),
				//'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.1336)),
				'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-0.9)),
				'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.1)),
				//'wrappingStyle' => 'infront'
			));
		}
		else
		{
			$table->addCell(5000)->addText("");
		}*/
		$table->addCell(5000)->addText("");
		$textrun = $table->addCell(5000)->addTextRun();
		$textrun->addText("");
		$textrun->addTextBreak(4);
		$table->addRow();
		
		$table->addCell(5000)->addText($item_info->penerima, $fontStyle, array('align' => 'center'));
		$table->addCell(5000)->addText($item_info->iden_nama, $fontStyle, array('align' => 'center'));
		
		$section->addTextBreak(2);
		
		$styleTable = array('borderSize' => 0, 'borderColor' => 'ffffff', 'cellMargin' => 0,  'cellSpacing' => 0);
		
		/*$table = $section->addTable($styleTable);
		$table->addRow();
		$table->addCell(1000)->addText(htmlspecialchars("*"));
		$table->addCell(9000)->addText(htmlspecialchars("Diisi oleh petugas berdasarkan nomor registrasi permohonan Informasi Publik"));
		$table->addRow();
		$table->addCell(1000)->addText(htmlspecialchars("**"));
		$table->addCell(9000)->addText(htmlspecialchars("Pilih salah satu dengan memberi tanda"));
		$table->addRow();
		$table->addCell(1000)->addText(htmlspecialchars("***"));
		$table->addCell(9000)->addText(htmlspecialchars("Coret yang tidak perlu"));*/
		
		
		
		
		
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename="Formulir_keberatan.docx"');

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('php://output');
		
	}
	
	public function print_ppid_word7($item_id = -1)
	{
		
		$item_id = (int)$item_id;
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		//$data['item_info'] = $item_info;
		
		$ppid_info = $this->Ticket->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		
		
		
		//$this->load->helper(array('dompdf', 'file'));
		//$html = $this->load->view('ppid/print_ppid_form5_word', $data, TRUE);
		
		
		
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$phpWord->setDefaultFontSize(11);
		$phpWord->setDefaultFontName('Arial');
		$phpWord->setDefaultParagraphStyle(
			array(
				'align'      => 'both',
				'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6),
				'spacing'    => 1.15,
				)
			);
	
		$section = $phpWord->addSection(array(
			'headerHeight'=> 5,
			'footerHeight' => 5,
			'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(21),
			'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(33),
		));
		$header = $section->addHeader();
		$header->firstPage();
		$header->addImage('../public/assets/images/kop1.png', 
			array(
				//'width' => 550, 
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
				'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(15)),
				//'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.1336)),
				'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-1.5)),
				'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.5)),
				'wrappingStyle' => 'infront'
			));
			
		$footer = $section->addFooter();
		$footer->firstPage();
		$footer->addImage('../public/assets/images/footer1.png', 
			array(
				
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
				'width' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(17)),
				'height' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(5)),
				'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
				'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-2)),
				'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-4.5)),
				'wrappingStyle' => 'infront'
			));
		
		$section->addTextBreak(4);
		
		$section->addText(get_city_location().", ".convert_date3($ppid_info->keberatan_tgl), array(), array(
			'align' => \PhpOffice\PhpWord\SimpleType\Jc::END,
		));
		
		$phpWord->addParagraphStyle(
			'rightTab',
			array('tabs' => array(new \PhpOffice\PhpWord\Style\Tab('left', 1500)))
		);
		$section->addText(htmlspecialchars("Nomor\t: ".$ppid_info->keberatan_no), null, 'rightTab');
		$section->addText(htmlspecialchars("Lampiran\t: ".$ppid_info->keberatan_lampiran), null, 'rightTab');
		$section->addText(htmlspecialchars("Perihal\t: Tanggapan Permohonan Informasi Publik"), null, 'rightTab');
		
		$section->addTextBreak(1);
		$section->addText('Yth.');
		$section->addText('Sdr/i '.$item_info->iden_nama);
		$section->addText('di tempat');
		
		$section->addTextBreak(1);
		
		$section->addText("Sehubungan dengan permohonan informasi Saudara melalui Pejabat Pengelola Informasi dan Dokumentasi (PPID) Badan POM perihal ".$ppid_info->keberatan_perihal.", bersama ini kami sampaikan hal-hal sebagai berikut:");
		
		$predefinedMultilevelStyle = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER);
		
		$section->addListItem($ppid_info->keberatan_isi_surat, 0, null, $predefinedMultilevelStyle);
		
		
		
		$section->addTextBreak(1);
		$section->addText("Demikian kami sampaikan, atas perhatiannya diucapkan terima kasih.");
		$section->addTextBreak(1);
		
		$phpWord->addParagraphStyle(
			'tab2',
			array('tabs' => array(new \PhpOffice\PhpWord\Style\Tab('left', 5500)))
		);
		$section->addText(htmlspecialchars("\tSekretaris Utama"), null, 'tab2');
		$section->addTextBreak(3);
		$section->addText(htmlspecialchars("\t ".$ppid_info->keberatan_nama_pejabat), null, 'tab2');
		
		
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename="Tanggapan_Keberatan.docx"');

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('php://output');
	}
	
	public function test_word()
	{
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$section = $phpWord->addSection();
		\PhpOffice\PhpWord\Shared\Html::addHtml($section, 'test');

		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename="test.docx"');

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('php://output');
	}
	
	public function get_attachments() //mode 0 = pertanyaan, 1 = jawaban, 2 = replies
	{
		$item_id = $this->input->get('ticketid');
		$mode = $this->input->get('mode');
		$files = $this->Ticket->get_attachments($item_id, $mode);
		//print_r($files->result());
		$files_array = array();
		
		foreach($files->result() as $f)
		{
			$files_array[] = array(
				'att_id' => $f->att_id,
				'name' => $f->real_name,
				'size' => $f->size,
				'url' => site_url('downloads/download_attachment_ticket?att_id='.$f->att_id.'&trackid='.$f->ticket_id),
				'mode' => $f->mode
			);
		}
		
		echo json_encode($files_array);
		
		
	}
}
?>
