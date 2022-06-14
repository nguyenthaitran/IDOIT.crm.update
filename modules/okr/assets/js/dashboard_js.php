<script>
(function(){
  	"use strict";
	// Build the chart
Highcharts.setOptions({
  chart: {
      style: {
          fontFamily: 'inherit !important',
          //fontWeight:'bold',
          fill: 'black'
      }
  },
  colors: [ '#1BC5BD','#FFA800','#0BB783','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
 });
Highcharts.chart('container_ck', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '<?php echo _l('checkin_status'); ?>'
    },
    credits: {
                enabled: false
              },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Tỉ lệ',
        colorByPoint: true,
        data: <?php echo html_entity_decode($checkin_status); ?>
    }]
});
        init_progress_bars();
})(jQuery);

</script>
