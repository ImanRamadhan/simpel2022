<?php $this->load->view("partial/header"); ?>
<script src="<?php echo base_url() ?>assets/js/jquery.fileDownload.js"></script>
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Lapsing</a></li>
					<li class="breadcrumb-item active"><?php echo $breadcrumb; ?></li>
				</ol>
			</div>
			<h4 class="page-title"></h4>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#buttonExport').on('click', function() {
			$.fileDownload('<?php echo site_url('excels/download_lapsing') ?>', {

				data: {
					formType: '<?php echo $lapsing_type; ?>',
					tgl1: moment($("#tgl1").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
					tgl2: moment($("#tgl2").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
					kota: $("#kota").val() || "",
					formType: $("#formType").val(),
					kategori: $("#kategori").val(),
					jenis: $("#jenis").val() || "",
					gender: $("#gender").val(),
					<?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
				},
				httpMethod: 'POST',
				success: function(Result) {},
				error: function(request, status, error) {
					//alert('error');
					// alert(request.responseText);
				}
			});
		});

		$('#process_btn').on('click', function() {

			$('#lapsing_form').submit();

		});

		if ($.remember({
				name: 'lapsing.tgl1'
			}) != null) {
			$('#tgl1').val($.remember({
				name: 'lapsing.tgl1'
			}));
		} else {
			$("#tgl1").val("<?php echo date('01/m/Y'); ?>");
		}

		if ($.remember({
				name: 'lapsing.tgl2'
			}) != null) {
			$('#tgl2').val($.remember({
				name: 'lapsing.tgl2'
			}));
		} else {
			$("#tgl2").val("<?php echo date('d/m/Y'); ?>");
		}

	});
</script>


<div class="row">
	<div class="col-md-12">
		<div class="card ">
			<div class="card-header bg-primary text-white">
				<?php echo $title; ?>
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right"></div>
				<?php
				if ($lapsing_type == 'LAPSING_RUJUKAN_MASUK') {
					$this->load->view("lapsingv2/lapsing_header_rujukan");
				} else if ($lapsing_type == 'LAPSING_RUJUKAN_KELUAR') {
					$this->load->view("lapsingv2/lapsing_header_rujukan");
				} else {
					$this->load->view("lapsingv2/lapsing_header");
				}
				?>
				<br />
				<?php if (!empty($kota)) : ?>
					<center>
						<h5>
							LAPSING <?php echo str_replace('_', ' + ', $kota);
									?><br />
							TANGGAL : <?php echo ($tgl1 != $tgl2) ? $tgl1 . ' s.d ' . $tgl2 : $tgl1; ?>
						</h5>
					</center>
				<?php endif; ?>

				<?php if (!empty($data_lapsing)) : ?>

					<div class="row">
						<div class="col-md-3">
						</div>
						<div class="col-md-6">
							<h5 class="text-center">KELOMPOK JENIS KOMODITAS</h5>
							<table class="table table-sm table-bordered table-striped">

								<thead>
									<tr>
										<th></th>
										<th class='text-center'>JML INFORMASI</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML PENGADUAN</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML TOTAL</th>
										<th class='text-center'>%</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$jml_i = 0;
									$persen_i = 0;
									$jml_p = 0;
									$persen_p = 0;
									$jml_t = 0;
									$persen_t = 0;
									foreach ($products->result_array() as $row) :
										$id = $row['id'];
									?>
										<tr>
											<td width="30%"><?php echo $row['desc'] ?></td>
											<?php
											$array = $data_lapsing['produk'][$id];
											$jml_i += $array[0];
											$persen_i += $array[1];
											$jml_p += $array[2];
											$persen_p += $array[3];
											$jml_t += $array[4];
											$persen_t += $array[5];


											?>
											<td class='text-center'><?php echo $array[0]; ?></td>
											<td class='text-center'><?php echo round($array[1] * 100, 2); ?>%</td>
											<td class='text-center'><?php echo $array[2]; ?></td>
											<td class='text-center'><?php echo round($array[3] * 100, 2); ?>%</td>
											<td class='text-center'><?php echo $array[4]; ?></td>
											<td class='text-center'><?php echo round($array[5] * 100, 2); ?>%</td>


										</tr>


									<?php endforeach; ?>
								<tfoot>
									<tr>
										<td>JUMLAH</td>
										<td class='text-center'><?php echo $jml_i; ?></td>
										<td class='text-center'><?php echo round($persen_i * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_p; ?></td>
										<td class='text-center'><?php echo round($persen_p * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_t; ?></td>
										<td class='text-center'><?php echo round($persen_t * 100, 2); ?>%</td>
									</tr>
								</tfoot>
								</tbody>
							</table>
							<hr />
							<h5 class="text-center">KELOMPOK MEKANISME MENJAWAB</h5>
							<table class="table table-sm table-bordered table-striped">

								<thead>
									<tr>
										<th></th>
										<th class='text-center'>JML INFORMASI</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML PENGADUAN</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML TOTAL</th>
										<th class='text-center'>%</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$jml_i = 0;
									$persen_i = 0;
									$jml_p = 0;
									$persen_p = 0;
									$jml_t = 0;
									$persen_t = 0;

									foreach ($data_lapsing['mekanisme'] as $k => $v) : ?>
										<tr>
											<td width="30%"><?php echo $k; ?></td>
											<?php
											$array = $v;
											$jml_i += $array[0];
											$persen_i += $array[1];
											$jml_p += $array[2];
											$persen_p += $array[3];
											$jml_t += $array[4];
											$persen_t += $array[5];
											?>
											<td class='text-center'><?php echo $array[0]; ?></td>
											<td class='text-center'><?php echo round($array[1] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[2]; ?></td>
											<td class='text-center'><?php echo round($array[3] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[4]; ?></td>
											<td class='text-center'><?php echo round($array[5] * 100, 2) . '%'; ?></td>


										</tr>


									<?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<td>JUMLAH</td>
										<td class='text-center'><?php echo $jml_i; ?></td>
										<td class='text-center'><?php echo round($persen_i * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_p; ?></td>
										<td class='text-center'><?php echo round($persen_p * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_t; ?></td>
										<td class='text-center'><?php echo round($persen_t * 100, 2); ?>%</td>
									</tr>
								</tfoot>
							</table>
							<hr />
							<h5 class="text-center">JENIS PROFESI KONSUMEN</h5>
							<table class="table table-sm table-bordered table-striped">

								<thead>
									<tr>
										<th></th>
										<th class='text-center'>JML INFORMASI</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML PENGADUAN</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML TOTAL</th>
										<th class='text-center'>%</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$jml_i = 0;
									$persen_i = 0;
									$jml_p = 0;
									$persen_p = 0;
									$jml_t = 0;
									$persen_t = 0;
									foreach ($profesi->result_array() as $row) :
										$id = $row['id'];
									?>
										<tr>
											<td width="30%"><?php echo $row['name'] ?></td>
											<?php
											$array = $data_lapsing['profesi'][$id];
											$jml_i += $array[0];
											$persen_i += $array[1];
											$jml_p += $array[2];
											$persen_p += $array[3];
											$jml_t += $array[4];
											$persen_t += $array[5];
											?>
											<td class='text-center'><?php echo $array[0]; ?></td>
											<td class='text-center'><?php echo round($array[1] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[2]; ?></td>
											<td class='text-center'><?php echo round($array[3] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[4]; ?></td>
											<td class='text-center'><?php echo round($array[5] * 100, 2) . '%'; ?></td>


										</tr>



									<?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<td>JUMLAH</td>
										<td class='text-center'><?php echo $jml_i; ?></td>
										<td class='text-center'><?php echo round($persen_i * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_p; ?></td>
										<td class='text-center'><?php echo round($persen_p * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_t; ?></td>
										<td class='text-center'><?php echo round($persen_t * 100, 2); ?>%</td>
									</tr>
								</tfoot>
							</table>
							<hr />
							<h5 class="text-center">KELOMPOK INFORMASI PRODUK</h5>
							<table class="table table-sm table-bordered table-striped">

								<thead>
									<tr>
										<th></th>
										<th class='text-center'>JML INFORMASI</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML PENGADUAN</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML TOTAL</th>
										<th class='text-center'>%</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$jml_i = 0;
									$persen_i = 0;
									$jml_p = 0;
									$persen_p = 0;
									$jml_t = 0;
									$persen_t = 0;
									foreach ($data_lapsing['klasifikasi'] as $k => $v) : ?>
										<tr>
											<td width="30%"><?php echo $k; ?></td>
											<?php
											$array = $v;
											$jml_i += $array[0];
											$persen_i += $array[1];
											$jml_p += $array[2];
											$persen_p += $array[3];
											$jml_t += $array[4];
											$persen_t += $array[5];
											?>
											<td class='text-center'><?php echo $array[0]; ?></td>
											<td class='text-center'><?php echo round($array[1] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[2]; ?></td>
											<td class='text-center'><?php echo round($array[3] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[4]; ?></td>
											<td class='text-center'><?php echo round($array[5] * 100, 2) . '%'; ?></td>


										</tr>


									<?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<td>JUMLAH</td>
										<td class='text-center'><?php echo $jml_i; ?></td>
										<td class='text-center'><?php echo round($persen_i * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_p; ?></td>
										<td class='text-center'><?php echo round($persen_p * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_t; ?></td>
										<td class='text-center'><?php echo round($persen_t * 100, 2); ?>%</td>
									</tr>
								</tfoot>
							</table>
							<hr />
							<h5 class="text-center">KELOMPOK FARMAKOLOGI</h5>
							<table class="table table-sm table-bordered table-striped">

								<thead>
									<tr>
										<th></th>
										<th class='text-center'>JML INFORMASI</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML PENGADUAN</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML TOTAL</th>
										<th class='text-center'>%</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$jml_i = 0;
									$persen_i = 0;
									$jml_p = 0;
									$persen_p = 0;
									$jml_t = 0;
									$persen_t = 0;
									foreach ($data_lapsing['farmakologi'] as $k => $v) : ?>
										<tr>
											<td width="30%"><?php echo $k; ?></td>
											<?php
											$array = $v;
											$jml_i += $array[0];
											$persen_i += $array[1];
											$jml_p += $array[2];
											$persen_p += $array[3];
											$jml_t += $array[4];
											$persen_t += $array[5];
											?>
											<td class='text-center'><?php echo $array[0]; ?></td>
											<td class='text-center'><?php echo round($array[1] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[2]; ?></td>
											<td class='text-center'><?php echo round($array[3] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[4]; ?></td>
											<td class='text-center'><?php echo round($array[5] * 100, 2) . '%'; ?></td>


										</tr>


									<?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<td>JUMLAH</td>
										<td class='text-center'><?php echo $jml_i; ?></td>
										<td class='text-center'><?php echo round($persen_i * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_p; ?></td>
										<td class='text-center'><?php echo round($persen_p * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_t; ?></td>
										<td class='text-center'><?php echo round($persen_t * 100, 2); ?>%</td>
									</tr>
								</tfoot>
							</table>
							<hr />

							<h5 class="text-center">KELOMPOK MUTU</h5>
							<table class="table table-sm table-bordered table-striped">

								<thead>
									<tr>
										<th></th>
										<th class='text-center'>JML INFORMASI</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML PENGADUAN</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML TOTAL</th>
										<th class='text-center'>%</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$jml_i = 0;
									$persen_i = 0;
									$jml_p = 0;
									$persen_p = 0;
									$jml_t = 0;
									$persen_t = 0;
									foreach ($data_lapsing['mutu'] as $k => $v) : ?>
										<tr>
											<td width="30%"><?php echo $k; ?></td>
											<?php
											$array = $v;
											$jml_i += $array[0];
											$persen_i += $array[1];
											$jml_p += $array[2];
											$persen_p += $array[3];
											$jml_t += $array[4];
											$persen_t += $array[5];
											?>
											<td class='text-center'><?php echo $array[0]; ?></td>
											<td class='text-center'><?php echo round($array[1] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[2]; ?></td>
											<td class='text-center'><?php echo round($array[3] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[4]; ?></td>
											<td class='text-center'><?php echo round($array[5] * 100, 2) . '%'; ?></td>


										</tr>


									<?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<td>JUMLAH</td>
										<td class='text-center'><?php echo $jml_i; ?></td>
										<td class='text-center'><?php echo round($persen_i * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_p; ?></td>
										<td class='text-center'><?php echo round($persen_p * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_t; ?></td>
										<td class='text-center'><?php echo round($persen_t * 100, 2); ?>%</td>
									</tr>
								</tfoot>
							</table>
							<hr />

							<h5 class="text-center">KELOMPOK LEGALITAS</h5>
							<table class="table table-sm table-bordered table-striped">

								<thead>
									<tr>
										<th></th>
										<th class='text-center'>JML INFORMASI</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML PENGADUAN</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML TOTAL</th>
										<th class='text-center'>%</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$jml_i = 0;
									$persen_i = 0;
									$jml_p = 0;
									$persen_p = 0;
									$jml_t = 0;
									$persen_t = 0;
									foreach ($data_lapsing['legalitas'] as $k => $v) : ?>
										<tr>
											<td width="30%"><?php echo $k; ?></td>
											<?php
											$array = $v;
											$jml_i += $array[0];
											$persen_i += $array[1];
											$jml_p += $array[2];
											$persen_p += $array[3];
											$jml_t += $array[4];
											$persen_t += $array[5];
											?>
											<td class='text-center'><?php echo $array[0]; ?></td>
											<td class='text-center'><?php echo round($array[1] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[2]; ?></td>
											<td class='text-center'><?php echo round($array[3] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[4]; ?></td>
											<td class='text-center'><?php echo round($array[5] * 100, 2) . '%'; ?></td>


										</tr>


									<?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<td>JUMLAH</td>
										<td class='text-center'><?php echo $jml_i; ?></td>
										<td class='text-center'><?php echo round($persen_i * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_p; ?></td>
										<td class='text-center'><?php echo round($persen_p * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_t; ?></td>
										<td class='text-center'><?php echo round($persen_t * 100, 2); ?>%</td>
									</tr>
								</tfoot>
							</table>
							<hr />

							<h5 class="text-center">KELOMPOK PENANDAAN</h5>
							<table class="table table-sm table-bordered table-striped">

								<thead>
									<tr>
										<th></th>
										<th class='text-center'>JML INFORMASI</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML PENGADUAN</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML TOTAL</th>
										<th class='text-center'>%</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$jml_i = 0;
									$persen_i = 0;
									$jml_p = 0;
									$persen_p = 0;
									$jml_t = 0;
									$persen_t = 0;
									foreach ($data_lapsing['penandaan'] as $k => $v) : ?>
										<tr>
											<td width="30%"><?php echo $k; ?></td>
											<?php
											$array = $v;
											$jml_i += $array[0];
											$persen_i += $array[1];
											$jml_p += $array[2];
											$persen_p += $array[3];
											$jml_t += $array[4];
											$persen_t += $array[5];
											?>
											<td class='text-center'><?php echo $array[0]; ?></td>
											<td class='text-center'><?php echo round($array[1] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[2]; ?></td>
											<td class='text-center'><?php echo round($array[3] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[4]; ?></td>
											<td class='text-center'><?php echo round($array[5] * 100, 2) . '%'; ?></td>


										</tr>


									<?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<td>JUMLAH</td>
										<td class='text-center'><?php echo $jml_i; ?></td>
										<td class='text-center'><?php echo round($persen_i * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_p; ?></td>
										<td class='text-center'><?php echo round($persen_p * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_t; ?></td>
										<td class='text-center'><?php echo round($persen_t * 100, 2); ?>%</td>
									</tr>
								</tfoot>
							</table>
							<hr />

							<h5 class="text-center">KELOMPOK INFO LAIN TTG PRODUK</h5>
							<table class="table table-sm table-bordered table-striped">

								<thead>
									<tr>
										<th></th>
										<th class='text-center'>JML INFORMASI</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML PENGADUAN</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML TOTAL</th>
										<th class='text-center'>%</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$jml_i = 0;
									$persen_i = 0;
									$jml_p = 0;
									$persen_p = 0;
									$jml_t = 0;
									$persen_t = 0;
									foreach ($data_lapsing['info_lain'] as $k => $v) : ?>
										<tr>
											<td width="30%"><?php echo $k; ?></td>
											<?php
											$array = $v;
											$jml_i += $array[0];
											$persen_i += $array[1];
											$jml_p += $array[2];
											$persen_p += $array[3];
											$jml_t += $array[4];
											$persen_t += $array[5];
											?>
											<td class='text-center'><?php echo $array[0]; ?></td>
											<td class='text-center'><?php echo round($array[1] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[2]; ?></td>
											<td class='text-center'><?php echo round($array[3] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[4]; ?></td>
											<td class='text-center'><?php echo round($array[5] * 100, 2) . '%'; ?></td>


										</tr>


									<?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<td>JUMLAH</td>
										<td class='text-center'><?php echo $jml_i; ?></td>
										<td class='text-center'><?php echo round($persen_i * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_p; ?></td>
										<td class='text-center'><?php echo round($persen_p * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_t; ?></td>
										<td class='text-center'><?php echo round($persen_t * 100, 2); ?>%</td>
									</tr>
								</tfoot>
							</table>
							<hr />

							<h5 class="text-center">KELOMPOK INFO UMUM</h5>
							<table class="table table-sm table-bordered table-striped">

								<thead>
									<tr>
										<th></th>
										<th class='text-center'>JML INFORMASI</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML PENGADUAN</th>
										<th class='text-center'>%</th>
										<th class='text-center'>JML TOTAL</th>
										<th class='text-center'>%</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$jml_i = 0;
									$persen_i = 0;
									$jml_p = 0;
									$persen_p = 0;
									$jml_t = 0;
									$persen_t = 0;
									foreach ($data_lapsing['info_umum'] as $k => $v) : ?>
										<tr>
											<td width="30%"><?php echo $k; ?></td>
											<?php
											$array = $v;
											$jml_i += $array[0];
											$persen_i += $array[1];
											$jml_p += $array[2];
											$persen_p += $array[3];
											$jml_t += $array[4];
											$persen_t += $array[5];
											?>
											<td class='text-center'><?php echo $array[0]; ?></td>
											<td class='text-center'><?php echo round($array[1] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[2]; ?></td>
											<td class='text-center'><?php echo round($array[3] * 100, 2) . '%'; ?></td>
											<td class='text-center'><?php echo $array[4]; ?></td>
											<td class='text-center'><?php echo round($array[5] * 100, 2) . '%'; ?></td>


										</tr>


									<?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<td>JUMLAH</td>
										<td class='text-center'><?php echo $jml_i; ?></td>
										<td class='text-center'><?php echo round($persen_i * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_p; ?></td>
										<td class='text-center'><?php echo round($persen_p * 100, 2); ?>%</td>
										<td class='text-center'><?php echo $jml_t; ?></td>
										<td class='text-center'><?php echo round($persen_t * 100, 2); ?>%</td>
									</tr>
								</tfoot>
							</table>
							<hr />

						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#kota').on('change', function() {
			$.remember({
				name: 'lapsing.kota',
				value: $('#kota').val()
			})

			if ($(this).val() == '') {
				$('#direktorat').empty();
				return;
			}

			$.getJSON("<?php echo site_url('lapsingv2/get_direktorat/'); ?>" + $(this).val(), function(data) {
				if (data) {
					$('#direktorat').empty();
					$('#direktorat').append($('<option></option>').attr('value', '').text('ALL'));
					$.each(data, function(key, entry) {
						$('#direktorat').append($('<option></option>').attr('value', entry.id).text(entry.name));
					})
				}
			});
		});

		$('#tgl1').on('change', function() {
			$.remember({
				name: 'lapsing.tgl1',
				value: $('#tgl1').val()
			})
		});

		$('#tgl2').on('change', function() {
			//console.log($('#tgl2').val());
			$.remember({
				name: 'lapsing.tgl2',
				value: $('#tgl2').val()
			})
		});

		if ($.remember({
				name: 'lapsing.kota'
			}) != null)
			$('#kota').val($.remember({
				name: 'lapsing.kota'
			}));

		if ($.remember({
				name: 'lapsing.direktorat'
			}) != null)
			$('#kota').val($.remember({
				name: 'lapsing.direktorat'
			}));

		if ($.remember({
				name: 'lapsing.tgl1'
			}) != null)
			$('#tgl1').val($.remember({
				name: 'lapsing.tgl1'
			}));

		if ($.remember({
				name: 'lapsing.tgl2'
			}) != null)
			$('#tgl2').val($.remember({
				name: 'lapsing.tgl2'
			}));


		$.extend($.fn.datepicker.defaults, {
			format: 'dd/mm/yyyy',
			language: 'id',
			daysOfWeekHighlighted: [0, 6],
			todayHighlight: true,
			weekStart: 1
		});

		$("#tgl1").datepicker({
			zIndexOffset: '1001',
			orientation: 'bottom'
		});
		$("#tgl2").datepicker({
			zIndexOffset: '1001',
			orientation: 'bottom'
		});

	});
</script>
<?php $this->load->view("partial/footer"); ?>