<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Data Master</a></li>
					<li class="breadcrumb-item active">Pengaturan Unit Kerja</li>
				</ol>
			</div>
			<h4 class="page-title">Daftar Unit Kerja</h4>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	$('#kota').on('change', function(e)
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
		cookieIdTable: 'deptTable',
        queryParams: function() {
            return $.extend(arguments[0], {
				kota: $("#kota").val() || "",
            });
        },
    });
});
</script>


<div class="row">
	<div class="col-md-12">
		
		<div class="card ">
			<div class="card-header bg-primary text-white">
				Daftar Unit Kerja
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
					<a class='btn btn-info btn-sm btn-round ml-auto' href='<?php echo site_url($controller_name."/create"); ?>'
							title='<?php echo $this->lang->line($controller_name . '_new'); ?>'>
						<span class="fa fa-plus">&nbsp;</span><?php echo $this->lang->line($controller_name. '_new'); ?>
					</a>
				</div>
				
				<div class="row form-horizontal">
					<div class="col-sm-12 col-lg-8 border-0">
						
						
							<div class="form-group form-group-sm row">

								<?php echo form_label('Pilih Kota', 'label_kota', array('class'=>'required col-form-label col-sm-3')); ?>
								<div class='col-sm-6'>
									<?php echo form_dropdown('kota', $cities, '', 'class="form-control form-control-sm" id="kota"');?>
								</div>
							</div>
							
					</div>
				</div>
				
				
				<br />
				<br />
				<div id="toolbar">
					<div class="float-left form-inline" role="toolbar">
						
						<button id="delete" class="btn btn-soft-info btn-sm">
							<span class="fa fa-trash">&nbsp;</span><?php echo $this->lang->line("common_delete"); ?>
						</button>
					  
					   
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
