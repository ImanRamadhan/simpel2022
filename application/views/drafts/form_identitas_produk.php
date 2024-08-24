<h4>Identitas Produk</h4>
						
<div class="row">
	<div class="col-sm-12 col-lg-6">

	<div class="form-group form-group-sm row">

		<?php echo form_label('Nama Dagang', 'prod_nama', array('class'=>'required col-form-label col-sm-3')); ?>
		<div class='col-sm-8'>
			<?php echo form_input(array(
				'name'=>'prod_nama',
				'id'=>'prod_nama',
				
				'class'=>'form-control form-control-sm',
				'value'=>$item_info->prod_nama)
				);?>
		</div>
	</div>
	
	
	
	<div class="form-group form-group-sm row">
		<?php echo form_label('Nama Generik', 'prod_generik', array('class'=>'required col-form-label col-sm-3')); ?>
		<div class='col-sm-8'>
			<?php echo form_input(array(
				'name'=>'prod_generik',
				'id'=>'prod_generik',
				
				'class'=>'form-control form-control-sm',
				'value'=>$item_info->prod_generik)
				);?>
		</div>
	</div>
	<div class="form-group form-group-sm row">
		<?php echo form_label('Pabrik', 'prod_pabrik', array('class'=>'required col-form-label col-sm-3')); ?>
		<div class='col-sm-8'>
			<?php echo form_input(array(
				'name'=>'prod_pabrik',
				'id'=>'prod_pabrik',
				
				'class'=>'form-control form-control-sm',
				'value'=>$item_info->prod_pabrik)
				);?>
		</div>
	</div>
	
	<div class="form-group form-group-sm row">
		<?php echo form_label('No. Reg', 'prod_noreg', array('class'=>'required col-form-label col-sm-3')); ?>
		<div class='col-sm-8'>
			<?php echo form_input(array(
				'name'=>'prod_noreg',
				'id'=>'prod_noreg',
				
				'class'=>'form-control form-control-sm',
				'value'=>$item_info->prod_noreg)
				);?>
		</div>
	</div>
	<div class="form-group form-group-sm row">
		<?php echo form_label('No. Batch', 'prod_nobatch', array('class'=>'required col-form-label col-sm-3')); ?>
		<div class='col-sm-8'>
			<?php echo form_input(array(
				'name'=>'prod_nobatch',
				'id'=>'prod_nobatch',
				
				'class'=>'form-control form-control-sm',
				'value'=>$item_info->prod_nobatch)
				);?>
		</div>
	</div>
	<div class="form-group form-group-sm row">
		<?php echo form_label('Alamat', 'prod_alamat', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
		<div class="col-sm-8">
			<?php echo form_textarea(array(
			'class'=>'form-control form-control-sm', 
			'name'=>'prod_alamat', 
			'id'=>'prod_alamat', 
			'rows'=>3,
			'value'=>$item_info->prod_alamat
			));?>
		</div>
	</div>
	<div class="form-group form-group-sm row">
		<?php echo form_label('Kota', 'prod_kota', array('class'=>'required col-form-label col-sm-3')); ?>
		<div class='col-sm-8'>
			<?php echo form_input(array(
				'name'=>'prod_kota',
				'id'=>'prod_kota',
				
				'class'=>'form-control form-control-sm',
				'value'=>$item_info->prod_kota)
				);?>
		</div>
	</div>

	</div>
	<div class="col-sm-12 col-lg-6">
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Negara', 'prod_negara', array('class'=>' col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<?php echo form_dropdown('prod_negara', $countries, $item_info->prod_negara, 'class="form-control form-control-sm" id="prod_negara" ');?>
			</div>
		</div>
		<div id="div-prod-provinsi" class="form-group form-group-sm row">
			<?php echo form_label('Provinsi', 'provinsi', array('class'=>' col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<?php echo form_dropdown('prod_provinsi', $provinces2, $item_info->prod_provinsi, 'class="form-control form-control-sm" id="prod_provinsi" ');?>
				<?php echo form_input(array(
				'name'=>'prod_provinsi2',
				'id'=>'prod_provinsi2',
				'class'=>'form-control form-control-sm',
				'value'=>$item_info->prod_provinsi)
				);?>
			</div>
		</div>
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Tgl Kadaluarsa', 'prod_kadaluarsa', array('class'=>' col-form-label col-sm-3')); ?>
			<div class='col-sm-4'>
				<div class="input-group">
					<?php echo form_input(array(
						'name'=>'prod_kadaluarsa',
						'id'=>'prod_kadaluarsa',
						
						'class'=>'form-control form-control-sm',
						'value'=>($item_info->prod_kadaluarsa != '0000-00-00'?$item_info->prod_kadaluarsa_fmt:''))
						);?>
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					</div>
				</div>
			</div>
			
		</div>
	
		<div class="form-group form-group-sm row">
			<?php echo form_label('Diperoleh di', 'prod_diperoleh', array('class'=>' col-form-label col-sm-3')); ?>
			<div class='col-sm-7'>
				<?php echo form_input(array(
					'name'=>'prod_diperoleh',
					'id'=>'prod_diperoleh',
					
					'class'=>'form-control form-control-sm',
					'value'=>$item_info->prod_diperoleh)
					);?>
				
			</div>
			
		</div>
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Tgl Diperoleh', 'prod_diperoleh_tgl', array('class'=>' col-form-label col-sm-3')); ?>
			<div class='col-sm-4'>
				<div class="input-group">
					<?php echo form_input(array(
						'name'=>'prod_diperoleh_tgl',
						'id'=>'prod_diperoleh_tgl',
						
						'class'=>'form-control form-control-sm',
						'value'=>($item_info->prod_diperoleh_tgl != '0000-00-00'?$item_info->prod_diperoleh_tgl_fmt:''))
						);?>
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					</div>
				</div>
			</div>
			
		</div>
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Tgl Digunakan', 'prod_digunakan_tgl', array('class'=>' col-form-label col-sm-3')); ?>
			<div class='col-sm-4'>
				<div class="input-group">
					<?php echo form_input(array(
						'name'=>'prod_digunakan_tgl',
						'id'=>'prod_digunakan_tgl',
						
						'class'=>'form-control form-control-sm',
						'value'=>($item_info->prod_digunakan_tgl != '0000-00-00'?$item_info->prod_digunakan_tgl_fmt:''))
						);?>
					
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					</div>
				</div>
			</div>
			
		</div>
		
		
	</div>
</div>