<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Users extends Secure_Controller
{
	private $_list_id;

	public function __construct()
	{
		parent::__construct('users');

		//$CI =& get_instance();

	}
	
	public function index()
	{
		if(!is_administrator())
			redirect('home/no_access');
		
		
		$data['table_headers'] = $this->xss_clean(get_user_manage_table_headers());
		
		$cities = $this->City->get_cities();
		$cities_array = array(
			'ALL' => 'ALL', 
			'PUSAT' => 'PUSAT',
			'UNIT TEKNIS' => 'UNIT TEKNIS'
		);
		
		foreach($cities->result() as $city)
		{
			$cities_array[strtoupper($city->nama_kota)] = $city->nama_kota;
		}
		$data['cities'] = $cities_array;

		$this->load->view('users/manage', $data);
	}
	
	public function get_features()
	{
		$features_array = array(
			'can_create_tickets',	/* User can create tickets */
			'can_view_tickets',		/* User can read tickets */
			'can_reply_tickets',	/* User can reply to tickets */
			'can_del_tickets',		/* User can delete tickets */
			'can_edit_tickets',		/* User can edit tickets */
			//'can_change_priority',
			'can_change_status',
			//'can_assign_self',	/* User can assign tickets to himself/herself */
			'can_assign_others',	/* User can assign tickets to other staff members */
			//'can_del_notes',		/* User can delete ticket notes posted by other staff members */
			'can_change_cat',		/* User can move ticke to a new category/department */
			//'can_man_kb',			/* User can manage knowledgebase articles and categories */
			'can_man_users',		/* User can create and edit staff accounts */
			//'can_man_cat',		/* User can manage categories */
			'can_man_dir',			/* User can manage direktorat/departments */
			//'can_man_canned',		/* User can manage canned responses */
			//'can_man_settings',		/* User can manage help desk settings */
			//'can_add_archive',		/* User can mark tickets as "Tagged" */
			//'can_view_unassigned',	/* User can view unassigned tickets */
			//'can_view_ass_others',	/* User can view tickets that are assigned to other staff */
			'can_run_reports',		/* User can run reports and see statistics */
			//'can_view_online',		/* User can view what staff members are currently online */
			'can_list_tickets_city'
		
		);
		return $features_array;
	}
	
	public function create($item_id = -1)
	{
		
		$item_info = $this->User->get_info(0);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		$data['page_title'] = 'Tambah Data';
		
		$cities = $this->City->get_cities();
		$city_array = array(
			'' => 'Pilih Kota',
			'PUSAT' => 'PUSAT',
			'UNIT TEKNIS' => 'UNIT TEKNIS'
		);
		foreach($cities->result() as $row)
		{
			$city_array[strtoupper($row->nama_kota)] = strtoupper($row->nama_kota);
		}
		$data['cities'] = $city_array;
		
		$roles = $this->User->get_roles();
		$role_array = array(''=>'Pilih');
		foreach($roles->result() as $row)
		{
			$role_array[$row->role_id] = $row->role_name;
		}
		$data['roles'] = $role_array;
		$data['depts'] = array();
		$data['privileges'] = array();
		
		$data['features'] = $this->get_features();

		$this->load->view('users/form', $data);
	}

	/*
	Gets one row for a user manage table. This is called using AJAX to update one row.
	*/
	public function get_row($row_id)
	{
		$person = $this->User->get_info($row_id);

		$data_row = $this->xss_clean(get_user_data_row($person));

		echo json_encode($data_row);
	}

	/*
	Returns user table data rows. This will be called with AJAX.
	*/
	public function search()
	{
		$search = $this->input->get('search');
		$limit  = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort   = $this->input->get('sort');
		$order  = $this->input->get('order');
		
		$city = $this->input->get('kota');
		$dir_id = $this->input->get('dir');
		$status = $this->input->get('status');
		
		$filters = array(
			'city' => $city,
			'status' => $status,
			'dir_id' => $dir_id
		);

		$users = $this->User->search($search, $filters, $limit, $offset, $sort, $order);
		$total_rows = $this->User->get_found_rows($search, $filters);

		$data_rows = array();
		foreach($users->result() as $person)
		{

			$data_rows[] = $this->xss_clean(get_user_data_row($person, ++$offset));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

	/*
	Loads the user edit form
	*/
	public function edit($user_id = -1)
	{
		$data['page_title'] = 'Ubah User';

		$info = $this->User->get_info($user_id);
		foreach(get_object_vars($info) as $property => $value)
		{
			$info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $info;
		
		$data['privileges'] = explode(',', $info->heskprivileges);
		
		$cities = $this->City->get_cities();
		$city_array = array(
			'' => 'Pilih Kota',
			'PUSAT' => 'PUSAT',
			'UNIT TEKNIS' => 'UNIT TEKNIS'
		);
		foreach($cities->result() as $row)
		{
			$city_array[strtoupper($row->nama_kota)] = strtoupper($row->nama_kota);
		}
		$data['cities'] = $city_array;
		
		$depts_array = array();
		$depts = $this->User->get_depts($info->city);
		foreach($depts->result() as $row)
		{
			$depts_array[$row->id] = $row->name;
		}
		$data['depts'] = $depts_array;
		
		
		$roles = $this->User->get_roles();
		$role_array = array(''=>'Pilih');
		foreach($roles->result() as $row)
		{
			$role_array[$row->role_id] = $row->role_name;
		}
		$data['roles'] = $role_array;
		
		$data['features'] = $this->get_features();
		
		$this->load->view("users/form", $data);
	}
	
	public function profile()
	{
		$user_id = $this->session->id;
		$info = $this->User->get_info($user_id);
		foreach(get_object_vars($info) as $property => $value)
		{
			$info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $info;
		
		$data['page_title'] = 'Profil Saya';
		
		$this->load->view("users/profile", $data);
	}

	/*
	Inserts/updates a user
	*/
	public function save($user_id = -1)
	{
		$name = $this->xss_clean($this->input->post('name'));
		$email = $this->xss_clean(strtolower($this->input->post('email')));
		$user = $this->xss_clean(strtolower($this->input->post('user')));
		
		$password = $this->input->post('pass');
		$password2 = $this->input->post('pass2');

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo json_encode(array('success' => FALSE, 'message' => 'Email tidak sesuai format', 'id' => -1));
			return;
		} 

		$sanitized_input = sanitize_input($password);
		$safe_input = custom_escape($sanitized_input);
		
		if (!(validate_input($safe_input)))
		{
			echo json_encode(array('success' => FALSE, 'message' => 'Password tidak sesuai format', 'id' => -1));
			return;
		}

		if (!(validate_strong_password($safe_input)))
		{
			echo json_encode(array('success' => FALSE, 'message' => 'Password tidak memenuhi syarat strong password', 'id' => -1));
			return;
		}

		if($password != $password2)
		{
			echo json_encode(array('success' => FALSE, 'message' => 'Password tidak sama', 'id' => -1));
			return;
		}

		$person_data = array(
			'name' => $name,
			'isadmin' => "'".$this->input->post('isadmin')."'", //enum
			'email' => $email,
			'user' => $user,
			'city' => strtoupper(trim($this->input->post('city'))),
			'direktoratid' => $this->input->post('direktoratid'),
			'no_hp' => $this->input->post('no_hp'),
			'is_active' => $this->input->post('is_active'),
			'is_notif' => $this->input->post('is_notif')
		);
		
		if($user_id == -1)
		{
			$person_data['pass'] = $this->User->pass2Hash(trim($password));
			//$person_data['user'] = $user;
		}
		
		if(!empty($password) && $password == $password2)
		{
			$person_data['pass'] = $this->User->pass2Hash(trim($password));
		}
		
		if(!empty($this->input->post('features')))
		{
			$person_data['heskprivileges'] = implode(',',  $this->input->post('features'));
		}

		if($this->User->save($person_data, $user_id))
		{
			// New User
			if($user_id == -1)
			{
				echo json_encode(array('success' => TRUE,
								'message' => $this->lang->line('users_successful_adding') . ' ' . $name,
								'id' => $this->xss_clean($person_data['name'])));
			}
			else // Existing user
			{
				echo json_encode(array('success' => TRUE,
								'message' => $this->lang->line('users_successful_updating') . ' ' . $name,
								'id' => $user_id));
			}
		}
		else // Failure
		{
			echo json_encode(array('success' => FALSE,
							'message' => $this->lang->line('users_error_adding_updating') . ' ' . $name,
							'id' => -1));
		}
	}

	/*
	AJAX call to verify if an email address already exists
	*/
	public function ajax_check_email()
	{
		$exists = $this->User->check_email_exists(strtolower($this->input->post('email')), $this->input->post('person_id'));

		echo !$exists ? 'true' : 'false';
	}
	
	public function ajax_check_user()
	{
		$exists = $this->User->check_user_exists(strtolower($this->input->post('user')), $this->input->post('id'));

		echo !$exists ? 'true' : 'false';
	}


	/*
	This deletes users from the users table
	*/
	public function delete()
	{
		$items_to_delete = $this->input->post('ids');

		if($this->User->delete_list($items_to_delete))
		{
			$message = $this->lang->line('items_successful_deleted') . ' ' . count($items_to_delete) . ' ' . $this->lang->line('items_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_cannot_be_deleted')));
		}
	}
	
	public function nonactive()
	{
		$items = $this->input->post('ids');

		if($this->User->nonactive_list($items))
		{
			$message = $this->lang->line('users_successful_nonactive') . ' ' . count($items) . ' ' . $this->lang->line('items_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('users_cannot_be_nonactive')));
		}
	}

	public function get_dir($city = '')
	{
		
		$city = $this->xss_clean($city);
		$dirs = $this->User->get_dir($city);

		echo json_encode($dirs);
	}

	public function get_directorat()
	{
		
		$city = $this->xss_clean($this->input->get('city'));
		$dirs = $this->User->list_directorats($city);

		echo json_encode($dirs);
	}
	
}
?>
