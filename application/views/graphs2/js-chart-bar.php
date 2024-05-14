<script>
    function CreateBarChart(ctx, DataSeries, Title, Subtitle, TitleX, TitleY ) {
    //Random Number
    var randomScalingFactor = function () {
        return Math.round(Math.random() * 100);
    };

    var Level = 1;
    //var DefaultXTitle = Title;

    //Set Option Highchart
    Highcharts.setOptions({
        lang: {
            drillUpText: '◁ Back'
        }
    });
    //Create Chart
    
    Highcharts.chart(ctx, {
        chart: {
            type: 'column'
        },
        title: {
            text: Title
        },
		subtitle: {
            text: Subtitle
        },
        xAxis: {
            type: 'category',
            crosshair: true,
			title: {
                text: TitleX
            }
        },
        yAxis: {
            visible: true,
            title: {
                text: TitleY
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            },
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    style: { textShadow: false, fontSize: '1vw' }
                }
            }
        },
		credits: {
            enabled: false
        },
        series: DataSeries,
		
        drilldown: {
            series: []
        },
		/*legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -40,
			y: 80,
			floating: true,
			borderWidth: 1,
			backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
			shadow: true
		}*/
    })
}
</script>