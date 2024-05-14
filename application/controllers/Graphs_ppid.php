<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Graphs_ppid extends Secure_Controller 
{
	function __construct()
	{
		parent::__construct('graphs');
		$this->load->model('Grafik');
		
	}

	public function index()
	{
		redirect('graphs_ppid/ppid');
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
			/*'PUSAT_BALAI' => 'PUSAT + BALAI',
			'PUSAT_CC' => 'PUSAT + CC',
			'PUSAT_UNIT_TEKNIS' => 'PUSAT + UNIT_TEKNIS',
			'PUSAT_CC_BALAI' => 'PUSAT + CC + BALAI',
			'PUSAT_CC_UNIT_TEKNIS' => 'PUSAT + CC + UNIT TEKNIS',*/
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
            "5"     => "Grafik Jumlah layanan berdasarkan Respon Balik Rujukan",
            "6"     => "Grafik Jumlah layanan berdasarkan Jenis Layanan",
            "7"     => "Grafik Jumlah layanan berdasarkan Balai",
            "8"     => "Grafik Jumlah layanan berdasarkan Jenis Kelamin",
            //"9"     => "Grafik Jumlah layanan berdasarkan Waktu Rujukan"
			
			/*"9"     => "Grafik Jumlah layanan berdasarkan Unit Teknis",
			"10"     => "Grafik Permintaan Informasi Publik",
			"11"     => "Grafik Rataan Waktu Layanan",
			"12"     => "Grafik PPID Status Layanan"*/
        );
        $data['graphs'] = $graph_array;
	}
	
	public function setup_search_ppid(&$data)
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
			"12"     => "Grafik PPID - Jumlah layanan berdasarkan Status Layanan",
			"11"     => "Grafik PPID - Rata-rata Waktu Penyelesaian",
            /*"1"     => "Grafik Jumlah layanan berdasarkan Jenis Produk",
            "2"     => "Grafik Jumlah layanan berdasarkan Jenis Pekerjaan",
            "3"     => "Grafik Jumlah layanan berdasarkan Mekanisme Menjawab",
            "4"     => "Grafik Jumlah layanan berdasarkan Rujukan Unit Teknis",
            "5"     => "Grafik Jumlah layanan berdasarkan Respon Balik Rujukan",
            "6"     => "Grafik Jumlah layanan berdasarkan Jenis Layanan",
            "7"     => "Grafik Jumlah layanan berdasarkan Balai",
            "8"     => "Grafik Jumlah layanan berdasarkan Jenis Kelamin",
            //"9"     => "Grafik Jumlah layanan berdasarkan Waktu Rujukan"
			
			/*"9"     => "Grafik Jumlah layanan berdasarkan Unit Teknis",
			"10"     => "Grafik Permintaan Informasi Publik",
			"11"     => "Grafik Rataan Waktu Layanan",
			"12"     => "Grafik PPID Status Layanan"*/
        );
        $data['graphs'] = $graph_array;
	}
	
	public function ppid()
	{
        $data['title'] = 'Grafik PPID';
        $data['filters'] = array(
			);
		$data['status_filter'] = '';
		$data['lapsing_type'] = "Grafik";	
		
		$data['datasources'] = array(
			//'' => '',
			//'SP4N' => 'SP4N',
			'PPID' => 'PPID'
		);

		$data['jenislayanan'] = array(
			'' => 'All',
			'P' => 'Permintaan Informasi',
			'K' => 'Pengajuan Keberatan'
			
		);

		$data['klasifikasi'] = get_klasifikasi();

		$data['subklasifikasi'] = array('' => '');
		$data['subkla'] = get_subklasifikasi_json();
		
		$this->setup_search_ppid($data);
		
		$this->load->view('graphs/form_ppid', $data);
	}
	
	public function rataanwaktulayananppid(){
		
		$inputTgl1 = $this->input->post('tgl1');
		$inputTgl2 = $this->input->post('tgl2');
		//$inputTgl1		= convert_date1($this->input->post('tgl1'));
		//$inputTgl2		= convert_date1($this->input->post('tgl2'));
		$inputKota 			= $this->input->post('kota');
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
		
		$returns_data = $this->Grafik->getrataanwaktuppid($inputTgl1, $inputTgl2, $inputKota, $filters);
		//print_r($returns_data); 
		
		$array_result = array();
		$array_output = array();
		$array_result[0]['data'] = array();

		$array_result[0]['name'] = sprintf("Grafik PPID - Rata-rata Waktu Layanan");
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
	
		echo json_encode($array_result, JSON_NUMERIC_CHECK);
	}
	
	
	public function statusTanggapan()
	{
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
		
		$returns_data = $this->Grafik->getStatusTanggapan($inputTgl1, $inputTgl2, $inputKota, $filters);
		
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