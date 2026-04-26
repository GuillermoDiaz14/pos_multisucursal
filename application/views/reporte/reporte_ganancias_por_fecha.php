<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-area-chart" aria-hidden="true"></i> Utilidad estimada
            <small>Estimación con costo actual del producto</small>
        </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Utilidad estimada" data-report-subtitle="Estimación basada en costo y precio actual">
        <?php
        $reportExportTitle = 'Utilidad estimada';
        $reportExportSubtitle = 'Estimación basada en costo y precio actual';
        $this->load->view('reporte/partials/report_toolbar');
        ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/reporte_ganancias_por_fecha">
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
                        <h3><?php echo number_format($summary['totales']['cantidad'], 0); ?></h3>
                        <p>Unidades</p>
                    </div>
                    <div class="icon"><i class="fa fa-cubes"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['costo_total'], 2); ?></h3>
                        <p>Costo estimado</p>
                    </div>
                    <div class="icon"><i class="fa fa-shopping-basket"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['venta_total'], 2); ?></h3>
                        <p>Venta estimada</p>
                    </div>
                    <div class="icon"><i class="fa fa-line-chart"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['utilidad_estimada'], 2); ?></h3>
                        <p>Utilidad estimada</p>
                    </div>
                    <div class="icon"><i class="fa fa-money"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Productos con mayor utilidad</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="utilidadChart" height="180"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Detalle por producto</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Unidades</th>
                                    <th>Costo</th>
                                    <th>Venta</th>
                                    <th>Utilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summary['rows'] as $row) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['codigo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo number_format($row['cantidad'], 0); ?></td>
                                    <td>$<?php echo number_format($row['costo_total'], 2); ?></td>
                                    <td>$<?php echo number_format($row['venta_total'], 2); ?></td>
                                    <td>$<?php echo number_format($row['utilidad_estimada'], 2); ?></td>
                                </tr>
                                <?php } ?>
                                <?php if (empty($summary['rows'])) { ?>
                                <tr><td colspan="6" class="report-empty">Sin resultados para los filtros seleccionados.</td></tr>
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
new Chart(document.getElementById('utilidadChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_slice(array_column($summary['rows'], 'nombre_producto'), 0, 10)); ?>,
        datasets: [{
            label: 'Utilidad',
            data: <?php echo json_encode(array_slice(array_map('floatval', array_column($summary['rows'], 'utilidad_estimada')), 0, 10)); ?>,
            backgroundColor: 'rgba(0, 166, 90, 0.78)'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
