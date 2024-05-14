<?php $this->load->view("partial/header"); ?>

<section class="content-header">
  <h1>
	Skor SPM Per Kategori
	<small></small>
  </h1>
  <ol class="breadcrumb">
	<li><a href="<?php echo site_url('home');?>"><i class="fa fa-dashboard"></i> Home</a></li>
	<li><a href="#">Laporan SPM</a></li>
	<li class="active">Skor SPM Per Kategori</li>
  </ol>
</section>
<script type="text/javascript">
$(document).ready(function()
{

    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>

    /*table_support.init({
        resource: '<?php echo site_url('report_score');?>',
        headers: <?php echo $table_headers; ?>,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'id'
        
    });*/
});
</script>
<section class="content">
<div class="row">
	<div class="col-xs-12">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title"></h3>
				<ul id="error_message_box" class="error_message_box"></ul>
			</div>
			<div class="box-body">
				
				<?php echo form_open($controller_name . '/score_per_balai', array('id'=>'score_form', 'class'=>'form-horizontal')); ?>
				<div class="row">
				  <div class="form-group form-group-sm">
					<?php echo form_label('Periode Penilaian', 'spm_id', array('class'=>'control-label col-xs-4')); ?>
					<div class='col-xs-4'>
						<?php echo form_dropdown('spm_id', $spms_tahun, $spm_id, 'class="form-control input-sm" id="spm_id"');?>
					</div>
				  </div>
				</div>
				<div class="row">
				  <div class="form-group form-group-sm">
					<?php echo form_label('Balai', 'city', array('class'=>'control-label col-xs-4')); ?>
					<div class='col-xs-4'>
						<?php echo form_dropdown('city', $cities, $city, 'class="form-control input-sm" id="spm_id"');?>
					</div>
				  </div>
				</div>
				<div class="row">
				  
					<div class='col-xs-12'>
						<p align="center">
							<button type="submit" class="btn btn-primary btn-sm">Proses</button>
							
						</p>
					</div>
				 
				</div>
				
				<?php echo form_close(); ?>
				<!--<div class="row">
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
				 </div>-->
				 
			</div>
		</div>
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title"></h3>
				<ul id="error_message_box" class="error_message_box"></ul>
			</div>
			<div class="box-body">
				
				
				 <div id="table_holder">
					<table id="table" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>No.</th>
								<th>Kode Balai</th>
								<th>Aspek Per Kategori</th>
								<th>Skor SPM</th>
								<th class="text-center">Total Skor</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1.</td>
								<td><?php echo $city; ?></td>
								<td>Sarana dan Prasarana</td>
								<td><?php echo number_format($score_1,2); ?></td>
								<td rowspan="3" align="center" valign="middle"><h2><?php echo number_format($total,2); ?></h2></td>
							</tr>
							<tr>
								<td>2.</td>
								<td><?php echo $city; ?></td>
								<td>Sumber Daya Manusia</td>
								<td><?php echo number_format($score_2,2); ?></td>
							</tr>
							<tr>
								<td>3.</td>
								<td><?php echo $city; ?></td>
								<td>Sistem dan Prosedur</td>
								<td><?php echo number_format($score_3,2); ?></td>
							</tr>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	/*$('#score_form').validate($.extend({
		submitHandler: function(form)
		{
			$(form).ajaxSubmit({
				success: function(response)
				{
					dialog_support.hide();
					table_support.handle_submit('<?php echo site_url('users'); ?>', response);
				},
				dataType: 'json'
			});
		},

		rules:
		{
			
			spm_id: "required",
			city: "required",
			
   		},

		messages: 
		{
     		city: "Kota harus dipilih",
     		spm_id: "Evaluasi harus dipilih",
     		
		}
	}, form_support.error));*/
});
</script>
<?php $this->load->view("partial/footer"); ?>