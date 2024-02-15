<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\BonusDetail;
use App\Models\Payroll;
use App\Models\Worker;
use App\Models\WorkerBonus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(Request $request)
    {

        $payroll = DB::table('payroll')->get();
//        $bonus->map(function ($data) {
//            $detail = DB::table('detail_bonus')->where('id_bonus', '=', $data->id)->where('active', '=', 1)->first();
//            $data->id_detail = $detail->id;
//            $data->calc = $detail->calc;
//            $data->amount = $detail->amount;
//        });

        return response()->json($payroll);
    }

    public function showPayroll(Request $request)
    {

        try {
            $this->validate($request, [
                'id' => 'required',
            ]);
            $data = $request->all();
            $payroll = Payroll::find($data['id']);
            return response()->json([
                'status' => 1,
                'message' => 'Successfully payroll',
                'payrall'=>$payroll
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }

    }

    public function newPayroll(Request $request)
    {

        try {
            $this->validate($request, [
                'start' => 'required',
                'end' => 'required',
                'type' => 'required',
                'description' => 'required'
            ]);
            $data = $request->all();
            $data['start'] = Carbon::parse($data['start']);
            $data['end'] = Carbon::parse($data['end']);
            $data['users_id'] = $request->user()->id;
            Payroll::create($data);
            return response()->json([
                'status' => 1,
                'message' => 'Successfully created payroll'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }

    }

}
