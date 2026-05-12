<?php
// Calcular resumen desde los registros cargados
$resumen = ['total_monto' => 0, 'contado' => 0, 'credito' => 0, 'apartado' => 0, 'count' => 0];
if (!empty($records)) {
    foreach ($records as $r) {
        $resumen['total_monto'] += (float)$r->total;
        $resumen['count']++;
        $tipo = isset($r->tipo_pago) ? $r->tipo_pago : 'contado';
        if ($tipo === 'credito')       $resumen['credito']++;
        elseif ($tipo === 'apartado')  $resumen['apartado']++;
        else                           $resumen['contado']++;
    }
}
?>
<style>
/* ── Historial de ventas ─────────────────────────────────── */
.hv-wrapper { padding:16px 20px; }

/* Tarjetas resumen */
.hv-cards { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:20px; }
.hv-card {
    background:#fff;
    border-radius:8px;
    box-shadow:0 1px 4px rgba(0,0,0,.12);
    padding:14px 16px;
    display:flex;
    align-items:center;
    gap:12px;
}
.hv-card-icon {
    width:46px; height:46px;
    border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:20px; color:#fff; flex-shrink:0;
}
.hv-card-icon.green  { background:#27ae60; }
.hv-card-icon.blue   { background:#2980b9; }
.hv-card-icon.orange { background:#e67e22; }
.hv-card-icon.gray   { background:#7f8c8d; }
.hv-card-body { min-width:0; }
.hv-card-value { font-size:20px; font-weight:700; color:#2c3e50; line-height:1.1; }
.hv-card-label { font-size:11px; color:#888; text-transform:uppercase; letter-spacing:.4px; margin-top:1px; }

/* Panel principal */
.hv-box {
    background:#fff;
    border-radius:8px;
    box-shadow:0 1px 4px rgba(0,0,0,.12);
    overflow:hidden;
}
.hv-box-header {
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:12px 16px;
    border-bottom:1px solid #ecf0f1;
    flex-wrap:wrap;
    gap:8px;
}
.hv-box-title { font-size:15px; font-weight:600; color:#2c3e50; margin:0; }

/* Barra de filtros */
.hv-filters { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
.hv-search-wrap { position:relative; }
.hv-search-wrap input {
    padding-left:30px;
    border-radius:5px;
    border:1px solid #ddd;
    height:32px;
    font-size:13px;
    width:220px;
}
.hv-search-wrap .fa-search {
    position:absolute; left:9px; top:50%; transform:translateY(-50%);
    color:#aaa; font-size:13px; pointer-events:none;
}
.hv-filter-select {
    height:32px; font-size:13px; border:1px solid #ddd;
    border-radius:5px; padding:0 8px; color:#555;
}
.hv-btn-clear {
    height:32px; padding:0 12px; font-size:13px;
    border:1px solid #ddd; border-radius:5px;
    background:#fff; color:#777; cursor:pointer;
}
.hv-btn-clear:hover { background:#f5f5f5; }

/* Tabla */
.hv-table { width:100%; border-collapse:collapse; font-size:13px; }
.hv-table thead th {
    background:#f8f9fa;
    padding:10px 12px;
    text-align:left;
    font-weight:600;
    color:#555;
    border-bottom:2px solid #e9ecef;
    white-space:nowrap;
}
.hv-table thead th.text-right { text-align:right; }
.hv-table thead th.text-center { text-align:center; }
.hv-table tbody tr { border-bottom:1px solid #f2f2f2; transition:background .1s; }
.hv-table tbody tr:hover { background:#fafbfc; }
.hv-table td { padding:9px 12px; vertical-align:middle; }
.hv-table td.text-right { text-align:right; }
.hv-table td.text-center { text-align:center; }

/* Badge tipo pago */
.badge-tipo {
    display:inline-block;
    padding:3px 9px;
    border-radius:20px;
    font-size:11px;
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:.3px;
}
.badge-contado  { background:#eafaf1; color:#1e8449; border:1px solid #a9dfbf; }
.badge-credito  { background:#ebf5fb; color:#1a5276; border:1px solid #aed6f1; }
.badge-apartado { background:#fef9e7; color:#9a7d0a; border:1px solid #f9e79f; }

/* Columna monto */
.monto-total { font-weight:700; color:#2c3e50; }
.monto-descuento { color:#e74c3c; font-size:12px; }

/* Acciones */
.hv-actions { display:flex; gap:4px; justify-content:center; white-space:nowrap; }
.hv-actions .btn { padding:3px 8px; font-size:12px; border-radius:4px; }

/* Venta ID */
.venta-id { font-weight:600; color:#7f8c8d; font-size:12px; }

/* Fecha */
.fecha-cell { white-space:nowrap; }
.fecha-day { font-weight:600; color:#2c3e50; }
.fecha-time { font-size:11px; color:#aaa; display:block; }

/* Empty */
.hv-empty { text-align:center; padding:40px 0; color:#aaa; }
.hv-empty i { font-size:40px; display:block; margin-bottom:10px; }

/* Paginación */
.hv-pagination {
    display:flex; align-items:center; justify-content:space-between;
    padding:10px 16px; border-top:1px solid #f0f0f0;
    flex-wrap:wrap; gap:8px;
}
.hv-pag-info { font-size:12px; color:#888; }
.hv-pag-btns { display:flex; gap:4px; flex-wrap:wrap; }
.hv-pag-btns button {
    min-width:30px; height:28px; padding:0 8px;
    border:1px solid #ddd; border-radius:4px;
    background:#fff; font-size:12px; cursor:pointer;
    color:#555; transition:all .12s;
}
.hv-pag-btns button:hover { background:#ecf0f1; }
.hv-pag-btns button.active { background:#2980b9; color:#fff; border-color:#2980b9; font-weight:600; }
.hv-pag-btns button:disabled { opacity:.4; cursor:default; }

/* Fila nueva (polling) */
@keyframes newRowPulse {
    0%   { background: #d5f5e3; }
    70%  { background: #a9dfbf; }
    100% { background: transparent; }
}
.venta-row-nueva td { animation: newRowPulse 1.2s ease-out; }

/* Badge "ventas nuevas" */
.hv-badge-nuevas {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #27ae60;
    color: #fff;
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}
.hv-badge-nuevas:hover { background: #219a52; }

/* Responsive */
@media(max-width:900px) {
    .hv-cards { grid-template-columns:repeat(2,1fr); }
}
@media(max-width:576px) {
    .hv-wrapper { padding:10px; }
    .hv-cards { grid-template-columns:1fr 1fr; gap:8px; }
    .hv-card { padding:10px 12px; }
    .hv-card-value { font-size:17px; }
    .hv-card-icon { width:38px; height:38px; font-size:17px; }
    .hv-search-wrap input { width:160px; }
    .hv-box-header { padding:10px 12px; }
    .hv-table font-size:12px; }
    .hv-table th, .hv-table td { padding:7px 8px; }
    /* Ocultar columnas secundarias en móvil */
    .col-vendedor, .col-descuento { display:none; }
}
</style>

<div class="content-wrapper">
<div class="hv-wrapper">

    <!-- Encabezado -->
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:8px;">
        <div>
            <h3 style="margin:0; font-size:18px; color:#2c3e50; font-weight:700;">
                <i class="fa fa-history text-primary"></i> Historial de ventas
            </h3>
            <p style="margin:2px 0 0; font-size:12px; color:#aaa;">Todas las ventas registradas en esta sucursal</p>
        </div>
        <a class="btn btn-success btn-sm" href="<?php echo base_url(); ?>carrito/carrito">
            <i class="fa fa-plus"></i> Nueva venta
        </a>
    </div>

    <!-- Alertas -->
    <?php $this->load->helper('form'); ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissable" style="border-radius:6px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissable" style="border-radius:6px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <!-- Tarjetas resumen -->
    <div class="hv-cards">
        <div class="hv-card">
            <div class="hv-card-icon green"><i class="fa fa-dollar"></i></div>
            <div class="hv-card-body">
                <div class="hv-card-value">$<?php echo number_format($resumen['total_monto'], 2); ?></div>
                <div class="hv-card-label">Total vendido</div>
            </div>
        </div>
        <div class="hv-card">
            <div class="hv-card-icon gray"><i class="fa fa-shopping-cart"></i></div>
            <div class="hv-card-body">
                <div class="hv-card-value"><?php echo $resumen['count']; ?></div>
                <div class="hv-card-label">Ventas totales</div>
            </div>
        </div>
        <div class="hv-card">
            <div class="hv-card-icon blue"><i class="fa fa-credit-card"></i></div>
            <div class="hv-card-body">
                <div class="hv-card-value"><?php echo $resumen['credito']; ?></div>
                <div class="hv-card-label">A crédito</div>
            </div>
        </div>
        <div class="hv-card">
            <div class="hv-card-icon orange"><i class="fa fa-tags"></i></div>
            <div class="hv-card-body">
                <div class="hv-card-value"><?php echo $resumen['apartado']; ?></div>
                <div class="hv-card-label">Apartados</div>
            </div>
        </div>
    </div>

    <!-- Caja principal -->
    <div class="hv-box">
        <div class="hv-box-header">
            <div style="display:flex; align-items:center; gap:10px;">
                <h4 class="hv-box-title"><i class="fa fa-table"></i> Ventas registradas</h4>
                <span id="badge-nuevas" style="display:none;" class="hv-badge-nuevas" onclick="mostrarNuevas()">
                    <i class="fa fa-arrow-down"></i>
                    <span id="badge-nuevas-count">0</span> nuevas
                </span>
                <span id="polling-indicator" title="Actualizando automáticamente…" style="color:#aaa; font-size:12px;">
                    <i class="fa fa-circle" style="font-size:8px;"></i>
                </span>
            </div>
            <div class="hv-filters">
                <div class="hv-search-wrap">
                    <i class="fa fa-search"></i>
                    <input type="text" id="searchText" placeholder="Buscar cliente o #venta…"
                           autofocus oninput="onSearchInput()" value="<?php echo htmlspecialchars($searchText ?? '', ENT_QUOTES); ?>">
                </div>
                <select class="hv-filter-select" id="filtroTipo" onchange="filtrarTabla(1)">
                    <option value="">Todos los tipos</option>
                    <option value="contado">Contado</option>
                    <option value="credito">Crédito</option>
                    <option value="apartado">Apartado</option>
                </select>
                <button class="hv-btn-clear" onclick="limpiarFiltros()" title="Limpiar filtros">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="hv-table" id="tablaVentas">
                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th class="col-vendedor">Vendedor</th>
                        <th>Tipo</th>
                        <th class="text-right col-descuento">Descuento</th>
                        <th class="text-right">Total</th>
                        <th class="text-center" style="width:120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaVentasTbody">
                    <?php include('table_partial.php'); ?>
                </tbody>
            </table>
        </div>

        <div class="hv-pagination">
            <div class="hv-pag-info" id="pag-info">—</div>
            <div class="hv-pag-btns" id="paginacion"></div>
        </div>
    </div>

</div>
</div>

<script>
var _debounceTimer = null;
var _perPage = <?php echo (int)($per_page ?? 50); ?>;

// ID más alto cargado en la página — punto de partida del polling
<?php
$maxId = 0;
if (!empty($records)) {
    foreach ($records as $r) {
        if ((int)$r->id_venta > $maxId) $maxId = (int)$r->id_venta;
    }
}
?>
var _lastKnownId  = <?php echo $maxId; ?>;
var _pendingRows  = '';   // HTML acumulado mientras el usuario filtra
var _pendingCount = 0;
var _pollingTimer = null;
var _isFiltering  = false;

/* ── Tabla y paginación ─────────────────────────────────── */

function filtrarTabla(pagina) {
    pagina = pagina || 1;
    _isFiltering = true;
    var texto = document.getElementById('searchText').value;
    var tipo  = document.getElementById('filtroTipo') ? document.getElementById('filtroTipo').value : '';
    $.ajax({
        url: '<?php echo base_url(); ?>carrito/filterVentas',
        type: 'POST',
        data: { searchText: texto, page: pagina, tipo_pago: tipo },
        success: function(resp) {
            $('#tablaVentasTbody').html(resp.html);
            renderPaginacion(resp.page, resp.total, resp.pages, resp.limit);
            // Si había pendientes y el filtro está limpio, actualizar lastKnownId
            if (!texto && !tipo && _pendingCount > 0) {
                _lastKnownId += _pendingCount;
                _pendingRows  = '';
                _pendingCount = 0;
                ocultarBadge();
            }
            _isFiltering = (!!texto || !!tipo);
        }
    });
}

function renderPaginacion(pagina, total, paginas, limit) {
    var desde = total === 0 ? 0 : (pagina - 1) * limit + 1;
    var hasta  = Math.min(pagina * limit, total);
    document.getElementById('pag-info').textContent = total === 0
        ? 'Sin resultados'
        : 'Mostrando ' + desde + '–' + hasta + ' de ' + total + ' ventas';

    var html = '';
    if (paginas > 1) {
        html += '<button onclick="filtrarTabla(' + Math.max(1, pagina-1) + ')" ' + (pagina===1?'disabled':'') + '>‹</button>';
        var start = Math.max(1, pagina - 2), end = Math.min(paginas, pagina + 2);
        if (start > 1) html += '<button onclick="filtrarTabla(1)">1</button>' + (start > 2 ? '<button disabled>…</button>' : '');
        for (var i = start; i <= end; i++) {
            html += '<button class="' + (i===pagina?'active':'') + '" onclick="filtrarTabla(' + i + ')">' + i + '</button>';
        }
        if (end < paginas) html += (end < paginas-1 ? '<button disabled>…</button>' : '') + '<button onclick="filtrarTabla(' + paginas + ')">' + paginas + '</button>';
        html += '<button onclick="filtrarTabla(' + Math.min(paginas, pagina+1) + ')" ' + (pagina===paginas?'disabled':'') + '>›</button>';
    }
    document.getElementById('paginacion').innerHTML = html;
}

function onSearchInput() {
    clearTimeout(_debounceTimer);
    _debounceTimer = setTimeout(function() { filtrarTabla(1); }, 350);
}

function limpiarFiltros() {
    document.getElementById('searchText').value = '';
    document.getElementById('filtroTipo').value = '';
    filtrarTabla(1);
}

/* ── Polling ────────────────────────────────────────────── */

function iniciarPolling() {
    _pollingTimer = setInterval(pollNuevasVentas, 12000);
}

function pollNuevasVentas() {
    $.ajax({
        url: '<?php echo base_url(); ?>carrito/ventasNuevas',
        type: 'POST',
        dataType: 'json',
        data: { since_id: _lastKnownId },
        success: function(resp) {
            if (!resp || resp.count === 0) return;

            // Extraer max id_venta de las filas recibidas para avanzar el cursor
            var $rows = $(resp.html).filter('tr');
            $rows.each(function() {
                var txt = $(this).find('.venta-id').text().replace('#', '').trim();
                var id  = parseInt(txt, 10);
                if (id > _lastKnownId) _lastKnownId = id;
            });

            if (_isFiltering) {
                // Usuario filtrando: acumular silencioso, mostrar badge
                _pendingRows  = resp.html + _pendingRows;
                _pendingCount += resp.count;
                mostrarBadge(_pendingCount);
            } else {
                // Sin filtro activo: inyectar directamente al inicio de la tabla
                var $tbody = $('#tablaVentasTbody');
                // Quitar mensaje "sin resultados" si estaba
                $tbody.find('tr td[colspan]').closest('tr').remove();
                $rows.addClass('venta-row-nueva').prependTo($tbody);
                // Limpiar animación después de que termina
                setTimeout(function() {
                    $tbody.find('.venta-row-nueva').removeClass('venta-row-nueva');
                }, 1300);
                actualizarPagInfo(resp.count);
                ocultarBadge();
            }
        },
        error: function() { /* silencioso */ }
    });
}

function mostrarBadge(count) {
    document.getElementById('badge-nuevas-count').textContent = count;
    document.getElementById('badge-nuevas').style.display = '';
}

function ocultarBadge() {
    document.getElementById('badge-nuevas').style.display = 'none';
    _pendingRows  = '';
    _pendingCount = 0;
}

function mostrarNuevas() {
    // El usuario clickea el badge: inyectar filas pendientes
    if (!_pendingRows) { ocultarBadge(); return; }
    var $tbody = $('#tablaVentasTbody');
    $tbody.find('tr td[colspan]').closest('tr').remove();
    var $rows = $(_pendingRows).filter('tr').addClass('venta-row-nueva');
    $rows.prependTo($tbody);
    setTimeout(function() { $tbody.find('.venta-row-nueva').removeClass('venta-row-nueva'); }, 1300);
    actualizarPagInfo($rows.length);
    ocultarBadge();
    // Limpiar también los filtros si había texto
    document.getElementById('searchText').value = '';
    document.getElementById('filtroTipo').value  = '';
    _isFiltering = false;
}

function actualizarPagInfo(nuevas) {
    var info = document.getElementById('pag-info');
    var txt  = info.textContent;
    // Extraer "de X ventas" y sumar
    var match = txt.match(/de (\d+) ventas/);
    if (match) {
        var total = parseInt(match[1], 10) + nuevas;
        info.textContent = txt.replace(/de \d+ ventas/, 'de ' + total + ' ventas');
    }
}

/* ── Init ───────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function() {
    var total   = <?php echo (int)($total_count ?? 0); ?>;
    var paginas = Math.ceil(total / _perPage);
    renderPaginacion(<?php echo (int)($page ?? 1); ?>, total, paginas, _perPage);
    iniciarPolling();
});
</script>
