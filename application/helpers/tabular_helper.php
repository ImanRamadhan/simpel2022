<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tabular views helper
 */



/*
Basic tabular headers function
*/
function transform_headers($array, $readonly = FALSE, $editable = TRUE)
{
	$result = array();

	if(!$readonly)
	{
		$array = array_merge(array(array('checkbox' => 'select', 'sortable' => FALSE)), $array);
	}

	if($editable)
	{
		$array[] = array('edit' => '','class'=>'text-nowrap');
	}

	foreach($array as $element)
	{
		reset($element);
		$result[] = array('field' => key($element),
			'title' => current($element),
			'switchable' => isset($element['switchable']) ? $element['switchable'] : !preg_match('(^$|&nbsp)', current($element)),
			'sortable' => isset($element['sortable']) ? $element['sortable'] : current($element) != '',
			'checkbox' => isset($element['checkbox']) ? $element['checkbox'] : FALSE,
			//'class' => isset($element['checkbox']) || preg_match('(^$|&nbsp)', current($element)) ? 'print_hide' : '',
			'class' => isset($element['class']) ? $element ['class'] : '',
			'sorter' => isset($element['sorter']) ? $element ['sorter'] : '',
			'align' => isset($element['align']) ? $element ['align'] : 'center',
			'visible' => isset($element['visible']) ? $element ['visible'] : true,
			'width' => isset($element['width']) ? $element ['width'] : '',
			);
	}
	

	return json_encode($result);
}

function get_tickets_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		
		array('no' => 'No.', 'align' => 'center', 'sortable' => false),
		array('id' => 'ID', 'visible' => false),
		array('trackid' => 'ID Layanan', 'class' => 'text-nowrap', 'titleTooltip' => ''),
		
		array('tglpengaduan' => 'Tgl Layanan', 'align' => 'center'),
		array('lastchange' => 'Update Terakhir', 'align' => 'center'),
		array('iden_nama' => 'Nama Konsumen','align' => 'left'),
		array('outstanding' => 'Outstanding','align' => 'center'),
		array('status' => 'Status','align' => 'center'),
		array('is_rujuk' => 'Dirujuk','align' => 'center'),
		//array('is_sent' => 'Notifikasi','align' => 'center'),
		array('tl' => 'TL','align' => 'center'),
		array('fb' => 'FB','align' => 'center'),
		array('is_verified' => 'Verified','align' => 'center'),
	);
	
	
	return transform_headers($headers);
}

function get_ticket_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	$ticketid = $item->id;
	
	//if($item->jenis == 'PPID' || $item->category == '2' || $item->category == '3')
	//	$controller_name = 'ppid';
	
	$view_link = anchor($controller_name."/view/$ticketid?r=".rand(1000,9999), "<i class='fa fa-search-plus'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_view')));
	$edit_link = anchor($controller_name."/edit/$ticketid", "<i class='fa fa-fw fa-pencil-alt'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_edit')));
	$pdf_link = anchor($controller_name."/print_pdf/$ticketid?r=".rand(1000,9999), "<i class='fa fa-print'></i>",
			array('class'=>'', 'target' => '_blank', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_pdf')));
			
	
	/*$problem = str_replace('&gt;', '', $item->problem);
	$problem = str_replace('<br />', '', $problem);
	$problem = str_replace('/>', '', $problem);
	$problem = str_replace('>', '', $problem);*/
	
	$problem = preg_replace("~[^0-9a-z\\s]~i", "", $item->problem);
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'trackid' => anchor($controller_name."/view/$ticketid?r=".rand(1000,9999), $item->trackid, array('title' => $problem)),
		'tglpengaduan' => $item->tglpengaduan,
		'lastchange' => time_since($item->lastchange),
		'iden_nama' => $item->iden_nama,
		'edit' => $view_link.' '.$edit_link. ' '.$pdf_link
		);
	
	$data['status'] = get_status($item->status); 
	
	$data['tl'] = ($item->tl == 1)?"<span class='text-success'>Y</span>":"<span class='text-danger'>N</span>";
	$data['fb'] = ($item->fb == 1)?"<span class='text-success'>Y</span>":"<span class='text-danger'>N</span>";
	//$data['is_rujuk'] = ($item->is_rujuk == 1)?'<span class="badge badge-primary">Ya</span>':'Tidak';
	
	if($item->is_rujuk == '1')
		$data['is_rujuk'] = '<span class="badge badge-primary">Ya</span>';
	elseif($item->is_rujuk == '0')
		$data['is_rujuk'] = 'Tidak';
	else
		$data['is_rujuk'] = '-';
	
	$data['outstanding'] = ($item->outstanding ? $item->outstanding : '');
	//$data['is_sent'] = (($item->is_sent && $item->is_rujuk) ? 'Sent' : '');
	
	$data['is_verified'] = ($item->is_verified == 1)?"<span class='text-success'>Y</span>":"<span class='text-danger'>N</span>";
	
	return $data;
}

function get_ppids_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		
		array('no' => 'No.', 'align' => 'center', 'sortable' => false),
		array('id' => 'ID', 'visible' => false),
		array('trackid' => 'ID Layanan', 'class' => 'text-nowrap', 'titleTooltip' => 'ID'),
		array('tglpengaduan' => 'Tgl Layanan', 'align' => 'center'),
		array('iden_nama' => 'Nama','align' => 'left'),
		array('iden_telp' => 'Nomor Kontak','align' => 'left'),
		array('profesi' => 'Pekerjaan','align' => 'left'),
		array('rincian' => 'Informasi yang Diminta','align' => 'left'),
		array('tujuan' => 'Tujuan Penggunaan Informasi','align' => 'left'),
		
	);
	
	
	return transform_headers($headers, TRUE);
}

function get_ppid_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	$view_link = anchor($controller_name."/view/$item->id?r=".rand(1000,9999), "<i class='fa fa-search-plus'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_view')));
	$edit_link = anchor($controller_name."/edit/$item->id", "<i class='fa fa-fw fa-pencil-alt'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_edit')));
	//$pdf_link = anchor($controller_name."/pdf/$item->id", "<i class='fa fa-print'></i>",
	//		array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_pdf')));
	$pdf_link = '';	
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		//'trackid' => anchor($controller_name."/view/$item->id?r=".rand(1000,9999), $item->trackid),
		'trackid' => anchor($controller_name."/view/$item->id?r=".rand(1000,9999), $item->trackid, array('title' => $item->isu_topik)),
		'tglpengaduan' => $item->tglpengaduan,
		'iden_nama' => $item->iden_nama,
		'rincian' => $item->rincian,
		'tujuan' => $item->tujuan,
		'profesi' => $item->profesi,
		'edit' => $view_link.' '.$edit_link. ' '.$pdf_link
		);
		
	/*$arr = array();
	if(!empty($item->iden_telp))
		array_push($arr, $item->iden_telp);
	
	if(!empty($item->iden_email))
		array_push($arr, $item->iden_email);
	
	$no_kontak = '';
	if(count($arr) > 0)
		$no_kontak = implode(' / ', $arr);
	
	$data['no_kontak'] = $no_kontak;*/
	
	$data['iden_telp'] = $item->iden_telp;
	
	
	
	return $data;
}

function get_ppids_keberatan_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		
		array('no' => 'No.', 'align' => 'center', 'sortable' => false),
		array('id' => 'ID', 'visible' => false),
		array('trackid' => 'ID Layanan', 'class' => 'text-nowrap', 'titleTooltip' => 'ID'),
		array('tglpengaduan' => 'Tgl Layanan', 'align' => 'center'),
		array('iden_nama' => 'Nama','align' => 'left'),
		array('iden_telp' => 'Nomor Kontak','align' => 'left'),
		array('profesi' => 'Pekerjaan','align' => 'left'),
		array('kuasa_nama' => 'Nama Kuasa Pemohon','align' => 'left'),
		array('alasan_keberatan' => 'Alasan Keberatan','align' => 'left'),
		
	);
	
	
	return transform_headers($headers, TRUE);
}

function get_ppid_keberatan_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	$view_link = anchor($controller_name."/view/$item->id?r=".rand(1000,9999), "<i class='fa fa-search-plus'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_view')));
	$edit_link = anchor($controller_name."/edit/$item->id", "<i class='fa fa-fw fa-pencil-alt'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_edit')));
	//$pdf_link = anchor($controller_name."/pdf/$item->id", "<i class='fa fa-print'></i>",
	//		array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_pdf')));
	$pdf_link = '';	
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		//'trackid' => anchor($controller_name."/view/$item->id?r=".rand(1000,9999), $item->trackid),
		'trackid' => anchor($controller_name."/view/$item->id?r=".rand(1000,9999), $item->trackid, array('title' => $item->kasus_posisi)),
		'tglpengaduan' => $item->tglpengaduan,
		'iden_nama' => $item->iden_nama,
		'kuasa_nama' => $item->kuasa_nama,
		//'alasan_keberatan' => $item->alasan_keberatan,
		'profesi' => $item->profesi,
		'edit' => $view_link.' '.$edit_link. ' '.$pdf_link
		);
		
	$alasans = explode(',', $item->alasan_keberatan);
	$alasans_str = '';
	
	for($i=0; $i<count($alasans); $i++)
	{
		if($alasans[$i] == 'a')
			$alasans_str .= 'Permohonan Informasi Ditolak, ';
		elseif($alasans[$i] == 'b')
			$alasans_str .= 'Informasi berkala tidak disediakan, ';
		elseif($alasans[$i] == 'c')
			$alasans_str .= 'Permintaan informasi tidak ditanggapi, ';
		elseif($alasans[$i] == 'd')
			$alasans_str .= 'Permintaan informasi ditanggapi tidak sebagaimana yang diminta, ';
		elseif($alasans[$i] == 'e')
			$alasans_str .= 'Permintaan informasi tidak dipenuhi, ';
		elseif($alasans[$i] == 'f')
			$alasans_str .= 'Biaya yang dikenakan tidak wajar, ';
		elseif($alasans[$i] == 'g')
			$alasans_str .= 'Informasi disampaikan melebihi jangka waktu yang ditentukan';
	}
	//$alasans_str = trim($alasans_str);
	if(strlen($alasans_str) > 0)
		$alasans_str = substr(trim($alasans_str),0,-1);
	
	$data['alasan_keberatan'] = $alasans_str;
	$data['iden_telp'] = $item->iden_telp;
	
	
	
	return $data;
}

function get_drafts_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		
		array('no' => 'No.', 'align' => 'center', 'sortable' => false),
		array('id' => 'ID', 'visible' => false),
		array('trackid' => 'ID Draft', 'class' => 'text-nowrap', 'titleTooltip' => 'ID Draft'),
		
		//array('tglpengaduan' => 'Tgl Pengaduan', 'align' => 'center'),
		array('lastchange' => 'Update Terakhir', 'align' => 'center'),
		array('iden_nama' => 'Nama Konsumen','align' => 'left'),
		//array('desc' => 'Jenis Produk','align' => 'center'),
		array('isu_topik' => 'Isu Topik','align' => 'left'),
		//array('outstanding' => 'Outstanding','align' => 'center'),
		array('status' => 'Status','align' => 'center'),
		array('tipe' => 'Jenis Formulir','align' => 'center')
		/*array('is_rujuk' => 'Dirujuk','align' => 'center'),
		array('notifikasi' => 'Notifikasi','align' => 'center'),
		array('tl' => 'TL','align' => 'center'),
		array('fb' => 'FB','align' => 'center'),
		array('is_verified' => 'Verified','align' => 'center'),*/
	);
	
	
	return transform_headers($headers);
}

function get_draft_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	//$view_link = anchor($controller_name."/view/$item->id?r=".rand(1000,9999), "<i class='fa fa-search-plus'></i>",
	//		array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_view')));
	$edit_link = anchor($controller_name."/edit/$item->id", "<i class='fa fa-fw fa-pencil-alt'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_edit')));
	//$pdf_link = anchor($controller_name."/pdf/$item->id", "<i class='fa fa-print'></i>",
	//		array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_pdf')));
			
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'trackid' => anchor($controller_name."/edit/$item->id?r=".rand(1000,9999), '#'.$item->id),
		//'tglpengaduan' =>  anchor($controller_name."/edit/$item->id?r=".rand(1000,9999), $item->tglpengaduan),
		'iden_nama' => $item->iden_nama,
		//'desc' => $item->jenis_produk,
		//'isu_topik' => $item->isu_topik,
		'isu_topik' => str_replace('%','&#37;',$item->isu_topik),
		//'lastchange' =>  anchor($controller_name."/edit/$item->id?r=".rand(1000,9999), $item->lastchange),
		'lastchange' => time_since($item->lastchange),
		'edit' => $edit_link
		);
	
	//$data['status'] = get_status($item->status); 
	$data['status'] = 'Draft';
	
	if($item->category == '1')
		$data['tipe'] = 'Layanan';
	elseif($item->category == '2')
		$data['tipe'] = '(PPID) Permintaan Informasi Publik';
	elseif($item->category == '3')
		$data['tipe'] = '(PPID) Pengajuan Keberatan';	
	elseif($item->category == '4')
		$data['tipe'] = 'Sengketa';
	else
		$data['tipe'] = '';
	
	/*$data['tl'] = ($item->tl == 1)?'Y':'N';
	$data['fb'] = ($item->fb == 1)?'Y':'N';
	$data['is_rujuk'] = ($item->is_rujuk == 1)?'<span class="badge badge-primary">Ya</span>':'Tidak';
	$data['is_verified'] = ($item->is_verified == 1)?'Y':'N';*/
	
	return $data;
}

/* User */
function get_user_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('id' => $CI->lang->line('users_id'), 'visible' => false),
		array('no' => 'No.', 'sortable' => false),
		array('user' => $CI->lang->line('users_username') , 'align' => 'left'),
		array('name' => $CI->lang->line('users_full_name'), 'align' => 'left'),
		
		array('email' => $CI->lang->line('users_email'),'align' => 'left','sortable' => false),
		//array('no_hp' => 'No.HP', 'align' => 'left', 'sortable' => false),
		array('city' => $CI->lang->line('users_city')),
		array('dir_name' => 'Unit Kerja', 'align' => 'left','sortable' => false),
		
		
		array('isadmin' => 'Tipe User'),
		array('is_active' => 'Status')
	);

	if($CI->User->has_grant('messages', $CI->session->userdata('user_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	return transform_headers($headers);
}

function get_user_data_row($user, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));

	$data = array (
		'id' => $user->id,
		'no' => $no.'.',
		'user' => $user->user,
		'name' => $user->name,
		'email' => $user->email,
		//'no_hp' => $user->no_hp,
		'city' => $user->city,
		'dir_name' => $user->dir_name,
		'edit' => anchor($controller_name."/edit/$user->id", '<i class="fa fa-edit" aria-hidden="true"></i>',
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update'))
	));
	
	$data['isadmin'] = get_user_role($user->isadmin);
	
	$data['is_active'] = $user->is_active;
	
	switch($data['is_active'])
	{
		case '0':
			$data['is_active'] = '<span class="badge badge-boxed badge-danger">Tidak Aktif</span>';
			break;
		case '1':
			$data['is_active'] = '<span class="badge badge-boxed badge-primary">Aktif</span>';
			break;
		default:
			$data['is_active'] = '';
	}
	
	
	return $data;
}



/*
Kota/Balai/Loka
*/
function get_balais_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('id' => 'ID', 'visible' => false, 'width' => '80px'),
		array('no' => 'No.', 'sortable' => false, 'width' => '80px'),
		array('nama_balai' => 'Nama Balai/Loka', 'align' => 'left'),
		array('tipe_balai' => 'Tipe', 'align' => 'center'),
		array('kode_prefix' => 'Kode Prefix', 'align' => 'center'),
		array('alamat' => 'Alamat', 'align' => 'center'),
		array('no_telp' => 'No. Telp', 'align' => 'left'),
		array('no_fax' => 'No. Fax', 'align' => 'left'),
		array('email' => 'Email', 'align' => 'left'),
	);
	
	

	if($CI->User->has_grant('messages', $CI->session->userdata('user_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	
	return transform_headers($headers, TRUE);
}


function get_balai_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'nama_balai' => $item->nama_balai,
		'kode_prefix' => $item->kode_prefix,
		'no_telp' => $item->no_telp,
		'no_fax' => $item->no_fax,
		'alamat' => $item->alamat,
		'email' => $item->email,
		'edit' => anchor($controller_name."/view/$item->id", '<i class="fa fa-edit" aria-hidden="true"></i>',
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update')))
		);
	
	$tipe = $item->tipe_balai;
	
	switch($tipe)
	{
		case '1':
			$tipe = 'Balai Besar';
			break;
		case '2':
			$tipe = 'Balai Tipe A';
			break;
		case '3':
			$tipe = 'Balai Tipe B';
			break;
		case '4':
			$tipe = 'Loka';
			break;
		default:
			$tipe = '';
	}
	
	$data['tipe_balai'] = $tipe;
		
			
	return $data;
}

function get_cities_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('id' => 'ID', 'visible' => false, 'width' => '80px'),
		array('no' => 'No.', 'sortable' => false, 'width' => '80px'),
		array('nama_kota' => 'Nama Kota', 'align' => 'left'),
		
		
	);
	
	

	if($CI->User->has_grant('messages', $CI->session->userdata('user_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	
	return transform_headers($headers);
}


function get_city_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'nama_kota' => $item->nama_kota,
		'edit' => anchor($controller_name."/view/$item->id", '<i class="fa fa-edit" aria-hidden="true"></i>',
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update')))
		);
	
	
		
			
	return $data;
}


function get_depts_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('id' => 'ID', 'visible' => false, 'width' => '80px'),
		array('no' => 'No.', 'sortable' => false, 'width' => '80px'),
		array('name' => 'Nama Unit Kerja', 'align' => 'left'),
		array('kode_prefix' => 'Kode', 'align' => 'center'),
		array('kota' => 'Kota', 'align' => 'center'),
		array('dir_status' => 'Status', 'align' => 'center'),
		
	);
	
	

	if($CI->User->has_grant('messages', $CI->session->userdata('user_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	
	return transform_headers($headers);
}


function get_dept_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'name' => $item->name,
		'kota' => $item->kota,
		'kode_prefix' => $item->kode_prefix,
		'edit' => anchor($controller_name."/view/$item->id", '<i class="fa fa-edit" aria-hidden="true"></i>',
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update')))
		);
	
	$data['dir_status'] = $item->dir_status;
	
	switch($data['dir_status'])
	{
		case '0':
			$data['dir_status'] = '<span class="badge badge-boxed badge-danger">Tidak Aktif</span>';
			break;
		case '1':
			$data['dir_status'] = '<span class="badge badge-boxed badge-primary">Aktif</span>';
			break;
		default:
			$data['dir_status'] = '';
	}
	
		
			
	return $data;
}


function get_rujukanmasuk_tickets_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		
		array('no' => 'No.', 'align' => 'center', 'sortable' => false),
		array('id' => 'ID', 'visible' => false),
		array('trackid' => 'ID Layanan', 'class' => 'text-nowrap', 'titleTooltip' => 'aaa'),
		
		array('tglpengaduan' => 'Tgl Layanan', 'align' => 'center'),
		array('tgldirujuk' => 'Tgl Dirujuk', 'align' => 'center', 'sortable' => false),
		array('lastchange' => 'Update Terakhir', 'align' => 'center'),
		array('iden_nama' => 'Nama Konsumen','align' => 'left'),
		//array('outstanding' => 'Outstanding','align' => 'center'),
		array('status' => 'Status','align' => 'center'),
		array('is_rujuk' => 'Dirujuk','align' => 'center'),
		//array('notifikasi' => 'Notifikasi','align' => 'center'),
		array('tl' => 'TL','align' => 'center'),
		array('fb' => 'FB','align' => 'center'),
		array('is_verified' => 'Verified','align' => 'center'),
	);
	
	
	return transform_headers($headers, TRUE);
}

function get_rujukanmasuk_ticket_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	$view_link = anchor("rujukan/view/$item->id?ref=rujukan_masuk&r=".rand(1000,9999), "<i class='fa fa-search-plus'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_view')));
	/*$edit_link = anchor("tickets/edit/$item->id", "<i class='fa fa-fw fa-pencil-alt'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_edit')));
	$pdf_link = anchor("tickets/print_pdf/$item->id", "<i class='fa fa-print'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_pdf')));
	$copy_link = anchor("drafts/copy_from/$item->id", "<i class='fa fa-copy'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_copy')));*/		
	$edit_link = '';
	$pdf_link = '';
	$copy_link = '';
	
	/*$problem = str_replace('&gt;', '', $item->prod_masalah);
	$problem = str_replace('<br />', '', $problem);
	$problem = str_replace('/>', '', $problem);
	$problem = str_replace('>', '', $problem);*/
	
	$problem = preg_replace("~[^0-9a-z\\s]~i", "", $item->prod_masalah);
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		//'trackid' => anchor("rujukan/view/$item->id?ref=rujukan_masuk&r=".rand(1000,9999), $item->trackid),
		'trackid' => anchor("rujukan/view/$item->id?ref=rujukan_masuk&r=".rand(1000,9999), $item->trackid, array('title' => $problem)),
		'tglpengaduan' => $item->tglpengaduan,
		// 'tgldirujuk' => '2024-01-01',
		'lastchange' => time_since($item->lastchange),
		'iden_nama' => $item->iden_nama,
		'edit' => $view_link.' '.$edit_link. ' '.$pdf_link. ' '.$copy_link
	);

	switch($CI->session->direktoratid){
		case $item->direktorat:
			$data['tgldirujuk'] = $item->tgl_rujuk1;
		
		case $item->direktorat2:
			$data['tgldirujuk'] = $item->tgl_rujuk2;

		case $item->direktorat3:
			$data['tgldirujuk'] = $item->tgl_rujuk3;
	
		case $item->direktorat4:
			$data['tgldirujuk'] = $item->tgl_rujuk4;

		case $item->direktorat5:
			$data['tgldirujuk'] = $item->tgl_rujuk5;
	}

	$data['status'] = get_status($item->status);
	
	// $data['status'] = get_status(5); 

	if($item->status == '2' && $CI->session->userdata('user_id') != $item->owner){
		$data['status'] = get_status(0); 
	}
	
	$data['tl'] = ($item->tl == 1)?'Y':'N';
	$data['fb'] = ($item->fb == 1)?'Y':'N';
	$data['is_rujuk'] = ($item->is_rujuk == 1)?'<span class="badge badge-primary">Ya</span>':'Tidak';
	$data['is_verified'] = ($item->is_verified == 1)?'Y':'N';
	
	return $data;
}


function get_rujukankeluarsaya_tickets_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		
		array('no' => 'No.', 'align' => 'center', 'sortable' => false),
		array('id' => 'ID', 'visible' => false),
		array('trackid' => 'ID Layanan', 'class' => 'text-nowrap', 'titleTooltip' => 'aaa'),
		
		array('tglpengaduan' => 'Tgl Layanan', 'align' => 'center'),
		array('lastchange' => 'Update Terakhir', 'align' => 'center'),
		array('iden_nama' => 'Nama Konsumen','align' => 'left'),
		//array('outstanding' => 'Outstanding','align' => 'center'),
		array('status' => 'Status','align' => 'center'),
		array('is_rujuk' => 'Dirujuk','align' => 'center'),
		//array('notifikasi' => 'Notifikasi','align' => 'center'),
		array('tl' => 'TL','align' => 'center'),
		array('fb' => 'FB','align' => 'center'),
		array('is_verified' => 'Verified','align' => 'center'),
	);
	
	
	return transform_headers($headers, TRUE);
}

function get_rujukankeluarsaya_ticket_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	$view_link = anchor("tickets/view/$item->id?ref=rujukan_keluar_saya&r=".rand(1000,9999), "<i class='fa fa-search-plus'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_view')));
	$edit_link = anchor("tickets/edit/$item->id", "<i class='fa fa-fw fa-pencil-alt'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_edit')));
	$pdf_link = anchor("tickets/print_pdf/$item->id", "<i class='fa fa-print'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_pdf')));
			
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'trackid' => anchor("tickets/view/$item->id?ref=rujukan_keluar_saya&r=".rand(1000,9999), $item->trackid),
		'tglpengaduan' => $item->tglpengaduan,
		'lastchange' => time_since($item->lastchange),
		'iden_nama' => $item->iden_nama,
		'edit' => $view_link.' '.$edit_link. ' '.$pdf_link
		);
	
	$data['status'] = get_status($item->status); 
	
	$data['tl'] = ($item->tl == 1)?'Y':'N';
	$data['fb'] = ($item->fb == 1)?'Y':'N';
	$data['is_rujuk'] = ($item->is_rujuk == 1)?'<span class="badge badge-primary">Ya</span>':'Tidak';
	$data['is_verified'] = ($item->is_verified == 1)?'Y':'N';
	
	return $data;
}

function get_rujukankeluar_tickets_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		
		array('no' => 'No.', 'align' => 'center', 'sortable' => false),
		array('id' => 'ID', 'visible' => false),
		array('trackid' => 'ID Layanan', 'class' => 'text-nowrap', 'titleTooltip' => 'aaa'),
		
		array('tglpengaduan' => 'Tgl Layanan', 'align' => 'center'),
		array('lastchange' => 'Update Terakhir', 'align' => 'center'),
		array('iden_nama' => 'Nama Konsumen','align' => 'left'),
		//array('outstanding' => 'Outstanding','align' => 'center'),
		array('status' => 'Status','align' => 'center'),
		array('is_rujuk' => 'Dirujuk','align' => 'center'),
		//array('notifikasi' => 'Notifikasi','align' => 'center'),
		array('tl' => 'TL','align' => 'center'),
		array('fb' => 'FB','align' => 'center'),
		array('is_verified' => 'Verified','align' => 'center'),
	);
	
	
	return transform_headers($headers, TRUE);
}

function get_rujukankeluar_ticket_data_row($item, $no)
{
	$CI =& get_instance();
	//$controller_name = strtolower(get_class($CI));
	
	$view_link = anchor("tickets/view/$item->id?ref=rujukan_keluar&r=".rand(1000,9999), "<i class='fa fa-search-plus'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_view')));
	$edit_link = anchor("tickets/edit/$item->id", "<i class='fa fa-fw fa-pencil-alt'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_edit')));
	$pdf_link = anchor("tickets/print_pdf/$item->id", "<i class='fa fa-print'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_pdf')));
			
	/*$problem = str_replace('&gt;', '', $item->prod_masalah);
	$problem = str_replace('<br />', '', $problem);
	$problem = str_replace('/>', '', $problem);
	$problem = str_replace('>', '', $problem);*/
	
	$problem = preg_replace("~[^0-9a-z\\s]~i", "", $item->prod_masalah);
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		//'no' => trim($item->prod_masalah),
		//'trackid' => anchor("tickets/view/$item->id?ref=rujukan_keluar&r=".rand(1000,9999), $item->trackid),
		'trackid' => anchor("tickets/view/$item->id?ref=rujukan_keluar&r=".rand(1000,9999), $item->trackid, array('title' => $problem)),
		'tglpengaduan' => $item->tglpengaduan,
		'lastchange' => time_since($item->lastchange),
		'iden_nama' => $item->iden_nama,
		'edit' => $view_link.' '.$edit_link. ' '.$pdf_link
		);
	
	$data['status'] = get_status($item->status); 
	
	$data['tl'] = ($item->tl == 1)?'Y':'N';
	$data['fb'] = ($item->fb == 1)?'Y':'N';
	$data['is_rujuk'] = ($item->is_rujuk == 1)?'<span class="badge badge-primary">Ya</span>':'Tidak';
	$data['is_verified'] = ($item->is_verified == 1)?'Y':'N';
	
	return $data;
}

function get_rujukanstatusclosed_tickets_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		
		array('no' => 'No.', 'align' => 'center', 'sortable' => false),
		array('id' => 'ID', 'visible' => false),
		array('trackid' => 'ID Layanan', 'class' => 'text-nowrap', 'titleTooltip' => 'aaa'),
		
		array('tglpengaduan' => 'Tgl Layanan', 'align' => 'center'),
		array('lastchange' => 'Update Terakhir', 'align' => 'center'),
		array('iden_nama' => 'Nama Konsumen','align' => 'left'),
		
		array('status' => 'Status','align' => 'center'),
		array('is_rujuk' => 'Dirujuk','align' => 'center'),
		//array('notifikasi' => 'Notifikasi','align' => 'center'),
		array('tl' => 'TL','align' => 'center'),
		array('fb' => 'FB','align' => 'center'),
		array('is_verified' => 'Verified','align' => 'center'),
	);
	
	
	return transform_headers($headers, TRUE);
}

function get_rujukanstatusclosed_ticket_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	$view_link = anchor("tickets/view/$item->id?ref=rujukan_status_closed&r=".rand(1000,9999), "<i class='fa fa-search-plus'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_view')));
	$edit_link = anchor("tickets/edit/$item->id", "<i class='fa fa-fw fa-pencil-alt'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_edit')));
	$pdf_link = anchor("tickets/print_pdf/$item->id", "<i class='fa fa-print'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_pdf')));
			
	/*$problem = str_replace('&gt;', '', $item->prod_masalah);
	$problem = str_replace('<br />', '', $problem);
	$problem = str_replace('/>', '', $problem);
	$problem = str_replace('>', '', $problem);*/
	
	$problem = preg_replace("~[^0-9a-z\\s]~i", "", $item->prod_masalah);
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		//'trackid' => anchor("tickets/view/$item->id?ref=rujukan_status_closed&r=".rand(1000,9999), $item->trackid),
		'trackid' => anchor("tickets/view/$item->id?ref=rujukan_status_closed&r=".rand(1000,9999), $item->trackid,  array('title' => $problem)),
		'tglpengaduan' => $item->tglpengaduan,
		'lastchange' => time_since($item->lastchange),
		'iden_nama' => $item->iden_nama,
		'edit' => $view_link.' '.$edit_link. ' '.$pdf_link
		);
	
	$data['status'] = get_status($item->status); 
	
	$data['tl'] = ($item->tl == 1)?'Y':'N';
	$data['fb'] = ($item->fb == 1)?'Y':'N';
	$data['is_rujuk'] = ($item->is_rujuk == 1)?'<span class="badge badge-primary">Ya</span>':'Tidak';
	$data['is_verified'] = ($item->is_verified == 1)?'Y':'N';
	
	return $data;
}

function get_rujukanstatustl_tickets_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		
		array('no' => 'No.', 'align' => 'center', 'sortable' => false),
		array('id' => 'ID', 'visible' => false),
		array('trackid' => 'ID Layanan', 'class' => 'text-nowrap', 'titleTooltip' => 'aaa'),
		
		array('tglpengaduan' => 'Tgl Layanan', 'align' => 'center'),
		array('lastchange' => 'Update Terakhir', 'align' => 'center'),
		array('iden_nama' => 'Nama Konsumen','align' => 'left'),
		array('isu_topik' => 'Isu Topik','align' => 'left'),
		//array('outstanding' => 'Outstanding','align' => 'center'),
		array('status' => 'Status','align' => 'center'),
		array('is_rujuk' => 'Dirujuk','align' => 'center'),
		//array('notifikasi' => 'Notifikasi','align' => 'center'),
		array('tl' => 'TL','align' => 'center'),
		array('fb' => 'FB','align' => 'center'),
		array('is_verified' => 'Verified','align' => 'center'),
	);
	
	
	return transform_headers($headers, TRUE);
}

function get_rujukanstatustl_ticket_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	$view_link = anchor("tickets/view/$item->id?ref=rujukan_status_tl&r=".rand(1000,9999), "<i class='fa fa-search-plus'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_view')));
	$edit_link = anchor("tickets/edit/$item->id", "<i class='fa fa-fw fa-pencil-alt'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_edit')));
	$pdf_link = anchor("tickets/print_pdf/$item->id", "<i class='fa fa-print'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_pdf')));
			
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'trackid' => anchor("tickets/view/$item->id?ref=rujukan_status_tl&r=".rand(1000,9999), $item->trackid),
		'tglpengaduan' => $item->tglpengaduan,
		'lastchange' => time_since($item->lastchange),
		'iden_nama' => $item->iden_nama,
		'isu_topik' => $item->isu_topik,
		'edit' => $view_link.' '.$edit_link. ' '.$pdf_link
		);
	
	$data['status'] = get_status($item->status); 
	
	$data['tl'] = ($item->tl == 1)?'Y':'N';
	$data['fb'] = ($item->fb == 1)?'Y':'N';
	$data['is_rujuk'] = ($item->is_rujuk == 1)?'<span class="badge badge-primary">Ya</span>':'Tidak';
	$data['is_verified'] = ($item->is_verified == 1)?'Y':'N';
	
	if(!$item->is_read)
	{
		$data['trackid'] .= ' <i class="fa fa-star" aria-hidden="true"></i>';
	}
	
	return $data;
}

function get_rujukanstatusfb_tickets_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		
		array('no' => 'No.', 'align' => 'center', 'sortable' => false),
		array('id' => 'ID', 'visible' => false),
		array('trackid' => 'ID Layanan', 'class' => 'text-nowrap', 'titleTooltip' => 'aaa'),
		
		array('tglpengaduan' => 'Tgl Layanan', 'align' => 'center'),
		array('lastchange' => 'Update Terakhir', 'align' => 'center'),
		array('iden_nama' => 'Nama Konsumen','align' => 'left'),
		//array('outstanding' => 'Outstanding','align' => 'center'),
		array('status' => 'Status','align' => 'center'),
		array('is_rujuk' => 'Dirujuk','align' => 'center'),
		//array('notifikasi' => 'Notifikasi','align' => 'center'),
		array('tl' => 'TL','align' => 'center'),
		array('fb' => 'FB','align' => 'center'),
		array('is_verified' => 'Verified','align' => 'center'),
	);
	
	
	return transform_headers($headers, TRUE);
}

function get_rujukanstatusfb_ticket_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	$view_link = anchor("tickets/view/$item->id?ref=rujukan_status_fb&r=".rand(1000,9999), "<i class='fa fa-search-plus'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_view')));
	$edit_link = anchor("tickets/edit/$item->id", "<i class='fa fa-fw fa-pencil-alt'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_edit')));
	$pdf_link = anchor("tickets/print_pdf/$item->id", "<i class='fa fa-print'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_pdf')));
			
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'trackid' => anchor("tickets/view/$item->id?ref=rujukan_status_fb&r=".rand(1000,9999), $item->trackid),
		'tglpengaduan' => $item->tglpengaduan,
		'lastchange' => time_since($item->lastchange),
		'iden_nama' => $item->iden_nama,
		'edit' => $view_link.' '.$edit_link. ' '.$pdf_link
		);
	
	$data['status'] = get_status($item->status); 
	
	$data['tl'] = ($item->tl == 1)?'Y':'N';
	$data['fb'] = ($item->fb == 1)?'Y':'N';
	$data['is_rujuk'] = ($item->is_rujuk == 1)?'<span class="badge badge-primary">Ya</span>':'Tidak';
	$data['is_verified'] = ($item->is_verified == 1)?'Y':'N';
	
	return $data;
}


function get_my_tickets_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		
		array('no' => 'No.', 'align' => 'center', 'sortable' => false),
		array('id' => 'ID', 'visible' => false),
		array('trackid' => 'ID Layanan', 'class' => 'text-nowrap', 'titleTooltip' => 'aaa'),
		
		array('tglpengaduan' => 'Tgl Layanan', 'align' => 'center'),
		array('lastchange' => 'Update Terakhir', 'align' => 'center'),
		array('iden_nama' => 'Nama Konsumen','align' => 'left'),
		//array('outstanding' => 'Outstanding','align' => 'center'),
		array('status' => 'Status','align' => 'center'),
		array('is_rujuk' => 'Dirujuk','align' => 'center'),
		//array('notifikasi' => 'Notifikasi','align' => 'center'),
		array('tl' => 'TL','align' => 'center'),
		array('fb' => 'FB','align' => 'center'),
		array('is_verified' => 'Verified','align' => 'center'),
	);
	
	
	return transform_headers($headers);
}

function get_my_ticket_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	
	
	//if($item->jenis == 'PPID' || $item->category == '2' || $item->category == '3')
	//	$controller_name = 'ppid';
	//else
		$controller_name = 'tickets';
	
	$view_link = anchor($controller_name."/view/$item->id?ref=layanan_saya&r=".rand(1000,9999), "<i class='fa fa-search-plus'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_view')));
	$edit_link = anchor($controller_name."/edit/$item->id", "<i class='fa fa-fw fa-pencil-alt'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_edit')));
	$pdf_link = anchor($controller_name."/print_pdf/$item->id", "<i class='fa fa-print'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_pdf')));
			
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'trackid' => anchor($controller_name."/view/$item->id?ref=layanan_saya&r=".rand(1000,9999), $item->trackid),
		'tglpengaduan' => $item->tglpengaduan,
		'lastchange' => time_since($item->lastchange),
		'iden_nama' => $item->iden_nama,
		'edit' => $view_link.' '.$edit_link. ' '.$pdf_link
		);
	
	$data['status'] = get_status($item->status); 
	
	$data['tl'] = ($item->tl == 1)?'Y':'N';
	$data['fb'] = ($item->fb == 1)?'Y':'N';
	$data['is_rujuk'] = ($item->is_rujuk == 1)?'<span class="badge badge-primary">Ya</span>':'Tidak';
	$data['is_verified'] = ($item->is_verified == 1)?'Y':'N';
	
	return $data;
}

function get_yanblik_tickets_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		
		array('no' => 'No.', 'align' => 'center', 'sortable' => false),
		array('id' => 'ID', 'visible' => false),
		array('trackid' => 'ID Layanan', 'class' => 'text-nowrap', 'titleTooltip' => 'aaa'),
		
		array('tglpengaduan' => 'Tgl Layanan', 'align' => 'center'),
		array('lastchange' => 'Update Terakhir', 'align' => 'center'),
		array('iden_nama' => 'Nama Konsumen','align' => 'left'),
		array('isu_topik' => 'Isu Topik','align' => 'left'),
		//array('outstanding' => 'Outstanding','align' => 'center'),
		array('status' => 'Status','align' => 'center'),
		array('is_rujuk' => 'Dirujuk','align' => 'center'),
		//array('notifikasi' => 'Notifikasi','align' => 'center'),
		array('tl' => 'TL','align' => 'center'),
		array('fb' => 'FB','align' => 'center'),
		
		array('is_verified' => 'Verified','align' => 'center'),
		array('hk' => 'Waktu Layanan','align' => 'center'),
	);
	
	
	return transform_headers($headers, TRUE);
}

function get_yanblik_ticket_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	$view_link = anchor("tickets/view/$item->id?ref=layanan_yanblik&r=".rand(1000,9999), "<i class='fa fa-search-plus'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_view')));
	$edit_link = anchor("tickets/edit/$item->id", "<i class='fa fa-fw fa-pencil-alt'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_edit')));
	$pdf_link = anchor("tickets/print_pdf/$item->id", "<i class='fa fa-print'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_pdf')));
			
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'trackid' => anchor("tickets/view/$item->id?ref=layanan_saya&r=".rand(1000,9999), $item->trackid),
		'tglpengaduan' => $item->tglpengaduan,
		'lastchange' => time_since($item->lastchange),
		'iden_nama' => $item->iden_nama,
		'isu_topik' => $item->isu_topik,
		'hk' => $item->hk,
		'edit' => $view_link.' '.$edit_link. ' '.$pdf_link
		);
	
	$data['status'] = get_status($item->status); 
	
	$data['tl'] = ($item->tl == 1)?'Y':'N';
	$data['fb'] = ($item->fb == 1)?'Y':'N';
	$data['is_rujuk'] = ($item->is_rujuk == 1)?'<span class="badge badge-primary">Ya</span>':'Tidak';
	$data['is_verified'] = ($item->is_verified == 1)?'Y':'N';
	
	return $data;
}

function get_verified_tickets_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		
		array('no' => 'No.', 'align' => 'center', 'sortable' => false),
		array('id' => 'ID', 'visible' => false),
		array('trackid' => 'ID Layanan', 'class' => 'text-nowrap', 'titleTooltip' => 'aaa'),
		
		array('tglpengaduan' => 'Tgl Layanan', 'align' => 'center'),
		array('lastchange' => 'Update Terakhir', 'align' => 'center'),
		array('iden_nama' => 'Nama Konsumen','align' => 'left'),
		array('isu_topik' => 'Isu Topik','align' => 'left'),
		//array('outstanding' => 'Outstanding','align' => 'center'),
		array('status' => 'Status','align' => 'center'),
		array('is_rujuk' => 'Dirujuk','align' => 'center'),
		//array('notifikasi' => 'Notifikasi','align' => 'center'),
		array('tl' => 'TL','align' => 'center'),
		array('fb' => 'FB','align' => 'center'),
		
		array('is_verified' => 'Verified','align' => 'center'),
		array('hk' => 'Waktu Layanan','align' => 'center'),
	);
	
	
	return transform_headers($headers, TRUE);
}

function get_verified_ticket_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	$view_link = anchor("tickets/view/$item->id?ref=layanan_yanblik&r=".rand(1000,9999), "<i class='fa fa-search-plus'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_view')));
	$edit_link = anchor("tickets/edit/$item->id", "<i class='fa fa-fw fa-pencil-alt'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_edit')));
	$pdf_link = anchor("tickets/print_pdf/$item->id", "<i class='fa fa-print'></i>",
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('tickets_pdf')));
			
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'trackid' => anchor("tickets/view/$item->id?ref=layanan_saya&r=".rand(1000,9999), $item->trackid),
		'tglpengaduan' => $item->tglpengaduan,
		'lastchange' => time_since($item->lastchange),
		'iden_nama' => $item->iden_nama,
		'isu_topik' => $item->isu_topik,
		'hk' => $item->hk,
		'edit' => $view_link.' '.$edit_link. ' '.$pdf_link
		);
	
	$data['status'] = get_status($item->status); 
	
	$data['tl'] = ($item->tl == 1)?'Y':'N';
	$data['fb'] = ($item->fb == 1)?'Y':'N';
	$data['is_rujuk'] = ($item->is_rujuk == 1)?'<span class="badge badge-primary">Ya</span>':'Tidak';
	$data['is_verified'] = ($item->is_verified == 1)?'Y':'N';
	
	return $data;
}

function get_calendar_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('id' => 'ID', 'visible' => false, 'width' => '80px'),
		array('no' => 'No.', 'sortable' => false, 'width' => '80px'),
		array('tgl' => 'Tanggal', 'align' => 'center'),
		array('hari' => 'Hari', 'align' => 'center'),
		array('keterangan' => 'Keterangan', 'align' => 'left'),
		//array('kode' => 'Kode', 'align' => 'center'),
		
	);
	
	

	if($CI->User->has_grant('messages', $CI->session->userdata('user_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	
	return transform_headers($headers);
}

function get_calendar_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	$dayName = date('N', strtotime($item->tgl));
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'tgl' => $item->tgl,
		'hari'=> to_day($dayName),
		'keterangan' => $item->keterangan,
		'edit' => anchor($controller_name."/view/$item->id", '<i class="fa fa-edit" aria-hidden="true"></i>',
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update')))
		);
			
	return $data;
}

function get_jobs_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('id' => 'ID', 'visible' => false, 'width' => '80px'),
		array('no' => 'No.', 'sortable' => false, 'width' => '80px'),
		array('ID' => 'ID/ Kode Database', 'align' => 'center', 'width' => '120px'),
		array('name' => 'Nama Profesi', 'align' => 'left'),
		
		
	);
	
	

	if($CI->User->has_grant('messages', $CI->session->userdata('user_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	
	return transform_headers($headers);
}


function get_job_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'ID' => $item->id,
		'name' => $item->name,
		'edit' => anchor($controller_name."/view/$item->id", '<i class="fa fa-edit" aria-hidden="true"></i>',
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update')))
		);
	
	
		
			
	return $data;
}

function get_klasifikasi_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('no' => 'No.', 'sortable' => false, 'width' => '80px'),
		array('id' => 'ID', 'visible' => false, 'width' => '80px'),
		array('nama' => 'Klasifikasi', 'align' => 'left'),
		array('status' => 'Status', 'align' => 'center'),
		
	);
	
	

	if($CI->User->has_grant('messages', $CI->session->userdata('user_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	
	return transform_headers($headers);
}


function get_klasifikasi_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'nama' => $item->nama,
		'edit' => anchor($controller_name."/view/$item->id", '<i class="fa fa-edit" aria-hidden="true"></i>',
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update')))
		);
	
	if($item->status)
		$data['status'] = 'Enabled';
	else
		$data['status'] = 'Disabled';
		
			
	return $data;
}

function get_subklasifikasi_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('id' => 'ID', 'visible' => false, 'width' => '80px'),
		array('no' => 'No.', 'sortable' => false, 'width' => '80px'),
		//array('klasifikasi' => 'Klasifikasi', 'align' => 'left'),
		array('subklasifikasi' => 'Subklasifikasi', 'align' => 'left'),
		array('status' => 'Status', 'align' => 'center')
	);
	
	if($CI->User->has_grant('messages', $CI->session->userdata('user_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	return transform_headers($headers);
}


function get_subklasifikasi_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		//'klasifikasi' => $item->klasifikasi,
		'subklasifikasi' => $item->subklasifikasi,
		'edit' => anchor($controller_name."/view/$item->id", '<i class="fa fa-edit" aria-hidden="true"></i>',
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update')))
		);
		
	if($item->status)
		$data['status'] = 'Enabled';
	else
		$data['status'] = 'Disabled';
	
	return $data;
}

function get_notifikasi_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('id' => 'ID', 'visible' => false, 'width' => '80px'),
		array('no' => 'No.', 'sortable' => false),
		array('is_read' => 'Flag', 'width' => '80px'),
		array('created_date' => 'Tgl', 'align' => 'left'),
		array('lastchange' => 'Update Terakhir', 'align' => 'center'),
		array('ticket_id' => 'ID Layanan', 'align' => 'left'),
		array('title' => 'Notifikasi', 'align' => 'left'),
		array('message' => 'Isi', 'align' => 'left')
		
	);
	
	if($CI->User->has_grant('messages', $CI->session->userdata('user_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	return transform_headers($headers);
}


function get_notifikasi_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	$message = trim(strip_tags($item->message));
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'title' => $item->title,
		'message' => (strlen($message) > 60)? substr($message,0,60).'...':$message,
		'created_date' => $item->created_date,
		'lastchange' => time_since($item->created_date),
		'ticket_id' => $item->ticket_id,
		'edit' => anchor($controller_name."/view/$item->id", '<i class="fa fa-search-plus" aria-hidden="true"></i>',
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_view')))
		);
	$data['is_read'] = ($item->is_read)?"<i class='fa fa-envelope-open'></i>":"<i class='fa fa-envelope'></i>";
	return $data;
}

function get_sla_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('id' => 'ID', 'visible' => false, 'width' => '80px'),
		array('no' => 'No.', 'sortable' => false, 'width' => '80px'),
		array('info' => 'Layanan', 'align' => 'center'),
		array('komoditi_id' => 'Komoditi', 'align' => 'left'),
		array('klasifikasi_id' => 'Klasifikasi', 'align' => 'left'),
		array('subklasifikasi_id' => 'Subklasifikasi', 'align' => 'left'),
		array('sla' => 'SLA (HK)', 'align' => 'center')
	);
	
	if($CI->User->has_grant('messages', $CI->session->userdata('user_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	return transform_headers($headers);
}


function get_sla_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		//'jenis_layanan' => $item->jenis_layanan,
		'klasifikasi_id' => $item->klasifikasi,
		'subklasifikasi_id' => $item->subklasifikasi,
		'komoditi_id' => $item->komoditi,
		'info' => $item->info,
		'sla' => $item->sla,
		'edit' => anchor($controller_name."/view/$item->id", '<i class="fa fa-edit" aria-hidden="true"></i>',
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update')))
		);
		
	if($item->info == 'P')
		$data['info'] = 'Pengaduan';
	elseif($item->info == 'I')
		$data['info'] = 'Informasi';
	
	return $data;
}

function get_monbalai_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		//array('id' => 'ID', 'visible' => false, 'width' => '80px'),
		array('no' => 'No.', 'sortable' => false, 'width' => '80px'),
		array('nama_balai' => 'Nama Balai', 'align' => 'left'),
		array('jml_layanan' => 'Jml Layanan', 'align' => 'center'),
		array('jml_open' => 'Status Open', 'align' => 'center'),
		array('jml_closed' => 'Status Closed', 'align' => 'center'),
		array('tl_belum' => 'Belum TL', 'align' => 'center'),
		array('tl_sudah' => 'Sudah TL', 'align' => 'center'),
		array('rata_hk' => 'Rata2 Waktu Layanan', 'align' => 'center'),
		array('sla' => 'Pemenuhan SLA (%)', 'align' => 'center')
	);
	
	if($CI->User->has_grant('messages', $CI->session->userdata('user_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	return transform_headers($headers, TRUE, FALSE);
}

function get_monbalai_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	
	$data = array (
		//'id' => $item->id,
		'no' => $no.'.',
		'nama_balai' => $item->nama_balai,
		'rata_hk' => $item->rata_hk,
		'jml_layanan' => $item->jml_layanan,
		'jml_closed' => $item->jml_closed,
		'jml_open' => ($item->jml_layanan - $item->jml_closed),
		'tl_sudah' => $item->jml_tl,
		'tl_belum' => ($item->jml_layanan - $item->jml_tl)
		);
		
	if($item->jml_layanan > 0)
		$data['sla'] = number_format($item->sla_yes*100/$item->jml_layanan,2);
	else
		$data['sla'] = '-';
		
	return $data;
}

function get_database_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	//$ticketid = base64_encode($item->id);
	$ticketid = $item->id;
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'trackid' => anchor("tickets/view/$ticketid?r=".rand(1000,9999), '<span class=text-nowrap>'.$item->trackid.'</span>', array('title' => $item->trackid)),
		'tglpengaduan' => $item->tglpengaduan,
		'lastchange' => time_since($item->lastchange),
		'iden_nama' => $item->iden_nama,
		'iden_alamat' => $item->iden_alamat,
		'iden_telp' => $item->iden_telp,
		'iden_email' => $item->iden_email,
		'problem_i' => (($item->info == 'I') ? $item->problem : ''),
		'problem_p' => (($item->info == 'P') ? $item->problem : ''),
		'jawaban_i' => (($item->info == 'I') ? $item->jawaban : ''),
		'jawaban_p' => (($item->info == 'P') ? $item->jawaban : ''),
		'keterangan' => $item->keterangan,
		'jenis_komoditi' => $item->jenis_komoditi,
		'jenis' => $item->jenis,
		'penerima' => $item->penerima,
		'isu_topik' => $item->isu_topik,
		'klasifikasi' => $item->klasifikasi,
		'subklasifikasi' => $item->subklasifikasi,
		'pekerjaan' => $item->pekerjaan,
		'kota' => $item->kota,
		'waktu' => $item->waktu,
		'shift' => $item->shift,
		'sarana' => $item->sarana,
		'petugas_entry' => $item->petugas_entry,
		'tl' => (($item->tl)?'Sudah':'Belum'),
		'tl_date' => $item->tl_date,
		'fb' => (($item->fb)?'Sudah':'Belum'),
		'fb_date' => $item->fb_date,
		'verified' => (($item->is_verified)?'Sudah':'Belum'),
		'verified_date' => $item->verified_date,
		'verificator_name' => $item->verificator_name,
		'hk' => $item->hk,
		'sla' => (($item->hk <= $item->sla)?'Y':'N'),
		'closed_date' => $item->closed_date,
		);
	
	$data['status'] = get_status($item->status); 
	
	
	
	return $data;
}

function get_database_rujukan_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	//$ticketid = base64_encode($item->id);
	$ticketid = $item->id;
	
	$problem = preg_replace("~[^0-9a-z\\s]~i", "", $item->problem);
	$jawaban = preg_replace("~[^0-9a-z\\s]~i", "", $item->jawaban);
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'trackid' => anchor("tickets/view/$ticketid?r=".rand(1000,9999), '<span class=text-nowrap>'.$item->trackid.'</span>', array('title' => $item->trackid)),
		'tglpengaduan' => $item->tglpengaduan,
		'lastchange' => time_since($item->lastchange),
		'iden_nama' => $item->iden_nama,
		'iden_alamat' => $item->iden_alamat,
		'iden_telp' => $item->iden_telp,
		'iden_email' => $item->iden_email,
		'iden_jk' => $item->iden_jk,
		'problem' => $problem,
		'jawaban' => $jawaban,
		'info' => $item->info,
		'keterangan' => $item->keterangan,
		'jenis_komoditi' => $item->jenis_komoditi,
		'jenis' => $item->jenis,
		'penerima' => $item->penerima,
		'isu_topik' => $item->isu_topik,
		'klasifikasi' => $item->klasifikasi,
		'subklasifikasi' => $item->subklasifikasi,
		'pekerjaan' => $item->pekerjaan,
		'kota' => $item->kota,
		'waktu' => $item->waktu,
		'shift' => $item->shift,
		'sarana' => $item->sarana,
		'petugas_entry' => $item->petugas_entry,
		'tl' => (($item->tl)?'Sudah TL':'Belum TL'),
		'tl_date' => $item->tl_date,
		'fb' => (($item->fb)?'Sudah':'Belum'),
		'fb_date' => $item->fb_date,
		'fb_isi' => $item->fb_isi,
		'verified' => (($item->is_verified)?'Sudah':'Belum'),
		'verified_date' => $item->verified_date,
		'verificator_name' => $item->verificator_name,
		'hk' => $item->hk,
		'sla' => $item->sla,
		'memenuhi_sla' => (($item->hk <= $item->sla)?'Y':'N'),
		'closed_date' => $item->closed_date,
		);
	
	$data['status'] = get_status($item->status); 
	
	if($item->direktorat > 0)
	{
		$data['rujukan1_sla'] = $item->sla1;
		$data['rujukan1_nama'] = get_dirname($item->direktorat);
		$data['rujukan1_status'] = $item->status_rujuk1;
		if($item->status_rujuk1 === '1')$data['rujukan1_status'] = 'Sudah TL';
		elseif($item->status_rujuk1 == '0')$data['rujukan1_status'] = 'Belum TL';
		$data['rujukan1_tl'] = get_replies($item->direktorat, $item->id);
	}
	if($item->direktorat2 > 0)
	{
		$data['rujukan2_sla'] = $item->sla2;
		//$data['rujukan2_nama'] = $item->dir_name2;
		$data['rujukan2_status'] = $item->status_rujuk2;
		if($item->status_rujuk2 === '1')$data['rujukan2_status'] = 'Sudah TL';
		elseif($item->status_rujuk2 == '0')$data['rujukan2_status'] = 'Belum TL';
		$data['rujukan2_tl'] = get_replies($item->direktorat2, $item->id);
	}
	if($item->direktorat3 > 0)
	{
		$data['rujukan3_sla'] = $item->sla3;
		//$data['rujukan3_nama'] = $item->dir_name3;
		$data['rujukan3_status'] = $item->status_rujuk3;
		if($item->status_rujuk3 === '1')$data['rujukan3_status'] = 'Sudah TL';
		elseif($item->status_rujuk3 == '0')$data['rujukan3_status'] = 'Belum TL';
		$data['rujukan3_tl'] = get_replies($item->direktorat3, $item->id);
	}
	if($item->direktorat4 > 0)
	{
		$data['rujukan4_sla'] = $item->sla4;
		//$data['rujukan4_nama'] = $item->dir_name4;
		$data['rujukan4_status'] = $item->status_rujuk4;
		if($item->status_rujuk4 === '1')$data['rujukan4_status'] = 'Sudah TL';
		elseif($item->status_rujuk4 == '0')$data['rujukan4_status'] = 'Belum TL';
		$data['rujukan4_tl'] = get_replies($item->direktorat4, $item->id);
	}
	if($item->direktorat5 > 0)
	{
		$data['rujukan5_sla'] = $item->sla5;
		//$data['rujukan5_nama'] = $item->dir_name5;
		$data['rujukan5_status'] = $item->status_rujuk5;
		if($item->status_rujuk5 === '1')$data['rujukan5_status'] = 'Sudah TL';
		elseif($item->status_rujuk5 == '0')$data['rujukan5_status'] = 'Belum TL';
		$data['rujukan5_tl'] = get_replies($item->direktorat5, $item->id);
	}
	return $data;
}

function get_database_yanblik_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	//$ticketid = base64_encode($item->id);
	$ticketid = $item->id;
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'trackid' => anchor("tickets/view/$ticketid?r=".rand(1000,9999), '<span class=text-nowrap>'.$item->trackid.'</span>', array('title' => $item->trackid)),
		'tglpengaduan' => $item->tglpengaduan,
		'lastchange' => time_since($item->lastchange),
		'iden_nama' => $item->iden_nama,
		'iden_alamat' => $item->iden_alamat,
		'iden_telp' => $item->iden_telp,
		'iden_email' => $item->iden_email,
		
		/*'problem_i' => (($item->info == 'I') ? $item->problem : ''),
		'problem_p' => (($item->info == 'P') ? $item->problem : ''),
		'jawaban_i' => (($item->info == 'I') ? $item->jawaban : ''),
		'jawaban_p' => (($item->info == 'P') ? $item->jawaban : ''),*/
		'problem' => $item->problem,
		'jawaban' => $item->jawaban,
		'info' => $item->info,
		'keterangan' => $item->keterangan,
		'jenis_komoditi' => $item->jenis_komoditi,
		'jenis' => $item->jenis,
		'penerima' => $item->penerima,
		'isu_topik' => $item->isu_topik,
		'klasifikasi' => $item->klasifikasi,
		'subklasifikasi' => $item->subklasifikasi,
		'pekerjaan' => $item->pekerjaan,
		'kota' => $item->kota,
		'waktu' => $item->waktu,
		'shift' => $item->shift,
		'sarana' => $item->sarana,
		'petugas_entry' => $item->petugas_entry,
		'tl' => (($item->tl)?'Sudah TL':'Belum TL'),
		'tl_date' => $item->tl_date,
		//'fb' => (($item->fb)?'Sudah':'Belum'),
		//'fb_date' => $item->fb_date,
		//'verified' => (($item->is_verified)?'Sudah':'Belum'),
		//'verified_date' => $item->verified_date,
		//'verificator_name' => $item->verificator_name,
		'hk' => $item->hk,
		'sla' => $item->sla,
		'memenuhi_sla' => (($item->hk <= $item->sla)?'Y':'N'),
		'closed_date' => $item->closed_date,
		);
	
	$data['status'] = get_status($item->status); 
	
	/*$data['rujukan1_sla'] = $item->sla1;
	$data['rujukan1_nama'] = get_dirname($item->direktorat);
	$data['rujukan1_status'] = $item->status1;
	if($item->status1 === '1')$data['rujukan1_status'] = 'Sudah TL';
	elseif($item->status1 == '0')$data['rujukan1_status'] = 'Belum TL';
	$data['rujukan1_tl'] = get_replies($item->direktorat, $item->id);
	
	$data['rujukan2_sla'] = $item->sla2;
	//$data['rujukan2_nama'] = $item->dir_name2;
	$data['rujukan2_status'] = $item->status2;
	if($item->status2 === '1')$data['rujukan2_status'] = 'Sudah TL';
	elseif($item->status2 == '0')$data['rujukan2_status'] = 'Belum TL';
	
	$data['rujukan3_sla'] = $item->sla3;
	//$data['rujukan3_nama'] = $item->dir_name3;
	$data['rujukan3_status'] = $item->status3;
	if($item->status3 === '1')$data['rujukan3_status'] = 'Sudah TL';
	elseif($item->status3 == '0')$data['rujukan3_status'] = 'Belum TL';
	
	$data['rujukan4_sla'] = $item->sla4;
	//$data['rujukan4_nama'] = $item->dir_name4;
	$data['rujukan4_status'] = $item->status4;
	if($item->status4 === '1')$data['rujukan4_status'] = 'Sudah TL';
	elseif($item->status4 == '0')$data['rujukan4_status'] = 'Belum TL';
	
	$data['rujukan5_sla'] = $item->sla5;
	//$data['rujukan5_nama'] = $item->dir_name5;
	$data['rujukan5_status'] = $item->status5;
	if($item->status5 === '1')$data['rujukan5_status'] = 'Sudah TL';
	elseif($item->status5 == '0')$data['rujukan5_status'] = 'Belum TL';
	
	*/
	
	
	/*
	$data['customer'] = $item->iden_nama .'; '. $item->iden_alamat .'; '.$item->sarana . '; ' .$item->waktu. '; ' .$item->pekerjaan .'; ' .$item->iden_instansi . '; '.$item->iden_email.'; '.$item->trackid;
	
	if($item->direktorat != '0')
	{
		$array = get_replies($item->direktorat, $item->id);
		$data['rujukan1_nama'] = $array['nama'];
		$data['rujukan1_tl'] = $array['tl'];
		$data['rujukan1_sla'] = $item->sla1;
		$data['rujukan1_status'] = (($item->status1)?'Sudah TL':'Belum TL');
	}
	
	if($item->direktorat2 != '0')
	{
		$array = get_replies($item->direktorat2, $item->id);
		$data['rujukan2_nama'] = $array['nama'];
		$data['rujukan2_tl'] = $array['tl'];
		$data['rujukan2_sla'] = $item->sla2;
		$data['rujukan2_status'] = (($item->status2)? 'Sudah TL':'Belum TL');
	}
	
	if($item->direktorat3 != '0')
	{
		$array = get_replies($item->direktorat3, $item->id);
		$data['rujukan3_nama'] = $array['nama'];
		$data['rujukan3_tl'] = $array['tl'];
		$data['rujukan3_sla'] = $item->sla3;
		$data['rujukan3_status'] = (($item->status3)?'Sudah TL':'Belum TL');
	}
	
	if($item->direktorat4 != '0')
	{
		$array = get_replies($item->direktorat4, $item->id);
		$data['rujukan4_nama'] = $array['nama'];
		$data['rujukan4_tl'] = $array['tl'];
		$data['rujukan4_sla'] = $item->sla4;
		$data['rujukan4_status'] = (($item->status4)?'Sudah TL':'Belum TL');
	}
	
	if($item->direktorat5 != '0')
	{
		$array = get_replies($item->direktorat5, $item->id);
		$data['rujukan5_nama'] = $array['nama'];
		$data['rujukan5_tl'] = $array['tl'];
		$data['rujukan5_sla'] = $item->sla5;
		$data['rujukan5_status'] = (($item->status5)?'Sudah TL':'Belum TL');
	}*/
	
	
	/*$data['rujukan1'] = ($item->direktorat != '0') ? get_replies($item->direktorat, $item->id) : '';
	$data['rujukan2'] = ($item->direktorat2 != '0') ? get_replies($item->direktorat2, $item->id) : '';
	$data['rujukan3'] = ($item->direktorat3 != '0') ? get_replies($item->direktorat3, $item->id) : '';
	$data['rujukan4'] = ($item->direktorat4 != '0') ? get_replies($item->direktorat4, $item->id) : '';
	$data['rujukan5'] = ($item->direktorat5 != '0') ? get_replies($item->direktorat5, $item->id) : '';*/
	
	
	return $data;
}

function get_drafts_rujukan_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	
	$data = array (
		//'id' => $item->id,
		'no' => $no.'.',
		'unit_name' => $item->kota. ' | ' .$item->unit_name,
		'sla' => $item->sla
		
		);
		
	
		
	return $data;
}

function get_categories_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('no' => 'No.', 'sortable' => false, 'width' => '80px'),
		array('name' => 'Nama', 'align' => 'left'),
		array('desc' => 'Deskripsi', 'align' => 'left'),
		array('deleted' => 'Status', 'align' => 'center'),
		
	);
	
	

	if($CI->User->has_grant('messages', $CI->session->userdata('user_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	
	return transform_headers($headers);
}


function get_categories_data_row($item, $no)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	
	
	$data = array (
		'id' => $item->id,
		'no' => $no.'.',
		'name' => $item->name,
		'desc' => $item->desc,
		'edit' => anchor($controller_name."/view/$item->id", '<i class="fa fa-edit" aria-hidden="true"></i>',
			array('class'=>'', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update')))
		);
	
	if($item->deleted)
		$data['deleted'] = 'Disabled';
	else
		$data['deleted'] = 'Enabled';
		
			
	return $data;
}

?>