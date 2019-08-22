<?php

namespace App\Http\Controllers;

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
            'contact' => $req->contact,
            'division' => $req->division
        );

        $validate = Participant::where($data)->first();
        if(!$validate){
            Participant::insert($data);
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
            'contact' => $req->contact,
            'division' => $req->division
        );

        $validate = Participant::where($data)
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
}
