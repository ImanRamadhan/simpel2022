<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Graphs extends Secure_Controller 
{
	function __construct()
	{
		parent::__construct('graphs');
		$this->load->model('Grafik');
		
	}

	public function index()
	{
		redirect('graphs/layanan');
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
			//'PUSAT_CC_UNIT_TEKNIS_BALAI' => 'PUSAT + CC + UNIT TEKNIS + BALAI'
		);
		
		foreach($cities->result() as $city)
		{
			$cities_array[$city->nama_kota] = $city->nama_kota;
		}
        $data['cities'] = $cities_array;
		
		if($this->session->city != 'PUSAT')
			$data['cities'] = array(
				strtoupper($this->session->city) => strtoupper($this->session->city)
			);
        
        $graph_array = array(
            "1"     => "Grafik Jumlah layanan berdasarkan Jenis Produk",
            "2"     => "Grafik Jumlah layanan berdasarkan Jenis Pekerjaan",
            "3"     => "Grafik Jumlah layanan berdasarkan Mekanisme Menjawab",
            "4"     => "Grafik Jumlah layanan berdasarkan Rujukan Unit Teknis",
            //"5"     => "Grafik Jumlah layanan berdasarkan Respon Balik Rujukan",
            "6"     => "Grafik Jumlah layanan berdasarkan Jenis Layanan",
            "7"     => "Grafik Jumlah layanan berdasarkan Balai/Loka",
            "8"     => "Grafik Jumlah layanan berdasarkan Jenis Kelamin",
            //"9"     => "Grafik Jumlah layanan berdasarkan Waktu Rujukan"
			"9"     => "Grafik Jumlah layanan berdasarkan Unit Teknis",
			"11"     => "Grafik Rata-rata Waktu Layanan",
			//"12"     => "Grafik PPID Status Layanan"
        );
        $data['graphs'] = $graph_array;
	}
	
	
    public function layanan()
	{
        $data['title'] = 'Grafik Layanan';
        $data['filters'] = array(
			);
		$data['status_filter'] = '';
		$data['lapsing_type'] = "Grafik";	
		
		if(is_pusat())
		{
		
			$data['datasources'] = array(
				'' => 'ALL',
				'LAYANAN_SP4N' => 'Layanan Pengaduan dan Informasi (Layanan ULPK CC + SP4N LAPOR)',
				'LAYANAN' => 'Layanan ULPK CC',
				'SP4N' => 'SP4N LAPOR',
				'PPID' => 'PPID'
			);
		}
		else
		{
			$data['datasources'] = array(
				'' => 'ALL',
				'LAYANAN_SP4N' => 'Layanan Pengaduan dan Informasi (Layanan ULPK + SP4N LAPOR)',
				'LAYANAN' => 'Layanan ULPK',
				'SP4N' => 'SP4N LAPOR',
				'PPID' => 'PPID'
			);
		}

		$data['jenislayanan'] = array(
			'' => 'ALL',
			'P' => 'Pengaduan',
			'I' => 'Permintaan Informasi'
		);
		
		$data['products'] = get_products();
		

		$data['klasifikasi'] = get_klasifikasi();

		$data['subklasifikasi'] = array('' => '');
		$data['subkla'] = get_subklasifikasi_json();
		
		$this->setup_search($data);
		
		$this->load->view('graphs/form', $data);
	}
	
	
	
	

	public function JenisProduk()
	{
		//$inputTgl1  	= date("Y-m-d", strtotime($this->input->post('tgl1')));
		//$inputTgl2 		= date("Y-m-d", strtotime($this->input->post('tgl2')));
		$inputTgl1		= convert_date1($this->input->post('tgl1'));
		$inputTgl2		= convert_date1($this->input->post('tgl2'));
		$inputKota 		= $this->input->post('kota');
		
		$inputDir 		= $this->input->post('dir');
		$inputJenis			= $this->input->post('jenislayanan');
		$inputDataSource	= $this->input->post('datasource');
		$klasifikasi		= $this->input->post('klasifikasi');
		$subklasifikasi		= $this->input->post('subklasifikasi');
		$kategori			= $this->input->post('kategori');
		
		$filters = array(
			'info' 			=> $inputJenis,
			'jenis' 		=> $inputDataSource,
			'klasifikasi' 	=> $klasifikasi,
			'subklasifikasi' => $subklasifikasi,
			'dir' 			=> $inputDir,
			'kategori'		=> $kategori
		);
		
		$returns_data = $this->Grafik->getJenisProdukData($inputTgl1, $inputTgl2, $inputKota, $filters);
		
		$array_result = array();
		$array_output = array();
		
		
		//$array_result[0]['name'] = sprintf("Grafik Jumlah Layanan Berdasarkan Jenis Produk");
		$array_result[0]['name'] = "Jml Layanan";
		for($i = 0; $i< count($returns_data); $i++){
			$returns = array();
		
			$returns['name'] 	= $returns_data[$i]["name"];
			//$returns['color'] 	= $returns_data[$i]["color"];
			$returns['y'] 		= (int)$returns_data[$i]["cnt"];
			array_push($array_output, $returns);
		}
		
		$array_result[0]['data'] = $array_output;
	
		echo json_encode($array_result, JSON_NUMERIC_CHECK);
	}

	public function JenisPekerjaan(){
		//$inputTgl1  	= $this->input->post('tgl1');
		//$inputTgl2 		= $this->input->post('tgl2');
		
		$inputTgl1		= convert_date1($this->input->post('tgl1'));
		$inputTgl2		= convert_date1($this->input->post('tgl2'));
		
		$inputKota 		= $this->input->post('kota');
		$inputDir 		= $this->input->post('dir');
		$inputJenis			= $this->input->post('jenislayanan');
		$inputDataSource	= $this->input->post('datasource');
		$klasifikasi		= $this->input->post('klasifikasi');
		$subklasifikasi		= $this->input->post('subklasifikasi');
		$kategori			= $this->input->post('kategori');
		
		$filters = array(
			'info' 			=> $inputJenis,
			'jenis' 		=> $inputDataSource,
			'klasifikasi' 	=> $klasifikasi,
			'subklasifikasi' => $subklasifikasi,
			'dir' 			=> $inputDir,
			'kategori'		=> $kategori
		);
		
		$returns_data = $this->Grafik->getJenisPekerjaan($inputTgl1, $inputTgl2, $inputKota, $filters);
		
		$array_result = array();
		$array_output = array();
		$array_result[0]['data'] = array();

		$array_result[0]['name'] = "Grafik Jumlah Layanan Berdasarkan Jenis Pekerjaan";
		for($i = 0; $i< count($returns_data); $i++){
			$returns = array();
		
			$returns['name'] 	= $returns_data[$i]["name"];
			//$returns['color'] 	= $returns_data[$i]["color"];
			$returns['y'] 		= (int)$returns_data[$i]["cnt"];
			array_push($array_output, $returns);
			array_push($array_result[0]['data'], $returns_data[$i]["name"]);
		}
		
		$array_result[1]['data'] = $array_output;
	
		echo json_encode($array_result, JSON_NUMERIC_CHECK);
	}

	public function MekanismeMenjawab(){
		//$inputTgl1  	= date("Y-m-d", strtotime($this->input->post('tgl1')));
		//$inputTgl2 		= date("Y-m-d", strtotime($this->input->post('tgl2')));
		$inputTgl1		= convert_date1($this->input->post('tgl1'));
		$inputTgl2		= convert_date1($this->input->post('tgl2'));
		$inputKota 		= $this->input->post('kota');
		$inputDir 		= $this->input->post('dir');
		$inputJenis			= $this->input->post('jenislayanan');
		$inputDataSource	= $this->input->post('datasource');
		$klasifikasi		= $this->input->post('klasifikasi');
		$subklasifikasi		= $this->input->post('subklasifikasi');
		$kategori			= $this->input->post('kategori');
		
		$filters = array(
			'info' 			=> $inputJenis,
			'jenis' 		=> $inputDataSource,
			'klasifikasi' 	=> $klasifikasi,
			'subklasifikasi' => $subklasifikasi,
			'dir' 			=> $inputDir,
			'kategori'		=> $kategori
		);
		
		$returns_data = $this->Grafik->getMekanismeMenjawab($inputTgl1, $inputTgl2, $inputKota, $filters);
		
		$array_result = array();
		$array_output = array();
		$array_result[0]['data'] = array();

		$array_result[0]['name'] = "Grafik Jumlah Layanan Berdasarkan Mekanisme Menjawab";
		for($i = 0; $i< count($returns_data); $i++){
			$returns = array();
		
			$returns['name'] 	= $returns_data[$i]["name"];
			$returns['y'] 		= (int)$returns_data[$i]["cnt"];
			if($i == 0){
				$returns['sliced'] 			= true;
				$returns['selected'] 		= true;	
			}
			
			array_push($array_output, $returns);
			array_push($array_result[0]['data'], $returns_data[$i]["name"]);
		}
		
		$array_result[1]['data'] = $array_output;
	
		echo json_encode($array_result, JSON_NUMERIC_CHECK);
	}

	public function RujukanTeknis(){
		//$inputTgl1  	= date("Y-m-d", strtotime($this->input->post('tgl1')));
		//$inputTgl2 		= date("Y-m-d", strtotime($this->input->post('tgl2')));
		
		$inputTgl1		= convert_date1($this->input->post('tgl1'));
		$inputTgl2		= convert_date1($this->input->post('tgl2'));
		
		$inputKota 		= $this->input->post('kota');
		$inputDir 		= $this->input->post('dir');
		$inputJenis			= $this->input->post('jenislayanan');
		$inputDataSource	= $this->input->post('datasource');
		$klasifikasi		= $this->input->post('klasifikasi');
		$subklasifikasi		= $this->input->post('subklasifikasi');
		$kategori			= $this->input->post('kategori');
		
		$filters = array(
			'info' 			=> $inputJenis,
			'jenis' 		=> $inputDataSource,
			'klasifikasi' 	=> $klasifikasi,
			'subklasifikasi' => $subklasifikasi,
			'dir' 			=> $inputDir,
			'kategori'		=> $kategori
		);
		
		$returns_data = $this->Grafik->getRujukanTeknis($inputTgl1, $inputTgl2, $inputKota, $filters);
		
		$array_result = array();
		$array_output = array();
		$array_result[0]['data'] = array();

		$array_result[0]['name'] = "Grafik Jumlah Layanan Berdasarkan Rujukan Unit Teknis";
		for($i = 0; $i< count($returns_data); $i++){
			$returns = array();
			$returns['kota'] 	= $returns_data[$i]["kota"];
			$returns['name'] 	= $returns_data[$i]["name"];
			$returns['y'] 		= (int)$returns_data[$i]["cnt"];
			array_push($array_output, $returns);
			array_push($array_result[0]['data'], $returns_data[$i]["name2"]);
		}
		
		$array_result[1]['data'] = $array_output;
	
		echo json_encode($array_result, JSON_NUMERIC_CHECK);
	}
	
	public function UnitTeknis(){
		//$inputTgl1  	= date("Y-m-d", strtotime($this->input->post('tgl1')));
		//$inputTgl2 		= date("Y-m-d", strtotime($this->input->post('tgl2')));
		$inputTgl1		= convert_date1($this->input->post('tgl1'));
		$inputTgl2		= convert_date1($this->input->post('tgl2'));
		$inputKota 		= $this->input->post('kota');
		$inputDir 		= $this->input->post('dir');
		$inputJenis			= $this->input->post('jenislayanan');
		$inputDataSource	= $this->input->post('datasource');
		$klasifikasi		= $this->input->post('klasifikasi');
		$subklasifikasi		= $this->input->post('subklasifikasi');
		$kategori			= $this->input->post('kategori');
		
		$filters = array(
			'info' 			=> $inputJenis,
			'jenis' 		=> $inputDataSource,
			'klasifikasi' 	=> $klasifikasi,
			'subklasifikasi' => $subklasifikasi,
			'dir' 			=> $inputDir,
			'kategori'		=> $kategori
		);
		
		$returns_data = $this->Grafik->getUnitTeknis($inputTgl1, $inputTgl2, $inputKota);
		
		$array_result = array();
		$array_output = array();
		$array_result[0]['data'] = array();

		$array_result[0]['name'] = sprintf("Grafik Jumlah Layanan Berdasarkan Unit Teknis");
		for($i = 0; $i< count($returns_data); $i++){
			$returns = array();
			
			if($returns_data[$i]["name"] == 'PUSAT' || $returns_data[$i]["name"] == 'UNIT TEKNIS')
				continue;
		
			$returns['name'] 	= $returns_data[$i]["name"];
			$returns['y'] 		= (int)$returns_data[$i]["cnt"];
			array_push($array_output, $returns);
			array_push($array_result[0]['data'], $returns_data[$i]["name"]);
		}
		
		$array_result[1]['data'] = $array_output;
	
		echo json_encode($array_result, JSON_NUMERIC_CHECK);
	}
	
	public function rataanwaktulayanan(){
		//$inputTgl1  	= date("Y-m-d", strtotime($this->input->post('tgl1')));
		//$inputTgl2 		= date("Y-m-d", strtotime($this->input->post('tgl2')));
		//$inputTgl1			= convert_date1($this->input->post('tgl1'));
		//$inputTgl2			= convert_date1($this->input->post('tgl2'));
		$inputTgl1		= convert_date1($this->input->post('tgl1'));
		$inputTgl2		= convert_date1($this->input->post('tgl2'));
		$inputKota 			= $this->input->post('kota');
		$inputDir 		= $this->input->post('dir');
		$inputJenis			= $this->input->post('jenislayanan');
		$inputDataSource	= $this->input->post('datasource');
		$klasifikasi		= $this->input->post('klasifikasi');
		$subklasifikasi		= $this->input->post('subklasifikasi');
		$kategori			= $this->input->post('kategori');
		
		if(!is_pusat())
			$inputKota = $this->session->city;
		
		$filters = array(
			'info' 			=> $inputJenis,
			'jenis' 		=> $inputDataSource,
			'klasifikasi' 	=> $klasifikasi,
			'subklasifikasi' => $subklasifikasi,
			'dir' 			=> $inputDir,
			'kategori'		=> $kategori
		);
		
		
		$returns_data = $this->Grafik->getrataanwaktu($inputTgl1, $inputTgl2, $inputKota, $filters);
		//print_r($returns_data); 
		
		$array_result = array();
		$array_output = array();
		$array_result[0]['data'] = array();

		$array_result[0]['name'] = sprintf("Grafik - Rata-rata Waktu Layanan");
		for($i = 0; $i< count($returns_data); $i++){
			$returns = array();
			
			//if($returns_data[$i]["name"] == 'PUSAT' || $returns_data[$i]["name"] == 'UNIT TEKNIS')
			//	continue;
		
			$returns['name'] 	= $returns_data[$i]["name"];
			$returns['y'] 		= number_format($returns_data[$i]["cnt"],2);
			array_push($array_output, $returns);
			array_push($array_result[0]['data'], $returns_data[$i]["name"]);
		}
		
		$array_result[1]['data'] = $array_output;
		//print_r($array_result);
	
		echo json_encode($array_result, JSON_NUMERIC_CHECK);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	public function ResponBalikRujukan(){
		//$inputTgl1  	= date("Y-m-d", strtotime($this->input->post('tgl1')));
		//$inputTgl2 		= date("Y-m-d", strtotime($this->input->post('tgl2')));
		$inputTgl1		= convert_date1($this->input->post('tgl1'));
		$inputTgl2		= convert_date1($this->input->post('tgl2'));
		$inputKota 		= $this->input->post('kota');
		
		$array_output = array();

		$returns = array();
		$data_lt = $this->Grafik->getResponBalikRujukan_lt_3days($inputTgl1, $inputTgl2, $inputKota, FALSE);
		$returns['name'] 			= "<3 Hari Kerja";
		$returns['y'] 				= (int)$data_lt[0]["cnt"];
		$returns['sliced'] 			= true;
		$returns['selected'] 		= true;	
		array_push($array_output, $returns);

		$returns = array();
		$data_eq = $this->Grafik->getResponBalikRujukan_eq_3days($inputTgl1, $inputTgl2, $inputKota, FALSE);
		$returns['name'] 			= '=3 Hari Kerja';
		$returns['y'] 				= (int)$data_eq[0]["cnt"];
		array_push($array_output, $returns);

		$returns = array();
		$data_gt = $this->Grafik->getResponBalikRujukan_gt_3days($inputTgl1, $inputTgl2, $inputKota, FALSE);
		$returns['name'] 			= '>3 Hari Kerja';
		$returns['y'] 				= (int)$data_gt[0]["cnt"];
		array_push($array_output, $returns);


		$array_result = array();		
		$array_result[0]['data'] = array('>3 Hari Kerja','=3 Hari Kerja', '<3 Hari Kerja');
		
		$array_result[0]['name'] = "Grafik Jumlah Layanan Berdasarkan Respon Balik Rujukan";
		$array_result[1]['data'] = $array_output;
	
		echo json_encode($array_result, JSON_NUMERIC_CHECK);
	}

	public function JenisPengaduan(){
		//$inputTgl1  	= date("Y-m-d", strtotime($this->input->post('tgl1')));
		//$inputTgl2 		= date("Y-m-d", strtotime($this->input->post('tgl2')));
		$inputTgl1		= convert_date1($this->input->post('tgl1'));
		$inputTgl2		= convert_date1($this->input->post('tgl2'));
		$inputKota 		= $this->input->post('kota');
		
		$array_output = array();

		$returns = array();
		$data_info = $this->Grafik->getJenisPengaduan_minta_info($inputTgl1, $inputTgl2, $inputKota, FALSE);
		$returns['name'] 			= "Permintaan Informasi";
		$returns['y'] 				= (int)$data_info[0]["cnt"];
		$returns['sliced'] 			= true;
		$returns['selected'] 		= true;	
		array_push($array_output, $returns);

		$returns = array();
		$data_pengaduan = $this->Grafik->getJenisPengaduan_pengaduan($inputTgl1, $inputTgl2, $inputKota, FALSE);
		$returns['name'] 			= 'Pengaduan';
		$returns['y'] 				= (int)$data_pengaduan[0]["cnt"];
		array_push($array_output, $returns);

		$array_result = array();		
		$array_result[0]['data'] = array('Permintaan Informasi','Pengaduan');
		
		$array_result[0]['name'] = "Grafik Jumlah Layanan Berdasarkan Jenis Layanan";
		$array_result[1]['data'] = $array_output;
	
		echo json_encode($array_result, JSON_NUMERIC_CHECK);
	}

	public function Balai(){
		//$inputTgl1  	= date("Y-m-d", strtotime($this->input->post('tgl1')));
		//$inputTgl2 		= date("Y-m-d", strtotime($this->input->post('tgl2')));
		$inputTgl1		= convert_date1($this->input->post('tgl1'));
		$inputTgl2		= convert_date1($this->input->post('tgl2'));
		$inputKota 		= $this->input->post('kota');
		
		$returns_data = $this->Grafik->getBalai($inputTgl1, $inputTgl2, $inputKota);
		
		$array_result = array();
		$array_output = array();
		$array_result[0]['data'] = array();

		$array_result[0]['name'] = sprintf("Grafik Jumlah Layanan Berdasarkan Balai");
		for($i = 0; $i< count($returns_data); $i++){
			$returns = array();
			
			if($returns_data[$i]["name"] == 'PUSAT' || $returns_data[$i]["name"] == 'UNIT TEKNIS')
				continue;
		
			$returns['name'] 	= $returns_data[$i]["name"];
			$returns['y'] 		= (int)$returns_data[$i]["cnt"];
			array_push($array_output, $returns);
			array_push($array_result[0]['data'], $returns_data[$i]["name"]);
		}
		
		$array_result[1]['data'] = $array_output;
	
		echo json_encode($array_result, JSON_NUMERIC_CHECK);
	}

	public function JenisKelamin(){
		//$inputTgl1  	= date("Y-m-d", strtotime($this->input->post('tgl1')));
		//$inputTgl2 		= date("Y-m-d", strtotime($this->input->post('tgl2')));
		$inputTgl1		= convert_date1($this->input->post('tgl1'));
		$inputTgl2		= convert_date1($this->input->post('tgl2'));
		$inputKota 		= $this->input->post('kota');
		
		$array_output = array();
		$array_result[0]['data'] = array('Laki-laki','Perempuan');

		$returns = array();
		$data_laki = $this->Grafik->getJenisKelamin($inputTgl1, $inputTgl2, $inputKota, FALSE, 'L');
		$returns['name'] 			= "Laki-laki";
		$returns['y'] 				= (int)$data_laki[0]["cnt"];
		$returns['sliced'] 			= true;
		$returns['selected'] 		= true;	
		array_push($array_output, $returns);

		$returns = array();
		$data_perempuan = $this->Grafik->getJenisKelamin($inputTgl1, $inputTgl2, $inputKota, FALSE, 'P');
		$returns['name'] 			= 'Perempuan';
		$returns['y'] 				= (int)$data_perempuan[0]["cnt"];
		array_push($array_output, $returns);

		$returns = array();
		$data_other = $this->Grafik->getJenisKelamin($inputTgl1, $inputTgl2, $inputKota, FALSE, '');
		
		if((int)$data_other[0]["cnt"]>0){
			$returns['name'] 			= '-';
			$returns['y'] 				= (int)$data_other[0]["cnt"];
			array_push($array_output, $returns);
			array_push($array_result[0]['data'], '-');	
		}
		
		$array_result = array();		
		
		$array_result[0]['name'] = "Grafik Jumlah Layanan Berdasarkan Jenis Kelamin";
		
		$array_result[1]['data'] = $array_output;
	
		echo json_encode($array_result, JSON_NUMERIC_CHECK);
	}
	
	
	
	

	
	
	public function statusLayanan()
	{
		$inputTgl1		= convert_date1($this->input->post('tgl1'));
		$inputTgl2		= convert_date1($this->input->post('tgl2'));
		$inputKota 		= $this->input->post('kota');
		$inputjenislayanan		= $this->input->post('jenislayanan');
		$inputdatasource		= "";
		$inputklasifikasi_id	= $this->input->post('klasifikasi_id');
		$inputsubklasifikasi_id	= $this->input->post('subklasifikasi_id');
		
		$returns_data = $this->Grafik->getStatusLayanan($inputTgl1, $inputTgl2, $inputKota, $inputjenislayanan, $inputdatasource, $inputklasifikasi_id, $inputsubklasifikasi_id);
		
		$array_result = array();
		$array_output = array();
		
		
		//$array_result[0]['name'] = sprintf("Grafik Jumlah Layanan Berdasarkan Jenis Produk");
		$array_result[0]['name'] = "Jumlah Layanan";
		for($i = 0; $i< count($returns_data); $i++){
			$returns = array();
		
			$returns['name'] 	= $returns_data[$i]["keputusan"];
			//$returns['color'] 	= $returns_data[$i]["color"];
			$returns['y'] 		= (int)$returns_data[$i]["jum"];
			array_push($array_output, $returns);
		}
		
		$array_result[0]['data'] = $array_output;
	
		echo json_encode($array_result, JSON_NUMERIC_CHECK);
	}
}
?>