<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Dashboard_unit extends Secure_Controller 
{
	function __construct()
	{
		parent::__construct(NULL, NULL, 'home');
		
	}

	public function index()
	{
	}
	
	//layanan
	public function layanan_all()
	{
		$open = $this->Ajaxc->layanan_open($this->session->dashboard_date1, $this->session->dashboard_date2);
		$closed = $this->Ajaxc->layanan_closed($this->session->dashboard_date1, $this->session->dashboard_date2);
		$replied = $this->Ajaxc->layanan_replied($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode(
			array(
				'open' => $open,
				'closed' => $closed,
				'replied' => $replied,
				'total' => $open + $closed + $replied
			)
		);
	}
	
	public function layanan_total()
	{		
		
		$cnt = $this->Ajaxc->layanan_total($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_open()
	{		
		
		$cnt = $this->Ajaxc->layanan_open($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_closed()
	{		
		
		$cnt = $this->Ajaxc->layanan_closed($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_replied()
	{		
		
		$cnt = $this->Ajaxc->layanan_replied($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	//layanan saya
	
	public function layanan_saya()
	{
		$open = $this->Ajaxc->layanan_saya_open($this->session->dashboard_date1, $this->session->dashboard_date2);
		$closed = $this->Ajaxc->layanan_saya_closed($this->session->dashboard_date1, $this->session->dashboard_date2);
		//$replied = $this->Ajaxc->layanan_saya_replied($this->session->dashboard_date1, $this->session->dashboard_date2);
		
		
		echo json_encode(
			array(
				'open' => $open,
				'closed' => $closed,
				//'replied' => $replied
			)
		);
		
	}
	
	public function layanan_saya_open()
	{		
		
		$cnt = $this->Ajaxc->layanan_saya_open($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_saya_closed()
	{		
		
		$cnt = $this->Ajaxc->layanan_saya_closed($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_saya_total()
	{		
		
		$cnt = $this->Ajaxc->layanan_saya_total($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	//rujukan
	public function rujukan_masuk()
	{		
		$cnt = $this->Ajaxc->rujukan_masuk($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function rujukan_masuk_blm_dijawab()
	{		
		$cnt = $this->Ajaxc->rujukan_masuk_blm_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function rujukan_masuk_sudah_dijawab()
	{		
		$cnt = $this->Ajaxc->rujukan_masuk_sudah_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function rujukan_masuk_chart()
	{
		$blm_jawab = $this->Ajaxc->rujukan_masuk_blm_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		$sdh_jawab = $this->Ajaxc->rujukan_masuk_sudah_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		
		echo json_encode(
			array(
				'blm_jawab' => $blm_jawab,
				'sdh_jawab' => $sdh_jawab,
				
			)
		);
		
	}
	
	public function rujukan_keluar_chart()
	{
		$blm_jawab = $this->Ajaxc->rujukan_keluar_blm_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		$sdh_jawab = $this->Ajaxc->rujukan_keluar_sudah_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		
		echo json_encode(
			array(
				'blm_jawab' => $blm_jawab,
				'sdh_jawab' => $sdh_jawab,
				
			)
		);
		
	}
	
	public function rujukan_keluar()
	{		
		$cnt = $this->Ajaxc->rujukan_keluar($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function rujukan_keluar_blm_dijawab()
	{		
		$cnt = $this->Ajaxc->rujukan_keluar_blm_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function rujukan_keluar_sudah_dijawab()
	{		
		$cnt = $this->Ajaxc->rujukan_keluar_sudah_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	
	//footer
	public function rujukan_keluar_replied()
	{		
		$cnt = $this->Ajaxc->rujukan_keluar_replied($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function rujukan_masuk_not_answered()
	{		
		$dateStartFilter = date('Y-01-01');
		$dateEndFilter = date('Y-m-d');
		//$cnt = $this->Ajaxc->rujukan_masuk_not_answered($this->session->dashboard_date1, $this->session->dashboard_date2);
		$cnt = $this->Ajaxc->rujukan_masuk_not_answered($dateStartFilter, $dateEndFilter);
		echo json_encode($cnt);
	}
	
	public function rujukan_masuk_not_closed()
	{		
		$cnt = $this->Ajaxc->rujukan_masuk_not_closed($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function belum_tl()
	{
		$red = $this->Ajaxc->belum_tl_red($this->session->dashboard_date1, $this->session->dashboard_date2);
		$orange = $this->Ajaxc->belum_tl_orange($this->session->dashboard_date1, $this->session->dashboard_date2);
		$black = $this->Ajaxc->belum_tl_black($this->session->dashboard_date1, $this->session->dashboard_date2);
		$green = $this->Ajaxc->belum_tl_green($this->session->dashboard_date1, $this->session->dashboard_date2);
		
		echo json_encode(
			array(
				'red' => $red,
				'orange' => $orange,
				'black' => $black,
				'green' => $green
			)
		);
		
	}
}
?>