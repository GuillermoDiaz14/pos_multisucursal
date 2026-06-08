<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-sitemap"></i> Subcategorías
            <small>Gestiona las subcategorías de tus productos</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-sitemap"></i> Subcategorías</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Éxito!</h4>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Error!</h4>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" style="border-top:3px solid #3c8dbc; box-shadow:0 8px 22px rgba(60,141,188,.08);">
                    <div class="box-header with-border" style="padding:18px 20px;">
                        <h3 class="box-title">
                            <i class="fa fa-list"></i> Listado de Subcategorías
                        </h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url('subcategoria/add'); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Agregar Subcategoría
                            </a>
                        </div>
                    </div>

                    <div class="box-body" style="padding: 18px 20px 20px;">
                        <div class="row" style="margin-bottom: 16px;">
                            <div class="col-md-5 col-sm-6">
                                <label style="margin-bottom:6px;">Filtrar por categoría</label>
                                <select class="form-control" id="filtro_categoria" onchange="location.href='<?php echo base_url('subcategoria/lista?id_categoria='); ?>'+this.value">
                                    <option value="">-- Todas las categorías --</option>
                                    <?php foreach($categorias as $cat): ?>
                                        <option value="<?php echo $cat->id_categoria; ?>" <?php echo ($id_categoria_filtro == $cat->id_categoria) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat->nombre_categoria); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <?php if(empty($subcategorias)): ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No hay subcategorías registradas.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive" style="border:1px solid #eef1f4; border-radius:6px; overflow:hidden;">
                            <table class="table table-bordered table-striped table-hover" style="margin:0;">
                                <thead style="background: #f5f5f5;">
                                    <tr>
                                        <th style="width: 70px;">ID</th>
                                        <th style="width: 180px;">Categoría</th>
                                        <th>Subcategoría</th>
                                        <th style="width: 32%;">Descripción</th>
                                        <th style="width: 110px;">Estado</th>
                                        <th style="width: 120px; text-align:center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($subcategorias as $subcat): ?>
                                        <tr>
                                            <td><strong>#<?php echo $subcat->id_subcategoria; ?></strong></td>
                                            <td>
                                                <span class="badge" style="background: #3498db; padding:6px 10px; font-weight:600;">
                                                    <?php echo htmlspecialchars($subcat->nombre_categoria); ?>
                                                </span>
                                            </td>
                                            <td><strong><?php echo htmlspecialchars($subcat->nombre_subcategoria); ?></strong></td>
                                            <td>
                                                <small class="text-muted"><?php echo htmlspecialchars(substr($subcat->descripcion ?? 'Sin descripción', 0, 70)); ?></small>
                                            </td>
                                            <td>
                                                <span class="label <?php echo ($subcat->activa) ? 'label-success' : 'label-danger'; ?>">
                                                    <?php echo ($subcat->activa) ? 'Activa' : 'Inactiva'; ?>
                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="<?php echo base_url('subcategoria/edit/' . $subcat->id_subcategoria); ?>" class="btn btn-sm btn-info" title="Editar">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo base_url('subcategoria/delete/' . $subcat->id_subcategoria); ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?');" title="Eliminar">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
