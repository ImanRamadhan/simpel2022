<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Layanan</a></li>
					<li class="breadcrumb-item active"><?php echo $page_title; ?></li>
				</ol>
			</div>
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
        resource: "<?php echo site_url('drafts_temp');?>",
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
    <div class="col-lg-12">
        <div class="error_message_box">
            <ul id="error_message_box" class="error_message_box alert-danger">
                
            </ul>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <?php echo $page_title; ?>
            </div>
            <div class="card-body">
                
					<div class="form-group form-group-sm row">
						<div class='col-sm-4'>
							<p><a href="<?php echo base_url().'excels/download_template_upload' ?>" class="btn btn-icon btn-dark"><i class="far fa-file"></i></a>
                        <label style="color:red">Silahkan Download File Template Excel Upload !</label></p>
						</div>
					</div>
					<div class="form-group form-group-sm row">
						<?php echo form_label('Pilih File', 'label_file', array('class'=>'required col-form-label col-sm-2')); ?>
						<div class='col-sm-4'>
							<input id="fileExcel" name="fileExcel" type="file" class="form-control" accept=".xlsx">
						</div>
					</div>
					<div class="form-group form-group-sm row">
						<?php echo form_label('', 'label', array('class'=>'required col-form-label col-sm-2')); ?>
						<div class='col-sm-10'>
							<button class="btn btn-warning" id="buttonProses" enabled>Upload</button>
						</div>
					</div>
					
                   
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
    $(document).ready(function() {
        $("#form-data").hide();

        $('#buttonProses').click(function() {
            var fileUpload = $('#fileExcel').prop('files')[0];
            if (fileUpload == undefined)
            {
                alert("Harap Input File Excel yang akan diupload!");
                return;
            }

            var fileSize = fileUpload.size;
            var ext = fileUpload.name.substring(fileUpload.name.indexOf('.') + 1).toLowerCase();
            //const files = document.querySelector('[type=file]').files
            const files = document.querySelector('#fileExcel').files;
            
            if (ext == 'xlsx') {
                var fd = new FormData();
                for (let i = 0; i < files.length; i++) {
                    let file = files[i];

                    fd.append('files[]', file);
                };
                fd.append('<?php echo $this->security->get_csrf_token_name(); ?>', csrf_token() );
				
                $.ajax({
                    type: 'POST',
                    url: '<?php echo site_url('excels/upload_file_template_xls') ?>',
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    data: fd,
                    success: function(data) {
						console.log(data);
                        if (data.status == 'S') {
                            $.notify( data.msg, { type: 'success' });
							//table_support.refresh();

                            setTimeout(function () {window.location = "<?php echo site_url('drafts_temp/uploadxls') ?>";}, 2000);
                        } else if (data.status == 'F') {
                            //swal("Error!", data.msg, "error");
                            $.notify( data.msg, { type: 'danger', delay:10000 });
                            console.log(data.msg);
                        }
                    },
                    error: function(data) {
                        //console.log(data);
                        //swal("Error!", "Something went wrong!", "error");
                        //$.notify(data.responseText, { type: 'danger' , delay:10000});
                        //console.log("masuk error " + data.responseText);
                        //console.log(data);
                    }
                });  
            } else {
                console.log('Tipe file');
                
            }      
        });

    })
</script>