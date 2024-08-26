<h4>Jawaban</h4>
<div class="row">
	<div class="col-sm-12 col-lg-12">
		<div class="form-group form-group-sm row">
			<?php echo form_label('Jawaban <span class="text-danger">*</span>', 'alamat_label', array('for' => 'message', 'class' => 'col-form-label col-sm-2 required')); ?>
			<div class="col-sm-6">
				<?php echo form_textarea(array(
					'class' => 'form-control form-control-sm',
					'name' => 'jawaban',
					'id' => 'jawaban',
					'rows' => 5,
					'value' => $item_info->jawaban
				)); ?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Attachment(s)', 'attachments', array('class' => 'col-form-label col-sm-2')); ?>
			<div class='col-sm-6'>
				<div class="dropzone" id="dragndrop2"></div>
			</div>
			<div class='col-sm-4'>
				<?php

				echo sprintf('Maksimum ukuran per berkas: %s', $upload_config['max_size_mb']);
				echo "<br />";
				echo sprintf('Ekstensi berkas yang diperbolehkan: %s', str_replace('|', ', ', $upload_config['allowed_types']));

				?>
			</div>

		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('', '', array('class' => 'col-form-label col-sm-2')); ?>

			<div class='col-sm-6'>
				<table id="attachment_table2" class="table table-sm"></table>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Keterangan', 'alamat_label', array('for' => 'message', 'class' => 'col-form-label col-sm-2 required')); ?>
			<div class="col-sm-6">
				<?php echo form_textarea(array(
					'class' => 'form-control form-control-sm',
					'name' => 'keterangan',
					'id' => 'keterangan',
					'rows' => 3,
					'value' => htmlspecialchars($item_info->keterangan)
				)); ?>
			</div>
		</div>

		<div class="form-group form-group-sm row">
			<?php echo form_label('Nama Petugas Input <span class="text-danger">*</span>', 'petugas_entry', array('class' => 'required col-form-label col-sm-2')); ?>
			<div class='col-sm-3'>
				<?php echo form_input(
					array(
						'name' => 'petugas_entry',
						'id' => 'petugas_entry',

						'class' => 'form-control form-control-sm',
						'value' => $item_info->petugas_entry
					)
				); ?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Nama Penjawab <span class="text-danger">*</span>', 'penjawab', array('class' => 'required col-form-label col-sm-2')); ?>
			<div class='col-sm-3'>
				<?php echo form_input(
					array(
						'name' => 'penjawab',
						'id' => 'penjawab',

						'class' => 'form-control form-control-sm',
						'value' => $item_info->penjawab
					)
				); ?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Dijawab melalui <span class="text-danger">*</span>', 'answered_via', array('class' => 'required col-form-label col-sm-2')); ?>
			<div class='col-sm-3'>
				<?php echo form_dropdown('answered_via', $answered_via, $item_info->answered_via, 'class="form-control form-control-sm" id="answered_via" '); ?>
			</div>
		</div>




	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$.fn.load_attachments2 = function() {
			$.ajax({
				type: 'GET',
				url: "<?php echo site_url($controller_name . '/get_attachments?draftid=' . $item_info->id . '&mode=1') ?>",
				success: function(result) {
					var data = $.parseJSON(result);
					$('#attachment_table2').empty();
					$.each(data, function(key, value) {
						var $tr = $('<tr>').append(
							$('<td>').text(value.name),
							$('<td>').text(bytesToSize(value.size)),
							$('<td>').append(
								$('<button>', {
									text: "Lihat",
									title: "Lihat File",
									class: 'btn btn-sm btn-light btn-round',
									click: function(e) {
										e.preventDefault();
										window.open(value.url, '_blank');
									}
								}),
								$('<button>', {
									text: "Hapus",
									title: "Hapus File",
									class: 'btn btn-sm btn-light btn-round',
									click: function(e) {
										e.preventDefault();
										var button = this;
										BootstrapDialog.confirm("Apakah Anda akan menghapus file " + value.name + "?", function(result) {
											if (result) {
												$(button).closest('tr').remove();

												$.ajax({
													type: 'POST',
													url: "<?php echo site_url($controller_name . '/delete_file'); ?>",
													data: {
														'<?php echo $this->security->get_csrf_token_name(); ?>': csrf_token(),
														draftid: '<?php echo $item_info->id; ?>',
														mode: 1,
														att_id: value.att_id
													},
													success: function(result) {
														//alert('File berhasil dihapus');

													}
												});
											}

										});


									}
								})
							),

						).appendTo('#attachment_table2');



					});

				}
			});
		}

		$.fn.load_attachments2();



		$("#dragndrop2").dropzone({
			url: "<?php echo site_url('drafts/upload_lamp_jawaban'); ?>",
			addRemoveLinks: true,
			<?php if (empty($item_info->id)): ?>
				autoProcessQueue: false,
			<?php endif; ?>
			uploadMultiple: true,
			parallelUploads: 10,
			maxFiles: 10,
			paramName: "file2",
			dictDefaultMessage: 'Drop file di sini untuk upload',
			init: function() {
				thisDropzone = this;
				this.on("sending", function(file, xhr, formData) {
					formData.append("<?php echo $this->security->get_csrf_token_name(); ?>", csrf_token());
					formData.append("mode", 1);
					formData.append("draftid", $('#draftid').val());
				});
				this.on("success", function(file, response) {
					$.fn.load_attachments2();
					this.removeAllFiles();

					var data = $.parseJSON(response);
					if (data.error)
						$.notify(data.message, {
							type: 'danger'
						});
				});
				this.on("addedfile", function(file) {});

				this.on("removedfile", function(file) {});

				this.on("error", function(file, message) {
					console.log("testttt");
					alert(message);
				});
			}



		});
	});
</script>