<?php
$flashError = $this->session->flashdata('error');
$flashSuccess = $this->session->flashdata('success');
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-history"></i> Histórico de cajas
            <small>Faltante / sobrante por cajero</small>
        </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Histórico de cajas" data-report-subtitle="Faltante / sobrante por cajero">
        <?php
        $reportExportTitle = 'Histórico de cajas';
        $reportExportSubtitle = 'Faltante / sobrante por cajero';
        $this->load->view('reporte/partials/report_toolbar');
        ?>

        <?php if ($flashError): ?>
            <div class="alert alert-danger"><?php echo $flashError; ?></div>
        <?php endif; ?>
        <?php if ($flashSuccess): ?>
            <div class="alert alert-success"><?php echo $flashSuccess; ?></div>
        <?php endif; ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>caja/historial">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha inicial (apertura)</label>
                                <input type="date" class="form-control" name="fecha_inicial" value="<?php echo htmlspecialchars($fechaInicial, ENT_QUOTES); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha final (apertura)</label>
                                <input type="date" class="form-control" name="fecha_final" value="<?php echo htmlspecialchars($fechaFinal, ENT_QUOTES); ?>">
                            </div>
                        </div>
                        <?php if (!empty($canViewAll)): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sucursal</label>
                                <select name="id_sucursal" class="form-control">
                                    <option value="0">Todas las sucursales</option>
                                    <?php foreach ($sucursales as $s): ?>
                                        <option value="<?php echo (int)$s->id_sucursal; ?>"
                                            <?php echo ((int)$selectedSucursal === (int)$s->id_sucursal) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($s->nombre_sucursal, ENT_QUOTES); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cajero</label>
                                <select name="id_usuario" class="form-control">
                                    <option value="0">Todos</option>
                                    <?php foreach ($cajeros as $c): ?>
                                        <option value="<?php echo (int)$c->userId; ?>"
                                            <?php echo ((int)$idUsuarioFiltro === (int)$c->userId) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($c->name, ENT_QUOTES); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Estado</label>
                                <select name="estado" class="form-control">
                                    <option value="" <?php echo $estadoFiltro === '' ? 'selected' : ''; ?>>Todos</option>
                                    <option value="cerrado" <?php echo $estadoFiltro === 'cerrado' ? 'selected' : ''; ?>>Cerradas</option>
                                    <option value="abierto" <?php echo $estadoFiltro === 'abierto' ? 'selected' : ''; ?>>Abiertas</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Aplicar filtros</button>
                    <a href="<?php echo base_url(); ?>caja/historial" class="btn btn-default">Limpiar</a>
                </div>
            </form>
        </div>

        <!-- KPIs -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo (int)$resumen['total_cajas']; ?></h3>
                        <p>Cajas en el periodo</p>
                    </div>
                    <div class="icon"><i class="fa fa-archive"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>$<?php echo number_format((float)$resumen['suma_sobrante'], 2); ?></h3>
                        <p>Sobrantes acumulados</p>
                    </div>
                    <div class="icon"><i class="fa fa-arrow-up"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>$<?php echo number_format((float)$resumen['suma_faltante'], 2); ?></h3>
                        <p>Faltantes acumulados</p>
                    </div>
                    <div class="icon"><i class="fa fa-arrow-down"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="small-box <?php echo $resumen['neto_diferencia'] >= 0 ? 'bg-green' : 'bg-red'; ?>">
                    <div class="inner">
                        <h3>$<?php echo number_format((float)$resumen['neto_diferencia'], 2); ?></h3>
                        <p>Neto (sobrante − faltante)</p>
                    </div>
                    <div class="icon"><i class="fa fa-balance-scale"></i></div>
                </div>
            </div>
        </div>

        <!-- Resumen por cajero -->
        <?php if (!empty($resumen['por_cajero'])): ?>
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-user"></i> Diferencia por cajero</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Cajero</th>
                            <th class="text-right">Cajas</th>
                            <th class="text-right">Sobrante</th>
                            <th class="text-right">Faltante</th>
                            <th class="text-right">Neto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resumen['por_cajero'] as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['cajero'], ENT_QUOTES); ?></td>
                            <td class="text-right"><?php echo (int)$row['cajas']; ?></td>
                            <td class="text-right text-success">$<?php echo number_format((float)$row['sobrante'], 2); ?></td>
                            <td class="text-right text-danger">$<?php echo number_format((float)$row['faltante'], 2); ?></td>
                            <td class="text-right <?php echo $row['neto'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                <strong>$<?php echo number_format((float)$row['neto'], 2); ?></strong>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Listado -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Cajas (<?php echo count($records); ?>)</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Apertura</th>
                            <th>Cierre</th>
                            <th>Sucursal</th>
                            <th>Cajero</th>
                            <th class="text-right">Apertura</th>
                            <th class="text-right">Esperado</th>
                            <th class="text-right">Contado</th>
                            <th class="text-right">Diferencia</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($records)): ?>
                            <tr><td colspan="11" class="report-empty">No se encontraron cajas con los filtros aplicados.</td></tr>
                        <?php else: ?>
                            <?php foreach ($records as $r):
                                $diff = (float)$r->diferencia;
                                if ($r->estado !== 'cerrado') {
                                    $badgeDiff = '<span class="text-muted">—</span>';
                                } elseif (abs($diff) < 0.01) {
                                    $badgeDiff = '<span class="label label-success">Cuadrada</span>';
                                } elseif ($diff > 0) {
                                    $badgeDiff = '<span class="label label-warning">Sobrante $' . number_format($diff, 2) . '</span>';
                                } else {
                                    $badgeDiff = '<span class="label label-danger">Faltante $' . number_format(abs($diff), 2) . '</span>';
                                }
                            ?>
                            <tr>
                                <td><?php echo (int)$r->id_caja; ?></td>
                                <td><?php echo fmt_fecha($r->fecha_apertura, true); ?></td>
                                <td><?php echo $r->fecha_cierre ? fmt_fecha($r->fecha_cierre, true) : '—'; ?></td>
                                <td><?php echo htmlspecialchars($r->nombre_sucursal ?: '—', ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($r->nombre_usuario_apertura ?: '—', ENT_QUOTES); ?></td>
                                <td class="text-right">$<?php echo number_format((float)$r->monto_apertura, 2); ?></td>
                                <td class="text-right">
                                    <?php if ($r->efectivo_esperado !== null): ?>
                                        $<?php echo number_format((float)$r->efectivo_esperado, 2); ?>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">
                                    <?php if ($r->efectivo_contado !== null): ?>
                                        $<?php echo number_format((float)$r->efectivo_contado, 2); ?>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right"><?php echo $badgeDiff; ?></td>
                                <td>
                                    <?php if ($r->estado === 'abierto'): ?>
                                        <span class="label label-info">Abierta</span>
                                    <?php else: ?>
                                        <span class="label label-default">Cerrada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo base_url(); ?>caja/detalle/<?php echo (int)$r->id_caja; ?>"
                                       class="btn btn-xs btn-primary" title="Ver movimientos">
                                       <i class="fa fa-search"></i> Detalle
                                    </a>
                                    <?php if ($r->estado === 'cerrado'): ?>
                                    <a href="<?php echo base_url(); ?>caja/reporte_cierre/<?php echo (int)$r->id_caja; ?>"
                                       class="btn btn-xs btn-default" title="Reporte de cierre">
                                       <i class="fa fa-file-text-o"></i>
                                    </a>
                                    <a href="<?php echo base_url(); ?>caja/ticket_cierre/<?php echo (int)$r->id_caja; ?>"
                                       target="_blank" class="btn btn-xs btn-default" title="Ticket PDF">
                                       <i class="fa fa-print"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer text-muted small">
                Se muestran hasta 500 cajas. Refina con los filtros si necesitas un periodo mayor.
            </div>
        </div>
    </section>
</div>
