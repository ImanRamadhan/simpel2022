<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Tanggapan Tertulis</title>
<style>

@page {
	margin-top: 1.5cm;
	margin-bottom: 0.6cm;
	margin-left: 2cm;
	margin-right: 1.5cm;
}
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11pt;
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
    bottom: -35px;
	left: -75px;
}
.header {
	position: fixed;
	top: -25px;
	left: -70px;
	right: 0px;
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
	<img src="assets/images/kop/<?php echo $kop_image; ?>" width="800px" />
</div>
<div style="height:120px">
</div>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	
	<tr>
		<td>
			<table border="0" cellspacing="2" cellpadding="1" width="100%">
				<tr>
					<td width="10%"></td>
					<td width="2%"></td>
					<td ></td>
					<td width="30%" nowrap align="right"><?php echo get_city_location(); ?>, <?php echo convert_date3(date('Y-m-d'));	?></td>
				</tr>
				<tr>
					<td width="">Nomor</td>
					<td width="">:</td>
					<td><?php echo $ppid_info->tt_nomor;?></td>
					<td width=""></td>
				</tr>
				<tr>
					<td width="">Lampiran</td>
					<td width="">:</td>
					<td><?php echo $ppid_info->tt_lampiran;?></td>
					<td width=""></td>
				</tr>
				<tr>
					<td width="">Perihal</td>
					<td width="">:</td>
					<td>Tanggapan Permohonan Informasi Publik</td>
					<td width=""></td>
				</tr>
				
				
			</table>
		
		</td>
	</tr>
	<tr>
		<td height="40px">
		</td>
	</tr>
	<tr>
		<td align="justify">
			Yth. <br />
Sdr/i. <?php echo $item_info->iden_nama; ?><br />
di tempat<br /><br /><br />

<p>Sehubungan dengan permohonan informasi Saudara melalui Pejabat Pengelola Informasi dan Dokumentasi (PPID) Badan POM perihal <?php echo $ppid_info->tt_perihal;?>, bersama ini kami sampaikan hal-hal sebagai berikut:</p>
<p><?php echo $ppid_info->tt_isi;?></p>

<p>Demikian kami sampaikan, atas perhatiannya diucapkan terima kasih.</p>

		</td>
	</tr>
	<tr>
		<td height="30px">
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellspacing="1" cellpadding="1" width="90%">
				<tr>
					<td width="50%" align="center"></td>
					<td align="center">Koordinator PPID Badan POM</td>
					
				</tr>
				<tr>
					<td width="50%" align="center" height="70px"></td>
					<td align="center"></td>
					
				</tr>
				<tr>
					<td width="50%" align="center"></td>
					<td align="center"><?php echo $ppid_info->tt_pejabat;?></td>
					
				</tr>
			</table>
		</td>
	</tr>
	
</table>
<div class="footer">
	<img src="assets/images/footer1.png" width="815px" />
</div>
</body>

</html>