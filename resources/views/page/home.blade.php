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
            <div class="col-md-8">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="info-box bg-yellow">
                            <span class="info-box-icon"><i class="fa fa-users"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Employees</span>
                                <span class="info-box-number">{{ number_format($no_employee) }}</span>

                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $per_training }}%"></div>
                                </div>
                                <span class="progress-description">
                                    {{ $per_training }}% with training
                                  </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-user-times"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">No Training</span>
                                <span class="info-box-number">{{ number_format($without_training) }}</span>

                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $per_without_training }}%"></div>
                                </div>
                                <span class="progress-description">
                                    {{ $per_without_training }}% without training
                                  </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="fa fa-newspaper-o"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Individual Training</span>
                                <span class="info-box-number">{{ number_format($no_training) }}</span>

                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $per_cert }}%"></div>
                                </div>
                                <span class="progress-description">
                                    {{ $per_cert }}% submitted certificates
                                  </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-box bg-aqua">
                            <span class="info-box-icon"><i class="fa fa-clipboard"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Total Percentage</span>
                                <span class="info-box-number">{{ $per_total }}%</span>

                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $per_total }}%"></div>
                                </div>
                                <span class="progress-description">
                                    (cert. submitted / employees) x 100%
                                  </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bar Chart (No. of Trainings per Month)</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="singelBarChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Radar Chart (Per Division)</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="chart-responsive">
                            <canvas id="radarChart"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-padding">
                        <ul class="nav nav-pills nav-stacked">
                            @for($i=0; $i<7; $i++)
                                <li>
                                    <a href="#">
                                        <font class="division{{ $i }}">--</font>
                                        <span class="pull-right per{{ $i }}">
                                        %
                                    </span>
                                    </a>
                                </li>
                            @endfor
                        </ul>
                    </div>
                    <!-- /.footer -->
                </div>
                <!-- /.box -->
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
        //<i class="fa fa-angle-up"></i> <font class="per{{ $i }}"></font>
        for(i=0; i<7; i++){
            $('.division'+i).html(labelRadar[i]);
            if(dataRadar[i]>=100)
            {
                $('.per'+i).addClass('text-success text-bold').html('<i class="fa fa-angle-up"></i> '+dataRadar[i]+"%");
            }else if(dataRadar[i]>=75 && dataRadar[i]<100){
                $('.per'+i).addClass('text-warning text-bold').html(dataRadar[i]+"%");
            }else{
                $('.per'+i).addClass('text-danger text-bold').html('<i class="fa fa-angle-down"></i> '+dataRadar[i]+"%");
            }


        }

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