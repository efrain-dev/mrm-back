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
        $this->validate($request, [
            'from' => 'nullable',
            'to' => 'nullable',
            'filter' => 'nullable',
            'type' => 'nullable',
        ]);
        $filter = $request->get('filter');
        $show = $request->get('type');
        [$from, $to] = $this->getDates($request);
        $query = DB::table('payroll as p')
            ->where(function ($query) use ($filter) {
                $query = $query->orWhere('p.id', 'like', '%' . $filter . '%');
            })->whereDate("p.start", '>=', $from)->whereDate("p.end", '<=', $to);
        switch ($show) {
            case "D":
            {
                $query = $query->where('p.type', '=', "D");
                break;
            }
            case"N":
            {
                $query = $query->where('p.type', '=', "N");
                break;
            }
            default:
        }
        $payroll = $query->get();
        $payroll = $this->mapPayroll($payroll);
        return response()->json($payroll);
    }

    public function getOrder(Request $request)
    {
        $this->validate($request, [
            'filter' => 'nullable',
        ]);
        $filter = $request->get('filter');
        $payroll = DB::table('payroll as p')
            ->where(function ($query) use ($filter) {
                $query = $query->orWhere('p.id', 'like', '%' . $filter . '%');
            })->orderBy('p.start', 'DESC')->where('p.status', '=', "O")->take(10)->get();
        return response()->json($payroll);
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            Payroll::find($id)->update(['status' => 'C']);
            return response()->json([
                'status' => 1,
                'message' => 'Successfully updated payroll'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }
    }

    public function getPayrollsApi(Request $request)
    {
        $this->validate($request, [
            'from' => 'nullable',
            'to' => 'nullable'
        ]);
        [$from, $to] = $this->getDates($request);
        [$payroll, $from, $to, $net_pay, $ncdor, $total, $gross_pay, $desc, $bon] = $this->getPayrolls($from, $to);
        $totales = [
            'net_pay' => $net_pay,
            'ncdor' => $ncdor,
            'total' => $total,
            'gross_pay' => $gross_pay,
            'desc' => $desc,
            'bon' => $bon,
            'from' => $from,
            'to' => $to,
        ];
        return response()->json($totales);

    }

    public function getWorkerApi(Request $request)
    {

        [$from, $to] = $this->getDates($request);
        $this->validate($request, [
            'worker' => 'required',
            'last' => 'required',
            'payroll' => 'nullable',
            'from' => 'nullable',
            'to' => 'nullable'
        ]);
        $worker = $request->get('worker');
        $last = $request->get('last');
        $payroll = $request->get('payroll');
        $result = $this->getPayrollsWorker($from, $to, $worker, $payroll, $last);
        return response()->json($result);
    }

    public function getPayrolls($from, $to)
    {
        $payroll = DB::table('payroll as p')
            ->whereDate("p.start", '>=', $from)->whereDate("p.end", '<=', $to)
            ->get();
        $payroll = $this->mapPayroll($payroll);
        $desc = $payroll->sum('desc');
        $bon = $payroll->sum('bon');
        $net_pay = $payroll->sum('net_pay');
        $ncdor = $payroll->sum('ncdor');
        $total = $payroll->sum('total');
        $gross_pay = $payroll->sum('gross_pay');
        return ([$payroll, $from->addDay(), $to, $net_pay, $ncdor, $total, $gross_pay, $desc, $bon]);

    }

    private function mapPayroll($payroll)
    {
        $payroll->map(function ($item) {
            [$payroll,] = $this->calcPayroll($item);
            $item->desc = $payroll->desc;
            $item->bon = $payroll->bon;
            $item->net_pay = $payroll->net_pay;
            $item->ncdor = $payroll->ncdor;
            $item->total = $payroll->total;
            $item->gross_pay = $payroll->gross_pay;
        });
        return $payroll;
    }

    public function getPayrollsWorker($from, $to, $id, $payroll = null, $last = false)
    {
        $query = DB::table('report as r')->join('payroll as p', 'p.id', '=', 'r.id_payroll')
            ->select('p.*')
            ->where('r.id_worker', $id)
            ->groupBy('p.id');

        if ($payroll) {
            $payroll = $query->where('r.id_payroll', '=', $payroll)->get();
        } else {
            if ($last) {
                $payroll = $query->orderBy('p.start', 'DESC')->take(1)->get();
            } else {
                $payroll = $query->whereDate("p.start", '>=', $from)->whereDate("p.end", '<=', $to)->get();
            }
        }
        $worker = DB::table('worker as w')
            ->where('w.id', $id)
            ->select('w.name', 'w.last_name', 'w.id as id_worker')->first();
        $payroll->map(function ($item) use ($worker) {
            [$detail_bonus, $reports, $total_hours, $regular_hours, $extra_hours, $night_hours, $overtime_night_hours
                , $period_regular, $night, $overtime_regular, $overtime_night, $bon, $desc, $net_pay, $ncdor
                , $subtotal, $gross_pay,$rate,$rate_night] =
                $this->calcEmpleado($item->id, $item->type, $worker);
            $item->net_pay = $net_pay;
            $item->ncdor = $ncdor;
            $item->gross_pay = $gross_pay;
            $item->desc = $desc;
            $item->bon = $bon;
            $item->detail_bonus = $detail_bonus;
            $item->total_hours = $total_hours;
            $item->regular_hours = $regular_hours;
            $item->extra_hours = $extra_hours;
            $item->night_hours = $night_hours;
            $item->overtime_night_hours = $overtime_night_hours;
            $item->period_regular = $period_regular;
            $item->night = $night;
        });
        return [
            'net_pay' => $payroll->sum('net_pay'),
            'ncdor' => $payroll->sum('ncdor'),
            'total' => $payroll->sum('total'),
            'gross_pay' => $payroll->sum('gross_pay'),
            'desc' => $payroll->sum('desc'),
            'bon' => $payroll->sum('bon'),
            'total_hours'=>$payroll->sum('total_hours'),
            'regular_hours'=>$payroll->sum('regular_hours'),
            'extra_hours'=>$payroll->sum('extra_hours'),
            'night_hours'=>$payroll->sum('night_hours'),
            'overtime_night_hours'=>$payroll->sum('overtime_night_hours'),
            'period_regular'=>$payroll->sum('period_regular'),
            'night'=>$payroll->sum('night'),
            'from' => $from,
            'to' => $to,
            'worker' => $worker,
            'payroll' => $payroll
        ];
    }

    public function getDates($request)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $from = $from ? Carbon::createFromFormat('Y-m-d', $from) : Carbon::now()->startOfYear()->subDay();
        $to = $to ? Carbon::createFromFormat('Y-m-d', $to)->addDay() : Carbon::now()->addMonth()->startOfMonth();
        return [$from, $to];
    }

    public function showPayroll(Request $request)
    {
        try {
            $this->validate($request, [
                'payroll' => 'required',
            ]);
            $data = $request->all();
            $pay = Payroll::find($data['payroll']);
            [$payroll, $empleados] = $this->calcPayroll($pay);
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

    public function calcPayroll($payroll)
    {
        $empleados = DB::table('report')->where('id_payroll', '=', $payroll->id)
            ->select('worker.name', 'worker.last_name','worker.id as id_worker')
            ->join('worker', 'worker.id', '=', 'report.id_worker')->groupBy('id_worker')->get();
        $payroll->net_pay = 0;
        $payroll->ncdor = 0;
        $payroll->total = 0;
        $payroll->desc = 0;
        $payroll->bon = 0;
        $payroll->gross_pay = 0;

        $empleados = $this->mapEmpleado($empleados, $payroll);
        return [$payroll, $empleados];
    }

    private function mapEmpleado($empleados, $payroll)
    {
        $empleados->map(function ($item) use ($payroll) {
            [$detail_bonus, $reports, $total_hours, $regular_hours, $extra_hours, $night_hours, $overtime_night_hours, $period_regular, $night, $overtime_regular, $overtime_night, $bon, $desc, $net_pay, $ncdor, $subtotal, $gross_pay,$rate,$rate_night] = $this->calcEmpleado($payroll->id, $payroll->type, $item);
            $item->total_hours = $total_hours;
            $item->regular_hours = $regular_hours;
            $item->extra_hours = $extra_hours;
            $item->night_hours = $night_hours;
            $item->overtime_night_hours = $overtime_night_hours;
            $item->bonifications = $bon;
            $item->extra_deductions = $desc;
            $item->period_regular = $period_regular;
            $item->total_night = $night;
            $item->total_overtime_regular = $overtime_regular;
            $item->total_overtime_night = $overtime_night;
            $item->net_pay = $net_pay;
            $item->ncdor = $ncdor;
            $item->rate =$rate;
            $item->rate_night = $rate_night;
            $payroll->net_pay += $item->net_pay;
            $payroll->bon += $bon;
            $payroll->desc += $desc;
            $payroll->net_pay += $item->net_pay;
            $payroll->ncdor += $ncdor;
            $payroll->total += $subtotal;
            $payroll->gross_pay += $gross_pay;
            $item->subtotal = $subtotal;
            $item->gross_pay = $gross_pay;
            $item->detail_bonus = $detail_bonus;
            $item->reports = $reports;

        });

        return $empleados;
    }

    private function calcEmpleado($payroll, $type, $item)
    {

        $bonusPer = $this->getBonPermament($payroll);
        $reports = DB::table('report as r')->where('r.id_payroll', '=', $payroll)->where('r.id_worker', $item->id_worker)->select('r.*')->get();
        $regular_hours = $reports->sum('regular');
        $extra_hours = $reports->sum('extra');
        $night_hours = $reports->sum('night');
        $rate = $reports->first()->rate;
        $rate_night = $reports->first()->rate_night;

        if ($type == 'D') {
            $overtime_night = 0;
            $overtime_night_hours = 0;
            $night = (($rate * ($bonusPer->firstWhere('name', '=', 'Night hours')->amount / 100)) + $rate) * $night_hours;
        } else {
            $overtime_night_hours = $reports->sum('overtime_night');
            $overtime_night = (($rate_night * ($bonusPer->firstWhere('name', '=', 'Overtime night')->amount / 100)) + $rate_night) * $overtime_night_hours;
            $night = (($rate_night * ($bonusPer->firstWhere('name', '=', 'Night hours')->amount / 100)) + $rate_night) * $night_hours;
        }
        $total_hours = $regular_hours + $extra_hours + $night_hours + $overtime_night_hours;
        [$bon, $desc, $detail_bonus] = $this->getBon($payroll, $item->id_worker);
        $period_regular = $rate * $total_hours;
        $overtime_regular = (($rate * ($bonusPer->firstWhere('name', '=', 'Overtime Hours')->amount / 100)) + $rate) * $extra_hours;
        $net_pay = $period_regular + $night + $overtime_regular + $overtime_night;
        $subtotal = $net_pay + $bon;
        $ncdor = $net_pay * ($bonusPer->firstWhere('name', '=', 'NCDOR')->amount / 100);
        $gross_pay = $subtotal - $ncdor - $desc;
        return [$detail_bonus, $reports, $total_hours, $regular_hours, $extra_hours, $night_hours, $overtime_night_hours, $period_regular, $night, $overtime_regular, $overtime_night, $bon, $desc, $net_pay, $ncdor, $subtotal, $gross_pay,$rate,$rate_night];
    }

    private function getBon($payroll, $worker)
    {
        $bonus = DB::table('bonus_payroll')->join('detail_bonus', 'detail_bonus.id', '=', 'bonus_payroll.id_detail_bonus')
            ->join('bonus', 'bonus.id', '=', 'detail_bonus.id_bonus')
            ->where('bonus_payroll.id_payroll', '=', $payroll)
            ->where('bonus_payroll.id_worker', '=', $worker)
            ->select('detail_bonus.*', 'bonus.type', 'bonus.name')
            ->get();
        $bon = $bonus->where('type', '=', 'B')->sum('amount');
        $des = $bonus->where('type', '=', 'D')->sum('amount');
        return [$bon, $des, $bonus];

    }

    private function getBonPermament($payroll)
    {
        return DB::table('bonus_payroll')->join('detail_bonus', 'detail_bonus.id', '=', 'bonus_payroll.id_detail_bonus')
            ->join('bonus', 'bonus.id', '=', 'detail_bonus.id_bonus')
            ->where('bonus_payroll.id_payroll', '=', $payroll)
            ->where('permanent', '=', true)->where('active', '=', 1)
            ->select('detail_bonus.*', 'bonus.type', 'bonus.name')
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
            $data['start'] = Carbon::createFromFormat('Y-m-d', $data['start']);
            $data['end'] = Carbon::createFromFormat('Y-m-d', $data['end']);
            $data['users_id'] = $request->user()->id;
            $payroll = Payroll::create($data);
            $global = DB::table('bonus')->join('detail_bonus', 'detail_bonus.id_bonus', '=', 'bonus.id')->where('permanent', '=', true)
                ->where('active', '=', 1)->select('detail_bonus.id as id')->get();
            foreach ($global as $item) {
                DB::table('bonus_payroll')->insert(['id_detail_bonus' => $item->id, 'id_payroll' => $payroll->id, 'id_worker' => null]);

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
