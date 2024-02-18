<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Catalogos\PayrollController;

use Illuminate\Http\Request;


class ReportController extends Controller
{
    private $payrollController;

    public function __construct()
    {
        $this->payrollController = new PayrollController();
    }


    public function getPFDWorker(Request $request)
    {
        $this->validate($request, [
            'worker' => 'required',
            'last' => 'required',
            'payroll' => 'nullable',
            'from'=>'nullable',
            'to'=>'nullable'
        ]);
        [$from, $to]= $this->payrollController->getDates($request);
        $worker = $request->get('worker');
        $payroll = $request->get('payroll');

        $result =  $this->payrollController->getPayrollsWorker($from, $to, $worker,$payroll ,false);
        return response()->json($result);
    }


}
