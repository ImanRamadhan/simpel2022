<?php $this->load->view("partial/header");?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Layanan</a></li>
					<li class="breadcrumb-item active">Belum di-TL</li>
				</ol>
			</div>
			<h4 class="page-title">Layanan Belum di-TL</h4>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	
    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>
	
	<?php 
	/*$page = $this->session->flashdata('page');
	if(!empty($page)): ?>
		$.remember({ name: 'tickets.tgl1', value: '<?php echo convert_date2($this->session->dashboard_date1); ?>' });
		$.remember({ name: 'tickets.tgl2', value: '<?php echo convert_date2($this->session->dashboard_date2); ?>' });
		<?php if($page == 'pusat'):?>
			$.remember({ name: 'tickets.kota', value: 'PUSAT' });
		<?php elseif($page == 'cc'):?>
			$.remember({ name: 'tickets.kota', value: 'CC' });
		<?php elseif($page == 'balai'):?>
			$.remember({ name: 'tickets.kota', value: 'BALAI' });
		<?php elseif($page == 'all'):?>
			$.remember({ name: 'tickets.kota', value: 'ALL' });
		<?php endif; ?>
	<?php endif;*/ ?>
	
	
	
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
	
	
	$('#sla').on('hidden.bs.select', function(e)
	{
        table_support.refresh();
    });
	
	$('#process_btn').on('click', function(e)
	{
		$.remember({ name: 'tickets.tgl1', value: $('#tgl1').val() });
		$.remember({ name: 'tickets.tgl2', value: $('#tgl2').val() });
		
        table_support.refresh();
    });
	
	<?php if(is_pusat()):?>
	$('#kota').on('change', function(){
		$.remember({ name: 'tickets.kota', value: $('#kota').val() });
	});
	
	if($.remember({ name: 'tickets.kota' }) != null)
		$('#kota').val($.remember({ name: 'tickets.kota' }));
	
	<?php endif; ?>
	
	if($.remember({ name: 'tickets.tgl1' }) != null) 
	{
		$('#tgl1').val($.remember({ name: 'tickets.tgl1' }));
	}
	else
	{
		$("#tgl1").val("<?php echo date('01/m/Y'); ?>");
	}
	
	if($.remember({ name: 'tickets.tgl2' }) != null) 
	{
		$('#tgl2').val($.remember({ name: 'tickets.tgl2' }));
	}
	else
	{
		$("#tgl2").val("<?php echo date('d/m/Y'); ?>");
	}
	
	
	var city_filter = '<?php echo $city_filter; ?>';
	if(city_filter != '')
		$('#kota').val(city_filter);
	
	var tgl1 = '<?php echo $tgl1; ?>';
	if(tgl1 != '')
		$('#tgl1').val(tgl1);
	
	var tgl2 = '<?php echo $tgl2; ?>';
	if(tgl2 != '')
		$('#tgl2').val(tgl2);
	
	
	
	var tl_filter = '<?php echo $tl_filter; ?>';
	if(tl_filter != '')
		$('#tl').val(tl_filter);
	
	var sla_filter = '<?php echo $sla_filter; ?>';
	if(sla_filter != '')
		$('#sla').val(sla_filter);


    table_support.init({
        resource: '<?php echo site_url("tickets_sla");?>',
        headers: <?php echo $table_headers; ?>,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'id',
		cookie: true,
		cookieIdTable: 'ticketTable',
		sortName: 'id',
		sortOrder: 'desc',
        queryParams: function() {
            return $.extend(arguments[0], {
				<?php if(is_pusat()):?>
                kota: $("#kota").val() || "",
				<?php else: ?>
				kota: '<?php echo $this->session->city; ?>',
				<?php endif; ?>
				tgl1: moment($("#tgl1").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "",
				tgl2: moment($("#tgl2").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "",
				tl: $("#tl").val() || "",
				sla: $("#sla").val() || "",
				/*kategori: $('#kategori').val() || "",
				jenis: $('#jenis').val() || "",
				field: $('#field').val() || "",
				keyword: $('#keyword').val() || "",
				status: $("#status").val() || [""],
				tl: $("#tl").val() || [""],
				fb: $("#fb").val() || [""],
				is_rujuk: $("#is_rujuk").val() || [""],
				is_verified: $("#is_verified").val() || [""],
				iden_profesi: $('#iden_profesi').val() || "",*/
				
            });
        },
		/*rowAttributes: function (row, index) {
			//return { "data-id": row.id };
			return { "title": row.problem };
		},*/
    });
});
</script>


<div class="row">
	<div class="col-md-12">
		
		<div class="card ">
			<div class="card-header bg-primary text-white">
				Data Layanan
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
					<?php if(is_allowed('can_create_tickets')): ?>
					<a href="<?php echo site_url("drafts/create"); ?>" class="btn btn-info btn-sm btn-round"><i class="fa fa-plus"></i> <?php echo $this->lang->line($controller_name . '_new'); ?> </a>
					<?php endif; ?>
				</div>
				<?php $this->load->view("partial/search_advanced"); ?>
				
				
				<div id="toolbar">
					<div class="float-left form-inline" role="toolbar">
						
						<!--<button id="delete" class="btn btn-soft-info btn-sm ">
							<span class="fa fa-trash">&nbsp;</span><?php echo $this->lang->line("common_delete"); ?>
						</button>-->
						
						<!--<button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> Aksi <span class="caret"></span> </button>
						<div class="dropdown-menu">
							<button class="dropdown-item" id="btn_close">Tutup layanan terpilih</button>
							<button class="dropdown-item" id="btn_verify">Verifikasi layanan terpilih</button>
							<button class="dropdown-item" id="delete">Hapus layanan terpilih</button>
						</div>-->
						&nbsp;
						<a id="export" class="btn export btn-soft-info btn-sm" href="#" title="Export data ke MS Excel">
							<span class="fa fa-download">&nbsp;</span>Export
						</a>
						<?php
						
						?>
					    
						&nbsp;TL : &nbsp;
						<?php echo form_dropdown('tl', array('ALL'=>'ALL', 'N'=>'N','Y'=>'Y'), '', array('id'=>'tl', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>'All', 'data-style'=>'btn-soft-info btn-sm', 'data-width'=>'fit')); ?>
						
						&nbsp;SLA : &nbsp;
						<?php echo form_dropdown('sla', array('ALL'=> 'ALL', 'green'=>'SLA 14-60 HK','orange'=>'SLA 5-14 HK','red'=>'SLA < 5 HK','black'=>'Melebihi SLA'), '', array('id'=>'sla', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>'All', 'data-style'=>'btn-soft-info btn-sm', 'data-width'=>'fit')); ?>
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
		
		window.location.href = "<?php echo site_url('excels/download_layanan');?>?tgl1="+ tgl1 +"&tgl2="+ tgl2 +"&kota="+kota+"&keyword=" + keyword+"&field=" + field + "&kategori=" + kategori + "&jenis=" + jenis + "&status=" + status + "&tl=" + tl + "&fb=" + fb + "&sla=" + sla;
		return false;
	});
});
</script>
<?php $this->load->view("partial/footer"); ?>
