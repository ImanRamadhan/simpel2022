<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajaxb extends CI_Model
{
	
	public function layanan_total($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		$this->db->where('kota', $this->session->city);
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function layanan_open($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		
		$this->db->where('status', '0');
		$this->db->where('kota', $this->session->city);
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function layanan_closed($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		
		$this->db->where('status', '3');
		$this->db->where('kota', $this->session->city);
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function layanan_replied($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		
		$this->db->where('status', '2');
		$this->db->where('kota', $this->session->city);
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function layanan_saya_closed($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		
		$this->db->where('status', '3');
		$this->db->where('owner', $this->session->id);
		$this->db->where('kota', $this->session->city);
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function layanan_saya_open($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		
		$this->db->where('status', '0');
		$this->db->where('owner', $this->session->id);
		$this->db->where('kota', $this->session->city);
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function layanan_saya_total($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		
		$this->db->where('owner', $this->session->id);
		$this->db->where('kota', $this->session->city);
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		
	public function layanan_saya_status_open($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		$this->db->where('owner', $this->session->id);
		$this->db->where('status', '0');
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function layanan_saya_status_closed($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		$this->db->where('owner', $this->session->id);
		$this->db->where('status', '3');
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function layanan_saya_status_replied($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		$this->db->where('owner', $this->session->id);
		$this->db->where('status', '2');
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function layanan_saya_blm_feedback($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		$this->db->where('owner', $this->session->id);
		$this->db->where('fb', '0');
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function rujukan_masuk($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		$this->db->group_start();
			$this->db->where('direktorat', $this->session->direktoratid);
			$this->db->or_where('direktorat2', $this->session->direktoratid);
			$this->db->or_where('direktorat3', $this->session->direktoratid);
			$this->db->or_where('direktorat4', $this->session->direktoratid);
			$this->db->or_where('direktorat5', $this->session->direktoratid);
			//$this->db->or_where('direktorat6', $this->session->direktoratid);
			//$this->db->or_where('direktorat7', $this->session->direktoratid);
		$this->db->group_end();
		$this->db->where_in('info', array('P','I'));
				
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function rujukan_masuk_blm_dijawab($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		$this->db->group_start();
			$this->db->where('direktorat', $this->session->direktoratid);
			$this->db->or_where('direktorat2', $this->session->direktoratid);
			$this->db->or_where('direktorat3', $this->session->direktoratid);
			$this->db->or_where('direktorat4', $this->session->direktoratid);
			$this->db->or_where('direktorat5', $this->session->direktoratid);
			//$this->db->or_where('direktorat6', $this->session->direktoratid);
			//$this->db->or_where('direktorat7', $this->session->direktoratid);
		$this->db->group_end();
		$this->db->where('replierid is null');
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function rujukan_masuk_sudah_dijawab($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		$this->db->group_start();
			$this->db->where('direktorat', $this->session->direktoratid);
			$this->db->or_where('direktorat2', $this->session->direktoratid);
			$this->db->or_where('direktorat3', $this->session->direktoratid);
			$this->db->or_where('direktorat4', $this->session->direktoratid);
			$this->db->or_where('direktorat5', $this->session->direktoratid);
			//$this->db->or_where('direktorat6', $this->session->direktoratid);
			//$this->db->or_where('direktorat7', $this->session->direktoratid);
		$this->db->group_end();
		$this->db->where('replierid is not null');
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function rujukan_keluar($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		
		/*$this->db->group_start();
			$this->db->where('direktorat >', 0);
			$this->db->or_where('direktorat2 >', 0);
			$this->db->or_where('direktorat3 >', 0);
			$this->db->or_where('direktorat4 >', 0);
			$this->db->or_where('direktorat5 >', 0);
		$this->db->group_end();*/
		$this->db->where('is_rujuk', '1');
		$this->db->where('owner_dir', $this->session->direktoratid);
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function rujukan_keluar_blm_dijawab($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		/*$this->db->group_start();
			$this->db->where('direktorat >', 0);
			$this->db->or_where('direktorat2 >', 0);
			$this->db->or_where('direktorat3 >', 0);
			$this->db->or_where('direktorat4 >', 0);
			$this->db->or_where('direktorat5 >', 0);
		$this->db->group_end();*/
		$this->db->where('is_rujuk', '1');
		$this->db->where('owner_dir', $this->session->direktoratid);
		$this->db->where('replierid is null');
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function rujukan_keluar_sudah_dijawab($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		/*$this->db->group_start();
			$this->db->where('direktorat >', 0);
			$this->db->or_where('direktorat2 >', 0);
			$this->db->or_where('direktorat3 >', 0);
			$this->db->or_where('direktorat4 >', 0);
			$this->db->or_where('direktorat5 >', 0);
		$this->db->group_end();*/
		$this->db->where('is_rujuk', '1');
		$this->db->where('owner_dir', $this->session->direktoratid);
		$this->db->where('replierid is not null');
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	
	
	//for balai
	
	
	
	
	
	public function balai_rujukan_masuk_blm_dijawab($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $date1);
		$this->db->where('tglpengaduan <=', $date2);
		$this->db->group_start();
			$this->db->where('direktorat', $this->session->direktoratid);
			$this->db->or_where('direktorat2', $this->session->direktoratid);
			$this->db->or_where('direktorat3', $this->session->direktoratid);
			$this->db->or_where('direktorat4', $this->session->direktoratid);
			$this->db->or_where('direktorat5', $this->session->direktoratid);
		$this->db->group_end();
		$this->db->where('replierid is null');
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	//footer
	public function rujukan_keluar_replied($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		//$this->db->where('tglpengaduan >=', $date1);
		//$this->db->where('tglpengaduan <=', $date2);
		/*$this->db->group_start();
			$this->db->where('direktorat >', 0);
			$this->db->or_where('direktorat2 >', 0);
			$this->db->or_where('direktorat3 >', 0);
			$this->db->or_where('direktorat4 >', 0);
			$this->db->or_where('direktorat5 >', 0);
		$this->db->group_end();*/
		$this->db->where('is_rujuk', '1');
		$this->db->where('owner_dir', $this->session->direktoratid);
		//$this->db->where('replierid is not null');
		$this->db->where('status','2');
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function rujukan_masuk_not_answered($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		//$this->db->where('tglpengaduan >=', $date1);
		//$this->db->where('tglpengaduan <=', $date2);
		//$this->db->where('kota', 'PUSAT');
		//$this->db->where('status !=', '3');
		$this->db->where('replierid', '');
		$this->db->group_start();
			$this->db->where('direktorat', $this->session->direktoratid);
			$this->db->or_where('direktorat2', $this->session->direktoratid);
			$this->db->or_where('direktorat3', $this->session->direktoratid);
			$this->db->or_where('direktorat4', $this->session->direktoratid);
			$this->db->or_where('direktorat5', $this->session->direktoratid);
		$this->db->group_end();
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function rujukan_masuk_not_closed($date1, $date2)
	{
		$this->db->select('COUNT(id) as cnt');
		$this->db->from('desk_tickets');
		//$this->db->where('tglpengaduan >=', $date1);
		//$this->db->where('tglpengaduan <=', $date2);
		//$this->db->where('kota', 'PUSAT');
		//$this->db->where('status !=', '3');
		//$this->db->where('replierid <>', '');
		$this->db->group_start();
			$this->db->where('direktorat', $this->session->direktoratid);
			$this->db->or_where('direktorat2', $this->session->direktoratid);
			$this->db->or_where('direktorat3', $this->session->direktoratid);
			$this->db->or_where('direktorat4', $this->session->direktoratid);
			$this->db->or_where('direktorat5', $this->session->direktoratid);
		$this->db->group_end();
		$this->db->where_in('info', array('P','I'));
		
		$query = $this->db->get();
		return $query->row()->cnt;
	}
	
	public function belum_tl_red($date1, $date2)
	{
		if(is_unit_teknis())
		{
			$sql = "
			SELECT count(1) as jumlah
			FROM (
				SELECT TOTAL_HK(tglpengaduan, date(now())) - sla as hitung
				FROM desk_tickets  
				WHERE tl = 0 AND info in ('P','I') AND kota='".$this->session->city."' AND  tglpengaduan >= '".$date1."' AND tglpengaduan <= '".$date2."'  
			) as X 
			WHERE X.hitung >= 0 AND X.hitung <= 5 
			";
			
			return $this->db->query($sql)->row()->jumlah;
		}
	}
	
	public function belum_tl_orange($date1, $date2)
	{
		if(is_unit_teknis())
		{
			$sql = "
			SELECT count(1) as jumlah  
			FROM (
				SELECT TOTAL_HK(tglpengaduan, date(now())) - sla as hitung
				FROM desk_tickets  
				WHERE tl = 0 AND info in ('P','I') AND kota='".$this->session->city."' AND  tglpengaduan >= '".$date1."' AND tglpengaduan <= '".$date2."'  
			) as X 
			WHERE X.hitung > 5 AND X.hitung <= 14
			";
			
			return $this->db->query($sql)->row()->jumlah;
		}
	}

	public function belum_tl_black($date1, $date2)
	{
		if(is_unit_teknis())
		{
			$sql = "
			SELECT count(1) as jumlah 
			FROM (
				SELECT TOTAL_HK(tglpengaduan, date(now())) as selisih, sla
				FROM desk_tickets  
				WHERE tl = 0 AND info in ('P','I') AND kota='".$this->session->city."' AND  tglpengaduan >= '".$date1."' AND tglpengaduan <= '".$date2."'  
			) as X 
			WHERE selisih > sla
			";
			
			return $this->db->query($sql)->row()->jumlah;
		}
	}
	
	public function belum_tl_green($date1, $date2)
	{
		if(is_unit_teknis())
		{
			$sql = "
			SELECT count(1) as jumlah  
			FROM (
				SELECT TOTAL_HK(tglpengaduan, date(now())) - sla as hitung
				FROM desk_tickets  
				WHERE tl = 0 AND info in ('P','I') AND kota='".$this->session->city."' AND  tglpengaduan >= '".$date1."' AND tglpengaduan <= '".$date2."'  
			) as X 
			WHERE X.hitung > 14 AND X.hitung <= 60
			";
			
			return $this->db->query($sql)->row()->jumlah;
		}
	}
	
	
}
?>
