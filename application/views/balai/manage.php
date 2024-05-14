<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Data Master</a></li>
					<li class="breadcrumb-item active">Pengaturan Balai</li>
				</ol>
			</div>
			<h4 class="page-title">Daftar Balai/Loka</h4>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	
	$('#filters').on('hidden.bs.select', function(e)
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
		cookieIdTable: 'cityTable',
		sortOrder: 'asc',
		sortName: 'nama_balai',
        queryParams: function() {
            return $.extend(arguments[0], {
				filters: $("#filters").val() || [""],
            });
        },
    });
});
</script>


<div class="row">
	<div class="col-md-12">
		
		<div class="card ">
			<div class="card-header bg-primary text-white">
				Daftar Balai/Loka
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
					
					<a  title='<?php echo $this->lang->line($controller_name . '_new'); ?>' class='btn btn-info btn-sm btn-round ml-auto' href="<?php echo site_url($controller_name."/create"); ?>"><span class="fa fa-plus">&nbsp;</span><?php echo $this->lang->line($controller_name. '_new'); ?></a>
					
				</div>
				<br />
				<br />
				<div id="toolbar">
					<div class="float-left form-inline" role="toolbar">
						
						<!--<button id="delete" class="btn btn-soft-info btn-sm">
							<span class="fa fa-trash">&nbsp;</span><?php echo $this->lang->line("common_delete"); ?>
						</button>-->
					  
					   Pilih Tipe : &nbsp;
						<?php echo form_multiselect('filters[]', $filters, '', array('id'=>'filters', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>$this->lang->line('common_none_selected_text'), 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-soft-info btn-sm', 'data-width'=>'fit')); ?>
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
