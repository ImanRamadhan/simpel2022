<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open('home/save', array('id'=>'employee_form', 'class'=>'form-horizontal')); ?>
	<div class="tab-content">
		<div class="tab-pane fade in active" id="employee_login_info">
			<fieldset>
				<div class="form-group form-group-sm">	
					<?php echo form_label($this->lang->line('employees_username'), 'user_id', array('class'=>'required control-label col-xs-3')); ?>
					<div class='col-xs-8'>
						<div class="input-group">
							<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-user"></span></span>
							<?php echo form_input(array(
									'name'=>'user_id',
									'id'=>'user_id',
									'class'=>'form-control input-sm',
									'value'=>$person_info->user_id,
									'readonly'=>'true')
									);?>
						</div>
					</div>
				</div>

				<?php $password_label_attributes = $person_info->user_id == "" ? array('class'=>'required') : array(); ?>

				<div class="form-group form-group-sm">	
					<?php echo form_label($this->lang->line('employees_current_password'), 'current_password', array_merge($password_label_attributes, array('class'=>'control-label col-xs-3'))); ?>
					<div class='col-xs-8'>
						<div class="input-group">
							<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-lock"></span></span>
							<?php echo form_password(array(
									'name'=>'current_password',
									'id'=>'current_password',
									'class'=>'form-control input-sm')
									);?>
						</div>
					</div>
				</div>

				<div class="form-group form-group-sm">	
					<?php echo form_label($this->lang->line('employees_password'), 'password', array_merge($password_label_attributes, array('class'=>'control-label col-xs-3'))); ?>
					<div class='col-xs-8'>
						<div class="input-group">
							<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-lock"></span></span>
							<?php echo form_password(array(
									'name'=>'password',
									'id'=>'password',
									'class'=>'form-control input-sm')
									);?>
						</div>
					</div>
				</div>

				<div class="form-group form-group-sm">	
				<?php echo form_label($this->lang->line('employees_repeat_password'), 'repeat_password', array_merge($password_label_attributes, array('class'=>'control-label col-xs-3'))); ?>
					<div class='col-xs-8'>
						<div class="input-group">
							<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-lock"></span></span>
							<?php echo form_password(array(
									'name'=>'repeat_password',
									'id'=>'repeat_password',
									'class'=>'form-control input-sm')
									);?>
						</div>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
<?php echo form_close(); ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	$.validator.setDefaults({ ignore: [] });

	$.validator.addMethod("notEqualTo", function(value, element, param) {
		return this.optional(element) || value != $(param).val();
	}, '<?php echo $this->lang->line('employees_password_not_must_match'); ?>');
	
	$('#employee_form').validate($.extend({
		submitHandler:function(form) 
		{
			$(form).ajaxSubmit({
				success:function(response)
				{
					dialog_support.hide();
					$.notify(response.message, { type: response.success ? 'success' : 'danger'} );
				},
				dataType:'json'
			});
		},
		rules:
		{
			current_password:
			{
				required:true,
				minlength: 8
			},
			password:
			{
				required:true,
				minlength: 8,
				notEqualTo: "#current_password"
			},	
			repeat_password:
			{
 				equalTo: "#password"
			}
   		},
		messages: 
		{
			password:
			{
				required:"<?php echo $this->lang->line('employees_password_required'); ?>",
				minlength: "<?php echo $this->lang->line('employees_password_minlength'); ?>"
			},
			repeat_password:
			{
				equalTo: "<?php echo $this->lang->line('employees_password_must_match'); ?>"
     		}
		}
	}, form_support.error));
});
</script>
