<?php

namespace App\Http\Controllers;

use App\Monitoring;
use App\Training;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FixController extends Controller
{
    function trainingdate()
    {
        $monitoring = Monitoring::leftJoin('trainings','trainings.id','=','monitoring.training_id')
                        ->select('monitoring.id','trainings.date_training','trainings.id as training_id')
                        ->get();
        foreach($monitoring as $row){
            $date = Carbon::parse($row->date_training);
            $data = array(
                'created_at' => $date,
                'updated_at' => $date
            );
            Monitoring::where('id',$row->id)
                ->update($data);

            Training::where('id',$row->training_id)
                ->update($data);

            echo $row->id.' - '.$date.'<br>';
        }
    }
}
