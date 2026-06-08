<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-paint-brush"></i> Colores
            <small>Gestiona los colores disponibles</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-paint-brush"></i> Colores</li>
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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-list"></i> Listado de Colores
                        </h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url('color/add'); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Agregar Color
                            </a>
                        </div>
                    </div>

                    <div class="box-body">
                        <?php if(empty($colores)): ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No hay colores registrados.
                            </div>
                        <?php else: ?>
                            <table class="table table-bordered table-striped table-hover">
                                <thead style="background: #f5f5f5;">
                                    <tr>
                                        <th style="width: 5%;">ID</th>
                                        <th style="width: 8%;">Color</th>
                                        <th style="width: 35%;">Nombre</th>
                                        <th style="width: 12%;">Estado</th>
                                        <th style="width: 12%;">Productos</th>
                                        <th style="width: 28%; text-align: center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($colores as $color): ?>
                                        <tr>
                                            <td><?php echo $color->id_color; ?></td>
                                            <td style="text-align: center;">
                                                <div style="display: inline-block; width: 30px; height: 30px; border-radius: 4px; 
                                                           border: 1px solid #ddd; background-color: <?php echo htmlspecialchars($color->codigo_hex ?? '#cccccc'); ?>;
                                                           box-shadow: inset 0 0 2px rgba(0,0,0,0.1);"></div>
                                            </td>
                                            <td><?php echo htmlspecialchars($color->nombre_color); ?></td>
                                            <td>
                                                <span class="label <?php echo ($color->activo) ? 'label-success' : 'label-danger'; ?>">
                                                    <?php echo ($color->activo) ? 'Activo' : 'Inactivo'; ?>
                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge badge-primary">0</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="<?php echo base_url('color/edit/' . $color->id_color); ?>" class="btn btn-sm btn-info" title="Editar">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo base_url('color/delete/' . $color->id_color); ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?');" title="Eliminar">
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
