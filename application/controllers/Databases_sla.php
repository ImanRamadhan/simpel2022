<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Databases_Sla extends Secure_Controller
{
	function __construct()
	{
		parent::__construct('databasesv2');
	}

	public function index() {}

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
			/*'status' => $status,
						'tl' => $tl,
						'fb' => $fb,
						'is_rujuk' => $is_rujuk,
						'is_verified' => $is_verified*/
		);
		if (is_pusat())
			$filters['kota'] = $kota;

		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);

		$items = $this->Database->search_sla($search, $filters, $limit, $offset, $sort, $order);
		$total_rows = $this->Database->get_sla_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach ($items->result() as $item) {
			$data_rows[] = $this->xss_clean(get_database_data_row($item, ++$no));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters));
	}
}
