<style>
/* ── Toast de notificación ── */
#resurtir-toast {
    position: fixed;
    top: 70px;
    left: 50%;
    transform: translateX(-50%) translateY(-20px);
    z-index: 9999;
    min-width: 340px;
    max-width: 520px;
    padding: 18px 24px;
    border-radius: 8px;
    box-shadow: 0 6px 24px rgba(0,0,0,.22);
    font-size: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 14px;
    opacity: 0;
    pointer-events: none;
    transition: opacity .25s, transform .25s;
}
#resurtir-toast.show {
    opacity: 1;
    pointer-events: auto;
    transform: translateX(-50%) translateY(0);
}
#resurtir-toast.toast-success { background:#27ae60; color:#fff; }
#resurtir-toast.toast-error   { background:#c0392b; color:#fff; }
#resurtir-toast.toast-info    { background:#2980b9; color:#fff; }
#resurtir-toast .toast-icon   { font-size:24px; flex-shrink:0; }
#resurtir-toast .toast-close  { margin-left:auto; cursor:pointer; opacity:.8; font-size:18px; }

/* ── Panel no encontrado mejorado ── */
#resultado_error {
    border-left: 5px solid #e67e22;
    border-radius: 6px;
    padding: 20px 24px;
}
#resultado_error .nf-icon { font-size: 48px; color: #e67e22; display:block; margin-bottom:8px; }

/* ── Panel de éxito inline ── */
#resultado_exito {
    border-left: 5px solid #27ae60;
    border-radius: 6px;
}
#resultado_exito .exito-icon { font-size: 52px; color:#27ae60; }
#resultado_exito .stock-badge {
    font-size: 36px;
    font-weight: 700;
    color: #27ae60;
    display: block;
    line-height: 1;
}
</style>

<!-- Toast global -->
<div id="resurtir-toast">
    <span class="toast-icon"><i class="fa fa-check-circle"></i></span>
    <span id="toast-msg"></span>
    <span class="toast-close" onclick="hideToast()">×</span>
</div>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-refresh" aria-hidden="true"></i> Resurtir Producto
            <small>Aumentar stock de productos existentes</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <!-- Main column -->
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Buscar y Resurtir Producto</h3>
                    </div>
                    <div class="box-body">
                        <form id="form_resurtir" autocomplete="off">

                            <div class="form-group">
                                <label>Buscar producto</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                                    <input type="text"
                                           class="form-control"
                                           id="codigo_escaneo"
                                           placeholder="Escanea el código de barras o escribe el nombre..."
                                           autofocus
                                           autocomplete="off">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" id="btn_buscar_manual">
                                            <i class="fa fa-search"></i> Buscar
                                        </button>
                                    </span>
                                </div>
                                <small class="text-muted">Presiona Enter o espera a que se detecte el código automáticamente</small>
                            </div>

                            <!-- Buscando... spinner -->
                            <div id="buscando_spinner" style="display:none; text-align:center; padding:20px;">
                                <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                                <p class="text-muted" style="margin-top:8px;">Buscando producto...</p>
                            </div>

                            <!-- Resultados múltiples -->
                            <div id="resultado_multiples" style="display:none;" class="box box-warning">
                                <div class="box-header">
                                    <h3 class="box-title"><i class="fa fa-list"></i> Se encontraron varios productos — selecciona uno</h3>
                                </div>
                                <div class="box-body" style="padding:0;">
                                    <div id="lista_productos" style="max-height:320px; overflow-y:auto;"></div>
                                </div>
                                <div class="box-footer">
                                    <button type="button" class="btn btn-default btn-sm" onclick="resetFormResurtir()">
                                        <i class="fa fa-arrow-left"></i> Nueva búsqueda
                                    </button>
                                </div>
                            </div>

                            <!-- Producto encontrado -->
                            <div id="resultado_producto" style="display:none;" class="box box-success">
                                <div class="box-header" style="background:#27ae60; color:#fff; border-radius:4px 4px 0 0;">
                                    <h3 class="box-title" style="color:#fff;">
                                        <i class="fa fa-check-circle"></i> Producto encontrado
                                    </h3>
                                    <div class="box-tools">
                                        <button type="button" class="btn btn-xs" style="color:#fff;" onclick="resetFormResurtir()">
                                            <i class="fa fa-times"></i> Cambiar
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <table class="table table-condensed" style="margin-bottom:0;">
                                                <tr>
                                                    <td style="width:130px;"><strong>Nombre</strong></td>
                                                    <td><span id="nombre_producto" style="font-size:15px; font-weight:600;"></span></td>
                                                </tr>
                                                <tr id="row_talla_simple">
                                                    <td><strong>Talla</strong></td>
                                                    <td><span id="talla_producto"></span></td>
                                                </tr>
                                                <tr id="row_talla_variante" style="display:none;">
                                                    <td><strong>Talla</strong></td>
                                                    <td>
                                                        <select id="select_variante" class="form-control input-sm" style="max-width:240px;"></select>
                                                        <small class="text-muted">Selecciona la talla a resurtir</small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Código</strong></td>
                                                    <td><code id="ean13_producto"></code></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Precio compra</strong></td>
                                                    <td>
                                                        <span id="precio_compra_display"></span>
                                                        <button type="button" class="btn btn-xs btn-info" id="btn_editar_precio">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-sm-4 text-center" style="border-left:1px solid #eee; padding-top:10px;">
                                            <div style="font-size:12px; text-transform:uppercase; letter-spacing:1px; color:#888; margin-bottom:4px;">Stock actual</div>
                                            <div id="stock_actual_badge" style="font-size:42px; font-weight:700; line-height:1; color:#2980b9;"></div>
                                            <div style="font-size:12px; color:#888;">unidades</div>
                                        </div>
                                    </div>

                                    <hr style="margin:16px 0;">

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group" style="margin-bottom:0;">
                                                <label for="stock_nuevo" style="font-size:13px;">Cantidad a agregar</label>
                                                <input type="number"
                                                       class="form-control input-lg"
                                                       id="stock_nuevo"
                                                       min="1"
                                                       placeholder="Ej: 50"
                                                       style="font-size:22px; font-weight:700; text-align:center;">
                                            </div>
                                        </div>
                                        <div class="col-sm-6" style="padding-top:24px;">
                                            <button type="button" class="btn btn-success btn-lg btn-block" id="btn_confirmar">
                                                <i class="fa fa-check"></i> Confirmar Resurtimiento
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Producto NO encontrado -->
                            <div id="resultado_error" style="display:none;" class="alert alert-warning">
                                <div class="text-center" style="padding:10px 0;">
                                    <i class="fa fa-exclamation-triangle nf-icon"></i>
                                    <h4 style="margin:0 0 6px;">Código no encontrado en el sistema</h4>
                                    <p style="font-size:15px; margin-bottom:4px;">
                                        <code id="codigo_buscado" style="font-size:16px; background:#fef9e7; padding:2px 8px; border-radius:4px;"></code>
                                    </p>
                                    <p class="text-muted" style="margin-bottom:16px;">¿Es un producto nuevo? Puedes registrarlo ahora.</p>
                                    <button type="button" class="btn btn-primary btn-lg" id="btn_agregar_nuevo">
                                        <i class="fa fa-plus-circle"></i> Registrar como nuevo producto
                                    </button>
                                    <button type="button" class="btn btn-default btn-lg" onclick="resetFormResurtir()" style="margin-left:8px;">
                                        <i class="fa fa-arrow-left"></i> Buscar otro
                                    </button>
                                </div>
                            </div>

                            <!-- Resurtimiento exitoso -->
                            <div id="resultado_exito" style="display:none;" class="alert alert-success">
                                <div class="text-center" style="padding:10px 0;">
                                    <i class="fa fa-check-circle exito-icon"></i>
                                    <h3 style="margin:8px 0 4px; color:#27ae60;">¡Resurtimiento exitoso!</h3>
                                    <p style="font-size:15px; margin-bottom:16px;"><strong id="msg_producto_resurtido"></strong></p>
                                    <div class="row" style="max-width:400px; margin:0 auto 20px;">
                                        <div class="col-xs-4 text-center">
                                            <div style="font-size:11px; text-transform:uppercase; color:#888; margin-bottom:4px;">Anterior</div>
                                            <div id="stock_anterior_final" style="font-size:24px; font-weight:700; color:#888;"></div>
                                        </div>
                                        <div class="col-xs-4 text-center" style="font-size:28px; color:#888; padding-top:12px;">
                                            <i class="fa fa-arrow-right"></i>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <div style="font-size:11px; text-transform:uppercase; color:#27ae60; margin-bottom:4px;">Nuevo total</div>
                                            <span class="stock-badge" id="stock_final"></span>
                                            <div style="font-size:11px; color:#27ae60;">(+<span id="cantidad_agregada_final"></span>)</div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success btn-lg" onclick="resetFormResurtir()">
                                        <i class="fa fa-refresh"></i> Resurtir otro producto
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-md-4">
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

                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-info-circle"></i> Instrucciones</h3>
                    </div>
                    <div class="box-body">
                        <ol style="font-size:13px; padding-left:18px;">
                            <li>Escanea el código de barras del producto</li>
                            <li>El sistema lo buscará automáticamente</li>
                            <li>Ingresa la cantidad a agregar</li>
                            <li>Confirma el resurtimiento</li>
                        </ol>
                        <hr>
                        <ul style="font-size:12px; padding-left:18px;">
                            <li>Si el código no existe, puedes registrarlo como nuevo producto</li>
                            <li>Puedes también buscar por nombre escribiendo en el campo</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    var productoActual = null;
    var ajaxActual = null;

    /* ── Toast ── */
    function showToast(msg, type, duracion) {
        var $t = $('#resurtir-toast');
        $t.removeClass('toast-success toast-error toast-info');
        $t.addClass('toast-' + (type || 'success'));
        var icons = { success: 'fa-check-circle', error: 'fa-times-circle', info: 'fa-info-circle' };
        $t.find('.toast-icon i').attr('class', 'fa ' + (icons[type] || icons.success));
        $('#toast-msg').text(msg);
        $t.addClass('show');
        clearTimeout($t.data('timer'));
        $t.data('timer', setTimeout(hideToast, duracion || 4000));
    }
    window.hideToast = function() {
        $('#resurtir-toast').removeClass('show');
    };

    /* ── Reset del formulario ── */
    window.resetFormResurtir = function() {
        productoActual = null;
        $('#codigo_escaneo').val('').focus();
        $('#resultado_producto, #resultado_error, #resultado_exito, #resultado_multiples, #buscando_spinner').hide();
    };

    /* ── Búsqueda ── */
    function ejecutarBusqueda() {
        var busqueda = $('#codigo_escaneo').val().trim();
        if (!busqueda) return;

        // Cancelar AJAX anterior si aún está pendiente
        if (ajaxActual) { ajaxActual.abort(); ajaxActual = null; }

        $('#resultado_producto, #resultado_exito, #resultado_multiples').hide();
        $('#buscando_spinner').show();

        ajaxActual = $.ajax({
            url: '<?php echo base_url("producto/buscar_producto"); ?>',
            method: 'POST',
            dataType: 'json',
            data: { busqueda: busqueda },
            success: function(response) {
                ajaxActual = null;
                $('#buscando_spinner').hide();
                if (response.success) {
                    if (response.productos.length === 1) {
                        var stock = (response.stock_sucursal !== undefined) ? response.stock_sucursal : (response.productos[0].stock_sucursal || 0);
                        mostrar_producto(response.productos[0], stock);
                    } else {
                        mostrar_resultados_multiples(response.productos);
                    }
                } else {
                    mostrar_no_encontrado(busqueda);
                }
            },
            error: function(xhr, status) {
                ajaxActual = null;
                if (status === 'abort') return;
                $('#buscando_spinner').hide();
                showToast('Error de conexión al buscar el producto', 'error');
                $('#codigo_escaneo').focus();
            }
        });
    }

    // Enter en el input de búsqueda
    $('#codigo_escaneo').on('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); ejecutarBusqueda(); }
    });

    // Captura global de Enter: si hay texto en el buscador y no hay modal abierto,
    // redirigir al input y ejecutar búsqueda (previene que el scanner dispare botones)
    $(document).on('keydown.resurtir', function(e) {
        if (e.key !== 'Enter') return;
        if ($('.modal.in').length) return;                          // modal abierto: ignorar
        if ($(document.activeElement).is('#codigo_escaneo')) return; // ya lo maneja el handler del input
        if ($(document.activeElement).is('#stock_nuevo')) return;    // campo de cantidad: no interferir
        var val = $('#codigo_escaneo').val().trim();
        if (val) {
            e.preventDefault();
            ejecutarBusqueda();
        } else {
            // Sin contenido: redirigir el foco al buscador para el próximo scan
            e.preventDefault();
            $('#codigo_escaneo').focus();
        }
    });

    // Evitar que Enter en los botones del panel "no encontrado" los dispare
    $('#resultado_error').on('keydown', 'button', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); e.stopPropagation(); }
    });

    $('#btn_buscar_manual').on('click', ejecutarBusqueda);

    /* ── Mostrar producto ── */
    function mostrar_producto(producto, stock) {
        productoActual = producto;
        $('#nombre_producto').text(producto.nombre_producto);
        $('#ean13_producto').text(producto.codigo);
        $('#precio_compra_display').text('$' + parseFloat(producto.precio_compra || 0).toFixed(2));

        var tieneVariantes = parseInt(producto.tiene_variantes) === 1 && Array.isArray(producto.variantes) && producto.variantes.length;
        if (tieneVariantes) {
            $('#row_talla_simple').hide();
            $('#row_talla_variante').show();
            var $sel = $('#select_variante').empty();
            producto.variantes.forEach(function(v) {
                $sel.append('<option value="' + v.id_variante + '" data-stock="' + v.stock + '" data-precio-compra="' + (v.precio_compra || 0) + '">Talla ' + v.talla + ' — stock: ' + v.stock + '</option>');
            });
            actualizar_datos_variante();
        } else {
            $('#row_talla_variante').hide();
            $('#row_talla_simple').show();
            $('#talla_producto').text(producto.talla || '—');
            $('#stock_actual_badge').text(stock);
        }

        $('#resultado_error, #resultado_exito, #resultado_multiples').hide();
        $('#resultado_producto').show();
        $('#stock_nuevo').val('').focus();
    }

    function actualizar_datos_variante() {
        var $opt = $('#select_variante option:selected');
        var stock = parseInt($opt.data('stock'), 10) || 0;
        var pc    = parseFloat($opt.data('precio-compra')) || 0;
        $('#stock_actual_badge').text(stock);
        $('#precio_compra_display').text('$' + pc.toFixed(2));
    }

    $(document).on('change', '#select_variante', actualizar_datos_variante);

    /* ── Múltiples resultados ── */
    function mostrar_resultados_multiples(productos) {
        var html = '<div class="list-group" style="margin-bottom:0;">';
        productos.forEach(function(prod) {
            var stockColor = prod.stock_sucursal > 0 ? '#27ae60' : '#c0392b';
            html += '<a href="#" class="list-group-item producto-item" data-id="' + prod.id_producto + '" style="border-left:3px solid ' + stockColor + ';">';
            html += '<strong>' + prod.nombre_producto + '</strong>';
            if (parseInt(prod.tiene_variantes) === 1) {
                html += ' <span class="label label-info">Tallas: ' + (prod.variantes ? prod.variantes.length : 0) + '</span>';
            } else if (prod.talla) {
                html += ' <span class="label label-default">' + prod.talla + '</span>';
            }
            html += '<br><small class="text-muted">Código: ' + prod.codigo + '</small>';
            html += ' <span class="pull-right" style="color:' + stockColor + '; font-weight:700;">Stock: ' + prod.stock_sucursal + '</span>';
            html += '</a>';
        });
        html += '</div>';
        $('#lista_productos').html(html);
        $('#resultado_error, #resultado_exito, #resultado_producto').hide();
        $('#resultado_multiples').show();
        $('#lista_productos').find('.producto-item').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var prod = productos.find(function(p) { return p.id_producto == id; });
            mostrar_producto(prod, prod.stock_sucursal);
        });
    }

    /* ── No encontrado ── */
    function mostrar_no_encontrado(busqueda) {
        $('#codigo_buscado').text(busqueda);
        $('#btn_agregar_nuevo').data('codigo', busqueda);
        $('#resultado_producto, #resultado_exito, #resultado_multiples, #buscando_spinner').hide();
        $('#resultado_error').show();
        // No limpiar ni desenfocar: el panel debe quedar visible hasta que
        // el usuario decida registrar el nuevo producto o buscar otro manualmente.
    }

    function ir_a_agregar(codigo) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(codigo).catch(function() {});
        }
        window.location.href = '<?php echo base_url("producto/add"); ?>/' + encodeURIComponent(codigo);
    }

    $('#btn_agregar_nuevo').on('click', function() {
        ir_a_agregar($(this).data('codigo') || '');
    });

    /* ── Editar precio de compra ── */
    $(document).on('click', '#btn_editar_precio', function(e) {
        e.preventDefault();
        if (!productoActual) return;
        var actual = productoActual.precio_compra;
        if (parseInt(productoActual.tiene_variantes) === 1) {
            actual = parseFloat($('#select_variante option:selected').data('precio-compra')) || 0;
        }
        $('#modalPrecioCompra').modal('show');
        $('#nuevo_precio_compra').val(actual).focus().select();
    });

    $('#btn_guardar_precio').on('click', function() {
        var precio = parseFloat($('#nuevo_precio_compra').val());
        if (precio === '' || isNaN(precio) || precio < 0) {
            showToast('Ingresa un precio válido', 'error');
            return;
        }
        var tieneVar = parseInt(productoActual.tiene_variantes) === 1;
        var $opt = tieneVar ? $('#select_variante option:selected') : null;
        var id_variante = tieneVar ? (parseInt($opt.val(), 10) || 0) : 0;

        $.ajax({
            url: '<?php echo base_url("producto/actualizar_precio_compra"); ?>',
            method: 'POST',
            dataType: 'json',
            data: { id_producto: productoActual.id_producto, id_variante: id_variante, precio_compra: precio },
            success: function(response) {
                if (response.success) {
                    if (tieneVar && $opt) {
                        $opt.attr('data-precio-compra', precio);
                        // sincronizar también el objeto cacheado
                        (productoActual.variantes || []).forEach(function(v) {
                            if (parseInt(v.id_variante) === id_variante) v.precio_compra = precio;
                        });
                    } else {
                        productoActual.precio_compra = precio;
                    }
                    $('#precio_compra_display').text('$' + precio.toFixed(2));
                    $('#modalPrecioCompra').modal('hide');
                    showToast('Precio de compra actualizado', 'info');
                } else {
                    showToast('Error: ' + response.message, 'error');
                }
            },
            error: function() { showToast('Error al actualizar precio', 'error'); }
        });
    });

    /* ── Confirmar resurtimiento ── */
    $('#btn_confirmar').on('click', confirmar_resurtimiento);
    $('#stock_nuevo').on('keydown', function(e) {
        if (e.key === 'Enter') confirmar_resurtimiento();
    });

    function confirmar_resurtimiento() {
        var stock_nuevo = parseInt($('#stock_nuevo').val());
        if (!stock_nuevo || stock_nuevo <= 0) {
            showToast('Ingresa una cantidad mayor a 0', 'error');
            $('#stock_nuevo').focus();
            return;
        }
        if (!productoActual) {
            showToast('No hay producto seleccionado', 'error');
            return;
        }
        var tieneVariantes = parseInt(productoActual.tiene_variantes) === 1;
        var id_variante = 0;
        var tallaSeleccionada = '';
        if (tieneVariantes) {
            var $opt = $('#select_variante option:selected');
            id_variante = parseInt($opt.val(), 10) || 0;
            tallaSeleccionada = $opt.text().split('—')[0].replace('Talla', '').trim();
            if (!id_variante) {
                showToast('Selecciona una talla', 'error');
                return;
            }
        }

        var $btn = $('#btn_confirmar').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Procesando...');
        $.ajax({
            url: '<?php echo base_url("producto/resurtir_producto"); ?>',
            method: 'POST',
            dataType: 'json',
            data: {
                id_producto: productoActual.id_producto,
                codigo: productoActual.codigo,
                stock_nuevo: stock_nuevo,
                id_variante: id_variante
            },
            success: function(response) {
                $btn.prop('disabled', false).html('<i class="fa fa-check"></i> Confirmar Resurtimiento');
                if (response.success) {
                    var tallaMsg = response.talla || tallaSeleccionada || productoActual.talla || '';
                    $('#msg_producto_resurtido').text(productoActual.nombre_producto + (tallaMsg ? ' — Talla: ' + tallaMsg : ''));
                    $('#stock_anterior_final').text(response.stock_anterior);
                    $('#cantidad_agregada_final').text(response.cantidad_agregada);
                    $('#stock_final').text(response.stock_nuevo);
                    $('#resultado_producto, #resultado_error, #resultado_multiples').hide();
                    $('#resultado_exito').show();
                    showToast('Stock actualizado: ' + productoActual.nombre_producto + ' → ' + response.stock_nuevo + ' unidades', 'success', 5000);
                    // Listo para el siguiente escaneo: limpiar y foco al buscador
                    productoActual = null;
                    $('#codigo_escaneo').val('').focus();
                } else {
                    showToast('Error: ' + response.message, 'error');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-check"></i> Confirmar Resurtimiento');
                showToast('Error de conexión al procesar el resurtimiento', 'error');
            }
        });
    }
});
</script>

<!-- Modal editar precio -->
<div class="modal fade" id="modalPrecioCompra" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">Editar Precio de Compra</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nuevo Precio de Compra</label>
                    <input type="number" class="form-control input-lg" id="nuevo_precio_compra" step="0.01" min="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_guardar_precio">Guardar</button>
            </div>
        </div>
    </div>
</div>
