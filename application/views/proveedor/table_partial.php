<table class="table table-hover"  id="miTabla">
                    <tr>
                    <th>Id</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Celular</th>
                        <th>direccion</th>
                        <th>doc. fiscal</th>
                        <th class="text-center">Acciones</th>
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
        <tr>
        <td><?php echo $record->id_proveedor ?></td>
                        <td><?php echo $record->nombre ?></td>
                        <td><?php echo $record->email ?></td>
                        <td><?php echo $record->celular ?></td>
                        <td><?php echo $record->direccion ?></td>
                        <td><?php echo $record->doc_fiscal ?></td>
                        <td class="text-center">
                        <a class="btn btn-sm btn-info" href="<?php echo base_url().'proveedor/edit/'.$record->id_proveedor; ?>" title="Edit"><i class="fa fa-pencil"></i></a>

<a class="btn btn-sm btn-danger" href="<?php echo base_url('proveedor/confirmar_eliminar_proveedor/' . $record->id_proveedor); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?');"><i class="fa fa-trash"></i></a>

                        </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
