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
/* ── Tabs móvil ──────────────────────────────────────────── */
.pos-mobile-tabs {
    display:none;
}
@media(max-width:768px) {
    .pos-mobile-tabs {
        display:flex;
        background:#2c3e50;
        position:sticky;
        top:0;
        z-index:50;
    }
    .pos-mobile-tabs button {
        flex:1;
        padding:12px 0;
        border:none;
        background:transparent;
        color:#aaa;
        font-size:13px;
        font-weight:600;
        cursor:pointer;
        border-bottom:3px solid transparent;
        transition:all .15s;
    }
    .pos-mobile-tabs button.active {
        color:#fff;
        border-bottom-color:#27ae60;
    }
    .pos-mobile-tabs .tab-badge {
        background:#27ae60;
        color:#fff;
        border-radius:10px;
        padding:1px 6px;
        font-size:11px;
        margin-left:4px;
    }
    /* Paneles ocultos en móvil salvo el activo */
    .pos-search-panel.mobile-hidden,
    .pos-cart-panel.mobile-hidden {
        display:none !important;
    }
}

/* ── POS Layout ──────────────────────────────────────────── */
.pos-wrapper { display:flex; gap:12px; padding:10px; height:calc(100vh - 100px); }

/* Panel izquierdo: búsqueda + lista */
.pos-search-panel {
    flex:0 0 42%;
    display:flex;
    flex-direction:column;
    background:#fff;
    border-radius:6px;
    box-shadow:0 1px 4px rgba(0,0,0,.15);
    overflow:hidden;
}
.pos-search-header {
    padding:10px 12px 8px;
    background:#2c3e50;
    color:#fff;
}
.pos-search-header h4 { margin:0; font-size:15px; }
.pos-search-body { padding:10px 12px; flex-shrink:0; }
.pos-product-list {
    flex:1;
    overflow-y:auto;
    padding:0 8px 8px;
}

/* Panel derecho: carrito + pago */
.pos-cart-panel {
    flex:1;
    display:flex;
    flex-direction:column;
    gap:8px;
}
.pos-cart-box {
    background:#fff;
    border-radius:6px;
    box-shadow:0 1px 4px rgba(0,0,0,.15);
    display:flex;
    flex-direction:column;
    flex:1;
    overflow:hidden;
}
.pos-cart-header {
    background:#1a252f;
    color:#fff;
    padding:8px 14px;
    font-size:14px;
    font-weight:600;
    flex-shrink:0;
}
.pos-cart-table-wrap {
    flex:1;
    overflow-y:auto;
}
.pos-cart-table-wrap table {
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}
.pos-cart-table-wrap th {
    background:#ecf0f1;
    padding:7px 8px;
    text-align:left;
    border-bottom:2px solid #bdc3c7;
    white-space:nowrap;
    position:sticky;
    top:0;
    z-index:1;
}
.pos-cart-table-wrap td {
    padding:5px 8px;
    border-bottom:1px solid #f0f0f0;
    vertical-align:middle;
}
.pos-cart-table-wrap tr:hover td { background:#fafafa; }
.qty-input {
    width:60px;
    text-align:center;
    border:1px solid #ccc;
    border-radius:3px;
    padding:2px 4px;
    font-size:13px;
}
.btn-qty { padding:1px 7px; font-size:13px; line-height:1.4; }
.btn-remove { color:#c0392b; background:none; border:none; font-size:16px; cursor:pointer; padding:0 4px; }
.btn-remove:hover { color:#e74c3c; }

/* Panel de pago */
.pos-pay-box {
    background:#fff;
    border-radius:6px;
    box-shadow:0 1px 4px rgba(0,0,0,.15);
    padding:12px 14px;
    flex-shrink:0;
}
.pos-total-display {
    background:#27ae60;
    color:#fff;
    border-radius:6px;
    padding:10px 14px;
    text-align:center;
    margin-bottom:10px;
}
.pos-total-display .total-label { font-size:12px; opacity:.85; text-transform:uppercase; letter-spacing:.5px; }
.pos-total-display .total-amount { font-size:32px; font-weight:700; line-height:1.1; }

.pay-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
.pay-field label { font-size:11px; font-weight:600; color:#555; margin-bottom:2px; display:block; text-transform:uppercase; }
.pay-field input, .pay-field select { font-size:13px; height:32px; padding:0 8px; }
.pay-field-full { grid-column:1/-1; }

.btn-registrar {
    width:100%;
    padding:11px;
    font-size:16px;
    font-weight:700;
    border-radius:5px;
    margin-top:10px;
    letter-spacing:.3px;
}

/* Producto item en búsqueda */
.prod-item {
    display:flex;
    align-items:center;
    gap:8px;
    padding:6px 8px;
    border-radius:4px;
    cursor:pointer;
    border:1px solid transparent;
    transition:background .12s;
    margin-bottom:3px;
}
.prod-item:hover { background:#eaf4fb; border-color:#aed6f1; }
.prod-item img { width:36px; height:36px; object-fit:cover; border-radius:3px; flex-shrink:0; }
.prod-item-info { flex:1; min-width:0; }
.prod-item-name { font-size:13px; font-weight:600; color:#2c3e50; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.prod-item-code { font-size:11px; color:#888; }
.prod-item-price { font-size:13px; font-weight:700; color:#27ae60; white-space:nowrap; }
.prod-item-add { flex-shrink:0; }

/* Cliente selector */
.cliente-dropdown {
    position:relative;
}
.cliente-list {
    list-style:none;
    padding:0;
    margin:0;
    position:absolute;
    width:100%;
    max-height:160px;
    overflow-y:auto;
    border:1px solid #ccc;
    border-radius:4px;
    display:none;
    z-index:100;
    background:#fff;
    box-shadow:0 3px 10px rgba(0,0,0,.15);
}
.cliente-list li {
    padding:7px 10px;
    cursor:pointer;
    font-size:13px;
    border-bottom:1px solid #f5f5f5;
}
.cliente-list li:hover { background:#eaf4fb; }

/* Resumen compacto */
.summary-row {
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-size:12px;
    color:#555;
    padding:2px 0;
}
.summary-row.highlight { font-size:13px; font-weight:600; color:#333; }

/* Empty cart message */
.cart-empty {
    text-align:center;
    color:#aaa;
    padding:30px 0;
    font-size:13px;
}
.cart-empty i { font-size:36px; display:block; margin-bottom:8px; }

/* Header botón caja */
.pos-topbar {
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:6px 12px;
    background:#fff;
    border-bottom:1px solid #e0e0e0;
    margin-bottom:0;
}
.pos-topbar h4 { margin:0; font-size:15px; color:#2c3e50; }

/* ── Responsive ───────────────────────────────────────────── */
@media(max-width:768px) {
    .pos-wrapper {
        flex-direction:column;
        height:auto;
        padding:6px;
        gap:8px;
    }
    .pos-search-panel {
        flex:none;
        /* En móvil la lista de búsqueda es colapsable */
    }
    .pos-product-list {
        max-height:220px;
    }
    .pay-grid { grid-template-columns:1fr 1fr; }
    .pos-total-display .total-amount { font-size:28px; }
    .btn-registrar { font-size:15px; padding:13px; }

    /* Botones +/- más grandes en táctil */
    .btn-qty { padding:4px 12px; font-size:15px; }
    .qty-input { width:48px; font-size:14px; }
    .pos-cart-table-wrap td, .pos-cart-table-wrap th { padding:6px 5px; font-size:12px; }

    /* Taps más grandes en lista de productos */
    .prod-item { padding:9px 8px; }
    .prod-item img { width:40px; height:40px; }
    .prod-item-name { font-size:14px; }
    .prod-item-price { font-size:14px; }
    .prod-item-add i { font-size:22px; }

    /* Inputs táctiles */
    .pay-field input, .pay-field select { height:38px; font-size:14px; }
    #monto_recibido, #anticipo { font-size:16px; height:42px; }
}

/* Tablet landscape */
@media(min-width:769px) and (max-width:1024px) {
    .pos-search-panel { flex:0 0 38%; }
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

    <!-- Modal estado de caja -->
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
                    <p style="font-size:28px; font-weight:700; color:#27ae60; margin:6px 0;">$<span id="modal-total-venta"></span></p>
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

    <!-- Tabs solo visibles en móvil -->
    <div class="pos-mobile-tabs">
        <button id="tab-buscar" class="active" onclick="switchTab('buscar')">
            <i class="fa fa-search"></i> Buscar
        </button>
        <button id="tab-carrito" onclick="switchTab('carrito')">
            <i class="fa fa-shopping-basket"></i> Carrito
            <span class="tab-badge" id="tab-cart-count">0</span>
        </button>
    </div>

    <!-- Layout principal -->
    <div class="pos-wrapper">

        <!-- ═══ PANEL IZQUIERDO: Búsqueda ═══ -->
        <div class="pos-search-panel">
            <div class="pos-search-header">
                <h4><i class="fa fa-search"></i> Buscar producto</h4>
            </div>
            <div class="pos-search-body">
                <input type="text" class="form-control" id="producto_busqueda"
                       placeholder="Nombre o código de barras…" autofocus
                       oninput="buscarProductos(this.value)">
                <div style="margin-top:8px;" class="cliente-dropdown">
                    <label style="font-size:11px; font-weight:600; color:#eee; margin-bottom:2px; display:block; text-transform:uppercase;">
                        <i class="fa fa-user-o"></i> Cliente
                    </label>
                    <input type="text" class="form-control input-sm" id="search_cliente"
                           placeholder="Buscar cliente…"
                           value="<?php echo htmlspecialchars($clienteGeneralNombre, ENT_QUOTES, 'UTF-8'); ?>">
                    <ul class="cliente-list">
                        <?php foreach ($clientes as $cliente): ?>
                            <li data-value="<?php echo $cliente->id_cliente; ?>"><?php echo htmlspecialchars($cliente->nombre, ENT_QUOTES); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Lista de productos -->
            <div class="pos-product-list" id="lista_productos">
                <?php foreach ($productos as $key => $producto):
                    $nombreProducto = $producto->nombre_producto;
                    $codigoProducto = $producto->codigo;
                    $imagenProducto = empty($producto->imagen) ? '11carrito22.png' : $producto->imagen;
                ?>
                <div class="prod-item producto-item"
                     id="producto_<?php echo $key; ?>"
                     data-id-producto="<?php echo $producto->id_producto; ?>"
                     data-nombre-producto="<?php echo htmlspecialchars(strtolower($nombreProducto), ENT_QUOTES, 'UTF-8'); ?>"
                     data-precio-venta="<?php echo $producto->precio_venta; ?>"
                     data-codigo-producto="<?php echo htmlspecialchars(strtolower($codigoProducto), ENT_QUOTES, 'UTF-8'); ?>"
                     onclick="seleccionarProducto(<?php echo $producto->id_producto; ?>, '<?php echo htmlspecialchars(addslashes(strtolower($nombreProducto)), ENT_QUOTES, 'UTF-8'); ?>', <?php echo $producto->precio_venta; ?>)">
                    <img src="<?php echo base_url('uploads/' . $imagenProducto); ?>" alt="">
                    <div class="prod-item-info">
                        <div class="prod-item-name"><?php echo htmlspecialchars($nombreProducto, ENT_QUOTES); ?></div>
                        <div class="prod-item-code"><?php echo htmlspecialchars($codigoProducto, ENT_QUOTES); ?></div>
                    </div>
                    <div class="prod-item-price">$<?php echo number_format($producto->precio_venta, 2); ?></div>
                    <div class="prod-item-add"><i class="fa fa-plus-circle text-primary" style="font-size:18px;"></i></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ═══ PANEL DERECHO: Carrito + Pago ═══ -->
        <div class="pos-cart-panel">

            <!-- Carrito -->
            <div class="pos-cart-box">
                <div class="pos-cart-header">
                    <i class="fa fa-shopping-basket"></i> Carrito
                    <span id="cart-count" class="badge" style="background:#27ae60; margin-left:6px;">0</span>
                </div>
                <div class="pos-cart-table-wrap" id="cart-table-wrap">
                    <div class="cart-empty" id="cart-empty-msg">
                        <i class="fa fa-shopping-cart"></i>
                        Sin productos. Busca o escanea para agregar.
                    </div>
                    <table id="cart-table" style="display:none;">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th style="width:90px;">Cantidad</th>
                                <th style="width:80px;">Precio</th>
                                <th style="width:80px;">Subtotal</th>
                                <th style="width:30px;"></th>
                            </tr>
                        </thead>
                        <tbody id="cart-tbody"></tbody>
                    </table>
                </div>
            </div>

            <!-- Panel de pago -->
            <div class="pos-pay-box">
                <!-- Total grande -->
                <div class="pos-total-display">
                    <div class="total-label">Total a cobrar</div>
                    <div class="total-amount">$<span id="display-total">0.00</span></div>
                </div>

                <!-- Campos de pago -->
                <div class="pay-grid">
                    <div class="pay-field">
                        <label>Tipo de pago</label>
                        <select class="form-control" id="tipo_pago" onchange="bloquearMetodoPago()">
                            <option value="contado">Al contado</option>
                            <option value="credito">A crédito</option>
                            <option value="apartado">Apartado</option>
                        </select>
                    </div>
                    <div class="pay-field">
                        <label>Método de pago</label>
                        <select class="form-control" id="id_metodo_pago">
                            <?php foreach ($configuracion['metodo_pago'] as $metodo): ?>
                                <option value="<?php echo $metodo->id_metodo_pago; ?>"><?php echo $metodo->nombre_metodo_pago; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Resumen de totales -->
                    <div class="pay-field pay-field-full" style="border-top:1px solid #eee; padding-top:6px;">
                        <div class="summary-row">
                            <span>Descuento ($)</span>
                            <input type="number" id="descuento_total" value="" min="0" step="0.01"
                                   style="width:100px; text-align:right; border:1px solid #ddd; border-radius:3px; padding:2px 6px; font-size:13px;"
                                   oninput="calcularsubTotalconDescuento()" placeholder="0.00">
                        </div>
                        <div class="summary-row" style="margin-top:4px;">
                            <span>Subtotal neto</span>
                            <strong id="display-base">$0.00</strong>
                        </div>
                        <div class="summary-row">
                            <span>Impuesto</span>
                            <span id="display-impuesto">$0.00</span>
                        </div>
                    </div>

                    <!-- Contado -->
                    <div class="pay-field" id="cobro_contado_section">
                        <label>Monto recibido ($)</label>
                        <input type="number" class="form-control" id="monto_recibido" min="0" step="0.01"
                               inputmode="decimal" oninput="actualizarCambio()" placeholder="0.00">
                    </div>
                    <div class="pay-field" id="cambio_section">
                        <label>Cambio ($)</label>
                        <input type="number" class="form-control" id="cambio" readonly tabindex="-1" placeholder="0.00"
                               style="background:#f9f9f9; font-weight:700; color:#27ae60;">
                    </div>

                    <!-- Apartado -->
                    <div class="pay-field pay-field-full" id="anticipo_section" style="display:none;">
                        <label>Anticipo / Enganche ($)</label>
                        <input type="number" class="form-control" id="anticipo" value="" min="0" step="0.01"
                               inputmode="decimal" oninput="validarAnticipo()">
                        <small class="text-muted"><i class="fa fa-info-circle"></i> El producto queda reservado hasta el pago total.</small>
                    </div>
                </div>

                <!-- Alertas y botón registrar -->
                <div id="alertas-dinamicas" style="margin-top:6px;"></div>
                <button class="btn btn-success btn-registrar" onclick="enviarProductos()">
                    <i class="fa fa-check"></i> Registrar venta
                </button>
            </div>

        </div><!-- fin pos-cart-panel -->
    </div><!-- fin pos-wrapper -->

    <!-- Inputs ocultos para compatibilidad con lógica existente -->
    <input type="hidden" id="subtotal" value="0">
    <input type="hidden" id="base_imponible" value="0">
    <input type="hidden" id="impuesto" value="0">
</div>

<script>
const productosExistentes = [];
const inputBusquedaProducto = document.getElementById('producto_busqueda');
let cartItems = {}; // {idProducto: {nombre, precio, cantidad}}

function normalizarTexto(texto) {
    return (texto || '').toString().trim().toLowerCase();
}

function obtenerProductosVisibles() {
    return Array.from(document.querySelectorAll('#lista_productos .producto-item'))
        .filter(p => p.style.display !== 'none');
}

function buscarProductos(termino) {
    var t = normalizarTexto(termino);
    var exacta = null;
    document.querySelectorAll('#lista_productos .producto-item').forEach(function(p) {
        var nombre = normalizarTexto(p.dataset.nombreProducto);
        var codigo = normalizarTexto(p.dataset.codigoProducto);
        var coincide = t === '' || nombre.includes(t) || codigo.includes(t);
        p.style.display = coincide ? '' : 'none';
        if (t !== '' && (codigo === t || nombre === t)) exacta = p;
    });
    return exacta;
}

function seleccionarProductoAutomaticamente(el) {
    if (!el) return false;
    seleccionarProducto(
        parseInt(el.dataset.idProducto, 10),
        el.dataset.nombreProducto,
        parseFloat(el.dataset.precioVenta)
    );
    inputBusquedaProducto.value = '';
    buscarProductos('');
    inputBusquedaProducto.focus();
    return true;
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
    // En móvil: cambiar al carrito para ver lo agregado
    if (window.innerWidth <= 768) switchTab('carrito');
}

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
    tr.innerHTML = `
        <td style="max-width:160px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="${item.nombre}">${item.nombre}</td>
        <td>
            <div style="display:flex; align-items:center; gap:3px;">
                <button class="btn btn-default btn-qty" onclick="cambiarCantidad(${idProducto}, -1)">-</button>
                <input type="number" class="qty-input" id="cantidad_${idProducto}" value="${item.cantidad}" min="1"
                       oninput="setCantidad(${idProducto}, this.value)">
                <button class="btn btn-default btn-qty" onclick="cambiarCantidad(${idProducto}, 1)">+</button>
            </div>
        </td>
        <td>$${parseFloat(item.precio).toFixed(2)}</td>
        <td id="subtotal_${idProducto}">$${sub}</td>
        <td><button class="btn-remove" onclick="eliminarProducto(${idProducto})" title="Quitar"><i class="fa fa-times"></i></button></td>
    `;
}

function cambiarCantidad(idProducto, delta) {
    var item = cartItems[idProducto];
    if (!item) return;
    item.cantidad = Math.max(1, item.cantidad + delta);
    document.getElementById('cantidad_' + idProducto).value = item.cantidad;
    document.getElementById('subtotal_' + idProducto).textContent = '$' + (item.precio * item.cantidad).toFixed(2);
    recalcularTotales();
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
}

function actualizarCartUI() {
    var count = Object.keys(cartItems).length;
    document.getElementById('cart-count').textContent = count;
    var tabBadge = document.getElementById('tab-cart-count');
    if (tabBadge) tabBadge.textContent = count;
    var table = document.getElementById('cart-table');
    var emptyMsg = document.getElementById('cart-empty-msg');
    if (count > 0) {
        table.style.display = '';
        emptyMsg.style.display = 'none';
    } else {
        table.style.display = 'none';
        emptyMsg.style.display = '';
    }
}

var _activeTab = 'buscar';
function switchTab(tab) {
    _activeTab = tab;
    var searchPanel = document.querySelector('.pos-search-panel');
    var cartPanel = document.querySelector('.pos-cart-panel');
    var tabBuscar = document.getElementById('tab-buscar');
    var tabCarrito = document.getElementById('tab-carrito');
    if (tab === 'buscar') {
        searchPanel.classList.remove('mobile-hidden');
        cartPanel.classList.add('mobile-hidden');
        tabBuscar.classList.add('active');
        tabCarrito.classList.remove('active');
        inputBusquedaProducto.focus();
    } else {
        searchPanel.classList.add('mobile-hidden');
        cartPanel.classList.remove('mobile-hidden');
        tabBuscar.classList.remove('active');
        tabCarrito.classList.add('active');
    }
}

function recalcularTotales() {
    var total = 0;
    Object.values(cartItems).forEach(function(item) {
        total += item.precio * item.cantidad;
    });

    var descuento = parseFloat(document.getElementById('descuento_total').value) || 0;
    if (descuento < 0) descuento = 0;
    var totalConDescuento = Math.max(0, total - descuento);

    var imp = parseFloat(document.getElementById('imp').value) || 0;
    var divisor = (imp + 100) / 100;
    var base = totalConDescuento / divisor;
    var impValor = totalConDescuento - base;

    // Actualizar inputs ocultos (compatibilidad con enviarProductos)
    document.getElementById('subtotal').value = totalConDescuento.toFixed(2);
    document.getElementById('base_imponible').value = base.toFixed(2);
    document.getElementById('impuesto').value = impValor.toFixed(2);

    // Actualizar display
    document.getElementById('display-total').textContent = totalConDescuento.toFixed(2);
    document.getElementById('display-base').textContent = '$' + base.toFixed(2);
    document.getElementById('display-impuesto').textContent = '$' + impValor.toFixed(2);

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
    var $a = $('<div class="alert alert-' + tipo + ' alert-dismissable" style="margin-bottom:0; padding:7px 12px; font-size:13px;"><button type="button" class="close" data-dismiss="alert" style="font-size:16px;">×</button>' + msg + '</div>');
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

// Enter en búsqueda → agregar producto
inputBusquedaProducto.addEventListener('keydown', function(e) {
    if (e.key !== 'Enter') return;
    e.preventDefault();
    var exacta = buscarProductos(e.target.value);
    if (!seleccionarProductoAutomaticamente(exacta)) {
        var visibles = obtenerProductosVisibles();
        if (visibles.length === 1) seleccionarProductoAutomaticamente(visibles[0]);
    }
});

$(document).ready(function() {
    // Tipo de pago desde URL
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
        $('.cliente-list li').each(function() {
            $(this).toggle($(this).text().toLowerCase().includes(t));
        });
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
});
</script>
