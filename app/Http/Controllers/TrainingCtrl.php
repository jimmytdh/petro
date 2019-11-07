<?php

namespace App\Http\Controllers;

use App\Deliverable;
use App\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Training;
use App\Monitoring;
use App\Participant;

class TrainingCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }
    
    public function index($edit=false,$info=array())
    {
        $keyword = Session::get('search_training');
        $data = Training::select('*');
        if($keyword){
            $data = $data->where('name','like',"%$keyword%");
        }
        $data = $data->paginate(30);

        $deliverable = Deliverable::orderBy('name','asc')->get();
        $division = Division::orderBy('name','asc')->get();

        return view('page.training',[
            'menu' => 'trainings',
            'data' => $data,
            'edit' => $edit,
            'info' => $info,
            'deliverable' => $deliverable,
            'division' => $division
        ]);
    }

    public function search(Request $req)
    {
        Session::put('search_training',$req->keyword);
        return redirect()->back();
    }

    public function save(Request $req)
    {
        $data = array(
            'name' => $req->name,
            'date_training' => $req->date_training,
            'hours' => $req->hours,
            'deliverable' => $req->deliverable
        );

        $validateName = Training::where($data)->first();
        if(!$validateName) {
            Training::create($data);
            return redirect('/trainings')->with('status',[
                'status' => 'success',
                'title' => 'Added',
                'msg' => $req->name.' successfully added!'
            ]);
            
        }

        return redirect()->back()->with('status',[
            'title' => 'Duplicate',
            'msg' => "Training was already added in the system!",
            'status' => 'error'
        ]);
    }

    public function edit($id)
    {
        $data = Training::find($id);
        return self::index(true,$data);
    }

    public function update(Request $req, $id)
    {
        $data = array(
            'name' => $req->name,
            'date_training' => $req->date_training,
            'hours' => $req->hours,
            'deliverable' => $req->deliverable
        );

        $validate = Training::where($data)
                        ->where('id','<>',$id)
                        ->first();
        if($validate){
            return redirect()->back()->with('status',[
                'title' => 'Duplicate',
                'msg' => "Training was already added to the system!",
                'status' => 'error'
            ]);
        }
        $data = Training::where('id',$id)
            ->update($data);

        return redirect()->back()->with('status',[
            'status' => 'success',
            'title' => 'Updated',
            'msg' => $req->name.' successfully updated!'
        ]);
    }

    public function delete($id)
    {
        $validate = Monitoring::where('training_id',$id)->first();
        if($validate){
            return redirect()->back()->with('status',[
                'status' => 'info',
                'title' => 'Denied',
                'msg' => "Training is in use!"
            ]);
        }

        Training::find($id)->delete();
        return redirect()->back()->with('status',[
            'status' => 'info',
            'title' => 'Deleted',
            'msg' => "1 training successfully deleted!"
        ]);
    }

    public function list($id)
    {
        $name = Training::find($id)->name;
        $included = Monitoring::select('participant_id')->where('training_id',$id)->get();        
        $list = Participant::select('participants.*','divisions.name')
                ->whereNotIn('participants.id',$included)
                ->leftJoin('divisions','divisions.id','=','participants.division')
                ->orderBy('participants.lname','asc')
                ->get();

        $data = Monitoring::select(
                        'monitoring.id',
                        'participants.lname',
                        'participants.fname',
                        'participants.mname',
                        'divisions.name',
                        'participants.id as participant_id',
                        'monitoring.cert'
                    )
                    ->leftJoin('participants','participants.id','=','monitoring.participant_id')
                    ->leftJoin('divisions','divisions.id','=','participants.division')
                    ->where('training_id',$id)
                    ->orderBy('participants.lname','asc')
                    ->get();

        return view('page.training.list',[
            'menu' => 'trainings',
            'data' => $data,
            'list' => $list,
            'name' => $name,
            'id' => $id
        ]);
    }

    static function countParticipants($id)
    {
        $c = Monitoring::where('training_id',$id)->count();
        return $c;
    }

    public function add(Request $req, $id)
    {
        foreach($req->list as $row){
            $data = array(
                'training_id' => $id,
                'participant_id' => $row
            );
            Monitoring::create($data);
        }
        return redirect()->back()->with('status',[
            'status' => 'success',
            'title' => 'Added',
            'msg' => "Participants successfully added!"
        ]);
    }

    public function deleteParticipant($trainign_id,$participant_id)
    {
        Monitoring::where('participant_id',$participant_id)
            ->where('training_id',$trainign_id)
            ->delete();

        return redirect()->back()->with('status',[
            'status' => 'info',
            'title' => 'Deleted',
            'msg' => "Participant successfully deleted!"
        ]);
    }
}
