<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/morris/morris.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/raphael/raphael-min.js"></script>
<script type="text/javascript">
	//validation and submit handling
	$(document).ready(function() {
		$('#date1').datepicker({
			format: 'dd/mm/yyyy',
			orientation: 'bottom'
		});
		$('#date2').datepicker({
			format: 'dd/mm/yyyy',
			orientation: 'bottom'
		});

		/*
		$.getJSON('<?php echo site_url('dashboard/layanan_pusat'); ?>', function (data) {
			$('#layanan_pusat').html(data);
		});
		
		$.getJSON('<?php echo site_url('dashboard/layanan_cc'); ?>', function (data) {
			$('#layanan_cc').html(data);
		});
		
		$.getJSON('<?php echo site_url('dashboard/layanan_balai'); ?>', function (data) {
			$('#layanan_balai').html(data);
		});
		
		$.getJSON('<?php echo site_url('dashboard/layanan_total'); ?>', function (data) {
			$('#layanan_total').html(data);
		});
		*/



		$.getJSON('<?php echo site_url('dashboard/ppid_sp4n_all/SP4N'); ?>', function(data) {
			//console.log(data);
			$('#sp4n_pusat').html(data.pusat);
			$('#sp4n_cc').html(data.cc);
			$('#sp4n_balai').html(data.balai);
			$('#sp4n_total').html(data.total);

			Morris.Donut({
				element: 'donut-sp4n',
				data: [{
						label: "Balai",
						value: data.balai
					},
					{
						label: "CC",
						value: data.cc
					},
					{
						label: "Pusat",
						value: data.pusat
					}
				],
				resize: true,
				colors: ['#e3eaef', '#ff679b', '#777edd'],
				labelColor: '#888',
				backgroundColor: 'transparent',
				fillOpacity: 0.1,
				formatter: function(x) {
					return x + ""
				}
			});
		});

		$.getJSON('<?php echo site_url('dashboard/layanan_cc'); ?>', function(data) {
			$('#layanan_cc').html(data);
		});

		$.getJSON('<?php echo site_url('dashboard/layanan_balai'); ?>', function(data) {
			$('#layanan_balai').html(data);
		});

		$.getJSON('<?php echo site_url('dashboard/layanan_total'); ?>', function(data) {
			$('#layanan_total').html(data);
		});

		$.getJSON('<?php echo site_url('dashboard/layanan_all'); ?>', function(data) {
			//console.log(data);
			$('#layanan_pusat').html(data.pusat);
			$('#layanan_cc').html(data.cc);
			$('#layanan_balai').html(data.balai);
			$('#layanan_total').html(data.total);

			Morris.Donut({
				element: 'donut-layanan',
				data: [{
						label: "Balai",
						value: data.balai
					},
					{
						label: "CC",
						value: data.cc
					},
					{
						label: "Pusat",
						value: data.pusat
					}
				],
				resize: true,
				colors: ['#e3eaef', '#ff679b', '#777edd'],
				labelColor: '#888',
				backgroundColor: 'transparent',
				fillOpacity: 0.1,
				formatter: function(x) {
					return x + ""
				}
			});


		});

		$.getJSON('<?php echo site_url('dashboard/ppid_sp4n_all/PPID'); ?>', function(data) {
			//console.log(data);
			$('#ppid_pusat').html(data.pusat);
			$('#ppid_cc').html(data.cc);
			$('#ppid_balai').html(data.balai);
			$('#ppid_total').html(data.total);

			Morris.Donut({
				element: 'donut-ppid',
				data: [{
						label: "Balai",
						value: data.balai
					},
					{
						label: "CC",
						value: data.cc
					},
					{
						label: "Pusat",
						value: data.pusat
					}
				],
				resize: true,
				colors: ['#e3eaef', '#ff679b', '#777edd'],
				labelColor: '#888',
				backgroundColor: 'transparent',
				fillOpacity: 0.1,
				formatter: function(x) {
					return x + ""
				}
			});


		});

		/*
		
		$.getJSON('<?php echo site_url('dashboard/pusat_pengaduan'); ?>', function (data) {
			$('#pusat_pengaduan').html(data);
		});
		
		$.getJSON('<?php echo site_url('dashboard/pusat_informasi'); ?>', function (data) {
			$('#pusat_informasi').html(data);
		});*/

		/*$.getJSON('<?php echo site_url('dashboard/pusat_status_open'); ?>', function (data) {
			$('#pusat_status_open').html(data);
		});
		/*

		$.getJSON('<?php echo site_url('dashboard/pusat_pengaduan'); ?>', function (data) {
			$('#pusat_pengaduan').html(data);
		});
		
		$.getJSON('<?php echo site_url('dashboard/pusat_informasi'); ?>', function (data) {
			$('#pusat_informasi').html(data);
		});*/

		/*$.getJSON('<?php echo site_url('dashboard/pusat_status_open'); ?>', function (data) {
			$('#pusat_status_open').html(data);
		});
		
		$.getJSON('<?php echo site_url('dashboard/pusat_status_closed'); ?>', function (data) {
			$('#pusat_status_closed').html(data);
		});
		
		$.getJSON('<?php echo site_url('dashboard/pusat_status_replied'); ?>', function (data) {
			$('#pusat_status_replied').html(data);
		});
		*/

		/*$.getJSON('<?php echo site_url('dashboard/pusat_blm_feedback'); ?>', function (data) {
			$('#pusat_blm_feedback').html(data);
		});*/

		$.getJSON('<?php echo site_url('dashboard/pusat_layanan'); ?>', function(data) {

			$('#pusat_status_open').html(data.open);
			$('#pusat_status_closed').html(data.closed);
			$('#pusat_status_replied').html(data.replied);

			Morris.Donut({
				element: 'donut-layanan-pusat',
				data: [{
						label: "Open",
						value: data.open
					},
					{
						label: "Closed",
						value: data.closed
					},
					{
						label: "Replied",
						value: data.replied
					}
				],
				resize: true,
				colors: ['#ff679b', '#08ab9b', '#777edd'],
				labelColor: '#888',
				backgroundColor: 'transparent',
				fillOpacity: 0.1,
				formatter: function(x) {
					return x + ""
				}
			});

		});




		/*$.getJSON('<?php echo site_url('dashboard/layanan_saya_pengaduan'); ?>', function (data) {
			$('#layanan_saya_pengaduan').html(data);
		});
		
		$.getJSON('<?php echo site_url('dashboard/layanan_saya_informasi'); ?>', function (data) {
			$('#layanan_saya_informasi').html(data);
		});*/

		$.getJSON('<?php echo site_url('dashboard/layanan_saya'); ?>', function(data) {

			$('#layanan_saya_status_open').html(data.open);
			$('#layanan_saya_status_closed').html(data.closed);
			$('#layanan_saya_status_replied').html(data.replied);

			if (!(data.open == "0" && data.closed == "0" && data.replied == "0")) {

				Morris.Donut({
					element: 'donut-layanan-saya',
					data: [{
							label: "Open",
							value: data.open
						},
						{
							label: "Closed",
							value: data.closed
						},
						{
							label: "Replied",
							value: data.replied
						}
					],
					resize: true,
					colors: ['#ff679b', '#08ab9b', '#777edd'],
					labelColor: '#888',
					backgroundColor: 'transparent',
					fillOpacity: 0.1,
					formatter: function(x) {
						return x + ""
					}
				});
			}
		});

		/*$.getJSON('<?php echo site_url('dashboard/layanan_saya_status_open'); ?>', function (data) {
			$('#layanan_saya_status_open').html(data);
		});
		
		$.getJSON('<?php echo site_url('dashboard/layanan_saya_status_closed'); ?>', function (data) {
			$('#layanan_saya_status_closed').html(data);
		});
		
		$.getJSON('<?php echo site_url('dashboard/layanan_saya_status_replied'); ?>', function (data) {
			$('#layanan_saya_status_replied').html(data);
		});*/

		$.getJSON('<?php echo site_url('dashboard/layanan_saya_blm_feedback'); ?>', function(data) {
			$('#layanan_saya_blm_feedback').html(data);
		});



		$.getJSON('<?php echo site_url('dashboard/rujukan_masuk'); ?>', function(data) {
			$('#rujukan_masuk').html(data);
		});


		/*
		$.getJSON('<?php echo site_url('dashboard/rujukan_masuk_blm_dijawab'); ?>', function (data) {
			$('#rujukan_masuk_blm_dijawab').html(data);
		});
		
		$.getJSON('<?php echo site_url('dashboard/rujukan_masuk_sudah_dijawab'); ?>', function (data) {
			$('#rujukan_masuk_sudah_dijawab').html(data);
		});*/

		$.getJSON('<?php echo site_url('dashboard/rujukan_keluar'); ?>', function(data) {
			$('#rujukan_keluar').html(data);
		});

		$.getJSON('<?php echo site_url('dashboard/rujukan_masuk_chart'); ?>', function(data) {
			$('#rujukan_masuk_blm_dijawab').html(data.blm_jawab);
			$('#rujukan_masuk_sudah_dijawab').html(data.sdh_jawab);

			if (data.sdh_jawab > 0 || data.blm_jawab > 0) {
				Morris.Donut({
					element: 'donut-rujukan-masuk',
					data: [{
							label: "Belum Dijawab",
							value: data.blm_jawab
						},
						{
							label: "Sudah Dijawab",
							value: data.sdh_jawab
						},

					],
					resize: true,
					colors: ['#ff679b', '#777edd'],
					labelColor: '#888',
					backgroundColor: 'transparent',
					fillOpacity: 0.1,
					formatter: function(x) {
						return x + ""
					}
				});
			}
		});

		$.getJSON('<?php echo site_url('dashboard/rujukan_keluar_chart'); ?>', function(data) {
			$('#rujukan_keluar_blm_dijawab').html(data.blm_jawab);
			$('#rujukan_keluar_sudah_dijawab').html(data.sdh_jawab);

			Morris.Donut({
				element: 'donut-rujukan-keluar',
				data: [{
						label: "Belum Dijawab",
						value: data.blm_jawab
					},
					{
						label: "Sudah Dijawab",
						value: data.sdh_jawab
					},

				],
				resize: true,
				colors: ['#ff679b', '#777edd'],
				labelColor: '#888',
				backgroundColor: 'transparent',
				fillOpacity: 0.1,
				formatter: function(x) {
					return x + ""
				}
			});

		});
		/*
		$.getJSON('<?php echo site_url('dashboard/rujukan_keluar_blm_dijawab'); ?>', function (data) {
			$('#rujukan_keluar_blm_dijawab').html(data);
		});
		
		$.getJSON('<?php echo site_url('dashboard/rujukan_keluar_sudah_dijawab'); ?>', function (data) {
			$('#rujukan_keluar_sudah_dijawab').html(data);
		});*/

		$.getJSON('<?php echo site_url('dashboard/layanan_pusat_tl'); ?>', function(data) {
			$('#pusat_sudah_tl').html(data.done);
			$('#pusat_belum_tl').html(data.notdone);
			Morris.Donut({
				element: 'donut-layanan-tl',
				data: [{
						label: "Sudah di-TL",
						value: data.done
					},
					{
						label: "Belum di-TL",
						value: data.notdone
					}
				],
				resize: true,
				colors: ['#777edd', '#ff679b'],
				labelColor: '#888',
				backgroundColor: 'transparent',
				fillOpacity: 0.1,
				formatter: function(x) {
					return x + ""
				}
			});

		});
		/*
		$.getJSON('<?php echo site_url('dashboard/rujukan_keluar_blm_dijawab'); ?>', function (data) {
			$('#rujukan_keluar_blm_dijawab').html(data);
		});
		
		$.getJSON('<?php echo site_url('dashboard/rujukan_keluar_sudah_dijawab'); ?>', function (data) {
			$('#rujukan_keluar_sudah_dijawab').html(data);
		});*/



		$.getJSON('<?php echo site_url('dashboard/layanan_pusat_fb'); ?>', function(data) {
			$('#pusat_sudah_fb').html(data.done);
			$('#pusat_belum_fb').html(data.notdone);
			Morris.Donut({
				element: 'donut-layanan-fb',
				data: [{
						label: "Sudah di-FB",
						value: data.done
					},
					{
						label: "Belum di-FB",
						value: data.notdone
					}
				],
				resize: true,
				colors: ['#777edd', '#ff679b'],
				labelColor: '#888',
				backgroundColor: 'transparent',
				fillOpacity: 0.1,
				formatter: function(x) {
					return x + ""
				}
			});

		});

		$.getJSON('<?php echo site_url('dashboard/belum_tl'); ?>', function(data) {
			$('#belum_tl_red').html(data.red);
			$('#belum_tl_orange').html(data.orange);
			$('#belum_tl_black').html(data.black);
			$('#belum_tl_green').html(data.green);
			Morris.Donut({
				element: 'donut-belum-tl',
				data: [{
						label: "SLA <= 5 HK",
						value: data.red
					},
					{
						label: "SLA 5 - 14 HK",
						value: data.orange
					},
					{
						label: "SLA 14 - 60 HK",
						value: data.green
					},
					{
						label: "Melewati SLA",
						value: data.black
					}
				],
				resize: true,
				colors: ['red', 'orange', 'green', 'black'],
				labelColor: '#888',
				backgroundColor: 'transparent',
				fillOpacity: 0.1,
				formatter: function(x) {
					return x + ""
				}
			});

		});

	});
	$.getJSON('<?php echo site_url('dashboard/performa_sla'); ?>', function(data) {
		$('#memenuhi_sla').html(data.meet);
		$('#tidak_memenuhi_sla').html(data.notmeet);
		Morris.Donut({
			element: 'donut-performa-sla',
			data: [{
					label: "Memenuhi SLA",
					value: data.meet
				},
				{
					label: "Tidak Memenuhi SLA",
					value: data.notmeet
				},
			],
			resize: true,
			colors: ['red', 'orange'],
			labelColor: '#888',
			backgroundColor: 'transparent',
			fillOpacity: 0.1,
			formatter: function(x) {
				return x + ""
			}
		});

	});
</script>