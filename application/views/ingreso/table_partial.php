<table class="table table-hover"  id="miTabla">
                    <tr>
                    <th>Id</th>
                        <th>Descripcion</th>
                        <th>monto</th>
                        <th>Fecha</th>
                        <th class="text-center">Actiones</th>
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
        <tr>
        <td><?php echo $record->id_ingreso ?></td>
                        <td><?php echo $record->descripcion ?></td>
                        <td><?php echo $record->monto ?></td>
                        <td><?php echo $record->fecha ?></td>
                        <td class="text-center">
                        <a class="btn btn-sm btn-info" href="<?php echo base_url().'ingreso/edit/'.$record->id_ingreso; ?>" title="Edit"><i class="fa fa-pencil"></i></a>

                        <a class="btn btn-sm btn-danger" href="<?php echo base_url('ingreso/confirmar_eliminar_ingreso/' . $record->id_ingreso); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?');"><i class="fa fa-trash"></i></a>

                        </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
