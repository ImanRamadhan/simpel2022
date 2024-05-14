<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/morris/morris.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/raphael/raphael-min.js"></script>
<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	$('#date1').datepicker({format: 'dd/mm/yyyy', orientation:'bottom'});
	$('#date2').datepicker({format: 'dd/mm/yyyy', orientation:'bottom'});
	
	$.getJSON('<?php echo site_url('dashboard_balai/layanan_all');?>', function (data) {
		$('#layanan_open').html(data.open);
		$('#layanan_replied').html(data.replied);
		$('#layanan_closed').html(data.closed);
		$('#layanan_total').html(data.total);
		
		Morris.Donut({
			element: 'donut-layanan',
			data: [
			  {label: "Open", value: data.open},
			  {label: "Closed", value: data.closed},
			  {label: "Replied", value: data.replied}
			],
			resize: true,
			colors:[ '#e3eaef', '#ff679b', '#777edd'], 
			labelColor: '#888',
			backgroundColor: 'transparent',
			fillOpacity: 0.1,
			formatter: function (x) { return x + ""}
		});
		
	});
	
	/*$.getJSON('<?php echo site_url('dashboard_balai/layanan_total');?>', function (data) {
		$('#layanan_total').html(data);
	});
	
	$.getJSON('<?php echo site_url('dashboard_balai/layanan_open');?>', function (data) {
		$('#layanan_open').html(data);
	});
	
	$.getJSON('<?php echo site_url('dashboard_balai/layanan_replied');?>', function (data) {
		$('#layanan_replied').html(data);
	});
	
	$.getJSON('<?php echo site_url('dashboard_balai/layanan_closed');?>', function (data) {
		$('#layanan_closed').html(data);
	});*/
	
	$.getJSON('<?php echo site_url('dashboard_balai/layanan_saya');?>', function (data) {
		
		$('#layanan_saya_status_open').html(data.open);
		$('#layanan_saya_status_closed').html(data.closed);
		//$('#layanan_saya_status_replied').html(data.replied);
		
		if(!(data.open == "0" && data.closed == "0" && data.replied == "0"))
		{
		
			Morris.Donut({
				element: 'donut-layanan-saya',
				data: [
				  {label: "Open", value: data.open},
				  {label: "Closed", value: data.closed},
				 // {label: "Replied", value: data.replied}
				],
				resize: true,
				colors:[  '#ff679b', '#08ab9b','#777edd' ], 
				labelColor: '#888',
				backgroundColor: 'transparent',
				fillOpacity: 0.1,
				formatter: function (x) { return x + ""}
			});
		}
	});
	
	/*$.getJSON('<?php echo site_url('dashboard_balai/layanan_saya_open');?>', function (data) {
		$('#layanan_saya_open').html(data);
	});
	
	$.getJSON('<?php echo site_url('dashboard_balai/layanan_saya_closed');?>', function (data) {
		$('#layanan_saya_closed').html(data);
	});
	
	$.getJSON('<?php echo site_url('dashboard_balai/layanan_saya_total');?>', function (data) {
		$('#layanan_saya_total').html(data);
	});*/
	
	//rujukan
	/*$.getJSON('<?php echo site_url('dashboard_balai/rujukan_masuk');?>', function (data) {
		$('#rujukan_masuk').html(data);
	});
	
	$.getJSON('<?php echo site_url('dashboard_balai/rujukan_masuk_blm_dijawab');?>', function (data) {
		$('#rujukan_masuk_blm_dijawab').html(data);
	});
	
	$.getJSON('<?php echo site_url('dashboard_balai/rujukan_masuk_sudah_dijawab');?>', function (data) {
		$('#rujukan_masuk_sudah_dijawab').html(data);
	});*/
	
	$.getJSON('<?php echo site_url('dashboard_balai/rujukan_masuk_chart');?>', function (data) {
		$('#rujukan_masuk_blm_dijawab').html(data.blm_jawab);
		$('#rujukan_masuk_sudah_dijawab').html(data.sdh_jawab);
		
		if(data.sdh_jawab > 0 || data.blm_jawab > 0)
		{
			Morris.Donut({
				element: 'donut-rujukan-masuk',
				data: [
				  {label: "Belum Dijawab", value: data.blm_jawab},
				  {label: "Sudah Dijawab", value: data.sdh_jawab},
				  
				],
				resize: true,
				colors:['#ff679b', '#777edd'], 
				labelColor: '#888',
				backgroundColor: 'transparent',
				fillOpacity: 0.1,
				formatter: function (x) { return x + ""}
			});
		}
	});
	
	$.getJSON('<?php echo site_url('dashboard_balai/rujukan_keluar_chart');?>', function (data) {
		$('#rujukan_keluar_blm_dijawab').html(data.blm_jawab);
		$('#rujukan_keluar_sudah_dijawab').html(data.sdh_jawab);
		
		Morris.Donut({
			element: 'donut-rujukan-keluar',
			data: [
			  {label: "Belum Dijawab", value: data.blm_jawab},
			  {label: "Sudah Dijawab", value: data.sdh_jawab},
			  
			],
			resize: true,
			colors:['#ff679b', '#777edd'], 
			labelColor: '#888',
			backgroundColor: 'transparent',
			fillOpacity: 0.1,
			formatter: function (x) { return x + ""}
		});
		
	});
	
	/*$.getJSON('<?php echo site_url('dashboard_balai/rujukan_keluar');?>', function (data) {
		$('#rujukan_keluar').html(data);
	});
	
	$.getJSON('<?php echo site_url('dashboard_balai/rujukan_keluar_blm_dijawab');?>', function (data) {
		$('#rujukan_keluar_blm_dijawab').html(data);
	});
	
	$.getJSON('<?php echo site_url('dashboard_balai/rujukan_keluar_sudah_dijawab');?>', function (data) {
		$('#rujukan_keluar_sudah_dijawab').html(data);
	});*/
	
	$.getJSON('<?php echo site_url('dashboard_balai/belum_tl');?>', function (data) {
		$('#belum_tl_red').html(data.red);
		$('#belum_tl_orange').html(data.orange);
		$('#belum_tl_black').html(data.black);
		$('#belum_tl_green').html(data.green);
		Morris.Donut({
			element: 'donut-belum-tl',
			data: [
			  {label: "SLA <= 5 HK", value: data.red},
			  {label: "SLA 5 - 14 HK", value: data.orange},
			  {label: "SLA 14 - 60 HK", value: data.green},
			  {label: "Melewati SLA", value: data.black}
			],
			resize: true,
			colors:[ 'red', 'orange', 'green', 'black'], 
			labelColor: '#888',
			backgroundColor: 'transparent',
			fillOpacity: 0.1,
			formatter: function (x) { return x + ""}
		});

	});
	
	
	
});
</script>