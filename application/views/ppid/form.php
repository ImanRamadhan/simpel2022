<?php $this->load->view("partial/header"); ?>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/dropzone.css" />
<script src="<?php echo base_url();?>assets/plugins/dropzone/dist/min/dropzone.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>
<script>
Dropzone.autoDiscover = false;
</script>
<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Layanan</a></li>
					<li class="breadcrumb-item active"><?php echo $page_title; ?></li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $page_title; ?></h4>
		</div>
	</div>
</div>

<?php echo form_open($controller_name . '/save/' . $item_info->id, array('id'=>'ticket_form', 'class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
	<div class="row">
		<div class="col-lg-12">
			<ul id="error_message_box" class="error_message_box card alert-danger"></ul>
			<div class="card">
				<div class="card-header bg-primary text-white">
					Ubah Data 
				</div>
				<div class="card-body">
				<ul class="nav nav-tabs border-0" role="tablist">
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0 active" data-toggle="tab" href="#tabpelapor" role="tab">Identitas Pelapor</a>
					</li>
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#tabproduk" role="tab">Identitas Produk</a>
					</li>                                       
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#tabpengaduan" role="tab">Layanan</a>
					</li>
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#tabklasifikasi" role="tab">Klasifikasi</a>
					</li>
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#tabtindaklanjut" role="tab">Tindak Lanjut</a>
					</li>
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#tabjawaban" role="tab">Jawaban</a>
					</li>
					<li class="nav-item">
						<a class="nav-link border rounded-lg border-bottom-0" data-toggle="tab" href="#tabppid" role="tab" id="tabppid_">Formulir PPID</a>
					</li>
					
				</ul>
				
				<div class="tab-content bg-white h-80">
					<div class="tab-pane active p-3 border rounded-lg" id="tabpelapor" role="tabpanel">
						<?php $this->load->view('tickets/form_identitas_pelapor'); ?>
					</div>
					<div class="tab-pane p-3 border rounded-lg" id="tabproduk" role="tabpanel">
						<?php $this->load->view('tickets/form_identitas_produk'); ?>
					</div>                                        
					<div class="tab-pane p-3 border rounded-lg" id="tabpengaduan" role="tabpanel">
						<?php $this->load->view('tickets/form_pengaduan'); ?>
					</div>
					<div class="tab-pane p-3 border rounded-lg" id="tabklasifikasi" role="tabpanel">
						<?php $this->load->view('tickets/form_klasifikasi'); ?>
					</div>
					<div class="tab-pane p-3 border rounded-lg" id="tabtindaklanjut" role="tabpanel">
						<?php $this->load->view('tickets/form_tindaklanjut'); ?>
					</div>
					<div class="tab-pane p-3 border rounded-lg" id="tabjawaban" role="tabpanel">
						<?php $this->load->view('tickets/form_jawaban'); ?>
					</div>
					<div class="tab-pane p-3 border rounded-lg" id="tabppid" role="tabpanel">
						<?php $this->load->view('ppid/form_ppid'); ?>
					</div>
				
				</div>
				
				</div>
				<div class="card-footer">
					<div class="text-center">
						<?php /*if($item_info->status != '3'):*/ ?>
						<button class="btn btn-info" id="save"><i class="fa fa-save" aria-hidden="true"></i> Simpan</button>
						<?php /*endif;*/ ?>
						<a class="btn btn-light" href="<?php echo $back_url;?>"></i> Kembali</a>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php echo form_close(); ?>

<script type="text/javascript">

var subklasifikasi = <?php echo $subkla;?>;

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
	
	$("#prod_digunakan_tgl").datepicker();
	$("#prod_kadaluarsa").datepicker();
	$("#prod_diperoleh_tgl").datepicker();
	
	$('#tgl_diterima').datepicker();
	$('#tgl_tanggapan').datepicker();
	$('#tt_tgl').datepicker();
	$('#keberatan_tgl').datepicker();
	$('#tgl_pemberitahuan_tertulis').datepicker();
	
	var negara = $('#iden_negara').val();
	if(negara == "" || negara == "Indonesia")
	{
		
		$('#iden_provinsi2').hide();
		$('#iden_kota2').hide();
	}
	else
	{
		$('#iden_provinsi').select2().next().hide();
		$('#iden_kota').select2().next().hide();
		$('#iden_provinsi2').show();
		$('#iden_kota2').show();
	}
	
	
	var prod_negara = $('#prod_negara').val();
	if(prod_negara == "" || prod_negara == "Indonesia")
	{
		
		$('#prod_provinsi2').hide();
		
	}
	else
	{
		$('#prod_provinsi').select2().next().hide();
		$('#prod_provinsi2').show();
	}
	
	
	<?php if($item_info->is_rujuk):?>
	<?php else: ?>
	/*$('#dir1').hide();$('#sla1').hide();
	$('#dir2').hide();$('#sla2').hide();
	$('#dir3').hide();$('#sla3').hide();
	$('#dir4').hide();$('#sla4').hide();
	$('#dir5').hide();$('#sla5').hide();*/
	$('#list_rujukan').hide();
	<?php endif; ?>
	
	$('input[name="is_rujuk"]').on('click', function(){
		if($('#rujuk_yes').is(":checked")){
			
			$('#list_rujukan').show();
		}
		if($('#rujuk_no').is(":checked")){
			
			$('#list_rujukan').hide();
		}
	});
	
	$('#prod_negara').on('change', function(){
		var negara = $(this).val();
		
		if(negara.toLowerCase() != 'indonesia')
		{
			$('#prod_provinsi').select2().next().hide();
			$('#prod_provinsi2').show();
			
		}
		else
		{
			$('#prod_provinsi2').hide();
			$('#prod_provinsi').select2().next().show();
			
		}
	});
	
	$('#iden_negara').on('change', function(){
		var negara = $(this).val();
		if(negara.toLowerCase() != 'indonesia')
		{
			$('#iden_provinsi').select2().next().hide();
			$('#iden_provinsi2').show();
			$('#iden_kota').select2().next().hide();
			$('#iden_kota2').show();
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
	
	$('#klasifikasi').on('change', function(){
		
		if($(this).val() == '')
		{
			$('#subklasifikasi').empty();
			return;
		}
		
		$.getJSON("<?php echo site_url('tickets/get_subklasifikasi2/'); ?>" + $(this).val(), function(data) {
			if(data)
			{
				$('#subklasifikasi').empty();
				$.each(data, function (key, entry) {
					$('#subklasifikasi').append($('<option></option>').attr('value', entry.subklasifikasi).text(entry.subklasifikasi));
				  })
			}
		});
	});
	
	
	$('#klasifikasi_id').on('change', function(){
		var kat = $('#kategori').val();
		var info = $("input[name='info']:checked").val();
		var klasifikasi = $('#klasifikasi_id').val();
		
		$('#subklasifikasi_id').empty();
		$.each(subklasifikasi[klasifikasi], function (key, entry) {
			//console.log(entry);
			$('#subklasifikasi_id').append($('<option></option>').attr('value', entry.id).text(entry.value));
		  });
	});
	
	//load_komoditi('<?php echo $item_info->info; ?>');
	
	if($('#jenis').val() != 'PPID')
	{
		$('#tabppid_').css('display','none');
		$('#tabppid2_').css('display','none');
	}
	
	$('#jenis').on('change', function(){
		if($(this).val() == 'PPID')
		{
			$('#tabppid_').css('display','block');
			$('#tabppid2_').css('display','block');
		}
		else
		{
			$('#tabppid_').css('display','none');
			$('#tabppid2_').css('display','none');
		}
	});
	
	if($('#submited_via').val() != 'Medsos')
		$('#tipe_medsos').hide();
	
	$('#submited_via').on('change', function(){
		if($(this).val() == 'Medsos')
		{
			$('#tipe_medsos').show();
		}
		else
		{
			$('#tipe_medsos').hide();
		}
	});
	/*$.validator.addMethod("uniqueRujukan", function(value, element) {
		var flag = $('#dir1').val() == $('#dir2').val() || 
			$('#dir1').val() == $('#dir3').val() || 
			$('#dir1').val() == $('#dir4').val() || 
			$('#dir1').val() == $('#dir5').val() ||
			$('#dir2').val() == $('#dir3').val() || 
			$('#dir2').val() == $('#dir4').val() || 
			$('#dir2').val() == $('#dir5').val() ||
			$('#dir3').val() == $('#dir4').val() || 
			$('#dir3').val() == $('#dir5').val() || 
			$('#dir4').val() == $('#dir5').val();
		return !flag;
	}, "Unit Rujukan tidak boleh sama");*/
	
	$('#ticket_form').validate($.extend({
		submitHandler: function(form)
		{
			
			$(form).ajaxSubmit({
				beforeSubmit: function() {
					//$('#submit').attr('disabled',true);
				},
				success: function(response)
				{				
					$.notify(response.message, { type: response.success ? 'success' : 'danger' });
					setTimeout(function(){window.location.href = "<?php echo site_url('ppid/edit/'.$item_info->id); ?>";}, 1000);
				},
				dataType: 'json'
			});
				
			
		},

		rules:
		{
			iden_nama: "required",
			iden_alamat: "required",
			iden_profesi: "required",
			//iden_email: "required",
			isu_topik: "required",
			prod_masalah: "required",
			penerima: "required",
			kategori: "required",
			submited_via: "required",
			klasifikasi: "required",
			subklasifikasi: "required",
			jawaban: "required",
			petugas_entry: "required",
			penjawab: "required",
			answered_via: "required",
			//is_rujuk: "required",
			info: "required",
			is_rujuk : {
				required : true,
				//uniqueRujukan : true
			},
			tgl_diterima : "required",
			diterima_via : "required",
			no_ktp : "required",
			rincian : "required",
			tujuan : "required",
			tgl_pemberitahuan_tertulis : "required",
			waktu_penyediaan : "required",
			nama_pejabat_ppid : "required",
			tt_tgl : "required",
			tt_nomor : "required",
			tt_lampiran : "required",
			tt_perihal : "required",
			tt_isi : "required",
			tt_pejabat : "required",
			keputusan : "required"
   		},

		messages: 
		{
     		iden_nama: "Nama Pelapor harus diisi",
     		iden_alamat: "Alamat Pelapor harus diisi",
			iden_profesi: "Pekerjaan harus diisi",
			//iden_email: "Email harus diisi",
			isu_topik: "Isu Topik harus diisi",
			prod_masalah: "Pengaduan/Pertanyaan harus diisi",
     		penerima: "Penerima harus diisi",
			kategori: "Jenis Komoditi harus diisi",
			submited_via: "Layanan melalui harus diisi",
			klasifikasi: "Klasifikasi harus diisi",
			subklasifikasi: "Subklasifikasi harus diisi",
			jawaban: "Jawaban harus diisi",
			petugas_entry: "Nama Petugas Input harus diisi",
			penjawab: "Nama Penjawab harus diisi",
			answered_via: "Dijawab melalui harus diisi",
			//is_rujuk: "Perlu rujuk harus dipilih",
			info: "Jenis Layanan harus dipilih",
			is_rujuk : {
				required : "Perlu rujuk harus dipilih",
				//uniqueRujukan : "Unit Rujukan tidak boleh sama"
			},
			tgl_diterima : "Tanggal Diterima harus diisi",
			diterima_via : "Melalui harus diisi",
			no_ktp : "No. KTP harus diisi",
			rincian : "Rincian Informasi harus diisi",
			tujuan : "Tujuan Penggunaan Informasi harus diisi",
			tgl_pemberitahuan_tertulis : "Tgl Pemberitahuan Tertulis harus diisi",
			waktu_penyediaan : "Waktu Penyediaan harus diisi",
			nama_pejabat_ppid : "Nama Pejabat PPID harus diisi",
			tt_tgl : "Tanggal Tanggapan Tertulis harus diisi",
			tt_nomor : "Nomor Tanggapan Tertulis harus diisi",
			tt_lampiran : "Lampiran harus diisi",
			tt_perihal : "Perihal harus diisi",
			tt_isi : "Isi surat harus diisi",
			tt_pejabat : "Pejabat penandatangan surat harus diisi",
			keputusan : "Keputusan harus dipilih"
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