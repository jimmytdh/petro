<div class="modal fade" id="acceptDocument">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-green">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-calendar-plus-o"></i> Accept Documents</h4>
            </div>
            <form action="{{ url('documents/accept') }}" method="post">
            {{ csrf_field() }}
            <div class="modal-body">
                <table width="100%">
                    @for($i=0;$i<10;$i++)
                    <tr>
                        <td width="50%" style="padding: 10px">
                            <input type="text" name="route_no[]" autofocus class="form-control form-block" placeholder="Enter Route No." /></td>
                        <td style="padding: 10px">
                            <input type="text" name="remarks[]" class="form-control form-block" placeholder="Remarks" />
                        </td>
                    </tr>
                    @endfor
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Accept</button>
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>