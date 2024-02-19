<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $links = DB::table('link')->get();
        return response()->json($links);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
            ]);
            $data = $request->all();
            Link::create($data);
            return response()->json([
                'status' => 1,
                'message' => 'Link successfully created'
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
        $item = Link::find($id);
        return response()->json($item);
    }


    public function update(Request $request, $id)
    {
        try {
            $link = Link::find($id);
            $this->validate($request, [
                'name' => 'nullable',
            ]);
            $data = $request->all();
            $link->fill($data);
            $link->save();
            return response()->json([
                'status' => 1,
                'message' => 'Successfully updated Link'
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
            $link = Link::find($id);
            $link->delete();
            return response()->json([
                'status' => 1,
                'message' => 'Link successfully removed'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }
    }

}
