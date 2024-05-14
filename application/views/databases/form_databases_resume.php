<?php $this->load->view("partial/header"); ?>

<script src="<?php echo base_url()?>assets/js/jquery.fileDownload.js"></script>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0);">Database</a></li>
					<li class="breadcrumb-item active">Resume Harian</li>
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
		var tgl1 = moment($("#tgl1").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "";
		var	tgl2 = moment($("#tgl2").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "";
		$.fileDownload('<?php echo site_url('excels_new/download_resume') ?>', {
            
            data: {
                tgl1        : tgl1,
                tgl2        : tgl2,
                kota        : $("#kota").val() || "",
                kategori    : $("#kategori").val(),
                jenis  		: $("#datasource").val(),
                //type        : "3",
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
	if($.remember({ name: 'database.tgl1' }) != null) 
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
    }
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
		<div class="card ">
			<div class="card-header bg-primary text-white">
				<?php echo $title;?>
			</div>
			<div class="card-body">
				<div id="title_bar" class="btn-toolbar float-right">
				</div>
                <?php 
                    $this->load->view("databases/component-databases-header-resume");
                ?>
				<div id="table_holder">
                    
                    <div class="card-header bg-primary text-white">
                        Resume Harian
                    </div>
                    <table id="DatatableResp" class="table table-responsive table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr class="first">
                                <th rowspan="2" style="vertical-align : middle;text-align:center;">No</th>
                                <th rowspan="2" style="vertical-align : middle;text-align:center;">Identitas Konsumen</th>
                                <th colspan="2" style="vertical-align : middle;text-align:center;">Layanan</th>
                                <th colspan="2" style="vertical-align : middle;text-align:center;">Jawaban</th>
                                <th rowspan="2" style="vertical-align : middle;text-align:center;">Keterangan</th>
                            </tr>
                            <tr class="second">
                                <th style="vertical-align : middle;text-align:center; width:20px;">Permintaan Informasi</th>
                                <th style="vertical-align : middle;text-align:center; width:20px;">Pengaduan</th>
                                <th style="vertical-align : middle;text-align:center; width:20px;">Permintaan Informasi</th>
                                <th style="vertical-align : middle;text-align:center; width:20px;">Pengaduan</th>
                            </tr>
                        </thead>
                        <tbody>
						<?php 
						$i = 0;
						foreach($layanan as $row):
						?>
						<tr >
							<td><?php echo ++$i; ?></td>
							<td style="width:10%"><?php echo $row['identitas_konsumen']; ?></td>
							<td style="width:200px" class=""><?php echo ($row['info']=='I')?$row['prod_masalah']:''; ?></td>
							<td width="width:200px" class=""><?php echo ($row['info']=='P')?$row['prod_masalah']:''; ?></td>
							<td width="width:200px" class=""><?php echo ($row['info']=='I')?$row['jawaban']:''; ?></td>
							<td width="width:200px" class=""><?php echo ($row['info']=='P')?$row['jawaban']:''; ?></td>
							<td><?php echo (($row['keterangan']=='0')?'':$row['keterangan']); ?></td>
							
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

	var firstheight = $('.first').height();
    $("thead tr.second th").css("top", firstheight-2);	
	
	$('#process_btn').on('click', function(e)
	{
		$.remember({ name: 'database.tgl1', value: $('#tgl1').val() });
		$.remember({ name: 'database.tgl2', value: $('#tgl2').val() });
		
		
	});
	
   /* $('#process_btn').on('click', function(e)
	{
		$.remember({ name: 'lapsing.tgl1', value: $('#tgl1').val() })
		$.remember({ name: 'lapsing.tgl2', value: $('#tgl2').val() })

        
        //tblDataResp.destroy();
        var tblDataResp = $('#DatatableResp').DataTable({
            columnDefs: [
                { defaultContent: '-', targets: '_all' },
                { data: 'id', targets: 0, visible:true, sortable: false, orderable: false },
                { data: 'identitas_konsumen', targets: 1, sortable: false, orderable: false },
                {
                    render: function (data, type, row) {
                        if(row.info == "I"){
                            return row.prod_masalah;
                        } else {
                            return '-';
                        }
                    },
                    targets: 2, className: 'dt-center', width: "20%"
                },
                {
                    render: function (data, type, row) {
                        if(row.info == "P"){
                            return row.prod_masalah;
                        } else {
                            return '-';
                        }
                    },
                    targets: 3, className: 'dt-center', width: "20%"
                },
                {
                    render: function (data, type, row) {
                        if(row.info == "I"){
                            return row.jawaban;
                        } else {
                            return '-';
                        }
                    },
                    targets: 4, className: 'dt-center', width: "20%"
                },
                {
                    render: function (data, type, row) {
                        if(row.info == "P"){
                            return row.jawaban;
                        } else {
                            return '-';
                        }
                    },
                    targets: 5, className: 'dt-center', width: "20%"
                },
                { data: 'keterangan', targets: 6, sortable: false, orderable: false },
            ],
            serverSide: true,
            bLengthChange: false,
            ordering: false,
            scrollY: 600,
            scrollX: true,
            processing: true,
            ajax: {
                url: '<?php echo site_url('Databases/resume_data') ?>',
                type: 'POST',
                datatype: "json",
                
                data: function (data, callback, settings ) {
                    data.tgl1 = $("#tgl1").val(),
                    data.tgl2 = $("#tgl2").val(),
                    data.kota = $("#kota").val(),
                    data.kategori = $("#kategori").val(),
                    data.datasource = $("#datasource").val(),
                    data.length = data.length,
                    data.start = data.start,
                    data.search = data.search.value,
                    data.<?php echo $this->security->get_csrf_token_name(); ?>= csrf_token()
                },
                dataSrc: function (data) {
                    tblDataResp.clear();
                    return data;
                },
                
            },
            
            deferRender: true,
            searchDelay: 350,
            destroy: true
        });
        tblDataResp.on('order.dt search.dt', function () {
            tblDataResp.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });	*/
} );
</script>
<?php $this->load->view("partial/footer"); ?>
