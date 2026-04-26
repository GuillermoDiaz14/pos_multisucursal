<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-money" aria-hidden="true"></i> Caja operativa
        <small>Control operativo de efectivo</small>
      </h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/caja_operativa">
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
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['saldo_caja_actual'], 2); ?></h3>
                        <p>Saldo caja actual</p>
                    </div>
                    <div class="icon"><i class="fa fa-money"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['ventas_efectivo'], 2); ?></h3>
                        <p>Ventas en efectivo</p>
                    </div>
                    <div class="icon"><i class="fa fa-credit-card"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['ingresos'], 2); ?></h3>
                        <p>Ingresos manuales</p>
                    </div>
                    <div class="icon"><i class="fa fa-plus-circle"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['gastos'], 2); ?></h3>
                        <p>Gastos</p>
                    </div>
                    <div class="icon"><i class="fa fa-minus-circle"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Resumen diario de caja</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="cajaOperativaChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Totales del periodo</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr><th>Concepto</th><th>Total</th></tr>
                            <tr><td>Ventas en efectivo</td><td>$<?php echo number_format($summary['ventas_efectivo'], 2); ?></td></tr>
                            <tr><td>Ingresos manuales</td><td>$<?php echo number_format($summary['ingresos'], 2); ?></td></tr>
                            <tr><td>Gastos</td><td>$<?php echo number_format($summary['gastos'], 2); ?></td></tr>
                            <tr><td>Neto operativo</td><td><strong>$<?php echo number_format($summary['neto_operativo'], 2); ?></strong></td></tr>
                            <tr><td>Estado de caja</td><td><?php echo $summary['estado_caja']; ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var cajaTrend = <?php echo json_encode($dailyTrend); ?>;
var cajaLabels = cajaTrend.map(function(item) { return item.fecha; });
var ventasEfectivo = cajaTrend.map(function(item) { return parseFloat(item.ventas_efectivo); });
var gastos = cajaTrend.map(function(item) { return parseFloat(item.gastos); });
var ingresos = cajaTrend.map(function(item) { return parseFloat(item.ingresos); });

new Chart(document.getElementById('cajaOperativaChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: cajaLabels,
        datasets: [
            {
                label: 'Ventas efectivo',
                data: ventasEfectivo,
                backgroundColor: 'rgba(0, 166, 90, 0.6)'
            },
            {
                label: 'Ingresos',
                data: ingresos,
                backgroundColor: 'rgba(243, 156, 18, 0.6)'
            },
            {
                label: 'Gastos',
                data: gastos,
                backgroundColor: 'rgba(221, 75, 57, 0.6)'
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
