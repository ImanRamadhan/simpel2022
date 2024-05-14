<?php $this->load->view("partial/header"); ?>

<section class="content-header">
  <h1>
	Skor SPM Per Balai
	<small></small>
  </h1>
  <ol class="breadcrumb">
	<li><a href="<?php echo site_url('home');?>"><i class="fa fa-dashboard"></i> Home</a></li>
	<li><a href="<?php echo site_url('spms');?>">SPM</a></li>
	<li class="active">Skor SPM Per Balai</li>
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
				<?php echo form_open($controller_name . '/grafik', array('id'=>'grafik_form', 'class'=>'form-horizontal')); ?>
				<div class="row">
				  <div class="form-group form-group-sm">
					<?php echo form_label('Pilih Evaluasi', 'spm_id', array('class'=>'control-label col-xs-4')); ?>
					<div class='col-xs-4'>
						<?php echo form_dropdown('spm_id', $spms, '', 'class="form-control input-sm" id="spm_id"');?>
					</div>
				  </div>
				</div>
				<div class="row">
				  <div class="form-group form-group-sm">
					<?php echo form_label('Pilih Balai', 'city', array('class'=>'control-label col-xs-4')); ?>
					<div class='col-xs-4'>
						<?php echo form_dropdown('city', $cities, '', 'class="form-control input-sm" id="spm_id"');?>
					</div>
				  </div>
				</div>
				
				<?php echo form_close(); ?>
				<div class="row">
					<div class='col-xs-4'>
					</div>
					<div class='col-xs-4'>
					  <table class="table table-striped">
						<tr>
							<th>No.</th>
							<th>Aspek Per Kategori</th>
							<th>Skor SPM</th>
						</tr>
						<tr>
							<td>1.</td>
							<td>Sarana dan Prasarana</td>
							<td>2,49</td>
						</tr>
						<tr>
							<td>2.</td>
							<td>Sumber Daya Manusia</td>
							<td>2,00</td>
						</tr>
						<tr>
							<td>3.</td>
							<td>Sistem dan Prosedur</td>
							<td>1,97</td>
						</tr>
						<tr>
							<td></td>
							<td>Total Skor</td>
							<td>2,23</td>
						</tr>
					  </table>
					</div>
				 </div>
			</div>
		</div>
	</div>
</div>
</section>

<?php $this->load->view("partial/footer"); ?>