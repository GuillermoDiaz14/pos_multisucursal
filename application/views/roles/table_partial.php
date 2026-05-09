<?php if (!empty($records)): ?>
<?php foreach ($records as $record): ?>
<tr>
  <td style="padding-left:16px;">
    <i class="fa fa-user-circle-o text-muted" style="margin-right:6px;"></i>
    <strong><?php echo htmlspecialchars($record->role); ?></strong>
  </td>
  <td>
    <?php if ($record->status == ACTIVE): ?>
      <span class="label label-success"><i class="fa fa-check"></i> Activo</span>
    <?php else: ?>
      <span class="label label-warning"><i class="fa fa-pause"></i> Inactivo</span>
    <?php endif; ?>
  </td>
  <td>
    <i class="fa fa-calendar-o text-muted" style="margin-right:4px;"></i>
    <?php echo date("d-m-Y", strtotime($record->createdDtm)); ?>
  </td>
  <td class="text-center">
    <a class="btn btn-xs btn-info" href="<?php echo base_url().'roles/edit/'.$record->roleId; ?>" title="Editar permisos">
      <i class="fa fa-pencil"></i> Editar
    </a>
    <a class="btn btn-xs btn-danger"
       href="<?php echo base_url('roles/confirmar_eliminar_rol/'.$record->roleId); ?>"
       onclick="return confirm('¿Eliminar el rol «<?php echo addslashes($record->role); ?>»?\nEsta acción no se puede deshacer.')">
      <i class="fa fa-trash"></i>
    </a>
  </td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
  <td colspan="4" class="text-center text-muted" style="padding:30px 20px;">
    <i class="fa fa-search fa-2x" style="display:block; margin-bottom:8px;"></i>
    No se encontraron roles.
  </td>
</tr>
<?php endif; ?>
