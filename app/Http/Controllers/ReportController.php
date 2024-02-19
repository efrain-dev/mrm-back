<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Catalogos\PayrollController;

use App\Models\Payroll;
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
        $last = $request->get('last');

        $result =  $this->payrollController->getPayrollsWorker($from, $to, $worker,$payroll ,$last);

        dd($this->pdfWorker()->stream('pdf.pdf'));
        return response()->json($result);
    }

    public function pdfWorker(){
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $data = 0;
        $pdf->loadView('pdf.worker',compact('data'))->setPaper('a4', 'portrait');
        return $pdf;
    }
}
