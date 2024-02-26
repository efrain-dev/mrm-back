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
            width: 600px;
            margin: 0 auto;
        }

        .header {
            display: block;
            background-color: #0073b7;
            color: #ffffff;
            padding: 20px 0;
        }

        .logo {
            display: inline-block;
            width: 300px;
        }

        .company-info {
            display: inline-block;
            text-align: right;
        }

        .company-info h1 {
            margin-top: 0;
        }

        .table-container {
            margin-top: 40px;
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
        <img  class="logo"  src=https://i.imgur.com/TU11X0P.png" alt="Logo">
        <div class="company-info">
            <h1>MRM Power Force</h1>
            <p>mrmelectriclic@gmail.com</p>
            <p>+1 (919) 579-6722</p>
        </div>
    </div>

    <div class="table-container">
        <h2>Invoice</h2>
        <table>
            <thead>
            <tr>
                <th>Date</th>
                <th>Invoice Number</th>
                <th>Customer</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>08/28/2023</td>
                <td>1160</td>
                <td>Johnson Modern Electric</td>
            </tr>
            </tbody>
        </table>

        <table>
            <thead>
            <tr>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Manpower</td>
                <td>40</td>
                <td>$10</td>
                <td>$400</td>
            </tr>
            <tr>
                <td>Manpower</td>
                <td>40</td>
                <td>$10</td>
                <td>$400</td>
            </tr>
            <tr>
                <td>Manpower</td>
                <td>40</td>
                <td>$10</td>
                <td>$400</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" class="total">Total</td>
                <td class="total">$1200</td>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="notes">
        <h3>Note to customer</h3>
        <p>Week 8/21/23-8/25/23</p>
        <p>Durham Erwin Terrace</p>
        <p>Job 22-38</p>
    </div>

    <div class="footer">
        <p>Payment: 0</p>
        <p>Balance due: $1200</p>
    </div>
</div>
</body>
</html>
