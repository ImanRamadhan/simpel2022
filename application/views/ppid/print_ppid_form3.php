<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Formulir Penolakan</title>
<style>

@page {
	margin-top: 0.75cm;
	margin-bottom: 0.5cm;
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
.result {
	border: 1px solid black;
	padding: 7px;
	text-align: center;
	margin: auto;
	font-weight: bold;
	width: 250px;
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
		<td align="center" height="100px">
			<div class="title">SURAT PEMBERITAHUAN PPID TENTANG PENOLAKAN PERMOHONAN INFORMASI<br />
			No. Pendaftaran*: <?php echo $item_info->trackid; ?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellspacing="2" cellpadding="1" width="90%">
				<tr>
					<td width="200px"><b>Nama</b></td>
					<td width="10px">:</td>
					<td><?php echo $item_info->iden_nama; ?></td>
				</tr>
				<tr>
					<td width="200px" valign="top"><b>Alamat</b></td>
					<td width="10px" valign="top">:</td>
					<td><?php echo $item_info->iden_alamat; ?></td>
				</tr>
				<tr>
					<td width="200px"><b>No.Telp/Email</b></td>
					<td width="10px">:</td>
					<td>
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
					<td width="200px" valign="top"><b>Rincian Informasi yang Dibutuhkan</b></td>
					<td width="10px" valign="top">:</td>
					<td valign="top"><?php echo $ppid_info->rincian; ?></td>
				</tr>
				<tr>
					<td width="200px" valign="top"><b>Tujuan Penggunaan Informasi</b></td>
					<td width="10px" valign="top">:</td>
					<td valign="top"><?php echo $ppid_info->tujuan; ?></td>
				</tr>
			</table>
		
		</td>
	</tr>
	<tr>
		<td height="20px" valign="middle">
		
		</td>
	</tr>
	<tr>
		<td>
		<b>PPID memutuskan bahwa informasi yang dimohon adalah:</b><br />
		</td>
	</tr>
	<tr>
		<td align="center" height="40px">
			<div class="result">INFORMASI YANG DIKECUALIKAN</div>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellspacing="1" cellpadding="1" width="90%">
				<tr>
					<td width="250px" valign="top">Pengecualian Informasi didasarkan pada alasan</td>
					<td width="10px" valign="top">:</td>
					<td width="20px" valign="top"><input type="checkbox" <?php echo ($ppid_info->pengecualian_pasal17?'checked':''); ?> /></td>
					<td valign="top">Pasal 17 Huruf <?php echo $ppid_info->pasal17_huruf;?> UU KIP**
					</td>
				</tr>
				<tr>
					<td width="250px" valign="top"></td>
					<td width="10px" valign="top"></td>
					<td width="20px" valign="top"><input type="checkbox" <?php echo ($ppid_info->pengecualian_pasal_lain?'checked':''); ?> /></td>
					<td valign="top"><?php echo $ppid_info->pasal_lain_uu;?>***
					
					</td>
				</tr>
				
			</table>
		</td>
	</tr>
	<tr>
		<td height="20px" valign="middle">
		
		</td>
	</tr>
	<tr>
		<td align="justify">Bahwa berdasarkan Pasal-Pasal di atas, membuka informasi tersebut dapat menimbulkan konsekuensi sebagai berikut: <br />
			<u><?php echo $ppid_info->konsekuensi?></u><br /><br />

			Dengan demikian menyatakan bahwa:
			<br />
		</td>
	</tr>
	<tr>
		<td align="center" height="40px">
			<div class="result">PERMOHONAN INFORMASI DITOLAK</div>
		</td>
	</tr>
	<tr>
		<td align="justify">Jika Pemohon Informasi keberatan atas penolakan ini maka Pemohon Informasi dapat mengajukan keberatan kepada Atasan PPID selambat-lambatnya 30 (tiga puluh) hari kerja sejak menerima Surat Pemberitahuan ini.
			<br />
		</td>
	</tr>
	<tr>
		<td height="20px">
		</td>
	</tr>
	<tr>
		<td align="center">
			(<?php echo get_city_location(); ?>, <?php echo convert_date3($ppid_info->tgl_pemberitahuan_tertulis);?>)****<br />
			<b>Pejabat Pengelola lnformasi dan Dokumentasi (PPID)<br />Bidang Pelayanan Informasi</b>
			<br />
		</td>
	</tr>
	<tr>
		<td height="70px">
		</td>
	</tr>
	<tr>
		<td align="center">
			<?php echo $ppid_info->nama_pejabat_ppid;?>
		</td>
	</tr>
	
	<tr>
		<td height="150px">
			Keterangan:<br />
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>*</td><td>Diisi oleh petugas berdasarkan nomor pendaftaran permohonan Informasi Publik.</td>
				</tr>
				<tr>
					<td>**</td><td>Diisi oleh PPID sesuai dengan pengecualian pada Pasal 17 huruf a-i UU KIP.</td>
				</tr>
				<tr>
					<td>***</td><td>Sesuai dengan Pasal 17 huruf j UU KIP, diisi oleh PPID sesuai dengan pasal pengecualian dalam Undang-Undang lain yang mengecualikan informasi yang dimohon tersebut (sebutkan padal dan Undang-Undangnya.</td>
				</tr>
				<tr>
					<td>****</td><td>Diisi oleh petugas dengan memperhatikan batas tentang jangka waktu pemberitahuan tertulis sebagaimana diatur dalam UU KIP dan Peraturan ini.</td>
				</tr>
			</table>
			
		</td>
	</tr>
</table>

</body>

</html>