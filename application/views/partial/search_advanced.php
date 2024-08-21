<div class="row form-horizontal">
	<div class="col-sm-12 col-lg-8 border-0">

		<?php if (is_pusat()): ?>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Kota', 'label_kota', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-6'>
					<?php echo form_dropdown('kota', $cities, (!empty($this->session->tickets_city) ? $this->session->tickets_city : ''), 'class="form-control form-control-sm" id="kota"'); ?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Direktorat', 'label_direktorat', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-6'>
					<?php
					echo form_dropdown('direktorat',  $direktorat, '', 'class="form-control form-control-sm" id="direktorat"'); ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Pilih Tanggal', 'label_tgl', array('class' => 'required col-form-label col-sm-3')); ?>
			<div class='col-sm-6'>
				<div class="form-group">
					<div class="input-group">

						<input type="text" id="tgl1" name="tgl1" class="form-control form-control-sm" placeholder="" autocomplete="off">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
						</div>
						<div class="mt-1">&nbsp;s.d&nbsp;</div>
						<input type="text" id="tgl2" name="tgl2" class="form-control form-control-sm" placeholder="" autocomplete="off">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
						</div>

					</div>

				</div>
			</div>
			<div class='col-sm-3'>
				<a data-toggle="collapse" href="#collapseBox" aria-expanded="false" aria-controls="collapseBox">[Pencarian Lanjutan]</a>
			</div>

		</div>
		<div class="collapse" id="collapseBox">
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Jenis Komoditas', 'label_kota', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<?php echo form_dropdown('kategori', $categories, '', 'class="form-control form-control-sm" id="kategori"'); ?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Sumber Data', 'label_kota', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<?php echo form_dropdown('jenis', $datasources, '', 'class="form-control form-control-sm" id="jenis"'); ?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Profesi', 'label_kota', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<?php echo form_dropdown('iden_profesi', $jobs, '', 'class="form-control form-control-sm" id="iden_profesi"'); ?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Layanan Melalui', 'label_kota', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<?php echo form_dropdown('submited_via', $mekanism, '', 'class="form-control form-control-sm" id="submited_via"'); ?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Cari berdasarkan', 'label', array('class' => 'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<?php echo form_dropdown('field', $fields, (!empty($this->input->get('field')) ? $this->input->get('field') : ''), 'class="form-control form-control-sm" id="field"'); ?>

				</div>
				<div class='col-sm-6'>
					<?php echo form_input(
						array(
							'name' => 'keyword',
							'id' => 'keyword',
							'placeholder' => 'Masukkan Kata Kunci',
							'class' => 'form-control form-control-sm',
							'value' => (!empty($this->input->get('keyword')) ? $this->input->get('keyword') : '')
						)
					); ?>
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<div class='col-sm-12 text-center'>
				<button class="btn btn-primary btn-sm" id="process_btn"> <i class="fa fa-search"></i> Cari Layanan</button>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#kota').on('change', function() {

					if ($(this).val() == '') {
						$('#direktorat').empty();
						return;
					}

					$.getJSON("<?php echo site_url('lapsingv2/get_direktorat/'); ?>" + $(this).val(), function(data) {
						if (data) {
							$('#direktorat').empty();
							$('#direktorat').append($('<option></option>').attr('value', '').text('ALL'));
							$.each(data, function(key, entry) {
								$('#direktorat').append($('<option></option>').attr('value', entry.id).text(entry.name));
							})
						}
					});
				});



			});
		</script>
	</div>
</div>