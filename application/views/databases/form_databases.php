<?php $this->load->view("partial/header"); ?>

<script src="<?php echo base_url() ?>assets/js/jquery.fileDownload.js"></script>
<style>

</style>


<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Database</a></li>
					<li class="breadcrumb-item active">Database</li>
				</ol>
			</div>
			<h4 class="page-title"></h4>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#buttonExport').on('click', function(event) {
			event.preventDefault();
			//alert('Export');

			$.fileDownload('<?php echo site_url('excels/download_databases') ?>', {
				data: {
					tgl1: $("#tgl1").val(),
					tgl2: $("#tgl2").val(),
					kota: $("#kota").val() || "",
					direktorat: $("#direktorat").val() || "",
					kategori: $("#kategori").val(),
					datasource: $("#datasource").val(),
					type: "1",
					<?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
				},
				httpMethod: 'POST',
				success: function(Result) {

				},
				error: function(request, status, error) {
					//alert('error');
					// alert(request.responseText);
				}
			});
		});
		/*if($.remember({ name: 'database.tgl1' }) != null) 
	{
		$('#tgl1').val($.remember({ name: 'database.tgl1' }));
	}
	else
	{
		$("#tgl1").val("<?php echo date('01/m/Y'); ?>");
	}
	
	if($.remember({ name: 'database.tgl2' }) != null) 
	{
		$('#tgl2').val($.remember({ name: 'database.tgl2' }));
	}
	else
	{
		$("#tgl2").val("<?php echo date('d/m/Y'); ?>");
    }*/
	});
</script>

<style>
	.table-responsive {
		height: 450px;
		overflow: scroll;
	}

	thead tr:nth-child(1) th {
		position: sticky;
		position: -webkit-sticky;
		top: 0;
		z-index: 100;

	}

	thead tr.second th {
		position: sticky;
		position: -webkit-sticky;
		z-index: 100;

	}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header bg-primary text-white">
				<?php echo $title; ?>
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
				</div>
				<?php
				$this->load->view("databases/component-databases-header");
				?>
				<div id="table_holder">

					<div class="card-header bg-primary text-white">
						Database Layanan

					</div>
					<table id="DatatableResp" class="table table-responsive table-striped table-bordered">
						<thead>
							<tr class="first">
								<th style="vertical-align : middle;text-align:center;">No</th>
								<th style="vertical-align : middle;text-align:center;">ID Layanan</th>
								<th style="vertical-align : middle;text-align:center;">Nama Konsumen</th>
								<th style="vertical-align : middle;text-align:center;">Gender</th>
								<th style="vertical-align : middle;text-align:center;">Alamat Konsumen</th>
								<th style="vertical-align : middle;text-align:center;">Telp</th>
								<th style="vertical-align : middle;text-align:center;">Email</th>

								<th style="vertical-align : middle;text-align:center; width:200px" class="">Permintaan Informasi</th>
								<th style="vertical-align : middle;text-align:center; width:200px" class="">Pengaduan</th>
								<th style="vertical-align : middle;text-align:center; width:200px" class="">Jawaban Permintaan Informasi</th>
								<th style="vertical-align : middle;text-align:center; width:200px" class="">Jawaban Pengaduan</th>
								<th style="vertical-align : middle;text-align:center;">Keterangan</th>
								<th style="vertical-align : middle;text-align:center;">Jenis Komoditi</th>
								<th style="vertical-align : middle;text-align:center;">Penerima</th>
								<th style="vertical-align : middle;text-align:center;">Isu Topik</th>
								<th style="vertical-align : middle;text-align:center;">Klasifikasi</th>
								<th style="vertical-align : middle;text-align:center;">Sub Klasifikasi</th>
								<th style="vertical-align : middle;text-align:center;">Pekerjaan</th>
								<th style="vertical-align : middle;text-align:center;">Sarana</th>
								<th style="vertical-align : middle;text-align:center;">Waktu</th>
								<th style="vertical-align : middle;text-align:center;">Shift</th>

								<th style="vertical-align : middle;text-align:center;">Petugas Input</th>
								<!--<th  colspan="2">Rujuk/Tindak Lanjut</th>-->
								<th style="vertical-align : middle;text-align:center;">Status TL</th>
								<th style="vertical-align : middle;text-align:center;">Tanggal TL</th>
								<th style="vertical-align : middle;text-align:center;">Status Verifikasi</th>
								<th style="vertical-align : middle;text-align:center;">Tanggal Verifikasi</th>
								<th style="vertical-align : middle;text-align:center;">Verifikator</th>
								<th style="vertical-align : middle;text-align:center;">Tgl Layanan</th>
								<th style="vertical-align : middle;text-align:center;">Tgl Closed</th>
								<th style="vertical-align : middle;text-align:center;">Status FB</th>
								<th style="vertical-align : middle;text-align:center;">Tanggal FB</th>
								<th style="vertical-align : middle;text-align:center;">Waktu Layanan</th>
								<th style="vertical-align : middle;text-align:center;">Memenuhi SLA</th>
								<th style="vertical-align : middle;text-align:center;">Balai/Loka</th>
							</tr>
							<!--<tr class="second">
								<th style="vertical-align : middle;text-align:center;">Nama</th>
								<th style="vertical-align : middle;text-align:center;">Alamat</th>
								<th style="vertical-align : middle;text-align:center;">Telp</th>
								<th style="vertical-align : middle;text-align:center;">Email</th>
                                <th style="vertical-align : middle;text-align:center; width:200px" class="">Permintaan Informasi</th>
                                <th style="vertical-align : middle;text-align:center; width:200px" class="">Pengaduan</th>
                                <th style="vertical-align : middle;text-align:center; width:200px" class="">Permintaan Informasi</th>
                                <th style="vertical-align : middle;text-align:center; width:200px" class="">Pengaduan</th>
                                <!--<th style="vertical-align : middle;text-align:center;"> < 3 HR</th>
                                <th style="vertical-align : middle;text-align:center;">> 3 HR</th>-->
							<!--</tr>-->
						</thead>
						<tbody>
							<?php
							$i = 0;
							foreach ($layanan as $row):
							?>
								<tr>
									<td><?php echo ++$i; ?></td>
									<td style="width:10%" nowrap><?php echo $row['trackid']; ?></td>
									<td style="width:10%"><?php echo $row['iden_nama']; ?></td>
									<td style="width:10%"><?php echo $row['iden_jk']; ?></td>
									<td style="width:10%"><?php echo $row['iden_alamat']; ?></td>
									<td style="width:10%"><?php echo $row['iden_telp']; ?></td>
									<td style="width:10%"><?php echo $row['iden_email']; ?></td>
									<td style="width:200px" class=""><?php echo ($row['info'] == 'I') ? $row['detail_laporan'] : ''; ?></td>
									<td width="width:200px" class=""><?php echo ($row['info'] == 'P') ? $row['detail_laporan'] : ''; ?></td>
									<td width="width:200px" class=""><?php echo ($row['info'] == 'I') ? $row['jawaban'] : ''; ?></td>
									<td width="width:200px" class=""><?php echo ($row['info'] == 'P') ? $row['jawaban'] : ''; ?></td>
									<td><?php echo $row['keterangan']; ?></td>
									<td><?php echo $row['jenis_komoditi']; ?></td>
									<td><?php echo $row['penerima']; ?></td>

									<td><?php echo $row['isu_topik']; ?></td>
									<td><?php echo $row['klasifikasi']; ?></td>
									<td><?php echo $row['subklasifikasi']; ?></td>
									<td><?php echo $row['pekerjaan']; ?></td>
									<td><?php echo $row['sarana']; ?></td>
									<td><?php echo $row['waktu']; ?></td>
									<td><?php echo $row['shift']; ?></td>

									<td><?php echo $row['petugas_entry']; ?></td>
									<!--<td><?php /*
							if(!empty($row['rujuk_tl_hr']))
								echo ($row['rujuk_tl_hr'] <= 3)?'Ya':'Tidak'; 
							else
								echo 'Tidak';*/
											?></td>-->

									<!--<td><?php /*
							if(!empty($row['rujuk_tl_hr']))
								echo ($row['rujuk_tl_hr'] > 3)?'Ya':'Tidak'; 
							else
								echo 'Tidak';*/
											?></td>-->
									<td><?php echo $row['tl'] ? 'Sudah' : 'Belum'; ?></td>
									<td><?php echo $row['tl_date']; ?></td>
									<td><?php echo $row['is_verified'] ? 'Sudah' : 'Belum'; ?></td>
									<td><?php echo $row['verified_date']; ?></td>
									<td><?php echo $row['verificator_name']; ?></td>

									<td><?php echo $row['tglpengaduan']; ?></td>
									<td><?php echo $row['closed_date']; ?></td>
									<td><?php echo $row['fb'] ? 'Sudah' : 'Belum'; ?></td>
									<td><?php echo $row['fb_date']; ?></td>
									<td align="center"><?php echo $row['hk']; ?></td>
									<td align="center"><?php

														if ($row['hk'] <= $row['sla']) {
															echo 'Y';
														} else
															echo 'N';

														?></td>
									<td align="center"><?php echo $row['kota']; ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		//var firstheight = $('.first').height();
		//     $("thead tr.second th").css("top", firstheight-2);
	});
</script>
<?php $this->load->view("partial/footer"); ?>