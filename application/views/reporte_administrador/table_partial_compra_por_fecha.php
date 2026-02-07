<table class="table table-hover"  id="miTabla">
                    <tr>
                  <th>Nro Compra</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Nota</th>
                        <th>Total</th>
                  
                       
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
        <tr>
           <td><?php echo $record->id_compra ?></td>
                        <td><?php echo $record->fecha_compra ?></td>
                        <td><?php echo $record->nombre_proveedor ?></td>
                        <td><?php echo $record->nota ?></td>
                        <td><?php echo $record->total ?></td>
                    

        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
