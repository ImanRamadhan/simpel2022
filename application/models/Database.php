<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Database class
 * 
 */

class Database extends CI_Model
{
	/**
	 * Determines whether the employee specified employee has access the specific module.
	 */
	public function has_grant($permission_id, $user_id)
	{
		//if no module_id is null, allow access
		if ($permission_id == NULL) {
			return TRUE;
		}

		$query = $this->db->get_where('grants', array('user_id' => $user_id, 'permission_id' => $permission_id), 1);

		return ($query->num_rows() == 1);
	}

	/*
	Determines if a given item_id is an item
	*/
	public function exists($item_id, $ignore_deleted = FALSE, $deleted = FALSE) {}



	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('desk_tickets');
		//$this->db->where('deleted', 0);

		return $this->db->count_all_results();
	}



	/*
	Get number of rows
	*/
	public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	/*
	Database
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.id, trackid, iden_nama, iden_alamat, iden_telp, iden_email, iden_jk');
			$this->db->select('lastchange, status, prod_masalah as problem, info, jawaban, keterangan, penerima ');
			$this->db->select('isu_topik, klasifikasi, subklasifikasi, kota, waktu, shift');
			$this->db->select('submited_via as sarana, petugas_entry, tl, tl_date, verified_date');
			$this->db->select('is_verified, sla, closed_date, fb, fb_date, jenis');
			//$this->db->select('status, is_rujuk, tl, fb, is_verified, hk, is_sent, lastchange, prod_masalah as problem');
			/*$this->db->select("CASE WHEN is_rujuk = '1' AND status = '3' THEN hk WHEN is_rujuk = '1' AND status <> '3' THEN  5 * ((DATEDIFF( NOW() , dt) ) DIV 7) + MID('0123455501234445012333450122234501101234000123450', 7 * WEEKDAY(dt) + WEEKDAY(NOW()) + 1, 1) - (select count(tgl) from desk_holiday where tgl between dt AND NOW()) ELSE 0 END as outstanding");*/
			$this->db->select('TOTAL_HK(tglpengaduan, tl_date) as hk');
			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
			$this->db->select("date_format(closed_date,'%d/%m/%Y') as closed_date");
			$this->db->select("date_format(tl_date,'%d/%m/%Y') as tl_date");
			$this->db->select("date_format(fb_date,'%d/%m/%Y') as fb_date");
			$this->db->select("date_format(verified_date,'%d/%m/%Y') as verified_date");
			$this->db->select('desk_categories.name as jenis_komoditi');
			$this->db->select('desk_profesi.name as pekerjaan');
			$this->db->select('desk_users.name as verificator_name');
		}

		$this->db->from('desk_tickets');
		$this->db->join('desk_categories', 'desk_categories.id=desk_tickets.kategori', 'left');
		$this->db->join('desk_profesi', 'desk_profesi.id=desk_tickets.iden_profesi', 'left');
		$this->db->join('desk_users', 'desk_users.id=desk_tickets.verified_by', 'left');

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

		if (!empty($filters['direktorat']) && ($filters['direktorat'] != 'ALL') && ($filters['direktorat'] != '')) {
			$this->db->or_where('direktorat', $filters['direktorat']);
			$this->db->or_where('direktorat2', $filters['direktorat']);
			$this->db->or_where('direktorat3', $filters['direktorat']);
			$this->db->or_where('direktorat4', $filters['direktorat']);
			$this->db->or_where('direktorat5', $filters['direktorat']);
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

		$this->db->where_in('info', array('P', 'I'));

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			//$this->db->or_like('custom2', $search);
			$this->db->group_end();
		}

		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}

	/*
	Get number of rows SLA
	*/
	public function get_sla_found_rows($search, $filters)
	{
		return $this->search_sla($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	/*
	Database SLA
	*/
	public function search_sla($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.id, desk_tickets.iden_jk, trackid, iden_nama, iden_alamat, iden_telp, iden_email');
			$this->db->select('lastchange, status, prod_masalah as problem, info, jawaban, keterangan, penerima ');
			$this->db->select('isu_topik, klasifikasi, subklasifikasi, kota, waktu, shift');
			$this->db->select('submited_via as sarana, petugas_entry, tl, tl_date, verified_date');
			$this->db->select('is_verified, sla, closed_date, fb, fb_date, jenis');
			//$this->db->select('status, is_rujuk, tl, fb, is_verified, hk, is_sent, lastchange, prod_masalah as problem');
			/*$this->db->select("CASE WHEN is_rujuk = '1' AND status = '3' THEN hk WHEN is_rujuk = '1' AND status <> '3' THEN  5 * ((DATEDIFF( NOW() , dt) ) DIV 7) + MID('0123455501234445012333450122234501101234000123450', 7 * WEEKDAY(dt) + WEEKDAY(NOW()) + 1, 1) - (select count(tgl) from desk_holiday where tgl between dt AND NOW()) ELSE 0 END as outstanding");*/
			$this->db->select('TOTAL_HK(tglpengaduan, tl_date) as hk');
			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
			$this->db->select("date_format(closed_date,'%d/%m/%Y') as closed_date");
			$this->db->select("date_format(tl_date,'%d/%m/%Y') as tl_date");
			$this->db->select("date_format(fb_date,'%d/%m/%Y') as fb_date");
			$this->db->select("date_format(verified_date,'%d/%m/%Y') as verified_date");
			$this->db->select('desk_categories.name as jenis_komoditi');
			$this->db->select('desk_profesi.name as pekerjaan');
			$this->db->select('desk_users.name as verificator_name');
		}

		$this->db->from('desk_tickets');
		$this->db->join('desk_categories', 'desk_categories.id=desk_tickets.kategori', 'left');
		$this->db->join('desk_profesi', 'desk_profesi.id=desk_tickets.iden_profesi', 'left');
		$this->db->join('desk_users', 'desk_users.id=desk_tickets.verified_by', 'left');

		$this->db->where('closed_date IS NOT NULL');
		$this->db->where('tl', 1);
		$this->db->where('tl_date IS NOT NULL');

		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if ($this->session->city == 'PUSAT') {
			if (!empty($filters['kota'])) {
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

		if (!empty($filters['direktorat']) && $filters['direktorat'] != 'ALL') {

			$this->db->group_start();
			$this->db->where('desk_tickets.direktorat', $filters['direktorat']);
			$this->db->or_where('desk_tickets.direktorat2', $filters['direktorat']);
			$this->db->or_where('desk_tickets.direktorat3', $filters['direktorat']);
			$this->db->or_where('desk_tickets.direktorat4', $filters['direktorat']);
			$this->db->or_where('desk_tickets.direktorat5', $filters['direktorat']);
			$this->db->group_end();
		}

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			//$this->db->or_like('custom2', $search);
			$this->db->group_end();
		}




		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		//$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}
		// echo $this->db->get_compiled_select();
		// exit;
		return $this->db->get();
	}

	public function get_rujukan_found_rows($search, $filters)
	{
		return $this->search_rujukan($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	/* Database Rujukan */
	public function search_rujukan($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.id, trackid, iden_nama, iden_alamat, iden_telp, iden_email, iden_instansi, iden_jk');
			$this->db->select('lastchange, status, prod_masalah as problem, info, jawaban, keterangan, penerima, jenis ');
			$this->db->select('isu_topik, klasifikasi, subklasifikasi, desk_tickets.kota, waktu, shift');
			$this->db->select('submited_via as sarana, petugas_entry, tl');
			$this->db->select('is_verified, sla, fb, fb_isi, jenis');
			$this->db->select('direktorat, direktorat2, direktorat3, direktorat4, direktorat5');
			$this->db->select('d1_prioritas as sla1, d2_prioritas as sla2, d3_prioritas as sla3, d4_prioritas as sla4, d5_prioritas as sla5');
			//$this->db->select('sla1, sla2, sla3, sla4, sla5');
			$this->db->select('TOTAL_HK(tglpengaduan, tl_date) as hk');
			//$this->db->select('dir_name1, dir_name2, dir_name3, dir_name4, dir_name5');
			//$this->db->select('status1, status2, status3, status4, status5');

			/*$this->db->select("CASE WHEN is_rujuk = '1' AND status = '3' THEN hk WHEN is_rujuk = '1' AND status <> '3' THEN  5 * ((DATEDIFF( NOW() , dt) ) DIV 7) + MID('0123455501234445012333450122234501101234000123450', 7 * WEEKDAY(dt) + WEEKDAY(NOW()) + 1, 1) - (select count(tgl) from desk_holiday where tgl between dt AND NOW()) ELSE 0 END as outstanding");*/
			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
			$this->db->select("date_format(closed_date,'%d/%m/%Y') as closed_date");
			$this->db->select("date_format(tl_date,'%d/%m/%Y') as tl_date");
			$this->db->select("date_format(fb_date,'%d/%m/%Y') as fb_date");
			$this->db->select("date_format(verified_date,'%d/%m/%Y') as verified_date");
			$this->db->select('desk_categories.desc as jenis_komoditi');
			$this->db->select('desk_profesi.name as pekerjaan');
			$this->db->select('desk_users.name as verificator_name');

			$this->db->select("IFNULL(status_rujuk1,'-') as status_rujuk1");
			$this->db->select("IFNULL(status_rujuk2,'-') as status_rujuk2");
			$this->db->select("IFNULL(status_rujuk3,'-') as status_rujuk3");
			$this->db->select("IFNULL(status_rujuk4,'-') as status_rujuk4");
			$this->db->select("IFNULL(status_rujuk5,'-') as status_rujuk5");
			//$this->db->select('status_rujuk1, status_rujuk2, status_rujuk3, status_rujuk4, status_rujuk5');
		}

		$this->db->from('desk_tickets');
		$this->db->join('desk_categories', 'desk_categories.id=desk_tickets.kategori', 'left');
		$this->db->join('desk_profesi', 'desk_profesi.id=desk_tickets.iden_profesi', 'left');


		$this->db->join('desk_users', 'desk_users.id=desk_tickets.verified_by', 'left');
		$this->db->join('desk_rujukan', 'desk_tickets.id=desk_rujukan.rid', 'left');


		$this->db->where('is_rujuk', "'1'", FALSE);

		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if ($this->session->city == 'PUSAT') {
			if (!empty($filters['kota'])) {
				$this->apply_filter($this->db, $filters['kota']);
			}
		} else {
			$this->db->where('desk_tickets.kota', $this->session->city);
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
			//$this->db->where('jenis', $filters['jenis']);
			if ($filters['jenis'] == 'LAYANAN')
				$this->db->where('jenis', '');
			elseif ($filters['jenis'] == 'LAYANAN_SP4N')
				$this->db->where_in('jenis', array('', 'SP4N'));
			else
				$this->db->where('jenis', $filters['jenis']);
		}
		if ($filters['status_rujukan'] != 'ALL') {
			$status_rujukan = 0;
			if ($filters['status_rujukan'] == 'Y')
				$status_rujukan = 1;
			elseif ($filters['status_rujukan'] == 'N')
				$status_rujukan = 0;

			$this->db->where('tl', $status_rujukan);
			//$this->db->where('tl', $filters['status_rujukan']);
		}
		if (!empty($filters['unit_rujukan']) && $filters['unit_rujukan'] != 'ALL') {

			$this->db->group_start();
			$this->db->where('desk_tickets.direktorat', $filters['unit_rujukan']);
			$this->db->or_where('desk_tickets.direktorat2', $filters['unit_rujukan']);
			$this->db->or_where('desk_tickets.direktorat3', $filters['unit_rujukan']);
			$this->db->or_where('desk_tickets.direktorat4', $filters['unit_rujukan']);
			$this->db->or_where('desk_tickets.direktorat5', $filters['unit_rujukan']);
			$this->db->group_end();
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

		//$this->db->where_in('info', array('P','I'));

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			//$this->db->or_like('custom2', $search);
			$this->db->group_end();
		}




		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		//$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}



		return $this->db->get();
	}

	public function get_yanblik_found_rows($search, $filters)
	{
		return $this->search_yanblik($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	/* Database Yanblik */
	public function search_yanblik($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.id, trackid, iden_nama, iden_alamat, iden_telp, iden_email, iden_instansi');
			$this->db->select('lastchange, status, prod_masalah as problem, info, jawaban, keterangan, penerima ');
			$this->db->select('isu_topik, klasifikasi, subklasifikasi, desk_tickets.kota, waktu, shift');
			$this->db->select('submited_via as sarana, petugas_entry, tl, tl_date, verified_date');
			$this->db->select('is_verified, sla, hk, closed_date, fb, fb_date, jenis');
			$this->db->select('direktorat, direktorat2, direktorat3, direktorat4, direktorat5');
			//$this->db->select('sla1, sla2, sla3, sla4, sla5');
			//$this->db->select('dir_name1, dir_name2, dir_name3, dir_name4, dir_name5');
			//$this->db->select('status1, status2, status3, status4, status5');
			//$this->db->select('status, is_rujuk, tl, fb, is_verified, hk, is_sent, lastchange, prod_masalah as problem');
			/*$this->db->select("CASE WHEN is_rujuk = '1' AND status = '3' THEN hk WHEN is_rujuk = '1' AND status <> '3' THEN  5 * ((DATEDIFF( NOW() , dt) ) DIV 7) + MID('0123455501234445012333450122234501101234000123450', 7 * WEEKDAY(dt) + WEEKDAY(NOW()) + 1, 1) - (select count(tgl) from desk_holiday where tgl between dt AND NOW()) ELSE 0 END as outstanding");*/
			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
			$this->db->select('desk_categories.desc as jenis_komoditi');
			$this->db->select('desk_profesi.name as pekerjaan');
			//$this->db->select('desk_users.name as verificator_name');

		}

		$this->db->from('desk_tickets');
		$this->db->join('desk_categories', 'desk_categories.id=desk_tickets.kategori', 'left');
		$this->db->join('desk_profesi', 'desk_profesi.id=desk_tickets.iden_profesi', 'left');


		//$yanblik = array('Proses pendaftaran','Sertifikasi','Desain kemasan','Logo','Petugas Yanblik');
		$yanblik = array('Proses pendaftaran', 'Sertifikasi', 'Petugas Yanblik');
		$this->db->where_in('subklasifikasi', $yanblik);

		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if ($this->session->city == 'PUSAT') {
			if (!empty($filters['kota'])) {
				$this->apply_filter($this->db, $filters['kota']);
			}
		} else {
			$this->db->where('desk_tickets.kota', $this->session->city);
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
			$this->db->where('jenis', $filters['jenis']);
		}
		if (!empty($filters['status_rujukan']) && $filters['status_rujukan'] != 'ALL') {
			$this->db->where('tl', $filters['status_rujukan']);
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

		//$this->db->where_in('info', array('P','I'));

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			//$this->db->or_like('custom2', $search);
			$this->db->group_end();
		}




		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		//$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}



		return $this->db->get();
	}

	public function apply_filter(&$db, $kota)
	{
		$db->where_in('info', array('P', 'I'));

		switch ($kota) {
			case 'ALL':
			case 'PUSAT_CC_UNIT_TEKNIS_BALAI':
				break;
			case 'CC':
				$db->where('desk_tickets.kota', 'PUSAT');
				$db->where('ws >', 0);
				break;
			case 'BALAI':
				$db->where('desk_tickets.kota <> ', 'PUSAT');
				$db->where('ws', 0);
				break;
			case 'PUSAT_CC':
				$db->where('desk_tickets.kota', 'PUSAT');
				break;
			case 'PUSAT_BALAI':
				$db->where('ws', 0);
				break;
			case 'PUSAT_UNIT_TEKNIS':
				$db->group_start();
				$db->where('desk_tickets.kota', 'PUSAT');
				$db->where('ws', 0);
				$db->group_end();
				$db->or_where('desk_tickets.kota', 'UNIT TEKNIS');
				break;
			case 'PUSAT_CC_UNIT_TEKNIS':
				$db->where('desk_tickets.kota', 'PUSAT');
				$db->or_where('desk_tickets.kota', 'UNIT TEKNIS');
				break;
			case 'PUSAT_CC_BALAI':
				$db->where('desk_tickets.kota <> ', 'UNIT TEKNIS');
				break;
			case 'PUSAT_CC_UNIT_TEKNIS_BALAI':
				break;
			case 'PUSAT':
				$db->where('desk_tickets.kota', 'PUSAT');
				$db->where('ws', 0);
				break;
			default:
				$db->where('desk_tickets.kota', $kota);
				$db->where('ws', 0);
		}
	}
}
