<?php $this->load->view("partial/header"); ?>

<script src="<?php echo base_url()?>assets/js/jquery.fileDownload.js"></script>
<style>

</style>


<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Database</a></li>
					<li class="breadcrumb-item active">Monitoring Balai</li>
				</ol>
			</div>
			<h4 class="page-title"></h4>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
	$('#buttonExport').on('click', function(event){
		event.preventDefault();
		
		$.fileDownload('<?php echo site_url('excels/download_monbalai') ?>', {
            data: {
                tgl1        : $("#tgl1").val(),
                tgl2        : $("#tgl2").val(),
              
				<?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
            },
            httpMethod: 'POST',
            success: function (Result) {

            },
            error: function (request, status, error) {
                //alert('error');
               // alert(request.responseText);
            }
        });
	});
	/*if($.remember({ name: 'database.tgl1' }) != null) 
	{
		$('#tgl1').val($.remember({ name: 'database.tgl1' }));
	}
	else
	{
		$("#tgl1").val("<?php echo date('01/m/Y'); ?>");
	}
	
	if($.remember({ name: 'database.tgl2' }) != null) 
	{
		$('#tgl2').val($.remember({ name: 'database.tgl2' }));
	}
	else
	{
		$("#tgl2").val("<?php echo date('d/m/Y'); ?>");
    }*/
});
</script>

<style>
.table-responsive{height:450px;overflow:scroll;} 
thead tr:nth-child(1) th{
	position: sticky;
	position: -webkit-sticky; 
	top: 0;
	z-index: 100;
	
}

thead tr.second th {
	position: sticky;
	position: -webkit-sticky; 
	z-index: 100;
	
}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header bg-primary text-white">
				<?php echo $title;?>
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
				</div>
                <?php 
                    $this->load->view("databases/component-databases-header-monbalai");
                ?>
				<div id="table_holder">
                    
                    <div class="card-header bg-primary text-white">
                        Monitoring Balai
                        
                    </div>
                    <table id="DatatableResp" class="table table-striped table-bordered">
                        <thead>
                            <tr class="first">
                                <th rowspan="2" style="vertical-align : middle;text-align:center;">No</th>
                                <th rowspan="2" style="vertical-align : middle;text-align:center;">Nama Balai</th>
								<th rowspan="2" style="vertical-align : middle;text-align:center;">Jml Layanan</th>
								<th rowspan="2" style="vertical-align : middle;text-align:center;">Status Open</th>
								<th rowspan="2" style="vertical-align : middle;text-align:center;">Status Closed</th>
								<th rowspan="2" style="vertical-align : middle;text-align:center;">Belum TL</th>
								<th rowspan="2" style="vertical-align : middle;text-align:center;">Sudah TL</th>
                                <th rowspan="2" style="vertical-align : middle;text-align:center;">Rata2 Waktu Layanan</th>
								<th rowspan="2" style="vertical-align : middle;text-align:center;">Pemenuhan SLA</th>
                                
                            </tr>
                            
                        </thead>
                        <tbody>
						<?php 
						$i = 0;
						foreach($layanan as $row):
						?>
						<tr >
							<td style="width:2%"><?php echo ++$i; ?></td>
							<td style="width:10%" nowrap><?php echo $row['nama_balai']; ?></td>
							<td style="width:10%;text-align:center;"><?php echo $row['total']; ?></td>
							<td style="width:10%;text-align:center;"><?php echo ($row['total'] - $row['sts_closed']); ?></td>
							<td style="width:10%;text-align:center;"><?php echo $row['sts_closed']; ?></td>
							<td style="width:10%;text-align:center;"><?php echo ($row['total'] - $row['tl']); ?></td>
							<td style="width:10%;text-align:center;"><?php echo $row['tl']; ?></td>
							<td style="width:10%;text-align:center;"><?php echo number_format($row['rata'],2); ?></td>
							<td style="width:10%;text-align:center;">
							<?php 
							if($row['total']>0)
								echo ($row['sla_yes']*100/$row['total']).'%'; 
							else
								echo '';
							?></td>
						</tr>
						<?php endforeach;?>
                        </tbody>
                    </table>

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    
   //var firstheight = $('.first').height();
    //    $("thead tr.second th").css("top", firstheight-2);
});
</script>
<?php $this->load->view("partial/footer"); ?>
