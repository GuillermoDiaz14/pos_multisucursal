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
                <h3 class="box-title"><i class="fa fa-search"></i> Productos disponibles en esta sucursal</h3>
                <div class="box-tools pull-right">
                    <input type="text" class="form-control input-sm" id="buscador"
                           placeholder="Nombre o código..." oninput="buscarProductos(this.value)"
                           style="width:220px; display:inline-block;">
                </div>
            </div>
            <div class="box-body no-padding" id="tabla-disponibles-wrap">
                <table class="table table-condensed table-hover" id="tabla-disponibles">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>Stock</th>
                            <th style="width:80px"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($productos as $p):
                        $stock = (int)$p->stock;
                        $badgeClass = $stock === 0 ? 'bg-red' : ($stock <= 5 ? 'bg-orange' : 'bg-green');
                    ?>
                    <tr data-nombre="<?php echo strtolower(htmlspecialchars($p->nombre_producto, ENT_QUOTES)); ?>"
                        data-codigo="<?php echo strtolower(htmlspecialchars($p->codigo, ENT_QUOTES)); ?>">
                        <td><?php echo htmlspecialchars($p->nombre_producto); ?></td>
                        <td><code><?php echo htmlspecialchars($p->codigo); ?></code></td>
                        <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $stock; ?></span></td>
                        <td>
                            <button type="button" class="btn btn-xs btn-primary"
                                    onclick="agregarProducto(<?php echo $p->id_producto; ?>,'<?php echo addslashes($p->nombre_producto); ?>',<?php echo $stock; ?>)">
                                <i class="fa fa-plus"></i> Agregar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
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
                            <th style="width:100px">Disponible</th>
                            <th style="width:130px">Cantidad</th>
                            <th style="width:60px"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody-seleccionados">
                        <tr id="fila-vacia">
                            <td colspan="4" class="text-center text-muted" style="padding:30px 20px">
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

    var seleccionados = {};

    window.buscarProductos = function (termino) {
        var t = termino.toLowerCase().trim();
        document.querySelectorAll('#tabla-disponibles tbody tr').forEach(function (tr) {
            if (!t) { tr.classList.remove('oculto'); return; }
            var match = tr.getAttribute('data-nombre').indexOf(t) !== -1 ||
                        tr.getAttribute('data-codigo').indexOf(t) !== -1;
            tr.classList.toggle('oculto', !match);
        });
    };

    window.agregarProducto = function (id, nombre, stock) {
        if (seleccionados[id]) {
            var row = document.getElementById('sel_' + id);
            if (row) {
                row.style.transition = 'background .3s';
                row.style.background = '#fcf8e3';
                setTimeout(function () { row.style.background = ''; }, 700);
            }
            return;
        }
        seleccionados[id] = { nombre: nombre, stock: stock, cantidad: 1 };
        renderTabla();
    };

    window.quitarProducto = function (id) {
        delete seleccionados[id];
        renderTabla();
    };

    window.actualizarCantidad = function (id, val) {
        var v = Math.max(1, Math.min(parseInt(val, 10) || 1, seleccionados[id].stock));
        seleccionados[id].cantidad = v;
        var inp = document.getElementById('qty_' + id);
        if (inp && inp.value != v) inp.value = v;
    };

    function esc(s) {
        return String(s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function renderTabla() {
        var keys  = Object.keys(seleccionados);
        document.getElementById('contador').textContent = keys.length;
        var tbody = document.getElementById('tbody-seleccionados');

        if (keys.length === 0) {
            tbody.innerHTML = '<tr id="fila-vacia"><td colspan="4" class="text-center text-muted" style="padding:30px 20px">' +
                '<i class="fa fa-arrow-up fa-2x" style="display:block;margin-bottom:6px;opacity:.4"></i>' +
                'Busca y agrega productos desde la lista de arriba.</td></tr>';
            return;
        }

        var html = '';
        keys.forEach(function (id) {
            var p = seleccionados[id];
            var badge = p.stock === 0 ? 'bg-red' : (p.stock <= 5 ? 'bg-orange' : 'bg-green');
            html += '<tr id="sel_' + id + '">' +
                '<td>' + esc(p.nombre) + '</td>' +
                '<td><span class="badge ' + badge + '">' + p.stock + '</span></td>' +
                '<td><input type="number" class="form-control input-sm" id="qty_' + id + '" ' +
                    'value="' + p.cantidad + '" min="1" max="' + p.stock + '" ' +
                    'onchange="actualizarCantidad(' + id + ', this.value)" ' +
                    'oninput="actualizarCantidad(' + id + ', this.value)"></td>' +
                '<td><button type="button" class="btn btn-xs btn-danger" onclick="quitarProducto(' + id + ')">' +
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

        var productos = keys.map(function (id) {
            return { id_producto: id, cantidad: seleccionados[id].cantidad };
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
                    window.location.href = '<?php echo base_url('trasladar/trasladar_lista'); ?>';
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
