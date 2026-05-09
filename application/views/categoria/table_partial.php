<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
    <tr>
        <td><?php echo (int)$record->id_categoria; ?></td>
        <td><?php echo htmlspecialchars($record->nombre_categoria, ENT_QUOTES, 'UTF-8'); ?></td>
        <td class="text-center">
            <a class="btn btn-sm btn-info" href="<?php echo base_url('categoria/edit/'.$record->id_categoria); ?>" title="Editar">
                <i class="fa fa-pencil"></i>
            </a>
            <a class="btn btn-sm btn-danger"
               href="<?php echo base_url('categoria/confirmar_eliminar_categoria/'.$record->id_categoria); ?>"
               onclick="return confirm('¿Eliminar la categoría «<?php echo addslashes($record->nombre_categoria); ?>»?')">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="3" class="text-center text-muted" style="padding:30px 0">
            <i class="fa fa-folder-open-o fa-2x" style="display:block;margin-bottom:8px"></i>
            No se encontraron categorías.
        </td>
    </tr>
<?php endif; ?>
