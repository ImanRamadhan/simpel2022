<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Formulir Keberatan</title>
<style>

@page {
	margin-top: 1cm;
	margin-bottom: 0.6cm;
	margin-left: 2cm;
	margin-right: 1.5cm;
}
body {
	font-family: "Times New Roman", Times, serif;
	font-size: 9pt;
	font-weight:normal;
}

.kop {
	font-size: 13pt;
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
<div class="header">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td width="120px"><img src="assets/images/bpom.png" width="120px" /></td>
					
					<td align="center" width="600px">
						<span class="kop"><?php echo $balai_info->kop; ?></span><br />
						<?php echo $balai_info->alamat; ?><br />
						Tlp. <?php echo $balai_info->no_telp; ?> / Fax. <?php echo $balai_info->no_fax; ?><br />
						Email : <?php echo $balai_info->email; ?>
						
					</td>
				</tr>
				
			</table>
		
		</td>
	</tr>
	<tr>
		<td align="center" height="50px">
			<div class="title">PERNYATAAN KEBERATAN ATAS PERMOHONAN INFORMASI
			</div>
		</td>
	</tr>
	<tr>
		<td><b>A. INFORMASI PENGAJU KEBERATAN</b><br /><br />
			<table border="0" cellspacing="2" cellpadding="1" width="90%">
				<tr>
					<td width="200px" valign="top"><b>Nomor Registrasi Keberatan</b></td>
					<td width="10px" valign="top">:</td>
					<td valign="top"><?php echo $item_info->trackid; ?>*</td>
				</tr>
				<tr>
					<td width="200px" valign="top"><b>Nomor Pendaftaran Permohonan Informasi</b></td>
					<td width="10px" valign="top">:</td>
					<td valign="top"><?php echo $item_info->trackid; ?></td>
				</tr>
				<tr>
					<td width="200px" valign="top"><b>Tujuan Penggunaan Informasi</b></td>
					<td width="10px" valign="top">:</td>
					<td valign="top"><?php echo $ppid_info->tujuan; ?></td>
				</tr>
				<tr>
					<td width="200px" valign="top"><b>Identitas Pemohon</b></td>
					<td width="10px" valign="top"></td>
					<td valign="top"><?php echo ''; ?></td>
				</tr>
				<tr>
					<td width="200px" valign="top">&nbsp;&nbsp;&nbsp;Nama</td>
					<td width="10px" valign="top">:</td>
					<td valign="top"><?php echo $item_info->iden_nama; ?></td>
				</tr>
				<tr>
					<td width="200px" valign="top">&nbsp;&nbsp;&nbsp;Alamat</td>
					<td width="10px" valign="top">:</td>
					<td valign="top"><?php echo $item_info->iden_alamat; ?></td>
				</tr>
				<tr>
					<td width="200px" valign="top">&nbsp;&nbsp;&nbsp;Nomor Telepon/Email</td>
					<td width="10px" valign="top">:</td>
					<td valign="top">
					<?php 
					$arr = array();
					if(!empty($item_info->iden_telp))
						array_push($arr, $item_info->iden_telp);
					
					if(!empty($item_info->iden_email))
						array_push($arr, $item_info->iden_email);
					
					if(count($arr) > 0)
						echo implode('/', $arr);
					
					?>
					</td>
				</tr>
				<tr>
					<td width="200px" valign="top"><b>Identitas Kuasa Pemohon **</b></td>
					<td width="10px" valign="top"></td>
					<td valign="top"><?php echo ''; ?></td>
				</tr>
				<tr>
					<td width="200px" valign="top">&nbsp;&nbsp;&nbsp;Nama</td>
					<td width="10px" valign="top">:</td>
					<td valign="top"><?php echo $ppid_info->kuasa_nama; ?></td>
				</tr>
				<tr>
					<td width="200px" valign="top">&nbsp;&nbsp;&nbsp;Alamat</td>
					<td width="10px" valign="top">:</td>
					<td valign="top"><?php echo $ppid_info->kuasa_alamat; ?></td>
				</tr>
				<tr>
					<td width="200px" valign="top">&nbsp;&nbsp;&nbsp;Nomor Telepon/Email</td>
					<td width="10px" valign="top">:</td>
					<td valign="top">
					<?php 
					$arr = array();
					if(!empty($ppid_info->kuasa_telp))
						array_push($arr, $ppid_info->kuasa_telp);
					
					if(!empty($ppid_info->kuasa_email))
						array_push($arr, $ppid_info->kuasa_email);
					
					if(count($arr) > 0)
						echo implode('/', $arr);
					
					?>
					</td>
				</tr>
				
			</table>
			<br />
			
		</td>
	</tr>
	<tr>
		<td style="cellpadding:0;cellspacing:0;margin:0"><b>B. ALASAN PENGAJUAN KEBERATAN***</b><br />
		
		<?php
		$alasan_keberatan = $ppid_info->alasan_keberatan;
		$alasan_array = array();
		if(!empty($alasan_keberatan))
		{
			$tokens = explode(',', $alasan_keberatan);
			foreach($tokens as $obj)
			{
				$alasan_array[$obj] = $obj;
			}
			
		}
		$alasan_keberatan_array = array(
			'a' => 'a. Permohonan Informasi Ditolak',
			'b' => 'b. Informasi berkala tidak disediakan',
			'c' => 'c. Permintaan informasi tidak ditanggapi',
			'd' => 'd. Permintaan informasi ditanggapi tidak sebagaimana yang diminta',
			'e' => 'e. Permintaan informasi tidak dipenuhi',
			'f' => 'f. Biaya yang dikenakan tidak wajar',
			'g' => 'g. Informasi disampaikan melebihi jangka waktu yang ditentukan'
		);

		?>
		
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<?php foreach($alasan_keberatan_array as $k => $v):?>
			<tr>
			<td>&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php echo (array_key_exists($k, $alasan_array)?'checked':''); ?> />
			</td>
			<td>
			<?php echo $v;?></td>
			</tr>
		<?php endforeach; ?>
		</table>
		
		</td>
	</tr>
	<tr>
		<td><b>C. KASUS POSISI</b><br /><br />
			<?php echo $ppid_info->kasus_posisi;?>
		</td>
	</tr>
	<tr>
		<td><b>D. HARI/TANGGAL TANGGAPAN ATAS KEBERATAN AKAN DIBERIKAN :</b>&nbsp;<?php echo $ppid_info->tgl_tanggapan_fmt;?>****<br />
			Demikian pengajuan keberatan ini saya sampaikan, atas perhatian dan tanggapannya, saya ucapkan terima kasih.<br /><br />

		</td>
	</tr>
	<tr>
		<td align="center"><?php echo get_city_location(); ?>, 
			<?php echo convert_date3($item_info->tglpengaduan);	?> *****
			<br /><br />
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellspacing="1" cellpadding="1" width="90%">
				<tr>
					<td width="50%" align="center">Mengetahui,******<br /><b>Petugas Informasi<br />Penerima Keberatan</b></td>
					<td align="center"><b>Pengaju Keberatan</b></td>
				</tr>
				<tr>
					<td width="50%" align="center" height="50px">
					
					</td>
					<td align="center"></td>
					
				</tr>
				<tr>
					<td width="50%" align="center"><b><?php echo $item_info->penerima; ?><!--<br />Nama dan Tanda Tangan--></b></td>
					<td align="center"><b><?php echo $item_info->iden_nama; ?><!--<br />Nama dan Tanda Tangan--></b></td>
					
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="150px">
			Keterangan:<br />
			<table>
				<tr>
					<td>*</td><td>Nomor register pengajuan keberatan diisi berdasarkan buku register pengajuan keberatan.</td>
				</tr>
				<tr>
					<td>**</td><td>Identitas kuasa pemohon diisi jika ada kuasa pemohonnya dan melampirkan Surat Kuasa.</td>
				</tr>
				<tr>
					<td>***</td><td>Sesuai dengan Pasal 35 UU KIP, dipilih oleh pengaju keberatan sesuai dengan alasan keberatan yang diajukan.</td>
				</tr>
				<tr>
					<td>****</td><td>Diisi sesuai dengan ketentuan jangka waktu dalam UU KIP.</td>
				</tr>
				<tr>
					<td>*****</td><td>Tanggal diisi dengan tanggal diterimanya pengajuan keberatan yaitu sejak keberatan dinyatakan lengkap sesuai dengan buku register pengajuan keberatan.</td>
				</tr>
				<tr>
					<td>******</td><td>Dalam hal keberatan diajukan secara langsung, maka formulir keberatan juga ditandatangani oleh petugas yang menerima pengajuan keberatan.</td>
				</tr>
			<table>
			
		</td>
	</tr>
</table>

</body>

</html>