<div class="row form-horizontal">
	<div class="col-sm-12 col-lg-8 border-0">
		
			<?php if(is_pusat()):?>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Kota', 'label_kota', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-6'>
					<?php echo form_dropdown('kota', $cities, (!empty($this->session->tickets_city)?$this->session->tickets_city:''), 'class="form-control form-control-sm" id="kota"');?>
				</div>
			</div>
			<?php endif; ?>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Tanggal', 'label_tgl', array('class'=>'required col-form-label col-sm-3')); ?>
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
				
				
			</div>
		
			<div class="form-group form-group-sm row">
				<div class='col-sm-12 text-center'>
					<button class="btn btn-primary btn-sm" id="process_btn"> <i class="fa fa-search"></i> Cari Layanan</button>
				</div>
			</div>
			<script type="text/javascript">
			$(document).ready(function()
			{
				
				

			});
			</script>
	</div>
</div>