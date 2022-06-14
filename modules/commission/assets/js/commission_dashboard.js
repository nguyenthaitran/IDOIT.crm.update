(function($) {
  "use strict";
  var data = {};
  requestGet('commission/dashboard_commission_chart').done(function(response) {
    response = JSON.parse(response);
    console.log(response);
    Highcharts.setOptions({
      chart: {
          style: {
              fontFamily: 'inherit !important',
              fill: 'black'
          }
      },
      colors: [ '#0BB783','#1BC5BD','#FFA800','#F64E60', '#E3E8EE', '#3699FF', '#FC2D42','#E3E8EE','#989898']
     });
        Highcharts.chart('commission_chart', {
         chart: {
             type: 'column'
         },
         title: {
             text: 'Biểu đồ hoa hồng'
         },
         subtitle: {
             text: ''
         },
         credits: {
            enabled: false
          },
         xAxis: {
             categories: response.month,
             crosshair: true,
         },
         yAxis: {
             min: 0,
             title: {
              text: response.name
             }
         },
         tooltip: {
             headerFormat: '<span>{point.key}</span><table>',
             pointFormat: '<tr>' +
                 '<td><b>{point.y:.0f} {series.name}</b></td></tr>',
             footerFormat: '</table>',
             shared: true,
             useHTML: true
         },
         plotOptions: {
             column: {
                 pointPadding: 0.2,
                 borderWidth: 0
             }
         },

         series: [{
            type: 'column',
            colorByPoint: true,
            name: response.unit,
            data: response.data,
            showInLegend: false
         }]
     });
        
  })
})(jQuery);
