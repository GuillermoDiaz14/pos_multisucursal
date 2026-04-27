<?php
$roleId = $roleInfo->roleId;
$role   = $roleInfo->role;
$status = $roleInfo->status;

$moduleIcons = [
    'Caja'            => 'fa-money',
    'Ventas'          => 'fa-shopping-cart',
    'Compras'         => 'fa-truck',
    'Gastos'          => 'fa-minus-circle',
    'Ingresos'        => 'fa-plus-circle',
    'Métodos de Pago' => 'fa-credit-card',
    'Productos'       => 'fa-cubes',
    'Proveedores'     => 'fa-building',
    'Traslados'       => 'fa-exchange',
    'Reportes'        => 'fa-bar-chart',
];
?>
<?php $this->load->helper('form'); ?>

<style>
.modulo-card {
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 10px;
    overflow: hidden;
}
.modulo-card .modulo-header {
    background: #f5f5f5;
    padding: 10px 14px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.modulo-card .modulo-header label {
    margin: 0;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    flex: 1;
}
.modulo-card .modulo-header input[type=checkbox] {
    width: 16px;
    height: 16px;
    cursor: pointer;
}
.modulo-card.activo .modulo-header {
    background: #d4edda;
    border-color: #c3e6cb;
}
.modulo-card .modulo-sub {
    padding: 10px 14px 10px 38px;
    background: #fafafa;
    border-top: 1px solid #eee;
}
.modulo-card .modulo-sub label {
    font-weight: normal;
    margin-bottom: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
}
.modulo-card .modulo-sub label:last-child {
    margin-bottom: 0;
}
.modulo-card .modulo-sub input[type=checkbox] {
    width: 15px;
    height: 15px;
    cursor: pointer;
}
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-shield"></i> Administrar Roles
      <small>Editando: <strong><?php echo htmlspecialchars($role); ?></strong></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Inicio</a></li>
      <li><a href="<?php echo base_url('roles/roleListing'); ?>">Roles</a></li>
      <li class="active">Editar Rol</li>
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
      <i class="fa fa-check-circle"></i> <?php echo $success; ?>
    </div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>'); ?>

    <!-- Datos del rol -->
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-info-circle"></i> Datos del Rol</h3>
          </div>
          <form action="<?php echo base_url(); ?>roles/editRole" method="post" id="editRole">
            <input type="hidden" name="roleId" id="roleId" value="<?php echo $roleId; ?>">
            <div class="box-body">
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <label><i class="fa fa-tag text-muted"></i> Nombre del rol</label>
                    <input type="text" class="form-control" name="role"
                           value="<?php echo htmlspecialchars($role); ?>" maxlength="50" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label><i class="fa fa-toggle-on text-muted"></i> Estado</label>
                    <select class="form-control" name="status" required>
                      <option value="">Seleccione estado...</option>
                      <option value="<?php echo ACTIVE; ?>"   <?php echo ($status == ACTIVE)   ? 'selected' : ''; ?>>Activo</option>
                      <option value="<?php echo INACTIVE; ?>" <?php echo ($status == INACTIVE) ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-primary btn-block">
                        <i class="fa fa-save"></i> Guardar cambios
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Matriz de permisos -->
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-warning">
          <div class="box-header with-border">
            <h3 class="box-title">
              <i class="fa fa-key"></i> Permisos de acceso a módulos
            </h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-xs btn-default" id="btn-marcar-todos">
                <i class="fa fa-check-square-o"></i> Marcar todos
              </button>
              <button type="button" class="btn btn-xs btn-default" id="btn-desmarcar-todos" style="margin-left:4px;">
                <i class="fa fa-square-o"></i> Desmarcar todos
              </button>
            </div>
          </div>

          <form method="POST" action="<?php echo base_url(); ?>roles/storeAccessMatrix" id="permisos-form">
            <input type="hidden" name="roleIdForMatrix" id="roleIdForMatrix" value="<?php echo $roleId; ?>">
            <div class="box-body">
              <p class="text-muted" style="font-size:13px; margin-bottom:16px;">
                <i class="fa fa-info-circle"></i>
                Selecciona los módulos a los que este rol tendrá acceso. Los módulos sin acceso no aparecerán en el menú.
              </p>
              <div class="row">
                <?php
                if (!empty($moduleList)):
                  foreach ($moduleList as $record):
                    $moduleName = $record['module'];
                    $key        = array_search($moduleName, array_column($roleAccessMatrix, 'module'));
                    $matrix     = $key !== false ? (array)$roleAccessMatrix[$key] : ['module' => $moduleName, 'total_access' => 0];
                    $isActive   = !empty($matrix['total_access']);
                    $icon       = isset($moduleIcons[$moduleName]) ? $moduleIcons[$moduleName] : 'fa-circle-o';

                    $reportMatrix = [];
                    if ($moduleName === 'Reportes' && !empty($matrix['reports'])) {
                        foreach ($matrix['reports'] as $sr) {
                            $sr = (array)$sr;
                            if (isset($sr['key'])) $reportMatrix[$sr['key']] = $sr;
                        }
                    }
                ?>
                <div class="col-md-6 col-lg-4">
                  <div class="modulo-card <?php echo $isActive ? 'activo' : ''; ?>">
                    <div class="modulo-header">
                      <input type="hidden" name="access[<?php echo $moduleName; ?>][module]" value="<?php echo $moduleName; ?>">
                      <label>
                        <input type="checkbox"
                               name="access[<?php echo $moduleName; ?>][total_access]"
                               class="module-check"
                               data-modulo="<?php echo $moduleName; ?>"
                               <?php echo $isActive ? 'checked' : ''; ?>>
                        <i class="fa <?php echo $icon; ?>"></i>
                        <?php echo $moduleName; ?>
                      </label>
                    </div>

                    <?php if ($moduleName === 'Productos'): ?>
                    <div class="modulo-sub">
                      <label>
                        <input type="checkbox" name="access[Productos][ver_precio_compra]"
                          <?php echo (!empty($matrix['ver_precio_compra'])) ? 'checked' : ''; ?>>
                        <i class="fa fa-tag text-muted"></i> Ver precio de compra
                      </label>
                      <label>
                        <input type="checkbox" name="access[Productos][gestionar]"
                          <?php echo (!empty($matrix['gestionar'])) ? 'checked' : ''; ?>>
                        <i class="fa fa-pencil text-muted"></i> Gestionar (editar / eliminar)
                      </label>
                    </div>
                    <?php endif; ?>

                    <?php if ($moduleName === 'Reportes' && !empty($reportList)): ?>
                    <div class="modulo-sub">
                      <div style="margin-bottom:10px;">
                        <label style="font-weight:600; margin-bottom:4px; display:block;">
                          <i class="fa fa-globe text-muted"></i> Alcance de reportes
                        </label>
                        <select class="form-control input-sm" name="access[Reportes][scope]">
                          <option value="sucursal" <?php echo (!isset($matrix['scope']) || $matrix['scope'] === 'sucursal') ? 'selected' : ''; ?>>Solo mi sucursal</option>
                          <option value="todas" <?php echo (isset($matrix['scope']) && $matrix['scope'] === 'todas') ? 'selected' : ''; ?>>Todas las sucursales</option>
                        </select>
                      </div>
                      <div style="border-top:1px solid #eee; padding-top:8px;">
                        <span style="font-size:12px; font-weight:600; color:#666; display:block; margin-bottom:6px;">
                          <i class="fa fa-list-ul"></i> Reportes permitidos
                        </span>
                        <?php foreach ($reportList as $report): ?>
                        <label>
                          <input type="checkbox"
                                 name="access[Reportes][reports][<?php echo $report['key']; ?>][allowed]"
                                 <?php echo (isset($reportMatrix[$report['key']]['allowed']) && (int)$reportMatrix[$report['key']]['allowed'] === 1) ? 'checked' : ''; ?>>
                          <?php echo htmlspecialchars($report['title']); ?>
                        </label>
                        <?php endforeach; ?>
                      </div>
                    </div>
                    <?php endif; ?>

                  </div>
                </div>
                <?php
                  endforeach;
                endif;
                ?>
              </div>
            </div>

            <div class="box-footer">
              <button type="submit" class="btn btn-warning">
                <i class="fa fa-save"></i> Guardar permisos
              </button>
              <a href="<?php echo base_url('roles/roleListing'); ?>" class="btn btn-default" style="margin-left:6px;">
                <i class="fa fa-arrow-left"></i> Volver a lista
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

  </section>
</div>

<script>
// Marcar/desmarcar tarjeta activa al cambiar checkbox del módulo
document.querySelectorAll('.module-check').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var card = this.closest('.modulo-card');
        if (this.checked) {
            card.classList.add('activo');
        } else {
            card.classList.remove('activo');
        }
    });
});

// Marcar todos
document.getElementById('btn-marcar-todos').addEventListener('click', function() {
    document.querySelectorAll('#permisos-form input[type=checkbox]').forEach(function(cb) {
        cb.checked = true;
        if (cb.classList.contains('module-check')) {
            cb.closest('.modulo-card').classList.add('activo');
        }
    });
});

// Desmarcar todos
document.getElementById('btn-desmarcar-todos').addEventListener('click', function() {
    document.querySelectorAll('#permisos-form input[type=checkbox]').forEach(function(cb) {
        cb.checked = false;
        if (cb.classList.contains('module-check')) {
            cb.closest('.modulo-card').classList.remove('activo');
        }
    });
});
</script>
<script src="<?php echo base_url(); ?>assets/js/addRole.js"></script>
