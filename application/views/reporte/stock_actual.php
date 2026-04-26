<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-cubes" aria-hidden="true"></i> Stock actual
        <small>Existencias y valorización del inventario</small>
      </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Stock actual" data-report-subtitle="Existencias y valorización del inventario">
        <?php
        $reportExportTitle = 'Stock actual';
        $reportExportSubtitle = 'Existencias y valorización del inventario';
        $this->load->view('reporte/partials/report_toolbar');
        ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/stock_actual">
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
                        <h3><?php echo (int) $summary['totales']['productos']; ?></h3>
                        <p>Productos</p>
                    </div>
                    <div class="icon"><i class="fa fa-cube"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo number_format($summary['totales']['unidades'], 0); ?></h3>
                        <p>Unidades</p>
                    </div>
                    <div class="icon"><i class="fa fa-sort-numeric-asc"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['valor_inventario'], 2); ?></h3>
                        <p>Valor inventario</p>
                    </div>
                    <div class="icon"><i class="fa fa-money"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?php echo (int) $summary['totales']['stock_bajo']; ?></h3>
                        <p>Stock bajo</p>
                    </div>
                    <div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Menor stock</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="stockActualChart" height="180"></canvas>
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
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Valor</th>
                            </tr>
                            <?php foreach ($summary['rows'] as $row) { ?>
                            <tr>
                                <td><?php echo $row['codigo']; ?></td>
                                <td><?php echo $row['nombre_producto']; ?></td>
                                <td><?php echo $row['nombre_categoria']; ?></td>
                                <td><?php echo number_format($row['stock'], 0); ?></td>
                                <td>$<?php echo number_format($row['valor_inventario'], 2); ?></td>
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
var stockRows = <?php echo json_encode(array_slice($summary['rows'], 0, 8)); ?>;
stockRows.sort(function(a, b) { return parseFloat(a.stock) - parseFloat(b.stock); });

new Chart(document.getElementById('stockActualChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: stockRows.map(function(item) { return item.nombre_producto; }),
        datasets: [{
            label: 'Stock',
            data: stockRows.map(function(item) { return parseFloat(item.stock); }),
            backgroundColor: 'rgba(0, 166, 90, 0.7)'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true
    }
});
</script>
