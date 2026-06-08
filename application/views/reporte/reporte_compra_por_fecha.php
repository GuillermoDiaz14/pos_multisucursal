<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-shopping-cart" aria-hidden="true"></i> Compras por periodo
            <small>Control de abastecimiento por fecha y proveedor</small>
        </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Compras por periodo" data-report-subtitle="Órdenes y montos del rango seleccionado">
        <?php
        $reportExportTitle = 'Compras por periodo';
        $reportExportSubtitle = 'Órdenes y montos del rango seleccionado';
        $this->load->view('reporte/partials/report_toolbar');
        ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/reporte_compra_por_fecha">
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
                                <label for="searchText">Proveedor o orden</label>
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
            <div class="col-md-4">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo (int) $summary['totales']['ordenes']; ?></h3>
                        <p>Órdenes</p>
                    </div>
                    <div class="icon"><i class="fa fa-file-text-o"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['total'], 2); ?></h3>
                        <p>Total comprado</p>
                    </div>
                    <div class="icon"><i class="fa fa-money"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['promedio'], 2); ?></h3>
                        <p>Orden promedio</p>
                    </div>
                    <div class="icon"><i class="fa fa-calculator"></i></div>
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
                        <canvas id="comprasPeriodoChart" height="180"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Detalle de compras</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Orden</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Nota</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summary['rows'] as $row) { ?>
                                <tr>
                                    <td>#<?php echo (int) $row['id_compra']; ?></td>
                                    <td><?php echo fmt_fecha($row['fecha_compra'], false, '-'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_proveedor'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nota'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>$<?php echo number_format($row['total'], 2); ?></td>
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
new Chart(document.getElementById('comprasPeriodoChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_keys($summary['trend'])); ?>,
        datasets: [{
            label: 'Compras',
            data: <?php echo json_encode(array_values(array_map('floatval', $summary['trend']))); ?>,
            borderColor: '#b45309',
            backgroundColor: 'rgba(180, 83, 9, 0.18)',
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
