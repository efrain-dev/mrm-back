
<!DOCTYPE html>
<html lang="es">
<style>
    html {
        margin-top: 20px;
        margin-bottom: 30px;
        padding: 0;
    }

    header {
        margin: 0;
        padding: 0;
        font-size: 0.7rem;

    }

    main {
        margin: 0;

    }

    .header-pdf {
        font-size: 0.7rem;
        text-align: center;



    }

    .titulo1 {
        font-weight: 0;
    }

    .firma {
        font-weight: 0;
        font-size: 0.7rem;

    }

    .titulos {
        margin: 0;
        padding: 0;

    }

    .table {
        width: 100%;
        border-collapse: collapse;
        border: none;
    }
    .table-total {
        width: 30%;
    }

    .tr_item {
        border: solid 1px black;
    }

    td {
        font-size: 75%;
        border: none;

    }

    th {
        color: black;
    }




    .header {
        font-size:  0.6rem;
        border: solid 1px black;
        background-color: #D9D9D9;
    }
    .tbody {
        border: solid 1px black;

    }
    .tr-final {
        border: solid 1px black;

    }

    .name-item {
        width: 180px;
    }

    .item {
        font-size: 0.6rem;
        text-align: center;


    }
    .item-border {

        border-top: black solid 1px;
        border-bottom: black solid 1px;

    }
    .item-center {
        font-size: 0.6rem;
        text-align: center;
    }

    .tr_item-none {
        border-top: white solid 1px;
        border-bottom: white solid 1px;
        border-left: white solid 1px;
        border-right: white solid 1px;

    }

    .tr_item-none-start {
        border-top: black solid 1px;
        border-left: black solid 1px;
        border-bottom: black solid 1px;

    }

    .tr_item-none-end {
        border-top: black solid 1px;
        border-right: black solid 1px;
        border-bottom: black solid 1px;

    }

    .item_titulo_detalle_total {
        display: inline-block;
        width: 100px;
    }
</style>
<body>

<header>
    <h4 class="titulo1 titulos" style="text-align: center"><strong>Payrolls</strong></h4>
    <h4 class="titulo1 titulos"><strong >{{\Carbon\Carbon::parse($data['from'])->format('d/m/Y')}}-{{\Carbon\Carbon::parse($data['to'])->format('d/m/Y')}}</strong>  </h4>
</header>

<main>
    <table class="table" style="margin-top: 10px">
        <thead >
        <tr class="header">
            <th style="min-width: 30px" >Payroll</th>
            <th >Description</th>
            <th >Type</th>
            <th >Start</th>
            <th >End</th>
            <th >Gross Pay</th>

        </tr>
        </thead>
        <tbody >

        @foreach($data['payroll'] as $item)


            <tr >
                <td class="tr_item-none-start item">{{$item->id}} </td>
                <td class="item-border item">{{$item->description}} </td>
                <td class="item-border item">{{$item->type=="D"?'Normal':'Night'}}</td>
                <td class="item-border item">{{\Carbon\Carbon::parse($item->start)->format('d/m/Y')}}</td>
                <td class="item-border item">{{\Carbon\Carbon::parse($item->end)->format('d/m/Y')}}</td>
                <td class="tr_item-none-end item">${{number_format($item->gross_pay,2)}} </td>
            </tr>

        @endforeach





        </tbody>
    </table>
    <div class="table-total">
        <table class="table" style="margin-top: 10px">
            <thead >
            <tr class="header">
                <th >Year to date</th>
                <th >Year to date  NCDOR</th>
            </tr>
            </thead>
            <tbody >
            <tr >
                <td class="tr_item-none-start item" >{{$data['gross_pay']}}</td>
                <td class="tr_item-none-end item">{{$data['ncdor']}}</td>
            </tr>
            </tbody>
        </table>
    </div>


</main>

</body>

</html>
