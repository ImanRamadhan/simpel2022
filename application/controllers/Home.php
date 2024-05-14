<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Home extends Secure_Controller 
{
	function __construct()
	{
		parent::__construct(NULL, NULL, 'home');
	}

	public function index()
	{
		
		
		if(!isset($this->session->dashboard_date1) ||
		empty($this->session->dashboard_date1))
		{
			$this->session->dashboard_date1 = date('Y-m-d');
			$this->session->dashboard_date2 = date('Y-m-d');
		}

		$data['date_1'] = convert_date2($this->session->dashboard_date1);
		$data['date_2'] = convert_date2($this->session->dashboard_date2);
		
		/*$data['fields'] = array(
			'id_layanan' => 'ID Pengaduan',
			'nama_pelapor' => 'Nama Pelapor',
			'email' => 'Email Pelapor',
			'isu_topik' => 'Isu Topik',
			'isi_pengaduan' => 'Isi Pengaduan',
			'no_telp' => 'No. Telp'
		
		);*/
		
		$data['fields'] = array(
			'trackid' => 'ID Layanan', 
			'cust_nama' => 'Nama Pelapor', 
			'cust_email' => 'Email Pelapor', 
			'isu_topik' => 'Isu Topik',
			'isi_layanan' => 'Isi Layanan',
			'cust_telp' => 'No. Telp Pelapor',
			'jawaban' => 'Jawaban',
			'penerima' => 'Kode Petugas',
			'klasifikasi' => 'Klasifikasi',
			'subklasifikasi' => 'Subklasifikasi'
		);
		
		if($this->session->city == 'PUSAT')
			$this->load->view('home/home', $data);
		elseif($this->session->city == 'UNIT TEKNIS')
			$this->load->view('home/home_unit', $data);
		else
			$this->load->view('home/home_balai', $data);
		
	}
	
	public function logout()
	{
		$this->User->logout();
		//$this->clear_cache();
		if(!$this->User->is_logged_in())
			redirect('login');
	}

	function clear_cache()
    {
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }

	/*
	Loads the change user password form
	*/
	public function change_password()
	{
		$user_id = $this->session->userdata('user_id');
		$person_info = $this->User->get_info($user_id);
		foreach(get_object_vars($person_info) as $property => $value)
		{
			$person_info->$property = $this->xss_clean($value);
		}
		$data['person_info'] = $person_info;

		$this->load->view('home/form_change_password', $data);
	}

	/*
	Change user password
	*/
	public function save($item = -1)
	{
		$user_id = $this->session->userdata('user_id');
		if($this->input->post('current_password') != '' && !empty($user_id))
		{
			if($this->Employee->check_password($this->input->post('username'), $this->input->post('current_password')))
			{
				$employee_data = array(
					'username' => $this->input->post('username'),
					'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
					'hash_version' => 2
				);

				if($this->Employee->change_password($employee_data, $employee_id))
				{
					echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('employees_successful_change_password'), 'id' => $employee_id));
				}
				else//failure
				{
					echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('employees_unsuccessful_change_password'), 'id' => -1));
				}
			}
			else
			{
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('employees_current_password_invalid'), 'id' => -1));
			}
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('employees_current_password_invalid'), 'id' => -1));
		}
	}
	
	private function _handle_file_upload()
	{
		$setting = $this->config->item('upload_setting');
		$setting['upload_path'] = './uploads/temp/';
		$setting['allowed_types'] = 'gif|jpg|png';
		$this->load->library('upload', $setting);
		$this->upload->do_upload('userImage');
		
		return strlen($this->upload->display_errors()) == 0 || !strcmp($this->upload->display_errors(), '<p>'.$this->lang->line('upload_no_file_selected').'</p>');
	}
	
	public function do_change_picture()
	{
		$imagePath = '';
			
		if(!empty($_FILES['userImage']['name']))
		{
			$upload_success = $this->_handle_file_upload();
			$data = $this->upload->data();
			
			$imagePath = base_url().'uploads/temp/'.$data['file_name'];
			
			$x = $this->input->post('x');
			$y = $this->input->post('y');
			$w = $this->input->post('w');
			$h = $this->input->post('h');
			
			if(!empty($x) &&
			!empty($y) &&
			!empty($w) && 
			!empty($h)
			)
			{
				$img_r = imagecreatefromjpeg($imagePath);
				$dst_r = ImageCreateTrueColor( $w, $h );
		 
				imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $w, $h, $w, $h);
				$newpath = './uploads/users/'.$this->session->user_id.'.jpg';
				imagejpeg($dst_r, $newpath);
				$imagePath = $newpath;
				
				//hapus file lama
				@unlink('./uploads/temp/'.$data['file_name']);
				$user_file = $this->session->user_id.'.jpg';
				$this->session->profile_picture = $user_file;
				
				$user_data = array(
					'profile_picture' => $user_file,
					'username' => $this->session->username
				);
				$this->User->save($user_data, $this->session->user_id);
				
				$message = $this->xss_clean('Photo berhasil disimpan');
				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => '', 'imagepath' => $imagePath, 'cropped' => TRUE));
				
			}
			else
			{
				$user_file = $data['file_name'];
				$this->session->profile_picture = $user_file;
				$user_data = array(
					'profile_picture' => $user_file,
					'username' => $this->session->username
				);
				$this->User->save($user_data, $this->session->user_id);
				
				$message = $this->xss_clean('Photo berhasil disimpan');
				echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => '', 'imagepath' => $imagePath, 'cropped' => FALSE));
			}
		}
		
		
	}
	
	public function image_crop()
	{
		$img_r = imagecreatefromjpeg($_GET['img']);
		$dst_r = ImageCreateTrueColor( $_GET['w'], $_GET['h'] );
		 
		imagecopyresampled($dst_r, $img_r, 0, 0, $_GET['x'], $_GET['y'], $_GET['w'], $_GET['h'], $_GET['w'],$_GET['h']);
		  
		header('Content-type: image/jpeg');
		imagejpeg($dst_r);
		 
		exit;
	}
	
	public function change_picture()
	{
		$imagePath = '';

		$data['imagePath'] = $imagePath;
		$this->load->view('home/form_change_picture', $data);
	}
	
	public function no_access()
	{
		$this->load->view('home/no_access');
	}
	
	public function dashboard_date()
	{
		$date1 = $this->input->post('date1');
		$date2 = $this->input->post('date2');
		
		$this->session->dashboard_date1 = convert_date1($date1);
		$this->session->dashboard_date2 = convert_date1($date2);
		
		redirect('home');
	}
	
	
	
	
}
?>