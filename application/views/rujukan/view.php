<?php $this->load->view("partial/header"); ?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/dropzone.css" />
<script src="<?php echo base_url();?>assets/plugins/dropzone/dist/min/dropzone.min.js"></script>
<script>
Dropzone.autoDiscover = false;
</script>
<style>
	.dropzone .dz-preview .dz-download {
      font-size: 14px;
      text-align: center;
      display: block;
      cursor: pointer;
      border: none; }
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Layanan</a></li>
					<li class="breadcrumb-item active">Rujukan</li>
				</ol>
			</div>
			<h4 class="page-title">Detail rujukan <?php echo $item_info->trackid; ?></h4>
		</div>
	</div>
</div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open($controller_name . '/save/' . $item_info->id, array('id'=>'city_form', 'class'=>'form-horizontal')); ?>
	
	
	<div class="row">
		<div class="col-lg-12">
			
			<div class="card">
				<div class="card-header bg-primary text-white">
					Detail rujukan
				</div>
				<div class="card-body">
					<?php $this->load->view('tickets/info_layanan'); ?>
					<br />
					<?php $this->load->view('tickets/info_identitas_pelapor'); ?>
					<br />
					<?php $this->load->view('tickets/info_identitas_produk'); ?>
					<br />	
					<?php $this->load->view('tickets/info_pengaduan'); ?>
					<br />
					<?php $this->load->view('tickets/info_klasifikasi'); ?>
					<br />
					<?php $this->load->view('tickets/info_tindaklanjut'); ?>
					<br />
					<?php $this->load->view('tickets/info_pertanyaan'); ?>
					<br />
					<?php $this->load->view('tickets/info_jawaban'); ?>
					<br />
					<?php $this->load->view('tickets/info_reply'); ?>
					<br />
					<?php $this->load->view('tickets/info_riwayat'); ?>
					<br />
					

					
				</div>
				<div class="card-footer">
					<div class="text-center">
						
						<a class="btn btn-light" href="<?php echo $url_back;?>"> Kembali</a>
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
	$('#city_form').validate($.extend({
		submitHandler: function(form)
		{
			$(form).ajaxSubmit({
				beforeSubmit: function() {
					$('#submit').attr('disabled',true);
					$('#submit').html("<i class='fa fa-spinner fa-spin'></i> Processing...");
				},
				success: function(response)
				{
					dialog_support.hide();
					table_support.handle_submit('<?php echo site_url('stds'); ?>', response);
					table_support.refresh();
					//location.reload(true);
				},
				dataType: 'json'
			});
		},

		rules:
		{
			sarana: "required",
			standard: "required",
			
   		},

		messages: 
		{
     		sarana: "Sarana harus diisi",
     		standard: "Standar harus diisi",
     		
		}
	}, form_support.error));
});
</script>
<?php $this->load->view("partial/footer"); ?>