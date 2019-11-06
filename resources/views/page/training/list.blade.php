<?php
$user =  \Illuminate\Support\Facades\Session::get('user');
?>
@extends('app')

@section('css')
    <link rel="stylesheet" href="{{ url('back/css/multi-select.css') }}">
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="col-md-5">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Select Participants to Add to Training</h3>
                            </div>
                            <div class="box-body">
                                <form method="post" action="{{ url('trainings/add/'.$id) }}">
                                    {{ csrf_field() }}
                                    <div class="box-body">
                                        <div class="form-group">
{{--                                            <select class="form-control" name="lists[]" required multiple="multiple" data-placeholder="Select Participant" size="20" style="height: 100%;">--}}
{{--                                                @foreach($list as $l)--}}
{{--                                                    <option value="{{ $l->id }}">{{ $l->lname }}, {{ $l->fname }} {{ $l->mname }} ({{ $l->name }})</option>--}}
{{--                                                @endforeach                                               --}}
{{--                                            </select>--}}
                                            <select id='pre-selected-options' name="list[]" multiple='multiple'>
                                                @foreach($list as $l)
                                                    <option value="{{ $l->id }}">{{ $l->lname }}, {{ $l->fname }} {{ $l->mname }} ({{ $l->name }})</option>
                                                @endforeach
                                            </select>
                                        </div>                                                               
                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer">
                                        <a href="{{ url('trainings') }}" class="btn btn-default">
                                            <i class="fa fa-arrow-left"></i> Back
                                        </a>
                                        <button type="submit" class="pull-right btn btn-primary">
                                            <i class="fa fa-users"></i> Add Participants
                                        </button>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
            <div class="col-md-7">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">{{ $name }}</h3>                    
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body table-responsive">
                        @if(count($data)>0)
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Last Name</th>            
                                    <th>First Name</th>            
                                    <th>Middle Name</th>            
                                    <th>Division</th> 
                                    <th>Certificates</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $row)
                                    <?php
                                        $len = strlen($row->cert);
                                    ?>
                                    <tr>
                                        <td class="text-success" width="20%">
                                            <a href="#info" class="editable" data-id="{{ $row->id }}">
                                                {{ $row->lname }}
                                            </a>
                                        </td>
                                        <td width="20%">{{ $row->fname }}</td>
                                        <td width="20%">{{ $row->mname }}</td>
                                        <td width="20%">{{ $row->name }}</td>
                                        <td width="10%">
                                            @if($len>0)
                                            <a href="#" class="text-success"><i class="fa fa-check"></i></a>
                                            @else
                                            <a href="#" class="text-danger"><i class="fa fa-times"></i></a>
                                            @endif
                                            &nbsp;<a href="#"><i class="fa fa-newspaper-o"></i></a>
                                        </td>
                                        <td width="10%">
                                            <a class="pull-right text-danger" href="#delete" data-training="{{ $id }}" data-id="{{ $row->id }}">
                                                <i class="fa fa-trash-o"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        @endif
                    </div>
                    <div class="box-footer">
                        @if(count($data)==0)
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
@endsection

@section('js')
    <script src="{{ url('/back/js/jquery.multi-select.js') }}"></script>
    @include('script.info')
    <script>
        $('#pre-selected-options').multiSelect();
        $('a[href="#delete"]').on('click',function(){
            var id = $(this).data('id');
            var training = $(this).data('training');
            $('#delete').modal('show');
            $('#delete_link').attr('href',"{{ url('/trainings/participants/delete/') }}/"+training+"/"+id);
        });
    </script>
@endsection