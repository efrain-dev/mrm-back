<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = Worker::all();
        return response()->json($workers);
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
                'charge' => 'required',
                'email' => 'required|email',
                'address' => 'required',
                'contact' => 'required',
            ]);
            $data = $request->all();
            Worker::create($data);
            return response()->json([
                'status'=>1,
                'message' => 'Worker creado con exito'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'=>0,
                'message' => 'A ocurrido una excpecion'.$e->getMessage()
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
                'charge' => 'nullable',
                'email' => 'nullable|email',
                'address' => 'nullable',
                'contact' => 'nullable',
            ]);

            $data = $request->all();
            $worker->fill($data);
            $worker->save();
            return response()->json([
                'status'=>1,
                'message' => 'Trabajador actualizado con exito'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'=>0,
                'message' => 'A ocurrido una excpecion'
            ], 500);
        }

    }

    public function destroy($id)
    {
        try {
            $worker = Worker::find($id);
            $worker->delete();
            return response()->json([
                'status'=>1,
                'message' => 'Trabajador eliminado con exito'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'=>0,
                'message' => 'A ocurrido una excpecion al intentar eliminar'
            ], 500);
        }
    }
}
