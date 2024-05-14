<h4>Tindak Lanjut</h4>
						
<div class="row">
	<div class="col-sm-12 col-lg-10">
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Perlu Rujuk', 'perlu_rujuk_label', array('for'=>'message', 'class'=>'col-form-label col-sm-2 required')); ?>
			<div class="col-sm-5">
				<div class="form-check-inline my-1">
					<div class="custom-control custom-radio">
						<input type="radio" id="rujuk_yes" name="is_rujuk" class="custom-control-input" value='1' <?php echo ($item_info->is_rujuk == '1'?'checked':'')?>>
						<label class="custom-control-label" for="rujuk_yes">Ya</label>
					</div>
				</div>
				<div class="form-check-inline my-1">
					<div class="custom-control custom-radio">
						<input type="radio" id="rujuk_no" name="is_rujuk" class="custom-control-input" value='0' <?php echo ($item_info->is_rujuk == '0'?'checked':'')?>>
						<label class="custom-control-label" for="rujuk_no">Tidak</label>
					</div>
				</div>
			</div>
		</div>
		<div id="list_rujukan">
		<div class="form-group form-group-sm row">
			<?php echo form_label('SLA Rujukan', 'sla', array('class'=>'required col-form-label col-sm-2')); ?>
			
			<div class="col-sm-2">
				<div class="input-group mb-2">
					
					<?php echo form_input(array(
					'name'=>'sla',
					'id'=>'sla',
					'type'=>'number',
					'min' => 0,
					'class'=>'form-control form-control-sm',
					'value'=>$item_info->sla)
					);?>
					<div class="input-group-append ">
						<span class="input-group-text form-control-sm">Hari kerja</span>
					</div>
				  </div>
			
			</div>
			
		</div>
		<div class="form-group form-group-sm row">
				<?php echo form_label('Rujukan 1.', 'rujuk1', array('class'=>'required col-form-label col-sm-2')); ?>
				<div class='col-sm-8'>
					<?php echo form_dropdown('dir1', $dir_rujukan, $item_info->direktorat, 'class="form-control form-control-sm" id="dir1" ');?>
				</div>
				<div class='col-sm-2'>
					<?php echo form_dropdown('sla1', $sla,  $item_info->d1_prioritas, 'class="form-control form-control-sm" id="sla1" ');?>
				</div>
				
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Rujukan 2.', 'rujuk1', array('class'=>'required col-form-label col-sm-2')); ?>
				<div class='col-sm-8'>
					<?php echo form_dropdown('dir2', $dir_rujukan, $item_info->direktorat2, 'class="form-control form-control-sm" id="dir2" ');?>
				</div>
				<div class='col-sm-2'>
					<?php echo form_dropdown('sla2', $sla, $item_info->d2_prioritas, 'class="form-control form-control-sm" id="sla2" ');?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Rujukan 3.', 'rujuk1', array('class'=>'required col-form-label col-sm-2')); ?>
				<div class='col-sm-8'>
					<?php echo form_dropdown('dir3', $dir_rujukan, $item_info->direktorat3, 'class="form-control form-control-sm" id="dir3" ');?>
				</div>
				<div class='col-sm-2'>
					<?php echo form_dropdown('sla3', $sla, $item_info->d3_prioritas, 'class="form-control form-control-sm" id="sla3" ');?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Rujukan 4.', 'rujuk1', array('class'=>'required col-form-label col-sm-2')); ?>
				<div class='col-sm-8'>
					<?php echo form_dropdown('dir4', $dir_rujukan, $item_info->direktorat4, 'class="form-control form-control-sm" id="dir4" ');?>
				</div>
				<div class='col-sm-2'>
					<?php echo form_dropdown('sla4', $sla, $item_info->d4_prioritas, 'class="form-control form-control-sm" id="sla4" ');?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Rujukan 5.', 'rujuk1', array('class'=>'required col-form-label col-sm-2')); ?>
				<div class='col-sm-8'>
					<?php echo form_dropdown('dir5', $dir_rujukan, $item_info->direktorat5, 'class="form-control form-control-sm" id="dir5" ');?>
				</div>
				<div class='col-sm-2'>
					<?php echo form_dropdown('sla5', $sla, $item_info->d5_prioritas, 'class="form-control form-control-sm" id="sla5" ');?>
				</div>
			</div>
			
			<div class="form-group form-group-sm row">
				
				<div class='col-sm-12'>
					<br />
					<p><strong><u>Keterangan</u>: </strong><br />Rujukan 1 sebagai penindaklanjut utama<br /> Rujukan 2-5 sebagai penindaklanjut/untuk diketahui</p>
					<p><strong><u>Pengisian SLA Rujukan</u>: </strong><br />
					1. Memberikan informasi (SLA 5 HK) <br />
2. Pengaduan tidak memerlukan TL lapangan (SLA 14 HK)<br />
3. Pengaduan memerlukan TL lapangan (SLA  60 HK)<br />
					</p>
				</div>
			</div>
		</div>
		
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	$('#dir1').select2({theme: "bootstrap4"});
	$('#dir2').select2({theme: "bootstrap4"});
	$('#dir3').select2({theme: "bootstrap4"});
	$('#dir4').select2({theme: "bootstrap4"});
	$('#dir5').select2({theme: "bootstrap4"});
	/*
	$('#dir1').select2({}).val('<?php echo $item_info->direktorat;?>').trigger('change');
	$('#dir1').select2({
		theme: "bootstrap4",
		allowClear: true,
		placeholder: "",
		minimumInputLength: 3,
		ajax :  {
			url: '<?php echo site_url('tickets/suggest_unit_teknis');?>',
			dataType: 'json',
            delay: 250,
            data: function (data) {
				return {
					term: data.term, // search term
					dir1: $('#dir1').val(),
					dir2: $('#dir2').val(),
					dir3: $('#dir3').val(),
					dir4: $('#dir4').val(),
					dir5: $('#dir5').val()
				};
            },
			processResults: function (response) {
				return {
					results:response
				};
			},
			cache: true
		}
	});
	$('#dir2').select2({}).val('<?php echo $item_info->direktorat2;?>').trigger('change');
	$('#dir2').select2({
		theme: "bootstrap4",
		allowClear: true,
		placeholder: "",
		minimumInputLength: 3,
		ajax :  {
			url: '<?php echo site_url('tickets/suggest_unit_teknis');?>',
			dataType: 'json',
            delay: 250,
            data: function (data) {
				return {
					term: data.term,
					dir1: $('#dir1').val(),
					dir2: $('#dir2').val(),
					dir3: $('#dir3').val(),
					dir4: $('#dir4').val(),
					dir5: $('#dir5').val()
				};
            },
			processResults: function (response) {
				return {
					results:response
				};
			},
			cache: true
		}
	});
	
	$('#dir3').select2({}).val('<?php echo $item_info->direktorat3;?>').trigger('change');
	$('#dir3').select2({
		theme: "bootstrap4",
		allowClear: true,
		placeholder: "",
		minimumInputLength: 3,
		ajax :  {
			url: '<?php echo site_url('tickets/suggest_unit_teknis');?>',
			dataType: 'json',
            delay: 250,
            data: function (data) {
				return {
					term: data.term, 
					dir1: $('#dir1').val(),
					dir2: $('#dir2').val(),
					dir3: $('#dir3').val(),
					dir4: $('#dir4').val(),
					dir5: $('#dir5').val()
				};
            },
			processResults: function (response) {
				return {
					results:response
				};
			},
			cache: true
		}
	});
	
	$('#dir4').select2({}).val('<?php echo $item_info->direktorat4;?>').trigger('change');
	$('#dir4').select2({
		theme: "bootstrap4",
		allowClear: true,
		placeholder: "",
		minimumInputLength: 3,
		ajax :  {
			url: '<?php echo site_url('tickets/suggest_unit_teknis');?>',
			dataType: 'json',
            delay: 250,
            data: function (data) {
				return {
					term: data.term,
					dir1: $('#dir1').val(),
					dir2: $('#dir2').val(),
					dir3: $('#dir3').val(),
					dir4: $('#dir4').val(),
					dir5: $('#dir5').val()
				};
            },
			processResults: function (response) {
				return {
					results:response
				};
			},
			cache: true
		}
	});
	
	$('#dir5').select2({}).val('<?php echo $item_info->direktorat5;?>').trigger('change');
	$('#dir5').select2({
		theme: "bootstrap4",
		allowClear: true,
		placeholder: "",
		minimumInputLength: 3,
		ajax :  {
			url: '<?php echo site_url('tickets/suggest_unit_teknis');?>',
			dataType: 'json',
            delay: 250,
            data: function (data) {
				return {
					term: data.term, // search term
					dir1: $('#dir1').val(),
					dir2: $('#dir2').val(),
					dir3: $('#dir3').val(),
					dir4: $('#dir4').val(),
					dir5: $('#dir5').val()
				};
            },
			processResults: function (response) {
				return {
					results:response
				};
			},
			cache: true
		}
	});*/
	
});
</script>