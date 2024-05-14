<?php $this->load->view("partial/header"); ?>

<section class="content-header">
  <h1>
	 Laporan Evaluasi SPM
	<small></small>
  </h1>
  <ol class="breadcrumb">
	<li><a href="<?php echo site_url('home');?>"><i class="fa fa-dashboard"></i> Home</a></li>
	<li><a href="#"> Laporan</a></li>
	<li class="active">Laporan Evaluasi SPM</li>
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
			  <?php echo form_open($controller_name . '/evaluasi_spm', array('id'=>'score_form', 'method'=>'get', 'class'=>'form-horizontal')); ?>
				<div class="row">
				  <div class="col-md-12">
					  <div class="form-group form-group-sm">
						<?php echo form_label('Periode Penilaian', 'spm_id', array('class'=>'control-label col-sm-4')); ?>
						<div class='col-sm-4'>
							<?php echo form_dropdown('spm_id', $evaluations, $spm_id, 'class="form-control input-sm" id="spm_id"');?>
						</div>
						<div class="col-sm-1">
							<button type="submit" class="btn btn-primary btn-sm">Proses</button>
						</div>
					  </div>
					</div>
				</div>
				<?php echo form_close(); ?>
				<div class="row">
					<div class="col-md-12">
					<?php
					//print ('<pre>');
					//print_r($hasil);
					//print ('</pre>');
					?>
					<div id="toolbar">
					</div>
					
					<table class="table table-bordered table-striped" data-toggle="table" data-show-export="true">
						<thead>
							<tr>
								<th class="text-center" >No.</th>
								<th class="text-center" >Kode Balai</th>
								<th class="text-center" >% Selesai</th>
								<th class="text-center" >Indeks Pemenuhan SPM</th>
								
							</tr>
						</thead>
						<tbody>
						<?php
							$i = 1;
							foreach($hasil as $row):
						?>
							<tr>
								<td align="center"><?php echo $i; ?></td>
								<td align="center"><a href="<?php echo site_url('reports/evaluasi_spm_detail/'.$row['spm_id'].'/'.$row['city']);?>"><?php echo $row['city']; ?></a></td>
								
								<td align="center"><?php echo number_format(($row['answered']==''?0:$row['answered'])/$row['num_of_standards']*100,0); ?> %</td>
								<td align="center"><?php echo $row['avg_score'] == 0? '': number_format($row['avg_score'], 2); ?></td>
							</tr>
						<?php
							$i++;
							endforeach; 
						?>
						</tbody>
					</table>

					<br />
					</div>
				</div>
				
				
			</div>
		</div>
	
		
</section>

<script>
$(document).ready(function() {
    //$('#table').bootstrapTable();
} );
</script>
<?php $this->load->view("partial/footer"); ?>