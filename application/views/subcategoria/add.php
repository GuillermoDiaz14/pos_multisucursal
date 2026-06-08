<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus"></i> Agregar Subcategoría
            <small>Crear una nueva subcategoría</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url('subcategoria/lista'); ?>"><i class="fa fa-sitemap"></i> Subcategorías</a></li>
            <li class="active"><i class="fa fa-plus"></i> Agregar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Formulario de Subcategoría</h3>
                    </div>

                    <form id="frm_subcategoria" action="<?php echo base_url('subcategoria/addNewSubcategoria'); ?>" method="POST" class="form-horizontal">
                        <div class="box-body">

                            <div class="form-group">
                                <label for="id_categoria" class="col-sm-3 control-label">Categoría *</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="id_categoria" name="id_categoria" required>
                                        <option value="">-- Selecciona una categoría --</option>
                                        <?php foreach($categorias as $cat): ?>
                                            <option value="<?php echo $cat->id_categoria; ?>">
                                                <?php echo htmlspecialchars($cat->nombre_categoria); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('id_categoria', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nombre_subcategoria" class="col-sm-3 control-label">Nombre *</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nombre_subcategoria" name="nombre_subcategoria" 
                                           placeholder="Ej: Camisetas, Pantalones, Accesorios..." maxlength="200" required
                                           value="<?php echo set_value('nombre_subcategoria'); ?>">
                                    <?php echo form_error('nombre_subcategoria', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="descripcion" class="col-sm-3 control-label">Descripción</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="descripcion" name="descripcion" 
                                              rows="4" placeholder="Descripción opcional..." maxlength="500"><?php echo set_value('descripcion'); ?></textarea>
                                    <?php echo form_error('descripcion', '<span class="help-block text-danger">', '</span>'); ?>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer">
                            <a href="<?php echo base_url('subcategoria/lista'); ?>" class="btn btn-default">
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
    $('#frm_subcategoria').on('submit', function(e) {
        var nombre = $.trim($('#nombre_subcategoria').val());
        if (!nombre) {
            e.preventDefault();
            alert('Por favor completa todos los campos requeridos');
            return false;
        }
    });
});
</script>
