<?php

namespace App\Http\Controllers;

use App\Division;
use App\Monitoring;
use App\Participant;
use App\Tracking;
use App\TrackingMaster;
use App\Training;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }

    public function index()
    {
        return view('page.home',[
            'menu' => 'home'
        ]);
    }

    public function radarLabel()
    {
        $data = Division::select('name')->orderBy('name')->get();
        $tmp = array();
        foreach($data as $r)
        {
            $tmp[] = $r->name;
        }
        return $tmp;
    }

    public function radarData()
    {
        $div = Division::orderBy('name')->get();
        $data = array();
        foreach($div as $d)
        {
            $data[] = self::countFrequency($d->id);
        }
        return $data;
    }

    public function countFrequency($id)
    {
        $emp = Participant::select('id')->where('division',$id)->get();
        $totalEmp = count($emp);
        $totalTrain = Monitoring::join('participants','participants.id','=','monitoring.participant_id')
                        ->where('participants.division',$id)
                        ->groupBy('participants.id')
                        ->get();
        $totalTrain = count($totalTrain);
        
        if($totalEmp==0)
            return 0;

        $t = $totalTrain/$totalEmp;
        $t1 = $t * 100;
        $t2 = $t * 30;
        $total = number_format($t1 + $t2);
        return $total;
    }

    public function chartData()
    {
        $y = date('Y');
        $data = array();
        for($i=1; $i<=12; $i++)
        {
            $m = str_pad($i,2,"0",STR_PAD_LEFT);
            $date = "$y-$m-01";
            $start = Carbon::parse($date)->startOfMonth($date);        
            $end = Carbon::parse($date)->endOfMonth($date);        
            
            $data[] = Training::whereBetween('date_training',[$start,$end])->count();
        }
        return $data;
    }
}
