<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Data Master</a></li>
					<li class="breadcrumb-item active">Pengaturan SLA</li>
				</ol>
			</div>
			<h4 class="page-title">Pengaturan SLA</h4>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	$('#info').on('change', function(e)
	{
        table_support.refresh();
    });
	$('#komoditi_id').on('change', function(e)
	{
        table_support.refresh();
    });
	$('#klasifikasi_id').on('change', function(e)
	{
        table_support.refresh();
    });
	
    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>

    table_support.init({
        resource: '<?php echo site_url($controller_name);?>',
        headers: <?php echo $table_headers; ?>,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'id',
		cookie: true,
		cookieIdTable: 'slaTable',
		sortName: 'id',
		sortOrder: 'asc',
        queryParams: function() {
            return $.extend(arguments[0], {
				info: $("#info").val() || "",
				komoditi_id: $("#komoditi_id").val() || "",
				klasifikasi_id: $("#klasifikasi_id").val() || ""
            });
        },
    });
});
</script>


<div class="row">
	<div class="col-md-12">
		
		<div class="card ">
			<div class="card-header bg-primary text-white">
				Daftar SLA
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
					
					<a  title='<?php echo $this->lang->line($controller_name . '_new'); ?>' class='btn btn-info btn-sm btn-round ml-auto' href="<?php echo site_url($controller_name."/create"); ?>"><span class="fa fa-plus">&nbsp;</span><?php echo $this->lang->line($controller_name. '_new'); ?></a>
					
				</div>
				<br />
				<br />
				<div id="toolbar">
					<div class="float-left form-inline" role="toolbar">
						
						<button id="delete" class="btn btn-soft-info btn-sm">
							<span class="fa fa-trash">&nbsp;</span><?php echo $this->lang->line("common_delete"); ?>
						</button>
						&nbsp;Layanan : &nbsp;
						<?php echo form_dropdown('info', array(''=> 'Pilih', 'P'=>'Pengaduan','I' => 'Informasi'), '', array('id'=>'info', 'class'=>'form-control form-control-sm')); ?>
						&nbsp;Komoditi : &nbsp;
						<?php echo form_dropdown('komoditi_id', $commodities, '', array('id'=>'komoditi_id', 'class'=>'form-control form-control-sm')); ?>
						&nbsp;Klasifikasi : &nbsp;
						<?php echo form_dropdown('klasifikasi_id', $klasifikasis, '', array('id'=>'klasifikasi_id', 'class'=>'form-control form-control-sm')); ?>
					   
					</div>
				</div>
				<div id="table_holder">
					<table id="table" class="table table-striped"></table>
				</div>
				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{	
	
});
</script>
<?php $this->load->view("partial/footer"); ?>
