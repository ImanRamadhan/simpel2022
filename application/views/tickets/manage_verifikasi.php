<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Layanan</a></li>
					<li class="breadcrumb-item active">Layanan Verifikasi Saya</li>
				</ol>
			</div>
			<h4 class="page-title">Layanan Verifikasi Saya</h4>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	
    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>
	
	$('#process_btn').on('click', function(e)
	{
		$.remember({ name: 'tickets_verifikasi.tgl1', value: $('#tgl1').val() })
		$.remember({ name: 'tickets_verifikasi.tgl2', value: $('#tgl2').val() })
		
        table_support.refresh();
    });
	
	if($.remember({ name: 'tickets_verifikasi.tgl1' }) != null) 
	{
		$('#tgl1').val($.remember({ name: 'tickets_verifikasi.tgl1' }));
	}
	else
	{
		$("#tgl1").val("<?php echo date('01/m/Y'); ?>");
	}
	
	if($.remember({ name: 'tickets_verifikasi.tgl2' }) != null) 
	{
		$('#tgl2').val($.remember({ name: 'tickets_verifikasi.tgl2' }));
	}
	else
	{
		$("#tgl2").val("<?php echo date('d/m/Y'); ?>");
	}
	
	$('#kota').on('change', function(){
		$.remember({ name: 'tickets_verifikasi.kota', value: $('#kota').val() })
	});
	
	if($.remember({ name: 'tickets_verifikasi.kota' }) != null)
		$('#kota').val($.remember({ name: 'tickets_verifikasi.kota' }));

    table_support.init({
        resource: "<?php echo site_url('tickets_verifikasi');?>",
        headers: <?php echo $table_headers; ?>,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'id',
		cookie: true,
		cookieIdTable: 'ticketverifikasiTable',
		sortName: 'id',
		sortOrder: 'desc',
        queryParams: function() {
            return $.extend(arguments[0], {
                //kota: $("#kota").val() || "",
				tgl1: moment($("#tgl1").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "",
				tgl2: moment($("#tgl2").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "",
				
            });
        },
    });
});
</script>


<div class="row">
	<div class="col-md-12">
		
		<div class="card ">
			<div class="card-header bg-primary text-white">
				Layanan Verifikasi Saya
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
					
					<!--<a href="<?php echo site_url("drafts/create"); ?>" class="btn btn-info btn-sm btn-round"><i class="fa fa-plus"></i> <?php echo $this->lang->line($controller_name . '_new'); ?> </a>-->
					
				</div>
				<?php $this->load->view("partial/search_date"); ?>
				
				
				<div id="toolbar">
					<div class="float-left form-inline" role="toolbar">
						
						<a id="export" class="btn export btn-soft-info btn-sm" href="#" title="Export data ke MS Excel">
							<span class="fa fa-download">&nbsp;</span>Export
						</a>
					   
					</div>
				</div>
				<div id="table_holder">
					<table id="table" class="table table-striped w-100"></table>
				</div>
				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
	
	
	$("#tgl1").datepicker();
	$("#tgl2").datepicker();
	
	$('#export').on('click', function(e){
		e.preventDefault(); 
		var kota = '<?php echo $this->session->city; ?>';
		var tgl1 = moment($("#tgl1").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "";
		var	tgl2 = moment($("#tgl2").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "";
		var keyword = $("#keyword").val() || "";
		var field = $("#field").val() || "";
		var kategori = $("#kategori").val() || "";
		var jenis = $("#jenis").val() || "";
		
		window.location.href = "<?php echo site_url('excels/download_layanan');?>?tgl1="+ tgl1 +"&tgl2="+ tgl2 +"&kota="+kota+"&keyword=" + keyword+"&field=" + field + "&kategori=" + kategori + "&jenis=" + jenis + "&menu=VERIFIKASISAYA";
		return false;
	});

});
</script>
<?php $this->load->view("partial/footer"); ?>
