<table class="table table-hover"  id="miTabla">
                    <tr>
                    <th>Nro Compra</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Nota</th>
                        <th>Total</th>
                  
                        <th class="text-center">Acciones</th>
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
        <tr>
        <td><?php echo $record->id_compra ?></td>
                        <td><?php echo $record->fecha_compra ?></td>
                        <td><?php echo $record->nombre_proveedor ?></td>
                        <td><?php echo $record->nota ?></td>
                        <td><?php echo $record->total ?></td>
                    
                        <td class="text-center">
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'entrada/compra_editar/'.$record->id_compra; ?>" title="Edit"><i class="fa fa-pencil"></i></a>

                            <a class="btn btn-sm btn-danger" href="<?php echo base_url('entrada/eliminar_compra/' . $record->id_compra); ?>"><i class="fa fa-trash"></i></a>
           <a class="btn btn-sm btn-info" href="<?php echo base_url().'entrada/exportToPDF/'.$record->id_compra; ?>" title="ticket" target="_blank"><i class="fa fa-file-text-o"></i></a>
                        </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
