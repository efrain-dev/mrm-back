
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
    <h4 class="titulo1 titulos" style="text-align: center"><strong>{{$data['payroll']->type=="D"?'Normal Payroll':'Night Payroll'}}</h4>
    <h4 class="titulo1 titulos"><strong>{{$data['payroll']->description}} </h4>
    <h4 class="titulo1 titulos"><strong style="margin-right: 550px">{{\Carbon\Carbon::parse($data['payroll']->start)->format('d/m/Y')}}-{{\Carbon\Carbon::parse($data['payroll']->end)->format('d/m/Y')}}</strong>  <strong>{{$data['payroll']->id}}</strong> </h4>
</header>
<main>

    <table class="table" style="margin-top: 10px">
        <thead >
        <tr class="header">
            <th style="min-width: 150px" >Employee name</th>
            <th >Rate</th>

            @if($data['payroll']->type=="N")
                <th >Night Rate</th>
            @endif
            <th >Regular hours</th>
            <th >Overtime hours</th>
            @if($data['payroll']->type=="N")
                <th >Night Overtime</th>
            @endif
            <th >Night hours</th>
            <th >Extra bonifications</th>
            <th >Net pay</th>
            <th >NCDOR</th>
            <th >Extra deductions</th>
            <th >Subtotal</th>
            <th >Gross  pay</th>

        </tr>
        </thead>
        <tbody >
        @php($total = 0)

        @foreach($data['empleados'] as $item)


            <tr >
                <td class="tr_item-none-start item">{{$item->name}} {{$item->last_name}}</td>
                <td class="item-border item">{{$item->rate}} </td>
                @if($data['payroll']->type=="N")
                    <td class="item-border item">{{$item->rate_night}} </td>
                @endif
                <td class="item-border item">{{$item->regular_hours}} </td>
                <td class="item-border item">{{$item->extra_hours}} </td>
                @if($data['payroll']->type=="N")
                    <td class="item-border item">{{$item->overtime_night_hours}} </td>
                @endif
                <td class="item-border item">{{$item->night_hours}} </td>
                <td class="item-border item">${{number_format($item->bonifications,2)}} </td>
                <td class="item-border item">${{number_format($item->net_pay,2)}} </td>
                <td class="item-border item">${{number_format($item->ncdor,2)}} </td>
                <td class="item-border item">${{number_format($item->extra_deductions,2)}} </td>
                <td class="item-border item">${{number_format($item->subtotal,2)}} </td>
                <td class="tr_item-none-end item">${{number_format($item->gross_pay)}}</td>
            </tr>

        @endforeach





        </tbody>
    </table>
    <div class="table-total">
        <table class="table" style="margin-top: 10px">
            <thead >
            <tr class="header">
                <th >Total pay</th>
                <th >Total NCDOR</th>
            </tr>
            </thead>
            <tbody >
            <tr >
                <td class="tr_item-none-start item" >{{$data['payroll']->gross_pay}}</td>
                <td class="tr_item-none-end item">{{$data['payroll']->ncdor}}</td>
            </tr>
            </tbody>
        </table>
    </div>


</main>

</body>

</html>
