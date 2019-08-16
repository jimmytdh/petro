<style>
    .tbl-info th {
        padding: 5px 5px;
    }

    .tbl-info td {
        padding: 3px 5px;
    }
</style>

<div class="modal-header bg-success text-success">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title title_name">
        {{ $participant->lname }}, {{ $participant->fname }} {{ $participant->mname }}
        <br />
        <small style="margin-top:-20px;">{{ $participant->name }}</small>
    </h4>
</div>
<div class="modal-body">
    <table class="tbl-info" width="100%" border="1">
        <thead class="bg-black">
            <tr>
                <th width="10%" class="text-center">#</th>
                <th width="70%">Date and Training</th>
                <th width="20%" class="text-center">Hours</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $c = 1; 
                $total = 0;
            ?>
            @foreach($monitoring as $m)

            <tr>
                <td class="text-right text-center">{{ $c++ }}</td>
                <td class="text-left">
                    <small class="text-danger">{{ date('F d, Y',strtotime($m->date_training)) }}</small>
                    <br />
                    {{ $m->name }}
                </td>
                <td class="text-bold text-center">{{ $m->hours }}</td>
                <?php $total += $m->hours; ?>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="modal-footer text-bold text-success">
    TOTAL HOURS: {{ number_format($total) }}
</div>