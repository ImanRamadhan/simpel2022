
<?php echo form_open($url_post , array('id'=>'confirm_form', 'class'=>'form-horizontal')); ?>
<div class="row">
	<div class="col-sm-12">
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Pilih Tanggal TL', 'tgl', array('class'=>'col-form-label col-sm-5')); ?>
			<div class='col-sm-4'>
						
				<?php 
				
				$data = array(
					'name'=>'tl_date',
					'id'=>'tl_date',
					'class'=>'form-control form-control-sm',
					'value'=>date('d/m/Y'));

				echo form_input($data);?>
				
			</div>
			
			
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
$(document).ready(function()
{
	$.extend( $.fn.datepicker.defaults, {format: 'dd/mm/yyyy', language: 'id', daysOfWeekHighlighted: [0,6], todayHighlight: true, weekStart: 1} );
	
	
	$('#tl_date').datepicker();
	
	/*$('#confirm_form').validate($.extend({
		submitHandler: function(form)
		{
		},

		rules:
		{
			tgl: "required"
			
   		},

		messages: 
		{
     		tgl: "Tanggal TL harus diisi"
     		
		},
		
		
	}, form_support.error));*/

});


</script>