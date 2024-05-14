<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Data Master</a></li>
					<li class="breadcrumb-item active">Pengaturan SLA</li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $page_title; ?></h4>
		</div>
	</div>
</div>



<?php echo form_open($controller_name . '/save/' . $item_info->id, array('id'=>'sla_form', 'class'=>'form-horizontal')); ?>
	
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
								<?php echo form_label('Layanan <span class="text-danger">*</span>', 'layanan', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-4'>
									<?php 
									$flag = '';
									//if($edit)
									//	$flag = 'disabled="disabled"';
									
									echo form_dropdown('info', array('P' => 'P - Pengaduan', 'I' => 'I - Informasi'), $item_info->info, 'class="form-control form-control-sm" id="info" '.$flag);
									
									?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Komoditi <span class="text-danger">*</span>', 'komoditi', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-4'>
									<?php echo form_dropdown('komoditi_id', $commodities, $item_info->komoditi_id, 'class="form-control form-control-sm" id="komoditi_id" ');?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Klasifikasi <span class="text-danger">*</span>', 'klasifikasi', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-6'>
									<?php 
									$flag = '';
									//if($edit)
									//	$flag = 'disabled="disabled"';
									
									echo form_dropdown('klasifikasi_id', $klasifikasis, $item_info->klasifikasi_id, 'class="form-control form-control-sm" id="klasifikasi_id" '.$flag);
									
									?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('Subklasifikasi <span class="text-danger">*</span>', 'name', array('class'=>'col-form-label col-sm-3')); ?>
								
								<div class='col-sm-6'>
									<?php 
									
									//if($edit)
										echo form_dropdown('subklasifikasi_id', $subklasifikasis, $item_info->subklasifikasi_id, 'class="form-control form-control-sm" id="subklasifikasi_id" ');
									//else
									//	echo form_dropdown('subklasifikasi_id', null, $item_info->subklasifikasi_id, 'class="form-control form-control-sm" id="subklasifikasi_id" ');
									?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								
								<?php echo form_label('SLA (HK)<span class="text-danger">*</span>', 'name', array('class'=>'col-form-label col-sm-3')); ?>
								
								<div class='col-sm-2'>
									
									<?php echo form_input(array(
										'name'=>'sla',
										'id'=>'sla',
										'class'=>'form-control form-control-sm',
										'value'=>$item_info->sla)
										);?>
								</div>
							</div>
					
					

						</div>
				</div>
			</div>
			<div class="card-footer">
				
					<div class="text-center">
						
						<button class="btn btn-sm btn-primary" id="submit"> Simpan</button>
						<a class="btn btn-sm btn-light" href="<?php echo site_url('slas'); ?>"> Batal</a>
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
	/*$('#klasifikasi_id').on('change', function(){
		
		$.getJSON("<?php echo site_url('slas/get_subkla/'); ?>" + $(this).val(), function(data) {
			if(data)
			{
				$('#subklasifikasi_id').empty();
				$.each(data, function (key, entry) {
					$('#subklasifikasi_id').append($('<option></option>').attr('value', entry.id).text(entry.subklasifikasi));
				  })
			}
		});
	});*/
	
	$('#sla_form').validate($.extend({
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
					
					setTimeout(function(){window.location.href = "<?php echo site_url('slas'); ?>";}, 3000);
				},
				dataType: 'json'
			});
		},

		rules:
		{
			subklasifikasi: { 
				required: true,
				/*remote: {
					url: "<?php echo site_url('jobs/ajax_check_job')?>",
					type: "post",
					data: $.extend(csrf_form_base(),
					{
						"id" : "<?php echo $item_info->id; ?>",
						
					})
				}*/
			},
			klasifikasi: { 
				required: true
			}
   		},

		messages: 
		{
     		subklasifikasi: 
			{ 
				required:"Nama Subklasifikasi harus diisi",
				//remote: "Nama Profesi sudah digunakan"
			},
     		klasifikasi: 
			{ 
				required:"Nama Klasifikasi harus diisi",
				//remote: "Nama Profesi sudah digunakan"
			}
		}
	}, form_support.error));
});
</script>
<?php $this->load->view("partial/footer"); ?>