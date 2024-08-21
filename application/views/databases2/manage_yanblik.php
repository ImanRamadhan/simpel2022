<?php $this->load->view("partial/header"); ?>
<!--<link href="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css" rel="stylesheet">
<script src="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js"></script>-->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css" />
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.fileDownload.js"></script>
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Database</a></li>
					<li class="breadcrumb-item active">Database Yanblik</li>
				</ol>
			</div>
			<h4 class="page-title">Database Yanblik</h4>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {

		<?php $this->load->view('partial/bootstrap_tables_locale'); ?>

		<?php
		/*$page = $this->session->flashdata('page');
	if(!empty($page)): ?>
		$.remember({ name: 'tickets.tgl1', value: '<?php echo convert_date2($this->session->dashboard_date1); ?>' });
		$.remember({ name: 'tickets.tgl2', value: '<?php echo convert_date2($this->session->dashboard_date2); ?>' });
		<?php if($page == 'pusat'):?>
			$.remember({ name: 'tickets.kota', value: 'PUSAT' });
		<?php elseif($page == 'cc'):?>
			$.remember({ name: 'tickets.kota', value: 'CC' });
		<?php elseif($page == 'balai'):?>
			$.remember({ name: 'tickets.kota', value: 'BALAI' });
		<?php elseif($page == 'all'):?>
			$.remember({ name: 'tickets.kota', value: 'ALL' });
		<?php endif; ?>
	<?php endif;*/ ?>



		$('#status').on('hidden.bs.select', function(e) {
			table_support.refresh();
		});
		$('#tl').on('hidden.bs.select', function(e) {
			table_support.refresh();
		});
		$('#fb').on('hidden.bs.select', function(e) {
			table_support.refresh();
		});
		$('#is_rujuk').on('hidden.bs.select', function(e) {
			table_support.refresh();
		});
		$('#is_verified').on('hidden.bs.select', function(e) {
			table_support.refresh();
		});


		$('#process_btn').on('click', function(e) {
			$.remember({
				name: 'tickets.tgl1',
				value: $('#tgl1').val()
			});
			$.remember({
				name: 'tickets.tgl2',
				value: $('#tgl2').val()
			});

			table_support.refresh();
		});

		<?php if (is_pusat()): ?>
			$('#kota').on('change', function() {
				$.remember({
					name: 'tickets.kota',
					value: $('#kota').val()
				});
			});

			if ($.remember({
					name: 'tickets.kota'
				}) != null)
				$('#kota').val($.remember({
					name: 'tickets.kota'
				}));

		<?php endif; ?>

		if ($.remember({
				name: 'tickets.tgl1'
			}) != null) {
			$('#tgl1').val($.remember({
				name: 'tickets.tgl1'
			}));
		} else {
			$("#tgl1").val("<?php echo date('01/m/Y'); ?>");
		}

		if ($.remember({
				name: 'tickets.tgl2'
			}) != null) {
			$('#tgl2').val($.remember({
				name: 'tickets.tgl2'
			}));
		} else {
			$("#tgl2").val("<?php echo date('d/m/Y'); ?>");
		}


		table_support.init({
			resource: '<?php echo site_url('databases_yanblik'); ?>',
			headers: [
				[{
						"field": "no",
						"title": "NO.",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2,
						"width": 50
					},
					{
						"field": "trackid",
						"title": "ID",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "customer",
						"title": "IDENTITAS KONSUMEN",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"colspan": 6
					},

					{
						"field": "customer",
						"title": "LAYANAN",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"colspan": 3
					},

					{
						"field": "keterangan",
						"title": "KETERANGAN",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "jenis_komoditi",
						"title": "JENIS KOMODITI",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "jenis",
						"title": "SUMBER DATA",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "penerima",
						"title": "PETUGAS PENERIMA",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "isu_topik",
						"title": "ISU/TOPIK",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "left",
						"rowspan": 2
					},
					{
						"field": "klasifikasi",
						"title": "KLASIFIKASI",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "subklasifikasi",
						"title": "SUBKLASIFIKASI",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "sarana",
						"title": "SARANA",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "waktu",
						"title": "WAKTU",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "shift",
						"title": "SHIFT",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "petugas_entry",
						"title": "PETUGAS INPUT",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},

					{
						"field": "verified",
						"title": "STATUS VERIFIKASI",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "verified_date",
						"title": "TGL VERIFIKASI",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "verificator_name",
						"title": "VERIFIKATOR",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "tglpengaduan",
						"title": "TGL LAYANAN",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "closed_date",
						"title": "TGL CLOSED",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "status",
						"title": "STATUS RUJUKAN",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},

					{
						"field": "tl",
						"title": "STATUS TL",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "tl_date",
						"title": "TGL TL",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "fb",
						"title": "STATUS FB",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "fb_date",
						"title": "TGL FB",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "kota",
						"title": "BALAI/LOKA",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "sla",
						"title": "TARGET <br />PENYELESAIAN<br />(SLA)",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "hk",
						"title": "JANGKA WAKTU<br />PENYELESAIAN",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
					{
						"field": "memenuhi_sla",
						"title": "PEMENUHAN<br />SLA",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},

					{
						"field": "rujukan1",
						"title": "RUJUKAN 1",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"colspan": 5
					},
					{
						"field": "rujukan2",
						"title": "RUJUKAN 2",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"colspan": 5
					},
					{
						"field": "rujukan3",
						"title": "RUJUKAN 3",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"colspan": 5
					},
					{
						"field": "rujukan4",
						"title": "RUJUKAN 4",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"colspan": 5
					},
					{
						"field": "rujukan5",
						"title": "RUJUKAN 5",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"colspan": 5
					},
					{
						"field": "fb_isi",
						"title": "JAWABAN FEEDBACK",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"rowspan": 2
					},
				],
				[{
						"field": "iden_nama",
						"title": "NAMA",
						"switchable": true,
						"sortable": false,
						"class": "",
						"sorter": "",
						"align": "center",
						"valign": "top"
					},
					{
						"field": "iden_alamat",
						"title": "ALAMAT",
						"switchable": true,
						"sortable": false,
						"class": "",
						"sorter": "",
						"align": "center",
						"valign": "top"
					},
					{
						"field": "iden_telp",
						"title": "TELP",
						"switchable": true,
						"sortable": false,
						"class": "",
						"sorter": "",
						"align": "center",
						"valign": "top"
					},
					{
						"field": "iden_email",
						"title": "EMAIL",
						"switchable": true,
						"sortable": false,
						"class": "",
						"sorter": "",
						"align": "center",
						"valign": "top"
					},
					{
						"field": "pekerjaan",
						"title": "PEKERJAAN",
						"switchable": true,
						"sortable": false,
						"class": "",
						"sorter": "",
						"align": "center",
						"valign": "top"
					},
					{
						"field": "iden_jk",
						"title": "JENIS KELAMIN",
						"switchable": true,
						"sortable": false,
						"class": "",
						"sorter": "",
						"align": "center",
						"valign": "top"
					},
					{
						"field": "problem",
						"title": "ISI LAYANAN",
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"width": 300
					},
					{
						"field": "jawaban",
						"title": "JAWABAN",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center",
						"width": 300
					},
					{
						"field": "info",
						"title": "JENIS<br />LAYANAN",
						"switchable": true,
						"sortable": false,
						"valign": "top",
						"class": "",
						"sorter": "",
						"align": "center"
					},
					//{"field":"problem_i","title":"Permintaan Informasi","switchable":true,"class":"","sorter":"","align":"left","valign":"top"},
					//{"field":"problem_p","title":"Pengaduan","switchable":true,"class":"","sorter":"","align":"left","valign":"top"},

					//{"field":"jawaban_i","title":"Permintaan Informasi","switchable":true,"class":"","align":"left","valign":"top"},
					//{"field":"jawaban_p","title":"Pengaduan","switchable":true,"class":"","align":"left","valign":"top"},

					{
						"field": "rujukan1_nama",
						"title": "NAMA UNIT <br />RUJUKAN 1",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},
					{
						"field": "rujukan1_sla",
						"title": "SLA RUJUKAN 1",
						"switchable": true,
						"class": "",
						"align": "center",
						"valign": "top"
					},
					{
						"field": "rujukan1_tl",
						"title": "TL RUJUKAN 1",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top",
						"width": 300
					},
					{
						"field": "rujukan1_status",
						"title": "STATUS RUJUKAN 1",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},
					{
						"field": "rujukan1_waktu",
						"title": "WAKTU <br />PENYELESAIAN <br />RUJUKAN 1",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},

					{
						"field": "rujukan2_nama",
						"title": "NAMA UNIT <br />RUJUKAN 2",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},
					{
						"field": "rujukan2_sla",
						"title": "SLA RUJUKAN 2",
						"switchable": true,
						"class": "",
						"align": "center",
						"valign": "top"
					},
					{
						"field": "rujukan2_tl",
						"title": "TL RUJUKAN 2",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top",
						"width": 300
					},
					{
						"field": "rujukan2_status",
						"title": "STATUS RUJUKAN 2",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},
					{
						"field": "rujukan2_waktu",
						"title": "WAKTU <br />PENYELESAIAN <br />RUJUKAN 2",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},

					{
						"field": "rujukan3_nama",
						"title": "NAMA UNIT <br />RUJUKAN 3",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},
					{
						"field": "rujukan3_sla",
						"title": "SLA RUJUKAN 3",
						"switchable": true,
						"class": "",
						"align": "center",
						"valign": "top"
					},
					{
						"field": "rujukan3_tl",
						"title": "TL RUJUKAN 3",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top",
						"width": 300
					},
					{
						"field": "rujukan3_status",
						"title": "STATUS RUJUKAN 3",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},
					{
						"field": "rujukan3_waktu",
						"title": "WAKTU <br />PENYELESAIAN <br />RUJUKAN 3",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},

					{
						"field": "rujukan4_nama",
						"title": "NAMA UNIT <br />RUJUKAN 4",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},
					{
						"field": "rujukan4_sla",
						"title": "SLA RUJUKAN 4",
						"switchable": true,
						"class": "",
						"align": "center",
						"valign": "top"
					},
					{
						"field": "rujukan4_tl",
						"title": "TL RUJUKAN 4",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top",
						"width": 300
					},
					{
						"field": "rujukan4_status",
						"title": "STATUS RUJUKAN 4",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},
					{
						"field": "rujukan4_waktu",
						"title": "WAKTU <br />PENYELESAIAN <br />RUJUKAN 4",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},

					{
						"field": "rujukan5_nama",
						"title": "NAMA UNIT <br />RUJUKAN 5",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},
					{
						"field": "rujukan5_sla",
						"title": "SLA RUJUKAN 5",
						"switchable": true,
						"class": "",
						"align": "center",
						"valign": "top"
					},
					{
						"field": "rujukan5_tl",
						"title": "TL RUJUKAN 5",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top",
						"width": 300
					},
					{
						"field": "rujukan5_status",
						"title": "STATUS RUJUKAN 5",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					},
					{
						"field": "rujukan5_waktu",
						"title": "WAKTU <br />PENYELESAIAN <br />RUJUKAN 5",
						"switchable": true,
						"class": "",
						"align": "left",
						"valign": "top"
					}
				]
			],
			pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
			uniqueId: 'id',
			cookie: true,
			cookieIdTable: 'databaseTable',
			sortName: 'id',
			sortOrder: 'asc',
			fixedColumns: true,
			height: 500,
			queryParams: function() {
				return $.extend(arguments[0], {
					<?php if (is_pusat()): ?>
						kota: $("#kota").val() || "",
					<?php else: ?>
						kota: '<?php echo $this->session->city; ?>',
					<?php endif; ?>
					tgl1: moment($("#tgl1").val(), 'DD/MM/YYYY').format('YYYY-MM-DD') || "",
					tgl2: moment($("#tgl2").val(), 'DD/MM/YYYY').format('YYYY-MM-DD') || "",
					kategori: $('#kategori').val() || "",
					jenis: $('#jenis').val() || "",
					status_rujukan: $("#status_rujukan").val() || "",
					unit_rujukan: $("#unit_rujukan").val() || "",
					/*field: $('#field').val() || "",
					keyword: $('#keyword').val() || "",
					status: $("#status").val() || [""],
					tl: $("#tl").val() || [""],
					fb: $("#fb").val() || [""],
					is_rujuk: $("#is_rujuk").val() || [""],
					is_verified: $("#is_verified").val() || [""],*/
				});
			},
			/*rowAttributes: function (row, index) {
				//return { "data-id": row.id };
				return { "title": row.problem };
			},*/
		});
	});
</script>


<div class="row">
	<div class="col-md-12">

		<div class="card ">
			<div class="card-header bg-primary text-white">
				Database Yanblik
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">

				</div>
				<?php /*$this->load->view("partial/search_advanced");*/ ?>

				<?php if ($this->session->city == 'PUSAT'): ?>
					<div class="form-group form-group-sm row">
						<?php echo form_label('Pilih Kota', 'label_kota', array('class' => 'required col-form-label col-sm-2')); ?>
						<div class='col-sm-4'>
							<?php
							echo form_dropdown('kota', $cities, '', 'class="form-control form-control-sm" id="kota"'); ?>
						</div>
					</div>
					<div class="form-group form-group-sm row">
						<?php echo form_label('Pilih Direktorat', 'label_direktorat', array('class' => 'required col-form-label col-sm-2')); ?>
						<div class='col-sm-4'>
							<?php
							echo form_dropdown('direktorat',  $direktorat, '', 'class="form-control form-control-sm" id="direktorat"'); ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="form-group form-group-sm row">
					<?php echo form_label('Pilih Komoditas', 'label_komoditas', array('class' => 'required col-form-label col-sm-2')); ?>
					<div class='col-sm-4'>
						<?php
						echo form_dropdown('kategori', $categories, '', 'class="form-control form-control-sm" id="kategori"'); ?>
					</div>
				</div>
				<div class="form-group form-group-sm row">
					<?php echo form_label('Pilih Sumber Data', 'label_datasource', array('class' => 'required col-form-label col-sm-2')); ?>
					<div class='col-sm-4'>
						<?php
						echo form_dropdown('jenis', $datasources, '', 'class="form-control form-control-sm" id="jenis"'); ?>
					</div>
				</div>

				<div class="form-group form-group-sm row">
					<?php echo form_label('Pilih Tanggal', 'label_tgl', array('class' => 'required col-form-label col-sm-2')); ?>
					<div class='col-sm-4'>
						<div class="form-group">
							<div class="input-group">
								<input type="text" id="tgl1" name="tgl1" class="form-control" placeholder="" autocomplete="off" value="<?php echo (!empty($this->input->post('tgl1')) ? $this->input->post('tgl1') : date('01/m/Y')) ?>" />
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
								</div>
								<div class="mt-1">&nbsp;s.d&nbsp;</div>
								<input type="text" id="tgl2" name="tgl2" class="form-control" placeholder="" autocomplete="off" value="<?php echo (!empty($this->input->post('tgl2')) ? $this->input->post('tgl2') : date('d/m/Y')) ?>" />
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="form-group form-group-sm row">
					<?php echo form_label('', 'label_tgl', array('class' => 'required col-form-label col-sm-2')); ?>
					<div class='col-sm-10'>
						<button class="btn btn-warning btn-sm" id="process_btn"> <i class="fa fa-search"></i> Lihat Database</button>
						<button id="buttonExport" class='btn-export btn btn-success btn-sm'><i class="fas fa-file-excel"></i> &nbsp; Export</button>
					</div>
				</div>


				<div id="toolbar">
					<div class="float-left form-inline" role="toolbar">



						&nbsp;
						<!--<a id="export" class="btn export btn-soft-info btn-sm" href="#" title="Export data ke MS Excel">
							<span class="fa fa-download">&nbsp;</span>Export
						</a>-->
						<?php

						?><!--
					    &nbsp;Status : &nbsp;
						<?php echo form_multiselect('status[]', array('0' => 'Open', '2' => 'Replied', '3' => 'Closed'), '', array('id' => 'status', 'class' => 'selectpicker show-menu-arrow', 'data-none-selected-text' => 'All', 'data-selected-text-format' => 'count > 1', 'data-style' => 'btn-soft-info btn-sm', 'data-width' => 'fit')); ?>
						<!--&nbsp;Dirujuk : &nbsp;
						<?php echo form_multiselect('is_rujuk[]', array('0' => 'Tidak', '1' => 'Ya'), '', array('id' => 'is_rujuk', 'class' => 'selectpicker show-menu-arrow', 'data-none-selected-text' => 'All', 'data-selected-text-format' => 'count > 1', 'data-style' => 'btn-soft-info btn-sm', 'data-width' => 'fit')); ?>- ->
						&nbsp;TL : &nbsp;
						<?php echo form_multiselect('tl[]', array('0' => 'N', '1' => 'Y'), '', array('id' => 'tl', 'class' => 'selectpicker show-menu-arrow', 'data-none-selected-text' => 'All', 'data-selected-text-format' => 'count > 1', 'data-style' => 'btn-soft-info btn-sm', 'data-width' => 'fit')); ?>
						&nbsp;FB : &nbsp;
						<?php echo form_multiselect('fb[]', array('0' => 'N', '1' => 'Y'), '', array('id' => 'fb', 'class' => 'selectpicker show-menu-arrow', 'data-none-selected-text' => 'All', 'data-selected-text-format' => 'count > 1', 'data-style' => 'btn-soft-info btn-sm', 'data-width' => 'fit')); ?>
						<!- -&nbsp;Verified : &nbsp;
						<?php echo form_multiselect('is_verified[]', array('0' => 'N', '1' => 'Y'), '', array('id' => 'is_verified', 'class' => 'selectpicker show-menu-arrow', 'data-none-selected-text' => 'All', 'data-selected-text-format' => 'count > 1', 'data-style' => 'btn-soft-info btn-sm', 'data-width' => 'fit')); ?>-->
					</div>

				</div>

				<div id="table_holder">
					<table id="table" class="table-striped" style="table-layout: fixed; width: 600%;"></table>
				</div>

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

		$("#tgl1").datepicker({
			zIndexOffset: '1001',
			orientation: 'bottom'
		});
		$("#tgl2").datepicker({
			zIndexOffset: '1001',
			orientation: 'bottom'
		});



		$('#buttonExport').on('click', function(event) {
			event.preventDefault();
			$.fileDownload('<?php echo site_url('excels/download_databases2') ?>', {

				data: {
					tgl1: $("#tgl1").val(),
					tgl2: $("#tgl2").val(),
					kota: $("#kota").val() || "",
					kategori: $("#kategori").val(),
					datasource: $("#jenis").val(),
					//statusRujukan   : $("#statusRujukan").val(),
					type: "3",
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
	});
</script>
<?php $this->load->view("partial/footer"); ?>