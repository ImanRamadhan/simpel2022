<style>
.form-check-label {
	font-weight: normal;
	color: #212529;
}
</style>
<h4>Formulir PPID</h4>
<div class="tab-pane p-3 border rounded-lg" id="formulir" role="tabpanel">
	<div class="row">
		<div class="col-sm-12 col-lg-6">
			<?php $r = rand(1000,9999);?>
			
			<?php if(!empty($ppid_info->alasan_keberatan)):?>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Formulir Keberatan', 'unduh', array('class'=>'col-form-label col-sm-3')); ?>
				<div class='col-sm-9'>
					<ul>
						<?php $r = rand(1000,9999);?>
						<li>Formulir Keberatan <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_form6/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-pdf"></i> pdf</a>&nbsp; <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_word6/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-word"></i> docx</a></li>
						<li>Tanggapan Keberatan <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_form7/'.$item_info->id.'?r='.$r)?>"> <i class="fa fa-file-pdf"></i> pdf</a>&nbsp; <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_word7/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-word"></i> docx</a></li>
						
						
					</ol>
				</div>
			</div>
			<?php else: ?>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Formulir PPID', 'unduh', array('class'=>'col-form-label col-sm-3')); ?>
				<div class='col-sm-9'>
					<ul>
						
						<li>Formulir Permintaan Informasi Publik <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_form1/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-pdf"></i> pdf</a>&nbsp; <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_word1/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-word"></i> docx</a>&nbsp; <a class="" target="_blank" href="<?php echo site_url('ppid/ppid_word1/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-word"></i> *docx*</a></li>
						<!--<li>Formulir Tanda Terima Permohonan Informasi PPID <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_form2/'.$item_info->id.'?r='.$r)?>"> <i class="fa fa-file-pdf"></i> pdf</a>&nbsp; <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_word2/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-word"></i> docx</a></li>-->
						
						<li>Formulir Pemberitahuan Tertulis PPID <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_form4/'.$item_info->id.'?r='.$r)?>"> <i class="fa fa-file-pdf"></i> pdf</a>&nbsp; <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_word4/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-word"></i> docx</a>&nbsp; <a class="" target="_blank" href="<?php echo site_url('ppid/ppid_word4/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-word"></i> *docx*</a></li>
						<li>Tanggapan Tertulis <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_form5/'.$item_info->id.'?r='.$r)?>"> <i class="fa fa-file-pdf"></i> pdf</a>&nbsp; <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_word5/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-word"></i> docx</a>&nbsp; </li>
						
						<?php if(!empty($ppid_info->pengecualian_pasal17) || !empty($ppid_info->pengecualian_pasal_lain) ):?>
						<li>Formulir Penolakan <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_form3/'.$item_info->id.'?r='.$r)?>"> <i class="fa fa-file-pdf"></i> pdf</a> &nbsp; <a class="" target="_blank" href="<?php echo site_url('ppid/print_ppid_word3/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-word"></i> docx</a></li>
						<?php endif; ?>
						
						<li>Formulir Ketidaklengkapan &nbsp; <a class="" target="_blank" href="<?php echo site_url('ppid/ppid_word3/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-word"></i> *docx*</a></li>
						<li>Surat Keputusan ttg Penolakan &nbsp; <a class="" target="_blank" href="<?php echo site_url('ppid/ppid_word5/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-word"></i> *docx*</a></li>
						<li>Formulir Keberatan &nbsp; <a class="" target="_blank" href="<?php echo site_url('ppid/ppid_word6/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-word"></i> *docx*</a></li>
						<li>Formulir Registrasi Keberatan <a class="" target="_blank" href="<?php echo site_url('ppid/ppid_word7/'.$item_info->id.'?r='.$r)?>"><i class="fa fa-file-word"></i> docx</a>&nbsp; </li>

					</ol>
				</div>
			</div>
			<?php endif; ?>
			
		</div>
	</div>
	
	<div class="row">
		
		<div class="col-sm-12 col-lg-6">
			<h5>Permintaan Informasi Publik</h5>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Diterima Tanggal', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->tgl_diterima; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Melalui', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->diterima_via; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('No. KTP', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->no_ktp; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Rincian Informasi yang Dibutuhkan', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->rincian; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Tujuan Penggunaan Informasi', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->tujuan; ?></p>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Cara Memperoleh informasi', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				
				<?php 
			
				$cara_memperoleh_info = $ppid_info->cara_memperoleh_info;
				$cara_array = array();
				if(!empty($cara_memperoleh_info))
				{
					$cara = explode(',', $cara_memperoleh_info);
					foreach($cara as $obj)
					{
						$cara_array[$obj] = $obj;
					}
					
				}
				
				$cara_memperoleh_array = array(
					'1' => 'Melihat/membaca/mendengarkan/mencatat',
					'2' => 'Mendapatkan salinan dokumen (hardcopy/softcopy)'
				);
				?>
				
				<div class="col-sm-8">
					
					<?php foreach($cara_memperoleh_array as $k => $v):?>
					<div class="form-check">
					  <input class="form-check-input" type="checkbox" <?php echo (array_key_exists($k, $cara_array)?'checked':''); ?> name="cara_memperoleh_info[]" value="<?php echo $k;?>" id="defaultCheck1<?php echo $k; ?>">
					  <label class="form-check-label" for="defaultCheck1<?php echo $k; ?>">
						<?php echo $v; ?>
					  </label>
					</div>
					<?php endforeach; ?>
						
					
				</div>
			</div>
			
			<div class="form-group form-group-sm row">
				<?php echo form_label('Cara mendapatkan salinan informasi', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				
				<?php 
			
				$cara_mendapat_salinan = $ppid_info->cara_mendapat_salinan;
				$cara_salinan_array = array();
				if(!empty($cara_mendapat_salinan))
				{
					$cara = explode(',', $cara_mendapat_salinan);
					foreach($cara as $obj)
					{
						$cara_salinan_array[$obj] = $obj;
					}
					
				}
				
				$cara_mendapat_array = array(
					'1' => 'Mengambil langsung',
					'2' => 'Kurir',
					'3' => 'Pos',
					'4'	=> 'Email',
					'5'	=> 'Faksimili'
					
				);
				?>
				
				<div class="col-sm-8">
					
					<?php foreach($cara_mendapat_array as $k => $v):?>
					<div class="form-check">
					  <input class="form-check-input" type="checkbox" <?php echo (array_key_exists($k, $cara_salinan_array)?'checked':''); ?> name="cara_mendapat_salinan[]" value="<?php echo $k;?>" id="defaultCheck3<?php echo $k; ?>">
					  <label class="form-check-label" for="defaultCheck3<?php echo $k; ?>">
						<?php echo $v; ?>
					  </label>
					</div>
					<?php endforeach; ?>
						
					
				</div>
			</div>
			<h5>Pemberitahuan Tertulis</h5>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Tanggal', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->tgl_pemberitahuan_tertulis; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Penguasaan Informasi Publik', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<?php if($ppid_info->penguasaan_kami): ?>
					<p class="form-control-plaintext">Kami: <?php echo $ppid_info->penguasaan_kami_teks; ?></p>
					<?php endif; ?>
					<?php if($ppid_info->penguasaan_badan_lain): ?>
					<p class="form-control-plaintext">Badan Publik Lain: <?php echo $ppid_info->nama_badan_lain; ?></p>
					<?php endif; ?>
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php 
				$bentuk_fisik = $ppid_info->bentuk_fisik;
				$bentuk_fisik_tokens = array();
				if(!empty($bentuk_fisik))
				{
					$tokens = explode(',', $bentuk_fisik);
					foreach($tokens as $obj)
					{
						$bentuk_fisik_tokens[$obj] = $obj;
					}
					
				}
				
				$bentuk_fisik_array = array(
					'1' => 'Softcopy (termasuk rekaman)',
					'2'	=> 'Hardcopy/Salinan Tertulis'
				);
				
				echo form_label('Bentuk Fisik Yang Tersedia', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<?php foreach($bentuk_fisik_array as $k => $v):?>
					<div class="form-check">
					  <input class="form-check-input" type="checkbox" <?php echo (array_key_exists($k, $bentuk_fisik_tokens)?'checked':''); ?> name="bentuk_fisik[]" value="<?php echo $k;?>" id="bentukFisik<?php echo $k; ?>">
					  <label class="form-check-label" for="bentukFisik<?php echo $k; ?>">
						<?php echo $v; ?>
					  </label>
					</div>
					<?php endforeach; ?>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Biaya yang dibutuhkan', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<table>
						<tr>
							<td>Biaya Penyalinan</td><td>: Rp. </td><td align="right"><?php echo !empty($ppid_info->biaya_penyalinan)?number_format($ppid_info->biaya_penyalinan,0):'-'; ?></td>
						</tr>
						<tr>
							<td>Jml Lembaran</td><td>: </td><td align="right"><?php echo $ppid_info->biaya_penyalinan_lbr; ?></td>
						</tr>
						<tr>
							<td>Biaya Pengiriman</td><td>: Rp. </td><td align="right"><?php echo !empty($ppid_info->biaya_pengiriman)?number_format($ppid_info->biaya_pengiriman,0):'-'; ?></td>
						</tr>
						<tr>
							<td>Biaya Lain</td><td>: Rp. </td><td align="right"><?php echo !empty($ppid_info->biaya_lain)?number_format($ppid_info->biaya_lain,0):'-'; ?></td>
						</tr>
						<tr>
							<td>Total Biaya</td><td>: Rp. </td><td align="right"><?php echo !empty($ppid_info->biaya_total)?number_format($ppid_info->biaya_total,0):'-'; ?></td>
						</tr>
						
					</table>
					
					
					</p>
					
				</div>
			</div>
			
			<div class="form-group form-group-sm row">
				<?php echo form_label('Waktu penyediaan', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->waktu_penyediaan; ?> hari kerja</p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Penjelasan penghitaman / pengaburan Informasi yang dimohon', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->penghitaman; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Informasi Tidak Dapat Diberikan karena', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<div class="form-check">
					  <input class="form-check-input" type="checkbox" name="info_blm_dikuasai" <?php echo ($ppid_info->info_blm_dikuasai?'checked':''); ?> value="1" id="defaultCheck41">
					  <label class="form-check-label" for="defaultCheck41">
						Informasi yang diminta belum dikuasai
					  </label>
					</div>
					<div class="form-check">
					  <input class="form-check-input" type="checkbox" name="info_blm_didoc" <?php echo ($ppid_info->info_blm_didoc?'checked':''); ?> value="1" id="defaultCheck42">
					  <label class="form-check-label" for="defaultCheck42">
						Informasi yang diminta belum didokumentasikan
					  </label>
					</div>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Penyediaan informasi yang belum didokumentasikan dilakukan dalam jangka waktu',  'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->waktu_penyediaan2; ?>
					</p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Nama Pejabat PPID',  'nama_pejabat', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->nama_pejabat_ppid; ?>
					</p>
					
				</div>
			</div>
			
		</div>
		<div class="col-sm-12 col-lg-6">
			<h5>Penolakan</h5>
			
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pengecualian Informasi didasarkan pada alasan', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->pengecualian_pasal17?'Pasal 17 huruf '.$ppid_info->pasal17_huruf.' UU KIP':''; ?>
					<br />
					<?php echo $ppid_info->pasal_lain_uu; ?>
					</p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Membuka informasi tersebut dapat menimbulkan konsekuensi sebagai berikut',  'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->konsekuensi; ?>
					</p>
					
				</div>
			</div>
			<h5>Tanggapan Tertulis</h5>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Tanggal',  'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->tt_tgl; ?>
					</p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Nomor',  'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->tt_nomor; ?>
					</p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Lampiran',  'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->tt_lampiran; ?>
					</p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Perihal',  'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->tt_perihal; ?>
					</p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Isi Surat',  'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->tt_isi; ?>
					</p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pejabat penandatangan surat',  'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->tt_pejabat; ?>
					</p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Keputusan',  'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->keputusan; ?>
					</p>
					
				</div>
			</div>
			<?php if($ppid_info->keputusan == 'Dikabulkan sebagian' || $ppid_info->keputusan == 'Ditolak'):?>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Alasan Ditolak',  'alasan_ditolak', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->alasan_ditolak; ?>
					</p>
					
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if(!empty($ppid_info->alasan_keberatan)):?>
	<div class="row">
		
		<div class="col-sm-12 col-lg-6">
			<h5>Formulir Keberatan</h5>
			<div class="form-group form-group-sm row">
				<?php echo form_label('No. Registrasi Keberatan', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $item_info->trackid; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Nama Kuasa Pemohon', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->kuasa_nama; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Alamat Kuasa Pemohon', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->kuasa_alamat; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('No. Telp Kuasa Pemohon', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->kuasa_telp; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Email Kuasa Pemohon', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->kuasa_email; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php 
				
				$alasan_keberatan = $ppid_info->alasan_keberatan;
				$alasan_array = array();
				if(!empty($alasan_keberatan))
				{
					$tokens = explode(',', $alasan_keberatan);
					foreach($tokens as $obj)
					{
						$alasan_array[$obj] = $obj;
					}
					
				}
				
				$alasan_keberatan_array = array(
					'a' => 'a. Permohonan Informasi Ditolak',
					'b' => 'b. Informasi berkala tidak disediakan',
					'c' => 'c. Permintaan informasi tidak ditanggapi',
					'd' => 'd. Permintaan informasi ditanggapi tidak sebagaimana yang diminta',
					'e' => 'e. Permintaan informasi tidak dipenuhi',
					'f' => 'f. Biaya yang dikenakan tidak wajar',
					'g' => 'g. Informasi disampaikan melebihi jangka waktu yang ditentukan'
					
					
				);
				
							
				echo form_label('Alasan Pengajuan Keberatan', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<?php foreach($alasan_keberatan_array as $k => $v):?>
					<div class="form-check">
					  <input class="form-check-input" type="checkbox" <?php echo (array_key_exists($k, $alasan_array)?'checked':''); ?> name="alasan_keberatan[]" value="<?php echo $k;?>" id="defaultCheck7<?php echo $k; ?>">
					  <label class="form-check-label" for="defaultCheck1<?php echo $k; ?>">
						<?php echo $v; ?>
					  </label>
					</div>
					<?php endforeach; ?>
					
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Kasus Posisi',  'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->kasus_posisi; ?>
					</p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Tanggal Tanggapan Akan Diberikan',  'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->tgl_tanggapan; ?>
					</p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Petugas Penerima Informasi Keberatan',  'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext">
					<?php echo $ppid_info->nama_petugas; ?>
					</p>
					
				</div>
			</div>
			
		</div>
		<div class="col-sm-12 col-lg-6">
			<h5>Tanggapan Keberatan</h5>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Tanggal', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->keberatan_tgl; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Nomor', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->keberatan_no; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Lampiran', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->keberatan_lampiran; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Perihal', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->keberatan_perihal; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Isi Surat', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->keberatan_isi_surat; ?></p>
					
				</div>
			</div>
			<div class="form-group form-group-sm row">
				<?php echo form_label('Pejabat penandatangan surat', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
				<div class="col-sm-8">
					<p class="form-control-plaintext"><?php echo $ppid_info->keberatan_nama_pejabat; ?></p>
					
				</div>
			</div>
			
		</div>
		
	</div>
	<?php endif; ?>
	
</div>

