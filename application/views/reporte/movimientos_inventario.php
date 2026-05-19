<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-list-alt" aria-hidden="true"></i> Movimientos de inventario
            <small>Entradas y salidas por producto</small>
        </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Movimientos de inventario" data-report-subtitle="Entradas y salidas por producto">
        <?php
        $reportExportTitle = 'Movimientos de inventario';
        $reportExportSubtitle = 'Entradas y salidas por producto';
        $this->load->view('reporte/partials/report_toolbar');
        ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/movimientos_inventario" id="formMovimientos">
                <input type="hidden" name="id_sucursal" value="<?php echo (int) $selectedSucursalId; ?>">
                <input type="hidden" name="id_producto" id="id_producto" value="<?php echo (int) $idProducto; ?>">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Producto</label>
                                <div style="position:relative;">
                                    <input type="text" id="productoBusqueda" class="form-control" autocomplete="off"
                                           placeholder="Código o nombre"
                                           value="<?php echo $producto ? htmlspecialchars($producto['nombre_producto'] . ' (' . $producto['codigo'] . ')', ENT_QUOTES, 'UTF-8') : ''; ?>">
                                    <div id="productoResultados" class="list-group" style="position:absolute;z-index:1000;width:100%;display:none;max-height:240px;overflow-y:auto;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Talla</label>
                                <select class="form-control" name="id_variante" id="id_variante" <?php echo (!$producto || (int) $producto['tiene_variantes'] !== 1) ? 'disabled' : ''; ?>>
                                    <option value="0">Todas</option>
                                    <?php if ($producto && (int) $producto['tiene_variantes'] === 1): foreach ($producto['variantes'] as $v): ?>
                                        <option value="<?php echo (int) $v['id_variante']; ?>" <?php echo (int) $idVariante === (int) $v['id_variante'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($v['talla'], ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Fecha inicial</label>
                                <input type="date" class="form-control" name="fecha_inicial" value="<?php echo $fechaInicial; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Fecha final</label>
                                <input type="date" class="form-control" name="fecha_final" value="<?php echo $fechaFinal; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Sucursal</label>
                                <input type="text" class="form-control" value="<?php echo $sucursalNombre; ?>" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="btnAplicar" <?php echo $idProducto > 0 ? '' : 'disabled'; ?>>Aplicar filtros</button>
                    <?php if ($idProducto > 0): ?>
                        <a href="<?php echo base_url(); ?>reporte/movimientos_inventario?id_sucursal=<?php echo (int) $selectedSucursalId; ?>" class="btn btn-default">Limpiar</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <?php if (!$producto): ?>
            <div class="callout callout-info">
                <h4><i class="fa fa-info-circle"></i> Selecciona un producto</h4>
                <p>Busca un producto por código o nombre para ver el historial de movimientos en la sucursal seleccionada.</p>
            </div>
        <?php else: ?>

        <div class="row report-kpi-strip">
            <div class="col-md-3">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo number_format($summary['totales']['saldo_inicial'], 0); ?></h3>
                        <p>Saldo inicial</p>
                    </div>
                    <div class="icon"><i class="fa fa-flag-o"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>+<?php echo number_format($summary['totales']['entradas'], 0); ?></h3>
                        <p>Entradas</p>
                    </div>
                    <div class="icon"><i class="fa fa-arrow-down"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>-<?php echo number_format($summary['totales']['salidas'], 0); ?></h3>
                        <p>Salidas</p>
                    </div>
                    <div class="icon"><i class="fa fa-arrow-up"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo number_format($summary['totales']['saldo_final'], 0); ?></h3>
                        <p>Saldo final</p>
                    </div>
                    <div class="icon"><i class="fa fa-flag-checkered"></i></div>
                </div>
            </div>
        </div>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?php echo htmlspecialchars($producto['nombre_producto'], ENT_QUOTES, 'UTF-8'); ?>
                    <small>· <?php echo htmlspecialchars($producto['codigo'], ENT_QUOTES, 'UTF-8'); ?></small>
                    <?php if ((int) $producto['tiene_variantes'] === 1 && $idVariante > 0):
                        foreach ($producto['variantes'] as $v):
                            if ((int) $v['id_variante'] === (int) $idVariante): ?>
                            · <span class="label label-info">Talla <?php echo htmlspecialchars($v['talla'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; endforeach; endif; ?>
                </h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Referencia</th>
                            <th>Talla</th>
                            <th class="text-right">Entrada</th>
                            <th class="text-right">Salida</th>
                            <th class="text-right">Saldo</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tipoLabels = array(
                            'venta'             => array('Venta', 'label-danger'),
                            'compra'            => array('Compra', 'label-success'),
                            'traslado_salida'   => array('Traslado (salida)', 'label-warning'),
                            'traslado_entrada'  => array('Traslado (entrada)', 'label-primary'),
                        );
                        $variantesMap = array();
                        if ((int) $producto['tiene_variantes'] === 1) {
                            foreach ($producto['variantes'] as $v) {
                                $variantesMap[(int) $v['id_variante']] = $v['talla'];
                            }
                        }
                        ?>
                        <?php foreach ($summary['rows'] as $row):
                            $info = isset($tipoLabels[$row['tipo']]) ? $tipoLabels[$row['tipo']] : array($row['tipo'], 'label-default');
                            $tallaTxt = isset($variantesMap[(int) $row['id_variante']]) ? $variantesMap[(int) $row['id_variante']] : '';
                            $entrada = $row['cantidad'] > 0 ? number_format($row['cantidad'], 0) : '';
                            $salida  = $row['cantidad'] < 0 ? number_format(abs($row['cantidad']), 0) : '';
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['fecha'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><span class="label <?php echo $info[1]; ?>"><?php echo $info[0]; ?></span></td>
                            <td>#<?php echo (int) $row['referencia']; ?></td>
                            <td>
                                <?php if ($tallaTxt !== ''): ?>
                                    <span class="label label-info"><?php echo htmlspecialchars($tallaTxt, ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right text-success"><strong><?php echo $entrada; ?></strong></td>
                            <td class="text-right text-danger"><strong><?php echo $salida; ?></strong></td>
                            <td class="text-right"><strong><?php echo number_format($row['saldo'], 0); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['usuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($summary['rows'])): ?>
                        <tr><td colspan="8" class="report-empty">Sin movimientos en el periodo seleccionado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </section>
</div>

<script>
(function() {
    var input  = document.getElementById('productoBusqueda');
    var result = document.getElementById('productoResultados');
    var hidden = document.getElementById('id_producto');
    var btn    = document.getElementById('btnAplicar');
    var varSel = document.getElementById('id_variante');
    var baseUrl = '<?php echo base_url(); ?>';
    var timer;

    if (!input) return;

    input.addEventListener('input', function() {
        clearTimeout(timer);
        var q = this.value.trim();
        if (q.length < 2) { result.style.display = 'none'; return; }
        timer = setTimeout(function() {
            fetch(baseUrl + 'reporte/movimientos_inventario_buscar_producto?q=' + encodeURIComponent(q))
                .then(function(r){ return r.json(); })
                .then(function(data){
                    result.innerHTML = '';
                    if (!data.items || !data.items.length) {
                        result.style.display = 'none';
                        return;
                    }
                    data.items.forEach(function(it) {
                        var a = document.createElement('a');
                        a.href = '#';
                        a.className = 'list-group-item';
                        a.innerHTML = '<strong>' + it.codigo + '</strong> — ' + it.nombre_producto
                                    + (parseInt(it.tiene_variantes,10) === 1 ? ' <small class="text-muted">· con tallas</small>' : '');
                        a.addEventListener('click', function(e) {
                            e.preventDefault();
                            hidden.value = it.id_producto;
                            input.value = it.nombre_producto + ' (' + it.codigo + ')';
                            result.style.display = 'none';
                            btn.disabled = false;
                            if (varSel) { varSel.disabled = parseInt(it.tiene_variantes,10) !== 1; }
                        });
                        result.appendChild(a);
                    });
                    result.style.display = 'block';
                });
        }, 250);
    });

    document.addEventListener('click', function(e) {
        if (!result.contains(e.target) && e.target !== input) {
            result.style.display = 'none';
        }
    });

    input.addEventListener('focus', function() {
        if (!input.value) { hidden.value = ''; btn.disabled = true; }
    });
})();
</script>
