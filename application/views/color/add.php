<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus"></i> Agregar Color
            <small>Crear un nuevo color</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url('color/lista'); ?>"><i class="fa fa-paint-brush"></i> Colores</a></li>
            <li class="active"><i class="fa fa-plus"></i> Agregar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" style="padding: 0 15px;">
                <div class="box box-primary" style="border-top:3px solid #3c8dbc; box-shadow:0 8px 22px rgba(60,141,188,.08);">
                    <div class="box-header with-border" style="padding:18px 20px;">
                        <h3 class="box-title" style="font-weight:700;"><i class="fa fa-paint-brush"></i> Crear Nuevo Color</h3>
                        <p class="text-muted" style="margin:6px 0 0;">Registro simple para acelerar el alta.</p>
                    </div>

                    <form id="frm_color" action="<?php echo base_url('color/addNewColor'); ?>" method="POST" class="form-horizontal">
                        <div class="box-body" style="padding: 22px 30px 10px;">

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="nombre_color" class="col-sm-3 control-label" style="padding-top: 8px;">Nombre *</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-lg" id="nombre_color" name="nombre_color"
                                           placeholder="Ej: Rojo oscuro, Azul marino..." maxlength="50" required
                                           value="<?php echo set_value('nombre_color'); ?>" style="font-size: 14px; padding: 10px 12px;">
                                    <?php echo form_error('nombre_color', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer" style="padding: 15px 30px 20px;">
                            <a href="<?php echo base_url('color/lista'); ?>" class="btn btn-default">
                                <i class="fa fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary pull-right">
                                <i class="fa fa-save"></i> Guardar Color
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
    // Validar formulario
    $('#frm_color').on('submit', function(e) {
        var nombre = $.trim($('#nombre_color').val());
        if (!nombre) {
            e.preventDefault();
            alert('Por favor ingresa el nombre del color');
            return false;
        }
    });
});
</script>
