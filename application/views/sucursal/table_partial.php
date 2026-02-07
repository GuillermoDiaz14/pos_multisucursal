<table class="table table-hover"  id="miTabla">
                    <tr>
                  <th>Id</th>
                        <th>impuesto</th>
                        <th>celular</th>
                        <th>direccion</th>
                        <th>ciudad</th>
                        <th>correo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
        <tr>
  <td><?php echo $record->id_sucursal ?></td>
                        <td><?php echo $record->nombre_sucursal ?></td>
                        <td><?php echo $record->impuesto ?></td>
                        <td><?php echo $record->celular ?></td>
                        <td><?php echo $record->direccion ?></td>
                        <td><?php echo $record->ciudad ?></td>
                           <td><?php echo $record->correo ?></td>
                        <td class="text-center">
                        <a class="btn btn-sm btn-info" href="<?php echo base_url().'sucursal/edit/'.$record->id_sucursal; ?>" title="Edit"><i class="fa fa-pencil"></i></a>

<a class="btn btn-sm btn-danger" href="<?php echo base_url('sucursal/confirmar_eliminar_sucursal/' . $record->id_sucursal); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?');"><i class="fa fa-trash"></i></a>

                        </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
