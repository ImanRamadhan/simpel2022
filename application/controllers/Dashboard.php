<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Dashboard extends Secure_Controller 
{
	function __construct()
	{
		parent::__construct(NULL, NULL, 'home');
	}

	public function index()
	{
	}
	
	//pusat
	public function layanan_pusat()
	{		
		$cnt = $this->Ajax->layanan_pusat($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_cc()
	{		
		$cnt = $this->Ajax->layanan_cc($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_balai()
	{		
		$cnt = $this->Ajax->layanan_balai($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_total()
	{		
		
		$cnt = $this->Ajax->layanan_total($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_all()
	{
		$pusat = $this->Ajax->layanan_pusat($this->session->dashboard_date1, $this->session->dashboard_date2);
		$cc = $this->Ajax->layanan_cc($this->session->dashboard_date1, $this->session->dashboard_date2);
		$balai = $this->Ajax->layanan_balai($this->session->dashboard_date1, $this->session->dashboard_date2);
		$total = $pusat + $cc + $balai;
		
		echo json_encode(
			array(
				'pusat' => $pusat,
				'cc' => $cc,
				'balai' => $balai,
				'total' => $pusat + $cc + $balai
			)
		);
		
	}
	
	
	public function pusat_pengaduan()
	{		
		$cnt = $this->Ajax->pusat_pengaduan($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function pusat_informasi()
	{		
		$cnt = $this->Ajax->pusat_informasi($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function pusat_status_open()
	{		
		$cnt = $this->Ajax->pusat_status_open($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function pusat_status_closed()
	{		
		$cnt = $this->Ajax->pusat_status_closed($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function pusat_status_replied()
	{		
		$cnt = $this->Ajax->pusat_status_replied($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function pusat_blm_feedback()
	{		
		$cnt = $this->Ajax->pusat_blm_feedback($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	
	
	public function pusat_layanan()
	{
		$open = $this->Ajax->pusat_status_open($this->session->dashboard_date1, $this->session->dashboard_date2);
		$closed = $this->Ajax->pusat_status_closed($this->session->dashboard_date1, $this->session->dashboard_date2);
		$replied = $this->Ajax->pusat_status_replied($this->session->dashboard_date1, $this->session->dashboard_date2);
		
		
		echo json_encode(
			array(
				'open' => $open,
				'closed' => $closed,
				'replied' => $replied
			)
		);
		
	}
	
	
	//layanan saya
	public function layanan_saya_pengaduan()
	{		
		$cnt = $this->Ajax->layanan_saya_pengaduan($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_saya_informasi()
	{		
		$cnt = $this->Ajax->layanan_saya_informasi($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_saya_status_open()
	{		
		$cnt = $this->Ajax->layanan_saya_status_open($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_saya_status_closed()
	{		
		$cnt = $this->Ajax->layanan_saya_status_closed($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_saya_status_replied()
	{		
		$cnt = $this->Ajax->layanan_saya_status_replied($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function layanan_saya()
	{
		$open = $this->Ajax->layanan_saya_status_open($this->session->dashboard_date1, $this->session->dashboard_date2);
		$closed = $this->Ajax->layanan_saya_status_closed($this->session->dashboard_date1, $this->session->dashboard_date2);
		$replied = $this->Ajax->layanan_saya_status_replied($this->session->dashboard_date1, $this->session->dashboard_date2);
		
		
		echo json_encode(
			array(
				'open' => $open,
				'closed' => $closed,
				'replied' => $replied
			)
		);
		
	}
	
	public function layanan_saya_blm_feedback()
	{		
		$cnt = $this->Ajax->layanan_saya_blm_feedback($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	//rujukan
	public function rujukan_masuk()
	{		
		$cnt = $this->Ajax->rujukan_masuk($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function rujukan_masuk_blm_dijawab()
	{		
		$cnt = $this->Ajax->rujukan_masuk_blm_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function rujukan_masuk_sudah_dijawab()
	{		
		$cnt = $this->Ajax->rujukan_masuk_sudah_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function rujukan_keluar()
	{		
		$cnt = $this->Ajax->rujukan_keluar($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function rujukan_keluar_blm_dijawab()
	{		
		$cnt = $this->Ajax->rujukan_keluar_blm_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function rujukan_keluar_sudah_dijawab()
	{		
		$cnt = $this->Ajax->rujukan_keluar_sudah_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	
	
	public function rujukan_masuk_chart()
	{
		$blm_jawab = $this->Ajax->rujukan_masuk_blm_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		$sdh_jawab = $this->Ajax->rujukan_masuk_sudah_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		
		echo json_encode(
			array(
				'blm_jawab' => $blm_jawab,
				'sdh_jawab' => $sdh_jawab,
				
			)
		);
		
	}
	
	public function rujukan_keluar_chart()
	{
		$blm_jawab = $this->Ajax->rujukan_keluar_blm_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		$sdh_jawab = $this->Ajax->rujukan_keluar_sudah_dijawab($this->session->dashboard_date1, $this->session->dashboard_date2);
		
		echo json_encode(
			array(
				'blm_jawab' => $blm_jawab,
				'sdh_jawab' => $sdh_jawab,
				
			)
		);
		
	}
	
	
	public function layanan_pusat_tl()
	{
		$done = $this->Ajax->pusat_tl_done($this->session->dashboard_date1, $this->session->dashboard_date2);
		$notdone = $this->Ajax->pusat_tl_notdone($this->session->dashboard_date1, $this->session->dashboard_date2);
		
		
		echo json_encode(
			array(
				'done' => $done,
				'notdone' => $notdone
			)
		);
		
	}
	
	public function layanan_pusat_fb()
	{
		$done = $this->Ajax->pusat_fb_done($this->session->dashboard_date1, $this->session->dashboard_date2);
		$notdone = $this->Ajax->pusat_fb_notdone($this->session->dashboard_date1, $this->session->dashboard_date2);
		
		
		echo json_encode(
			array(
				'done' => $done,
				'notdone' => $notdone
			)
		);
		
	}
	
	public function belum_tl()
	{
		$red = $this->Ajax->belum_tl_red($this->session->dashboard_date1, $this->session->dashboard_date2);
		$orange = $this->Ajax->belum_tl_orange($this->session->dashboard_date1, $this->session->dashboard_date2);
		$black = $this->Ajax->belum_tl_black($this->session->dashboard_date1, $this->session->dashboard_date2);
		$green = $this->Ajax->belum_tl_green($this->session->dashboard_date1, $this->session->dashboard_date2);
		
		echo json_encode(
			array(
				'red' => $red,
				'orange' => $orange,
				'black' => $black,
				'green' => $green
			)
		);
		
	}
	
	//footer
	public function rujukan_keluar_replied()
	{		
		$cnt = $this->Ajax->rujukan_keluar_replied($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	public function rujukan_masuk_not_closed()
	{		
		$cnt = $this->Ajax->rujukan_masuk_not_closed($this->session->dashboard_date1, $this->session->dashboard_date2);
		echo json_encode($cnt);
	}
	
	
}
?>