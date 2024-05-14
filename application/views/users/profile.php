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

<?php echo form_open($controller_name . '/save', array('id'=>'profile_form', 'class'=>'form-horizontal')); ?>
	
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
								
								<?php echo form_label('Username', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									<p class="form-control-plaintext"><?php echo $item_info->user; ?></p>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Nama Lengkap', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									<p class="form-control-plaintext"><?php echo $item_info->name; ?></p>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Unit Kerja', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									<p class="form-control-plaintext"><?php echo $item_info->nama_direktorat; ?></p>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Balai/Loka', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									<p class="form-control-plaintext"><?php echo $item_info->city; ?></p>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Email', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									
									<?php echo form_input(array(
									'name'=>'email',
									'id'=>'email',
									'class'=>'form-control form-control-sm',
									'value'=>$item_info->email)
									);?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('No. HP', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-3'>
									
									<?php echo form_input(array(
									'name'=>'no_hp',
									'id'=>'no_hp',
									'class'=>'form-control form-control-sm',
									'value'=>$item_info->no_hp)
									);?>
								</div><span><i>Format Nomor : 08xxx</i></span>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Notifikasi Rujukan', 'is_notif', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									
									<div class="form-check form-check-inline">
									  <input class="form-check-input" type="radio" name="is_notif" id="exampleRadios1" value="1" <?php echo (($item_info->is_notif)?'checked':'')?>>
									  <label class="form-check-label" for="exampleRadios1">
										Aktif
									  </label>
									</div>
									<div class="form-check form-check-inline">
									  <input class="form-check-input" type="radio" name="is_notif" id="exampleRadios2" value="0" <?php echo (($item_info->is_notif)?'':'checked')?>>
									  <label class="form-check-label" for="exampleRadios2">
										Tidak Aktif
									  </label>
									</div>
								</div>
							</div>

						</div>
				</div>
			</div>
			<div class="card-footer">
				
					<div class="text-center">
						<button class="btn btn-sm btn-primary" id="submit"> Simpan</button>
						<a class="btn btn-sm btn-light" href="<?php echo site_url('home'); ?>"> Kembali</a>
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
					
					setTimeout(function(){window.location.href = "<?php echo site_url('profile'); ?>";}, 3000);
				},
				dataType: 'json'
			});
		},

		rules:
		{
			email: "required",
			no_hp: "required",
   		},

		messages: 
		{
     		email: "Email harus diisi",
			no_hp: "Nomor HP harus diisi",
		}
	}, form_support.error));
});
</script>
<?php $this->load->view("partial/footer"); ?>