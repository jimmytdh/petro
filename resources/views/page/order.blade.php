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
            <div class="col-xs-12">
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Hospital Order
                        <br /> 
                        <small class="text-bold text-danger">[Result: 0]</small>
                    </h3>
                    <div class="pull-right">
                        <form method="post" action="{{ url('order/search') }}" class="form-inline">  
                            <div class="form-group">
                                <input type="date" name="table_search" class="form-control pull-right" value="{{ date('Y-m-d') }}">
                            </div>     
                            <div class="input-group">
                                <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">   
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                                </div> 
                            </div>                                           
                        </form>
                    </div>
                    
                    {{-- <div class="box-tools">
                      <div class="input-group input-group-sm" style="width: 280px;">
                        <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                        <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
      
                        <div class="input-group-btn">
                          <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                        <div class="input-group-btn">
                            <a href="{{ url('/order/add') }}" class="btn btn-success"><i class="fa fa-plus"></i> Add Participant</a>
                        </div>
                      </div>
                    </div> --}}
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="bg-black">
                            <tr>
                                <th width="15%">Date</th>
                                <th width="35%">Training</th>
                                <th width="30%">Participant</th>
                                <th width="10%" class="text-center"># of Hours</th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-info text-bold">
                                    <i class="fa fa-calendar"></i> 
                                    August 14, 2019
                                </td>
                                <td class="text-success">BLS Training</td>
                                <td>Jimmy B. Lomocso Jr.</td>
                                <td class="text-center text-danger text-bold">8 hrs</td>
                                <td class="text-center">
                                    <a class="text-danger" href="#delete" data-id="">
                                        <i class="fa fa-trash-o"></i> Delete
                                    </a>
                                </td>
                            </tr>            
                        </tbody>
                    </table>
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->
              </div>
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
                    <p>Are you sure you want to delete this reviewee?</p>
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
            $('#delete_link').attr('href',"{{ url('division/delete/') }}/"+id);
        });
    </script>
@endsection