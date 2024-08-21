<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class DatabaseM extends CI_Model
{
	public function build_condition($inputKota, $kategori, $inputDatasource, $inputStatusRujukan, $inputUnitRujukan = '')
	{
		$query = "";


		if ($inputKota == 'ALL')
			$filter_cc = " AND info IN ('I','P') ";
		elseif ($inputKota == 'CC')
			$filter_cc = " AND ws > 0 AND info IN ('I','P') ";
		elseif ($inputKota == 'BALAI')
			$filter_cc = " AND ws = 0 AND kota <> 'PUSAT' AND kota <> 'UNIT TEKNIS' AND info IN ('I','P') ";
		elseif ($inputKota == 'PUSAT_BALAI')
			$filter_cc = " AND ws = 0 AND info IN ('I','P') ";
		elseif ($inputKota == 'PUSAT')
			$filter_cc = " AND (ws = 0 AND kota = 'PUSAT') AND info IN ('I','P') ";
		elseif ($inputKota == 'PUSAT_CC')
			$filter_cc = " AND kota = 'PUSAT' AND info IN ('I','P') ";
		elseif ($inputKota == 'PUSAT_UNIT_TEKNIS')
			$filter_cc = " AND (ws > 0 OR (ws = 0 AND kota = 'PUSAT') OR kota ='UNIT TEKNIS') AND info IN ('I','P') ";
		elseif ($inputKota == 'PUSAT_CC_UNIT_TEKNIS')
			$filter_cc = " AND (ws > 0 OR (kota = 'PUSAT' OR kota ='UNIT TEKNIS')) AND info IN ('I','P') ";
		elseif ($inputKota == 'PUSAT_CC_BALAI')
			$filter_cc = " AND (ws > 0 OR (kota !='UNIT TEKNIS')) AND info IN ('I','P') ";
		elseif ($inputKota == 'PUSAT_CC_UNIT_TEKNIS_BALAI')
			$filter_cc = " AND info IN ('I','P') ";
		elseif ($inputKota == 'SIKER')
			$filter_cc = " AND info = 'IK' ";
		elseif ($inputKota == 'UNIT TEKNIS') {
			if (is_pusat())
				$filter_cc = " AND kota = 'UNIT TEKNIS' AND info IN ('I','P') ";
			else
				$filter_cc = " AND owner_dir = '" . $this->session->direktoratid . "' AND info IN ('I','P') ";
		} else
			$filter_cc = " AND kota = '" . $inputKota . "' AND info IN ('I','P') ";

		$query .= $filter_cc;


		if (!empty($kategori)) {
			if ($kategori != 'ALL')
				$query .= " and kategori = '$kategori'";
		}
		if (!empty($inputDatasource)) {
			if ($inputDatasource != "ALL") {
				if ($inputDatasource == "LAYANAN")
					$query .= " and jenis = ''";
				elseif ($inputDatasource == "LAYANAN_SP4N")
					$query .= " and (jenis = '' OR jenis = 'SP4N') ";
				else
					$query .= " and jenis = '$inputDatasource'";
			}
		}
		if (!empty($inputStatusRujukan)) {
			//if($inputStatusRujukan != "ALL")
			//   $query .= " and tl = '$inputStatusRujukan'";

			if ($inputStatusRujukan == "Y")
				$query .= " and tl = 1 ";
			elseif ($inputStatusRujukan == "N")
				$query .= " and tl = 0 ";
		}
		if ((!empty($inputUnitRujukan)) && ($inputUnitRujukan != '') && ($inputUnitRujukan != 'ALL')) {
			$query .= " AND (";
			$query .= " direktorat = " . $inputUnitRujukan . " OR ";
			$query .= " direktorat2 = " . $inputUnitRujukan . " OR ";
			$query .= " direktorat3 = " . $inputUnitRujukan . " OR ";
			$query .= " direktorat4 = " . $inputUnitRujukan . " OR ";
			$query .= " direktorat5 = " . $inputUnitRujukan . " ";
			$query .= " ) ";
		}

		return $query;
	}

	public function get_data_layanan($inputTgl1, $inputTgl2, $inputKota, $filters = array())
	{
		$this->db->select("desk_tickets.id, trackid, iden_nama, iden_alamat, iden_email, iden_telp");
		$this->db->select("desk_tickets.kota, klasifikasi, subklasifikasi");
		$this->db->select("direktorat, direktorat2, direktorat3, direktorat4, direktorat5");
		$this->db->select("submited_via as sarana, tl, fb, fb_isi, tl_date, sla, iden_jk");
		$this->db->select("prod_masalah as detail_laporan, penerima, t1.petugas_entry as kode_petugas , keterangan, is_rujuk");

		$this->db->select("desk_categories.name as jenis_komoditi");
		$this->db->select("desk_profesi.name as pekerjaan");
		$this->db->from('desk_tickets');
		$this->db->join('desk_categories', 'desk_tickets.kategori = desk_categories.id');
		$this->db->join('desk_profesi', 'desk_tickets.iden_profesi = desk_profesi.id');
		//$this->db->join('desk_users','desk_tickets.verified_by = desk_users.id');

		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);


		if (!empty($filters['tl'])) {
		}

		if (!empty($filters['fb'])) {
		}

		if (!empty($filters['sla'])) {
		}


		$this->apply_filter($this->db, $inputKota);

		$query =  $this->db->get();
		return $query->result_array();
	}

	public function apply_filter(&$db, $kota)
	{
		$db->where_in('info', array('P', 'I'));

		switch ($kota) {
			case 'ALL':
			case 'PUSAT_CC_UNIT_TEKNIS_BALAI':
				break;
			case 'CC':
				$db->where('kota', 'PUSAT');
				$db->where('ws >', 0);
				break;
			case 'BALAI':
				$db->where('kota <> ', 'PUSAT');
				$db->where('ws', 0);
				break;
			case 'PUSAT_CC':
				$db->where('kota', 'PUSAT');
				break;
			case 'PUSAT_BALAI':
				$db->where('ws', 0);
				break;
			case 'PUSAT_UNIT_TEKNIS':
				$db->group_start();
				$db->where('kota', 'PUSAT');
				$db->where('ws', 0);
				$db->group_end();
				$db->or_where('kota', 'UNIT TEKNIS');
				break;
			case 'PUSAT_CC_UNIT_TEKNIS':
				$db->where('kota', 'PUSAT');
				$db->or_where('kota', 'UNIT TEKNIS');
				break;
			case 'PUSAT_CC_BALAI':
				$db->where('kota <> ', 'UNIT TEKNIS');
				break;
			case 'PUSAT_CC_UNIT_TEKNIS_BALAI':
				break;
			case 'PUSAT':
				$db->where('kota', 'PUSAT');
				$db->where('ws', 0);
				break;
			default:
				$db->where('kota', $kota);
				$db->where('ws', 0);
		}
	}

	public function get_data_pengaduan_konsumen(
		$inputTgl1,
		$inputTgl2,
		$inputKota,
		$inputKategori,
		$inputDatasource,
		$inputLength,
		$inputStart,
		$inputSearch = '',
		$inputField = '',
		$menu = '',
		$status = '',
		$tl = '',
		$fb = '',
		$sla = '',
		$inputdirektorat = ''
	) {

		$query = "SELECT 
        t1.id, concat(t1.iden_nama, '; ', t1.iden_alamat, '; ', t1.submited_via, '; ', t1.waktu, '; ', t2.name,'; ', t1.iden_instansi, '; ', t1.iden_email, '; ', t1.trackid, '; ') AS identitas_konsumen, t1.iden_nama, t1.iden_alamat, t1.submited_via, t1.waktu, t2.name, t1.iden_instansi, t1.iden_email, t1.iden_telp, t1.trackid, t1.kota, 
        t1.info, t1.jenis,  t1.prod_masalah AS detail_laporan, t1.jawaban, t1.keterangan, t1.hk, t1.sla, 
        t3.name as jenis_komoditi, t1.petugas_entry, t1.is_rujuk, t1.direktorat, t1.direktorat2, t1.direktorat3, t1.direktorat4, t1.direktorat5,  
        t1.isu_topik, t1.klasifikasi, t1.subklasifikasi, t2.name as pekerjaan, t1.iden_jk, 
        t1.submited_via as sarana, t1.waktu, t1.shift, t1.penerima, t1.fb, t1.fb_isi, date_format(fb_date,'%Y-%m-%d') as fb_date, 
        IFNULL(datediff(closed_date,sent_date), '') as rujuk_tl_hr, date_format(verified_date,'%Y-%m-%d') as verified_date, 
		TOTAL_HK(tglpengaduan, tl_date) as waktu_layanan, t1.status, t1.tl, t4.name as verificator_name, date_format(dt,'%Y-%m-%d') as dt, tglpengaduan, 
		date_format(closed_date,'%Y-%m-%d') as closed_date, is_verified, date_format(tl_date,'%Y-%m-%d') as tl_date   
        from desk_tickets AS t1 
        LEFT JOIN desk_profesi AS t2 ON t1.iden_profesi=t2.id 
        LEFT JOIN desk_categories AS t3 ON t3.id=t1.kategori 
        LEFT JOIN desk_users t4 ON t4.id = t1.verified_by 
        where tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' ";

		//if($menu == 'YANBLIK')
		//	$query .= " AND klasifikasi = 'Layanan Publik' ";

		if ($menu == 'VERIFIKASISAYA')
			$query .= " AND verified_by = " . $this->session->id . " ";

		if ($status != '')
			$query .= " AND t1.status = '$status' ";

		if ((!empty($tl) && ($tl != '') && ($tl != 'ALL')))
			$query .= " AND tl = '$tl' ";

		if ((!empty($fb) && ($fb != '') && ($fb != 'ALL')))
			$query .= " AND fb = '$fb' ";

		if ((!empty($sla) && ($sla != '') && ($sla != 'ALL')))
			$query .= " AND sla = '$sla' ";


		$query .= $this->build_condition($inputKota, $inputKategori, $inputDatasource, "", $inputdirektorat);

		if (!empty($inputSearch)) {

			if ($inputField == 'trackid')
				$query .= " AND (t1.trackid = '$inputSearch')";
			elseif ($inputField == 'cust_nama')
				$query .= " AND (t1.iden_nama LIKE '%$inputSearch%')";
			elseif ($inputField == 'cust_email')
				$query .= " AND (t1.iden_email LIKE '%$inputSearch%')";
			elseif ($inputField == 'cust_telp')
				$query .= " AND (t1.iden_telp LIKE '%$inputSearch%')";
			elseif ($inputField == 'isu_topik')
				$query .= " AND (t1.isu_topik LIKE '%$inputSearch%')";
			elseif ($inputField == 'isi_layanan')
				$query .= " AND (t1.prod_masalah LIKE '%$inputSearch%')";
			elseif ($inputField == 'jawaban')
				$query .= " AND (t1.jawaban LIKE '%$inputSearch%')";
			elseif ($inputField == 'penerima')
				$query .= " AND (t1.penerima LIKE '%$inputSearch%')";
			elseif ($inputField == 'klasifikasi')
				$query .= " AND (t1.klasifikasi LIKE '%$inputSearch%')";
			elseif ($inputField == 'subklasifikasi')
				$query .= " AND (t1.subklasifikasi LIKE '%$inputSearch%')";
		}

		//$query .= " order by t1.id asc ";
		// echo $query;
		// exit;
		/*if($inputLength!= ""){
			$query .= " LIMIT $inputStart, $inputLength ";  
		}*/
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_data_yanblik(
		$inputTgl1,
		$inputTgl2,
		$inputKota,
		$inputKategori,
		$inputDatasource,
		$inputLength,
		$inputStart,
		$inputSearch = '',
		$inputField = '',
		$menu = '',
		$status = '',
		$tl = '',
		$fb = '',
		$direktorat
	) {

		$query = "SELECT 
        t1.id, concat(t1.iden_nama, '; ', t1.iden_alamat, '; ', t1.submited_via, '; ', t1.waktu, '; ', t2.name,'; ', t1.iden_instansi, '; ', t1.iden_email, '; ', t1.trackid, '; ') AS identitas_konsumen, t1.iden_nama, t1.iden_alamat, t1.submited_via, t1.waktu, t2.name, t1.iden_instansi, t1.iden_email, t1.iden_telp, t1.trackid, t1.kota, 
        t1.info, t1.prod_masalah AS detail_laporan, t1.jawaban, t1.keterangan, t1.hk, t1.sla, 
        t3.name as jenis_komoditi, t1.petugas_entry, t1.is_rujuk,  
        t1.isu_topik, t1.klasifikasi, t1.subklasifikasi, t2.name as pekerjaan, t1.iden_jk, 
        t1.submited_via as sarana, t1.waktu, t1.shift, t1.penerima, t1.fb, t1.fb_isi, date_format(fb_date,'%Y-%m-%d') as fb_date, 
        IFNULL(datediff(closed_date,sent_date), '') as rujuk_tl_hr, date_format(verified_date,'%Y-%m-%d') as verified_date, 
		TOTAL_HK(tglpengaduan, tl_date) as waktu_layanan, t1.status, t1.tl, t4.name as verificator_name, date_format(dt,'%Y-%m-%d') as dt, tglpengaduan, 
		date_format(closed_date,'%Y-%m-%d') as closed_date, is_verified, date_format(tl_date,'%Y-%m-%d') as tl_date   
        from desk_tickets AS t1 
        LEFT JOIN desk_profesi AS t2 ON t1.iden_profesi=t2.id 
        LEFT JOIN desk_categories AS t3 ON t3.id=t1.kategori 
        LEFT JOIN desk_users t4 ON t4.id = t1.verified_by 
        where tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' ";

		//if($menu == 'YANBLIK')
		//	$query .= " AND klasifikasi = 'Layanan Publik' ";
		$query .= " AND subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";

		if ($menu == 'VERIFIKASISAYA')
			$query .= " AND verified_by = " . $this->session->id . " ";

		if ($status != '')
			$query .= " AND t1.status = '$status' ";

		if ($tl != '')
			$query .= " AND tl = '$tl' ";

		if ($fb != '')
			$query .= " AND fb = '$fb' ";


		$query .= $this->build_condition($inputKota, $inputKategori, $inputDatasource, "", $direktorat);

		if (!empty($inputSearch)) {

			if ($inputField == 'trackid')
				$query .= " AND (t1.trackid = '$inputSearch')";
			elseif ($inputField == 'cust_nama')
				$query .= " AND (t1.iden_nama LIKE '%$inputSearch%')";
			elseif ($inputField == 'cust_email')
				$query .= " AND (t1.iden_email LIKE '%$inputSearch%')";
			elseif ($inputField == 'cust_telp')
				$query .= " AND (t1.iden_telp LIKE '%$inputSearch%')";
			elseif ($inputField == 'isu_topik')
				$query .= " AND (t1.isu_topik LIKE '%$inputSearch%')";
			elseif ($inputField == 'isi_layanan')
				$query .= " AND (t1.prod_masalah LIKE '%$inputSearch%')";
			elseif ($inputField == 'jawaban')
				$query .= " AND (t1.jawaban LIKE '%$inputSearch%')";
			elseif ($inputField == 'penerima')
				$query .= " AND (t1.penerima LIKE '%$inputSearch%')";
		}

		//$query .= " order by t1.id asc ";
		//echo $query;
		/*if($inputLength!= ""){
			$query .= " LIMIT $inputStart, $inputLength ";  
		}*/
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_data_rujukan($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, $inputStatusRujukan, $reportType = '')
	{
		$query = "select t1.id, t1.direktorat, t1.direktorat2, t1.direktorat3, t1.direktorat4, t1.direktorat5, concat(t1.iden_nama, '; ', t1.iden_jk, '; ', t1.iden_alamat, '; ', t1.submited_via, '; ', t1.waktu, '; ', t2.name,'; ', t1.iden_instansi, '; ', t1.iden_email, '; ', t1.trackid, '; ') AS identitas_konsumen, 
		t1.info, t1.prod_masalah AS detail_laporan, t1.jawaban, t1.keterangan, t3.name as jenis_komoditi, t1.petugas_entry as kode_petugas ,
		t1.isu_topik, t1.klasifikasi, t1.subklasifikasi, t2.name as pekerjaan, t1.submited_via as sarana, t1.waktu, t1.shift, t1.penerima,
		IFNULL(datediff(closed_date,sent_date), '') as rujuk_tl_hr, t1.tl,  t4.name as verificator_name,  
		IFNULL((1 + 5 * ((DATEDIFF(tl_date, dt) ) DIV 7) + MID('0123455501234445012333450122234501101234000123450', 7 * WEEKDAY(dt) + WEEKDAY(tl_date) + 1, 1) - (select count(tgl) from desk_holiday where tgl between dt AND tl_date)), 0) as sla, fb, fb_date, fb_isi  
		from desk_tickets AS t1  
		LEFT JOIN desk_profesi AS t2 ON t1.iden_profesi=t2.id 
		LEFT JOIN desk_categories AS t3 ON t3.id=t1.kategori 
		LEFT JOIN desk_users t4 ON t4.id = t1.verified_by 
        where tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' ";


		if (empty($reportType)) // database
		{
			$query .= $this->build_condition($inputKota, $inputKategori, $inputDatasource, $inputStatusRujukan);
			$query .= " AND is_rujuk = '1' ";
		} else {
			if ($reportType == '1') {
				$query .= " AND (direktorat = '" . $this->session->direktoratid . "' OR direktorat2 = '" . $this->session->direktoratid . "' OR direktorat3 = '" . $this->session->direktoratid . "' OR direktorat4 = '" . $this->session->direktoratid . "' OR direktorat5 = '" . $this->session->direktoratid . "')";
			}
		}


		$query .= " order by id asc";
		//echo $query;

		$results = $this->db->query($query);
		return $results->result_array();
	}

	/* Database Rujukan */
	public function get_data_rujukan2($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, $inputStatusRujukan, $inputUnitRujukan, $reportType = '')
	{
		$query = "select t1.id, t1.direktorat, t1.direktorat2, t1.direktorat3, t1.direktorat4, t1.direktorat5, t1.iden_nama, t1.iden_alamat, t1.iden_telp, t1.iden_jk,  t1.submited_via,  t1.waktu, t2.name, t1.iden_instansi,  t1.iden_email,  t1.trackid, t1.info, t1.prod_masalah AS detail_laporan, t1.jawaban, t1.keterangan, t3.name as jenis_komoditi, t1.petugas_entry as kode_petugas, t1.isu_topik, t1.klasifikasi, t1.subklasifikasi, t2.name as pekerjaan, t1.submited_via as sarana, t1.waktu, t1.shift, t1.penerima, t1.jenis, t1.is_verified, t1.tl,  t4.name as verificator_name, sla, fb, fb_date, fb_isi, date_format(tl_date,'%d/%m/%Y') as tl_date, date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan, date_format(verified_date,'%d/%m/%Y') as verified_date, date_format(fb_date,'%d/%m/%Y') as fb_date, date_format(closed_date,'%d/%m/%Y') as closed_date, status, kota, TOTAL_HK(tglpengaduan, tl_date) as hk    
		from desk_tickets AS t1  
		LEFT JOIN desk_profesi AS t2 ON t1.iden_profesi=t2.id 
		LEFT JOIN desk_categories AS t3 ON t3.id=t1.kategori 
		LEFT JOIN desk_users t4 ON t4.id = t1.verified_by 
        where tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' ";


		if (empty($reportType)) // database
		{
			$query .= $this->build_condition($inputKota, $inputKategori, $inputDatasource, $inputStatusRujukan, $inputUnitRujukan);
			$query .= " AND is_rujuk = '1' ";
		} else {
			if ($reportType == '1') {
				$query .= " AND (direktorat = '" . $this->session->direktoratid . "' OR direktorat2 = '" . $this->session->direktoratid . "' OR direktorat3 = '" . $this->session->direktoratid . "' OR direktorat4 = '" . $this->session->direktoratid . "' OR direktorat5 = '" . $this->session->direktoratid . "')";
			}
		}


		$query .= " order by id asc";
		//echo $query;

		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_data_yanblik2($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, $inputStatusRujukan, $reportType = '')
	{
		$query = "select t1.id, t1.direktorat, t1.direktorat2, t1.direktorat3, t1.direktorat4, t1.direktorat5, t1.iden_nama, t1.iden_alamat, t1.iden_telp, t1.iden_jk,  t1.submited_via,  t1.waktu, t2.name, t1.iden_instansi,  t1.iden_email,  t1.trackid, t1.info, t1.prod_masalah AS detail_laporan, t1.jawaban, t1.keterangan, t3.name as jenis_komoditi, t1.petugas_entry as kode_petugas, t1.isu_topik, t1.klasifikasi, t1.subklasifikasi, t2.name as pekerjaan, t1.submited_via as sarana, t1.waktu, t1.shift, t1.penerima, t1.jenis, t1.is_verified, t1.tl,  t4.name as verificator_name, sla, fb, fb_date, fb_isi, date_format(tl_date,'%d/%m/%Y') as tl_date, date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan, date_format(verified_date,'%d/%m/%Y') as verified_date, date_format(fb_date,'%d/%m/%Y') as fb_date, date_format(closed_date,'%d/%m/%Y') as closed_date, status, kota, TOTAL_HK(tglpengaduan, tl_date) as hk    
		from desk_tickets AS t1  
		LEFT JOIN desk_profesi AS t2 ON t1.iden_profesi=t2.id 
		LEFT JOIN desk_categories AS t3 ON t3.id=t1.kategori 
		LEFT JOIN desk_users t4 ON t4.id = t1.verified_by 
        where tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' AND subklasifikasi IN ('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";


		if (empty($reportType)) // database
		{
			$query .= $this->build_condition($inputKota, $inputKategori, $inputDatasource, $inputStatusRujukan);
			//$query .= " AND is_rujuk = '1' ";
		} else {
			if ($reportType == '1') {
				$query .= " AND (direktorat = '" . $this->session->direktoratid . "' OR direktorat2 = '" . $this->session->direktoratid . "' OR direktorat3 = '" . $this->session->direktoratid . "' OR direktorat4 = '" . $this->session->direktoratid . "' OR direktorat5 = '" . $this->session->direktoratid . "')";
			}
		}


		$query .= " order by id asc";
		//echo $query;

		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_status_rujukan($id, $no_urut)
	{

		$query = "SELECT status_rujuk$no_urut as status, tl_date$no_urut as tl_date, TOTAL_HK(tglpengaduan, tl_date$no_urut) as waktu_penyelesaian FROM desk_rujukan, desk_tickets WHERE desk_rujukan.rid = desk_tickets.id AND desk_tickets.id = '$id'";
		//echo $query;
		//exit;
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_info_direktorat($dir_id)
	{
		$query = "SELECT concat(name, ' ( ', kota, ' )') as names FROM desk_direktorat WHERE id = '$dir_id'";
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_info_desk_reply($dir_id, $replyto)
	{
		$query = "select a.name, a.message , a.dt
		from desk_replies a, desk_users b 
		where a.staffid = b.id AND b.direktoratid = '$dir_id' and replyto = '$replyto'";
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_data_resume($inputTgl1, $inputTgl2, $kota, $inputLength, $inputStart, $inputSearch, $direktorat)
	{
		$sql = "SELECT  id, info, concat(iden_nama, '; ', iden_jk, '; ', iden_alamat, '; ', submited_via, '; ', waktu, '; ', iden_instansi, '; ', iden_email, '; ', trackid, '; ') AS identitas_konsumen, prod_masalah, jawaban, keterangan 
				from desk_tickets 
				where tglpengaduan between '$inputTgl1' AND '$inputTgl2' ";

		$sql .= $this->get_condition_resume($kota, $direktorat);


		/*if($inputSearch != ''){
			$sql .= " AND (iden_nama like '%$inputSearch%' OR prod_masalah like '%$inputSearch%')";
		}*/

		$sql .= " order by desk_tickets.id, waktu ASC ";
		/*if($inputLength != ''){
			$sql .= " LIMIT $inputStart, $inputLength ";  
		}*/
		// print_r($sql);
		// die;
		$results = $this->db->query($sql);
		return $results->result_array();
	}

	public function get_condition_resume($kota, $inputUnitRujukan)
	{
		$sql = "";

		if ($kota == 'ALL')
			$sql = "";
		elseif ($kota == 'PUSAT')
			$sql = " AND ws = 0 AND info IN ('I','P') AND kota = 'PUSAT' ";
		elseif ($kota == 'PUSAT_BALAI')
			$sql = " AND ws = 0 AND info IN ('I','P')";
		elseif ($kota == 'PUSAT_CC')
			$sql = " AND (ws > 0 OR (ws = 0 AND kota = 'PUSAT')) AND info IN ('I','P') ";
		elseif ($kota == 'PUSAT_UNIT_TEKNIS')
			$sql = " AND (ws > 0 OR (ws = 0 AND kota = 'PUSAT') OR kota = 'UNIT TEKNIS') AND info IN ('I','P') ";
		elseif ($kota == 'PUSAT_CC_UNIT_TEKNIS')
			$sql = " AND (kota = 'PUSAT' OR kota = 'UNIT TEKNIS') AND info IN ('I','P') ";
		elseif ($kota == 'PUSAT_CC_BALAI')
			$sql = " AND (kota != 'UNIT TEKNIS') AND info IN ('I','P') ";
		elseif ($kota == 'PUSAT_CC_UNIT_TEKNIS_BALAI')
			$sql = " AND info IN ('I','P')) ";
		elseif ($kota == 'CC')
			$sql = " AND ws > 0 AND info IN ('I','P') ";
		elseif ($kota == 'BALAI')
			$sql = " AND ws = 0 AND kota <> 'PUSAT' AND info IN ('I','P') ";
		else
			$sql = " AND kota = '$kota' AND info IN ('I','P') ";

		if ((!empty($inputUnitRujukan)) && ($inputUnitRujukan != '') && ($inputUnitRujukan != 'ALL')) {
			$sql .= " AND (";
			$sql .= " direktorat = " . $inputUnitRujukan . " OR ";
			$sql .= " direktorat2 = " . $inputUnitRujukan . " OR ";
			$sql .= " direktorat3 = " . $inputUnitRujukan . " OR ";
			$sql .= " direktorat4 = " . $inputUnitRujukan . " OR ";
			$sql .= " direktorat5 = " . $inputUnitRujukan . " ";
			$sql .= " ) ";
		}

		return $sql;
	}

	public function get_data_monbalai($inputTgl1, $inputTgl2)
	{

		$sql = "select a.nama_balai, ifnull(b.total,0) as total,  ifnull(d.cnt,0) as sts_closed, ifnull(e.cnt,0) as tl, ifnull(b.rata,0) as rata, ifnull(f.cnt,0) as sla_yes from desk_balai a left join (select kota as name, count(*) as total, avg(hk) as rata from desk_tickets WHERE `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' group by kota) b ON a.nama_balai = b.name  left join (select kota as name, count(*) as cnt from desk_tickets WHERE `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND status = '3' group by kota) d ON a.nama_balai = d.name left join (select kota as name, count(*) as cnt from desk_tickets WHERE `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND tl = 1 group by kota) e ON a.nama_balai = e.name left join (select kota as name, count(*) as cnt from desk_tickets WHERE `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND hk <= sla group by kota) f ON a.nama_balai = f.name order by a.nama_balai asc";

		$results = $this->db->query($sql);
		return $results->result_array();
	}
}
