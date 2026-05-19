<?php
$impuesto = '';
foreach ($configuracion['configuracion'] as $config) {
    $impuesto = $config->impuesto;
}
?>
<style>
#tabla-disponibles-wrap { max-height: 280px; overflow-y: auto; }
#tabla-disponibles tbody tr.oculto { display: none; }
.badge.bg-green  { background-color: #00a65a; }
.badge.bg-orange { background-color: #f39c12; }
.badge.bg-red    { background-color: #dd4b39; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-exchange"></i> Nuevo traslado <small>Registrar traslado</small></h1>
    </section>

    <section class="content">

        <!-- Alerts -->
        <div id="alertas"></div>

        <!-- Flash messages -->
        <?php $this->load->helper('form'); ?>
        <?php if ($error = $this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $error; ?>
        </div>
        <?php endif; ?>
        <?php if ($success = $this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $success; ?>
        </div>
        <?php endif; ?>

        <!-- Step 1: Destino + Comentario -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-info-circle"></i> Información del traslado</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Sucursal destino <span class="text-danger">*</span></label>
                            <select class="form-control" id="id_sucursal_destino">
                                <option value="">— Seleccionar destino —</option>
                                <?php foreach ($sucursales as $s): ?>
                                <option value="<?php echo $s->id_sucursal; ?>"><?php echo htmlspecialchars($s->nombre_sucursal); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Comentario <small class="text-muted">(opcional)</small></label>
                            <input type="text" class="form-control" id="comentario" placeholder="Motivo del traslado..." maxlength="256">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Buscar productos -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-search"></i> Productos disponibles
                    <small class="text-muted" id="info-resultado">(mostrando los más recientes)</small>
                </h3>
                <div class="box-tools pull-right">
                    <input type="text" class="form-control input-sm" id="buscador"
                           placeholder="Escanea código o escribe nombre..."
                           autocomplete="off" autofocus
                           style="width:280px; display:inline-block;">
                    <button type="button" class="btn btn-xs btn-default" id="btn-agregar-visibles"
                            title="Agregar al detalle todos los productos visibles">
                        <i class="fa fa-check-square-o"></i> Agregar visibles
                    </button>
                </div>
            </div>
            <div class="box-body no-padding" id="tabla-disponibles-wrap">
                <table class="table table-condensed table-hover" id="tabla-disponibles">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>Stock</th>
                            <th style="width:60px">Tallas</th>
                            <th style="width:80px"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody-disponibles">
                        <tr><td colspan="5" class="text-center text-muted" style="padding:20px"><i class="fa fa-spinner fa-spin"></i> Cargando…</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Step 3: Productos seleccionados -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-shopping-cart"></i> Productos a trasladar
                    (<span id="contador">0</span>)
                </h3>
            </div>
            <div class="box-body no-padding">
                <table class="table table-condensed" id="tabla-seleccionados">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th style="width:160px">Talla</th>
                            <th style="width:100px">Disponible</th>
                            <th style="width:130px">Cantidad</th>
                            <th style="width:60px"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody-seleccionados">
                        <tr id="fila-vacia">
                            <td colspan="5" class="text-center text-muted" style="padding:30px 20px">
                                <i class="fa fa-arrow-up fa-2x" style="display:block;margin-bottom:6px;opacity:.4"></i>
                                Busca y agrega productos desde la lista de arriba.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="box-footer text-right">
                <button type="button" class="btn btn-success btn-lg" id="btn-registrar" onclick="enviarTraslado()">
                    <i class="fa fa-paper-plane"></i> Registrar traslado
                </button>
            </div>
        </div>

    </section>
</div>

<script>
(function () {
    'use strict';

    // Carga inicial server-rendered (sin volver a la BD hasta que el usuario busque).
    var PRODUCTOS_INICIALES = <?php
        $arr = [];
        foreach ($productos as $p) {
            $arr[(int)$p->id_producto] = [
                'id'             => (int)$p->id_producto,
                'nombre'         => $p->nombre_producto,
                'codigo'         => $p->codigo,
                'tieneVariantes' => (int)($p->tiene_variantes ?? 0) === 1,
                'stock'          => (int)$p->stock,
            ];
        }
        echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    ?>;
    var VARIANTES_INICIALES = <?php echo json_encode($variantes_por_producto, JSON_NUMERIC_CHECK); ?>;

    var BASE_URL    = '<?php echo base_url(); ?>';
    var PRODUCTOS   = Object.assign({}, PRODUCTOS_INICIALES); // cache acumulativo
    var VARIANTES   = Object.assign({}, VARIANTES_INICIALES);
    var resultadosVisibles = []; // ids actualmente mostrados en la tabla

    var seleccionados = {}; // clave: id_producto:id_variante
    var searchTimer = null;
    var searchXhr   = null;

    function claveLinea(id_producto, id_variante) {
        return id_producto + ':' + (id_variante || 0);
    }
    function cssKey(k) { return k.replace(':', '_'); }
    function esc(s) {
        return String(s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function stockDisponible(item) {
        var prod = PRODUCTOS[item.id_producto];
        if (!prod) return 0;
        if (prod.tieneVariantes) {
            var v = (VARIANTES[item.id_producto] || []).find(function (x) { return x.id_variante === item.id_variante; });
            return v ? parseInt(v.stock, 10) : 0;
        }
        return prod.stock;
    }

    // ── Renderizado de la tabla de disponibles ─────────────────────────────
    function renderDisponibles(ids) {
        resultadosVisibles = ids.slice();
        var tbody = document.getElementById('tbody-disponibles');
        var info  = document.getElementById('info-resultado');

        if (!ids.length) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted" style="padding:20px">Sin coincidencias.</td></tr>';
            info.textContent = '(0 resultados)';
            return;
        }

        var html = '';
        ids.forEach(function (id) {
            var p = PRODUCTOS[id];
            if (!p) return;
            var st = p.stock;
            var badge = st === 0 ? 'bg-red' : (st <= 5 ? 'bg-orange' : 'bg-green');
            html += '<tr>' +
                '<td>' + esc(p.nombre) + '</td>' +
                '<td><code>' + esc(p.codigo) + '</code></td>' +
                '<td><span class="badge ' + badge + '">' + st + '</span></td>' +
                '<td>' + (p.tieneVariantes ? '<span class="label label-info">Sí</span>' : '<span class="text-muted">—</span>') + '</td>' +
                '<td><button type="button" class="btn btn-xs btn-primary" onclick="agregarProducto(' + id + ')"><i class="fa fa-plus"></i> Agregar</button></td>' +
                '</tr>';
        });
        tbody.innerHTML = html;
        info.textContent = '(' + ids.length + (ids.length >= 200 ? '+' : '') + ' resultados — más recientes primero)';
    }

    // Render inicial
    renderDisponibles(Object.keys(PRODUCTOS_INICIALES).map(Number).sort(function (a, b) { return b - a; }));

    // ── Búsqueda AJAX ──────────────────────────────────────────────────────
    function buscarAjax(texto, cb) {
        if (searchXhr) searchXhr.abort();
        searchXhr = $.ajax({
            url:      BASE_URL + 'Trasladar/buscar_productos',
            type:     'POST',
            dataType: 'json',
            data:     { texto: texto },
            success:  function (r) {
                if (!r || !Array.isArray(r.productos)) { cb([]); return; }
                var ids = [];
                r.productos.forEach(function (p) {
                    var id = parseInt(p.id_producto, 10);
                    PRODUCTOS[id] = {
                        id: id,
                        nombre: p.nombre_producto,
                        codigo: p.codigo,
                        tieneVariantes: parseInt(p.tiene_variantes, 10) === 1,
                        stock: parseInt(p.stock, 10)
                    };
                    ids.push(id);
                });
                if (r.variantes) {
                    Object.keys(r.variantes).forEach(function (k) { VARIANTES[k] = r.variantes[k]; });
                }
                cb(ids);
            },
            error: function (xhr, st) {
                if (st !== 'abort') cb([]);
            }
        });
    }

    document.getElementById('buscador').addEventListener('input', function () {
        var val = this.value.trim();
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            buscarAjax(val, function (ids) {
                renderDisponibles(ids);
            });
        }, 250);
    });

    // Enter = escáner: si el código coincide exactamente, agregar al detalle
    document.getElementById('buscador').addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();
        var code = this.value.trim();
        if (!code) return;

        // Si ya está en PRODUCTOS cargados, intentar match exacto.
        var found = null;
        Object.keys(PRODUCTOS).some(function (id) {
            if (PRODUCTOS[id].codigo === code) { found = PRODUCTOS[id]; return true; }
        });

        if (found) {
            agregarProducto(found.id);
            this.value = '';
            this.focus();
            // Recargar la lista (mostrar los más recientes de nuevo)
            renderDisponibles(Object.keys(PRODUCTOS_INICIALES).map(Number).sort(function (a, b) { return b - a; }));
            return;
        }
        // Si no, consultar al servidor
        var inp = this;
        buscarAjax(code, function (ids) {
            var match = null;
            for (var i = 0; i < ids.length; i++) {
                if (PRODUCTOS[ids[i]].codigo === code) { match = PRODUCTOS[ids[i]]; break; }
            }
            if (match) {
                agregarProducto(match.id);
                inp.value = '';
                inp.focus();
                renderDisponibles(Object.keys(PRODUCTOS_INICIALES).map(Number).sort(function (a, b) { return b - a; }));
            } else {
                renderDisponibles(ids);
            }
        });
    });

    // Botón "Agregar visibles": mete al detalle todos los productos actualmente listados
    document.getElementById('btn-agregar-visibles').addEventListener('click', function () {
        if (!resultadosVisibles.length) return;
        resultadosVisibles.forEach(function (id) { agregarProducto(id); });
        mostrarAlerta('success', resultadosVisibles.length + ' producto(s) agregado(s) al detalle.');
    });

    window.agregarProducto = function (id) {
        var prod = PRODUCTOS[id];
        if (!prod) return;

        var id_variante = 0;
        if (prod.tieneVariantes) {
            var variantes = (VARIANTES[id] || []).filter(function (v) { return parseInt(v.stock, 10) > 0; });
            if (!variantes.length) {
                mostrarAlerta('warning', 'Este producto no tiene tallas con stock en esta sucursal.');
                return;
            }
            id_variante = variantes[0].id_variante;
        }
        var key = claveLinea(id, id_variante);
        if (seleccionados[key]) {
            var disp = stockDisponible(seleccionados[key]);
            if (seleccionados[key].cantidad < disp) seleccionados[key].cantidad += 1;
            renderTabla();
            return;
        }
        seleccionados[key] = { id_producto: id, id_variante: id_variante, cantidad: 1 };
        renderTabla();
    };

    window.cambiarVariante = function (oldKey, newIdVariante) {
        var item = seleccionados[oldKey];
        if (!item) return;
        var newKey = claveLinea(item.id_producto, newIdVariante);
        if (newKey === oldKey) return;
        if (seleccionados[newKey]) {
            seleccionados[newKey].cantidad += item.cantidad;
        } else {
            item.id_variante = newIdVariante;
            seleccionados[newKey] = item;
        }
        delete seleccionados[oldKey];
        // Tope a stock disponible de la nueva variante
        var disp = stockDisponible(seleccionados[newKey]);
        if (seleccionados[newKey].cantidad > disp) seleccionados[newKey].cantidad = disp || 1;
        renderTabla();
    };

    window.quitarProducto = function (key) {
        delete seleccionados[key];
        renderTabla();
    };

    window.actualizarCantidad = function (key, val) {
        var item = seleccionados[key];
        if (!item) return;
        var disp = stockDisponible(item);
        var v = Math.max(1, Math.min(parseInt(val, 10) || 1, disp || 1));
        item.cantidad = v;
        var inp = document.getElementById('qty_' + cssKey(key));
        if (inp && inp.value != v) inp.value = v;
    };

    function renderTabla() {
        var keys  = Object.keys(seleccionados);
        document.getElementById('contador').textContent = keys.length;
        var tbody = document.getElementById('tbody-seleccionados');

        if (keys.length === 0) {
            tbody.innerHTML = '<tr id="fila-vacia"><td colspan="5" class="text-center text-muted" style="padding:30px 20px">' +
                '<i class="fa fa-arrow-up fa-2x" style="display:block;margin-bottom:6px;opacity:.4"></i>' +
                'Busca y agrega productos desde la lista de arriba.</td></tr>';
            return;
        }

        var html = '';
        keys.forEach(function (key) {
            var item = seleccionados[key];
            var prod = PRODUCTOS[item.id_producto];
            var disp = stockDisponible(item);
            var badge = disp === 0 ? 'bg-red' : (disp <= 5 ? 'bg-orange' : 'bg-green');
            var ck = cssKey(key);

            var tallaCell;
            if (prod.tieneVariantes) {
                var opts = (VARIANTES[item.id_producto] || []).map(function (v) {
                    var sel = v.id_variante === item.id_variante ? ' selected' : '';
                    return '<option value="' + v.id_variante + '"' + sel + '>' +
                           esc(v.talla) + ' (stock: ' + v.stock + ')</option>';
                }).join('');
                tallaCell = '<select class="form-control input-sm" ' +
                    'onchange="cambiarVariante(\'' + key + '\', parseInt(this.value,10))">' + opts + '</select>';
            } else {
                tallaCell = '<span class="text-muted">—</span>';
            }

            html += '<tr id="sel_' + ck + '">' +
                '<td>' + esc(prod.nombre) + '<br><small class="text-muted"><code>' + esc(prod.codigo) + '</code></small></td>' +
                '<td>' + tallaCell + '</td>' +
                '<td><span class="badge ' + badge + '">' + disp + '</span></td>' +
                '<td><input type="number" class="form-control input-sm" id="qty_' + ck + '" ' +
                    'value="' + item.cantidad + '" min="1" max="' + Math.max(1, disp) + '" ' +
                    'onchange="actualizarCantidad(\'' + key + '\', this.value)" ' +
                    'oninput="actualizarCantidad(\'' + key + '\', this.value)"></td>' +
                '<td><button type="button" class="btn btn-xs btn-danger" onclick="quitarProducto(\'' + key + '\')">' +
                    '<i class="fa fa-times"></i></button></td>' +
                '</tr>';
        });
        tbody.innerHTML = html;
    }

    function mostrarAlerta(tipo, mensaje) {
        var div = document.getElementById('alertas');
        div.innerHTML = '<div class="alert alert-' + tipo + ' alert-dismissable">' +
            '<button type="button" class="close" data-dismiss="alert">×</button>' +
            esc(mensaje) + '</div>';
        div.scrollIntoView({ behavior: 'smooth' });
    }

    window.enviarTraslado = function () {
        var destino    = document.getElementById('id_sucursal_destino').value;
        var comentario = document.getElementById('comentario').value;
        var keys       = Object.keys(seleccionados);

        if (!destino) {
            mostrarAlerta('danger', 'Debe seleccionar la sucursal destino.');
            return;
        }
        if (keys.length === 0) {
            mostrarAlerta('danger', 'Agregue al menos un producto al traslado.');
            return;
        }

        var productos = keys.map(function (k) {
            return {
                id_producto: seleccionados[k].id_producto,
                id_variante: seleccionados[k].id_variante,
                cantidad:    seleccionados[k].cantidad,
            };
        });

        var btn = document.getElementById('btn-registrar');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Registrando...';

        $.ajax({
            url: '<?php echo base_url('Trasladar/addNewTrasladar'); ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                id_sucursal_destino: destino,
                comentario: comentario,
                productos: JSON.stringify(productos)
            },
            success: function (r) {
                if (r.ok) {
                    var id = r.id_traslado ? parseInt(r.id_traslado, 10) : 0;
                    if (id > 0) {
                        window.location.href = '<?php echo base_url('trasladar/detalle/'); ?>' + id + '?ok=1';
                    } else {
                        window.location.href = '<?php echo base_url('trasladar/trasladar_lista'); ?>';
                    }
                } else {
                    mostrarAlerta('danger', r.msg || 'Error al registrar el traslado.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-paper-plane"></i> Registrar traslado';
                }
            },
            error: function () {
                mostrarAlerta('danger', 'Error de conexión. Intente nuevamente.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-paper-plane"></i> Registrar traslado';
            }
        });
    };
})();
</script>
