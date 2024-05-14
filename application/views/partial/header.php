<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Simpel <?php echo isset($title) ? ' - ' . $title : ''; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Aplikasi Simpellpk" name="description" />
        <meta content="KMI" name="author" />

        <link rel="shortcut icon" href="<?php echo base_url()?>images/logo_32.png">

		<link href="<?php echo base_url()?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/bootstrap-select/css/bootstrap-select.min.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/bootstrap-table/bootstrap-table.min.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/bootstrap-dialog/css/bootstrap-dialog.min.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.min.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2-bootstrap4.min.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		
		<link href="<?php echo base_url()?>css/bootstrap.autocomplete.css" rel="stylesheet" type="text/css" />
		
		<link href="<?php echo base_url()?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url()?>assets/css/style.css?v=1.0" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url()?>assets/css/custom.css?v=1.0" rel="stylesheet" type="text/css" />
		
        <script src="<?php echo base_url()?>assets/js/jquery.min.js"></script>
		
		<link href="<?php echo base_url()?>bower_components/jquery-ui/themes/base/jquery-ui.min.css" rel="stylesheet" type="text/css" />
		<script src="<?php echo base_url()?>bower_components/jquery-ui/jquery-ui.min.js"></script>
		<script src="<?php echo base_url()?>js/jquery-migrate-3.0.0.min.js"></script>
		
		
        <script src="<?php echo base_url()?>assets/js/bootstrap.bundle.min.js"></script>
		<script src="<?php echo base_url()?>assets/plugins/bootstrap-table/bootstrap-table.min.js"></script>
		<script src="<?php echo base_url()?>assets/plugins/bootstrap-table/extensions/cookie/bootstrap-table-cookie.min.js"></script>
		<script src="<?php echo base_url()?>assets/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
		<script src="<?php echo base_url()?>assets/plugins/select2/select2.min.js"></script>
		<script src="<?php echo base_url()?>assets/plugins/bootstrap-dialog/js/bootstrap-dialog.min.js"></script>
		<script src="<?php echo base_url()?>bower_components/remarkable-bootstrap-notify/bootstrap-notify.min.js"></script>
		
		<script src="<?php echo base_url()?>bower_components/moment/min/moment.min.js"></script>
		<script src="<?php echo base_url()?>bower_components/jquery-form/dist/jquery.form.min.js"></script>
		<script src="<?php echo base_url()?>bower_components/jquery-validate/dist/jquery.validate.min.js"></script>
        <script src="<?php echo base_url()?>assets/js/waves.min.js"></script>
        <script src="<?php echo base_url()?>assets/js/jquery.slimscroll.min.js"></script>
		<script src="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
		
		
		 
		<script src="<?php echo base_url()?>assets/js/jquery.remember.js"></script>
		<script src="<?php echo base_url()?>bower_components/js-cookie/src/js.cookie.js"></script>
		<script src="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.id.js"></script>

		
		<script type="text/javascript" src="<?php echo base_url()?>js/manage_tables.js?v=1.0"></script>

      
		
		<?php $this->load->view('partial/header_js'); ?>
		<?php $this->load->view('partial/lang_lines'); ?>
		
		 

    </head>

    <body>

        <!-- Top Bar Start -->
        <div class="topbar">
            <div class="topbar-main">
                <div class="container-fluid">
                    <!-- LOGO -->
                    <div class="topbar-left">
                        <a href="<?php echo site_url('home');?>" class="logo">
                            <span>
                                <!--<img src="<?php echo base_url()?>assets/images/bpom.png" alt="logo-small" class="logo-sm">-->
                            </span>
                            <span>
                                <img src="<?php echo base_url()?>assets/images/logo.png" alt="logo-large" class="logo-lg">
                            </span>
                        </a>
                    </div>
    
                    <!-- Navbar -->
                    <nav class="navbar-custom">
    
                       
                        <ul class="list-unstyled topbar-nav float-right mb-0">   
                                       
    
							
							
							<!--<li class="dropdown">
                                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                                    aria-haspopup="false" aria-expanded="false">
                                    <i class="mdi mdi-help-circle-outline nav-icon"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#"><i class="dripicons-help text-muted mr-2"></i> User Manual</a>
                                    
                                </div>
                            </li>-->
							
                            <li class="dropdown">
                                <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button"
                                    aria-haspopup="false" aria-expanded="false">
                                    <i class="mdi mdi-bell-outline nav-icon"></i>
									<?php 
									$unread = get_count_unread_notifikasi(TRUE);
									if($unread > 0):
									?>
                                    <span class="badge badge-danger badge-pill noti-icon-badge"><?php echo $unread;?></span>
									<?php endif;?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-lg">
                                    <!-- item-->
                                    <h6 class="dropdown-item-text">
                                        Notifikasi (<?php echo get_count_unread_notifikasi();?>)
                                    </h6>
                                    <div class="slimscroll notification-list">
                                        <?php 
										$notif = get_last_notifikasi();
										foreach($notif->result() as $item)
										{
											$message = trim(strip_tags($item->message));
										?>
                                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                                            <div class="notify-icon bg-success"><i class="mdi mdi-message"></i></div>
                                            <p class="notify-details"><?php echo $item->title;?><small class="text-muted"><?php echo (strlen($message) > 60)? substr($message,0,60).'...':$message; ?></small></p>
                                        </a>
										
                                        <?php } ?>
                                    </div>
                                    <!-- All-->
                                    <a href="<?php echo site_url('notifications');?>" class="dropdown-item text-center text-primary">
                                        Lihat semua <i class="fi-arrow-right"></i>
                                    </a>
                                </div>
                            </li>
							
                           
    
                            <li class="dropdown">
                                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                                    aria-haspopup="false" aria-expanded="false">
                                    <img src="<?php echo base_url()?>images/avatar.png" alt="profile-user" class="rounded-circle" /> 
                                    <span class="ml-1 nav-user-name hidden-sm"> <?php echo $user_info->user; ?> <i class="mdi mdi-chevron-down"></i> </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="<?php echo site_url('profile');?>"><i class="dripicons-user text-muted mr-2"></i> Profil Saya</a>
									<a class="dropdown-item" href="<?php echo site_url('profile/change_pwd');?>"><i class="dripicons-gear text-muted mr-2"></i> Ubah Password</a>
                                   
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?php echo site_url('home/logout');?>"><i class="dripicons-exit text-muted mr-2"></i> Logout</a>
                                </div>
                            </li>
                            <li class="menu-item">
                                <!-- Mobile menu toggle-->
                                <a class="navbar-toggle nav-link" id="mobileToggle">
                                    <div class="lines">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                                <!-- End mobile menu toggle-->
                            </li>    
                        </ul>
            
                        <ul class="list-unstyled topbar-nav mb-0">
                            
                            
                        </ul>
    
                    </nav>
                    <!-- end navbar-->
                </div>
            </div>
            

            <!-- MENU Start -->
            <div class="navbar-custom-menu">
                <div class="container-fluid">
                    <div id="navigation">
                        <!-- Navigation Menu-->
							<?php 
							if($this->session->city == 'PUSAT')
								$this->load->view('partial/menu'); 
							else if($this->session->city == 'UNIT TEKNIS')
								$this->load->view('partial/menu_unit_teknis');
							else
								$this->load->view('partial/menu_balai');
							?>
                        <!-- End navigation menu -->
                    </div> <!-- end navigation -->
                </div> <!-- end container-fluid -->
            </div> <!-- end navbar-custom -->
        </div>
        <!-- Top Bar End -->
		

       
        <!-- Page Content-->
        <div class="wrapper">
            <div class="container-fluid">
				<marquee class="m-1" scrollamount="3"><?php echo get_marquee();?></marquee>
				<?php
//print("<pre>");
//print_r($_SESSION);
//print("</pre>");
?>
				