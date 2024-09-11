<style>
.form-check-label {
	font-weight: normal;
}
</style>
<h4>Formulir Permintaan Informasi Publik</h4>
<div class="row">
	<div class="col-sm-12 col-lg-6">
		<div class="form-group form-group-sm row">
			<?php echo form_label('Diterima Tanggal <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-4">
				<div class="input-group">
					<?php echo form_input(array(
					'class'=>'form-control form-control-sm', 
					'name'=>'tgl_diterima', 
					'id'=>'tgl_diterima', 
					'value'=>(($ppid_info->tgl_diterima_fmt != '00/00/0000')?$ppid_info->tgl_diterima_fmt:'')
					));?>
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Melalui <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'diterima_via', 
				'id'=>'diterima_via', 
				'value'=>$ppid_info->diterima_via
				));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('No. KTP <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm',
				'onkeypress'=>'return isNumberKey(event)', 
				'name'=>'no_ktp', 
				'id'=>'no_ktp', 
				'value'=>$ppid_info->no_ktp
				));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Rincian Informasi yang dibutuhkan <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_textarea(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'rincian', 
				'id'=>'rincian', 
				'rows'=>3,
				'value'=>$ppid_info->rincian
				));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Tujuan Penggunaan Informasi <span class="text-danger">*</span>', 'tujuan', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_textarea(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'tujuan', 
				'id'=>'tujuan', 
				'rows'=>3,
				'value'=>$ppid_info->tujuan
				));?>
			</div>
		</div>
		
		<div class="form-group form-group-sm row">
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
			
			
			
			echo form_label('Cara memperoleh informasi', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
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
			
			
			echo form_label('Cara mendapatkan salinan informasi', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
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
		<h4>Pemberitahuan Tertulis</h4>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Tanggal <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-4">
				<div class="input-group">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'tgl_pemberitahuan_tertulis', 
				'id'=>'tgl_pemberitahuan_tertulis', 
				'value'=>(($ppid_info->tgl_pemberitahuan_tertulis_fmt != '00/00/0000')?$ppid_info->tgl_pemberitahuan_tertulis_fmt:'')
				));?>
				<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Penguasaan Informasi Publik', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-9">
				<div class="form-check">
				  <input class="form-check-input" type="checkbox" name="penguasaan_kami" <?php echo ($ppid_info->penguasaan_kami?'checked':''); ?> value="1" id="defaultCheck11">
				  <label class="form-check-label" for="defaultCheck11">
					Kami
				  </label>
				  <?php echo form_textarea(array(
					'class'=>'form-control form-control-sm', 
					'name'=>'penguasaan_kami_teks', 
					'id'=>'penguasaan_kami_teks', 
					'rows'=>3,
					'value'=>$ppid_info->penguasaan_kami_teks
					));?>
				  
				</div>
				<div class="form-check">
				  <input class="form-check-input" type="checkbox" name="penguasaan_badan_lain" <?php echo ($ppid_info->penguasaan_badan_lain?'checked':''); ?> value="1" id="defaultCheck12">
				  <label class="form-check-label" for="defaultCheck12">
					Badan Publik Lain, yaitu
				  </label>
				  <div class="">
						<?php echo form_input(array(
						'class'=>'form-control form-control-sm', 
						'name'=>'nama_badan_lain', 
						'id'=>'nama_badan_lain', 
						'value'=>$ppid_info->nama_badan_lain
						));?>
					</div>
				</div>
				
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
			<?php echo form_label('Biaya Yang Dibutuhkan', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-4">
				<div class="form-check">
				  <input class="form-check-input" type="checkbox" name="penyalinan" <?php echo ($ppid_info->penyalinan?'checked':''); ?> value="1" id="defaultCheck41">
				  <label class="form-check-label" for="defaultCheck41">
					Penyalinan
				  </label>
				  <div class="input-group mb-3">
					<div class="input-group-prepend ">
						<span class="input-group-text form-control-sm">Rp.</span>
					</div>
					<?php echo form_input(array(
						'class'=>'form-control form-control-sm', 
						'name'=>'biaya_penyalinan', 
						'id'=>'biaya_penyalinan', 
						'value'=>$ppid_info->biaya_penyalinan
						));?>
					
				  </div>
					
				</div>
				
			</div>
			<div class="col-sm-3">
				<label class="form-check-label" > Jml Lembaran</label>
					<?php echo form_input(array(
					'class'=>'form-control form-control-sm', 
					'name'=>'biaya_penyalinan_lbr', 
					'id'=>'biaya_penyalinan_lbr', 
					'value'=>$ppid_info->biaya_penyalinan_lbr
					));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-4">
				<div class="form-check">
				  <input class="form-check-input" type="checkbox" name="pengiriman" <?php echo ($ppid_info->pengiriman?'checked':''); ?> value="1" id="defaultCheck41">
				  <label class="form-check-label" for="defaultCheck41">
					Pengiriman
				  </label>
					<div class="input-group mb-3">
						<div class="input-group-prepend ">
							<span class="input-group-text form-control-sm">Rp.</span>
						</div>
						<?php echo form_input(array(
							'class'=>'form-control form-control-sm', 
							'name'=>'biaya_pengiriman', 
							'id'=>'biaya_pengiriman', 
							'value'=>$ppid_info->biaya_pengiriman
							));?>
					</div>
					
					
				</div>
				
			</div>
			<div class="col-sm-4">
				<div class="form-check">
				  <input class="form-check-input" type="checkbox" name="lain_lain" <?php echo ($ppid_info->lain_lain?'checked':''); ?> value="1" id="defaultCheck41">
				  <label class="form-check-label" for="defaultCheck41">
					Lain-lain
				  </label>
					<div class="input-group mb-3">
					<div class="input-group-prepend ">
						<span class="input-group-text form-control-sm">Rp.</span>
					</div>
					<?php echo form_input(array(
						'class'=>'form-control form-control-sm', 
						'name'=>'biaya_lain', 
						'id'=>'biaya_lain', 
						'value'=>$ppid_info->biaya_lain
						));?>
					
				  </div>
					
				</div>
				
			</div>
		
		</div>
		
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Waktu Penyediaan <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-4">
				<div class="input-group mb-2">
					
					<?php echo form_input(array(
						'class'=>'form-control form-control-sm', 
						'name'=>'waktu_penyediaan', 
						'id'=>'waktu_penyediaan', 
						//'type' => 'number',
						'value'=>$ppid_info->waktu_penyediaan
						));?>
					<div class="input-group-append ">
						<span class="input-group-text form-control-sm">Hari kerja</span>
					</div>
				  </div>
			
				
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Penjelasan penghitaman / pengaburan Informasi yang dimohon', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_textarea(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'penghitaman', 
				'id'=>'penghitaman', 
				'rows'=>5,
				'value'=>$ppid_info->penghitaman
				));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Informasi Tidak Dapat Diberikan karena', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
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
			<?php echo form_label('Penyediaan informasi yang belum didokumentasikan dilakukan dalam jangka waktu', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-4">
				<div class="input-group mb-2">
					
					<?php echo form_input(array(
						'class'=>'form-control form-control-sm', 
						'name'=>'waktu_penyediaan2', 
						'id'=>'waktu_penyediaan2', 
						//'type' => 'number',
						'value'=>$ppid_info->waktu_penyediaan2
						));?>
					<div class="input-group-append ">
						<span class="input-group-text form-control-sm">Hari kerja</span>
					</div>
				  </div>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Nama Pejabat PPID <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'nama_pejabat_ppid', 
				'id'=>'nama_pejabat_ppid', 
				'value'=>$ppid_info->nama_pejabat_ppid
				));?>
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-lg-6">
		<h4>Penolakan</h4>
		
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Pengecualian Informasi didasarkan pada alasan', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<div class="form-check">
				  <input class="form-check-input" type="checkbox" name="pengecualian_pasal17" <?php echo ($ppid_info->pengecualian_pasal17?'checked':''); ?> value="1" id="defaultCheck41">
				  <label class="form-check-label" for="defaultCheck41">
					Pasal 17 huruf
				  </label>
				  <input type="text" class="" size="5" name="pasal17_huruf" value="<?php echo $ppid_info->pasal17_huruf?>" />
				  <label class="form-check-label" for="defaultCheck41">
					UU KIP
				  </label>
				</div>
				<div class="form-check">
				  <input class="form-check-input" type="checkbox" name="pengecualian_pasal_lain" <?php echo ($ppid_info->pengecualian_pasal_lain?'checked':''); ?>  value="1" id="defaultCheck42">
				 <?php echo form_textarea(array(
					'class'=>'form-control form-control-sm', 
					'name'=>'pasal_lain_uu', 
					'id'=>'pasal_lain_uu', 
					'rows'=>3,
					'value'=>$ppid_info->pasal_lain_uu
					));?>
					
				  
				</div>
				
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Membuka informasi tersebut dapat menimbulkan konsekuensi sebagai berikut', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_textarea(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'konsekuensi', 
				'id'=>'konsekuensi', 
				'rows'=>6,
				'value'=>$ppid_info->konsekuensi
				));?>
			</div>
		</div>
		<h4>Tanggapan Tertulis</h4>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Tanggal <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-4">
				<div class="input-group">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'tt_tgl', 
				'id'=>'tt_tgl', 
				'value'=>(($ppid_info->tt_tgl_fmt != '00/00/0000')?$ppid_info->tt_tgl_fmt:'')
				));?>
				<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Nomor <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'tt_nomor', 
				'id'=>'tt_nomor', 
				'value'=>$ppid_info->tt_nomor
				));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Lampiran <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'tt_lampiran', 
				'id'=>'tt_lampiran', 
				'value'=>$ppid_info->tt_lampiran
				));?>
			</div>
		</div>
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Perihal <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_textarea(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'tt_perihal', 
				'id'=>'tt_perihal', 
				'rows'=>4,
				'value'=>$ppid_info->tt_perihal
				));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Isi Surat <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_textarea(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'tt_isi', 
				'id'=>'tt_isi', 
				'rows'=>8,
				'value'=>$ppid_info->tt_isi
				));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Pejabat penandatangan surat <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'tt_pejabat', 
				'id'=>'tt_pejabat', 
				'value'=>$ppid_info->tt_pejabat
				));?>
			</div>
		</div>
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Keputusan <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-5">
				<?php echo form_dropdown('keputusan', array('' => '', 'Dikabulkan sepenuhnya' => 'Dikabulkan sepenuhnya', 'Dikabulkan sebagian' => 'Dikabulkan sebagian', 'Ditolak' => 'Ditolak'), $ppid_info->keputusan, 'class="form-control form-control-sm" id="keputusan"');?>
			</div>
		</div>
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Alasan Ditolak', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-5">
				<?php echo form_dropdown('alasan_ditolak', array('' => '', 'Tidak Dikuasai' => 'Tidak Dikuasai', 'Belum Didokumentasikan' => 'Belum Didokumentasikan', 'Dikecualikan' => 'Dikecualikan'), $ppid_info->alasan_ditolak, 'class="form-control form-control-sm" id="alasan_ditolak"');?>
			</div>
		</div>
		
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	<?php if($mode == 'ADD' || $ppid_info->keputusan == ''||$ppid_info->keputusan == 'Dikabulkan sepenuhnya'): ?>
		$('#alasan_ditolak').prop('disabled', true);
	<?php endif; ?>
	
	$('#keputusan').on('change',function(){
		var val = $(this).val();
		if(val == 'Dikabulkan sebagian' || val == 'Ditolak')
		{
			$('#alasan_ditolak').prop('disabled', false);
		}
		else
		{
			$('#alasan_ditolak').val("");
			$('#alasan_ditolak').prop('disabled', true);
		}
	});
});
</script>