<style>
body {
    font-family: Arial, sans-serif;
    font-size: 10px;
    margin: 0;
    padding: 0;
}

.ticket {
    width: 80mm;
    margin: auto;
}

.center {
    text-align: center;
}

.row-between {
    display: flex;
    justify-content: space-between;
}

hr {
    border: none;
    border-top: 1px dashed #000;
    margin: 4px 0;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    font-size: 9px;
    padding: 2px 0;
}

th {
    border-bottom: 1px solid #000;
}

.total {
    font-size: 12px;
    font-weight: bold;
    text-align: right;
}

@media print {
    #imprimirTicket {
        display: none;
    }
}
</style>

<div class="ticket">

<?php foreach ($ventas as $venta): 
    $id_venta = $venta->id_venta; 
    $fecha_venta = $venta->fecha_venta; 
    $nombre_cliente = $venta->nombre_cliente;
    $total = $venta->total; 
    $descuento = $venta->descuento ?? 0;
endforeach; ?>

<div class="center">
    <strong>MI MARCA</strong><br>
    Tel: 722-000-0000
</div>

<hr>

<div class="row-between">
    <div>Venta: <?php echo $id_venta; ?></div>
    <div><?php echo $fecha_venta; ?></div>
</div>

Cliente: <?php echo $nombre_cliente; ?>

<hr>

<table>
    <tr>
        <th>Producto</th>
        <th>P</th>
        <th>C</th>
        <th>Sub</th>
    </tr>
    <?php foreach ($detalles as $detalle): ?>
    <tr>
        <td><?php echo $detalle->nombre_producto; ?></td>
        <td>$<?php echo number_format($detalle->precio_individual,2); ?></td>
        <td><?php echo $detalle->cantidad; ?></td>
        <td>$<?php echo number_format($detalle->sub_total,2); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<hr>

<div class="row-between">
    <div>Descuento:</div>
    <div>$<?php echo number_format($descuento,2); ?></div>
</div>

<div class="total">
TOTAL: $<?php echo number_format($total,2); ?>
</div>

<hr>

<div class="center">
    ¡Gracias por su compra!
</div>

<button id="imprimirTicket">Imprimir</button>

</div>

<script>
document.getElementById('imprimirTicket').addEventListener('click', function () {
    window.print();
});
</script>
