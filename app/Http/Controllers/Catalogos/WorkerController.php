<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Link;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class WorkerController extends Controller
{

    public function sendEmail(Request $request)
    {
        $this->validate($request, [
            'worker' => 'required',
            'header' => 'required',
            'body' => 'required',
        ]);
        try {

            $worker = Worker::find($request->get('worker'));
            $data['email'] = $worker->email;
            $data['title'] = $request->get('header');
            $data['body'] = $request->get('body');
            $data['name'] = $worker->name . ' ' . $worker->last_name;
            Mail::send('partial.link', $data, function ($message) use ($data) {
                $message->to($data["email"], $data["email"])
                    ->subject($data["title"]);
            });
            return response()->json([
                'status' => 1,
                'message' => 'Email successfully send'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        [$hire, $birthdate, $filter, $active] = $this->getDates($request);
        $query = DB::table('worker as w')
            ->where(function ($query) use ($filter) {
                $query = $query->orWhere('w.name', 'like', '%' . $filter . '%');
                $query = $query->orWhere('w.last_name', 'like', '%' . $filter . '%');
                $query = $query->orWhere('w.salary', 'like', '%' . $filter . '%');
                $query = $query->orWhere('w.social_number', 'like', '%' . $filter . '%');
                $query = $query->orWhere('w.email', 'like', '%' . $filter . '%');
                $query = $query->orWhere('w.address', 'like', '%' . $filter . '%');
                $query = $query->orWhere('w.contact', 'like', '%' . $filter . '%');
                $query = $query->orWhere('w.cel', 'like', '%' . $filter . '%');

            });

        if ($hire['active']) {

            $query = $query->whereBetween('w.date_in', [$hire['from'], $hire['to']]);
        }
        if ($birthdate['active']) {
            $query = $query->whereBetween('w.birthdate', [$birthdate['from'], $birthdate['to']]);
        }
        switch ($active) {
            case '1':
            {
                $query = $query->where('w.active', '=', 1);
                break;
            }
            case '0':
            {
                $query = $query->where('w.active', '=', 0);
                break;
            }
            default:
        }

        $workers = $query->get();
        return response()->json($workers);
    }

    public function getDates($request)
    {
        $hire = $request->get('hire');
        $birthdate = $request->get('birthdate');
        $filter = $request->get('filter') ?: '';
        $active = $request->get('active');
        if ($hire['active']) {
            $hire['from'] = $hire['from'] != '' ? Carbon::createFromFormat('Y-m-d', $hire['from']) : Carbon::now()->startOfYear()->startOfMonth();
            $hire['to'] = $hire['to'] != '' ? Carbon::createFromFormat('Y-m-d', $hire['to'])->addDay() : Carbon::now()->addMonth()->startOfMonth();
        }
        if ($birthdate['active']) {

            $birthdate['from'] = $birthdate['from'] != '' ? Carbon::createFromFormat('Y-m-d', $birthdate['from']) : Carbon::now()->startOfMonth();
            $birthdate['to'] = $birthdate['to'] != '' ? Carbon::createFromFormat('Y-m-d', $birthdate['to'])->addDay() : Carbon::now()->addMonth()->startOfMonth();

        }

        return [$hire, $birthdate, $filter, $active];
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'last_name' => 'required',
                'salary' => 'required',
                'date_in' => 'required',
                'date_out' => 'nullable',
                'birthdate' => 'required',
                'social_number' => 'required',
                'rate_night' => 'required',
                'email' => 'required|email',
                'address' => 'required',
                'contact' => 'required',
                'cel' => 'nullable',

            ]);
            $data = $request->all();
            $data['date_in'] = $data['date_in'] ? Carbon::createFromFormat('Y-m-d', $data['date_in']) : '';
            $data['date_out'] = $data['date_out'] ? Carbon::createFromFormat('Y-m-d', $data['date_out']) : '';
            $data['birthdate'] = $data['birthdate'] ? Carbon::createFromFormat('Y-m-d', $data['birthdate']) : '';
            Worker::create($data);
            return response()->json([
                'status' => 1,
                'message' => 'Worker successfully created'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }

    }


    public function show($id)
    {
        $item = Worker::find($id);
        return response()->json($item);
    }


    public function update(Request $request, $id)
    {
        try {
            $worker = Worker::find($id);

            $this->validate($request, [
                'name' => 'nullable',
                'last_name' => 'nullable',
                'salary' => 'nullable',
                'date_in' => 'nullable',
                'date_out' => 'nullable',
                'birthdate' => 'nullable',
                'social_number' => 'nullable',
                'rate_night' => 'required',
                'email' => 'nullable|email',
                'address' => 'nullable',
                'contact' => 'nullable',
                'cel' => 'nullable',

            ]);

            $data = $request->all();
            $worker->fill($data);
            $worker->save();
            return response()->json([
                'status' => 1,
                'message' => 'Successfully updated worker'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }

    }

    public function destroy($id)
    {
        try {
            $bonus = DB::table('bonus_payroll')->join('detail_bonus', 'detail_bonus.id', '=', 'bonus_payroll.id_detail_bonus')
                ->join('bonus', 'bonus.id', '=', 'detail_bonus.id_bonus')
                ->where('bonus_payroll.id_worker', '=', $id)
                ->get();
            $reports = DB::table('report as r')->where('r.id_worker', $id)->get();
            if (count($bonus)>0||count($reports)>0){
                Worker::find($id)->update(['active' => 0]);
            }else{
                Worker::find($id)->delete();
            }
            return response()->json([
                'status' => 1,
                'message' => 'Worker successfully removed'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }
    }

}
