<form action="{{ url('trainings/certificate/'.$data->id) }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group" style="border:1px solid #ccc;padding: 5px 3px 3px 8px;">
            <label class="text-bold text-success"><input type="checkbox" name="with_cert" value="1" @if($data->with_cert) checked @endif> With Certificate?</label>
        </div>
        <div class="form-group">
            <input type="file" name="file" class="form-control" accept="application/pdf" />
        </div>
        @if(strlen($cert)>0)
        <div class="text-center">
            <a href="{{ url($data->cert) }}" class="text-danger text-bold" target="_blank">
                <i class="fa fa-file-pdf-o"></i> View Certificate
            </a>
        </div>
        @else
            <div class="text-muted text-center">
                <i class="fa fa-times"> No Certificate Attached</i>
            </div>
        @endif
    </div>
    <div class="modal-footer">
        <a href="{{ url('/trainings/certificate/delete/'.$data->id) }}" class="btn btn-danger pull-left"><i class="fa fa-trash-o"></i> Delete</a>
        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Update</button>
    </div>
</form>