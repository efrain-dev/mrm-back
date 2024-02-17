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
                'payroll' => 'required',
            ]);
            $data = $request->all();
            $pay = Payroll::find($data['payroll']);
            [$payroll,$empleados] = $this->calcPayroll($pay);
            return response()->json([
                'status' => 1,
                'message' => 'Successfully payroll',
                'payroll' => $payroll,
                'detail' => $empleados

            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }

    }
    private function calcPayroll($payroll){
        $empleados = DB::table('report')->where('id_payroll', '=',  $payroll->id)
            ->select('worker.name', 'worker.last_name', 'worker.salary as rate', 'worker.rate_night', 'worker.id as id_worker')
            ->join('worker', 'worker.id', '=', 'report.id_worker')->groupBy('id_worker')->get();
        $bonusPer = $this->getBonPermament($payroll->id);
        $payroll->net_pay= 0;
        $payroll->ncdor= 0;
        $payroll->total= 0;
        $empleados = $this->mapEmpleado($empleados,$payroll,$bonusPer);
        return [$payroll,$empleados];
    }
    private function mapEmpleado($empleados,$payroll,$bonusPer){
        $empleados->map(function ($item) use ($payroll, $bonusPer) {

            [$total_hours,$regular_hours,$extra_hours,$night_hours,$period,$night,$overtime,$bon,$desc,$net_pay,$ncdor,$total,$gross_pay]=  $this->calcEmpleado($payroll->id,$bonusPer,$item);
            $item->total_hours =$total_hours;
            $item->regular_hours = $regular_hours;
            $item->extra_hours =$extra_hours;
            $item->night_hours = $night_hours;
            $item->bonifications = $bon;
            $item->extra_deductions = $desc;
            $item->period=  $period;
            $item->total_night = $night;
            $item->total_overtime = $overtime;
            $item->net_pay = $net_pay;
            $payroll->net_pay +=$item->net_pay;
            $payroll->ncdor +=$ncdor;
            $payroll->total +=$total;
            $item->total =   $total;
            $item->gross_pay =   $gross_pay;
        });
        return $empleados;
    }
    private function calcEmpleado($payroll,$bonusPer,$item){
        $reports = DB::table('report')->selectRaw('sum(regular) as regular, sum(extra) as extra, sum(night) as night')->where('id_payroll', '=', $payroll)->where('report.id_worker', $item->id_worker)->first();
        $total_hours = $reports->regular + $reports->extra + $reports->night;
        $regular_hours = $reports->regular;
        $extra_hours = $reports->extra;
        $night_hours = $reports->night;
        [$bon, $desc] = $this->getBon($payroll, $item->id_worker);
        $period=  $item->rate *$total_hours;
        $night=  (($item->rate * ($bonusPer->firstWhere('name','=','Night hours')->amount/100)) + $item->rate)*$night_hours;
        $overtime=  (($item->rate * ($bonusPer->firstWhere('name','=','Overtime Hours')->amount/100)) + $item->rate)*$extra_hours;
        $net_pay = $period+$night+$overtime;
        $total =$net_pay+$bon-$desc;
        $ncdor = $net_pay* ($bonusPer->firstWhere('name','=','NCDOR')->amount/100);
        $gross_pay =   $total- $ncdor;
        return [$total_hours,$regular_hours,$extra_hours,$night_hours,$period,$night,$overtime,$bon,$desc,$net_pay,$ncdor,$total,$gross_pay];
    }
    private function getBon($payroll, $worker)
    {
        $bonus = DB::table('bonus_payroll')->join('detail_bonus', 'detail_bonus.id', '=', 'bonus_payroll.id_detail_bonus')
            ->join('bonus', 'bonus.id', '=', 'detail_bonus.id_bonus')
            ->where('bonus_payroll.id_payroll','=',$payroll)
            ->where('bonus_payroll.id_worker','=',$worker)
            ->select('detail_bonus.*','bonus.type')
            ->get();
        $bon = $bonus->where('type','=','B')->sum('amount');
        $des = $bonus->where('type','=','D')->sum('amount');
        return [$bon, $des];

    }
    private function getBonPermament($payroll)
    {
       return DB::table('bonus_payroll')->join('detail_bonus', 'detail_bonus.id', '=', 'bonus_payroll.id_detail_bonus')
            ->join('bonus', 'bonus.id', '=', 'detail_bonus.id_bonus')
            ->where('bonus_payroll.id_payroll','=',$payroll)
            ->where('permanent', '=', true)->where('active','=',1)
            ->select('detail_bonus.*','bonus.type','bonus.name')
            ->get();


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
            $data['start'] = Carbon::createFromFormat('d/m/Y', $data['start']);
            $data['end'] = Carbon::createFromFormat('d/m/Y', $data['end']);
            $data['users_id'] = $request->user()->id;
            $payroll = Payroll::create($data);
            $global = DB::table('bonus')->join('detail_bonus', 'detail_bonus.id_bonus', '=', 'bonus.id')->where('permanent', '=', true)
                ->where('active', '=', 1)->select('detail_bonus.id as id')->get();
            foreach ($global as $item){
                DB::table('bonus_payroll')->insert(['id_detail_bonus' => $item->id, 'id_payroll'=>$payroll->id,'id_worker'=>null]);

            }
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
