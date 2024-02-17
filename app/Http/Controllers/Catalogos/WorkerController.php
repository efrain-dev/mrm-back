<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class WorkerController extends Controller
{
    public function index(Request $request)
    {
        [$hire, $birthdate, $filter, $active] = $this->getDates($request);
        $workers = DB::table('worker as w')
            ->where(function ($query) use ($filter) {
                $query = $query->orWhere('w.name', 'like', '%' . $filter . '%');
                $query = $query->orWhere('w.last_name', 'like', '%' . $filter . '%');
                $query = $query->orWhere('w.salary', 'like', '%' . $filter . '%');
                $query = $query->orWhere('w.social_number', 'like', '%' . $filter . '%');
                $query = $query->orWhere('w.email', 'like', '%' . $filter . '%');
                $query = $query->orWhere('w.address', 'like', '%' . $filter . '%');
                $query = $query->orWhere('w.contact', 'like', '%' . $filter . '%');
            });
        if ($hire['active']) {
            $workers = $workers->whereBetween('w.date_in', [$hire['from'], $hire['to']]);
        }
        if ($birthdate['active']) {
            $workers = $workers->whereBetween('w.birthdate', [$birthdate['from'], $birthdate['to']]);
        }
        switch ($active) {
            case 1:
            {
                $workers = $workers->where('w.active', '=', 1);
                break;
            }
            case 0:
            {
                $workers = $workers->where('w.active', '=', 0);
                break;
            }
        }
        $workers = $workers->get();
        return response()->json($workers);
    }

    public function getDates($request)
    {
        $hire = $request->get('hire');
        $birthdate = $request->get('birthdate');
        $filter = $request->get('filter') ?: '';
        $active = $request->get('active');
        if ($hire['active']){
            $hire['from'] = $hire['from'] ?   Carbon::createFromFormat('d/m/Y', $hire['from']) : Carbon::now()->startOfYear()->startOfMonth();
            $hire['to'] = $hire['to'] ?  Carbon::createFromFormat('d/m/Y', $hire['to']) ->addDay() : Carbon::now()->addMonth()->startOfMonth();
        }
        if ($birthdate['active']){
            $birthdate['from'] = $birthdate['from'] ? Carbon::createFromFormat('d/m/Y', $birthdate['from'])   : Carbon::now()->startOfMonth();
            $birthdate['to'] = $birthdate['to'] ? Carbon::createFromFormat('d/m/Y', $birthdate['to']) ->addDay() : Carbon::now()->addMonth()->startOfMonth();
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
            ]);
            $data = $request->all();
            $data['date_in'] = $data['date_in']? Carbon::createFromFormat('d/m/Y', $data['date_in']):'';
            $data['date_out'] = $data['date_out']? Carbon::createFromFormat('d/m/Y', $data['date_out']):'';
            $data['birthdate'] = $data['birthdate']? Carbon::createFromFormat('d/m/Y', $data['birthdate']):'';
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
            $worker = Worker::find($id);
            $worker->delete();
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
