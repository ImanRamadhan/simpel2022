<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Rujukan</a></li>
					<li class="breadcrumb-item active">Rujukan Masuk</li>
				</ol>
			</div>
			<h4 class="page-title">Rujukan Masuk</h4>
		</div>
	</div>
</div>
<script src="<?php echo base_url()?>assets/js/jquery.fileDownload.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	
    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>
	
	$('#process_btn').on('click', function(e)
	{
		$.remember({ name: 'rujukan_masuk.tgl1', value: $('#tgl1').val() })
		$.remember({ name: 'rujukan_masuk.tgl2', value: $('#tgl2').val() })
		
        table_support.refresh();
    });
	
	$('#status').on('hidden.bs.select', function(e)
	{
        table_support.refresh();
    });
	
	
	// if($.remember({ name: 'rujukan_masuk.tgl1' }) != null) 
	// {
	// 	$('#tgl1').val($.remember({ name: 'rujukan_masuk.tgl1' }));
	// }
	// else
	// {
	// 	$("#tgl1").val("<?php echo date('01/m/Y'); ?>");
	// }

	$("#tgl1").val("<?php echo date('01/01/Y'); ?>");
	
	// if($.remember({ name: 'rujukan_masuk.tgl2' }) != null) 
	// {
	// 	$('#tgl2').val($.remember({ name: 'rujukan_masuk.tgl2' }));
	// }
	// else
	// {
	// 	$("#tgl2").val("<?php echo date('d/m/Y'); ?>");
	// }

	$("#tgl2").val("<?php echo date('d/m/Y'); ?>");
	
	var tgl1 = '<?php echo $tgl1; ?>';
	if(tgl1 != '')
		$('#tgl1').val(tgl1);
	
	var tgl2 = '<?php echo $tgl2; ?>';
	if(tgl2 != '')
		$('#tgl2').val(tgl2);
	
	var status_filter = '<?php echo $status_filter; ?>';
	if(status_filter != '')
		$('#status').val(status_filter);

    table_support.init({
        resource: "<?php echo site_url('rujukan_masuk');?>",
        headers: <?php echo $table_headers; ?>,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'id',
		cookie: true,
		cookieIdTable: 'rujukanmasukTable',
        queryParams: function() {
            return $.extend(arguments[0], {
              
				tgl1: moment($("#tgl1").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "",
				tgl2: moment($("#tgl2").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "",
				status: $("#status").val() || [""],
            });
        },
    });
});
</script>


<div class="row">
	<div class="col-md-12">
		
		<div class="card ">
			<div class="card-header bg-primary text-white">
				Rujukan Masuk
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
					
					
					
				</div>
				<?php $this->load->view("partial/search_date"); ?>
				
				<div id="toolbar">
					<div class="float-left form-inline" role="toolbar">
						
						
					    <a id="export" class="btn export btn-soft-info btn-sm" href="#" title="Export data ke MS Excel">
							<span class="fa fa-download">&nbsp;</span>Export
						</a>
					   &nbsp;Status : &nbsp;
						<?php echo form_multiselect('status[]', array('0'=>'Belum Dijawab','1'=>'Sudah Dijawab'), $status, array('id'=>'status', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>'All', 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-soft-info btn-sm', 'data-width'=>'fit')); ?>
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
		$.remember({ name: 'tickets.kota', value: $('#kota').val() })
	});
	
	if($.remember({ name: 'tickets.kota' }) != null)
		$('#kota').val($.remember({ name: 'tickets.kota' }));
	
	$("#tgl1").datepicker({
		zIndexOffset: '1001',
		orientation: 'bottom'
	});
	$("#tgl2").datepicker({
		zIndexOffset: '1001',
		orientation: 'bottom'
	});
	
	$('#export').on('click', function(e){
		e.preventDefault(); 
		var tgl1 = moment($("#tgl1").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "";
		var	tgl2 = moment($("#tgl2").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "";
		
		$.fileDownload('<?php echo site_url('excels_new/download_rujukan_masuk') ?>', {
            
            data: {
                tgl1            : tgl1,
                tgl2            : tgl2,
                kota            : $("#kota").val() || "",
                //kategori        : $("#kategori").val(),
                //datasource      : $("#datasource").val(),
                //statusRujukan   : $("#statusRujukan").val(),
                //type            : "1",
				<?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
            },
            httpMethod: 'POST',
            success: function (Result) {
            },
            error: function (request, status, error) {
                //alert('error');
               // alert(request.responseText);
            }
        });
	});

});
</script>
<?php $this->load->view("partial/footer"); ?>
