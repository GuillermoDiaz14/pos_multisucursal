<table class="table table-hover"  id="miTabla">
                    <tr>
                    <th>Id</th>
                        <th>Descripcion</th>
                        <th>monto</th>
                        <th>Fecha</th>
                         <th>Nro celular</th>
                        <th class="text-center">Actiones</th>
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
        <tr>
        <td><?php echo $record->id_cliente ?></td>
                        <td><?php echo $record->nombre ?></td>
                        <td><?php echo $record->correo ?></td>
                        <td><?php echo $record->doc_identidad ?></td>
                        <td><?php echo $record->celular ?></td>
                        <td class="text-center">
                        <a class="btn btn-sm btn-info" href="<?php echo base_url().'cliente/edit/'.$record->id_cliente; ?>" title="Edit"><i class="fa fa-pencil"></i></a>

                        <a class="btn btn-sm btn-danger" href="<?php echo base_url('cliente/confirmar_eliminar_cliente/' . $record->id_cliente); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?');"><i class="fa fa-trash"></i></a>

                        </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
