<?php echo form_open($controller_name . '/resume_data', array('id'=>'database_form', 'class'=>'form-horizontal')); ?>
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
				<?php echo form_label('Pilih Tanggal', 'label_tgl', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<div class="form-group">
					  <div class="input-group">
						<input type="text" id="tgl1" name="tgl1" class="form-control" placeholder="" autocomplete="off">
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
						<input type="text" id="tgl2" name="tgl2" class="form-control" placeholder="" autocomplete="off">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
						</div>
					  </div>
					</div>
				</div>
			</div>
			
			<div class="form-group form-group-sm row">
				<div class='col-sm-12 text-center'>
					<button class="btn btn-warning btn-sm" id="process_btn"> <i class="fa fa-search"></i> Lihat Resume Harian</button>
					<button id="buttonExport" class='btn-export btn btn-success btn-sm' ><i class="fas fa-file-excel"></i> &nbsp; Export</button>
				</div>
			</div>
			<script type="text/javascript">
			$(document).ready(function()
			{
				$('#kota').on('change', function(){
					$.remember({ name: 'database.kota', value: $('#kota').val() })
				});
				
				if($.remember({ name: 'database.kota' }) != null)
					$('#kota').val($.remember({ name: 'database.kota' }));
				
				$("#tgl1").datepicker({
					format : 'dd/mm/yyyy',
					todayHighlight: true,
					endDate: new Date(),
					autoclose: true,
					maxdate: '0',
					zIndexOffset: '1001',
					orientation: 'bottom'
				});
				$("#tgl2").datepicker(
					{
						format : 'dd/mm/yyyy',
					todayHighlight: true,
					endDate: new Date(),
					autoclose: true,
					maxdate: '0',
					zIndexOffset: '1001',
					orientation: 'bottom'
					}
				);
				

			});
			</script>
	</div>
</div>