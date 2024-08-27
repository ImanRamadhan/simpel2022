<h4>Kirim Balasan</h4>
<div class="tab-pane p-3 border rounded-lg" id="jawaban" role="tabpanel">
	<?php if ($item_info->status == '3'): ?>
		<div class="alert alert-info">
			<i class="mdi mdi-alert-circle mr-2"></i> Layanan ini telah ditutup.
		</div>
	<?php endif; ?>
	<div class="row">
		<div class="col-sm-12 col-lg-12">
			<ul id="error_message_box" class="error_message_box"></ul>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Balasan <span class="text-danger">*</span>', 'balasan', array('for' => 'message', 'class' => 'col-form-label col-sm-3')); ?>
				<div class="col-sm-6">
					<?php echo form_textarea(array(
						'class' => 'form-control input-sm ',
						'name' => 'message',
						'id' => 'message',
						'rows' => 6,
						'value' => ''
					)); ?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Attachment(s)', 'attachments', array('class' => 'col-form-label col-sm-3')); ?>
				<div class='col-sm-9'>
					<div class="row">
						<div class="col-sm-8">
							<div class="dropzone" id="dragndrop"></div>
						</div>
						<div class='col-sm-4'>
							<?php

							echo sprintf('Maksimum ukuran per berkas: %s', $upload_config['max_size_mb']);
							echo "<br />";
							echo sprintf('Ekstensi berkas yang diperbolehkan: %s', str_replace('|', ', ', $upload_config['allowed_types']));

							?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<table id="attachment_table" class="table table-sm"></table>
						</div>
					</div>

				</div>
			</div>
			<div class="form-group form-group-sm row">
				<div class='col-sm-12'>
					<div class="text-center">

						<button class="btn btn-info btn-sm btn-round" id="send_reply" title="Kirim Balasan">Kirim Balasan</button>

					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {

		var thisDropzone;

		$("#dragndrop").dropzone({
			url: "<?php echo $upload_url; ?>",
			addRemoveLinks: true,
			autoProcessQueue: false,
			parallelUploads: 5,
			maxFilesize: 20,
			dictDefaultMessage: 'Drop file di sini untuk upload',
			uploadMultiple: true,
			init: function() {
				thisDropzone = this;
				this.on("sending", function(file, xhr, formData) {
					formData.append("<?php echo $this->security->get_csrf_token_name(); ?>", csrf_token());
					formData.append("mode", 0);
					formData.append("message", $('#message').val());
					formData.append("ticketid", "<?php echo $item_info->trackid; ?>");
					formData.append("id", "<?php echo $item_info->id; ?>");
				});
				this.on("success", function(file, response) {
					//$.fn.load_attachments();
					this.removeAllFiles();

					$.notify('Balasan berhasil dikirim', {
						type: 'success'
					});
					setTimeout(function() {
						location.reload();
					}, 1000);
					//location.reload();
					//var data = $.parseJSON(response);
					//if(data.error)
					//$.notify(data.message, { type: 'danger' });
					//alert(data.message);
				});
				this.on("addedfile", function(file) {});

				this.on("removedfile", function(file) {});

				this.on("error", function(file, message) {
					alert('Terjadi error pada saat proses upload');
				});
			}



		});

		$("#send_reply").click(function(e) {
			e.preventDefault();
			if ($('#message').val() == '') {
				alert('Balasan tidak boleh kosong');
				return false;
			}
			if (thisDropzone.getQueuedFiles().length > 0)
				thisDropzone.processQueue();
			else {
				$.ajax({
					type: 'POST',
					url: "<?php echo site_url('tickets/send_reply_only') ?>",
					data: {
						ticketid: '<?php echo $item_info->trackid; ?>',
						id: '<?php echo $item_info->id; ?>',
						message: $('#message').val(),
						<?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
					},
					success: function(result) {
						//alert('Balasan berhasil dikirim');
						$.notify('Balasan berhasil dikirim', {
							type: 'success'
						});
						setTimeout(function() {
							location.reload();
						}, 1000);

					}
				});
			}
		});
	});
</script>