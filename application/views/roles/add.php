<?php $this->load->helper('form'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-shield"></i> Administrar Roles
      <small>Crear nuevo rol</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Inicio</a></li>
      <li><a href="<?php echo base_url('roles/roleListing'); ?>">Roles</a></li>
      <li class="active">Nuevo Rol</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-6">

        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">×</button><i class="fa fa-ban"></i> ', '</div>'); ?>
        <?php if ($error = $this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">×</button>
          <i class="fa fa-ban"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-plus-circle"></i> Datos del Rol</h3>
          </div>
          <form action="<?php echo base_url(); ?>roles/addNewRole" method="post" id="addRole">
            <div class="box-body">
              <div class="form-group">
                <label for="role"><i class="fa fa-tag text-muted"></i> Nombre del rol <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="role" name="role"
                       value="<?php echo set_value('role'); ?>" maxlength="50"
                       placeholder="Ej: Vendedor, Supervisor, Almacén..." required autofocus>
                <span class="help-block">Máximo 50 caracteres.</span>
              </div>
              <div class="form-group">
                <label for="status"><i class="fa fa-toggle-on text-muted"></i> Estado <span class="text-danger">*</span></label>
                <select class="form-control" id="status" name="status" required>
                  <option value="">Seleccione estado...</option>
                  <option value="<?php echo ACTIVE; ?>"   <?php echo set_select('status', ACTIVE); ?>>Activo</option>
                  <option value="<?php echo INACTIVE; ?>" <?php echo set_select('status', INACTIVE); ?>>Inactivo</option>
                </select>
              </div>
            </div>
            <div class="box-footer">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Guardar rol
              </button>
              <a href="<?php echo base_url('roles/roleListing'); ?>" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Cancelar
              </a>
            </div>
          </form>
        </div>

        <div class="callout callout-info">
          <h4><i class="fa fa-lightbulb-o"></i> Siguiente paso</h4>
          <p>Después de crear el rol podrás configurar sus permisos de acceso a los módulos del sistema.</p>
        </div>

      </div>
    </div>
  </section>
</div>
<script src="<?php echo base_url(); ?>assets/js/addRole.js"></script>
