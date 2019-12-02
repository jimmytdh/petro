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
        $year = Session::get('year');
        $d = $year."-01-01";
        $start = Carbon::parse($d)->startOfYear();
        $end = Carbon::parse($d)->endOfYear();

        $no_employee = Participant::count();

        $training = Monitoring::leftJoin('trainings','trainings.id','=','monitoring.training_id')
            ->whereBetween('trainings.date_training',[$start,$end])
            ->distinct('monitoring.participant_id');

        $with_training = $training->count('monitoring.participant_id');
        $included = $training->groupBy('monitoring.participant_id')
                ->pluck('monitoring.participant_id')
                ->all();

        $without_training = Participant::whereNotIn('id',$included)
                ->count();

        $total_monitoring = Monitoring::leftJoin('trainings','trainings.id','=','monitoring.training_id')
            ->whereBetween('trainings.date_training',[$start,$end])
            ->count();
        $no_certificate = Monitoring::leftJoin('trainings','trainings.id','=','monitoring.training_id')
            ->whereBetween('trainings.date_training',[$start,$end])
            ->where('with_cert',1)->count();

        $per_training = ($with_training / $no_employee) * 100;
        $per_without_training = ($without_training / $no_employee) * 100;
        $per_cert = ($no_certificate / $total_monitoring) * 100;
        $per_total = ($no_certificate/$no_employee)*100;

        return view('page.home',[
            'menu' => 'home',
            'no_employee' => $no_employee,
            'without_training' => $without_training,
            'no_training' => $total_monitoring,
            'per_training' => number_format($per_training,0),
            'per_without_training' => number_format($per_without_training,0),
            'per_cert' => number_format($per_cert,0),
            'per_total' => number_format($per_total,1),

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
        $year = Session::get('year');
        $d = $year."-01-01";
        $start = Carbon::parse($d)->startOfYear();
        $end = Carbon::parse($d)->endOfYear();

        $emp = Participant::select('id')->where('division',$id)->get();
        $totalEmp = count($emp);
        $totalTrain = Monitoring::join('participants','participants.id','=','monitoring.participant_id')
                        ->leftJoin('trainings','trainings.id','=','monitoring.training_id')
                        ->whereBetween('trainings.date_training',[$start,$end])
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
        $y = Session::get('year');
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
