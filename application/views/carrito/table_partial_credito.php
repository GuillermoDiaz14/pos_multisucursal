<table class="table table-hover"  id="miTabla">
                    <tr>
                    <th>Nro venta</th>
                        <th>Cliente</th>
                        <th>Sub total</th>
                        <th>Impuesto</th>
                        <th>Descuento</th>
                        <th>Total</th>
                  
                        <th class="text-center">Acciones</th>
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): 

   $tipo_pago=$record->tipo_pago;
        ?>
        <tr>
        <td><?php echo $record->id_venta ?></td>
                        <td><?php echo $record->nombre_cliente ?></td>
                        <td><?php echo $record->base_imponible ?></td>
                        <td><?php echo $record->impuesto ?></td>
                        <td><?php echo $record->descuento ?></td>
                        <td><?php echo $record->total ?></td>

                             <td class="text-center">
 <?php
       if ($tipo_pago=="contado") {
                            // code...
                                         
      ?> 
                            <a class="btn btn-sm btn-warning" href="<?php echo base_url().'carrito/carrito_editar/'.$record->id_venta; ?>" title="Edit"><i class="fa fa-pencil"></i></a>
                                             <a class="btn btn-sm btn-danger" href="<?php echo base_url('carrito/eliminar_venta/' . $record->id_venta); ?>"><i class="fa fa-trash"></i></a>
                            <?php
                        }
      ?> 
      <?php
       if ($tipo_pago=="credito") {
                            // code...
                                         
      ?> 
                            <a class="btn btn-sm btn-primary" href="<?php echo base_url().'carrito/credito/'.$record->id_venta; ?>" title="Edit">Credito<i class="fa fa-pencil"></i></a>
                            <?php
                        }
      ?> 
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'carrito/exportToPDF/'.$record->id_venta; ?>" title="ticket" target="_blank"><i class="fa fa-file-text-o"></i></a>                   </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
