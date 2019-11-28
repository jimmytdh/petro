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
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Monitoring</h3>

                        <div class="box-tools">
                            <form method="post" action="{{ url('monitoring/search') }}">
                                {{ csrf_field() }}
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="keyword" value="{{ Session::get('search_monitoring') }}" class="form-control pull-right" placeholder="Search">

                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body table-responsive">
                        @if(count($data)>0)
                        <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Complete Name</th>                      
                                    <th>Division</th> 
                                    <th>Training Attended</th>
                                    <th>Date</th>           
                                    <th class="text-center">Certificate</th>
                                    <th class="text-center">Hours of<br />Training</th>
                                    <th class="text-center">Self</th>           
                                    <th class="text-center">Supervisor</th>           
                                    <th class="text-center">TOTAL</th>           
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $row)
                                    <tr>
                                        <td class="text-bold text-success">
                                            <a href="#info" class="editable" data-id="{{ $row->participant_id }}">{{ $row->lname }}, {{ $row->fname }} {{ $row->mname }}</a>
                                        </td>
                                        <td>{{ $row->division }}</td>
                                        <td title="{{ $row->training }}">{{ \App\Http\Controllers\ParamController::string_limit_words($row->training,40) }}</td>
                                        <td>{{ date('F d, Y',strtotime($row->date))}}</td>
                                        <td class="text-center">
                                            @if($row->with_cert)
                                                <i class="fa fa-check text-success text-bold"></i>
                                            @endif
                                        </td>
                                        <td class="text-center text-danger">{{ $row->hours }}</td>
                                        <td class="text-center text-bold text-info">
                                            <a href="#evalutaion" class="editable" data-column="self" data-score="{{ $row->self }}" data-id="{{ $row->monitoring_id }}">
                                                {{ $row->self }}
                                            </a>
                                        </td>
                                        <td class="text-center text-bold text-info">
                                            <a href="#evalutaion" class="editable" data-column="supervisor" data-score="{{ $row->supervisor }}" data-id="{{ $row->monitoring_id }}">
                                                {{ $row->supervisor }}
                                            </a>
                                        </td>
                                        <td class="text-center text-bold text-danger">
                                            <?php 
                                                $total = ($row->self + $row->supervisor)/2;
                                                $total = number_format($total,1);
                                            ?>
                                            {{ $total }}
                                        </td>
                                    </tr>  
                                    @endforeach                                  
                                </tbody>

                            </table>
                        @endif
                    </div>
                    <div class="box-footer">
                        @if(count($data)>0)
                            {{ $data->links() }}
                        @else
                            <div class="callout callout-warning">
                                <p>Opps. No participants found!</p>
                            </div>
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="clearfix"></div>
        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->
@endsection

@section('modal')
    <div class="modal fade" id="delete">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-danger text-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p class="text-center">Are you sure you want to delete this participant?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                    <a id="delete_link" class="btn btn-primary"><i class="fa fa-check"></i> Yes</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="evalutaion">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header bg-success text-success">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Evaluation Score</h4>
                    </div>
                    <form method="POST" action="" id="form_self">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Input Score</label>
                                <input type="number" class="form-control" id="self_score" name="score" value="0" min="0" />
                            </div>                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</a>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
@endsection

@section('js')
    @include('script.info')
    <script>
        $('a[href="#delete"]').on('click',function(){
            var id = $(this).data('id');
            var training = $(this).data('training');
            $('#delete').modal('show');
            $('#delete_link').attr('href',"{{ url('/trainings/participants/delete/') }}/"+training+"/"+id);
        });
        
        $('a[href="#evalutaion"]').on('click',function(){
            var id = $(this).data('id');
            var score = $(this).data('score');
            var column = $(this).data('column');
            $('#self_score').val(score);
            $('#form_self').attr('action',"{{ url('/monitoring/') }}/"+column+"/"+id);
            $('#evalutaion').modal('show');
        });
    </script>
@endsection