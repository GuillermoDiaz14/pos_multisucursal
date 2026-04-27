<?php $this->load->helper('form'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-shield"></i> Administrar Roles
      <small>Gestiona los roles y permisos del sistema</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Inicio</a></li>
      <li class="active">Roles</li>
    </ol>
  </section>

  <section class="content">

    <?php if ($error = $this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissable">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <i class="fa fa-ban"></i> <?php echo $error; ?>
    </div>
    <?php endif; ?>
    <?php if ($success = $this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <i class="fa fa-check"></i> <?php echo $success; ?>
    </div>
    <?php endif; ?>

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">
          <i class="fa fa-list"></i> Lista de Roles
          <?php if (!empty($roleRecords)): ?>
          <span class="badge" style="background:#777; margin-left:6px;"><?php echo count($roleRecords); ?></span>
          <?php endif; ?>
        </h3>
        <div class="box-tools pull-right">
          <div class="input-group input-group-sm" style="width:200px; display:inline-flex; margin-right:8px; vertical-align:middle;">
            <input type="text" id="searchText" class="form-control" placeholder="Buscar rol..." oninput="filterRoles()">
            <span class="input-group-btn">
              <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
            </span>
          </div>
          <a class="btn btn-sm btn-success" href="<?php echo base_url(); ?>roles/add">
            <i class="fa fa-plus"></i> Nuevo rol
          </a>
        </div>
      </div>
      <div class="box-body no-padding">
        <table class="table table-hover table-striped" id="roles-table">
          <thead>
            <tr>
              <th style="width:45%; padding-left:16px;">Nombre del Rol</th>
              <th style="width:15%;">Estado</th>
              <th style="width:25%;">Fecha de creación</th>
              <th class="text-center" style="width:15%;">Acciones</th>
            </tr>
          </thead>
          <tbody id="roles-body">
            <?php if (!empty($roleRecords)): ?>
            <?php foreach ($roleRecords as $record): ?>
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
                <?php echo date("d/m/Y", strtotime($record->createdDtm)); ?>
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
              <td colspan="4" class="text-center text-muted" style="padding:40px 20px;">
                <i class="fa fa-inbox fa-2x" style="display:block; margin-bottom:10px;"></i>
                No hay roles registrados. <a href="<?php echo base_url(); ?>roles/add">Crear el primero</a>.
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="box-footer text-muted" style="font-size:12px;">
        <i class="fa fa-info-circle"></i> Edita un rol para configurar sus permisos de acceso a los módulos.
      </div>
    </div>

  </section>
</div>

<script src="<?php echo base_url(); ?>assets/js/common.js"></script>
<script>
function filterRoles() {
  var searchText = document.getElementById('searchText').value;
  $.ajax({
    url: '<?php echo base_url(); ?>roles/filterroles',
    type: 'POST',
    data: { searchText: searchText },
    beforeSend: function() {
      $('#roles-body').html('<tr><td colspan="4" class="text-center" style="padding:20px;"><i class="fa fa-spinner fa-spin"></i> Buscando...</td></tr>');
    },
    success: function(response) {
      $('#roles-body').html(response);
    }
  });
}
</script>
