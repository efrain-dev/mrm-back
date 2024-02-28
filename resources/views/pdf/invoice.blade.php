<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 700px;
            margin: 0 auto;
        }

        .header {

        }

        .logo {
            margin-right: 10px;
            margin-bottom: 50px;
            width: 250px;
            display: inline-block;
        }

        .company-info {
            margin-left: 80px;

            display: inline-block;
            text-align: right;
        }

        .company-info h1 {
            margin-top: 0;
        }

        .table-container {
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            text-align: left;
        }

        .total {
            font-weight: bold;
        }

        .notes {
            margin-top: 40px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">

        <div class="company-info">
            <h1 style="margin: 0">Invoice</h1>
            <p style="margin: 0">MRM Power Force</p>
            <p style="margin: 0">mrmelectriclic@gmail.com</p>
            <p style="margin: 0">+1 (919) 579-6722</p>
            <p style="margin: 0">5136 Kenwood Rd</p>
            <p style="margin: 0">Durham, NC 27712</p>
        </div>
        <div class="company-info">
            <img  class="logo"  src=https://i.imgur.com/TU11X0P.png" alt="Logo">
        </div>
    </div>
    <div class="table-container">
        <hr>
        <h4 style="margin: 0; margin-bottom: 5px">Bill to</h4>
        <table>
            <thead>
            <tr>
                <th style="width: 250px">Customer</th>
                <th>Address</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td >{!!$data['header']['clientName']?:''!!}</td>
                <td>{!!$data['header']['clientAddress']?:''!!}</td>
            </tr>
            </tbody>
        </table>
        <hr>
        <h4 style="margin: 0; margin-bottom: 5px">Invoice details</h4>
        <table>
            <thead>
            <tr>
                <th style="width: 85px">Invoice no</th>
                <th>Terms</th>
                <th>Invoice date</th>
                <th>Due date</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{!!$data['header']['invoiceNo']?:''!!}</td>
                <td>{!!$data['header']['terms']?:''!!}</td>
                <td>{!!$data['header']['invoiceDate']?:''!!}</td>
                <td>{!!$data['header']['dueDate']?:''!!}</td>
            </tr>
            </tbody>
        </table>

        <hr>
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Product or service</th>
                <th>SKU</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @php($num = 1)
            @php($total = 0)
            @php($subtotal = 0)

            @foreach( $data['details'] as $item)
                <tr>
                    <td>{{$num}}</td>
                    <td>{!!$item['date']?:''!!}</td>
                    <td>{!!$item['productOrService']?:''!!}</td>
                    <td>{!!$item['SKU']?:''!!}</td>
                    <td>{!!$item['QTY']?:''!!}</td>
                    <td>{!!$item['rate']?:''!!}</td>
                    @php($subtotal=   $item['QTY']  *$item['rate'])
                    <td>{{$subtotal}}</td>
                    @php($total += $subtotal)
                </tr>
                @php($num++)
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="6" class="total">Total</td>
                <td class="total">${{$total}}</td>
            </tr>
            <tr>
                <td colspan="6" >Payment</td>
                <td >${{$data['header']['payment']}}</td>
            </tr>
            <tr>
                <td colspan="6" class="total">Balance due </td>
                @php($balance = $total -(is_numeric($data['header']['payment'])?$data['header']['payment']:0))
                <td class="total">${{$balance}}</td>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="notes">
        <h3>Note to customer</h3>
        <p style="width: 200px">{{$data['header']['noteToCustomer']}}</p>

    </div>

    <div class="footer">
        <p>Payment: ${{$data['header']['payment']}}</p>
        <p>Balance due: ${{$balance}}</p>
    </div>
</div>
</body>
</html>
