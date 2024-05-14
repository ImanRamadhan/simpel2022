<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Profile extends Secure_Controller 
{
	function __construct()
	{
		parent::__construct('profile');
	}

	public function index()
	{
		
		$data['show'] = FALSE;
		$user = $this->User->get_info($this->session->id);
		$data['item_info'] = $user;
		//print_r($this->session->userdata);
		//print_r($user);
		
		$data['page_title'] = 'Profile';
			
		$this->load->view('users/profile', $data);
		
	}
	
	public function change_pwd()
	{
		$data['page_title'] = 'Ubah Password';
			
		$this->load->view('users/change_pwd', $data);
	}
	
	public function save($user_id = -1)
	{
		$no_hp = $this->xss_clean($this->input->post('no_hp'));
		$email = $this->xss_clean(strtolower($this->input->post('email')));
		$is_notif = $this->xss_clean(strtolower($this->input->post('is_notif')));

		$person_data = array(
			'no_hp' => $no_hp,
			'email' => $email,
			'is_notif' => $is_notif
		);
		
		$user_id = $this->session->id;

		if($this->User->save_profile($person_data, $user_id))
		{
			
			
			echo json_encode(array('success' => TRUE,
								'message' => 'Profile berhasil disimpan',
								'id' => $user_id));
			
		}
		else // Failure
		{
			echo json_encode(array('success' => FALSE,
							'message' => 'Profil gagal disimpan',
							'id' => -1));
		}
	}

	public function change_password()
	{
		$password = $this->input->post('oldpassword');
		$newpassword = $this->input->post('newpassword');
		$confirmpassword = $this->input->post('newpassword2');
		
		if($newpassword != $confirmpassword)
		{
			echo json_encode(array('success' => FALSE, 'message' => 'Password tidak sama', 'id' => 0));
		}
		else
		{
			
			if(!$this->User->check_password($this->session->id, $password))
				echo json_encode(array('success' => FALSE, 'message' => 'Password lama salah', 'id' => 0));
			else
			{
				$user_data = array(
					'pass' => $this->User->pass2Hash($newpassword)
				);
				if($this->User->change_password($user_data, $this->session->id))
				{
					echo json_encode(array('success' => TRUE, 'message' => 'Password berhasil diubah', 'id' => 0));
				}
				else
				{
					echo json_encode(array('success' => FALSE, 'message' => 'Password gagal diubah', 'id' => 0));
				}
			}
		}
		
		
	}
}
?>
