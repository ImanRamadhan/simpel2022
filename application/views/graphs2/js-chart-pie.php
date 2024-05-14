<script>
    function Create3DDonut(ctx, DataSeries, Title, Subtitle) {
    //Random Number
    var randomScalingFactor = function () {
        return Math.round(Math.random() * 100);
    };

    var Level = 1;
    var DefaultXTitle = Title;

    //Set Option Highchart
    Highcharts.setOptions({
        lang: {
            drillUpText: '◁ Back'
        }
    });
    //Create Chart
    
    // Build the chart
        Highcharts.chart(ctx, {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: Title
            },
			subtitle: {
                text: Subtitle
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    },
                    showInLegend: true
                }
            },
			credits: {
				enabled: false
			},
            series: [{
                name: 'Jumlah Layanan',
                colorByPoint: true,
                data: DataSeries[1].data
            }]
        });
}
</script>