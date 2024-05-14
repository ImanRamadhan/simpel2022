<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Data Master</a></li>
					<li class="breadcrumb-item active">Pengaturan Calendar</li>
				</ol>
			</div>
			<h4 class="page-title"><?php echo $title?></h4>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	$('#thn').on('change', function(e)
	{
        table_support.refresh();
    });
	$('#bln').on('change', function(e)
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
		cookieIdTable: 'calendarTable',
        queryParams: function() {
            return $.extend(arguments[0], {
				thn: $("#thn").val() || "",
				bln: $("#bln").val() || ""
            });
        },
    });
});
</script>

<div class="row">
	<div class="col-md-12">
		<div class="card ">
			<div class="card-header bg-primary text-white">
				Daftar Hari Libur
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
					<a  title='Tambah Hari Libur' class='btn btn-info btn-sm btn-round ml-auto' href="<?php echo site_url($controller_name."/create"); ?>"><span class="fa fa-plus">&nbsp;</span>Tambah Hari Libur</a>
				</div>
				<br />
				<br />
				<div id="toolbar">
					<div class="float-left form-inline" role="toolbar">
						
						<button id="delete" class="btn btn-soft-info btn-sm">
							<span class="fa fa-trash">&nbsp;</span><?php echo $this->lang->line("common_delete"); ?>
						</button>
					  
					    &nbsp;Pilih Tahun : &nbsp;
						<?php echo form_dropdown('thn', $thns, date('Y'), array('id'=>'thn', 'class'=>'form-control form-control-sm')); ?>
						&nbsp;Pilih Bulan : &nbsp;
						<?php echo form_dropdown('bln', $blns, '', array('id'=>'bln', 'class'=>'form-control form-control-sm')); ?>
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
