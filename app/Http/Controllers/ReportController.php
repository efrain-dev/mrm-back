<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Catalogos\PayrollController;

use App\Models\Config;
use App\Models\Payroll;
use App\Models\Worker;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


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
            'from' => 'nullable',
            'to' => 'nullable',
            'send' => 'required',
            'download' => 'nullable'
        ]);
        [$from, $to] = $this->payrollController->getDates($request);
        $worker = $request->get('worker');
        $payroll = $request->get('payroll');
        $last = $request->get('last');
        $send = $request->get('send');
        $down = $request->get('download') ?: 1;
        try {
            $result = $this->payrollController->getPayrollsWorker($from, $to, $worker, $payroll, $last);
            $pdf = $this->pdfWorker($result);
            if ($send == 1) {
                $name = $result['worker']->name . " " . $result['worker']->last_name;
                $worker = Worker::find($worker);
                $this->sendMail($pdf, $name, $worker->email);
            }
            if ($down == 1) {
                return $pdf->download();
            }else{
                return response()->json([
                    'status' => 1,
                    'message' => 'Successfully'
                ]);
            }

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }


    }
    public function getPDFPayroll(Request $request){
        $this->validate($request, [
            'payroll' => 'nullable',
            'from' => 'nullable',
            'to' => 'nullable',
        ]);
        $payroll = $request->get('payroll');
        try {
            if ($payroll){
                $pay = Payroll::find($payroll);
                [$payroll, $empleados] = $this->payrollController->calcPayroll($pay);
                $result = ['payroll'=>$payroll,'empleados'=>$empleados];
                $pdf = $this->pdfPayroll($result);
            }else{

                [$from, $to] = $this->payrollController->getDates($request);
                [$payroll, $from, $to, $net_pay, $ncdor, $total, $gross_pay, $desc, $bon] = $this->payrollController->getPayrolls($from, $to);
                $result = compact('payroll','from','to','net_pay','ncdor','total','gross_pay','desc','bon' );
                $pdf = $this->pdfPayrolls($result);

            }
            return $pdf->stream();

        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An exception has occurred' . $e->getMessage()
            ], 500);
        }
    }
    public function pdfWorker($data)
    {
        return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.worker', compact('data'));
    }
    public function pdfPayroll($data)
    {
        return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.payroll', compact('data'));
    }
    public function pdfPayrolls($data)
    {
        return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.payrolls', compact('data'));
    }
    public function sendMail($pdf, $name, $mail)
    {
        $config = Config::find(1);
        $data['email'] =$mail;
        $data['title'] = $config->title_pay;
        $data['body'] =  $config->pay;
        Mail::send('partial.mail', $data, function ($message) use ($data, $pdf, $name) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"])
                ->attachData($pdf->output(), $name.'.pdf');
        });

    }
}
