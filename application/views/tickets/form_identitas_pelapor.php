<h4>Identitas Pelapor</h4>
						
<div class="row">
	<div class="col-sm-12 col-lg-6">

	<div class="form-group form-group-sm row">

		<?php echo form_label('Nama <span class="text-danger">*</span>', 'iden_nama', array('class'=>' col-form-label col-sm-3')); ?>
		
		<div class='col-sm-8'>
			<?php echo form_input(array(
				'name'=>'iden_nama',
				'id'=>'iden_nama',
				
				'class'=>'form-control form-control-sm',
				'value'=>$item_info->iden_nama)
				);?>
		</div>
	</div>
	
	<div class="form-group form-group-sm row">
		<?php echo form_label('Jenis Kelamin <span class="text-danger">*</span>', 'iden_jk', array('class'=>'col-form-label col-sm-3')); ?>
		<div class='col-sm-3'>
			
			<?php echo form_dropdown('iden_jk', array('' => '', 'L' => 'Laki-laki', 'P' => 'Perempuan'), $item_info->iden_jk, 'class="form-control form-control-sm" id="iden_jk"');?>
		</div>
	</div>
	
	<div class="form-group form-group-sm row">
		<?php echo form_label('Instansi', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
		<div class='col-sm-8'>
			<?php echo form_input(array(
				'name'=>'iden_instansi',
				'id'=>'iden_instansi',
				
				'class'=>'form-control form-control-sm',
				'value'=>$item_info->iden_instansi)
				);?>
		</div>
	</div>
	<div class="form-group form-group-sm row">
		<?php echo form_label('Jenis Perusahaan', 'iden_jenis', array('class'=>'required col-form-label col-sm-3')); ?>
		<div class='col-sm-8'>
			<?php echo form_input(array(
				'name'=>'iden_jenis',
				'id'=>'iden_jenis',
				
				'class'=>'form-control form-control-sm',
				'value'=>$item_info->iden_jenis)
				);?>
		</div>
	</div>
	<div class="form-group form-group-sm row">
		<?php echo form_label('Alamat <span class="text-danger">*</span>', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
		<div class="col-sm-8">
			<?php echo form_textarea(array(
			'class'=>'form-control form-control-sm', 
			'name'=>'iden_alamat', 
			'id'=>'iden_alamat', 
			'rows'=>2,
			'value'=>$item_info->iden_alamat
			));?>
		</div>
	</div>
	<div class="form-group form-group-sm row">
		<?php echo form_label('Email ', 'iden_email', array('class'=>'required col-form-label col-sm-3')); ?>
		<div class='col-sm-8'>
			<?php echo form_input(array(
				'name'=>'iden_email',
				'id'=>'iden_email',
				
				'class'=>'form-control form-control-sm',
				'value'=>$item_info->iden_email)
				);?>
		</div>
	</div>

	</div>
	<div class="col-sm-12 col-lg-6">
		<div class="form-group form-group-sm row">
			<?php echo form_label('Negara', 'iden_negara', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<?php echo form_dropdown('iden_negara', $countries, $item_info->iden_negara, 'class="form-control form-control" id="iden_negara" ');?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Provinsi', 'iden_provinsi', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<?php echo form_dropdown('iden_provinsi', $provinces, $item_info->iden_provinsi, 'class="form-control form-control" id="iden_provinsi" ');?>
				<?php echo form_input(array(
				'name'=>'iden_provinsi2',
				'id'=>'iden_provinsi2',
				'class'=>'form-control form-control-sm',
				'value'=>$item_info->iden_provinsi)
				);?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Kota/Kab', 'iden_kota', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<?php echo form_dropdown('iden_kota', $kabs, $item_info->iden_kota, 'class="form-control form-control" id="iden_kota" ');?>
				<?php echo form_input(array(
				'name'=>'iden_kota2',
				'id'=>'iden_kota2',
				'class'=>'form-control form-control-sm',
				'value'=>$item_info->iden_kota)
				);?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('No. Telp', 'iden_telp', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-3'>
				<?php echo form_input(array(
					'name'=>'iden_telp',
					'id'=>'iden_telp',
					
					'class'=>'form-control form-control-sm',
					'value'=>$item_info->iden_telp)
					);?>
			</div>
			<?php echo form_label('No. Fax', 'iden_fax', array('class'=>'required col-form-label col-sm-2')); ?>
			<div class='col-sm-3'>
				<?php echo form_input(array(
					'name'=>'iden_fax',
					'id'=>'iden_fax',
					
					'class'=>'form-control form-control-sm',
					'value'=>$item_info->iden_fax)
					);?>
			</div>
		</div>
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Pekerjaan <span class="text-danger">*</span>', 'iden_profesi', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-5'>
				<?php echo form_dropdown('iden_profesi', $profesi, $item_info->iden_profesi, 'class="form-control form-control-sm" id="iden_profesi" ');?>
			</div>
			
		</div>
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Usia', 'usia', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-2'>
				<?php echo form_input(array(
					'name'=>'usia',
					'id'=>'usia',
					'type'=>'number',
					'min' => 0,
					'class'=>'form-control form-control-sm',
					'value'=>$item_info->usia)
					);?>
			</div>
		</div>
		
	</div>
</div>