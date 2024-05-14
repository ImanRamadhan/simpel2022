<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Layanan</a></li>
					<li class="breadcrumb-item active">Drafts Saya</li>
				</ol>
			</div>
			<h4 class="page-title">Drafts Saya</h4>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	
    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>
	
	$('#process_btn').on('click', function(e)
	{
		$.remember({ name: 'drafts.tgl1', value: $('#tgl1').val() })
		$.remember({ name: 'drafts.tgl2', value: $('#tgl2').val() })
		
        table_support.refresh();
    });
	
	
	
	
	if($.remember({ name: 'drafts.tgl1' }) != null) 
	{
		$('#tgl1').val($.remember({ name: 'drafts.tgl1' }));
	}
	else
	{
		$("#tgl1").val("<?php echo date('01/m/Y'); ?>");
	}
	
	if($.remember({ name: 'drafts.tgl2' }) != null) 
	{
		$('#tgl2').val($.remember({ name: 'drafts.tgl2' }));
	}
	else
	{
		$("#tgl2").val("<?php echo date('d/m/Y'); ?>");
	}

    table_support.init({
        resource: "<?php echo site_url('drafts');?>",
        headers: <?php echo $table_headers; ?>,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'id',
		cookie: true,
		cookieIdTable: 'draftTable',
		sortName: 'id',
		sortOrder: 'desc',
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
				Drafts Saya
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
					
					<!---<a href="<?php echo site_url("drafts/create"); ?>" class="btn btn-info btn-sm btn-round"><i class="fa fa-plus"></i> <?php echo 'Data Layanan'; ?> </a>&nbsp;
					<a href="<?php echo site_url("drafts/create_ppid"); ?>" class="btn btn-info btn-sm btn-round"><i class="fa fa-plus"></i> <?php echo 'Data PPID'; ?> </a>&nbsp;
					<a href="<?php echo site_url("drafts/create_keberatan"); ?>" class="btn btn-info btn-sm btn-round"><i class="fa fa-plus"></i> <?php echo 'Pengajuan Keberatan'; ?> </a>-->&nbsp;
					
					<div class="btn-group">
					  <button type="button" class="btn btn-info btn-sm btn-round dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-plus"></i> Tambah Data
					  </button>
					  <div class="dropdown-menu">
						<a class="dropdown-item" href="<?php echo site_url("drafts/create"); ?>">Data Layanan</a>
						<a class="dropdown-item" href="<?php echo site_url("drafts/create_ppid"); ?>"> Data PPID</a>
						<a class="dropdown-item" href="<?php echo site_url("drafts/create_keberatan"); ?>">Pengajuan Keberatan</a>
						
					  </div>
					</div>
				</div>
				<br />
				<br />
				<?php /*$this->load->view("partial/search_date");*/ ?>
				
				
				<div id="toolbar">
					<div class="float-left form-inline" role="toolbar">
						
						<button id="delete" class="btn btn-soft-info btn-sm">
							<span class="fa fa-trash">&nbsp;</span><?php echo $this->lang->line("common_delete"); ?>
						</button>
					  
					   
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
	
});
</script>
<?php $this->load->view("partial/footer"); ?>
