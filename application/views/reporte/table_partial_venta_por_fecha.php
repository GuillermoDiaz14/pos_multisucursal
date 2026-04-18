<table class="table table-hover"  id="miTabla">
                    <tr>
                    <th>Nro venta</th>
                        <th>Cliente</th>
                        <th>Subtotal neto</th>
                        <th>Impuesto</th>
                        <th>Descuento</th>
                        <th>Total</th>
                  
                       
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
        <tr>
        <td><?php echo $record->id_venta ?></td>
                        <td><?php echo $record->nombre_cliente ?></td>
                        <td><?php echo '$'.number_format((float)$record->base_imponible,2); ?></td>
                        <td><?php echo '$'.number_format((float)$record->impuesto,2); ?></td>
                        <td><?php echo '$'.number_format((float)$record->descuento,2); ?></td>
                        <td><?php echo '$'.number_format((float)$record->total,2); ?></td>
                    

        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
