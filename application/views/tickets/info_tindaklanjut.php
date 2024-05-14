<h4>Tindak Lanjut</h4>
<div class="tab-pane p-3 border rounded-lg" id="tindaklanjut" role="tabpanel">
	
	
	<div class="row">
		<div class="col-sm-12 col-lg-12">
			<div class="form-group form-group-sm row">
				<?php echo form_label('Dirujuk', 'perlu_rujuk_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo ($item_info->is_rujuk == '1')?'Ya':'Tidak'; ?></p>
				</div>
			</div>
			<?php if($item_info->is_rujuk == '1'):?>
				
				<?php if(!empty($rujukan_info->rid)):?>
				<div class="form-group form-group-sm row">
					<?php echo form_label('Rujuk ke', 'rujuk_ke_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
					<div class="col-sm-8">
						<table class="table table-striped table-bordered">
							<thead>
							<tr>
								<th>Nama Unit Rujukan</th>
								<th class="text-center">SLA Unit Teknis (HK)</th>
								<th class="text-center">Status Rujukan</th>
								<th class="text-center">Waktu Penyelesaian (HK)</th>
								<?php if($item_info->owner == $this->session->id): ?>
								<th class="text-center"></th>
								<?php endif; ?>
							</tr>
							</thead>
							<tbody>
								<?php if($item_info->direktorat):?>
								<tr>
									<td><?php echo print_dir_rujukan($item_info->direktorat); ?></td>
									<td align="center"><?php echo $item_info->d1_prioritas;?></td>
									<td align="center">
									<?php /*echo ($rujukan_info->status_rujuk1)?'Sudah TL':'Belum TL';*/ ?>
									<?php
									if($rujukan_info->status_rujuk1)
									{
										echo '<span class="text-success">Sudah TL</span> ';
									}
									else
									{
										echo '<span class="text-danger">Belum TL</span> ';
									}
									
									?>
									</td>
									<td align="center"><?php echo (($item_info->direktorat)?$rujukan_info->hk_rujukan1:'')?></td>
									<?php if($item_info->owner == $this->session->id): ?>
									<td align="center">
									<?php
									if($rujukan_info->status_rujuk1)
									{
										if($controller_name == 'tickets')
											echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah Status TL" data-href="'.site_url('rujukan/confirm_tl_no/1/'.$item_info->id.'/'.$item_info->direktorat).'">Ubah Status</a>';
									}
									else
									{
										if($controller_name == 'tickets')
											echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah Status TL" data-href="'.site_url('rujukan/confirm_tl_yes/1/'.$item_info->id.'/'.$item_info->direktorat).'">Ubah Status</a>';
									}
									
									?>
									
									</td>
									<?php endif; ?>
								</tr>
								<?php endif; ?>
								
								
								
								<?php if($item_info->direktorat2):?>
								<tr>
									<td><?php echo print_dir_rujukan($item_info->direktorat2); ?></td>
									<td align="center"><?php echo $item_info->d2_prioritas;?></td>
									<td align="center">
									<?php /*echo ($rujukan_info->status_rujuk1)?'Sudah TL':'Belum TL';*/ ?>
									<?php
									if($rujukan_info->status_rujuk2)
									{
										echo '<span class="text-success">Sudah TL</span> ';
									}
									else
									{
										echo '<span class="text-danger">Belum TL</span> ';
									}
									
									?>
									</td>
									<td align="center"><?php echo (($item_info->direktorat2)?$rujukan_info->hk_rujukan2:'')?></td>
									<?php if($item_info->owner == $this->session->id): ?>
									<td align="center">
									<?php
									if($rujukan_info->status_rujuk2)
									{
										if($controller_name == 'tickets')
											echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah Status TL" data-href="'.site_url('rujukan/confirm_tl_no/2/'.$item_info->id.'/'.$item_info->direktorat2).'">Ubah Status</a>';
									}
									else
									{
										if($controller_name == 'tickets')
											echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah Status TL" data-href="'.site_url('rujukan/confirm_tl_yes/2/'.$item_info->id.'/'.$item_info->direktorat2).'">Ubah Status</a>';
									}
									
									?>
									
									</td>
									<?php endif; ?>
								</tr>
								<?php endif; ?>
								
								<?php if($item_info->direktorat3):?>
								<tr>
									<td><?php echo print_dir_rujukan($item_info->direktorat3); ?></td>
									<td align="center"><?php echo $item_info->d3_prioritas;?></td>
									<td align="center">
									<?php /*echo ($rujukan_info->status_rujuk1)?'Sudah TL':'Belum TL';*/ ?>
									<?php
									if($rujukan_info->status_rujuk3)
									{
										echo '<span class="text-success">Sudah TL</span> ';
									}
									else
									{
										echo '<span class="text-danger">Belum TL</span> ';
									}
									
									?>
									</td>
									<td align="center"><?php echo (($item_info->direktorat3)?$rujukan_info->hk_rujukan3:'')?></td>
									<?php if($item_info->owner == $this->session->id): ?>
									<td align="center">
									<?php
									if($rujukan_info->status_rujuk3)
									{
										if($controller_name == 'tickets')
											echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah Status TL" data-href="'.site_url('rujukan/confirm_tl_no/3/'.$item_info->id.'/'.$item_info->direktorat3).'">Ubah Status</a>';
									}
									else
									{
										if($controller_name == 'tickets')
											echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah Status TL" data-href="'.site_url('rujukan/confirm_tl_yes/3/'.$item_info->id.'/'.$item_info->direktorat3).'">Ubah Status</a>';
									}
									
									?>
									
									</td>
									<?php endif; ?>
								</tr>
								<?php endif; ?>
								
								<?php if($item_info->direktorat4):?>
								<tr>
									<td><?php echo print_dir_rujukan($item_info->direktorat4); ?></td>
									<td align="center"><?php echo $item_info->d4_prioritas;?></td>
									<td align="center">
									<?php /*echo ($rujukan_info->status_rujuk1)?'Sudah TL':'Belum TL';*/ ?>
									<?php
									if($rujukan_info->status_rujuk4)
									{
										echo '<span class="text-success">Sudah TL</span> ';
									}
									else
									{
										echo '<span class="text-danger">Belum TL</span> ';
									}
									
									?>
									</td>
									<td align="center"><?php echo (($item_info->direktorat4)?$rujukan_info->hk_rujukan4:'')?></td>
									<?php if($item_info->owner == $this->session->id): ?>
									<td align="center">
									<?php
									if($rujukan_info->status_rujuk4)
									{
										if($controller_name == 'tickets')
											echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah Status TL" data-href="'.site_url('rujukan/confirm_tl_no/4/'.$item_info->id.'/'.$item_info->direktorat4).'">Ubah Status</a>';
									}
									else
									{
										if($controller_name == 'tickets')
											echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah Status TL" data-href="'.site_url('rujukan/confirm_tl_yes/4/'.$item_info->id.'/'.$item_info->direktorat4).'">Ubah Status</a>';
									}
									
									?>
									
									</td>
									<?php endif; ?>
								</tr>
								<?php endif; ?>
								
								<?php if($item_info->direktorat5):?>
								<tr>
									<td><?php echo print_dir_rujukan($item_info->direktorat5); ?></td>
									<td align="center"><?php echo $item_info->d5_prioritas;?></td>
									<td align="center">
									<?php /*echo ($rujukan_info->status_rujuk1)?'Sudah TL':'Belum TL';*/ ?>
									<?php
									if($rujukan_info->status_rujuk5)
									{
										echo '<span class="text-success">Sudah TL</span> ';
									}
									else
									{
										echo '<span class="text-danger">Belum TL</span> ';
									}
									
									?>
									</td>
									<td align="center"><?php echo (($item_info->direktorat5)?$rujukan_info->hk_rujukan5:'')?></td>
									<?php if($item_info->owner == $this->session->id): ?>
									<td align="center">
									<?php
									if($rujukan_info->status_rujuk5)
									{
										if($controller_name == 'tickets')
											echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah Status TL" data-href="'.site_url('rujukan/confirm_tl_no/5/'.$item_info->id.'/'.$item_info->direktorat5).'">Ubah Status</a>';
									}
									else
									{
										if($controller_name == 'tickets')
											echo '<a class="modal-dlg text-primary" data-btn-submit="Ya" title="Ubah Status TL" data-href="'.site_url('rujukan/confirm_tl_yes/5/'.$item_info->id.'/'.$item_info->direktorat5).'">Ubah Status</a>';
									}
									
									?>
									
									</td>
									<?php endif; ?>
								</tr>
								<?php endif; ?>
								
								
							</tbody>
						</table>
					</div>
				</div>
				<?php else:?>
				<div class="form-group form-group-sm row">
					<?php echo form_label('Rujuk ke', 'perlu_rujuk_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
					<div class="col-sm-8">
						<p class="form-control-plaintext">
							
						<?php
						if($item_info->direktorat)
							echo "- ".print_dir_rujukan($item_info->direktorat).' ('.$item_info->d1_prioritas.' HK)'."<br />";
						if($item_info->direktorat2)
							echo "- ".print_dir_rujukan($item_info->direktorat2).' ('.$item_info->d2_prioritas.' HK)'."<br />";
						if($item_info->direktorat3)
							echo "- ".print_dir_rujukan($item_info->direktorat3).' ('.$item_info->d3_prioritas.' HK)'."<br />";
						if($item_info->direktorat4)
							echo "- ".print_dir_rujukan($item_info->direktorat4).' ('.$item_info->d4_prioritas.' HK)'."<br />";
						if($item_info->direktorat5)
							echo "- ".print_dir_rujukan($item_info->direktorat5).' ('.$item_info->d5_prioritas.' HK)'."<br />";
						?>
							
						</p>
					</div>
				</div>
				<?php endif; ?>
			
			<?php endif;?>
			
		</div>
	</div>
</div>