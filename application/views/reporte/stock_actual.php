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
                <h3 class="box-title"><i class="fa fa-filter"></i> Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/stock_actual" id="stockActualFiltros">
                <input type="hidden" name="id_sucursal" value="<?php echo $selectedSucursalId; ?>">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Categoría</label>
                                <select class="form-control" name="categoria_id" id="filtroCategoria">
                                    <option value="0">Todas</option>
                                    <?php foreach ($categorias as $categoria) { ?>
                                    <option value="<?php echo $categoria->id_categoria; ?>" <?php echo (int) $categoriaId === (int) $categoria->id_categoria ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($categoria->nombre_categoria, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Subcategoría</label>
                                <select class="form-control" name="subcategoria_id" id="filtroSubcategoria">
                                    <option value="0">Todas</option>
                                    <?php foreach ($subcategorias as $sub) { ?>
                                    <option value="<?php echo $sub->id_subcategoria; ?>" data-categoria="<?php echo $sub->id_categoria; ?>" <?php echo (int) $subcategoriaId === (int) $sub->id_subcategoria ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($sub->nombre_subcategoria, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Temporada</label>
                                <select class="form-control" name="temporada_id">
                                    <option value="0">Todas</option>
                                    <?php foreach ($temporadas as $t) { ?>
                                    <option value="<?php echo $t->id_temporada; ?>" <?php echo (int) $temporadaId === (int) $t->id_temporada ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($t->nombre_temporada, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Color</label>
                                <select class="form-control" name="color_id">
                                    <option value="0">Todos</option>
                                    <?php foreach ($colores as $co) { ?>
                                    <option value="<?php echo $co->id_color; ?>" <?php echo (int) $colorId === (int) $co->id_color ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($co->nombre_color, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Género</label>
                                <select class="form-control" name="genero">
                                    <option value="">Todos</option>
                                    <?php foreach ($generos as $g) { $gv = $g->genero; ?>
                                    <option value="<?php echo htmlspecialchars($gv, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $generoSel === $gv ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($gv, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Producto o código</label>
                                <input type="text" class="form-control" name="producto" value="<?php echo htmlspecialchars($producto, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nombre o código...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Umbral stock bajo</label>
                                <input type="number" min="0" class="form-control" name="umbral_stock_bajo" value="<?php echo (int) $umbralStockBajo; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Sólo stock bajo</label>
                                <div>
                                    <label class="radio-inline"><input type="radio" name="solo_stock_bajo" value="0" <?php echo !$soloStockBajo ? 'checked' : ''; ?>> No</label>
                                    <label class="radio-inline"><input type="radio" name="solo_stock_bajo" value="1" <?php echo $soloStockBajo ? 'checked' : ''; ?>> Sí</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Sucursal</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($sucursalNombre, ENT_QUOTES, 'UTF-8'); ?>" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Aplicar filtros</button>
                    <a href="<?php echo base_url(); ?>reporte/stock_actual" class="btn btn-default"><i class="fa fa-eraser"></i> Limpiar</a>
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
                        <p>Valor inventario (al costo)</p>
                    </div>
                    <div class="icon"><i class="fa fa-money"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?php echo (int) $summary['totales']['stock_bajo']; ?> <small style="font-size:14px;">(<?php echo (int) $summary['totales']['sin_stock']; ?> en cero)</small></h3>
                        <p>Stock bajo (≤ <?php echo (int) $umbralStockBajo; ?>)</p>
                    </div>
                    <div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-tags"></i> Valor por categoría</h3>
                    </div>
                    <div class="box-body"><canvas id="chartCategoria" height="200"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-calendar"></i> Valor por temporada</h3>
                    </div>
                    <div class="box-body"><canvas id="chartTemporada" height="200"></canvas></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-object-group"></i> Valor por subcategoría</h3>
                    </div>
                    <div class="box-body"><canvas id="chartSubcategoria" height="200"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-venus-mars"></i> Valor por género</h3>
                    </div>
                    <div class="box-body"><canvas id="chartGenero" height="200"></canvas></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list"></i> Detalle</h3>
                        <span class="pull-right text-muted small"><?php echo count($summary['rows']); ?> renglón(es)</span>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Subcategoría</th>
                                    <th>Temporada</th>
                                    <th>Color</th>
                                    <th>Género</th>
                                    <th>Talla</th>
                                    <th class="text-right">Costo unit.</th>
                                    <th class="text-right">Stock</th>
                                    <th class="text-right">Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($summary['rows'] as $row) {
                                $esVar = (int) ($row['tiene_variantes'] ?? 0) === 1;
                                $talla = $esVar && !empty($row['talla']) ? $row['talla'] : '—';
                                $stockNum = (float) $row['stock'];
                                $stockClass = $stockNum <= 0 ? 'text-danger' : ($stockNum <= (int) $umbralStockBajo ? 'text-warning' : '');
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['codigo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_categoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_subcategoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_temporada'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <?php if (!empty($row['color_hex'])): ?>
                                        <span style="display:inline-block;width:12px;height:12px;border-radius:2px;border:1px solid #ccc;background:<?php echo htmlspecialchars($row['color_hex'], ENT_QUOTES, 'UTF-8'); ?>;vertical-align:middle;margin-right:4px;"></span>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($row['nombre_color'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['genero'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <?php if ($esVar): ?>
                                        <span class="label label-info"><?php echo htmlspecialchars($talla, ENT_QUOTES, 'UTF-8'); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">$<?php echo number_format((float) $row['precio_compra_unitario'], 2); ?></td>
                                <td class="text-right <?php echo $stockClass; ?>"><strong><?php echo number_format($stockNum, 0); ?></strong></td>
                                <td class="text-right">$<?php echo number_format($row['valor_inventario'], 2); ?></td>
                            </tr>
                            <?php } ?>
                            <?php if (empty($summary['rows'])) { ?>
                            <tr><td colspan="11" class="text-center">Sin resultados para los filtros seleccionados.</td></tr>
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
(function(){
    // Filtrado en cascada de subcategorías por categoría seleccionada
    var $cat = document.getElementById('filtroCategoria');
    var $sub = document.getElementById('filtroSubcategoria');
    function aplicarCascada() {
        var catSel = parseInt($cat.value, 10) || 0;
        Array.prototype.forEach.call($sub.options, function(opt){
            if (opt.value === '0') { opt.hidden = false; return; }
            var oc = parseInt(opt.getAttribute('data-categoria'), 10) || 0;
            opt.hidden = (catSel > 0 && oc !== catSel);
        });
        if (catSel > 0 && $sub.selectedOptions[0] && $sub.selectedOptions[0].hidden) {
            $sub.value = '0';
        }
    }
    if ($cat && $sub) {
        $cat.addEventListener('change', aplicarCascada);
        aplicarCascada();
    }

    var breakdowns = <?php echo json_encode($summary['breakdowns']); ?>;

    function palette(n) {
        var base = ['#3c8dbc','#00a65a','#f39c12','#dd4b39','#605ca8','#00c0ef','#39cccc','#d2d6de','#001f3f','#85144b','#2ECC40','#FF851B'];
        var out = []; for (var i=0;i<n;i++) out.push(base[i % base.length]); return out;
    }

    function renderDoughnut(id, data) {
        var ctx = document.getElementById(id);
        if (!ctx) return;
        var labels = data.map(function(d){ return d.label; });
        var values = data.map(function(d){ return d.valor; });
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: { labels: labels, datasets: [{ data: values, backgroundColor: palette(values.length) }] },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right' },
                    tooltip: { callbacks: { label: function(ctx){
                        var v = ctx.parsed || 0;
                        return ctx.label + ': $' + v.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
                    }}}
                }
            }
        });
    }

    renderDoughnut('chartCategoria',    breakdowns.categoria || []);
    renderDoughnut('chartTemporada',    breakdowns.temporada || []);
    renderDoughnut('chartSubcategoria', breakdowns.subcategoria || []);
    renderDoughnut('chartGenero',       breakdowns.genero || []);
})();
</script>
