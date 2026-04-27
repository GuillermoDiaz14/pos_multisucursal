<?php
$diff = isset($caja->diferencia) ? (float)$caja->diferencia : 0;
$esperado = isset($caja->efectivo_esperado) ? (float)$caja->efectivo_esperado : (float)$resumen['saldo_sistema'];
$contado  = isset($caja->efectivo_contado)  ? (float)$caja->efectivo_contado  : 0;
$cerrada = ($caja->estado === 'cerrado');
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-file-text-o"></i> Reporte de cierre
            <small>Caja #<?php echo (int)$caja->id_caja; ?> — <?php echo htmlspecialchars($caja->nombre_sucursal ?: '', ENT_QUOTES); ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>caja/historial"><i class="fa fa-history"></i> Histórico</a></li>
            <li><a href="<?php echo base_url(); ?>caja/detalle/<?php echo (int)$caja->id_caja; ?>">Caja #<?php echo (int)$caja->id_caja; ?></a></li>
            <li class="active">Reporte de cierre</li>
        </ol>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Reporte de cierre — Caja #<?php echo (int)$caja->id_caja; ?>" data-report-subtitle="Consolidado del turno">
        <?php
        $reportExportTitle = 'Reporte de cierre — Caja #' . (int)$caja->id_caja;
        $reportExportSubtitle = 'Consolidado del turno';
        $this->load->view('reporte/partials/report_toolbar');
        ?>

        <?php if (!$cerrada): ?>
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-triangle"></i>
                Esta caja está <strong>abierta</strong>. Lo que ves es un <em>pre-cierre</em> con valores actuales.
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Datos del turno</h3>
                    </div>
                    <div class="box-body">
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
                            <?php if ($cerrada): ?>
                            <tr>
                                <th>Cajero (cierre)</th>
                                <td><?php echo htmlspecialchars($caja->nombre_usuario_cierre ?: '—', ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <th>Cierre</th>
                                <td><?php echo htmlspecialchars($caja->fecha_cierre, ENT_QUOTES); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box <?php echo $cerrada ? (abs($diff) < 0.01 ? 'box-success' : 'box-warning') : 'box-default'; ?>">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-balance-scale"></i> Arqueo
                            <?php if ($cerrada): ?>
                                <?php if (abs($diff) < 0.01): ?>
                                    <span class="label label-success">Cuadrada</span>
                                <?php elseif ($diff > 0): ?>
                                    <span class="label label-warning">Sobrante</span>
                                <?php else: ?>
                                    <span class="label label-danger">Faltante</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="label label-info">Pre-cierre</span>
                            <?php endif; ?>
                        </h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-condensed">
                            <tr>
                                <th>Efectivo esperado</th>
                                <td class="text-right">$<?php echo number_format($esperado, 2); ?></td>
                            </tr>
                            <?php if ($cerrada): ?>
                            <tr>
                                <th>Efectivo contado</th>
                                <td class="text-right">$<?php echo number_format($contado, 2); ?></td>
                            </tr>
                            <tr class="<?php echo abs($diff) < 0.01 ? 'success' : ($diff > 0 ? 'warning' : 'danger'); ?>">
                                <th>Diferencia</th>
                                <td class="text-right"><strong>$<?php echo number_format($diff, 2); ?></strong></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                        <?php if ($cerrada && !empty($caja->observaciones_cierre)): ?>
                            <div class="callout callout-info" style="margin-top:8px;">
                                <h4>Observaciones del cierre</h4>
                                <p style="white-space:pre-wrap;"><?php echo htmlspecialchars($caja->observaciones_cierre, ENT_QUOTES); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="box-footer">
                        <a href="<?php echo base_url(); ?>caja/ticket_cierre/<?php echo (int)$caja->id_caja; ?>"
                           target="_blank" class="btn btn-default">
                            <i class="fa fa-print"></i> Imprimir ticket PDF
                        </a>
                        <a href="<?php echo base_url(); ?>caja/detalle/<?php echo (int)$caja->id_caja; ?>"
                           class="btn btn-primary">
                            <i class="fa fa-search"></i> Ver movimientos
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Movimientos del turno -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list-alt"></i> Movimientos del turno</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <thead>
                        <tr><th>Concepto</th><th class="text-right">Total</th></tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($resumen['detalle_metodos'])): ?>
                            <?php foreach ($resumen['detalle_metodos'] as $nombre => $monto): ?>
                                <tr>
                                    <td>Ventas: <?php echo htmlspecialchars($nombre, ENT_QUOTES); ?></td>
                                    <td class="text-right">$<?php echo number_format((float)$monto, 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2" class="report-empty">Sin ventas registradas.</td></tr>
                        <?php endif; ?>
                        <tr>
                            <td>Ingresos extra</td>
                            <td class="text-right text-success">+ $<?php echo number_format((float)$resumen['ingresos'], 2); ?></td>
                        </tr>
                        <tr>
                            <td>Gastos</td>
                            <td class="text-right text-danger">- $<?php echo number_format((float)$resumen['gastos'], 2); ?></td>
                        </tr>
                        <tr class="active">
                            <td><strong>Saldo del sistema (efectivo esperado)</strong></td>
                            <td class="text-right"><strong>$<?php echo number_format((float)$resumen['saldo_sistema'], 2); ?></strong></td>
                        </tr>
                        <?php if ((float)$resumen['ventas_otros_metodos'] > 0): ?>
                        <tr>
                            <td colspan="2" class="text-muted small">
                                <em>Nota:</em> ventas en métodos no efectivo
                                ($<?php echo number_format((float)$resumen['ventas_otros_metodos'], 2); ?>)
                                no se contabilizan en el efectivo esperado.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
