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
			  <?php echo form_open($controller_name . '/evaluasi_kualitas', array('id'=>'score_form', 'method'=>'get', 'class'=>'form-horizontal')); ?>
				<div class="row">
				  <div class="form-group form-group-sm">
					<?php echo form_label('Periode Penilaian', 'k_id', array('class'=>'control-label col-xs-2')); ?>
					<div class='col-xs-2'>
						<?php echo form_dropdown('k_id', $evaluations, $k_id, 'class="form-control input-sm" id="k_id" ');?>
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
								<th class="text-center" rowspan="2" >Nomor Data</th>
								<th class="text-center" rowspan="2" >Kode Balai</th>
								<th class="text-center" colspan="7">Penilaian Kualitas Jawaban</th>
								
							</tr>
							<tr>
								<th class="text-center">Scientific Based</th>
								<th class="text-center">Dijelaskan Peraturan</th>
								<th class="text-center">Kesesuaian Jawaban</th>
								<th class="text-center">Tepat Kategori</th>
								<th class="text-center">Tepat Jenis Layanan</th>
								<th class="text-center">Tepat Jenis Komoditi</th>
								<th class="text-center">Edukasi</th>
							</tr>
							<!--<tr>
								<th class="text-center">No.</th>
								<th class="text-center">Nomor Data</th>
								<th class="text-center">Kode Balai</th>
								
								<th class="text-center">Scientific Based</th>
								<th class="text-center">Dijelaskan Peraturan</th>
								<th class="text-center">Kesesuaian Jawaban</th>
								<th class="text-center">Tepat Kategori</th>
								<th class="text-center">Tepat Jenis Layanan</th>
								<th class="text-center">Tepat Jenis Komoditi</th>
								<th class="text-center">Edukasi</th>
							</tr>-->
						</thead>
						<tbody>
						<?php
							$i = 1;
							foreach($hasil as $row):
						?>
							<tr>
								<td align="center"><?php echo $i; ?></td>
								<td align="center"><a href="<?php echo site_url('evaluasi/show_fill/'.$k_id.'/'.$row['sample_id'].'/'.$row['city']);?>"><?php echo $row['trackid']; ?></a></td>
								<td align="center"><?php echo $row['city']; ?></td>
								<td align="center"><?php echo $row['1']; ?></td>
								<td align="center"><?php echo $row['2']; ?></td>
								<td align="center"><?php echo $row['3']; ?></td>
								<td align="center"><?php echo $row['4']; ?></td>
								<td align="center"><?php echo $row['5']; ?></td>
								<td align="center"><?php echo $row['6']; ?></td>
								<td align="center"><?php echo $row['7']; ?></td>
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