<table class="table table-hover"  id="miTabla">
                    <tr>
                    <th>Id</th>
                        <th>Nombre</th>
                    

                        <th class="text-center">Acciones</th>
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
        <tr>
        <td><?php echo $record->id_categoria ?></td>
                        <td><?php echo $record->nombre_categoria ?></td>
              
                
                        <td class="text-center">
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'categoria/edit/'.$record->id_categoria; ?>" title="Edit"><i class="fa fa-pencil"></i></a>

                            <a class="btn btn-sm btn-danger" href="<?php echo base_url('categoria/confirmar_eliminar_categoria/' . $record->id_categoria); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?');"><i class="fa fa-trash"></i></a>
                      
                        </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
