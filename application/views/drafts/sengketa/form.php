<?php $this->load->view("partial/header"); ?>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/dropzone.css" />
<script src="<?php echo base_url();?>assets/plugins/dropzone/dist/min/dropzone.min.js"></script>
<script src="https://momentjs.com/downloads/moment.js" ></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>
<script>
Dropzone.autoDiscover = false;
</script>
<style>
	.dropzone .dz-preview .dz-download {
      font-size: 10px;
      text-align: center;
      display: block;
      cursor: pointer;
      border: none; }
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Drafts</a></li>
					<li class="breadcrumb-item active"><?php echo $page_title; ?></li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $page_title; /*echo $this->session->city; */?></h4>
		</div>
	</div>
</div>

<?php echo form_open($controller_name . '/save_sengketa/' . $item_info->id, array('id'=>'ticket_form', 'class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
	<div class="row">
		<div class="col-lg-12">
			<div class="error_message_box">
				<ul id="error_message_box" class="error_message_box alert-danger">
					
				</ul>
			</div>
			<div class="card">
				<div class="card-header bg-primary text-white">
					<?php echo $page_title; ?>
				</div>
				<div class="card-body">
				<ul class="nav nav-tabs border-0" role="tablist">
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0 active" data-toggle="tab" href="#tabpelapor" role="tab">Identitas Pelapor</a>
					</li>
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#tabpermohonan" role="tab">Permohonan</a>
					</li>                                       
					
					
				</ul>
				
				<div class="tab-content bg-white h-80">
					<div class="tab-pane active p-3 border rounded-lg" id="tabpelapor" role="tabpanel">
						<?php $this->load->view('drafts/sengketa/form_identitas_pelapor'); ?>
					</div>
					<div class="tab-pane p-3 border rounded-lg" id="tabpermohonan" role="tabpanel">
						<?php $this->load->view('drafts/sengketa/form_permohonan'); ?>
					</div>                                        
					
					
				</div>
				
				</div>
				<div class="card-footer">
					<div class="text-center">
						<input type="hidden" name="send" id="send" value="0" />
						<input type="hidden" name="draftid" id="draftid" value="<?php echo !empty($item_info->id)?$item_info->id:0; ?>" />
						<a class="btn btn-primary text-white" id="save" title="Simpan sebagai draft"><i class="fa fa-save" aria-hidden="true"></i> <?php echo ($mode == 'ADD')?'Simpan Sebagai Draft':'Simpan'?></a>
						<button class="btn btn-success text-white" title="Simpan dan Kirim Data" id="submit"><span class="fa fa-send" aria-hidden="true"></span> Kirim</button>
						<a title="Kembali" class="btn btn-light" href="<?php echo $back_url;?>"></i> Kembali</a>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php echo form_close(); ?>

<script type="text/javascript">
dialog_support.init("button.modal-dlg");



//validation and submit handling
$(document).ready(function()
{
	$('#iden_negara').select2({theme: "bootstrap4"});
	$('#iden_provinsi').select2({theme: "bootstrap4"});
	$('#iden_kota').select2({theme: "bootstrap4"});
	//$('#iden_profesi').select2({theme: "bootstrap4"});
	
	$('#prod_negara').select2({theme: "bootstrap4"});
	$('#prod_provinsi').select2({theme: "bootstrap4"});
	//$('#kategori').select2({theme: "bootstrap4"});
	
	$.extend( $.fn.datepicker.defaults, {format: 'dd/mm/yyyy', language: 'id', daysOfWeekHighlighted: [0,6], todayHighlight: true, weekStart: 1} );
	
	$('#waktu').datetimepicker({
		format: 'HH:mm:ss'
	});
	
	<?php if($this->session->city != 'PUSAT'):?>
		$('#tglpengaduan').datepicker();
	<?php endif;?>
	
	$("#prod_digunakan_tgl").datepicker();
	$("#prod_kadaluarsa").datepicker();
	$("#prod_diperoleh_tgl").datepicker();
	
	$('#tgl_diterima').datepicker();
	$('#tgl_tanggapan').datepicker();
	$('#tt_tgl').datepicker();
	$('#keberatan_tgl').datepicker();
	$('#tgl_pemberitahuan_tertulis').datepicker();
	
	$('#iden_provinsi2').hide();
	$('#iden_kota2').hide();
	$('#prod_provinsi2').hide();
	
	<?php if(!$item_info->is_rujuk):?>
	/*$('#dir1').hide();$('#sla1').hide();
	$('#dir2').hide();$('#sla2').hide();
	$('#dir3').hide();$('#sla3').hide();
	$('#dir4').hide();$('#sla4').hide();
	$('#dir5').hide();$('#sla5').hide();*/
	$('#list_rujukan').hide();
	<?php endif; ?>
	
	
	
	$('#iden_negara').on('change', function(){
		var negara = $(this).val();
		//alert(negara);
		if(negara.toLowerCase() != 'indonesia')
		{
			$('#iden_provinsi').select2().next().hide();
			$('#iden_provinsi2').show();
			$('#iden_kota').select2().next().hide();
			$('#iden_kota2').show();
			
			//$('#iden_provinsi').hide();
			//$('#iden_provinsi2').show();
			//$('#iden_kota').hide();
			//$('#iden_kota2').show();
		}
		else
		{
			$('#iden_provinsi2').hide();
			//$('#iden_provinsi').show();
			$('#iden_provinsi').select2().next().show();
			$('#iden_kota2').hide();
			//$('#iden_kota').show();
			$('#iden_kota').select2().next().show();
		}
	});
	
	
	
	$('#iden_provinsi').on('change', function(){
		
		$.getJSON("<?php echo site_url('tickets/get_kab/'); ?>" + $(this).val(), function(data) {
			if(data)
			{
				$('#iden_kota').empty();
				$.each(data, function (key, entry) {
					$('#iden_kota').append($('<option></option>').attr('value', entry.nama).text(entry.nama));
				  })
			}
		});
	});
	
	
	
	
	$('#iden_nama').focus();

	
	
	jQuery.validator.addMethod("validdate", function(value, element) {
		var dt = moment(value,'DD/MM/YYYY');
		
		var day = moment().format('D');
		if(day <=10)
			//if(day == 19)
		{
			
			var firstdaylastmonth = moment().subtract(1, 'months').startOf('month');
			//console.log(`${dt.format('ll')} is before ${firstdaylastmonth.format('ll')}`);
			
			if(dt.isBefore(firstdaylastmonth))
				return false;
			
				
		}
		else
		{
			var firstdaymonth = moment().startOf('month');
			//console.log(`${dt.format('ll')} is before ${firstdaymonth.format('ll')}`);
			
			if(dt.isBefore(firstdaymonth))
				return false;
		}
		
		return true;
		
	}, "* Tanggal pengaduan di bulan lalu dapat ditambahkan sebelum tanggal 10 setiap bulannya");
	
	$('#ticket_form').validate($.extend({
			submitHandler: function(form)
			{	
				/*$(form).ajaxSubmit({
					beforeSubmit: function() {
						
					},
					success: function(response)
					{				
						$.notify(response.message, { type: response.success ? 'success' : 'danger' });
						setTimeout(function(){window.location.href = "<?php echo site_url('drafts'); ?>";}, 1000);
					},
					dataType: 'json'
				});*/
			},

			rules:
			{
				iden_nama: "required",
				iden_alamat: "required",
				iden_profesi: "required",
				//iden_email: "required",
				sengketa_perihal: "required",
				sengketa_alasan: "required"
				
			},

			messages: 
			{
				iden_nama: "Nama Pelapor harus diisi",
				iden_alamat: "Alamat Pelapor harus diisi",
				iden_profesi: "Pekerjaan harus diisi",
				sengketa_perihal: "Hal yang dimohonkan harus diisi",
				sengketa_alasan: "Alasan Pengajuan Permohonan harus diisi"
				
				
			},
			ignore: "",
			
		}, form_support.error));
	
	//$('#submit').prop('disabled',true);
	var dropzone1;
	
	$('#save').on('click', function(e){
		e.preventDefault();
		//alert('save as draft');
		
		
		
		
		
		$.ajax({  
			 url: $('#ticket_form').attr('action'),  
			 method:"POST",  
			 data: $('#ticket_form').serialize(), 
			 dataType: 'json',
			 enctype: 'multipart/form-data',
			 cache: false,
			 beforeSend:function(){  
				  //$('#response').html('<span class="text-info">Loading response...</span>');  
			 },  
			 success:function(response){  
				//console.log(response);
				$.notify( response.message, { type: response.success ? 'success' : 'danger' });
				
				if(response.id != -1)
				{
					$('#draftid').val(response.id);
					/*var myDropzone = Dropzone.forElement("#dragndrop");
					console.log("send " + myDropzone.getQueuedFiles().length + " files");
					myDropzone.processQueue();
					
					var myDropzone2 = Dropzone.forElement("#dragndrop2");
					console.log("send " + myDropzone2.getQueuedFiles().length + " files");
					myDropzone2.processQueue();*/
					
					setTimeout(function(){window.location.href = "<?php echo site_url('drafts/edit/'); ?>" + response.id;}, 1000);
				}
				
				
			 }  
		});  
		
	});
	
	
	$('#submit').on('click', function(e){
		e.preventDefault();
		if($('#ticket_form').valid())
		{
			
			//$('#submit').prop('disabled',true);
			$('#send').val(1);
			//save first
			$.ajax({  
				 url: $('#ticket_form').attr('action'),  
				 method:"POST",  
				 data: $('#ticket_form').serialize(), 
				 dataType: 'json',
				 async: false,
				 enctype: 'multipart/form-data',
				 cache: false,
				 beforeSend:function(){  
					  //$('#response').html('<span class="text-info">Loading response...</span>');  
				 },  
				 success:function(response){  
					//console.log(response);
					//$.notify( response.message, { type: response.success ? 'success' : 'danger' });
					
					if(response.id != -1)
					{
						$('#draftid').val(response.id);
						/*var myDropzone = Dropzone.forElement("#dragndrop");
						
						myDropzone.on("successmultiple", function(file, response) {
							//console.log('success');
							
						});
						console.log("send " + myDropzone.getQueuedFiles().length + " files");
						if (myDropzone.getQueuedFiles().length > 0) {
							myDropzone.processQueue();
						}
												
						var myDropzone2 = Dropzone.forElement("#dragndrop2");
						myDropzone2.on("successmultiple", function(file, response) {
							//console.log('success');
							
						});
						console.log("send " + myDropzone2.getQueuedFiles().length + " files");
						
						if (myDropzone2.getQueuedFiles().length > 0) {
							myDropzone2.processQueue();
						}*/
						
						BootstrapDialog.confirm('Apakah Anda yakin untuk mengirim layanan ini?', function(result){
							if(result)
							{
								$.ajax({  
									 url: '<?php echo site_url('drafts/send_ticket/');?>' + response.id,  
									 method:"POST",  
									 data: $('#ticket_form').serialize(), 
									 dataType: 'json',
									 success:function(data){
										$.notify(data.message, { type: data.success ? 'success' : 'danger' });
										setTimeout(function(){window.location.href = "<?php echo site_url('tickets/view/'); ?>" + data.id;}, 1000);
									 }
								});
							}
							else
							{
								setTimeout(function(){window.location.href = "<?php echo site_url('drafts/edit/'); ?>" + response.id;}, 1000);
							}
						});
							
							
						
						
						
					}
					
					
				 }  
			});
			
			
		}
	});
	
	
	
});
</script>
<?php $this->load->view("partial/footer"); ?>