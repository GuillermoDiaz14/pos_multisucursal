<?php if (!empty($records)): ?>
    <?php foreach ($records as $record):
        $tipo = isset($record->tipo_pago) ? $record->tipo_pago : 'contado';
        $tipo_venta = isset($record->tipo_venta) ? $record->tipo_venta : 'normal';
        $badgeClass = 'label-contado';
        $tipoLabel  = 'Contado';
        if ($tipo === 'credito')  { $badgeClass = 'label-credito';  $tipoLabel = 'Crédito'; }
        if ($tipo === 'apartado') { $badgeClass = 'label-apartado'; $tipoLabel = 'Apartado'; }
        $vendedor = isset($record->nombre_vendedor) ? $record->nombre_vendedor : '—';
    ?>
    <tr>
        <td><?php echo $record->id_venta; ?></td>
        <td><?php echo $record->fecha_venta; ?></td>
        <td><?php echo htmlspecialchars($record->nombre_cliente, ENT_QUOTES); ?></td>
        <td><?php echo htmlspecialchars($vendedor, ENT_QUOTES); ?></td>
        <td><span class="label <?php echo $badgeClass; ?>"><?php echo $tipoLabel; ?></span></td>
        <td class="text-right"><?php echo '$' . number_format((float)$record->descuento, 2); ?></td>
        <td class="text-right"><strong><?php echo '$' . number_format((float)$record->total, 2); ?></strong></td>
        <td class="text-center">
            <?php if ($tipo === 'credito' && $tipo_venta !== 'apartado'): ?>
                <a class="btn btn-sm btn-primary" href="<?php echo base_url() . 'carrito/credito/' . $record->id_venta; ?>">
                    <i class="fa fa-credit-card"></i> Crédito
                </a>
            <?php endif; ?>
            <?php if ($tipo_venta === 'apartado'): ?>
                <a class="btn btn-sm btn-warning" href="<?php echo base_url() . 'carrito/apartado_detalle/' . $record->id_venta; ?>">
                    <i class="fa fa-tags"></i> Apartado
                </a>
            <?php endif; ?>
            <a class="btn btn-sm btn-default" href="<?php echo base_url() . 'carrito/exportToPDF/' . $record->id_venta; ?>" target="_blank" title="Ticket">
                <i class="fa fa-print"></i>
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="8" class="text-center text-muted">No se encontraron ventas.</td></tr>
<?php endif; ?>
