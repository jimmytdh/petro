<div class="modal fade" id="addDocument">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-green text-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-o"></i> Create Document</h4>
            </div>
            <form method="post" action="{{ url('/documents/save') }}">
            {{ csrf_field() }}
            <div class="modal-body no-padding">
                <table class="table table-hover table-striped" style="margin: 0px;">
                    <tbody>
                        <tr>
                            <td class="text-right">Prepared By :</td>
                            <td class="text-bold">{{ $user->fname }} {{ $user->lname }}</td>
                        </tr>
                        <tr>
                            <td class="text-right">Prepared Date :</td>
                            <td class="text-bold">{{ date('M d, Y h:i a') }}</td>
                        </tr>
                        <tr>
                            <td class="text-right col-lg-4">Document Type :</td>
                            <td class="col-lg-8">
                                <select name="doc_type" class="form-control form-select-sm" required>
                                    <option value="general">General Document</option>
                                    <option value="incoming">Incoming Mail</option>
                                    <option value="outgoing">Outgoing Mail</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-right">Remarks / <br />Additional Information :</td>
                            <td>
                                <textarea name="description" class="form-control" rows="7" style="resize: none;"></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="updateDocument">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-green text-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-o"></i> Update Document</h4>
            </div>
            <div class="document_content">
                <div class="modal-body no-padding">
                    <div class="text-center" style="padding:20px">
                        <img src="{{ url('img/loading.gif') }}" /><br />
                        <small class="text-muted">Loading...Please wait...</small>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="paperSize" style="z-index:999991;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-file-pdf-o"></i> Select Paper Size</h4>
            </div>
            <div class="modal-body text-center">
                <div class="col-xs-4">
                    <a href="#" class="text-success"
                       onclick="window.open('{{ url('/pdf/empty/letter/') }}',
                                    'Print Barcode',
                                    'width=700,height=700');">
                        <i class="fa fa-file-pdf-o fa-5x"></i><br>
                        Letter
                    </a>
                </div>
                <div class="col-xs-4">
                    <a href="#" class="text-warning"
                        onclick="window.open('{{ url('/pdf/empty/a4/') }}',
                        'Print Barcode',
                        'width=700,height=700');">
                        <i class="fa fa-file-pdf-o fa-5x"></i><br>
                        A4
                    </a>
                </div>
                <div class="col-xs-4">
                    <a href="#" class="text-danger"
                        onclick="window.open('{{ url('/pdf/empty/legal/') }}',
                           'Print Barcode',
                           'width=700,height=700');">
                        <i class="fa fa-file-pdf-o fa-5x"></i><br>
                        Legal
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
            <br />

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

