<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Databasesv2 extends Secure_Controller
{
	function __construct()
	{
		parent::__construct('databasesv2');
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
			'ALL' => 'ALL',
			'LAYANAN' => 'Layanan',
			'SP4N' => 'SP4N',
			'PPID' => 'PPID',
			'LAYANAN_SP4N' => 'Layanan + SP4N'
		);

		$data['statusRujukan'] = array(
			'ALL'	=> 'ALL',
			'N'	=> 'Belum di-TL',
			'Y'	=> 'Sudah di-TL'
		);

		$categories = $this->Ticket->get_categories();
		$categories_array = array('ALL' => 'ALL');
		foreach ($categories->result() as $cat) {
			$categories_array[$cat->id] = $cat->desc;
		}
		$data['categories'] = $categories_array;



		$data['unitRujukan'] = get_direktorat_rujukan();
	}

	public function index()
	{
		$data['title'] = 'Database Layanan';
		$data['filters'] = array();
		$data['status_filter'] = '';

		$this->setup_search($data);

		$data['layanan'] = array();

		$this->load->view('databases2/manage_layanan', $data);
	}

	public function sla()
	{
		$data['title'] = 'Database SLA';
		$data['filters'] = array();
		$data['status_filter'] = '';

		$this->setup_search($data);

		$data['layanan'] = array();

		$this->load->view('databases2/manage_sla', $data);
	}

	public function yanblik()
	{
		$data['title'] = 'Database Yanblik';
		$data['filters'] = array();
		$data['status_filter'] = '';

		$this->setup_search($data);

		$data['layanan'] = array();

		$this->load->view('databases2/manage_yanblik', $data);
	}

	public function rujukan()
	{
		$data['title'] = 'Database Rujukan';
		$data['filters'] = array();
		$data['status_filter'] = '';

		$this->setup_search($data);

		$data['layanan'] = array();

		$this->load->view('databases2/manage_rujukan', $data);
	}

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
		$status_rujukan = $this->input->get('status_rujukan');

		/*$status = $this->input->get('status[]');
		$tl = $this->input->get('tl[]');
		$fb = $this->input->get('fb[]');
		$is_rujuk = $this->input->get('is_rujuk[]');
		$is_verified = $this->input->get('is_verified[]');*/

		$filters = array(
			'tgl1' => $tgl1,
			'tgl2' => $tgl2,
			'field' => $field,
			'keyword' => $keyword,
			'kategori' => $kategori,
			'jenis' => $jenis,
			'status_rujukan' => $status_rujukan,
			/*'tl' => $tl,
						'fb' => $fb,
						'is_rujuk' => $is_rujuk,
						'is_verified' => $is_verified*/
		);
		if (is_pusat())
			$filters['kota'] = $kota;

		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);



		$items = $this->Database->search($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Database->get_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach ($items->result() as $item) {
			$data_rows[] = $this->xss_clean(get_database_data_row($item, ++$no));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters));
	}
}
