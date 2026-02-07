<table class="table table-hover"  id="miTabla">
                    <tr>
                    <th>Id</th>
                    <th>Metodo pago</th>
                        <th class="text-center">Actiones</th>
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
        <tr>
        <td><?php echo $record->id_metodo_pago ?></td>
                        <td><?php echo $record->nombre_metodo_pago ?></td>
                        <td class="text-center">
                        <a class="btn btn-sm btn-info" href="<?php echo base_url().'metodo_pago/edit/'.$record->id_metodo_pago; ?>" title="Edit"><i class="fa fa-pencil"></i></a>

                        <a class="btn btn-sm btn-danger" href="<?php echo base_url('metodo_pago/confirmar_eliminar_metodo_pago/' . $record->id_metodo_pago); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?');"><i class="fa fa-trash"></i></a>

                        </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
