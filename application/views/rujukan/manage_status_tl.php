<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Rujukan</a></li>
					<li class="breadcrumb-item active">Rujukan Status TL Pengaduan</li>
				</ol>
			</div>
			<h4 class="page-title">Rujukan Status TL Pengaduan</h4>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	
    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>
	
	$('#process_btn').on('click', function(e)
	{
		$.remember({ name: 'rujukan_status_tl.tgl1', value: $('#tgl1').val() })
		$.remember({ name: 'rujukan_status_tl.tgl2', value: $('#tgl2').val() })
		
        table_support.refresh();
    });
	
	
	
	
	if($.remember({ name: 'rujukan_status_tl.tgl1' }) != null) 
	{
		$('#tgl1').val($.remember({ name: 'rujukan_status_tl.tgl1' }));
	}
	else
	{
		$("#tgl1").val("<?php echo date('01/m/Y'); ?>");
	}
	
	if($.remember({ name: 'rujukan_status_tl.tgl2' }) != null) 
	{
		$('#tgl2').val($.remember({ name: 'rujukan_status_tl.tgl2' }));
	}
	else
	{
		$("#tgl2").val("<?php echo date('d/m/Y'); ?>");
	}

    table_support.init({
        resource: "<?php echo site_url('rujukan_status_tl');?>",
        headers: <?php echo $table_headers; ?>,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'id',
		cookie: true,
		cookieIdTable: 'rujukanstatustlTable',
        queryParams: function() {
            return $.extend(arguments[0], {
                
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
				Rujukan Status TL Pengaduan
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
					
					
					
				</div>
				<?php $this->load->view("partial/search_date"); ?>
				
				
				<div id="toolbar">
					<div class="float-left form-inline" role="toolbar">
						
						
					  
					   
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
	
	
	$("#tgl1").datepicker({
		zIndexOffset: '1001',
		orientation: 'bottom'
	});
	$("#tgl2").datepicker({
		zIndexOffset: '1001',
		orientation: 'bottom'
	});
	

});
</script>
<?php $this->load->view("partial/footer"); ?>
