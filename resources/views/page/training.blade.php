<?php
    $user =  \Illuminate\Support\Facades\Session::get('user');
    use app\Http\Controllers\TrainingCtrl;
?>
@extends('app')

@section('css')

@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            @if(!$edit)
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-body">
                            <form method="post" action="{{ url('trainings/save') }}">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <fieldset>
                                        <legend class="text-bold text-success"><small>Add Training</small></legend>
                                        <div class="form-group">
                                            <label for="name">Training Name</label>
                                            <input type="text" autofocus required autocomplete="off" name="name" class="form-control" placeholder="Enter Training Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Date</label>
                                            <input type="date" id="date" autofocus required autocomplete="off" name="date_training" class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="name">No. of Hours</label>
                                            <input type="number" autofocus required autocomplete="off" name="hours" min="1" value="1" class="form-control" placeholder="Enter Training Name">
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend class="text-bold text-success"><small>Deliverable</small></legend>
                                        <div class="form-group">
                                            <label for="">Filter by Division</label>
                                            <select name="deliverable" class="form-control" id="filterDivision">
                                                <option value="all">View All...</option>
                                                @foreach($division as $d)
                                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Title</label>
                                            <select name="deliverable" class="form-control" id="deliverable">
                                                <option value="">Select Deliverable...</option>
                                                @foreach($deliverable as $d)
                                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </fieldset>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" class="pull-right btn btn-primary">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-4">
                    <div class="box box-warning">
                        <div class="box-body">
                            <form role="form" method="post" action="{{ url('trainings/update/'.$info->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <fieldset>
                                        <legend class="text-bold text-success"><small>Update Training</small></legend>
                                    </fieldset>
                                    <div class="form-group">
                                        <label for="name">Training Name</label>
                                        <input type="text" autofocus required autocomplete="off" value="{{ $info->name }}" name="name" class="form-control" placeholder="Enter Training Name">
                                    </div> 
                                    <div class="form-group">
                                        <label for="name">Date</label>
                                        <input type="date" id="date" required autocomplete="off" name="date_training" class="form-control" value="{{ $info->date_training }}">
                                    </div>  
                                    <div class="form-group">
                                        <label for="name">No. of Hours</label>
                                        <input type="number" autofocus required autocomplete="off" name="hours" min="1" value="{{ $info->hours }}" class="form-control" placeholder="Enter Training Name">
                                    </div>
                                    <fieldset>
                                        <legend class="text-bold text-success"><small>Deliverable</small></legend>
                                    </fieldset>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="">Filter by Division</label>
                                            <select name="deliverable" class="form-control" id="filterDivision">
                                                <option value="all">View All...</option>
                                                @foreach($division as $d)
                                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <select name="deliverable" class="form-control" id="deliverable">
                                            <option value="">Select Deliverable...</option>
                                            @foreach($deliverable as $d)
                                                <option value="{{ $d->id }}" @if($info->deliverable==$d->id) selected @endif>{{ $d->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <a href="{{ url('/'.$menu) }}" class="pull-left btn btn-default">
                                        <i class="fa fa-arrow-left"></i> Cancel
                                    </a>
                                    <button type="submit" class="pull-right btn btn-primary">
                                        <i class="fa fa-save"></i> Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-8">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">List of Trainings</h3>

                        <div class="box-tools">
                            <form method="post" action="{{ url('trainings/search') }}">
                                {{ csrf_field() }}
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="keyword" value="{{ Session::get('search_training') }}" class="form-control pull-right" placeholder="Search">

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
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Date</th>    
                                    <th class="text-center">Participants</th>    
                                    <th class="text-center">Hours</th>    
                                    <th></th>                                
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td width="15%">
                                            <a href="{{ url('trainings/edit/'.$row->id) }}" class="editable">
                                                {{ str_pad($row->id,'4','0',STR_PAD_LEFT) }}
                                            </a>
                                        </td>
                                        <td width="30%" class="text-success">
                                            @if($row->deliverable>0)
                                                <small><i class="fa fa-link text-danger"></i></small>
                                            @endif
                                            {{ $row->name }}
                                        </td>
                                        <td width="20%" class="text-danger">{{ date('M d, Y',strtotime($row->date_training)) }}</td>                                
                                        <td width="10%" class="text-center">
                                            <a href="{{ url('trainings/list/'.$row->id) }}" class="editable">
                                                {{ TrainingCtrl::countParticipants($row->id) }}
                                            </a>
                                        </td>                                
                                        <td width="10%" class="text-center">{{ $row->hours }}</td>                                
                                        <td width="10%">
                                            <a class="pull-right text-danger" href="#delete" data-id="{{ $row->id }}">
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
                        @if(count($data)>0)
                            {{ $data->links() }}
                        @else
                            <div class="callout callout-warning">
                                <p>Opps. No trainings found!</p>
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
                    <p class="text-center">Are you sure you want to delete this training?</p>
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
    <script>
        $('a[href="#delete"]').on('click',function(){
            var id = $(this).data('id');
            $('#delete').modal('show');
            $('#delete_link').attr('href',"{{ url($menu.'/delete/') }}/"+id);
        });

        filterDeliverable();

        $('#filterDivision').change(function(){
            filterDeliverable();
        });
        $('#date').change(function () {
            filterDeliverable();
        });

        function filterDeliverable()
        {
            var division = $('#filterDivision').find(':selected').val();
            var date = $('#date').val();
            var url = "{{ url('/deliverable/get') }}/"+division+"/"+date;
            console.log(url);
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('#deliverable').empty().append($("<option></option>")
                        .attr("value","")
                        .text("Select Deliverable..."));
                    $.each(data, function(key, val){
                        $('#deliverable').append($("<option></option>")
                            .attr("value",val.id)
                            .text(val.name));
                    });
                }
            });
        }
    </script>
@endsection