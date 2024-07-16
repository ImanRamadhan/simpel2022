<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Report class
 */

class Report extends CI_Model
{
	
	//LAPSING
	public function build_condition($sheet, $inputKota, $kategori, $jenis, $gender){
		$query = "";
		if(!empty($inputKota)){
			if($inputKota=='ALL')
				$filter_cc = '';
			elseif($inputKota=='PUSAT')
				$filter_cc = ' AND (kota=\'PUSAT\' AND ws = 0) ';
			elseif($inputKota=='CC')
				$filter_cc = ' AND (kota=\'PUSAT\' AND ws > 0) ';
			elseif($inputKota=='UNIT_TEKNIS')
				$filter_cc = ' AND (kota = \'UNIT TEKNIS\') ';
			elseif($inputKota=='BALAI')
				$filter_cc = ' AND (kota <> \'PUSAT\' AND kota <> \'UNIT TEKNIS\') ';
			elseif($inputKota=='PUSAT_BALAI')
				$filter_cc = ' AND ((kota=\'PUSAT\' AND ws = 0) OR (kota <> \'UNIT TEKNIS\')) ';
			elseif($inputKota=='PUSAT_CC')
				$filter_cc = ' AND (kota=\'PUSAT\') ';
			elseif($inputKota=='PUSAT_UNIT_TEKNIS')
				$filter_cc = ' AND ((kota=\'PUSAT\' AND ws = 0) OR (kota = \'UNIT TEKNIS\')) ';
			elseif($inputKota=='PUSAT_CC_UNIT_TEKNIS')
				$filter_cc = ' AND (kota = \'PUSAT\' OR kota = \'UNIT TEKNIS\') ';
			elseif($inputKota=='PUSAT_CC_BALAI')
				$filter_cc = ' AND (kota = \'PUSAT\' OR kota <> \'UNIT TEKNIS\') ';
			elseif($inputKota=='PUSAT_CC_UNIT_TEKNIS_BALAI')
				$filter_cc = ' ';
			else
				$filter_cc = ' AND kota = \''.$inputKota.'\' ';

			$query .= $filter_cc;
		} 
		
		if($kategori != ""){
			if($kategori != 'ALL')
				$query .= " and kategori = '$kategori'";
			
		}
		/*if($jenis != ""){
			$query .= " and jenis = '$jenis'";
		}*/
		if($gender != ""){
			if($gender != 'ALL') 
				$query .= " and iden_jk = '$gender'";
		}

		/*if($sheet == 1){
			$query .= " and submited_via = 'Medsos' ";
		}*/
		

		return $query;
	}

	public function build_condition_where($kategori){
		$query = "";
		if($kategori != ""){
			if($kategori == 'ALL') $query .= " ";
			else $query .= " WHERE t1.id = '$kategori'";
			
		}
		return $query;
	}
	

	public function get_data_kelompok_jenis_pengaduan($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota, $kategori, $jenis, $gender)
	{
		
		$query = "select t1.desc as name, t2.cnt as cnt_p, t3.cnt as cnt_i 
		from desk_categories t1 
		left join 
		( 
			select kategori, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan 
			BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='P' 
			";

		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by kategori
			) t2 on t1.id=t2.kategori 
		left join 
		( 
			select kategori, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan 
			BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='I' ";

		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);
		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}
		$query .= " group by kategori) t3 on t1.id=t3.kategori ";
		
		if(($form_type != "LAPSING") && ($form_type != "YANBLIK") && ($form_type != "GENDER")){
			$query .= $this->build_condition_where($kategori);
		}
		
		
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_data_kelompok_mekanisme_menjawab($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota, $kategori, $jenis, $gender)
	{
		
		$query = "select t.nama as name, a.cnt as cnt_p, b.cnt as cnt_i 
		from desk_mekanisme t 
		left join 
			(
				select submited_via, count(*) as cnt 
				from desk_tickets 
				WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
				AND info='P' ";

		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by submited_via
				) a 
				on t.nama=a.submited_via 
		left join 
			(
				select submited_via, count(*) as cnt 
				from desk_tickets 
				WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
				AND info='I' ";
		
		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by submited_via ) b 
				on t.nama=b.submited_via 
				order by t.urutan";
		
		$results = $this->db->query($query);
		return $results->result_array();
	}
	
	public function get_data_jenis_profesi_pengadu($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota, $kategori, $jenis, $gender)
	{
		
		$query = "select t1.*, t2.cnt as cnt_p, t3.cnt as cnt_i 
		from desk_profesi t1 left join 
		( 
			select iden_profesi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='P' ";

		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by iden_profesi
			) t2 on t1.id=t2.iden_profesi 
		left join 
		( 
			select iden_profesi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='I' ";
		
		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by iden_profesi ) t3 on t1.id=t3.iden_profesi 
		order by t1.id";
		
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_data_kelompok_informasi_produk($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota, $kategori, $jenis, $gender)
	{
		
		$query = "select t1.nama as name, t2.cnt as cnt_p, t3.cnt as cnt_i 
		from desk_klasifikasi t1 left join 
		( 
			select klasifikasi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='P' ";

		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by klasifikasi
			) t2 on t1.nama=t2.klasifikasi 
		left join 
		( 
			select klasifikasi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='I' ";
		
		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by klasifikasi ) t3 on t1.nama=t3.klasifikasi ";
		
		if($form_type == "YANBLIK"){
			//$query .= " WHERE t1.nama IN ('Legalitas', 'Penandaan','Info Umum') ";
		}
		
		
		$query .= " order by t1.id";
		
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_data_kelompok_farmakologi($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota, $kategori, $jenis, $gender)
	{
		
		$query = "select t1.subklasifikasi as name, t2.cnt as cnt_p, t3.cnt as cnt_i 
		from desk_subklasifikasi t1 left join 
		( 
			select subklasifikasi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='P' ";

		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by subklasifikasi
			) t2 on t1.subklasifikasi=t2.subklasifikasi 
		left join 
		( 
			select subklasifikasi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='I' 
			";
		
		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by subklasifikasi ) t3 on t1.subklasifikasi=t3.subklasifikasi 
		WHERE t1.klasifikasi='Farmakologi' 
		order by t1.id";
		
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_data_kelompok_mutu($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota, $kategori, $jenis, $gender)
	{
		
		$query = "select t1.subklasifikasi as name, t2.cnt as cnt_p, t3.cnt as cnt_i 
		from desk_subklasifikasi t1 left join 
		( 
			select subklasifikasi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='P' ";

		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by subklasifikasi
			) t2 on t1.subklasifikasi=t2.subklasifikasi 
		left join 
		( 
			select subklasifikasi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='I' ";
		
		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by subklasifikasi ) t3 on t1.subklasifikasi=t3.subklasifikasi 
			WHERE t1.klasifikasi='Mutu' 
			order by t1.id";
		
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_data_kelompok_legalitas($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota, $kategori, $jenis, $gender)
	{
		
		$query = "select t1.subklasifikasi as name, t2.cnt as cnt_p, t3.cnt as cnt_i 
		from desk_subklasifikasi t1 left join 
		( 
			select subklasifikasi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='P'  ";

		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi', 'Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by subklasifikasi
			) t2 on t1.subklasifikasi=t2.subklasifikasi 
			left join 
		( 
			select subklasifikasi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='I' ";
		
		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi', 'Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by subklasifikasi ) t3 on t1.subklasifikasi=t3.subklasifikasi ";

		if($form_type == "YANBLIK"){
			$query .= " WHERE t3.subklasifikasi IN ('Proses pendaftaran','Sertifikasi', 'Petugas Yanblik') ";
			
		} 
		
		
		else {
			$query .= " WHERE t1.klasifikasi='Legalitas' AND t1.deleted = 0 ";
		}

		$query .= " order by t1.id";
		
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_data_kelompok_penandaan($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota, $kategori, $jenis, $gender)
	{
		
		$query = "select t1.subklasifikasi as name, t2.cnt as cnt_p, t3.cnt as cnt_i 
		from desk_subklasifikasi t1 left join 
		( 
			select subklasifikasi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='P' ";

		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by subklasifikasi
			) t2 on t1.subklasifikasi=t2.subklasifikasi 
		left join 
		( 
			select subklasifikasi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='I' ";
		
		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by subklasifikasi ) t3 on t1.subklasifikasi=t3.subklasifikasi ";
		
		if($form_type == "YANBLIK"){
			$query .= " WHERE t1.subklasifikasi IN ('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			
		} 
		
		
		else {
			$query .= " WHERE t1.klasifikasi='Penandaan' ";
		}

		$query .= " order by t1.id";
		
		$results = $this->db->query($query);
		return $results->result_array();
	}
	
	public function get_data_kelompok_info_lain_produk($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota, $kategori, $jenis, $gender)
	{
		
		$query = "select t1.subklasifikasi as name, t2.cnt as cnt_p, t3.cnt as cnt_i 
		from desk_subklasifikasi t1 left join 
		( 
			select subklasifikasi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='P' ";

		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by subklasifikasi
			) t2 on t1.subklasifikasi=t2.subklasifikasi 
		left join 
		( 
			select subklasifikasi, count(*) as cnt 
			from desk_tickets WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='I' ";
		
		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by subklasifikasi
			) t3 on t1.subklasifikasi=t3.subklasifikasi 
		WHERE t1.klasifikasi='Informasi lain ttg produk' 
		order by t1.id";
		
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_data_kelompok_info_umum($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota, $kategori, $jenis, $gender)
	{
		
		$query = "select t1.subklasifikasi as name, t2.cnt as cnt_p, t3.cnt as cnt_i 
		from desk_subklasifikasi t1 
		left join 
		( 
			select subklasifikasi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='P' ";

		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by subklasifikasi
			) t2 on t1.subklasifikasi=t2.subklasifikasi 
		left join 
		( 
			select subklasifikasi, count(*) as cnt 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
			AND info='I' ";
		
		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		$query .= " group by subklasifikasi
			) t3 on t1.subklasifikasi=t3.subklasifikasi ";
		
		if($form_type == "YANBLIK"){
			$query .= " WHERE t1.subklasifikasi = 'Petugas Yanblik' ";
			
		} 
		
		else {
			$query .= " WHERE t1.klasifikasi='Info Umum' ";
		}
		
		$query .= " order by t1.id ";
		
		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_total_data_($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota, $kategori, $jenis, $gender)
	{
		
		$query = "select count(*) as total 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' ";
		
		if($form_type == "YANBLIK"){
			$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Petugas Yanblik') ";
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		elseif($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		elseif($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}
		else {
			$query .= " and info IN ('P','I') ";
		}

		if($sheet == 1){
			$query .= " and submited_via = 'Medsos' ";
		}

		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		$results = $this->db->query($query);
		return $results->result_array();
	}

	public function get_total_data_per_klasifikasi($sheet, $form_type, $klasifikasi, $inputTgl1, $inputTgl2, $inputKota, $kategori, $jenis, $gender)
	{
		
		$query = "select count(*) as total 
			from desk_tickets 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' and klasifikasi = '$klasifikasi'";
			
		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);

		if($form_type == "YANBLIK"){
			if($klasifikasi == 'Info Umum') 
				$query .= " and subklasifikasi IN('Petugas Yanblik') ";
			if($klasifikasi == 'Legalitas') 
				$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi') ";
			if($klasifikasi == 'Penandaan') {
				//$query .= " and subklasifikasi IN('Desain kemasan','Logo') ";
				$query .= " and info IN ('P','I') ";
			}
			
			//$query .= " and klasifikasi = 'Layanan Publik' ";
		}
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		if($form_type == "GENDER")
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}

		if($sheet == 1){
			$query .= " and submited_via = 'Medsos' ";
		}
		
		$results = $this->db->query($query);
		return $results->result_array();
	}
	
	//PPID
	public function get_data_ppid($inputTgl1, $inputTgl2, $inputKota, $inputType)
	{
		$query = "select trackid, iden_nama, iden_telp, iden_alamat, tglpengaduan, desk_profesi.name as profesi, 
			desk_ppid.*
			from desk_tickets 
			left join desk_profesi ON desk_tickets.iden_profesi = desk_profesi.id 
			left join desk_ppid ON desk_tickets.id = desk_ppid.id 
			WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' AND desk_tickets.jenis = 'PPID' ";
		
		$query .= $this->build_condition(0, $inputKota, '', '', '');
		
		if($inputType == 'P')
		{
			$query .= " AND (alasan_keberatan is null or alasan_keberatan = '') ";
		}
		elseif($inputType == 'K')
		{
			$query .= " AND (alasan_keberatan != '') ";
		}
		
		$results = $this->db->query($query);
		return $results->result_array();
		
	}
	
	public function get_data_lapsing($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota, $kategori, $jenis, $gender)
	{
		
		$query = "select kategori, klasifikasi, subklasifikasi, info, submited_via, iden_profesi from desk_tickets WHERE tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' ";
		$query .= $this->build_condition($sheet, $inputKota, $kategori, $jenis, $gender);
		
		if($form_type == "PPID"){
			$query .= " and jenis = 'PPID' ";
		}
		
		if(!empty($jenis) && $jenis != 'ALL')
		{
			if($jenis == 'LAYANAN')
				$query .= " and jenis = '' ";
			elseif($jenis == 'LAYANAN_SP4N')
				$query .= " and jenis IN ('','SP4N') ";
			else
				$query .= " and jenis = '".$jenis."' ";
		}
		
		$results = $this->db->query($query);
		$item_data = $results->result_array();
		
		$num_of_rows = count($item_data);
		
		$array_produk = array(
			1 => array(0,0,0,0,0,0),
			2 => array(0,0,0,0,0,0),
			3 => array(0,0,0,0,0,0),
			4 => array(0,0,0,0,0,0),
			5 => array(0,0,0,0,0,0),
			6 => array(0,0,0,0,0,0),
			7 => array(0,0,0,0,0,0),
			8 => array(0,0,0,0,0,0),
			9 => array(0,0,0,0,0,0),
			10 => array(0,0,0,0,0,0)
		);
		
		$array_mekanisme = array(
			'Email' => array(0,0,0,0,0,0),
			'Langsung' => array(0,0,0,0,0,0),
			'Telepon' => array(0,0,0,0,0,0),
			'Fax' => array(0,0,0,0,0,0),
			'Surat' => array(0,0,0,0,0,0),
			'SMS' => array(0,0,0,0,0,0),
			'Medsos' => array(0,0,0,0,0,0),
			'Mobile' => array(0,0,0,0,0,0),
			'Kotak Saran' => array(0,0,0,0,0,0),
			'WhatsApp' => array(0,0,0,0,0,0),
			'Aplikasi Lain' => array(0,0,0,0,0,0)
		
		);
		
		//$array_mekanisme_p = $array_mekanisme;
		//$array_mekanisme_i = $array_mekanisme;
		
		$array_profesi = array(
			1 => array(0,0,0,0,0,0),
			2 => array(0,0,0,0,0,0),
			3 => array(0,0,0,0,0,0),
			4 => array(0,0,0,0,0,0),
			5 => array(0,0,0,0,0,0),
			6 => array(0,0,0,0,0,0),
			7 => array(0,0,0,0,0,0),
			8 => array(0,0,0,0,0,0),
			9 => array(0,0,0,0,0,0),
			10 => array(0,0,0,0,0,0),
			11 => array(0,0,0,0,0,0),
			12 => array(0,0,0,0,0,0),
			13 => array(0,0,0,0,0,0),
			14 => array(0,0,0,0,0,0),
			15 => array(0,0,0,0,0,0)
		);
		
		//$array_profesi_p = $array_profesi;
		//$array_profesi_i = $array_profesi;
		
		$array_klasifikasi = array(
			'Farmakologi' => array(0,0,0,0,0,0),
			'Mutu' => array(0,0,0,0,0,0),
			'Legalitas' => array(0,0,0,0,0,0),
			'Penandaan' => array(0,0,0,0,0,0),
			'Informasi lain ttg produk' => array(0,0,0,0,0,0),
			'Info Umum' => array(0,0,0,0,0,0)
		
		);
		
		//$array_klasifikasi_p = $array_klasifikasi;
		//$array_klasifikasi_i = $array_klasifikasi;
		
		
		$array_farmakologi = array(
			'Kontraindikasi' => array(0,0,0,0,0,0),
			'Efek samping' => array(0,0,0,0,0,0),
			'Indikasi/Khasiat/Kegunaan/Manfaat' => array(0,0,0,0,0,0),
			'Dosis' => array(0,0,0,0,0,0),
			'Interaksi' => array(0,0,0,0,0,0),
			'Aturan Pakai' => array(0,0,0,0,0,0),
			'Farmakokinetika/farmakodinamika' => array(0,0,0,0,0,0),
			'Peringatan' => array(0,0,0,0,0,0)
		
		);
		
		//$array_farmakologi_p = $array_farmakologi;
		//$array_farmakologi_i = $array_farmakologi;
		
		
		$array_mutu = array(
			'Pengujian' => array(0,0,0,0,0,0),
			'Cara Penyimpanan' => array(0,0,0,0,0,0),
			'Stabilitas' => array(0,0,0,0,0,0),
			'Zat Pengawet' => array(0,0,0,0,0,0),
			'Zat Pemanis' => array(0,0,0,0,0,0),
			'Zat Pewarna' => array(0,0,0,0,0,0),
			'BTP Lain' => array(0,0,0,0,0,0),
			'Angka Kecukupan Gizi' => array(0,0,0,0,0,0)
		);
		
		//$array_mutu_p = $array_mutu;
		//$array_mutu_i = $array_mutu;
		
		$array_legalitas = array(
			'Proses pendaftaran' => array(0,0,0,0,0,0),
			'Sertifikasi' => array(0,0,0,0,0,0),
			'Inspeksi' => array(0,0,0,0,0,0),
			'Produk Terdaftar' => array(0,0,0,0,0,0),
			'Public Warning' => array(0,0,0,0,0,0),
			'Periklanan' => array(0,0,0,0,0,0),
			//'Pre Review Iklan' => array(0,0,0,0,0,0),
			//'Post Review Iklan' => array(0,0,0,0,0,0)
		);
		
		//$array_legalitas_p = $array_legalitas;
		//$array_legalitas_i = $array_legalitas;
		
		$array_penandaan = array(
			'Label Halal' => array(0,0,0,0,0,0),
			'No. Batch' => array(0,0,0,0,0,0),
			'No.Registrasi' => array(0,0,0,0,0,0),
			'Tanggal Daluarsa' => array(0,0,0,0,0,0),
			'Komposisi' => array(0,0,0,0,0,0),
			'Desain kemasan' => array(0,0,0,0,0,0),
			'Logo' => array(0,0,0,0,0,0)
			
		);
		
		//$array_penandaan_p = $array_penandaan;
		//$array_penandaan_i = $array_penandaan;
		
		$array_info_lain = array(
			'Harga' => array(0,0,0,0,0,0),
			'Literatur/Peraturan' => array(0,0,0,0,0,0),
			'Produsen/Distributor' => array(0,0,0,0,0,0),
			'Brosur/Buletin/Leaflet/Makalah' => array(0,0,0,0,0,0)
		);
		
		//$array_info_lain_p = $array_info_lain;
		//$array_info_lain_i = $array_info_lain;
		
		$array_info_umum = array(
			'Management Badan POM' => array(0,0,0,0,0,0),
			'Petugas Yanblik' => array(0,0,0,0,0,0)
		);
		
		//$array_info_umum_p = $array_info_umum;
		//$array_info_umum_i = $array_info_umum;
		
		$jml_farmakologi = 0;
		$jml_mutu = 0;
		$jml_legalitas = 0;
		$jml_penandaan = 0;
		$jml_info_umum = 0;
		$jml_info_lain = 0;
		
		$jml_infomasi = 0;
		$jml_pengaduan = 0;
		
		
		//loop
		
		foreach($item_data as $item)
		{
			$kategori = $item['kategori'];
			if(array_key_exists($kategori, $array_produk))
				$array_produk[$kategori][4]++;
			
			$submited_via = $item['submited_via'];
			if(array_key_exists($submited_via, $array_mekanisme))
				$array_mekanisme[$submited_via][4]++;
			
			$profesi = $item['iden_profesi'];
			if(array_key_exists($profesi, $array_profesi))
				$array_profesi[$profesi][4]++;
			
			$klasifikasi = $item['klasifikasi'];
			
			if(strtolower($klasifikasi) == 'informasi lain ttg produk')
				$klasifikasi = 'Informasi lain ttg produk';
			
			if(array_key_exists($klasifikasi, $array_klasifikasi))
				$array_klasifikasi[$klasifikasi][4]++;
			
			$subklasifikasi = $item['subklasifikasi'];
			
			if(strtolower($subklasifikasi) == 'desain kemasan')
				$subklasifikasi = 'Desain kemasan';
			
			if(strtolower($subklasifikasi) == 'proses pendaftaran')
				$subklasifikasi = 'Proses pendaftaran';
			
			if(strtolower($subklasifikasi) == 'efek samping')
				$subklasifikasi = 'Efek samping';
			
			
			
			if($klasifikasi == 'Farmakologi')
			{
				if(array_key_exists($subklasifikasi, $array_farmakologi))
				{
					$array_farmakologi[$subklasifikasi][4]++;
					$jml_farmakologi++;
				}
			}
			if($klasifikasi == 'Mutu')
			{
				if(array_key_exists($subklasifikasi, $array_mutu))
				{
					$array_mutu[$subklasifikasi][4]++;
					$jml_mutu++;
				}
			}
			if($klasifikasi == 'Legalitas')
			{
				if(array_key_exists($subklasifikasi, $array_legalitas))
				{
					$array_legalitas[$subklasifikasi][4]++;
					$jml_legalitas++;
				}
			}
			if($klasifikasi == 'Penandaan')
			{
				if(array_key_exists($subklasifikasi, $array_penandaan))
				{
					$array_penandaan[$subklasifikasi][4]++;
					$jml_penandaan++;
				}
			}
			if($klasifikasi == 'Informasi lain ttg produk')
			{
				if(array_key_exists($subklasifikasi, $array_info_lain))
				{
					$array_info_lain[$subklasifikasi][4]++;
					$jml_info_lain++;
				}
			}
			if($klasifikasi == 'Info Umum')
			{
				if(array_key_exists($subklasifikasi, $array_info_umum))
				{
					$array_info_umum[$subklasifikasi][4]++;
					$jml_info_umum++;
				}
			}
			
			
			if($item['info']=='I')
			{
				$jml_infomasi++;
				
				if(array_key_exists($kategori, $array_produk))
					$array_produk[$kategori][0]++;
			
				if(array_key_exists($submited_via, $array_mekanisme))
					$array_mekanisme[$submited_via][0]++;
				
				if(array_key_exists($profesi, $array_profesi))
					$array_profesi[$profesi][0]++;
				
				if(array_key_exists($klasifikasi, $array_klasifikasi))
					$array_klasifikasi[$klasifikasi][0]++;
				
				
				if($klasifikasi == 'Farmakologi')
				{
					if(array_key_exists($subklasifikasi, $array_farmakologi))
						$array_farmakologi[$subklasifikasi][0]++;
				}
				
				if($klasifikasi == 'Mutu')
				{
					if(array_key_exists($subklasifikasi, $array_mutu))
						$array_mutu[$subklasifikasi][0]++;
				}
				
				if($klasifikasi == 'Legalitas')
				{
					if(array_key_exists($subklasifikasi, $array_legalitas))
					$array_legalitas[$subklasifikasi][0]++;
				}
				
				if($klasifikasi == 'Penandaan')
				{
					if(array_key_exists($subklasifikasi, $array_penandaan))
					$array_penandaan[$subklasifikasi][0]++;
				}
				
				if($klasifikasi == 'Informasi lain ttg produk')
				{
					if(array_key_exists($subklasifikasi, $array_info_lain))
					$array_info_lain[$subklasifikasi][0]++;
				}
				
				if($klasifikasi == 'Info Umum')
				{
					if(array_key_exists($subklasifikasi, $array_info_umum))
					$array_info_umum[$subklasifikasi][0]++;
				}
				
			}
			
			if($item['info']=='P')
			{
				$jml_pengaduan++;
				if(array_key_exists($kategori, $array_produk))
					$array_produk[$kategori][2]++;
				
				if(array_key_exists($submited_via, $array_mekanisme))
					$array_mekanisme[$submited_via][2]++;
				
				if(array_key_exists($profesi, $array_profesi))
					$array_profesi[$profesi][2]++;
				
				if(strtolower($klasifikasi) == 'informasi lain ttg produk')
					$klasifikasi = 'Informasi lain ttg produk';
				
				if(array_key_exists($klasifikasi, $array_klasifikasi))
					$array_klasifikasi[$klasifikasi][2]++;
				
				if($klasifikasi == 'Farmakologi')
				{
					if(array_key_exists($subklasifikasi, $array_farmakologi))
						$array_farmakologi[$subklasifikasi][2]++;
				}
				
				if($klasifikasi == 'Mutu')
				{
					if(array_key_exists($subklasifikasi, $array_mutu))
						$array_mutu[$subklasifikasi][2]++;
				}
				
				if($klasifikasi == 'Legalitas')
				{
					if(array_key_exists($subklasifikasi, $array_legalitas))
						$array_legalitas[$subklasifikasi][2]++;
				}
				
				if($klasifikasi == 'Penandaan')
				{
					if(array_key_exists($subklasifikasi, $array_penandaan))
						$array_penandaan[$subklasifikasi][2]++;
				}
				
				if($klasifikasi == 'Informasi lain ttg produk')
				{
					if(array_key_exists($subklasifikasi, $array_info_lain))
						$array_info_lain[$subklasifikasi][2]++;
				}
				
				if($klasifikasi == 'Info Umum')
				{
					if(array_key_exists($subklasifikasi, $array_info_umum))
						$array_info_umum[$subklasifikasi][2]++;
				}
			}
			
			
			
			
			
			
			
		}
		
		$jml_data = count($item_data);
		if( $jml_data > 0 )
		{
			$i=1;
			foreach($array_produk as $i => $v)
			{
				$array_produk[$i][1] = ($array_produk[$i][0]>0)?($array_produk[$i][0]/$jml_data):0;
				$array_produk[$i][3] = ($array_produk[$i][2]>0)?($array_produk[$i][2]/$jml_data):0;
				$array_produk[$i][5] = ($array_produk[$i][4]>0)?($array_produk[$i][4]/$jml_data):0;
				
				
			}
			
			
			foreach($array_mekanisme as $i => $v)
			{
				$array_mekanisme[$i][1] = ($array_mekanisme[$i][0]>0)?($array_mekanisme[$i][0]/$jml_data):0;
				$array_mekanisme[$i][3] = ($array_mekanisme[$i][2]>0)?($array_mekanisme[$i][2]/$jml_data):0;
				$array_mekanisme[$i][5] = ($array_mekanisme[$i][4]>0)?($array_mekanisme[$i][4]/$jml_data):0;
			}
			
			
			
			foreach($array_profesi as $i => $v)
			{
				$array_profesi[$i][1] = ($array_profesi[$i][0]>0)?($array_profesi[$i][0]/$jml_data):0;
				$array_profesi[$i][3] = ($array_profesi[$i][2]>0)?($array_profesi[$i][2]/$jml_data):0;
				$array_profesi[$i][5] = ($array_profesi[$i][4]>0)?($array_profesi[$i][4]/$jml_data):0;
			}
			
			
			foreach($array_klasifikasi as $i => $v)
			{
				$array_klasifikasi[$i][1] = ($array_klasifikasi[$i][0]>0)?($array_klasifikasi[$i][0]/$jml_data):0;
				$array_klasifikasi[$i][3] = ($array_klasifikasi[$i][2]>0)?($array_klasifikasi[$i][2]/$jml_data):0;
				$array_klasifikasi[$i][5] = ($array_klasifikasi[$i][4]>0)?($array_klasifikasi[$i][4]/$jml_data):0;
			}
			
			
			foreach($array_farmakologi as $i => $v)
			{
				$array_farmakologi[$i][1] = ($array_farmakologi[$i][0]>0)?($array_farmakologi[$i][0]/$jml_farmakologi):0;
				$array_farmakologi[$i][3] = ($array_farmakologi[$i][2]>0)?($array_farmakologi[$i][2]/$jml_farmakologi):0;
				$array_farmakologi[$i][5] = ($array_farmakologi[$i][4]>0)?($array_farmakologi[$i][4]/$jml_farmakologi):0;
			}
			
			foreach($array_mutu as $i => $v)
			{
				$array_mutu[$i][1] = ($array_mutu[$i][0]>0)?($array_mutu[$i][0]/$jml_mutu):0;
				$array_mutu[$i][3] = ($array_mutu[$i][2]>0)?($array_mutu[$i][2]/$jml_mutu):0;
				$array_mutu[$i][5] = ($array_mutu[$i][4]>0)?($array_mutu[$i][4]/$jml_mutu):0;
			}
			
			foreach($array_legalitas as $i => $v)
			{
				$array_legalitas[$i][1] = ($array_legalitas[$i][0]>0)?($array_legalitas[$i][0]/$jml_legalitas):0;
				$array_legalitas[$i][3] = ($array_legalitas[$i][2]>0)?($array_legalitas[$i][2]/$jml_legalitas):0;
				$array_legalitas[$i][5] = ($array_legalitas[$i][4]>0)?($array_legalitas[$i][4]/$jml_legalitas):0;
			}
			
			foreach($array_penandaan as $i => $v)
			{
				$array_penandaan[$i][1] = ($array_penandaan[$i][0]>0)?($array_penandaan[$i][0]/$jml_penandaan):0;
				$array_penandaan[$i][3] = ($array_penandaan[$i][2]>0)?($array_penandaan[$i][2]/$jml_penandaan):0;
				$array_penandaan[$i][5] = ($array_penandaan[$i][4]>0)?($array_penandaan[$i][4]/$jml_penandaan):0;
			}
			
			foreach($array_info_lain as $i => $v)
			{
				$array_info_lain[$i][1] = ($array_info_lain[$i][0]>0)?($array_info_lain[$i][0]/$jml_info_lain):0;
				$array_info_lain[$i][3] = ($array_info_lain[$i][2]>0)?($array_info_lain[$i][2]/$jml_info_lain):0;
				$array_info_lain[$i][5] = ($array_info_lain[$i][4]>0)?($array_info_lain[$i][4]/$jml_info_lain):0;
			}
			
			
			foreach($array_info_umum as $i => $v)
			{
				$array_info_umum[$i][1] = ($array_info_umum[$i][0]>0)?($array_info_umum[$i][0]/$jml_info_umum):0;
				$array_info_umum[$i][3] = ($array_info_umum[$i][2]>0)?($array_info_umum[$i][2]/$jml_info_umum):0;
				$array_info_umum[$i][5] = ($array_info_umum[$i][4]>0)?($array_info_umum[$i][4]/$jml_info_umum):0;
			}
			
			
			
		}
		
		//print_r($array_produk);
		//echo array_sum(array_column($array_produk, 't'));
		//return count($item_data);
		
		return array(
			'produk' => $array_produk,
			'mekanisme' => $array_mekanisme,
			'profesi' => $array_profesi,
			'klasifikasi' => $array_klasifikasi,
			'farmakologi' => $array_farmakologi,
			'mutu' => $array_mutu,
			'legalitas' => $array_legalitas,
			'penandaan' => $array_penandaan,
			'info_lain' => $array_info_lain,
			'info_umum' => $array_info_umum,
			'jml_data' => $jml_data,
			'jml_i' => $jml_infomasi,
			'jml_p' => $jml_pengaduan
		);
	}
	
	public function get_data_lapsing_ppid_($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota)
	{
		$query = "select date_format(tglpengaduan, '%M %Y') as bln, date_format(tglpengaduan, '%Y%m') as bln2, count(*) as jml, sum(if(keputusan = 'Dikabulkan sepenuhnya', 1, 0))  as 'dikabulkansepenuhnya', sum(if(keputusan = 'Dikabulkan sebagian', 1, 0))  as 'dikabulkansebagian', sum(if(keputusan = 'Ditolak', 1, 0)) as 'ditolak', sum(if(alasan_ditolak = 'Dikecualikan', 1, 0))  as 'dikecualikan', sum(if(alasan_ditolak = 'Belum Didokumentasikan', 1, 0))  as 'belumdidokumentasikan', sum(if(alasan_ditolak = 'Tidak Dikuasai', 1, 0))  as 'tidakdikuasai' from desk_tickets a LEFT JOIN desk_ppid b ON a.id = b.id WHERE jenis='PPID' and tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' and kota = 'PUSAT' GROUP BY bln, bln2 order by bln2";
		
		/*if($form_type == "YANBLIK"){
			//$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Desain kemasan','Logo','Petugas Yanblik') ";
			$query .= " and klasifikasi = 'Layanan Publik' ";
		} else {
			$query .= " and info IN ('P','I') ";
		}

		if($sheet == 1){
			$query .= " and submited_via = 'Medsos' ";
		}*/

		//$query .= $this->build_condition($sheet, $inputKota, 0, '', '');
		
		$query .= "";

		$results = $this->db->query($query);
		return $results->result_array();
	}
	
	public function get_products()
	{
		$this->db->from('desk_categories');
		$this->db->where('deleted', 0);
		
		return $this->db->get();
	}
	
	public function get_profesi()
	{
		$this->db->from('desk_profesi');
		//$this->db->where('mode', $mode);
		
		return $this->db->get();
	}
	
	public function get_data_lapsing_ppid($sheet, $form_type, $inputTgl1, $inputTgl2, $inputKota)
	{
		$query = "SELECT 
			concat (
			case month(tglpengaduan)
				WHEN 1 THEN 'Januari' 
				WHEN 2 THEN 'Februari' 
				WHEN 3 THEN 'Maret' 
				WHEN 4 THEN 'April' 
				WHEN 5 THEN 'Mei' 
				WHEN 6 THEN 'Juni' 
				WHEN 7 THEN 'Juli' 
				WHEN 8 THEN 'Agustus' 
				WHEN 9 THEN 'September'
				WHEN 10 THEN 'Oktober' 
				WHEN 11 THEN 'November' 
				WHEN 12 THEN 'Desember' 
			end, ' ',year(tglpengaduan)) as bln, date_format(tglpengaduan, '%Y%m') as bln2, count(*) as jml, 
			sum(if(keputusan = 'Dikabulkan sepenuhnya', 1, 0)) as 'dikabulkansepenuhnya', 
			sum(if(keputusan = 'Dikabulkan sebagian', 1, 0)) as 'dikabulkansebagian', 
			sum(if(keputusan = 'Ditolak', 1, 0)) as 'ditolak', sum(if(alasan_ditolak = 'Dikecualikan', 1, 0)) as 'dikecualikan', 
			sum(if(alasan_ditolak = 'Belum Didokumentasikan', 1, 0)) as 'belumdidokumentasikan', 
			sum(if(alasan_ditolak = 'Tidak Dikuasai', 1, 0)) as 'tidakdikuasai', AVG(TOTAL_HK(tglpengaduan, tl_date)) as rataPelayanan 
		FROM desk_tickets a
		LEFT JOIN desk_ppid b ON a.id = b.id 		
		WHERE jenis='PPID' 
		AND tglpengaduan BETWEEN '$inputTgl1' AND '$inputTgl2' 
		";
		
		/*if($form_type == "YANBLIK"){
			//$query .= " and subklasifikasi IN('Proses pendaftaran','Sertifikasi','Desain kemasan','Logo','Petugas Yanblik') ";
			$query .= " and klasifikasi = 'Layanan Publik' ";
		} else {
			$query .= " and info IN ('P','I') ";
		}

		if($sheet == 1){
			$query .= " and submited_via = 'Medsos' ";
		}*/

		$query .= $this->build_condition($sheet, $inputKota, 0, '', '');
		
		$query .= " GROUP BY bln, bln2 order by bln2";
		
		$results = $this->db->query($query);
		return $results->result_array();
	}
}
?>
