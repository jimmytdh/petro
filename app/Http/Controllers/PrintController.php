<?php

namespace App\Http\Controllers;

use App\Tracking;
use App\TrackingMaster;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PrintController extends Controller
{
    public function printEmpty($size)
    {
        $route_no = Session::get('route_no');

        $pdf = PDF::loadView('pdf.empty',[
            'size' => $size,
            'route_no' => $route_no,
        ])
            ->setPaper($size, 'portrait');


        return $pdf->stream($route_no.'.pdf');
    }

    public function printTrack()
    {
        $route_no = Session::get('route_no');
        $document = TrackingMaster::where('route_no',$route_no)->first();
        $tracking = Tracking::where('track_id',$document->id)->get();

        $pdf = PDF::loadView('pdf.track',[
            'document' => $document,
            'tracking' => $tracking,
            'route_no' => $route_no
        ])
            ->setPaper('a4', 'portrait');

        return $pdf->stream($route_no.'.pdf');
    }
}
