<?php if (!empty($records)): ?>
    <?php foreach ($records as $record):
        $vendedor = isset($record->nombre_vendedor) ? $record->nombre_vendedor : '—';
    ?>
    <tr>
        <td><?php echo $record->id_venta; ?></td>
        <td><?php echo $record->fecha_venta; ?></td>
        <td><?php echo htmlspecialchars($record->nombre_cliente, ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($vendedor, ENT_QUOTES, 'UTF-8'); ?></td>
        <td class="text-right"><?php echo '$' . number_format((float) $record->base_imponible, 2); ?></td>
        <td class="text-right"><?php echo '$' . number_format((float) $record->impuesto, 2); ?></td>
        <td class="text-right"><?php echo '$' . number_format((float) $record->descuento, 2); ?></td>
        <td class="text-right"><strong><?php echo '$' . number_format((float) $record->total, 2); ?></strong></td>
        <td class="text-center">
            <?php if (!empty($is_admin)): ?>
                <a class="btn btn-sm btn-warning" href="<?php echo base_url() . 'carrito/carrito_editar/' . $record->id_venta; ?>" title="Editar">
                    <i class="fa fa-pencil"></i>
                </a>
                <a class="btn btn-sm btn-danger" href="<?php echo base_url('carrito/eliminar_venta/' . $record->id_venta); ?>" title="Eliminar">
                    <i class="fa fa-trash"></i>
                </a>
                <a class="btn btn-sm btn-default" href="<?php echo base_url() . 'carrito/exportToPDF/' . $record->id_venta; ?>" target="_blank" title="Ver venta">
                    <i class="fa fa-file-text-o"></i>
                </a>
            <?php else: ?>
                <span class="text-muted">Solo administrador</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="9" class="text-center text-muted">No se encontraron ventas al contado.</td>
    </tr>
<?php endif; ?>
