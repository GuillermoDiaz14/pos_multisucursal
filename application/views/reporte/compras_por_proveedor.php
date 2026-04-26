<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-industry" aria-hidden="true"></i> Compras por proveedor
        <small>Concentrado de abastecimiento por proveedor</small>
      </h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/compras_por_proveedor">
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
                                <label>Proveedor</label>
                                <select class="form-control" name="proveedor_id">
                                    <option value="0">Todos</option>
                                    <?php foreach ($proveedores as $proveedor) { ?>
                                    <option value="<?php echo $proveedor->id_proveedor; ?>" <?php echo (int) $proveedorId === (int) $proveedor->id_proveedor ? 'selected' : ''; ?>>
                                        <?php echo $proveedor->nombre; ?>
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
                        <h3>$<?php echo number_format($summary['totales']['total_comprado'], 2); ?></h3>
                        <p>Total comprado</p>
                    </div>
                    <div class="icon"><i class="fa fa-shopping-cart"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo (int) $summary['totales']['ordenes']; ?></h3>
                        <p>Órdenes</p>
                    </div>
                    <div class="icon"><i class="fa fa-file-text-o"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['compra_promedio'], 2); ?></h3>
                        <p>Compra promedio</p>
                    </div>
                    <div class="icon"><i class="fa fa-calculator"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?php echo (int) $summary['totales']['proveedores']; ?></h3>
                        <p>Proveedores</p>
                    </div>
                    <div class="icon"><i class="fa fa-truck"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Top proveedores</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="comprasProveedorChart" height="180"></canvas>
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
                                <th>Proveedor</th>
                                <th>Órdenes</th>
                                <th>Total</th>
                                <th>Promedio</th>
                            </tr>
                            <?php foreach ($summary['rows'] as $row) { ?>
                            <tr>
                                <td><?php echo $row['proveedor'] ?: 'Sin proveedor'; ?></td>
                                <td><?php echo (int) $row['ordenes']; ?></td>
                                <td>$<?php echo number_format($row['total_comprado'], 2); ?></td>
                                <td>$<?php echo number_format($row['compra_promedio'], 2); ?></td>
                            </tr>
                            <?php } ?>
                            <?php if (empty($summary['rows'])) { ?>
                            <tr><td colspan="4" class="text-center">Sin resultados para los filtros seleccionados.</td></tr>
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
var proveedores = <?php echo json_encode(array_column(array_slice($summary['rows'], 0, 8), 'proveedor')); ?>;
var compras = <?php echo json_encode(array_map('floatval', array_column(array_slice($summary['rows'], 0, 8), 'total_comprado'))); ?>;

new Chart(document.getElementById('comprasProveedorChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: proveedores,
        datasets: [{
            label: 'Compras',
            data: compras,
            backgroundColor: 'rgba(243, 156, 18, 0.7)'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true
    }
});
</script>
