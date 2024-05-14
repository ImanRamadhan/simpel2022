<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Formulir Layanan</title>
<style>

@page {
	margin-top: 1.5cm;
	margin-bottom: 0.6cm;
	margin-left: 0.6cm;
	margin-right: 1.5cm;
}
body {
	font-family: "Arial";
	font-size: 9pt;
	font-weight:normal;
}

#watermark {
	position: fixed;
    top: 15%;
    width: 100%;
    text-align: center;
    opacity: .1;
    transform: rotate(10deg);
    transform-origin: 50% 50%;
    z-index: -1000;
}

.kop {
	font-size: 14pt;
	font-weight:bold;
}

.title {
	font-size: 12pt;
	font-weight: bold;
}

.footer {
	position: absolute;
    bottom: 0;
}
.header {
}
.content {
}
input[type='checkbox'] {
	vertical-align: bottom;
	
}
.page-break	{ display: block; page-break-after: always; }
</style>

</head>



<body>
<div id="watermark">
	<img src="images/rahasia2.png" width="70%" />
</div>
<div class="header">
<table border="1" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" align="center">&nbsp;
			<table border="1" cellspacing="0" cellpadding="0" width="70%" align="center">
				<tr>
					<td align="center">
						<span class="kop">FORMULIR PENGADUAN KONSUMEN</span><br />
						(Catatan Identitas Pelapor Akan Dirahasiakan)
						
					</td>
				</tr>
				
			</table>
			&nbsp;
			<div align="center"><b>IDENTITAS PELAPOR</b></div>
		</td>
	</tr>
	
	<tr>
		<td colspan="2">
			<table border="0" cellspacing="2" cellpadding="1">
				<tr>
					<td width="100" valign="top">Nama/Umur</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->iden_nama; ?><?php echo $item_info->usia == 0?'':' / '.$item_info->usia; ?></td>
					<td width="100" valign="top">Tanggal</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->tglpengaduan; ?></td>
				</tr>
				<tr>
					<td width="100" valign="top">Profesi</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->profesi; ?></td>
					<td width="100" valign="top">No. Telepon</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->iden_telp; ?></td>
				</tr>
				<tr>
					<td width="100" valign="top">Nama Perusahaan</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->iden_instansi; ?></td>
					<td width="100" valign="top">Email</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->iden_email; ?></td>
				</tr>
				<tr>
					<td width="100" valign="top">Jenis Perusahaan</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->iden_jenis; ?></td>
					<td width="100" valign="top">Alamat</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->iden_alamat; ?></td>
				</tr>
				<tr>
					<td width="100" valign="top">Pengaduan</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->submited_via; ?></td>
					<td width="100" valign="top">Jam</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->waktu; ?></td>
				</tr>
				<tr>
					<td width="100" valign="top">Jenis Produk</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->jenis_produk; ?></td>
					<td width="100" valign="top"></td>
					<td width="2" valign="top"></td>
					<td width="150" valign="top"></td>
				</tr>
				<tr>
					<td width="100" valign="top">Inti Masalah</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top" colspan="4"><?php echo $item_info->isu_topik; ?></td>
					
				</tr>
			</table>
			
			
			<br />
			<div align="center"><b>IDENTITAS PRODUK</b></div>
		
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="0" cellspacing="2" cellpadding="1">
				<tr>
					<td width="100" valign="top">Nama Produk</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->prod_nama; ?></td>
					<td width="100" valign="top">Nama Pabrik</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->prod_pabrik; ?></td>
				</tr>
				<tr>
					<td width="100" valign="top">No. Reg</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->prod_noreg; ?></td>
					<td width="100" valign="top">Alamat Pabrik</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->prod_alamat; ?></td>
				</tr>
				<tr>
					<td width="100" valign="top">Tgl. Kadaluarsa</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->prod_kadaluarsa=='0000-00-00'?'':$item_info->prod_kadaluarsa; ?>&nbsp;</td>
					<td width="100" valign="top">Nomor Batch</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->prod_nobatch; ?>&nbsp;</td>
				</tr>
				
			</table>
			
			<br />
		
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div align="center"><b>PERTANYAAN / MASALAH KONSUMEN</b></div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<p><?php echo $item_info->prod_masalah; ?></p>&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="0" cellspacing="2" cellpadding="1">
				<tr>
					<td width="100" valign="top">Klasifikasi</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->klasifikasi; ?></td>
					<td width="100" valign="top"><b></b></td>
					<td width="2" valign="top"></td>
					<td width="150" valign="top"></td>
				</tr>
				<tr>
					<td width="100" valign="top">Subklasifikasi</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->subklasifikasi; ?></td>
					<td width="100" valign="top"><b></b></td>
					<td width="2" valign="top"></td>
					<td width="150" valign="top"></td>
				</tr>
				
				
			</table>
			
			
			<br />
			
		
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div align="center"><b>INFORMASI PENDUKUNG</b></div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="0" cellspacing="2" cellpadding="1">
				<tr>
					<td width="100" valign="top">Dimana diperoleh</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->prod_diperoleh; ?></td>
					<td width="100" valign="top">Tanggal</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->prod_diperoleh_tgl=='0000-00-00'?'':$item_info->prod_diperoleh_tgl; ?></td>
				</tr>
				<tr>
					<td width="100" valign="top">Tgl. Digunakan</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top"><?php echo $item_info->prod_digunakan_tgl=='0000-00-00'?'':$item_info->prod_digunakan_tgl; ?>&nbsp;</td>
					<td width="100" valign="top">Informasi Lain</td>
					<td width="2" valign="top">:</td>
					<td width="150" valign="top">&nbsp;</td>
				</tr>
				
				
			</table>
			
			
			<br />
			
		
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div align="center"><b>INFORMASI PENDUKUNG</b></div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="0" cellspacing="2" cellpadding="1" width="100%">
				<tr>
					<td width="" valign="top"><input type="checkbox" /> Bidang/Seksi <br />
					Pengujian Terapetik<br />
					Pengujian Pangan<br />
					Pengujian Mikro
					</td>
					
					<td width="" valign="top" nowrap><input type="checkbox" /> Bidang/Seksi<br /> Pemeriksaan
					</td>
					<td width="" valign="top" nowrap><input type="checkbox" /> Produsen
					</td>
					<td width="" valign="top" nowrap><input type="checkbox" /> BADAN POM
					</td>
					<td width="" valign="top" nowrap><input type="checkbox" /> Contact Person
					</td>
					<td width="" valign="top" nowrap><input type="checkbox" /> Lintas Sektor
					</td>
					
				</tr>
				
				
				
			</table>
			
			<p>ALASAN TIDAK DITINDAK LANJUTI/HASIL TINDAK LANJUT :</p>
			
			
		
		</td>
	</tr>
	<tr>
		<td width="50%" align="center"><b>JAWABAN KE KONSUMEN</b> </td>
		<td align="center"><b>KETERANGAN</b></td>
	</tr>
	<tr>
		<td width="50%" height="100"><?php echo $item_info->jawaban; ?> </td>
		<td><?php echo $item_info->keterangan; ?> </td>
	</tr>
	<tr>
		<td colspan="2">
			<div align="center"><b>INFORMASI KE KONSUMEN</b></div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			
			<table border="0" cellspacing="2" cellpadding="1" width="100%">
				<tr>
					
					<td width="50%" valign="top" height="50">
					</td>
					<td width="" valign="top" align="center">Ttd. Petugas
					</td>
					
				</tr>
				<tr>
					
					<td width="50%">Dalam waktu 1x24 jam petugas harus menginformasikan ke konsumen
					</td>
					<td width="" valign="top" align="center"><?php echo $item_info->petugas_entry; ?> 
					</td>
					
				</tr>
				
				
				
			</table>
			
			
		</td>
	</tr>
	
</table>

</body>

</html>