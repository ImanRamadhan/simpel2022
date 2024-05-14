<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					
					<li class="breadcrumb-item active">Notifikasi</li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $page_title; ?></h4>
		</div>
	</div>
</div>



<?php echo form_open($controller_name . '/save/' . $item_info->id, array('id'=>'notifikasi_form', 'class'=>'form-horizontal')); ?>
	
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
								
								<?php echo form_label('Tanggal', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									<p class="form-control-plaintext"><?php echo $item_info->created_date; ?></p>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('ID Layanan', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									<p class="form-control-plaintext"><?php echo $ticket_id; ?></p>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Judul Notifikasi', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									<p class="form-control-plaintext"><?php echo $item_info->title; ?></p>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Isi Notifikasi', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									<p class="form-control-plaintext"><?php echo nl2br($item_info->message); ?></p>
								</div>
							</div>

						</div>
				</div>
			</div>
			<div class="card-footer">
				
					<div class="text-center">
						<a class="btn btn-sm btn-light" href="<?php echo site_url('notifications'); ?>"> Kembali</a>
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
	
});
</script>
<?php $this->load->view("partial/footer"); ?>