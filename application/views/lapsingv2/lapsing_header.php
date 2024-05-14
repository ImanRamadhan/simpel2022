
<div class="row form-horizontal">
	<div class="col-sm-3 col-lg-3">
	</div>
	<div class="col-sm-6 col-lg-6 border-0">
		
		<?php echo form_open($controller_name . '/lapsing_data', array('id'=>'lapsing_form', 'class'=>'form-horizontal')); ?>
			<?php if($this->session->city == 'PUSAT'):?>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Kota', 'label_kota', array('class'=>'required col-form-label col-sm-2')); ?>
				<div class='col-sm-8'>
					<?php
					echo form_dropdown('kota', $cities, '', 'class="form-control form-control-sm" id="kota"');?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Sumber Data', 'label_kota', array('class'=>'required col-form-label col-sm-2')); ?>
				<div class='col-sm-8'>
					<?php
					echo form_dropdown('jenis', array('ALL'=>'ALL','LAYANAN'=>'Layanan','PPID'=>'PPID','SP4N'=>'SP4N', 'LAYANAN_SP4N'=>'Layanan + SP4N'), $jenis, 'class="form-control form-control-sm" id="jenis"');?>
				</div>
			</div>
			<?php endif; ?>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Tanggal', 'label_tgl', array('class'=>'required col-form-label col-sm-2')); ?>
				<div class='col-sm-8'>
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
			
			
		</form>
			<div class="form-group form-group-sm row">
				<?php echo form_label('', 'label', array('class'=>'required col-form-label col-sm-2')); ?>
				<div class='col-sm-6 text-center'>
					<?php echo form_input(array(
						'name'=>'formType',
						'id'=>'formType',
						'type' => 'hidden',
						'value'=>$lapsing_type)
					);?>
					<button class="btn btn-primary btn-sm btn-warning" id="process_btn"> <i class="fa fa-search"></i> Lihat Laporan</button>		<button type="button" id="buttonExport" class="btn btn-sm btn-success"><i class="fa fa-download"></i >&nbsp;Export</button>
				</div>
				
			</div>
	</div>
</div>
