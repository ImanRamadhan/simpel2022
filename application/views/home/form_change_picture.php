<link rel="stylesheet" href="<?php echo base_url('plugins/jcrop')?>/jquery.Jcrop.min.css" type="text/css" />
<script src="<?php echo base_url('plugins/jcrop')?>/jquery.Jcrop.min.js"></script>
<style>
.bgColor {
    width: 100%;
    height: 150px;
    background-color: #fff4be;
    border-radius: 4px;
    margin-bottom: 30px;
}

.inputFile {
    padding: 5px;
    background-color: #FFFFFF;
    border: #F0E8E0 1px solid;
    border-radius: 4px;
}

.btnSubmit {
    background-color: #696969;
    padding: 5px 30px;
    border: #696969 1px solid;
    border-radius: 4px;
    color: #FFFFFF;
    margin-top: 10px;
}

#uploadFormLayer {
    padding: 20px;
}

input#crop {
    padding: 5px 25px 5px 25px;
    background: lightseagreen;
    border: #485c61 1px solid;
    color: #FFF;
    visibility: hidden;
}

#cropped_img {
    margin-top: 40px;
}
</style>
<div class="bgColor">
<?php echo form_open('home/do_change_picture', array('id'=>'upload_form', 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data')); ?>
	<div id="uploadFormLayer">
		<input name="userImage" id="userImage" type="file"	class="inputFile">
		<input type="submit" name="upload" value="Upload" class="btnSubmit">
	</div>
	<input type="hidden" name="x" id="x" />
	<input type="hidden" name="y" id="y" />
	<input type="hidden" name="w" id="w" />
	<input type="hidden" name="h" id="h" />
	<span style="font-size:90%"><i>Tipe berkas: jpg, png, gif <?php 
	$upload_setting = $this->config->item('upload_setting');
	?> Maks: <?php echo round($upload_setting['max_size']/1024); ?>MB</i></span>
<?php echo form_close(); ?>
</div>
<div>
    <img src="<?php echo $imagePath; ?>" id="cropbox" class="img" /><br />
</div>
<div id="btn">
    <input type='button' id="crop" value='CROP'>
</div>
<div>
    <img src="#" id="cropped_img" style="display: none;">
</div>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	var size;
    $("#crop").click(function(){
        var img = $("#cropbox").attr('src');
        $("#cropped_img").show();
        $("#cropped_img").attr('src','<?php echo site_url('home/image_crop');?>?x='+size.x+'&y='+size.y+'&w='+size.w+'&h='+size.h+'&img='+img);
		$('#x').val(size.x);
		$('#y').val(size.y);
		$('#w').val(size.w);
		$('#h').val(size.h);
    });
	
	$('#upload_form').validate($.extend({
		submitHandler: function(form)
		{
			$(form).ajaxSubmit({
				success: function(response)
				{
					if(response.cropped)
					{
						dialog_support.hide();
						window.location.reload(true);
					}
					else
					{
						$("#cropbox").attr('src', response.imagepath);
						$('#cropbox').Jcrop({
						  aspectRatio: 1,
						  boxWidth: 600, 
						  boxHeight: 600,
						  onSelect: function(c){
						   size = {x:c.x,y:c.y,w:c.w,h:c.h};
						   $("#crop").css("visibility", "visible");     
						  }
						});
					}
				},
				dataType: 'json'
			});
		},

		rules:
		{
			
   		},

		messages: 
		{
         		     		
		}
	}, form_support.error));
});
</script>
