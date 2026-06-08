<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-cubes" aria-hidden="true"></i> Productos más vendidos
            <small>Top de demanda y rotación</small>
        </h1>
    </section>

    <section class="content report-shell" data-report-root data-report-title="Productos más vendidos" data-report-subtitle="Ranking de productos del periodo">
        <?php
        $reportExportTitle = 'Productos más vendidos';
        $reportExportSubtitle = 'Ranking de productos del periodo';
        $this->load->view('reporte/partials/report_toolbar');
        ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filtros</h3>
            </div>
            <form method="get" action="<?php echo base_url(); ?>reporte/reporte_venta_productos_mas_vendidos" id="topProdFiltros">
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
                        <div class="col-md-2">
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Top N</label>
                                <select class="form-control" name="top_n">
                                    <?php foreach (array(10,25,50,100,200) as $opt) { ?>
                                    <option value="<?php echo $opt; ?>" <?php echo (int) $topN === $opt ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="checkbox" style="margin-top:0;">
                                    <label>
                                        <input type="checkbox" name="desglosar_talla" value="1" <?php echo !empty($desglosarTalla) ? 'checked' : ''; ?>>
                                        Desglosar por talla
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Aplicar filtros</button>
                    <a href="<?php echo base_url(); ?>reporte/reporte_venta_productos_mas_vendidos" class="btn btn-default"><i class="fa fa-eraser"></i> Limpiar</a>
                </div>
            </form>
        </div>

        <div class="row report-kpi-strip">
            <div class="col-md-4">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo (int) $summary['totales']['productos']; ?></h3>
                        <p>Productos con ventas en el periodo</p>
                    </div>
                    <div class="icon"><i class="fa fa-cube"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo number_format($summary['totales']['unidades'], 0); ?></h3>
                        <p>Unidades vendidas (total)</p>
                    </div>
                    <div class="icon"><i class="fa fa-sort-numeric-desc"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>$<?php echo number_format($summary['totales']['total_vendido'], 2); ?></h3>
                        <p>Valor vendido (total)</p>
                    </div>
                    <div class="icon"><i class="fa fa-money"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-tags"></i> Ventas por categoría</h3>
                    </div>
                    <div class="box-body"><canvas id="chartCategoria" height="200"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-calendar"></i> Ventas por temporada</h3>
                    </div>
                    <div class="box-body"><canvas id="chartTemporada" height="200"></canvas></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-object-group"></i> Ventas por subcategoría</h3>
                    </div>
                    <div class="box-body"><canvas id="chartSubcategoria" height="200"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-venus-mars"></i> Ventas por género</h3>
                    </div>
                    <div class="box-body"><canvas id="chartGenero" height="200"></canvas></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Top <?php echo (int) $topN; ?> por unidades</h3>
                        <span class="pull-right text-muted small"><?php echo count($summary['rows']); ?> renglón(es)</span>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Subcategoría</th>
                                    <th>Temporada</th>
                                    <th>Color</th>
                                    <th>Género</th>
                                    <th class="text-right">Unidades</th>
                                    <th class="text-right">Total vendido</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $idx = 0; foreach ($summary['rows'] as $row):
                                    $idx++;
                                    $tieneVar = (int) ($row['tiene_variantes'] ?? 0) === 1;
                                    $variantes = !empty($row['variantes']) ? $row['variantes'] : array();
                                    $mostrarSub = !empty($desglosarTalla) && $tieneVar && !empty($variantes);
                                ?>
                                <tr<?php echo $mostrarSub ? ' style="background:#f7f9fa;"' : ''; ?>>
                                    <td><?php echo $idx; ?></td>
                                    <td><?php echo htmlspecialchars($row['codigo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['nombre_producto'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <?php if ($tieneVar): ?><small class="text-muted">· variantes</small><?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['nombre_categoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_subcategoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_temporada'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre_color'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['genero'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-right"><strong><?php echo number_format($row['unidades'], 0); ?></strong></td>
                                    <td class="text-right"><strong>$<?php echo number_format($row['total_vendido'], 2); ?></strong></td>
                                </tr>
                                <?php if ($mostrarSub): foreach ($variantes as $v): ?>
                                <tr>
                                    <td></td><td></td>
                                    <td style="padding-left:30px;" colspan="6">
                                        <i class="fa fa-level-up fa-rotate-90 text-muted"></i>
                                        Talla <span class="label label-info"><?php echo htmlspecialchars($v['talla'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </td>
                                    <td class="text-right"><?php echo number_format($v['unidades'], 0); ?></td>
                                    <td class="text-right">$<?php echo number_format($v['total_vendido'], 2); ?></td>
                                </tr>
                                <?php endforeach; endif; ?>
                                <?php endforeach; ?>
                                <?php if (empty($summary['rows'])) { ?>
                                <tr><td colspan="10" class="report-empty">Sin resultados para los filtros seleccionados.</td></tr>
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

    var breakdowns = <?php echo json_encode($summary['breakdowns']); ?>;
    function palette(n) {
        var base = ['#3c8dbc','#00a65a','#f39c12','#dd4b39','#605ca8','#00c0ef','#39cccc','#d2d6de','#001f3f','#85144b','#2ECC40','#FF851B'];
        var out=[]; for (var i=0;i<n;i++) out.push(base[i % base.length]); return out;
    }
    function renderBar(id, data) {
        var ctx = document.getElementById(id); if (!ctx) return;
        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: { labels: data.map(function(d){return d.label;}),
                    datasets: [{ label:'Vendido', data: data.map(function(d){return d.valor;}), backgroundColor: palette(data.length) }] },
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
