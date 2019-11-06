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
            @if(!$edit)
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Deliverable</h3>
                        </div>
                        <div class="box-body">
                            <form method="post" action="{{ url('deliverable/save') }}">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="name">Title</label>
                                        <input type="text" autofocus required autocomplete="off" name="name" class="form-control" id="name" placeholder="Enter Deliverable Title">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Target Personnel</label>
                                        <input type="number" min="1" required autocomplete="off" name="target" class="form-control" placeholder="0">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Target Month</label>
                                        <select name="month" id="" class="form-control" required>
                                            @for($i=1; $i<=12; $i++)
                                                <option value="{{ $i }}">{{ \App\Http\Controllers\ParamController::convertNumToMonth($i) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Target Year</label>
                                        <select name="year" id="" class="form-control" required>
                                            <?php $year = (date('Y'))+1; ?>
                                            @for($i=1; $i<=10; $i++)
                                                <option>{{ $year-- }}</option>
                                            @endfor
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
                <div class="col-md-4">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Update Participant</h3>
                        </div>
                        <div class="box-body">
                            <form role="form" method="post" action="{{ url('deliverable/update/'.$info->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="name">Title</label>
                                        <input type="text" autofocus required autocomplete="off" value="{{ $info->name }}" name="name" class="form-control" id="name" placeholder="Enter Division Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Target Personnel</label>
                                        <input type="number" min="1" required autocomplete="off" name="target" value="{{ $info->target }}" class="form-control" placeholder="0">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Target Month</label>
                                        <select name="month" id="" class="form-control" required>
                                            @for($i=1; $i<=12; $i++)
                                                <option value="{{ $i }}" @if($i==$info->target_month) selected @endif>{{ \App\Http\Controllers\ParamController::convertNumToMonth($i) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Target Year</label>
                                        <select name="year" id="" class="form-control" required>
                                            <?php $year = (date('Y'))+1; ?>
                                            @for($i=1; $i<=10; $i++)
                                                <option @if($year==$info->target_year) selected @endif>{{ $year-- }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <a href="{{ url('/deliverable') }}" class="pull-left btn btn-default">
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
                        <h3 class="box-title">List of Deliverable</h3>

                        <div class="box-tools">
                            @if(Session::get('deliverableFilter'))
                            <a href="{{ url('/param/clear/deliverableFilter') }}" class="btn btn-warning btn-sm"><i class="fa fa-trash"></i> Clear Filter</a>
                            @endif
                            <button data-toggle="modal" data-target="#filter" type="button" class="btn btn-success btn-sm"><i class="fa fa-filter"></i> Filter Result</button>
                        </div>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body table-responsive">
                        @if(count($data)>0)
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Target<br/>Personnel</th>
                                    <th>Target<br/>Month</th>
                                    <th>Target<br/>Year</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td>
                                            <a href="{{ url('deliverable/edit/'.$row->id) }}" class="editable">
                                                {{ str_pad($row->id,'4','0',STR_PAD_LEFT) }}
                                            </a>
                                        </td>
                                        <td class="text-success">
                                            @if(\App\Http\Controllers\DeliverableCtrl::isLink($row->id))
                                                <small><i class="fa fa-link text-danger"></i></small>
                                            @endif
                                            {{ $row->name }}
                                        </td>
                                        <td>{{ $row->target }}</td>
                                        <td>{{ \App\Http\Controllers\ParamController::convertNumToMonth($row->target_month) }}</td>
                                        <td>{{ $row->target_year }}</td>
                                        <td>
                                            <?php
                                                $total = 0;
                                                $d = \App\Http\Controllers\DeliverableCtrl::countTraining($row->id);
                                                if($d!=0)
                                                    $total = ($d / $row->target) * 100;
                                            ?>
                                            @if($total>=100)
                                                <font class="text-success text-bold">
                                            @elseif($total<=99 && $total>=75)
                                                <font class="text-info">
                                            @else
                                                <font class="text-danger">
                                            @endif
                                            {{ number_format($total,1) }}%
                                            </font>
                                        </td>
                                        <td>
                                            <a class="pull-right text-danger" href="#delete" data-id="{{ $row->id }}">
                                                <i class="fa fa-trash-o"></i>
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
                                <p>Opps. No deliverable found!</p>
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
                    <p class="text-center">Are you sure you want to delete this deliverable?</p>
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

    <div class="modal fade" id="filter">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-search"></i> Filter Result </h4>
                </div>
                <form action="{{ url('deliverable/search') }}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Keyword</label>
                            <input type="text" name="keyword" class="form-control" value="{{ $filterKeyword }}"/>
                        </div>
                        <div class="form-group">
                            <label for="">Target Month</label>
                            <select name="month" id="" class="form-control">
                                <option value="">Select Month...</option>
                                @for($i=1; $i<=12; $i++)
                                    <option @if($i==$filterMonth) selected @endif value="{{ $i }}">{{ \App\Http\Controllers\ParamController::convertNumToMonth($i) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Target Year</label>
                            <select name="year" id="" class="form-control">
                                <option value="">Select Year...</option>
                                <?php $year = (date('Y'))+1; ?>
                                @for($i=1; $i<=10; $i++)
                                    <option @if($year==$filterYear) selected @endif>{{ $year-- }}</option>
                                @endfor
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
    <script>
        $('a[href="#delete"]').on('click',function(){
            var id = $(this).data('id');
            $('#delete').modal('show');
            $('#delete_link').attr('href',"{{ url('deliverable/delete/') }}/"+id);
        });
    </script>
@endsection