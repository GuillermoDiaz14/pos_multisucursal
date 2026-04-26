<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-exchange" aria-hidden="true"></i> Flujo total
        <small>Vista gerencial del negocio</small>
      </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Flujo total" data-report-subtitle="Vista gerencial del negocio">
        <?php
        $reportExportTitle = 'Flujo total';
        $reportExportSubtitle = 'Vista gerencial del negocio';
        $this->load->view('reporte/partials/report_toolbar');
        ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/flujo_total">
                <input type="hidden" name="id_sucursal" value="<?php echo $selectedSucursalId; ?>">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha inicial</label>
                                <input type="date" class="form-control" name="fecha_inicial" value="<?php echo $fechaInicial; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha final</label>
                                <input type="date" class="form-control" name="fecha_final" value="<?php echo $fechaFinal; ?>">
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

        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['ventas_total'], 2); ?></h3>
                        <p>Ventas totales</p>
                    </div>
                    <div class="icon"><i class="fa fa-line-chart"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['cobrado_efectivo'], 2); ?></h3>
                        <p>Cobrado en efectivo</p>
                    </div>
                    <div class="icon"><i class="fa fa-money"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['cobrado_transferencia'], 2); ?></h3>
                        <p>Cobrado por transferencia</p>
                    </div>
                    <div class="icon"><i class="fa fa-exchange"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['flujo_neto'], 2); ?></h3>
                        <p>Flujo neto</p>
                    </div>
                    <div class="icon"><i class="fa fa-balance-scale"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Composición del flujo</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="flujoTotalChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Resumen del periodo</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr><th>Concepto</th><th>Total</th></tr>
                            <tr><td>Ventas al contado</td><td>$<?php echo number_format($summary['ventas_contado'], 2); ?></td></tr>
                            <tr><td>Ventas a crédito</td><td>$<?php echo number_format($summary['ventas_credito'], 2); ?></td></tr>
                            <tr><td>Ventas en apartado</td><td>$<?php echo number_format($summary['ventas_apartado'], 2); ?></td></tr>
                            <tr><td>Compras</td><td>$<?php echo number_format($summary['compras_total'], 2); ?></td></tr>
                            <tr><td>Ingresos adicionales</td><td>$<?php echo number_format($summary['ingresos'], 2); ?></td></tr>
                            <tr><td>Gastos</td><td>$<?php echo number_format($summary['gastos'], 2); ?></td></tr>
                            <tr><td>Utilidad estimada</td><td><strong>$<?php echo number_format($summary['utilidad_estimada'], 2); ?></strong></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('flujoTotalChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Efectivo', 'Transferencia', 'Crédito', 'Apartado'],
        datasets: [{
            data: [
                <?php echo json_encode((float) $summary['cobrado_efectivo']); ?>,
                <?php echo json_encode((float) $summary['cobrado_transferencia']); ?>,
                <?php echo json_encode((float) $summary['ventas_credito']); ?>,
                <?php echo json_encode((float) $summary['ventas_apartado']); ?>
            ],
            backgroundColor: [
                'rgba(0, 166, 90, 0.75)',
                'rgba(243, 156, 18, 0.75)',
                'rgba(0, 115, 183, 0.75)',
                'rgba(221, 75, 57, 0.75)'
            ]
        }]
    },
    options: {
        responsive: true
    }
});
</script>
