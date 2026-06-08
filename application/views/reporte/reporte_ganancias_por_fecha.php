<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-area-chart" aria-hidden="true"></i> Utilidad estimada
            <small>Estimación con costo actual del producto</small>
        </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Utilidad estimada" data-report-subtitle="Estimación basada en costo y precio actual">
        <?php
        $reportExportTitle = 'Utilidad estimada';
        $reportExportSubtitle = 'Estimación basada en costo y precio actual';
        $this->load->view('reporte/partials/report_toolbar');
        ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/reporte_ganancias_por_fecha" id="utilFiltros">
                <input type="hidden" name="id_sucursal" value="<?php echo (int) $selectedSucursalId; ?>">
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
                                <label>Categoría</label>
                                <select class="form-control" name="categoria_id" id="filtroCategoria">
                                    <option value="0">Todas</option>
                                    <?php foreach ($categorias as $cat) { ?>
                                    <option value="<?php echo $cat->id_categoria; ?>" <?php echo (int) $categoriaId === (int) $cat->id_categoria ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat->nombre_categoria, ENT_QUOTES, 'UTF-8'); ?>
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
                    </div>
                    <div class="row">
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
                                <label>Productos máx. en detalle</label>
                                <select class="form-control" name="limite">
                                    <?php foreach (array(50,100,200,500,1000,2000) as $opt) { ?>
                                    <option value="<?php echo $opt; ?>" <?php echo (int) $limite === $opt ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Aplicar filtros</button>
                    <a href="<?php echo base_url(); ?>reporte/reporte_ganancias_por_fecha" class="btn btn-default"><i class="fa fa-eraser"></i> Limpiar</a>
                </div>
            </form>
        </div>

        <div class="row report-kpi-strip">
            <div class="col-md-3">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo number_format($summary['totales']['cantidad'], 0); ?></h3>
                        <p>Unidades · <?php echo (int) $summary['totales']['productos']; ?> productos</p>
                    </div>
                    <div class="icon"><i class="fa fa-cubes"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['costo_total'], 2); ?></h3>
                        <p>Costo estimado</p>
                    </div>
                    <div class="icon"><i class="fa fa-shopping-basket"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['venta_total'], 2); ?></h3>
                        <p>Venta estimada</p>
                    </div>
                    <div class="icon"><i class="fa fa-line-chart"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['utilidad_estimada'], 2); ?> <small style="font-size:14px;">(<?php echo number_format($summary['totales']['margen_pct'], 1); ?>%)</small></h3>
                        <p>Utilidad estimada · margen</p>
                    </div>
                    <div class="icon"><i class="fa fa-money"></i></div>
                </div>
            </div>
        </div>

        <?php if (!empty($summary['mostrandoMax'])): ?>
        <div class="callout callout-warning" style="margin-bottom:15px;">
            <h4><i class="fa fa-info-circle"></i> Mostrando los <?php echo (int) $summary['limite']; ?> productos con mayor utilidad.</h4>
            <p>Los KPIs, tendencia y gráficas reflejan <strong>el universo completo</strong> del periodo filtrado.</p>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-line-chart"></i> Tendencia diaria · Venta vs Utilidad</h3>
                    </div>
                    <div class="box-body"><canvas id="chartTrend" height="80"></canvas></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-tags"></i> Utilidad por categoría</h3>
                    </div>
                    <div class="box-body"><canvas id="chartCategoria" height="200"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-calendar"></i> Utilidad por temporada</h3>
                    </div>
                    <div class="box-body"><canvas id="chartTemporada" height="200"></canvas></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-object-group"></i> Utilidad por subcategoría</h3>
                    </div>
                    <div class="box-body"><canvas id="chartSubcategoria" height="200"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-venus-mars"></i> Utilidad por género</h3>
                    </div>
                    <div class="box-body"><canvas id="chartGenero" height="200"></canvas></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list"></i> Detalle por producto</h3>
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
                                    <th class="text-right">Unidades</th>
                                    <th class="text-right">Costo</th>
                                    <th class="text-right">Venta</th>
                                    <th class="text-right">Utilidad</th>
                                    <th class="text-right">Margen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summary['rows'] as $row) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['codigo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_categoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_subcategoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_temporada'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_color'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['genero'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-right"><?php echo number_format($row['cantidad'], 0); ?></td>
                                    <td class="text-right">$<?php echo number_format($row['costo_total'], 2); ?></td>
                                    <td class="text-right">$<?php echo number_format($row['venta_total'], 2); ?></td>
                                    <td class="text-right"><strong>$<?php echo number_format($row['utilidad_estimada'], 2); ?></strong></td>
                                    <td class="text-right"><?php echo number_format($row['margen_pct'], 1); ?>%</td>
                                </tr>
                                <?php } ?>
                                <?php if (empty($summary['rows'])) { ?>
                                <tr><td colspan="12" class="report-empty">Sin resultados para los filtros seleccionados.</td></tr>
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
    var $cat = document.getElementById('filtroCategoria');
    var $sub = document.getElementById('filtroSubcategoria');
    function aplicarCascada() {
        var catSel = parseInt($cat.value, 10) || 0;
        Array.prototype.forEach.call($sub.options, function(opt){
            if (opt.value === '0') { opt.hidden = false; return; }
            var oc = parseInt(opt.getAttribute('data-categoria'), 10) || 0;
            opt.hidden = (catSel > 0 && oc !== catSel);
        });
        if (catSel > 0 && $sub.selectedOptions[0] && $sub.selectedOptions[0].hidden) { $sub.value = '0'; }
    }
    if ($cat && $sub) { $cat.addEventListener('change', aplicarCascada); aplicarCascada(); }

    var trend = <?php echo json_encode($summary['trend']); ?>;
    var breakdowns = <?php echo json_encode($summary['breakdowns']); ?>;

    function palette(n) {
        var base = ['#00a65a','#3c8dbc','#f39c12','#dd4b39','#605ca8','#00c0ef','#39cccc','#d2d6de','#001f3f','#85144b','#2ECC40','#FF851B'];
        var out=[]; for (var i=0;i<n;i++) out.push(base[i % base.length]); return out;
    }

    // Línea tendencia
    if (trend.length) {
        new Chart(document.getElementById('chartTrend').getContext('2d'), {
            type: 'line',
            data: {
                labels: trend.map(function(d){return d.fecha;}),
                datasets: [
                    { label:'Venta', data: trend.map(function(d){return d.venta;}),
                      borderColor:'#3c8dbc', backgroundColor:'rgba(60,141,188,.1)', tension:.3, fill:true },
                    { label:'Utilidad', data: trend.map(function(d){return d.utilidad;}),
                      borderColor:'#00a65a', backgroundColor:'rgba(0,166,90,.1)', tension:.3, fill:true }
                ]
            },
            options: { responsive:true, maintainAspectRatio:false,
                plugins:{ tooltip:{ callbacks:{ label:function(ctx){
                    return ctx.dataset.label+': $'+ctx.parsed.y.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2});
                }}}}
            }
        });
    }

    function renderBar(id, data) {
        var ctx = document.getElementById(id); if (!ctx) return;
        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: { labels: data.map(function(d){return d.label;}),
                    datasets: [{ label:'Utilidad', data: data.map(function(d){return d.utilidad;}), backgroundColor: palette(data.length) }] },
            options: { indexAxis:'y', responsive:true, plugins:{ legend:{display:false},
                tooltip:{ callbacks:{ label:function(ctx){
                    var v=ctx.parsed.x||0; return '$'+v.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2});
                }}}}
            }
        });
    }
    renderBar('chartCategoria',    breakdowns.categoria    || []);
    renderBar('chartTemporada',    breakdowns.temporada    || []);
    renderBar('chartSubcategoria', breakdowns.subcategoria || []);
    renderBar('chartGenero',       breakdowns.genero       || []);
})();
</script>
