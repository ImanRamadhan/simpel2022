<h4>Jawaban</h4>
<div class="tab-pane p-3 border rounded-lg" id="jawaban" role="tabpanel">
	
	<div class="row">
		<div class="col-sm-12 col-lg-12">
			<div class="form-group form-group-sm row">
				<?php echo form_label('Jawaban', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo nl2br($item_info->jawaban); ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Keterangan', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $item_info->keterangan; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Attachment(s)', 'attachments', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-5'>
					<ul class="list-group list-group-flush">
					<?php
					foreach($att_jawaban as $row)
					{
						echo "<li class='list-group-item'><a target='_blank' href='".$row['url']."'>".$row['real_name']."</a></li>";
					}
					?>
					</ul>
				</div>
				
			</div>
			
			<div class="form-group form-group-sm row">
				<?php echo form_label('Nama Petugas Input', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<p class="form-control-plaintext"><?php echo $item_info->petugas_entry; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Nama Penjawab', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<p class="form-control-plaintext"><?php echo $item_info->penjawab; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Dijawab melalui', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
				<div class='col-sm-4'>
					<p class="form-control-plaintext"><?php echo $item_info->answered_via; ?></p>
				</div>
			</div>
			
			
			
		</div>
	</div>
</div>
