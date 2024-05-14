<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Formulir Tanda Terima Permohonan Informasi PPID</title>
<style>

@page {
	margin-top: 1.5cm;
	margin-bottom: 0.6cm;
	margin-left: 2cm;
	margin-right: 1.5cm;
}
body {
	font-family: "Times New Roman", Times, serif;
	font-size: 10pt;
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
		<td height="40px">
		</td>
	</tr>
	<tr>
		<td align="center" height="100px">
			<div class="title">TANDA TERIMA PERMOHONAN INFORMASI<br />
			No. Pendaftaran: <?php echo $item_info->trackid; ?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellspacing="2" cellpadding="1" width="90%">
				<tr>
					<td width="200px"><b>Nomor Registrasi</b></td>
					<td width="10px">:</td>
					<td><?php echo $item_info->trackid; ?></td>
				</tr>
				<tr>
					<td width="200px"><b>Nama Pemohon</b></td>
					<td width="10px">:</td>
					<td><?php echo $item_info->iden_nama; ?></td>
				</tr>
				<tr>
					<td width="200px"><b>No. KTP</b></td>
					<td width="10px">:</td>
					<td><?php echo $ppid_info->no_ktp; ?></td>
				</tr>
				<tr>
					<td width="200px"><b>Diterima Tanggal</b></td>
					<td width="10px">:</td>
					<td><?php echo convert_date3($ppid_info->tgl_diterima);?> melalui <?php echo $ppid_info->diterima_via; ?></td>
				</tr>
				
			</table>
		
		</td>
	</tr>
	<tr>
		<td height="40px">
		</td>
	</tr>
	
	<tr>
		<td>
			<table border="0" cellspacing="1" cellpadding="1" width="90%">
				<tr>
					<td width="50%" align="center"><b>Pemohon Informasi Publik</b></td>
					<td align="center"><b>Petugas PPID</b></td>
					
				</tr>
				<tr>
					<td width="50%" align="center" height="50px"></td>
					<td align="center">
					
					</td>
					
				</tr>
				<tr>
					<td width="50%" align="center"><b><?php echo $item_info->iden_nama; ?><!--<br />Nama dan Tanda Tangan--></b></td>
					<td align="center"><b><?php echo $item_info->penerima; ?><!--<br />Nama dan Tanda Tangan--></b></td>
					
				</tr>
			</table>
		</td>
	</tr>
	
</table>

</body>

</html>