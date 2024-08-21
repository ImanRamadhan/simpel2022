<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class DatabaseP extends CI_Model
{
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

	public function get_data_resume($search, $filters = array())
	{
		$this->db->select('desk_tickets.id, trackid, iden_nama, iden_alamat, iden_telp, iden_email, iden_jk, direktorat, direktorat2, direktorat3, direktorat4, direktorat5');
		$this->db->select('lastchange, status, prod_masalah as detail_laporan, info, jawaban, keterangan, penerima ');
		$this->db->select('isu_topik, klasifikasi, subklasifikasi, kota, waktu, shift');
		$this->db->select('submited_via as sarana, petugas_entry as kode_petugas, tl, tl_date, verified_date');
		$this->db->select('is_verified, sla, closed_date, fb, fb_isi, fb_date, jenis');
		$this->db->select('TOTAL_HK(tglpengaduan, tl_date) as waktu_layanan');
		$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		$this->db->select("date_format(closed_date,'%d/%m/%Y') as closed_date");
		$this->db->select("date_format(tl_date,'%d/%m/%Y') as tl_date");
		$this->db->select("date_format(fb_date,'%d/%m/%Y') as fb_date");
		$this->db->select("date_format(verified_date,'%d/%m/%Y') as verified_date");
		$this->db->select('desk_categories.name as jenis_komoditi');
		$this->db->select('desk_profesi.name as pekerjaan');
		$this->db->select('desk_users.name as verificator_name');

		$this->db->from('desk_tickets');
		$this->db->join('desk_categories', 'desk_categories.id=desk_tickets.kategori', 'left');
		$this->db->join('desk_profesi', 'desk_profesi.id=desk_tickets.iden_profesi', 'left');
		$this->db->join('desk_users', 'desk_users.id=desk_tickets.verified_by', 'left');

		// $this->db->where('closed_date IS NOT NULL');
		// $this->db->where('tl', 1);
		// $this->db->where('tl_date IS NOT NULL');

		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if ($this->session->city == 'PUSAT') {
			if (!empty($filters['kota'])) {
				if ($filters['kota'] == 'UNIT_TEKNIS') {
					$filters['kota'] = 'UNIT TEKNIS';
				}
				$this->apply_filter($this->db, $filters['kota']);
			}
		} elseif ($this->session->city == 'UNIT TEKNIS') {
			$this->db->where('owner_dir', $this->session->direktoratid);
		} else {
			$this->db->where('kota', $this->session->city);
		}

		if (!empty($filters['keyword'])) {
			$field = $filters['field'];
			if ($field == 'trackid') {
				$this->db->where('desk_tickets.trackid', $filters['keyword']);
			} elseif ($field == 'cust_nama') {
				$this->db->like('desk_tickets.iden_nama', $filters['keyword']);
			} elseif ($field == 'cust_email') {
				$this->db->like('desk_tickets.iden_email', $filters['keyword']);
			} elseif ($field == 'cust_telp') {
				$this->db->like('desk_tickets.iden_telp', $filters['keyword']);
			} elseif ($field == 'isu_topik') {
				$this->db->like('desk_tickets.isu_topik', $filters['keyword']);
			} elseif ($field == 'isi_layanan') {
				$this->db->like('desk_tickets.prod_masalah', $filters['keyword']);
			} elseif ($field == 'jawaban') {
				$this->db->like('desk_tickets.jawaban', $filters['keyword']);
			} elseif ($field == 'penerima') {
				$this->db->like('desk_tickets.penerima', $filters['keyword']);
			}
		}

		if (!empty($filters['jenis']) && $filters['jenis'] != 'ALL') {
			if ($filters['jenis'] == 'LAYANAN')
				$this->db->where('jenis', '');
			elseif ($filters['jenis'] == 'LAYANAN_SP4N')
				$this->db->where_in('jenis', array('', 'SP4N'));
			else
				$this->db->where('jenis', $filters['jenis']);
		}

		if (!empty($filters['kategori']) && $filters['kategori'] != 'ALL') {
			$this->db->where('kategori', $filters['kategori']);
		}

		if (!empty($filters['status'])) {
			$statuses = array();
			foreach ($filters['status'] as $v) {
				$statuses[] = "'" . $v . "'";
			}
			$this->db->where_in('status', $statuses, FALSE);
		}

		if (!empty($filters['direktorat']) && $filters['direktorat'] != 'ALL') {
			$this->db->group_start();
			$this->db->where('direktorat', $filters['direktorat']);
			$this->db->or_where('direktorat2', $filters['direktorat']);
			$this->db->or_where('direktorat3', $filters['direktorat']);
			$this->db->or_where('direktorat4', $filters['direktorat']);
			$this->db->or_where('direktorat5', $filters['direktorat']);
			$this->db->group_end();
		}
		$this->db->where_in('info', array('P', 'I'));

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			//$this->db->or_like('custom2', $search);
			$this->db->group_end();
		}
		$query =  $this->db->get();
		return $query->result_array();
	}

	public function get_data_layanan($search, $filters = array())
	{
		$this->db->select('desk_tickets.id, trackid, iden_nama, iden_alamat, iden_telp, iden_email, iden_jk, direktorat, direktorat2, direktorat3, direktorat4, direktorat5');
		$this->db->select('lastchange, status, prod_masalah as detail_laporan, info, jawaban, keterangan, penerima ');
		$this->db->select('isu_topik, klasifikasi, subklasifikasi, kota, waktu, shift');
		$this->db->select('submited_via as sarana, petugas_entry as kode_petugas, tl, tl_date, verified_date');
		$this->db->select('is_verified, sla, closed_date, fb, fb_isi, fb_date, jenis');
		//$this->db->select('status, is_rujuk, tl, fb, is_verified, hk, is_sent, lastchange, prod_masalah as problem');
		/*$this->db->select("CASE WHEN is_rujuk = '1' AND status = '3' THEN hk WHEN is_rujuk = '1' AND status <> '3' THEN  5 * ((DATEDIFF( NOW() , dt) ) DIV 7) + MID('0123455501234445012333450122234501101234000123450', 7 * WEEKDAY(dt) + WEEKDAY(NOW()) + 1, 1) - (select count(tgl) from desk_holiday where tgl between dt AND NOW()) ELSE 0 END as outstanding");*/
		$this->db->select('TOTAL_HK(tglpengaduan, tl_date) as waktu_layanan');
		$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		$this->db->select("date_format(closed_date,'%d/%m/%Y') as closed_date");
		$this->db->select("date_format(tl_date,'%d/%m/%Y') as tl_date");
		$this->db->select("date_format(fb_date,'%d/%m/%Y') as fb_date");
		$this->db->select("date_format(verified_date,'%d/%m/%Y') as verified_date");
		$this->db->select('desk_categories.name as jenis_komoditi');
		$this->db->select('desk_profesi.name as pekerjaan');
		$this->db->select('desk_users.name as verificator_name');

		$this->db->from('desk_tickets');
		$this->db->join('desk_categories', 'desk_categories.id=desk_tickets.kategori', 'left');
		$this->db->join('desk_profesi', 'desk_profesi.id=desk_tickets.iden_profesi', 'left');
		$this->db->join('desk_users', 'desk_users.id=desk_tickets.verified_by', 'left');

		// $this->db->where('closed_date IS NOT NULL');
		// $this->db->where('tl', 1);
		// $this->db->where('tl_date IS NOT NULL');

		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if ($this->session->city == 'PUSAT') {
			if (!empty($filters['kota'])) {
				if ($filters['kota'] == 'UNIT_TEKNIS') {
					$filters['kota'] = 'UNIT TEKNIS';
				}
				$this->apply_filter($this->db, $filters['kota']);
			}
		} elseif ($this->session->city == 'UNIT TEKNIS') {
			$this->db->where('owner_dir', $this->session->direktoratid);
		} else {
			$this->db->where('kota', $this->session->city);
		}

		if (!empty($filters['keyword'])) {
			$field = $filters['field'];
			if ($field == 'trackid') {
				$this->db->where('desk_tickets.trackid', $filters['keyword']);
			} elseif ($field == 'cust_nama') {
				$this->db->like('desk_tickets.iden_nama', $filters['keyword']);
			} elseif ($field == 'cust_email') {
				$this->db->like('desk_tickets.iden_email', $filters['keyword']);
			} elseif ($field == 'cust_telp') {
				$this->db->like('desk_tickets.iden_telp', $filters['keyword']);
			} elseif ($field == 'isu_topik') {
				$this->db->like('desk_tickets.isu_topik', $filters['keyword']);
			} elseif ($field == 'isi_layanan') {
				$this->db->like('desk_tickets.prod_masalah', $filters['keyword']);
			} elseif ($field == 'jawaban') {
				$this->db->like('desk_tickets.jawaban', $filters['keyword']);
			} elseif ($field == 'penerima') {
				$this->db->like('desk_tickets.penerima', $filters['keyword']);
			}
		}

		if (!empty($filters['jenis']) && $filters['jenis'] != 'ALL') {
			if ($filters['jenis'] == 'LAYANAN')
				$this->db->where('jenis', '');
			elseif ($filters['jenis'] == 'LAYANAN_SP4N')
				$this->db->where_in('jenis', array('', 'SP4N'));
			else
				$this->db->where('jenis', $filters['jenis']);
		}

		if (!empty($filters['kategori']) && $filters['kategori'] != 'ALL') {
			$this->db->where('kategori', $filters['kategori']);
		}

		if (!empty($filters['status'])) {
			$statuses = array();
			foreach ($filters['status'] as $v) {
				$statuses[] = "'" . $v . "'";
			}
			$this->db->where_in('status', $statuses, FALSE);
		}

		if (!empty($filters['direktorat']) && ($filters['direktorat'] != 'ALL') && ($filters['direktorat'] != '')) {
			$this->db->group_start();
			$this->db->where('direktorat', $filters['direktorat']);
			$this->db->or_where('direktorat2', $filters['direktorat']);
			$this->db->or_where('direktorat3', $filters['direktorat']);
			$this->db->or_where('direktorat4', $filters['direktorat']);
			$this->db->or_where('direktorat5', $filters['direktorat']);
			$this->db->group_end();
		}




		/*if(!empty($filters['is_rujuk']))
		{
			$is_rujuks = array();
			foreach($filters['is_rujuk'] as $v)
			{
				$is_rujuks[] = "'".$v."'";
			}
			$this->db->where_in('is_rujuk', $is_rujuks, FALSE);
		}*/
		/*if(!empty($filters['tl']))
		{
			$this->db->where_in('tl',$filters['tl']);
		}
		if(!empty($filters['fb']))
		{
			$this->db->where_in('fb',$filters['fb']);
		}*/
		/*if(!empty($filters['is_verified']))
		{
			$this->db->where_in('is_verified',$filters['is_verified']);
		}*/

		$this->db->where_in('info', array('P', 'I'));

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			//$this->db->or_like('custom2', $search);
			$this->db->group_end();
		}


		// echo $this->db->get_compiled_select();
		// exit;

		// get_found_rows case

		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		//$this->db->order_by($sort, $order);


		//$this->apply_filter($this->db, $inputKota);

		$query =  $this->db->get();
		return $query->result_array();
	}

	public function get_data_layanan_saya($search, $filters = array())
	{
		$this->db->select("desk_tickets.id, trackid, iden_nama, iden_alamat, iden_email, iden_telp, iden_jk, status");
		$this->db->select("desk_tickets.kota, klasifikasi, subklasifikasi, jawaban, info, isu_topik, waktu, shift");
		$this->db->select("direktorat, direktorat2, direktorat3, direktorat4, direktorat5");
		$this->db->select("submited_via as sarana, tl, fb, fb_isi, fb_date, tl_date, sla, closed_date, jenis, petugas_entry as kode_petugas");
		$this->db->select("prod_masalah as detail_laporan, penerima, petugas_entry, keterangan, is_rujuk, is_verified, verified_date");
		$this->db->select("TOTAL_HK(tglpengaduan, tl_date) as waktu_layanan, tglpengaduan");

		$this->db->select("desk_categories.name as jenis_komoditi");
		$this->db->select("desk_profesi.name as pekerjaan");
		$this->db->select("desk_users.name as verificator_name");
		$this->db->from('desk_tickets');
		$this->db->join('desk_categories', 'desk_tickets.kategori = desk_categories.id', 'left');
		$this->db->join('desk_profesi', 'desk_tickets.iden_profesi = desk_profesi.id', 'left');
		$this->db->join('desk_users', 'desk_tickets.verified_by = desk_users.id', 'left');

		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}


		$this->db->where('owner', $this->session->id);


		if (!empty($filters['keyword'])) {
			$field = $filters['field'];
			if ($field == 'trackid') {
				$this->db->where('desk_tickets.trackid', $filters['keyword']);
			} elseif ($field == 'cust_nama') {
				$this->db->like('desk_tickets.iden_nama', $filters['keyword']);
			} elseif ($field == 'cust_email') {
				$this->db->like('desk_tickets.iden_email', $filters['keyword']);
			} elseif ($field == 'cust_telp') {
				$this->db->like('desk_tickets.iden_telp', $filters['keyword']);
			} elseif ($field == 'isu_topik') {
				$this->db->like('desk_tickets.isu_topik', $filters['keyword']);
			} elseif ($field == 'isi_layanan') {
				$this->db->like('desk_tickets.prod_masalah', $filters['keyword']);
			} elseif ($field == 'jawaban') {
				$this->db->like('desk_tickets.jawaban', $filters['keyword']);
			} elseif ($field == 'penerima') {
				$this->db->like('desk_tickets.penerima', $filters['keyword']);
			} elseif ($field == 'klasifikasi') {
				$this->db->like('desk_tickets.klasifikasi', $filters['keyword']);
			} elseif ($field == 'subklasifikasi') {
				$this->db->like('desk_tickets.subklasifikasi', $filters['keyword']);
			}
		}

		if (!empty($filters['iden_profesi'])) {
			$this->db->where('iden_profesi', $filters['iden_profesi']);
		}

		if (!empty($filters['jenis'])) {
			if ($filters['jenis'] == 'LAYANAN') {
				$this->db->where('jenis', '');
			} else
				$this->db->where('jenis', $filters['jenis']);
		}

		if (!empty($filters['kategori'])) {
			$this->db->where('kategori', $filters['kategori']);
		}

		if (!empty($filters['submited_via'])) {
			$this->db->where('submited_via', $filters['submited_via']);
		}

		if (!empty($filters['status'])) {
			$status_ = explode(',', $filters['status']);
			$statuses = array();
			foreach ($status_ as $v) {
				$statuses[] = "'" . $v . "'";
			}
			$this->db->where_in('status', $statuses, FALSE);
		}
		/*if(!empty($filters['is_rujuk']))
		{
			$is_rujuks = array();
			foreach($filters['is_rujuk'] as $v)
			{
				$is_rujuks[] = "'".$v."'";
			}
			$this->db->where_in('is_rujuk', $is_rujuks, FALSE);
		}*/
		if (!empty($filters['tl']) && $filters['tl'] != 'ALL') {
			$tl_ = explode(',', $filters['tl']);
			$tls = array();
			foreach ($tl_ as $v) {
				$tls[] = $v;
			}
			$this->db->where_in('tl', $tls);
		}
		if (!empty($filters['fb']) && $filters['fb'] != 'ALL') {
			$fb_ = explode(',', $filters['fb']);
			$fbs = array();
			foreach ($fb_ as $v) {
				$fbs[] = $v;
			}
			$this->db->where_in('fb', $fbs);
		}
		if (!empty($filters['sla']) && $filters['sla'] != 'ALL') {
			$this->db->where_in('sla', $filters['sla']);
			$sla_ = explode(',', $filters['sla']);
			$slas = array();
			foreach ($sla_ as $v) {
				$slas[] = $v;
			}
			$this->db->where_in('sla', $slas);
		}
		/*if(!empty($filters['is_verified']))
		{
			$this->db->where_in('is_verified',$filters['is_verified']);
		}*/

		if (!empty($filters['direktorat']) && $filters['direktorat'] != 'ALL') {
			$this->db->group_start();
			$this->db->where('direktorat', $filters['direktorat']);
			$this->db->or_where('direktorat2', $filters['direktorat']);
			$this->db->or_where('direktorat3', $filters['direktorat']);
			$this->db->or_where('direktorat4', $filters['direktorat']);
			$this->db->or_where('direktorat5', $filters['direktorat']);
			$this->db->group_end();
		}

		$this->db->where_in('info', array('P', 'I'));

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			//$this->db->or_like('custom2', $search);
			$this->db->group_end();
		}

		//echo $this->db->get_compiled_select();
		//exit;

		//$this->apply_filter($this->db, $inputKota);

		$query =  $this->db->get();
		return $query->result_array();
	}

	public function get_data_rujukan_masuk($search, $filters = array())
	{
		$this->db->select("desk_tickets.id, trackid, iden_nama, iden_alamat, iden_email, iden_telp, iden_jk, status");
		$this->db->select("desk_tickets.kota, klasifikasi, subklasifikasi, jawaban, info, isu_topik, waktu, shift");
		$this->db->select("direktorat, direktorat2, direktorat3, direktorat4, direktorat5");
		$this->db->select("submited_via as sarana, tl, fb, fb_isi, fb_date, tl_date, sla, closed_date, jenis, petugas_entry as kode_petugas");
		$this->db->select("prod_masalah as detail_laporan, penerima, petugas_entry, keterangan, is_rujuk, is_verified, verified_date");
		$this->db->select("TOTAL_HK(tglpengaduan, tl_date) as waktu_layanan, tglpengaduan");

		$this->db->select("desk_categories.name as jenis_komoditi");
		$this->db->select("desk_profesi.name as pekerjaan");
		$this->db->select("desk_users.name as verificator_name");
		$this->db->from('desk_tickets');
		$this->db->join('desk_categories', 'desk_tickets.kategori = desk_categories.id', 'left');
		$this->db->join('desk_profesi', 'desk_tickets.iden_profesi = desk_profesi.id', 'left');
		$this->db->join('desk_users', 'desk_tickets.verified_by = desk_users.id', 'left');

		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if (!empty($filters['direktorat']) && $filters['direktorat'] != 'ALL') {
			$this->db->group_start();
			$this->db->where('direktorat', $filters['direktorat']);
			$this->db->or_where('direktorat2', $filters['direktorat']);
			$this->db->or_where('direktorat3', $filters['direktorat']);
			$this->db->or_where('direktorat4', $filters['direktorat']);
			$this->db->or_where('direktorat5', $filters['direktorat']);
			$this->db->group_end();
		}



		if ($this->session->city == 'PUSAT') {
			if (!empty($filters['kota'])) {
				$this->apply_filter($this->db, $filters['kota']);
			}
		} else if ($this->session->city == 'UNIT TEKNIS') {
			//$this->db->where('owner_dir', $this->session->direktoratid);
		} else {
			//$this->db->where('kota', $this->session->city);
		}

		if (!empty($filters['keyword'])) {
			$field = $filters['field'];
			if ($field == 'trackid') {
				$this->db->where('desk_tickets.trackid', $filters['keyword']);
			} elseif ($field == 'cust_nama') {
				$this->db->like('desk_tickets.iden_nama', $filters['keyword']);
			} elseif ($field == 'cust_email') {
				$this->db->like('desk_tickets.iden_email', $filters['keyword']);
			} elseif ($field == 'cust_telp') {
				$this->db->like('desk_tickets.iden_telp', $filters['keyword']);
			} elseif ($field == 'isu_topik') {
				$this->db->like('desk_tickets.isu_topik', $filters['keyword']);
			} elseif ($field == 'isi_layanan') {
				$this->db->like('desk_tickets.prod_masalah', $filters['keyword']);
			} elseif ($field == 'jawaban') {
				$this->db->like('desk_tickets.jawaban', $filters['keyword']);
			} elseif ($field == 'penerima') {
				$this->db->like('desk_tickets.penerima', $filters['keyword']);
			} elseif ($field == 'klasifikasi') {
				$this->db->like('desk_tickets.klasifikasi', $filters['keyword']);
			} elseif ($field == 'subklasifikasi') {
				$this->db->like('desk_tickets.subklasifikasi', $filters['keyword']);
			}
		}

		if (!empty($filters['iden_profesi'])) {
			$this->db->where('iden_profesi', $filters['iden_profesi']);
		}

		if (!empty($filters['jenis'])) {
			if ($filters['jenis'] == 'LAYANAN') {
				$this->db->where('jenis', '');
			} else
				$this->db->where('jenis', $filters['jenis']);
		}

		if (!empty($filters['kategori'])) {
			$this->db->where('kategori', $filters['kategori']);
		}

		if (!empty($filters['submited_via'])) {
			$this->db->where('submited_via', $filters['submited_via']);
		}

		if (!empty($filters['status'])) {
			$status_ = explode(',', $filters['status']);
			$statuses = array();
			foreach ($status_ as $v) {
				$statuses[] = "'" . $v . "'";
			}
			$this->db->where_in('status', $statuses, FALSE);
		}
		/*if(!empty($filters['is_rujuk']))
		{
			$is_rujuks = array();
			foreach($filters['is_rujuk'] as $v)
			{
				$is_rujuks[] = "'".$v."'";
			}
			$this->db->where_in('is_rujuk', $is_rujuks, FALSE);
		}*/
		if (!empty($filters['tl'])) {
			$tl_ = explode(',', $filters['tl']);
			$tls = array();
			foreach ($tl_ as $v) {
				$tls[] = $v;
			}
			$this->db->where_in('tl', $tls);
		}
		if (!empty($filters['fb'])) {
			$fb_ = explode(',', $filters['fb']);
			$fbs = array();
			foreach ($fb_ as $v) {
				$fbs[] = $v;
			}
			$this->db->where_in('fb', $fbs);
		}
		if (!empty($filters['sla'])) {
			$this->db->where_in('sla', $filters['sla']);
			$sla_ = explode(',', $filters['sla']);
			$slas = array();
			foreach ($sla_ as $v) {
				$slas[] = $v;
			}
			$this->db->where_in('sla', $slas);
		}
		/*if(!empty($filters['is_verified']))
		{
			$this->db->where_in('is_verified',$filters['is_verified']);
		}*/

		$this->db->where_in('info', array('P', 'I'));

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			//$this->db->or_like('custom2', $search);
			$this->db->group_end();
		}

		//echo $this->db->get_compiled_select();
		//exit;

		//$this->apply_filter($this->db, $inputKota);

		$query =  $this->db->get();
		return $query->result_array();
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
}
