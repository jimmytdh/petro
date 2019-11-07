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

        $name = "$user->fname $user->lname $training";
        $name = preg_replace('/[^A-Za-z0-9\-]/', ' ', $name);
        $fileName = str_replace(' ','_',$name).".".$req->file('file')->getClientOriginalExtension();

        $path = "upload/$division/$year";

        $req->file('file')->move($path,$fileName);
        $data->update([
             'cert' => "$path/$fileName"
        ]);

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
}
