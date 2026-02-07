<table class="table table-hover"  id="employeeTable">
                    <tr>
                        <th>Nombre</th>
                        <th>Dni</th>
                        <th>Celular</th>
                        <th class="text-center">Actiones</th>
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
        <tr>
            <td><?php echo $record->nombre ?></td>
            <td><?php echo $record->dni ?></td>
            <td><?php echo $record->celular ?></td>
            <td class="text-center">
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'empleado/edit/'.$record->id_empleado; ?>" title="Edit"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-sm btn-danger deleteEmpleado" href="#" data-empleadoid="<?php echo $record->id_empleado; ?>" title="Delete"><i class="fa fa-trash"></i></a>
                            <a class="btn btn-sm btn-danger" href="<?php echo base_url('empleado/confirmar_eliminar_empleado/' . $record->id_empleado); ?>"><i class="fa fa-trash"></i></a>
                        </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
