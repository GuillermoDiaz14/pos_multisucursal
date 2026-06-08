<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-venus-mars"></i> Géneros
            <small>Catálogo simple de géneros</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-venus-mars"></i> Géneros</li>
        </ol>
    </section>

    <section class="content">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($table_ready)): ?>
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-triangle"></i> Este catálogo necesita la migración de géneros para habilitar crear, editar y eliminar.
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list"></i> Listado de Géneros</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url('genero/add'); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Agregar Género
                            </a>
                        </div>
                    </div>

                    <div class="box-body">
                        <?php if (empty($generos)): ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No hay géneros registrados.
                            </div>
                        <?php else: ?>
                            <table class="table table-bordered table-striped table-hover">
                                <thead style="background:#f5f5f5;">
                                    <tr>
                                        <th style="width:8%;">ID</th>
                                        <th style="width:24%;">Nombre</th>
                                        <th style="width:44%;">Descripción</th>
                                        <th style="width:10%;">Estado</th>
                                        <th style="width:14%; text-align:center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($generos as $genero): ?>
                                        <tr>
                                            <td><?php echo (int)$genero->id_genero; ?></td>
                                            <td><?php echo htmlspecialchars($genero->nombre_genero, ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <small><?php echo htmlspecialchars($genero->descripcion ?: 'Sin descripción', ENT_QUOTES, 'UTF-8'); ?></small>
                                            </td>
                                            <td>
                                                <span class="label <?php echo !empty($genero->activa) ? 'label-success' : 'label-danger'; ?>">
                                                    <?php echo !empty($genero->activa) ? 'Activo' : 'Inactivo'; ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?php echo base_url('genero/edit/' . $genero->id_genero); ?>" class="btn btn-sm btn-info" title="Editar">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo base_url('genero/delete/' . $genero->id_genero); ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro?');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
