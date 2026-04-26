<?php $meses = array(1 => 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar" aria-hidden="true"></i> Compras mensuales
            <small>Tendencia mensual de compras</small>
        </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Compras mensuales" data-report-subtitle="Comportamiento mensual del año seleccionado">
        <?php
        $reportExportTitle = 'Compras mensuales';
        $reportExportSubtitle = 'Comportamiento mensual del año seleccionado';
        $this->load->view('reporte/partials/report_toolbar');
        ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/reporte_compra_mensual">
                <input type="hidden" name="id_sucursal" value="<?php echo (int) $selectedSucursalId; ?>">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="year">Año</label>
                                <select id="year" class="form-control" name="year">
                                    <?php for ($optionYear = (int) date('Y') - 3; $optionYear <= (int) date('Y') + 1; $optionYear++) { ?>
                                    <option value="<?php echo $optionYear; ?>" <?php echo $optionYear === (int) $year ? 'selected' : ''; ?>>
                                        <?php echo $optionYear; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
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
                        <p>Órdenes del año</p>
                    </div>
                    <div class="icon"><i class="fa fa-file-text-o"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['total'], 2); ?></h3>
                        <p>Total anual</p>
                    </div>
                    <div class="icon"><i class="fa fa-shopping-cart"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['promedio'], 2); ?></h3>
                        <p>Promedio por orden</p>
                    </div>
                    <div class="icon"><i class="fa fa-calculator"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Compras por mes</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="comprasMensualesChart" height="180"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Detalle mensual</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mes</th>
                                    <th>Órdenes</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summary['rows'] as $row) { ?>
                                <tr>
                                    <td><?php echo $meses[(int) $row['mes']]; ?></td>
                                    <td><?php echo (int) $row['ordenes']; ?></td>
                                    <td>$<?php echo number_format($row['total'], 2); ?></td>
                                </tr>
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
new Chart(document.getElementById('comprasMensualesChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_values($meses)); ?>,
        datasets: [{
            label: 'Compras',
            data: <?php echo json_encode(array_map('floatval', array_column($summary['rows'], 'total'))); ?>,
            backgroundColor: 'rgba(243, 156, 18, 0.75)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
