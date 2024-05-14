<h4>Permohonan</h4>
						
<div class="row">
	<div class="col-sm-12 col-lg-6">

	
	<div class="form-group form-group-sm row">
		<?php echo form_label('Hal yang dimohonkan <span class="text-danger">*</span>', 'hal_dimohon', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
		<div class="col-sm-8">
			<?php echo form_textarea(array(
			'class'=>'form-control form-control-sm', 
			'name'=>'sengketa_perihal', 
			'id'=>'sengketa_perihal', 
			'rows'=>5,
			'value'=>$ppid_info->sengketa_perihal
			));?>
		</div>
	</div>
	
	

	</div>
	<div class="col-sm-12 col-lg-6">
		
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Alasan Pengajuan Permohonan <span class="text-danger">*</span>', 'sengketa_alasan', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
			<div class="col-sm-8">
				<?php echo form_textarea(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'sengketa_alasan', 
				'id'=>'sengketa_alasan', 
				'rows'=>5,
				'value'=>$ppid_info->sengketa_alasan
				));?>
			</div>
		</div>
		
	</div>
</div>