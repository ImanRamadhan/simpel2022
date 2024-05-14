<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* Grafik Model for PPID */
class GrafikP extends CI_Model
{
	
	public function filter_query($db, $inputKota, $inputDir)
	{
		if($inputKota == 'ALL')
		{
		}
		elseif($inputKota == 'PUSAT')
		{
			$this->db->where('kota', 'PUSAT');
			$this->db->where('ws',0);
			
			if($inputDir)
				$this->db->where('owner_dir', $inputDir);
		}
		elseif($inputKota == 'PUSAT_BALAI')
		{
			$this->db->where('ws', 0);
		}
		elseif($inputKota == 'BALAI')
		{
			$this->db->where_not_in('kota', array('PUSAT','UNIT TEKNIS'));
		}
		elseif($inputKota == 'PUSAT_CC')
		{
			$this->db->where('kota', 'PUSAT');
		}
		elseif($inputKota == 'PUSAT_CC_BALAI')
		{
			$this->db->where('kota <>', 'UNIT TEKNIS');
		}
		elseif($inputKota == 'PUSAT_UNIT_TEKNIS')
		{
			$this->db->where_in('kota', array('PUSAT','UNIT TEKNIS'));
			$this->db->where('ws',0);
		}
		elseif($inputKota == 'PUSAT_CC_UNIT_TEKNIS')
		{
			$this->db->where_in('kota', array('PUSAT','UNIT TEKNIS'));
		}
		elseif($inputKota == 'CC')
		{
			$this->db->where('ws',1);
			$this->db->where('kota', 'PUSAT');
		}
		else
		{
			$this->db->where('kota', $inputKota);
			if($inputDir)
				$this->db->where('owner_dir', $inputDir);
		}
	}
	
	public function getrataanwaktu($inputTgl1, $inputTgl2, $inputKota, $filters = array()){
		
		$this->db->select("kota as name, AVG(TOTAL_HK(tglpengaduan, tl_date)) as cnt");
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		
		$this->db->where('jenis', 'PPID');
		$this->filter_query($this->db, $inputKota, '');
		$this->db->group_by('kota');
		
		$query = $this->db->get();
		return $query->result_array();
		
		
	}
	
	public function getStatusTanggapan($inputTgl1, $inputTgl2, $inputKota, $filters = array()){
		
		$this->db->select("'Dikabulkan Sepenuhnya' as keputusan, count(*) as jum");
		$this->db->from('desk_tickets');
		$this->db->join('desk_ppid','desk_tickets.id = desk_ppid.id', 'left');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		$this->db->where('jenis', 'PPID');
		$this->db->where('keputusan','Dikabulkan Sepenuhnya');
		$this->filter_query($this->db, $inputKota, '');

		$sub_query1 = $this->db->get_compiled_select();
		
		$this->db->select("'Dikabulkan Sebagian' as keputusan, count(*) as jum");
		$this->db->from('desk_tickets');
		$this->db->join('desk_ppid','desk_tickets.id = desk_ppid.id', 'left');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		$this->db->where('jenis', 'PPID');
		$this->db->where('keputusan','Dikabulkan Sebagian');
		$this->filter_query($this->db, $inputKota, '');

		$sub_query2 = $this->db->get_compiled_select();
		
		$this->db->select("'Ditolak' as keputusan, count(*) as jum");
		$this->db->from('desk_tickets');
		$this->db->join('desk_ppid','desk_tickets.id = desk_ppid.id', 'left');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		$this->db->where('jenis', 'PPID');
		$this->db->where('keputusan','Ditolak');
		$this->filter_query($this->db, $inputKota, '');

		$sub_query3 = $this->db->get_compiled_select();
		
		$query = $this->db->query($sub_query1." UNION ".$sub_query2." UNION ".$sub_query3);
        return $query->result_array();
		
	}
	
}