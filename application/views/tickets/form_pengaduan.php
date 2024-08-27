<h4>Layanan</h4>

<div class="row">
	<div class="col-sm-12 col-lg-12">

		<div class="form-group form-group-sm row">
			<?php echo form_label('Isu Topik <span class="text-danger">*</span>', 'isu_topik', array('for' => 'message', 'class' => 'col-form-label col-sm-2')); ?>
			<div class="col-sm-6">
				<?php echo form_textarea(array(
					'class' => 'form-control form-control-sm ',
					'name' => 'isu_topik',
					'id' => 'isu_topik',
					'rows' => 3,
					'value' => $item_info->isu_topik
				)); ?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Isi Layanan <span class="text-danger">*</span>', 'prod_masalah', array('for' => 'message', 'class' => 'col-form-label col-sm-2')); ?>
			<div class="col-sm-6">
				<?php echo form_textarea(array(
					'class' => 'form-control form-control-sm',
					'name' => 'prod_masalah',
					'id' => 'prod_masalah',
					'rows' => 6,
					'value' => $item_info->prod_masalah
				)); ?>
			</div>
		</div>

		<div class="form-group form-group-sm row">
			<?php echo form_label('Tgl Layanan <span class="text-danger">*</span>', 'tglpengaduan', array('class' => 'required col-form-label col-sm-2')); ?>
			<div class='col-sm-3'>

				<?php

				$data = array(
					'name' => 'tglpengaduan',
					'id' => 'tglpengaduan',
					'class' => 'form-control form-control-sm',
					'value' => $item_info->tglpengaduan_fmt
				);
				//if($_SESSION['city'] == 'PUSAT')
				$data['readonly'] = 'readonly';

				echo form_input($data); ?>

			</div>
			<?php echo form_label('Waktu <span class="text-danger">*</span>', 'jam', array('class' => 'required col-form-label col-sm-1')); ?>
			<div class='col-sm-2'>
				<?php
				$data = array(
					'name' => 'jam',
					'id' => 'jam',
					'class' => 'form-control form-control-sm',
					'value' => $item_info->waktu
				);
				if ($_SESSION['city'] == 'PUSAT')
					$data['readonly'] = 'readonly';
				echo form_input($data); ?>

			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Petugas Penerima <span class="text-danger">*</span>', 'penerima', array('class' => 'required col-form-label col-sm-2')); ?>
			<div class='col-sm-3'>
				<?php echo form_input(
					array(
						'name' => 'penerima',
						'id' => 'penerima',

						'class' => 'form-control form-control-sm',
						'value' => $item_info->penerima
					)
				); ?>
			</div>

		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Attachment(s)', 'attachments', array('class' => 'col-form-label col-sm-2')); ?>
			<div class='col-sm-6'>
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
		<div class="form-group form-group-sm row">
			<?php echo form_label('', '', array('class' => 'col-form-label col-sm-2')); ?>

			<div class='col-sm-6'>
				<table id="attachment_table" class="table table-sm"></table>
			</div>
		</div>

	</div>

</div>
<script type="text/javascript">
	$(document).ready(function() {
		$.fn.load_attachments = function() {
			$.ajax({
				type: 'GET',
				url: "<?php echo site_url($controller_name . '/get_attachments?ticketid=' . $item_info->trackid . '&mode=0') ?>",
				success: function(result) {
					var data = $.parseJSON(result);
					$('#attachment_table').empty();
					$.each(data, function(key, value) {
						var $tr = $('<tr>').append(
							$('<td>').text(value.name),
							$('<td>').text(bytesToSize(value.size)),
							$('<td>').append(
								$('<button>', {
									text: "Download",
									title: "Download File",
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
											$(button).closest('tr').remove();

											$.ajax({
												type: 'POST',
												url: "<?php echo site_url($controller_name . '/delete_file'); ?>",
												data: {
													'<?php echo $this->security->get_csrf_token_name(); ?>': csrf_token(),
													ticketid: '<?php echo $item_info->trackid; ?>',
													mode: 0,
													att_id: value.att_id
												},
												success: function(result) {
													//alert('File berhasil dihapus');

												}
											});

										});

									}
								})

							),

						).appendTo('#attachment_table');

					});

				}
			});
		}

		$.fn.load_attachments();



		$("#dragndrop").dropzone({
			url: "<?php echo site_url('tickets/upload_lamp_pengaduan'); ?>",
			addRemoveLinks: true,
			uploadMultiple: true,
			parallelUploads: 10,
			maxFiles: 10,
			paramName: "file",
			dictDefaultMessage: 'Drop file di sini untuk upload',
			maxFilesize: 20,
			init: function() {
				thisDropzone = this;
				this.on("sending", function(file, xhr, formData) {
					formData.append("<?php echo $this->security->get_csrf_token_name(); ?>", csrf_token());
					formData.append("mode", 0);
					formData.append("ticketid", "<?php echo $item_info->trackid; ?>");

				});
				this.on("success", function(file, response) {
					$.fn.load_attachments();
					this.removeAllFiles();

					var data = $.parseJSON(response);
					if (data.error)
						$.notify(data.message, {
							type: 'danger'
						});
					//alert(data.message);
				});
				this.on("addedfile", function(file) {});

				this.on("removedfile", function(file) {});

				this.on("error", function(file, message) {
					//alert('Terjadi error pada saat proses upload');
					$.notify('Terjadi error pada saat proses upload', {
						type: 'danger'
					});
				});
			}



		});
	});
</script>