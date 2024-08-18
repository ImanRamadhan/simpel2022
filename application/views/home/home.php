<?php $this->load->view("partial/header"); ?>
<!-- Page-Title -->
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
					<li class="breadcrumb-item active">Dashboard</li>
				</ol>
			</div>
			<h4 class="page-title">Dashboard</h4>
			
		</div>
	</div>
</div>
<!-- end page title end breadcrumb -->

<div class="row">
	<div class="col-md-6 col-lg-6 col-xl-6">
		<div class="card">
			<div class="card-header ">
				
				<?php echo form_open('home/dashboard_date', array('id'=>'date_form', 'class'=>'form-inline')); ?>
				<div class="form-group">
					<label class="" for="date1">Tanggal</label>
					<div class="input-group">
						<input type="text" class="form-control form-control-sm ml-1" id="date1" name="date1" autocomplete="off" placeholder="dd/mm/yyyy" value="<?php echo $date_1; ?>">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					
					<label class="ml-1" for="date2">s.d </label>
					<div class="input-group">
						<input type="text" class="form-control form-control-sm ml-1" id="date2" name="date2" autocomplete="off" placeholder="dd/mm/yyyy" value="<?php echo $date_2; ?>">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
						</div>
					</div>
					
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-sm ml-2">Go</button>
				</div>
				<?php echo form_close(); ?>
				
				
			</div>
		</div>
	</div>
	<div class="col-md-6 col-lg-6 col-xl-6">
		<div class="card">
			<div class="card-header ">
				
				<?php echo form_open('tickets/list_all', array('id'=>'date_form', 'class'=>'form-inline','method'=>'get')); ?>
				<div class="form-group">
					<label class="" for="">Cari berdasarkan&nbsp;</label>
					<?php echo form_dropdown('field', $fields, '', 'class="form-control form-control-sm" id="field"');?>
				</div>
				<div class="form-group">&nbsp;
					<?php echo form_input(array(
						'name'=>'keyword',
						'id'=>'keyword',
						'placeholder' => 'Masukkan Kata Kunci',
						'class'=>'form-control form-control-sm',
						'value'=>'')
						);?>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-sm ml-2"><i class="fa fa-search"></i> Cari Layanan</button>
				</div>
				<?php echo form_close(); ?>
				
				
			</div>
		</div>
	</div>
</div>
<div class="row">                    
	
	<div class="col-md-12 col-lg-6 col-xl-3">
		<div class="card">
			<div class="card-body">
				<h5 class="mt-0 mb-3">Layanan</h5>
				<div id="donut-layanan" class="morris-chart workloed-chart"></div>
				<ul class="list-unstyled list-group text-muted mb-0 mt-2">
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">PUSAT  <a href="<?php echo site_url('tickets/list_all?kota=PUSAT&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-primary badge-pill font-12" id="layanan_pusat">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">CC <a href="<?php echo site_url('tickets/list_all?kota=CC&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-primary badge-pill font-12" id="layanan_cc">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">BALAI <a href="<?php echo site_url('tickets/list_all?kota=BALAI&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-primary badge-pill font-12" id="layanan_balai">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">TOTAL LAYANAN <a href="<?php echo site_url('tickets/list_all?kota=ALL&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-primary badge-pill font-12" id="layanan_total">0</span></a></li>
					
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-lg-6 col-xl-3">
		<div class="card">
			<div class="card-body">
				<h5 class="mt-0 mb-3">PUSAT</h5>
				<div id="donut-layanan-pusat" class="morris-chart workloed-chart"></div>
				<ul class="list-unstyled list-group text-muted m-2">
					
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Status Open <a href="<?php echo site_url('tickets/list_all?kota=PUSAT&status=0&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-pink badge-pill font-12" id="pusat_status_open">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Status Closed <a href="<?php echo site_url('tickets/list_all?kota=PUSAT&status=3&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-success badge-pill font-12" id="pusat_status_closed">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Status Replied <a href="<?php echo site_url('tickets/list_all?kota=PUSAT&status=2&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-warning badge-pill font-12" id="pusat_status_replied">0</span></a></li>
					<!--<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Belum Difeedback<span class="badge badge-primary badge-pill font-12" id="pusat_blm_feedback">0</span></li>-->
					<!--<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Pengaduan  <span class="badge badge-primary badge-pill font-12" id="pusat_pengaduan">0</span></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Permintaan Informasi <span class="badge badge-primary badge-pill font-12" id="pusat_informasi">0</span></li>-->
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-lg-6 col-xl-3">
		<div class="card">
			<div class="card-body">
				<h5 class="mt-0 mb-3">Rujukan Masuk</h5>
				<div id="donut-rujukan-masuk" class="morris-chart workloed-chart"></div>
				<ul class="list-unstyled list-group text-muted m-2">
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Rujukan Masuk  <a href="<?php echo site_url('rujukan/list_masuk?tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-primary badge-pill font-12" id="rujukan_masuk">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Rujukan Masuk - Belum Dijawab <a href="<?php echo site_url('rujukan/list_masuk?status=0&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-pink badge-pill font-12" id="rujukan_masuk_blm_dijawab">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Rujukan Masuk - Sudah Dijawab <a href="<?php echo site_url('rujukan/list_masuk?status=1&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-primary badge-pill font-12" id="rujukan_masuk_sudah_dijawab">0</span></a></li>
					
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-lg-6 col-xl-3">
		<div class="card">
			<div class="card-body">
				<h5 class="mt-0 mb-3">Rujukan Keluar</h5>
				<div id="donut-rujukan-keluar" class="morris-chart workloed-chart"></div>
				<ul class="list-unstyled list-group text-muted m-2">
					
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Rujukan Keluar <a href="<?php echo site_url('rujukan/list_keluar?tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-primary badge-pill font-12" id="rujukan_keluar">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Rujukan Keluar - Belum Dijawab <a href="<?php echo site_url('rujukan/list_keluar?status=0&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-pink badge-pill font-12" id="rujukan_keluar_blm_dijawab">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Rujukan Keluar - Sudah Dijawab<a href="<?php echo site_url('rujukan/list_keluar?status=1&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-primary badge-pill font-12" id="rujukan_keluar_sudah_dijawab">0</span></a></li>
				</ul>
			</div>
		</div>
	</div>
	
	
	                                           
</div><!--end row-->
<div class="row">
	<div class="col-md-12 col-lg-6 col-xl-3">
		<div class="card">
			<div class="card-body">
				<h5 class="mt-0 mb-3">Status TL PUSAT</h5>
				<div id="donut-layanan-tl" class="morris-chart workloed-chart"></div>
				<ul class="list-unstyled list-group text-muted m-2">
					
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Belum di-TL <a href="<?php echo site_url('tickets/list_all?kota=PUSAT&tl=0&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-pink badge-pill font-12" id="pusat_belum_tl">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Sudah di-TL  <a href="<?php echo site_url('tickets/list_all?kota=PUSAT&tl=1&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-primary badge-pill font-12" id="pusat_sudah_tl">0</span></a></li>
					
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-lg-6 col-xl-3">
		<div class="card">
			<div class="card-body">
				<h5 class="mt-0 mb-3">Status Feedback PUSAT</h5>
				<div id="donut-layanan-fb" class="morris-chart workloed-chart"></div>
				<ul class="list-unstyled list-group text-muted m-2">
					
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Belum di-Feedback <a href="<?php echo site_url('tickets/list_all?kota=PUSAT&fb=0&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-pink badge-pill font-12" id="pusat_belum_fb">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Sudah di-Feedback <a href="<?php echo site_url('tickets/list_all?kota=PUSAT&fb=1&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-primary badge-pill font-12" id="pusat_sudah_fb">0</span></a></li>
					
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-lg-6 col-xl-3">
		<div class="card">
			<div class="card-body">
				<h5 class="mt-0 mb-3">Layanan Saya</h5>
				<div id="donut-layanan-saya" class="morris-chart workloed-chart"></div>
				<ul class="list-unstyled list-group text-muted m-2">
					<!--<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Pengaduan  <span class="badge badge-primary badge-pill font-12" id="layanan_saya_pengaduan">0</span></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Permintaan Informasi <span class="badge badge-primary badge-pill font-12" id="layanan_saya_informasi">0</span></li>-->
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Status Open <a href="<?php echo site_url('tickets/list_my?status=0&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-pink badge-pill font-12" id="layanan_saya_status_open">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Status Closed <a href="<?php echo site_url('tickets/list_my?status=3&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-primary badge-pill font-12" id="layanan_saya_status_closed">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Status Replied <a href="<?php echo site_url('tickets/list_my?status=2&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-primary badge-pill font-12" id="layanan_saya_status_replied">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Belum Difeedback <a href="<?php echo site_url('tickets/list_my?fb=0&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-primary badge-pill font-12" id="layanan_saya_blm_feedback">0</span></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-lg-6 col-xl-3">
		<div class="card">
			<div class="card-body">
				<h5 class="mt-0 mb-3">Layanan PUSAT Belum di-TL</h5>
				<div id="donut-belum-tl" class="morris-chart workloed-chart"></div>
				<ul class="list-unstyled list-group text-muted m-2">
					
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">SLA 14 - 60 HK <a href="<?php echo site_url('tickets/list_sla?kota=PUSAT_CC&tl=N&sla=green&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-success badge-pill font-12" id="belum_tl_green">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">SLA 5 - 14 HK <a href="<?php echo site_url('tickets/list_sla?kota=PUSAT_CC&tl=N&sla=orange&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-warning badge-pill font-12" id="belum_tl_orange">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">SLA 0 - 5 HK <a href="<?php echo site_url('tickets/list_sla?kota=PUSAT_CC&tl=N&sla=red&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-danger badge-pill font-12" id="belum_tl_red">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Melewati SLA <a href="<?php echo site_url('tickets/list_sla?kota=PUSAT_CC&tl=N&sla=black&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-dark badge-pill font-12" id="belum_tl_black">0</span></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="card">
			<div class="card-body">
				<h5 class="mt-0 mb-3">Performa SLA Layanan Sudah TL</h5>
				<div id="donut-performa-sla" class="morris-chart workloed-chart"></div>
				<ul class="list-unstyled list-group text-muted m-2">
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Memenuhi SLA <a href="<?php echo site_url('tickets/list_sla_perform?kota=PUSAT_CC&tl=N&sla=green&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-success badge-pill font-12" id="memenuhi_sla">0</span></a></li>
					<li class="list-group-item border-0 d-flex justify-content-between align-items-center font-12">Tidak Memenuhi SLA <a href="<?php echo site_url('tickets/list_sla_perform?kota=PUSAT_CC&tl=N&sla=orange&tgl1='.$date_1.'&tgl2='.$date_2)?>" title="Klik untuk melihat detail"><span class="badge badge-warning badge-pill font-12" id="tidak_memenuhi_sla">0</span></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view("home/home_js"); ?>
<?php $this->load->view("partial/footer"); ?>
