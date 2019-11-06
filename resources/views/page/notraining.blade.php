<?php
$user =  \Illuminate\Support\Facades\Session::get('user');
use App\Monitoring;
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
                        <h3 class="box-title">List of Participants with No Training for the Year of {{ Session::get('year') }}</h3>
                        <br />
                        <font class="text-danger text-bold">Result: {{ $data->total() }}</font>

                        <div class="box-tools">
                            @if(Session::get('noTrainingFilter'))
                                <a href="{{ url('/param/clear/noTrainingFilter') }}" class="btn btn-warning btn-sm"><i class="fa fa-trash"></i> Clear Filter</a>
                            @endif
                            <button type="submit" class="btn btn-success btn-sm" data-toggle="modal" data-target="#filter">
                                Filter Result <i class="fa fa-filter"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body table-responsive">
                        @if(count($data)>0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Complete Name</th>
                                        <th>Designation</th>
                                        <th>Email</th>
                                        <th>Contact #</th>
                                        <th>Division</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $row)
                                        <tr>
                                            <td>
                                                <a href="{{ url('participants/edit/'.$row->id) }}" class="editable">
                                                    {{ str_pad($row->id,'4','0',STR_PAD_LEFT) }}
                                                </a>
                                            </td>
                                            <td class="text-success">{{ $row->lname }}, {{ $row->fname }} {{ $row->mname }}</td>
                                            <td>{{ $row->designation }}</td>
                                            <td>{{ $row->email }}</td>
                                            <td>{{ $row->contact }}</td>
                                            <td>{{ \App\Division::find($row->division)->name }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
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
    <div class="modal fade" id="filter">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-search"></i> Filter Result </h4>
                </div>
                <form action="{{ url('participants/notraining') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label>Keyword</label>
                        <input type="text" name="keyword" class="form-control" value="{{ $filterKeyword }}"/>
                    </div>
                    <div class="form-group">
                        <label for="">Select Division</label>
                        <select name="division" class="form-control">
                            <option value="">Select All...</option>
                            @foreach($division as $d)
                                <option value="{{ $d->id }}" @if($d->id==$filterDivision) selected @endif>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-block btn-success">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('js')

@endsection