<?php $this->load->view("partial/header"); ?>

<section class="content-header">
  <h1>
	 Laporan Evaluasi Kualitas Jawaban
	<small></small>
  </h1>
  <ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	<li><a href="#"> Laporan</a></li>
	<li class="active">Laporan Evaluasi Kualitas Jawaban</li>
  </ol>
</section>
<section class="content">
<div class="row">
	<div class="col-xs-12">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title"></h3>
			</div>
			<div class="box-body">
			  <?php echo form_open($controller_name . '/evaluasi_kualitas', array('id'=>'score_form', 'class'=>'form-horizontal')); ?>
				<div class="row">
				  <div class="form-group form-group-sm">
					<?php echo form_label('Tahun Penilaian', 'spm_id', array('class'=>'control-label col-xs-4')); ?>
					<div class='col-xs-2'>
						<?php echo form_dropdown('spm_id', $spms, '', 'class="form-control input-sm" id="spm_id"');?>
					</div>
					<div class="col-xs-1">
						<button type="submit" class="btn btn-primary btn-sm">Proses</button>
					</div>
				  </div>
				</div>
				
				<div class="row">
				  
					<div class='col-xs-9'>
						<p align="center">
							
							
						</p>
					</div>
				 
				</div>
				
				<?php echo form_close(); ?>
			</div>
		</div>
	
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title"></h3>
			</div>
			<div class="box-body">
			  
			</div>
		</div>
</section>

<script>

</script>
<?php $this->load->view("partial/footer"); ?>