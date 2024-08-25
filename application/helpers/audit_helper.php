<?php

function log_layanan($before, $after)
{
	$CI = get_instance();
	$CI->load->model('Ticket');
	
	$fieldchanges = '';
	foreach($CI->db->list_fields('desk_tickets') as $property)
	{
		if($property == 'lastchange' || $property == 'history')
			continue;
		
		if($before->$property != $after->$property)
		{
			if(!empty(realName($property)))
				$fieldchanges .= realName($property). '; ';
		}
			
		
	}
	
	if(!empty($fieldchanges))
	{
		$item_info = $CI->Ticket->get_info($after->id);
		$item_data = array();
		$item_data['history'] = $item_info->history. '<li class="smaller">Pada '.date('Y-m-d H:i:s').' layanan diubah oleh '.$CI->session->name.' Field : '.$fieldchanges.'</li>';   
        $CI->Ticket->save($item_data, $after->id);
	}
	
	//exit;
}

function log_ppid($before, $after)
{
	$CI = get_instance();
	$CI->load->model('Ticket');
	
	$fieldchanges = '';
	foreach($CI->db->list_fields('desk_ppid') as $property)
	{

		if($before->$property != $after->$property)
		{
			if(!empty(realName($property)))
				$fieldchanges .= realName($property). '; ';
		}
			
		
	}
	
	if(!empty($fieldchanges))
	{
		$item_info = $CI->Ticket->get_info($after->id);
		$item_data = array();
		$item_data['history'] = $item_info->history. '<li class="smaller">Pada '.date('Y-m-d H:i:s').' layanan diubah oleh '.$CI->session->name.' Field : '.$fieldchanges.'</li>';   
        $CI->Ticket->save($item_data, $after->id);
	}
	
	//exit;
}

function logChangesTicket($oldarrayticket, $item_id)
{
    $CI = get_instance();

    //Ticket
    $CI->load->model('Ticket');
    $newarray = (array) $CI->Ticket->get_info_ticket($item_id);
    
    $diff=array_diff($oldarrayticket,$newarray);
    // print_r("<pre>");
    // print_r($diff);
    // print_r("</pre>");
    
    $item_data['history'] = $oldarrayticket['history'];

    $ischanged = false;

    if(count($diff) > 0){
        $fieldchanges = "";
        foreach ($diff as $key => $value){
            $aexclude=array("lastchange"=>"XX","history"=>"XX");
            if (! array_key_exists($key,$aexclude)){

                $realname = realName($key);

                $ischanged = true;
                $fieldchanges .= " ".$realname ."; ";
            }
        }
        if($ischanged){
		    $item_data['history'] .= '<li class="smaller">Pada '.date('Y-m-d H:i:s').' layanan diubah oleh '.$CI->session->name.' Field : '.$fieldchanges.'</li>';   
            $CI->Ticket->save($item_data, $item_id);
        }
    }
}

function logChangesTicketPPID($oldarrayppid, $item_id)
{
    $CI = get_instance();

    //Ticket
    $CI->load->model('Ticket');

     //PPID
     $oldarrayticket = (array) $CI->Ticket->get_info_ticket($item_id);
     $newarray = (array) $CI->Ticket->get_info_ppid($item_id);
     $diff=array_diff($oldarrayppid,$newarray);
    
     $item_data['history'] = $oldarrayticket['history'];

    $ischanged = false;

     if(count($diff) > 0){
        $fieldchanges = "";
         foreach ($diff as $key => $value){
            $aexclude=array("AAA"=>"AAA","BBB"=>"BBB");
            if (! array_key_exists($key,$aexclude)){  
                $ischanged = true;
                $ischangedPPID = true;
                $realname = realName($key);
                $fieldchanges .= " ".$realname ."; ";
            }
         }
         
        if($ischangedPPID){
            $item_data['history'] .= '<li class="smaller">Pada '.date('Y-m-d H:i:s').' layanan diubah oleh '.$CI->session->name.' Field PPID : '.$fieldchanges.'</li>';   
        }
     }

     if($ischanged){
        $CI->Ticket->save($item_data, $item_id);
     }
}

function realName($key){
    $arr = array(
        "iden_nama" => "Nama Pelapor",
        "iden_jk" => "Jenis Kelamin Pelapor",
        "iden_instansi" => "Instansi",
        "iden_jenis" => "Jenis Perusahaan",
        "iden_alamat" => "Alamat Pelapor",
        "iden_email" => "Email Pelapor",
        "iden_negara" => "Negara",
        "iden_provinsi2" => "Propinsi",
        "iden_kota2" => "Kota/Kab",
        "iden_telp" => "No. Telp",
        "iden_fax" => "No. Fax",
        "iden_profesi" => "Pekerjaan",
		"profesi" => "Pekerjaan",
        "usia" => "Usia",
        "prod_nama" => "Nama Dagang",
        "prod_generik" => "Nama Generik",
        "prod_pabrik" => "Pabrik",
        "prod_noreg" => "No. Reg",
        "prod_nobatch" => "No. Batch",
        "prod_alamat" => "Alamat Produk",
        "prod_kota" => "Kota Produk",
        "prod_negara" => "Negara Produk",
        "prod_provinsi" => "Propinsi Produk",
        "prod_kadaluarsa" => "Tgl Kadaluarsa Produk",
        "prod_diperoleh" => "Diperoleh di",
        "prod_diperoleh_tgl" => "Diperoleh tanggal",
        "prod_digunakan_tgl" => "Tanggal Digunakan",
        "isu_topik" => "Isu Topik",
        "prod_masalah" => "Isi Layanan",
        "tglpengaduan" => "Tgl Layanan",
        "jam" => "Jam",
        "penerima" => "Petugas Penerima",
        "jenis_layanan" => "Jenis Layanan",
        "iden_instansi" => "Jenis Komoditi",
        "submited_via" => "Layanan Melalui",
        "jenis" => "Sumber Data",
        "shift" => "Shift",
        "klasifikasi_id" => "Klasifikasi",
        "subklasifikasi_id" => "Subklasifikasi",
        "jawaban" => "Jawaban",
        "keterangan" => "Keterangan",
        "petugas_entry" => "Nama Petugas Input",
        "penjawab" => "Nama Penjawab",
        "answered_via" => "Dijawab melalui",
		
		"is_rujuk"=> "Perlu Rujuk",
		"direktorat"=> "Rujukan 1",
		"d1_prioritas"=> "SLA Rujukan 1",
		"direktorat2"=> "Rujukan 2",
		"d2_prioritas"=>"SLA Rujukan 2",
		"direktorat3"=>"Rujukan 3",
		"d3_prioritas"=>"SLA Rujukan 3",
		"direktorat4"=>"Rujukan 4",
		"d4_prioritas"=>"SLA Rujukan 4",
		"direktorat5"=>"Rujukan 5",
		"d5_prioritas"=>"SLA Rujukan 5",

        //PPID
        "tgl_diterima" => "Diterima Tanggal",
        "diterima_via" => "Diterima Melalui",
        "no_ktp" => "KTP PPID",
        "rincian" => "Rincian Informasi yang dibutuhkan",
        "tujuan" => "Tujuan Penggunaan Informasi",
        "cara_memperoleh_info" => "Cara Memperoleh Informasi",
        "cara_mendapat_salinan" => "Cara mendapatkan salinan informasi",
        "tgl_pemberitahuan_tertulis" => "Tanggal pemberitahuan tertulis",
        "penguasaan_kami_teks" => "Penguasaan Kami Teks",
        "nama_badan_lain" => "Nama Badan Lain",
        "bentuk_fisik" => "Bentuk fisik yang tersedia",
        "biaya_penyalinan" => "Biaya Penyalinan",
        "biaya_penyalinan_lbr" => "Jml Lembar Penyalinan",
        "biaya_pengiriman" => "Biaya Pengiriman",
        "biaya_lain" => "Biaya lainnya",
        "waktu_penyediaan" => "Waktu Penyediaan",
        "penghitaman" => "Penjelasan penghitaman",
        "info_blm_dikuasai" => "Info belum dikuasai",
        "info_blm_didoc" => "Info belum didokumentasikan",
        "waktu_penyediaan2" => "Penyediaan informasi dilakukan dalam jangka waktu",
        "nama_pejabat_ppid" => "Nama Pejabat PPID",
        "pengecualian_pasal17" => "Pengecualian Pasal 17",
        "pasal17_huruf" => "Pasal 17 huruf",
        "pengecualian_pasal_lain" => "Pengecualian pasal lain",
        "pasal_lain_uu" => "Pasal Lain",
        "konsekuensi" => "Konsekuensi",
        "tt_tgl" => "Tanggal tanggapan tertulis",
        "tt_nomor" => "Tanggapan tertulis nomor",
        "tt_lampiran" => "Tanggapan tertulis lampiran",
        "tt_perihal" => "Tanggapan tertulis perihal",
        "tt_isi" => "Isi tanggapan tertulis",
        "tt_pejabat" => "Pejabat penandatanganan surat",
        "keputusan" => "Keputusan",
        "alasan_ditolak" => "Alasan Ditolak",
        "kuasa_nama" => "Nama Kuasa Pemohon",
        "kuasa_alamat" => "Alamat Kuasa Pemohon",
        "kuasa_telp" => "No. Telp Kuasa Pemohon",
        "kuasa_email" => "Email Kuasa Pemohon",
        "alasan_keberatan" => "Alasan Pengajuan Keberatan",
        "kasus_posisi" => "Kasus Posisi'",
        "tgl_tanggapan" => "Tanggal Tanggapan Akan Diberikan",
        "nama_petugas" => "Petugas Penerima Informasi Keberatan",
        "keberatan_tgl" => "Tanggal keberatan",
        "keberatan_no" => "No Keberatan",
        "keberatan_lampiran" => "Lampiran Keberatan",
        "keberatan_perihal" => "Perihal Keberatan",
        "keberatan_isi_surat" => "Isi surat keberatan",
        "keberatan_nama_pejabat" => "Pejabat penandatangan surat"
    );

    //$returnvalue = (array_key_exists($key,$arr)) ? $arr[$key]: $key;
	$returnvalue = (array_key_exists($key,$arr)) ? $arr[$key]: '';
    return $returnvalue;
}

    function sanitize_input($input)
	{
        $CI = get_instance();
        $CI->load->helper('security');
		// XSS filtering to remove dangerous elements
		$sanitized_input = xss_clean($input);

		// Optionally strip out any tags you do not want
		$allowed_tags = '<b><i><u><code>';
		$sanitized_input = strip_tags($sanitized_input, $allowed_tags);

		return $sanitized_input;
	}

    function escape_input($input)
    {
        // Convert special characters to HTML entities
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    function custom_escape($input)
    {
        // Convert only special HTML characters that could be harmful
        $escaped_input = htmlspecialchars($input, ENT_NOQUOTES, 'UTF-8');

        // Allow typical password characters like @, #, $, etc.
        // Do not escape these as they are not harmful in this context
        $allowed_characters = ['@', '#', '$', '%', '&', '*', '_', '-', '+', '!', '~', '`'];

        // Optionally, handle special characters by converting them back if needed
        foreach ($allowed_characters as $char) {
            $escaped_input = str_replace(htmlspecialchars($char, ENT_NOQUOTES, 'UTF-8'), $char, $escaped_input);
        }

        return $escaped_input;
    }

	function validate_input($input)
	{
        $alert_string = "removed";
        if (strpos($input, $alert_string) !== false) {
            return false;
        } else {
            return true;
        }
	}

    function validate_strong_password($password)
    {
        // Define password complexity requirements
        $min_length = 8;

        // Check length
        if (strlen($password) < $min_length) {
            return false;
        }

        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        // Check for at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        // Check for at least one digit
        if (!preg_match('/\d/', $password)) {
            return false;
        }

        // Check for at least one special character
        if (!preg_match('/[\W_]/', $password)) { // \W matches any non-word character
            return false;
        }

        // If all checks pass, return true
        return true;
    }

?>