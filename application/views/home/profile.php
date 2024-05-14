<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
	
</script>

<section class="content-header">
  <h1>
	Profil Saya
	<small></small>
  </h1>
  <ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	<li class="active">Profil Saya</li>
  </ol>
</section>
<section class="content">
<div class="row">
	<div class="col-md-4">
		<div class="box box-primary">
			<div class="box-body box-profile">
				<img class="profile-user-img img-responsive img-circle" src="<?php echo !empty($this->session->profile_picture) ? base_url().'uploads/users/'.$this->session->profile_picture:'images/avatar.png'; ?>" alt="User profile picture">
				<h3 class="profile-username text-center"><?php echo $user->name;?></h3>

				<p class="text-muted text-center"><!--<?php echo $user->role_name;?>--></p>
				<ul class="list-group list-group-unbordered">
					<li class="list-group-item">
					  <b>Email</b> <a class="pull-right"><?php echo $this->session->email; ?></a>
					</li>
					<li class="list-group-item">
					  <b>Kota</b> <a class="pull-right"><?php echo $this->session->city; ?></a>
					</li>
					<li class="list-group-item">
					  <b>Tipe User</b> <a class="pull-right"><?php echo $this->session->role_name; ?></a>
					</li>
					
				</ul>
				<a data-href="<?php echo site_url('home/change_picture');?>" data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' class="btn btn-primary btn-block modal-dlg"><b>Ubah Foto</b></a>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
              <li class="active"><a href="#changepassword" data-toggle="tab">Ubah Password</a></li>
              <!--<li><a href="#timeline" data-toggle="tab">Timeline</a></li>
              <li><a href="#settings" data-toggle="tab">Settings</a></li>-->
            </ul>
			<div class="tab-content">
				<div class="active tab-pane" id="changepassword">
					<?php echo form_open('profile/change_password' , array('id'=>'change_password_form', 'class'=>'form-horizontal')); ?>
					  <div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Password Lama</label>

						<div class="col-sm-9">
						  <input type="password" class="form-control" id="password" name="password" placeholder="Password Lama">
						</div>
					  </div>
					  <div class="form-group">
						<label for="inputEmail" class="col-sm-3 control-label">Password Baru</label>

						<div class="col-sm-9">
						  <input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="Password Baru">
						</div>
					  </div>
					  <div class="form-group">
						<label for="inputName" class="col-sm-3 control-label">Konfirmasi Password</label>

						<div class="col-sm-9">
						  <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Konfirmasi Password">
						</div>
					  </div>
					  
					  <div class="form-group">
						<div class="col-sm-offset-3 col-sm-9">
						  <button type="submit" class="btn btn-primary">Submit</button>
						</div>
					  </div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	
</div>
</section>

<script type="text/javascript">
$(document).ready(function()
{
	dialog_support.init("a.modal-dlg");
	
	$('#change_password_form').validate($.extend({
		submitHandler:function(form)
		{
			
			$(form).ajaxSubmit({
			success:function(response)
			{
				if(response.success)
					$.notify(response.message, { type: 'success' });
				else
					$.notify(response.message, { type: 'error' });
				
			},
			dataType:'json'
		});

		},
		rules:
		{
			password: "required",
			newpassword: "required",
			confirmpassword: "required"
			
   		},
		messages: 
		{
			
			password: "Password lama harus diisi.",
			newpassword: "Password baru harus diisi.",
			confirmpassword: "Konfirmasi password harus diisi."
			
		},
		
	}, form_support.error));
});
</script>
<?php $this->load->view("partial/footer"); ?>
