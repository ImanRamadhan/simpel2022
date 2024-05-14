<h4>Pengaduan/Permintaan Informasi</h4>
<div class="tab-pane p-3 border rounded-lg" id="pengaduan" role="tabpanel">
	
	
	<div class="row">
		<div class="col-sm-12 col-lg-12">
		
			<div class="form-group form-group-sm row">
				<?php echo form_label('Isu Topik', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $item_info->isu_topik; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Isi Pengaduan / Pertanyaan', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo nl2br($item_info->prod_masalah); ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Jenis Layanan', 'jenis_layanan', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php 
						if($item_info->info == 'I') echo 'Permintaan Informasi';
						else if($item_info->info == 'P') echo 'Pengaduan';
						else if($item_info->info == 'IK') echo 'Informasi Keracunan';
						else echo '';
					?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Tgl Layanan', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->tglpengaduan_fmt; ?></p>
				</div>
				
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Petugas Penerima', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-8'>
					<p class="form-control-plaintext"><?php echo $item_info->penerima; ?></p>
				</div>
				
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Attachment(s)', 'attachments', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-5'>
					<ul class="list-group list-group-flush">
					<?php
					foreach($att_pengaduan as $row)
					{
						echo "<li class='list-group-item'><a target='_blank' href='".$row['url']."'>".$row['real_name']."</a></li>";
					}
					?>
					</ul>
					
				</div>
			</div>
	
		</div>
		
	</div>
</div>
