<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'third_party/securimage/securimage.php';

class Login extends CI_Controller
{
	public function index()
	{
		
		if($this->User->is_logged_in())
		{
			redirect('home');
		}
		else
		{
			
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			$this->form_validation->set_rules('username', 'lang:login_username', 'required|callback_login_check');			

			if($this->form_validation->run() == FALSE)
			{
				$this->load->view('login');
			}
			else
			{	
				redirect(base_url().'home', 'refresh');
			}
		}
	}
	
	function captcha()
	{
		$img = new Securimage();
		$img->image_height = 70;
		$img->image_width = 150;
		$img->charset = '0123456789';
		$img->perturbation = 0.1;
		$img->num_lines = 0;
		//$img->captcha_type = Securimage::SI_CAPTCHA_MATHEMATIC;
		$img->captcha_type = Securimage::SI_CAPTCHA_STRING;
		$img->show();
	}
	
	function clear_cache()
    {
       // $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
       // $this->output->set_header("Pragma: no-cache");
    }

	public function login_check($username)
	{
		
		
		
		
		
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		
		if(empty($username) || empty($password))
		{
			$this->form_validation->set_message('login_check', 'Silakan input username dan password');
			
			return FALSE;
		}
		
		$code = $this->input->post('validasi');
		
		if(empty($code))
		{
			$this->form_validation->set_message('login_check', 'Silakan input kode validasi');
			return FALSE;
		}
		
		
		$image = new Securimage();
		if ($image->check($code) == false) {
			$this->form_validation->set_message('login_check', 'Kode validasi salah');
			return FALSE;
		}
		

		if(!$this->User->login($username, $password))
		{
			$this->form_validation->set_message('login_check', $this->lang->line('login_invalid_username_and_password'));
			
			$user = $this->User->get_info_by_username($username);
			if(!empty($user->username))
			{
				$failed_login = (int)$user->failed_login + 1;

				$login_data = array(
					'failed_login' => $failed_login,
					'username' => $username
				);
				if($failed_login > 3)
				{
					$login_data['is_locked'] = 1; //locked
					$this->form_validation->set_message('login_check', 'Username Anda terkunci.');
				}
				
				//$this->User->save($login_data, $user->user_id);
			}
			

			return FALSE;
		}
		
		
		$user = $this->User->get_info_by_username($username);
		if(!empty($user->username))
		{
			$login_data = array(
				'failed_login' => 0,
				'username' => $username
			);
			
			//$this->User->save($login_data, $user->user_id);
		}

		
		

		return TRUE;
	}
	
	public function getRandom($n) { 
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
		$randomString = ''; 
	  
		for ($i = 0; $i < $n; $i++) { 
			$index = rand(0, strlen($characters) - 1); 
			$randomString .= $characters[$index]; 
		} 
	  
		return $randomString; 
	} 
	
	/*
	public function forgot_password()
	{
		$data['message'] = '';
		
		$email = $this->input->post('email');
		
		if(!empty($email))
		{
			$user = $this->User->get_info_by_email($email);
			
			if($user->user_id != '')
			{
				//echo 'send email';
				
				$config = array(
					'mailtype'  => 'html',
					'charset'   => 'utf-8',
					'protocol'  => 'smtp',
					'smtp_host' => 'smtp.gmail.com',
					'smtp_user' => 'aplikasi.evaluasi@gmail.com', 
					'smtp_pass'   => 'Jakarta123!', 
					'smtp_crypto' => 'ssl',
					'smtp_port'   => 465,
					'crlf'    => "\r\n",
					'newline' => "\r\n"
				);
				
				$newpass = $this->getRandom(6);
				$new_data = array(
					'password' => sha1($newpass)
				);
				$this->User->change_password($new_data, $user->user_id);
				
				$message = "Aplikasi Simpel LPK<br /><br />";
				$message .= "Username: ".$user->username."<br />";
				$message .= "Password: ".$newpass."<br />";

				$this->load->library('email', $config);
				$this->email->from('aplikasi.evaluasi@gmail.com', 'Reset Password'); 
				$this->email->to($email);
				$this->email->subject('Reset Password'); 
				$this->email->message($message); 
		   
				 //Send mail 
				if($this->email->send()) 
				{
				}
			}
			
			
			$data['message'] = 'Password telah dikirim ke email anda.';
		}
		
		
		
		$this->load->view('forgot_password', $data);
	}
	*/
	/*
	public function gcaptcha_check($recaptchaResponse)
	{
		$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $this->config->item('gcaptcha_secret_key') . '&response=' . $recaptchaResponse . '&remoteip=' . $this->input->ip_address();

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		$result = curl_exec($ch);
		curl_close($ch);

		$status = json_decode($result, TRUE);

		if(empty($status['success']))
		{
			$this->form_validation->set_message('gcaptcha_check', $this->lang->line('login_invalid_gcaptcha'));

			return FALSE;
		}

		return TRUE;
	}

	private function _installation_check()
	{
		// get PHP extensions and check that the required ones are installed
		$extensions = implode(', ', get_loaded_extensions());
		$keys = array('bcmath', 'intl', 'gd', 'openssl', 'mbstring', 'curl');
		$pattern = '/';
		foreach($keys as $key) 
		{
			$pattern .= '(?=.*\b' . preg_quote($key, '/') . '\b)';
		}
		$pattern .= '/i';
		$result = preg_match($pattern, $extensions);

		if(!$result)
		{
			error_log('Check your php.ini');
			error_log('PHP installed extensions: ' . $extensions);
			error_log('PHP required extensions: ' . implode(', ', $keys));
		}

		return $result;
	}
	*/
}
?>
