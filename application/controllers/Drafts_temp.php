<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");


class Drafts_Temp extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('draftstemp');
		$this->load->model('DraftTemp');
	}
	
	public function index()
	{
		redirect('drafts/list_all');
	}
	
	public function setup_search(&$data)
	{
		
		$data['cities'] = get_filters();
		
		$data['fields'] = array(
			'id_layanan' => 'ID Pengaduan',
			'nama_pelapor' => 'Nama Pelapor',
			'email' => 'Email Pelapor',
			'isu_topik' => 'Isu Topik',
			'isi_pengaduan' => 'Isi Pengaduan',
			'no_telp' => 'No. Telp'
		
		);
		
		$data['datasources'] = array(
			'' => '',
			'SP4N' => 'SP4N',
			'PPID' => 'PPID'
		);
		
		$categories = $this->Ticket->get_categories();
		$categories_array = array('0' => '');
		foreach($categories->result() as $cat)
		{
			$categories_array[$cat->id] = $cat->desc;
		}
		$data['categories'] = $categories_array;
		
	}
	
	public function uploadxls(){
		$data['page_title'] = 'Tambah Data Via Upload';
		$data['table_headers'] = $this->xss_clean(get_drafts_manage_table_headers());
		
		$this->load->view('drafts_temp/upload_xls', $data);
	}
	
	
	public function search()
	{
		$search = $this->input->get('search');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort = $this->input->get('sort');
		$order = $this->input->get('order');
		
		
		//$tgl1 = $this->input->get('tgl1');
		//$tgl2 = $this->input->get('tgl2');

		//$filters = array();
		$filters = array(
						
						//'tgl1' => $tgl1,
						//'tgl2' => $tgl2
						);
		// check if any filter is set in the multiselect dropdown
		//$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		//$filters = array_merge($filters, $filledup);
		
		$items = $this->DraftTemp->search($search, $filters, $limit, $offset, $sort, $order);
		

		$total_rows = $this->DraftTemp->get_found_rows($search, $filters);

		$data_rows = array();
		$no = $offset;
		foreach($items->result() as $item)
		{
			$data_rows[] = $this->xss_clean(get_draft_data_row($item, ++$no));
			
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows, 'filter' => $filters ));
	}
	
    public function save_bulk($data){
		return $this->db->insert_batch('desk_drafts_temp',$data);
	}
    
	public function copy_from($item_id = -1)
	{
		$data['page_title'] = 'Tambah Data';
		
		
		$item_info = $this->Ticket->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		
		//reset-start
		$item_info->tglpengaduan = date('d/m/Y');
		$item_info->tglpengaduan_fmt = date('d/m/Y');
		$item_info->waktu = date('H:i:s');
		$item_info->kota = $this->session->city;
		$item_info->is_rujuk = '0';
		$item_info->direktorat = 0;
		$item_info->direktorat2 = 0;
		$item_info->direktorat3 = 0;
		$item_info->direktorat4 = 0;
		$item_info->direktorat5 = 0;
		$item_info->id = -1;
		//reset-end
		
		$data['item_info'] = $item_info;
		
		$ppid_info = $this->Draft->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		$data['ppid_info'] = $ppid_info;
		
		$data['answered_via'] = get_sarana();
		$data['submited_via'] = get_sarana();
		$data['countries'] = get_countries();
		$data['provinces'] = get_provinces();
		$data['provinces2'] = get_provinces();
		
		$data['profesi'] = get_profesi();
		$data['products'] = get_products();
		$data['sumberdata'] = array('' => '','SP4N' => 'SP4N','PPID'=>'PPID');
		$data['klasifikasi'] = get_klasifikasi();
		$data['dir_rujukan'] = get_direktorat_rujukan();
		$data['subklasifikasi'] = array('' => '');
		
		$data['kabs'] = get_cities($item_info->iden_provinsi);
		$data['sla'] = get_sla();
		
		$data['upload_url'] = site_url('drafts/do_upload');
		$data['back_url'] = site_url('drafts');
		$this->load->view('drafts/form', $data);
	}
	
	public function edit($item_id = -1)
	{
		
		$item_info = $this->DraftTemp->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		
		$ppid_info = $this->DraftTemp->get_ppid_info($item_id);
		foreach(get_object_vars($ppid_info) as $property => $value)
		{
			$ppid_info->$property = $this->xss_clean($value);
		}
		$data['ppid_info'] = $ppid_info;
		
		
		
		$data['page_title'] = 'Ubah Data (Draft)';
		$data['answered_via'] = get_sarana();
		$data['submited_via'] = get_sarana();
		$data['countries'] = get_countries();
		$data['provinces'] = get_provinces();
		$data['provinces2'] = get_provinces();
		
		$data['profesi'] = get_profesi();
		$data['products'] = get_products();
		$data['sumberdata'] = array('' => '','SP4N' => 'SP4N','PPID'=>'PPID');
		
		//$data['klasifikasi'] = get_klasifikasi_sla($item_info->kategori, $item_info->info);
		$data['klasifikasi'] = get_klasifikasi();
		$data['subklasifikasi'] = get_subklasifikasi($item_info->klasifikasi_id);
		$data['dir_rujukan'] = get_direktorat_rujukan();
		
		$data['kabs'] = get_cities($item_info->iden_provinsi);
		$data['sla'] = get_sla();
		//$data['sla'] = array();
		/*
		if(!empty($item_info->klasifikasi))
		{
			$array = array();
			$subkla = $this->Ticket->get_subklasifikasi_sla($item_info->kategori, $item_info->info, $item_info->klasifikasi_id);
			
			foreach($subkla as $k)
			{
				$array[$k->id] = $k->subklasifikasi;
			}
			$data['subklasifikasi'] = $array;
		}
		else
			$data['subklasifikasi'] = array(0 => '');*/
		
		
		
		$data['back_url'] = site_url('drafts');
		$data['upload_url'] = site_url('drafts/do_upload');
		
		$data['delete_file_url'] = site_url('drafts/delete_file');
		
		//$data['commodities_p'] = print_komoditi('P');
		//$data['commodities_i'] = print_komoditi('I');
		
		$data['upload_config'] = $this->config->item('upload_draft_setting');
		$data['subkla'] = get_subklasifikasi_json();
		
		$data['mode'] = 'EDIT';
		
		$formtype = $item_info->category;
		
		if($formtype == '2')
		{
			$data['page_title'] = 'Ubah Data PPID (Draft)';
			$this->load->view('drafts/ppid/form', $data);
		}
		elseif($formtype == '3')
		{
			$data['page_title'] = 'Ubah Data Keberatan (Draft)';
			$this->load->view('drafts/keberatan/form', $data);
		}
		elseif($formtype == '4')
		{
			$data['page_title'] = 'Ubah Data Sengketa (Draft)';
			$this->load->view('drafts/sengketa/form', $data);
		}
		else
			$this->load->view('drafts/form', $data);
	}

    public function save($item_id = -1)
	{
		echo saveToDBDraft($item_id, $this->input->post());
	}
	
	

	public function confirm_send($item_id = -1)
	{
		$data['item_id'] = $item_id;
		$this->load->view('drafts/confirm_send', $data);
	}
	
	public function send_ticket($item_id = -1)
	{
		$item_info = $this->DraftTemp->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;
		
		/*$rujukan_info = $this->Draft->get_rujukan_info($item_id);
		foreach(get_object_vars($rujukan_info) as $property => $value)
		{
			$rujukan_info->$property = $this->xss_clean($value);
		}
		$data['rujukan_info'] = $rujukan_info;*/
		
		$tglpengaduan = $item_info->tglpengaduan;
		
		$prefix = 'AAA';
			
		if($item_info->kota == 'PUSAT')
			$prefix = 'PST';
		else
			$prefix = $this->Balai->get_prefix($item_info->kota);
		
		$item_data['trackid'] = $this->DraftTemp->generate_ticketid($item_info->kota, $prefix, $tglpengaduan);
		$item_data['history'] = '<li class="smaller">Pada '.date('Y-m-d H:i:s').' layanan dibuat oleh '.$this->session->name.'</li>';
		
		
		/*//get sla	
		if($item_info->jenis == 'PPID')
		{
			//$item_data['sla'] = 14;
			if($item_info->info == 'I')
				$item_data['sla'] = 17;
			elseif($item_info->info == 'P')
				$item_data['sla'] = 30;
		}
		else
		{
			//$item_data['sla'] = $this->Sla->get_sla($item_info->kategori, $item_info->info, $item_info->klasifikasi_id, $item_info->subklasifikasi_id);
			$item_data['sla'] = 7;
		}*/
		
		$sla = 5; //default
		if($item_info->category == '1') //Layanan
		{
			if($item_info->is_rujuk == '1')
			{
				//$sla = max($rujukan_info->sla1, $rujukan_info->sla2, $rujukan_info->sla3, $rujukan_info->sla4, $rujukan_info->sla5);
			}
			else
			{
				$sla = 5;
			}
		}
		elseif($item_info->category == '2') //PPID
		{
			$sla = 17;
		}
		elseif($item_info->category == '3') //Keberatan
		{
			$sla = 30;
		}
		elseif($item_info->category == '4') //Sengketa
		{
			$sla = 30;
		}
		$item_data['sla'] = $sla;
		
		$item_data['direktorat'] = $item_info->direktorat;
		$item_data['direktorat2'] = $item_info->direktorat2;
		$item_data['direktorat3'] = $item_info->direktorat3;
		$item_data['direktorat4'] = $item_info->direktorat4;
		$item_data['direktorat5'] = $item_info->direktorat5;
		
		$item_data['d1_prioritas'] = $item_info->d1_prioritas;
		$item_data['d2_prioritas'] = $item_info->d2_prioritas;
		$item_data['d3_prioritas'] = $item_info->d3_prioritas;
		$item_data['d4_prioritas'] = $item_info->d4_prioritas;
		$item_data['d5_prioritas'] = $item_info->d5_prioritas;
		
		$item_data['tglpengaduan'] = $tglpengaduan;
		
		if($this->DraftTemp->save_to_ticket($item_data, $item_id))
		{
			if($item_info->is_rujuk == '1')
			{
				$data = array(
					'trackid' => $item_data['trackid'],
					'isu_topik' => $item_info->isu_topik
				);
				$email_body = $this->load->view('mail/ticket_assigned_to_you', $data, TRUE);
				$timestamp = date('Y-m-d H:i:s');
				
				$dir_array = array();
				array_push($dir_array, $item_info->direktorat);
				array_push($dir_array, $item_info->direktorat2);
				array_push($dir_array, $item_info->direktorat3);
				array_push($dir_array, $item_info->direktorat4);
				array_push($dir_array, $item_info->direktorat5);
				
				$users_data = $this->User->get_users_in_dir($dir_array);
				
				foreach($users_data->result() as $row)
				{
					//insert emails
					$email_data = array(
						'mail_title' => 'Rujukan ditujukan kepada Anda',
						'mail_to' => $row->email,
						'mail_body' => $email_body,
						'created_date' => $timestamp,
						'ticket_id' => $item_data['trackid'],
						'status' => 0
					);
					$this->DraftTemp->insert_mail($email_data);
					
					//insert notifications
					$notif_data = array(
						'ticket_id' => $item_data['trackid'],
						'title' => 'Rujukan',
						'message' => $email_body,
						'created_date' => $timestamp,
						'user_id' => $row->id
					);
					
					$this->Notification->save($notif_data);
					
					if(!empty($row->no_hp))
					{
						//insert sms
						$konten = '[SIMPELLPK]Yth. Bpk/Ibu '.$row->name.', Terdapat rujukan untuk Anda dengan ID '.$item_data['trackid'];
						$sms_data = array(
							'no_tujuan' => $row->no_hp,
							'konten' => $konten,
							'created_date' => $timestamp,
							'ticket_id' => $item_data['trackid'],
							'is_sent' => 0
						);
						$this->DraftTemp->insert_sms($sms_data);
					}
				}
				
			}
			
			
			$this->session->set_flashdata('flash', array('success' => TRUE, 'message' => '<i class="fa fa-check-circle" aria-hidden="true"></i> Data berhasil dikirim dengan ID layanan '.$item_data['trackid']));
			echo json_encode(array('success' => TRUE, 'message' => 'Data berhasil dikirim', 'id' => $item_data['id']));
		}
		else
		{
			$this->session->set_flashdata('flash', array('success' => FALSE, 'message' => 'Data gagal dikirim', 'id' => $item_id));
			echo json_encode(array('success' => FALSE, 'message' => 'Data gagal dikirim', 'id' => $item_id));
		}
		
		
		
		//redirect('drafts/list_all');
	}

	public function delete()
	{
		$items_to_delete = $this->input->post('ids');

		if($this->DraftTemp->delete_list($items_to_delete))
		{
			$message = $this->lang->line('items_successful_deleted') . ' ' . count($items_to_delete) . ' ' . $this->lang->line('items_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_cannot_be_deleted')));
		}
	}
	
	public function get_kab($prov_id = 0)
	{
		
		$prov_id = $this->xss_clean($prov_id);
		$kabs = $this->Ticket->get_kab($prov_id);

		echo json_encode($kabs);
	}
	
	public function get_subklasifikasi($kla = 0)
	{
		
		$kla = $this->xss_clean($kla);
		$subklasifikasi = $this->Ticket->get_subklasifikasi($kla);

		echo json_encode($subklasifikasi);
	}
	
	
	public function upload_lamp_pengaduan()
	{
		$draftid = $this->input->post('draftid');
		
		if($draftid)
		{
			$this->load->library('upload', $this->config->item('upload_draft_setting'));
			
			$jumlah_berkas = count($_FILES['file']['name']);
			for($i = 0; $i < $jumlah_berkas;$i++)
			{
				 if(!empty($_FILES['file']['name'][$i]))
				 {
					$_FILES['file_']['name'] = $_FILES['file']['name'][$i];
					$_FILES['file_']['type'] = $_FILES['file']['type'][$i];
					$_FILES['file_']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
					$_FILES['file_']['error'] = $_FILES['file']['error'][$i];
					$_FILES['file_']['size'] = $_FILES['file']['size'][$i];
					
					
					if($this->upload->do_upload('file_'))
					{
						$data = $this->upload->data();
						$att_data = array(
							'saved_name' => $data['file_name'],
							'real_name' => $data['orig_name'],
							//'size' => $data['file_size'],
							'size' => $_FILES['file_']['size'],
							//'file_type' => $data['file_type'],
							'draft_id' => $draftid,
							'mode' => $this->input->post('mode')
						);
						
						if($this->Draft->save_attachment($att_data))
						{
							$id = $att_data['id'];
							echo json_encode(array('id' => $id, 'url' => base_url().'uploads/drafts/'.$att_data['saved_name'], 'error'=>0,'message'=>''));
						}
					}
					else
					{
						echo json_encode(array('error' => 1, 'message' => $this->upload->display_errors()));
					}
				 }
			}
			
		}
		
	}
	
	public function upload_lamp_jawaban()
	{
		$draftid = $this->input->post('draftid');
		
		if($draftid)
		{
			$this->load->library('upload', $this->config->item('upload_draft_setting'));
			
			$jumlah_berkas = count($_FILES['file2']['name']);
			for($i = 0; $i < $jumlah_berkas;$i++)
			{
				 if(!empty($_FILES['file2']['name'][$i]))
				 {
					$_FILES['file2_']['name'] = $_FILES['file2']['name'][$i];
					$_FILES['file2_']['type'] = $_FILES['file2']['type'][$i];
					$_FILES['file2_']['tmp_name'] = $_FILES['file2']['tmp_name'][$i];
					$_FILES['file2_']['error'] = $_FILES['file2']['error'][$i];
					$_FILES['file2_']['size'] = $_FILES['file2']['size'][$i];
					
					
					if($this->upload->do_upload('file2_'))
					{
						$data = $this->upload->data();
						$att_data = array(
							'saved_name' => $data['file_name'],
							'real_name' => $data['orig_name'],
							//'size' => $data['file_size'],
							'size' => $_FILES['file2_']['size'],
							//'file_type' => $data['file_type'],
							'draft_id' => $draftid,
							'mode' => $this->input->post('mode')
						);
						
						if($this->Draft->save_attachment($att_data))
						{
							$id = $att_data['id'];
							echo json_encode(array('id' => $id, 'url' => base_url().'uploads/drafts/'.$att_data['saved_name'], 'error'=>0,'message'=>''));
						}
					}
					else
					{
						echo json_encode(array('error' => 1, 'message' => $this->upload->display_errors()));
					}
				 }
			}
			
		}
		
	}
	
	public function get_attachments() //mode 0 = pertanyaan, 1 = jawaban, 2 = replies
	{
		$item_id = $this->input->get('draftid');
		$mode = $this->input->get('mode');
		$files = $this->Draft->get_attachments($item_id, $mode);
		//print_r($files->result());
		$files_array = array();
		
		foreach($files->result() as $f)
		{
			$files_array[] = array(
				'att_id' => $f->att_id,
				'name' => $f->real_name,
				'size' => $f->size,
				//'url' => base_url().'uploads/drafts/'.$f->saved_name,
				'url' => site_url('downloads/download_attachment_draft?att_id='.$f->att_id.'&trackid='.$f->draft_id),
				'mode' => $f->mode
			);
		}
		
		echo json_encode($files_array);
		
		
	}
	
	public function delete_rujukan()
	{
		$item_id = $this->input->post('sid');
		$dir_id = $this->input->post('dir_id');
		
		if($this->Draft->remove_rujukan($item_id, $dir_id))
		{
			echo json_encode(array('error' => 0, 'message' => 'Berhasil menghapus rujukan'));
		}
		else
		{
			echo json_encode(array('error' => 1, 'message' => 'Gagal menghapus rujukan'));
		}
	}
	
	public function delete_file()
	{
		$draftid = $this->input->post('ticketid');
		$mode = $this->input->post('mode');
		$att_id = $this->input->post('att_id');
		
		$att_id = (int)$att_id;
		
		$draft_info = $this->Draft->get_attachment_info($att_id);
		foreach(get_object_vars($draft_info) as $property => $value)
		{
			$draft_info->$property = $this->xss_clean($value);
		}
		
		if(!empty($draft_info->saved_name))
		{
			
			
			//$path = APPPATH.'../public/uploads/drafts/'.$draft_info->saved_name;
			$config = $this->config->item('upload_draft_setting');
			$path = $config['upload_path'].$draft_info->saved_name;
			unlink($path);
			
			if($this->Draft->delete_attachment($att_id))
			{
				echo json_encode(array('error' => 0, 'message' => 'Berhasil menghapus file'));
			}
			else
			{
				echo json_encode(array('error' => 1, 'message' => 'Gagal menghapus file'));
			}
		}
		
		
	}
	
	public function suggest_unit_teknis()
	{
		//$suggestions = $this->xss_clean($this->Ticket->get_unit_teknis_suggestions($this->input->get('term')));
		$suggestions = $this->xss_clean($this->Draft->get_unit_teknis_suggestions($this->input->get('term'),$this->input->get('item_id')));
		echo json_encode($suggestions);
	}
}
?>
