function set_datatable(segment, id = null) {
  var tableId = id ? id : '#table';
  table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ordering": false,
      "order": [],
      "info": false,
      "language": { 
        "infoFiltered": "",
        "sZeroRecords": "<b style='color: #777777;'>TIDAK DITEMUKAN</b>",
        "sSearch": "Cari:"
      },
      "ajax": {
        "url": index + segment,
        "type": "POST"
      },
      "columnDefs": [{ 
        "targets": [-1],
        "orderable": false,
      }],
    });
}

function load_table() {
  var table = $('#table').DataTable();
  table.search('').columns().search('').draw();
  table.ajax.reload();
}

function form_data(form = null) {
  var formId = form ? form : '#form';
  $.ajax({
      url: $(formId).attr('action'),
      type: "POST",
      data: new FormData($(formId)[0]),
      contentType: false,
      processData: false,
      dataType: "JSON",
      success: function(response) {
        $(formId).data('bootstrapValidator').resetForm();
        if (response.status) {
          Pace.restart();
          if (response.result) {
            action_result(response.result);
          } else {
            action_success();
          }
          if (response.message) {
            flashdata(response.message);
          } else {
            alert_error(response.alert);
          }
        } else {
          $.each(response.errors, function (key, val) {
              $('[name="' + key + '"]').closest('.form-group').addClass('has-error');
              $('[name="' + key + '"]').nextAll('.help-block').eq(0).text(val);
              if (val === '') {
                  $('[name="' + key + '"]').closest('.form-group').removeClass('has-error').addClass('has-success');
                  $('[name="' + key + '"]').nextAll('.help-block').eq(0).text('');
              }

              $('[name="' + key + '"]').on('keyup change', function () {
                if ($('[name="' + key + '"]').val()) {
                  $('[name="' + key + '"]').closest('.form-group').removeClass('has-error').addClass('has-success');
                  $('[name="' + key + '"]').nextAll('.help-block').eq(0).text('');
                }
              });

          });
      }
    }
  });
}

function flashdata(message, type = null) {
  var alert = type ? type : 'success';
  Swal.fire({
    type: alert,
    title: '<span style="font-weight: bold; color: #595959; font-size: 16px; font-family: serif;">' + message + '</span>',
    showConfirmButton: false,
    timer: 1500
  });
}

function alert_error(message) {
  $('<div class="alert alert-danger alert-dismissible"><p style="font-weight: bold; font-size: 16px;">' + message + '</p></div>').show().appendTo('#response');
  
   $(".alert").delay(1500).slideUp("slow", function(){
    $(this).remove();
  });
}

function confirm_delete(text, href) {
  Swal.fire({
    title: '<span style="font-family: serif;">Apakah anda yakin?</span>',
    text: text,
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#00A65A',
    cancelButtonColor: '#6C757D',
    confirmButtonText: '<span style="font-family: serif;"><i class="fa fa-angle-double-right"></i> Ya</span>',
    cancelButtonText: '<span style="font-family: serif;"><i class="fa fa-angle-double-left"></i> Tidak</span>',
    reverseButtons: true,
  }).then((result) => {
    if (result.value) {
      $.ajax({
          url : href,
          type: "POST",
          dataType: "JSON",
          success: function(response) {
            Pace.restart();
            success_delete();
            if (response.message) {
              flashdata(response.message);
            }
          }
      });
    }
  })
}

function form_multipart(form = null, btn = null) {
  var formId  = form ? form : '#form';
  var btnId   = btn ? btn : '#btn-save';

  $(formId).on('submit', function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      $(btnId).attr('disabled', true);
      $.ajax({
          url: $(this).attr('action'),
          type: "POST",
          data: new FormData(this),
          processData:false,
          contentType:false,
          dataType: "JSON",
          success: function (response) {
            $(btnId).removeAttr('disabled');
            if (response.status) {
              Pace.restart();
              if (response.result) {
                action_result(response.result);
              } else {
                action_success();
              }
              if (response.message) {
                flashdata(response.message);
              }
            } else {
              if (response.alert) {
                Swal.fire({
                  type: 'error',
                  title: '<div style="font-weight: bold; color: #595959; font-size: 16px; font-family: serif;">' + response.alert + '</div>',
                  showConfirmButton: false,
                  timer: 1500
                });
              } else {
                $.each(response.errors, function (key, val) {
                    $('[name="' + key + '"]').closest('.form-group').addClass('has-error');
                    $('[name="' + key + '"]').nextAll('.help-block').eq(0).text(val);
                    if (val === '') {
                        $('[name="' + key + '"]').closest('.form-group').removeClass('has-error').addClass('has-success');
                        $('[name="' + key + '"]').nextAll('.help-block').eq(0).text('');
                    }

                    if ($('[name="' + key + '"]').val()) {
                      $('[name="' + key + '"]').closest('.form-group').removeClass('has-error').addClass('has-success');
                      $('[name="' + key + '"]').nextAll('.help-block').eq(0).text('');
                    }

                });
              }
            }
          }
      })
  });
}

function reset_form(form = null) {
  formId = form ? form : '#form';
  $(formId)[0].reset();
  $('.form-group').removeClass('has-error has-success');
  $('.help-block').empty();
  $('.select2').select2(null, false);
  $('.select2').val('').trigger('change');
}

// $('.custom-file-input').on('change', function() {
// let fileName = $(this).val().split('\\').pop();
// $(this).next('.custom-file-label').addClass("selected").html(fileName); 
// });

// document.onkeydown = function(e) {
//   if (e.ctrlKey && 
//       (e.keyCode === 67 || 
//        e.keyCode === 86 || 
//        e.keyCode === 85 || 
//        e.keyCode === 117)) {
//       return false;
//   } else {
//       return true;
//   }
// };

// $(document).keypress("u",function(e) {
//   if(e.ctrlKey) {
//     return false;
//   } else {
//     return true;
//   }
// });

function bar_chart(siswa, kehadiran, warna) {
  var areaChartData = {
    labels  : siswa,
    datasets: [
      {
        label               : 'Kehadiran',
        fillColor           : 'rgba(60,141,188,0.9)',
        strokeColor         : 'rgba(60,141,188,0.8)',
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                : kehadiran
      }
    ]
  }
  var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
  var barChart                         = new Chart(barChartCanvas)
  var barChartData                     = areaChartData
  barChartData.datasets[0].fillColor   = '#00a65a'
  barChartData.datasets[0].strokeColor = '#00a65a'
  barChartData.datasets[0].pointColor  = '#00a65a'
  // for (var i = 0; i < warna.length; i++) {
  //   barChartData.datasets[0].fillColor   = warna[i]
  //   barChartData.datasets[0].strokeColor = warna[i]
  //   barChartData.datasets[0].pointColor  = warna[i]
  // }
  var barChartOptions                  = {
    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero        : false,
    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines      : true,
    //String - Colour of the grid lines
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    //Number - Width of the grid lines
    scaleGridLineWidth      : 1,
    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines  : true,
    //Boolean - If there is a stroke on each bar
    barShowStroke           : true,
    //Number - Pixel width of the bar stroke
    barStrokeWidth          : 2,
    //Number - Spacing between each of the X value sets
    barValueSpacing         : 4,
    //Number - Spacing between data sets within X values
    barDatasetSpacing       : 1,
    //String - A legend template
    legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
    //Boolean - whether to make the chart responsive
    responsive              : true,
    maintainAspectRatio     : true
  }
  barChartOptions.datasetFill = false
  barChart.Bar(barChartData, barChartOptions)
}

function pie_chart(data) {
  //-------------
  //- PIE CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
  var pieChart       = new Chart(pieChartCanvas)
  var PieData        = data
  var pieOptions     = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke    : true,
    //String - The colour of each segment stroke
    segmentStrokeColor   : '#fff',
    //Number - The width of each segment stroke
    segmentStrokeWidth   : 1,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 0, // This is 0 for Pie charts
    //Number - Amount of animation steps
    animationSteps       : 100,
    //String - Animation easing effect
    animationEasing      : 'easeOutBounce',
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate        : true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale         : false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive           : true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio  : true,
    //String - A legend template
    legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  pieChart.Doughnut(PieData, pieOptions)
}

function donut_chart(data) {
  var donutData = data
  // var donutData = [
  //   { label: 'Series2', data: 30, color: '#3c8dbc' },
  //   { label: 'Series3', data: 20, color: '#0073b7' },
  //   { label: 'Series4', data: 50, color: '#00c0ef' }
  // ]
  $.plot('#donut-chart', donutData, {
    series: {
      pie: {
        show       : true,
        radius     : 1,
        innerRadius: 0.5,
        label      : {
          show     : true,
          radius   : 2 / 3,
          formatter: labelFormatter,
          threshold: 0.1
        }

      }
    },
    legend: {
      show: false
    }
  })

}

function labelFormatter(label, series) {
  return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
    + label
    + '<br>'
    + Math.round(series.percent) + '%</div>'
}