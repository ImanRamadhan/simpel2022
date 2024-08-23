<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Data Master</a></li>
					<li class="breadcrumb-item active">Pengaturan Calendar</li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $page_title; ?></h4>
		</div>
	</div>
</div>



<?php echo form_open($controller_name . '/save/' . $item_info->id, array('id'=>'calendar_form', 'class'=>'form-horizontal')); ?>
	
	<div class="row">
		<div class="col-lg-8">
		<div id="error_message_box" class="error_message_box alert-danger"></div>
		<div class="card">
			<div class="card-header bg-primary text-white">
				
				<?php echo $page_title; ?>
			</div>
			<div class="card-body">
		
				<div class="form-group form-group-sm row">
					
					<?php echo form_label('Tanggal <span class="text-danger">*</span>', 'tgl', array('class'=>'required control-label col-sm-3')); ?>
					
					<div class='col-sm-4'>
						<?php echo form_input(array(
							'name'=>'tgl',
							'id'=>'tgl',
							'class'=>'form-control form-control-sm',
							'value'=>$item_info->tgl)
							);?>
					</div>
				</div>
				
				<div class="form-group form-group-sm row">
					<?php echo form_label('Keterangan <span class="text-danger">*</span>', 'keterangan', array('class'=>'required control-label col-sm-3')); ?>
					
					<div class='col-sm-8'>
						<?php echo form_input(array(
							'name'=>'keterangan',
							'id'=>'keterangan',
							'class'=>'form-control form-control-sm',
							'value'=>$item_info->keterangan)
							);?>
					</div>
				</div>

			</div>
			<div class="card-footer">
				
					<div class="text-center">
						
						<button class="btn btn-primary" id="submit"> Simpan</button>
						<a class="btn btn-light" href="<?php echo site_url('calendars'); ?>"> Batal</a>
					</div>
			</div>
		</div>

		</div>
	</div>
		
	</fieldset>

<?php echo form_close(); ?>

<script type="text/javascript">

//validation and submit handling
$(document).ready(function()
{
	$("#tgl").datepicker(
		{format : 'yyyy-mm-dd',
        todayHighlight: true,
        //endDate: new Date(),
        autoclose: true}
	);
	$('#calendar_form').validate($.extend({
		submitHandler: function(form)
		{
			$(form).ajaxSubmit({
				beforeSubmit: function() {
					$('#submit').attr('disabled',true);
					//$('#submit').html("<i class='fa fa-spinner fa-spin'></i> Processing...");
				},
				success: function(response)
				{
					$.notify(response.message, { type: response.success ? 'success' : 'danger' });
					
					if (response.success) {
						setTimeout(function(){window.location.href = "<?php echo site_url('calendars'); ?>";}, 2000);
					}

					$('#submit').attr('disabled',false);
				},
				dataType: 'json'
			});
		},

		rules:
		{
			tgl: "required",
			keterangan: "required"
   		},

		messages: 
		{
     		tgl: "Tanggal harus diisi",
     		keterangan: "Keterangan harus diisi"
		}
	}, form_support.error));
});
</script>
<?php $this->load->view("partial/footer"); ?>