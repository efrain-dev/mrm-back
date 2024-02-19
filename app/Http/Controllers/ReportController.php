<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Catalogos\PayrollController;

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

    public function pdfWorker($data)
    {
        return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.worker', compact('data'));
    }

    public function sendMail($pdf, $name, $mail)
    {
        $data['email'] = $mail;
        $data['title'] = 'Details of Your Pay Statement';
        $data['body'] = 'Report ' . ' ' . $name;
        Mail::send('partial.mail', $data, function ($message) use ($data, $pdf, $name) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"])
                ->attachData($pdf->output(), $name.'.pdf');
        });

    }
}
