<h4>Klasifikasi</h4>
<div class="tab-pane p-3 border rounded-lg" id="klasifikasi" role="tabpanel">
	
	
	<div class="row">
		<div class="col-sm-12 col-lg-12">
		
			<div class="form-group form-group-sm row">
				<?php echo form_label('Jenis Produk', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<p class="form-control-plaintext"><?php echo $item_info->jenis_produk; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pegaduan melalui', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<p class="form-control-plaintext"><?php echo $item_info->submited_via; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Sumber Data', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<p class="form-control-plaintext"><?php echo $item_info->jenis; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Shift', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<p class="form-control-plaintext"><?php echo $item_info->shift; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Klasifikasi', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<p class="form-control-plaintext"><?php echo $item_info->klasifikasi; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Sub Klasifikasi', 'subklasifikasi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<p class="form-control-plaintext"><?php echo $item_info->subklasifikasi; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('SLA', 'sla', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<p class="form-control-plaintext"><?php echo $item_info->sla; ?> HK</p>
				</div>
			</div>
	
		</div>
		
	</div>
</div>