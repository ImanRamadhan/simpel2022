<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Formulir Permohonan Informasi Publik</title>
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
			font-weight: normal;
		}

		.kop {
			font-size: 13pt;
			font-weight: bold;
		}

		.title {
			font-size: 12pt;
			font-weight: bold;
		}

		.table-outer-border {
			border: 1px solid black;
			border-collapse: collapse;
		}

		td, th {
			padding: 0px 5px;
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
		#table-hak td, th{
			padding: 10px;
		}
		.page-break {
			display: block;
			page-break-after: always;
		}
	</style>

</head>



<body>
	<div class="header">
		<table border="0" class="table-outer-border" cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="2">
					<table width="670px">
						<tr>
							<td align="center" width="120px"><img src="assets/images/bpom.png" width="120px" /></td>
							<td  align="center">
								<span class="kop"><?php echo $balai_info->kop; ?></span><br />
								<?php echo $balai_info->alamat; ?><br />
								Telepon / Fax : <?php echo $balai_info->no_telp; ?> / <?php echo $balai_info->no_fax; ?><br />
								Email : <?php echo $balai_info->email; ?>

							</td>
						</tr>
					</table>
				</td>

			</tr>
			<tr>
				<td colspan="2" align="center" height="100px">
					<div class="title">FORMULIR PERMINTAAN INFORMASI PUBLIK<br />
						No. Pendaftaran*: <?php echo $item_info->trackid; ?>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table border="0" cellspacing="2" cellpadding="1" width="90%">
						<tr>
							<td width="250px" valign="top"><b>Nama</b></td>
							<td width="10px" valign="top">:</td>
							<td valign="top"><?php echo $item_info->iden_nama; ?></td>
						</tr>
						<tr>
							<td valign="top"><b>Alamat</b></td>
							<td width="10px" valign="top">:</td>
							<td valign="top"><?php echo $item_info->iden_alamat; ?></td>
						</tr>
						<tr>
							<td valign="top"><b>Pekerjaan</b></td>
							<td width="10px" valign="top">:</td>
							<td valign="top"><?php echo $item_info->profesi; ?></td>
						</tr>
						<tr>
							<td valign="top"><b>No.Telp/Email</b></td>
							<td width="10px" valign="top">:</td>
							<td valign="top">
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
						<tr>
							<td valign="top"><b>Rincian Informasi yang Dibutuhkan</b> <br> <small>(Tambahan kertas bila diperlukan)</small> </td>
							<td width="10px" valign="top">:</td>
							<td valign="top"><?php echo $ppid_info->rincian; ?></td>
						</tr>
						<tr>
							<td valign="top"><b>Tujuan Penggunaan Informasi</b></td>
							<td width="10px" valign="top">:</td>
							<td valign="top"><?php echo $ppid_info->tujuan; ?></td>
						</tr>
					</table>

				</td>
			</tr>
			<tr>
				<td colspan="2" height="40px">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table border="0" cellspacing="1" cellpadding="1" width="90%">
						<tr>
							<td width="250px" valign="top"><b>Cara Memperoleh Informasi***</b></td>
							<td width="10px" valign="top">:</td>

							<?php
							$cara_memperoleh_info = $ppid_info->cara_memperoleh_info;
							$cara_array = array();
							if (!empty($cara_memperoleh_info)) {
								$cara = explode(',', $cara_memperoleh_info);
								foreach ($cara as $obj) {
									$cara_array[$obj] = $obj;
								}
							}
							$cara_memperoleh_array = array(
								'1' => 'Melihat/membaca/mendengarkan/mencatat',
								'2' => "Mendapatkan salinan dokumen (<i>hardcopy/softcopy</i>)"
							);

							?>

							<td valign="top">
								<?php foreach ($cara_memperoleh_array as $k => $v) : ?>
									<?php echo $k; ?> <input type="checkbox" <?php echo (array_key_exists($k, $cara_array) ? 'checked' : ''); ?> /><?php echo $v; ?><br />
								<?php endforeach; ?>


							</td>
						</tr>
						<tr>
							<td height="20px"></td>
						</tr>
						<tr>
							<td width="200px" valign="top"><b>Cara Mendapatkan Salinan Informasi***</b></td>
							<td width="10px" valign="top">:</td>
							<td> <?php echo $cara_row_1 ?> </td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td><?php echo $cara_row_2 ?> </td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td><?php echo $cara_row_3 ?> </td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="40px">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><?php echo get_city_location(); ?>,
					<?php echo convert_date3($item_info->tglpengaduan);	?>
					<br /><br />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table border="0" cellspacing="1" cellpadding="1" width="90%">
						<tr>
							<td width="50%" align="center"><b>Petugas Pelayanan Informasi<br />Penerima Permohonan</b></td>
							<td align="center"><b>Pemohon Informasi</b></td>

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
				<td colspan="2" height="150px">
					<b>Keterangan:</b><br />
					* Diisi oleh petugas berdasarkan nomor registrasi permohonan Informasi Publik<br />
					** Pilih salah satu dengan memberi tanda (<input type="checkbox" checked="checked" />)<br />
					*** Coret yang tidak perlu<br />
				</td>
			</tr>
		</table>
		<div class="page-break"></div>
		<table border="0" class="table-outer-border" id="table-hak"  width="670px" cellpadding="10" cellspacing="10" style="table-layout:fixed;">
			<tr>
				<td colspan="2" align="center" height="70px"><b>Hak-hak Pemohon Informasi<br />
						<br>
						Berdasarkan Undang-undang Nomor 14 tahun 2008 tentang <br> Keterbukaan Informasi Publik</b>
				</td>
				<td width="5%"> </td>
			</tr>
			<tr>
				<td width="5%" valign="top">I.</td>
				<td align="justify" width="95%px">
					<b>Pemohon Informasi berhak untuk meminta seluruh informasi yang berada di Badan Publik kecuali</b> (a) informasi yang apabila dibuka dan diberikan kepada pemohon informasi dapat: Menghambat proses penegakan hukum; Mengganggu kepentingan perlindungan hak atas kekayaan intelektual dan perlindungan dari persaingan usaha tidak sehat; Membahayakan pertahanan dan keamanan Negara; Mengungkap kekayaan alam Indonesia; Merugikan ketahanan ekonomi nasional; Merugikan kepentingan hubungan luar negeri; Mengungkap isi akta otentik yang bersifat pribadi dan kemauan terakhir ataupun wasiat seseorang; Mengungkap rahasia pribadi; Memorandum atau surat-surat antar Badan Publik atau intra Badan Publik yang menurut sifatnya dirahasiakan kecuali atas putusan Komisi Informasi atau Pengadilan; Informasi yang tidak boleh diungkapkan berdasarkan Undang-undang. (b) Badan Publik juga dapat tidak memberikan informasi yang belum dikuasai atau didokumentasikan.
				</td>
				<td width="5%"> </td>
			</tr>
			<tr>
				<td valign="top">II.</td>
				<td align="justify">
					<b>PASTIKAN ANDA MENDAPAT TANDA TERIMA PERMINTAAN INFORMASI BERUPA NOMOR PENDAFTARAN KE PETUGAS INFORMASI/PPID.</b> Bila tanda terima tidak diberikan tanyakan kepada petugas informasi alasannya, mungkin permintaan informasi anda kurang lengkap.
				</td>
				<td width="5%"> </td>
			</tr>
			<tr>
				<td valign="top">III.</td>
				<td align="justify">
					Pemohon Informasi berhak untuk mendapatkan <b>pemberitahuan tertulis</b> atas diterima atau tidaknya permohonan informasi dalan jangka waktu<b> 10 (sepuluh) hari kerja</b> sejak diterimanya permohonan informasi oleh Badan Publik. Badan Publik dapat memperpanjang waktu untuk memberi jawaban tertulis<b> 1 x 7 hari kerja</b>, dalam hal: informasi yang diminta belum dikuasai/didokumentasikan/ belum dapat diputuskan apakah informasi yang diminta termasuk informasi yang dikecualikan atau tidak.
				</td>
				<td width="5%"> </td>
			</tr>
			<tr>
				<td valign="top">IV.</td>
				<td align="justify">
					<b>Biaya</b> yang dikenakan bagi permintaan atas salinan informasi berdasarkan surat keputusan Pimpinan Badan Publik adalah (diisi sesuai dengan surat keputusan Pimpinan Badan Publik)
				</td>
				<td width="5%"> </td>
			</tr>
			<tr>
				<td colspan="2" height="15px"></td>
				<td width="5%"> </td>
			</tr>
			<tr style="border-bottom: 1px solid black;">
				<td></td>
				<td style="border-bottom: 1px solid black;"></td>
				<td width="5%"> </td>
			</tr>
			<tr>
				<td colspan="2" height="15px"></td>
				<td width="5%"> </td>
			</tr>
			<tr style="border-bottom: 1px solid black;">
				<td></td>
				<td style="border-bottom: 1px solid black;"></td>
				<td width="5%"> </td>
			</tr>
			<tr>
				<td colspan="2" height="15px"></td>
				<td width="5%"> </td>
			</tr>
			<tr style="border-bottom: 1px solid black;">
				<td></td>
				<td style="border-bottom: 1px solid black;"></td>
				<td width="5%"> </td>
			</tr>
			<tr>
				<td valign="top">V.</td>
				<td align="justify">
					Apabila <b>Pemohon Informasi tidak puas dengan keputusan Badan Publik (misal: menolak permintaan Anda atau memberikan hanya sebagian yang diminta)</b>, maka pemohon informasi dapat mengajukan <b>keberatan</b> kepada <b>atasan PPID</b> dalam jangka waktu <b>30 (tiga puluh) hari kerja</b> sejak permohonan informasi ditolak/ditemukannya alasan keberatan lainnya. Atasan PPID wajib memberikan tanggapan tertulis atas keberatan yang diajukan Pemohon Informasi selambat-lambatnya <b>30 (tiga puluh) hari kerja</b> sejak diterima/dicatatnya pengajuan keberatan dalam register keberatan.
				</td>
				<td width="5%"> </td>
			</tr>
			<tr>
				<td valign="top">VI.</td>
				<td align="justify">
					Apabila Pemohon Informasi tidak puas dengan keputusan Atasan PPID, maka pemohon informasi dapat mengajukan <b>keberatan </b>kepada <b>Komisi Informasi</b> dalam jangka waktu <b>14 (empat belas) hari kerja</b> sejak diterimanya keputusan atasan PPID oleh Pemohon Informasi Publik.
				</td>
				<td width="5%"> </td>
			</tr>
			</tr>
		</table>
</body>

</html>