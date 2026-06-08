<?php if (!empty($records)): foreach ($records as $r): ?>
<tr>
    <td><?php echo $r->id_traslado; ?></td>
    <td><?php echo htmlspecialchars($r->sucursal_traslado); ?></td>
    <td><?php echo fmt_fecha($r->fecha_actual, false, '-'); ?></td>
    <td><?php echo htmlspecialchars($r->nombre_usuario ?? '—'); ?></td>
    <td><?php echo htmlspecialchars($r->comentario); ?></td>
    <td class="text-center" style="white-space:nowrap;">
        <a class="btn btn-sm btn-primary" href="<?php echo base_url('trasladar/detalle/' . $r->id_traslado); ?>"
           title="Ver detalle"><i class="fa fa-eye"></i></a>
        <a class="btn btn-sm btn-info" href="<?php echo base_url('trasladar/exportToPDF/' . $r->id_traslado); ?>"
           title="Ver ticket PDF" target="_blank"><i class="fa fa-file-text-o"></i></a>
    </td>
</tr>
<?php endforeach; else: ?>
<tr>
    <td colspan="6" class="text-center text-muted" style="padding:30px 20px">
        <i class="fa fa-search fa-2x" style="display:block;margin-bottom:8px"></i>
        No se encontraron traslados.
    </td>
</tr>
<?php endif; ?>
