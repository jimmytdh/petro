<html>
<title>Track Details</title>
<style>
    .upper, .info, .table {
        width: 100%;
    }
    .upper td, .info td, .table td {
        border:1px solid #000;
    }
    .upper td {
        padding:10px;
    }
    .info {
        margin-top: 90px;
    }
    .info td {
        padding: 5px;
        vertical-align: top;
    }
    .table th {
        border:1px solid #000;
    }
    .table td {
        padding: 5px;
        vertical-align: top;
    }
    .barcode {
        top: 110px;
        position: relative;
        left: -33%;
    }
    .route_no {
        font-size:1.2em;
        margin-left:50px;
    }

</style>
<body>
<div style="position: absolute; left: 50%;">
    <div class="barcode">
        <?php echo DNS1D::getBarcodeHTML(Session::get('route_no'),"C39E",1,43) ?>
        <font class="route_no">{{ $route_no }}</font>
    </div>
</div>
<table class="upper" cellpadding="0" cellspacing="0">
    <tr>
        <?php $image_path = '/img/doh.png'; ?>
        <td width="20%"><center><img src="{{ public_path() . $image_path }}" width="80"></center></td>
        <td width="60%">
            <center>
                <strong>Republic of the Philippines</strong><br>
                Central Visayas Center for Health Development<br>
                <h3 style="margin:0;">TDH Tracking</h3>
            </center>
        </td>
    <!--
            {{--<td width="20%"><?php echo DNS2D::getBarcodeHTML(Session::get('route_no'), "QRCODE",5,5); ?></td>--}}
            -->
        <?php $image_path = '/img/logo.png'; ?>
        <td width="20%"><center><img src="{{ public_path() . $image_path }}" width="100"></center></td>
    </tr>

</table>

<table class="info" width="100%" cellspacing="0">
    <tr>
        <td width="30%">
            <strong>PREPARED BY:</strong><br>
            <?php $user = \App\User::find($document->prepared_by); ?>
            {{ $user->fname.' '.$user->lname }}
            <br><br>
        </td>
        <td>
            <strong>SECTION:</strong><br>
            {{ \App\Section::find($user->section)->description }}
            <br><br>
        </td>
        <td width="30%">
            <strong>PREPARED DATE:</strong><br>
            {{ date('M d, Y',strtotime($document->prepared_date)) }}
            <br><br>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <strong>DOCUMENT TYPE:</strong>
            <br />
            {{ \App\Http\Controllers\DocumentController::documentType($document->doc_type) }}
            <br>
            <br>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <strong>REMARKS / SUBJECT:</strong>
            <br />
            {!! nl2br($document->description) !!}
            <br>
            <br>
        </td>
    </tr>
</table>

<table cellspacing="0" class="table">
    <tr>
        <th width="15%">DATE</th>
        <th width="35%">RECEIVED BY</th>
        <th width="35%">ACTION / REMARKS</th>
        <th width="15%">SIGNATURE</th>
    </tr>
    <?php $c = 0; ?>
    @foreach($tracking as $doc)
        @if(($doc->received_by!=0)&&($doc->received_by != $doc->delivered_by) && ($c==1))
            <tr>
                <td>{{ date('M d, Y', strtotime($doc->date_in)) }}<br>{{ date('h:i A', strtotime($doc->date_in)) }}</td>
                <td>
                    <?php $user = \App\User::find($doc->received_by); ?>
                    {{ $user->fname }} {{ $user->lname }}
                    <br>
                    <em>({{ \App\Section::find($user->section)->description }})</em>
                </td>
                <td>{{ $doc->remarks }}</td>
                <td></td>
            </tr>
        @endif
        <?php $c = 1; ?>
    @endforeach
    <?php $i = count($tracking); ?>
    @for($i; $i < 10; $i++)
        <tr>
            <td>&nbsp;<br><br></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    @endfor
</table>
</body>
</html>