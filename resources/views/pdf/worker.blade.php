
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
        font-size: 10px;
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

    <h4 class="titulo1 titulos"><strong style="margin-right: 500px">{{$data['worker']->name}} {{$data['worker']->last_name}}</strong>  <strong>Gross pay: ${{number_format($data['gross_pay'])}}</strong> </h4>
    <h4 class="titulo1 titulos">Extended: {{\Carbon\Carbon::now()->format('d/m/Y')}}</h4>


</header>
<main>
    <h4 class="titulo1 titulos" style="margin-bottom: 10px;margin-top: 10px;"><strong>Hours</strong> </h4>

    <table class="table">
        <thead >
        <tr class="header">
            <th style="width: 180px" >Date</th>
            <th >Regular hours</th>
            <th >Night hours</th>
            <th >Overtime night hours</th>
            <th >Night overtime </th>
            <th >Total</th>
        </tr>
        </thead>
        <tbody >
        @php($total = 0)
        @foreach($data['payroll'] as $item)
            <tr >
                <td class="tr_item-none-start item">{{\Carbon\Carbon::parse($item->start)->format('d/m/Y')}}-{{\Carbon\Carbon::parse($item->end)->format('d/m/Y')}}</td>
                <td class="item-border item">{{number_format($item->regular_hours)}}</td>
                <td class="item-border item">{{number_format($item->night_hours)}}</td>
                <td class="item-border item">{{number_format($item->extra_hours)}}</td>
                <td class="item-border item">{{number_format($item->overtime_night_hours)}}</td>
                <td class="tr_item-none-end item">{{number_format($item->total_hours)}}</td>
                @php($total+=$item->total_hours)
            </tr>

        @endforeach


        <tr>

            <td class="tr_item-none-start item"></td>
            <td class="item-border item"></td>
            <td class="item-border item"></td>
            <td class="item-border item"></td>
            <td class="item-border item">Total</td>
            <td class="tr_item-none-end item">{{$total}}</td>
        </tr>


        </tbody>
    </table>
    <h4 class="titulo1 titulos" style="margin-bottom: 10px;margin-top: 10px;"><strong>Bonifications and deductions</strong> </h4>

    <table class="table">
        <thead >
        <tr class="header">
            <th style="width: 180px" >Name</th>
            <th >Type</th>
            <th >Date</th>
            <th >Mount</th>
        </tr>
        </thead>
        <tbody >
        @php($bon = 0)

        @foreach($data['payroll'] as $item)
            <tr >
                <td class="tr_item-none-start item">NCDOR</td>
                <td class="item-border item">Deduction</td>
                <td class="item-border item">{{\Carbon\Carbon::parse($item->start)->format('d/m/Y')}}</td>
                <td class="tr_item-none-end item">${{number_format($item->ncdor,2)}}</td>
            </tr>
            @php($bon-=$item->ncdor)

        @endforeach
        @foreach($data['payroll'] as $item)
            @foreach($item->detail_bonus as $res)

                <tr >
                    <td class="tr_item-none-start item">{{$res->name}}</td>
                    <td class="item-border item">{{$res->type=='D'?'Deduction':'Bonification'}}</td>
                    <td class="item-border item">{{\Carbon\Carbon::parse($res->date)->format('d/m/Y')}}</td>
                    <td class="tr_item-none-end item">${{number_format($res->amount,2)}}</td>

                    @if($res->type=='D')
                        @php($bon-=$res->amount)
                    @else
                        @php($bon+=$res->amount)
                    @endif
                </tr>
            @endforeach
        @endforeach
        <tr>

            <td class="tr_item-none-start item"></td>
            <td class="item-border item"></td>
            <td class="item-border item">Total</td>
            <td class="tr_item-none-end item">${{number_format($bon,2)}}</td>
        </tr>
        </tbody>
    </table>


    <h4 class="titulo1 titulos" style="margin-bottom: 10px;margin-top: 10px;"><strong>Year to date</strong> </h4>

    <table class="table">
        <thead >
        <tr class="header">
            <th style="width: 180px" >Name</th>
            <th >Mount</th>
        </tr>
        </thead>
        <tbody >
        @php($total = 0)
        <tr>
            <td class="tr_item-none-start item">Gross pay</td>
            <td class="tr_item-none-end item">{{$data['gross_pay']}}</td>
        </tr>
        <tr>
            <td class="tr_item-none-start item">Extra bonification</td>
            <td class="tr_item-none-end item">{{$data['bon']}}</td>
        </tr>
        <tr>
            <td class="tr_item-none-start item">NCDOR</td>
            <td class="tr_item-none-end item">{{$data['ncdor']}}</td>
        </tr>
        <tr>
            <td class="tr_item-none-start item">Extra deductions</td>
            <td class="tr_item-none-end item">{{$data['desc']}}</td>
        </tr>


        </tbody>
    </table>
</main>

</body>

</html>
