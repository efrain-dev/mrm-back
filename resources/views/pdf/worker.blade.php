@php
ini_set('memory_limit', '2048M');
ini_set('max_execution_time', '120')
@endphp
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
        padding: 10px;

    }

    .header-pdf {
        font-size: 0.5rem;
        border: solid 1px black;
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

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }


    .header {
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
        border-bottom: black solid 1px;
        border-left: black solid 1px;
        border-right: white solid 1px;
    }

    .tr_item-none-end {
        border-top: black solid 1px;
        border-bottom: white solid 1px;
        border-left: white solid 1px;
        border-right: black solid 1px;
    }

    .item_titulo_detalle_total {
        display: inline-block;
        width: 100px;
    }
</style>
<body>

<header>

    <h4 style="text-align: center" class="titulos">Reporte de Inventario</h4>
    <h4 style="text-align: center" class="titulo1 titulos">Generado Por </h4>
    <h4 style="text-align: center" class="titulo1 titulos">Fecha </h4>
    <h4 class="titulo1 titulos">Costo Inventario:</h4>
    <h4 class="titulo1 titulos">Cantidad:</h4>
</header>
<main>
    <table class="table">
        <thead class='header'>
        <tr class="header">
            <th class="header-pdf " style="width: 70px">Codigo</th>
            <th class="header-pdf ">Nombre</th>
            <th class="header-pdf ">Marca</th>
            <th class="header-pdf " style="width: 40px">Talla</th>
            <th class="header-pdf " style="width: 70px">Precio Venta</th>
            <th class="header-pdf " style="width: 70px">Precio Costo</th>
            <th class="header-pdf " style="width: 70px">Existencia</th>
            <th class="header-pdf " style="width: 70px">Total</th>

        </tr>
        </thead>
        <tbody>
            <tr>

                <td class="tr_item-none item"></td>
                <td class="tr_item-none item"></td>
                <td class="tr_item-none item"></td>
                <td class="tr_item-none item"></td>
                <td class="tr_item-none item"></td>
                <td class="tr_item-none item"></td>
                <td class="tr_item-none item"></td>
                <td class="tr_item-none item"></td>
            </tr>

        <tr>

            <td class="tr_item-none item"></td>
            <td class="tr_item-none item"></td>
            <td class="tr_item-none item"></td>
            <td class="tr_item-none item"></td>
            <td class="tr_item-none item"></td>
            <td class="tr_item-none-end item">Totales</td>
            <td class="tr_item item"></td>
            <td class="tr_item item">  </td>
        </tr>


        </tbody>
    </table>

</main>

</body>

</html>
