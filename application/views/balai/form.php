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



<?php echo form_open($controller_name . '/save/' . $item_info->id, array('id'=>'balai_form', 'class'=>'form-horizontal')); ?>
	
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
								
								<?php echo form_label('Nama Balai/Loka <span class="text-danger">*</span>', 'nama_balai', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									<?php $data = array(
										'name'=>'nama_balai',
										'id'=>'nama_balai',
										'class'=>'form-control form-control-sm',
										'value'=>$item_info->nama_balai);
										
									if($mode == 'edit')
										$data['readonly'] = 'readonly';
									echo form_input($data);	
									?>
								</div>
							</div>
							
							<div class="form-group form-group-sm row">
								<?php echo form_label('Tipe Balai <span class="text-danger">*</span>', 'tipe_balai', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-4'>
									
									<?php echo form_dropdown('tipe_balai', array('1' => 'Balai Besar', '2' => 'Balai Tipe A', '3' => 'Balai Tipe B', '4' => 'Loka'), $item_info->tipe_balai, 'class="form-control form-control-sm" id="tipe_balai"');?>
								</div>
							</div>
							
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Kode Prefix <span class="text-danger">*</span>', 'kode_prefix', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-3'>
									<?php 
									$data = array(
										'name'=>'kode_prefix',
										'id'=>'kode_prefix',
										'maxlength' => 3,
										'class'=>'form-control form-control-sm',
										'value'=>$item_info->kode_prefix);
									
									if($mode == 'edit')
										$data['readonly'] = 'readonly';
									echo form_input($data);
										
									?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Alamat', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-4')); ?>
								<div class="col-sm-8">
									<?php echo form_textarea(array(
									'class'=>'form-control form-control-sm', 
									'name'=>'alamat', 
									'id'=>'alamat', 
									'rows'=>4,
									'value'=>$item_info->alamat
									));?>
								</div>
							</div>
							
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('No. Telp', 'no_telp', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									<?php echo form_input(array(
										'name'=>'no_telp',
										'id'=>'no_telp',
										'class'=>'form-control form-control-sm',
										'value'=>$item_info->no_telp)
										);?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('No. Fax', 'no_telp', array('class'=>'col-form-label col-sm-4')); ?>
								
								<div class='col-sm-6'>
									<?php echo form_input(array(
										'name'=>'no_fax',
										'id'=>'no_fax',
										'class'=>'form-control form-control-sm',
										'value'=>$item_info->no_fax)
										);?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Email', 'no_telp', array('class'=>'col-form-label col-sm-4')); ?>
								
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
								<?php echo form_label('Kop Surat PPID', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-4')); ?>
								<div class="col-sm-8">
									<?php echo form_textarea(array(
									'class'=>'form-control form-control-sm', 
									'name'=>'kop', 
									'id'=>'kop', 
									'rows'=>2,
									'value'=>$item_info->kop
									));?>
								</div>
							</div>

						</div>
				</div>
			</div>
			<div class="card-footer">
				
					<div class="text-center">
						
						<button class="btn btn-sm btn-primary" id="submit"> Simpan</button>
						<a class="btn btn-sm btn-light" href="<?php echo site_url('balais'); ?>"> Batal</a>
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
	$('#balai_form').validate($.extend({
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
					
					setTimeout(function(){window.location.href = "<?php echo site_url('balais'); ?>";}, 3000);
				},
				dataType: 'json'
			});
		},

		rules:
		{
			nama_balai: { 
				required: true,
				<?php if($mode == 'add'):?>
				remote: {
					url: "<?php echo site_url('balais/ajax_check_balai')?>",
					type: "post",
					data: $.extend(csrf_form_base(),
					{
						"id" : "<?php echo $item_info->id; ?>",
						
					})
				}
				<?php endif; ?>
			},
			tipe_balai: "required",
			kode_prefix: { 
				required: true,
				<?php if($mode == 'add'):?>
				remote: {
					url: "<?php echo site_url('balais/ajax_check_prefix')?>",
					type: "post",
					data: $.extend(csrf_form_base(),
					{
						"id" : "<?php echo $item_info->id; ?>",
						
					})
				}
				<?php endif; ?>
			}
   		},

		messages: 
		{
     		nama_balai: 
			{ 
				required:"Nama Balai/Loka harus diisi",
				remote: "Nama Balai/Loka sudah digunakan"
			},
     		tipe_balai: "Tipe Balai harus diisi",
			kode_prefix: 
			{ 
				required:"Kode Prefix harus diisi",
				remote: "Kode Prefix sudah digunakan"
			}
		}
	}, form_support.error));
});
</script>
<?php $this->load->view("partial/footer"); ?>