<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Data Master</a></li>
					<li class="breadcrumb-item active">Pengaturan Komoditi</li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $page_title; ?></h4>
		</div>
	</div>
</div>



<?php echo form_open($controller_name . '/save/' . $item_info->id, array('id'=>'job_form', 'class'=>'form-horizontal')); ?>
	
	<div class="row">
		<div class="col-lg-6">
		
		<ul id="error_message_box" class="error_message_box card alert-danger"></ul>
		
		
		<div class="card">
			<div class="card-header bg-primary text-white">
				<?php echo $page_title; ?>
			</div>
			<div class="card-body">
			
				<div class="row">
						<div class="col-sm-12 col-lg-12">
			
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Nama Komoditi <span class="text-danger">*</span>', 'name', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									<?php echo form_input(array(
										'name'=>'nama',
										'id'=>'nama',
										'class'=>'form-control form-control-sm',
										'value'=>$item_info->name)
										);?>
								</div>
							</div>
                            <div class="form-group form-group-sm row">
								
								<?php echo form_label('Deskripsi Komoditi <span class="text-danger">*</span>', 'desc', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									<?php echo form_input(array(
										'name'=>'desc',
										'id'=>'desc',
										'class'=>'form-control form-control-sm',
										'value'=>$item_info->desc)
										);?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Status', 'status', array('class'=>'required col-form-label col-sm-4')); ?>
								<div class='col-sm-4'>
									<?php echo form_dropdown('status', array('0' => 'Enabled', '1' => 'Disabled'), $item_info->deleted, 'class="form-control form-control-sm" id="status" ');?>
								</div>
							</div>
					
					

						</div>
				</div>
			</div>
			<div class="card-footer">
				
					<div class="text-center">
						
						<button class="btn btn-sm btn-primary" id="submit"> Simpan</button>
						<a class="btn btn-sm btn-light" href="<?php echo site_url('categories'); ?>"> Batal</a>
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
	$('#job_form').validate($.extend({
		submitHandler: function(form)
		{
			$(form).ajaxSubmit({
				beforeSubmit: function() {
					$('#submit').attr('disabled',true);
					//$('#submit').html("<i class='fa fa-spinner fa-spin'></i> Processing...");
				},
				success: function(response)
				{
					//console.log(response);
					$.notify(response.message, { type: response.success ? 'success' : 'danger' });
					
					setTimeout(function(){window.location.href = "<?php echo site_url('categories'); ?>";}, 3000);
				},
				dataType: 'json'
			});
		},

		rules:
		{
			nama: { 
				required: true,
				<?php if(!$edit):?>
				remote: {
					url: "<?php echo site_url('categories/ajax_check_name')?>",
					type: "post",
					data: $.extend(csrf_form_base(),
					{
						//"id" : "<?php echo $item_info->id; ?>",
						
					})
				}
				<?php endif; ?>
			},
			desc: { 
				required: true
			},
   		},

		messages: 
		{
     		nama: 
			{ 
				required:"Nama Komoditi harus diisi",
				remote: "Nama Komoditi sudah digunakan"
			},
            desc: 
			{ 
				required:"Deskripsi Komoditi harus diisi"
			},
     		
		}
	}, form_support.error));
});
</script>
<?php $this->load->view("partial/footer"); ?>