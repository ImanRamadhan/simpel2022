<?php echo form_open($url_post, array('id' => 'confirm_form_upload_sigend_berkas', 'class' => 'form-horizontal')); ?>
<div class="row">
  <div class="col-sm-12">
    <div class="col-sm-8">
      <div class="dropzone dz-clickable" id="dragndrop-bukti">
        <div class="dz-default dz-message"><span>Drop file di sini untuk upload</span></div>
      </div>
    </div>
  </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
  $(document).ready(function() {
    $('#confirm_form_upload_sigend_berkas').on('submit', function(e){
      // validation code here
      
        e.preventDefault();
        if(thisDropzone.getQueuedFiles().length > 0){
          thisDropzone.processQueue();
          console.log("processing upload file ...")
        }

        $(this).toggle()
    });

    var thisDropzoneBukti;

    $("#dragndrop-bukti").dropzone({
      url: "<?php echo $upload_url; ?>",
      addRemoveLinks: true,
      autoProcessQueue: false,
      parallelUploads: 5,
      dictDefaultMessage: 'Drop file di sini untuk upload',
      uploadMultiple: true,
      init: function(){
        thisDropzone = this;
        this.on("sending", function(file, xhr, formData){
          formData.append("<?php echo $this->security->get_csrf_token_name(); ?>", csrf_token());
          formData.append("mode", 0); 
          formData.append("message", $('#message').val()); 
          formData.append("ticketid", "<?php echo $ticketid?>");
          formData.append("id", "<?php echo $id;?>");
        });
        this.on("success", function(file, response) {
          console.log(file)
          //$.fn.load_attachments();
          this.removeAllFiles();
          
          $.notify('Berkas Bukti TL berhasil dikirim', { type: 'success' });

          setTimeout(function(){location.reload();}, 1000);
          //location.reload();
          //var data = $.parseJSON(response);
          //if(data.error)
            //$.notify(data.message, { type: 'danger' });
            //alert(data.message);
        });
        this.on("addedfile", function(file) {
        });
        
        this.on("removedfile", function(file) {			  
        });
        
        this.on("error", function(file, message) {
        alert('Terjadi error pada saat proses upload');
        });
      }


    });
  });
</script>