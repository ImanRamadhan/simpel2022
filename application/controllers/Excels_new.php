<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\NamedRange;

class Excels_new extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
	}
	
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
	
	public function download_layanan()
	{
		$kota = $this->input->get('kota');
		
		$tgl1 = $this->input->get('tgl1');
		$tgl2 = $this->input->get('tgl2');
		
		$field = $this->input->get('field');
		$keyword = $this->input->get('keyword');
		$kategori = $this->input->get('kategori');
		$jenis = $this->input->get('jenis');
		
		$status = $this->input->get('status');
		$tl = $this->input->get('tl');
		$fb = $this->input->get('fb');
		$is_rujuk = $this->input->get('is_rujuk');
		$is_verified = $this->input->get('is_verified');
		
		$iden_profesi = $this->input->get('iden_profesi');
		$submited_via = $this->input->get('submited_via');
		$sla = $this->input->get('sla[]');
		
		$filters = array(
						'tgl1' => $tgl1,
						'tgl2' => $tgl2,
						'field' => $field,
						'keyword' => $keyword,
						'kategori' => $kategori,
						'jenis' => $jenis,
						'status' => $status,
						'tl' => $tl,
						'fb' => $fb,
						'is_rujuk' => $is_rujuk,
						'is_verified' => $is_verified,
						'iden_profesi' => $iden_profesi,
						'submited_via' => $submited_via,
						'sla' => $sla
						);
		if(is_pusat())
			$filters['kota'] = $kota;
		else
			$filters['kota'] =  $this->session->city;
		
		$file_out = 'Layanan_'.$kota.'_'.$tgl1.'_s.d_'.$tgl2.'.xlsx';
		
		$this->print_data('', $filters, $file_out, 'LAYANAN');
		
	}
	
	public function download_layanan_saya()
	{
		$kota = $this->input->get('kota');
		
		$tgl1 = $this->input->get('tgl1');
		$tgl2 = $this->input->get('tgl2');
		
		$field = $this->input->get('field');
		$keyword = $this->input->get('keyword');
		$kategori = $this->input->get('kategori');
		$jenis = $this->input->get('jenis');
		
		$status = $this->input->get('status');
		$tl = $this->input->get('tl');
		$fb = $this->input->get('fb');
		$is_rujuk = $this->input->get('is_rujuk');
		$is_verified = $this->input->get('is_verified');
		
		$iden_profesi = $this->input->get('iden_profesi');
		$submited_via = $this->input->get('submited_via');
		$sla = $this->input->get('sla[]');
		
		$filters = array(
						'tgl1' => $tgl1,
						'tgl2' => $tgl2,
						'field' => $field,
						'keyword' => $keyword,
						'kategori' => $kategori,
						'jenis' => $jenis,
						'status' => $status,
						'tl' => $tl,
						'fb' => $fb,
						'is_rujuk' => $is_rujuk,
						'is_verified' => $is_verified,
						'iden_profesi' => $iden_profesi,
						'submited_via' => $submited_via,
						'sla' => $sla
						);
		if(is_pusat())
			$filters['kota'] = $kota;
		else
			$filters['kota'] =  $this->session->city;
		
		$file_out = 'Layanan_Saya_'.$tgl1.'_s.d_'.$tgl2.'.xlsx';
		
		$this->print_data('', $filters, $file_out, 'LAYANANSAYA');
		
	}
	
	public function download_database()
	{
		$kota = $this->input->post('kota');
		$tgl1 = $this->input->post('tgl1');
		$tgl2 = $this->input->post('tgl2');
		
		$kategori = $this->input->post('kategori');
		if($kategori == 'ALL')$kategori = '';
		$jenis = $this->input->post('jenis');
		//if($jenis == 'ALL')$jenis = '';
		
		$filters = array(
						'tgl1' => $tgl1,
						'tgl2' => $tgl2,
						'kategori' => $kategori,
						'jenis' => $jenis,
						'kota' => $kota
						);
		//print_r($filters);exit;
		$file_out = 'Database_'.$kota.'_'.$tgl1.'_s.d_'.$tgl2.'.xlsx';
		
		$this->print_data('', $filters, $file_out, 'DATABASE');
	}
	
	public function download_resume()
	{
		$kota = $this->input->post('kota');
		$tgl1 = $this->input->post('tgl1');
		$tgl2 = $this->input->post('tgl2');
		
		/*$kategori = $this->input->post('kategori');
		if($kategori == 'ALL')$kategori = '';
		$jenis = $this->input->post('jenis');
		if($jenis == 'ALL')$jenis = '';*/
		
		$filters = array(
						'tgl1' => $tgl1,
						'tgl2' => $tgl2,
						//'kategori' => $kategori,
						//'jenis' => $jenis,
						'kota' => $kota
						);
		//print_r($filters);exit;
		$file_out = 'Resume_'.$kota.'_'.$tgl1.'_s.d_'.$tgl2.'.xlsx';
		
		$this->print_data('', $filters, $file_out, 'RESUME');
	}
	
	public function download_rujukan_masuk()
	{
		$kota = $this->input->post('kota');
		$tgl1 = $this->input->post('tgl1');
		$tgl2 = $this->input->post('tgl2');
				
		$filters = array(
						'tgl1' => $tgl1,
						'tgl2' => $tgl2,
						'kota' => $kota
						);
		//print_r($filters);exit;
		$file_out = 'Rujukan_Masuk_'.$kota.'_'.$tgl1.'_s.d_'.$tgl2.'.xlsx';
		
		$this->print_data('', $filters, $file_out, 'RUJUKAN_MASUK');
	}
	
	public function print_data($search = '', $filters = array(), $file_out = '', $tipe = '')
	{
		
		$tgl1 = $filters['tgl1'];
		$tgl2 = $filters['tgl2'];
		$kota = $filters['kota'];
		
		$spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_layanan_new.xls'));
		$worksheet = $spreadsheet->getSheet(0);
		
		$print_tgl           = "TANGGAL ".format_date_indo($tgl1)." s/d ".format_date_indo($tgl2);
        $worksheet->getCell('A5')->setValue($print_tgl);
		
		$this->load->model('DatabaseP');
		//$search = '';
		if($tipe == 'LAYANAN' || $tipe == 'DATABASE' || $tipe == 'RESUME')
			$records = $this->DatabaseP->get_data_layanan($search, $filters);
		elseif($tipe == 'LAYANANSAYA')
			$records = $this->DatabaseP->get_data_layanan_saya($search, $filters);
		elseif($tipe == 'RUJUKAN_MASUK')
			$records = $this->DatabaseP->get_data_rujukan_masuk($search, $filters);
		//print_r($records);
		//exit;
		
		$start_index = 10;
		
		
		for($i=0; $i<count($records); $i++)
		{
			$col_idx = 1;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $i+1);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['trackid']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['iden_nama']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['iden_alamat']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['iden_telp']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['iden_email']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['pekerjaan']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['iden_jk']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['detail_laporan']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['jawaban']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['info']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['keterangan']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['jenis_komoditi']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['jenis']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['penerima']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['isu_topik']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['klasifikasi']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['subklasifikasi']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['sarana']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['waktu']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['shift']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['kode_petugas']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, ($records[$i]['is_verified']?'Sudah':'Belum'));
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['verified_date']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['verificator_name']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['tglpengaduan']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['closed_date']);
			
			$printed  = '';
			switch($records[$i]['status'])
			{
				case 0:
					$printed = "Open";
					break;
				case 1:
					$printed = "Waiting Reply";
					break;
				case 2:
					$printed = "Replied";
					break;
				case 3:
					$printed = "Closed";
					break;
				case 4:
					$printed = "In Progress";
					break;
				case 5:
					$printed = "On Hold";
					break;
				default:
					$printed = "";
					
			}
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $printed);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, ($records[$i]['tl']?'Sudah':'Belum'));
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['tl_date']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, ($records[$i]['fb']?'Sudah':'Belum'));
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['fb_date']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['kota']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['sla']);
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['waktu_layanan']);
			$pemenuhan = '';
			if($records[$i]['tl'])
			{
				if($records[$i]['waktu_layanan'] <= $records[$i]['sla'])
					$pemenuhan = 'Memenuhi SLA';
				else
					$pemenuhan = 'Melebihi SLA';
			}
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $pemenuhan);
			
			if((int)$records[$i]['direktorat'])
			{
				$master_direktorat = $this->DatabaseP->get_info_direktorat($records[$i]['direktorat']);
                $info_desk_reply = $this->DatabaseP->get_info_desk_reply($records[$i]['direktorat'], $records[$i]['id']);
				$status_rujukan =  $this->DatabaseP->get_status_rujukan($records[$i]['id'], "1");

				$dir_rujukan = $master_direktorat[0]['names'];
				$kalimat_rujukan = "";
				for($x =0; $x< count($info_desk_reply); $x++){
					$kalimat_rujukan .= "". $info_desk_reply[$x]['dt'].' ('.$info_desk_reply[$x]['name'].")\n";
					$kalimat_rujukan .= $info_desk_reply[$x]['message']."\n";
				}
				
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $dir_rujukan);
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $kalimat_rujukan);
				
				
				$status = '';
				$waktu_penyelesaian = '';
				
				if(count($status_rujukan) > 0)
				{
					$status = ($status_rujukan[0]['status'])?'Sudah TL':'Belum TL';
					$waktu_penyelesaian = $status_rujukan[0]['waktu_penyelesaian'];
				}
				
				
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $status);
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $waktu_penyelesaian);
			}
			else
			{
				$col_idx++;
				$col_idx++;
				$col_idx++;
				$col_idx++;
			}
			
			if((int)$records[$i]['direktorat2'])
			{
				$master_direktorat = $this->DatabaseP->get_info_direktorat($records[$i]['direktorat2']);
                $info_desk_reply = $this->DatabaseP->get_info_desk_reply($records[$i]['direktorat2'], $records[$i]['id']);
				$status_rujukan =  $this->DatabaseP->get_status_rujukan($records[$i]['id'], "2");

				$dir_rujukan = $master_direktorat[0]['names'];
				$kalimat_rujukan = "";
				for($x =0; $x< count($info_desk_reply); $x++){
					$kalimat_rujukan .= "". $info_desk_reply[$x]['dt'].' ('.$info_desk_reply[$x]['name'].")\n";
					$kalimat_rujukan .= $info_desk_reply[$x]['message']."\n";
				}
				
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $dir_rujukan);
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $kalimat_rujukan);
				
				
				$status = '';
				$waktu_penyelesaian = '';
				
				if(count($status_rujukan) > 0)
				{
					$status = ($status_rujukan[0]['status'])?'Sudah TL':'Belum TL';
					$waktu_penyelesaian = $status_rujukan[0]['waktu_penyelesaian'];
				}
				
				
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $status);
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $waktu_penyelesaian);
			}
			else
			{
				$col_idx++;
				$col_idx++;
				$col_idx++;
				$col_idx++;
			}
			
			if((int)$records[$i]['direktorat3'])
			{
				$master_direktorat = $this->DatabaseP->get_info_direktorat($records[$i]['direktorat3']);
                $info_desk_reply = $this->DatabaseP->get_info_desk_reply($records[$i]['direktorat3'], $records[$i]['id']);
				$status_rujukan =  $this->DatabaseP->get_status_rujukan($records[$i]['id'], "3");

				$dir_rujukan = $master_direktorat[0]['names'];
				$kalimat_rujukan = "";
				for($x =0; $x< count($info_desk_reply); $x++){
					$kalimat_rujukan .= "". $info_desk_reply[$x]['dt'].' ('.$info_desk_reply[$x]['name'].")\n";
					$kalimat_rujukan .= $info_desk_reply[$x]['message']."\n";
				}
				
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $dir_rujukan);
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $kalimat_rujukan);
				
				
				$status = '';
				$waktu_penyelesaian = '';
				
				if(count($status_rujukan) > 0)
				{
					$status = ($status_rujukan[0]['status'])?'Sudah TL':'Belum TL';
					$waktu_penyelesaian = $status_rujukan[0]['waktu_penyelesaian'];
				}
				
				
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $status);
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $waktu_penyelesaian);
			}
			else
			{
				$col_idx++;
				$col_idx++;
				$col_idx++;
				$col_idx++;
			}
			
			if((int)$records[$i]['direktorat4'])
			{
				$master_direktorat = $this->DatabaseP->get_info_direktorat($records[$i]['direktorat4']);
                $info_desk_reply = $this->DatabaseP->get_info_desk_reply($records[$i]['direktorat4'], $records[$i]['id']);
				$status_rujukan =  $this->DatabaseP->get_status_rujukan($records[$i]['id'], "4");

				$dir_rujukan = $master_direktorat[0]['names'];
				$kalimat_rujukan = "";
				for($x =0; $x< count($info_desk_reply); $x++){
					$kalimat_rujukan .= "". $info_desk_reply[$x]['dt'].' ('.$info_desk_reply[$x]['name'].")\n";
					$kalimat_rujukan .= $info_desk_reply[$x]['message']."\n";
				}
				
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $dir_rujukan);
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $kalimat_rujukan);
				
				
				$status = '';
				$waktu_penyelesaian = '';
				
				if(count($status_rujukan) > 0)
				{
					$status = ($status_rujukan[0]['status'])?'Sudah TL':'Belum TL';
					$waktu_penyelesaian = $status_rujukan[0]['waktu_penyelesaian'];
				}
				
				
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $status);
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $waktu_penyelesaian);
			}
			else
			{
				$col_idx++;
				$col_idx++;
				$col_idx++;
				$col_idx++;
			}
			
			if((int)$records[$i]['direktorat5'])
			{
				$master_direktorat = $this->DatabaseP->get_info_direktorat($records[$i]['direktorat5']);
                $info_desk_reply = $this->DatabaseP->get_info_desk_reply($records[$i]['direktorat5'], $records[$i]['id']);
				$status_rujukan =  $this->DatabaseP->get_status_rujukan($records[$i]['id'], "5");

				$dir_rujukan = $master_direktorat[0]['names'];
				$kalimat_rujukan = "";
				for($x =0; $x< count($info_desk_reply); $x++){
					$kalimat_rujukan .= "". $info_desk_reply[$x]['dt'].' ('.$info_desk_reply[$x]['name'].")\n";
					$kalimat_rujukan .= $info_desk_reply[$x]['message']."\n";
				}
				
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $dir_rujukan);
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $kalimat_rujukan);
				
				
				$status = '';
				$waktu_penyelesaian = '';
				
				if(count($status_rujukan) > 0)
				{
					$status = ($status_rujukan[0]['status'])?'Sudah TL':'Belum TL';
					$waktu_penyelesaian = $status_rujukan[0]['waktu_penyelesaian'];
				}
				
				
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $status);
				$col_idx++;
				$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $waktu_penyelesaian);
			}
			else
			{
				$col_idx++;
				$col_idx++;
				$col_idx++;
				$col_idx++;
			}
			
			$col_idx++;
			$worksheet->setCellValueByColumnAndRow($col_idx, $start_index, $records[$i]['fb_isi']);
			
			
			
			
			
			$start_index++;
		}
		
		$styleArray = array(
			'borders' => array(
				'allBorders' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				),
			),
		);
		
		if(count($records))
			$worksheet->getStyle('A10:BE'.($start_index - 1))->applyFromArray($styleArray);
		
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        
        header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$file_out); 
		header('Cache-Control: max-age=0');
        $writer->save('php://output');
		
	}
	
	
	
}