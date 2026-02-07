<table class="table table-hover"  id="miTabla">
                    <tr>
                    <th>Rol</th>
                        <th>Estado</th>
                        <th>Creado en</th>
                        <th class="text-center">Acciones</th>
                    </tr>
<?php if (!empty($records)): ?>
    <?php foreach ($records as $record): ?>
        <tr>
        <td><?php echo $record->role ?></td>
                        <td>
                            <?php 
                            if($record->status == ACTIVE) {
                                ?> <span class="label label-success">Activo</span> <?php
                            } else {
                                ?> <span class="label label-warning">Inactivo</span> <?php
                            }
                            ?>
                        </td>
                        <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'roles/edit/'.$record->roleId; ?>" title="Edit"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-sm btn-danger deleteRole" href="#" data-roleid="<?php echo $record->roleId; ?>" title="Delete"><i class="fa fa-trash"></i></a>
                        </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4" class="text-center">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>
