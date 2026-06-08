<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-line-chart" aria-hidden="true"></i> Ventas por periodo
            <small>Análisis de tickets y montos por fecha</small>
        </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Ventas por periodo" data-report-subtitle="Tickets y montos del rango seleccionado">
        <?php
        $reportExportTitle = 'Ventas por periodo';
        $reportExportSubtitle = 'Tickets y montos del rango seleccionado';
        $this->load->view('reporte/partials/report_toolbar');
        ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/reporte_venta_por_fecha">
                <input type="hidden" name="id_sucursal" value="<?php echo (int) $selectedSucursalId; ?>">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_inicial">Fecha inicial</label>
                                <input id="fecha_inicial" type="date" class="form-control" name="fecha_inicial" value="<?php echo $fechaInicial; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_final">Fecha final</label>
                                <input id="fecha_final" type="date" class="form-control" name="fecha_final" value="<?php echo $fechaFinal; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="searchText">Cliente o ticket</label>
                                <input id="searchText" type="text" class="form-control" name="searchText" value="<?php echo htmlspecialchars($searchText, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sucursal</label>
                                <input type="text" class="form-control" value="<?php echo $sucursalNombre; ?>" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Aplicar filtros</button>
                </div>
            </form>
        </div>

        <div class="row report-kpi-strip">
            <div class="col-md-3">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo (int) $summary['totales']['tickets']; ?></h3>
                        <p>Tickets</p>
                    </div>
                    <div class="icon"><i class="fa fa-shopping-bag"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['total'], 2); ?></h3>
                        <p>Total vendido</p>
                    </div>
                    <div class="icon"><i class="fa fa-money"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['ticket_promedio'], 2); ?></h3>
                        <p>Ticket promedio</p>
                    </div>
                    <div class="icon"><i class="fa fa-calculator"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['descuento'], 2); ?></h3>
                        <p>Descuentos</p>
                    </div>
                    <div class="icon"><i class="fa fa-tag"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tendencia diaria</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="ventasPeriodoChart" height="180"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Detalle de ventas</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ticket</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Subtotal</th>
                                    <th>Impuesto</th>
                                    <th>Descuento</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summary['rows'] as $row) { ?>
                                <tr>
                                    <td>#<?php echo (int) $row['id_venta']; ?></td>
                                    <td><?php echo fmt_fecha($row['fecha_venta'], false, '-'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_cliente'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>$<?php echo number_format($row['base_imponible'], 2); ?></td>
                                    <td>$<?php echo number_format($row['impuesto'], 2); ?></td>
                                    <td>$<?php echo number_format($row['descuento'], 2); ?></td>
                                    <td>$<?php echo number_format($row['total'], 2); ?></td>
                                </tr>
                                <?php } ?>
                                <?php if (empty($summary['rows'])) { ?>
                                <tr><td colspan="7" class="report-empty">Sin resultados para los filtros seleccionados.</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('ventasPeriodoChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_keys($summary['trend'])); ?>,
        datasets: [{
            label: 'Ventas',
            data: <?php echo json_encode(array_values(array_map('floatval', $summary['trend']))); ?>,
            borderColor: '#0f766e',
            backgroundColor: 'rgba(15, 118, 110, 0.18)',
            fill: true,
            tension: 0.28
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
