<?php
/**
 * @var array $proveedores
 * @var array $productos
 * @var array $variantes_por_producto  // [id_producto => [ {id_variante, talla, stock, precio_compra}, ... ]]
 */
?>
<style>
#tabla-disponibles-wrap { max-height: 320px; overflow-y: auto; }
#tabla-disponibles tbody tr.oculto { display: none; }
.badge.bg-green  { background-color: #00a65a; }
.badge.bg-orange { background-color: #f39c12; }
.badge.bg-red    { background-color: #dd4b39; }
.compra-table input.qty,
.compra-table input.precio { width: 110px; }
.compra-table select.variante { min-width: 160px; }
.subtotal-cell { font-weight: 600; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-truck"></i> Nueva compra <small>Registrar compra</small></h1>
    </section>

    <section class="content">

        <div id="alertas"></div>

        <?php $this->load->helper('form'); ?>
        <?php if ($error = $this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button><?php echo $error; ?>
        </div>
        <?php endif; ?>
        <?php if ($success = $this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button><?php echo $success; ?>
        </div>
        <?php endif; ?>

        <!-- Step 1: proveedor + nota -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-info-circle"></i> Información de la compra</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Proveedor <span class="text-danger">*</span></label>
                            <select class="form-control" id="id_proveedor">
                                <option value="">— Seleccionar proveedor —</option>
                                <?php foreach ($proveedores as $p): ?>
                                <option value="<?php echo (int)$p->id_proveedor; ?>"><?php echo htmlspecialchars($p->nombre, ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Nota <small class="text-muted">(opcional)</small></label>
                            <input type="text" class="form-control" id="nota" maxlength="256">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: buscar productos -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> Productos</h3>
                <div class="box-tools pull-right">
                    <input type="text" class="form-control input-sm" id="buscador"
                           placeholder="Nombre o código…" oninput="buscarProductos(this.value)"
                           style="width:240px; display:inline-block;">
                </div>
            </div>
            <div class="box-body no-padding" id="tabla-disponibles-wrap">
                <table class="table table-condensed table-hover" id="tabla-disponibles">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Código</th>
                            <th style="width:90px">Stock</th>
                            <th style="width:80px">Tallas</th>
                            <th style="width:90px"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($productos as $p):
                        $stock = (int)$p->stock;
                        $tieneVar = (int)($p->tiene_variantes ?? 0) === 1;
                        $badgeClass = $stock === 0 ? 'bg-red' : ($stock <= 5 ? 'bg-orange' : 'bg-green');
                        $nombreEsc = htmlspecialchars($p->nombre_producto, ENT_QUOTES, 'UTF-8');
                        $codigoEsc = htmlspecialchars($p->codigo, ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr data-nombre="<?php echo strtolower($nombreEsc); ?>"
                        data-codigo="<?php echo strtolower($codigoEsc); ?>">
                        <td><?php echo $nombreEsc; ?></td>
                        <td><code><?php echo $codigoEsc; ?></code></td>
                        <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $stock; ?></span></td>
                        <td><?php echo $tieneVar ? '<span class="label label-info">Sí</span>' : '<span class="text-muted">—</span>'; ?></td>
                        <td>
                            <button type="button" class="btn btn-xs btn-primary"
                                    onclick="agregarProducto(<?php echo (int)$p->id_producto; ?>)">
                                <i class="fa fa-plus"></i> Agregar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Step 3: carrito de compra -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-shopping-cart"></i> Detalle (<span id="contador">0</span>)</h3>
                <div class="box-tools pull-right">
                    <strong>Total:</strong> <span id="total-compra">0.00</span>
                </div>
            </div>
            <div class="box-body no-padding">
                <table class="table table-condensed compra-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Talla</th>
                            <th>Precio compra</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th style="width:60px"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody-carrito">
                        <tr id="fila-vacia">
                            <td colspan="6" class="text-center text-muted" style="padding:30px 20px">
                                <i class="fa fa-arrow-up fa-2x" style="display:block;margin-bottom:6px;opacity:.4"></i>
                                Agrega productos desde la lista de arriba.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="box-footer text-right">
                <button type="button" class="btn btn-success btn-lg" id="btn-registrar" onclick="enviarCompra()">
                    <i class="fa fa-check"></i> Registrar compra
                </button>
            </div>
        </div>

    </section>
</div>

<script>
(function () {
    'use strict';

    // Diccionarios server-rendered (sin XHR adicionales)
    var PRODUCTOS = {
        <?php foreach ($productos as $p):
            $id = (int)$p->id_producto;
            $tv = (int)($p->tiene_variantes ?? 0);
        ?>
        <?php echo $id; ?>: {
            id: <?php echo $id; ?>,
            nombre: <?php echo json_encode($p->nombre_producto); ?>,
            codigo: <?php echo json_encode($p->codigo); ?>,
            tieneVariantes: <?php echo $tv === 1 ? 'true' : 'false'; ?>,
            precioCompraDefault: <?php echo json_encode((float)$p->precio_compra); ?>
        }<?php echo next($productos) !== false ? ',' : ''; ?>
        <?php endforeach; reset($productos); ?>
    };

    var VARIANTES = <?php echo json_encode($variantes_por_producto, JSON_NUMERIC_CHECK); ?>;

    // Estado: clave compuesta id_producto:id_variante
    var carrito = {};

    function esc(s) {
        return String(s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    window.buscarProductos = function (termino) {
        var t = termino.toLowerCase().trim();
        document.querySelectorAll('#tabla-disponibles tbody tr').forEach(function (tr) {
            if (!t) { tr.classList.remove('oculto'); return; }
            var match = tr.getAttribute('data-nombre').indexOf(t) !== -1 ||
                        tr.getAttribute('data-codigo').indexOf(t) !== -1;
            tr.classList.toggle('oculto', !match);
        });
    };

    window.agregarProducto = function (idProducto) {
        var prod = PRODUCTOS[idProducto];
        if (!prod) return;

        if (prod.tieneVariantes) {
            // Una línea por variante; preseleccionamos la primera
            var variantes = VARIANTES[idProducto] || [];
            if (!variantes.length) {
                mostrarAlerta('warning', 'Este producto está marcado con variantes pero no tiene tallas activas.');
                return;
            }
            var v = variantes[0];
            agregarLinea(idProducto, v.id_variante);
        } else {
            agregarLinea(idProducto, 0);
        }
    };

    function claveLinea(id_producto, id_variante) {
        return id_producto + ':' + (id_variante || 0);
    }

    function agregarLinea(id_producto, id_variante) {
        var key = claveLinea(id_producto, id_variante);
        if (carrito[key]) {
            carrito[key].cantidad += 1;
        } else {
            var prod = PRODUCTOS[id_producto];
            var precioInicial = prod.precioCompraDefault;
            if (id_variante > 0) {
                var v = (VARIANTES[id_producto] || []).find(function (x) { return x.id_variante === id_variante; });
                if (v && v.precio_compra) precioInicial = v.precio_compra;
            }
            carrito[key] = {
                id_producto: id_producto,
                id_variante: id_variante,
                cantidad: 1,
                precio_compra: precioInicial
            };
        }
        render();
    }

    window.cambiarVariante = function (oldKey, newIdVariante) {
        var item = carrito[oldKey];
        if (!item) return;
        var newKey = claveLinea(item.id_producto, newIdVariante);
        if (newKey === oldKey) return;
        if (carrito[newKey]) {
            // Fusionar cantidades si ya existe esa talla
            carrito[newKey].cantidad += item.cantidad;
            delete carrito[oldKey];
        } else {
            item.id_variante = newIdVariante;
            // Actualizar precio sugerido si la variante tiene su propio precio
            var v = (VARIANTES[item.id_producto] || []).find(function (x) { return x.id_variante === newIdVariante; });
            if (v && v.precio_compra) item.precio_compra = v.precio_compra;
            carrito[newKey] = item;
            delete carrito[oldKey];
        }
        render();
    };

    window.cambiarCantidad = function (key, val) {
        var n = Math.max(1, parseInt(val, 10) || 1);
        carrito[key].cantidad = n;
        actualizarSubtotalCelda(key);
    };

    window.cambiarPrecio = function (key, val) {
        var p = parseFloat(val);
        if (isNaN(p) || p < 0) p = 0;
        carrito[key].precio_compra = p;
        actualizarSubtotalCelda(key);
    };

    window.quitarLinea = function (key) {
        delete carrito[key];
        render();
    };

    function actualizarSubtotalCelda(key) {
        var item = carrito[key];
        var sub = item.cantidad * item.precio_compra;
        var cell = document.getElementById('sub_' + cssKey(key));
        if (cell) cell.textContent = sub.toFixed(2);
        actualizarTotal();
    }

    function actualizarTotal() {
        var total = 0;
        Object.keys(carrito).forEach(function (k) {
            total += carrito[k].cantidad * carrito[k].precio_compra;
        });
        document.getElementById('total-compra').textContent = total.toFixed(2);
    }

    function cssKey(k) { return k.replace(':', '_'); }

    function render() {
        var keys = Object.keys(carrito);
        document.getElementById('contador').textContent = keys.length;
        var tbody = document.getElementById('tbody-carrito');

        if (keys.length === 0) {
            tbody.innerHTML = '<tr id="fila-vacia"><td colspan="6" class="text-center text-muted" style="padding:30px 20px">' +
                '<i class="fa fa-arrow-up fa-2x" style="display:block;margin-bottom:6px;opacity:.4"></i>' +
                'Agrega productos desde la lista de arriba.</td></tr>';
            actualizarTotal();
            return;
        }

        var html = '';
        keys.forEach(function (key) {
            var item = carrito[key];
            var prod = PRODUCTOS[item.id_producto];
            var sub  = item.cantidad * item.precio_compra;
            var ck   = cssKey(key);

            var tallaCell;
            if (prod.tieneVariantes) {
                var opts = (VARIANTES[item.id_producto] || []).map(function (v) {
                    var sel = v.id_variante === item.id_variante ? ' selected' : '';
                    return '<option value="' + v.id_variante + '"' + sel + '>' + esc(v.talla) + '</option>';
                }).join('');
                tallaCell = '<select class="form-control input-sm variante" ' +
                    'onchange="cambiarVariante(\'' + key + '\', parseInt(this.value,10))">' + opts + '</select>';
            } else {
                tallaCell = '<span class="text-muted">—</span>';
            }

            html += '<tr id="row_' + ck + '">' +
                '<td>' + esc(prod.nombre) + '<br><small class="text-muted"><code>' + esc(prod.codigo) + '</code></small></td>' +
                '<td>' + tallaCell + '</td>' +
                '<td><input type="number" class="form-control input-sm precio" min="0" step="0.01" ' +
                    'value="' + Number(item.precio_compra).toFixed(2) + '" ' +
                    'oninput="cambiarPrecio(\'' + key + '\', this.value)"></td>' +
                '<td><input type="number" class="form-control input-sm qty" min="1" step="1" ' +
                    'value="' + item.cantidad + '" ' +
                    'oninput="cambiarCantidad(\'' + key + '\', this.value)"></td>' +
                '<td class="subtotal-cell" id="sub_' + ck + '">' + sub.toFixed(2) + '</td>' +
                '<td><button type="button" class="btn btn-xs btn-danger" onclick="quitarLinea(\'' + key + '\')">' +
                    '<i class="fa fa-times"></i></button></td>' +
                '</tr>';
        });
        tbody.innerHTML = html;
        actualizarTotal();
    }

    function mostrarAlerta(tipo, msg) {
        var div = document.getElementById('alertas');
        div.innerHTML = '<div class="alert alert-' + tipo + ' alert-dismissable">' +
            '<button type="button" class="close" data-dismiss="alert">×</button>' + esc(msg) + '</div>';
        div.scrollIntoView({ behavior: 'smooth' });
    }

    window.enviarCompra = function () {
        var proveedor = parseInt(document.getElementById('id_proveedor').value, 10) || 0;
        var nota      = document.getElementById('nota').value.trim();
        var keys      = Object.keys(carrito);

        if (!proveedor) { mostrarAlerta('danger', 'Selecciona un proveedor.'); return; }
        if (!keys.length) { mostrarAlerta('danger', 'Agrega al menos un producto.'); return; }

        // Validar: cantidades y precios
        for (var i = 0; i < keys.length; i++) {
            var it = carrito[keys[i]];
            if (it.cantidad <= 0) { mostrarAlerta('danger', 'Hay productos con cantidad inválida.'); return; }
            if (it.precio_compra < 0) { mostrarAlerta('danger', 'Hay productos con precio inválido.'); return; }
        }

        var items = keys.map(function (k) { return carrito[k]; });
        var payload = { proveedor: proveedor, nota: nota, items: items };

        var btn = document.getElementById('btn-registrar');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Registrando…';

        $.ajax({
            url: '<?php echo base_url('Entrada/addNewCompra'); ?>',
            type: 'POST',
            dataType: 'json',
            data: { payload: JSON.stringify(payload) },
            success: function (r) {
                if (r && r.ok) {
                    window.location.href = '<?php echo base_url('entrada/entradas_lista'); ?>';
                } else {
                    mostrarAlerta('danger', (r && r.msg) || 'No se pudo registrar la compra.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-check"></i> Registrar compra';
                }
            },
            error: function () {
                mostrarAlerta('danger', 'Error de conexión. Intenta nuevamente.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-check"></i> Registrar compra';
            }
        });
    };
})();
</script>
