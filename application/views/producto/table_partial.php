<table class="table table-hover"  id="miTabla">
                    <tr>
                    <th>imagen</th>
                        <th>Id</th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Precio compra</th>
                        <th>Precio venta</th>
                        <th>Stock</th>
                        <th>Categoria</th>
                        <th class="text-center">Acciones</th>
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
        <tr>
        <td>
                    <?php
                    // Verifica si la columna de imagen no está vacía
                    if (!empty($record->imagen)) {
                        // Construye la URL completa de la imagen
                        $imagen_url = base_url('uploads/' . $record->imagen);
                        ?>
                        <img src="<?php echo $imagen_url; ?>" alt="Imagen" width="50" height="50">
                    <?php } ?>
                </td>
                <td><?php echo $record->id_producto ?></td>
                <td><?php echo $record->codigo ?></td>
                        <td><?php echo $record->nombre_producto ?></td>
                        <td><?php echo $record->precio_compra ?></td>
                        <td><?php echo $record->precio_venta ?></td>
                        <td><?php echo $record->stock ?></td>

                        <td><?php echo $record->nombre_categoria ?></td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'producto/edit/'.$record->id_producto; ?>" title="Edit"><i class="fa fa-pencil"></i></a>

                            <a class="btn btn-sm btn-danger" href="<?php echo base_url('producto/confirmar_eliminar_producto/' . $record->id_producto); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?');"><i class="fa fa-trash"></i></a>
                      
                        </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
