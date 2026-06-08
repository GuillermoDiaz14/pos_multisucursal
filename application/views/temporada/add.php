<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus"></i> Agregar Temporada
            <small>Crear una nueva temporada</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url('temporada/lista'); ?>"><i class="fa fa-calendar"></i> Temporadas</a></li>
            <li class="active"><i class="fa fa-plus"></i> Agregar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Formulario de Temporada</h3>
                    </div>

                    <form id="frm_temporada" action="<?php echo base_url('temporada/addNewTemporada'); ?>" method="POST" class="form-horizontal">
                        <div class="box-body">

                            <div class="form-group">
                                <label for="nombre_temporada" class="col-sm-3 control-label">Nombre *</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nombre_temporada" name="nombre_temporada" 
                                           placeholder="Ej: Navidad 2026, Verano 2026..." maxlength="100" required
                                           value="<?php echo set_value('nombre_temporada'); ?>">
                                    <?php echo form_error('nombre_temporada', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="descripcion" class="col-sm-3 control-label">Descripción</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="descripcion" name="descripcion" 
                                              rows="3" placeholder="Descripción opcional..." maxlength="255"><?php echo set_value('descripcion'); ?></textarea>
                                    <?php echo form_error('descripcion', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="activa" class="col-sm-3 control-label">Activa</label>
                                <div class="col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="activa" value="1" <?php echo set_checkbox('activa', '1', TRUE) ? 'checked' : ''; ?>>
                                            Esta temporada está activa
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer">
                            <a href="<?php echo base_url('temporada/lista'); ?>" class="btn btn-default">
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
    $('#frm_temporada').on('submit', function(e) {
        var nombre = $.trim($('#nombre_temporada').val());
        if (!nombre) {
            e.preventDefault();
            alert('Por favor ingresa el nombre de la temporada');
            return false;
        }
    });
});
</script>
