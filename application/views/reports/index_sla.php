<?php $this->load->view("partial/header"); ?>

<section class="content-header">
  <h1>
	Indeks Pemenuhan SLA
	<small></small>
  </h1>
  <ol class="breadcrumb">
	<li><a href="<?php echo site_url('home');?>"><i class="fa fa-dashboard"></i> Home</a></li>
	<li><a href="#">Laporan Indeks</a></li>
	<li class="active">Indeks Pemenuhan SLA</li>
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
				
				<?php echo form_open($controller_name . '/index_sla', array('id'=>'score_form', 'class'=>'form-horizontal')); ?>
				<div class="row">
				  <div class="form-group form-group-sm">
					<?php echo form_label('Kode Balai', 'city', array('class'=>'control-label col-xs-4')); ?>
					<div class='col-xs-3'>
						<?php echo form_dropdown('city', $cities, $city, 'class="form-control input-sm" id="spm_id"');?>
					</div>
				  </div>
				</div>
				<div class="row">
				  <div class="form-group form-group-sm">
					<?php echo form_label('Tahun', 'tahun', array('class'=>'control-label col-xs-4')); ?>
					<div class='col-xs-2'>
						<?php echo form_dropdown('tahun', $tahun, $thn, 'class="form-control input-sm" id="tahun"');?>
					</div>
				  </div>
				</div>
				<div class="row">
				  <div class="form-group form-group-sm">
					<?php echo form_label('Bulan', 'bln', array('class'=>'control-label col-xs-4')); ?>
					<div class='col-xs-2'>
						<?php echo form_dropdown('bulan', $bulan, $bln, 'class="form-control input-sm" id="bulan"');?>
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
								<th>Balai</th>
								
								<th class="text-center">Jml Layanan</th>
								<th class="text-center">Jml Layanan Memenuhi SLA</th>
								<th class="text-center">Indeks Pemenuhan SLA</th>
								
							</tr>
						</thead>
						<tbody>
							<?php 
							$i = 1;
							foreach($results as $row): ?>
							<tr>
								<td><?php echo $i++; ?></td>
								<td><?php echo $row['city']; ?></td>
								
								<td align="center"><?php echo $row['jml_layanan']; ?></td>
								<td align="center"><?php echo $row['jml_sla']; ?></td>
								<td align="center"><?php echo (!empty($row['index_sla'])?number_format($row['index_sla'],2):'0'); ?></td>
								
							</tr>
							
							<?php endforeach; ?>
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