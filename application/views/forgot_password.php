<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<base href="<?php echo base_url();?>" />
	<title><?php echo $this->config->item('company') . ' |  Reset Password'; ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
	<link rel="stylesheet" type="text/css" href="<?php echo 'dist/bootswatch/' . (empty($this->config->item('theme')) ? 'flatly' : $this->config->item('theme')) . '/bootstrap.min.css' ?>"/>
	<!-- start css template tags -->

	<link rel="stylesheet" type="text/css" href="css/login.css"/>
	<script src="bower_components/jquery/dist/jquery.js"></script>
	<!-- end css template tags -->
	<style>
	body { 
	  background: url(<?php echo site_url().'images/background.jpg'; ?>) no-repeat center center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}
	</style>
</head>

<body>
	<div id="logo" align="center"></div>
	<div id="title" align="center">
	<img src="images/bpom.png" width="100px" height="100px" />&nbsp; &nbsp; <img src="images/halobpom.png" width="100px" height="100px" />
	</div>
	<div id="login" style="text-align:center"> <h4>Reset Password</h4>
		<?php echo form_open('login/forgot_password') ?>
			<div id="container">
				<div align="center" style="color:red"><?php echo validation_errors(); ?></div>
				<div align="center" style="color:green"><?php echo $message; ?></div>
				<p><i>Untuk reset password, silakan masukkan email anda.</i></p>
				<div id="login_form">
					<div class="input-group">
						<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-envelope"></span></span>
						<input class="form-control" placeholder="Alamat Email" name="email" type="email" size=20 ></input>
					</div>

					<input class="btn btn-primary btn-block" type="submit" name="loginButton" value="Kirim"/>
					<div class="form-group row">
							<div class="col-xs-12">
							<br />
							
								<a href="<?php echo site_url("login")?>">Kembali</a>
							</div>
					</div>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
	
	<script type="text/javascript">
	$(document).ready(function()
	{
		
		$('#toggle_password').click(function(){
			$('#toggle_password').toggleClass("glyphicon-eye-open glyphicon-eye-close");
			var $pwd = $("#password");
			if ($pwd.attr('type') === 'password') {
				$pwd.attr('type', 'text');
			} else {
				$pwd.attr('type', 'password');
			}
		});
	});
	</script>
</body>
</html>
