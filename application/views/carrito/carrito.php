<?php
foreach ($configuracion['configuracion'] as $config) {
    $impuesto = $config->impuesto;
}

$saldo = 0;
foreach ($cajaabierta as $caja) {
    $saldo = $caja->saldo;
}

$clienteGeneralId = '';
$clienteGeneralNombre = '';
foreach ($clientes as $clienteDefault) {
    if (mb_strtolower(trim($clienteDefault->nombre)) === 'cliente general') {
        $clienteGeneralId = $clienteDefault->id_cliente;
        $clienteGeneralNombre = $clienteDefault->nombre;
        break;
    }
}
if ($clienteGeneralId === '' && !empty($clientes)) {
    $clienteGeneralId = $clientes[0]->id_cliente;
    $clienteGeneralNombre = $clientes[0]->nombre;
}
?>
<style>
/* ─── Variables ─────────────────────────────────────────── */
:root {
    --pos-dark:   #1a252f;
    --pos-mid:    #2c3e50;
    --pos-green:  #27ae60;
    --pos-green2: #2ecc71;
    --pos-red:    #c0392b;
    --pos-radius: 6px;
    --pos-shadow: 0 1px 4px rgba(0,0,0,.18);
}

/* ─── Reset inner scroll ────────────────────────────────── */
.content-wrapper { overflow: hidden; }

/* ─── Topbar ────────────────────────────────────────────── */
.pos-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 12px;
    background: #fff;
    border-bottom: 1px solid #dde;
}
.pos-topbar h4 { margin: 0; font-size: 15px; color: var(--pos-mid); }

/* ─── Mobile tabs (eliminado) ───────────────────────────── */
.pos-mobile-tabs { display: none; }

/* ─── Layout 3 columnas ─────────────────────────────────── */
.pos-wrapper {
    display: flex;
    gap: 10px;
    padding: 8px 10px;
    height: calc(100vh - 95px);
    box-sizing: border-box;
}

/* ── Col izquierda: búsqueda ──── */
.pos-search-panel {
    flex: 0 0 26%;
    min-width: 220px;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: var(--pos-radius);
    box-shadow: var(--pos-shadow);
    overflow: hidden;
}
.pos-search-header {
    padding: 9px 12px 7px;
    background: var(--pos-mid);
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    flex-shrink: 0;
}
.pos-search-body {
    padding: 8px 10px;
    flex-shrink: 0;
    border-bottom: 1px solid #eee;
}
.pos-product-list {
    flex: 1;
    overflow-y: auto;
    padding: 4px 6px 6px;
}

/* ── Col central: carrito ──────── */
.pos-cart-box {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: var(--pos-radius);
    box-shadow: var(--pos-shadow);
    overflow: hidden;
    min-width: 0;
}
.pos-cart-header {
    background: var(--pos-dark);
    color: #fff;
    padding: 10px 14px;
    font-size: 14px;
    font-weight: 700;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.pos-cart-table-wrap {
    flex: 1;
    overflow-y: auto;
}
.pos-cart-table-wrap table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.pos-cart-table-wrap th {
    background: var(--pos-mid);
    color: #ecf0f1;
    padding: 8px 10px;
    text-align: left;
    border-bottom: 2px solid var(--pos-dark);
    white-space: nowrap;
    position: sticky;
    top: 0;
    z-index: 1;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .4px;
}
.pos-cart-table-wrap td {
    padding: 8px 10px;
    border-bottom: 1px solid #eef0f2;
    vertical-align: middle;
}
.pos-cart-table-wrap tbody tr:nth-child(even) td { background: #f8fafb; }
.pos-cart-table-wrap tbody tr:hover td { background: #eaf4fb; }

@keyframes cartRowFlash {
    0%   { background: #d5f5e3; }
    60%  { background: #abebc6; }
    100% { background: transparent; }
}
.cart-row-new td { animation: cartRowFlash .65s ease-out; }

.qty-input {
    width: 52px;
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 3px;
    padding: 3px 4px;
    font-size: 14px;
    font-weight: 700;
}
.btn-qty {
    padding: 3px 10px;
    font-size: 14px;
    line-height: 1.4;
    font-weight: 700;
}
.btn-remove {
    color: var(--pos-red);
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    padding: 0 4px;
    line-height: 1;
}
.btn-remove:hover { color: #e74c3c; }
.cart-prod-name {
    font-weight: 600;
    color: var(--pos-mid);
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 260px;
    display: block;
}
.cart-prod-subtotal { font-size: 13px; color: var(--pos-green); font-weight: 700; }
.cart-empty {
    text-align: center;
    color: #bbb;
    padding: 40px 0;
    font-size: 13px;
}
.cart-empty i { font-size: 40px; display: block; margin-bottom: 10px; }

/* ── Col derecha: pago ─────────── */
.pos-pay-box {
    flex: 0 0 270px;
    display: flex;
    flex-direction: column;
    gap: 0;
    background: #fff;
    border-radius: var(--pos-radius);
    box-shadow: var(--pos-shadow);
    overflow-y: auto;
    padding: 0;
}
.pos-total-display {
    background: var(--pos-green);
    color: #fff;
    padding: 14px 16px 12px;
    text-align: center;
    flex-shrink: 0;
}
.pos-total-display .total-label { font-size: 11px; opacity: .85; text-transform: uppercase; letter-spacing: .6px; }
.pos-total-display .total-amount { font-size: 38px; font-weight: 800; line-height: 1.1; }

.pay-inner { padding: 10px 12px; display: flex; flex-direction: column; gap: 8px; flex: 1; }

.pay-field label { font-size: 11px; font-weight: 700; color: #555; margin-bottom: 2px; display: block; text-transform: uppercase; }
.pay-field select, .pay-field input[type=text], .pay-field input[type=number] {
    font-size: 13px;
    height: 32px;
    padding: 0 8px;
    width: 100%;
}
.pay-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.pay-full { grid-column: 1 / -1; }

/* Monto recibido grande */
#monto_recibido {
    font-size: 20px !important;
    font-weight: 700;
    height: 44px !important;
    text-align: center;
    color: var(--pos-mid);
}
#cambio {
    font-size: 16px !important;
    font-weight: 700;
    height: 36px !important;
    text-align: center;
    color: var(--pos-green);
    background: #f0faf5 !important;
    cursor: default;
}
/* Quitar spinners del input cambio */
.input-cambio::-webkit-outer-spin-button,
.input-cambio::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.input-cambio { -moz-appearance: textfield; }

.pay-divider { border: none; border-top: 1px solid #eee; margin: 2px 0; }

/* Totales resumen */
.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    color: #666;
    padding: 1px 0;
}


/* Botón registrar */
.btn-registrar {
    width: 100%;
    padding: 13px;
    font-size: 17px;
    font-weight: 700;
    border-radius: 5px;
    letter-spacing: .3px;
    margin-top: 4px;
}

/* Cliente compact */
.cliente-dropdown { position: relative; }
.cliente-list {
    list-style: none;
    padding: 0;
    margin: 0;
    position: absolute;
    width: 100%;
    max-height: 150px;
    overflow-y: auto;
    border: 1px solid #ccc;
    border-radius: 4px;
    display: none;
    z-index: 200;
    background: #fff;
    box-shadow: 0 3px 10px rgba(0,0,0,.15);
    bottom: 100%;
    left: 0;
}
.cliente-list li {
    padding: 7px 10px;
    cursor: pointer;
    font-size: 12px;
    border-bottom: 1px solid #f5f5f5;
}
.cliente-list li:hover { background: #eaf4fb; }

/* Productos en lista de búsqueda */
.prod-item {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 6px 7px;
    border-radius: 4px;
    cursor: pointer;
    border: 1px solid transparent;
    transition: background .1s;
    margin-bottom: 2px;
}
.prod-item:hover { background: #eaf4fb; border-color: #aed6f1; }
.prod-item:active { background: #d5eaf7; }
.prod-item img { width: 34px; height: 34px; object-fit: cover; border-radius: 3px; flex-shrink: 0; }
.prod-item-info { flex: 1; min-width: 0; }
.prod-item-name { font-size: 12px; font-weight: 700; color: var(--pos-mid); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.prod-item-code { font-size: 10px; color: #999; }
.prod-item-price { font-size: 13px; font-weight: 700; color: var(--pos-green); white-space: nowrap; flex-shrink: 0; }
.prod-item-stock {
    font-size: 10px;
    font-weight: 700;
    color: var(--pos-green);
    background: #eafaf1;
    border: 1px solid #a9dfbf;
    border-radius: 3px;
    padding: 1px 4px;
    white-space: nowrap;
    display: block;
    margin-top: 2px;
}
.prod-item-stock.sin-stock { color: var(--pos-red); background: #fdedec; border-color: #f1948a; }

/* ─── Responsive ────────────────────────────────────────── */
@media (max-width: 768px) {
    .content-wrapper { overflow-y: auto; }
    .pos-wrapper {
        flex-direction: column;
        height: auto;
        padding: 6px;
        gap: 8px;
    }
    .pos-search-panel { min-width: 0; flex: none; }
    .pos-product-list { max-height: 200px; }
    .pos-cart-box { min-height: 180px; flex: none; }
    .pos-pay-box { flex: none; }
    .pos-total-display .total-amount { font-size: 32px; }
    .btn-registrar { font-size: 16px; padding: 15px; }
    .btn-qty { padding: 5px 13px; font-size: 16px; }
    .qty-input { width: 46px; }
    .pos-cart-table-wrap td, .pos-cart-table-wrap th { padding: 7px 6px; font-size: 12px; }
    .prod-item { padding: 9px 7px; }
    #monto_recibido { font-size: 22px !important; height: 48px !important; }
}

@media (min-width: 769px) and (max-width: 1100px) {
    .pos-search-panel { flex: 0 0 28%; }
    .pos-pay-box { flex: 0 0 250px; }
    .cart-prod-name { max-width: 180px; }
}
</style>

<div class="content-wrapper" style="padding-bottom:0;">

    <!-- Topbar -->
    <div class="pos-topbar">
        <h4>
            <i class="fa fa-shopping-cart text-primary"></i>
            Punto de Venta
            <small class="text-muted" style="font-size:12px; margin-left:8px;">
                <i class="fa fa-user"></i> <?php echo htmlspecialchars($nombre_vendedor, ENT_QUOTES); ?>
            </small>
        </h4>
        <div style="display:flex; gap:8px; align-items:center;">
            <button class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalCaja">
                <i class="fa fa-money"></i> Caja: $<?php echo number_format($saldo, 2); ?>
            </button>
            <a href="<?php echo base_url('carrito/ventas_lista'); ?>" class="btn btn-default btn-xs">
                <i class="fa fa-list"></i> Historial
            </a>
        </div>
    </div>

    <!-- Modal caja -->
    <div class="modal fade" id="modalCaja" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-money"></i> Estado de caja</h4>
                </div>
                <div class="modal-body">
                    <p>Saldo actual: <strong>$<?php echo number_format($saldo, 2); ?></strong></p>
                    <input type="hidden" id="saldo" value="<?php echo $saldo; ?>">
                </div>
                <div class="modal-footer">
                    <a href="<?php echo base_url(); ?>caja/cierre_arqueo" class="btn btn-danger btn-sm">
                        <i class="fa fa-balance-scale"></i> Cerrar caja (arqueo)
                    </a>
                    <button class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal venta exitosa -->
    <div class="modal fade" id="modalVentaExitosa" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header" style="background:#27ae60; color:#fff;">
                    <h4 class="modal-title"><i class="fa fa-check-circle"></i> ¡Venta registrada!</h4>
                </div>
                <div class="modal-body text-center">
                    <p style="font-size:14px; margin-bottom:4px;">Venta <strong>#<span id="modal-id-venta"></span></strong></p>
                    <p style="font-size:30px; font-weight:800; color:#27ae60; margin:6px 0;">$<span id="modal-total-venta"></span></p>
                    <p class="text-muted" style="font-size:12px;"><?php echo htmlspecialchars($nombre_vendedor, ENT_QUOTES); ?></p>
                </div>
                <div class="modal-footer">
                    <button id="btn-imprimir-ticket" class="btn btn-info btn-sm" onclick="printZebraTicket(window._ventaIdModal)">
                        <i class="fa fa-print"></i> Imprimir
                    </button>
                    <button onclick="nuevaVenta()" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Nueva venta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Inputs ocultos globales -->
    <input type="hidden" id="id_cliente" value="<?php echo htmlspecialchars($clienteGeneralId, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="imp" value="<?php echo $impuesto; ?>">
    <input type="hidden" id="subtotal" value="0">
    <input type="hidden" id="base_imponible" value="0">
    <input type="hidden" id="impuesto" value="0">

    <!-- Layout principal -->
    <div class="pos-wrapper">

        <!-- ═══ COL 1: Búsqueda ═══ -->
        <div class="pos-search-panel" id="panel-buscar">
            <div class="pos-search-header">
                <i class="fa fa-barcode"></i> Escanear / Buscar
            </div>
            <div class="pos-search-body">
                <input type="text" class="form-control" id="producto_busqueda"
                       placeholder="Código de barras o nombre…"
                       autofocus autocomplete="off"
                       oninput="buscarProductos(this.value)">
            </div>
            <div class="pos-product-list" id="lista_productos">
                <div id="pos-empty-hint" style="text-align:center; padding:28px 8px; color:#bbb; font-size:12px;">
                    <i class="fa fa-search" style="font-size:30px; display:block; margin-bottom:8px;"></i>
                    Escanea o escribe para buscar
                </div>
            </div>
        </div>

        <!-- ═══ COL 2: Carrito ═══ -->
        <div class="pos-cart-box" id="panel-carrito">
            <div class="pos-cart-header">
                <span>
                    <i class="fa fa-shopping-basket"></i> Carrito
                    <span id="cart-count" class="badge" style="background:#27ae60; margin-left:6px;">0</span>
                </span>
                <button class="btn btn-default btn-xs" onclick="limpiarCarrito()" id="btn-vaciar" style="display:none;" title="Vaciar carrito">
                    <i class="fa fa-trash"></i> Vaciar
                </button>
            </div>
            <div class="pos-cart-table-wrap" id="cart-table-wrap">
                <div class="cart-empty" id="cart-empty-msg">
                    <i class="fa fa-shopping-cart"></i>
                    Carrito vacío.<br>
                    <small>Escanea un producto para comenzar.</small>
                </div>
                <table id="cart-table" style="display:none;">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th style="width:100px; text-align:center;">Cantidad</th>
                            <th style="width:72px;">Precio</th>
                            <th style="width:78px;">Total</th>
                            <th style="width:28px;"></th>
                        </tr>
                    </thead>
                    <tbody id="cart-tbody"></tbody>
                </table>
            </div>
        </div>

        <!-- ═══ COL 3: Pago ═══ -->
        <div class="pos-pay-box" id="panel-pago">

            <!-- Total grande -->
            <div class="pos-total-display">
                <div class="total-label">Total a cobrar</div>
                <div class="total-amount">$<span id="display-total">0.00</span></div>
            </div>

            <div class="pay-inner">

                <!-- Descuento -->
                <div class="pay-field">
                    <label><i class="fa fa-tag"></i> Descuento ($)</label>
                    <input type="number" id="descuento_total" value="" min="0" step="0.01"
                           class="form-control" oninput="calcularsubTotalconDescuento()" placeholder="0.00">
                </div>

                <!-- Monto recibido -->
                <div class="pay-field" id="cobro_contado_section">
                    <label><i class="fa fa-money"></i> Monto recibido ($)</label>
                    <input type="number" class="form-control" id="monto_recibido" min="0" step="0.01"
                           inputmode="decimal" oninput="actualizarCambio()" placeholder="0.00">
                </div>

                <!-- Cambio -->
                <div class="pay-field" id="cambio_section">
                    <label>Cambio ($)</label>
                    <input type="number" class="form-control input-cambio" id="cambio" readonly tabindex="-1" placeholder="0.00">
                </div>

                <hr class="pay-divider">

                <!-- Tipo + Método de pago -->
                <div class="pay-row">
                    <div class="pay-field">
                        <label>Tipo de pago</label>
                        <select class="form-control" id="tipo_pago" onchange="bloquearMetodoPago()">
                            <option value="contado">Contado</option>
                            <option value="credito">Crédito</option>
                            <option value="apartado">Apartado</option>
                        </select>
                    </div>
                    <div class="pay-field">
                        <label>Método</label>
                        <select class="form-control" id="id_metodo_pago">
                            <?php foreach ($configuracion['metodo_pago'] as $metodo): ?>
                                <option value="<?php echo $metodo->id_metodo_pago; ?>"><?php echo $metodo->nombre_metodo_pago; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Apartado anticipo -->
                <div class="pay-field" id="anticipo_section" style="display:none;">
                    <label>Anticipo / Enganche ($)</label>
                    <input type="number" class="form-control" id="anticipo" value="" min="0" step="0.01"
                           inputmode="decimal" oninput="validarAnticipo()">
                    <small class="text-muted"><i class="fa fa-info-circle"></i> Producto reservado hasta pago total.</small>
                </div>

                <hr class="pay-divider">

                <!-- Resumen de totales -->
                <div>
                    <div class="summary-row">
                        <span>Subtotal bruto</span>
                        <strong id="display-bruto">$0.00</strong>
                    </div>
                    <div class="summary-row" id="row-descuento" style="display:none;">
                        <span>Descuento</span>
                        <strong id="display-descuento-val" style="color:#c0392b;">-$0.00</strong>
                    </div>
                    <div class="summary-row">
                        <span>Base imponible</span>
                        <strong id="display-base">$0.00</strong>
                    </div>
                    <div class="summary-row">
                        <span>Impuesto</span>
                        <span id="display-impuesto">$0.00</span>
                    </div>
                </div>

                <hr class="pay-divider">

                <!-- Cliente (compacto) -->
                <div class="pay-field cliente-dropdown">
                    <label><i class="fa fa-user-o"></i> Cliente</label>
                    <input type="text" class="form-control" id="search_cliente"
                           placeholder="Buscar cliente…"
                           value="<?php echo htmlspecialchars($clienteGeneralNombre, ENT_QUOTES, 'UTF-8'); ?>">
                    <ul class="cliente-list">
                        <?php foreach ($clientes as $cliente): ?>
                            <li data-value="<?php echo $cliente->id_cliente; ?>"><?php echo htmlspecialchars($cliente->nombre, ENT_QUOTES); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Alertas y botón -->
                <div id="alertas-dinamicas"></div>
                <button class="btn btn-success btn-registrar" onclick="enviarProductos()" title="Registrar venta (Ctrl+Enter)">
                    <i class="fa fa-check"></i> Registrar venta
                </button>
            </div>
        </div><!-- fin pos-pay-box -->

    </div><!-- fin pos-wrapper -->
</div>

<script>
const productosExistentes = [];
const inputBusquedaProducto = document.getElementById('producto_busqueda');
let cartItems = {};
let _buscarTimer = null;
let _ultimosResultados = [];
let _totalBruto = 0;

const URL_BUSCAR_POS = '<?php echo base_url("carrito/buscarPOS"); ?>';

function normalizarTexto(texto) {
    return (texto || '').toString().trim().toLowerCase();
}

/* ─── Productos ─────────────────────────────────────────── */

function renderListaProductos(productos) {
    var lista = document.getElementById('lista_productos');
    if (!productos || productos.length === 0) {
        lista.innerHTML = '<div style="text-align:center;padding:24px 8px;color:#bbb;font-size:12px;"><i class="fa fa-search" style="font-size:26px;display:block;margin-bottom:8px;"></i>Sin resultados</div>';
        return;
    }
    var html = '';
    productos.forEach(function(p) {
        var nombre  = normalizarTexto(p.nombre);
        var codigo  = normalizarTexto(p.codigo);
        var nombreE = p.nombre.replace(/'/g, "\\'");
        var stockClass = p.stock > 0 ? 'prod-item-stock' : 'prod-item-stock sin-stock';
        var stockLabel = p.stock > 0 ? p.stock + ' en stock' : 'Sin stock';
        html += '<div class="prod-item producto-item"' +
            ' data-id-producto="' + p.id + '"' +
            ' data-nombre-producto="' + nombre + '"' +
            ' data-precio-venta="' + p.precio + '"' +
            ' data-codigo-producto="' + codigo + '"' +
            ' onclick="seleccionarProducto(' + p.id + ', \'' + nombreE + '\', ' + p.precio + ')">' +
            '<img src="' + p.imagen + '" alt="">' +
            '<div class="prod-item-info">' +
              '<div class="prod-item-name">' + p.nombre + '</div>' +
              '<div class="prod-item-code">' + p.codigo + '</div>' +
              '<span class="' + stockClass + '">' + stockLabel + '</span>' +
            '</div>' +
            '<div class="prod-item-price">$' + parseFloat(p.precio).toFixed(2) + '</div>' +
            '</div>';
    });
    lista.innerHTML = html;
}

function buscarProductos(termino) {
    clearTimeout(_buscarTimer);
    var t = termino.trim();
    if (t === '') {
        document.getElementById('lista_productos').innerHTML =
            '<div id="pos-empty-hint" style="text-align:center;padding:28px 8px;color:#bbb;font-size:12px;">' +
            '<i class="fa fa-search" style="font-size:30px;display:block;margin-bottom:8px;"></i>' +
            'Escanea o escribe para buscar</div>';
        _ultimosResultados = [];
        return;
    }
    _buscarTimer = setTimeout(function() {
        $.post(URL_BUSCAR_POS, { q: t }, function(data) {
            _ultimosResultados = data || [];
            renderListaProductos(_ultimosResultados);
        }, 'json');
    }, 200);
}

function buscarProductosExacto(termino, callback) {
    $.post(URL_BUSCAR_POS, { q: termino }, function(data) {
        _ultimosResultados = data || [];
        renderListaProductos(_ultimosResultados);
        callback(_ultimosResultados);
    }, 'json');
}

function obtenerProductosVisibles() {
    return Array.from(document.querySelectorAll('#lista_productos .producto-item'));
}

function seleccionarProductoAutomaticamente(el) {
    if (!el) return false;
    seleccionarProducto(
        parseInt(el.dataset.idProducto, 10),
        el.dataset.nombreProducto,
        parseFloat(el.dataset.precioVenta)
    );
    limpiarBusqueda();
    return true;
}

function limpiarBusqueda() {
    inputBusquedaProducto.value = '';
    document.getElementById('lista_productos').innerHTML =
        '<div style="text-align:center;padding:28px 8px;color:#bbb;font-size:12px;">' +
        '<i class="fa fa-search" style="font-size:30px;display:block;margin-bottom:8px;"></i>' +
        'Escanea o escribe para buscar</div>';
    _ultimosResultados = [];
    inputBusquedaProducto.focus();
}

function seleccionarProducto(idProducto, nombreProducto, precioVenta) {
    if (cartItems[idProducto]) {
        cartItems[idProducto].cantidad++;
        renderCartRow(idProducto);
    } else {
        cartItems[idProducto] = { nombre: nombreProducto, precio: precioVenta, cantidad: 1 };
        productosExistentes.push(idProducto);
        renderCartRow(idProducto, true);
    }
    recalcularTotales();
    actualizarCartUI();
    limpiarBusqueda();
}

/* ─── Carrito ───────────────────────────────────────────── */

function renderCartRow(idProducto, esNueva) {
    var item = cartItems[idProducto];
    var tbody = document.getElementById('cart-tbody');
    if (esNueva) {
        var tr = document.createElement('tr');
        tr.id = 'cart-row-' + idProducto;
        tbody.appendChild(tr);
    }
    var tr = document.getElementById('cart-row-' + idProducto);
    var sub = (item.precio * item.cantidad).toFixed(2);
    tr.innerHTML =
        '<td title="' + item.nombre + '"><span class="cart-prod-name">' + item.nombre + '</span></td>' +
        '<td style="text-align:center;">' +
          '<div style="display:flex;align-items:center;justify-content:center;gap:3px;">' +
            '<button class="btn btn-default btn-qty" onclick="cambiarCantidad(' + idProducto + ', -1)">−</button>' +
            '<input type="number" class="qty-input" id="cantidad_' + idProducto + '" value="' + item.cantidad + '" min="1"' +
            ' oninput="setCantidad(' + idProducto + ', this.value)">' +
            '<button class="btn btn-default btn-qty" onclick="cambiarCantidad(' + idProducto + ', 1)">+</button>' +
          '</div>' +
        '</td>' +
        '<td style="color:#666;font-size:13px;">$' + parseFloat(item.precio).toFixed(2) + '</td>' +
        '<td class="cart-prod-subtotal" id="subtotal_' + idProducto + '">$' + sub + '</td>' +
        '<td><button class="btn-remove" onclick="eliminarProducto(' + idProducto + ')" title="Quitar"><i class="fa fa-times"></i></button></td>';

    if (esNueva) {
        tr.classList.add('cart-row-new');
        setTimeout(function() { tr.classList.remove('cart-row-new'); }, 700);
        var wrap = document.getElementById('cart-table-wrap');
        if (wrap) wrap.scrollTop = wrap.scrollHeight;
    }
}

function cambiarCantidad(idProducto, delta) {
    var item = cartItems[idProducto];
    if (!item) return;
    item.cantidad = Math.max(1, item.cantidad + delta);
    document.getElementById('cantidad_' + idProducto).value = item.cantidad;
    document.getElementById('subtotal_' + idProducto).textContent = '$' + (item.precio * item.cantidad).toFixed(2);
    recalcularTotales();
    setTimeout(function() { inputBusquedaProducto.focus(); }, 80);
}

function setCantidad(idProducto, val) {
    var item = cartItems[idProducto];
    if (!item) return;
    var qty = Math.max(1, parseFloat(val) || 1);
    item.cantidad = qty;
    document.getElementById('subtotal_' + idProducto).textContent = '$' + (item.precio * qty).toFixed(2);
    recalcularTotales();
}

function eliminarProducto(idProducto) {
    delete cartItems[idProducto];
    var idx = productosExistentes.indexOf(idProducto);
    if (idx !== -1) productosExistentes.splice(idx, 1);
    var tr = document.getElementById('cart-row-' + idProducto);
    if (tr) tr.remove();
    recalcularTotales();
    actualizarCartUI();
    inputBusquedaProducto.focus();
}

function actualizarCartUI() {
    var count = Object.keys(cartItems).length;
    document.getElementById('cart-count').textContent = count;
    var table = document.getElementById('cart-table');
    var emptyMsg = document.getElementById('cart-empty-msg');
    var btnVaciar = document.getElementById('btn-vaciar');
    if (count > 0) {
        table.style.display = '';
        emptyMsg.style.display = 'none';
        if (btnVaciar) btnVaciar.style.display = '';
    } else {
        table.style.display = 'none';
        emptyMsg.style.display = '';
        if (btnVaciar) btnVaciar.style.display = 'none';
    }
}


/* ─── Totales ───────────────────────────────────────────── */

function recalcularTotales() {
    var bruto = 0;
    Object.values(cartItems).forEach(function(item) {
        bruto += item.precio * item.cantidad;
    });
    _totalBruto = bruto;

    var descuento = parseFloat(document.getElementById('descuento_total').value) || 0;
    if (descuento < 0) descuento = 0;
    var totalConDescuento = Math.max(0, bruto - descuento);

    var imp = parseFloat(document.getElementById('imp').value) || 0;
    var divisor = (imp + 100) / 100;
    var base = totalConDescuento / divisor;
    var impValor = totalConDescuento - base;

    document.getElementById('subtotal').value = totalConDescuento.toFixed(2);
    document.getElementById('base_imponible').value = base.toFixed(2);
    document.getElementById('impuesto').value = impValor.toFixed(2);

    document.getElementById('display-total').textContent = totalConDescuento.toFixed(2);
    document.getElementById('display-bruto').textContent = '$' + bruto.toFixed(2);
    document.getElementById('display-base').textContent = '$' + base.toFixed(2);
    document.getElementById('display-impuesto').textContent = '$' + impValor.toFixed(2);

    // Mostrar fila descuento solo si hay descuento
    var rowDescuento = document.getElementById('row-descuento');
    if (descuento > 0) {
        rowDescuento.style.display = '';
        document.getElementById('display-descuento-val').textContent = '-$' + descuento.toFixed(2);
    } else {
        rowDescuento.style.display = 'none';
    }

    actualizarCambio();
}

function calcularsubTotalconDescuento() {
    recalcularTotales();
}

function actualizarCambio() {
    var tipoPago = document.getElementById('tipo_pago').value;
    var montoRecibido = parseFloat(document.getElementById('monto_recibido').value) || 0;
    var total = parseFloat(document.getElementById('subtotal').value) || 0;
    if (tipoPago !== 'contado') {
        document.getElementById('cambio').value = '';
        return;
    }
    var cambio = montoRecibido - total;
    document.getElementById('cambio').value = cambio > 0 ? cambio.toFixed(2) : '0.00';
}

function bloquearMetodoPago() {
    var tipoPago = document.getElementById('tipo_pago').value;
    var cobroSection = document.getElementById('cobro_contado_section');
    var cambioSection = document.getElementById('cambio_section');
    var anticipoSection = document.getElementById('anticipo_section');
    if (tipoPago === 'credito' || tipoPago === 'apartado') {
        cobroSection.style.display = 'none';
        cambioSection.style.display = 'none';
    } else {
        cobroSection.style.display = '';
        cambioSection.style.display = '';
    }
    anticipoSection.style.display = tipoPago === 'apartado' ? '' : 'none';
    if (tipoPago !== 'apartado') document.getElementById('anticipo').value = 0;
    actualizarCambio();
}

function validarAnticipo() {
    var anticipo = parseFloat(document.getElementById('anticipo').value) || 0;
    var total = parseFloat(document.getElementById('subtotal').value) || 0;
    if (anticipo < 0) document.getElementById('anticipo').value = 0;
    if (total > 0 && anticipo > total) document.getElementById('anticipo').value = total;
}


/* ─── Venta ─────────────────────────────────────────────── */

function enviarProductos() {
    var idCliente = document.getElementById('id_cliente').value;
    if (!idCliente) {
        mostrarAlerta('Selecciona un cliente antes de continuar.', 'warning');
        return;
    }
    var ids = Object.keys(cartItems);
    if (ids.length === 0) {
        mostrarAlerta('Agrega al menos un producto al carrito.', 'warning');
        return;
    }

    var tipoPago = document.getElementById('tipo_pago').value;
    var totalVenta = parseFloat(document.getElementById('subtotal').value) || 0;
    var montoRecibido = parseFloat(document.getElementById('monto_recibido').value) || 0;
    var cambio = parseFloat(document.getElementById('cambio').value) || 0;
    var anticipo = tipoPago === 'apartado' ? (parseFloat(document.getElementById('anticipo').value) || 0) : 0;

    if (tipoPago === 'contado' && montoRecibido < totalVenta) {
        mostrarAlerta('El monto recibido no puede ser menor al total a cobrar.', 'danger');
        document.getElementById('monto_recibido').focus();
        return;
    }
    if (tipoPago === 'apartado' && anticipo > totalVenta) {
        mostrarAlerta('El anticipo no puede ser mayor al total a cobrar.', 'danger');
        return;
    }

    var productosSeleccionados = ids.map(function(id) {
        var item = cartItems[id];
        return {
            nombre: item.nombre,
            precio_venta: item.precio,
            cantidad: item.cantidad,
            subtotal: item.precio * item.cantidad,
            id_producto: parseInt(id),
            id_cliente: parseInt(idCliente),
            total: totalVenta,
            descuento: parseFloat(document.getElementById('descuento_total').value) || 0,
            impuesto: parseFloat(document.getElementById('impuesto').value) || 0,
            base_imponible: parseFloat(document.getElementById('base_imponible').value) || 0,
            tipo_pago: tipoPago,
            id_metodo_pago: parseInt(document.getElementById('id_metodo_pago').value) || 0,
            monto_recibido: montoRecibido,
            cambio: cambio,
            tipo_venta: tipoPago === 'apartado' ? 'apartado' : 'normal',
            anticipo: anticipo
        };
    });

    $.ajax({
        url: '<?php echo base_url(); ?>Carrito/addNewVenta',
        type: 'POST',
        dataType: 'json',
        data: { productos: productosSeleccionados },
        success: function(data) {
            if (data.success) {
                actualizarSaldoCaja();
                if (tipoPago === 'apartado') {
                    alert('✓ Apartado registrado correctamente');
                    window.location.href = '<?php echo base_url(); ?>carrito/apartado_detalle/' + data.id_venta;
                    return;
                }
                document.getElementById('modal-id-venta').textContent = data.id_venta;
                document.getElementById('modal-total-venta').textContent = parseFloat(data.total || 0).toFixed(2);
                window._ventaIdModal = data.id_venta;
                $('#modalVentaExitosa').modal('show');
                limpiarCarrito();
            } else {
                mostrarAlerta(data.message || '✗ No se pudo registrar la venta', 'danger');
            }
        },
        error: function() {
            mostrarAlerta('✗ Error de conexión. Intenta nuevamente.', 'danger');
        }
    });
}

function mostrarAlerta(msg, tipo) {
    var $a = $('<div class="alert alert-' + tipo + ' alert-dismissable" style="margin-bottom:0;padding:7px 12px;font-size:13px;"><button type="button" class="close" data-dismiss="alert" style="font-size:16px;">×</button>' + msg + '</div>');
    $('#alertas-dinamicas').html($a);
    setTimeout(function() { $a.fadeOut(300, function() { $(this).remove(); }); }, 3500);
}

function limpiarCarrito() {
    cartItems = {};
    productosExistentes.length = 0;
    document.getElementById('cart-tbody').innerHTML = '';
    document.getElementById('descuento_total').value = '';
    document.getElementById('monto_recibido').value = '';
    document.getElementById('cambio').value = '';
    document.getElementById('anticipo').value = '0';
    recalcularTotales();
    actualizarCartUI();
}

function actualizarSaldoCaja() {
    $.getJSON('<?php echo base_url(); ?>Carrito/getSaldoCaja', function(data) {
        var saldo = parseFloat(data.saldo || 0);
        var fmt = '$' + saldo.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        $('button[data-target="#modalCaja"]').html('<i class="fa fa-money"></i> Caja: ' + fmt);
        $('#modalCaja strong').text(fmt);
        $('#saldo').val(saldo);
    });
}

function nuevaVenta() {
    $('#modalVentaExitosa').modal('hide');
    limpiarCarrito();
    document.getElementById('tipo_pago').value = 'contado';
    bloquearMetodoPago();
    document.getElementById('id_cliente').value = '<?php echo $clienteGeneralId; ?>';
    document.getElementById('search_cliente').value = '<?php echo htmlspecialchars($clienteGeneralNombre, ENT_QUOTES, 'UTF-8'); ?>';
    inputBusquedaProducto.value = '';
    buscarProductos('');
    inputBusquedaProducto.focus();
}

/* ─── Scanner: Enter en búsqueda ────────────────────────── */
inputBusquedaProducto.addEventListener('keydown', function(e) {
    if (e.key !== 'Enter') return;
    e.preventDefault();
    clearTimeout(_buscarTimer);
    var termino = e.target.value.trim();
    if (!termino) return;

    if (_ultimosResultados.length > 0) {
        var t = normalizarTexto(termino);
        var exacto = _ultimosResultados.find(function(p) {
            return normalizarTexto(p.codigo) === t || normalizarTexto(p.nombre) === t;
        });
        if (exacto) {
            seleccionarProducto(exacto.id, exacto.nombre, exacto.precio);
            limpiarBusqueda();
            return;
        }
        var visibles = obtenerProductosVisibles();
        if (visibles.length === 1) { seleccionarProductoAutomaticamente(visibles[0]); return; }
    }

    buscarProductosExacto(termino, function(resultados) {
        var t = normalizarTexto(termino);
        var exacto = resultados.find(function(p) {
            return normalizarTexto(p.codigo) === t || normalizarTexto(p.nombre) === t;
        });
        if (exacto) {
            seleccionarProducto(exacto.id, exacto.nombre, exacto.precio);
            limpiarBusqueda();
        } else if (resultados.length === 1) {
            var el = document.querySelector('#lista_productos .producto-item');
            if (el) seleccionarProductoAutomaticamente(el);
        }
    });
});

/* ─── Atajos de teclado ─────────────────────────────────── */
document.addEventListener('keydown', function(e) {
    if ($('.modal.in').length) return;

    // F9 → focus monto recibido
    if (e.key === 'F9') {
        e.preventDefault();
        var montoInput = document.getElementById('monto_recibido');
        if (montoInput && montoInput.closest('#cobro_contado_section').style.display !== 'none') {
            montoInput.focus();
            montoInput.select();
        }
    }

    // Ctrl+Enter → registrar venta
    if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
        e.preventDefault();
        if (Object.keys(cartItems).length > 0) enviarProductos();
    }

    // Escape → focus búsqueda
    if (e.key === 'Escape') {
        if ($(document.activeElement).is('input, select')) return;
        inputBusquedaProducto.focus();
    }
});

// Enter en monto_recibido → registrar venta
document.getElementById('monto_recibido').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); enviarProductos(); }
});

/* ─── Init ──────────────────────────────────────────────── */
$(document).ready(function() {
    var tipoPagoParam = new URLSearchParams(window.location.search).get('tipo_pago');
    if (tipoPagoParam && ['contado', 'credito', 'apartado'].includes(tipoPagoParam)) {
        document.getElementById('tipo_pago').value = tipoPagoParam;
    }
    bloquearMetodoPago();
    setTimeout(function() { inputBusquedaProducto.focus(); }, 150);

    // Cliente autocomplete
    $('#search_cliente').on('focus', function() { $('.cliente-list').show(); });
    $('#search_cliente').on('input', function() {
        var t = $(this).val().toLowerCase();
        $('.cliente-list li').each(function() { $(this).toggle($(this).text().toLowerCase().includes(t)); });
        $('.cliente-list').show();
    });
    $('.cliente-list li').on('click', function() {
        $('#id_cliente').val($(this).data('value'));
        $('#search_cliente').val($(this).text());
        $('.cliente-list').hide();
    });
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.cliente-dropdown').length) $('.cliente-list').hide();
    });

    // Siempre devolver foco al buscador si el click no fue en un input que requiere teclado
    var _inputsConFoco = ['monto_recibido', 'descuento_total', 'anticipo', 'search_cliente'];
    document.addEventListener('click', function(e) {
        var t = e.target;
        if (t.tagName === 'SELECT') return;
        if (t.classList.contains('qty-input')) return;
        if (_inputsConFoco.indexOf(t.id) !== -1) return;
        if (t === inputBusquedaProducto) return;
        // Pequeño delay para que el click complete su acción antes de mover el foco
        setTimeout(function() {
            if (document.activeElement === document.body ||
                document.activeElement === t ||
                !['INPUT','SELECT','TEXTAREA'].includes(document.activeElement.tagName)) {
                inputBusquedaProducto.focus();
            }
        }, 100);
    });
});
</script>
