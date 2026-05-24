<?php
$can_precio    = !empty($permisos['ver_precio_compra']);
$can_gestionar = !empty($permisos['gestionar']);
$colspan_total = 10 - ($can_precio ? 0 : 1) - ($can_gestionar ? 0 : 1);
if (!empty($records)): foreach ($records as $record):
    $s = (int)$record->stock;
    $rowClass = ($s === 0) ? 'stock-agotado' : (($s <= 5) ? 'stock-bajo' : '');
?>
<tr class="<?php echo $rowClass; ?>"
    data-stock="<?php echo $s; ?>"
    data-categoria="<?php echo (int)$record->categoria; ?>"
    data-id="<?php echo $record->id_producto; ?>"
    data-codigo="<?php echo htmlspecialchars($record->codigo, ENT_QUOTES); ?>"
    data-nombre="<?php echo htmlspecialchars($record->nombre_producto, ENT_QUOTES); ?>"
    data-precio-compra="<?php echo number_format((float)$record->precio_compra, 2); ?>"
    data-precio-venta="<?php echo number_format((float)$record->precio_venta, 2); ?>"
    data-nombre-categoria="<?php echo htmlspecialchars($record->nombre_categoria, ENT_QUOTES); ?>"
    data-talla="<?php echo htmlspecialchars($record->talla, ENT_QUOTES); ?>">
    <td>
        <?php if (!empty($record->imagen)):
            // Thumbnail con fallback a original si no existe
            $_imgFull  = $record->imagen;
            $_imgThumb = preg_replace('#(^|/)([^/]+)$#', '$1thumb_$2', $_imgFull);
            $_imgShow  = (is_file(FCPATH.'uploads/'.$_imgThumb)) ? $_imgThumb : $_imgFull;
        ?>
        <img src="<?php echo base_url('uploads/'.$_imgShow); ?>"
             class="img-thumb" width="50" height="50" loading="lazy"
             onclick="verImagen('<?php echo base_url('uploads/'.$_imgFull); ?>','<?php echo htmlspecialchars($record->nombre_producto, ENT_QUOTES); ?>')"
             title="Click para ampliar">
        <?php else: ?>
        <div class="no-image"><i class="fa fa-image"></i></div>
        <?php endif; ?>
    </td>
    <td class="text-muted"><?php echo $record->id_producto; ?></td>
    <td><code><?php echo $record->codigo; ?></code></td>
    <td><strong><?php echo $record->nombre_producto; ?></strong></td>
    <?php if ($can_precio): ?><td class="text-muted"><?php echo '$'.number_format((float)$record->precio_compra, 2); ?></td><?php endif; ?>
    <td><strong><?php echo '$'.number_format((float)$record->precio_venta, 2); ?></strong></td>
    <td>
        <?php if ($s === 0): ?>
            <span class="label label-danger stock-label">Sin stock</span>
        <?php elseif ($s <= 5): ?>
            <span class="label label-warning stock-label"><?php echo $s; ?> bajo</span>
        <?php else: ?>
            <span class="label label-success stock-label"><?php echo $s; ?></span>
        <?php endif; ?>
    </td>
    <td><?php echo $record->nombre_categoria; ?></td>
    <td><span class="label label-default"><?php echo $record->talla; ?></span></td>
    <?php if ($can_gestionar): ?>
    <td class="text-center" style="white-space:nowrap">
        <a href="<?php echo base_url('producto/edit/'.$record->id_producto); ?>"
           class="btn btn-xs btn-info" title="Editar datos">
            <i class="fa fa-pencil"></i>
        </a>
        <a href="<?php echo base_url('producto/editar_imagen/'.$record->id_producto); ?>"
           class="btn btn-xs btn-default" title="Cambiar imagen">
            <i class="fa fa-camera"></i>
        </a>
        <a href="<?php echo base_url('producto/confirmar_eliminar_producto/'.$record->id_producto); ?>"
           class="btn btn-xs btn-danger" title="Eliminar"
           onclick="return confirm('¿Eliminar «<?php echo addslashes($record->nombre_producto); ?>» permanentemente? Esta acción no se puede deshacer.')">
            <i class="fa fa-trash"></i>
        </a>
    </td>
    <?php endif; ?>
</tr>
<?php endforeach; else: ?>
<tr class="no-results-row">
    <td colspan="<?php echo $colspan_total; ?>" class="text-center text-muted" style="padding:40px 20px">
        <i class="fa fa-search fa-2x" style="display:block;margin-bottom:8px"></i>
        No se encontraron productos.
    </td>
</tr>
<?php endif; ?>
