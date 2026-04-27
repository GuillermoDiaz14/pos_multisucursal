<?php
$ventas   = isset($movimientos['ventas'])   ? $movimientos['ventas']   : array();
$cuotas   = isset($movimientos['cuotas'])   ? $movimientos['cuotas']   : array();
$gastos   = isset($movimientos['gastos'])   ? $movimientos['gastos']   : array();
$ingresos = isset($movimientos['ingresos']) ? $movimientos['ingresos'] : array();

$totalVentas   = 0.0; foreach ($ventas as $v)   { $totalVentas   += (float)$v->total; }
$totalCuotas   = 0.0; foreach ($cuotas as $c)   { $totalCuotas   += (float)$c->cuota; }
$totalGastos   = 0.0; foreach ($gastos as $g)   { $totalGastos   += (float)$g->monto; }
$totalIngresos = 0.0; foreach ($ingresos as $i) { $totalIngresos += (float)$i->monto; }

$diff = isset($caja->diferencia) ? (float)$caja->diferencia : 0;
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-archive"></i> Detalle caja #<?php echo (int)$caja->id_caja; ?>
            <small><?php echo htmlspecialchars($caja->nombre_sucursal ?: '', ENT_QUOTES); ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>caja/historial"><i class="fa fa-history"></i> Histórico</a></li>
            <li class="active">Caja #<?php echo (int)$caja->id_caja; ?></li>
        </ol>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Detalle caja #<?php echo (int)$caja->id_caja; ?>" data-report-subtitle="Movimientos del turno">
        <?php
        $reportExportTitle = 'Detalle caja #' . (int)$caja->id_caja;
        $reportExportSubtitle = 'Movimientos del turno';
        $this->load->view('reporte/partials/report_toolbar');
        ?>

        <!-- Cabecera -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Información del turno</h3>
                <div class="box-tools pull-right">
                    <?php if ($caja->estado === 'cerrado'): ?>
                        <a href="<?php echo base_url(); ?>caja/reporte_cierre/<?php echo (int)$caja->id_caja; ?>"
                           class="btn btn-default btn-sm"><i class="fa fa-file-text-o"></i> Reporte de cierre</a>
                        <a href="<?php echo base_url(); ?>caja/ticket_cierre/<?php echo (int)$caja->id_caja; ?>"
                           target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Ticket PDF</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-condensed">
                            <tr>
                                <th>Sucursal</th>
                                <td><?php echo htmlspecialchars($caja->nombre_sucursal ?: '—', ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <th>Cajero (apertura)</th>
                                <td><?php echo htmlspecialchars($caja->nombre_usuario_apertura ?: '—', ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <th>Apertura</th>
                                <td><?php echo htmlspecialchars($caja->fecha_apertura, ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <th>Monto apertura</th>
                                <td>$<?php echo number_format((float)$caja->monto_apertura, 2); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-condensed">
                            <tr>
                                <th>Estado</th>
                                <td>
                                    <?php if ($caja->estado === 'abierto'): ?>
                                        <span class="label label-info">Abierta</span>
                                    <?php else: ?>
                                        <span class="label label-default">Cerrada</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Cierre</th>
                                <td><?php echo htmlspecialchars($caja->fecha_cierre ?: '—', ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <th>Cajero (cierre)</th>
                                <td><?php echo htmlspecialchars($caja->nombre_usuario_cierre ?: '—', ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <th>Observaciones</th>
                                <td><?php echo nl2br(htmlspecialchars($caja->observaciones_cierre ?: '—', ENT_QUOTES)); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPIs -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>$<?php echo number_format($totalVentas, 2); ?></h3>
                        <p>Ventas del turno</p>
                    </div>
                    <div class="icon"><i class="fa fa-shopping-cart"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>$<?php echo number_format($totalIngresos, 2); ?></h3>
                        <p>Ingresos extra</p>
                    </div>
                    <div class="icon"><i class="fa fa-plus-circle"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>$<?php echo number_format($totalGastos, 2); ?></h3>
                        <p>Gastos</p>
                    </div>
                    <div class="icon"><i class="fa fa-minus-circle"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="small-box <?php echo $caja->estado === 'cerrado' ? (abs($diff) < 0.01 ? 'bg-green' : ($diff > 0 ? 'bg-yellow' : 'bg-red')) : 'bg-gray'; ?>">
                    <div class="inner">
                        <h3>$<?php echo number_format($diff, 2); ?></h3>
                        <p><?php echo $caja->estado === 'cerrado' ? 'Diferencia (sobrante/faltante)' : 'Caja abierta — sin arqueo'; ?></p>
                    </div>
                    <div class="icon"><i class="fa fa-balance-scale"></i></div>
                </div>
            </div>
        </div>

        <!-- Resumen métodos de pago -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-credit-card"></i> Resumen por método de pago</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table">
                    <thead>
                        <tr><th>Método</th><th class="text-right">Total</th></tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($resumen['detalle_metodos'])): ?>
                            <?php foreach ($resumen['detalle_metodos'] as $nombre => $monto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($nombre, ENT_QUOTES); ?></td>
                                    <td class="text-right">$<?php echo number_format((float)$monto, 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2" class="report-empty">Sin ventas en el turno.</td></tr>
                        <?php endif; ?>
                        <tr class="active">
                            <td><strong>Saldo del sistema (efectivo esperado)</strong></td>
                            <td class="text-right"><strong>$<?php echo number_format((float)$resumen['saldo_sistema'], 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabs de movimientos -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-ventas" data-toggle="tab">Ventas (<?php echo count($ventas); ?>)</a></li>
                <li><a href="#tab-cuotas" data-toggle="tab">Cuotas (<?php echo count($cuotas); ?>)</a></li>
                <li><a href="#tab-gastos" data-toggle="tab">Gastos (<?php echo count($gastos); ?>)</a></li>
                <li><a href="#tab-ingresos" data-toggle="tab">Ingresos (<?php echo count($ingresos); ?>)</a></li>
            </ul>
            <div class="tab-content">
                <!-- Ventas -->
                <div class="tab-pane active" id="tab-ventas">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Método</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($ventas)): ?>
                                    <tr><td colspan="7" class="report-empty">Sin ventas registradas.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($ventas as $v): ?>
                                        <tr>
                                            <td><?php echo (int)$v->id_venta; ?></td>
                                            <td><?php echo htmlspecialchars($v->fecha_venta, ENT_QUOTES); ?></td>
                                            <td><?php echo htmlspecialchars($v->tipo_pago ?: '—', ENT_QUOTES); ?></td>
                                            <td><?php echo htmlspecialchars($v->metodo_pago ?: '—', ENT_QUOTES); ?></td>
                                            <td><?php echo htmlspecialchars($v->cliente ?: '—', ENT_QUOTES); ?></td>
                                            <td><?php echo htmlspecialchars($v->vendedor ?: '—', ENT_QUOTES); ?></td>
                                            <td class="text-right">$<?php echo number_format((float)$v->total, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="active">
                                        <td colspan="6" class="text-right"><strong>Total</strong></td>
                                        <td class="text-right"><strong>$<?php echo number_format($totalVentas, 2); ?></strong></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Cuotas -->
                <div class="tab-pane" id="tab-cuotas">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Venta</th>
                                    <th>Tipo venta</th>
                                    <th>Cliente</th>
                                    <th>Fecha pago</th>
                                    <th class="text-right">Cuota</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($cuotas)): ?>
                                    <tr><td colspan="6" class="report-empty">Sin cuotas (apartado/crédito) registradas.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($cuotas as $c): ?>
                                        <tr>
                                            <td><?php echo (int)$c->id_cuota; ?></td>
                                            <td><?php echo (int)$c->id_venta; ?></td>
                                            <td><?php echo htmlspecialchars($c->tipo_pago ?: '—', ENT_QUOTES); ?></td>
                                            <td><?php echo htmlspecialchars($c->cliente ?: '—', ENT_QUOTES); ?></td>
                                            <td><?php echo htmlspecialchars($c->fecha_pago, ENT_QUOTES); ?></td>
                                            <td class="text-right">$<?php echo number_format((float)$c->cuota, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="active">
                                        <td colspan="5" class="text-right"><strong>Total</strong></td>
                                        <td class="text-right"><strong>$<?php echo number_format($totalCuotas, 2); ?></strong></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Gastos -->
                <div class="tab-pane" id="tab-gastos">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Descripción</th>
                                    <th class="text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($gastos)): ?>
                                    <tr><td colspan="4" class="report-empty">Sin gastos registrados.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($gastos as $g): ?>
                                        <tr>
                                            <td><?php echo (int)$g->id_gasto; ?></td>
                                            <td><?php echo htmlspecialchars($g->fecha, ENT_QUOTES); ?></td>
                                            <td><?php echo htmlspecialchars($g->descripcion, ENT_QUOTES); ?></td>
                                            <td class="text-right text-danger">- $<?php echo number_format((float)$g->monto, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="active">
                                        <td colspan="3" class="text-right"><strong>Total</strong></td>
                                        <td class="text-right text-danger"><strong>- $<?php echo number_format($totalGastos, 2); ?></strong></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Ingresos -->
                <div class="tab-pane" id="tab-ingresos">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Descripción</th>
                                    <th class="text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($ingresos)): ?>
                                    <tr><td colspan="4" class="report-empty">Sin ingresos registrados.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($ingresos as $i): ?>
                                        <tr>
                                            <td><?php echo (int)$i->id_ingreso; ?></td>
                                            <td><?php echo htmlspecialchars($i->fecha, ENT_QUOTES); ?></td>
                                            <td><?php echo htmlspecialchars($i->descripcion, ENT_QUOTES); ?></td>
                                            <td class="text-right text-success">+ $<?php echo number_format((float)$i->monto, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="active">
                                        <td colspan="3" class="text-right"><strong>Total</strong></td>
                                        <td class="text-right text-success"><strong>+ $<?php echo number_format($totalIngresos, 2); ?></strong></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
