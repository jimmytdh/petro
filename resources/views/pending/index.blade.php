<?php
$user =  \Illuminate\Support\Facades\Session::get('user');
$keyword = \Illuminate\Support\Facades\Session::get('search_pending');
?>
@extends('app')

@section('css')
    <style>
        .table td {
            vertical-align: top;
        }
        .accept_status {
            border:1px solid #ccc;
            padding: 10px 20px;
            border-radius: 10px;
        }
        .accept_status li {
            margin-left: 15px;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $title }}</h3>
                    @if($keyword)
                        <br />
                        <small class="text-danger">
                            Keyword: {{ $keyword }}
                        </small>
                    @endif
                    <div class="box-tools pull-right">
                        <form class="form-inline" method="post" action="{{ url('/documents/pending/search') }}">
                            {{ csrf_field() }}
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#acceptDocument">
                                <i class="fa fa-calendar-plus-o"></i> Accept Document(s)
                            </button>
                            <div class="input-group input-group-sm">
                                <input type="text" name="keyword" value="{{ $keyword }}" class="form-control pull-right" placeholder="Search">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <?php
                        $result = session('result');
                    ?>
                    @if($result)
                        <?php
                            $c = count($result['route_no']);
                            $route_no = $result['route_no'];
                            $class = $result['class'];
                        ?>
                        <ul class="accept_status">
                            @for($i=0;$i<$c;$i++)
                                @if($class[$i]=='success')
                                    <li class="text-success text-bold">Route No. "{{ $route_no[$i] }}" successfully accepted!</li>
                                @else
                                    <li class="text-danger text-bold">Route No. "{{ $route_no[$i] }}" didn't match in the database.</li>
                                @endif

                            @endfor
                        </ul>
                    @endif
                    @if(count($data) > 0)
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr class="bg-black-active">
                                <th width="15%">Route No.</th>
                                <th width="15%">Delivered By</th>
                                <th width="15%">Document Type</th>
                                <th width="30%">Description</th>
                                <th width="15%">Duration</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $row)
                                <?php
                                    $nextDate = \App\Http\Controllers\ParamController::getNextDate($row->tracking_id,$row->id);
                                    $duration = \App\Http\Controllers\ParamController::timeDiff($row->date_in,$nextDate);
                                ?>
                            <tr>
                                <td class="text-bold text-success normal">
                                    <a href="#updateDocument" data-toggle="modal" data-id="{{ $row->id }}" class="editable">
                                        {{ $row->route_no }}
                                    </a>
                                    <br />
                                    <small class="text-muted small-sub">
                                        {{ \Carbon\Carbon::parse($row->date_in)->format('M d, Y h:i a') }}
                                    </small>
                                </td>
                                <td class="text-bold text-warning normal">
                                    @if($row->fname)
                                        {{ $row->lname }}, {{ $row->fname }}
                                        <br />
                                        <small class="text-muted small-sub">
                                            @if(strlen($row->section)>30)
                                                {{ $row->code }}
                                            @else
                                                {{ $row->section }}
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-danger" style="font-weight: normal">
                                            Own Document
                                        </span>
                                    @endif
                                </td>
                                <td>{{ \App\Http\Controllers\DocumentController::documentType($row->doc_type) }}</td>
                                <td>{!! nl2br($row->description) !!}</td>
                                <td class="text-danger">
                                    @if($duration)
                                        {{ $duration }}
                                    @else
                                        Just Now
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="#trackDocument" data-toggle="modal" class="btn btn-block btn-info btn-xs btn-track" data-route_no="{{ $row->route_no }}">
                                        <i class="fa fa-line-chart"></i> Track
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
                            <p>Opps. No documents in this query!</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->
@endsection

@section('modal')
    @include('modal.document')
@endsection

@section('js')
    <script>
        $('.btn-track').on('click',function(){
            var route_no = $(this).data('route_no');
            $('span#route_no').html(route_no);
            var url = "{{ url('track') }}/"+route_no;
            var loading = "{{ url('loading') }}";
            $('.track_content').load(loading);
            setTimeout(function(){
                $('.track_content').load(url);
            },1000);
        });

        $('a[href="#updateDocument"]').on('click',function(){
            var id = $(this).data('id');
            var url = "{{ url('documents/edit') }}/"+id;
            var loading = "{{ url('loading') }}";
            $('.document_content').load(loading);
            setTimeout(function(){
                $('.document_content').load(url);
            },1000);
        });

    </script>
@endsection