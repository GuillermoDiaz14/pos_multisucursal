<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
    <tr data-visible="1">
        <td><span class="sc-id">#<?php echo $record->id_sucursal; ?></span></td>
        <td><span class="sc-name"><?php echo htmlspecialchars($record->nombre_sucursal, ENT_QUOTES, 'UTF-8'); ?></span></td>
        <td><span class="badge-impuesto"><?php echo htmlspecialchars($record->impuesto, ENT_QUOTES); ?>%</span></td>
        <td style="color:#555;font-size:12px;"><?php echo htmlspecialchars($record->celular, ENT_QUOTES, 'UTF-8'); ?></td>
        <td class="col-sc-dir" style="color:#777;font-size:12px;"><?php echo htmlspecialchars($record->direccion, ENT_QUOTES, 'UTF-8'); ?></td>
        <td style="color:#555;"><?php echo htmlspecialchars($record->ciudad, ENT_QUOTES, 'UTF-8'); ?></td>
        <td class="col-sc-correo" style="color:#777;font-size:12px;"><?php echo htmlspecialchars($record->correo ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
        <td class="text-center">
            <div class="sc-actions">
                <a class="btn btn-xs btn-info" href="<?php echo base_url().'sucursal/edit/'.$record->id_sucursal; ?>" title="Editar">
                    <i class="fa fa-pencil"></i>
                </a>
                <a class="btn btn-xs btn-warning" href="<?php echo base_url('sucursal/ticket_config/'.$record->id_sucursal); ?>" title="Configurar ticket">
                    <i class="fa fa-ticket"></i>
                </a>
                <button class="btn btn-xs btn-danger" onclick="abrirModalEliminar(<?php echo $record->id_sucursal; ?>)" title="Eliminar">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="8">
            <div class="sc-empty">
                <i class="fa fa-building-o"></i>
                No se encontraron sucursales.
            </div>
        </td>
    </tr>
<?php endif; ?>
