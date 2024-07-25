<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
			font-weight: normal;
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
			font-weight: bold;
		}

		.title {
			font-size: 12pt;
			font-weight: bold;
		}

		.footer {
			position: absolute;
			bottom: 0;
		}

		.header {}

		.content {}

		input[type='checkbox'] {
			vertical-align: bottom;

		}

		.page-break {
			display: block;
			page-break-after: always;
		}
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
								<span class="kop">FORMULIR LAYANAN PENGADUAN MASYARAKAT DAN INFORMASI <br />OBAT DAN MAKANAN
								</span>
							</td>
						</tr>
					</table>
					(Catatan Identitas Pelapor Akan Dirahasiakan)

				</td>
			</tr>
			<tr>
				<th colspan="2" align="center" height="30">IDENTITAS PELAPOR</th>
			</tr>
			<tr>
				<td colspan="2">
					<table border="0" cellspacing="2" cellpadding="1">
						<tr>
							<td width="100" valign="top">Nama/Umur</td>
							<td width="2" valign="top">:</td>
							<td width="150" valign="top"><?php echo $item_info->iden_nama; ?><?php echo $item_info->usia == 0 ? '' : ' / ' . $item_info->usia; ?></td>
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

				</td>
			</tr>
			<tr>
				<th colspan="2" align="center" height="30">IDENTITAS PRODUK</th>
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
							<td width="150" valign="top"><?php echo $item_info->prod_kadaluarsa == '0000-00-00' ? '' : $item_info->prod_kadaluarsa; ?>&nbsp;</td>
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
					<div align="center"><b>ISI PERTANYAAN / PENGADUAN</b></div>
				</td>
			</tr>
			<tr>
				<td valign="center"  height="80" colspan="2">
					<p><?php echo $item_info->prod_masalah; ?></p>&nbsp;
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<div valign="center" align="center"><b>TINDAK LANJUT</b></div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table width="100%" border="0" cellspacing="2" cellpadding="1">
						<tr >
							<td valign="top">Rujukan</td>
							<td valign="top">:</td>
							<td valign="top" style="padding :0px 0px 10px 0px">
								<?php if($item_info->is_rujuk == 1) : ?>
								
								<?php echo strlen($item_info->rujukan1) > 0 ? "$item_info->rujukan1 <strong>[$item_info->rujukan_hk1 HK] </strong>" : "" ?>
								<?php echo strlen($item_info->rujukan2) > 0 ? "<br> $item_info->rujukan2 <strong>[$item_info->rujukan_hk2 HK] </strong> " : "" ?>
								<?php echo strlen($item_info->rujukan3) > 0 ? "<br> $item_info->rujukan3 <strong>[$item_info->rujukan_hk3 HK] </strong> " : "" ?>
								<?php echo strlen($item_info->rujukan4) > 0 ? "<br> $item_info->rujukan4 <strong>[$item_info->rujukan_hk4 HK] </strong> " : "" ?>
								<?php echo strlen($item_info->rujukan5) > 0 ? "<br> $item_info->rujukan5 <strong>[$item_info->rujukan_hk5 HK] </strong> " : "" ?>
								<?php else : ?>
									Tidak Dirujuk
								<?php endif ?>
							</td>
						</tr>

						<tr>
							<td  width="10%" valign="top">Jawaban</td>
							<td width="2%" valign="top">:</td>
							<td width="88%" valign="top"><?php echo  $item_info->jawaban; ?></td>
						</tr>
						
						<tr height="30"></tr>
						<tr>
							<td colspan="3"  valign="top"> <strong> Keterangan </strong></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="50%" align="center"><b>Pelapor</b> </td>
				<td align="center"><b>Petugas</b></td>
			</tr>
			<tr>
				<td width="50%" valign="bottom" align="center" height="100"><?php echo $item_info->iden_nama; ?> </td>
				<td width="" valign="bottom" align="center"><?php echo $item_info->petugas_entry; ?>
			</tr>
		</table>

</body>

</html>