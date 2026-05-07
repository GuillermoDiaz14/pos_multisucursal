<?php if (!empty($records)): ?>
    <?php foreach ($records as $record):
        $vendedor = isset($record->nombre_vendedor) ? $record->nombre_vendedor : '—';
        $fechaObj  = DateTime::createFromFormat('Y-m-d H:i:s', $record->fecha_venta);
        $fechaDia  = $fechaObj ? $fechaObj->format('d/m/Y') : htmlspecialchars($record->fecha_venta, ENT_QUOTES);
        $fechaHora = $fechaObj ? $fechaObj->format('H:i') : '';
        $desc = (float)$record->descuento;
    ?>
    <tr data-visible="1">
        <td><span class="venta-id">#<?php echo $record->id_venta; ?></span></td>
        <td class="fecha-cell">
            <span class="fecha-day"><?php echo $fechaDia; ?></span>
            <?php if ($fechaHora): ?><span class="fecha-time"><?php echo $fechaHora; ?></span><?php endif; ?>
        </td>
        <td><?php echo htmlspecialchars($record->nombre_cliente, ENT_QUOTES, 'UTF-8'); ?></td>
        <td class="col-vendedor" style="color:#777;font-size:12px;"><?php echo htmlspecialchars($vendedor, ENT_QUOTES, 'UTF-8'); ?></td>
        <td class="text-right col-base">$<?php echo number_format((float)$record->base_imponible, 2); ?></td>
        <td class="text-right col-impuesto">$<?php echo number_format((float)$record->impuesto, 2); ?></td>
        <td class="text-right">
            <?php if ($desc > 0): ?>
                <span class="monto-descuento">-$<?php echo number_format($desc, 2); ?></span>
            <?php else: ?>
                <span style="color:#ccc;">—</span>
            <?php endif; ?>
        </td>
        <td class="text-right"><span class="monto-total">$<?php echo number_format((float)$record->total, 2); ?></span></td>
        <td class="text-center">
            <div class="hv-actions">
                <button class="btn btn-xs btn-default" onclick="printZebraTicket(<?php echo $record->id_venta; ?>)" title="Imprimir ticket">
                    <i class="fa fa-print"></i>
                </button>
                <?php if (!empty($is_admin)): ?>
                    <a class="btn btn-xs btn-info" href="<?php echo base_url().'carrito/carrito_editar/'.$record->id_venta; ?>" title="Editar">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a class="btn btn-xs btn-danger"
                       href="<?php echo base_url('carrito/eliminar_venta/'.$record->id_venta); ?>"
                       title="Eliminar"
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
        <td colspan="9">
            <div class="hv-empty">
                <i class="fa fa-inbox"></i>
                No se encontraron ventas al contado.
            </div>
        </td>
    </tr>
<?php endif; ?>
