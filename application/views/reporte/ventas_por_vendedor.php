<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-users" aria-hidden="true"></i> Ventas por vendedor
        <small>Desempeño comercial por usuario</small>
      </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Ventas por vendedor" data-report-subtitle="Desempeño comercial por usuario">
        <?php
        $reportExportTitle = 'Ventas por vendedor';
        $reportExportSubtitle = 'Desempeño comercial por usuario';
        $this->load->view('reporte/partials/report_toolbar');
        ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/ventas_por_vendedor">
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
                                <label>Vendedor</label>
                                <select class="form-control" name="usuario_id">
                                    <option value="0">Todos</option>
                                    <?php foreach ($usuarios as $usuario) { ?>
                                    <option value="<?php echo $usuario->userId; ?>" <?php echo (int) $usuarioId === (int) $usuario->userId ? 'selected' : ''; ?>>
                                        <?php echo $usuario->name; ?>
                                    </option>
                                    <?php } ?>
                                </select>
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
                        <h3>$<?php echo number_format($summary['totales']['total_vendido'], 2); ?></h3>
                        <p>Ventas</p>
                    </div>
                    <div class="icon"><i class="fa fa-line-chart"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo (int) $summary['totales']['tickets']; ?></h3>
                        <p>Tickets</p>
                    </div>
                    <div class="icon"><i class="fa fa-shopping-bag"></i></div>
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
                        <h3 class="box-title">Top vendedores</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="ventasVendedorChart" height="180"></canvas>
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
                            <tr>
                                <th>Vendedor</th>
                                <th>Tickets</th>
                                <th>Ventas</th>
                                <th>Promedio</th>
                                <th>Utilidad</th>
                            </tr>
                            <?php foreach ($summary['rows'] as $row) { ?>
                            <tr>
                                <td><?php echo $row['vendedor']; ?></td>
                                <td><?php echo (int) $row['tickets']; ?></td>
                                <td>$<?php echo number_format($row['total_vendido'], 2); ?></td>
                                <td>$<?php echo number_format($row['ticket_promedio'], 2); ?></td>
                                <td>$<?php echo number_format($row['utilidad_estimada'], 2); ?></td>
                            </tr>
                            <?php } ?>
                            <?php if (empty($summary['rows'])) { ?>
                            <tr><td colspan="5" class="text-center">Sin resultados para los filtros seleccionados.</td></tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var vendedores = <?php echo json_encode(array_column(array_slice($summary['rows'], 0, 8), 'vendedor')); ?>;
var ventas = <?php echo json_encode(array_map('floatval', array_column(array_slice($summary['rows'], 0, 8), 'total_vendido'))); ?>;

new Chart(document.getElementById('ventasVendedorChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: vendedores,
        datasets: [{
            label: 'Ventas',
            data: ventas,
            backgroundColor: 'rgba(0, 115, 183, 0.7)'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true
    }
});
</script>
