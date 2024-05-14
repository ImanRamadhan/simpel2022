<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Databases extends Secure_Controller 
{
    function __construct()
	{
		parent::__construct('databases');
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
			'ALL' => 'ALL',
			'SP4N' => 'SP4N',
			'PPID' => 'PPID'
		);
		
		$data['statusRujukan'] = array(
			'ALL'	=> 'ALL',
			'0'	=> 'Belum di-TL',
			'1'	=> 'Sudah di-TL'
		);

		$categories = $this->Ticket->get_categories();
		$categories_array = array('ALL' => 'ALL');
		foreach($categories->result() as $cat)
		{
			$categories_array[$cat->id] = $cat->desc;
		}
		$data['categories'] = $categories_array;
	}
    
    public function index()
	{
        $data['title'] = 'Database Layanan';
        $data['filters'] = array(
			);
        $data['status_filter'] = '';
        
		$this->setup_search($data);
		
		$data['layanan'] = array();
		
		$this->load->view('databases/form_databases', $data);
	}

	public function database_data(){
		//$inputTgl1  		= date("Y-m-d", strtotime($this->input->post('tgl1')));
		//$inputTgl2 			= date("Y-m-d", strtotime($this->input->post('tgl2')));
		$inputTgl1  		= convert_date1($this->input->post('tgl1'));
		$inputTgl2 			= convert_date1($this->input->post('tgl2'));
		
		if($this->session->city == 'PUSAT')
			$inputKota 			= $this->input->post('kota');
		else
			$inputKota 			= $this->session->city;
		
		$inputDatasource 	= $this->input->post('datasource');
		$inputKategori 		= $this->input->post('kategori');
		$inputLength 			= $this->input->post('length');
		$inputStart 			= $this->input->post('start');
		$inputSearch 			= $this->input->post('search');

		$data['layanan']	= $this->DatabaseM->get_data_pengaduan_konsumen($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, $inputLength, $inputStart, $inputSearch);
		
		//print_r($data['layanan']);
		
		$this->setup_search($data);
		
		$data['title'] = 'Database Pengaduan Konsumen';
		$this->load->view('databases/form_databases', $data);

		//echo json_encode($data_pengaduan_konsumen, JSON_NUMERIC_CHECK);
	}

	public function rujukan()
	{
        $data['title'] = 'Database Rujukan';
        $data['filters'] = array(
			);
        $data['status_filter'] = '';
		$data['layanan'] = array();
        
		$this->setup_search($data);
		
		$this->load->view('databases/form_databases_rujukan', $data);
	}

	public function database_rujukan_data(){
		//$inputTgl1  		= date("Y-m-d", strtotime($this->input->post('tgl1')));
		//$inputTgl2 			= date("Y-m-d", strtotime($this->input->post('tgl2')));
		$inputTgl1  		= convert_date1($this->input->post('tgl1'));
		$inputTgl2 			= convert_date1($this->input->post('tgl2'));
		
		if($this->session->city == 'PUSAT')
			$inputKota 			= $this->input->post('kota');
		else
			$inputKota 			= $this->session->city;
		
		$inputKategori 		= $this->input->post('kategori');
		$inputDatasource 	= $this->input->post('datasource');
		$inputStatusRujukan = $this->input->post('statusRujukan');
		
		$data_pengaduan_konsumen	= $this->DatabaseM->get_data_rujukan($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, $inputStatusRujukan);
		$output_basic = $data_pengaduan_konsumen;

		for($i = 0; $i <count($data_pengaduan_konsumen); $i++){
			$output_basic[$i]['var_kalimat_rujukan'] = '';
			$output_basic[$i]['var_kalimat_rujukan2'] = '';
			$output_basic[$i]['var_kalimat_rujukan3'] = '';
			$output_basic[$i]['var_kalimat_rujukan4'] = '';
			$output_basic[$i]['var_kalimat_rujukan5'] = '';
			//$output_basic[$i]['var_kalimat_rujukan6'] = '';
			//$output_basic[$i]['var_kalimat_rujukan7'] = '';
			
			if($data_pengaduan_konsumen[$i]['direktorat'] != '0'){
				$master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat']);
				$info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat'], $data_pengaduan_konsumen[$i]['id']);

				$kalimat_rujukan = $master_direktorat[0]['names'].'  <br />
   ';
				for($x =0; $x< count($info_desk_reply); $x++){
					$kalimat_rujukan .= '<b>'. $info_desk_reply[$x]['dt'].' ('.$info_desk_reply[$x]['name'].') </b> <br />
   ';
					$kalimat_rujukan .= $info_desk_reply[$x]['message'].'  <br /> <br /> 
   ';
				}
				$output_basic[$i]['var_kalimat_rujukan'] = $kalimat_rujukan;
			}

			if($data_pengaduan_konsumen[$i]['direktorat2'] != '0'){
				$master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat2']);
				
				$info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat2'], $data_pengaduan_konsumen[$i]['id']);

				$kalimat_rujukan = $master_direktorat[0]['names'].'  <br />
   ';
				for($x =0; $x< count($info_desk_reply); $x++){
					$kalimat_rujukan .= '<b>'. $info_desk_reply[$x]['dt'].' ('.$info_desk_reply[$x]['name'].') </b> <br />
   ';
					$kalimat_rujukan .= $info_desk_reply[$x]['message'].'  <br /> <br /> 
   ';
				}
				$output_basic[$i]['var_kalimat_rujukan2'] = $kalimat_rujukan;
			}

			if($data_pengaduan_konsumen[$i]['direktorat3'] != '0'){
				$master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat3']);
				
				$info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat3'], $data_pengaduan_konsumen[$i]['id']);

				$kalimat_rujukan = $master_direktorat[0]['names'].'  <br />
   ';
				for($x =0; $x< count($info_desk_reply); $x++){
					$kalimat_rujukan .= '<b>'. $info_desk_reply[$x]['dt'].' ('.$info_desk_reply[$x]['name'].') </b> <br />
   ';
					$kalimat_rujukan .= $info_desk_reply[$x]['message'].'  <br /> <br /> 
   ';
				}
				$output_basic[$i]['var_kalimat_rujukan3'] = $kalimat_rujukan;
			}

			if($data_pengaduan_konsumen[$i]['direktorat4'] != '0'){
				$master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat4']);
				
				$info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat4'], $data_pengaduan_konsumen[$i]['id']);

				$kalimat_rujukan = $master_direktorat[0]['names'].'  <br />
   ';
				for($x =0; $x< count($info_desk_reply); $x++){
					$kalimat_rujukan .= '<b>'. $info_desk_reply[$x]['dt'].' ('.$info_desk_reply[$x]['name'].') </b> <br />
   ';
					$kalimat_rujukan .= $info_desk_reply[$x]['message'].'  <br /> <br /> 
   ';
				}
				$output_basic[$i]['var_kalimat_rujukan4'] = $kalimat_rujukan;
			}

			if($data_pengaduan_konsumen[$i]['direktorat5'] != '0'){
				$master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat5']);
				
				$info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat5'], $data_pengaduan_konsumen[$i]['id']);

				$kalimat_rujukan = $master_direktorat[0]['names'].'  <br />
   ';
				for($x =0; $x< count($info_desk_reply); $x++){
					$kalimat_rujukan .= '<b>'. $info_desk_reply[$x]['dt'].' ('.$info_desk_reply[$x]['name'].') </b> <br />
   ';
					$kalimat_rujukan .= $info_desk_reply[$x]['message'].'  <br /> <br /> 
   ';
				}
				$output_basic[$i]['var_kalimat_rujukan5'] = $kalimat_rujukan;
			}
			
		}
		$data['layanan'] = $output_basic;
		//echo json_encode($output_basic, JSON_NUMERIC_CHECK);
		
		$this->setup_search($data);
		
		$data['title'] = 'Database Rujukan';
		$this->load->view('databases/form_databases_rujukan', $data);
	}

	public function resume()
	{
        $data['title'] = 'Resume Harian';
        $data['filters'] = array(
			);
        $data['status_filter'] = '';
        $data['layanan'] = array();
		$this->setup_search($data);
		
		$this->load->view('databases/form_databases_resume', $data);
	}

	public function resume_data(){
		//$inputTgl1  		= date("Y-m-d", strtotime($this->input->post('tgl1')));
		//$inputTgl2 			= date("Y-m-d", strtotime($this->input->post('tgl2')));
		$inputTgl1  		= convert_date1($this->input->post('tgl1'));
		$inputTgl2 			= convert_date1($this->input->post('tgl2'));
		
		if($this->session->city == 'PUSAT')
			$inputKota 			= $this->input->post('kota');
		else
			$inputKota 			= $this->session->city;
		
		$inputLength 			= $this->input->post('length');
		$inputStart 			= $this->input->post('start');
		$inputSearch 			= $this->input->post('search');

		$data_resume	= $this->DatabaseM->get_data_resume($inputTgl1, $inputTgl2, $inputKota, $inputLength, $inputStart, $inputSearch);
		

		//echo json_encode($data_resume, JSON_NUMERIC_CHECK);
		$data['layanan'] = $data_resume;
		$data['title'] = 'Resume Harian';
		$this->setup_search($data);
		$this->load->view('databases/form_databases_resume', $data);
		
	}
	
	public function monbalai()
	{
        $data['title'] = 'Monitoring Balai';
        $data['filters'] = array(
			);
        $data['status_filter'] = '';
		$data['layanan'] = array();
        
		$this->setup_search($data);
		
		$this->load->view('databases/form_monbalai', $data);
	}
	
	public function monbalai_data(){
		//$inputTgl1  		= date("Y-m-d", strtotime($this->input->post('tgl1')));
		//$inputTgl2 			= date("Y-m-d", strtotime($this->input->post('tgl2')));
		$inputTgl1  		= convert_date1($this->input->post('tgl1'));
		$inputTgl2 			= convert_date1($this->input->post('tgl2'));
		
		
		$records	= $this->DatabaseM->get_data_monbalai($inputTgl1, $inputTgl2);

		$data['layanan'] = $records;
		$data['title'] = 'Monitoring Balai';
		$this->setup_search($data);
		$this->load->view('databases/form_monbalai', $data);
		
	}
}