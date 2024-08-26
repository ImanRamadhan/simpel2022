<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Data Master</a></li>
					<li class="breadcrumb-item active">Pengaturan User</li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $page_title; ?></h4>
		</div>
	</div>
</div>



<?php echo form_open($controller_name . '/save/' . $item_info->id, array('id'=>'user_form', 'class'=>'form-horizontal')); ?>
	
	<div class="row">
		<div class="col-sm-12 col-lg-12">
		<div class="col-lg-4 alert-info" style="margin-bottom:10px">
			<span>Password harus mengandung :</span></br>
			<span>- Minimal 8 Character</span></br>
			<span>- Minimal mengandung 1 Huruf Besar</span></br>
			<span>- Minimal mengandung 1 Huruf Kecil</span></br>
			<span>- Minimal mengandung 1 Angka</span></br>
			<span>- Minimal mengandung 1 Karakter Spesial</span></br>
		</div>
		<div id="error_message_box" class="error_message_box card alert-danger"></div>
		<div class="card">
			<div class="card-header bg-primary text-white">
				<?php echo $page_title; ?>
			</div>
			<div class="card-body">
			
				<div class="row">
					<div class="col-sm-12 col-lg-9">
						<div class="form-group form-group-sm row">
							<?php echo form_label('Username <span class="text-danger">*</span>', 'user', array('class'=>'col-form-label col-sm-3')); ?>
							
							<div class='col-sm-6'>
								<?php 
								$data = array(
									'name'=>'user',
									'id'=>'user',
									'class'=>'form-control form-control-sm',
									'value'=>$item_info->user);
								if(!empty($item_info->id) && $this->session->id > 1)
									$data['readonly'] = 'readonly';
									
								echo form_input($data);?>
							</div>
						 </div>
						<div class="form-group form-group-sm row">
							<?php echo form_label('Nama Lengkap <span class="text-danger">*</span>', 'name', array('class'=>'col-form-label col-sm-3')); ?>
							
							<div class='col-sm-6'>
								<?php echo form_input(array(
									'name'=>'name',
									'id'=>'name',
									'class'=>'form-control form-control-sm',
									'value'=>$item_info->name)
									);?>
							</div>
						</div>
						
						<div class="form-group form-group-sm row">
							<?php echo form_label('Balai/Loka <span class="text-danger">*</span>', 'tipe_balai', array('class'=>'col-form-label col-sm-3')); ?>
							
							<div class='col-sm-6'>
								<?php echo form_dropdown('city', $cities, $item_info->city, 'class="form-control form-control-sm" id="city"');?>
							</div>
						</div>
						
						<div class="form-group form-group-sm row">
							
							<?php echo form_label('Unit Kerja <span class="text-danger">*</span>', 'kode_prefix', array('class'=>'col-form-label col-sm-3')); ?>
							
							<div class='col-sm-9'>
								<?php echo form_dropdown('direktoratid', $depts, $item_info->direktoratid, 'class="form-control form-control-sm" id="direktoratid"');?>
								
							</div>
						</div>
						<div class="form-group form-group-sm row">
							<?php echo form_label('Email <span class="text-danger">*</span>', 'name', array('class'=>'col-form-label col-sm-3')); ?>
							
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
							<?php echo form_label('Nomor HP <span class="text-danger">*</span>', 'no_hp', array('class'=>'col-form-label col-sm-3')); ?>
							
							<div class='col-sm-6'>
								<?php echo form_input(array(
									'name'=>'no_hp',
									'id'=>'no_hp',
									'onkeypress'=>'return isNumberKey(event)',
									'class'=>'form-control form-control-sm',
									'value'=>$item_info->no_hp)
									);?>
							</div>
						 </div>
						 <div class="form-group form-group-sm row">
							<?php echo form_label('Password', 'pass', array('class'=>'col-form-label col-sm-3')); ?>
							
							<div class='col-sm-6'>
								<?php echo form_input(array(
									'name'=>'pass',
									'id'=>'pass',
									'type' => 'password',
									'class'=>'form-control form-control-sm',
									'autocomplete' => 'false',
									'value'=>'')
									);?>
							</div>
						 </div>
						 <div class="form-group form-group-sm row">
							<?php echo form_label('Konfirmasi Password', 'pass2', array('class'=>'col-form-label col-sm-3')); ?>
							
							<div class='col-sm-6'>
								<?php echo form_input(array(
									'name'=>'pass2',
									'id'=>'pass2',
									'type' => 'password',
									'class'=>'form-control form-control-sm',
									'autocomplete' => 'false',
									'value'=>'')
									);?>
							</div>
						 </div>
						 
						<div class="form-group form-group-sm row">
							
							<?php echo form_label('Status User <span class="text-danger">*</span>', 'tipe_user', array('class'=>'col-form-label col-sm-3')); ?>
							
							<div class='col-sm-6'>
								<?php echo form_dropdown('is_active', array('1' => 'Aktif', '0' => 'Tidak Aktif'), $item_info->is_active, 'class="form-control" id="is_active"');?>
								
							</div>
						</div>
						<div class="form-group form-group-sm row">
							
							<?php echo form_label('Tipe User <span class="text-danger">*</span>', 'tipe_user', array('class'=>'col-form-label col-sm-3')); ?>
							
							<div class='col-sm-6'>
								<?php echo form_dropdown('isadmin', $roles, $item_info->isadmin, 'class="form-control form-control-sm" id="isadmin"');?>
								
							</div>
						</div>
						<div class="form-group form-group-sm row">
								
								<?php echo form_label('Notifikasi Rujukan', 'is_notif', array('class'=>'col-form-label col-sm-3')); ?>
								
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
						<div class="form-group form-group-sm row">
							
							<?php echo form_label('Fitur', 'tipe_user', array('class'=>'col-form-label col-sm-3')); ?>
							
							<div class='col-sm-8'>
								<?php 
								$i = 0;
								foreach($features as $f):
								$i++;
								?>
								<div class="checkbox my-2">
									<div class="custom-control custom-checkbox">
										<?php
										$checked = '';
										if(in_array($f, $privileges))
											$checked = 'checked="checked"';
										?>
									
										<input type="checkbox" class="custom-control-input form-control-sm" name="features[]" value="<?php echo $f;?>" id="<?php echo $f; ?>" <?php echo $checked; ?>>
										<label class="custom-control-label" for="<?php echo $f; ?>"><?php echo $this->lang->line('users_'.$f); ?></label>
									</div>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
						
						

					</div>
				</div>
			</div>
			<div class="card-footer">
				
					<div class="text-center">
						
						<button class="btn btn-sm btn-primary" id="submit"> Simpan</button>
						<a class="btn btn-sm btn-light" href="<?php echo site_url('users'); ?>"> Batal</a>
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
	$('#city').select2({theme: "bootstrap4"});
	$('#direktoratid').select2({theme: "bootstrap4"});
	
	$('#user_form').validate($.extend({
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
					if (response.success) {
						setTimeout(function(){window.location.href = "<?php echo site_url('users'); ?>";}, 2000);
					}
					$('#submit').attr('disabled',false);
				},
				dataType: 'json'
			});
		},

		rules:
		{
			user: {
				required: true,
				remote: {
					url: "<?php echo site_url('users/ajax_check_user')?>",
					type: "post",
					data: $.extend(csrf_form_base(),
					{
						"id" : "<?php echo $item_info->id; ?>",
						
					})
				}
			},
			name: "required",
			email: "required",
			no_hp: "required",
   		},

		messages: 
		{
     		user: {
				required: "Username harus diisi",
				remote: "Username sudah digunakan"
			},
     		name: "Nama Lengkap harus diisi",
     		email: "Email harus diisi",
			no_hp: "Nomor HP harus diisi",
		}
	}, form_support.error));
	
	$('#city').on('change', function(){
		
		$.getJSON("<?php echo site_url('users/get_dir/'); ?>" + $(this).val(), function(data) {
			if(data)
			{
				$('#direktoratid').empty();
				$.each(data, function (key, entry) {
					$('#direktoratid').append($('<option></option>').attr('value', entry.id).text(entry.name));
				  })
			}
		});
	});
	
	$('#isadmin').on('change', function(){
		var type = $(this).val();
		if(type == '')
		{
			$("#can_create_tickets").prop('checked',false);
			$("#can_view_tickets").prop('checked',false);
			$("#can_reply_tickets").prop('checked',false);
			$("#can_del_tickets").prop('checked',false);
			$("#can_edit_tickets").prop('checked',false);
			$("#can_change_priority").prop('checked',false);
			$("#can_change_status").prop('checked',false);
			$("#can_assign_others").prop('checked',false);
			$("#can_del_notes").prop('checked',false);
			$("#can_change_cat").prop('checked',false);
			$("#can_man_users").prop('checked',false);
			$("#can_man_dir").prop('checked',false);
			$("#can_run_reports").prop('checked',false);
			$("#can_list_tickets_city").prop('checked',false);
		}
		else if(type == '0')
		{
			$("#can_create_tickets").prop('checked',true);
			$("#can_view_tickets").prop('checked',true);
			$("#can_reply_tickets").prop('checked',true);
			$("#can_del_tickets").prop('checked',true);
			$("#can_edit_tickets").prop('checked',true);
			$("#can_change_priority").prop('checked',true);
			$("#can_change_status").prop('checked',true);
			$("#can_assign_others").prop('checked',true);
			$("#can_del_notes").prop('checked',false);
			$("#can_change_cat").prop('checked',true);
			$("#can_man_users").prop('checked',false);
			$("#can_man_dir").prop('checked',false);
			$("#can_run_reports").prop('checked',true);
			$("#can_list_tickets_city").prop('checked',true);
		}
		else if(type == 1) /* Adm */
		{
			$("#can_create_tickets").prop('checked',true);
			$("#can_view_tickets").prop('checked',true);
			$("#can_reply_tickets").prop('checked',true);
			$("#can_del_tickets").prop('checked',true);
			$("#can_edit_tickets").prop('checked',true);
			$("#can_change_priority").prop('checked',true);
			$("#can_change_status").prop('checked',true);
			$("#can_assign_others").prop('checked',true);
			$("#can_del_notes").prop('checked',true);
			$("#can_change_cat").prop('checked',true);
			$("#can_man_users").prop('checked',true);
			$("#can_man_dir").prop('checked',true);
			$("#can_run_reports").prop('checked',true);
			$("#can_list_tickets_city").prop('checked',true);
		}
		else if(type == 2) /* Verifikator */
		{
			$("#can_create_tickets").prop('checked',true);
			$("#can_view_tickets").prop('checked',true);
			$("#can_reply_tickets").prop('checked',true);
			$("#can_del_tickets").prop('checked',false);
			$("#can_edit_tickets").prop('checked',true);
			$("#can_change_priority").prop('checked',true);
			$("#can_change_status").prop('checked',true);
			$("#can_assign_others").prop('checked',false);
			$("#can_del_notes").prop('checked',false);
			$("#can_change_cat").prop('checked',false);
			$("#can_man_users").prop('checked',false);
			$("#can_man_dir").prop('checked',false);
			$("#can_run_reports").prop('checked',true);
			$("#can_list_tickets_city").prop('checked',true);
		}
		else if(type == 3) /* Petugas */
		{
			$("#can_create_tickets").prop('checked',true);
			$("#can_view_tickets").prop('checked',true);
			$("#can_reply_tickets").prop('checked',true);
			$("#can_del_tickets").prop('checked',false);
			$("#can_edit_tickets").prop('checked',false);
			$("#can_change_priority").prop('checked',false);
			$("#can_change_status").prop('checked',true);
			$("#can_assign_others").prop('checked',false);
			$("#can_del_notes").prop('checked',false);
			$("#can_change_cat").prop('checked',false);
			$("#can_man_users").prop('checked',false);
			$("#can_man_dir").prop('checked',false);
			$("#can_run_reports").prop('checked',true);
			$("#can_list_tickets_city").prop('checked',true);
		}
		else if(type == 4) /* Tim Koordinasi */
		{
			$("#can_create_tickets").prop('checked',false);
			$("#can_view_tickets").prop('checked',true);
			$("#can_reply_tickets").prop('checked',true);
			$("#can_del_tickets").prop('checked',false);
			$("#can_del_tickets").prop('checked',false);
			$("#can_edit_tickets").prop('checked',false);
			$("#can_change_priority").prop('checked',false);
			$("#can_change_status").prop('checked',false);
			$("#can_assign_others").prop('checked',false);
			$("#can_del_notes").prop('checked',false);
			$("#can_change_cat").prop('checked',false);
			$("#can_man_users").prop('checked',false);
			$("#can_man_dir").prop('checked',false);
			$("#can_run_reports").prop('checked',false);
			$("#can_list_tickets_city").prop('checked',false);
		}
		else if(type == 5) /* Tim Koordinasi Yanblik */
		{
			$("#can_create_tickets").prop('checked',true);
			$("#can_view_tickets").prop('checked',true);
			$("#can_reply_tickets").prop('checked',true);
			$("#can_del_tickets").prop('checked',false);
			$("#can_del_tickets").prop('checked',false);
			$("#can_edit_tickets").prop('checked',false);
			$("#can_change_priority").prop('checked',false);
			$("#can_change_status").prop('checked',false);
			$("#can_assign_others").prop('checked',false);
			$("#can_del_notes").prop('checked',false);
			$("#can_change_cat").prop('checked',false);
			$("#can_man_users").prop('checked',false);
			$("#can_man_dir").prop('checked',false);
			$("#can_run_reports").prop('checked',false);
			$("#can_list_tickets_city").prop('checked',false);
		}
	});
	
});

function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode != 46 && charCode > 31 &&
		(charCode < 48 || charCode > 57))
		return false;
	return true;
}
</script>
<?php $this->load->view("partial/footer"); ?>