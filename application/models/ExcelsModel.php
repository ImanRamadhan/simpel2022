<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class ExcelsModel extends CI_Model
{
	
	public function get_master_negara()
	{
		$query = "select 'ID','Indonesia' union select * from desk_country";
		$results = $this->db->query($query);
		return $results->result_array();
	}
	
	public function get_master_provinsi()
	{
		$query = "select kode, nama from desk_provinsi order by nama";
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_master_kota()
	{
		$query = "select id as kode, nama_kota as nama from desk_kota order by nama_kota";
		$results = $this->db->query($query);
		return $results->result_array();
	}
	
	public function get_master_pekerjaan()
	{
		$query = "select id as kode, name as nama from desk_profesi order by name";
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_master_komoditi()
	{
		$query = "select id as kode, `desc` as nama from desk_categories";
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_master_layanan()
	{
		$query = "select id as kode, nama from desk_mekanisme";
		$results = $this->db->query($query);
		return $results->result_array();
	}
	public function get_master_klasifikasi()
	{
		//$query = "select id as kode, nama from desk_klasifikasi where deleted = 0";
		$query = "select id as kode, UPPER(replace(nama, ' ', '_')) as nama from desk_klasifikasi where deleted = 0";
		$results = $this->db->query($query);
		return $results->result_array();
	}
	public function get_master_subklasifikasi()
	{
		//$query = "select klasifikasi, id as kode, subklasifikasi from desk_subklasifikasi where deleted = 0 Order by klasifikasi asc";
		$query = "select UPPER(klasifikasi) as klasifikasi, id as kode, subklasifikasi from desk_subklasifikasi where deleted = 0 Order by klasifikasi asc";
		$results = $this->db->query($query);
		return $results->result_array();
	}
	public function get_categories($desc)
	{
		$this->db->select('id, name, desc');
		$this->db->from('desk_categories');
		$this->db->where('desc', $desc);

		$query = $this->db->get();
		if($query->num_rows() == 1)
		{
			return $query->row()->id;
		}
		return 0;
	}

	public function get_klasifikasi_id($desc)
	{
		$this->db->select('id');
		$this->db->from('desk_klasifikasi');
		//$this->db->where('nama', $desc);
		$this->db->where("UPPER(replace(nama, ' ' , '_')) = '$desc' ");

		$query = $this->db->get();
		if($query->num_rows() == 1)
		{
			return $query->row()->id;
		}
		return 0;
	}
	
	public function get_klasifikasi($desc)
	{
		$this->db->select('nama');
		$this->db->from('desk_klasifikasi');
		//$this->db->where('nama', $desc);
		$this->db->where("UPPER(replace(nama, ' ' , '_')) = '$desc' ");

		$query = $this->db->get();
		if($query->num_rows() == 1)
		{
			return $query->row()->nama;
		}
		return 0;
	}
	public function get_subklas($desc)
	{
		$this->db->select('id');
		$this->db->from('desk_subklasifikasi');
		$this->db->where('subklasifikasi', $desc);
		$this->db->where('deleted', '0');

		$query = $this->db->get();
		if($query->num_rows() == 1)
		{
			return $query->row()->id;
		}
		return 0;
	}

	public function get_profesi($desc)
	{
		$this->db->select('id, name');
		$this->db->from('desk_profesi');
		$this->db->where('name', $desc);

		$query = $this->db->get();
		if($query->num_rows() == 1)
		{
			return $query->row()->id;
		}
		return 0;
	}
	
	public function get_master_rujukan($city)
	{
		if(!empty($cities)){
			$query = "select id as kode, name from desk_direktorat where is_rujukan = 1 AND deleted = 0 AND kota in ($city) order by kota, name asc";
		} else {
			$query = "select id as kode, name from desk_direktorat where is_rujukan = 1 AND deleted = 0 order by kota, name asc";
		}
		
		$results = $this->db->query($query);
		return $results->result_array();
	}
	
	public function get_master_sla(){
		
		$array = array();

		$array[0] = array();
		$array[0]['kode'] = '5';
		$array[0]['name'] = '5 HK';

		$array[1] = array();
		$array[1]['kode'] = '6';
		$array[1]['name'] = '6 HK';

		$array[2] = array();
		$array[2]['kode'] = '14';
		$array[2]['name'] = '14 HK';

		return $array;
	}
}
?>
