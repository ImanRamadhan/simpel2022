<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Ticket class
 * 
 */

class Ticket extends CI_Model
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
	public function exists($item_id, $ignore_deleted = FALSE, $deleted = FALSE)
	{
		$this->db->from('desk_tickets');
		$this->db->where('id', (int) $item_id);
		if ($ignore_deleted == FALSE) {
			$this->db->where('deleted', $deleted);
		}

		return ($this->db->get()->num_rows() == 1);
	}



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
	Perform a search on items
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.id, trackid, status, is_rujuk, tl, fb, is_verified, hk, iden_nama, is_sent, lastchange, prod_masalah as problem, jenis, category');
			//$this->db->select("CASE WHEN is_rujuk = '1' AND status = '3' THEN hk WHEN is_rujuk = '1' AND status <> '3' THEN  5 * ((DATEDIFF( NOW() , dt) ) DIV 7) + MID('0123455501234445012333450122234501101234000123450', 7 * WEEKDAY(dt) + WEEKDAY(NOW()) + 1, 1) - (select count(tgl) from desk_holiday where tgl between dt AND NOW()) ELSE 0 END as outstanding");
			$this->db->select("CASE WHEN is_rujuk = '1' AND tl=1 THEN TOTAL_HK(tglpengaduan, tl_date) WHEN is_rujuk='1' AND tl=0 THEN  TOTAL_HK(tglpengaduan, DATE(NOW())) ELSE 0 END as outstanding");
			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		}

		$this->db->from('desk_tickets');


		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if ($this->session->city == 'PUSAT') {
			if (!empty($filters['kota'])) {
				$this->apply_filter($this->db, $filters['kota']);
			}
		} else if ($this->session->city == 'UNIT TEKNIS') {
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
			} elseif ($field == 'klasifikasi') {
				$this->db->like('desk_tickets.klasifikasi', $filters['keyword']);
			} elseif ($field == 'subklasifikasi') {
				$this->db->like('desk_tickets.subklasifikasi', $filters['keyword']);
			} elseif ($field == 'perusahaan_instansi') {
				$this->db->like('desk_tickets.iden_instansi', $filters['keyword']);
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
		if (!empty($filters['tl'])) {
			$this->db->where_in('tl', $filters['tl']);
		}
		if (!empty($filters['fb'])) {
			$this->db->where_in('fb', $filters['fb']);
		}
		if (!empty($filters['sla'])) {
			$this->db->where_in('sla', $filters['sla']);
		}
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

		//echo $this->db->get_compiled_select();
		//exit;


		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}



		return $this->db->get();
	}




	/*
	PPID
	*/
	public function get_ppid_found_rows($search, $filters)
	{
		return $this->search_ppid($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	public function search_ppid($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.id, trackid, iden_nama, iden_email, iden_telp, iden_profesi, lastchange, isu_topik');
			$this->db->select('desk_profesi.name as profesi');
			$this->db->select('desk_ppid.rincian, desk_ppid.tujuan, desk_ppid.alasan_keberatan, desk_ppid.kuasa_nama, desk_ppid.kasus_posisi');
			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		}

		$this->db->from('desk_tickets');
		$this->db->join('desk_profesi', 'desk_tickets.iden_profesi = desk_profesi.id', 'left');
		$this->db->join('desk_ppid', 'desk_tickets.id = desk_ppid.id', 'left');

		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}
		if (!empty($filters['is_lengkap'])) {
			$is_lengkap = str_replace("_", " ", $filters['is_lengkap']);
			$this->db->where('is_lengkap =', $is_lengkap);
		}

		if ($this->session->city == 'PUSAT') {
			if (!empty($filters['kota'])) {
				$this->apply_filter($this->db, $filters['kota']);
			}
		} else if ($this->session->city == 'UNIT TEKNIS') {
			$this->db->where('owner_dir', $this->session->direktoratid);
		} else {
			$this->db->where('kota', $this->session->city);
		}

		if (!empty($filters['kota'])) {
			$this->apply_filter($this->db, $filters['kota']);
		}

		if (!empty($filters['type'])) {
			if ($filters['type'] == 'P') //PPID
			{
				$this->db->group_start();
				$this->db->where('desk_ppid.alasan_keberatan', NULL);
				$this->db->or_where('desk_ppid.alasan_keberatan', '');
				$this->db->group_end();
			} elseif ($filters['type'] == 'K') //Keberatan
			{
				$this->db->where('desk_ppid.alasan_keberatan <>', '');
			}
		}

		$this->db->where_in('info', array('P', 'I'));
		//PPID
		$this->db->where('jenis', 'PPID');

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			$this->db->group_end();
		}



		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}
		// $this->db->get();
		// print_r($this->db->last_query());
		// die;
		//echo $this->db->get_compiled_select();
		//exit;

		return $this->db->get();
	}


	/*
	Layanan Saya
	*/
	public function get_mytickets_found_rows($search, $filters)
	{
		return $this->search_mytickets($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	public function search_mytickets($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.id, trackid, lastchange, iden_nama, isu_topik, status, is_rujuk, tl, fb, is_verified, jenis, category');

			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		}

		$this->db->from('desk_tickets');


		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if (!empty($filters['kota'])) {
			$this->apply_filter($this->db, $filters['kota']);
		}

		if (!empty($filters['status'])) {
			$statuses = array();
			foreach ($filters['status'] as $v) {
				$statuses[] = "'" . $v . "'";
			}
			$this->db->where_in('status', $statuses, FALSE);
		}
		if (!empty($filters['tl'])) {
			$this->db->where_in('tl', $filters['tl']);
		}
		if (!empty($filters['fb'])) {
			$this->db->where_in('fb', $filters['fb']);
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

		$this->db->where_in('info', array('P', 'I'));
		$this->db->where('owner', $this->session->id);

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			$this->db->group_end();
		}


		//echo $this->db->get_compiled_select();
		//exit;


		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}



		return $this->db->get();
	}

	/*
	Layanan Yanblik
	*/
	public function get_yanblik_tickets_found_rows($search, $filters)
	{
		return $this->search_yanblik_tickets($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	public function search_yanblik_tickets($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.id, trackid, lastchange, iden_nama, isu_topik, status, is_rujuk, tl, fb, is_verified, hk');

			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		}

		$this->db->from('desk_tickets');

		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if (!empty($filters['kota'])) {
			$this->apply_filter($this->db, $filters['kota']);
		}

		/*if(!empty($filters['yanblik']))
		{
			//$this->db->where_in('subklasifikasi', $filters['yanblik']);
			
		}*/
		$this->db->where('klasifikasi', 'Layanan Publik');

		$this->db->where_in('info', array('P', 'I'));


		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			$this->db->group_end();
		}



		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}



		return $this->db->get();
	}

	/*
	Layanan Verifikasi saya
	*/
	public function get_verifikasi_tickets_found_rows($search, $filters)
	{
		return $this->search_verifikasi_tickets($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	public function search_verifikasi_tickets($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.id, trackid, lastchange, iden_nama, isu_topik, status, is_rujuk, tl, fb, is_verified, hk');
			$this->db->select("CASE WHEN is_rujuk = '1' AND status = '3' THEN hk WHEN is_rujuk = '1' AND status <> '3' THEN  5 * ((DATEDIFF( NOW() , dt) ) DIV 7) + MID('0123455501234445012333450122234501101234000123450', 7 * WEEKDAY(dt) + WEEKDAY(NOW()) + 1, 1) - (select count(tgl) from desk_holiday where tgl between dt AND NOW()) ELSE 0 END as outstanding");
			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		}

		$this->db->from('desk_tickets');

		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		/*if(!empty($filters['kota']))
		{
			$this->apply_filter($this->db, $filters['kota']);
		}*/
		$this->db->where('verified_by', $this->session->id);



		$this->db->where_in('info', array('P', 'I'));


		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			$this->db->group_end();
		}



		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}



		return $this->db->get();
	}

	/*
	Layanan SLA saya
	*/
	public function get_sla_tickets_found_rows($search, $filters)
	{
		return $this->search_sla_tickets($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	public function search_sla_tickets($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.id, trackid, lastchange, iden_nama, isu_topik, status, is_rujuk, tl, fb, is_verified, hk');
			$this->db->select('prod_masalah as problem, 0 as outstanding');
			//$this->db->select("CASE WHEN is_rujuk = '1' AND status = '3' THEN hk WHEN is_rujuk = '1' AND status <> '3' THEN  5 * ((DATEDIFF( NOW() , dt) ) DIV 7) + MID('0123455501234445012333450122234501101234000123450', 7 * WEEKDAY(dt) + WEEKDAY(NOW()) + 1, 1) - (select count(tgl) from desk_holiday where tgl between dt AND NOW()) ELSE 0 END as outstanding");
			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		}

		$this->db->from('desk_tickets');

		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if (!empty($filters['kota'])) {
			$this->apply_filter($this->db, $filters['kota']);
		}

		if (!empty($filters['tl'])) {
			if ($filters['tl'] == 'Y')
				$this->db->where('tl', 1);
			elseif ($filters['tl'] == 'N')
				$this->db->where('tl', 0);
		}

		if (!empty($filters['sla'])) {


			if ($filters['sla'] == 'green') {
				$this->db->where('(TOTAL_HK(tglpengaduan, DATE(NOW())) - sla) > 14 AND (TOTAL_HK(tglpengaduan, DATE(NOW())) - sla) <= 60');
			} elseif ($filters['sla'] == 'orange') {
				$this->db->where('(TOTAL_HK(tglpengaduan, DATE(NOW())) - sla) > 5 AND (TOTAL_HK(tglpengaduan, DATE(NOW())) - sla) <= 14');
			} elseif ($filters['sla'] == 'red') {
				$this->db->where('(TOTAL_HK(tglpengaduan, DATE(NOW())) - sla) >= 0 AND (TOTAL_HK(tglpengaduan, DATE(NOW())) - sla) <= 5');
			} elseif ($filters['sla'] == 'black') {
				$this->db->where('TOTAL_HK(tglpengaduan, DATE(NOW())) > sla');
			}
		}




		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			$this->db->group_end();
		}

		//echo $this->db->get_compiled_select();
		//exit;

		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}



		return $this->db->get();
	}

	public function get_sla_tickets_perform_found_rows($search, $filters)
	{
		return $this->search_sla_tickets_perform($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	public function search_sla_tickets_perform($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {
			$this->db->select('desk_tickets.id, trackid, lastchange, iden_nama, isu_topik, status, is_rujuk, tl, fb, is_verified, hk');
			$this->db->select('prod_masalah as problem, 0 as outstanding');
			//$this->db->select("CASE WHEN is_rujuk = '1' AND status = '3' THEN hk WHEN is_rujuk = '1' AND status <> '3' THEN  5 * ((DATEDIFF( NOW() , dt) ) DIV 7) + MID('0123455501234445012333450122234501101234000123450', 7 * WEEKDAY(dt) + WEEKDAY(NOW()) + 1, 1) - (select count(tgl) from desk_holiday where tgl between dt AND NOW()) ELSE 0 END as outstanding");
			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		}

		$this->db->from('desk_tickets');

		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if (!empty($filters['kota'])) {
			$this->apply_filter($this->db, $filters['kota']);
		}

		$this->db->where('tl', 1);
		$this->db->where('closed_date IS NOT NULL');

		if (!empty($filters['sla'])) {
			if ($filters['sla'] == 'meet') {
				$this->db->where('DATEDIFF(date(tl_date), date(tglpengaduan)) <= sla');
			} elseif ($filters['sla'] == 'notmeet') {
				$this->db->where('DATEDIFF(date(tl_date), date(tglpengaduan)) > sla');
			}
		}

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			$this->db->group_end();
		}

		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}

	/*
	Rujukan Masuk
	*/
	public function get_rujukan_masuk_found_rows($search, $filters)
	{
		return $this->search_rujukan_masuk($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	public function search_rujukan_masuk($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.*, desk_rujukan.*');

			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		}

		$this->db->from('desk_tickets');
		$this->db->join('desk_rujukan', 'desk_rujukan.rid = desk_tickets.id', 'LEFT');


		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if (!empty($filters['status'])) {
			foreach ($filters['status'] as $v) {
				if ($v == '1') {
					$this->db->where('replierid is not null');
				} else {
					$this->db->where('replierid is null');
				}
			}
		}


		$this->db->where_in('info', array('P', 'I'));

		$this->db->group_start();
		$this->db->where('direktorat', $this->session->direktoratid);
		$this->db->or_where('direktorat2', $this->session->direktoratid);
		$this->db->or_where('direktorat3', $this->session->direktoratid);
		$this->db->or_where('direktorat4', $this->session->direktoratid);
		$this->db->or_where('direktorat5', $this->session->direktoratid);
		$this->db->group_end();

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			$this->db->group_end();
		}



		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}



		return $this->db->get();
	}

	/*
	Rujukan Keluar
	*/
	public function get_rujukan_keluar_found_rows($search, $filters)
	{
		return $this->search_rujukan_keluar($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	public function search_rujukan_keluar($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.*');

			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		}

		$this->db->from('desk_tickets');


		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if (!empty($filters['status'])) {
			foreach ($filters['status'] as $v) {
				if ($v == '1') {
					$this->db->where('replierid is not null');
				} else {
					$this->db->where('replierid is null');
				}
			}
		}

		//$this->db->where_in('info', array('P','I'));

		/*$this->db->group_start();
			$this->db->where('direktorat > ', 0);
			$this->db->or_where('direktorat2 >', 0);
			$this->db->or_where('direktorat3 >', 0);
			$this->db->or_where('direktorat4 >', 0);
			$this->db->or_where('direktorat5 >', 0);
		$this->db->group_end();*/

		/*$this->db->where('direktorat != ', $this->session->direktoratid);
		$this->db->where('direktorat2 != ', $this->session->direktoratid);
		$this->db->where('direktorat3 != ', $this->session->direktoratid);
		$this->db->where('direktorat4 != ', $this->session->direktoratid);
		$this->db->where('direktorat5 != ', $this->session->direktoratid);*/

		$this->db->where('is_rujuk', '1');
		$this->db->where('owner_dir', $this->session->direktoratid);
		//$this->db->where('kota', $this->session->city);

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			$this->db->group_end();
		}



		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}

		//echo $this->db->get_compiled_select();
		//exit;


		return $this->db->get();
	}


	/*
	Rujukan Keluar Saya
	*/
	public function get_rujukan_keluar_saya_found_rows($search, $filters)
	{
		return $this->search_rujukan_keluar_saya($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	public function search_rujukan_keluar_saya($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.*');

			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		}

		$this->db->from('desk_tickets');


		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		//$this->db->where_in('info', array('P','I'));

		/*$this->db->group_start();
			$this->db->where('direktorat > ', 1);
			$this->db->or_where('direktorat2 > ', 1);
			$this->db->or_where('direktorat3 >', 1);
			$this->db->or_where('direktorat4 >', 1);
			$this->db->or_where('direktorat5 >', 1);
		$this->db->group_end();*/
		$this->db->where('is_rujuk', '1');

		$this->db->where('kota', $this->session->city);
		$this->db->where('owner', $this->session->id);

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			$this->db->group_end();
		}



		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}



		return $this->db->get();
	}

	/*
	Rujukan Status Closed
	*/
	public function get_rujukan_status_closed_found_rows($search, $filters)
	{
		return $this->search_rujukan_status_closed($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	public function search_rujukan_status_closed($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.*');

			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		}

		$this->db->from('desk_tickets');


		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		//$this->db->where_in('info', array('P','I'));

		if ($this->session->city == 'PUSAT') {

			/*$this->db->group_start();
				$this->db->where('direktorat > ', 0);
				$this->db->or_where('direktorat2 > ', 0);
				$this->db->or_where('direktorat3 >', 0);
				$this->db->or_where('direktorat4 >', 0);
				$this->db->or_where('direktorat5 >', 0);
			$this->db->group_end();*/
			$this->db->where('is_rujuk', '1');

			$this->db->where('kota', $this->session->city);
			$this->db->where('status', '3');
		} else //Balai
		{
			$this->db->group_start();
			$this->db->where('direktorat', $this->session->direktoratid);
			$this->db->or_where('direktorat2', $this->session->direktoratid);
			$this->db->or_where('direktorat3', $this->session->direktoratid);
			$this->db->or_where('direktorat4', $this->session->direktoratid);
			$this->db->or_where('direktorat5', $this->session->direktoratid);
			$this->db->group_end();
			$this->db->where('status', '3');
		}

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			$this->db->group_end();
		}



		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}



		return $this->db->get();
	}

	/*
	Rujukan Status TL
	*/
	public function get_rujukan_status_tl_found_rows($search, $filters)
	{
		return $this->search_rujukan_status_tl($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	public function search_rujukan_status_tl($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.*');
			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
			$this->db->select('ifnull(desk_notifikasi_flag.is_read,1) as is_read');
		}

		$this->db->from('desk_tickets');
		$this->db->join("(SELECT * FROM desk_notifikasi_flag WHERE user_id=" . $this->session->id . " AND is_read = 0) desk_notifikasi_flag", 'desk_notifikasi_flag.ticket_id=desk_tickets.id AND desk_notifikasi_flag.user_id = desk_tickets.owner', 'left');


		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if ($this->session->city == 'PUSAT') {

			$this->db->where('info', 'P');

			/*$this->db->group_start();
				$this->db->where('direktorat > ', 0);
				$this->db->or_where('direktorat2 > ', 0);
				$this->db->or_where('direktorat3 >', 0);
				$this->db->or_where('direktorat4 >', 0);
				$this->db->or_where('direktorat5 >', 0);
			$this->db->group_end();*/
			$this->db->where('is_rujuk', '1');

			$this->db->where('kota', $this->session->city);
		} else {
			$this->db->group_start();
			$this->db->where('direktorat', $this->session->direktoratid);
			$this->db->or_where('direktorat2', $this->session->direktoratid);
			$this->db->or_where('direktorat3', $this->session->direktoratid);
			$this->db->or_where('direktorat4', $this->session->direktoratid);
			$this->db->or_where('direktorat5', $this->session->direktoratid);
			$this->db->group_end();
			$this->db->where('info', 'P');
		}

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			$this->db->group_end();
		}



		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}



		return $this->db->get();
	}

	/*
	Rujukan Status FB
	*/
	public function get_rujukan_status_fb_found_rows($search, $filters)
	{
		return $this->search_rujukan_status_fb($search, $filters, 0, 0, 'desk_tickets.id', 'desc', TRUE);
	}

	public function search_rujukan_status_fb($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_tickets.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_tickets.id) as count');
		} else {

			$this->db->select('desk_tickets.*');

			$this->db->select("date_format(tglpengaduan,'%d/%m/%Y') as tglpengaduan");
		}

		$this->db->from('desk_tickets');


		if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}

		if ($this->session->city == 'PUSAT') {
			$this->db->where('info', 'P');

			/*$this->db->group_start();
				$this->db->where('direktorat > ', 0);
				$this->db->or_where('direktorat2 > ', 0);
				$this->db->or_where('direktorat3 >', 0);
				$this->db->or_where('direktorat4 >', 0);
				$this->db->or_where('direktorat5 >', 0);
			$this->db->group_end();*/
			$this->db->where('is_rujuk', '1');

			$this->db->where('kota', $this->session->city);
		} else {
			$this->db->group_start();
			$this->db->where('direktorat', $this->session->direktoratid);
			$this->db->or_where('direktorat2', $this->session->direktoratid);
			$this->db->or_where('direktorat3', $this->session->direktoratid);
			$this->db->or_where('direktorat4', $this->session->direktoratid);
			$this->db->or_where('direktorat5', $this->session->direktoratid);
			$this->db->group_end();
			$this->db->where('info', 'P');
		}

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('desk_tickets.trackid', $search);
			$this->db->group_end();
		}



		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

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
				$db->where('kota', 'PUSAT');
				$db->where('ws >', 0);
				break;
			case 'BALAI':
				$db->where('kota <> ', 'PUSAT');
				$db->where('kota <> ', 'UNIT TEKNIS');
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
				//$db->where('ws', 0);
		}
	}


	/*
	Returns all the items
	*/
	public function get_all($stock_location_id = -1, $rows = 0, $limit_from = 0)
	{
		$this->db->from('desk_tickets');
		//$this->db->where('desk_tickets.deleted', 0);

		// order by name of item
		$this->db->order_by('desk_tickets.id', 'asc');

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}


	/*
	Gets information about a particular item
	*/
	public function get_info($item_id)
	{
		/*$this->db->select('desk_tickets.*');
		$this->db->select('desk_categories.desc as jenis_produk');
		$this->db->select('desk_profesi.name as profesi');
		$this->db->select('a.name as created_by');
		
		$this->db->from('desk_tickets');
		$this->db->join('desk_categories','desk_tickets.kategori=desk_categories.id','left');
		$this->db->join('desk_profesi','desk_tickets.iden_profesi=desk_profesi.id','left');
		$this->db->join('desk_users a','desk_tickets.owner=a.id','left');
		
		$this->db->where('desk_tickets.id', $item_id);
		
		$query = $this->db->get();*/

		$item_id = (int)$item_id;

		$sql = "SELECT a.* ";
		$sql .= ", date_format(a.prod_kadaluarsa,'%d/%m/%Y') as prod_kadaluarsa_fmt";
		$sql .= ", date_format(a.prod_diperoleh_tgl,'%d/%m/%Y') as prod_diperoleh_tgl_fmt";
		$sql .= ", date_format(a.prod_digunakan_tgl,'%d/%m/%Y') as prod_digunakan_tgl_fmt";
		$sql .= ", date_format(a.tglpengaduan,'%d/%m/%Y') as tglpengaduan_fmt";
		$sql .= ", date_format(a.lastchange,'%d/%m/%Y %H:%i:%s') as lastchange_fmt";
		$sql .= ", date_format(a.tl_date,'%d/%m/%Y') as tl_date_fmt";
		$sql .= ", date_format(a.fb_date,'%d/%m/%Y') as fb_date_fmt";
		$sql .= ", date_format(a.closed_date,'%d/%m/%Y') as closed_date_fmt";
		$sql .= ", date_format(a.verified_date,'%d/%m/%Y') as verified_date_fmt";
		// Begin select table direktorat
		for ($i = 1; $i <= 5; $i++) {
			$sql .= ", dd$i.name as rujukan$i";
			$sql .= ", d$i" . "_prioritas as rujukan_hk$i";
		}
		// ehd join table direktorat
		$sql .= ", date_format(a.dt,'%d/%m/%Y') as dt_fmt";
		$sql .= ", b.desc as jenis_produk";
		$sql .= ", c.name as profesi";
		$sql .= ", d.name as created_by";
		$sql .= ", e.name as verified_by";
		$sql .= ", a.is_rujuk";
		$sql .= ", f.name as last_replier";
		$sql .= " FROM (SELECT * FROM desk_tickets WHERE id=$item_id) a";
		// Begin join table direktorat
		for ($i = 1; $i <= 5; $i++) {
			if ($i == 1) {
				$sql .= " LEFT JOIN desk_direktorat dd$i ON dd$i.id = a.direktorat";
			} else {
				$sql .= " LEFT JOIN desk_direktorat dd$i ON dd$i.id = a.direktorat$i";
			}
		}
		// ehd join table direktorat

		$sql .= " LEFT JOIN desk_categories b ON a.kategori = b.id";
		$sql .= " LEFT JOIN desk_profesi c ON a.iden_profesi = c.id";
		$sql .= " LEFT JOIN desk_users d ON a.owner = d.id";
		$sql .= " LEFT JOIN desk_users e ON a.verified_by = e.id";
		$sql .= " LEFT JOIN desk_users f ON a.replierid = f.id";

		$query = $this->db->query($sql);

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach ($this->db->list_fields('desk_tickets') as $field) {
				$item_obj->$field = '';
			}

			$item_obj->created_by = '';

			return $item_obj;
		}
	}

	public function get_info_by_trackid($item_id)
	{

		$sql = "SELECT a.* ";
		$sql .= ", date_format(a.prod_kadaluarsa,'%d/%m/%Y') as prod_kadaluarsa_fmt";
		$sql .= ", date_format(a.prod_diperoleh_tgl,'%d/%m/%Y') as prod_diperoleh_tgl_fmt";
		$sql .= ", date_format(a.prod_digunakan_tgl,'%d/%m/%Y') as prod_digunakan_tgl_fmt";
		$sql .= ", date_format(a.tglpengaduan,'%d/%m/%Y') as tglpengaduan_fmt";
		$sql .= ", date_format(a.lastchange,'%d/%m/%Y %H:%i:%s') as lastchange_fmt";
		$sql .= ", date_format(a.tl_date,'%d/%m/%Y') as tl_date_fmt";
		$sql .= ", date_format(a.fb_date,'%d/%m/%Y') as fb_date_fmt";
		//$sql .= ", date_format(a.dt,'%d/%m/%Y') as dt_fmt";
		$sql .= ", b.desc as jenis_produk";
		$sql .= ", c.name as profesi";
		$sql .= ", d.name as created_by";
		$sql .= ", e.name as verified_by";
		$sql .= ", f.name as last_replier";
		$sql .= " FROM (SELECT * FROM desk_tickets WHERE trackid='$item_id') a";
		$sql .= " LEFT JOIN desk_categories b ON a.kategori = b.id";
		$sql .= " LEFT JOIN desk_profesi c ON a.iden_profesi = c.id";
		$sql .= " LEFT JOIN desk_users d ON a.owner = d.id";
		$sql .= " LEFT JOIN desk_users e ON a.verified_by = e.id";
		$sql .= " LEFT JOIN desk_users f ON a.replierid = f.id";

		$query = $this->db->query($sql);

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach ($this->db->list_fields('desk_tickets') as $field) {
				$item_obj->$field = '';
			}

			$item_obj->created_by = '';

			return $item_obj;
		}
	}

	public function get_ppid_info($item_id)
	{
		$this->db->select('desk_ppid.*');
		$this->db->select("date_format(tgl_diterima,'%d/%m/%Y') as tgl_diterima_fmt,");
		$this->db->select("date_format(tt_tgl,'%d/%m/%Y') as tt_tgl_fmt,");
		$this->db->select("date_format(tgl_tanggapan,'%d/%m/%Y') as tgl_tanggapan_fmt,");
		$this->db->select("date_format(keberatan_tgl,'%d/%m/%Y') as keberatan_tgl_fmt");
		$this->db->select("date_format(tgl_pemberitahuan_tertulis,'%d/%m/%Y') as tgl_pemberitahuan_tertulis_fmt");
		$this->db->from('desk_ppid');
		$this->db->where('desk_ppid.id', $item_id);

		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach ($this->db->list_fields('desk_ppid') as $field) {
				$item_obj->$field = '';
			}

			$item_obj->tgl_diterima_fmt = '';
			$item_obj->tt_tgl_fmt = '';
			$item_obj->tgl_tanggapan_fmt = '';
			$item_obj->keberatan_tgl_fmt = '';
			$item_obj->tgl_pemberitahuan_tertulis_fmt = '';

			return $item_obj;
		}
	}

	public function get_rujukan_info($item_id)
	{
		$this->db->select('desk_rujukan.*');

		$this->db->select("TOTAL_HK(desk_tickets.tglpengaduan,desk_rujukan.tl_date1) as hk_rujukan1");
		$this->db->select("TOTAL_HK(desk_tickets.tglpengaduan,desk_rujukan.tl_date2) as hk_rujukan2");
		$this->db->select("TOTAL_HK(desk_tickets.tglpengaduan,desk_rujukan.tl_date3) as hk_rujukan3");
		$this->db->select("TOTAL_HK(desk_tickets.tglpengaduan,desk_rujukan.tl_date4) as hk_rujukan4");
		$this->db->select("TOTAL_HK(desk_tickets.tglpengaduan,desk_rujukan.tl_date5) as hk_rujukan5");

		//$this->db->select("date_format(tt_tgl,'%d/%m/%Y') as tt_tgl_fmt,");

		$this->db->from('desk_rujukan');
		$this->db->join('desk_tickets', 'desk_tickets.id = desk_rujukan.rid', 'left');
		$this->db->where('desk_rujukan.rid', $item_id);

		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach ($this->db->list_fields('desk_rujukan') as $field) {
				$item_obj->$field = '';
			}

			/*$item_obj->tgl_diterima_fmt = '';
			*/

			return $item_obj;
		}
	}


	/*
	Inserts or updates a ticket
	*/
	public function save(&$item_data, $item_id = FALSE)
	{

		$item_data2 = $item_data;
		//enum data type
		$enum_array = array('id', 'is_rujuk', 'info', 'shift', 'is_sent', 'status');
		foreach ($enum_array as $enum) {
			if (array_key_exists($enum, $item_data2)) {
				$this->db->set($enum, "'" . $item_data2[$enum] . "'", FALSE);
				unset($item_data2[$enum]);
			}
		}

		foreach ($item_data2 as $k => $v) {
			$this->db->set($k, $v);
		}


		if ($item_id == -1 /*|| !$this->exists($item_id, TRUE)*/) {
			if ($this->db->insert('desk_tickets')) {
				$item_data['id'] = $this->db->insert_id();

				return TRUE;
			}

			return FALSE;
		}

		$this->db->where('id', $item_id);

		return $this->db->update('desk_tickets');
	}

	public function save2(&$item_data, $item_id)
	{
		$this->db->where('id', $item_id);

		return $this->db->update('desk_tickets', $item_data);
	}

	public function update_rujukan_sla($item_id, $dir_id, $no_urut, $sla)
	{
		$this->db->where('sid', $item_id);
		$this->db->where('dir_id', $dir_id);
		$this->db->where('no_urut', $no_urut);
		$this->db->update('desk_rujuk', array('sla' => $sla));
	}

	public function update_rujukan(&$item_data, $dir_id, $sla, $no_urut)
	{
		$item_id = $item_data['id'];

		//delete
		$this->db->where('sid', $item_id);
		//$this->db->where('dir_id', $dir_id);
		$this->db->where('no_urut', $no_urut);
		$this->db->delete('desk_rujuk');

		if ($dir_id > 0) {
			//insert
			$insert_data = array(
				'sid' => $item_data['id'],
				'dir_id' => $dir_id,
				'no_urut' => $no_urut,
				'sla' => $sla,
				'ticket_date' => $item_data['tglpengaduan']

			);
			$this->db->insert('desk_rujuk', $insert_data);
		}

		return TRUE;
	}

	public function delete_rujukan($item_id, $dir_id, $no_urut)
	{
		//delete
		$this->db->where('sid', $item_id);
		//$this->db->where('dir_id', $dir_id);
		$this->db->where('no_urut', $no_urut);
		$this->db->delete('desk_rujuk');

		return TRUE;
	}



	public function save_last_replier($item_id)
	{

		$info = $this->get_info($item_id);
		$status = $info->status;

		if ($status != '3')
			$status = '2';

		if ($this->session->id == $info->owner) {
			$status = '0';
		}

		$item_data = array(
			'lastreplier' => '1',
			'replierid' => $this->session->id,
			'status' => $status
		);



		$this->db->where('id', $item_id);

		return $this->db->update('desk_tickets', $item_data);
	}

	public function save_ppid(&$item_data, $item_id)
	{
		$this->db->from('desk_ppid');
		$this->db->where('id', (int) $item_id);

		if ($this->db->get()->num_rows() == 1) {
			$this->db->where('id', $item_id);
			return $this->db->update('desk_ppid', $item_data);
		}

		$item_data['id'] = $item_id;

		return $this->db->insert('desk_ppid', $item_data);
	}

	public function save_rujukan(&$item_data, $item_id)
	{
		$this->db->from('desk_rujukan');
		$this->db->where('rid', (int) $item_id);

		if ($this->db->get()->num_rows() == 1) {
			$this->db->where('rid', $item_id);
			return $this->db->update('desk_rujukan', $item_data);
		}

		$item_data['rid'] = $item_id;

		return $this->db->insert('desk_rujukan', $item_data);
	}

	public function add_history($item_id, $text)
	{
		$text2 = "CONCAT(history, '<li class=smaller>" . $text . "</li>')";
		$this->db->set('history', $text2, FALSE);
		$this->db->where('id', $item_id);
		return $this->db->update('desk_tickets');
	}

	public function change_tl($item_data, $item_id)
	{

		if ($item_data['tl']) {

			$info = $this->get_info($item_id);
			$tglpengaduan = $info->tglpengaduan;

			//$hk = $this->get_hk($tglpengaduan, date('Y-m-d'));


			$text = sprintf("CONCAT(history, '<li class=smaller>Pada %s status TL diubah menjadi Sudah oleh %s</li>')", $item_data['tl_date'], $this->session->name);
			$this->db->set('tl', 1);
			$this->db->set('tl_date', $item_data['tl_date']);
			$this->db->set('history', $text, FALSE);

			if ($info->is_rujuk != '1') {
				$this->db->set('fb', 1);
				$this->db->set('fb_date', $item_data['tl_date']);
			}


			//$this->db->set('hk', $hk);
		} else {
			$text = sprintf("CONCAT(history, '<li class=smaller>Pada %s status TL diubah menjadi Belum oleh %s</li>')", $item_data['tl_date'], $this->session->name);
			$this->db->set('tl', 0);
			$this->db->set('tl_date', null);
			$this->db->set('history', $text, FALSE);

			if ($info->is_rujuk != '1') {
				$this->db->set('fb', 0);
				$this->db->set('fb_date', null);
			}

			//$this->db->set('hk', 0);
		}
		$this->db->where('id', $item_id);
		return $this->db->update('desk_tickets');
	}

	public function change_fb($item_data, $item_id)
	{

		if ($item_data['fb']) {
			$text = sprintf("CONCAT(history, '<li class=smaller>Pada %s status FB diubah menjadi Sudah oleh %s</li>')", date('Y-m-d H:i:s'), $this->session->name);
			$this->db->set('fb', 1);
			$this->db->set('fb_date', $item_data['fb_date']);
			$this->db->set('fb_isi', $item_data['fb_isi']);
			$this->db->set('history', $text, FALSE);
		} else {
			$text = sprintf("CONCAT(history, '<li class=smaller>Pada %s status FB diubah menjadi Belum oleh %s</li>')", date('Y-m-d H:i:s'), $this->session->name);
			$this->db->set('fb', 0);
			$this->db->set('fb_date', null);
			$this->db->set('fb_isi', null);
			$this->db->set('history', $text, FALSE);
		}
		$this->db->where('id', $item_id);
		return $this->db->update('desk_tickets');
	}

	public function change_status($item_id, $status)
	{

		if ($status == '0') {
			$text = sprintf("CONCAT(history, '<li class=smaller>Pada %s status layanan diubah menjadi Open oleh %s</li>')", date('Y-m-d H:i:s'), $this->session->name);
			$this->db->set('status', "'0'", FALSE);
			$this->db->set('history', $text, FALSE);

			$this->db->set('tl', 0);
			$this->db->set('tl_date', null);

			$this->db->set('fb', 0);
			$this->db->set('fb_date', null);

			//$this->db->set('verified_date', null);
			//$this->db->set('verified_by', 0);

			$this->db->where('id', $item_id);
			return $this->db->update('desk_tickets');
		} elseif ($status == '3') {
			//hitung hk
			$info = $this->get_info($item_id);
			//$tglpengaduan = $info->tglpengaduan;
			//$hk = $this->get_hk($tglpengaduan, date('Y-m-d'));

			$datetime = date('Y-m-d H:i:s');

			$text = sprintf("CONCAT(history, '<li class=smaller>Pada %s status layanan diubah menjadi Closed oleh %s</li>')", $datetime, $this->session->name);
			$this->db->set('status', "'3'", FALSE);
			$this->db->set('history', $text, FALSE);
			//$this->db->set('hk', $hk);
			$this->db->set('closed_date', $datetime);

			//jika tidak dirujuk langsung TL
			if ($info->is_rujuk == '0' && $info->tl == 0) {
				$this->db->set('tl', 1);
				$this->db->set('tl_date', $datetime);
				//$this->db->set('verified_date', $datetime);
				//$this->db->set('verified_by', $this->session->id);


				$this->db->set('fb', 1);
				$this->db->set('fb_date', $datetime);
			}

			$this->db->where('id', $item_id);
			return $this->db->update('desk_tickets');
		}

		return TRUE;
	}

	public function change_status_rujukan($item_id, $dir_id, $status)
	{
		$datetime = date('Y-m-d H:i:s');
		$info = $this->get_info($item_id);

		$dir1 = $info->direktorat;
		$dir2 = $info->direktorat2;
		$dir3 = $info->direktorat3;
		$dir4 = $info->direktorat4;
		$dir5 = $info->direktorat5;

		if ($status) {
			$text = sprintf("CONCAT(history, '<li class=smaller>Pada %s status rujukan diubah menjadi Sudah TL oleh %s</li>')", $datetime, $this->session->name);
			//$this->db->set('status',"'0'", FALSE);
			$this->db->set('history', $text, FALSE);

			if ($dir1 == $dir_id) {
				$this->db->set('status1', 1);
			} elseif ($dir2 == $dir_id) {
				$this->db->set('status2', 1);
			} elseif ($dir3 == $dir_id) {
				$this->db->set('status3', 1);
			} elseif ($dir4 == $dir_id) {
				$this->db->set('status4', 1);
			} elseif ($dir5 == $dir_id) {
				$this->db->set('status5', 1);
			}

			$this->db->set('tl', 0);
			$this->db->set('tl_date', null);

			$this->db->set('fb', 0);
			$this->db->set('fb_date', null);

			//$this->db->set('verified_date', null);
			//$this->db->set('verified_by', 0);

			$this->db->where('id', $item_id);
			//$this->db->where('id', $item_id);
			return $this->db->update('desk_rujukan');
		} else {
			//hitung hk
			$info = $this->get_info($item_id);
			//$tglpengaduan = $info->tglpengaduan;
			//$hk = $this->get_hk($tglpengaduan, date('Y-m-d'));

			$datetime = date('Y-m-d H:i:s');

			$text = sprintf("CONCAT(history, '<li class=smaller>Pada %s status layanan diubah menjadi Closed oleh %s</li>')", $datetime, $this->session->name);
			$this->db->set('status', "'3'", FALSE);
			$this->db->set('history', $text, FALSE);
			//$this->db->set('hk', $hk);
			$this->db->set('closed_date', $datetime);

			//jika tidak dirujuk langsung TL
			if ($info->is_rujuk == '0' && $info->tl == 0) {
				$this->db->set('tl', 1);
				$this->db->set('tl_date', $datetime);
				//$this->db->set('verified_date', $datetime);
				//$this->db->set('verified_by', $this->session->id);


				$this->db->set('fb', 1);
				$this->db->set('fb_date', $datetime);
			}

			$this->db->where('id', $item_id);
			return $this->db->update('desk_tickets');
		}

		return TRUE;
	}

	/*
	HK dihitung dari dt s.d verified_date
	*/
	public function update_hk($trackid)
	{
		$info = $this->get_info_by_trackid($trackid);
		$tglpengaduan = $info->tglpengaduan;
		$verified_date = $info->verified_date;
		$status = $info->status;
		if (!empty($tglpengaduan) && !empty($verified_date) && $verified_date != '0000-00-00' && $status == '3') {
			$hk = $this->get_hk($tglpengaduan, $verified_date);

			$this->db->set('hk', $hk);
			$this->db->where('trackid', $trackid);
			return $this->db->update('desk_tickets');
		}

		return 0;
	}

	public function get_hk($tgl1, $tgl2)
	{

		if ($tgl1 == $tgl2)
			return 0;

		$start = new DateTime($tgl1);
		$end = new DateTime($tgl2);
		// otherwise the end date is excluded (bug?)
		$end->modify('+1 day');

		$interval = $end->diff($start);

		// total days
		$days = $interval->days;

		// create an iterateable period of date (P1D equates to 1 day)
		$period = new DatePeriod($start, new DateInterval('P1D'), $end);


		$holidays = array();
		$this->db->select('tgl');
		$this->db->from('desk_holiday');
		$this->db->where('tgl >=', $tgl1);
		$this->db->where('tgl <=', $tgl2);
		$query = $this->db->get();
		foreach ($query->result() as $d) {
			$holidays[] = $d->tgl;
		}

		foreach ($period as $dt) {
			$curr = $dt->format('D');

			// substract if Saturday or Sunday
			if ($curr == 'Sat' || $curr == 'Sun') {
				$days--;
			}

			// (optional) for the updated question
			elseif (in_array($dt->format('Y-m-d'), $holidays)) {
				$days--;
			}
		}

		return $days;
	}

	/*
	Updates multiple items at once
	*/
	public function update_multiple($item_data, $item_ids)
	{
		$this->db->where_in('item_id', explode(':', $item_ids));

		return $this->db->update('items', $item_data);
	}

	/*
	Deletes one item
	*/
	public function delete($item_id)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$success = TRUE;

		$item_id = (int)$item_id;
		$sql = "SELECT trackid, cc_id FROM desk_tickets WHERE id=$item_id LIMIT 0,1";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 1) {
			$row = $query->row();
			$trackid = $row->trackid;
			$cc_id = $row->cc_id;

			$sql = "INSERT INTO desk_tickets_hist SELECT * FROM desk_tickets WHERE id=$item_id";
			$this->db->query($sql);

			$sql = "DELETE FROM desk_tickets WHERE id=$item_id";
			$this->db->query($sql);

			$hist_array = array(
				'trackid' => $trackid,
				'cc_id' => $cc_id,
				'deleted_at' => date('Y-m-d H:i:s'),
				'deleted_by' => $this->session->id,
				'cc_sent' => 0
			);
			$this->db->insert('desk_tickets_deleted', $hist_array);
		}

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	/*
	Undeletes one item
	*/
	public function undelete($item_id)
	{
		$this->db->where('id', $item_id);

		return $this->db->update('desk_tickets', array('deleted' => 0));
	}

	/*
	Deletes a list of items
	*/
	public function delete_list($item_ids)
	{
		if (empty($item_ids))
			return FALSE;

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$success = TRUE;

		foreach ($item_ids as $item_id) {
			$item_id = (int)$item_id;
			$sql = "SELECT trackid, cc_id FROM desk_tickets WHERE id=$item_id LIMIT 0,1";
			$query = $this->db->query($sql);

			if ($query->num_rows() == 1) {
				$row = $query->row();
				$trackid = $row->trackid;
				$cc_id = $row->cc_id;

				$sql = "INSERT INTO desk_tickets_hist SELECT * FROM desk_tickets WHERE id=$item_id";
				$this->db->query($sql);

				$sql = "DELETE FROM desk_tickets WHERE id=$item_id";
				$this->db->query($sql);

				$hist_array = array(
					'trackid' => $trackid,
					'cc_id' => $cc_id,
					'deleted_at' => date('Y-m-d H:i:s'),
					'deleted_by' => $this->session->id,
					'cc_sent' => 0
				);
				$this->db->insert('desk_tickets_deleted', $hist_array);
			}
		}


		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	/*
	Verify a list of items
	*/
	public function verify_list($item_ids)
	{
		if (empty($item_ids))
			return FALSE;

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->where_in('id', $item_ids);
		$this->db->where('kota', $this->session->city);
		$array = array(
			'is_verified' => 1,
			'verified_by' => $this->session->id,
			'verified_date' => date('Y-m-d H:i:s')
		);
		$success = $this->db->update('desk_tickets', $array);


		//tambahkan set fb dan tl =>  1
		foreach ($item_ids as $id) {
			$sql = "UPDATE desk_tickets SET tl=1, tl_date=NOW(), fb=1, fb_date=NOW() WHERE is_rujuk='0' AND id=$id";
			$query = $this->db->query($sql);
		}


		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	/*
	Close a list of items
	*/
	public function close_list($item_ids)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$tgl = date('Y-m-d H:i:s');
		$note = sprintf('<li class="smaller">Pada %s layanan ditutup oleh %s </li>', $tgl, $this->session->name);

		$this->db->where_in('id', $item_ids);
		$this->db->set('status', "'3'", FALSE);
		$this->db->set('history', "CONCAT(history,'$note')", FALSE);
		$this->db->set('closed_date', $tgl);
		$success = $this->db->update('desk_tickets');


		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	public function get_replies($item_id)
	{
		$this->db->select('a.*, c.kota, c.name as direktorat');
		$this->db->from('desk_replies a');
		$this->db->join('desk_users b', 'a.staffid = b.id', 'left');
		$this->db->join('desk_direktorat c', 'b.direktoratid = c.id');
		$this->db->where('replyto', $item_id);
		$this->db->order_by('dt', 'asc');

		$query = $this->db->get();
		return $query->result();
	}

	public function get_replies_count($item_id)
	{
		$this->db->select('count(id) as cnt');
		$this->db->from('desk_replies');
		$this->db->where('replyto', $item_id);

		return $this->db->get()->row()->cnt;
	}


	public function get_categories()
	{
		$this->db->select('*');
		$this->db->from('desk_categories');
		//$this->db->where('deleted', 0);
		//$this->db->distinct();
		$this->db->order_by('id', 'asc');

		return $this->db->get();
	}

	public function get_countries()
	{
		$this->db->select('*');
		$this->db->from('desk_country');
		$this->db->order_by('nama', 'asc');

		return $this->db->get();
	}

	public function get_provinces()
	{
		$this->db->select('*');
		$this->db->from('desk_provinsi');
		$this->db->order_by('nama', 'asc');

		return $this->db->get();
	}

	public function get_profesi()
	{
		$this->db->select('*');
		$this->db->from('desk_profesi');
		$this->db->order_by('name', 'asc');

		return $this->db->get();
	}

	public function get_products()
	{
		$this->db->select('*');
		$this->db->from('desk_categories');
		$this->db->where('deleted', 0);
		$this->db->order_by('desc', 'asc');

		return $this->db->get();
	}

	public function get_range_age()
	{
		$this->db->select('*');
		$this->db->from('desk_ages');
		$this->db->order_by('id', 'asc');

		return $this->db->get();
	}

	public function get_mekanisme()
	{
		$this->db->select('*');
		$this->db->from('desk_mekanisme');
		$this->db->order_by('urutan', 'asc');

		return $this->db->get();
	}

	public function get_products_sla($info)
	{
		$this->db->select('desk_categories.id, desk_categories.desc, desk_categories.wewenang ');
		$this->db->distinct();
		$this->db->from('desk_categories');
		$this->db->join('desk_sla', 'desk_categories.id = desk_sla.komoditi_id');
		$this->db->where('desk_sla.info', $info);
		$this->db->order_by('desc', 'asc');

		return $this->db->get();
	}

	public function get_klasifikasi()
	{
		$this->db->select('*');
		$this->db->from('desk_klasifikasi');
		$this->db->order_by('nama', 'asc');

		return $this->db->get();
	}

	public function get_kab($nama_prov)
	{
		//$query = $this->db->query("SELECT desk_kabupaten.nama FROM desk_provinsi LEFT JOIN desk_kabupaten ON desk_provinsi.kode = left(desk_kabupaten.kode,2) WHERE desk_provinsi.nama = '".$nama_prov."'");

		$this->db->select('desk_kabupaten.nama');
		$this->db->from('desk_provinsi');
		$this->db->join('desk_kabupaten', 'desk_provinsi.kode=left(desk_kabupaten.kode,2)', 'left');
		$this->db->where('desk_provinsi.nama', $nama_prov);
		$this->db->order_by('desk_kabupaten.nama', 'asc');

		$query =  $this->db->get();

		return $query->result();
	}

	public function get_subklasifikasi($kla = '')
	{

		$this->db->from('desk_subklasifikasi');
		if (!empty($kla))
			$this->db->where('klasifikasi_id', $kla);
		$this->db->where('deleted', 0);

		$this->db->order_by('subklasifikasi', 'asc');

		$query =  $this->db->get();
		return $query->result();
	}

	public function get_subklasifikasi2($kla = '')
	{

		$this->db->from('desk_subklasifikasi');
		if (!empty($kla))
			$this->db->where('klasifikasi', $kla);
		$this->db->where('deleted', 0);

		$this->db->order_by('subklasifikasi', 'asc');

		$query =  $this->db->get();
		return $query->result();
	}

	public function get_unitkerjas($kota = '')
	{

		$this->db->from('desk_direktorat');
		if (!empty($kota))
			$this->db->where('kota', $kota);
		$this->db->where('deleted', 0);

		$this->db->order_by('name', 'asc');

		$query =  $this->db->get();
		return $query->result();
	}

	public function get_subklasifikasi_sla($cat, $info, $klasifikasi)
	{

		$this->db->select("desk_subklasifikasi.id, desk_subklasifikasi.subklasifikasi");
		$this->db->distinct();
		$this->db->from('desk_sla');
		$this->db->join('desk_subklasifikasi', 'desk_sla.subklasifikasi_id = desk_subklasifikasi.id');

		$this->db->where('info', $info);
		$this->db->where('komoditi_id', $cat);
		$this->db->where('klasifikasi_id', $klasifikasi);

		$this->db->order_by('desk_subklasifikasi.subklasifikasi', 'asc');

		$query =  $this->db->get();
		return $query->result();
	}

	public function get_sla($cat, $info, $klasifikasi, $subklasifikasi)
	{
		$this->db->select("sla");
		$this->db->distinct();
		$this->db->from('desk_sla');

		$this->db->where('info', $info);
		$this->db->where('komoditi_id', $cat);
		$this->db->where('klasifikasi_id', $klasifikasi);
		$this->db->where('subklasifikasi_id', $subklasifikasi);

		$query =  $this->db->get();
		return $query->row()->sla;
	}

	public function get_klasifikasi_sla($cat, $info)
	{
		$this->db->select("desk_klasifikasi.id, desk_klasifikasi.nama as klasifikasi");
		$this->db->distinct();
		$this->db->from('desk_sla');
		$this->db->join('desk_klasifikasi', 'desk_sla.klasifikasi_id = desk_klasifikasi.id');

		$this->db->where('info', $info);
		$this->db->where('komoditi_id', $cat);

		$this->db->order_by('desk_klasifikasi.nama', 'asc');

		$query =  $this->db->get();
		return $query->result();
	}

	public function get_direktorat_rujukan($cities = array())
	{
		$this->db->select('*');
		$this->db->from('desk_direktorat');
		$this->db->where('is_rujukan', 1);
		$this->db->where('deleted', 0);
		$this->db->where('dir_status', 1);

		if (!empty($cities))
			$this->db->where_in('kota', $cities);

		$this->db->order_by('kota, name', 'asc');

		return $this->db->get();
	}


	public function get_rujukans_($dir1, $dir2, $dir3, $dir4, $dir5 /*, $dir6, $dir7*/)
	{
		$this->db->select('*');
		$this->db->from('desk_direktorat');
		$this->db->where_in('id', array($dir1, $dir2, $dir3, $dir4, $dir5 /*, $dir6, $dir7*/));
		$query = $this->db->get();

		$array = array();
		foreach ($query->result() as $d) {
			$array[] = '[' . $d->kota . '] ' . $d->name;
		}

		return $array;
	}

	public function get_rujukans($item_id)
	{
		$this->db->select('a.*, b.name, b.kota');
		$this->db->from('desk_rujuk a');
		$this->db->join('desk_direktorat b', 'b.id = a.dir_id');
		$this->db->where('sid', $item_id);
		$this->db->order_by('no_urut', 'asc');

		$query = $this->db->get();
		return $query->result();
	}



	public function get_rujukans2_($id)
	{
		$query = $this->db->query('SELECT o.*, x.*, a.name AS dir1, b.name AS dir2, c.name AS dir3, d.name AS dir4, e.name AS dir5, a.kota AS kota1, b.kota AS kota2, c.kota AS kota3, d.kota AS kota4, e.kota AS kota5 FROM desk_tickets x LEFT JOIN desk_rujukan o ON o.id=x.id LEFT JOIN desk_direktorat a ON x.direktorat =a.id LEFT JOIN desk_direktorat b ON x.direktorat2 =b.id LEFT JOIN desk_direktorat c ON x.direktorat3 =c.id LEFT JOIN desk_direktorat d ON x.direktorat4 =d.id LEFT JOIN desk_direktorat e ON x.direktorat5 =e.id WHERE o.id=' . $id);

		$array = array();


		if ($query->num_rows() == 1) {
			$row = $query->row();
			$dir1 = $row->direktorat;
			$dir2 = $row->direktorat2;
			$dir3 = $row->direktorat3;
			$dir4 = $row->direktorat4;
			$dir5 = $row->direktorat5;

			/*$sla1 = $row->r1_sla;
			$sla2 = $row->r2_sla;
			$sla3 = $row->r3_sla;
			$sla4 = $row->r4_sla;
			$sla5 = $row->r5_sla;*/

			$array = array();
			if ($dir1 != 0)
				$array[] = array('id' => $dir1, 'dir' => '[' . $row->kota1 . '] ' . $row->dir1, 'sla' => $row->sla1, 'status' => $row->status1);
			if ($dir2 != 0)
				$array[] = array('id' => $dir2, 'dir' => '[' . $row->kota2 . '] ' . $row->dir2, 'sla' => $row->sla2, 'status' => $row->status2);
			if ($dir3 != 0)
				$array[] = array('id' => $dir3, 'dir' => '[' . $row->kota3 . '] ' . $row->dir3, 'sla' => $row->sla3, 'status' => $row->status3);
			if ($dir4 != 0)
				$array[] = array('id' => $dir4, 'dir' => '[' . $row->kota4 . '] ' . $row->dir4, 'sla' => $row->sla4, 'status' => $row->status4);
			if ($dir5 != 0)
				$array[] = array('id' => $dir5, 'dir' => '[' . $row->kota5 . '] ' . $row->dir5, 'sla' => $row->sla5, 'status' => $row->status5);
		}

		return $array;
	}

	public function get_prov_code($prov_nama)
	{
		$this->db->select('kode');
		$this->db->from('desk_provinsi');
		$this->db->where('nama', $prov_nama);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->row()->kode;
		}
		return 0;
	}

	public function get_prov_name($code)
	{
		$this->db->select('nama');
		$this->db->from('desk_provinsi');
		$this->db->where('kode', $code);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->row()->nama;
		}
		return '';
	}

	public function get_attachments($item_id, $mode)
	{
		$this->db->from('desk_attachments_tickets');
		$this->db->where('ticket_id', $item_id);
		$this->db->where('mode', $mode);

		return $this->db->get();
	}

	public function get_attachments_ppidtl($item_id, $mode)
	{
		$this->db->from('desk_attachments_ppidtl');
		$this->db->where('ticket_id', $item_id);
		$this->db->where('mode', $mode);
		return $this->db->get();
	}

	public function delete_attachments($att_id, $ticket_id, $mode)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		//$this->db->trans_start();

		$this->db->where('att_id', $att_id);
		$this->db->where('ticket_id', $ticket_id);
		$this->db->where('mode', $mode);
		$success = $this->db->delete('desk_attachments_tickets');

		//$this->db->trans_complete();
		//$success &= $this->db->trans_status();

		return $success;
	}

	public function delete_attachment($att_id)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		//$this->db->trans_start();

		$this->db->where('att_id', $att_id);
		$success = $this->db->delete('desk_attachments_tickets');

		//$this->db->trans_complete();
		//$success &= $this->db->trans_status();

		return $success;
	}

	public function save_attachment(&$item_data)
	{
		if ($this->db->insert('desk_attachments_tickets', $item_data)) {
			$item_data['id'] = $this->db->insert_id();

			return TRUE;
		}

		return FALSE;
	}

	public function save_attachment2(&$item_data)
	{
		if ($this->db->insert('desk_attachments', $item_data)) {
			$item_data['id'] = $this->db->insert_id();

			return TRUE;
		}

		return FALSE;
	}

	public function save_attachment_ppidtl(&$item_data)
	{
		if ($this->db->insert('desk_attachments_ppidtl', $item_data)) {
			$item_data['id'] = $this->db->insert_id();
			return TRUE;
		}

		return FALSE;
	}

	public function save_reply(&$item_data)
	{
		if ($this->db->insert('desk_replies', $item_data)) {
			$item_data['id'] = $this->db->insert_id();

			return TRUE;
		}

		return FALSE;
	}


	public function get_att_file($att_id, $att_name)
	{
		$this->db->from('desk_attachments');
		$this->db->where('att_id', $att_id);
		$this->db->where('real_name', $att_name);

		$query = $this->db->get();
		return $query->row()->saved_name;
	}

	public function get_attachment_info($item_id)
	{

		$this->db->from('desk_attachments_tickets');
		$this->db->where('att_id', $item_id);

		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach ($this->db->list_fields('desk_attachments_tickets') as $field) {
				$item_obj->$field = '';
			}
			return $item_obj;
		}
	}

	public function get_attachment_info2($item_id)
	{

		$this->db->from('desk_attachments');
		$this->db->where('att_id', $item_id);

		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach ($this->db->list_fields('desk_attachments') as $field) {
				$item_obj->$field = '';
			}
			return $item_obj;
		}
	}

	public function get_tickets_not_verified($year, $month)
	{
		$this->db->select("COUNT(id) AS cnt");
		$this->db->from('desk_tickets');
		$this->db->where('is_verified', 0);
		$this->db->where('YEAR(tglpengaduan)', $year);
		$this->db->where('MONTH(tglpengaduan)', $month);
		$this->db->where('kota', $this->session->city);

		$query = $this->db->get();
		return $query->row()->cnt;
	}



	/*
	Get number of rows
	*/
	public function get_monbalai_found_rows($search, $filters)
	{
		return $this->search_monbalai($search, $filters, 0, 0, 'desk_balai.id', 'desc', TRUE);
	}

	/*
	Perform a search on items
	*/
	public function search_monbalai($search, $filters, $rows = 0, $limit_from = 0, $sort = 'desk_balai.id', $order = 'asc', $count_only = FALSE)
	{
		// get_found_rows case
		if ($count_only == TRUE) {
			$this->db->select('COUNT(desk_balai.id) as count');
		} else {


			$this->db->select('desk_balai.nama_balai');
			$this->db->select('ifnull(a.total,0) as jml_layanan');
			$this->db->select('format(ifnull(a.rata_hk,0),2) as rata_hk');
			$this->db->select('ifnull(b.total_closed,0) as jml_closed');
			$this->db->select('ifnull(c.total_tl,0) as jml_tl');
			$this->db->select('ifnull(d.total_sla,0) as sla_yes');
		}


		if ($count_only == TRUE) {
			$this->db->from('desk_balai');
		} else {
			$this->db->from('desk_balai');

			if (!empty($filters['tgl1']) && !empty($filters['tgl2'])) {
				$this->db->join("(select kota as nama_balai, count(*) as total, avg(hk) as rata_hk from desk_tickets WHERE `tglpengaduan` BETWEEN '" . $filters['tgl1'] . "' AND '" . $filters['tgl2'] . "' group by kota) a", "a.nama_balai=desk_balai.nama_balai", "left");
				$this->db->join("(select kota as nama_balai, count(*) as total_closed from desk_tickets WHERE `tglpengaduan` BETWEEN '" . $filters['tgl1'] . "' AND '" . $filters['tgl2'] . "' AND status = '3' group by kota) b", "b.nama_balai=desk_balai.nama_balai", "left");
				$this->db->join("(select kota as nama_balai, count(*) as total_tl from desk_tickets WHERE `tglpengaduan` BETWEEN '" . $filters['tgl1'] . "' AND '" . $filters['tgl2'] . "' AND tl = '1' group by kota) c", "c.nama_balai=desk_balai.nama_balai", "left");
				$this->db->join("(select kota as nama_balai, count(*) as total_sla from desk_tickets WHERE `tglpengaduan` BETWEEN '" . $filters['tgl1'] . "' AND '" . $filters['tgl2'] . "' AND hk <= sla group by kota) d", "d.nama_balai=desk_balai.nama_balai", "left");
			}
		}






		//$this->db->where_in('info', array('P','I'));

		if (!empty($search)) {
			//$this->db->group_start();
			//$this->db->like('desk_tickets.trackid', $search);
			//$this->db->or_like('custom2', $search);
			//$this->db->group_end();
		}




		// get_found_rows case
		if ($count_only == TRUE) {
			return $this->db->get()->row()->count;
		}


		//$this->db->group_by('desk_tickets.trackid');

		// order by name of item by default
		$this->db->order_by($sort, $order);

		if ($rows > 0) {
			$this->db->limit($rows, $limit_from);
		}



		return $this->db->get();
	}

	public function get_issue_topic_suggestions($search)
	{
		$suggestions = array();
		//$this->db->distinct();
		$this->db->select('isu_topik');
		$this->db->from('desk_tickets');
		$this->db->like('isu_topik', $search);
		//$this->db->or_like('nama_cust', $search);
		//$this->db->where('deleted', 0);
		//$this->db->order_by('kd_cust', 'asc');
		$this->db->limit(10, 0);
		foreach ($this->db->get()->result() as $row) {
			$suggestions[] = array('label' => $row->isu_topik, 'value' => $row->isu_topik);
		}

		return $suggestions;
	}

	public function get_unit_teknis_suggestions($search, $item_id = -1)
	{


		$suggestions = array();
		//$this->db->distinct();
		$this->db->select('id, name, kota');
		$this->db->from('desk_direktorat');
		$this->db->where("id NOT IN (SELECT dir_id FROM desk_rujuk WHERE sid = $item_id)", NULL, FALSE);

		$this->db->group_start();
		$this->db->like('name', $search);
		$this->db->or_like('kota', $search);
		$this->db->group_end();
		$this->db->order_by('kota,name', 'asc');
		//$this->db->limit(50,0);
		$suggestions[] = array('text' => 'PUSAT | ULPK Badan POM', 'id' => 1);
		foreach ($this->db->get()->result() as $row) {
			$suggestions[] = array('text' => $row->kota . ' | ' . $row->name, 'id' => $row->id);
		}

		return $suggestions;
	}

	public function get_direktorat($id)
	{
		$this->db->select("*");
		$this->db->from('desk_direktorat');

		$this->db->where('id', $id);


		$query = $this->db->get();
		$row = $query->row();
		return $row->kota . ' | ' . $row->name;
	}

	public function get_info_ticket($item_id)
	{
		$item_id = (int)$item_id;

		$sql = "SELECT a.* ";
		$sql .= " FROM desk_tickets a";
		$sql .= " WHERE a.id=$item_id ";

		$query = $this->db->query($sql);

		return $query->row();
	}

	public function get_info_ppid($item_id)
	{
		$item_id = (int)$item_id;

		$sql = "SELECT a.* ";
		$sql .= " FROM desk_ppid a";
		$sql .= " WHERE a.id=$item_id ";

		$query = $this->db->query($sql);

		return $query->row();
	}
}
