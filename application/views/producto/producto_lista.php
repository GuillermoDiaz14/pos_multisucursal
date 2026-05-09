<?php $this->load->helper('form'); ?>

<style>
.stock-label { min-width: 65px; display: inline-block; text-align: center; border-radius: 10px; padding: 3px 8px; font-size: 12px; font-weight: 600; }
.img-thumb { cursor: pointer; border-radius: 4px; border: 1px solid #ddd; transition: transform .15s; object-fit: cover; }
.img-thumb:hover { transform: scale(1.35); z-index: 10; position: relative; box-shadow: 0 2px 8px rgba(0,0,0,.3); }
.no-image { width:50px; height:50px; background:#f4f4f4; border:1px dashed #ccc; border-radius:4px; display:inline-flex; align-items:center; justify-content:center; color:#bbb; font-size:20px; }
.filter-toolbar { background:#f8f9fa; padding:12px 15px; border-bottom:1px solid #e7ebee; }
.pagina-actual { background-color: #3c8dbc !important; color: #fff !important; border-color: #367fa9 !important; }
#paginacion .btn { margin: 0 1px; }
tr.stock-agotado > td:first-child { border-left: 3px solid #dd4b39; }
tr.stock-bajo > td:first-child { border-left: 3px solid #f39c12; }
code { font-size: 11px; color: #555; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-th-list"></i> Productos <small>Inventario de sucursal</small></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Inicio</a></li>
            <li class="active">Productos</li>
        </ol>
    </section>

    <section class="content">

        <!-- Alertas -->
        <div class="row">
            <div class="col-md-12">
                <?php $error = $this->session->flashdata('error'); if($error): ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
                <?php $success = $this->session->flashdata('success'); if($success): ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo $success; ?>
                </div>
                <?php endif; ?>
                <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', '<button type="button" class="close" data-dismiss="alert">×</button></div>'); ?>
            </div>
        </div>

        <?php
            $can_precio  = !empty($permisos['ver_precio_compra']);
            $can_gestionar = !empty($permisos['gestionar']);
            $colspan_total = 10 - ($can_precio ? 0 : 1) - ($can_gestionar ? 0 : 1);
        ?>

        <!-- Stats -->
        <?php
            $total      = (int)($total_count ?? count($records));
            $sin_stock  = 0;
            $stock_bajo = 0;
            foreach ($records as $r) {
                $s = (int)$r->stock;
                if ($s === 0)    $sin_stock++;
                elseif ($s <= 1) $stock_bajo++;
            }
        ?>
        <div class="row">
            <div class="col-xs-6 col-sm-3">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-cubes"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total productos</span>
                        <span class="info-box-number" id="stat-total-productos"><?php echo $total; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-3">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-exclamation-triangle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Stock bajo (<span id="stat-umbral-label">≤1</span>)</span>
                        <span class="info-box-number" id="stat-stock-bajo"><?php echo $stock_bajo; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-3">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-times-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Sin stock</span>
                        <span class="info-box-number" id="stat-sin-stock"><?php echo $sin_stock; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-3">
                <div class="info-box" style="cursor:default; min-height:70px">
                    <span class="info-box-icon bg-green"><i class="fa fa-bolt"></i></span>
                    <div class="info-box-content" style="padding-top:6px">
                        <?php if ($can_gestionar): ?>
                        <a href="<?php echo base_url('producto/add'); ?>" class="btn btn-block btn-success btn-sm"><i class="fa fa-plus"></i> Nuevo producto</a>
                        <a href="<?php echo base_url('producto/importar'); ?>" class="btn btn-block btn-default btn-sm" style="margin-top:4px"><i class="fa fa-upload"></i> Importar CSV</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-table"></i> Lista de productos</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-sm btn-success" onclick="exportarExcel()" title="Exportar a Excel (respeta filtros activos)">
                                <i class="fa fa-file-excel-o"></i> Excel
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="exportarPDF()" title="Exportar a PDF (respeta filtros activos)">
                                <i class="fa fa-file-pdf-o"></i> PDF
                            </button>
                            <a href="<?php echo base_url('producto/etiqueta'); ?>" class="btn btn-sm btn-default" title="Imprimir etiquetas">
                                <i class="fa fa-tag"></i> Etiquetas
                            </a>
                            <a href="<?php echo base_url('producto/resurtir'); ?>" class="btn btn-sm btn-default" title="Resurtir stock">
                                <i class="fa fa-refresh"></i> Resurtir
                            </a>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="filter-toolbar">
                        <div class="row">
                            <div class="col-xs-12 col-sm-5">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" id="searchText" class="form-control"
                                           placeholder="Buscar por nombre o código..."
                                           value="<?php echo htmlspecialchars($searchText); ?>"
                                           oninput="debounceFilter()"
                                           autofocus>
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-default" onclick="limpiarFiltros()" title="Limpiar búsqueda">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4" style="margin-top:4px">
                                <select id="filterCategoria" class="form-control input-sm" onchange="filterTable()" style="height:30px">
                                    <option value="">-- Todas las categorías --</option>
                                    <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat->id_categoria; ?>"><?php echo $cat->nombre_categoria; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-3" style="margin-top:4px">
                                <div class="input-group input-group-sm">
                                    <select id="filterStock" class="form-control" onchange="aplicarFiltroStock(); toggleUmbralInput();" style="height:30px">
                                        <option value="">-- Todo el stock --</option>
                                        <option value="ok">Stock OK</option>
                                        <option value="low">Stock bajo</option>
                                        <option value="out">Sin stock (0)</option>
                                    </select>
                                    <span class="input-group-addon" id="umbral-addon" title="Umbral de 'stock bajo'" style="display:none">≤</span>
                                    <input type="number" id="umbralStock" class="form-control" value="1" min="0" max="9999"
                                           style="width:52px; max-width:52px; height:30px; padding:4px 6px; display:none"
                                           oninput="debounceUmbral()"
                                           title="Cantidad máxima para considerar stock bajo">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="miTabla">
                                <thead>
                                    <tr>
                                        <th style="width:60px">Imagen</th>
                                        <th style="width:45px">#</th>
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <?php if ($can_precio): ?><th>P.Compra</th><?php endif; ?>
                                        <th>P.Venta</th>
                                        <th style="width:90px">Stock</th>
                                        <th>Categoría</th>
                                        <th style="width:60px">Talla</th>
                                        <?php if ($can_gestionar): ?><th class="text-center" style="width:100px">Acciones</th><?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody id="tabla-body">
                                    <?php if (!empty($records)): foreach ($records as $record):
                                        $s = (int)$record->stock;
                                        $rowClass = ($s === 0) ? 'stock-agotado' : (($s <= 5) ? 'stock-bajo' : '');
                                    ?>
                                    <tr class="<?php echo $rowClass; ?>" data-stock="<?php echo $s; ?>" data-categoria="<?php echo (int)$record->categoria; ?>">
                                        <td>
                                            <?php if (!empty($record->imagen)): ?>
                                            <img src="<?php echo base_url('uploads/'.$record->imagen); ?>"
                                                 class="img-thumb" width="50" height="50"
                                                 onclick="verImagen('<?php echo base_url('uploads/'.$record->imagen); ?>','<?php echo htmlspecialchars($record->nombre_producto, ENT_QUOTES); ?>')"
                                                 title="Click para ampliar">
                                            <?php else: ?>
                                            <div class="no-image"><i class="fa fa-image"></i></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted"><?php echo $record->id_producto; ?></td>
                                        <td><code><?php echo $record->codigo; ?></code></td>
                                        <td><strong><?php echo $record->nombre_producto; ?></strong></td>
                                        <?php if ($can_precio): ?><td class="text-muted"><?php echo '$'.number_format((float)$record->precio_compra, 2); ?></td><?php endif; ?>
                                        <td><strong><?php echo '$'.number_format((float)$record->precio_venta, 2); ?></strong></td>
                                        <td>
                                            <?php if ($s === 0): ?>
                                                <span class="label label-danger stock-label">Sin stock</span>
                                            <?php elseif ($s <= 5): ?>
                                                <span class="label label-warning stock-label"><?php echo $s; ?> bajo</span>
                                            <?php else: ?>
                                                <span class="label label-success stock-label"><?php echo $s; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $record->nombre_categoria; ?></td>
                                        <td><span class="label label-default"><?php echo $record->talla; ?></span></td>
                                        <?php if ($can_gestionar): ?>
                                        <td class="text-center" style="white-space:nowrap">
                                            <a href="<?php echo base_url('producto/edit/'.$record->id_producto); ?>" class="btn btn-xs btn-info" title="Editar datos"><i class="fa fa-pencil"></i></a>
                                            <a href="<?php echo base_url('producto/editar_imagen/'.$record->id_producto); ?>" class="btn btn-xs btn-primary" title="Cambiar imagen"><i class="fa fa-camera"></i></a>
                                            <a href="<?php echo base_url('producto/confirmar_eliminar_producto/'.$record->id_producto); ?>" class="btn btn-xs btn-danger" title="Eliminar"
                                               onclick="return confirm('¿Eliminar «<?php echo addslashes($record->nombre_producto); ?>» permanentemente? Esta acción no se puede deshacer.')"><i class="fa fa-trash"></i></a>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr class="no-results-row">
                                        <td colspan="<?php echo $colspan_total; ?>" class="text-center text-muted" style="padding:40px 20px">
                                            <i class="fa fa-search fa-2x" style="display:block;margin-bottom:8px"></i>
                                            No se encontraron productos.
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="box-footer clearfix">
                        <small class="text-muted pull-left" id="info-paginacion" style="line-height:30px"></small>
                        <div id="paginacion" class="pull-right"></div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<!-- Modal imagen -->
<div class="modal fade" id="imgModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title" id="imgModalTitle">Imagen del producto</h4>
            </div>
            <div class="modal-body text-center" style="padding:10px">
                <img id="imgModalSrc" src="" class="img-responsive img-rounded" style="max-height:420px;margin:auto">
            </div>
        </div>
    </div>
</div>

<!-- SheetJS para exportar Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<!-- jsPDF + autoTable para exportar PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<script>
var COLSPAN_TABLA  = <?php echo $colspan_total; ?>;
var CAN_PRECIO     = <?php echo $can_precio ? 'true' : 'false'; ?>;
var _prodPerPage   = <?php echo (int)($per_page ?? 100); ?>;
var _prodPagActual = 1;
var _prodTotal     = <?php echo (int)($total_count ?? 0); ?>;
var _prodPages     = Math.ceil(_prodTotal / _prodPerPage);

(function () {

    // ── Server-side pagination ──────────────────────────────────────────────

    function renderServerPaginacion(pagina, total, paginas, limit) {
        var desde = total === 0 ? 0 : (pagina - 1) * limit + 1;
        var hasta  = Math.min(pagina * limit, total);
        var info   = document.getElementById('info-paginacion');
        var cont   = document.getElementById('paginacion');
        if (info) info.textContent = total === 0 ? 'Sin resultados'
            : 'Mostrando ' + desde + '–' + hasta + ' de ' + total + ' productos';
        if (!cont) return;
        cont.innerHTML = '';
        if (paginas <= 1) return;
        function mkBtn(txt, enabled, cb) {
            var b = document.createElement('button');
            b.className = 'btn btn-sm btn-default';
            b.style.margin = '0 1px';
            b.innerHTML = txt;
            b.disabled = !enabled;
            if (enabled) b.addEventListener('click', cb);
            return b;
        }
        cont.appendChild(mkBtn('«', pagina > 1, function() { filterTable(_prodPagActual - 1); }));
        var start = Math.max(1, pagina-2), end = Math.min(paginas, pagina+2);
        if (start > 1) { cont.appendChild(mkBtn('1', true, function() { filterTable(1); })); if (start > 2) { var d=document.createElement('button'); d.className='btn btn-sm btn-default'; d.style.margin='0 1px'; d.disabled=true; d.textContent='…'; cont.appendChild(d); } }
        for (var i = start; i <= end; i++) {
            (function(num){ var b = mkBtn(num, true, function() { filterTable(num); }); if (num===pagina) b.classList.add('pagina-actual'); cont.appendChild(b); })(i);
        }
        if (end < paginas) { if (end < paginas-1) { var d2=document.createElement('button'); d2.className='btn btn-sm btn-default'; d2.style.margin='0 1px'; d2.disabled=true; d2.textContent='…'; cont.appendChild(d2); } cont.appendChild(mkBtn(paginas, true, function() { filterTable(paginas); })); }
        cont.appendChild(mkBtn('»', pagina < paginas, function() { filterTable(_prodPagActual + 1); }));
    }

    // ── Stock badge helpers (work on current page's DOM rows) ───────────────

    function umbral() {
        var v = parseInt(document.getElementById('umbralStock').value, 10);
        return isNaN(v) ? 1 : v;
    }

    function renderStockBadges() {
        var u = umbral();
        document.querySelectorAll('#tabla-body tr[data-stock]').forEach(function (tr) {
            var s = parseInt(tr.getAttribute('data-stock'), 10);
            var badge = tr.querySelector('.stock-label');
            if (!badge) return;
            tr.classList.remove('stock-agotado', 'stock-bajo');
            badge.className = 'label stock-label';
            if (s === 0) {
                tr.classList.add('stock-agotado');
                badge.classList.add('label-danger');
                badge.textContent = 'Sin stock';
            } else if (s <= u) {
                tr.classList.add('stock-bajo');
                badge.classList.add('label-warning');
                badge.textContent = s + ' bajo';
            } else {
                badge.classList.add('label-success');
                badge.textContent = s;
            }
        });
    }

    function actualizarTodosStats(sinStock, stockBajo, total) {
        var u = umbral();
        if (typeof sinStock === 'undefined') {
            sinStock = 0; stockBajo = 0; total = 0;
            document.querySelectorAll('#tabla-body tr[data-stock]').forEach(function (tr) {
                var s = parseInt(tr.getAttribute('data-stock'), 10);
                total++;
                if (s === 0) sinStock++;
                else if (s >= 1 && s <= u) stockBajo++;
            });
        }
        var elTotal = document.getElementById('stat-total-productos');
        var elBajo  = document.getElementById('stat-stock-bajo');
        var elSin   = document.getElementById('stat-sin-stock');
        var elLbl   = document.getElementById('stat-umbral-label');
        if (elTotal) elTotal.textContent = total;
        if (elBajo)  elBajo.textContent  = stockBajo;
        if (elSin)   elSin.textContent   = sinStock;
        if (elLbl)   elLbl.textContent   = '≤' + u;
    }

    var umbralTimer;
    window.debounceUmbral = function () {
        clearTimeout(umbralTimer);
        umbralTimer = setTimeout(function () {
            renderStockBadges();
            actualizarTodosStats();
            var val = document.getElementById('filterStock').value;
            if (val) aplicarFiltroStock();
        }, 400);
    };

    window.toggleUmbralInput = function () {
        var val = document.getElementById('filterStock').value;
        var addon = document.getElementById('umbral-addon');
        var inp   = document.getElementById('umbralStock');
        var mostrar = (val === 'low');
        addon.style.display = mostrar ? '' : 'none';
        inp.style.display   = mostrar ? '' : 'none';
    };

    window.aplicarFiltroStock = function () {
        toggleUmbralInput();
        var val = document.getElementById('filterStock').value;
        var u = umbral();
        document.querySelectorAll('#tabla-body tr[data-stock]').forEach(function (tr) {
            var s = parseInt(tr.getAttribute('data-stock'), 10);
            var mostrar = true;
            if (val === 'ok')  mostrar = s > u;
            if (val === 'low') mostrar = s >= 1 && s <= u;
            if (val === 'out') mostrar = s === 0;
            if (!mostrar) {
                tr.setAttribute('data-stock-hidden', '1');
                tr.style.display = 'none';
            } else {
                tr.removeAttribute('data-stock-hidden');
            }
        });
        actualizarTodosStats();
    };

    var debounceTimer;
    window.debounceFilter = function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() { filterTable(1); }, 380);
    };

    window.filterTable = function (pagina) {
        pagina = pagina || 1;
        _prodPagActual = pagina;
        var searchText  = document.getElementById('searchText').value;
        var idCategoria = document.getElementById('filterCategoria').value;
        var stockActual = document.getElementById('filterStock').value;
        $.ajax({
            url: '<?php echo base_url('producto/filterProductos'); ?>',
            type: 'POST',
            dataType: 'json',
            data: { searchText: searchText, id_categoria: idCategoria, page: pagina },
            beforeSend: function () {
                $('#tabla-body').html(
                    '<tr><td colspan="' + COLSPAN_TABLA + '" class="text-center" style="padding:30px">' +
                    '<i class="fa fa-spinner fa-spin fa-2x text-muted"></i></td></tr>'
                );
            },
            success: function (resp) {
                _prodTotal = resp.total;
                _prodPages = resp.pages;
                $('#tabla-body').html(resp.html);
                renderStockBadges();
                renderServerPaginacion(resp.page, resp.total, resp.pages, resp.limit);
                if (stockActual) {
                    document.getElementById('filterStock').value = stockActual;
                    aplicarFiltroStock();
                } else {
                    toggleUmbralInput();
                    actualizarTodosStats(resp.sin_stock, resp.stock_bajo, resp.total);
                }
            }
        });
    };

    window.limpiarFiltros = function () {
        document.getElementById('searchText').value = '';
        document.getElementById('filterCategoria').value = '';
        document.getElementById('filterStock').value = '';
        filterTable(1);
    };

    window.verImagen = function (url, nombre) {
        document.getElementById('imgModalSrc').src = url;
        document.getElementById('imgModalTitle').textContent = nombre;
        $('#imgModal').modal('show');
    };

    // Retorna las filas exportables (las que no están ocultas por filtro de stock)
    function getExportRows() {
        return Array.prototype.filter.call(
            document.querySelectorAll('#tabla-body tr[data-stock]'),
            function (tr) { return !tr.hasAttribute('data-stock-hidden'); }
        );
    }

    window.exportarExcel = function () {
        var filas = getExportRows();
        if (!filas.length) { alert('No hay productos para exportar con los filtros actuales.'); return; }

        var cabeceras = ['#', 'Código', 'Producto'];
        if (CAN_PRECIO) cabeceras.push('P.Compra');
        cabeceras.push('P.Venta', 'Stock', 'Categoría', 'Talla');

        var datos = [cabeceras];
        filas.forEach(function (tr) {
            var fila = [
                tr.getAttribute('data-id'),
                tr.getAttribute('data-codigo'),
                tr.getAttribute('data-nombre')
            ];
            if (CAN_PRECIO) fila.push(parseFloat(tr.getAttribute('data-precio-compra')));
            fila.push(
                parseFloat(tr.getAttribute('data-precio-venta')),
                parseInt(tr.getAttribute('data-stock'), 10),
                tr.getAttribute('data-nombre-categoria'),
                tr.getAttribute('data-talla')
            );
            datos.push(fila);
        });

        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.aoa_to_sheet(datos);
        // Ancho de columnas
        ws['!cols'] = [{wch:6},{wch:18},{wch:35},{wch:12},{wch:10},{wch:18},{wch:10}];
        XLSX.utils.book_append_sheet(wb, ws, 'Inventario');
        XLSX.writeFile(wb, 'inventario_sucursal.xlsx');
    };

    window.exportarPDF = function () {
        var filas = getExportRows();
        if (!filas.length) { alert('No hay productos para exportar con los filtros actuales.'); return; }

        var jsPDF = window.jspdf ? window.jspdf.jsPDF : window.jsPDF;
        if (!jsPDF) { alert('La librería PDF aún no cargó. Intenta de nuevo.'); return; }

        var doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'letter' });
        doc.setFontSize(14);
        doc.text('Inventario de Sucursal', 14, 14);
        doc.setFontSize(9);
        doc.setTextColor(100);
        doc.text('Generado: ' + new Date().toLocaleDateString('es-MX', {year:'numeric',month:'long',day:'numeric'}), 14, 20);

        var head = [['#', 'Código', 'Producto']];
        if (CAN_PRECIO) head[0].push('P.Compra');
        head[0].push('P.Venta', 'Stock', 'Categoría', 'Talla');

        var body = filas.map(function (tr) {
            var fila = [
                tr.getAttribute('data-id'),
                tr.getAttribute('data-codigo'),
                tr.getAttribute('data-nombre')
            ];
            if (CAN_PRECIO) fila.push('$' + tr.getAttribute('data-precio-compra'));
            fila.push(
                '$' + tr.getAttribute('data-precio-venta'),
                tr.getAttribute('data-stock'),
                tr.getAttribute('data-nombre-categoria'),
                tr.getAttribute('data-talla')
            );
            return fila;
        });

        doc.autoTable({
            head: head,
            body: body,
            startY: 25,
            styles: { fontSize: 8, cellPadding: 2 },
            headStyles: { fillColor: [60, 141, 188], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [245, 248, 250] },
            didParseCell: function (data) {
                // Colorear columna Stock según valor
                if (data.section === 'body') {
                    var stockCol = CAN_PRECIO ? 5 : 4;
                    if (data.column.index === stockCol) {
                        var s = parseInt(data.cell.raw, 10);
                        if (s === 0)       data.cell.styles.textColor = [221, 75, 57];
                        else if (s <= parseInt(document.getElementById('umbralStock').value, 10) || 1)
                                           data.cell.styles.textColor = [243, 156, 18];
                        else               data.cell.styles.textColor = [0, 166, 90];
                    }
                }
            }
        });

        doc.save('inventario_sucursal.pdf');
    };

    document.addEventListener('DOMContentLoaded', function () {
        renderStockBadges();
        actualizarTodosStats();
        toggleUmbralInput();
        renderServerPaginacion(1, _prodTotal, _prodPages, _prodPerPage);
    });
})();
</script>
