<?php $this->load->view("partial/header"); ?>

<section class="content-header">
  <h1>
	Indeks Kualitas Layanan
	<small></small>
  </h1>
  <ol class="breadcrumb">
	<li><a href="<?php echo site_url('home');?>"><i class="fa fa-dashboard"></i> Home</a></li>
	<li><a href="#">Laporan</a></li>
	<li class="active">Indeks Kualitas Layanan</li>
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
				
				<?php echo form_open($controller_name . '/index_idx', array('id'=>'score_form', 'class'=>'form-horizontal')); ?>
				
				<div class="row">
				  <div class="form-group form-group-sm">
					<?php echo form_label('Tahun', 'tahun', array('class'=>'control-label col-xs-4')); ?>
					<div class='col-xs-2'>
						<?php echo form_dropdown('thn', $years, $thn, 'class="form-control input-sm" id="thn"');?>
					</div>
				  </div>
				</div>
				<div class="row">
				  <div class="form-group form-group-sm">
					<?php echo form_label('Triwulan', 'tw', array('class'=>'control-label col-xs-4')); ?>
					<div class='col-xs-2'>
						<?php echo form_dropdown('tw', $tws, $tw, 'class="form-control input-sm" id="tw"');?>
					</div>
				  </div>
				</div>
				
				<div class="row">
				  
					<div class='col-xs-10'>
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
								<th class="text-center">Indeks Kualitas Jawaban</th>
								<th class="text-center">Indeks TL Rujukan</th>
								<th class="text-center">Indeks Pemenuhan SLA</th>
								<th class="text-center">Indeks Pemenuhan SPM</th>
								<th class="text-center">Indeks Kualitas Layanan</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$i = 1;
							
							foreach($results as $k=>$row): 
							?>
							<tr>
								<td><?php echo $i++; ?></td>
								<td><?php echo $k; ?></td>
								<td align="center"><?php echo (!empty($row['index_kj'])? number_format($row['index_kj'],2):'0'); ?></td>
								<td align="center"><?php echo (!empty($row['index_tl'])? number_format($row['index_tl'],2):'0'); ?></td>
								<td align="center"><?php echo (!empty($row['index_sla'])? number_format($row['index_sla'],2):'0'); ?></td>
								<td align="center"><?php echo (!empty($row['index_spm'])? number_format($row['index_spm'],2):'0'); ?></td>
								<td align="center"><?php 
								
									$index_kj = !empty($row['index_kj'])?$row['index_kj']:0;
									$index_tl = !empty($row['index_tl'])?$row['index_tl']:0;
									$index_sla = !empty($row['index_sla'])?$row['index_sla']:0;
									$index_spm = !empty($row['index_spm'])?$row['index_spm']:0;
									$index_idx = ($index_kj + $index_tl + $index_sla + $index_spm) / 4;
									//print('<pre>');
									//print_r($row);
									//print('</pre>');
									echo number_format($index_idx,2);
								?></td>
							</tr>
							
							<?php 
							
							endforeach; 
							?>
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