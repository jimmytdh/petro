<html>
<style type="text/css">
    .barcode {
        @if($size=='letter')
        top: 960px;
        @elseif($size=='a4')
        top: 1030px;
        @elseif($size=='legal')
        top: 1255px;
        @endif
        position: relative;
        left: -20px;
    }
    .route_no {
        font-size: 1em;
        text-align:center;
    }
</style>
<title>{{ $route_no }}</title>
<body>
<div style="position: absolute;">
    <div class="barcode">
        <font class="route_no">{{ $route_no }}</font>
        <?php echo DNS1D::getBarcodeHTML($route_no,"C39E",1,15) ?>
    </div>
</div>
</body>
</html>