<h4>Identitas Pelapor</h4>
<div class="tab-pane p-3 border rounded-lg" id="pelapor" role="tabpanel">
	
	
	<div class="row">
		<div class="col-sm-12 col-lg-6">
	
		<div class="form-group form-group-sm row">

			<?php echo form_label('Nama', 'iden_nama', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<p class="form-control-plaintext"><?php echo $item_info->iden_nama; ?></p>
				
			</div>
		</div>
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Jenis Kelamin', 'iden_jk', array('class'=>'col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<p class="form-control-plaintext">
				<?php 
				if($item_info->iden_jk == 'L')
					echo 'Laki-laki';
				elseif($item_info->iden_jk == 'P')
					echo 'Perempuan';
				else
					echo $item_info->iden_jk; 
				?></p>
				
			</div>
		</div>
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Instansi', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<p class="form-control-plaintext"><?php echo $item_info->iden_instansi; ?></p>
				
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Jenis Perusahaan', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<p class="form-control-plaintext"><?php echo $item_info->iden_jenis; ?></p>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Alamat', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
			<div class="col-sm-8">
				<p class="form-control-plaintext"><?php echo $item_info->iden_alamat; ?></p>
				
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Email', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<p class="form-control-plaintext"><?php echo $item_info->iden_email; ?></p>
				
			</div>
		</div>
	
		</div>
		<div class="col-sm-12 col-lg-6">
			<div class="form-group form-group-sm row">
				<?php echo form_label('Negara', 'iden_negara', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->iden_negara; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Provinsi', 'iden_provinsi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->iden_provinsi; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Kota/Kab', 'iden_kota', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->iden_kota; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('No. Telp', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<p class="form-control-plaintext"><?php echo $item_info->iden_telp; ?></p>
					
				</div>
				<?php echo form_label('No. Fax', 'iden_instansi', array('class'=>'required col-form-label col-sm-2')); ?>
				<div class='col-sm-2'>
					<p class="form-control-plaintext"><?php echo $item_info->iden_fax; ?></p>
					
				</div>
			</div>
			
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pekerjaan', 'iden_profesi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<p class="form-control-plaintext"><?php echo $item_info->profesi; ?></p>
					
				</div>
				
			</div>
			
			<div class="form-group form-group-sm row">
				<?php echo form_label('Usia', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-2'>
					<p class="form-control-plaintext"><?php echo ($item_info->usia > 0)?$item_info->usia:''; ?></p>
				</div>
			</div>
			
		</div>
	</div>
	
</div>