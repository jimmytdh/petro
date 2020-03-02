<?php

?>
@extends('app')

@section('css')

@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="col-md-3">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Monthly Monitoring</h3>

                    </div>
                    <div class="box-body">
                        <form action="{{ url('/monthly') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="date">Select Date</label>
                                <input type="date" name="date" required value="{{ $date }}" id="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success btn-block" type="submit">
                                    <i class="fa fa-search"></i> Submit
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
                @if($trainings)
                <div class="box box-success">
                    <div class="box-header text-center">
                        <h3 class="box-title">
                            <i class="fa fa-clipboard"></i> {{ number_format(count($trainings)) }} training(s) found!
                        </h3>

                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-9">
                @if($trainings)
                    @foreach($trainings as $row)
                    <?php
                        $list = \App\Http\Controllers\MonitoringCtrl::getTraineeList($row->id);
                    ?>
                    <div class="box box-info">
                        <div class="box-header">
                            <h3 class="box-title">
                                {{ $row->name }}
                                <br>
                                <small class="text-danger">
                                    Date: {{ \Carbon\Carbon::parse($row->date_training)->format('F d, Y') }}
                                </small>
                            </h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-gray">
                                    <tr>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>Middle Name</th>
                                        <th>Division</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $l)
                                    <tr>
                                        <td>{{ $l->lname }}</td>
                                        <td>{{ $l->fname }}</td>
                                        <td>{{ $l->mname }}</td>
                                        <td>{{ $l->name }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($row->problem)
                        <div class="box-footer bg-red">
                            {{ $row->problem }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="box box-danger">
                        <div class="box-body text-center">
                            <img src="{{ url('/img/no_result_found.gif') }}" alt="">
                        </div>
                    </div>
                @endif
            </div>
            <div class="clearfix"></div>
        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->
@endsection

@section('modal')

@endsection

@section('js')

@endsection