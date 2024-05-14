<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Excels_ppid extends CI_Controller {

	public function index()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Hello World !');
        
		$writer = new Xlsx($spreadsheet);
		
		$filename = 'simple';
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
    }
    
	public function export_excel()
	{
		
		$inputTgl1  		= $this->input->get('tgl1');
		$inputTgl2 			= $this->input->get('tgl2');
		$inputKota			= $this->input->get('kota');
		
		if(empty($inputKota))
			$inputKota = $this->session->city;
		
		//$inputType			= $this->input->get('type'); //P or K
		$inputType			= 'P';
		
		$spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_ppid.xlsx'));
		$worksheet = $spreadsheet->getSheet(0);
		
		if($inputKota != 'PUSAT')
		{
			$balai_info = $this->Balai->get_address($this->session->city);
			foreach(get_object_vars($balai_info) as $property => $value)
			{
				$balai_info->$property = $value;
			}
			$alamat = $balai_info->kop."\n".$balai_info->alamat."\n"."Tlp. ".$balai_info->no_telp." / Fax. ".$balai_info->no_fax."\n"."Email : ".$balai_info->email;
			
			$worksheet->getCell('A2')->setValue($alamat);
		}
		
		$title = sprintf("REGISTER PERMOHONAN INFORMASI TANGGAL %s S.D %s", convert_date2($inputTgl1), convert_date2($inputTgl2));
		$worksheet->getCell('A4')->setValue($title);
		
		
		//$worksheet->getCell('A2')->setValue($header);
		
		$start_index = 10;
		$no_urut = 1;
		$records = $this->Report->get_data_ppid($inputTgl1, $inputTgl2, $inputKota, $inputType);
		for($i = 0; $i <count($records); $i++){
			$worksheet->getCell('A'.$start_index)->setValue($no_urut);
			$worksheet->getCell('B'.$start_index)->setValue($records[$i]['trackid']);
			$worksheet->getCell('C'.$start_index)->setValue($records[$i]['tglpengaduan']);
			$worksheet->getCell('D'.$start_index)->setValue($records[$i]['iden_nama']);
			$worksheet->getCell('E'.$start_index)->setValue($records[$i]['iden_alamat']);
			
			$arr = array();
			if(!empty($records[$i]['iden_telp']))
				array_push($arr, $records[$i]['iden_telp']);
			
			if(!empty($records[$i]['iden_email']))
				array_push($arr, $records[$i]['iden_email']);
			
			$telp_email = '';
			if(count($arr) > 0)
				$telp_email = implode('/', $arr);
			
			$worksheet->getCell('F'.$start_index)->setValue($telp_email);
			$worksheet->getCell('G'.$start_index)->setValue($records[$i]['profesi']);
			$worksheet->getCell('H'.$start_index)->setValue($records[$i]['rincian']);
			$worksheet->getCell('I'.$start_index)->setValue($records[$i]['tujuan']);
			
			if($records[$i]['penguasaan_kami'])
				$worksheet->getCell('J'.$start_index)->setValue("v");
			
			if($records[$i]['penguasaan_badan_lain'])
				$worksheet->getCell('K'.$start_index)->setValue("v");
			
			if($records[$i]['info_blm_didoc'])
				$worksheet->getCell('L'.$start_index)->setValue("v");
			
			if(!empty($records[$i]['bentuk_fisik']))
			{
				$bentuk_fisik = $records[$i]['bentuk_fisik'];
				
				if(strpos($bentuk_fisik, '1') !== false)
					$worksheet->getCell('M'.$start_index)->setValue("v");
				
				if(strpos($bentuk_fisik, '2') !== false)
					$worksheet->getCell('N'.$start_index)->setValue("v");
				
			}
			
			if(!empty($records[$i]['cara_memperoleh_info']))
			{
				$cara_memperoleh_info = $records[$i]['cara_memperoleh_info'];
				
				if(strpos($cara_memperoleh_info, '1') !== false)
					$worksheet->getCell('O'.$start_index)->setValue("v");
				
				if(strpos($cara_memperoleh_info, '2') !== false)
					$worksheet->getCell('P'.$start_index)->setValue("v");
				
			}
			
			$worksheet->getCell('Q'.$start_index)->setValue($records[$i]['keputusan']);
			
			if(!empty($records[$i]['pengecualian_pasal17']))
			{
				$pasal17 = sprintf("Pasal 17 huruf %s UU KIP", $records[$i]['pasal17_huruf']);
				$worksheet->getCell('R'.$start_index)->setValue($pasal17);
			}
			if(!empty($records[$i]['pengecualian_pasal_lain']))
			{
				$worksheet->getCell('R'.$start_index)->setValue($records[$i]['pasal_lain_uu']);
			}
			$worksheet->getCell('S'.$start_index)->setValue($records[$i]['tgl_pemberitahuan_tertulis']);
			$worksheet->getCell('T'.$start_index)->setValue($records[$i]['tt_tgl']);
			$worksheet->getCell('U'.$start_index)->setValue($records[$i]['biaya_total']);
			$worksheet->getCell('V'.$start_index)->setValue("-");
			
			$start_index++;
			$no_urut++;
		}
		
		$styleArray = array(
			'borders' => array(
				'allBorders' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
			),
		);
		
		if(count($records))
			$worksheet->getStyle('A10:V'.($start_index - 1))->applyFromArray($styleArray);
		
		
		
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        
        header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=Register_PI_'.$inputTgl1.'_s.d_'.$inputTgl2.'.xlsx'); 
		header('Cache-Control: max-age=0');
        $writer->save('php://output');
	}
	
	public function export_excel_keberatan()
	{
		
		$inputTgl1  		= $this->input->get('tgl1');
		$inputTgl2 			= $this->input->get('tgl2');
		$inputKota			= $this->input->get('kota');
		
		if(empty($inputKota))
			$inputKota = $this->session->city;
		
		//$inputType			= $this->input->get('type'); //P or K
		$inputType			= 'K';
		
		
		
		$spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_ppid_keberatan.xlsx'));
		$worksheet = $spreadsheet->getSheet(0);
		
		if($inputKota != 'PUSAT')
		{
			$balai_info = $this->Balai->get_address($this->session->city);
			foreach(get_object_vars($balai_info) as $property => $value)
			{
				$balai_info->$property = $value;
			}
			$alamat = $balai_info->kop."\n".$balai_info->alamat."\n"."Tlp. ".$balai_info->no_telp." / Fax. ".$balai_info->no_fax."\n"."Email : ".$balai_info->email;
			
			$worksheet->getCell('A1')->setValue($alamat);
		}
		
		$title = sprintf("TANGGAL %s S.D %s", convert_date2($inputTgl1), convert_date2($inputTgl2));
		$worksheet->getCell('A5')->setValue($title);
		
		$start_index = 10;
		$no_urut = 1;
		$records = $this->Report->get_data_ppid($inputTgl1, $inputTgl2, $inputKota, $inputType);
		for($i = 0; $i <count($records); $i++){
			$worksheet->getCell('A'.$start_index)->setValue($no_urut);
			
			$worksheet->getCell('B'.$start_index)->setValue($records[$i]['tglpengaduan']);
			$worksheet->getCell('C'.$start_index)->setValue($records[$i]['iden_nama']);
			$worksheet->getCell('D'.$start_index)->setValue($records[$i]['iden_alamat']);
			
			$arr = array();
			if(!empty($records[$i]['iden_telp']))
				array_push($arr, $records[$i]['iden_telp']);
			
			if(!empty($records[$i]['iden_email']))
				array_push($arr, $records[$i]['iden_email']);
			
			$telp_email = '';
			if(count($arr) > 0)
				$telp_email = implode('/', $arr);
			
			$worksheet->getCell('E'.$start_index)->setValue($telp_email);
			$worksheet->getCell('F'.$start_index)->setValue($records[$i]['profesi']);
			$worksheet->getCell('G'.$start_index)->setValue($records[$i]['trackid']);
			$worksheet->getCell('H'.$start_index)->setValue($records[$i]['rincian']);
			$worksheet->getCell('I'.$start_index)->setValue($records[$i]['tujuan']);
			
			if(!empty($records[$i]['alasan_keberatan']))
			{
				$alasan = $records[$i]['alasan_keberatan'];
				
				if(strpos($alasan, 'a') !== false)
					$worksheet->getCell('J'.$start_index)->setValue("v");
				
				if(strpos($alasan, 'b') !== false)
					$worksheet->getCell('K'.$start_index)->setValue("v");
				
				if(strpos($alasan, 'c') !== false)
					$worksheet->getCell('L'.$start_index)->setValue("v");
				
				if(strpos($alasan, 'd') !== false)
					$worksheet->getCell('M'.$start_index)->setValue("v");
				
				if(strpos($alasan, 'e') !== false)
					$worksheet->getCell('N'.$start_index)->setValue("v");
				
				if(strpos($alasan, 'f') !== false)
					$worksheet->getCell('O'.$start_index)->setValue("v");
				
				if(strpos($alasan, 'g') !== false)
					$worksheet->getCell('P'.$start_index)->setValue("v");
				
			}
			
			if($records[$i]['keberatan_tgl'] != '0000-00-00')
			{
				$timestamp = strtotime($records[$i]['keberatan_tgl']);
				$day = date('w', $timestamp);
				$hari = to_day($day);
				
				$worksheet->getCell('R'.$start_index)->setValue($hari. ', '. convert_date2($records[$i]['keberatan_tgl']));
			}
			
			if(!empty($records[$i]['keberatan_nama_pejabat']))
				$worksheet->getCell('S'.$start_index)->setValue($records[$i]['keberatan_nama_pejabat'].', Sekretaris Utama');
			
			$start_index++;
			$no_urut++;
		}
		
		
		
		
		
		$styleArray = array(
			'borders' => array(
				'allBorders' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
			),
		);
		
		if(count($records))
			$worksheet->getStyle('A10:T'.($start_index - 1))->applyFromArray($styleArray);
		
		$array_ket = array(
			'a' => 'penolakan atas permintaan informasi berdasarkan alasan pengecualian sebagaimana dimaksud Pasal 17 Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik',
			'b' => 'tidak disediakannya informasi berkala sebagaimana dimaksud dalam Pasal 9 Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik',
			'c' => 'tidak ditanggapinya permintaan informasi',
			'd' => 'permintaan informasi ditanggapi tidak sebagaimana yang diminta',
			'e' => 'tidak dipenuhinya permintaan informasi',
			'f' => 'pengenaan biaya yang tidak wajar',
			'g' => 'penyampaian informasi melebihi waktu sesuai Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik'
		); 
		
		foreach($array_ket as $k => $v)
		{
			$start_index++;
			$worksheet->getCell('A'.$start_index)->setValue($k);
			$worksheet->getCell('B'.$start_index)->setValue($v);
			
		}
		
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        
        header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=Register_Keberatan_'.$inputTgl1.'_s.d_'.$inputTgl2.'.xlsx'); 
		header('Cache-Control: max-age=0');
        $writer->save('php://output');
	}
	
	
	
	
   
}