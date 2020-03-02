<?php

namespace App\Http\Controllers;

use App\Division;
use App\Monitoring;
use App\Participant;
use App\Training;
use Carbon\Carbon;
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
        $d = Session::get('year')."-01-01";
        $start = Carbon::parse($d)->startOfYear();
        $end = Carbon::parse($d)->endOfYear();

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
                    ->whereBetween('trainings.date_training',[$start,$end])
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
        $d = Session::get('year')."-01-01";
        $start = Carbon::parse($d)->startOfYear();
        $end = Carbon::parse($d)->endOfYear();

        $participant = Participant::leftJoin('divisions','divisions.id','=','participants.division')
                    ->where('participants.id',$id)
                    ->first();
        
        $monitoring = Monitoring::leftJoin('trainings','trainings.id','=','monitoring.training_id')
                        ->where('participant_id',$id)
                        ->whereBetween('trainings.date_training',[$start,$end]);

        $mon = $monitoring->get();

        $cert = $monitoring->sum('with_cert');

        return view('load.info',[
            'participant' => $participant,
            'monitoring' => $mon,
            'no_cert' => $cert
        ]);
    }

    public function certificate($id){
        $data = Monitoring::find($id);
        return view('load.certificate',[
            'data' => $data,
            'cert' => $data->cert
        ]);
    }

    public function certificateUpload(Request $req,$id){
        $data = Monitoring::find($id);
        $training = Training::find($data->training_id)->name;
        $user = Participant::find($data->participant_id);
        $division = Division::find($user->division)->name;
        $year = Training::find($data->training_id)->date_training;
        $year = Carbon::parse($year)->format('Y');

        $file['with_cert'] = $req->with_cert;

        if($req->file('file')){
            $name = "$user->fname $user->lname $training";
            $name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name);
            $fileName = str_replace(' ','_',$name).".".$req->file('file')->getClientOriginalExtension();

            $path = "upload/$division/$year";

            $req->file('file')->move($path,$fileName);
            $file['cert'] = "$path/$fileName";
        }

        $data->update($file);

        return redirect()->back()->with('status',[
            'status' => 'success',
            'title' => 'Upload Success',
            'msg' => "Certificate of $user->fname $user->lname successfully uploaded!"
        ]);
    }

    public function certificateDelete($id)
    {
        $data = Monitoring::find($id);

        $cert = $data->cert;
        if(file_exists($cert)){
            unlink($cert);

            $data->update([
                'cert' => ''
            ]);
        }

        return redirect()->back()->with('status',[
            'status' => 'success',
            'title' => 'Success',
            'msg' => "Certificate successfully removed!"
        ]);
    }

    public function monthly($trainings = null, $date = null){
        if(!$date)
            $date = date('Y-m-d');

        return view('page.monthly',[
            'menu' => 'monthly',
            'trainings' => $trainings,
            'date' => $date
        ]);
    }

    public function getMonthlyTraining(Request $req){
        $start = Carbon::parse($req->date)->startOfMonth();
        $end = Carbon::parse($req->date)->endOfMonth();

        $trainings = Training::whereBetween('date_training',[$start,$end])
            ->orderBy('date_training','asc')
            ->get();
        if(count($trainings)==0)
            $trainings=null;

        return self::monthly($trainings, $start->format('Y-m-d'));
    }

    static function getTraineeList($training_id){
        $data = Monitoring::select(
                'monitoring.id',
                'participants.lname',
                'participants.fname',
                'participants.mname',
                'divisions.name',
                'participants.id as participant_id',
                'monitoring.cert',
                'monitoring.with_cert'
            )
            ->leftJoin('participants','participants.id','=','monitoring.participant_id')
            ->leftJoin('divisions','divisions.id','=','participants.division')
            ->where('training_id',$training_id)
            ->orderBy('participants.lname','asc')
            ->get();
        return $data;
    }
}
