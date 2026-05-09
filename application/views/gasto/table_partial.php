<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
    <tr>
        <td>
            <span class="gas-desc" title="<?php echo htmlspecialchars($record->descripcion, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($record->descripcion, ENT_QUOTES, 'UTF-8'); ?>
            </span>
        </td>
        <td><span class="gas-monto">$<?php echo number_format((float)$record->monto, 2); ?></span></td>
        <td class="col-gas-fecha" style="font-size:12px;color:#777;"><?php echo fmt_fecha($record->fecha); ?></td>
        <td class="text-center">
            <div class="gas-actions">
                <a class="btn btn-xs btn-info" href="<?php echo base_url().'gasto/edit/'.$record->id_gasto; ?>" title="Editar">
                    <i class="fa fa-pencil"></i>
                </a>
                <a class="btn btn-xs btn-danger" href="#"
                   onclick="confirmarEliminarGas(event, '<?php echo base_url().'gasto/confirmar_eliminar_gasto/'.$record->id_gasto; ?>', '<?php echo htmlspecialchars($record->descripcion, ENT_QUOTES); ?>')"
                   title="Eliminar">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4">
            <div class="gas-empty">
                <i class="fa fa-money"></i>
                No se encontraron gastos.
            </div>
        </td>
    </tr>
<?php endif; ?>
