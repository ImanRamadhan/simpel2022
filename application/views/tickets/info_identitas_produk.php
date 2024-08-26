<h4>Identitas Produk</h4>
<div class="tab-pane p-3 border rounded-lg" id="produk" role="tabpanel">


	<div class="row">
		<div class="col-sm-12 col-lg-6">

			<div class="form-group form-group-sm row">

				<?php echo form_label('Nama Dagang', 'iden_nama', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->prod_nama; ?></p>

				</div>
			</div>



			<div class="form-group form-group-sm row">
				<?php echo form_label('Nama Generik', 'iden_instansi', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->prod_generik; ?></p>

				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pabrik', 'iden_instansi', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->prod_pabrik; ?></p>

				</div>
			</div>

			<div class="form-group form-group-sm row">
				<?php echo form_label('No. Reg', 'iden_instansi', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->prod_noreg; ?></p>

				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('No. Batch', 'iden_instansi', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->prod_nobatch; ?></p>

				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Alamat', 'alamat_label', array('for' => 'message', 'class' => 'col-form-label col-sm-3 required')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo escape_input($item_info->prod_alamat); ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Kota', 'kota_label', array('for' => 'message', 'class' => 'col-form-label col-sm-3 required')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $item_info->prod_kota; ?></p>
				</div>
			</div>

		</div>
		<div class="col-sm-12 col-lg-6">

			<div class="form-group form-group-sm row">
				<?php echo form_label('Negara', 'iden_instansi', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->prod_negara; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Provinsi', 'iden_instansi', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->prod_provinsi; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Tgl Kadaluarsa', 'iden_instansi', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext">
						<?php echo ($item_info->prod_kadaluarsa == '0000-00-00') ? '-' : $item_info->prod_kadaluarsa_fmt; ?></p>
				</div>

			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Diperoleh di', 'iden_instansi', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<p class="form-control-plaintext"><?php echo $item_info->prod_diperoleh; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Tgl Diperoleh', 'iden_instansi', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<p class="form-control-plaintext">
						<?php echo ($item_info->prod_diperoleh_tgl == '0000-00-00') ? '-' : $item_info->prod_diperoleh_tgl_fmt; ?></p>
				</div>
			</div>

			<div class="form-group form-group-sm row">
				<?php echo form_label('Tgl Digunakan', 'iden_instansi', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<p class="form-control-plaintext">
						<?php echo ($item_info->prod_digunakan_tgl == '0000-00-00') ? '-' : $item_info->prod_digunakan_tgl_fmt; ?></p>
				</div>

			</div>


		</div>
	</div>

</div>