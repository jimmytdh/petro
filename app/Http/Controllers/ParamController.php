<?php

namespace App\Http\Controllers;

use App\Section;
use App\Tracking;
use App\TrackingMaster;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ParamController extends Controller
{
    static function getAge($dob)
    {
        $now = Carbon::now();
        $dob = Carbon::parse($dob);
        $age = $dob->diffInYears($now);

        return $age;
    }

    static function timeDiff($start,$end)
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);

        if($start > $end)
            return false;

        $start_time = strtotime($start);
        $end_time = strtotime($end);
        $difference = $end_time - $start_time;
        $diff = '';

        $seconds = $difference % 60;            //seconds
        $difference = floor($difference / 60);

        if($seconds) $diff = $seconds."sec";

        $min = $difference % 60;              // min
        $difference = floor($difference / 60);

        if($min) $diff = $min."min $diff";

        $hours = $difference % 24;  //hours
        $difference = floor($difference / 24);

        if($hours) $diff = $hours."hr $diff";

        $days = $difference % 30;  //days
        $difference = floor($difference / 30);

        if($days) $diff = $days."day $diff";

        $month = $difference % 12;  //month
        $difference = floor($difference / 12);

        if($month) $diff = $month."mo $diff";


        return $diff;
    }

    public function changeYear(Request $req)
    {
        Session::put('year',$req->year);
        return redirect()->back();
    }
}
