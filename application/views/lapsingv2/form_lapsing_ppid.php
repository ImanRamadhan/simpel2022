<?php $this->load->view("partial/header"); ?>
<script src="<?php echo base_url() ?>assets/js/jquery.fileDownload.js"></script>
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Lapsing</a></li>
					<li class="breadcrumb-item active">Lapsing PPID</li>
				</ol>
			</div>
			<h4 class="page-title"></h4>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#buttonExport').on('click', function() {
			$.fileDownload('<?php echo site_url('excels/download_lapsing_new') ?>', {

				data: {
					formType: '<?php echo $lapsing_type; ?>',
					tgl1: moment($("#tgl1").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
					tgl2: moment($("#tgl2").val(), "DD/MM/YYYY").format("YYYY-MM-DD"),
					kota: $("#kota").val() || "",
					formType: $("#formType").val(),
					kategori: $("#kategori").val(),
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

<style>
	tfoot {
		background-color: #ADD8E6;
		color: #000;
		font-weight: bold;
	}
</style>

<div class="row">
	<div class="col-md-12">
		<div class="card ">
			<div class="card-header bg-primary text-white">
				<?php echo $title; ?>
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right"></div>
				<?php

				$this->load->view("lapsingv2/lapsing_ppid_header");

				?>
				<br />
				<?php if (!empty($kota)): ?>
					<center>
						<h5>
							LAPORAN SINGKAT LAYANAN INFORMASI PPID BADAN POM <?php /*echo str_replace('_',' + ', $kota); */ ?><br />
							PERIODE : <?php echo ($tgl1 != $tgl2) ? $tgl1 . ' s.d ' . $tgl2 : $tgl1; ?>
						</h5>
					</center>
				<?php endif; ?>

				<?php if (!empty($data_lapsing)): ?>

					<div class="row">
						<div class="col-md-2">
						</div>
						<div class="col-md-8">
							<h5 class="text-center"></h5>
							<table class="table table-sm table-bordered table-striped">

								<thead>
									<tr>

										<th rowspan="2" class='text-center'>Bulan</th>
										<th rowspan="2" class='text-center'>Jumlah Permohonan</th>
										<th rowspan="2" class='text-center'>Waktu Rata-rata Pelayanan</th>
										<th colspan="2" class='text-center'>Jumlah Pemohon yang dikabulkan</th>
										<th rowspan="2" class='text-center'>Jumlah Pemohon Ditolak</th>
										<th colspan="3" class='text-center'>Alasan Permohonan yang ditolak</th>

									</tr>
									<tr>

										<th class='text-center'>Sepenuhnya</th>
										<th class='text-center'>Sebagian</th>
										<th class='text-center'>Dikecualikan</th>
										<th class='text-center'>Belum Didokumentasikan</th>
										<th class='text-center'>Tidak Dikuasai</th>

									</tr>
								</thead>
								<tbody>
									<?php
									$total = 0;
									$totalRataRata = 0;
									$total_dikabulkan_sepenuhnya = 0;
									$total_dikabulkan_sebagian = 0;
									$total_ditolak = 0;

									$total_dikecualikan = 0;
									$total_blmdidokumentasikan = 0;
									$total_tidakdikuasai = 0;

									$array = array();
									foreach ($data_lapsing as $row):
										$total += $row['jml'];
										$totalRataRata += $row['rataPelayanan'];
										$total_dikabulkan_sepenuhnya += $row['dikabulkansepenuhnya'];
										$total_dikabulkan_sebagian += $row['dikabulkansebagian'];
										$total_ditolak += $row['ditolak'];

										$total_dikecualikan += $row['dikecualikan'];
										$total_blmdidokumentasikan += $row['belumdidokumentasikan'];
										$total_tidakdikuasai += $row['tidakdikuasai'];

										$array[] = $row['bln'];

									?>
										<tr>
											<td width="30%" class='text-center'><?php echo $row['bln']; ?></td>
											<td class='text-center'><?php echo $row['jml']; ?></td>
											<td class='text-center'><?php echo number_format($row['rataPelayanan'], 2, ',', ''); ?></td>
											<td class='text-center'><?php echo $row['dikabulkansepenuhnya']; ?></td>
											<td class='text-center'><?php echo $row['dikabulkansebagian']; ?></td>
											<td class='text-center'><?php echo $row['ditolak']; ?></td>
											<td class='text-center'><?php echo $row['dikecualikan']; ?></td>
											<td class='text-center'><?php echo $row['belumdidokumentasikan']; ?></td>
											<td class='text-center'><?php echo $row['tidakdikuasai']; ?></td>
										</tr>


									<?php endforeach; ?>
									<?php
									$num_rows = count($data_lapsing);
									if ($num_rows > 0): ?>
								<tfoot>
									<tr>
										<td class='text-center'>
											<?php
											if ($num_rows == 1)
												echo $array[0];
											else {
												echo $array[0] . ' - ' . $array[$num_rows - 1];
											}
											?>
										</td>
										<td class='text-center'><?php echo $total; ?></td>
										<td class='text-center'></td>
										<td class='text-center'><?php echo $total_dikabulkan_sepenuhnya; ?></td>
										<td class='text-center'><?php echo $total_dikabulkan_sebagian; ?></td>
										<td class='text-center'><?php echo $total_ditolak; ?></td>
										<td class='text-center'><?php echo $total_dikecualikan; ?></td>
										<td class='text-center'><?php echo $total_blmdidokumentasikan; ?></td>
										<td class='text-center'><?php echo $total_tidakdikuasai; ?></td>
									</tr>
								</tfoot>
							<?php endif; ?>
							</tbody>
							</table>


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