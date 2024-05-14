<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class DatabaseP extends CI_Model
{
	public function apply_filter(&$db, $kota)
	{
		$db->where_in('info', array('P','I'));
		
		switch($kota)
		{
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
				$db->or_where('kota' ,'UNIT TEKNIS');
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
	
	public function get_data_layanan($search, $filters = array())
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
		$this->db->join('desk_categories','desk_tickets.kategori = desk_categories.id', 'left');
		$this->db->join('desk_profesi','desk_tickets.iden_profesi = desk_profesi.id', 'left');
		$this->db->join('desk_users','desk_tickets.verified_by = desk_users.id', 'left');
		
		if(!empty($filters['tgl1']) && !empty($filters['tgl2']))
		{
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}
		
		if($this->session->city == 'PUSAT')
		{
			if(!empty($filters['kota']))
			{
				$this->apply_filter($this->db, $filters['kota']);
			}
		}
		else if($this->session->city == 'UNIT TEKNIS')
		{
			$this->db->where('owner_dir', $this->session->direktoratid);
		}
		else
		{
			$this->db->where('kota', $this->session->city);
		}
		
		if(!empty($filters['keyword']))
		{
			$field = $filters['field'];
			if($field == 'trackid')
			{
				$this->db->where('desk_tickets.trackid', $filters['keyword']);
			}
			elseif($field == 'cust_nama')
			{
				$this->db->like('desk_tickets.iden_nama', $filters['keyword']);
			}
			elseif($field == 'cust_email')
			{
				$this->db->like('desk_tickets.iden_email', $filters['keyword']);
			}
			elseif($field == 'cust_telp')
			{
				$this->db->like('desk_tickets.iden_telp', $filters['keyword']);
			}
			elseif($field == 'isu_topik')
			{
				$this->db->like('desk_tickets.isu_topik', $filters['keyword']);
			}
			elseif($field == 'isi_layanan')
			{
				$this->db->like('desk_tickets.prod_masalah', $filters['keyword']);
			}
			elseif($field == 'jawaban')
			{
				$this->db->like('desk_tickets.jawaban', $filters['keyword']);
			}
			elseif($field == 'penerima')
			{
				$this->db->like('desk_tickets.penerima', $filters['keyword']);
			}
			elseif($field == 'klasifikasi')
			{
				$this->db->like('desk_tickets.klasifikasi', $filters['keyword']);
			}
			elseif($field == 'subklasifikasi')
			{
				$this->db->like('desk_tickets.subklasifikasi', $filters['keyword']);
			}
		}
		
		if(!empty($filters['iden_profesi']))
		{
			$this->db->where('iden_profesi', $filters['iden_profesi']);
		}
		
		if(!empty($filters['jenis']) && $filters['jenis'] != 'ALL')
		{
			if($filters['jenis'] == 'LAYANAN')
			{
				$this->db->where('jenis', '');
			}
			elseif($filters['jenis'] == 'LAYANAN_SP4N')
			{
				$this->db->where_in('jenis', array('','SP4N'));
			}
			else
				$this->db->where('jenis', $filters['jenis']);
		}
		
		if(!empty($filters['kategori']))
		{
			$this->db->where('kategori', $filters['kategori']);
		}
		
		if(!empty($filters['submited_via']))
		{
			$this->db->where('submited_via', $filters['submited_via']);
		}
		
		if(!empty($filters['status']))
		{
			$status_ = explode(',', $filters['status']);
			$statuses = array();
			foreach($status_ as $v)
			{
				$statuses[] = "'".$v."'";
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
		if(!empty($filters['tl']))
		{
			$tl_ = explode(',', $filters['tl']);
			$tls = array();
			foreach($tl_ as $v)
			{
				$tls[] = $v;
			}
			$this->db->where_in('tl',$tls);
		}
		if(!empty($filters['fb']))
		{
			$fb_ = explode(',', $filters['fb']);
			$fbs = array();
			foreach($fb_ as $v)
			{
				$fbs[] = $v;
			}
			$this->db->where_in('fb',$fbs);
		}
		if(!empty($filters['sla']))
		{
			$this->db->where_in('sla',$filters['sla']);
			$sla_ = explode(',', $filters['sla']);
			$slas = array();
			foreach($sla_ as $v)
			{
				$slas[] = $v;
			}
			$this->db->where_in('sla',$slas);
		}
		/*if(!empty($filters['is_verified']))
		{
			$this->db->where_in('is_verified',$filters['is_verified']);
		}*/
		
		$this->db->where_in('info', array('P','I'));
		
		if(!empty($search))
		{
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
		$this->db->join('desk_categories','desk_tickets.kategori = desk_categories.id', 'left');
		$this->db->join('desk_profesi','desk_tickets.iden_profesi = desk_profesi.id', 'left');
		$this->db->join('desk_users','desk_tickets.verified_by = desk_users.id', 'left');
		
		if(!empty($filters['tgl1']) && !empty($filters['tgl2']))
		{
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}
		
		
		$this->db->where('owner', $this->session->id);
		
		
		if(!empty($filters['keyword']))
		{
			$field = $filters['field'];
			if($field == 'trackid')
			{
				$this->db->where('desk_tickets.trackid', $filters['keyword']);
			}
			elseif($field == 'cust_nama')
			{
				$this->db->like('desk_tickets.iden_nama', $filters['keyword']);
			}
			elseif($field == 'cust_email')
			{
				$this->db->like('desk_tickets.iden_email', $filters['keyword']);
			}
			elseif($field == 'cust_telp')
			{
				$this->db->like('desk_tickets.iden_telp', $filters['keyword']);
			}
			elseif($field == 'isu_topik')
			{
				$this->db->like('desk_tickets.isu_topik', $filters['keyword']);
			}
			elseif($field == 'isi_layanan')
			{
				$this->db->like('desk_tickets.prod_masalah', $filters['keyword']);
			}
			elseif($field == 'jawaban')
			{
				$this->db->like('desk_tickets.jawaban', $filters['keyword']);
			}
			elseif($field == 'penerima')
			{
				$this->db->like('desk_tickets.penerima', $filters['keyword']);
			}
			elseif($field == 'klasifikasi')
			{
				$this->db->like('desk_tickets.klasifikasi', $filters['keyword']);
			}
			elseif($field == 'subklasifikasi')
			{
				$this->db->like('desk_tickets.subklasifikasi', $filters['keyword']);
			}
		}
		
		if(!empty($filters['iden_profesi']))
		{
			$this->db->where('iden_profesi', $filters['iden_profesi']);
		}
		
		if(!empty($filters['jenis']))
		{
			if($filters['jenis'] == 'LAYANAN')
			{
				$this->db->where('jenis', '');
			}
			else
				$this->db->where('jenis', $filters['jenis']);
		}
		
		if(!empty($filters['kategori']))
		{
			$this->db->where('kategori', $filters['kategori']);
		}
		
		if(!empty($filters['submited_via']))
		{
			$this->db->where('submited_via', $filters['submited_via']);
		}
		
		if(!empty($filters['status']))
		{
			$status_ = explode(',', $filters['status']);
			$statuses = array();
			foreach($status_ as $v)
			{
				$statuses[] = "'".$v."'";
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
		if(!empty($filters['tl']))
		{
			$tl_ = explode(',', $filters['tl']);
			$tls = array();
			foreach($tl_ as $v)
			{
				$tls[] = $v;
			}
			$this->db->where_in('tl',$tls);
		}
		if(!empty($filters['fb']))
		{
			$fb_ = explode(',', $filters['fb']);
			$fbs = array();
			foreach($fb_ as $v)
			{
				$fbs[] = $v;
			}
			$this->db->where_in('fb',$fbs);
		}
		if(!empty($filters['sla']))
		{
			$this->db->where_in('sla',$filters['sla']);
			$sla_ = explode(',', $filters['sla']);
			$slas = array();
			foreach($sla_ as $v)
			{
				$slas[] = $v;
			}
			$this->db->where_in('sla',$slas);
		}
		/*if(!empty($filters['is_verified']))
		{
			$this->db->where_in('is_verified',$filters['is_verified']);
		}*/
		
		$this->db->where_in('info', array('P','I'));
		
		if(!empty($search))
		{
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
		$this->db->join('desk_categories','desk_tickets.kategori = desk_categories.id', 'left');
		$this->db->join('desk_profesi','desk_tickets.iden_profesi = desk_profesi.id', 'left');
		$this->db->join('desk_users','desk_tickets.verified_by = desk_users.id', 'left');
		
		if(!empty($filters['tgl1']) && !empty($filters['tgl2']))
		{
			$this->db->where('tglpengaduan >=', $filters['tgl1']);
			$this->db->where('tglpengaduan <=', $filters['tgl2']);
		}
		
		$this->db->group_start();
			$this->db->where('direktorat', $this->session->direktoratid);
			$this->db->or_where('direktorat2', $this->session->direktoratid);
			$this->db->or_where('direktorat3', $this->session->direktoratid);
			$this->db->or_where('direktorat4', $this->session->direktoratid);
			$this->db->or_where('direktorat5', $this->session->direktoratid);
		$this->db->group_end();
		
		if($this->session->city == 'PUSAT')
		{
			if(!empty($filters['kota']))
			{
				$this->apply_filter($this->db, $filters['kota']);
			}
		}
		else if($this->session->city == 'UNIT TEKNIS')
		{
			//$this->db->where('owner_dir', $this->session->direktoratid);
		}
		else
		{
			//$this->db->where('kota', $this->session->city);
		}
		
		if(!empty($filters['keyword']))
		{
			$field = $filters['field'];
			if($field == 'trackid')
			{
				$this->db->where('desk_tickets.trackid', $filters['keyword']);
			}
			elseif($field == 'cust_nama')
			{
				$this->db->like('desk_tickets.iden_nama', $filters['keyword']);
			}
			elseif($field == 'cust_email')
			{
				$this->db->like('desk_tickets.iden_email', $filters['keyword']);
			}
			elseif($field == 'cust_telp')
			{
				$this->db->like('desk_tickets.iden_telp', $filters['keyword']);
			}
			elseif($field == 'isu_topik')
			{
				$this->db->like('desk_tickets.isu_topik', $filters['keyword']);
			}
			elseif($field == 'isi_layanan')
			{
				$this->db->like('desk_tickets.prod_masalah', $filters['keyword']);
			}
			elseif($field == 'jawaban')
			{
				$this->db->like('desk_tickets.jawaban', $filters['keyword']);
			}
			elseif($field == 'penerima')
			{
				$this->db->like('desk_tickets.penerima', $filters['keyword']);
			}
			elseif($field == 'klasifikasi')
			{
				$this->db->like('desk_tickets.klasifikasi', $filters['keyword']);
			}
			elseif($field == 'subklasifikasi')
			{
				$this->db->like('desk_tickets.subklasifikasi', $filters['keyword']);
			}
		}
		
		if(!empty($filters['iden_profesi']))
		{
			$this->db->where('iden_profesi', $filters['iden_profesi']);
		}
		
		if(!empty($filters['jenis']))
		{
			if($filters['jenis'] == 'LAYANAN')
			{
				$this->db->where('jenis', '');
			}
			else
				$this->db->where('jenis', $filters['jenis']);
		}
		
		if(!empty($filters['kategori']))
		{
			$this->db->where('kategori', $filters['kategori']);
		}
		
		if(!empty($filters['submited_via']))
		{
			$this->db->where('submited_via', $filters['submited_via']);
		}
		
		if(!empty($filters['status']))
		{
			$status_ = explode(',', $filters['status']);
			$statuses = array();
			foreach($status_ as $v)
			{
				$statuses[] = "'".$v."'";
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
		if(!empty($filters['tl']))
		{
			$tl_ = explode(',', $filters['tl']);
			$tls = array();
			foreach($tl_ as $v)
			{
				$tls[] = $v;
			}
			$this->db->where_in('tl',$tls);
		}
		if(!empty($filters['fb']))
		{
			$fb_ = explode(',', $filters['fb']);
			$fbs = array();
			foreach($fb_ as $v)
			{
				$fbs[] = $v;
			}
			$this->db->where_in('fb',$fbs);
		}
		if(!empty($filters['sla']))
		{
			$this->db->where_in('sla',$filters['sla']);
			$sla_ = explode(',', $filters['sla']);
			$slas = array();
			foreach($sla_ as $v)
			{
				$slas[] = $v;
			}
			$this->db->where_in('sla',$slas);
		}
		/*if(!empty($filters['is_verified']))
		{
			$this->db->where_in('is_verified',$filters['is_verified']);
		}*/
		
		$this->db->where_in('info', array('P','I'));
		
		if(!empty($search))
		{
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
	
	public function get_status_rujukan($id, $no_urut){
		
		$query = "SELECT status_rujuk$no_urut as status, tl_date$no_urut as tl_date, TOTAL_HK(tglpengaduan, tl_date$no_urut) as waktu_penyelesaian FROM desk_rujukan, desk_tickets WHERE desk_rujukan.rid = desk_tickets.id AND desk_tickets.id = '$id'";
		//echo $query;
		//exit;
		$results = $this->db->query($query);
		return $results->result_array();
	}
	
	public function get_info_direktorat($dir_id){
		$query = "SELECT concat(name, ' ( ', kota, ' )') as names FROM desk_direktorat WHERE id = '$dir_id'";
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_info_desk_reply($dir_id, $replyto){
		$query = "select a.name, a.message , a.dt
		from desk_replies a, desk_users b 
		where a.staffid = b.id AND b.direktoratid = '$dir_id' and replyto = '$replyto'";
		$results = $this->db->query($query);
		return $results->result_array();
	}
	
}