<?php

namespace App\Http\Controllers;

use App\Monitoring;
use App\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MonitoringCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }

    
    public function index()
    {
        $keyword = Session::get('search_monitoring');
        $data = Monitoring::select(
                        'monitoring.*',
                        'participants.*',
                        'participants.id as participant_id',
                        'monitoring.id as monitoring_id',
                        'divisions.name as division',
                        'trainings.name as training',
                        'trainings.hours',
                        'trainings.date_training as date'
                 );

        if($keyword){
            $data = $data->where(function($q) use ($keyword){
                $q->where('fname','like',"%$keyword%")
                    ->orwhere('lname','like',"%$keyword%")
                    ->orwhere('trainings.name','like',"%$keyword%");
            });
        }

        $data = $data->leftJoin('participants','participants.id','=','monitoring.participant_id')
                    ->leftJoin('divisions','divisions.id','=','participants.division')
                    ->leftJoin('trainings','trainings.id','=','monitoring.training_id')
                    ->orderBy('trainings.date_training','desc')
                    ->orderBy('participants.lname','asc')
                    ->paginate(30);

        return view('page.monitoring',[
            'menu' => 'monitoring',
            'data' => $data
        ]);
    }

    public function search(Request $req)
    {
        Session::put('search_monitoring',$req->keyword);
        return redirect()->back();
    }

    public function save(Request $req, $column, $id)
    {
        Monitoring::where('id',$id)
            ->update([
                $column => $req->score
            ]);

        $column = ucfirst($column);
        
        return redirect()->back()->with('status',[
            'status' => 'success',
            'title' => 'Updated',
            'msg' => "$column score succcessfully updated!"
        ]);
    }

    public function info($id){
        $participant = Participant::leftJoin('divisions','divisions.id','=','participants.division')
                    ->where('participants.id',$id)
                    ->first();
        
        $monitoring = Monitoring::leftJoin('trainings','trainings.id','=','monitoring.training_id')
                        ->where('participant_id',$id)
                        ->get();

        return view('load.info',[
            'participant' => $participant,
            'monitoring' => $monitoring
        ]);
    }
}
