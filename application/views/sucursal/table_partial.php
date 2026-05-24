<?php
$ci          = &get_instance();
$accessInfo  = $ci->session->userdata('accessInfo') ?: [];
$isAdmin     = !empty($ci->session->userdata('emergency_admin'));
$sucPerm     = isset($accessInfo['Sucursal']) ? $accessInfo['Sucursal'] : [];
$ventaPerm   = isset($accessInfo['Ventas']) ? $accessInfo['Ventas'] : [];
$puedeEditar   = $isAdmin || !empty($sucPerm['editar']);
$puedeEliminar = $isAdmin || !empty($sucPerm['eliminar']);
$puedeTicket   = $isAdmin || !empty($ventaPerm['configurar_ticket']);
?>
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
                <?php if ($puedeEditar): ?>
                <a class="btn btn-xs btn-info" href="<?php echo base_url().'sucursal/edit/'.$record->id_sucursal; ?>" title="Editar">
                    <i class="fa fa-pencil"></i>
                </a>
                <?php endif; ?>
                <?php if ($puedeTicket): ?>
                <a class="btn btn-xs btn-warning" href="<?php echo base_url('sucursal/ticket_config/'.$record->id_sucursal); ?>" title="Configurar ticket">
                    <i class="fa fa-ticket"></i>
                </a>
                <?php endif; ?>
                <?php if ($puedeEliminar): ?>
                <button class="btn btn-xs btn-danger" onclick="abrirModalEliminar(<?php echo $record->id_sucursal; ?>)" title="Eliminar">
                    <i class="fa fa-trash"></i>
                </button>
                <?php endif; ?>
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
