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
        $config = DB::table('config')->select('pay', 'title_pay', 'title_videos', 'videos')->first();
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
    public function createCarpetas()
    {
        try {
            $carpeta = getenv('USERPROFILE') . '\Downloads\BACKUPMRM';
            $carpeta1 = getenv('USERPROFILE') . '\Downloads\BACKUPMRM\DOWN';
            $carpeta2 = getenv('USERPROFILE') . '\Downloads\BACKUPMRM\UP';
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            if (!file_exists($carpeta1)) {
                mkdir($carpeta1, 0777, true);
            }
            if (!file_exists($carpeta2)) {
                mkdir($carpeta2, 0777, true);
            }
            return response()->json([
                'status' => 1,
                'message' => 'Successfully '
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }
    }
    public function copyBD()
    {
        $archivoOriginal = base_path('database/database.sqlite');
        $carpeta = getenv('USERPROFILE') . '\Downloads\BACKUPMRM\DOWN';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $destino = getenv('USERPROFILE') . '\Downloads\BACKUPMRM\DOWN\database.sqlite';
        try {
            copy($archivoOriginal, $destino);
            return response()->json([
                'status' => 1,
                'message' => 'Successfully copy backup in Download'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }
    }
    public function upBD()
    {
        $archivoOriginal = getenv('USERPROFILE') . '\Downloads\BACKUPMRM\UP\database.sqlite';
        $carpeta = getenv('USERPROFILE') . '\Downloads\BACKUPMRM\UP';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $destino = base_path('database/database.sqlite');
        try {
            copy($archivoOriginal, $destino);
            return response()->json([
                'status' => 1,
                'message' => 'Successfully copy backup in Download'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }
    }
}
