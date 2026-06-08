<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar"></i> Temporadas
            <small>Gestiona las temporadas de tus productos</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-calendar"></i> Temporadas</li>
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
                            <i class="fa fa-list"></i> Listado de Temporadas
                        </h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url('temporada/add'); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Agregar Temporada
                            </a>
                        </div>
                    </div>

                    <div class="box-body">
                        <?php if(empty($temporadas)): ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No hay temporadas registradas.
                            </div>
                        <?php else: ?>
                            <table class="table table-bordered table-striped table-hover">
                                <thead style="background: #f5f5f5;">
                                    <tr>
                                        <th style="width: 8%;">ID</th>
                                        <th style="width: 34%;">Nombre</th>
                                        <th style="width: 28%;">Descripción</th>
                                        <th style="width: 10%;">Estado</th>
                                        <th style="width: 20%; text-align: center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($temporadas as $temp): ?>
                                        <tr>
                                            <td><?php echo $temp->id_temporada; ?></td>
                                            <td><?php echo htmlspecialchars($temp->nombre_temporada); ?></td>
                                            <td>
                                                <small><?php echo htmlspecialchars(substr($temp->descripcion ?? 'Sin descripción', 0, 40)); ?></small>
                                            </td>
                                            <td>
                                                <span class="label <?php echo ($temp->activa) ? 'label-success' : 'label-danger'; ?>">
                                                    <?php echo ($temp->activa) ? 'Activa' : 'Inactiva'; ?>
                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="<?php echo base_url('temporada/edit/' . $temp->id_temporada); ?>" class="btn btn-sm btn-info" title="Editar">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo base_url('temporada/delete/' . $temp->id_temporada); ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?');" title="Eliminar">
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
