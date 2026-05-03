<?php if (!empty($records)): ?>
    <?php foreach ($records as $record):
        $deuda = $record->total - $record->saldo;
        $estado = isset($record->estado_apartado) ? $record->estado_apartado : 'en_proceso';
        $badgeClass = 'label-en-proceso';
        $estadoLabel = 'En proceso';
        if ($estado === 'entregado')  { $badgeClass = 'label-entregado'; $estadoLabel = 'Entregado'; }
        if ($estado === 'cancelado')  { $badgeClass = 'label-cancelado'; $estadoLabel = 'Cancelado'; }
        $isAdmin = isset($isAdmin) ? $isAdmin : false;
    ?>
    <tr>
        <td><?php echo $record->id_venta; ?></td>
        <td><?php echo fmt_fecha($record->fecha_venta); ?></td>
        <td><?php echo htmlspecialchars($record->nombre_cliente, ENT_QUOTES); ?></td>
        <td><?php echo '$' . number_format((float)$record->total, 2); ?></td>
        <td><?php echo '$' . number_format((float)$record->saldo, 2); ?></td>
        <td><?php echo '$' . number_format((float)$deuda, 2); ?></td>
        <td><span class="label <?php echo $badgeClass; ?>"><?php echo $estadoLabel; ?></span></td>
        <td class="text-center">
            <a class="btn btn-sm btn-info" href="<?php echo base_url() . 'carrito/apartado_detalle/' . $record->id_venta; ?>" title="Ver detalle">
                <i class="fa fa-eye"></i> Detalle
            </a>
            <?php if ($isAdmin && $estado === 'en_proceso' && $deuda <= 0.009): ?>
                <a class="btn btn-sm btn-success"
                   href="<?php echo base_url() . 'carrito/entregar_apartado/' . $record->id_venta; ?>"
                   onclick="return confirm('¿Confirmar entrega del producto al cliente?')"
                   title="Entregar">
                    <i class="fa fa-check"></i> Entregar
                </a>
            <?php endif; ?>
            <?php if ($isAdmin && $estado === 'en_proceso'): ?>
                <a class="btn btn-sm btn-danger"
                   href="<?php echo base_url() . 'carrito/cancelar_apartado/' . $record->id_venta; ?>"
                   onclick="return confirm('¿Cancelar este apartado? El inventario será restaurado.')"
                   title="Cancelar">
                    <i class="fa fa-times"></i> Cancelar
                </a>
            <?php endif; ?>
            <?php if ($isAdmin): ?>
                <a class="btn btn-sm btn-danger"
                   href="<?php echo base_url() . 'carrito/eliminar_apartado/' . $record->id_venta; ?>"
                   onclick="return confirm('¿Eliminar este apartado? No se puede deshacer.')"
                   title="Eliminar">
                    <i class="fa fa-trash"></i>
                </a>
            <?php endif; ?>
            <button type="button" class="btn btn-sm btn-default"
                    data-zebra-apartado="<?php echo $record->id_venta; ?>"
                    onclick="printZebraApartado(<?php echo $record->id_venta; ?>)"
                    title="Imprimir Ticket ZPL">
                <i class="fa fa-print"></i>
            </button>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="8" class="text-center text-muted">No se encontraron apartados</td></tr>
<?php endif; ?>
