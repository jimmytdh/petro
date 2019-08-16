<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Division;
use Illuminate\Support\Facades\Session;
use App\Participant;

class DivisionCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }
    
    public function index($edit=false,$info=array())
    {
        $keyword = Session::get('search_division');
        $data = Division::select('*');
        if($keyword){
            $data = $data->where('name','like',"%$keyword%");
        }
        $data = $data->paginate(30);

        return view('page.division',[
            'menu' => 'division',
            'data' => $data,
            'edit' => $edit,
            'info' => $info
        ]);
    }

    public function search(Request $req)
    {
        Session::put('search_division',$req->keyword);
        return redirect()->back();
    }

    public function save(Request $req)
    {
        $name = $req->name;
        $validateName = Division::where('name',$name)->first();
        if($validateName) {
            return redirect()->back()->with('status',[
                'title' => 'Duplicate',
                'msg' => "Name is already taken. Please use different name!",
                'status' => 'error'
            ]);
        }else {
            $table = new Division();
            $table->name = $name;
            $table->save();
            return redirect('/division')->with('status',[
                'status' => 'success',
                'title' => 'Added',
                'msg' => $req->name.' successfully added!'
            ]);
        }
    }

    static function countParticipants($id)
    {
        return Participant::where('division',$id)->count();
    }

    public function edit($id)
    {
        $data = Division::find($id);
        return self::index(true,$data);
    }

    public function update(Request $req, $id)
    {
        $validate = Division::where('name',$req->name)
                        ->where('id','<>',$id)
                        ->first();
        if($validate){
            return redirect()->back()->with('status',[
                'title' => 'Duplicate',
                'msg' => "Name is already taken. Please use different name!",
                'status' => 'error'
            ]);
        }
        $data = Division::find($id)
            ->update([
                'name' => $req->name
            ]);

        return redirect()->back()->with('status',[
            'status' => 'success',
            'title' => 'Updated',
            'msg' => $req->name.' successfully updated!'
        ]);
    }

    public function delete($id)
    {
        $validate = Participant::where('division',$id)->first();
        if($validate){
            return redirect('/division')->with('status',[
                'status' => 'info',
                'title' => 'Denied',
                'msg' => "Division is in use!"
            ]);
        }

        Division::find($id)->delete();
        return redirect('/division')->with('status',[
            'status' => 'info',
            'title' => 'Deleted',
            'msg' => "1 division successfully deleted!"
        ]);
    }
}
