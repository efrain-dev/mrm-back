<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\BonusDetail;
use App\Models\PayrollBonus;
use App\Models\Worker;
use App\Models\WorkerBonus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BonusController extends Controller
{
    public function index(Request $request, $type = 0)
    {
        $query = DB::table('bonus')->join('detail_bonus', 'detail_bonus.id_bonus', '=', 'bonus.id')->select('detail_bonus.*', 'bonus.name');
        if ($type) {
            $query = $query->where('permanent', '=', true)->where('active', '=', 1);
        } else {
            $query = $query->where('permanent', '=', false);
        }

        $bonus = $query->get();

        return response()->json($bonus);
    }

    public function getBonus(Request $request)
    {
        $this->validate($request, [
            'payroll' => 'required',
            'worker' => 'required',
        ]);
        $query = DB::table('bonus_payroll')
            ->join('detail_bonus', 'detail_bonus.id', '=', 'bonus_payroll.id_detail_bonus')
            ->join('bonus', 'bonus.id', '=', 'detail_bonus.id_bonus')
            ->where('bonus_payroll.id_payroll', $request->get('payroll'))
            ->where('bonus_payroll.id_worker', $request->get('worker'))
            ->select('detail_bonus.amount', 'detail_bonus.date', 'bonus_payroll.id', 'bonus.name as bonus', 'bonus.type')->get();
        return response()->json($query);
    }

    public function getType(Request $request)
    {
        $bonus = DB::table('bonus')->where('permanent', '=', false)->get();
        return response()->json($bonus);
    }

    public function newBonus(Request $request)
    {

        try {
            $this->validate($request, [
                'name' => 'required',
                'type' => 'required',
            ]);
            $data = $request->all();
            Bonus::create($data);
            return response()->json([
                'status' => 1,
                'message' => 'Successfully created bonus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }

    }

    public function newDetailBonus(Request $request)
    {
        try {
            $this->validate($request, [
                'amount' => 'required',
                'bonus' => 'required',
                'payroll' => 'required',
                'date' => 'required',
                'worker' => 'required',
            ]);
            $data = $request->all();
            $bonus = BonusDetail::create(
                [
                    'amount' => $data['amount'],
                    'id_bonus' => $data['bonus'],
                    'calc' => 1,
                    'date' => Carbon::createFromFormat('Y-m-d', $data['date']),
                ]);
            PayrollBonus::create(['id_detail_bonus' => $bonus->id, 'id_payroll' => $data['payroll'], 'id_worker' => $data['worker']]);

            return response()->json([
                'status' => 1,
                'message' => 'Successfully created bonus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }

    }

    public function editDetailBonus(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required',
            'id' => 'required',
        ]);
        $data = $request->all();

        try {
            $message = DB::transaction(function () use ($data) {
                $bonus = BonusDetail::find($data['id']);
                $bonus->update(['active' => false]);
                BonusDetail::create([
                    'amount' => $data['amount'],
                    'id_bonus' => $bonus->id_bonus,
                    'calc' => 2,
                    'date' => Carbon::now(),
                    'permanent' => true
                ]);
                return [
                    'status' => 1,
                    'message' => 'Successfully updated bonus'
                ];
            });
        } catch (Exception $e) {
            $message = ['status' => 'error', 'message' => $e->getMessage(), 500];
        }
        return response()->json($message);
    }

    public function deleteBonus(Request $request)
    {
        $this->validate($request, [
            'payroll' => 'required',
            'worker' => 'required',
            'bonus' => 'required',
        ]);
        try {
            $message = DB::transaction(function () use ($request) {
                $detail = DB::table('bonus_payroll')
                    ->where('id_detail_bonus', $request->get('bonus'))
                    ->where('id_payroll', $request->get('payroll'))
                    ->where('id_worker', $request->get('worker'))->get();
                foreach ($detail as $item) {
                    PayrollBonus::find($item->id)->delete();
                }
                return [
                    'status' => 1,
                    'message' => 'Successfully deleted bonus'
                ];
            });
        } catch (Exception $e) {
            $message = ['status' => 'error', 'message' => $e->getMessage(), 500];
        }
        return response()->json($message);
    }
}
