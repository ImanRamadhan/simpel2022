<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Layanan</a></li>
					<li class="breadcrumb-item active">Layanan Saya</li>
				</ol>
			</div>
			<h4 class="page-title">Layanan Saya</h4>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	
    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>
	
	$('#process_btn').on('click', function(e)
	{
		$.remember({ name: 'tickets_me.tgl1', value: $('#tgl1').val() })
		$.remember({ name: 'tickets_me.tgl2', value: $('#tgl2').val() })
		
        table_support.refresh();
    });
	
	$('#status').on('hidden.bs.select', function(e)
	{
        table_support.refresh();
    });
	$('#tl').on('hidden.bs.select', function(e)
	{
        table_support.refresh();
    });
	$('#fb').on('hidden.bs.select', function(e)
	{
        table_support.refresh();
    });
	
	
	
	if($.remember({ name: 'tickets_me.tgl1' }) != null) 
	{
		$('#tgl1').val($.remember({ name: 'tickets_me.tgl1' }));
	}
	else
	{
		$("#tgl1").val("<?php echo date('01/m/Y'); ?>");
	}
	
	if($.remember({ name: 'tickets_me.tgl2' }) != null) 
	{
		$('#tgl2').val($.remember({ name: 'tickets_me.tgl2' }));
	}
	else
	{
		$("#tgl2").val("<?php echo date('d/m/Y'); ?>");
	}
	
	var tgl1 = '<?php echo $tgl1; ?>';
	if(tgl1 != '')
		$('#tgl1').val(tgl1);
	
	var tgl2 = '<?php echo $tgl2; ?>';
	if(tgl2 != '')
		$('#tgl2').val(tgl2);
	
	var fb_filter = '<?php echo $fb_filter; ?>';
	if(fb_filter != '')
		$('#fb').val(fb_filter);
	
	var status_filter = '<?php echo $status_filter; ?>';
	if(status_filter != '')
		$('#status').val(status_filter);

    table_support.init({
        resource: "<?php echo site_url('tickets_me');?>",
        headers: <?php echo $table_headers; ?>,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'id',
		cookie: true,
		cookieIdTable: 'ticketmeTable',
		sortName: 'id',
		sortOrder: 'desc',
        queryParams: function() {
            return $.extend(arguments[0], {
                kota: $("#kota").val() || "",
				tgl1: moment($("#tgl1").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "",
				tgl2: moment($("#tgl2").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "",
				status: $("#status").val() || [""],
				tl: $("#tl").val() || [""],
				fb: $("#fb").val() || [""],
				kategori: $('#kategori').val() || "",
				jenis: $('#jenis').val() || "",
				field: $('#field').val() || "",
				keyword: $('#keyword').val() || "",
				tl: $("#tl").val() || [""],
				fb: $("#fb").val() || [""],
				is_rujuk: $("#is_rujuk").val() || [""],
				is_verified: $("#is_verified").val() || [""],
				iden_profesi: $('#iden_profesi').val() || "",
				submited_via : $('#submited_via').val() || ""
            });
        },
    });
});
</script>


<div class="row">
	<div class="col-md-12">
		
		<div class="card ">
			<div class="card-header bg-primary text-white">
				Layanan Saya
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
					<?php if(is_allowed('can_create_tickets')): ?>
					<a href="<?php echo site_url("drafts/create"); ?>" class="btn btn-info btn-sm btn-round"><i class="fa fa-plus"></i> <?php echo $this->lang->line($controller_name . '_new'); ?> </a>
					<?php endif; ?>
				</div>
				<?php /*$this->load->view("partial/search");*/ ?>
				<?php $this->load->view("partial/search_advanced_me"); ?>
				
				<div id="toolbar">
					<div class="float-left pull-left form-inline" role="toolbar">
						
						<button id="delete" class="btn btn-soft-info btn-sm">
							<span class="fa fa-trash">&nbsp;</span><?php echo $this->lang->line("common_delete"); ?>
						</button>
						
						&nbsp;
						<a id="export" class="btn export btn-soft-info btn-sm" href="#" title="Export data ke MS Excel">
							<span class="fa fa-download">&nbsp;</span>Export
						</a>
						
						&nbsp;Pilih Status : &nbsp;
						<?php echo form_multiselect('status[]', array('0'=>'Open','2'=>'Replied','3' => 'Closed'), '', array('id'=>'status', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>'All', 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-soft-info btn-sm', 'data-width'=>'fit')); ?>
						&nbsp;Pilih TL : &nbsp;
						<?php echo form_multiselect('tl[]', array('0'=>'N','1'=>'Y'), '', array('id'=>'tl', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>'All', 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-soft-info btn-sm', 'data-width'=>'fit')); ?>
						&nbsp;Pilih FB : &nbsp;
						<?php echo form_multiselect('fb[]', array('0'=>'N','1'=>'Y'), '', array('id'=>'fb', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>'All', 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-soft-info btn-sm', 'data-width'=>'fit')); ?>
					  
					   
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
		var kota = $("#kota").val() || "";
		var tgl1 = moment($("#tgl1").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "";
		var	tgl2 = moment($("#tgl2").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "";
		var keyword = $("#keyword").val() || "";
		var field = $("#field").val() || "";
		var kategori = $("#kategori").val() || "";
		var jenis = $("#jenis").val() || "";
		var tl = $("#tl").val() || "";
		var fb = $("#fb").val() || "";
		var status = $("#status").val() || "";
		var sla = $("#sla").val() || "";
		var iden_profesi = $('#iden_profesi').val() || "";
		var submited_via = $('#submited_via').val() || "";
		var sla = $('#sla').val() || "";
		
		window.location.href = "<?php echo site_url('excels_new/download_layanan_saya');?>?tgl1="+ tgl1 +"&tgl2="+ tgl2 +"&kota="+kota+"&keyword=" + keyword+"&field=" + field + "&kategori=" + kategori + "&jenis=" + jenis + "&status=" + status + "&tl=" + tl + "&fb=" + fb + "&sla=" + sla + "&iden_profesi=" + iden_profesi + "&submited_via="+submited_via;
		return false;
	});
});
</script>
<?php $this->load->view("partial/footer"); ?>
