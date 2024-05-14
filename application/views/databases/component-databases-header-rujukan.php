<?php echo form_open($controller_name . '/database_rujukan_data', array('id'=>'database_form', 'class'=>'form-horizontal')); ?>
<div class="row">
	<div class="col-sm-12 col-lg-8 border-0">
			<?php if($this->session->city == 'PUSAT'): ?>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Kota', 'label_kota', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-6'>
					<?php
					echo form_dropdown('kota', $cities, '', 'class="form-control form-control-sm" id="kota"');?>
				</div>
			</div>
			<?php endif; ?>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Komoditas', 'label_komoditas', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-6'>
					<?php
					echo form_dropdown('kategori', $categories, '', 'class="form-control form-control-sm" id="kategori"');?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Sumber Data', 'label_datasource', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-6'>
					<?php
					echo form_dropdown('datasource', $datasources, '', 'class="form-control form-control-sm" id="datasource"');?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Status Rujukan', 'label_status_rujukan', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-6'>
					<?php
					echo form_dropdown('statusRujukan', $statusRujukan, '', 'class="form-control form-control-sm" id="statusRujukan"');?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Tanggal', 'label_tgl', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<div class="form-group">
					  <div class="input-group">
						<input type="text" id="tgl1" name="tgl1" class="form-control" placeholder="" autocomplete="off" value="<?php echo (!empty($this->input->post('tgl1'))?$this->input->post('tgl1'):date('01/m/Y'))?>" />
						 <div class="input-group-prepend">
							<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
						</div>
					  </div>
					</div>
				</div>
				<div class='col-sm-1'>
					<?php echo form_label('s.d', 'label_kota', array('class'=>'required col-form-label')); ?>
				</div>
				<div class='col-sm-4'>
					<div class="form-group">
					  <div class="input-group">
						<input type="text" id="tgl2" name="tgl2" class="form-control" placeholder="" autocomplete="off" value="<?php echo (!empty($this->input->post('tgl2'))?$this->input->post('tgl2'):date('d/m/Y'))?>" />
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
						</div>
					  </div>
					</div>
				</div>
			</div>
			
			<div class="form-group form-group-sm row">
				<div class='col-sm-12 text-center'>
					<button class="btn btn-warning btn-sm" id="process_btn"> <i class="fa fa-search"></i> Lihat Database Rujukan</button>
					<button id="buttonExport" class='btn-export btn btn-success btn-sm' ><i class="fas fa-file-excel"></i> &nbsp; Export</button>
				</div>
			</div>
			<script type="text/javascript">
			$(document).ready(function()
			{
				$('#kota').on('change', function(){
					$.remember({ name: 'lapsing.kota', value: $('#kota').val() })
				});
				
				if($.remember({ name: 'lapsing.kota' }) != null)
					$('#kota').val($.remember({ name: 'lapsing.kota' }));
				
				$("#tgl1").datepicker({
					format : 'dd/mm/yyyy',
					todayHighlight: true,
					endDate: new Date(),
					autoclose: true,
					maxdate: '0'
				});
				$("#tgl2").datepicker(
					{format : 'dd/mm/yyyy',
					todayHighlight: true,
					endDate: new Date(),
					autoclose: true,
					maxdate: '0'}
				);
				

			});
			</script>
	</div>
</div>
<?php echo form_close(); ?>