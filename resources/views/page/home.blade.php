<?php
  $user =  \Illuminate\Support\Facades\Session::get('user');
?>
@extends('app')

@section('css')

@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Radar Chart (Per Division)</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="radarChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Bar Chart (No. of Trainings per Month)</h3>
                        </div>
                        <div class="box-body">
                            <canvas id="singelBarChart"></canvas>
                        </div>
                    </div>
                </div>
        </section>
        <!-- /.content -->
        <div class="clearfix"></div>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('js')
<script src="{{ url('back/bower_components/chartjs/Chart.bundle.min.js') }}"></script>
<script>
        var labelRadar = $.ajax({
            url: "{{ url('home/radar/label') }}",
            type: 'GET',
            async: false,
            done: function(response) {
              return response;
          }
        }).responseJSON;

        var dataRadar = $.ajax({
            url: "{{ url('home/radar/data') }}",
            type: 'GET',
            async: false,
            done: function(response) {
              return response;
          }
        }).responseJSON;

        var chartData = $.ajax({
            url: "{{ url('home/chart/data') }}",
            type: 'GET',
            async: false,
            done: function(response) {
              return response;
          }
        }).responseJSON;

        try {

            //radar chart
            var ctx = document.getElementById("radarChart");
            if (ctx) {
              ctx.height = 200;
              var myChart = new Chart(ctx, {
                type: 'radar',
                data: {
                  labels: labelRadar,
                  defaultFontFamily: 'Poppins',
                  datasets: [
                    {
                      label: "Total",
                      data: dataRadar,
                      borderColor: "rgba(0, 123, 255, 0.6)",
                      borderWidth: "2",
                      backgroundColor: "rgba(0, 123, 255, 0.4)"
                    }
                  ]
                },
                options: {
                  legend: {
                    position: 'top',
                    labels: {
                      fontFamily: 'Poppins'
                    }
        
                  },
                  scale: {
                    ticks: {
                      beginAtZero: true,
                      fontFamily: "Poppins",
                      max: 130,
                      min: 0
                    }
                  }
                }
              });
              myChart.options.legend.display = false;
            }
        
          } catch (error) {
            console.log(error)
          }

          try {

            // single bar chart
            var ctx = document.getElementById("singelBarChart");
            if (ctx) {
              ctx.height = 150;
              var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                  labels: ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"],
                  datasets: [
                    {
                      label: "# of Training",
                      data: chartData,
                      borderColor: "rgba(0, 123, 255, 0.9)",
                      borderWidth: "0",
                      backgroundColor: "rgba(0, 123, 255, 0.5)"
                    }
                  ]
                },
                options: {
                  legend: {
                    position: 'top',
                    labels: {
                      fontFamily: 'Poppins'
                    }
        
                  },
                  scales: {
                    xAxes: [{
                      ticks: {
                        fontFamily: "Poppins"
        
                      }
                    }],
                    yAxes: [{
                      ticks: {
                        beginAtZero: true,
                        fontFamily: "Poppins"
                      }
                    }]
                  }
                }
              });
              myChart.options.legend.display = false;
            }
        
          } catch (error) {
            console.log(error);
          }
</script>
@endsection