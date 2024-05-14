<div class="row form-horizontal">
	<div class="col-sm-12 col-lg-8 border-0">
		<div class="form-group form-group-sm row">
			<?php echo form_label('Pilih Grafik', 'label_kota', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-7'>
				<?php
				echo form_dropdown('grafik', $graphs, '', 'class="form-control form-control-sm" id="grafik"');?>				
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Pilih Tanggal', 'label_tgl', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-3'>
				<div class="form-group">
					<div class="input-group">
					<input type="text" id="tgl1" name="tgl1" class="form-control" placeholder="" autocomplete="off">
						<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					</div>
					</div>
				</div>
			</div>
			<div class='col-sm-1'>
				<?php echo form_label('s.d', 'label_kota', array('class'=>'required col-form-label')); ?>
			</div>
			<div class='col-sm-3'>
				<div class="form-group">
					<div class="input-group">
					<input type="text" id="tgl2" name="tgl2" class="form-control" placeholder="" autocomplete="off">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					</div>
					</div>
				</div>
			</div>	
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Pilih Kota', 'label_kota', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-7'>
				<?php
				echo form_dropdown('kota', $cities, '', 'class="form-control form-control-sm" id="kota"');?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Pilih Unit Kerja', 'label_kota', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-7'>
				<?php
				echo form_dropdown('dir', array(0=>''), '', 'class="form-control form-control-sm" id="dir"');?>
			</div>
		</div>
		
		
	
		<div class="form-group form-group-sm row">
			<div class='col-sm-12 text-center'>
				<button class="btn btn-primary btn-sm" id="process_btn"> <i class="fa fa-search"></i> Lihat Grafik</button>
			</div>
		</div>
		<script type="text/javascript">
		
		$(document).ready(function()
		{
			
			
			$('#kota').on('change', function(){
		
				if($(this).val() == '')
				{
					$('#dir').empty();
					return;
				}
				var val = $(this).val();
				
				$.getJSON("<?php echo site_url('depts/get_units/'); ?>" + val, function(data) {
					if(data)
					{
						$('#dir').empty();
						$('#dir').append($('<option></option>').attr('value', 0).text('ALL'));
						$.each(data, function (key, entry) {
							$('#dir').append($('<option></option>').attr('value', entry.id).text(entry.name));
						  })
					}
				});
			});

		});
		
		</script>
	</div>
</div>