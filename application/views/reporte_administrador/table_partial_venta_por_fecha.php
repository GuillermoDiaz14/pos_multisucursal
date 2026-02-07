<table class="table table-hover"  id="miTabla">
                    <tr>
                    <th>Nro venta</th>
                        <th>Cliente</th>
                        <th>Sub total</th>
                        <th>Impuesto</th>
                        <th>Descuento</th>
                        <th>Total</th>
                  
                       
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
        <tr>
        <td><?php echo $record->id_venta ?></td>
                        <td><?php echo $record->nombre_cliente ?></td>
                        <td><?php echo $record->base_imponible ?></td>
                        <td><?php echo $record->impuesto ?></td>
                        <td><?php echo $record->descuento ?></td>
                        <td><?php echo $record->total ?></td>
                    

        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
