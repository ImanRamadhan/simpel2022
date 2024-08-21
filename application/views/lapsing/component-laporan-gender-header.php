<div class="row form-horizontal">
	<div class="col-sm-12 col-lg-12 border-0">

		<form action='<?= base_url('Excels/download_lapsing_komoditas') ?>' method="post">
			<?php if ($this->session->city == 'PUSAT') : ?>
				<div class="form-group form-group-sm row">
					<?php echo form_label('Pilih Kota', 'label_kota', array('class' => 'required col-form-label col-sm-2')); ?>
					<div class='col-sm-4'>
						<?php
						echo form_dropdown('kota', $cities, '', 'class="form-control form-control-sm" id="kota"'); ?>
					</div>
				</div>
				<div class="form-group form-group-sm row">
					<?php echo form_label('Pilih Direktorat', 'label_direktorat', array('class' => 'required col-form-label col-sm-2')); ?>
					<div class='col-sm-4'>
						<?php
						echo form_dropdown('direktorat',  $direktorat, '', 'class="form-control form-control-sm" id="direktorat"'); ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih gender', 'label_gender', array('class' => 'required col-form-label col-sm-2')); ?>
				<div class='col-sm-4'>
					<?php
					echo form_dropdown('gender', $genders, '', 'class="form-control form-control-sm" id="gender"'); ?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Sumber Data', 'label_kota', array('class' => 'required col-form-label col-sm-2')); ?>
				<div class='col-sm-4'>
					<?php
					echo form_dropdown('jenis', array('ALL' => 'ALL', 'LAYANAN' => 'Layanan', 'PPID' => 'PPID', 'SP4N' => 'SP4N', 'LAYANAN_SP4N' => 'Layanan + SP4N'), '', 'class="form-control form-control-sm" id="jenis"'); ?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Tanggal', 'label_tgl', array('class' => 'required col-form-label col-sm-2')); ?>
				<div class='col-sm-4'>
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


			</div>

			<?php echo form_input(
				array(
					'name' => 'formType',
					'id' => 'formType',
					'type' => 'hidden',
					'value' => $lapsing_type
				)
			); ?>
			<!--<div class='col-sm-12 text-right'>
				<button type="button" id="buttonExport" class='btn-export btn btn-success text-white font-weight-bold ' style="border-radius: 0.25rem;padding: 8px 15px;"><i class="fas fa-file-excel"></i> &nbsp; Export</a>
			</div>-->
		</form>
		<!--<div class="form-group form-group-sm row">
				<div class='col-sm-12 text-center'>
					<button class="btn btn-primary btn-sm" id="process_btn"> <i class="fa fa-search"></i> Lihat Laporan</button>
				</div>
			</div>-->
		<div class="form-group form-group-sm row">
			<div class='col-sm-7 text-center'>
				<button class="btn btn-primary btn-sm" id="process_btn"> <i class="fa fa-search"></i> Lihat Laporan</button>
			</div>
			<div class='col-sm-5 text-center'>
				<button type="button" id="buttonExport" class="btn btn-sm btn-primary float-right"><i class="fa fa-download"></i>&nbsp;Export</button>
			</div>
		</div>
	</div>
</div>