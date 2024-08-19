<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\NamedRange;

class Excels extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ExcelsModel');
    }

    public function index()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);

        $filename = 'simple';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function download_lapsing()
    {
        //$inputTgl1  	= date("Y-m-d", strtotime($this->input->post('tgl1')));
        //$inputTgl2 		= date("Y-m-d", strtotime($this->input->post('tgl2')));
        $inputTgl1      = $this->input->post('tgl1');
        $inputTgl2         = $this->input->post('tgl2');

        $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template01_lapsing_1.xls'));

        for ($sheet = 0; $sheet < 1; $sheet++) {
            if ($sheet == 0) {
                $worksheet = $spreadsheet->getSheetByName('Lapsing');
            } else {
                $worksheet = $spreadsheet->getSheetByName('Medsos');
            }

            $print_p5           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
            $worksheet->getCell('P5')->setValue($print_p5);

            $print_r9           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
            $worksheet->getCell('R9')->setValue($print_r9);
            $this->print_lapsing_body($sheet, $worksheet);
        }

        if ($this->session->city == 'PUSAT')
            $inputKota         = $this->input->post('kota');
        else
            $inputKota        = $this->session->city;

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=Lapsing_' . $inputKota . '_' . $inputTgl1 . ' s.d ' . $inputTgl2 . '.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function download_lapsing_gender()
    {
        //$inputTgl1  	= date("Y-m-d", strtotime($this->input->post('tgl1')));
        //$inputTgl2 		= date("Y-m-d", strtotime($this->input->post('tgl2')));
        $inputTgl1      = $this->input->post('tgl1');
        $inputTgl2         = $this->input->post('tgl2');
        $inputgender         = $this->input->post('gender');

        $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template01_lapsing_gender.xls'));

        $maxSheet = 2;
        $nameSheet1 = 'Lapsing_laki';
        if ($inputgender != 'ALL') {
            $nameSheet1 = 'Lapsing';
            $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template01_lapsing_1.xls'));
            if ($inputgender == 'L') {
                $maxSheet = 1;
            }
            if ($inputgender == 'P') {
                $maxSheet = 1;
            }
        }


        for ($sheet = 0; $sheet < $maxSheet; $sheet++) {
            if ($sheet == 0) {
                $worksheet = $spreadsheet->getSheetByName($nameSheet1);
            } else if ($sheet == 1) {
                $worksheet = $spreadsheet->getSheetByName('Lapsing_perempuan');
            } else {
                $worksheet = $spreadsheet->getSheetByName('Medsos');
            }

            $print_p5           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
            $worksheet->getCell('P5')->setValue($print_p5);

            $print_r9           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
            $worksheet->getCell('R9')->setValue($print_r9);
            $this->print_lapsing_body($sheet, $worksheet);
        }

        if ($this->session->city == 'PUSAT')
            $inputKota         = $this->input->post('kota');
        else
            $inputKota        = $this->session->city;

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=Lapsing_gender_' . $inputgender . '_' . $inputKota . '_' . $inputTgl1 . ' s.d ' . $inputTgl2 . '.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function download_lapsing_komoditas()
    {
        //$inputTgl1  	= date("Y-m-d", strtotime($this->input->post('tgl1')));
        //$inputTgl2 		= date("Y-m-d", strtotime($this->input->post('tgl2')));
        $inputTgl1      = $this->input->post('tgl1');
        $inputTgl2         = $this->input->post('tgl2');

        if ($this->input->post('formType') == "KOMODITAS") {
            $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template01_adm_komoditas.xls'));
        } else if ($this->input->post('formType') == "YANBLIK") {
            $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template01_adm_yanblik.xls'));
        } else if ($this->input->post('formType') == "GENDER") {
            $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template01_adm_gender.xls'));
        }

        $worksheet = $spreadsheet->getActiveSheet();
        $print_p5           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
        $worksheet->getCell('P5')->setValue($print_p5);

        $print_r9           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
        $worksheet->getCell('R9')->setValue($print_r9);
        $this->print_lapsing_body(0, $worksheet);


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=Lapsing_' . $this->input->post('formType') . '.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function print_lapsing_body($sheet, &$worksheet)
    {
        $formType          = $this->input->post('formType');

        if ($this->session->city == 'PUSAT')
            $inputKota         = $this->input->post('kota');
        else
            $inputKota        = $this->session->city;

        $inputGender     = $this->input->post('gender');
        $inputKategori     = $this->input->post('kategori');

        $inputTgl1      = $this->input->post('tgl1');
        $inputTgl2         = $this->input->post('tgl2');
        //$inputJenis 	= $formType;
        $inputJenis        = $this->input->post('jenis');

        $data = $this->Report->get_data_lapsing($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
        // print_r($data);
        // die;

        $worksheet->fromArray($data['produk'], NULL, 'C17');
        $worksheet->fromArray($data['mekanisme'], NULL, 'K17');
        $worksheet->fromArray($data['profesi'], NULL, 'AI17');
        $worksheet->fromArray($data['klasifikasi'], NULL, 'AA17');

        $worksheet->fromArray($data['farmakologi'], NULL, 'C37');
        $worksheet->fromArray($data['mutu'], NULL, 'K37');
        $worksheet->fromArray($data['legalitas'], NULL, 'S37');
        $worksheet->fromArray($data['penandaan'], NULL, 'AA37');
        $worksheet->fromArray($data['info_lain'], NULL, 'AI37');
        $worksheet->fromArray($data['info_umum'], NULL, 'AQ37');

        $worksheet->getCell('R10')->setValue($data['jml_data']);
        $worksheet->getCell('R11')->setValue('Informasi: ' . $data['jml_i']);
        $worksheet->getCell('T11')->setValue('Pengaduan: ' . $data['jml_p']);
    }

    public function print_lapsing_body_($sheet, &$worksheet)
    {
        $formType          = $this->input->post('formType');
        if (($formType == 'SP4N') || ($formType == 'PPID')) {
            $inputJenis     = $formType;
        } else {
            $inputJenis     = '';
        }

        if ($this->session->city == 'PUSAT')
            $inputKota         = $this->input->post('kota');
        else
            $inputKota        = $this->session->city;

        $inputGender     = $this->input->post('gender');
        $inputKategori     = $this->input->post('kategori');

        if (($formType == 'SP4N') || ($formType == 'PPID')) {
            $inputJenis     = $formType;
        } else {
            $inputJenis     = '';
        }

        $inputTgl1      = date("Y-m-d", strtotime($this->input->post('tgl1')));
        $inputTgl2         = date("Y-m-d", strtotime($this->input->post('tgl2')));
        $jumlah_kolom       = 7;

        for ($inputType = 1; $inputType <= 10; $inputType++) {
            //echo $inputType;
            if ($inputType == '1') {

                $total_data_                = $this->Report->get_total_data_($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $total                      = $total_data_['0']['total'];
                $raw_data_lapsing              = $this->Report->get_data_kelompok_jenis_pengaduan($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $this->cetak_data($worksheet, "B", 17, $raw_data_lapsing, $total);
            } else if ($inputType == '2') {
                $total_data_                = $this->Report->get_total_data_($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $raw_data_lapsing              = $this->Report->get_data_kelompok_mekanisme_menjawab($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $total                      = $total_data_['0']['total'];
                $this->cetak_data($worksheet, "J", 17, $raw_data_lapsing, $total);
            } else if ($inputType == '3') {
                $total_data_                = $this->Report->get_total_data_($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $raw_data_lapsing              = $this->Report->get_data_jenis_profesi_pengadu($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $total                      = $total_data_['0']['total'];
                $this->cetak_data($worksheet, "AH", 17, $raw_data_lapsing, $total);
            } else if ($inputType == '4') {
                $total_data_                = $this->Report->get_total_data_($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $raw_data_lapsing              = $this->Report->get_data_kelompok_informasi_produk($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $total                      = $total_data_['0']['total'];
                $this->cetak_data($worksheet, "Z", 17, $raw_data_lapsing, $total);
            } else if ($inputType == '5') {
                $total_data_                = $this->Report->get_total_data_per_klasifikasi($sheet, $formType, 'Farmakologi', $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $raw_data_lapsing              = $this->Report->get_data_kelompok_farmakologi($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $total                      = $total_data_['0']['total'];
                $this->cetak_data($worksheet, "B", 33, $raw_data_lapsing, $total);
            } else if ($inputType == '6') {
                $total_data_                = $this->Report->get_total_data_per_klasifikasi($sheet, $formType, 'Mutu', $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $raw_data_lapsing          = $this->Report->get_data_kelompok_mutu($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $total                      = $total_data_['0']['total'];
                $this->cetak_data($worksheet, "J", 33, $raw_data_lapsing, $total);
            } else if ($inputType == '7') {
                $total_data_                = $this->Report->get_total_data_per_klasifikasi($sheet, $formType, 'Legalitas', $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $raw_data_lapsing          = $this->Report->get_data_kelompok_legalitas($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $total                      = $total_data_['0']['total'];
                $this->cetak_data($worksheet, "R", 33, $raw_data_lapsing, $total);
            } else if ($inputType == '8') {
                $total_data_                = $this->Report->get_total_data_per_klasifikasi($sheet, $formType, 'Penandaan', $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $raw_data_lapsing          = $this->Report->get_data_kelompok_penandaan($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $total                      = $total_data_['0']['total'];
                $this->cetak_data($worksheet, "Z", 33, $raw_data_lapsing, $total);
            } else if ($inputType == '9') {
                $total_data_                = $this->Report->get_total_data_per_klasifikasi($sheet, $formType, 'Informasi lain ttg Produk', $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $raw_data_lapsing          = $this->Report->get_data_kelompok_info_lain_produk($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $total                      = $total_data_['0']['total'];
                $this->cetak_data($worksheet, "AH", 33, $raw_data_lapsing, $total);
            } else if ($inputType == '10') {
                $total_data_                = $this->Report->get_total_data_per_klasifikasi($sheet, $formType, 'Info Umum', $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $raw_data_lapsing          = $this->Report->get_data_kelompok_info_umum($sheet, $formType, $inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputJenis, $inputGender);
                $total                      = $total_data_['0']['total'];
                $this->cetak_data($worksheet, "AP", 33, $raw_data_lapsing, $total);
            }
        }
    }

    public function cetak_data(&$worksheet, $start_coll_default, $start_index, $raw_data_lapsing, $total)
    {
        //$start_index    = 17;
        for ($i = 0; $i < count($raw_data_lapsing); $i++) {
            $start_coll         = $start_coll_default;
            $loop               = 0;
            for ($loop = 0; $loop < 7; $loop++) {
                $cnt_p  = ($raw_data_lapsing[$i]['cnt_p'] == '' ? 0 : $raw_data_lapsing[$i]['cnt_p']);
                $cnt_i  = ($raw_data_lapsing[$i]['cnt_i'] == '' ? 0 : $raw_data_lapsing[$i]['cnt_i']);

                $cnt    = $cnt_p + $cnt_i;
                if ($total > 0) {
                    $persen_p   = $cnt_p / intval($total);
                    $persen_i   = $cnt_i / intval($total);
                    $persen     = $cnt / intval($total);
                } else {
                    $persen_p = 0;
                    $persen_i = 0;
                    $persen = 0;
                }
                if ($loop == 0) {
                    $worksheet->getCell($start_coll . $start_index)->setValue($raw_data_lapsing[$i]['name']);
                }
                if ($loop == 1) {
                    $worksheet->getCell($start_coll . $start_index)->setValue($cnt_i);
                }
                if ($loop == 2) {
                    $worksheet->getCell($start_coll . $start_index)->setValue($persen_i);
                }
                if ($loop == 3) {
                    $worksheet->getCell($start_coll . $start_index)->setValue($cnt_p);
                }
                if ($loop == 4) {
                    $worksheet->getCell($start_coll . $start_index)->setValue($persen_p);
                }
                if ($loop == 5) {
                    $worksheet->getCell($start_coll . $start_index)->setValue($cnt);
                }
                if ($loop == 6) {
                    $worksheet->getCell($start_coll . $start_index)->setValue($persen);
                }
                $start_coll++;
            }
            $start_index++;
        }
    }

    //Tab databases
    public function download_databases()
    {
        //$inputTgl1  		= date("Y-m-d", strtotime($this->input->post('tgl1')));
        //$inputTgl2 			= date("Y-m-d", strtotime($this->input->post('tgl2')));
        $inputTgl1        = convert_date1($this->input->post('tgl1'));
        $inputTgl2        = convert_date1($this->input->post('tgl2'));
        $reportType       = $this->input->post('type');
        $filename         = "";

        if ($reportType == '1') {
            $filename = "DatabaseHarian_" . $inputTgl1 . "_" . $inputTgl2;
            //$spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_laporan_databases.xls'));
            $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_database_rujukan.xls'));
            $worksheet = $spreadsheet->getSheetByName('Database harian');

            $worksheet->getCell('A4')->setValue('DATABASES PENGADUAN KONSUMEN');
            $print_a5           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
            $worksheet->getCell('A5')->setValue($print_a5);
            $this->print_databases_body($worksheet);
        } else if ($reportType == '2') {
            $filename = "DatabaseRujukan_" . $inputTgl1 . "_" . $inputTgl2;
            $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_laporan_rujukan.xls'));
            $worksheet = $spreadsheet->getSheetByName('Database harian');

            $worksheet->getCell('A4')->setValue('DATABASES PENGADUAN KONSUMEN');
            $print_a5           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
            $worksheet->getCell('A5')->setValue($print_a5);
            $this->print_databases_body($worksheet);
        } else if ($reportType == '3') {
            $filename = "DatabaseResume_" . $inputTgl1 . "_" . $inputTgl2;
            $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_laporan_resume.xls'));
            $worksheet = $spreadsheet->getSheetByName('Laporan harian');

            $this->print_databases_body($worksheet);
        } elseif ($reportType == '4') {
            $filename = "DatabaseYanblik_" . $inputTgl1 . "_" . $inputTgl2;
            $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_laporan_databases.xls'));
            $worksheet = $spreadsheet->getSheetByName('Database harian');

            $worksheet->getCell('A4')->setValue('DATABASES YANBLIK');
            $print_a5           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
            $worksheet->getCell('A5')->setValue($print_a5);
            $this->print_databases_body($worksheet);
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    /* Database Rujukan */
    public function download_databases2()
    {
        //$inputTgl1  		= date("Y-m-d", strtotime($this->input->post('tgl1')));
        //$inputTgl2 			= date("Y-m-d", strtotime($this->input->post('tgl2')));
        $inputTgl1          = convert_date1($this->input->post('tgl1'));
        $inputTgl2             = convert_date1($this->input->post('tgl2'));
        $reportType         = $this->input->post('type');
        $filename           = "";

        if ($reportType == '1') {
            $filename = "DatabaseRujukan_" . $inputTgl1 . "_" . $inputTgl2;
            $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_database_rujukan.xls'));
            //$worksheet = $spreadsheet->getSheetByName('Database Rujukan');
            $worksheet = $spreadsheet->getSheet(0);

            $worksheet->getCell('A4')->setValue('DATABASE LAYANAN');
            $print_a5           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
            $worksheet->getCell('A5')->setValue($print_a5);
            $this->print_databases_rujukan($worksheet);
        } else if ($reportType == '2') {
            $filename = "DatabaseRujukan_" . $inputTgl1 . "_" . $inputTgl2;
            $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_database_rujukan.xls'));
            //$worksheet = $spreadsheet->getSheetByName('Database Rujukan');
            $worksheet = $spreadsheet->getSheet(0);

            $worksheet->getCell('A4')->setValue('DATABASE RUJUKAN');
            $print_a5           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
            $worksheet->getCell('A5')->setValue($print_a5);
            $this->print_databases_rujukan($worksheet);
        } else if ($reportType == '3') {
            $filename = "DatabaseYanblik_" . $inputTgl1 . "_" . $inputTgl2;
            $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_database_rujukan.xls'));
            //$worksheet = $spreadsheet->getSheetByName('Database Rujukan');
            $worksheet = $spreadsheet->getSheet(0);

            $worksheet->getCell('A4')->setValue('DATABASE YANBLIK');
            $print_a5           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
            $worksheet->getCell('A5')->setValue($print_a5);
            $this->print_databases_rujukan($worksheet);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function download_databases_offline()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);
        $inputTgl1          = convert_date1($this->input->get('tgl1'));
        $inputTgl2             = convert_date1($this->input->get('tgl2'));

        $filename = "c:\\xampp\\tmp\\DatabaseHarian_" . $inputTgl1 . "_" . $inputTgl2;
        $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_laporan_databases.xls'));
        $worksheet = $spreadsheet->getSheetByName('Database harian');

        $worksheet->getCell('A4')->setValue('DATABASES PENGADUAN KONSUMEN');
        $print_a5           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
        $worksheet->getCell('A5')->setValue($print_a5);

        $inputKota = '';
        $inputKategori = '';
        $inputDatasource = '';

        $data_pengaduan_konsumen    = $this->DatabaseM->get_data_pengaduan_konsumen($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, '', '', '');

        $this->build_databases_body($worksheet, "A", 10, $data_pengaduan_konsumen);


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        //$writer->save('php://output');
        $writer->save($filename . '.xlsx');
    }

    public function print_databases_body(&$worksheet)
    {
        //$inputTgl1  		= date("Y-m-d", strtotime($this->input->post('tgl1')));
        //$inputTgl2 			= date("Y-m-d", strtotime($this->input->post('tgl2')));
        $inputTgl1          = convert_date1($this->input->post('tgl1'));
        $inputTgl2             = convert_date1($this->input->post('tgl2'));
        if ($this->session->city == 'PUSAT')
            $inputKota             = $this->input->post('kota');
        else
            $inputKota             = $this->session->city;
        $inputDatasource     = $this->input->post('datasource');
        $inputKategori         = $this->input->post('kategori');
        $inputStatusRujukan = $this->input->post('statusRujukan');
        $reportType         = $this->input->post('type');

        if ($reportType == '1') {
            $data_pengaduan_konsumen    = $this->DatabaseM->get_data_pengaduan_konsumen($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, '', '', '');

            $this->build_databases_body($worksheet, "A", 10, $data_pengaduan_konsumen);
        } else if ($reportType == '4') {
            $data_pengaduan_konsumen    = $this->DatabaseM->get_data_yanblik($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, '', '', '');

            $this->build_databases_body($worksheet, "A", 10, $data_pengaduan_konsumen);
        } else if ($reportType == '3') {
            $data_resume    = $this->DatabaseM->get_data_resume(is_admin(), get_userid(), $inputTgl1, $inputTgl2, $inputKota, '', '', '');

            $this->build_databases_resume_body($worksheet, "A", 10, $data_resume);
        } else if ($reportType == '2') {
            $data_pengaduan_konsumen    = $this->DatabaseM->get_data_rujukan($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, $inputStatusRujukan);
            $output_basic = $data_pengaduan_konsumen;

            for ($i = 0; $i < count($data_pengaduan_konsumen); $i++) {

                if ($data_pengaduan_konsumen[$i]['direktorat'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat']);
                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat'], $data_pengaduan_konsumen[$i]['id']);

                    $kalimat_rujukan = $master_direktorat[0]['names'] . "\n";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_kalimat_rujukan'] = $kalimat_rujukan;
                } else {
                    $output_basic[$i]['var_kalimat_rujukan'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat2'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat2']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat2'], $data_pengaduan_konsumen[$i]['id']);

                    $kalimat_rujukan = $master_direktorat[0]['names'] . "\n";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_kalimat_rujukan2'] = $kalimat_rujukan;
                } else {
                    $output_basic[$i]['var_kalimat_rujukan2'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat3'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat3']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat3'], $data_pengaduan_konsumen[$i]['id']);

                    $kalimat_rujukan = $master_direktorat[0]['names'] . "\n";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_kalimat_rujukan3'] = $kalimat_rujukan;
                } else {
                    $output_basic[$i]['var_kalimat_rujukan3'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat4'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat4']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat4'], $data_pengaduan_konsumen[$i]['id']);

                    $kalimat_rujukan = $master_direktorat[0]['names'] . "\n";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= '<b>' . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_kalimat_rujukan4'] = $kalimat_rujukan;
                } else {
                    $output_basic[$i]['var_kalimat_rujukan4'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat5'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat5']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat5'], $data_pengaduan_konsumen[$i]['id']);

                    $kalimat_rujukan = $master_direktorat[0]['names'] . "\n";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= '<b>' . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_kalimat_rujukan5'] = $kalimat_rujukan;
                } else {
                    $output_basic[$i]['var_kalimat_rujukan5'] = "-";
                }
            }
            $this->build_databases_rujukan_body($worksheet, "A", 10, $output_basic);
        }
    }

    public function print_databases_rujukan(&$worksheet)
    {
        //$inputTgl1  		= date("Y-m-d", strtotime($this->input->post('tgl1')));
        //$inputTgl2 			= date("Y-m-d", strtotime($this->input->post('tgl2')));
        $inputTgl1          = convert_date1($this->input->post('tgl1'));
        $inputTgl2             = convert_date1($this->input->post('tgl2'));
        if ($this->session->city == 'PUSAT')
            $inputKota             = $this->input->post('kota');
        else
            $inputKota             = $this->session->city;
        $inputDatasource     = $this->input->post('datasource');
        $inputKategori         = $this->input->post('kategori');
        $inputStatusRujukan = $this->input->post('status_rujukan');
        $inputUnitRujukan     = $this->input->post('unit_rujukan');
        $reportType         = $this->input->post('type');

        if ($reportType == '1') {
        } else if ($reportType == '3') {

            $data_pengaduan_konsumen    = $this->DatabaseM->get_data_yanblik2($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, '');
            $output_basic = $data_pengaduan_konsumen;

            for ($i = 0; $i < count($data_pengaduan_konsumen); $i++) {

                if ($data_pengaduan_konsumen[$i]['direktorat'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat']);
                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat'], $data_pengaduan_konsumen[$i]['id']);

                    $dir_rujukan = $master_direktorat[0]['names'];
                    $kalimat_rujukan = "";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_dir_rujukan'] = $dir_rujukan;
                    $output_basic[$i]['var_kalimat_rujukan'] = $kalimat_rujukan;

                    $output_basic[$i]['var_status_rujukan'] = "-";
                    $output_basic[$i]['var_waktu_rujukan'] = "-";
                } else {
                    $output_basic[$i]['var_dir_rujukan'] = "-";
                    $output_basic[$i]['var_kalimat_rujukan'] = "-";
                    $output_basic[$i]['var_status_rujukan'] = "-";
                    $output_basic[$i]['var_waktu_rujukan'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat2'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat2']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat2'], $data_pengaduan_konsumen[$i]['id']);

                    $dir_rujukan = $master_direktorat[0]['names'];
                    $kalimat_rujukan = "";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_dir_rujukan2'] = $dir_rujukan;
                    $output_basic[$i]['var_kalimat_rujukan2'] = $kalimat_rujukan;
                    $output_basic[$i]['var_status_rujukan2'] = "-";
                    $output_basic[$i]['var_waktu_rujukan2'] = "-";
                } else {
                    $output_basic[$i]['var_dir_rujukan2'] = "-";
                    $output_basic[$i]['var_kalimat_rujukan2'] = "-";
                    $output_basic[$i]['var_status_rujukan2'] = "-";
                    $output_basic[$i]['var_waktu_rujukan2'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat3'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat3']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat3'], $data_pengaduan_konsumen[$i]['id']);

                    $dir_rujukan = $master_direktorat[0]['names'];
                    $kalimat_rujukan = "";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_dir_rujukan3'] = $dir_rujukan;
                    $output_basic[$i]['var_kalimat_rujukan3'] = $kalimat_rujukan;
                    $output_basic[$i]['var_status_rujukan3'] = "-";
                    $output_basic[$i]['var_waktu_rujukan3'] = "-";
                } else {
                    $output_basic[$i]['var_dir_rujukan3'] = "-";
                    $output_basic[$i]['var_kalimat_rujukan3'] = "-";
                    $output_basic[$i]['var_status_rujukan3'] = "-";
                    $output_basic[$i]['var_waktu_rujukan3'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat4'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat4']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat4'], $data_pengaduan_konsumen[$i]['id']);

                    $dir_rujukan = $master_direktorat[0]['names'];
                    $kalimat_rujukan = "";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= '<b>' . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_dir_rujukan4'] = $dir_rujukan;
                    $output_basic[$i]['var_kalimat_rujukan4'] = $kalimat_rujukan;
                    $output_basic[$i]['var_status_rujukan4'] = "-";
                    $output_basic[$i]['var_waktu_rujukan4'] = "-";
                } else {
                    $output_basic[$i]['var_dir_rujukan4'] = "-";
                    $output_basic[$i]['var_kalimat_rujukan4'] = "-";
                    $output_basic[$i]['var_status_rujukan4'] = "-";
                    $output_basic[$i]['var_waktu_rujukan4'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat5'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat5']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat5'], $data_pengaduan_konsumen[$i]['id']);

                    $dir_rujukan = $master_direktorat[0]['names'];
                    $kalimat_rujukan = "";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= '<b>' . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_dir_rujukan5'] = $dir_rujukan;
                    $output_basic[$i]['var_kalimat_rujukan5'] = $kalimat_rujukan;
                    $output_basic[$i]['var_status_rujukan5'] = "-";
                    $output_basic[$i]['var_waktu_rujukan5'] = "-";
                } else {
                    $output_basic[$i]['var_dir_rujukan5'] = "-";
                    $output_basic[$i]['var_kalimat_rujukan5'] = "-";
                    $output_basic[$i]['var_status_rujukan5'] = "-";
                    $output_basic[$i]['var_waktu_rujukan5'] = "-";
                }
            }
            $this->build_databases_rujukan_body2($worksheet, "A", 10, $output_basic);
        } else if ($reportType == '2') {
            $data_pengaduan_konsumen    = $this->DatabaseM->get_data_rujukan2($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, $inputStatusRujukan, $inputUnitRujukan);
            $output_basic = $data_pengaduan_konsumen;

            for ($i = 0; $i < count($data_pengaduan_konsumen); $i++) {

                if ($data_pengaduan_konsumen[$i]['direktorat'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat']);
                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat'], $data_pengaduan_konsumen[$i]['id']);

                    $dir_rujukan = $master_direktorat[0]['names'];
                    $kalimat_rujukan = "";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_dir_rujukan'] = $dir_rujukan;
                    $output_basic[$i]['var_kalimat_rujukan'] = $kalimat_rujukan;

                    $output_basic[$i]['var_status_rujukan'] = "-";
                    $output_basic[$i]['var_waktu_rujukan'] = "-";
                } else {
                    $output_basic[$i]['var_dir_rujukan'] = "-";
                    $output_basic[$i]['var_kalimat_rujukan'] = "-";
                    $output_basic[$i]['var_status_rujukan'] = "-";
                    $output_basic[$i]['var_waktu_rujukan'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat2'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat2']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat2'], $data_pengaduan_konsumen[$i]['id']);

                    $dir_rujukan = $master_direktorat[0]['names'];
                    $kalimat_rujukan = "";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_dir_rujukan2'] = $dir_rujukan;
                    $output_basic[$i]['var_kalimat_rujukan2'] = $kalimat_rujukan;
                    $output_basic[$i]['var_status_rujukan2'] = "-";
                    $output_basic[$i]['var_waktu_rujukan2'] = "-";
                } else {
                    $output_basic[$i]['var_dir_rujukan2'] = "-";
                    $output_basic[$i]['var_kalimat_rujukan2'] = "-";
                    $output_basic[$i]['var_status_rujukan2'] = "-";
                    $output_basic[$i]['var_waktu_rujukan2'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat3'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat3']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat3'], $data_pengaduan_konsumen[$i]['id']);

                    $dir_rujukan = $master_direktorat[0]['names'];
                    $kalimat_rujukan = "";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_dir_rujukan3'] = $dir_rujukan;
                    $output_basic[$i]['var_kalimat_rujukan3'] = $kalimat_rujukan;
                    $output_basic[$i]['var_status_rujukan3'] = "-";
                    $output_basic[$i]['var_waktu_rujukan3'] = "-";
                } else {
                    $output_basic[$i]['var_dir_rujukan3'] = "-";
                    $output_basic[$i]['var_kalimat_rujukan3'] = "-";
                    $output_basic[$i]['var_status_rujukan3'] = "-";
                    $output_basic[$i]['var_waktu_rujukan3'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat4'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat4']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat4'], $data_pengaduan_konsumen[$i]['id']);

                    $dir_rujukan = $master_direktorat[0]['names'];
                    $kalimat_rujukan = "";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= '<b>' . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_dir_rujukan4'] = $dir_rujukan;
                    $output_basic[$i]['var_kalimat_rujukan4'] = $kalimat_rujukan;
                    $output_basic[$i]['var_status_rujukan4'] = "-";
                    $output_basic[$i]['var_waktu_rujukan4'] = "-";
                } else {
                    $output_basic[$i]['var_dir_rujukan4'] = "-";
                    $output_basic[$i]['var_kalimat_rujukan4'] = "-";
                    $output_basic[$i]['var_status_rujukan4'] = "-";
                    $output_basic[$i]['var_waktu_rujukan4'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat5'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat5']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat5'], $data_pengaduan_konsumen[$i]['id']);

                    $dir_rujukan = $master_direktorat[0]['names'];
                    $kalimat_rujukan = "";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= '<b>' . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_dir_rujukan5'] = $dir_rujukan;
                    $output_basic[$i]['var_kalimat_rujukan5'] = $kalimat_rujukan;
                    $output_basic[$i]['var_status_rujukan5'] = "-";
                    $output_basic[$i]['var_waktu_rujukan5'] = "-";
                } else {
                    $output_basic[$i]['var_dir_rujukan5'] = "-";
                    $output_basic[$i]['var_kalimat_rujukan5'] = "-";
                    $output_basic[$i]['var_status_rujukan5'] = "-";
                    $output_basic[$i]['var_waktu_rujukan5'] = "-";
                }
            }
            $this->build_databases_rujukan_body2($worksheet, "A", 10, $output_basic);
        }
    }

    public function build_databases_body(&$worksheet, $start_coll_default, $start_index, $raw_data)
    {
        // print_r($raw_data);
        // die;
        for ($i = 0; $i < count($raw_data); $i++) {
            $start_coll         = $start_coll_default;
            for ($loop = 0; $loop <= 34; $loop++) {

                if ($loop == 0) {
                    $printed  = ($i + 1);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 1) {
                    $printed  = ($raw_data[$i]['trackid'] == '' ? '' : $raw_data[$i]['trackid']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 2) {
                    $printed  = ($raw_data[$i]['iden_nama'] == '' ? '' : $raw_data[$i]['iden_nama']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 3) {
                    $printed  = ($raw_data[$i]['iden_alamat'] == '' ? '' : $raw_data[$i]['iden_alamat']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 4) {
                    $printed  = ($raw_data[$i]['iden_telp'] == '' ? '' : $raw_data[$i]['iden_telp']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 5) {
                    $printed  = ($raw_data[$i]['iden_email'] == '' ? '' : $raw_data[$i]['iden_email']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 6) {
                    $printed  = ($raw_data[$i]['name'] == '' ? '' : $raw_data[$i]['name']); //pekerjaan
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 7) {
                    $printed  = ($raw_data[$i]['iden_jk'] == '' ? '' : $raw_data[$i]['iden_jk']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 8) {
                    if ($raw_data[$i]['info'] == "I") {
                        $printed  = ($raw_data[$i]['detail_laporan'] == '' ? '' : $raw_data[$i]['detail_laporan']);
                    } elseif ($raw_data[$i]['info'] == "P") {
                        $printed  = "";
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 9) {
                    if ($raw_data[$i]['info'] == "I") {
                        $printed  = "";
                    } elseif ($raw_data[$i]['info'] == "P") {
                        $printed  = ($raw_data[$i]['jawaban'] == '' ? '' : $raw_data[$i]['jawaban']);
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 10) {
                    $printed  = ($raw_data[$i]['jenis'] == '' ? '' : $raw_data[$i]['jenis']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 11) {
                    $printed  = ($raw_data[$i]['keterangan'] == '' ? '' : $raw_data[$i]['keterangan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 12) {
                    $printed  = ($raw_data[$i]['jenis_komoditi'] == '' ? '' : $raw_data[$i]['jenis_komoditi']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 13) {
                    $printed  = ($raw_data[$i]['jenis'] == '' ? '' : $raw_data[$i]['jenis']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 14) {
                    $printed  = ($raw_data[$i]['penerima'] == '' ? '' : $raw_data[$i]['penerima']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 15) {
                    $printed  = ($raw_data[$i]['isu_topik'] == '' ? '' : $raw_data[$i]['isu_topik']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 16) {
                    $printed  = ($raw_data[$i]['klasifikasi'] == '' ? '' : $raw_data[$i]['klasifikasi']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 17) {
                    $printed  = ($raw_data[$i]['subklasifikasi'] == '' ? '' : $raw_data[$i]['subklasifikasi']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 18) {
                    $printed  = ($raw_data[$i]['sarana'] == '' ? '' : $raw_data[$i]['sarana']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 19) {
                    $printed  = ($raw_data[$i]['waktu'] == '' ? '' : $raw_data[$i]['waktu']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 20) {
                    $printed  = ($raw_data[$i]['shift'] == '' ? '' : $raw_data[$i]['shift']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 21) {
                    $printed  = ($raw_data[$i]['petugas_entry'] == '' ? '' : $raw_data[$i]['petugas_entry']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                /*if($loop == 21){
                    if($raw_data[$i]['rujuk_tl_hr'] != ""){
                        if($raw_data[$i]['rujuk_tl_hr'] < 3){
                            $printed  = 'Ya';
                        } else {
                            $printed  = 'Tidak';
                        }
                    } else {
                        $printed  = 'Tidak';
                    }
                    $worksheet->getCell($start_coll.$start_index)->setValue($printed);
                }
                if($loop == 22){
                    if($raw_data[$i]['rujuk_tl_hr'] != ""){
                        if($raw_data[$i]['rujuk_tl_hr'] < 3){
                            $printed  = 'Tidak';
                        } else {
                            $printed  = 'Ya';
                        }
                    } else {
                        $printed  = 'Ya';
                    }
                    $worksheet->getCell($start_coll.$start_index)->setValue($printed);
                }*/
                if ($loop == 22) {
                    if ($raw_data[$i]['tl']) {
                        $printed  = 'Sudah';
                    } else {
                        $printed  = 'Belum';
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 23) {
                    $printed  = ($raw_data[$i]['verified_date'] == '' ? '' : $raw_data[$i]['verified_date']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 24) {
                    $printed  = ($raw_data[$i]['verificator_name'] == '' ? '' : $raw_data[$i]['verificator_name']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 25) {
                    $printed  = ($raw_data[$i]['tglpengaduan'] == '' ? '' : $raw_data[$i]['tglpengaduan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 26) {
                    $printed  = ($raw_data[$i]['closed_date'] == '' ? '' : $raw_data[$i]['closed_date']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 27) {
                    if ($raw_data[$i]['is_rujuk']) {
                        $printed  = 'Sudah';
                    } else {
                        $printed  = 'Belum';
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 28) {
                    if ($raw_data[$i]['tl']) {
                        $printed  = 'Sudah';
                    } else {
                        $printed  = 'Belum';
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 29) {
                    $printed  = $raw_data[$i]['tl_date'];

                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 30) {
                    if ($raw_data[$i]['fb']) {
                        $printed  = 'Sudah';
                    } else {
                        $printed  = 'Belum';
                    }

                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 31) {
                    $printed  = $raw_data[$i]['fb_date'];

                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 32) {
                    $printed  = $raw_data[$i]['kota'];

                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 33) {
                    // if ($raw_data[$i]['hk'] <= $raw_data[$i]['sla'])
                    //     $printed  = "Y";
                    // else
                    //     $printed  = "N";
                    //target penyelesaian
                    $printed  = $raw_data[$i]['sla'];
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }

                if ($loop == 34) {
                    $printed  = $raw_data[$i]['waktu_layanan'];

                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                // if ($loop == 34) {
                //     //
                //     $printed  = $raw_data[$i]['waktu_layanan'];

                //     $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                // }
                // if ($loop == 34) {
                //     $printed  = $raw_data[$i]['waktu_layanan'];

                //     $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                // }
                $start_coll++;
            }
            $start_index++;
        }
      
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
        );

        if (count($raw_data))
            $worksheet->getStyle('A11:AH' . ($start_index - 1))->applyFromArray($styleArray);
    }

    public function build_databases_rujukan_body(&$worksheet, $start_coll_default, $start_index, $raw_data)
    {
        for ($i = 0; $i < count($raw_data); $i++) {
            $start_coll         = $start_coll_default;
            for ($loop = 0; $loop <= 26; $loop++) {

                if ($loop == 0) {
                    $printed  = ($i + 1);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 1) {
                    $printed  = ($raw_data[$i]['identitas_konsumen'] == '' ? '' : $raw_data[$i]['identitas_konsumen']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 2) {
                    if ($raw_data[$i]['info'] == "I") {
                        $printed  = ($raw_data[$i]['detail_laporan'] == '' ? '' : $raw_data[$i]['detail_laporan']);
                    } elseif ($raw_data[$i]['info'] == "P") {
                        $printed  = "";
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 3) {
                    if ($raw_data[$i]['info'] == "I") {
                        $printed  = "";
                    } elseif ($raw_data[$i]['info'] == "P") {
                        $printed  = ($raw_data[$i]['detail_laporan'] == '' ? '' : $raw_data[$i]['detail_laporan']);
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 4) {
                    if ($raw_data[$i]['info'] == "I") {
                        $printed  = ($raw_data[$i]['jawaban'] == '' ? '' : $raw_data[$i]['jawaban']);
                    } elseif ($raw_data[$i]['info'] == "P") {
                        $printed  = "";
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 5) {
                    if ($raw_data[$i]['info'] == "I") {
                        $printed  = "";
                    } elseif ($raw_data[$i]['info'] == "P") {
                        $printed  = ($raw_data[$i]['jawaban'] == '' ? '' : $raw_data[$i]['jawaban']);
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 6) {
                    $printed  = ($raw_data[$i]['keterangan'] == '' ? '' : $raw_data[$i]['keterangan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 7) {
                    $printed  = ($raw_data[$i]['jenis_komoditi'] == '' ? '' : $raw_data[$i]['jenis_komoditi']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 8) {
                    $printed  = ($raw_data[$i]['kode_petugas'] == '' ? '' : $raw_data[$i]['kode_petugas']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 9) {
                    $printed  = ($raw_data[$i]['isu_topik'] == '' ? '' : $raw_data[$i]['isu_topik']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 10) {
                    $printed  = ($raw_data[$i]['klasifikasi'] == '' ? '' : $raw_data[$i]['klasifikasi']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 11) {
                    $printed  = ($raw_data[$i]['subklasifikasi'] == '' ? '' : $raw_data[$i]['subklasifikasi']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 12) {
                    $printed  = ($raw_data[$i]['pekerjaan'] == '' ? '' : $raw_data[$i]['pekerjaan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 13) {
                    $printed  = ($raw_data[$i]['sarana'] == '' ? '' : $raw_data[$i]['sarana']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 14) {
                    $printed  = ($raw_data[$i]['waktu'] == '' ? '' : $raw_data[$i]['waktu']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 15) {
                    $printed  = ($raw_data[$i]['shift'] == '' ? '' : $raw_data[$i]['shift']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 16) {
                    $printed  = ($raw_data[$i]['penerima'] == '' ? '' : $raw_data[$i]['penerima']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 17) {
                    if ($raw_data[$i]['rujuk_tl_hr'] != "") {
                        if ($raw_data[$i]['rujuk_tl_hr'] < 3) {
                            $printed  = 'Ya';
                        } else {
                            $printed  = 'Tidak';
                        }
                    } else {
                        $printed  = 'Tidak';
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 18) {
                    if ($raw_data[$i]['rujuk_tl_hr'] != "") {
                        if ($raw_data[$i]['rujuk_tl_hr'] < 3) {
                            $printed  = 'Tidak';
                        } else {
                            $printed  = 'Ya';
                        }
                    } else {
                        $printed  = 'Ya';
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 19) {
                    if ($raw_data[$i]['tl'] == "1") {
                        $printed  = 'Sudah';
                    } else {
                        $printed  = 'Belum';
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 20) {
                    $printed  = ($raw_data[$i]['sla'] == '' ? '' : $raw_data[$i]['sla']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 21) {
                    $printed  = ($raw_data[$i]['var_kalimat_rujukan'] == '' ? '' : $raw_data[$i]['var_kalimat_rujukan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 22) {
                    $printed  = ($raw_data[$i]['var_kalimat_rujukan2'] == '' ? '' : $raw_data[$i]['var_kalimat_rujukan2']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 23) {
                    $printed  = ($raw_data[$i]['var_kalimat_rujukan3'] == '' ? '' : $raw_data[$i]['var_kalimat_rujukan3']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 24) {
                    $printed  = ($raw_data[$i]['var_kalimat_rujukan4'] == '' ? '' : $raw_data[$i]['var_kalimat_rujukan4']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 25) {
                    $printed  = ($raw_data[$i]['var_kalimat_rujukan5'] == '' ? '' : $raw_data[$i]['var_kalimat_rujukan5']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 26) {
                    //$printed  = ($raw_data[$i]['fb'] && !empty($raw_data[$i]['fb_date'])?'Y':'N');
                    $printed  = $raw_data[$i]['fb_isi'];
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }



                $start_coll++;
            }
            $worksheet->getRowDimension($start_index)->setRowHeight(-1);
            $start_index++;
        }
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
        );

        if (count($raw_data)) {
            $worksheet->getStyle('A10:AA' . ($start_index - 1))->applyFromArray($styleArray);
            $worksheet->getStyle('A10:AA' . ($start_index - 1))->getAlignment()->setWrapText(true);
        }
    }

    public function build_databases_rujukan_body2(&$worksheet, $start_coll_default, $start_index, $raw_data)
    {
        for ($i = 0; $i < count($raw_data); $i++) {
            $start_coll         = $start_coll_default;
            for ($loop = 0; $loop <= 56; $loop++) {

                if ($loop == 0) {
                    $printed  = ($i + 1);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 1) {
                    $printed  = ($raw_data[$i]['trackid'] == '' ? '' : $raw_data[$i]['trackid']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 2) {
                    $printed  = ($raw_data[$i]['iden_nama'] == '' ? '' : $raw_data[$i]['iden_nama']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 3) {
                    $printed  = ($raw_data[$i]['iden_alamat'] == '' ? '' : $raw_data[$i]['iden_alamat']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 4) {
                    $printed  = ($raw_data[$i]['iden_telp'] == '' ? '' : $raw_data[$i]['iden_telp']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 5) {
                    $printed  = ($raw_data[$i]['iden_email'] == '' ? '' : $raw_data[$i]['iden_email']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 6) {
                    $printed  = ($raw_data[$i]['pekerjaan'] == '' ? '' : $raw_data[$i]['pekerjaan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 7) {
                    $printed  = ($raw_data[$i]['iden_jk'] == '' ? '' : $raw_data[$i]['iden_jk']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 8) {
                    $printed  = ($raw_data[$i]['detail_laporan'] == '' ? '' : $raw_data[$i]['detail_laporan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 9) {
                    $printed  = ($raw_data[$i]['jawaban'] == '' ? '' : $raw_data[$i]['jawaban']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 10) {
                    $printed  = ($raw_data[$i]['info'] == '' ? '' : $raw_data[$i]['info']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }

                if ($loop == 11) {
                    $printed  = nl2br($raw_data[$i]['keterangan'] == '' ? '' : $raw_data[$i]['keterangan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 12) {
                    $printed  = trim($raw_data[$i]['jenis_komoditi'] == '' ? '' : $raw_data[$i]['jenis_komoditi']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 13) {
                    $printed  = ($raw_data[$i]['jenis'] == '' ? '' : $raw_data[$i]['jenis']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 14) {
                    $printed  = ($raw_data[$i]['penerima'] == '' ? '' : $raw_data[$i]['penerima']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 15) {
                    $printed  = ($raw_data[$i]['isu_topik'] == '' ? '' : $raw_data[$i]['isu_topik']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 16) {
                    $printed  = ($raw_data[$i]['klasifikasi'] == '' ? '' : $raw_data[$i]['klasifikasi']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 17) {
                    $printed  = ($raw_data[$i]['subklasifikasi'] == '' ? '' : $raw_data[$i]['subklasifikasi']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }

                if ($loop == 18) {
                    $printed  = ($raw_data[$i]['sarana'] == '' ? '' : $raw_data[$i]['sarana']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 19) {
                    $printed  = ($raw_data[$i]['waktu'] == '' ? '' : $raw_data[$i]['waktu']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 20) {
                    $printed  = ($raw_data[$i]['shift'] == '' ? '' : $raw_data[$i]['shift']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 21) {
                    $printed  = ($raw_data[$i]['kode_petugas'] == '' ? '' : $raw_data[$i]['kode_petugas']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 22) {
                    $printed  = ($raw_data[$i]['is_verified']) ? 'Sudah' : 'Belum';
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 23) {
                    $printed  = ($raw_data[$i]['verified_date'] == '' ? '' : $raw_data[$i]['verified_date']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 24) {
                    $printed  = ($raw_data[$i]['verificator_name'] == '' ? '' : $raw_data[$i]['verificator_name']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 25) {
                    $printed  = ($raw_data[$i]['tglpengaduan'] == '' ? '' : $raw_data[$i]['tglpengaduan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 26) {
                    $printed  = ($raw_data[$i]['closed_date'] == '' ? '' : $raw_data[$i]['closed_date']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 27) {
                    $printed  = '';
                    switch ($raw_data[$i]['status']) {
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
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 28) {
                    $printed  = ($raw_data[$i]['tl']) ? 'Sudah' : 'Belum';
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 29) {
                    $printed  = ($raw_data[$i]['tl_date'] == '' ? '' : $raw_data[$i]['tl_date']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 30) {
                    $printed  = ($raw_data[$i]['fb']) ? 'Sudah' : 'Belum';
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 31) {
                    $printed  = ($raw_data[$i]['fb_date'] == '' ? '' : $raw_data[$i]['fb_date']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 32) {
                    $printed  = ($raw_data[$i]['kota'] == '' ? '' : $raw_data[$i]['kota']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 33) {
                    $printed  = ($raw_data[$i]['sla'] == '' ? '' : $raw_data[$i]['sla']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 34) {
                    $printed  = ($raw_data[$i]['hk'] == '' ? '' : $raw_data[$i]['hk']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 35) {
                    $printed  = ($raw_data[$i]['hk'] <= $raw_data[$i]['sla']) ? 'Y' : 'N';
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }

                if ($loop == 36) {
                    $printed  = ($raw_data[$i]['var_dir_rujukan'] == '' ? '' : $raw_data[$i]['var_dir_rujukan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 37) {
                    $printed  = ($raw_data[$i]['var_kalimat_rujukan'] == '' ? '' : $raw_data[$i]['var_kalimat_rujukan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 38) {
                    $printed  = ($raw_data[$i]['var_status_rujukan'] == '' ? '' : $raw_data[$i]['var_status_rujukan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 39) {
                    $printed  = ($raw_data[$i]['var_waktu_rujukan'] == '' ? '' : $raw_data[$i]['var_waktu_rujukan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }

                if ($loop == 40) {
                    $printed  = ($raw_data[$i]['var_dir_rujukan2'] == '' ? '' : $raw_data[$i]['var_dir_rujukan2']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 41) {
                    $printed  = ($raw_data[$i]['var_kalimat_rujukan2'] == '' ? '' : $raw_data[$i]['var_kalimat_rujukan2']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 42) {
                    $printed  = ($raw_data[$i]['var_status_rujukan2'] == '' ? '' : $raw_data[$i]['var_status_rujukan2']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 43) {
                    $printed  = ($raw_data[$i]['var_waktu_rujukan2'] == '' ? '' : $raw_data[$i]['var_waktu_rujukan2']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }

                if ($loop == 44) {
                    $printed  = ($raw_data[$i]['var_dir_rujukan3'] == '' ? '' : $raw_data[$i]['var_dir_rujukan3']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 45) {
                    $printed  = ($raw_data[$i]['var_kalimat_rujukan3'] == '' ? '' : $raw_data[$i]['var_kalimat_rujukan3']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 46) {
                    $printed  = ($raw_data[$i]['var_status_rujukan3'] == '' ? '' : $raw_data[$i]['var_status_rujukan3']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 47) {
                    $printed  = ($raw_data[$i]['var_waktu_rujukan3'] == '' ? '' : $raw_data[$i]['var_waktu_rujukan3']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }

                if ($loop == 48) {
                    $printed  = ($raw_data[$i]['var_dir_rujukan4'] == '' ? '' : $raw_data[$i]['var_dir_rujukan4']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 49) {
                    $printed  = ($raw_data[$i]['var_kalimat_rujukan4'] == '' ? '' : $raw_data[$i]['var_kalimat_rujukan4']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 50) {
                    $printed  = ($raw_data[$i]['var_status_rujukan4'] == '' ? '' : $raw_data[$i]['var_status_rujukan4']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 51) {
                    $printed  = ($raw_data[$i]['var_waktu_rujukan4'] == '' ? '' : $raw_data[$i]['var_waktu_rujukan4']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }

                if ($loop == 52) {
                    $printed  = ($raw_data[$i]['var_dir_rujukan5'] == '' ? '' : $raw_data[$i]['var_dir_rujukan5']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 53) {
                    $printed  = ($raw_data[$i]['var_kalimat_rujukan5'] == '' ? '' : $raw_data[$i]['var_kalimat_rujukan5']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 54) {
                    $printed  = ($raw_data[$i]['var_status_rujukan5'] == '' ? '' : $raw_data[$i]['var_status_rujukan5']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 55) {
                    $printed  = ($raw_data[$i]['var_waktu_rujukan5'] == '' ? '' : $raw_data[$i]['var_waktu_rujukan5']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    //$worksheet->getStyle($start_coll.$start_index)->getAlignment()->setWrapText(true);
                }
                if ($loop == 56) {
                    $printed  = ($raw_data[$i]['fb_isi'] == '' ? '' : $raw_data[$i]['fb_isi']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                    $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setWrapText(true);
                }



                $start_coll++;
            }
            $worksheet->getRowDimension($start_index)->setRowHeight(-1);
            $start_index++;
        }
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
        );

        if (count($raw_data)) {
            $worksheet->getStyle('A10:BE' . ($start_index - 1))->applyFromArray($styleArray);
            $worksheet->getStyle('A10:BE' . ($start_index - 1))->getAlignment()->setWrapText(true);
        }
    }

    public function build_databases_resume_body(&$worksheet, $start_coll_default, $start_index, $raw_data)
    {
        for ($i = 0; $i < count($raw_data); $i++) {
            $start_coll         = $start_coll_default;
            for ($loop = 0; $loop < 7; $loop++) {

                if ($loop == 0) {
                    $printed  = ($i + 1);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 1) {
                    $printed  = ($raw_data[$i]['identitas_konsumen'] == '' ? '' : $raw_data[$i]['identitas_konsumen']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 2) {
                    if ($raw_data[$i]['info'] == "I") {
                        $printed  = ($raw_data[$i]['prod_masalah'] == '' ? '' : $raw_data[$i]['prod_masalah']);
                    } elseif ($raw_data[$i]['info'] == "P") {
                        $printed  = "";
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 3) {
                    if ($raw_data[$i]['info'] == "I") {
                        $printed  = "";
                    } elseif ($raw_data[$i]['info'] == "P") {
                        $printed  = ($raw_data[$i]['prod_masalah'] == '' ? '' : $raw_data[$i]['prod_masalah']);
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 4) {
                    if ($raw_data[$i]['info'] == "I") {
                        $printed  = ($raw_data[$i]['jawaban'] == '' ? '' : $raw_data[$i]['jawaban']);
                    } elseif ($raw_data[$i]['info'] == "P") {
                        $printed  = "";
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 5) {
                    if ($raw_data[$i]['info'] == "I") {
                        $printed  = "";
                    } elseif ($raw_data[$i]['info'] == "P") {
                        $printed  = ($raw_data[$i]['jawaban'] == '' ? '' : $raw_data[$i]['jawaban']);
                    }
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                if ($loop == 6) {
                    $printed  = ($raw_data[$i]['keterangan'] == '' ? '' : $raw_data[$i]['keterangan']);
                    $worksheet->getCell($start_coll . $start_index)->setValue($printed);
                }
                $start_coll++;
            }
            $start_index++;
        }
    }

    public function download_layanan()
    {
        $inputTgl1          = $this->input->get('tgl1');
        $inputTgl2             = $this->input->get('tgl2');
        if ($this->session->city == 'PUSAT')
            $inputKota             = $this->input->get('kota');
        elseif ($this->session->city == 'UNIT TEKNIS')
            $inputKota             = 'UNIT TEKNIS';
        else
            $inputKota        = $this->session->city;

        $menu                 = $this->input->get('menu');
        $tl                 = $this->input->get('tl');
        $fb                 = $this->input->get('fb');
        $status             = $this->input->get('status');
        $sla                 = $this->input->get('sla');

        $inputKategori         = $this->input->get('kategori');
        $inputDatasource     = $this->input->get('jenis');
        $inputLength        = '';
        $inputStart            = '';
        $inputSearch        = $this->input->get('keyword');
        $inputField            = $this->input->get('field');

        $filename = "Layanan_" . $inputTgl1 . "_" . $inputTgl2;
        $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_layanan_new.xls'));
        $worksheet = $spreadsheet->getSheet(0);

        $records = $this->DatabaseM->get_data_pengaduan_konsumen($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, $inputLength, $inputStart, $inputSearch, $inputField, $menu, $status, $tl, $fb, $sla);

        $start_index = 10;

        for ($i = 0; $i < count($records); $i++) {
            $worksheet->getCell('A' . $start_index)->setValue($i + 1);
            $worksheet->getCell('B' . $start_index)->setValue($records[$i]['trackid']);
            $worksheet->getCell('C' . $start_index)->setValue($records[$i]['iden_nama']);
            $worksheet->getCell('D' . $start_index)->setValue($records[$i]['iden_alamat']);
            $worksheet->getCell('E' . $start_index)->setValue($records[$i]['iden_telp']);
            $worksheet->getCell('F' . $start_index)->setValue($records[$i]['iden_email']);
            $worksheet->getCell('G' . $start_index)->setValue($records[$i]['detail_laporan']);
            $worksheet->getCell('H' . $start_index)->setValue($records[$i]['jawaban']);
            $worksheet->getCell('I' . $start_index)->setValue($records[$i]['info']);
            $worksheet->getCell('J' . $start_index)->setValue($records[$i]['keterangan']);
            $worksheet->getCell('K' . $start_index)->setValue($records[$i]['jenis_komoditi']);
            $worksheet->getCell('L' . $start_index)->setValue($records[$i]['penerima']);
            $worksheet->getCell('M' . $start_index)->setValue($records[$i]['isu_topik']);
            $worksheet->getCell('N' . $start_index)->setValue($records[$i]['klasifikasi']);
            $worksheet->getCell('O' . $start_index)->setValue($records[$i]['subklasifikasi']);
            $worksheet->getCell('P' . $start_index)->setValue($records[$i]['pekerjaan']);
            $worksheet->getCell('Q' . $start_index)->setValue($records[$i]['sarana']);
            $worksheet->getCell('R' . $start_index)->setValue($records[$i]['waktu']);
            $worksheet->getCell('S' . $start_index)->setValue($records[$i]['shift']);
            $worksheet->getCell('T' . $start_index)->setValue($records[$i]['petugas_entry']);
            $worksheet->getCell('U' . $start_index)->setValue($records[$i]['sla']);
            $worksheet->getCell('V' . $start_index)->setValue(($records[$i]['tl']) ? 'Sudah TL' : 'Belum TL');
            $worksheet->getCell('W' . $start_index)->setValue($records[$i]['waktu_layanan']);

            $pemenuhan = '';
            if ($records[$i]['tl']) {
                if ($records[$i]['waktu_layanan'] <= $records[$i]['sla'])
                    $pemenuhan = 'Memenuhi SLA';
                else
                    $pemenuhan = 'Melebihi SLA';
            }

            $worksheet->getCell('X' . $start_index)->setValue($pemenuhan);

            if ((int)$records[$i]['direktorat']) {
                $master_direktorat = $this->DatabaseM->get_info_direktorat($records[$i]['direktorat']);
                $info_desk_reply = $this->DatabaseM->get_info_desk_reply($records[$i]['direktorat'], $records[$i]['id']);
                $status_rujukan =  $this->DatabaseM->get_status_rujukan($records[$i]['id'], "1");

                $dir_rujukan = $master_direktorat[0]['names'];
                $kalimat_rujukan = "";
                for ($x = 0; $x < count($info_desk_reply); $x++) {
                    $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                    $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                }

                $worksheet->getCell('Y' . $start_index)->setValue($dir_rujukan);
                $worksheet->getCell('Z' . $start_index)->setValue($kalimat_rujukan);

                $status = '';
                $waktu_penyelesaian = '';

                if (count($status_rujukan) > 0) {
                    $status = ($status_rujukan[0]['status']) ? 'Sudah TL' : 'Belum TL';
                    $waktu_penyelesaian = $status_rujukan[0]['waktu_penyelesaian'];
                }

                $worksheet->getCell('AA' . $start_index)->setValue($status);
                $worksheet->getCell('AB' . $start_index)->setValue($waktu_penyelesaian);
            }

            if ((int)$records[$i]['direktorat2']) {
                $master_direktorat = $this->DatabaseM->get_info_direktorat($records[$i]['direktorat2']);
                $info_desk_reply = $this->DatabaseM->get_info_desk_reply($records[$i]['direktorat2'], $records[$i]['id']);
                $status_rujukan =  $this->DatabaseM->get_status_rujukan($records[$i]['id'], "2");

                $dir_rujukan = $master_direktorat[0]['names'];
                $kalimat_rujukan = "";
                for ($x = 0; $x < count($info_desk_reply); $x++) {
                    $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                    $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                }

                $worksheet->getCell('AC' . $start_index)->setValue($dir_rujukan);
                $worksheet->getCell('AD' . $start_index)->setValue($kalimat_rujukan);

                $status = '';
                $waktu_penyelesaian = '';

                if (count($status_rujukan) > 0) {
                    $status = ($status_rujukan[0]['status']) ? 'Sudah TL' : 'Belum TL';
                    $waktu_penyelesaian = $status_rujukan[0]['waktu_penyelesaian'];
                }

                $worksheet->getCell('AE' . $start_index)->setValue($status);
                $worksheet->getCell('AF' . $start_index)->setValue($waktu_penyelesaian);
            }

            if ((int)$records[$i]['direktorat3']) {
                $master_direktorat = $this->DatabaseM->get_info_direktorat($records[$i]['direktorat3']);
                $info_desk_reply = $this->DatabaseM->get_info_desk_reply($records[$i]['direktorat3'], $records[$i]['id']);
                $status_rujukan =  $this->DatabaseM->get_status_rujukan($records[$i]['id'], "3");

                $dir_rujukan = $master_direktorat[0]['names'];
                $kalimat_rujukan = "";
                for ($x = 0; $x < count($info_desk_reply); $x++) {
                    $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                    $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                }

                $worksheet->getCell('AG' . $start_index)->setValue($dir_rujukan);
                $worksheet->getCell('AH' . $start_index)->setValue($kalimat_rujukan);

                $status = '';
                $waktu_penyelesaian = '';

                if (count($status_rujukan) > 0) {
                    $status = ($status_rujukan[0]['status']) ? 'Sudah TL' : 'Belum TL';
                    $waktu_penyelesaian = $status_rujukan[0]['waktu_penyelesaian'];
                }

                $worksheet->getCell('AI' . $start_index)->setValue($status);
                $worksheet->getCell('AJ' . $start_index)->setValue($waktu_penyelesaian);
            }

            if ((int)$records[$i]['direktorat4']) {
                $master_direktorat = $this->DatabaseM->get_info_direktorat($records[$i]['direktorat4']);
                $info_desk_reply = $this->DatabaseM->get_info_desk_reply($records[$i]['direktorat4'], $records[$i]['id']);
                $status_rujukan =  $this->DatabaseM->get_status_rujukan($records[$i]['id'], "4");

                $dir_rujukan = $master_direktorat[0]['names'];
                $kalimat_rujukan = "";
                for ($x = 0; $x < count($info_desk_reply); $x++) {
                    $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                    $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                }

                $worksheet->getCell('AK' . $start_index)->setValue($dir_rujukan);
                $worksheet->getCell('AL' . $start_index)->setValue($kalimat_rujukan);

                $status = '';
                $waktu_penyelesaian = '';

                if (count($status_rujukan) > 0) {
                    $status = ($status_rujukan[0]['status']) ? 'Sudah TL' : 'Belum TL';
                    $waktu_penyelesaian = $status_rujukan[0]['waktu_penyelesaian'];
                }

                $worksheet->getCell('AM' . $start_index)->setValue($status);
                $worksheet->getCell('AN' . $start_index)->setValue($waktu_penyelesaian);
            }

            if ((int)$records[$i]['direktorat5']) {
                $master_direktorat = $this->DatabaseM->get_info_direktorat($records[$i]['direktorat5']);
                $info_desk_reply = $this->DatabaseM->get_info_desk_reply($records[$i]['direktorat5'], $records[$i]['id']);
                $status_rujukan =  $this->DatabaseM->get_status_rujukan($records[$i]['id'], "5");

                $dir_rujukan = $master_direktorat[0]['names'];
                $kalimat_rujukan = "";
                for ($x = 0; $x < count($info_desk_reply); $x++) {
                    $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                    $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                }

                $worksheet->getCell('AO' . $start_index)->setValue($dir_rujukan);
                $worksheet->getCell('AP' . $start_index)->setValue($kalimat_rujukan);

                $status = '';
                $waktu_penyelesaian = '';

                if (count($status_rujukan) > 0) {
                    $status = ($status_rujukan[0]['status']) ? 'Sudah TL' : 'Belum TL';
                    $waktu_penyelesaian = $status_rujukan[0]['waktu_penyelesaian'];
                }

                $worksheet->getCell('AQ' . $start_index)->setValue($status);
                $worksheet->getCell('AR' . $start_index)->setValue($waktu_penyelesaian);
            }

            $worksheet->getCell('AS' . $start_index)->setValue($records[$i]['fb_isi']);

            $start_index++;
        }

        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
        );

        if (count($records))
            $worksheet->getStyle('A10:AS' . ($start_index - 1))->applyFromArray($styleArray);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=Layanan_' . $inputKota . '_' . $inputTgl1 . '_s.d_' . $inputTgl2 . '.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function download_layanan_()
    {
        $inputTgl1          = $this->input->get('tgl1');
        $inputTgl2             = $this->input->get('tgl2');
        if ($this->session->city == 'PUSAT')
            $inputKota             = $this->input->get('kota');
        else
            $inputKota        = $this->session->city;

        $menu                 = $this->input->get('menu');
        $tl                 = $this->input->get('tl');
        $fb                 = $this->input->get('fb');
        $status             = $this->input->get('status');
        $sla                 = $this->input->get('sla');

        $inputKategori         = $this->input->get('kategori');
        $inputDatasource     = $this->input->get('jenis');
        $inputLength        = '';
        $inputStart            = '';
        $inputSearch        = $this->input->get('keyword');
        $inputField            = $this->input->get('field');

        $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_layanan.xls'));
        $worksheet = $spreadsheet->getSheetByName('Layanan');

        $print_tgl           = "TANGGAL " . format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
        //$print_tgl           = $inputTgl1." s/d ".$inputTgl2;
        $worksheet->getCell('A2')->setValue($print_tgl);


        $records = $this->DatabaseM->get_data_pengaduan_konsumen($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, $inputLength, $inputStart, $inputSearch, $inputField, $menu, $status, $tl, $fb, $sla);

        //exit;

        $start_index = 6;

        for ($i = 0; $i < count($records); $i++) {
            $worksheet->getCell('A' . $start_index)->setValue($i + 1);
            $worksheet->getCell('B' . $start_index)->setValue($records[$i]['identitas_konsumen']);

            if ($records[$i]['info'] == 'I') {
                $worksheet->getCell('C' . $start_index)->setValue($records[$i]['detail_laporan']);
                $worksheet->getCell('E' . $start_index)->setValue($records[$i]['jawaban']);
            } elseif ($records[$i]['info'] == 'P') {
                $worksheet->getCell('D' . $start_index)->setValue($records[$i]['detail_laporan']);
                $worksheet->getCell('F' . $start_index)->setValue($records[$i]['jawaban']);
            }

            $worksheet->getCell('G' . $start_index)->setValue($records[$i]['keterangan']);
            $worksheet->getCell('H' . $start_index)->setValue($records[$i]['jenis_komoditi']);
            $worksheet->getCell('I' . $start_index)->setValue($records[$i]['penerima']);
            $worksheet->getCell('J' . $start_index)->setValue($records[$i]['isu_topik']);
            $worksheet->getCell('K' . $start_index)->setValue($records[$i]['klasifikasi']);
            $worksheet->getCell('L' . $start_index)->setValue($records[$i]['subklasifikasi']);
            $worksheet->getCell('M' . $start_index)->setValue($records[$i]['pekerjaan']);
            $worksheet->getCell('N' . $start_index)->setValue($records[$i]['sarana']);
            $worksheet->getCell('O' . $start_index)->setValue($records[$i]['waktu']);
            $worksheet->getCell('P' . $start_index)->setValue($records[$i]['shift']);
            $worksheet->getCell('Q' . $start_index)->setValue($records[$i]['petugas_entry']);

            $status = $records[$i]['status'];
            if ($status == '0')
                $status = 'Open';
            elseif ($status == '2')
                $status = 'Replied';
            elseif ($status == '3')
                $status = 'Closed';


            $worksheet->getCell('R' . $start_index)->setValue($status);

            $tl = $records[$i]['tl'];
            if ($tl)
                $tl = 'Y';
            else
                $tl = 'N';


            $worksheet->getCell('S' . $start_index)->setValue($tl);

            $fb = $records[$i]['fb'];
            if ($fb)
                $fb = 'Y';
            else
                $fb = 'N';

            $worksheet->getCell('T' . $start_index)->setValue($fb);

            $is_verified = $records[$i]['is_verified'];
            if ($is_verified)
                $is_verified = 'Y';
            else
                $is_verified = 'N';

            $worksheet->getCell('U' . $start_index)->setValue($is_verified);


            /*if(!empty($records[$i]['rujuk_tl_hr']) && $records[$i]['rujuk_tl_hr'] <= 3)
			{
				$worksheet->getCell('R'.$start_index)->setValue("Ya");
				$worksheet->getCell('S'.$start_index)->setValue("Tidak");
			}
			else
			{
				$worksheet->getCell('R'.$start_index)->setValue("Tidak");
				$worksheet->getCell('S'.$start_index)->setValue("Ya");
			}*/

            $start_index++;
        }

        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
        );

        if (count($records))
            $worksheet->getStyle('A6:U' . ($start_index - 1))->applyFromArray($styleArray);


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=Layanan_' . $menu . '_' . $inputKota . '_' . $inputTgl1 . '_s.d_' . $inputTgl2 . '.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function download_monbalai()
    {
        $inputTgl1          = $this->input->get('tgl1');
        $inputTgl2             = $this->input->get('tgl2');


        $inputLength        = '';
        $inputStart            = '';


        $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_monbalai.xls'));
        $worksheet = $spreadsheet->getSheet(0);

        $print_tgl           = "TANGGAL " . format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
        //$print_tgl           = $inputTgl1." s/d ".$inputTgl2;
        $worksheet->getCell('A2')->setValue($print_tgl);


        $records = $this->DatabaseM->get_data_monbalai($inputTgl1, $inputTgl2);

        //exit;

        $start_index = 5;

        for ($i = 0; $i < count($records); $i++) {
            $worksheet->getCell('A' . $start_index)->setValue($i + 1);
            $worksheet->getCell('B' . $start_index)->setValue($records[$i]['nama_balai']);

            $total = $records[$i]['total'];
            $jml_closed = $records[$i]['sts_closed'];
            $jml_open = $total - $jml_closed;
            $tl = $records[$i]['tl'];
            $blm_tl = ($total - $tl);
            $rata = $records[$i]['rata'];
            $sla_yes = $records[$i]['sla_yes'];

            $sla = '-';
            if ($total > 0)
                $sla = number_format($sla_yes * 100 / $total, 2);

            $worksheet->getCell('C' . $start_index)->setValue($total);
            $worksheet->getCell('D' . $start_index)->setValue($jml_open);
            $worksheet->getCell('E' . $start_index)->setValue($jml_closed);
            $worksheet->getCell('F' . $start_index)->setValue($blm_tl);

            $worksheet->getCell('G' . $start_index)->setValue($tl);
            $worksheet->getCell('H' . $start_index)->setValue($rata);
            $worksheet->getCell('I' . $start_index)->setValue($sla);



            $start_index++;
        }

        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
        );

        if (count($records))
            $worksheet->getStyle('A6:I' . ($start_index - 1))->applyFromArray($styleArray);


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=Monitoring_balai_' . $inputTgl1 . '_s.d_' . $inputTgl2 . '.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function download_rujukan()
    {
        $inputTgl1          = convert_date1($this->input->post('tgl1'));
        $inputTgl2             = convert_date1($this->input->post('tgl2'));
        $reportType         = $this->input->post('type');
        $filename           = "";

        if ($reportType == '1') { //rujukan masuk
            $filename = "RujukanMasuk_" . $inputTgl1 . "_" . $inputTgl2;
            $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template_laporan_rujukan.xls'));
            $worksheet = $spreadsheet->getSheetByName('Database harian');

            $worksheet->getCell('A4')->setValue('DATABASES PENGADUAN KONSUMEN');
            $print_a5           = format_date_indo($inputTgl1) . " s/d " . format_date_indo($inputTgl2);
            $worksheet->getCell('A5')->setValue($print_a5);
            $this->print_rujukan_body($worksheet);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function print_rujukan_body(&$worksheet)
    {
        $inputTgl1          = convert_date1($this->input->post('tgl1'));
        $inputTgl2             = convert_date1($this->input->post('tgl2'));
        $inputKota             = $this->input->post('kota');
        $inputDatasource     = $this->input->post('datasource');
        $inputKategori         = $this->input->post('kategori');
        $inputStatusRujukan = $this->input->post('statusRujukan');
        $reportType         = $this->input->post('type');

        if ($reportType == '1') { //rujukan masuk
            $data_pengaduan_konsumen    = $this->DatabaseM->get_data_rujukan($inputTgl1, $inputTgl2, $inputKota, $inputKategori, $inputDatasource, $inputStatusRujukan, $reportType);
            $output_basic = $data_pengaduan_konsumen;

            for ($i = 0; $i < count($data_pengaduan_konsumen); $i++) {

                if ($data_pengaduan_konsumen[$i]['direktorat'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat']);
                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat'], $data_pengaduan_konsumen[$i]['id']);

                    $kalimat_rujukan = $master_direktorat[0]['names'] . "\n";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_kalimat_rujukan'] = $kalimat_rujukan;
                } else {
                    $output_basic[$i]['var_kalimat_rujukan'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat2'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat2']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat2'], $data_pengaduan_konsumen[$i]['id']);

                    $kalimat_rujukan = $master_direktorat[0]['names'] . "\n";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_kalimat_rujukan2'] = $kalimat_rujukan;
                } else {
                    $output_basic[$i]['var_kalimat_rujukan2'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat3'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat3']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat3'], $data_pengaduan_konsumen[$i]['id']);

                    $kalimat_rujukan = $master_direktorat[0]['names'] . "\n";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= "" . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_kalimat_rujukan3'] = $kalimat_rujukan;
                } else {
                    $output_basic[$i]['var_kalimat_rujukan3'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat4'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat4']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat4'], $data_pengaduan_konsumen[$i]['id']);

                    $kalimat_rujukan = $master_direktorat[0]['names'] . "\n";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= '<b>' . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_kalimat_rujukan4'] = $kalimat_rujukan;
                } else {
                    $output_basic[$i]['var_kalimat_rujukan4'] = "-";
                }

                if ($data_pengaduan_konsumen[$i]['direktorat5'] != '0') {
                    $master_direktorat = $this->DatabaseM->get_info_direktorat($data_pengaduan_konsumen[$i]['direktorat5']);

                    $info_desk_reply = $this->DatabaseM->get_info_desk_reply($data_pengaduan_konsumen[$i]['direktorat5'], $data_pengaduan_konsumen[$i]['id']);

                    $kalimat_rujukan = $master_direktorat[0]['names'] . "\n";
                    for ($x = 0; $x < count($info_desk_reply); $x++) {
                        $kalimat_rujukan .= '<b>' . $info_desk_reply[$x]['dt'] . ' (' . $info_desk_reply[$x]['name'] . ")\n";
                        $kalimat_rujukan .= $info_desk_reply[$x]['message'] . "\n";
                    }
                    $output_basic[$i]['var_kalimat_rujukan5'] = $kalimat_rujukan;
                } else {
                    $output_basic[$i]['var_kalimat_rujukan5'] = "-";
                }
            }
            $this->build_databases_rujukan_body($worksheet, "A", 10, $output_basic);
        }
    }

    public function download_lapsing_new()
    {
        //$inputTgl1  	= date("Y-m-d", strtotime($this->input->post('tgl1')));
        //$inputTgl2 		= date("Y-m-d", strtotime($this->input->post('tgl2')));
        $inputTgl1      = $this->input->post('tgl1');
        $inputTgl2         = $this->input->post('tgl2');
        $formType          = $this->input->post('formType');

        $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template01_lapsing_new.xls'));

        $worksheet = $spreadsheet->getSheetByName('Lapsing');

        $print_a6           = "PERIODE " . format_date_indo($inputTgl1) . " S/D " . format_date_indo($inputTgl2);
        $worksheet->getCell('A6')->setValue($print_a6);

        //get data
        if ($this->session->city == 'PUSAT')
            $inputKota         = $this->input->post('kota');
        else
            $inputKota        = $this->session->city;

        $raw_data = $this->Report->get_data_lapsing_ppid(1, $formType, $inputTgl1, $inputTgl2, $inputKota);

        $start_index    = 10;

        $totalJml       = 0;
        $totalRata       = 0;
        $totalDikabulTotal       = 0;
        $totalDikabulSebagian       = 0;
        $totalditolak       = 0;
        $totaldikecualikan       = 0;
        $totalBelumdokumentasi       = 0;
        $totalTidakdikuasai       = 0;

        $lengthRow = count($raw_data);

        for ($i = 0; $i < $lengthRow; $i++) {
            $start_coll     = "B";

            $printed = $raw_data[$i]['bln'];
            $worksheet->getCell($start_coll . $start_index)->setValue($printed);
            $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setWrapText(true);
            $start_coll++;

            $printed = $raw_data[$i]['jml'];
            $totalJml += $printed;
            $worksheet->getCell($start_coll . $start_index)->setValue($printed);
            $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
            $start_coll++;

            $printed = $raw_data[$i]['rataPelayanan'];
            $totalRata += $printed;
            $worksheet->getCell($start_coll . $start_index)->setValue(number_format($printed, 2, ',', ''));
            $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
            $start_coll++;

            $printed = $raw_data[$i]['dikabulkansepenuhnya'];
            $totalDikabulTotal += $printed;
            $worksheet->getCell($start_coll . $start_index)->setValue($printed);
            $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
            $start_coll++;

            $printed = $raw_data[$i]['dikabulkansebagian'];
            $totalDikabulSebagian += $printed;
            $worksheet->getCell($start_coll . $start_index)->setValue($printed);
            $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
            $start_coll++;

            $printed = $raw_data[$i]['ditolak'];
            $totalditolak += $printed;
            $worksheet->getCell($start_coll . $start_index)->setValue($printed);
            $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
            $start_coll++;

            $printed = $raw_data[$i]['dikecualikan'];
            $totaldikecualikan += $printed;
            $worksheet->getCell($start_coll . $start_index)->setValue($printed);
            $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
            $start_coll++;

            $printed = $raw_data[$i]['belumdidokumentasikan'];
            $totalBelumdokumentasi += $printed;
            $worksheet->getCell($start_coll . $start_index)->setValue($printed);
            $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
            $start_coll++;

            $printed = $raw_data[$i]['tidakdikuasai'];
            $totalTidakdikuasai += $printed;
            $worksheet->getCell($start_coll . $start_index)->setValue($printed);
            $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');

            $start_index++;
        }

        //print totalan
        $start_coll     = "B";

        $printed = $raw_data[0]['bln'] . ' - ' . $raw_data[($lengthRow - 1)]['bln'];
        $worksheet->getCell($start_coll . $start_index)->setValue($printed);
        $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setWrapText(true);
        $worksheet->getStyle($start_coll . $start_index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('07f5ed');
        $start_coll++;

        $worksheet->getCell($start_coll . $start_index)->setValue($totalJml);
        $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
        $worksheet->getStyle($start_coll . $start_index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('07f5ed');
        $start_coll++;

        $worksheet->getCell($start_coll . $start_index)->setValue(number_format($totalRata, 2, ',', ''));
        $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
        $worksheet->getStyle($start_coll . $start_index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('07f5ed');
        $start_coll++;

        $worksheet->getCell($start_coll . $start_index)->setValue($totalDikabulTotal);
        $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
        $worksheet->getStyle($start_coll . $start_index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('07f5ed');
        $start_coll++;

        $worksheet->getCell($start_coll . $start_index)->setValue($totalDikabulSebagian);
        $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
        $worksheet->getStyle($start_coll . $start_index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('07f5ed');
        $start_coll++;

        $worksheet->getCell($start_coll . $start_index)->setValue($totalditolak);
        $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
        $worksheet->getStyle($start_coll . $start_index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('07f5ed');
        $start_coll++;

        $worksheet->getCell($start_coll . $start_index)->setValue($totaldikecualikan);
        $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
        $worksheet->getStyle($start_coll . $start_index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('07f5ed');
        $start_coll++;

        $worksheet->getCell($start_coll . $start_index)->setValue($totalBelumdokumentasi);
        $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
        $worksheet->getStyle($start_coll . $start_index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('07f5ed');
        $start_coll++;

        $worksheet->getCell($start_coll . $start_index)->setValue($totalTidakdikuasai);
        $worksheet->getStyle($start_coll . $start_index)->getAlignment()->setHorizontal('center');
        $worksheet->getStyle($start_coll . $start_index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('07f5ed');

        //style
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
        );

        $worksheet->getStyle('B10:' . $start_coll . $start_index)->applyFromArray($styleArray);


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=Lapsing_new_' . $inputKota . '_' . $inputTgl1 . ' s.d ' . $inputTgl2 . '.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function upload_file_template_xls()
    {
        $this->load->model('ExcelsModel');

        $path     = APPPATH . 'tempupload/';
        $file    = "";
        $randomid = mt_rand(100000000, 999999999);

        if (isset($_FILES['files'])) {
            $all_files = count($_FILES['files']['tmp_name']);

            for ($i = 0; $i < $all_files; $i++) {
                $file_name = $randomid . '.xlsx'; //$_FILES['files']['name'][$i];
                $file_tmp = $_FILES['files']['tmp_name'][$i];
                $file_type = $_FILES['files']['type'][$i];
                $file_size = $_FILES['files']['size'][$i];
                if ($file_size > 20097152) {
                    $errors[] = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
                }

                $file = $path . $file_name;

                if (!isset($errors)) {
                    move_uploaded_file($file_tmp, $file);
                } else {
                    echo json_encode("Error upload");
                    return;
                }
            }
        } else {
            echo "error kosong";
            die;
        }

        $inputFileType = 'Xlsx';
        $inputFileName = $path . $randomid . '.xlsx';

        /**  Create a new Reader of the type defined in $inputFileType  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        /**  Advise the Reader that we only want to load cell data  **/
        $reader->setReadDataOnly(true);
        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);
        //read excel data 
        $workSheet = $spreadsheet->getSheetByName('Lembar Kerja');

        $dt = date('Y-m-d H:i:s');

        //Identitas Pelapor
        $data_bulk = array();
        $column = 5;
        $eof = false;
        while ($eof == false) {
            $data = array();

            if ($workSheet->getCell('A' . $column)->getValue() == '') {
                $eof = true;
                break;
            } else {
                $data['iden_nama'] = $workSheet->getCell('A' . $column)->getValue();
            }

            $data['iden_jk'] = $workSheet->getCell('B' . $column)->getValue() == '' ? '' : $workSheet->getCell('B' . $column)->getValue();
            $data['iden_instansi'] = $workSheet->getCell('C' . $column)->getValue() == '' ? '' : $workSheet->getCell('C' . $column)->getValue();
            $data['iden_jenis'] = $workSheet->getCell('D' . $column)->getValue() == '' ? '' : $workSheet->getCell('D' . $column)->getValue();
            $data['iden_alamat'] = $workSheet->getCell('E' . $column)->getValue() == '' ? '' : $workSheet->getCell('E' . $column)->getValue();
            $data['iden_email'] = $workSheet->getCell('F' . $column)->getValue() == '' ? '' : $workSheet->getCell('F' . $column)->getValue();
            $data['iden_negara'] = $workSheet->getCell('G' . $column)->getValue() == '' ? '' : $workSheet->getCell('G' . $column)->getValue();
            $data['iden_provinsi'] = $workSheet->getCell('H' . $column)->getValue() == '' ? '' : $workSheet->getCell('H' . $column)->getValue();
            $data['iden_kota'] = $workSheet->getCell('I' . $column)->getValue() == '' ? '' : $workSheet->getCell('I' . $column)->getValue();
            $data['iden_telp'] = $workSheet->getCell('J' . $column)->getValue() == '' ? '' : $workSheet->getCell('J' . $column)->getValue();
            $data['iden_fax'] = $workSheet->getCell('K' . $column)->getValue() == '' ? '' : $workSheet->getCell('K' . $column)->getValue();

            $idGet = $this->ExcelsModel->get_profesi($workSheet->getCell('L' . $column)->getValue());
            $data['iden_profesi'] = $idGet;
            $data['usia'] = $workSheet->getCell('M' . $column)->getValue() == '' ? '' : $workSheet->getCell('M' . $column)->getValue();

            //Identitas Produk
            $data['prod_nama'] = $workSheet->getCell('N' . $column)->getValue() == '' ? '' :  $workSheet->getCell('N' . $column)->getValue();
            $data['prod_generik'] = $workSheet->getCell('O' . $column)->getValue() == '' ? '' :  $workSheet->getCell('O' . $column)->getValue();
            $data['prod_pabrik'] = $workSheet->getCell('P' . $column)->getValue() == '' ? '' :  $workSheet->getCell('P' . $column)->getValue();
            $data['prod_noreg'] = $workSheet->getCell('Q' . $column)->getValue() == '' ? '' :  $workSheet->getCell('Q' . $column)->getValue();
            $data['prod_nobatch'] = $workSheet->getCell('R' . $column)->getValue() == '' ? '' :  $workSheet->getCell('R' . $column)->getValue();
            $data['prod_alamat'] = $workSheet->getCell('S' . $column)->getValue() == '' ? '' :  $workSheet->getCell('S' . $column)->getValue();
            $data['prod_kota'] = $workSheet->getCell('T' . $column)->getValue() == '' ? '' :  $workSheet->getCell('T' . $column)->getValue();
            $data['prod_provinsi'] = $workSheet->getCell('V' . $column)->getValue() == '' ? '' :  $workSheet->getCell('V' . $column)->getValue();
            $data['prod_negara'] = $workSheet->getCell('U' . $column)->getValue() == '' ? '' :  $workSheet->getCell('U' . $column)->getValue();
            $data['prod_kadaluarsa'] = $workSheet->getCell('W' . $column)->getValue() == '' ? '' :  $workSheet->getCell('W' . $column)->getValue();
            $data['prod_diperoleh'] = $workSheet->getCell('X' . $column)->getValue() == '' ? '' :  $workSheet->getCell('X' . $column)->getValue();
            $data['prod_diperoleh_tgl'] = $workSheet->getCell('Y' . $column)->getValue() == '' ? '' :  $workSheet->getCell('Y' . $column)->getValue();
            $data['prod_digunakan_tgl'] = $workSheet->getCell('Z' . $column)->getValue() == '' ? '' :  $workSheet->getCell('Z' . $column)->getValue();

            //Layanan
            $data['isu_topik'] = $workSheet->getCell('AA' . $column)->getValue() == '' ? '' :  $workSheet->getCell('AA' . $column)->getValue();
            $data['prod_masalah'] = $workSheet->getCell('AB' . $column)->getValue() == '' ? '' :  $workSheet->getCell('AB' . $column)->getValue();
            $data['penerima'] = $workSheet->getCell('AC' . $column)->getValue() == '' ? '' :  $workSheet->getCell('AC' . $column)->getValue();

            //Klasifikasi
            $data['info'] = $workSheet->getCell('AE' . $column)->getValue() == 'Pengaduan' ? 'P' :  "I";

            $idGet = $this->ExcelsModel->get_categories($workSheet->getCell('AF' . $column)->getValue());
            $data['kategori'] = $idGet;

            $data['submited_via'] = $workSheet->getCell('AG' . $column)->getValue() == '' ? '' :  $workSheet->getCell('AG' . $column)->getValue();

            $data['jenis'] = $workSheet->getCell('AH' . $column)->getValue() == '' ? '' :  $workSheet->getCell('AH' . $column)->getValue();

            $shift = 0;
            switch ($workSheet->getCell('AI' . $column)->getValue()) {
                case "Shift 1":
                    $shift = 1;
                    break;
                case "Shift 2":
                    $shift = 2;
                    break;
                case "Shift 3":
                    $shift = 3;
                    break;
                default:
                    $shift = 0;
                    break;
            }

            $data['shift'] = $shift;
            $klasifikasi = $workSheet->getCell('AJ' . $column)->getValue() == '' ? '' :  $workSheet->getCell('AJ' . $column)->getValue();

            $klasifikasi_name = $this->ExcelsModel->get_klasifikasi($klasifikasi);
            $data['klasifikasi'] = $klasifikasi_name;
            $idget = $this->ExcelsModel->get_klasifikasi_id($klasifikasi);
            $data['klasifikasi_id'] = $idget;

            $subklasifikasi = $workSheet->getCell('AK' . $column)->getValue() == '' ? '' :  $workSheet->getCell('AK' . $column)->getValue();
            $data['subklasifikasi'] = $subklasifikasi;
            $idget = $this->ExcelsModel->get_subklas($subklasifikasi);
            $data['subklasifikasi_id'] = $idget;

            //tindak lanjut
            $data['is_rujuk'] = $workSheet->getCell('AL'.$column)->getValue() == 'Ya' ? '1' :  '0';
            if($data['is_rujuk'] == '1'){
                $columns = ['AM', 'AO', 'AQ', 'AS', 'AU'];
                $priorities = ['AN', 'AP', 'AR', 'AT', 'AV'];
            
                foreach ($columns as $index => $col) {
                    $direktoratKey = $index == 0 ? 'direktorat' : 'direktorat' . ($index + 1);
                    $data[$direktoratKey] = $workSheet->getCell($col . $column)->getValue() != '' 
                        ? $this->get_direktorat_id($workSheet->getCell($col . $column)->getValue()) 
                        : '0';
                }
            
                foreach ($priorities as $index => $col) {
                    $priorityKey = 'd' . ($index + 1) . '_prioritas';
                    $data[$priorityKey] = $workSheet->getCell($col . $column)->getValue() != '' 
                        ? explode(' ', $workSheet->getCell($col . $column)->getValue())[0] 
                        : '0';
                }
            }
            

            //Jawaban
            $data['jawaban'] = $workSheet->getCell('AW'.$column)->getValue() == '' ? '' :  $workSheet->getCell('AW'.$column)->getValue();
            $data['keterangan'] = $workSheet->getCell('AX'.$column)->getValue() == '' ? '' :  $workSheet->getCell('AX'.$column)->getValue();
            $data['petugas_entry'] = $workSheet->getCell('AY'.$column)->getValue() == '' ? '' :  $workSheet->getCell('AY'.$column)->getValue();
            $data['penjawab'] = $workSheet->getCell('AZ'.$column)->getValue() == '' ? '' :  $workSheet->getCell('AZ'.$column)->getValue();
            $data['answered_via'] = $workSheet->getCell('BA'.$column)->getValue() == '' ? '' :  $workSheet->getCell('BA'.$column)->getValue();

            //default
            $data['owner'] = $this->session->id;
            $data['kota'] = $this->session->city;
            $data['dt'] = $dt;
            $data['tglpengaduan'] = date('Y-m-d');
            
            $data['trackid'] = '';
            $data['waktu'] = date('H:i:s');
            $data['owner_dir'] = $this->session->direktoratid;

            $data['tipe_medsos'] = '';
            if((int)$this->input->post("type") == 2){
                      $data['is_sent'] = '1';
                  } else {
                      $data['is_sent'] = '0';
                  }

                  $data_bulk[] = $data;
                  $column++;
              }
      
            try {
                $prefix = 'PST';
                if($this->session->city != 'PUSAT')
                    $prefix = $this->Balai->get_prefix($this->session->city);

                $this->Draft->save_bulk($data_bulk);

                if((int)$this->input->post("type") == 2){

                    $item_save = array_map(function($item) use($prefix) {
                        $item['is_sent'] = '1';
                        $item['trackid'] = $this->Draft->generate_ticketid($this->session->city,$prefix,date('Y-m-d'));
                        return $item;
                    }, $data_bulk);

                    $this->Ticket->save_bulk($item_save);
                }

                $msg = array('status' => 'S', 'msg' => "Berhasil mengirim upload file!");

            } catch (\Throwable $th) {
                $msg = array('status' => 'F', 'msg' => "Upload Gagal!");
            }

            echo json_encode($msg);
        }

    private function get_direktorat_id($name){
        return $this->db->get_where("desk_direktorat",["name" => $name])->row()->id;
    }

    public function download_template_upload()
    {
        $inputTgl1      = $this->input->post('tgl1');
        $inputTgl2         = $this->input->post('tgl2');

        $spreadsheet = IOFactory::load(realpath(APPPATH . '/doc_templates/template-tambah-data.xls'));

        $countries = $this->ExcelsModel->get_master_negara();
        $propinsi = $this->ExcelsModel->get_master_provinsi();
        $kota = $this->ExcelsModel->get_master_kota();
        $kerjaan = $this->ExcelsModel->get_master_pekerjaan();
        $layanan = $this->ExcelsModel->get_master_layanan();
        $komoditi = $this->ExcelsModel->get_master_komoditi();
        $klasifikasi = $this->ExcelsModel->get_master_klasifikasi();
        $subklasifikasi = $this->ExcelsModel->get_master_subklasifikasi();

        if ($this->session->city != 'PUSAT') {
            $dir_rujukan = $this->ExcelsModel->get_master_rujukan($this->session->city);
        } else {
            $dir_rujukan = $this->ExcelsModel->get_master_rujukan('');
        }

        $slarujukan = $this->ExcelsModel->get_master_sla();

        $spreadsheet->getSheetByName('MDNegara')->fromArray($countries, NULL, 'A1')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);
        $spreadsheet->getSheetByName('MDProvinsi')->fromArray($propinsi, NULL, 'A1')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);
        $spreadsheet->getSheetByName('MDKota')->fromArray($kota, NULL, 'A1')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);
        $spreadsheet->getSheetByName('MDPekerjaan')->fromArray($kerjaan, NULL, 'A1')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);
        $spreadsheet->getSheetByName('MDJenisKomoditi')->fromArray($komoditi, NULL, 'A1')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);
        $spreadsheet->getSheetByName('MDLayanan')->fromArray($layanan, NULL, 'A1')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);
        $spreadsheet->getSheetByName('MDKlasifikasi')->fromArray($klasifikasi, NULL, 'A1')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);

        //$spreadsheet->getSheetByName('MDSubklasifikasi')->fromArray($subklasifikasi,NULL,'A1')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);
        //SUBKLAS
        $istart = 0;
        $klasfifikasiTemp = "";
        $rowstart = 0;
        $rowfinish = 0;
        $sheetPrinted = $spreadsheet->getSheetByName('MDSubklasifikasi');

        foreach ($subklasifikasi as $value) {
            $klasfifikasiget = $value["klasifikasi"];
            $istart++;

            $spreadsheet->getSheetByName('MDSubklasifikasi')->SetCellValue("A" . $istart, $value["klasifikasi"]);
            $spreadsheet->getSheetByName('MDSubklasifikasi')->SetCellValue("B" . $istart, $value["subklasifikasi"]);

            if ($klasfifikasiget != $klasfifikasiTemp) {
                if ($istart > 1) {
                    $spreadsheet->addNamedRange(
                        new NamedRange(
                            str_replace(" ", "_", $klasfifikasiTemp),
                            $sheetPrinted,
                            "B" . $rowstart . ":B" . $rowfinish
                        )
                    );
                }
                $rowstart = $istart;
                $klasfifikasiTemp = $klasfifikasiget;
            } else {
                $rowfinish = $istart;
            }
        }

        if (count($subklasifikasi) > 0) {
            $spreadsheet->addNamedRange(
                new NamedRange(
                    str_replace(" ", "_", $klasfifikasiTemp),
                    $sheetPrinted,
                    "B" . $rowstart . ":B" . $rowfinish
                )
            );
        }

        $spreadsheet->getSheetByName('MDDirRujukan')->fromArray($dir_rujukan, NULL, 'A1')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);
        $spreadsheet->getSheetByName('MDSLARujukan')->fromArray($slarujukan, NULL, 'A1')->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);


        for ($column = 5; $column < 105; $column++) {
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('G' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('=\'MDNegara\'!$B$1:$B$' . count($countries));

            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('U' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('=\'MDNegara\'!$B$1:$B$' . count($countries));

            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('H' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('=\'MDProvinsi\'!$B$1:$B$' . count($propinsi));

            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('V' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('=\'MDProvinsi\'!$B$1:$B$' . count($propinsi));

            //KOTA
            /*$validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('I'.$column)->getDataValidation();
            $validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
            $validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('=\'MDKota\'!$B$1:$B$'.count($kota));*/

            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('T' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('=\'MDKota\'!$B$1:$B$' . count($kota));

            //Pekerjaan
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('L' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('=\'MDPekerjaan\'!$B$1:$B$' . count($kerjaan));

            //JenisKelamin
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('B' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"Laki-Laki,Perempuan"');

            //tgl kadal
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('W' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DATE);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setOperator('isValidDate');
            $validation->setErrorTitle('Oops!');
            $validation->setError('Invalid date.');

            //tgl diperoleh
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('Y' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DATE);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setOperator('isValidDate');
            $validation->setErrorTitle('Oops!');
            $validation->setError('Invalid date.');

            //tgl digunakan
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('Z' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DATE);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setOperator('isValidDate');
            $validation->setErrorTitle('Oops!');
            $validation->setError('Invalid date.');

            //jenis layanan
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('AE' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"Pengaduan,Permintaan Informasi"');

            //Komoditi
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('AF' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('=\'MDJenisKomoditi\'!$B$1:$B$' . count($komoditi));

            //layanan melalui
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('AG' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('=\'MDLayanan\'!$B$1:$B$' . count($layanan));

            //Sumber Data
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('AH' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"SP4N,PPID"');

            //Shift
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('AI' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"Shift 1,Shift 2,Shift 3"');

            //klasifikasi
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('AJ' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('=\'MDKlasifikasi\'!$B$1:$B$' . count($klasifikasi));

            //subklasifikasi
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('AK' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            //$validation->setFormula1('=\'MDSubklasifikasi\'!$B$1:$B$'.count($subklasifikasi));
            //=INDIRECT(SUBSTITUTE(AJ5;" "; "_"))
            $validation->setFormula1('=INDIRECT($AJ$' . $column . ')');
            //$printformula = '=INDIRECT(SUBSTITUTE($AJ$'.$column.';" "; "_"))';
            //$validation->setFormula1($printformula); 

            //Perlu Rujuk
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('AL' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('"Ya,Tidak"');

            $fieldRujukan = array("AM", "AO", "AQ", "AS", "AU");
            $fieldSla = array("AN", "AP", "AR", "AT", "AV");
            for ($r = 0; $r < 5; $r++) {
                $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell($fieldRujukan[$r] . $column)->getDataValidation();
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Value is not in list.');
                $validation->setPromptTitle('Pick from list');
                $validation->setPrompt('Please pick a value from the drop-down list.');
                $validation->setFormula1('=\'MDDirRujukan\'!$B$1:$B$' . count($dir_rujukan));

                //SLA Rujukan
                $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell($fieldSla[$r] . $column)->getDataValidation();
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Value is not in list.');
                $validation->setPromptTitle('Pick from list');
                $validation->setPrompt('Please pick a value from the drop-down list.');
                $validation->setFormula1('=\'MDSLARujukan\'!$B$1:$B$' . count($slarujukan));
            }

            //dijawab melalui
            $validation = $spreadsheet->getSheetByName('Lembar Kerja')->getCell('BA' . $column)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');
            $validation->setFormula1('=\'MDLayanan\'!$B$1:$B$' . count($layanan));
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=Template_upload_data.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}

