<h4>Layanan <?php if($item_info->status == '3'): ?><i class="fa fa-lock" aria-hidden="true"></i><?php endif; ?></h4>
<div class="tab-pane p-3 border rounded-lg" id="pelapor" role="tabpanel">
	
	
	<div class="row">
		<div class="col-sm-12 col-lg-6">
	
		<div class="form-group form-group-sm row">

			<?php echo form_label('ID Layanan', 'iden_nama', array('class'=>' col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<p class="form-control-plaintext"><?php echo $item_info->trackid; ?></p>
				
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Tgl Layanan', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<p class="form-control-plaintext"><?php echo $item_info->tglpengaduan_fmt; ?></p>
			</div>
			
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Status', 'status', array('class'=>'col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<p class="form-control-plaintext">
				
				<?php 
				
				if($item_info->status == '3')
				{
					echo '<span class="text-success">'.get_status_plain($item_info->status).'</span> ';
					if($controller_name == 'tickets')
						echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah status layanan menjadi Open" data-href="'.site_url('tickets/confirm_status_open/'.$item_info->id).'">[Ubah menjadi Open]</a>';
				}
				else
				{
					echo '<span class="text-danger">'.get_status_plain($item_info->status).'</span> ';
					if($controller_name == 'tickets')
						echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah status layanan menjadi Closed" data-href="'.site_url('tickets/confirm_status_closed/'.$item_info->id).'">[Ubah menjadi Closed]</a>';
				}
				
				?>
				</p>
				
				
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Dibuat oleh', 'iden_negara', array('class'=>' col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<p class="form-control-plaintext"><?php echo $item_info->created_by; ?></p>
				
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Tanggal Dibuat', 'iden_instansi', array('class'=>' col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<p class="form-control-plaintext"><?php echo $item_info->tglpengaduan_fmt.' '.$item_info->waktu; ?></p>
				
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Terakhir Diupdate', 'iden_instansi', array('class'=>' col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<p class="form-control-plaintext"><?php echo $item_info->lastchange_fmt; ?></p>
			</div>
		</div>
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Jumlah Balasan', 'balasan_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3 ')); ?>
			<div class="col-sm-8">
				<p class="form-control-plaintext"><?php echo $replies_cnt; ?></p>
				
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Terakhir membalas', 'iden_instansi', array('class'=>' col-form-label col-sm-3')); ?>
			<div class='col-sm-8'>
				<p class="form-control-plaintext"><?php echo !empty($item_info->last_replier)?$item_info->last_replier:'-'; ?></p>
				
			</div>
		</div>
		
	
		</div>
		<div class="col-sm-12 col-lg-6">
			<div class="form-group form-group-sm row">
				<?php echo form_label('Rujuk ke Unit Teknis', 'rujuk', array('class'=>' col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo ($item_info->is_rujuk == '1')?'Ya':'Tidak'; ?></p>
				</div>
			</div>
			<?php /*if($item_info->is_rujuk == '1'):*/?>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Status TL', 'tl_label', array('class'=>' col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext">
					<?php 
						
						if($item_info->tl)
						{
							echo '<span class="text-success">Sudah</span> ';
							if($controller_name == 'tickets')
								echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah Status TL" data-href="'.site_url('tickets/confirm_tl_no/'.$item_info->id).'">[Ubah menjadi Belum]</a>';
						}
						else
						{
							echo '<span class="text-danger">Belum</span> ';
							if($controller_name == 'tickets')
								echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah Status TL" data-href="'.site_url('tickets/confirm_tl_yes/'.$item_info->id).'">[Ubah menjadi Sudah]</a>';
						}
					?>
					</p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Tanggal TL', 'tl_date', array('class'=>' col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->tl_date_fmt; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Status <i>Feedback</i>', 'fb', array('class'=>' col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext">
					
					<?php 
						
						if($item_info->fb)
						{
							echo '<span class="text-success">Sudah</span> ';
							if($controller_name == 'tickets'){
								echo '<a class="modal-dlg text-primary" data-btn-submit="Ya"  title="Ubah Status FB" data-href="'.site_url('tickets/confirm_fb_no/'.$item_info->id).'">[Ubah menjadi Belum]</a>';
								if($item_info->jenis == 'PPID' && !isset($att_ppidtl)){
									echo '&nbsp;&nbsp;<a class="modal-dlg text-primary" data-btn-submit="Upload Berkas" title="Upload bukti berkas yg sudah ditandatangan" data-href="'.site_url('tickets/confirm_upload_signed_formulir/'.$item_info->id . '/' . $item_info->trackid).'">[upload berkas layanan]</a>';
								}

								if($item_info->jenis == 'PPID' && isset($att_ppidtl)){
									echo '&nbsp;&nbsp;<a href="'.site_url('uploads/files/').$att_ppidtl->saved_name.'" target="_blank"><i class="fa fa-file-pdf">'.$att_ppidtl->real_name.'</i></a>';
									echo '&nbsp;&nbsp;<a class="modal-dlg text-primary" data-btn-submit="Upload Berkas" title="Upload bukti berkas yg sudah ditandatangan" data-href="'.site_url('tickets/confirm_upload_signed_formulir/'.$item_info->id . '/' . $item_info->trackid).'">[upload berkas layanan]</a>';
								}	

							}
								
						}
						else
						{
							echo '<span class="text-danger">Belum</span> ';
							if($controller_name == 'tickets')
								echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah Status FB" data-href="'.site_url('tickets/confirm_fb_yes/'.$item_info->id).'">[Ubah menjadi Sudah]</a>';
						}
					?>
					</p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Tanggal <i>Feedback</i>', 'verified_by', array('class'=>' col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->fb_date_fmt; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Isi <i>Feedback</i>', 'isi_fb', array('class'=>' col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->fb_isi; ?></p>
				</div>
			</div>
			<?php /*endif;*/?>
			
			
			<div class="form-group form-group-sm row">
				<?php echo form_label('Diverifikasi oleh', 'verified_by', array('class'=>' col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->verified_by; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Tanggal Verifikasi', 'verified_by', array('class'=>' col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->verified_date_fmt; ?></p>
				</div>
			</div>
			
			
		</div>
	</div>
	
</div>