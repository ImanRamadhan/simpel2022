<h4>Klasifikasi</h4>
						
<div class="row">
	<div class="col-sm-12 col-lg-6">
		<div class="form-group form-group-sm row">
			<?php echo form_label('Jenis Layanan ', 'jenis_layanan', array('for'=>'message', 'class'=>'col-form-label col-sm-4')); ?>
			<div class="col-sm-8">
				<div class="form-check-inline my-1">
					<div class="custom-control custom-radio">
						<input type="radio" id="informasiradio" name="info" class="custom-control-input" value="I" disabled />
						<label class="custom-control-label" for="informasiradio">Permintaan Informasi</label>
					</div>
				</div>
				<div class="form-check-inline my-1">
					<div class="custom-control custom-radio">
						<input type="radio" id="pengaduanradio" name="info" class="custom-control-input" value="P" checked />
						<label class="custom-control-label" for="pengaduanradio">Pengaduan</label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Jenis Komoditi <span class="text-danger">*</span>', 'kategori', array('class'=>'required col-form-label col-sm-4')); ?>
			<div class='col-sm-6'>
				<?php echo form_dropdown('kategori', $products, $item_info->kategori, 'class="form-control form-control-sm" id="kategori" ');?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Layanan melalui <span class="text-danger">*</span>', 'iden_instansi', array('class'=>'required col-form-label col-sm-4')); ?>
			<div class='col-sm-4'>
				<?php echo form_dropdown('submited_via', $submited_via, $item_info->submited_via, 'class="form-control form-control-sm" id="submited_via" ');?>
			</div>
			<div class='col-sm-3'>
				<?php echo form_dropdown('tipe_medsos', array('Facebook'=>'Facebook', 'Twitter'=>'Twitter','Instagram'=>'Instagram'), $item_info->tipe_medsos, 'class="form-control form-control-sm" id="tipe_medsos" ');?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Sumber Data', 'jenis', array('class'=>'required col-form-label col-sm-4')); ?>
			<div class='col-sm-4'>
				<?php echo form_dropdown('jenis', array('PPID'=>'PPID'), $item_info->jenis, 'class="form-control form-control-sm" id="jenis" ');?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Shift <span class="text-danger">*</span>', 'iden_instansi', array('class'=>'required col-form-label col-sm-4')); ?>
			<div class='col-sm-4'>
				<?php echo form_dropdown('shift', array('1' => '1', '2' => '2', '3'=> '3'), $item_info->shift, 'class="form-control form-control-sm" id="shift" ');?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Klasifikasi <span class="text-danger">*</span>', 'iden_instansi', array('class'=>'required col-form-label col-sm-4')); ?>
			<div class='col-sm-8'>
				<?php echo form_dropdown('klasifikasi', $klasifikasi, $item_info->klasifikasi, 'class="form-control form-control-sm" id="klasifikasi" ');?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Sub Klasifikasi <span class="text-danger">*</span>', 'iden_instansi', array('class'=>'required col-form-label col-sm-4')); ?>
			<div class='col-sm-8'>
				<?php echo form_dropdown('subklasifikasi', $subklasifikasi, $item_info->subklasifikasi, 'class="form-control form-control-sm" id="subklasifikasi" ');?>
			</div>
		</div>
		
	</div>
	
</div>