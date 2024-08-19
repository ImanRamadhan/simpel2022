<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class RequestAccess extends CI_Model {

    public function addRequest(){
        $this->db->insert('m_user', []);
    }

    public function save(){
        $userId = $this->input->post('user_id');
        $id     = $this->input->post('id');
        $type   = $this->input->post('type');
        $data   = [];

		if ($userId != null) {
			$exist  = $this->db->where('user_id',$userId)->get("login_request")->row();
		} else {
			$exist  = $this->db->where('id',$id)->get("login_request")->row();
		}
		
        $date   = new DateTime();

        if(!$exist){
            $data["user_id"]    	= $userId;
			$data["created_by"]		= $this->session->id;
			$data["updated_by"]		= $this->session->id;
            $this->db->insert('login_request', $data);
            return TRUE;
        } else {
			$data["code"] 			= NULL;
			$data["valid_until"] 	= NULL;
			$data["updated_by"]		= $this->session->id;
			$userId 				= $exist->user_id;
		}

        if((int)$type == 1){
            $dateTomorrow           = $date->modify("+1 day")->format("Y-m-d H:i:s");
            $data["valid_until"]    = $dateTomorrow;
			$data["code"]			= random_int(10000000, 99999999);
			$data["updated_by"]		= $this->session->id;
        }else if((int)$type == 2){
            $data["valid_until"]    = $date->modify("-1 hour")->format("Y-m-d H:i:s");
        }
		
        $this->db->where('user_id',$userId);
        $this->db->update("login_request",$data);
        return TRUE;
    }
    
    public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters, 0, 0, 'login_request.id', 'asc', TRUE);
	}

	/*
	Perform a search on items
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'login_request.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if($count_only == TRUE)
		{
			$this->db->select('COUNT(login_request.id) as count');
		}
		else
		{
		
			$this->db->select('login_request.*,desk_users.name as nama');
			
		}
        $this->db->join("desk_users", "desk_users.id = login_request.user_id");
		if(!is_administrator()){
			$this->db->where("login_request.user_id", $this->session->id);
		}
		$this->db->from('login_request');		
		if(!empty($search))
		{
			$this->db->group_start();
				$this->db->like('desk_users.name', $search);
				//$this->db->or_like('custom2', $search);
			$this->db->group_end();
		}
		
		
		// get_found_rows case
		if($count_only == TRUE)
		{
			return $this->db->get()->row()->count;
		}

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}
}