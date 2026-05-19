<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Stock bajo
        <small>Productos con necesidad de reposición</small>
      </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Stock bajo" data-report-subtitle="Productos con necesidad de reposición">
        <?php
        $reportExportTitle = 'Stock bajo';
        $reportExportSubtitle = 'Productos con necesidad de reposición';
        $this->load->view('reporte/partials/report_toolbar');
        ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/stock_bajo">
                <input type="hidden" name="id_sucursal" value="<?php echo $selectedSucursalId; ?>">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Categoría</label>
                                <select class="form-control" name="categoria_id">
                                    <option value="0">Todas</option>
                                    <?php foreach ($categorias as $categoria) { ?>
                                    <option value="<?php echo $categoria->id_categoria; ?>" <?php echo (int) $categoriaId === (int) $categoria->id_categoria ? 'selected' : ''; ?>>
                                        <?php echo $categoria->nombre_categoria; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Producto o código</label>
                                <input type="text" class="form-control" name="producto" value="<?php echo htmlspecialchars($producto, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Máx. stock</label>
                                <input type="number" min="0" class="form-control" name="stock_maximo" value="<?php echo (int) $stockMaximo; ?>">
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
            <div class="col-md-4">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?php echo (int) $summary['totales']['productos']; ?></h3>
                        <p>Productos críticos</p>
                    </div>
                    <div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo number_format($summary['totales']['unidades'], 0); ?></h3>
                        <p>Unidades restantes</p>
                    </div>
                    <div class="icon"><i class="fa fa-cubes"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['valor_inventario'], 2); ?></h3>
                        <p>Valor remanente</p>
                    </div>
                    <div class="icon"><i class="fa fa-money"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Productos más críticos</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="stockBajoChart" height="180"></canvas>
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
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Talla</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Valor</th>
                            </tr>
                            <?php foreach ($summary['rows'] as $row) {
                                $esVar = (int) ($row['tiene_variantes'] ?? 0) === 1;
                                $talla = $esVar && !empty($row['talla']) ? $row['talla'] : '—';
                                $stockNum = (float) $row['stock'];
                                $stockClass = $stockNum <= 0 ? 'text-danger' : 'text-warning';
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['codigo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <?php if ($esVar): ?>
                                        <span class="label label-info"><?php echo htmlspecialchars($talla, ENT_QUOTES, 'UTF-8'); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['nombre_categoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="<?php echo $stockClass; ?>"><strong><?php echo number_format($stockNum, 0); ?></strong></td>
                                <td>$<?php echo number_format($row['valor_inventario'], 2); ?></td>
                            </tr>
                            <?php } ?>
                            <?php if (empty($summary['rows'])) { ?>
                            <tr><td colspan="6" class="text-center">Sin resultados para los filtros seleccionados.</td></tr>
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
var stockCritico = <?php echo json_encode(array_slice($summary['rows'], 0, 8)); ?>;

function rowLabel(item) {
    var base = item.nombre_producto || '';
    if (parseInt(item.tiene_variantes, 10) === 1 && item.talla) {
        return base + ' · ' + item.talla;
    }
    return base;
}

new Chart(document.getElementById('stockBajoChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: stockCritico.map(rowLabel),
        datasets: [{
            label: 'Stock',
            data: stockCritico.map(function(item) { return parseFloat(item.stock); }),
            backgroundColor: 'rgba(221, 75, 57, 0.75)'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true
    }
});
</script>
