<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Grafik extends CI_Model
{
	
	public function filter_query($db, $inputKota, $filters)
	{
		if($inputKota == 'ALL')
		{
		}
		elseif($inputKota == 'PUSAT')
		{
			$this->db->where('kota', 'PUSAT');
			$this->db->where('ws',0);
		}
		elseif($inputKota == 'UNIT_TEKNIS' || $inputKota == 'UNIT TEKNIS')
		{
			if(is_pusat())
				$this->db->where('kota', 'UNIT TEKNIS');
			else
				$this->db->where('owner_dir', $this->session->direktoratid);
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
			
		}
		
		//if(!empty($filters['dir']) && $filters['dir']>0)
		//	$this->db->where('owner_dir', $filters['dir']);
		
		if(!empty($filters['jenis']))
		{
			if($filters['jenis'] == 'LAYANAN')
			{
				$this->db->where('jenis', '');
			}
			elseif($filters['jenis'] == 'LAYANAN_SP4N')
			{
				$this->db->where_in('jenis', array('', 'SP4N'));
			}
			else
			{
				$this->db->where('jenis', $filters['jenis']);
			}
		}
		
		if(!empty($filters['info']))
			$this->db->where('info', $filters['info']);
		
		if(!empty($filters['kategori']) && $filters['kategori'] > 0)
			$this->db->where('kategori', $filters['kategori']);
		
		if(!empty($filters['klasifikasi']))
			$this->db->where('klasifikasi', $filters['klasifikasi']);
		
		if(!empty($filters['subklasifikasi']))
		{
			$this->db->where('subklasifikasi', $filters['subklasifikasi']);
		}
	}
	
	public function filter_query_ppid($db, $inputKota, $filters)
	{
		if($inputKota == 'ALL')
		{
		}
		elseif($inputKota == 'PUSAT')
		{
			$this->db->where('kota', 'PUSAT');
			$this->db->where('ws',0);
		}
		elseif($inputKota == 'UNIT_TEKNIS' || $inputKota == 'UNIT TEKNIS')
		{
			if(is_pusat())
				$this->db->where('kota', 'UNIT TEKNIS');
			else
				$this->db->where('owner_dir', $this->session->direktoratid);
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
			
		}
		
		//if(!empty($filters['dir']) && $filters['dir']>0)
		//	$this->db->where('owner_dir', $filters['dir']);
		
		
		
		if(!empty($filters['info']))
		{
			if($filters['info'] == 'K')
			{
				$this->db->where('desk_ppid.alasan_keberatan <>', '');
			}
			elseif($filters['info'] == 'P')
			{
				
				$this->db->group_start();
					$this->db->where('desk_ppid.alasan_keberatan', NULL);
					$this->db->or_where('desk_ppid.alasan_keberatan', '');
				$this->db->group_end();
			}
			
			//$this->db->where('info', $filters['info']);
		}
		
		if(!empty($filters['kategori']) && $filters['kategori'] > 0)
			$this->db->where('kategori', $filters['kategori']);
		
		if(!empty($filters['klasifikasi']))
			$this->db->where('klasifikasi', $filters['klasifikasi']);
		
		if(!empty($filters['subklasifikasi']))
		{
			$this->db->where('subklasifikasi', $filters['subklasifikasi']);
		}
	}
	
	public function getJenisProdukData($inputTgl1, $inputTgl2, $inputKota, $filters){
		
		$this->db->select("kategori, count(*) as cnt");
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		
		$this->filter_query($this->db, $inputKota, $filters);
		
		$this->db->group_by('kategori');
		$sub_query = $this->db->get_compiled_select();
		
		$this->db->select('t1.*, cnt');
		$this->db->from('desk_categories t1');
		$this->db->join("($sub_query) t2", "t1.id=t2.kategori","left");
		$query = $this->db->get();
		return $query->result_array();
		
	}
    
    public function getJenisPekerjaan($inputTgl1, $inputTgl2, $inputKota, $filters){
		$this->db->select("iden_profesi, count(*) as cnt");
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		
		$this->filter_query($this->db, $inputKota, $filters);
		
		$this->db->group_by('iden_profesi');
		$sub_query = $this->db->get_compiled_select();
		
		$this->db->select('t1.*, cnt');
		$this->db->from('desk_profesi t1');
		$this->db->join("($sub_query) t2", "t1.id=t2.iden_profesi","left");
		$query = $this->db->get();
		return $query->result_array();
		
	}
	
	public function getMekanismeMenjawab($inputTgl1, $inputTgl2, $inputKota, $filters) {
		$this->db->select("answered_via as name, count(*) as cnt");
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		
		$this->filter_query($this->db, $inputKota, $filters);
		
		$this->db->group_by('answered_via');
		$query = $this->db->get();
		return $query->result_array();
	}

    public function getRujukanTeknis($inputTgl1, $inputTgl2, $inputKota, $filters){
		$this->db->select("direktorat, count(*) as cnt1");
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		
		$this->filter_query($this->db, $inputKota, $filters);
		
		$this->db->group_by('direktorat');
		$sub_query1 = $this->db->get_compiled_select();
		
		$this->db->select("direktorat2, count(*) as cnt2");
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		
		$this->filter_query($this->db, $inputKota, $filters);
		
		$this->db->group_by('direktorat2');
		$sub_query2 = $this->db->get_compiled_select();
		
		$this->db->select("direktorat3, count(*) as cnt3");
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		
		$this->filter_query($this->db, $inputKota, $filters);
		
		$this->db->group_by('direktorat3');
		$sub_query3 = $this->db->get_compiled_select();
		
		$this->db->select("direktorat4, count(*) as cnt4");
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		
		$this->filter_query($this->db, $inputKota, $filters);
		
		$this->db->group_by('direktorat4');
		$sub_query4 = $this->db->get_compiled_select();
		
		$this->db->select("direktorat5, count(*) as cnt5");
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		
		$this->filter_query($this->db, $inputKota, $filters);
		
		$this->db->group_by('direktorat5');
		$sub_query5 = $this->db->get_compiled_select();
		
		$this->db->select("CONCAT(t1.kota, ' | ',t1.name) as name2, name, kota");
		$this->db->select('((case when cnt1 is null then 0 else cnt1 end)+(case when cnt2 is null then 0 else cnt2 end)+(case when cnt3 is null then 0 else cnt3 end)+(case when cnt4 is null then 0 else cnt4 end)+(case when cnt5 is null then 0 else cnt5 end)) as cnt');
		$this->db->from('desk_direktorat t1');
		$this->db->join("($sub_query1) tt1", "t1.id=tt1.direktorat","left");
		$this->db->join("($sub_query2) tt2", "t1.id=tt2.direktorat2","left");
		$this->db->join("($sub_query3) tt3", "t1.id=tt3.direktorat3","left");
		$this->db->join("($sub_query4) tt4", "t1.id=tt4.direktorat4","left");
		$this->db->join("($sub_query5) tt5", "t1.id=tt5.direktorat5","left");
		$this->db->where('((case when cnt1 is null then 0 else cnt1 end)+(case when cnt2 is null then 0 else cnt2 end)+(case when cnt3 is null then 0 else cnt3 end)+(case when cnt4 is null then 0 else cnt4 end)+(case when cnt5 is null then 0 else cnt5 end)) >',0);
		$this->db->order_by('kota,name');
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
		$this->filter_query_ppid($this->db, $inputKota, '');

		$sub_query1 = $this->db->get_compiled_select();
		
		$this->db->select("'Dikabulkan Sebagian' as keputusan, count(*) as jum");
		$this->db->from('desk_tickets');
		$this->db->join('desk_ppid','desk_tickets.id = desk_ppid.id', 'left');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		$this->db->where('jenis', 'PPID');
		$this->db->where('keputusan','Dikabulkan Sebagian');
		$this->filter_query_ppid($this->db, $inputKota, '');

		$sub_query2 = $this->db->get_compiled_select();
		
		$this->db->select("'Ditolak' as keputusan, count(*) as jum");
		$this->db->from('desk_tickets');
		$this->db->join('desk_ppid','desk_tickets.id = desk_ppid.id', 'left');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		$this->db->where('jenis', 'PPID');
		$this->db->where('keputusan','Ditolak');
		$this->filter_query_ppid($this->db, $inputKota, '');

		$sub_query3 = $this->db->get_compiled_select();
		
		$query = $this->db->query($sub_query1." UNION ".$sub_query2." UNION ".$sub_query3);
        return $query->result_array();
		
	}
	
	
	
	public function getrataanwaktu($inputTgl1, $inputTgl2, $inputKota, $filters = array()){
		$this->db->select("kota as name, AVG(TOTAL_HK(tglpengaduan, tl_date)) as cnt");
			
		$this->db->from('desk_tickets');
		$this->db->join('desk_ppid','desk_tickets.id = desk_ppid.id', 'left');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		$this->filter_query($this->db, $inputKota, $filters);
		$this->db->group_by('kota');
		
		$query = $this->db->get();
		return $query->result_array();
		
	}
	
	public function getrataanwaktuppid($inputTgl1, $inputTgl2, $inputKota, $filters = array()){
		
		$this->db->select("kota as name, AVG(TOTAL_HK(tglpengaduan, tl_date)) as cnt");
			
		$this->db->from('desk_tickets');
		$this->db->join('desk_ppid','desk_tickets.id = desk_ppid.id', 'left');
		$this->db->where('jenis','PPID');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		$this->filter_query_ppid($this->db, $inputKota, $filters);
		$this->db->group_by('kota');
		
		//echo $this->db->get_compiled_select();
		//exit;
		
		$query = $this->db->get();
		return $query->result_array();
		
		
	}
	
	public function getUnitTeknis($inputTgl1, $inputTgl2, $inputKota, $filters = array()){
		
		$this->db->select("owner_dir, count(*) as cnt");
		$this->db->from('desk_tickets');
		$this->db->where('tglpengaduan >=', $inputTgl1);
		$this->db->where('tglpengaduan <=', $inputTgl2);
		
		$this->filter_query($this->db, $inputKota, $filters);
		
		$this->db->group_by('owner_dir');
		$sub_query = $this->db->get_compiled_select();
		
		$this->db->select('t1.name, cnt');
		$this->db->from('desk_direktorat t1');
		$this->db->join("($sub_query) t2", "t1.id=t2.owner_dir","left");
		
		if($inputKota == 'UNIT_TEKNIS')
			$this->db->where('kota', 'UNIT TEKNIS');
		else
			$this->db->where('kota', $inputKota);
		
		$query = $this->db->get();
		return $query->result_array();
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
    

    public function getResponBalikRujukan_lt_3days($inputTgl1, $inputTgl2, $inputKota, $isAdmin){
        
        if($inputKota == 'ALL')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `tglpengaduan` BETWEEN '$inputTgl1' 
            AND '$inputTgl2' AND datediff(`closed_date`, `sent_date`) <3 
            AND(`direktorat` > 0 OR `direktorat2` > 0 OR `direktorat3` > 0  
            OR `direktorat4` > 0 OR `direktorat5` > 0)";
        elseif($inputKota == 'PUSAT_BALAI')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' 
            AND datediff(`closed_date`, `sent_date`) <3 AND(`direktorat` > 0 
            OR `direktorat2` > 0 OR `direktorat3` > 0  OR `direktorat4` > 0 
            OR `direktorat5` > 0) AND ws = 0";
        elseif($inputKota == 'PUSAT_CC')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' 
            AND datediff(`closed_date`, `sent_date`) <3 AND(`direktorat` > 0 
            OR `direktorat2` > 0 OR `direktorat3` > 0  OR `direktorat4` > 0 
            OR `direktorat5` > 0) AND (ws = 1 OR (ws = 0 AND kota = 'PUSAT' ))";
        elseif($inputKota == 'CC')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' 
            AND datediff(`closed_date`, `sent_date`) <3 AND(`direktorat` > 0 
            OR `direktorat2` > 0 OR `direktorat3` > 0  OR `direktorat4` > 0 
            OR `direktorat5` > 0) AND ws = 1";
        else
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' 
            AND datediff(`closed_date`, `sent_date`) <3 AND(`direktorat` > 0 
            OR `direktorat2` > 0 OR `direktorat3` > 0  OR `direktorat4` > 0 
            OR `direktorat5` > 0) AND kota = '$inputKota' AND ws = 0";

        // if(!isAdmin)
        //     $sql = "select count(*) as cnt from desk_tickets` WHERE `tglpengaduan` BETWEEN '2019-04-03' AND '2020-07-16' AND datediff(closed_date, sent_date) <3 AND `owner` IN ('.$string_users.')AND(`direktorat` > 0 OR `direktorat2` > 0 OR `direktorat3` > 0  OR `direktorat4` > 0 OR `direktorat5` > 0) AND ws = 0";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getResponBalikRujukan_eq_3days($inputTgl1, $inputTgl2, $inputKota, $isAdmin){
        
        if($inputKota == 'ALL')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `status`='3' AND `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' 
            AND datediff(`closed_date`, `sent_date`) =3 AND(`direktorat` > 0 OR `direktorat2` > 0 
            OR `direktorat3` > 0  OR `direktorat4` > 0 OR `direktorat5` > 0)";
        elseif($inputKota == 'PUSAT_BALAI')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `status`='3' AND `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' 
            AND datediff(`closed_date`, `sent_date`) =3 AND(`direktorat` > 0 OR `direktorat2` > 0 
            OR `direktorat3` > 0  OR `direktorat4` > 0 OR `direktorat5` > 0) AND ws = 0";
        elseif($inputKota == 'PUSAT_CC')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `status`='3' AND `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' 
            AND datediff(`closed_date`, `sent_date`) =3 AND(`direktorat` > 0 OR `direktorat2` > 0 
            OR `direktorat3` > 0  OR `direktorat4` > 0 OR `direktorat5` > 0) 
            AND (ws = 1 OR kota = 'PUSAT')  ";
        elseif($inputKota == 'CC')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `status`='3' AND `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' 
            AND datediff(`closed_date`, `sent_date`) =3 AND(`direktorat` > 0 OR `direktorat2` > 0 
            OR `direktorat3` > 0  OR `direktorat4` > 0 OR `direktorat5` > 0) AND ws = 1 ";
        else
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `status`='3' AND `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND 
            datediff(`closed_date`, `sent_date`) =3 AND(`direktorat` > 0 OR `direktorat2` > 0 
            OR `direktorat3` > 0  OR `direktorat4` > 0 OR `direktorat5` > 0) 
            AND kota = '$inputKota' AND ws = 0";


        // if(!isAdmin)
        //     $sql = "select count(*) as cnt from desk_tickets 
        //     WHERE `status`='3' AND `tglpengaduan` BETWEEN '2019-04-03' AND '2020-07-16' 
        //     AND datediff(`closed_date`, `sent_date`) =3 AND `owner` IN ('.$string_users.') 
        //     AND(`direktorat` > 0 OR `direktorat2` > 0 OR `direktorat3` > 0  OR `direktorat4` > 0 
        //     OR `direktorat5` > 0)";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getResponBalikRujukan_gt_3days($inputTgl1, $inputTgl2, $inputKota, $isAdmin){
        
        if($inputKota == 'ALL')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `status`='3' AND `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' 
            AND datediff(`closed_date`, `sent_date`) > 3 AND(`direktorat` > 0 OR `direktorat2` > 0 
            OR `direktorat3` > 0  OR `direktorat4` > 0 OR `direktorat5` > 0)";
        elseif($inputKota == 'PUSAT_BALAI')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `status`='3' AND `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' 
            AND datediff(`closed_date`, `sent_date`) > 3 AND(`direktorat` > 0 OR 
            `direktorat2` > 0 OR `direktorat3` > 0  OR `direktorat4` > 0 OR 
            `direktorat5` > 0) AND ws = 0";
        elseif($inputKota == 'PUSAT_CC')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `status`='3' AND `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' 
            AND datediff(`closed_date`, `sent_date`) > 3 AND(`direktorat` > 0 
            OR `direktorat2` > 0 OR `direktorat3` > 0  OR `direktorat4` > 0 
            OR `direktorat5` > 0) AND (ws = 1 OR kota = 'PUSAT')";
        elseif($inputKota == 'CC')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `status`='3' AND `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' 
            AND datediff(`closed_date`, `sent_date`) > 3 AND(`direktorat` > 0 
            OR `direktorat2` > 0 OR `direktorat3` > 0  OR `direktorat4` > 0 
            OR `direktorat5` > 0) AND ws = 1";
        else
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE `status`='3' AND `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' 
            AND datediff(`closed_date`, `sent_date`) > 3 AND(`direktorat` > 0 
            OR `direktorat2` > 0 OR `direktorat3` > 0  OR `direktorat4` > 0 
            OR `direktorat5` > 0) AND kota = '$inputKota' AND ws = 0 ";

        // if(!$_SESSION['isadmin'])
        //     $sql = "select count(*) as cnt from desk_tickets 
        //     WHERE `status`='3' AND `tglpengaduan` BETWEEN '2019-04-03' AND '2020-07-16' 
        //     AND datediff(`closed_date`, `sent_date`) > 3 AND `owner` IN ('.$string_users.') 
        //     AND(`direktorat` > 0 OR `direktorat2` > 0 OR `direktorat3` > 0  OR `direktorat4` > 0 
        //     OR `direktorat5` > 0) AND ws = 0 ";
            


        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getJenisPengaduan_minta_info($inputTgl1, $inputTgl2, $inputKota, $isAdmin){
        
        if($inputKota == 'ALL')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND info = 'I' ";
        elseif($inputKota == 'PUSAT_BALAI')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND info = 'I' AND ws = 0 ";
        elseif($inputKota == 'PUSAT_CC')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND info = 'I' AND (ws = 1 OR kota = 'PUSAT') ";
        elseif($inputKota == 'CC')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND info = 'I' AND ws = 1 ";
        else
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND info = 'I' 
            AND kota = '$inputKota' AND ws = 0 ";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getJenisPengaduan_pengaduan($inputTgl1, $inputTgl2, $inputKota, $isAdmin){
        
        if($inputKota == 'ALL')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND info = 'P' ";
        elseif($inputKota == 'PUSAT_BALAI')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND info = 'P' AND ws = 0 ";
        elseif($inputKota == 'PUSAT_CC')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND info = 'P' 
            AND (ws = 1 OR (ws = 0 AND kota = 'PUSAT')) ";
        elseif($inputKota == 'CC')
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND info = 'P' 
            AND ws = 1 ";
        else
            $sql = "select count(*) as cnt from desk_tickets 
            WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND info = 'P' 
            AND kota = '$inputKota' AND ws = 0";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getBalai($inputTgl1, $inputTgl2, $inputKota){
		
        //$sql = "select kota as name, count(*) as cnt from desk_tickets WHERE `tglpengaduan` BETWEEN '2019-04-03' AND '2020-07-16' group by kota order by cnt desc";
		//$sql = "select a.nama_balai as name, ifnull(b.cnt,0) as cnt from desk_balai a left join (select kota as name, count(*) as cnt from desk_tickets WHERE `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' group by kota) b ON a.nama_balai = b.name order by b.cnt desc";
		
		if($inputKota == 'ALL')
			$sql = "select a.nama_balai as name, ifnull(b.cnt,0) as cnt from desk_balai a left join (select kota as name, count(*) as cnt from desk_tickets WHERE `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' group by kota) b ON a.nama_balai = b.name order by b.cnt desc";
		else
			$sql = "select kota as name, count(*) as cnt from desk_tickets WHERE `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' and kota = '$inputKota' group by kota";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getJenisKelamin($inputTgl1, $inputTgl2, $inputKota, $isAdmin, $jk){
        
        if($inputKota == 'ALL')
            $sql = "select count(*) as cnt from desk_tickets WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND iden_jk = '$jk' ";
        elseif($inputKota == 'PUSAT_BALAI')
            $sql = "select count(*) as cnt from desk_tickets WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND iden_jk = '$jk' AND ws = 0 ";
        elseif($inputKota == 'PUSAT_CC')
            $sql = "select count(*) as cnt from desk_tickets WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND iden_jk = '$jk' AND (ws = 1 OR (ws = 0 AND kota = 'PUSAT')) ";
        elseif($inputKota == 'CC')
            $sql = "select count(*) as cnt from desk_tickets WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND iden_jk = '$jk' AND ws = 1 ";
        else
            $sql = "select count(*) as cnt from desk_tickets WHERE  `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2' AND iden_jk = '$jk' AND kota = '$inputKota' AND ws = 0";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

	public function getStatusLayanan($inputTgl1, $inputTgl2, $inputKota, $inputjenislayanan, $inputdatasource, $klasifikasi_id, $subklasifikasi_id){
        
        $basicsql = "
        select 'Dikabulkan sepenuhnya' as keputusan, count(1) as jum
        FROM desk_tickets a
        LEFT JOIN desk_ppid b ON a.id = b.id
        WHERE jenis='PPID' and keputusan = 'Dikabulkan sepenuhnya' [XXX] [JL] [KL] [SL]
        union
        select 'Dikabulkan sebagian' as keputusan, count(1) as jum
        FROM desk_tickets a
        LEFT JOIN desk_ppid b ON a.id = b.id
        WHERE jenis='PPID' and keputusan = 'Dikabulkan sebagian' [XXX] [JL] [KL] [SL]
        union
        select 'Ditolak' as keputusan, count(1) as jum
        FROM desk_tickets a
        LEFT JOIN desk_ppid b ON a.id = b.id
        WHERE jenis='PPID' and keputusan = 'Ditolak' [XXX] [JL] [KL] [SL]
        union
        select 'Belum Didokumentasikan' as keputusan, count(1) as jum
        FROM desk_tickets a
        LEFT JOIN desk_ppid b ON a.id = b.id
        WHERE jenis='PPID' and keputusan = 'Belum Didokumentasikan' [XXX] [JL] [KL] [SL]
        union
        select 'Tidak Dikuasai' as keputusan, count(1) as jum
        FROM desk_tickets a
        LEFT JOIN desk_ppid b ON a.id = b.id
        WHERE jenis='PPID' and keputusan = 'Tidak Dikuasai' [XXX] [JL] [KL] [SL]
        ";


        if($inputKota == 'ALL')
            $sql = str_replace ("[XXX]", "", $basicsql);

        elseif($inputKota == 'PUSAT_BALAI')
            $sql = str_replace ("[XXX]", " AND ws = 0 ", $basicsql);
            
        elseif($inputKota == 'PUSAT_CC')
            $sql = str_replace ("[XXX]", " AND (ws = 1 OR (ws = 0 AND kota = 'PUSAT')) ", $basicsql);

        elseif($inputKota == 'CC')
            $sql = str_replace ("[XXX]", "  AND ws = 1  ", $basicsql);
            
        else
            $sql = str_replace ("[XXX]", "  AND kota = '".$inputKota."' ", $basicsql);

        if ($inputjenislayanan != '') {
            $sql = str_replace ("[JL]", " AND info = '$inputjenislayanan' ", $sql);
        } else {
            $sql = str_replace ("[JL]", "  ", $sql);
        }

        
        if ($klasifikasi_id != '') {
            $sql = str_replace ("[KL]", " AND klasifikasi_id = '$klasifikasi_id' ", $sql);
        } else {
            $sql = str_replace ("[KL]", "  ", $sql);
        }
        
        if ($subklasifikasi_id != '') {
            $sql = str_replace ("[SL]", " AND subklasifikasi_id = '$subklasifikasi_id' ", $sql);
        } else {
            $sql = str_replace ("[SL]", "  ", $sql);
        }
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
	
	
	
	public function getUnitTeknis_($inputTgl1, $inputTgl2, $inputKota){
		$sql = "select a.name , ifnull(b.cnt,0) as cnt 
            from desk_direktorat a  
            left join 
                ( select owner_dir, count(*) as cnt 
                from desk_tickets WHERE kota = 'UNIT TEKNIS' AND `tglpengaduan` BETWEEN '$inputTgl1' AND '$inputTgl2'  
                group by owner_dir 
                ) b ON a.id = b.owner_dir where a.kota = 'UNIT TEKNIS' order by b.cnt desc ";
                
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}