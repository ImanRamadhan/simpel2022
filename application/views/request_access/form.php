<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Request Access</a></li>
					<li class="breadcrumb-item active">Request Access Login</li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $page_title; ?></h4>
		</div>
	</div>
</div>



<?php echo form_open($controller_name . '/save/', array('id' => 'request_access_form', 'class' => 'form-horizontal')); ?>

<div class="row">
	<div class="col-lg-8 col-sm-12 col-md-8">

		<ul id="error_message_box" class="error_message_box card alert-danger"></ul>


		<div class="card">
			<div class="card-header bg-primary text-white">
				<?php echo $page_title; ?>
			</div>
			<div class="card-body">

				<div class="row">
					<div class="col-sm-12 col-lg-12">

						<div class="form-group form-group-sm row">

							<?php echo form_label('Pilih User <span class="text-danger">*</span>', 'name', array('class' => 'col-form-label col-sm-3')); ?>

							<div class='col-sm-9'>
								<?php echo form_dropdown(
									array(
										'name' => 'user_id',
										'id' => 'select-user',
										'class' => 'form-control form-control-sm',
									)
								); ?>
								<?php echo form_hidden('type','0'); ?>
								<?php echo form_hidden('id', 0); ?>
							</div>
						</div>



					</div>
				</div>
			</div>
			<div class="card-footer">

				<div class="text-center">

					<button class="btn btn-sm btn-primary" id="submit"> Simpan</button>
					<a class="btn btn-sm btn-light" href="<?php echo site_url('request_access'); ?>"> Batal</a>
				</div>
			</div>
		</div>

	</div>
</div>

</fieldset>

<?php echo form_close(); ?>

<script type="text/javascript">
	//validation and submit handling
	$(document).ready(function() {
		$('#request_access_form').validate($.extend({
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					beforeSubmit: function() {
						$('#submit').attr('disabled', true);
						//$('#submit').html("<i class='fa fa-spinner fa-spin'></i> Processing...");
					},
					success: function(response) {
						//console.log(response);
						$.notify(response.message, {
							type: response.success ? 'success' : 'danger'
						});

						setTimeout(function() {
							window.location.href = "<?php echo site_url('request_access'); ?>";
						}, 3000);
					},
					dataType: 'json'
				});
			},

			rules: {
				select_user: {
					required: true,
				},

			},

			messages: {
				select_user: {
					required: "Harus diisi",
				},

			}
		}, form_support.error));
	});
	$('#select-user').select2({
        ajax: {
            url: '<?php echo site_url('request_access/search_user')?>', // Replace with your API endpoint
            dataType: 'json',
            delay: 250, // Delay in milliseconds before sending the request
            data: function (params) {
                return {
                    search: params.term // Search term
                };
            },
            processResults: function (data) {
                // Parse the results into the format expected by Select2
                return {
                    results: data.items // Assuming your API returns an object with an "items" array
                };
            },
            cache: true
        },
        minimumInputLength: 3, // Minimum number of characters before search begins
        placeholder: ' -- Pilih -- ',
        allowClear: true
    });
</script>

<?php $this->load->view("partial/footer"); ?>