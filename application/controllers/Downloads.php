<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Downloads extends Secure_Controller
{
	function __construct()
	{
		parent::__construct(NULL, NULL, 'home');
	}

	public function index()
	{
	}

	/* Download untuk attachment replies  */
	public function download_attachment()
	{
		$att_id = $this->input->get('att_id');
		$trackid = $this->input->get('trackid');
		
		$att_id = (int)$att_id;
		
		$file = $this->Ticket->get_attachment_info2($att_id);
		if(empty($file->ticket_id))
		{
			$this->load->view('home/file_not_found');
			return;
		}
		
		if($trackid != $file->ticket_id)
		{
			$this->load->view('home/file_not_found');
			
			return;
		}
		
		$config = $this->config->item('upload_setting');
		
		//echo $config['upload_path'].$file->saved_name;
		//exit;
		
		
		
		header ("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Length: ' . $file->size);
		header('Content-Disposition: attachment; filename=' . $file->real_name);
		readfile($config['upload_path'].$file->saved_name);
		
	}
	
	public function download_attachment_ticket()
	{
		$att_id = $this->input->get('att_id');
		$trackid = $this->input->get('trackid');
		
		$att_id = (int)$att_id;
		
		$file = $this->Ticket->get_attachment_info($att_id);
		if(empty($file->ticket_id))
		{
			$this->load->view('home/file_not_found');
			return;
		}
		
		if($trackid != $file->ticket_id)
		{
			$this->load->view('home/file_not_found');
			
			return;
		}
		
		$config = $this->config->item('upload_setting');
		
		//echo $config['upload_path'].$file->saved_name;
		//echo $file->size;
		//exit;
		
		
		
		/*header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Length: ' . $file->size);
		header('Content-Disposition: attachment; filename=' . $file->real_name);
		readfile($config['upload_path'].$file->saved_name);*/
		
		$realpath = $config['upload_path'].$file->saved_name;
		//echo $realpath;
		//return;
		
		if(!file_exists($realpath))
		{
			echo 'File not found';
			return;
		}
		
		// Send the file as an attachment to prevent malicious code from executing
		header("Pragma: "); # To fix a bug in IE when running https
		header("Cache-Control: "); # To fix a bug in IE when running https
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		//header('Content-Length: ' . filesize($realpath));
		header('Content-Length: ' . $file->size);
		header('Content-Disposition: attachment; filename=' . $file->real_name);

		// For larger files use chunks, smaller ones can be read all at once
		$chunksize = 1048576; // = 1024 * 1024 (1 Mb)
		if ($file->size > $chunksize)
		{
			$handle = fopen($realpath, 'rb');
			$buffer = '';
			while ( ! feof($handle))
			{
				set_time_limit(300);
				$buffer = fread($handle, $chunksize);
				echo $buffer;
				flush();
			}
			fclose($handle);
		}
		else
		{
			readfile($realpath);
		}
		exit();
		
		
	}
	
	public function download_attachment_draft()
	{
		$att_id = $this->input->get('att_id');
		$trackid = $this->input->get('trackid');
		
		$att_id = (int)$att_id;
		
		$file = $this->Draft->get_attachment_info($att_id);
		if(empty($file->draft_id))
		{
			$this->load->view('home/file_not_found');
			return;
		}
		
		if($trackid != $file->draft_id)
		{
			$this->load->view('home/file_not_found');
			
			return;
		}
		
		$config = $this->config->item('upload_draft_setting');
		$realpath = $config['upload_path'].$file->saved_name;
		//echo $config['upload_path'].$file->saved_name;
		//exit;
		
		
		
		header ("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Length: ' . $file->size);
		//header('Content-Length: ' . filesize($realpath));
		header('Content-Disposition: attachment; filename=' . $file->real_name);
		readfile($config['upload_path'].$file->saved_name);
		
	}

	
}
?>
