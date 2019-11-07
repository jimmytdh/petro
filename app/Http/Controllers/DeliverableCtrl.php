<?php

namespace App\Http\Controllers;

use App\Deliverable;
use App\Training;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Division;
use Illuminate\Support\Facades\Session;
use App\Participant;

class DeliverableCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
    }
    
    public function index($edit=false,$info=array())
    {
        $filterKeyword = '';
        $filterMonth = 0;
        $filterYear = 0;
        $filter = Session::get('deliverableFilter');
        $data = Deliverable::select('*');
        if($filter){
            $filter = (object)$filter;

            if($filter->keyword){
                $k = $filter->keyword;
                $data = $data->where('name','like',"%$k%");
                $filterKeyword = $filter->keyword;
            }

            if($filter->month){
                $data = $data->where('target_month',$filter->month);
                $filterMonth = $filter->month;
            }

            if($filter->year){
                $data = $data->where('target_year',$filter->year);
                $filterYear = $filter->year;
            }
        }
        $data = $data
                ->orderBy('target_date','desc')
                ->paginate(30);

        $division = Division::orderBy('name','asc')->get();

        return view('page.deliverable',[
            'menu' => 'deliverable',
            'data' => $data,
            'edit' => $edit,
            'info' => $info,
            'filterKeyword' => $filterKeyword,
            'filterMonth' => $filterMonth,
            'filterYear' => $filterYear,
            'division' => $division
        ]);
    }

    public function search(Request $req)
    {
        Session::put('deliverableFilter',[
            'keyword' => $req->keyword,
            'month' => $req->month,
            'year' => $req->year
        ]);

        return redirect('/deliverable');
    }

    public function save(Request $req)
    {
        $name = $req->name;
        $validateName = Deliverable::where('name',$name)->first();
        if($validateName) {
            return redirect()->back()->with('status',[
                'title' => 'Duplicate',
                'msg' => "Name is already taken. Please use different name!",
                'status' => 'error'
            ]);
        }else {
            $tmp = "$req->year-$req->month-01";
            $date = Carbon::parse($tmp)->endOfMonth();
            $table = new Deliverable();
            $table->name = $name;
            $table->division = $req->division;
            $table->target = $req->target;
            $table->target_date = $date;
            $table->save();
            return redirect('/deliverable')->with('status',[
                'status' => 'success',
                'title' => 'Added',
                'msg' => $req->name.' successfully added!'
            ]);
        }
    }

    static function isLink($id)
    {
        $link = Training::where('deliverable',$id)->first();
        if($link)
            return true;

        return false;
    }

    static function countTraining($id)
    {
        $count = Training::leftJoin('monitoring','monitoring.training_id','=','trainings.id')
                    ->leftJoin('deliverable','deliverable.id','=','trainings.deliverable')
                    ->where('deliverable.id',$id)
                    ->where('monitoring.participant_id','<>',0)
                    ->count();
        return $count;
    }

    public function edit($id)
    {
        $data = Deliverable::find($id);
        return self::index(true,$data);
    }

    public function update(Request $req, $id)
    {
        $validate = Deliverable::where('name',$req->name)
                        ->where('id','<>',$id)
                        ->first();
        if($validate){
            return redirect()->back()->with('status',[
                'title' => 'Duplicate',
                'msg' => "Name is already taken. Please use different name!",
                'status' => 'error'
            ]);
        }
        $tmp = "$req->year-$req->month-01";
        $date = Carbon::parse($tmp)->endOfMonth();
        $data = Deliverable::find($id)
            ->update([
                'name' => $req->name,
                'division' => $req->division,
                'target' => $req->target,
                'target_date' => $date
            ]);

        return redirect()->back()->with('status',[
            'status' => 'success',
            'title' => 'Updated',
            'msg' => $req->name.' successfully updated!'
        ]);
    }

    public function delete($id)
    {

        Deliverable::find($id)->delete();

        Training::where('deliverable',$id)
            ->update([
                'deliverable' => 0
            ]);

        return redirect('/deliverable')->with('status',[
            'status' => 'info',
            'title' => 'Deleted',
            'msg' => "1 deliverable successfully deleted!"
        ]);
    }

    public function getByDivision($division,$date)
    {
        $date = Carbon::parse($date);
        $data = Deliverable::select('id','name');
        if($division!='all'){
            $data = $data->where('division',$division);
        }

        $data = $data
                ->where('target_date','<=',$date)
                ->orderBy('name','asc')
                ->get();

        return $data;
    }
}
