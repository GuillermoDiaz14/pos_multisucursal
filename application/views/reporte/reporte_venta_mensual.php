<?php $meses = array(1 => 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar" aria-hidden="true"></i> Ventas mensuales
            <small>Tendencia mensual de ventas</small>
        </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Ventas mensuales" data-report-subtitle="Comportamiento mensual del año seleccionado">
        <?php
        $reportExportTitle = 'Ventas mensuales';
        $reportExportSubtitle = 'Comportamiento mensual del año seleccionado';
        $this->load->view('reporte/partials/report_toolbar');
        ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/reporte_venta_mensual">
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
                        <h3><?php echo (int) $summary['totales']['tickets']; ?></h3>
                        <p>Tickets del año</p>
                    </div>
                    <div class="icon"><i class="fa fa-ticket"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['total'], 2); ?></h3>
                        <p>Total anual</p>
                    </div>
                    <div class="icon"><i class="fa fa-line-chart"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-yellow">
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
                        <h3 class="box-title">Ventas por mes</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="ventasMensualesChart" height="180"></canvas>
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
                                    <th>Tickets</th>
                                    <th>Subtotal</th>
                                    <th>Impuesto</th>
                                    <th>Descuento</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summary['rows'] as $row) { ?>
                                <tr>
                                    <td><?php echo $meses[(int) $row['mes']]; ?></td>
                                    <td><?php echo (int) $row['tickets']; ?></td>
                                    <td>$<?php echo number_format($row['subtotal'], 2); ?></td>
                                    <td>$<?php echo number_format($row['impuesto'], 2); ?></td>
                                    <td>$<?php echo number_format($row['descuento'], 2); ?></td>
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
new Chart(document.getElementById('ventasMensualesChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_values($meses)); ?>,
        datasets: [{
            label: 'Ventas',
            data: <?php echo json_encode(array_map('floatval', array_column($summary['rows'], 'total'))); ?>,
            backgroundColor: 'rgba(0, 115, 183, 0.75)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
