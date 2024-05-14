<?php $this->load->view("partial/header"); ?>


<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Data Master</a></li>
					<li class="breadcrumb-item active">User</li>
				</ol>
			</div>
			<h4 class="page-title">Daftar User</h4>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
	$('#kota').on('change', function(e)
	{
		var city = $(this).val();
		//alert(city);
		$.ajax({
			
			url: '<?php echo site_url('users/get_directorat');?>',
            type: 'get',
            data: {city:city},
            dataType: 'json',
            success:function(response){
				//console.log(response);
				$("#dir").empty();
				$("#dir").append("<option value=''>ALL</option>");
				$.each(response, function (index, item) {
					//console.log(item.id);
					$("#dir").append("<option value='"+item.id+"'>"+item.name+"</option>");
				});
			}
		});
		
        table_support.refresh();
    });
	
	$('#status').on('hidden.bs.select', function(e)
	{
        table_support.refresh();
    });
	
	$('#dir').on('change', function(e)
	{
        table_support.refresh();
    });
	
	<?php $this->load->view('partial/bootstrap_tables_locale'); ?>

	table_support.init({
		resource: '<?php echo site_url($controller_name);?>',
		headers: <?php echo $table_headers; ?>,
		pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
		uniqueId: 'id',
		search: true,
		enableActions: function()
		{
			
		},
		queryParams: function() {
            return $.extend(arguments[0], {
                kota: $("#kota").val() || "",
				dir: $("#dir").val() || "",
				status: $("#status").val() || [""]
            });
        },
	});

	
});
</script>

<div class="row">
	<div class="col-md-12">
		
		<div class="card ">
			<div class="card-header bg-primary text-white">
				Daftar User
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
					<!--<button class='btn btn-info btn-sm ml-auto modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/create"); ?>'
							title='<?php echo $this->lang->line($controller_name . '_new'); ?>'>
						<span class="glyphicon glyphicon-tag">&nbsp;</span><?php echo $this->lang->line($controller_name. '_new'); ?>
					</button>-->
					<a href="<?php echo site_url($controller_name."/create"); ?>" class="btn btn-info btn-sm btn-round"><i class="fa fa-plus"></i> <?php echo $this->lang->line($controller_name . '_new'); ?> </a>
					
				</div>
				<div class="row form-horizontal">
					<div class="col-sm-12 col-lg-8 border-0">
						<div class="form-group form-group-sm row">

							<?php echo form_label('Pilih Kota', 'label_kota', array('class'=>'required col-form-label col-sm-2')); ?>
							<div class='col-sm-3'>
								<?php echo form_dropdown('kota', $cities, '', 'class="form-control form-control-sm" id="kota"');?>
							</div>
						</div>
						<div class="form-group form-group-sm row">

							<?php echo form_label('Pilih Unit Kerja', 'label_dir', array('class'=>'required col-form-label col-sm-2')); ?>
							<div class='col-sm-8'>
								<?php echo form_dropdown('dir', array(), '', 'class="form-control form-control-sm" id="dir"');?>
							</div>
						</div>
					</div>
				</div>
				
				<div id="toolbar">
					<div class="float-left form-inline" role="toolbar">
						
						<button id="delete" class="btn btn-soft-info btn-sm">
							<span class="fa fa-trash">&nbsp;</span><?php echo $this->lang->line("common_delete"); ?>
						</button>
						&nbsp;
						<button id="nonaktif" class="btn btn-soft-info btn-sm">
							Non Aktifkan
						</button>
						
						&nbsp;Status : &nbsp;
						<?php echo form_multiselect('status[]', array('0'=>'Tidak Aktif','1'=>'Aktif'), '', array('id'=>'status', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>'All', 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-soft-info btn-sm', 'data-width'=>'fit')); ?>
						
					</div>
				</div>
				<div id="table_holder">
					<table id="table" class="table table-striped"></table>
				</div>
				
			</div>
		</div>
	</div>
</div>


<?php $this->load->view("partial/footer"); ?>
