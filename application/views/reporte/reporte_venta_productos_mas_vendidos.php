<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-cubes" aria-hidden="true"></i> Productos más vendidos
            <small>Top de demanda y rotación</small>
        </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Productos más vendidos" data-report-subtitle="Ranking de productos del periodo">
        <?php
        $reportExportTitle = 'Productos más vendidos';
        $reportExportSubtitle = 'Ranking de productos del periodo';
        $this->load->view('reporte/partials/report_toolbar');
        ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/reporte_venta_productos_mas_vendidos">
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
            <div class="col-md-4">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo (int) $summary['totales']['productos']; ?></h3>
                        <p>Productos en ranking</p>
                    </div>
                    <div class="icon"><i class="fa fa-cube"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo number_format($summary['totales']['unidades'], 0); ?></h3>
                        <p>Unidades vendidas</p>
                    </div>
                    <div class="icon"><i class="fa fa-sort-numeric-desc"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['total_vendido'], 2); ?></h3>
                        <p>Valor vendido</p>
                    </div>
                    <div class="icon"><i class="fa fa-money"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Top 10 por unidades</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="productosTopChart" height="180"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Detalle</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Unidades</th>
                                    <th>Total vendido</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summary['rows'] as $row) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['codigo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_categoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo number_format($row['unidades'], 0); ?></td>
                                    <td>$<?php echo number_format($row['total_vendido'], 2); ?></td>
                                </tr>
                                <?php } ?>
                                <?php if (empty($summary['rows'])) { ?>
                                <tr><td colspan="5" class="report-empty">Sin resultados para los filtros seleccionados.</td></tr>
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
new Chart(document.getElementById('productosTopChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($summary['rows'], 'nombre_producto')); ?>,
        datasets: [{
            label: 'Unidades',
            data: <?php echo json_encode(array_map('floatval', array_column($summary['rows'], 'unidades'))); ?>,
            backgroundColor: 'rgba(22, 50, 79, 0.82)'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
