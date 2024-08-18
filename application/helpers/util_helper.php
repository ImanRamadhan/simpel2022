<?php

function to_day($i)
{
	$hari = '';
	switch($i)
	{
		case 1:
			$hari = 'Senin';
			break;
		case 2:
			$hari = 'Selasa';
			break;
		case 3:
			$hari = 'Rabu';
			break;
		case 4:
			$hari = 'Kamis';
			break;
		case 5:
			$hari = 'Jumat';
			break;
		case 6:
			$hari = 'Sabtu';
			break;
		case 7:
			$hari = 'Minggu';
			break;
		
		default:
			$hari = '';
	}
	return $hari;
}

function to_bulan($i)
{
	$bln = '';
	switch($i)
	{
		case 1:
			$bln = 'Januari';
			break;
		case 2:
			$bln = 'Februari';
			break;
		case 3:
			$bln = 'Maret';
			break;
		case 4:
			$bln = 'April';
			break;
		case 5:
			$bln = 'Mei';
			break;
		case 6:
			$bln = 'Juni';
			break;
		case 7:
			$bln = 'Juli';
			break;
		case 8:
			$bln = 'Agustus';
			break;
		case 9:
			$bln = 'September';
			break;
		case 10:
			$bln = 'Oktober';
			break;
		case 11:
			$bln = 'November';
			break;
		case 12:
			$bln = 'Desember';
			break;
		default:
			$bln = '';
	}
	return $bln;
}

function get_sarana()
{
	$array = array(
		'' => '',
		'Langsung' => 'Langsung',
		'Telepon' => 'Telepon',
		'Fax' => 'Fax',
		'Surat' => 'Surat',
		'Email' => 'Email',
		'SMS' => 'SMS',
		'Medsos' => 'Medsos',
		'WhatsApp' => 'WhatsApp',
		'Kotak Saran' => 'Kotak Saran',
		'Mobile' => 'Mobile',
		'Aplikasi Lain' => 'Aplikasi Lain'
	);
	
	return $array;
}

function convert_date1($input) //dd/mm/yyyy to yyyy-mm-dd
{
	if(empty($input) || $input == '00/00/0000') return '';
	
	$date = explode('/', $input);
	if(count($date) > 2)
		return $date[2].'-'.$date[1].'-'.$date[0];
	
	return '';
}

function convert_date2($input) //yyyy-mm-dd to dd/mm/yyyy 
{
	if(empty($input) || $input == '0000-00-00') return '';
	
	$date = explode('-', $input);
	if(count($date) > 2)
		return $date[2].'/'.$date[1].'/'.$date[0];
	
	return '';
}

function convert_date3($input) //yyyy-mm-dd to dd MM yyyy 
{
	if(empty($input) || $input == '0000-00-00') return '';
	
	$date = explode('-', $input);
	if(count($date) > 2)
		return $date[2].' '.to_bulan($date[1]).' '.$date[0];
	
	return '';
}

function get_marquee()
{
	$CI =& get_instance();
	$CI->load->model('Ticket');
	$not_verified = $CI->Ticket->get_tickets_not_verified(date('Y'), date('n'));
	echo sprintf("Pada bulan %s %s terdapat %d layanan yang belum diverifikasi", to_bulan(date('n')), date('Y'), $not_verified);
	
}

function get_countries()
{
	$array = array('' => '', 'Indonesia' => 'Indonesia');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	$countries = $CI->Ticket->get_countries();
		
	foreach($countries->result() as $country)
	{
		$array[$country->nama] = $country->nama;
	}
	
	return $array;
}

function get_klasifikasi()
{
	$array = array('' => '');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	$objects = $CI->Ticket->get_klasifikasi();
		
	foreach($objects->result() as $obj)
	{
		//$array[$obj->id] = $obj->nama;
		$array[$obj->nama] = $obj->nama;
	}
	
	return $array;
}

function get_subklasifikasi_json()
{
	$array = array();
	$CI =& get_instance();
	$CI->load->model('Ticket');
	$objects = $CI->Ticket->get_subklasifikasi();
	
		
	foreach($objects as $obj)
	{
		$klasifikasi_id = $obj->klasifikasi_id;
		$subklasifikasi = $obj->subklasifikasi;
		$id = $obj->id;
		
		if(empty($array[$klasifikasi_id]))
		{
			$array[$klasifikasi_id] = array();
		}
		
		array_push($array[$klasifikasi_id], array('id' => $id, 'value' => $subklasifikasi));
		
		
		
		
	}
	
	return json_encode($array);
}

function get_subklasifikasi2($kla = '')
{
	$array = array('' => '');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	$objects = $CI->Ticket->get_subklasifikasi2($kla);
		
	foreach($objects as $obj)
	{
		$subklasifikasi = $obj->subklasifikasi;
		//$id = $obj->id;
		//$array[] = array($id => $subklasifikasi);
		$array[$subklasifikasi] =  $subklasifikasi;
	}
	
	return $array;
}

function get_subklasifikasi($kla = '')
{
	$array = array('' => '');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	$objects = $CI->Ticket->get_subklasifikasi($kla);
		
	foreach($objects as $obj)
	{
		$subklasifikasi = $obj->subklasifikasi;
		$id = $obj->id;
		//$array[] = array($id => $subklasifikasi);
		$array[$id] =  $subklasifikasi;
	}
	
	return $array;
}

function get_klasifikasi_sla($cat, $info)
{
	$array = array('' => '');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	$objects = $CI->Ticket->get_klasifikasi_sla($cat, $info);
		
	foreach($objects as $obj)
	{
		$klasifikasi = $obj->klasifikasi;
		$id = $obj->id;
		$array[$id] = $klasifikasi;
	}
	
	return $array;
}


function get_direktorat_rujukan()
{
	$array = array('' => '');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	
	$array[1] = 'PUSAT | ULPK Badan POM';
	
	$objects = $CI->Ticket->get_direktorat_rujukan(array('PUSAT', 'UNIT TEKNIS'));
		
	foreach($objects->result() as $obj)
	{
		$array[$obj->id] = $obj->kota .' | '. $obj->name;
	}
	
	$objects = $CI->Ticket->get_direktorat_rujukan();
		
	foreach($objects->result() as $obj)
	{
		$array[$obj->id] = $obj->kota .' | '. $obj->name;
	}
	
	return $array;
}

function get_direktorat_rujukan_options()
{
	$array = array('' => '');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	
	$string = '';
	
	$string .= '<option value="1">PUSAT | ULPK Badan POM</option>';
	
	$objects = $CI->Ticket->get_direktorat_rujukan(array('PUSAT', 'UNIT TEKNIS'));
		
	$result = array();
	foreach($objects->result() as $obj)
	{
		$result[$obj->kota][] = $obj;
	}
	
	
	/*foreach($objects->result() as $obj)
	{
		$string .= '<option value="'.$obj->id.'">'.$obj->kota .' | '. $obj->name.'</option>';
		
	}*/
	
	$objects = $CI->Ticket->get_direktorat_rujukan();
		
	foreach($objects->result() as $obj)
	{
		$result[$obj->kota][] = $obj;
	}
	foreach($result as $k => $v) {
		$string .= '<optgroup label="'.$k.'">';
		foreach($v as $obj)
		{
			$string .= '<option value="'.$obj->id.'">'.$obj->kota .' | '. $obj->name.'</option>';
		}
		$string .= '</optgroup>';
	}
	
	return $string;
}

function get_direktorat_rujukan2()
{
	$array = array();
	$CI =& get_instance();
	$CI->load->model('Ticket');
	
	array_push($array, array('id'=>'', 'text'=>''));	
	array_push($array, array('id'=>'1', 'text'=>'PUSAT | ULPK Badan POM'));	
	
	$objects = $CI->Ticket->get_direktorat_rujukan(array('PUSAT', 'UNIT TEKNIS'));
		
	foreach($objects->result() as $obj)
	{
		array_push($array, array('id'=>$obj->id, 'text'=>$obj->kota .' | '. $obj->name));
	}
	
	$objects = $CI->Ticket->get_direktorat_rujukan();
		
	foreach($objects->result() as $obj)
	{
		array_push($array, array('id'=>$obj->id, 'text'=>$obj->kota .' | '. $obj->name));
	}
	
	return $array;
}

function get_direktorat_rujukan_for_balai($city)
{
	$array = array('' => '');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	
	$array[1] = 'PUSAT | ULPK Badan POM';
	
	$objects = $CI->Ticket->get_direktorat_rujukan(array($city));
		
	foreach($objects->result() as $obj)
	{
		$array[$obj->id] = $obj->kota .' | '. $obj->name;
	}
	
	
	return $array;
}

function get_sla()
{
	/*$array = array('' => 'Pilih kebutuhan jawaban', 0 => 'Kurang dari 1 hari');
	for($i = 1; $i<=10; $i++)
	{
		$array[$i] = $i.' hari';
	}
	$array[180] = '180 hari';*/
	
	$array = array('' => 'Pilih SLA');
	/*$array[5] = '5 HK';
	$array[14] = '14 HK';
	$array[60] = '60 HK';*/
	for($i = 1; $i<=60; $i++)
	{
		$array[$i] = $i.' HK';
	}
	
	/*$array[5] = 'Memberikan Informasi (SLA 5 HK)';
	$array[14] = 'Pengaduan Tidak Memerlukan TL Lapangan (SLA 14 HK)';
	$array[60] = 'Pengaduan Memerlukan TL Lapangan (SLA 60 HK)';*/
		
	return $array;
}

function get_sla_rujukan_options()
{
	$string = '<option value="5">5 HK</option><option value="14">14 HK</option><option value="60">60 HK</option>';
	return $string;
}


function get_provinces()
{
	$array = array(''=>'');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	$provinces = $CI->Ticket->get_provinces();
		
	foreach($provinces->result() as $pro)
	{
		$array[$pro->nama] = $pro->nama;
	}
	
	return $array;
}

function get_cities($nama_prov)
{
	$array = array(''=>'');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	$kabs = $CI->Ticket->get_kab($nama_prov);
		
	foreach($kabs as $o)
	{
		$array[$o->nama] = $o->nama;
	}
	
	return $array;
}

function get_products()
{
	$array = array(''=>'');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	$products = $CI->Ticket->get_products();
		
	foreach($products->result() as $o)
	{
		$array[$o->id] = $o->desc;
	}
	
	return $array;
}

function get_range_age(){
	$array = array(''=>'');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	$products = $CI->Ticket->get_range_age();
		
	foreach($products->result() as $o)
	{
		$array[$o->id] = $o->range;
	}
	
	return $array;
}

function get_products_sla($info)
{
	$array = array(''=>'');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	$products = $CI->Ticket->get_products_sla($info);
		
	/*foreach($products->result() as $o)
	{
		$array[$o->id] = $o->desc;
	}*/
	$array_wewenang = array();
	$array_nonwewenang = array();
	
	foreach($products->result() as $prod)
	{
		if($prod->wewenang)
			$array_wewenang[$prod->id] = $prod->desc;
		else
			$array_nonwewenang[$prod->id] = $prod->desc;
	}
	
	$array = array();
	$array['Wewenang'] = $array_wewenang;
	$array['Bukan Wewenang'] = $array_nonwewenang;
	
	
	return $array;
}

function get_profesi()
{
	$array = array(''=>'');
	$CI =& get_instance();
	$CI->load->model('Ticket');
	$objects = $CI->Ticket->get_profesi();
		
	foreach($objects->result() as $o)
	{
		$array[$o->id] = $o->name;
	}
	
	return $array;
}

function get_filters()
{
	$array = array(
		'ALL' => 'ALL', 
		'PUSAT' => 'PUSAT', 
		'CC' => 'CC', 
		'UNIT_TEKNIS' => 'UNIT TEKNIS',
		'BALAI' => 'BALAI',
		'PUSAT_BALAI' => 'PUSAT + BALAI',
		'PUSAT_CC' => 'PUSAT + CC',
		'PUSAT_UNIT_TEKNIS' => 'PUSAT + UNIT_TEKNIS',
		'PUSAT_CC_BALAI' => 'PUSAT + CC + BALAI',
		'PUSAT_CC_UNIT_TEKNIS' => 'PUSAT + CC + UNIT TEKNIS',
		'PUSAT_CC_UNIT_TEKNIS_BALAI' => 'PUSAT + CC + UNIT TEKNIS + BALAI'
	);
	
	$CI =& get_instance();
	$CI->load->model('City');
	$cities = $CI->City->get_cities();
		
	foreach($cities->result() as $city)
	{
		$array[strtoupper($city->nama_kota)] = strtoupper($city->nama_kota);
	}
	
	return $array;
}

function get_status($status_id)
{
	$status = '';
	switch($status_id)
	{
		case 0:
			$status = "<span class='badge badge-boxed badge-danger'>Open</span>";
			break;
		case 1:
			$status = "<span class='badge badge-boxed badge-warning'>Waiting Reply</span>";
			break;
		case 2:
			$status = "<span class='badge badge-boxed badge-warning'>Replied</span>";
			break;
		case 3:
			$status = "<span class='badge badge-boxed badge-success'>Closed</span>";
			break;
		case 4:
			$status = "<span class='badge badge-boxed badge-warning'>In Progress</span>";
			break;
		case 5:
			$status = "<span class='badge badge-boxed badge-warning'>On Hold</span>";
			break;
		default:
			$status = "";
			
	}
	
	return $status;
}

function get_status_plain($status_id)
{
	$status = '';
	switch($status_id)
	{
		case 0:
			$status = "Open";
			break;
		case 1:
			$status = "Waiting Reply";
			break;
		case 2:
			$status = "Replied";
			break;
		case 3:
			$status = "Closed";
			break;
		case 4:
			$status = "In Progress";
			break;
		case 5:
			$status = "On Hold";
			break;
		default:
			$status = "";
			
	}
	
	return $status;
}

function get_user_role($role_id)
{
	$role_text = "";
	
	switch($role_id)
	{
		case '1':
			$role_text = 'Administrator';
			break;
		case '2':
			$role_text = 'Verifikator';
			break;
		case '3':
			$role_text = 'User';
			break;
		case '0':
			$role_text = 'Petugas';
			break;
		case '4':
			$role_text = 'Tim Koordinasi Pusat/Balai';
			break;
		case '5':
			$role_text = 'Tim Koordinasi Yanblik';
			break;
		default:
			$role_text = '';
	}
	
	return $role_text;
}


function is_administrator()
{
	$CI =& get_instance();
	if( $CI->session->role_id == '1')
		return TRUE;
	
	return FALSE;
}

function is_petugas()
{
	$CI =& get_instance();
	if( $CI->session->role_id == '0')
		return TRUE;
	
	return FALSE;
}

function is_user()
{
	$CI =& get_instance();
	if( $CI->session->role_id == '3')
		return TRUE;
	
	return FALSE;
}

function is_verificator()
{
	$CI =& get_instance();
	if( $CI->session->role_id == '2')
		return TRUE;
	
	return FALSE;
}

function is_tim_koordinasi()
{
	$CI =& get_instance();
	if( $CI->session->role_id == '4')
		return TRUE;
	
	return FALSE;
}

function is_pusat()
{
	$CI =& get_instance();
	if( $CI->session->city == 'PUSAT')
		return TRUE;
	
	return FALSE;
}

function is_unit_teknis()
{
	$CI =& get_instance();
	if( $CI->session->city == 'UNIT TEKNIS')
		return TRUE;
	
	return FALSE;
}

function is_balai()
{
	$CI =& get_instance();
	if(!($CI->session->city == 'UNIT TEKNIS' || $CI->session->city == 'PUSAT'))
		return TRUE;
	
	return FALSE;
}

function is_user_balai()
{
	$CI =& get_instance();
	if( $CI->session->city != 'PUSAT' && $CI->session->city != 'UNIT TEKNIS' && $CI->session->role_id == '3' )
		return TRUE;
	
	return FALSE;
}

function get_userid()
{
	$CI =& get_instance();
	if( !empty($CI->session->id) )
		return $CI->session->id;
	
	return NULL;
}

function get_usercity()
{
	$CI =& get_instance();
	if( !empty($CI->session->city) )
		return $CI->session->city;
	
	return NULL;
}
function format_date_indo($tanggal_yyyy_mm_dd)
{
	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split = explode('-', $tanggal_yyyy_mm_dd);
	return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
}

function time_since($original)
{
	$original = strtotime($original);
	
	 $chunks = array(
        array(60 * 60 * 24 * 365 , 'thn '),
        array(60 * 60 * 24 * 30 , 'bln '),
        array(60 * 60 * 24 * 7, 'mg '),
        array(60 * 60 * 24 , 'hr '),
        array(60 * 60 , 'jam '),
        array(60 , 'mnt '),
        array(1 , 'dtk'),
    );
	
	$server_time = strtotime(date('Y-m-d H:i:s'));
	
	if($server_time < $original)
	{
		return "0 dtk";
	}
	
	$since = $server_time - $original;

    // $j saves performing the count function each time around the loop
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {

        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];

        // finding the biggest chunk (if the chunk fits, break)
        if (($count = floor($since / $seconds)) != 0) {
            // DEBUG print "<!-- It's $name -->\n";
            break;
        }
    }

    $print = "$count{$name}";

    if ($i + 1 < $j) {
        // now getting the second item
        $seconds2 = $chunks[$i + 1][0];
        $name2 = $chunks[$i + 1][1];

        // add second item if it's greater than 0
        if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
            $print .= "$count2{$name2}";
        }
    }
    return $print;
}


function get_count_unread_notifikasi($unread = FALSE)
{
	$CI =& get_instance();
	$CI->load->model('Notification');
	return $CI->Notification->get_notifications($CI->session->id, $unread, 0, TRUE);
}

function get_last_notifikasi()
{
	$CI =& get_instance();
	$CI->load->model('Notification');
	
	return $CI->Notification->get_notifications($CI->session->id, TRUE, 3);
}

function is_allowed($fname)
{
	$CI =& get_instance();
	if(strpos($CI->session->heskprivileges, $fname) === false)
		return false;
	return true;
}

function get_city_location()
{
	$CI =& get_instance();
	$city = '';
	if($CI->session->city == 'PUSAT' || $CI->session->city == 'UNIT_TEKNIS')
		$city = 'Jakarta';
	else
		$city = ucfirst(strtolower($CI->session->city));
	
	return $city;
}

function print_komoditi($info)
{
	$CI =& get_instance();
	$CI->load->model('Ticket');
	
	$products = $CI->Ticket->get_products_sla($info);
		
	$array_wewenang = array();
	$array_nonwewenang = array();
	
	foreach($products->result() as $prod)
	{
		
		if($prod->wewenang)
		{
			$array_wewenang[] = array(
			'id' => $prod->id,
			'desc' => $prod->desc
			);
		}
		else
		{
			$array_nonwewenang[] = array(
			'id' => $prod->id,
			'desc' => $prod->desc
			);
		}
		
	}
	
	$array = array(
		array(
			'label' => 'Wewenang',
			'komoditi' => $array_wewenang
		),
		array(
			'label' => 'Bukan Wewenang',
			'komoditi' => $array_nonwewenang
		),
	);

	return json_encode($array);
}

function get_dirname($dir_id)
{
	if($dir_id == 0)
		return '';
	
	$CI =& get_instance();
	$CI->load->model('Reply');
	$direktorat = $CI->Reply->get_info_direktorat($dir_id);
	return $direktorat[0]['name'];
	
	
}

function get_replies($dir_id, $replyto)
{
	$CI =& get_instance();
	$CI->load->model('Reply');
	
	$array = array();
	
	$string = '';
	//$direktorat = $CI->Reply->get_info_direktorat($dir_id);
	//$array['nama'] = $direktorat[0]['name'].'<br />';
	$replies = $CI->Reply->get_info_desk_reply($dir_id, $replyto);
	
	for($x =0; $x< count($replies); $x++){
		//$string .= '<b>'. $replies[$x]['dt'].' ('.$replies[$x]['name'].') </b> <br />';
		$string .= ''. $replies[$x]['dt'].' ('.$replies[$x]['name'].') <br />';
		$string .= $replies[$x]['message'].'<br /><br />';
	}
	
	
	//$array['tl'] = $string;
	return $string;
	//return $array;
}

function print_dir_rujukan($id)
{
	$CI =& get_instance();
	$CI->load->model('Ticket');
	
	$result = $CI->Ticket->get_direktorat($id);
		
	return $result;
}

?>