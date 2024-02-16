<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\BonusDetail;
use App\Models\Report;
use App\Models\Worker;
use App\Models\WorkerBonus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DetailController extends Controller
{

    public function getReport(Request $request)
    {

        $this->validate($request, [
            'worker' => 'nullable|numeric',
            'payroll'=> 'required'
        ]);
        $data = $request->all();
        $worker = $data['worker']?:null;
        $query = DB::table('report as r')->where(  'r.id_payroll','=',$data['payroll'])
            ->select('r.*');
        if ($worker){
            $query =   $query->where('id_worker','=', $worker);
        }
        return response()->json($query->get());
    }

    public function newDetail(Request $request)
    {
        try {
            $this->validate($request, [
                'regular' => 'required|numeric',
                'extra' => 'required|numeric',
                'night' => 'required|numeric',
                'start' => 'required',
                'end' => 'required',
                'id_payroll' => 'required',
                'id_worker' => 'required',

            ]);
            $data = $request->all();
            $data['start'] =  Carbon::createFromFormat('d/m/Y', $data['start']);
            $data['end'] =   Carbon::createFromFormat('d/m/Y', $data['end']) ;
            $data['users_id'] = $request->user()->id;

            Report::create($data);
            return response()->json([
                'status' => 1,
                'message' => 'Successfully created report'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteReport(Request $reques,$id)
    {
        try {
            $message = DB::transaction(function () use ($id) {
                Report::find($id)->delete();
                return [
                    'status' => 1,
                    'message' => 'Report deleted'
                ];
            });
        } catch (Exception $e) {
            $message = ['status' => 'error', 'message' => $e->getMessage(), 500];
        }
        return response()->json($message);

    }

}
