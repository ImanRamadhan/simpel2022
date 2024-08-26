<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

/* class Balai/Loka */

class Balais extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('balais');
	}

	public function index()
	{
		$data['table_headers'] = $this->xss_clean(get_balais_manage_table_headers());

		$data['filters'] = array(
			1 => 'Balai Besar',
			2 => 'Balai Tipe A',
			3 => 'Balai Tipe B',
			4 => 'Loka'
		);
		$data['status_filter'] = '';
		$this->load->view('balai/manage', $data);
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
		$filters = $this->input->get('filters[]');

		//$filters = array();
		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);



		$items = $this->Balai->search($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->Balai->get_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach ($items->result() as $item) {
			$data_rows[] = $this->xss_clean(get_balai_data_row($item, ++$no));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters));
	}




	/*
	Gets one row for a standard manage table. This is called using AJAX to update one row.
	*/
	public function get_row($row_id)
	{
		$item = $this->Balai->get_info($row_id);

		$data_row = $this->xss_clean(get_balai_data_row($item));

		echo json_encode($data_row);
	}

	public function view($item_id = -1)
	{

		$item_info = $this->Balai->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		$data['page_title'] = 'Ubah Data';
		$data['mode'] = 'edit';

		$this->load->view('balai/form', $data);
	}

	public function create($item_id = -1)
	{

		$item_info = $this->Balai->get_info($item_id);
		foreach (get_object_vars($item_info) as $property => $value) {
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		$data['page_title'] = 'Tambah Data';
		$data['mode'] = 'add';

		$this->load->view('balai/form', $data);
	}

	public function save($item_id = -1)
	{

		//Save item data
		$item_data = array(
			//'nama_balai' => $this->input->post('nama_balai'),
			'tipe_balai' => $this->input->post('tipe_balai'),
			//'kode_prefix' => strtoupper(trim($this->input->post('kode_prefix'))),
			'kop' => $this->input->post('kop'),
			'no_telp' => $this->input->post('no_telp'),
			'no_fax' => $this->input->post('no_fax'),
			'email' => $this->input->post('email'),
			'alamat' => $this->input->post('alamat')
		);

		if ($item_id == -1) {
			$item_data['nama_balai'] = $this->input->post('nama_balai');
			$item_data['kode_prefix'] = strtoupper(trim($this->input->post('kode_prefix')));
		}

		if ($this->Balai->save($item_data, $item_id)) {
			if ($item_id == -1) {
				$message = $this->xss_clean($this->lang->line('items_successful_adding') . ' ');

				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_data['id']));
			} else {
				$message = $this->xss_clean($this->lang->line('items_successful_updating') . ' ');

				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
			}
		} else // failure
		{
			$message = $this->xss_clean($this->lang->line('items_error_adding_updating') . ' ' . $item_data['id']);

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
		}
	}



	public function delete()
	{
		$items_to_delete = $this->input->post('ids');

		if ($this->Balai->delete_list($items_to_delete)) {
			$message = $this->lang->line('items_successful_deleted') . ' ' . count($items_to_delete) . ' ' . $this->lang->line('items_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		} else {
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_cannot_be_deleted')));
		}
	}


	/*
	AJAX call
	*/
	public function ajax_check_balai()
	{
		$exists = $this->Balai->check_balai_exists(strtolower($this->input->post('nama_balai')), $this->input->post('id'));

		echo !$exists ? 'true' : 'false';
	}

	public function ajax_check_prefix()
	{
		$exists = $this->Balai->check_prefix_exists(strtolower($this->input->post('kode_prefix')), $this->input->post('id'));

		echo !$exists ? 'true' : 'false';
	}
}
