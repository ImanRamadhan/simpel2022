<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="login page" name="description" />
        <meta content="KMI" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url();?>images/logo_32.png">

        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
		
		<style>
			body, html {
			  height: 100%;
			}
			body {
			  background: url('https://source.unsplash.com/twukN12EN7c/1920x1080') no-repeat center center fixed;
			  -webkit-background-size: cover;
			  -moz-background-size: cover;
			  background-size: cover;
			  -o-background-size: cover;
			}
			
		</style>

    </head>

    <body>
		
		<br />
        <!-- Log In page -->
        <div class="row">
            
            <div class="col-lg-12 h-30 d-flex justify-content-center">
				
                <div class="card mb-0 shadow-none">
                    <div class="card-body">
    
                        <h3 class="text-center m-0">
                            <a href="#" class="logo logo-admin"><img src="<?php echo base_url();?>images/bpom.png" height="60" alt="logo" class="my-3"></a>
                        </h3>
    
                        <div class="px-3">
                            <h4 class="text-muted font-13 mb-2 text-center">SISTEM PELAPORAN LAYANAN (SIMPEL)</h4>
                            <p class="text-muted text-center"><?php echo validation_errors(); ?></p>
    
                            
							<?php echo form_open('login', 'class="form-horizontal my-4" ') ?>
    
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="far fa-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                                    </div>                                    
                                </div>
    
                                <div class="form-group">
                                    <label for="userpassword">Password</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
										<div class="input-group-append">
                                            <span class="input-group-text" id="eye"><i id="eye-icon" class="fas fa-eye"></i></span>
                                        </div>
                                    </div>                                
                                </div>
								
								<div class="form-group">
                                    <label for="userpassword">Kode Validasi</label>
                                    
									
									<div class="input-group mb-3">
										
										<img id="captcha" src="<?php echo site_url('login/captcha'); ?>" alt="CAPTCHA Image" />
										<a href="#" onclick="document.getElementById('captcha').src = '<?php echo site_url('login/captcha'); ?>?r=' + Math.random(); return false"><img src="<?php echo base_url()?>images/refresh.png" width="30px" /></a>
                                        &nbsp;
                                        <input type="text" class="form-control" id="validasi" name="validasi" placeholder="Kode Validasi" autocomplete="off">
                                    </div>                                
                                </div>
    
                                <div class="form-group row mt-4">
                                    <div class="col-sm-6">
                                       <!-- <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customControlInline">
                                            <label class="custom-control-label" for="customControlInline">Remember me</label>
                                        </div> -->
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <!--<a href="#" class="text-muted font-13"><i class="mdi mdi-lock"></i> Forgot your password?</a>  -->                                  
                                    </div>
                                </div>
    
                                <div class="form-group mb-0 row">
                                    <div class="col-12 mt-2">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Log In <i class="fas fa-sign-in-alt ml-1"></i></button>
                                    </div>
                                </div>                            
                            </form>
                        </div>
                       
                        <div class="mt-4 text-center">
                            <p class="mb-0">© 2022 </p>
                        </div>
                    </div>
                </div>
            
            </div>
        </div>
		
		
        <!-- End Log In page -->

        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/waves.min.js"></script>
        <script src="assets/js/jquery.slimscroll.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.js"></script>
		<script type="text/javascript">
			$(document).ready(function()
			{
				$('#eye').on('click', function(){
					if($('#password').attr('type') === 'password')
					{
						$('#password').attr('type','text');
						$('#eye-icon').attr('class','fa fa-eye-slash');
					}
					else
					{
						$('#password').attr('type','password');
						$('#eye-icon').attr('class','fa fa-eye');
					}
				});
			});
		</script>

    </body>
</html>