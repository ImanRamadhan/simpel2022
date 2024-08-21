<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Lapsingv2 extends Secure_Controller
{
	function __construct()
	{
		parent::__construct('lapsingv2');
	}

	public function index()
	{
		redirect('lapsingv2/lapsing');
	}

	public function setup_search(&$data)
	{
		$cities = $this->City->get_cities();
		$cities_array = array(
			'ALL' => 'ALL',
			'PUSAT' => 'PUSAT',
			'CC' => 'CC',
			'UNIT_TEKNIS' => 'UNIT TEKNIS',
			'BALAI' => 'BALAI',
			'PUSAT_BALAI' => 'PUSAT + BALAI',
			'PUSAT_CC' => 'PUSAT + CC',
			'PUSAT_UNIT_TEKNIS' => 'PUSAT + UNIT_TEKNIS',
			'PUSAT_CC_BALAI' => 'PUSAT + CC + BALAI',
			'PUSAT_CC_UNIT_TEKNIS' => 'PUSAT + CC + UNIT TEKNIS',
			'PUSAT_CC_UNIT_TEKNIS_BALAI' => 'PUSAT + CC + UNIT TEKNIS + BALAI'
		);


		foreach ($cities->result() as $city) {
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
		$categories_array = array('ALL' => 'ALL');
		foreach ($categories->result() as $cat) {
			$categories_array[$cat->id] = $cat->desc;
		}
		$data['categories'] = $categories_array;

		$direktorat = $this->Dept->get_direktorat('');
		$direktorat_array = array('ALL' => 'ALL');
		foreach ($direktorat->result() as $dir) {
			$direktorat_array[$dir->id] = $dir->name;
		}
		$data['direktorat'] = $direktorat_array;

		$gender_array =  array(
			'ALL' => 'ALL',
			'L' => 'LAKI-LAKI',
			'P' => 'PEREMPUAN'
		);
		$data['genders'] = $gender_array;
		$data['jenis'] = 'ALL';
	}

	public function lapsing()
	{
		$data['title'] = 'Lapsing';
		$data['filters'] = array();
		$data['status_filter'] = '';
		$data['lapsing_type'] = "LAPSING";
		$data['breadcrumb'] = "LAPSING";

		$this->setup_search($data);

		$data['data_lapsing'] = '';

		$this->load->view('lapsingv2/form_lapsing', $data);
	}

	public function lapsing_komoditas()
	{
		$data['title'] = 'Laporan Singkat Per Komoditas';
		$data['filters'] = array();
		$data['status_filter'] = '';
		$data['lapsing_type'] = "KOMODITAS";
		$data['kategori'] = '';
		$data['kota'] = '';
		$data['breadcrumb'] = "LAPSING KOMODITAS";

		$this->setup_search($data);

		$this->load->view('lapsingv2/form_lapsing_komoditas', $data);
	}

	public function lapsing_sp4n()
	{
		$data['title'] = 'Laporan Singkat SP4N';
		$data['filters'] = array();
		$data['status_filter'] = '';
		$data['lapsing_type'] = "SP4N";

		$this->setup_search($data);

		$this->load->view('lapsing/form_lapsing', $data);
	}

	public function lapsing_rujukan_masuk()
	{
		$data['title'] = 'Laporan Singkat Rujukan Masuk';
		$data['filters'] = array();
		$data['status_filter'] = '';
		$data['lapsing_type'] = "LAPSING_RUJUKAN_MASUK";
		$data['breadcrumb'] = "LAPSING RUJUKAN MASUK";

		$this->setup_search($data);

		$this->load->view('lapsingv2/form_lapsing', $data);
	}

	public function get_direktorat($kota = '')
	{

		$kla = $this->xss_clean($kota);
		$direktorat = $this->Dept->get_direktorat($kla)->result();

		echo json_encode($direktorat);
	}

	public function lapsing_rujukan_keluar()
	{
		$data['title'] = 'Laporan Singkat Rujukan Keluar';
		$data['filters'] = array();
		$data['status_filter'] = '';
		$data['lapsing_type'] = "LAPSING_RUJUKAN_KELUAR";
		$data['breadcrumb'] = "LAPSING RUJUKAN KELUAR";

		$this->setup_search($data);

		$this->load->view('lapsingv2/form_lapsing', $data);
	}

	public function lapsing_ppid()
	{
		$data['title'] = 'Laporan Singkat PPID';
		$data['filters'] = array();
		$data['status_filter'] = '';
		$data['lapsing_type'] = "PPID";

		$this->setup_search($data);

		$this->load->view('lapsingv2/form_lapsing_ppid', $data);
	}

	public function lapsing_yanblik()
	{
		$data['title'] = 'Laporan Singkat Yanblik';
		$data['filters'] = array();
		$data['status_filter'] = '';
		$data['lapsing_type'] = "YANBLIK";

		$this->setup_search($data);

		$this->load->view('lapsing/form_lapsing_yanblik', $data);
	}

	public function lapsing_gender()
	{
		$data['title'] = 'Laporan Singkat Gender';
		$data['filters'] = array();
		$data['status_filter'] = '';
		$data['lapsing_type'] = "GENDER";

		$this->setup_search($data);

		$this->load->view('lapsing/form_lapsing', $data);
	}

	public function lapsing_data()
	{
		$formType  		= $this->input->post('formType');
		$data['filters'] = array();
		$data['status_filter'] = '';
		$data['title'] = $formType;
		$data['breadcrumb'] = $formType;

		$this->setup_search($data);


		$inputTgl1  	= $this->input->post('tgl1');
		$inputTgl2 		= $this->input->post('tgl2');
		$inputJenis		= $this->input->post('jenis');
		$inputdirektorat  	= $this->input->post('direktorat');

		$data['lapsing_type'] = $formType;

		$inputKota 		= $this->input->post('kota');

		$direktorat = $this->Dept->get_direktorat($inputKota);
		$direktorat_array = array('ALL' => 'ALL');
		foreach ($direktorat->result() as $dir) {
			$direktorat_array[$dir->id] = $dir->name;
		}
		$data['direktorat'] = $direktorat_array;

		if (empty($inputKota))
			$inputKota		= $this->session->city;


		$inputTgl1_ = convert_date1($inputTgl1);
		$inputTgl2_ = convert_date1($inputTgl2);

		$sheet = 1;
		$kategori = "";
		$jenis = $inputJenis;
		$gender = "";

		$data['products'] = $this->Report->get_products();
		$data['profesi'] = $this->Report->get_profesi();
		$raw_data = $this->Report->get_data_lapsing($sheet, $formType, $inputTgl1_, $inputTgl2_, $inputKota, $kategori, $jenis, $gender, $inputdirektorat);

		$data['data_lapsing'] = $raw_data;
		$data['tgl1'] = $inputTgl1;
		$data['tgl2'] = $inputTgl2;
		$data['kota'] = $inputKota;
		$data['jenis'] = $inputJenis;

		$this->load->view('lapsingv2/form_lapsing', $data);
	}

	public function lapsing_komoditas_data()
	{

		$data['filters'] = array();
		$data['status_filter'] = '';
		$data['title'] = 'Lapsing Per Komoditas';

		$this->setup_search($data);

		$formType  		= $this->input->post('formType');
		$inputTgl1  	= $this->input->post('tgl1');
		$inputTgl2 		= $this->input->post('tgl2');

		$data['lapsing_type'] = $formType;

		$inputKota 		= $this->input->post('kota');

		if (empty($inputKota))
			$inputKota		= $this->session->city;

		$data['kota'] = $inputKota;

		$inputTgl1_ = convert_date1($inputTgl1);
		$inputTgl2_ = convert_date1($inputTgl2);

		$inputDirektorat = $this->input->post('inputDirektorat');

		$sheet = 1;
		$kategori = $this->input->post('kategori');
		$data['kategori'] = $kategori;
		$jenis = "";
		$gender = "";

		$data['products'] = $this->Report->get_products();
		$data['profesi'] = $this->Report->get_profesi();

		$raw_data = $this->Report->get_data_lapsing($sheet, $formType, $inputTgl1_, $inputTgl2_, $inputKota, $kategori, $jenis, $gender, $inputDirektorat);

		$data['data_lapsing'] = $raw_data;

		$this->load->view('lapsingv2/form_lapsing_komoditas', $data);
	}

	public function lapsing_ppid_data()
	{

		$data['filters'] = array();
		$data['status_filter'] = '';
		$data['title'] = 'Lapsing';

		$this->setup_search($data);

		$formType  		= $this->input->post('formType');
		$inputTgl1  	= $this->input->post('tgl1');
		$inputTgl2 		= $this->input->post('tgl2');

		$data['lapsing_type'] = $formType;

		$inputKota 		= $this->input->post('kota');
		$inputdirektorat 		= $this->input->post('direktorat');

		if (empty($inputKota))
			$inputKota		= $this->session->city;

		if (is_balai()) {
			$inputKota		= $this->session->city;
		}


		$inputTgl1_ = convert_date1($inputTgl1);
		$inputTgl2_ = convert_date1($inputTgl2);

		$sheet = 1;
		$kategori = "";
		$jenis = "";
		$gender = "";

		//$data['products'] = $this->Report->get_products();
		//$data['profesi'] = $this->Report->get_profesi();
		$raw_data = $this->Report->get_data_lapsing_ppid($sheet, $formType, $inputTgl1_, $inputTgl2_, $inputKota, $inputdirektorat);

		$data['data_lapsing'] = $raw_data;
		$data['tgl1'] = $inputTgl1;
		$data['tgl2'] = $inputTgl2;
		$data['kota'] = $inputKota;

		$this->load->view('lapsingv2/form_lapsing_ppid', $data);
	}


	public function lapsing_data_()
	{
		$formType  		= $this->input->post('formType');
		$inputTgl1  	= $this->input->post('tgl1');
		$inputTgl2 		= $this->input->post('tgl2');


		$inputKota 		= $this->input->post('kota');

		if (empty($inputKota))
			$inputKota		= $this->session->city;




		$inputType 		= $this->input->post('type');
		$inputKategori 	= $this->input->post('kategori');

		if (($formType == 'SP4N') || ($formType == 'PPID')) {
			$inputJenis 	= $formType;
		} else {
			$inputJenis 	= '';
		}

		$inputGender 	= $this->input->post('gender');

		$array_output_data  	= array();
		$report_output       	= array();

		$total_data_    = 0;

		$raw_data_lapsing = null;
		if ($inputType == '1') {
			$total_data_    			= $this->Report->get_total_data_(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
			$raw_data_lapsing          	= $this->Report->get_data_kelompok_jenis_pengaduan(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
		} else if ($inputType == '2') {
			$total_data_    			= $this->Report->get_total_data_(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
			$raw_data_lapsing          	= $this->Report->get_data_kelompok_mekanisme_menjawab(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
		} else if ($inputType == '3') {
			$total_data_    			= $this->Report->get_total_data_(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
			$raw_data_lapsing          	= $this->Report->get_data_jenis_profesi_pengadu(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
		} else if ($inputType == '4') {
			$total_data_    			= $this->Report->get_total_data_(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
			$raw_data_lapsing          	= $this->Report->get_data_kelompok_informasi_produk(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
		} else if ($inputType == '5') {
			$total_data_    			= $this->Report->get_total_data_per_klasifikasi(0, $formType, 'Farmakologi', $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
			$raw_data_lapsing          	= $this->Report->get_data_kelompok_farmakologi(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
		} else if ($inputType == '6') {
			$total_data_    			= $this->Report->get_total_data_per_klasifikasi(0, $formType, 'Mutu', $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
			$raw_data_lapsing          = $this->Report->get_data_kelompok_mutu(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
		} else if ($inputType == '7') {
			$total_data_    			= $this->Report->get_total_data_per_klasifikasi(0, $formType, 'Legalitas', $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
			$raw_data_lapsing          = $this->Report->get_data_kelompok_legalitas(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
		} else if ($inputType == '8') {
			$total_data_    			= $this->Report->get_total_data_per_klasifikasi(0, $formType, 'Penandaan', $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
			$raw_data_lapsing          = $this->Report->get_data_kelompok_penandaan(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
		} else if ($inputType == '9') {
			$total_data_    			= $this->Report->get_total_data_per_klasifikasi(0, $formType, 'Informasi lain ttg Produk', $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
			$raw_data_lapsing          = $this->Report->get_data_kelompok_info_lain_produk(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
		} else if ($inputType == '10') {
			$total_data_    			= $this->Report->get_total_data_per_klasifikasi(0, $formType, 'Info Umum', $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
			$raw_data_lapsing          = $this->Report->get_data_kelompok_info_umum(0, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
		}

		$total     = $total_data_['0']['total'];

		$output_header                  = array();
		$output_header[0]['name']       = ' ';
		$output_header[0]['background'] = '#BBD38B';

		$output_header[1]['name']       = "JML INFORMASI";
		$output_header[1]['background'] = '#BBD38B';

		$output_header[2]['name']       = "%";
		$output_header[2]['background'] = '#BBD38B';

		$output_header[3]['name']       = "JML PENGADUAN";
		$output_header[3]['background'] = '#BBD38B';

		$output_header[4]['name']       = "%";
		$output_header[4]['background'] = '#BBD38B';

		$output_header[5]['name']       = "JML TOTAL";
		$output_header[5]['background'] = '#BBD38B';

		$output_header[6]['name']       = "%";
		$output_header[6]['background'] = '#BBD38B';

		$tot_jml_info			= 0;
		$tot_persen_info		= 0;
		$tot_jml_pengaduan		= 0;
		$tot_persen_pengaduan 	= 0;
		$tot_jml_total			= 0;
		$tot_persen_jml_total	= 0;

		for ($i = 0; $i < count($raw_data_lapsing); $i++) {

			$cnt_p  = ($raw_data_lapsing[$i]['cnt_p'] == '' ? 0 : $raw_data_lapsing[$i]['cnt_p']);
			$cnt_i  = ($raw_data_lapsing[$i]['cnt_i'] == '' ? 0 : $raw_data_lapsing[$i]['cnt_i']);

			$cnt    = $cnt_p + $cnt_i;
			if ($total > 0) {
				$persen_p   = round($cnt_p / intval($total) * 100, 2);
				$persen_i   = round($cnt_i / intval($total) * 100, 2);
				$persen     = round($cnt / intval($total) * 100, 2);
			} else {
				$persen_p = 0;
				$persen_i = 0;
				$persen = 0;
			}

			$data_out = array();
			array_push($data_out, $raw_data_lapsing[$i]['name']);
			array_push($data_out, $cnt_i);
			array_push($data_out, $persen_i);
			array_push($data_out, $cnt_p);
			array_push($data_out, $persen_p);
			array_push($data_out, $cnt);
			array_push($data_out, $persen);

			array_push($array_output_data, $data_out);

			$tot_jml_info			= $tot_jml_info + $cnt_i;
			$tot_persen_info		= $tot_persen_info + $persen_i;
			$tot_jml_pengaduan		= $tot_jml_pengaduan + $cnt_p;
			$tot_persen_pengaduan 	= $tot_persen_pengaduan + $persen_p;
			$tot_jml_total			= $tot_jml_total + $cnt;
			$tot_persen_jml_total	= $tot_persen_jml_total + $persen;
		}

		$data_out_total 		= array();
		$array_output_jumlah	= array();

		if ($inputType >= 1) {
			array_push($data_out_total, "JUMLAH");
			array_push($data_out_total, $tot_jml_info);
			array_push($data_out_total, round($tot_persen_info));
			array_push($data_out_total, $tot_jml_pengaduan);
			array_push($data_out_total, round($tot_persen_pengaduan));
			array_push($data_out_total, $tot_jml_total);
			array_push($data_out_total, round($tot_persen_jml_total));
			array_push($array_output_jumlah, $data_out_total);
		}


		$report_output[0]['header'] 	= $output_header;
		$report_output[1]['data'] 		= $array_output_data;
		$report_output[2]['jumlah'] 	= $array_output_jumlah;

		echo json_encode($report_output, JSON_NUMERIC_CHECK);
	}
}
