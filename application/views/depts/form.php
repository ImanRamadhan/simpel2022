<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Data Master</a></li>
					<li class="breadcrumb-item active">Pengaturan Balai</li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $page_title; ?></h4>
		</div>
	</div>
</div>
<?php echo form_open($controller_name . '/save/' . $item_info->id, array('id'=>'dept_form', 'class'=>'form-horizontal')); ?>
	
	<div class="row">
		<div class="col-lg-8">
			<ul id="error_message_box" class="error_message_box card alert-danger"></ul>
			
			<div class="card">
				<div class="card-header bg-primary text-white">
					<?php echo $page_title; ?>
				</div>
				<div class="card-body">
				
					<div class="row">
							<div class="col-sm-12 col-lg-12">
							
								<div class="form-group form-group-sm row">
									
									<?php echo form_label('Nama Unit Kerja <span class="text-danger">*</span>', 'name', array('class'=>'col-form-label col-sm-3')); ?>
									
									<div class='col-sm-8'>
										<?php echo form_input(array(
											'name'=>'name',
											'id'=>'name',
											'class'=>'form-control form-control-sm',
											'value'=>$item_info->name)
											);?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									
									<?php echo form_label('Kode', 'kode_prefix', array('class'=>'col-form-label col-sm-3')); ?>
									
									<div class='col-sm-2'>
										<?php echo form_input(array(
											'name'=>'kode_prefix',
											'id'=>'kode_prefix',
											'maxlength'=>3,
											'class'=>'form-control form-control-sm',
											'value'=>$item_info->kode_prefix)
											);?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('Kota <span class="text-danger">*</span>', 'kota', array('class'=>'col-form-label col-sm-3')); ?>
									
									<div class='col-sm-4'>
										
										<?php echo form_dropdown('kota', $cities, $item_info->kota, 'class="form-control form-control-sm" id="kota"');?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
							
									<?php echo form_label('Status <span class="text-danger">*</span>', 'dir_status', array('class'=>'col-form-label col-sm-3')); ?>
									
									<div class='col-sm-4'>
										<?php echo form_dropdown('dir_status', array('1' => 'Aktif', '0' => 'Tidak Aktif'), $item_info->dir_status, 'class="form-control form-control-sm" id="dir_status"');?>
										
									</div>
								</div>
				
							</div>
					
					</div>
				</div>
				<div class="card-footer">
				
					<div class="text-center">
						
						<button class="btn btn-sm btn-primary" id="submit"> Simpan</button>
						<a class="btn btn-sm btn-light" href="<?php echo site_url('depts'); ?>"> Batal</a>
					</div>
				</div>
			</div>
		
		
		</div>
	</div>

<?php echo form_close(); ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	$('#dept_form').validate($.extend({
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
					
					setTimeout(function(){window.location.href = "<?php echo site_url('depts'); ?>";}, 1000);
				},
				dataType: 'json'
			});
		},

		rules:
		{
			name: "required",
			kota: "required",
			
   		},

		messages: 
		{
     		name: "Nama Unit Kerja harus diisi",
     		kota: "Kota harus diisi",
     		
		}
	}, form_support.error));
});
</script>
