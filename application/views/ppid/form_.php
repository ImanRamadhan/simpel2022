<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Layanan</a></li>
					<li class="breadcrumb-item active">Layanan</li>
				</ol>
			</div>
			<h4 class="page-title">Tambah Data</h4>
		</div>
	</div>
</div>



<?php echo form_open($controller_name . '/save/' . $item_info->id, array('id'=>'ticket_form', 'class'=>'form-horizontal')); ?>
	
	
	<div class="row">
		<div class="col-lg-12">
			<ul id="error_message_box" class="error_message_box alert-danger"></ul>
			
			<div class="card">
				<div class="card-header bg-primary text-white">
					Tambah Data
				</div>
				<div class="card-body">
				
				<ul class="nav nav-tabs border-0" role="tablist">
					<li class="nav-item">
						<a class="nav-link active border rounded-lg border-bottom-0" data-toggle="tab" href="#pelapor" role="tab">Identitas Pelapor</a>
					</li>
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#produk" role="tab">Identitas Produk</a>
					</li>                                       
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#pengaduan" role="tab">Pengaduan</a>
					</li>
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#klasifikasi" role="tab">Klasifikasi</a>
					</li>
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#tindaklanjut" role="tab">Tindak Lanjut</a>
					</li>
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#jawaban" role="tab">Jawaban</a>
					</li>
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#tabppid" role="tab" id="tabppid_">Formulir PPID</a>
					</li>
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#tabppid2" role="tab" id="tabppid2_">Formulir Keberatan</a>
					</li>
				</ul>
				
				<div class="tab-content bg-white h-80">
					<div class="tab-pane active p-3 border rounded-lg" id="pelapor" role="tabpanel">
						<h4>Identitas Pelapor</h4>
						
						<div class="row">
							<div class="col-sm-12 col-lg-6">
						
							<div class="form-group form-group-sm row">
				
								<?php echo form_label('Nama <span class="text-danger">*</span>', 'iden_nama', array('class'=>'required col-form-label col-sm-3')); ?>
								
								<div class='col-sm-8'>
									<?php echo form_input(array(
										'name'=>'iden_nama',
										'id'=>'iden_nama',
										
										'class'=>'form-control input-sm',
										'value'=>$item_info->iden_nama)
										);?>
								</div>
							</div>
							
							<div class="form-group form-group-sm row">
								<?php echo form_label('Jenis Kelamin', 'iden_jk', array('class'=>'col-form-label col-sm-3')); ?>
								<div class='col-sm-8'>
									
									<?php echo form_dropdown('iden_jk', array('L' => 'Laki-laki', 'P' => 'Perempuan'), $item_info->iden_jk, 'class="form-control input-sm" id="iden_jk"');?>
								</div>
							</div>
							
							<div class="form-group form-group-sm row">
								<?php echo form_label('Instansi', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-8'>
									<?php echo form_input(array(
										'name'=>'iden_instansi',
										'id'=>'iden_instansi',
										
										'class'=>'form-control form-control-sm',
										'value'=>$item_info->iden_instansi)
										);?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Jenis Perusahaan', 'iden_jenis', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-8'>
									<?php echo form_input(array(
										'name'=>'iden_jenis',
										'id'=>'iden_jenis',
										
										'class'=>'form-control input-sm',
										'value'=>$item_info->iden_jenis)
										);?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Alamat <span class="text-danger">*</span>', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
								<div class="col-sm-8">
									<?php echo form_textarea(array(
									'class'=>'form-control input-sm required', 
									'name'=>'iden_alamat', 
									'id'=>'iden_alamat', 
									'rows'=>2,
									'value'=>$item_info->iden_alamat
									));?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Email', 'iden_email', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-8'>
									<?php echo form_input(array(
										'name'=>'iden_email',
										'id'=>'iden_email',
										
										'class'=>'form-control input-sm',
										'value'=>$item_info->iden_email)
										);?>
								</div>
							</div>
						
							</div>
							<div class="col-sm-12 col-lg-6">
								<div class="form-group form-group-sm row">
									<?php echo form_label('Negara', 'iden_negara', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-8'>
										<?php echo form_dropdown('iden_negara', array(), '', 'class="form-control input-sm" id="iden_negara" ');?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('Provinsi', 'iden_provinsi', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-8'>
										<?php echo form_dropdown('iden_provinsi', array(), '', 'class="form-control input-sm" id="iden_provinsi" ');?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('Kota/Kab', 'iden_kota', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-8'>
										<?php echo form_dropdown('iden_kota', array(), '', 'class="form-control input-sm" id="iden_kota" ');?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('No. Telp', 'iden_telp', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-3'>
										<?php echo form_input(array(
											'name'=>'iden_telp',
											'id'=>'iden_telp',
											
											'class'=>'form-control input-sm',
											'value'=>$item_info->iden_telp)
											);?>
									</div>
									<?php echo form_label('No. Fax', 'iden_fax', array('class'=>'required col-form-label col-sm-2')); ?>
									<div class='col-sm-3'>
										<?php echo form_input(array(
											'name'=>'iden_fax',
											'id'=>'iden_fax',
											
											'class'=>'form-control input-sm',
											'value'=>$item_info->iden_fax)
											);?>
									</div>
								</div>
								
								<div class="form-group form-group-sm row">
									<?php echo form_label('Pekerjaan', 'iden_profesi', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-5'>
										<?php echo form_dropdown('iden_profesi', array(), '', 'class="form-control input-sm" id="iden_profesi" ');?>
									</div>
									
								</div>
								
								<div class="form-group form-group-sm row">
									<?php echo form_label('Usia', 'usia', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-2'>
										<?php echo form_input(array(
											'name'=>'usia',
											'id'=>'usia',
											'class'=>'form-control input-sm',
											'value'=>$item_info->usia)
											);?>
									</div>
								</div>
								
							</div>
						</div>
						
					</div>
					<div class="tab-pane p-3 border rounded-lg" id="produk" role="tabpanel">
						<h4>Identitas Produk</h4>
						
						<div class="row">
							<div class="col-sm-12 col-lg-6">
						
							<div class="form-group form-group-sm row">
				
								<?php echo form_label('Nama Dagang', 'prod_nama', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-8'>
									<?php echo form_input(array(
										'name'=>'prod_nama',
										'id'=>'prod_nama',
										
										'class'=>'form-control input-sm',
										'value'=>$item_info->prod_nama)
										);?>
								</div>
							</div>
							
							
							
							<div class="form-group form-group-sm row">
								<?php echo form_label('Nama Generik', 'prod_generik', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-8'>
									<?php echo form_input(array(
										'name'=>'prod_generik',
										'id'=>'prod_generik',
										
										'class'=>'form-control form-control-sm',
										'value'=>$item_info->prod_generik)
										);?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Pabrik', 'prod_pabrik', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-8'>
									<?php echo form_input(array(
										'name'=>'prod_pabrik',
										'id'=>'prod_pabrik',
										
										'class'=>'form-control input-sm',
										'value'=>$item_info->prod_pabrik)
										);?>
								</div>
							</div>
							
							<div class="form-group form-group-sm row">
								<?php echo form_label('No. Reg', 'prod_noreg', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-8'>
									<?php echo form_input(array(
										'name'=>'prod_noreg',
										'id'=>'prod_noreg',
										
										'class'=>'form-control input-sm',
										'value'=>$item_info->prod_noreg)
										);?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('No. Batch', 'prod_nobatch', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-8'>
									<?php echo form_input(array(
										'name'=>'prod_nobatch',
										'id'=>'prod_nobatch',
										
										'class'=>'form-control input-sm',
										'value'=>$item_info->prod_nobatch)
										);?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Alamat', 'prod_alamat', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
								<div class="col-sm-8">
									<?php echo form_textarea(array(
									'class'=>'form-control input-sm', 
									'name'=>'prod_alamat', 
									'id'=>'prod_alamat', 
									'rows'=>2,
									'value'=>$item_info->prod_alamat
									));?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Kota', 'prod_kota', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-8'>
									<?php echo form_input(array(
										'name'=>'prod_kota',
										'id'=>'prod_kota',
										
										'class'=>'form-control input-sm',
										'value'=>$item_info->prod_kota)
										);?>
								</div>
							</div>
						
							</div>
							<div class="col-sm-12 col-lg-6">
								
								<div class="form-group form-group-sm row">
									<?php echo form_label('Negara', 'prod_negara', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-8'>
										<?php echo form_dropdown('prod_negara', array(), '', 'class="form-control input-sm" id="prod_negara" ');?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('Provinsi', 'prod_provinsi', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-8'>
										<?php echo form_dropdown('prod_provinsi', array(), '', 'class="form-control input-sm" id="prod_provinsi" ');?>
									</div>
								</div>
								
								<div class="form-group form-group-sm row">
									<?php echo form_label('Tgl Kadaluarsa', 'prod_kadaluarsa', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-3'>
										<?php echo form_input(array(
											'name'=>'prod_kadaluarsa',
											'id'=>'prod_kadaluarsa',
											
											'class'=>'form-control input-sm',
											'value'=>$item_info->prod_kadaluarsa)
											);?>
									</div>
									
								</div>
							
								<div class="form-group form-group-sm row">
									<?php echo form_label('Diperoleh di', 'prod_diperoleh', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-3'>
										<?php echo form_input(array(
											'name'=>'prod_diperoleh',
											'id'=>'prod_diperoleh',
											
											'class'=>'form-control input-sm',
											'value'=>$item_info->prod_diperoleh)
											);?>
									</div>
									
								</div>
								
								<div class="form-group form-group-sm row">
									<?php echo form_label('Tgl Diperoleh', 'prod_diperoleh_tgl', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-3'>
										<?php echo form_input(array(
											'name'=>'prod_diperoleh_tgl',
											'id'=>'prod_diperoleh_tgl',
											
											'class'=>'form-control input-sm',
											'value'=>$item_info->prod_diperoleh_tgl)
											);?>
									</div>
									
								</div>
								
								<div class="form-group form-group-sm row">
									<?php echo form_label('Tgl Digunakan', 'prod_digunakan_tgl', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-3'>
										<?php echo form_input(array(
											'name'=>'prod_digunakan_tgl',
											'id'=>'prod_digunakan_tgl',
											
											'class'=>'form-control input-sm',
											'value'=>$item_info->prod_digunakan_tgl)
											);?>
									</div>
									
								</div>
								
								
							</div>
						</div>
						
					</div>                                        
					<div class="tab-pane p-3 border rounded-lg" id="pengaduan" role="tabpanel">
						<h4>Pengaduan</h4>
						
						<div class="row">
							<div class="col-sm-12 col-lg-6">
							
							<div class="form-group form-group-sm row">
								<?php echo form_label('Isu Topik <span class="text-danger">*</span>', 'isu_topik', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
								<div class="col-sm-8">
									<?php echo form_textarea(array(
									'class'=>'form-control input-sm ', 
									'name'=>'isu_topik', 
									'id'=>'isu_topik', 
									'rows'=>2,
									'value'=>$item_info->isu_topik
									));?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Isi Pengaduan / Pertanyaan <span class="text-danger">*</span>', 'prod_masalah', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
								<div class="col-sm-8">
									<?php echo form_textarea(array(
									'class'=>'form-control input-sm', 
									'name'=>'prod_masalah', 
									'id'=>'prod_masalah', 
									'rows'=>4,
									'value'=>$item_info->prod_masalah
									));?>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Jenis', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
								<div class="col-sm-8">
									<div class="form-check-inline my-1">
										<div class="custom-control custom-radio">
											<input type="radio" id="customRadio7" name="info" class="custom-control-input">
											<label class="custom-control-label" for="customRadio7">Pengaduan</label>
										</div>
									</div>
									<div class="form-check-inline my-1">
										<div class="custom-control custom-radio">
											<input type="radio" id="customRadio8" name="info" class="custom-control-input">
											<label class="custom-control-label" for="customRadio8">Permintaan Informasi</label>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Tgl Pengaduan', 'tglpengaduan', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-8'>
								
									<?php if($_SESSION['city'] == 'PUSAT'): ?>
									<p class="form-control-plaintext"><?php echo date('d/m/Y'); ?></p>
									<?php else: ?>
								
									<?php echo form_input(array(
										'name'=>'tglpengaduan',
										'id'=>'tglpengaduan',
										
										'class'=>'form-control input-sm',
										'value'=>$item_info->tglpengaduan)
										);?>
									<?php endif; ?>
								</div>
								
							</div>
							<div class="form-group form-group-sm row">
								<?php echo form_label('Petugas Penerima Pengaduan', 'penerima', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-8'>
									<?php echo form_input(array(
										'name'=>'penerima',
										'id'=>'penerima',
										
										'class'=>'form-control input-sm',
										'value'=>$item_info->penerima)
										);?>
								</div>
								
							</div>
							
						
							</div>
							<div class="col-sm-12 col-lg-6">
								<div class="form-group form-group-sm row">
									<?php echo form_label('Attachment', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-8'>
										
									</div>
								</div>
								
								
								
							</div>
						</div>
					</div>
					<div class="tab-pane p-3 border rounded-lg" id="klasifikasi" role="tabpanel">
						<h4>Klasifikasi</h4>
						
						<div class="row">
							<div class="col-sm-12 col-lg-6">
							
								<div class="form-group form-group-sm row">
									<?php echo form_label('Jenis Produk', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-4'>
										<?php echo form_dropdown('kategori', array(), '', 'class="form-control input-sm" id="kategori" ');?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('Pegaduan melalui', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-4'>
										<?php echo form_dropdown('submited_via', array(), '', 'class="form-control input-sm" id="submited_via" ');?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('Sumber Data', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-4'>
										<?php echo form_dropdown('jenis', array(), '', 'class="form-control input-sm" id="jenis" ');?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('Shift', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-4'>
										<?php echo form_dropdown('shift', array(), '', 'class="form-control input-sm" id="shift" ');?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('Klasifikasi', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-4'>
										<?php echo form_dropdown('klasifikasi', array(), '', 'class="form-control input-sm" id="klasifikasi" ');?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('Sub Klasifikasi', 'iden_instansi', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-4'>
										<?php echo form_dropdown('subklasifikasi', array(), '', 'class="form-control input-sm" id="subklasifikasi" ');?>
									</div>
								</div>
						
							</div>
							
						</div>
					</div>
					<div class="tab-pane p-3 border rounded-lg" id="tindaklanjut" role="tabpanel">
						<h4>Tindak Lanjut</h4>
						
						<div class="row">
							<div class="col-sm-12 col-lg-6">
								<div class="form-group form-group-sm row">
									<?php echo form_label('Perlu Rujuk', 'perlu_rujuk_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
									<div class="col-sm-8">
										<div class="form-check-inline my-1">
											<div class="custom-control custom-radio">
												<input type="radio" id="customRadio9" name="is_rujuk" class="custom-control-input">
												<label class="custom-control-label" for="customRadio9">Ya</label>
											</div>
										</div>
										<div class="form-check-inline my-1">
											<div class="custom-control custom-radio">
												<input type="radio" id="customRadio10" name="is_rujuk" class="custom-control-input">
												<label class="custom-control-label" for="customRadio10">Tidak</label>
											</div>
										</div>
									</div>
								</div>
								
								
								
							</div>
						</div>
					</div>
					<div class="tab-pane p-3 border rounded-lg" id="jawaban" role="tabpanel">
						<h4>Jawaban</h4>
						<div class="row">
							<div class="col-sm-12 col-lg-6">
								<div class="form-group form-group-sm row">
									<?php echo form_label('Jawaban', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
									<div class="col-sm-8">
										<?php echo form_textarea(array(
										'class'=>'form-control input-sm', 
										'name'=>'jawaban', 
										'id'=>'jawaban', 
										'rows'=>3,
										'value'=>$item_info->jawaban
										));?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('Keterangan', 'alamat_label', array('for'=>'message', 'class'=>'col-form-label col-sm-3 required')); ?>
									<div class="col-sm-8">
										<?php echo form_textarea(array(
										'class'=>'form-control input-sm', 
										'name'=>'keterangan', 
										'id'=>'keterangan', 
										'rows'=>3,
										'value'=>$item_info->keterangan
										));?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('Nama Petugas Input', 'petugas_entry', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-4'>
										<?php echo form_input(array(
											'name'=>'petugas_entry',
											'id'=>'petugas_entry',
											
											'class'=>'form-control input-sm',
											'value'=>$item_info->petugas_entry)
											);?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('Nama Penjawab', 'penjawab', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-4'>
										<?php echo form_input(array(
											'name'=>'penjawab',
											'id'=>'penjawab',
											
											'class'=>'form-control input-sm',
											'value'=>$item_info->penjawab)
											);?>
									</div>
								</div>
								<div class="form-group form-group-sm row">
									<?php echo form_label('Dijawab melalui', 'answered_via', array('class'=>'required col-form-label col-sm-3')); ?>
									<div class='col-sm-4'>
										<?php echo form_dropdown('answered_via', array(), '', 'class="form-control input-sm" id="answered_via" ');?>
									</div>
								</div>
								
								
								
							</div>
						</div>
					</div>
				</div>
				
				</div>
				<div class="card-footer">
					<div class="text-center">
						<button class="btn btn-primary" id="save"><i class="fa fa-save" aria-hidden="true"></i> Simpan sbg Draft</button>
						<button class="btn btn-info" id="submit"><i class="fa fa-send" aria-hidden="true"></i> Kirim</button>
						<button class="btn btn-light"></i> Kembali</button>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php echo form_close(); ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	$('#ticket_form').validate($.extend({
		submitHandler: function(form)
		{
			$(form).ajaxSubmit({
				beforeSubmit: function() {
					$('#submit').attr('disabled',true);
					//$('#submit').html("<i class='fa fa-spinner fa-spin'></i> Processing...");
				},
				success: function(response)
				{				
					setTimeout(function(){window.location.href = "<?php echo site_url('tickets/list_all'); ?>";}, 1000);
				},
				dataType: 'json'
			});
		},

		rules:
		{
			iden_nama: "required",
			iden_alamat: "required",
			isu_topik: "required",
			prod_masalah: "required",
   		},

		messages: 
		{
     		iden_nama: "Nama Pelapor harus diisi",
     		iden_alamat: "Alamat Pelapor harus diisi",
			isu_topik: "Isu Topik harus diisi",
			prod_masalah: "Isi Pengaduan/Pertanyaan harus diisi",
     		
		},
		ignore: "",
		
	}, form_support.error));
});

function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode != 46 && charCode > 31 &&
		(charCode < 48 || charCode > 57))
		return false;
	return true;
}

</script>
<?php $this->load->view("partial/footer"); ?>