<?php $this->load->view("partial/header"); ?>
<link href="<?php echo base_url(); ?>hc/Content/datepicker.css" rel="stylesheet" />
<!-- <script src="<?php echo base_url(); ?>hc/Scripts/jquery-3.4.1.min.js"></script>-->
<script src="<?php echo base_url(); ?>hc/Scripts/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>hc/Scripts/JqueryCustomValidate.js"></script>
<script src="<?php echo base_url(); ?>hc/HighChart/highcharts.js"></script>
<script src="<?php echo base_url(); ?>hc/HighChart/highcharts-more.js"></script>
<script src="<?php echo base_url(); ?>hc/HighChart/modules/data.js"></script>
<script src="<?php echo base_url(); ?>hc/HighChart/modules/drilldown.js"></script>
<!--<script src="<?php echo base_url(); ?>hc/Scripts/moment.js"></script>-->
<script src="<?php echo base_url(); ?>hc/Scripts/DatetimePicker/tempusdominus-bootstrap-4.js"></script>
<script src="<?php echo base_url(); ?>hc/Scripts/datepicker.js"></script>
<script src="<?php echo base_url(); ?>hc/Scripts/Crypto/crypto-js.min.js"></script>
<script src="<?php echo base_url(); ?>hc/Scripts/Crypto/core.js"></script>
<script src="<?php echo base_url(); ?>hc/Scripts/Crypto/lib-typedarrays.js"></script>
<script src="<?php echo base_url(); ?>hc/Scripts/Crypto/aes.js"></script>

<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo site_url('home');?>">Home</a></li>
					<li class="breadcrumb-item"><a href="#">Grafik</a></li>
					<li class="breadcrumb-item active">Grafik PPID</a></li>
				</ol>
			</div>
			<h4 class="page-title"></h4>
		</div>
	</div>
</div>
<?php $this->load->view('graphs/js-chart-bar'); ?>
<?php $this->load->view('graphs/js-chart-bar-vertical'); ?>
<?php $this->load->view('graphs/js-chart-pie'); ?>

<script type="text/javascript">

$(document).ready(function()
{
	$('#process_btn').on('click', function(e)
	{
		$.remember({ name: 'grafik.tgl1', value: $('#tgl1').val() })
		$.remember({ name: 'grafik.tgl2', value: $('#tgl2').val() })
        $.remember({ name: 'grafik.grafik', value: $('#grafik').val() })
		
		switch($('#grafik').val()){
			

			case "11":
				$.ajax({
					url: '<?php echo site_url('Graphs_ppid/rataanwaktulayananppid') ?>',
					data: {
						//tgl1	: $("#tgl1").val(),
						//tgl2	: $("#tgl2").val(),
						tgl1: moment($("#tgl1").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "",
						tgl2: moment($("#tgl2").val(),'DD/MM/YYYY').format('YYYY-MM-DD') || "",
						kota	: $('#kota').val(),
						jenislayanan	: $('#jenislayanan').val(),
						datasource		: $('#datasource').val(),
						klasifikasi		: $('#klasifikasi').val(),
						subklasifikasi	: $('#subklasifikasi').val(),
						kategori		: $('#kategori').val(),
						<?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
					},
					type: 'POST',
					success: function (Result) {
						//console.log(Result);
						var DataChart = JSON.parse(Result);
						
						var subtitle = 'Tanggal '+ $("#tgl1").val() + ' s.d ' + $("#tgl2").val();
						CreateBarChartV("graphs_holder",  DataChart, DataChart[0].name, subtitle, 'Balai/Loka', 'HK');
						//console.log(DataChart[1].data);
						
						$('#table').bootstrapTable({
							  columns: [
							  {
								title: 'No.',
								align: 'center',
								formatter: function (value, row, index) {
									return index + 1;
								}
							  },
							  {
								field: 'name',
								title: 'Balai/Loka'
							  }, {
								field: 'y',
								title: 'Rata-rata Waktu Penyelesaian (HK)',
								align: 'center'
							  }],
							  data: DataChart[1].data,
							 
							});
						$('#table').bootstrapTable("load", DataChart[1].data);
						$('#table').show();
						
					},
					error: function (request, status, error) {
						//alert('error');
						//alert(request.responseText);
					}
				});
				break;

			case "12":
				$.ajax({
					url: '<?php echo site_url('Graphs_ppid/statusTanggapan') ?>',
					data: {
						tgl1	: $("#tgl1").val(),
						tgl2	: $("#tgl2").val(),
						kota	: $('#kota').val(),
						jenislayanan	: $('#jenislayanan').val(),
						datasource		: $('#datasource').val(),
						klasifikasi		: $('#klasifikasi').val(),
						subklasifikasi	: $('#subklasifikasi').val(),
						kategori		: $('#kategori').val(),
						<?php echo $this->security->get_csrf_token_name(); ?>: csrf_token()
					},
					type: 'POST',
					success: function (Result) {
						var DataChart = JSON.parse(Result);
						var subtitle = 'Tanggal '+ $("#tgl1").val() + ' s.d ' + $("#tgl2").val();
						CreateBarChart("graphs_holder", DataChart, 'Grafik PPID Jumlah Layanan Berdasarkan Status Layanan', subtitle, '', 'Jumlah Layanan');
						$('#table').hide();
						//$('#table').show();
					},
					error: function (request, status, error) {
						//alert('error');
						//alert(request.responseText);
					}
				});
				break;
		}
		
    });	
	if($.remember({ name: 'grafik.tgl1' }) != null) 
	{
		$('#tgl1').val($.remember({ name: 'grafik.tgl1' }));
	}
	else
	{
		$("#tgl1").val("<?php echo date('01/m/Y'); ?>");
	}
	
	if($.remember({ name: 'grafik.tgl2' }) != null) 
	{
		$('#tgl2').val($.remember({ name: 'grafik.tgl2' }));
	}
	else
	{
		$("#tgl2").val("<?php echo date('d/m/Y'); ?>");
    }
    
});

</script>



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
                    $this->load->view("graphs/component-graphs-header-ppid");
                ?>
				<div class="col-md-10">
					<div id="graphs_holder">
					</div>
				</div>
				<div class="col-md-4">
					<div id="toolbar">
						<div class="float-left form-inline" role="toolbar">
						</div>
					</div>
					
					<div id="table_holder">
						<table id="table" class="table table-striped w-100" data-show-export="true" data-toolbar="#toolbar"></table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
	
	
	$("#tgl1").datepicker(
		{format : 'dd/mm/yyyy',
            todayHighlight: true,
            endDate: new Date(),
            autoclose: true,
            maxdate: '0'}
	);
	$("#tgl2").datepicker(
		{format : 'dd/mm/yyyy',
            todayHighlight: true,
            endDate: new Date(),
            autoclose: true,
            maxdate: '0'}
	);
	

});
</script>
<?php $this->load->view("partial/footer"); ?>
