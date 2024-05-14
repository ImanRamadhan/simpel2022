<script>
    function CreateBarChartV(ctx, DataSeries, Title, Subtitle, TitleX, TitleY) {
    //Random Number
    var randomScalingFactor = function () {
        return Math.round(Math.random() * 100);
    };

    var Level = 1;
    var DefaultXTitle = Title;
	//console.log(DataSeries[1].data);
    //Set Option Highchart
    Highcharts.setOptions({
            lang: {
                drillUpText: '◁ Back'
            }
        });
        //Create Chart
        
        Highcharts.chart(ctx, {
        chart: {
            type: 'bar'
        },
        title: {
            text: Title
        },
		subtitle: {
            text: Subtitle
        },
        xAxis: {
            categories: DataSeries[0].data,
            title: {
                text: TitleX
            }
        },
        yAxis: {
            min: 0,
            minPadding: 0.05,
			allowDecimals: false,
            title: {
                align: 'high',
				text: TitleY
            },
            labels: {
                overflow: 'justify'
            }
        },
        // tooltip: {
        //     valueSuffix: ' millions'
        // },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        credits: {
            enabled: false
        },
        //series: DataSeries[1].data
        series: [
                    {
                        name: TitleY,
                        colorByPoint: true,
                        data: DataSeries[1].data
                    }
                ]
    });
}
</script>