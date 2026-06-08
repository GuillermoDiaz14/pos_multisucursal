<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-pencil"></i> Editar Subcategoría
            <small>Modificar información de la subcategoría</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url('subcategoria/lista'); ?>"><i class="fa fa-sitemap"></i> Subcategorías</a></li>
            <li class="active"><i class="fa fa-pencil"></i> Editar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar Subcategoría</h3>
                    </div>

                    <form id="frm_subcategoria" action="<?php echo base_url('subcategoria/editSubcategoria'); ?>" method="POST" class="form-horizontal">
                        <input type="hidden" name="id_subcategoria" value="<?php echo $subcategoriaInfo->id_subcategoria; ?>">

                        <div class="box-body">

                            <div class="form-group">
                                <label for="nombre_subcategoria" class="col-sm-3 control-label">Nombre *</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nombre_subcategoria" name="nombre_subcategoria" 
                                           placeholder="Nombre de la subcategoría" maxlength="200" required
                                           value="<?php echo htmlspecialchars($subcategoriaInfo->nombre_subcategoria); ?>">
                                    <?php echo form_error('nombre_subcategoria', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="descripcion" class="col-sm-3 control-label">Descripción</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="descripcion" name="descripcion" 
                                              rows="4" placeholder="Descripción opcional..." maxlength="500"><?php echo htmlspecialchars($subcategoriaInfo->descripcion ?? ''); ?></textarea>
                                    <?php echo form_error('descripcion', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="activa" class="col-sm-3 control-label">Estado</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="activa" name="activa">
                                        <option value="1" <?php echo ($subcategoriaInfo->activa) ? 'selected' : ''; ?>>Activa</option>
                                        <option value="0" <?php echo (!$subcategoriaInfo->activa) ? 'selected' : ''; ?>>Inactiva</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Información</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">
                                        <small class="text-muted">
                                            Creada: <?php echo date('d/m/Y H:i', strtotime($subcategoriaInfo->fecha_creacion)); ?><br>
                                            Última actualización: <?php echo date('d/m/Y H:i', strtotime($subcategoriaInfo->fecha_actualizacion)); ?>
                                        </small>
                                    </p>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer">
                            <a href="<?php echo base_url('subcategoria/lista'); ?>" class="btn btn-default">
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
    $('#frm_subcategoria').on('submit', function(e) {
        var nombre = $.trim($('#nombre_subcategoria').val());
        if (!nombre) {
            e.preventDefault();
            alert('Por favor completa los campos requeridos');
            return false;
        }
    });
});
</script>
