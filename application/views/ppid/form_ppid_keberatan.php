<style>
.form-check-label {
	font-weight: normal;
}
</style>
<h4>Formulir Pengajuan Keberatan</h4>
<div class="row">
	<div class="col-sm-12 col-lg-6">
		<!--<div class="form-group form-group-sm row">
			<?php echo form_label('No. Registrasi Keberatan <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'no_reg_keberatan', 
				'id'=>'no_reg_keberatan', 
				'value'=>$ppid_info->no_reg_keberatan
				));?>
			</div>
		</div>-->
		<div class="form-group form-group-sm row">
			<?php echo form_label('Nama Kuasa Pemohon <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'kuasa_nama', 
				'id'=>'kuasa_nama', 
				'value'=>$ppid_info->kuasa_nama
				));?>
			</div>
		</div>
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Alamat Kuasa Pemohon <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_textarea(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'kuasa_alamat', 
				'id'=>'kuasa_alamat', 
				'rows'=>3,
				'value'=>$ppid_info->kuasa_alamat
				));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('No. Telp Kuasa Pemohon <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'kuasa_telp', 
				'id'=>'kuasa_telp', 
				'value'=>$ppid_info->kuasa_telp
				));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Email Kuasa Pemohon <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'kuasa_email', 
				'id'=>'kuasa_email', 
				'value'=>$ppid_info->kuasa_email
				));?>
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
			
						
			echo form_label('Alasan Pengajuan Keberatan <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
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
			<?php echo form_label('Kasus Posisi <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_textarea(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'kasus_posisi', 
				'id'=>'kasus_posisi', 
				'rows'=>5,
				'value'=>$ppid_info->kasus_posisi
				));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Tanggal Tanggapan Akan Diberikan <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-4">
				<div class="input-group">
					<?php echo form_input(array(
					'class'=>'form-control form-control-sm', 
					'name'=>'tgl_tanggapan', 
					'id'=>'tgl_tanggapan', 
					'value'=>(($ppid_info->tgl_tanggapan_fmt != '00/00/0000')?$ppid_info->tgl_tanggapan_fmt:'')
					));?>
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Petugas Penerima Informasi Keberatan <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'nama_petugas', 
				'id'=>'nama_petugas', 
				'value'=>$ppid_info->nama_petugas
				));?>
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-lg-6">
		<h4>Tanggapan Keberatan</h4>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Tanggal <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-4">
				<div class="input-group">
					<?php echo form_input(array(
					'class'=>'form-control form-control-sm', 
					'name'=>'keberatan_tgl', 
					'id'=>'keberatan_tgl', 
					'value'=>(($ppid_info->keberatan_tgl_fmt != '00/00/0000')?$ppid_info->keberatan_tgl_fmt:'')
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
				'name'=>'keberatan_no', 
				'id'=>'keberatan_no', 
				'value'=>$ppid_info->keberatan_no
				));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Lampiran <span class="text-danger"></span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'keberatan_lampiran', 
				'id'=>'keberatan_lampiran', 
				'value'=>$ppid_info->keberatan_lampiran
				));?>
			</div>
		</div>
		
		<div class="form-group form-group-sm row">
			<?php echo form_label('Perihal <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_textarea(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'keberatan_perihal', 
				'id'=>'keberatan_perihal', 
				'rows'=>4,
				'value'=>(empty($ppid_info->keberatan_perihal)?'Tanggapan Pengajuan Keberatan':'')
				));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Isi Surat <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_textarea(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'keberatan_isi_surat', 
				'id'=>'keberatan_isi_surat', 
				'rows'=>8,
				'value'=>$ppid_info->keberatan_isi_surat
				));?>
			</div>
		</div>
		<div class="form-group form-group-sm row">
			<?php echo form_label('Pejabat penandatangan surat <span class="text-danger">*</span>', 'rincian', array('for'=>'message', 'class'=>'col-form-label col-sm-3')); ?>
			<div class="col-sm-8">
				<?php echo form_input(array(
				'class'=>'form-control form-control-sm', 
				'name'=>'keberatan_nama_pejabat', 
				'id'=>'keberatan_nama_pejabat', 
				'value'=>$ppid_info->keberatan_nama_pejabat
				));?>
			</div>
		</div>
		
		
	</div>
</div>
