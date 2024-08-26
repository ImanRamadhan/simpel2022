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
							<p><a href="<?php echo base_url().'excels/download_template_upload' ?>" class="btn btn-icon btn-info"><i class="far fa-file"><b>&nbsp;Download File Template</b></i></a>
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
							<button data-type="1" class="btn btn-warning" id="buttonProses" enabled>Upload</button>
                            <button data-type="2" class="btn btn-success" id="buttonSend" disabled> Kirim</button>
						</div>
					</div>
					
                 
				  <br />
				  <br />
                  <p><i>*) Data yang diunggah akan masuk ke halaman Drafts Saya</i></p>
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#form-data").hide();
        $('#fileExcel').on('change',function(){
            $('#buttonSend').removeAttr("disabled")
        })
        $('#buttonProses , #buttonSend').click(function() {
            var type = $(this).data('type')
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
                fd.append('type', type);
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
                            if(parseInt(type) == 2){
                                setTimeout(function () {window.location = "<?php echo site_url('tickets') ?>";}, 2000);
                            }
                            if(parseInt(type) == 1){
                                setTimeout(function () {window.location = "<?php echo site_url('drafts') ?>";}, 2000);
                            }
                        } else if (data.status == 'F') {
                            //swal("Error!", data.msg, "error");
                            $.notify( data.msg, { type: 'danger', delay:10000 });
                            console.log(data.msg);
                        }
                    },
                });  
            } else {
                console.log('Tipe file');
                
            }      
        });

    })
</script>