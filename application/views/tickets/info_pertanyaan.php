<h4>Pertanyaan</h4>
<div class="tab-pane p-3 border rounded-lg" id="jawaban" role="tabpanel">

	<div class="row">
		<div class="col-sm-12 col-lg-12">
			<p><?php echo nl2br($item_info->prod_masalah); ?></p>

			<?php if (count($replies)) echo '<h5></h5>'; ?>
			<table class="table table-striped">
				<?php foreach ($replies as $r): ?>
					<tr>
						<td>
							Tanggal : <?php echo $r->dt ?><br />
							Nama : <?php echo $r->name . ' (' . $r->direktorat . ' - ' . $r->kota . ')' ?><br />
							<br />
							<strong>Balasan/Jawaban</strong>
							<p><?php echo escape_input($r->message) ?></p>
							<?php
							$attachments = $r->attachments;
							if (!empty($attachments)) {
								echo "<strong>Lampiran:</strong>";
								echo "<ul>";
								$files = explode(',', $attachments);
								for ($i = 0; $i < count($files); $i++) {
									if (!empty($files[$i])) {
										$d = explode('#', $files[$i]);
										echo "<li><a target='#' href='" . site_url('downloads/download_attachment?att_id=' . $d[0] . '&trackid=' . $item_info->trackid) . "'>" . $d[1] . "</a></li>";
									}
								}
								echo "</ul>";
							}

							?>
						</td>
					</tr>
					<!--<div class="tab-pane p-3 border rounded-lg bg-light">-->


					<!--</div>-->

				<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>