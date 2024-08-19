<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");


class Request_access extends Secure_Controller
{

    public function __construct()
    {
        parent::__construct('request_access');
        $this->load->model('RequestAccess');
        $this->load->model('User');
    }
    public function index()
    {
        $data['title'] = 'Request Access Login';
        $data['table_headers'] = $this->xss_clean(get_request_access_manage_table_headers());

        $this->load->view('request_access/manage', $data);
    }



    public function getRequest()
    {
        return $this->RequestAccess->result();
    }

    public function create($item_id = -1)
    {

        $data['page_title'] = 'Request Access Login';

        $this->load->view('request_access/form', $data);
    }

    public function search()
    {
        $search = $this->input->get('search');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort = $this->input->get('sort');
		$order = $this->input->get('order');

		$filters = array();
		$items = $this->RequestAccess->search($search, $filters, $limit, $offset, $sort, $order);

		$total_rows = $this->RequestAccess->get_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach($items->result() as $item)
		{
			$data_rows[] = $this->xss_clean(get_request_access_data_row($item, ++$no));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters ));
    }

    public function search_user()
    {
        $search = $this->input->get('search');

        $users = $this->User->search($search, 'ALL');
        $data_rows = array();
        foreach ($users->result() as $person) {

            $data_rows[] = $this->xss_clean([
                'id' => $person->id,
                'text' => "$person->name [$person->city]"
            ]);
        }

        echo json_encode(array('items' => $data_rows));
    }

    public function save($itemId = 1)
    {

        if((int)$this->input->post('type') == 0)
            $message = $this->xss_clean($this->lang->line('items_add_request_login'));
        else if((int)$this->input->post('type') == 1)
            $message = $this->xss_clean($this->lang->line('items_update_approve_request_login'));
        else
            $message = $this->xss_clean($this->lang->line('items_update_reject_request_login'));
        
        if ($this->RequestAccess->save()) {
            echo json_encode(array('success' => TRUE, 'message' => $this->xss_clean($this->lang->line('items_successfull')) . ''. $message));
        }else{
            echo json_encode(array('success' => FALSE, 'message' => $this->xss_clean($this->lang->line('items_fail')) . ''. $message));  
        }
    }
}
