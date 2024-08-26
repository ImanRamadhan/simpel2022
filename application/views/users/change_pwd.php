<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					
					<li class="breadcrumb-item active">Profile</li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $page_title; ?></h4>
		</div>
	</div>
</div>

<?php echo form_open($controller_name . '/change_password', array('id'=>'profile_form', 'class'=>'form-horizontal')); ?>
	
	<div class="row">
		<div class="col-lg-8">
		<div class="col-lg-4 alert-info" style="margin-bottom:10px">
			<span>Password harus mengandung :</span></br>
			<span>- Minimal 8 Character</span></br>
			<span>- Minimal mengandung 1 Huruf Besar</span></br>
			<span>- Minimal mengandung 1 Huruf Kecil</span></br>
			<span>- Minimal mengandung 1 Angka</span></br>
			<span>- Minimal mengandung 1 Karakter Spesial</span></br>
		</div>
		<div id="error_message_box" class="error_message_box card alert-danger"></div>		
		<div class="card">
			<div class="card-header bg-primary text-white">
				<?php echo $page_title; ?>
			</div>
			<div class="card-body">
				<div class="row">
						<div class="col-sm-12 col-lg-12">
			
							
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Password Lama', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									
									<?php echo form_input(array(
									'name'=>'oldpassword',
									'id'=>'oldpassword',
									'type' => 'password',
									'class'=>'form-control form-control-sm',
									'value'=>'')
									);?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Password Baru', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									
									<?php echo form_input(array(
									'name'=>'newpassword',
									'id'=>'newpassword',
									'type' => 'password',
									'class'=>'form-control form-control-sm',
									'value'=>'')
									);?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Konfirmasi Password Baru', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									
									<?php echo form_input(array(
									'name'=>'newpassword2',
									'id'=>'newpassword2',
									'type' => 'password',
									'class'=>'form-control form-control-sm',
									'value'=>'')
									);?>
								</div>
							</div>
							
						</div>
				</div>
			</div>
			<div class="card-footer">
				
					<div class="text-center">
						<button class="btn btn-sm btn-primary" id="submit"> Simpan</button>
						<a class="btn btn-sm btn-light" href="<?php echo site_url('profile'); ?>"> Kembali</a>
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
	$('#profile_form').validate($.extend({
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
						setTimeout(function(){window.location.href = "<?php echo site_url('profile/change_pwd'); ?>";}, 2000);
					}
					$('#submit').attr('disabled',false);
				},
				dataType: 'json'
			});
		},

		rules:
		{
			oldpassword: "required",
			newpassword: "required",
			newpassword2: "required",
   		},

		messages: 
		{
			oldpassword: "Password Lama harus diisi",
     		newpassword: "Password Baru harus diisi",
			newpassword2: "Konfirmasi Password Baru harus diisi",
		}
	}, form_support.error));
});
</script>
<?php $this->load->view("partial/footer"); ?>