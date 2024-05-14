<?php $this->load->view("partial/header"); ?>
<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/libs/jsPDF/jspdf.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.2/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.2/dist/extensions/export/bootstrap-table-export.min.js"></script>
<section class="content-header">
  <h1>
	 Laporan Evaluasi Kualitas Jawaban
	<small></small>
  </h1>
  <ol class="breadcrumb">
	<li><a href="<?php echo site_url('home');?>"><i class="fa fa-dashboard"></i> Home</a></li>
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
			<div class="box-body table-responsive">
			  <?php echo form_open($controller_name . '/evaluasi_kualitas_per_balai', array('id'=>'score_form', 'method'=>'get', 'class'=>'form-horizontal')); ?>
				<div class="row">
				  <div class="form-group form-group-sm">
					<?php echo form_label('Periode Penilaian', 'k_id', array('class'=>'control-label col-xs-4')); ?>
					<div class='col-xs-2'>
						<?php echo form_dropdown('k_id', $evaluations, $k_id, 'class="form-control input-sm" id="k_id"');?>
					</div>
					<div class='col-xs-2'>
						<?php echo form_dropdown('city', $cities, $city, 'class="form-control input-sm" id="city"');?>
					</div>
					<div class="col-xs-1">
						<button type="submit" class="btn btn-primary btn-sm">Proses</button>
					</div>
				  </div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
					<?php
					//print ('<pre>');
					//print_r($hasil);
					//print ('</pre>');
					?>
					<div id="toolbar">
					</div>
					
					<table data-toggle="table" class="table-bordered table-striped" data-show-export="true">
						<thead>
							<tr>
								<th class="text-center" rowspan="2" >No.</th>
								<th class="text-center" rowspan="2" >Kode Balai</th>
								<th class="text-center" colspan="8" >Penilaian Kualitas Jawaban</th>
								
							</tr>
							<tr>
								<th class="text-center">Scientific Based</th>
								<th class="text-center">Dijelaskan Peraturan</th>
								<th class="text-center">Kesesuaian Jawaban</th>
								<th class="text-center">Tepat Kategori</th>
								<th class="text-center">Tepat Jenis Layanan</th>
								<th class="text-center">Tepat Jenis Komoditi</th>
								<th class="text-center">Edukasi</th>
								<th class="text-center">Rata-rata</th>
							</tr>
						</thead>
						<!--<thead>
							<tr>
								<th class="text-center" data-field="no">No.</th>
								<th class="text-center" data-field="kode_balai">Kode Balai</th>
								
								<th class="text-center" data-field="sb">Scientific Based</th>
								<th class="text-center" data-field="dp">Dijelaskan Peraturan</th>
								<th class="text-center" data-field="kj">Kesesuaian Jawaban</th>
								<th class="text-center" data-field="tk">Tepat Kategori</th>
								<th class="text-center" data-field="tjl">Tepat Jenis Layanan</th>
								<th class="text-center" data-field="tjk">Tepat Jenis Komoditi</th>
								<th class="text-center" data-field="edu">Edukasi</th>
								<th class="text-center" data-field="rata2">Rata-rata</th>
							</tr>
						</thead>-->
						<tbody>
						<?php
							$i = 1;
							foreach($hasil as $row):
						?>
							<tr>
								<td align="center"><?php echo $i; ?></td>
								<td align="center"><?php echo $row['city']; ?></td>
								<td align="center"><?php echo !empty($row['1'])? number_format($row['1'],2):''; ?></td>
								<td align="center"><?php echo !empty($row['2'])? number_format($row['2'],2):''; ?></td>
								<td align="center"><?php echo !empty($row['3'])? number_format($row['3'],2):''; ?></td>
								<td align="center"><?php echo !empty($row['4'])? number_format($row['4'],2):''; ?></td>
								<td align="center"><?php echo !empty($row['5'])? number_format($row['5'],2):''; ?></td>
								<td align="center"><?php echo !empty($row['6'])? number_format($row['6'],2):''; ?></td>
								<td align="center"><?php echo !empty($row['7'])? number_format($row['7'],2):''; ?></td>
								<td align="center"><?php echo !empty($row['rata'])? number_format($row['rata'],2):''; ?></td>
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
				
				<?php echo form_close(); ?>
			</div>
		</div>
	
		
</section>

<script>
$(document).ready(function() {
    //$('#table').bootstrapTable();
} );
</script>
<?php $this->load->view("partial/footer"); ?>