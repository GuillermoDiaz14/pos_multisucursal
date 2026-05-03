<?php if (!empty($records)): foreach ($records as $r): ?>
<tr>
    <td><?php echo $r->id_traslado; ?></td>
    <td><?php echo htmlspecialchars($r->sucursal_traslado); ?></td>
    <td><?php echo fmt_fecha($r->fecha_actual); ?></td>
    <td><?php echo htmlspecialchars($r->nombre_usuario ?? '—'); ?></td>
    <td><?php echo htmlspecialchars($r->comentario); ?></td>
    <td class="text-center">
        <a class="btn btn-sm btn-info" href="<?php echo base_url('trasladar/exportToPDF/' . $r->id_traslado); ?>"
           title="Ver ticket" target="_blank"><i class="fa fa-file-text-o"></i></a>
    </td>
</tr>
<?php endforeach; else: ?>
<tr>
    <td colspan="6" class="text-center text-muted" style="padding:30px 20px">
        <i class="fa fa-search fa-2x" style="display:block;margin-bottom:8px"></i>
        No se encontraron traslados recibidos.
    </td>
</tr>
<?php endif; ?>
