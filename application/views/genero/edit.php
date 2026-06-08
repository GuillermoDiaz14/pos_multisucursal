<?php $genero_estado = isset($generoInfo->activa) ? (int)$generoInfo->activa : 1; ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-pencil"></i> Editar Género
            <small>Modificar información del género</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url('genero/lista'); ?>"><i class="fa fa-venus-mars"></i> Géneros</a></li>
            <li class="active"><i class="fa fa-pencil"></i> Editar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar Género</h3>
                    </div>

                    <form id="frm_genero" action="<?php echo base_url('genero/editGenero'); ?>" method="POST" class="form-horizontal">
                        <input type="hidden" name="id_genero" value="<?php echo $generoInfo->id_genero; ?>">

                        <div class="box-body">
                            <div class="form-group">
                                <label for="nombre_genero" class="col-sm-3 control-label">Nombre *</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nombre_genero" name="nombre_genero" maxlength="50" required value="<?php echo htmlspecialchars($generoInfo->nombre_genero, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo form_error('nombre_genero', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="descripcion" class="col-sm-3 control-label">Descripción</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" maxlength="255" placeholder="Opcional"><?php echo htmlspecialchars($generoInfo->descripcion ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    <?php echo form_error('descripcion', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="activa" class="col-sm-3 control-label">Estado</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="activa" name="activa">
                                        <option value="1" <?php echo $genero_estado ? 'selected' : ''; ?>>Activo</option>
                                        <option value="0" <?php echo !$genero_estado ? 'selected' : ''; ?>>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <a href="<?php echo base_url('genero/lista'); ?>" class="btn btn-default">
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
    $('#frm_genero').on('submit', function(e) {
        if (!$.trim($('#nombre_genero').val())) {
            e.preventDefault();
            alert('Por favor ingresa el nombre del género');
            return false;
        }
    });
});
</script>
