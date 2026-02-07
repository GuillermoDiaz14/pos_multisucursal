<table class="table table-hover" id="miTabla">
    <tr>
        <th>Codigo</th>
        <th>Nombre producto</th>
        <th>Cantidad</th>
        <th>Precio compra</th>
        <th>Precio venta</th>
        <th>Ganancia</th>

    </tr>
    <?php
    if (!empty($ventas['result'])) {
        foreach ($ventas['result'] as $venta) {
            ?>
            <tr>
                <td><?php echo $venta->codigo ?></td>
                <td><?php echo $venta->nombre_producto ?></td>
                <td><?php echo $venta->total_cantidad ?></td>
                <td><?php echo number_format($venta->precio_compra_total, 2) ?></td>
                <td><?php echo number_format($venta->precio_venta_total, 2) ?></td>
                <td><?php echo number_format($venta->ganancias_total, 2) ?></td>
        
            </tr>
            <?php
        }
    }
    ?>
</table>

<!-- Mostrar las sumatorias totales -->
<div>

    <strong>Total Precio Compra Total:<br> <?php echo number_format($ventas['precio_compra_total'], 2); ?></strong><br>
    <strong>Total Precio Venta Total:<br> <?php echo number_format($ventas['precio_venta_total'], 2); ?></strong><br>
    <strong>Total Ganancias Total:<br> <?php echo number_format($ventas['ganancias_total'], 2); ?></strong><br>
</div>
