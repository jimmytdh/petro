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
            @if(!$edit)
                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Participant</h3>
                        </div>
                        <div class="box-body">
                            <form method="post" action="{{ url('participants/save') }}">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="fname">First Name</label>
                                        <input type="text" required autocomplete="off" name="fname" class="form-control" id="fname" placeholder="Enter First Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="mname">Middle Name</label>
                                        <input type="text" required autocomplete="off" name="mname" class="form-control" id="mname" placeholder="Enter First Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="lname">Last Name</label>
                                        <input type="text" required autocomplete="off" name="lname" class="form-control" id="lname" placeholder="Enter Last Name">
                                    </div> 
                                    <div class="form-group">
                                        <label for="designation">Designation</label>
                                        <input type="text" required autocomplete="off" name="designation" class="form-control" id="designation" placeholder="Enter Designation">
                                    </div> 
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" required autocomplete="off" name="email" class="form-control" id="email" placeholder="Enter Email">
                                    </div> 
                                    <div class="form-group">
                                        <label for="contact">Contact #</label>
                                        <input type="contact" required autocomplete="off" name="contact" class="form-control" id="contact" placeholder="Enter Contact #">
                                    </div>       
                                    <div class="form-group">
                                        <label for="division">Division</label>
                                        <select name="division" class="form-control" required>
                                            <option value="">Select Division...</option>    
                                            @foreach($division as $d)
                                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                                            @endforeach
                                        </select>    
                                    </div>                          
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
                <div class="col-md-3">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Update Participant</h3>
                        </div>
                        <div class="box-body">
                            <form role="form" method="post" action="{{ url('participants/update/'.$info->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="fname">First Name</label>
                                        <input type="text" required autocomplete="off" name="fname" value="{{ $info->fname }}" class="form-control" id="fname" placeholder="Enter First Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="mname">Middle Name</label>
                                        <input type="text" required autocomplete="off" name="mname" value="{{ $info->mname }}" class="form-control" id="mname" placeholder="Enter First Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="lname">Last Name</label>
                                        <input type="text" required autocomplete="off" name="lname" value="{{ $info->lname }}" class="form-control" id="lname" placeholder="Enter Last Name">
                                    </div>     
                                    <div class="form-group">
                                            <label for="designation">Designation</label>
                                        <input type="text" required autocomplete="off" name="designation" class="form-control" id="designation" placeholder="Enter Designation" value="{{ $info->designation }}">
                                        </div>  
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" required autocomplete="off" name="email" value="{{ $info->email }}" class="form-control" id="email" placeholder="Enter Email">
                                    </div> 
                                    <div class="form-group">
                                        <label for="contact">Contact #</label>
                                        <input type="contact" required autocomplete="off" name="contact" value="{{ $info->contact }}" class="form-control" id="contact" placeholder="Enter Contact #">
                                    </div>  
                                    <div class="form-group">
                                        <label for="division">Division</label>
                                        <select name="division" class="form-control" required>
                                            <option value="">Select Division...</option>    
                                            @foreach($division as $d)
                                                <option value="{{ $d->id }}" @if($info->division==$d->id) selected @endif>{{ $d->name }}</option>
                                            @endforeach
                                        </select>    
                                    </div>                          
                                </div>

                                <div class="box-footer">
                                    <a href="{{ url('/participants') }}" class="pull-left btn btn-default">
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
            <div class="col-md-9">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">List of Participants</h3>

                        <div class="box-tools">
                            <form method="post" action="{{ url('participants/search') }}">
                                {{ csrf_field() }}
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="keyword" value="{{ Session::get('search_participant') }}" class="form-control pull-right" placeholder="Search">

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
                                    <th class="text-center">Hours of<br />Training</th>
                                    <th></th>           
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
                                        <td>{{ \app\Division::find($row->division)->name }}</td>
                                        <td class="text-center text-danger text-bold">
                                            <a href="#info" class="editable" data-id="{{ $row->id }}">
                                            {{ number_format(App\Monitoring::leftJoin('trainings','trainings.id','=','monitoring.training_id')
                                                    ->where('participant_id',$row->id)
                                                    ->sum('trainings.hours'))
                                            }}
                                            </a>
                                        </td>
                                        <td>
                                            <a class="pull-right text-danger" href="#delete" data-id="{{ $row->id }}">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>
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
    @include('script.info')
    <script>
        $('a[href="#delete"]').on('click',function(){
            var id = $(this).data('id');
            $('#delete').modal('show');
            $('#delete_link').attr('href',"{{ url('/participants/delete/') }}/"+id);
        });
    </script>
@endsection