<?php

namespace App\Http\Controllers\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ConfigController extends Controller
{
    public function index(Request $request)
    {
        $config = DB::table('config')->select('pay','title_pay' ,'title_videos','videos')->first();
        return response()->json($config);
    }


    public function update(Request $request)
    {
        try {

            $config = Config::find(1);
            $this->validate($request, [
                'pay' => 'nullable',
                'title_pay' => 'nullable',
                'title_videos' => 'nullable',
                'videos' => 'nullable',
            ]);
            $data = $request->all();
            $config->fill($data);
            $config->save();
            return response()->json([
                'status' => 1,
                'message' => 'Successfully updated Config'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }

    }

}
