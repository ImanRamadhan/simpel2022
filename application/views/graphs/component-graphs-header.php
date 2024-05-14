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
			<?php if(is_pusat()):?>
			<div class="form-group form-group-sm row" id="divKota">
				<?php echo form_label('Pilih Kota', 'label_kota', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<?php
					echo form_dropdown('kota', $cities, '', 'class="form-control form-control-sm" id="kota"');?>
				</div>
			</div>
			<?php else: ?>
				<input type="hidden" name="kota" id="kota" value="<?php echo get_usercity(); ?>" />
			<?php endif; ?>
		
			<div class="form-group form-group-sm row" id="divJenisLayanan">
				<?php echo form_label('Pilih Jenis Layanan', 'label_jenislayanan', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<?php
					echo form_dropdown('jenislayanan', $jenislayanan, '', 'class="form-control form-control-sm" id="jenislayanan"');?>
				</div>
			</div>
			<div class="form-group form-group-sm row"  id="divdatasource">
				<?php echo form_label('Pilih Sumber Data', 'label_datasource', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-7'>
					<?php
					echo form_dropdown('datasource', $datasources, '', 'class="form-control form-control-sm" id="datasource"');?>
				</div>
			</div>
			<div class="form-group form-group-sm row"  id="divdatasource">
				<?php echo form_label('Pilih Komoditi', 'label_komoditi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<?php
					echo form_dropdown('kategori', $products, '', 'class="form-control form-control-sm" id="kategori"');?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Klasifikasi <span class="text-danger">*</span>', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<?php echo form_dropdown('klasifikasi', $klasifikasi, '', 'class="form-control form-control-sm" id="klasifikasi" ');?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pilih Subklasifikasi <span class="text-danger">*</span>', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-3'>
					<?php echo form_dropdown('subklasifikasi', $subklasifikasi, '', 'class="form-control form-control-sm" id="subklasifikasi" ');?>
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
			$('#klasifikasi').on('change', function(){
		
				if($(this).val() == '')
				{
					$('#subklasifikasi').empty();
					return;
				}
				
				$.getJSON("<?php echo site_url('tickets/get_subklasifikasi2/'); ?>" + $(this).val(), function(data) {
					if(data)
					{
						$('#subklasifikasi').empty();
						$('#subklasifikasi').append($('<option></option>').attr('value', '').text('ALL'));
						$.each(data, function (key, entry) {
							$('#subklasifikasi').append($('<option></option>').attr('value', entry.subklasifikasi).text(entry.subklasifikasi));
						  })
					}
				});
			});
			
			$('#kota').on('change', function(){
		
				if($(this).val() == '')
				{
					$('#dir').empty();
					return;
				}
				
				$.getJSON("<?php echo site_url('tickets/get_unitkerjas/'); ?>" + $(this).val(), function(data) {
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
			/*$('#klasifikasi_id').on('change', function(){
				var klasifikasi = $('#klasifikasi_id').val();
				
				$('#subklasifikasi_id').empty();
				$.each(subklasifikasi[klasifikasi], function (key, entry) {
					//console.log(entry);
					$('#subklasifikasi_id').append($('<option></option>').attr('value', entry.id).text(entry.value));
				});
			});*/
			

		});
		window.onload = function () {
			//$('#grafik').selectedIndex = "1";
			//$('#grafik').change();
			//bindingKota();
		}
		</script>
	</div>
</div>