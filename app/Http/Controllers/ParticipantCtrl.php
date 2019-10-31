<?php

namespace App\Http\Controllers;

use App\Monitoring;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Participant;
use App\Division;

class ParticipantCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }
    
    public function index($edit=false,$info=array())
    {
        $keyword = Session::get('search_participant');
        $data = Participant::select('*');
        if($keyword){
            $data = $data->where(function($q) use($keyword){
                $q->where('fname','like',"%$keyword%")
                    ->orwhere('mname','like',"%$keyword%")
                    ->orwhere('designation','like',"%$keyword%")
                    ->orwhere('lname','like',"%$keyword%");
            });
        }
        $data = $data->paginate(30);

        $division = Division::orderBy('name','asc')->get();

        return view('page.participants',[
            'menu' => 'participants',
            'data' => $data,
            'edit' => $edit,
            'info' => $info,
            'division' => $division
        ]);
    }

    public function search(Request $req)
    {
        Session::put('search_participant',$req->keyword);
        return redirect()->back();
    }

    public function save(Request $req)
    {
        $data = array(
            'fname' => $req->fname,
            'mname' => $req->mname,
            'lname' => $req->lname,
            'email' => $req->email,
            'designation' => $req->designation,
            'contact' => $req->contact,
            'division' => $req->division
        );
        $match = array(
            'fname' => $req->fname,
            'lname' => $req->lname,
            'designation' => $req->designation
        );

        $validate = Participant::where($match)->first();
        if(!$validate){
            Participant::create($data);
            return redirect('/participants')->with('status',[
                'status' => 'success',
                'title' => 'Added',
                'msg' => "$req->fname $req->lname successfully added to the system!"
            ]);
        }

        return redirect('/participants')->with('status',[
            'status' => 'error',
            'title' => 'Duplicate',
            'msg' => "$req->fname $req->lname was already enrolled in the system!"
        ]);
    }

    public function delete($id)
    {
        Participant::find($id)->delete();
        return redirect('/participants')->with('status',[
            'status' => 'info',
            'title' => 'Deleted',
            'msg' => "1 Participant successfully deleted!"
        ]);
    }

    public function edit($id)
    {
        $data = Participant::find($id);
        return self::index(true,$data);
    }

    public function update(Request $req, $id)
    {
        $data = array(
            'fname' => $req->fname,
            'mname' => $req->mname,
            'lname' => $req->lname,
            'email' => $req->email,
            'designation' => $req->designation,
            'contact' => $req->contact,
            'division' => $req->division
        );

        $match = array(
            'fname' => $req->fname,
            'lname' => $req->lname,
            'designation' => $req->designation
        );

        $validate = Participant::where($match)
                        ->where('id','<>',$id)
                        ->first();
        
        if($validate){
            return redirect('/participants')->with('status',[
                'status' => 'error',
                'title' => 'Duplicate',
                'msg' => "$req->fname $req->lname was already enrolled in the system!"
            ]);
        }
        
        $data = Participant::where('id',$id)
            ->update($data);
        
        return redirect()->back()->with('status',[
            'status' => 'success',
            'title' => 'Updated',
            'msg' => "$req->fname $req->lname successfully added to the system!"
        ]);
    }

    public function noTraining()
    {
        $year = Session::get('year');
        $start = Carbon::parse('01-01-'.$year.' 00:00:00');
        $end = Carbon::parse('31-12-'.$year.' 23:23:59');

//        $crashedCarIds = CrashedCar::pluck('car_id')->all();
//        $cars = Car::whereNotIn('id', $crashedCarIds)->select(...)->get();
        $withTrainings = Monitoring::leftJoin('trainings','trainings.id','=','monitoring.training_id')
                ->whereBetween('trainings.date_training',[$start,$end])
                ->groupBy('monitoring.participant_id')
                ->pluck('monitoring.participant_id')
                ->all();

        $data = Participant::whereNotIn('id',$withTrainings);

        $noTrainingFilter = Session::get('noTrainingFilter');
        $filterKeyword = '';
        $filterDivision = '';

        if($noTrainingFilter)
        {
            $noTrainingFilter = (object)$noTrainingFilter;
            if($noTrainingFilter->keyword){
                $k = $noTrainingFilter->keyword;
                $data = $data->where(function($q) use($k){
                    $q->where('fname','like',"%$k%")
                        ->orwhere('mname','like',"%$k%")
                        ->orwhere('designation','like',"%$k%")
                        ->orwhere('lname','like',"%$k%");
                });
                $filterKeyword = $noTrainingFilter->keyword;
            }

            if($noTrainingFilter->division){
                $data = $data->where('division',$noTrainingFilter->division);
                $filterDivision = $noTrainingFilter->division;
            }
        }

        $data = $data
                ->orderBy('lname','asc')
                ->paginate(20);

        $division = Division::orderBy('name','asc')->get();

        return view('page.notraining',[
            'menu' => 'notraining',
            'title' => 'List of no Trainings',
            'data' => $data,
            'division' => $division,
            'filterKeyword' => $filterKeyword,
            'filterDivision' => $filterDivision
        ]);
    }

    public function searchNoTraining(Request $req)
    {
        Session::put('noTrainingFilter',[
            'keyword' => $req->keyword,
            'division' => $req->division
        ]);

        return redirect('/participants/notraining');
    }
}
