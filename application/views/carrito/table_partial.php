<?php if (!empty($records)): ?>
    <?php foreach ($records as $record):
        $tipo      = isset($record->tipo_pago)   ? $record->tipo_pago   : 'contado';
        $tipo_venta= isset($record->tipo_venta)  ? $record->tipo_venta  : 'normal';
        $vendedor  = isset($record->nombre_vendedor) ? $record->nombre_vendedor : '—';

        if ($tipo === 'credito')       { $badgeClass = 'badge-credito';  $tipoLabel = 'Crédito'; }
        elseif ($tipo === 'apartado')  { $badgeClass = 'badge-apartado'; $tipoLabel = 'Apartado'; }
        else                           { $badgeClass = 'badge-contado';  $tipoLabel = 'Contado'; }

        // Separar fecha y hora
        $fechaObj  = DateTime::createFromFormat('Y-m-d H:i:s', $record->fecha_venta);
        $fechaDia  = $fechaObj ? fmt_fecha($record->fecha_venta, false, '-') : htmlspecialchars($record->fecha_venta, ENT_QUOTES);
        $fechaHora = $fechaObj ? $fechaObj->format('H:i')   : '';
    ?>
    <tr data-visible="1">
        <td><span class="venta-id">#<?php echo $record->id_venta; ?></span></td>
        <td class="fecha-cell">
            <span class="fecha-day"><?php echo $fechaDia; ?></span>
            <?php if ($fechaHora): ?><span class="fecha-time"><?php echo $fechaHora; ?></span><?php endif; ?>
        </td>
        <td><?php echo htmlspecialchars($record->nombre_cliente, ENT_QUOTES); ?></td>
        <td class="col-vendedor" style="color:#777; font-size:12px;"><?php echo htmlspecialchars($vendedor, ENT_QUOTES); ?></td>
        <td>
            <span class="badge-tipo <?php echo $badgeClass; ?>" data-tipo="<?php echo $tipo; ?>">
                <?php echo $tipoLabel; ?>
            </span>
        </td>
        <td class="text-right col-descuento">
            <?php $desc = (float)$record->descuento; ?>
            <?php if ($desc > 0): ?>
                <span class="monto-descuento">-$<?php echo number_format($desc, 2); ?></span>
            <?php else: ?>
                <span style="color:#ccc;">—</span>
            <?php endif; ?>
        </td>
        <td class="text-right">
            <span class="monto-total">$<?php echo number_format((float)$record->total, 2); ?></span>
        </td>
        <td class="text-center">
            <div class="hv-actions">
                <?php if ($tipo === 'credito' && $tipo_venta !== 'apartado'): ?>
                    <a class="btn btn-xs btn-primary" href="<?php echo base_url().'carrito/credito/'.$record->id_venta; ?>" title="Ver crédito">
                        <i class="fa fa-credit-card"></i>
                    </a>
                <?php endif; ?>
                <?php if ($tipo_venta === 'apartado'): ?>
                    <a class="btn btn-xs btn-warning" href="<?php echo base_url().'carrito/apartado_detalle/'.$record->id_venta; ?>" title="Ver apartado">
                        <i class="fa fa-tags"></i>
                    </a>
                <?php endif; ?>
                <button class="btn btn-xs btn-default" onclick="printZebraTicket(<?php echo $record->id_venta; ?>)" title="Imprimir ticket">
                    <i class="fa fa-print"></i>
                </button>
                <?php if (!empty($puede_editar)): ?>
                    <a class="btn btn-xs btn-info" href="<?php echo base_url().'carrito/carrito_editar/'.$record->id_venta; ?>" title="Editar venta">
                        <i class="fa fa-pencil"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($puede_eliminar)): ?>
                    <a class="btn btn-xs btn-danger"
                       href="<?php echo base_url().'carrito/eliminar_venta/'.$record->id_venta; ?>"
                       title="Eliminar venta"
                       onclick="return confirm('¿Eliminar la venta #<?php echo $record->id_venta; ?>? Esta acción revertirá el stock y el saldo de caja.')">
                        <i class="fa fa-trash"></i>
                    </a>
                <?php endif; ?>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="8">
            <div class="hv-empty">
                <i class="fa fa-inbox"></i>
                No se encontraron ventas.
            </div>
        </td>
    </tr>
<?php endif; ?>
