<?php $this->load->view("partial/header"); ?>

<section class="content-header">
  <h1>
	Grafik Evaluasi SPM
	<small></small>
  </h1>
  <ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	<li><a href="#"> Laporan</a></li>
	<li class="active">Grafik Evaluasi SPM</li>
  </ol>
</section>
<section class="content">
<div class="row">
	<div class="col-xs-12">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title"></h3>
			</div>
			<div class="box-body">
			  <?php echo form_open($controller_name . '/grafik', array('id'=>'score_form', 'class'=>'form-horizontal')); ?>
				<div class="row">
				  <div class="form-group form-group-sm">
					<?php echo form_label('Periode Penilaian', 'spm_id', array('class'=>'control-label col-xs-4')); ?>
					<div class='col-xs-3'>
						<?php echo form_dropdown('spm_id', $spms, $spm_id, 'class="form-control input-sm" id="spm_id"'); ?>
						<?php /*echo form_dropdown('tahun', $spms, $tahun, 'class="form-control input-sm" id="tahun"');*/ ?>
					</div>
					<div class="col-xs-1">
						<button type="submit" class="btn btn-primary btn-sm">Proses</button>
					</div>
				  </div>
				</div>
				
				<div class="row">
				  
					<div class='col-xs-9'>
						<p align="center">
							
							
						</p>
					</div>
				 
				</div>
				
				<?php echo form_close(); ?>
			</div>
		</div>
	
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Grafik Evaluasi SPM</h3>
			</div>
			<div class="box-body">
			  <div class="chart">
                <canvas id="lineChart"></canvas>
              </div>
			</div>
		</div>
	</div>
</div>
</section>
<script src="<?php echo base_url()?>js/chartjs/2.7.2/Chart.bundle.min.js"></script>
<script src="<?php echo base_url()?>js/chartjs/2.7.2/chartjs-plugin-datalabels.js"></script>
<script>
$(function () {
	var kota = [<?php echo implode(',', $city_array); ?>];
	var config = {
		type: 'line',
		data: {
			labels: kota,
			datasets: [{
				label: 'SPM',
				fill: false,
				backgroundColor: "rgba(60,141,188,1)",
				borderColor: "rgba(60,141,188,1)",
				data: [<?php echo isset($score_array)?implode(',', $score_array):'';?>]
			}]
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'IMPLEMENTASI SPM <?php echo $tahun; ?>'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			layout: {
				padding: {
					left: 20,
					right: 20,
					top: 20,
					bottom: 20
				}
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: ''
					},
					gridLines: {
						display:false
					},
					ticks: {
						autoSkip: false
					},
				}],
				yAxes: [{
					display: false,
					scaleLabel: {
						display: true,
						labelString: ''
					},
					gridLines: {
						display:false
					},
					ticks: {
						beginAtZero: false,
					}
				}]
			},
			plugins: {
				datalabels: {
					/*backgroundColor: function(context) {
						return context.dataset.backgroundColor;
					},
					borderRadius: 2,
					color: 'white',*/
					align: 'top',
					anchor: 'end',
					font: {
						weight: 'bold'
					},
				}
			},
		}
		
	};
	
	window.onload = function() {
			var ctx = document.getElementById('lineChart').getContext('2d');
			window.myLine = new Chart(ctx, config);
		};

    
});
</script>
<?php $this->load->view("partial/footer"); ?>