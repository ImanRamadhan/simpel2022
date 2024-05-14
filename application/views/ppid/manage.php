<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">PPID</a></li>
					<li class="breadcrumb-item active">Layanan PPID</li>
				</ol>
			</div>
			<h4 class="page-title">Layanan PPID</h4>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	
    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>
	
	$('#process_btn').on('click', function(e)
	{
		$.remember({ name: 'ppid.tgl1', value: $('#tgl1').val() })
		$.remember({ name: 'ppid.tgl2', value: $('#tgl2').val() })
		
        table_support.refresh();
    });
	
	
	
	
	if($.remember({ name: 'ppid.tgl1' }) != null) 
	{
		$('#tgl1').val($.remember({ name: 'ppid.tgl1' }));
	}
	else
	{
		$("#tgl1").val("<?php echo date('01/m/Y'); ?>");
	}
	
	if($.remember({ name: 'ppid.tgl2' }) != null) 
	{
		$('#tgl2').val($.remember({ name: 'ppid.tgl2' }));
	}
	else
	{
		$("#tgl2").val("<?php echo date('d/m/Y'); ?>");
	}

    table_support.init({
        resource: '<?php echo site_url($controller_name);?>',
        headers: <?php echo $table_headers; ?>,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'id',
		cookie: true,
		cookieIdTable: 'ppidTable',
        queryParams: function() {
            return $.extend(arguments[0], {
                kota: $("#kota").val() || "",
				tgl1: moment($("#tgl1").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "",
				tgl2: moment($("#tgl2").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "",
				type: 'P'
            });
        },
    });
});
</script>


<div class="row">
	<div class="col-md-12">
		
		<div class="card ">
			<div class="card-header bg-primary text-white">
				Layanan Permintaan Informasi Publik
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
					
					<a href="<?php echo site_url("drafts/create_ppid"); ?>" class="btn btn-info btn-sm btn-round"><i class="fa fa-plus"></i> <?php echo $this->lang->line($controller_name . '_new'); ?> </a>
					
				</div>
				<?php $this->load->view("partial/search"); ?>
				
				<h4 class="text-center">REGISTER PERMINTAAN INFORMASI</h4>
				<div id="toolbar">
					<div class="float-left form-inline" role="toolbar">
						
						<!--<button id="delete" class="btn btn-soft-info btn-sm">
							<span class="fa fa-trash">&nbsp;</span><?php echo $this->lang->line("common_delete"); ?>
						</button>-->
					  
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
	$('#kota').on('change', function(){
		$.remember({ name: 'ppid.kota', value: $('#kota').val() })
	});
	
	if($.remember({ name: 'ppid.kota' }) != null)
		$('#kota').val($.remember({ name: 'ppid.kota' }));
	
	$("#tgl1").datepicker();
	$("#tgl2").datepicker();
	
	$('#export').on('click', function(e){
		e.preventDefault(); 
		var kota = $("#kota").val() || "";
		var tgl1 = moment($("#tgl1").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "";
		var	tgl2 = moment($("#tgl2").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "";
		var search = '';
		//alert(kota + ' ' + tgl1 + ' ' + tgl2);
		window.location.href = "<?php echo site_url('excels_ppid/export_excel');?>?tgl1="+ tgl1 +"&tgl2="+ tgl2 +"&kota="+kota+"&search=" + search;
		return false;
	});
});
</script>
<?php $this->load->view("partial/footer"); ?>
