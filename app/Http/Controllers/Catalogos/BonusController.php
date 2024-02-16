<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\BonusDetail;
use App\Models\Worker;
use App\Models\WorkerBonus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BonusController extends Controller
{
    public function index(Request $request,$type)
    {     $query = DB::table('bonus')->join('detail_bonus','detail_bonus.id_bonus','=','bonus.id');
        if ($type){
            $query = $query->where('permanent','=',true);
        }else{
            $query = $query->where('permanent','=',false);
        }
        $bonus = $query->get();
        return response()->json($bonus);
    }
    public function getType(Request $request)
    {
        $bonus = DB::table('bonus')->where('permanent','=',false)->get();
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
                'calc' => 'required',
                'general' => 'required',
                'active' => 'required',
                'id_bonus' => 'required',
            ]);
            $data = $request->all();
            BonusDetail::create($data);
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
            'calc' => 'required',
            'general' => 'required',
            'active' => 'required',
            'id_bonus' => 'required',
            'id_detail' => 'required',
        ]);
        $data = $request->all();
        try {
            $message = DB::transaction(function () use ($data) {
                BonusDetail::find($data['id_detail'])->update(['active' => false]);
                unset($data['id_detail']);
                BonusDetail::create($data);
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

    public function deactivateBonus(Request $request)
    {
        try {
            $this->validate($request, [
                'id_detail' => 'required',
            ]);
            $data = $request->all();
            BonusDetail::find($data['id_detail'])->update(['active' => false]);
            return response()->json([
                'status' => 1,
                'message' => 'Successfully updated bonus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }

    }

    public function addWorkers(Request $request)
    {
        $this->validate($request, [
            'id_bonus' => 'required',
            'workers' => 'required'
        ]);
        $data = $request->all();
        try {
            $message = DB::transaction(function () use ($data) {
                foreach ($data['workers'] as $item){
                    WorkerBonus::create([
                        'id_bonus'=>$data['id_bonus'],
                        'id_worker'=>$item,
                    ]);
                }
                return [
                    'status' => 1,
                    'message' => 'Employees successfully added'
                ];
            });
        } catch (Exception $e) {
            $message = ['status' => 'error', 'message' => $e->getMessage(), 500];
        }
        return response()->json($message);

    }
    public function deleteWorker(Request $request)
    {
        $this->validate($request, [
            'bonus' => 'required'
        ]);
        $data = $request->get('bonus');
        try {
            $message = DB::transaction(function () use ($data) {
                foreach ($data as $item){
                    WorkerBonus::find($item)->delete();
                }
                return [
                    'status' => 1,
                    'message' => 'Employees deleted'
                ];
            });
        } catch (Exception $e) {
            $message = ['status' => 'error', 'message' => $e->getMessage(), 500];
        }
        return response()->json($message);

    }

}
