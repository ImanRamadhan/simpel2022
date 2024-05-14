<?php
function saveToDBDraft($item_id = -1, $inputdata)
{
    $CI = get_instance();
    
    $iden_nama = $inputdata['iden_nama'];
    $iden_jk =  $inputdata['iden_jk'];
    $iden_instansi =  $inputdata['iden_instansi'];
    $iden_jenis =  $inputdata['iden_jenis'];
    $iden_alamat =  $inputdata['iden_alamat'];
    $iden_email =  $inputdata['iden_email'];
    $iden_negara =  $inputdata['iden_negara'];
    $iden_provinsi =  $inputdata['iden_provinsi'];
    $iden_provinsi2 =  $inputdata['iden_provinsi2'];
    $iden_kota =  $inputdata['iden_kota'];		
    $iden_kota2 =  $inputdata['iden_kota2'];
    
    if(strtolower($iden_negara)!='indonesia')
    {
        $iden_provinsi = $iden_provinsi2;
        $iden_kota = $iden_kota2;
    }
    
    $iden_telp =  $inputdata['iden_telp'];
    $iden_fax =  $inputdata['iden_fax'];
    $iden_profesi =  $inputdata['iden_profesi'];
    $usia = $inputdata['usia'];
    $prod_nama =  $inputdata['prod_nama'];
    $prod_generik =  $inputdata['prod_generik'];
    $prod_pabrik =  $inputdata['prod_pabrik'];
    $prod_noreg =  $inputdata['prod_noreg'];
    $prod_nobatch =  $inputdata['prod_nobatch'];
    $prod_alamat =  $inputdata['prod_alamat'];
    $prod_kota =  $inputdata['prod_kota'];
    $prod_negara =  $inputdata['prod_negara'];
    $prod_provinsi =  $inputdata['prod_provinsi'];
    $prod_provinsi2 =  $inputdata['prod_provinsi2'];
    
    if(strtolower($prod_negara)!='indonesia')
    {
        $prod_provinsi = $prod_provinsi2;
    }
    
    $prod_kadaluarsa =  $inputdata['prod_kadaluarsa'];
    $prod_diperoleh =  $inputdata['prod_diperoleh'];
    $prod_diperoleh_tgl =  $inputdata['prod_diperoleh_tgl'];
    $prod_digunakan_tgl =  $inputdata['prod_digunakan_tgl'];
    $isu_topik =  $inputdata['isu_topik'];
    $prod_masalah =  $inputdata['prod_masalah'];
    $info =  $inputdata['info'];
    $penerima =  $inputdata['penerima'];
    $kategori =  $inputdata['kategori'];
    $submited_via =  $inputdata['submited_via'];
    $jenis =  $inputdata['jenis'];
    $shift =  $inputdata['shift'];
    
    $klasifikasi_id =  $inputdata['klasifikasi_id'];
    $subklasifikasi_id = $inputdata['subklasifikasi_id'];
    
    $CI->load->model('Klasifikasi');
    $kla_info = $CI->Klasifikasi->get_info($klasifikasi_id);
    $klasifikasi = $kla_info->nama;
    
    $CI->load->model('Subklasifikasi');
    $subkla_info = $CI->Subklasifikasi->get_info($subklasifikasi_id);
    $subklasifikasi = $subkla_info->subklasifikasi;
    
    $is_rujuk =  $inputdata['is_rujuk'];
    $dir1 =  $inputdata['dir1'];
    $dir2 =  $inputdata['dir2'];
    $dir3 =  $inputdata['dir3'];
    $dir4 =  $inputdata['dir4'];
    $dir5 =  $inputdata['dir5'];
    
    $sla1 =  $inputdata['sla1'];
    $sla2 =  $inputdata['sla2'];
    $sla3 =  $inputdata['sla3'];
    $sla4 =  $inputdata['sla4'];
    $sla5 =  $inputdata['sla5'];
    
    if($is_rujuk == '0')
    {
        $dir1 = 0;
        $dir2 = 0;
        $dir3 = 0;
        $dir4 = 0;
        $dir5 = 0;
        
        $sla1 = 0;
        $sla2 = 0;
        $sla3 = 0;
        $sla4 = 0;
        $sla5 = 0;
    }
    
    $jawaban =  $inputdata['jawaban'];
    $keterangan =  $inputdata['keterangan'];
    $petugas_entry =  $inputdata['petugas_entry'];
    $penjawab =  $inputdata['penjawab'];
    $answered_via =  $inputdata['answered_via'];
    
    //ppid data
    /*$tgl_diterima = $inputdata['tgl_diterima'];
    if(!empty($tgl_diterima))
        $tgl_diterima = convert_date1($tgl_diterima);
    
    $diterima_via = $inputdata['diterima_via'];
    $no_ktp = $inputdata['no_ktp'];
    $rincian =  $inputdata['rincian'];
    $tujuan =  $inputdata['tujuan'];
    
    
    $cara_memperoleh_info = '';
    if(!empty($inputdata['cara_memperoleh_info']))
    {
        $cara_memperoleh_info = $inputdata['cara_memperoleh_info'];
        $cara_memperoleh_info = implode(',', $cara_memperoleh_info);
    }
    
    $cara_mendapat_salinan = '';
    if(!empty($inputdata['cara_mendapat_salinan']))
    {
        $cara_mendapat_salinan = $inputdata['cara_mendapat_salinan'];
        $cara_mendapat_salinan = implode(',', $cara_mendapat_salinan);
    }
    
    if(!empty($inputdata['tgl_pemberitahuan_tertulis']))
        $tgl_pemberitahuan_tertulis = $inputdata['tgl_pemberitahuan_tertulis'];
        $tgl_pemberitahuan_tertulis = convert_date1($tgl_pemberitahuan_tertulis);
    
    $penguasaan_kami = !empty($inputdata['penguasaan_kami']) ? $inputdata['penguasaan_kami'] : '';
    $penguasaan_kami_teks = !empty($inputdata['penguasaan_kami_teks']) ? $inputdata['penguasaan_kami_teks'] : '';
    $penguasaan_badan_lain = !empty($inputdata['penguasaan_badan_lain']) ? $inputdata['penguasaan_badan_lain'] : '';
    $nama_badan_lain = !empty($inputdata['nama_badan_lain']) ? $inputdata['nama_badan_lain'] : '';
    
    $bentuk_fisik = '';
    if(!empty($inputdata['bentuk_fisik']))
    {
        $bentuk_fisik = $inputdata['bentuk_fisik'];
        $bentuk_fisik = implode(',', $bentuk_fisik);
    }
    
    $penyalinan = !empty($inputdata['penyalinan']) ? $inputdata['penyalinan'] : '';
    $biaya_penyalinan_lbr = !empty($inputdata['biaya_penyalinan_lbr'])?$inputdata['biaya_penyalinan_lbr']:0;
    $biaya_penyalinan = !empty($inputdata['biaya_penyalinan'])?$inputdata['biaya_penyalinan']:0;
    
    $pengiriman = !empty($inputdata['pengiriman']) ? $inputdata['pengiriman'] : '';
    $biaya_pengiriman = !empty($inputdata['biaya_pengiriman'])?$inputdata['biaya_pengiriman']:0;
    $lain_lain = !empty($inputdata['lain_lain']) ? $inputdata['lain_lain'] : '';
    $biaya_lain = !empty($inputdata['biaya_lain'])?$inputdata['biaya_lain']:0;
    
    $biaya_total = ($biaya_penyalinan * $biaya_penyalinan_lbr) + $biaya_pengiriman + $biaya_lain;
    
    $waktu_penyediaan = $inputdata['waktu_penyediaan'];
    $waktu_penyediaan2 = $inputdata['waktu_penyediaan2'];
    $penghitaman = $inputdata['penghitaman'];
    
    $nama_pejabat_ppid = $inputdata['nama_pejabat_ppid'];
    
    $info_blm_dikuasai = !empty($inputdata['info_blm_dikuasai'])?$inputdata['info_blm_dikuasai']:'';
    $info_blm_didoc = !empty($inputdata['info_blm_didoc'])?$inputdata['info_blm_didoc']:'';
    $pengecualian_pasal17 = !empty($inputdata['pengecualian_pasal17'])?$inputdata['pengecualian_pasal17']:'';
    $pengecualian_pasal_lain = !empty($inputdata['pengecualian_pasal_lain'])?$inputdata['pengecualian_pasal_lain']:'';
    $pasal17_huruf = !empty($inputdata['pasal17_huruf'])?$inputdata['pasal17_huruf']:'';
    $pasal_lain_uu = !empty($inputdata['pasal_lain_uu'])?$inputdata['pasal_lain_uu']:'';
    $konsekuensi = !empty($inputdata['konsekuensi'])?$inputdata['konsekuensi']:'';
    
    if(!empty($inputdata['tt_tgl']))
        $tt_tgl = $inputdata['tt_tgl'];
        $tt_tgl = convert_date1($tt_tgl);
    
    $tt_nomor = $inputdata['tt_nomor'];
    $tt_lampiran = $inputdata['tt_lampiran'];
    $tt_perihal = $inputdata['tt_perihal'];
    $tt_isi = $inputdata['tt_isi'];
    $tt_pejabat = $inputdata['tt_pejabat'];
    
    $keputusan = $inputdata['keputusan'];
    
    //$no_reg_keberatan = $inputdata['no_reg_keberatan'];
    $kuasa_nama = $inputdata['kuasa_nama'];
    $kuasa_alamat = $inputdata['kuasa_alamat'];
    $kuasa_telp = $inputdata['kuasa_telp'];
    $kuasa_email = $inputdata['kuasa_email'];
    
    $alasan_keberatan = '';
    if(!empty($inputdata['alasan_keberatan']))
    {
        $alasan_keberatan = $inputdata['alasan_keberatan'];
        $alasan_keberatan = implode(',', $alasan_keberatan);
    }
    
    $kasus_posisi = $inputdata['kasus_posisi'];
    
    if(!empty($inputdata['tgl_tanggapan']))
    $tgl_tanggapan = $inputdata['tgl_tanggapan'];
        $tgl_tanggapan = convert_date1($tgl_tanggapan);
    
    $nama_petugas = $inputdata['nama_petugas'];
    $keberatan_tgl = $inputdata['keberatan_tgl'];
    if(!empty($keberatan_tgl))
        $keberatan_tgl = convert_date1($keberatan_tgl);
    
    $keberatan_no = $inputdata['keberatan_no'];
    $keberatan_lampiran = $inputdata['keberatan_lampiran'];
    $keberatan_perihal = $inputdata['keberatan_perihal'];
    $keberatan_isi_surat = $inputdata['keberatan_isi_surat'];
    $keberatan_nama_pejabat = $inputdata['keberatan_nama_pejabat'];
    
    $alasan_ditolak = $inputdata['alasan_ditolak'];
    
    $formtype = $inputdata['formtype']; // 1 = Layanan, 2 = PPID, 3 = Keberatan, 4 = Sengketa
    if(empty($formtype))$formtype = 1;
            
    $ppid_data = array(
        'tgl_diterima' => $tgl_diterima,
        'diterima_via' => $diterima_via,
        'no_ktp' => $no_ktp,
        'rincian' => $rincian,
        'tujuan' => $tujuan,
        'cara_memperoleh_info' => $cara_memperoleh_info,
        'cara_mendapat_salinan' => $cara_mendapat_salinan,
        
        'tgl_pemberitahuan_tertulis' => $tgl_pemberitahuan_tertulis,
        'penguasaan_kami' => $penguasaan_kami,
        'penguasaan_kami_teks' => $penguasaan_kami_teks,
        'penguasaan_badan_lain' => $penguasaan_badan_lain,
        'nama_badan_lain' => $nama_badan_lain,
        'bentuk_fisik' => $bentuk_fisik,
        'penyalinan' => $penyalinan,
        'biaya_penyalinan' => $biaya_penyalinan,
        'biaya_penyalinan_lbr' => $biaya_penyalinan_lbr,
        'pengiriman' => $pengiriman,
        'biaya_pengiriman' => $biaya_pengiriman,
        'lain_lain' => $lain_lain,
        'biaya_lain' => $biaya_lain,
        'waktu_penyediaan' => $waktu_penyediaan,
        'waktu_penyediaan2' => $waktu_penyediaan2,
        'penghitaman' => $penghitaman,
        'info_blm_dikuasai' => $info_blm_dikuasai,
        'info_blm_didoc' => $info_blm_didoc,
        'pengecualian_pasal17' => $pengecualian_pasal17,
        'pengecualian_pasal_lain' => $pengecualian_pasal_lain,
        'pasal17_huruf' => $pasal17_huruf,
        'pasal_lain_uu' => $pasal_lain_uu,
        'konsekuensi' => $konsekuensi,
        'tt_tgl' => $tt_tgl,
        'tt_nomor' => $tt_nomor,
        'tt_lampiran' => $tt_lampiran,
        'tt_perihal' => $tt_perihal,
        'tt_isi' => $tt_isi,
        'tt_pejabat' => $tt_pejabat,
        'keputusan' => $keputusan,
        'nama_pejabat_ppid' => $nama_pejabat_ppid,
        
        //'no_reg_keberatan' => $no_reg_keberatan,
        'kuasa_nama' => $kuasa_nama,
        'kuasa_alamat' => $kuasa_alamat,
        'kuasa_telp' => $kuasa_telp,
        'kuasa_email' => $kuasa_email,
        'alasan_keberatan' => $alasan_keberatan,
        'kasus_posisi' => $kasus_posisi,
        'tgl_tanggapan' => $tgl_tanggapan,
        'nama_petugas' => $nama_petugas,
        'keberatan_tgl' => $keberatan_tgl,
        'keberatan_no' => $keberatan_no,
        'keberatan_lampiran' => $keberatan_lampiran,
        'keberatan_perihal' => $keberatan_perihal,
        'keberatan_isi_surat' => $keberatan_isi_surat,
        'keberatan_nama_pejabat' => $keberatan_nama_pejabat,
        'alasan_ditolak' => $alasan_ditolak	
    );*/
    
    $tipe_medsos =  $inputdata['tipe_medsos'];
    if(!empty($submited_via) && $submited_via != 'Medsos')
        $tipe_medsos = '';
        
    //Save item data
    $item_data = array(
        'iden_nama' => $iden_nama,
        'iden_jk' => $iden_jk,
        'iden_instansi' => $iden_instansi,
        'iden_jenis' => $iden_jenis,
        'iden_alamat' => $iden_alamat,
        'iden_email' => $iden_email,
        'iden_negara' => $iden_negara,
        'iden_provinsi' => $iden_provinsi,
        //'iden_provinsi2' =>                
        'iden_kota' => $iden_kota,
        'iden_telp' => $iden_telp,
        'iden_fax' => $iden_fax,
        'iden_profesi' => $iden_profesi,
        'usia' => $usia,
        'prod_nama' => $prod_nama,
        'prod_generik' => $prod_generik,
        'prod_pabrik' => $prod_pabrik, 
        'prod_noreg' => $prod_noreg,
        'prod_nobatch' => $prod_nobatch,
        'prod_alamat' => $prod_alamat,
        'prod_kota' => $prod_kota,
        'prod_provinsi' => $prod_provinsi,
        'prod_negara' => $prod_negara,
        'prod_kadaluarsa' => convert_date1($prod_kadaluarsa),
        'prod_diperoleh' => $prod_diperoleh,
        'prod_diperoleh_tgl' => convert_date1($prod_diperoleh_tgl),
        'prod_digunakan_tgl' => convert_date1($prod_digunakan_tgl),         
        'isu_topik' => $isu_topik,
        'prod_masalah' => $prod_masalah,
        'info' => $info,
        'penerima' => $penerima,
        'kategori' => $kategori,
        'submited_via' => $submited_via,
        'jenis' => $jenis,
        'shift' => $shift,
        'klasifikasi' => $klasifikasi,
        'subklasifikasi' => $subklasifikasi,
        'klasifikasi_id' => $klasifikasi_id,
        'subklasifikasi_id' => $subklasifikasi_id,
        //'sla' => $sla,
        'is_rujuk' => $is_rujuk,
        
        'direktorat' => $dir1,
        'direktorat2' => $dir2,
        'direktorat3' => $dir3,
        'direktorat4' => $dir4,              
        'direktorat5' => $dir5,
        
        'd1_prioritas' => $sla1,
        'd2_prioritas' => $sla2,
        'd3_prioritas' => $sla3,
        'd4_prioritas' => $sla4,              
        'd5_prioritas' => $sla5,
        
                    
        'jawaban' =>  $jawaban,
        'keterangan' =>  $keterangan,          
        'petugas_entry' =>  $petugas_entry,     
        'penjawab' =>  $penjawab,
        'answered_via' =>  $answered_via,
        //'ppid_rincian' => $ppid_rincian,
        //'ppid_tujuan' => $ppid_tujuan,
        'tipe_medsos' => $tipe_medsos,
        'category' => $formtype
    );
    $CI->load->model('Draft');
    /*
    $item_info = $CI->Draft->get_info($item_id);
    foreach(get_object_vars($item_info) as $property => $value)
    {
        $item_info->$property = $this->xss_clean($value);
    }
    */
    //$data['item_info'] = $item_info;
    
    $item_data['id'] = $item_id;
    
    if($item_id == -1)
    {
        //$item_data['trackid'] = $this->Draft->generate_ticketid($this->session->city, 'DRF', date('Y-m-d'));
        $item_data['trackid'] = '';
        $item_data['owner'] = $CI->session->id;
        $item_data['kota'] = $CI->session->city;
        $item_data['dt'] = date('Y-m-d H:i:s');
        $item_data['tglpengaduan'] = date('Y-m-d');
        $item_data['waktu'] = date('H:i:s');
        //$item_data['info'] = 'P';
        $item_data['owner_dir'] = $CI->session->direktoratid;	
    }
    
    //for balai
    if($CI->session->city != 'PUSAT')
    {
        $tglpengaduan = $inputdata['tglpengaduan'];
        if(!empty($tglpengaduan))
            $tglpengaduan = convert_date1($tglpengaduan);
        
        $waktu = $inputdata['waktu'];
        
        $item_data['tglpengaduan'] = $tglpengaduan;
        $item_data['waktu'] = $waktu;
        //$item_data['trackid'] = $this->Draft->generate_ticketid($this->session->city, 'DRF', $tglpengaduan);
        $item_data['trackid'] = '';
    }
    
    $send = $inputdata['send'];
    
    $CI->load->model('Draft');
    if($CI->Draft->save($item_data, $item_id))
    {
        $message = 'Data berhasil disimpan';	
        
        $ppid_data['id'] = $item_data['id'];
        //if(!empty($jenis) && $jenis == 'PPID')
        if(1)
        {
            //if(empty($tgl_diterima))
            //	$ppid_data['tgl_diterima'] = date('Y-m-d'];
            
           // $CI->Draft->save_ppid($ppid_data, $item_data['id']);
            
        }

        return json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_data['id']));
        
    }
    else // failure
    {
        $message = 'Data gagal disimpan';
        
        return json_encode(array('success' => FALSE, 'message' => $message, 'id' => $item_id));
    }
}
?>