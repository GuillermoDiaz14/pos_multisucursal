<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus"></i> Agregar Género
            <small>Crear un nuevo género</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url('genero/lista'); ?>"><i class="fa fa-venus-mars"></i> Géneros</a></li>
            <li class="active"><i class="fa fa-plus"></i> Agregar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Formulario de Género</h3>
                    </div>

                    <form id="frm_genero" action="<?php echo base_url('genero/addNewGenero'); ?>" method="POST" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nombre_genero" class="col-sm-3 control-label">Nombre *</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nombre_genero" name="nombre_genero" maxlength="50" required value="<?php echo set_value('nombre_genero'); ?>" placeholder="Ej: Hombre, Mujer, Unisex">
                                    <?php echo form_error('nombre_genero', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="descripcion" class="col-sm-3 control-label">Descripción</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" maxlength="255" placeholder="Opcional"><?php echo set_value('descripcion'); ?></textarea>
                                    <?php echo form_error('descripcion', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="activa" class="col-sm-3 control-label">Estado</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="activa" name="activa">
                                        <option value="1" selected>Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <a href="<?php echo base_url('genero/lista'); ?>" class="btn btn-default">
                                <i class="fa fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary pull-right">
                                <i class="fa fa-save"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#frm_genero').on('submit', function(e) {
        if (!$.trim($('#nombre_genero').val())) {
            e.preventDefault();
            alert('Por favor ingresa el nombre del género');
            return false;
        }
    });
});
</script>
