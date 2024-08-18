<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Formulir Pemberitahuan Tertulis PPID</title>
	<style>
		@page {
			margin-top: 0.4cm;
			margin-bottom: 0.1cm;
			margin-left: 2cm;
			margin-right: 1.5cm;
		}

		body {
			font-family: "Times New Roman", Times, serif;
			font-size: 10pt;
			font-weight: normal;
		}

		.kop {
			font-size: 11pt;
			font-weight: bold;
		}

		.title {
			font-size: 10pt;
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

		.mytable {
			border-collapse: collapse;
		}

		.mytable,
		td.mytd {
			border: 1px solid black;
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
	<table border="0" style="background-color: #ffffff; filter: alpha(opacity=40); opacity: 0.95;border:1px black solid;" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td align="center" valign="middle" style="padding-left:30px;margin:30px" width="90px">
							<img src="assets/images/bpom.png" width="90px" />
						</td>

						<td align="center" width="600px">
							<span class="kop"><?php echo $balai_info->kop; ?></span><br />
							<?php echo $balai_info->alamat; ?><br />
							Telepon /Fax. <?php echo $balai_info->no_telp; ?> / <?php echo $balai_info->no_fax; ?><br />
							<i>Email : </i><?php echo $balai_info->email; ?>

						</td>
					</tr>

				</table>

			</td>
		</tr>
		<tr>
			<td align="center" height="30px">
				<div class="title">PEMBERITAHUAN TERTULIS
				</div>
			</td>
		</tr>
		<tr>
			<?php
			$date = explode(' ', convert_date3($ppid_info->tgl_diterima));
			$tanggal = $date[0];
			$bulan	= $date[1];
			$tahun = $date[2];
			?>
			<td style="padding: 5px 5px 5px 5px" align="justify">
				Berdasarkan permintaan informasi pada tanggal <?= $tanggal	?> bulan <?= $bulan ?> tahun <?= $tahun ?> dengan nomor pendaftaran* <?php echo $item_info->trackid; ?><br />
				Kami menyampaikan kepada Saudara/i:
				<br />
			</td>
		</tr>
		<tr>
			<td height="5px" valign="middle"></td>
		</tr>
		<tr>
			<td style="padding: 5px 5px 5px 5px">
				<table border="0" cellspacing="1" cellpadding="1" width="100%">
					<tr>
						<td width="200px">Nama</td>
						<td width="10px">:</td>
						<td><?php echo $item_info->iden_nama; ?></td>
					</tr>
					<tr>
						<td valign="top">Alamat</td>
						<td width="10px" valign="top">:</td>
						<td valign="top"><?php echo $item_info->iden_alamat; ?></td>
					</tr>
					<tr>
						<td>No.Telp/Email</td>
						<td width="10px">:</td>
						<td>
							<?php
							$arr = array();
							if (!empty($item_info->iden_telp))
								array_push($arr, $item_info->iden_telp);

							if (!empty($item_info->iden_email))
								array_push($arr, $item_info->iden_email);

							if (count($arr) > 0)
								echo implode('/', $arr);

							?>
						</td>
					</tr>

				</table>
			</td>
		</tr>
		<tr>
			<td height="20px" style="border-bottom: 1px solid black;" valign="middle">

			</td>
		</tr>
		<tr>
			<td height="20px" valign="middle">

			</td>
		</tr>
		<tr>
			<td style="padding: 5px 5px 5px 5px">
				Pemberitahuan sebagai berikut:
			</td>
		</tr>

		<tr>
			<td style="padding: 5px 5px 5px 5px">
				<b>A. Informasi dapat diberikan</b>
				<table class="mytable" border="0" cellspacing="0" cellpadding="1" width="100%">
					<tr>
						<td class="mytd" width="5%" align="center"><b>No.</b></td>
						<td class="mytd" width="30%" align="center"><b>Hal-hal terkait Informasi Publik</b></td>
						<td class="mytd" colspan="2" align="center"><b>Keterangan</b></td>
					</tr>
					<tr>
						<td class="mytd" valign="top" align="center">1.</td>
						<td class="mytd" valign="top">Penguasaan Informasi Publik**</td>
						<td class="mytd" colspan="2">

							<table width="100%">
								<tr>
									<td width="5%" valign="top"><input type="checkbox" <?php echo $ppid_info->penguasaan_kami ? 'checked' : ''; ?> /></td>
									<td width="95%">Kami:<br /><?php echo $ppid_info->penguasaan_kami_teks; ?>

									</td>
								</tr>
								<tr>
									<td width="5%" valign="top"><input type="checkbox" <?php echo $ppid_info->penguasaan_badan_lain ? 'checked' : ''; ?> /></td>
									<td width="95%">Badan Publik Lain<br /><?php echo $ppid_info->nama_badan_lain; ?>
									</td>
								</tr>
							</table>

						</td>
					</tr>
					<tr>
						<td class="mytd" valign="top" align="center">2.</td>
						<td class="mytd" valign="top">Bentuk fisik yang tersedia**</td>
						<td class="mytd" colspan="2">

							<?php
							$bentuk_fisik = $ppid_info->bentuk_fisik;
							$bentuk_fisik_tokens = array();
							if (!empty($bentuk_fisik)) {
								$tokens = explode(',', $bentuk_fisik);
								foreach ($tokens as $obj) {
									$bentuk_fisik_tokens[$obj] = $obj;
								}
							}

							$bentuk_fisik_array = array(
								'1' => "<i>Softcopy</i> (termasuk rekaman)",
								'2'	=> "<i>Hardcopy</i> / Salinan Tertulis"
							);

							?>
							<?php foreach ($bentuk_fisik_array as $k => $v) : ?>
								<input type="checkbox" <?php echo (array_key_exists($k, $bentuk_fisik_tokens) ? 'checked' : ''); ?> /><?php echo $v; ?><br />
							<?php endforeach; ?>

						</td>
					</tr>
					<tr>
						<td class="mytd" valign="top" align="center">3.</td>
						<td class="mytd" valign="top">Biaya yang dibutuhkan***</td>
						<td class="mytd" width="15%" valign="top"><input type="checkbox" <?php echo ($ppid_info->penyalinan ? 'checked' : ''); ?> /> Penyalinan</td>
						<td class="mytd"> Rp. <?php echo ($ppid_info->biaya_penyalinan > 0) ? number_format($ppid_info->biaya_penyalinan) : '-'; ?> X <?php echo $ppid_info->biaya_penyalinan_lbr; ?> (jml lembaran) = Rp. <?php echo ($ppid_info->biaya_penyalinan > 0) ? number_format($ppid_info->biaya_penyalinan * $ppid_info->biaya_penyalinan_lbr) : '-'; ?></td>
					</tr>
					<tr>
						<td class="mytd" valign="top"></td>
						<td class="mytd" valign="top"></td>
						<td class="mytd" valign="top"><input type="checkbox" <?php echo ($ppid_info->pengiriman ? 'checked' : ''); ?> /> Pengiriman</td>
						<td class="mytd"> Rp. <?php echo ($ppid_info->biaya_pengiriman > 0) ? number_format($ppid_info->biaya_pengiriman) : '-'; ?></td>
					</tr>
					<tr>
						<td class="mytd" valign="top"></td>
						<td class="mytd" valign="top"></td>
						<td class="mytd" valign="top"><input type="checkbox" <?php echo ($ppid_info->lain_lain ? 'checked' : ''); ?> /> Lain-lain</td>
						<td class="mytd"> Rp. <?php echo ($ppid_info->biaya_lain > 0) ? number_format($ppid_info->biaya_lain) : '-'; ?></td>
					</tr>
					<tr>
						<td class="mytd" valign="top"></td>
						<td class="mytd" valign="top"></td>
						<td class="mytd" valign="top"> Jumlah</td>
						<td class="mytd"> Rp. <?php echo !empty($ppid_info->biaya_total) ? number_format($ppid_info->biaya_total) : '-'; ?></td>
					</tr>
					<tr>
						<td class="mytd" valign="top" align="center">4.</td>
						<td class="mytd" valign="top">Waktu penyediaan</td>
						<td class="mytd" colspan="2"> <?php echo !empty($ppid_info->waktu_penyediaan) ? $ppid_info->waktu_penyediaan : " - "; ?> Hari </td>
					</tr>
					<tr>
						<td class="mytd" valign="top" align="center">5.</td>
						<td class="mytd" colspan="3" valign="top">Penjelasan penghitaman/pengaburan Informasi yang dimohon**** (tambahkan kertas bila perlu)<br /><br />
							<?php echo $ppid_info->penghitaman; ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="padding: 5px 5px 5px 5px"><b>B. Informasi tidak dapat diberikan karena:**</b><br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php echo ($ppid_info->info_blm_dikuasai ? 'checked' : ''); ?> /> Informasi yang diminta belum dikuasai<br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php echo ($ppid_info->info_blm_didoc ? 'checked' : ''); ?> /> Informasi yang diminta belum didokumentasikan<br />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Penyediaan informasi yang belum didokumentasikan dilakukan dalam jangka waktu <?php echo !empty($ppid_info->waktu_penyediaan2) ? $ppid_info->waktu_penyediaan2 : '...'; ?> Hari Kerja *****
			</td>
		</tr>


		<tr>
			<td height="5px" style="padding: 5px 5px 5px 5px">
			</td>
		</tr>
		<tr>
			<td>
				<table border="0" width="100%">
					<tr>
						<td width="50%"></td>
						<td align="center">
							<?php echo get_city_location(); ?>, <?php echo convert_date3($ppid_info->tgl_pemberitahuan_tertulis); ?><br />
							<b>Pejabat Pengelola lnformasi dan Dokumentasi (PPID)<br />Bidang Pelayanan Informasi</b>
							<br />
						</td>
					</tr>
					<tr>
						<td height="50px" colspan="2">
						</td>
					</tr>
					<tr>
						<td width=""></td>
						<td align="center">
							<?php echo $ppid_info->nama_pejabat_ppid; ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>


		<tr>
			<td style="padding: 5px 5px 5px 5px">
				Keterangan:<br />
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>*</td>
						<td>Diisi sesuai dengan nomor pendaftaran pada formulir permohonan.</td>
					</tr>
					<tr>
						<td>**</td>
						<td>Pilih salah satu dengan memberi tanda (<input type="checkbox" checked />).</td>
					</tr>
					<tr>
						<td>***</td>
						<td>Biaya penyalinan (fotokopi atau disket) dan/atau biaya pengiriman (khusus kurir dan pos) sesuai dengan standar biaya yang telah ditetapkan.</td>
					</tr>
					<tr>
						<td>****</td>
						<td>Jika ada penghitaman informasi dalam suatu dokumen, maka diberikan alasan penghitamannya.</td>
					</tr>
					<tr>
						<td>*****</td>
						<td>Diisi dengan keterangan waktu yang jelas untuk menyediakan informasi yang diminta.</td>
					</tr>
				</table>

			</td>
		</tr>
	</table>
</body>

</html>