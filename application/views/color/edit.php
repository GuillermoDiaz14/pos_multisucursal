<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-pencil"></i> Editar Color
            <small>Modificar información del color</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url('color/lista'); ?>"><i class="fa fa-paint-brush"></i> Colores</a></li>
            <li class="active"><i class="fa fa-pencil"></i> Editar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-primary" style="border-top:3px solid #3c8dbc; box-shadow:0 8px 22px rgba(60,141,188,.08);">
                    <div class="box-header with-border" style="padding:18px 20px;">
                        <h3 class="box-title" style="font-weight:700;">Editar Color</h3>
                        <p class="text-muted" style="margin:6px 0 0;">Mantén el nombre y estado sin pasos extra.</p>
                    </div>

                    <form id="frm_color" action="<?php echo base_url('color/editColor'); ?>" method="POST" class="form-horizontal">
                        <input type="hidden" name="id_color" value="<?php echo $colorInfo->id_color; ?>">

                        <div class="box-body" style="padding: 22px 30px 10px;">

                            <div class="form-group">
                                <label for="nombre_color" class="col-sm-3 control-label">Nombre *</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nombre_color" name="nombre_color" 
                                           placeholder="Nombre del color" maxlength="50" required
                                           value="<?php echo htmlspecialchars($colorInfo->nombre_color); ?>">
                                    <?php echo form_error('nombre_color', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="activo" class="col-sm-3 control-label">Estado</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="activo" name="activo">
                                        <option value="1" <?php echo ($colorInfo->activo) ? 'selected' : ''; ?>>Activo</option>
                                        <option value="0" <?php echo (!$colorInfo->activo) ? 'selected' : ''; ?>>Inactivo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Información</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">
                                        <small class="text-muted">
                                            Creado: <?php echo !empty($colorInfo->createdDtm) ? date('d/m/Y H:i', strtotime($colorInfo->createdDtm)) : 'N/A'; ?><br>
                                            Última actualización: <?php echo !empty($colorInfo->updatedDtm) ? date('d/m/Y H:i', strtotime($colorInfo->updatedDtm)) : 'N/A'; ?>
                                        </small>
                                    </p>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer" style="padding: 15px 30px 20px;">
                            <a href="<?php echo base_url('color/lista'); ?>" class="btn btn-default">
                                <i class="fa fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary pull-right">
                                <i class="fa fa-save"></i> Guardar cambios
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
