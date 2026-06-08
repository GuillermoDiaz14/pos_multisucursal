<div class="content-wrapper">
<?php
$temporada_creada = !empty($temporadaInfo->fecha_creacion) ? $temporadaInfo->fecha_creacion : (!empty($temporadaInfo->createdDtm) ? $temporadaInfo->createdDtm : null);
$temporada_actualizada = !empty($temporadaInfo->fecha_actualizacion) ? $temporadaInfo->fecha_actualizacion : (!empty($temporadaInfo->updatedDtm) ? $temporadaInfo->updatedDtm : null);
?>
    <section class="content-header">
        <h1>
            <i class="fa fa-pencil"></i> Editar Temporada
            <small>Modificar información de la temporada</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url('temporada/lista'); ?>"><i class="fa fa-calendar"></i> Temporadas</a></li>
            <li class="active"><i class="fa fa-pencil"></i> Editar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar Temporada</h3>
                    </div>

                    <form id="frm_temporada" action="<?php echo base_url('temporada/editTemporada'); ?>" method="POST" class="form-horizontal">
                        <input type="hidden" name="id_temporada" value="<?php echo $temporadaInfo->id_temporada; ?>">

                        <div class="box-body">

                            <div class="form-group">
                                <label for="nombre_temporada" class="col-sm-3 control-label">Nombre *</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nombre_temporada" name="nombre_temporada" 
                                           placeholder="Nombre de la temporada" maxlength="100" required
                                           value="<?php echo htmlspecialchars($temporadaInfo->nombre_temporada); ?>">
                                    <?php echo form_error('nombre_temporada', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="descripcion" class="col-sm-3 control-label">Descripción</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="descripcion" name="descripcion" 
                                              rows="3" placeholder="Descripción opcional..." maxlength="255"><?php echo htmlspecialchars($temporadaInfo->descripcion ?? ''); ?></textarea>
                                    <?php echo form_error('descripcion', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="activa" class="col-sm-3 control-label">Estado</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="activa" name="activa">
                                        <option value="1" <?php echo ($temporadaInfo->activa) ? 'selected' : ''; ?>>Activa</option>
                                        <option value="0" <?php echo (!$temporadaInfo->activa) ? 'selected' : ''; ?>>Inactiva</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Información</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">
                                        <small class="text-muted">
                                            Creada: <?php echo $temporada_creada ? date('d/m/Y H:i', strtotime($temporada_creada)) : 'N/A'; ?><br>
                                            Última actualización: <?php echo $temporada_actualizada ? date('d/m/Y H:i', strtotime($temporada_actualizada)) : 'N/A'; ?>
                                        </small>
                                    </p>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer">
                            <a href="<?php echo base_url('temporada/lista'); ?>" class="btn btn-default">
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
