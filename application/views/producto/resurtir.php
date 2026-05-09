<div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
                        <form id="form_resurtir">
                            <!-- Búsqueda flexible: código o nombre -->
                            <div class="form-group">
                                <label>Buscar producto</label>
                                <input type="text"
                                       class="form-control input-lg"
                                       id="codigo_escaneo"
                                       placeholder="Código de barras o nombre del producto..."
                                       autofocus
                                       autocomplete="off">
                                <small class="form-text text-muted">
                                    Escanea código EAN-13 o escribe el nombre del producto
                                </small>
                            </div>

                            <!-- Resultados múltiples (búsqueda por nombre) -->
                            <div id="resultado_multiples" style="display:none;" class="box box-warning">
                                <div class="box-header">
                                    <h3 class="box-title">Se encontraron varios productos</h3>
                                </div>
                                <div class="box-body">
                                    <div id="lista_productos" style="max-height:300px; overflow-y:auto;"></div>
                                </div>
                            </div>

                            <!-- Información del producto encontrado -->
                            <div id="resultado_producto" style="display:none;" class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title">✓ Producto Encontrado</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped">
                                        <tr>
                                            <td><strong>Nombre:</strong></td>
                                            <td><span id="nombre_producto"></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Talla:</strong></td>
                                            <td><span id="talla_producto"></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Código EAN-13:</strong></td>
                                            <td><code id="ean13_producto"></code></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Stock Actual:</strong></td>
                                            <td><span id="stock_actual" style="font-size:16px; color:blue; font-weight:bold;"></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Precio Compra:</strong></td>
                                            <td>
                                                <span id="precio_compra_display"></span>
                                                <button type="button" class="btn btn-xs btn-info" id="btn_editar_precio">
                                                    <i class="fa fa-edit"></i> Editar
                                                </button>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Cantidad a agregar -->
                                    <div class="form-group" style="margin-top:20px;">
                                        <label for="stock_nuevo"><strong>Cantidad a Agregar</strong></label>
                                    <input type="number" 
                                           class="form-control input-lg" 
                                           id="stock_nuevo"
                                           min="1" 
                                           placeholder="Ej: 50"
                                           required>
                                    </div>

                                    <!-- Botones de acción -->
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success btn-lg" id="btn_confirmar">
                                            <i class="fa fa-check"></i> Confirmar Resurtimiento
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-lg" onclick="location.reload()">
                                            <i class="fa fa-times"></i> Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Producto no encontrado -->
                            <div id="resultado_error" style="display:none;" class="alert alert-warning">
                                <h5 class="alert-heading">⚠ Código no encontrado</h5>
                                <p>El código <strong id="codigo_buscado"></strong> no existe en el sistema.</p>
                                <p>¿Es un producto nuevo?</p>
                                <button type="button" class="btn btn-primary" id="btn_agregar_nuevo">
                                    <i class="fa fa-plus"></i> Agregar como nuevo producto
                                </button>
                                <button type="button" class="btn btn-default" onclick="location.reload()">
                                    <i class="fa fa-times"></i> Cancelar
                                </button>
                            </div>

                            <!-- Confirmación de resurtimiento exitoso -->
                            <div id="resultado_exito" style="display:none;" class="alert alert-success">
                                <h5 class="alert-heading">✓ Resurtimiento Exitoso</h5>
                                <hr>
                                <p><strong id="msg_producto_resurtido"></strong></p>
                                <table class="table table-sm">
                                    <tr>
                                        <td>Stock Anterior:</td>
                                        <td><span id="stock_anterior_final"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Cantidad Agregada:</td>
                                        <td><span id="cantidad_agregada_final"></span></td>
                                    </tr>
                                    <tr style="background-color:#e8f5e9;">
                                        <td><strong>Stock Nuevo:</strong></td>
                                        <td><strong style="color:green; font-size:16px;" id="stock_final"></strong></td>
                                    </tr>
                                </table>
                                <button type="button" class="btn btn-primary btn-lg" onclick="location.reload()">
                                    <i class="fa fa-refresh"></i> Resurtir Otro Producto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right column - Help -->
            <div class="col-md-4">
                <!-- Mensajes Flash -->
                <div id="flash_messages">
                    <?php
                        $error = $this->session->flashdata('error');
                        if($error) {
                    ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $error; ?>
                    </div>
                    <?php } ?>

                    <?php  
                        $success = $this->session->flashdata('success');
                        if($success) {
                    ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $success; ?>
                    </div>
                    <?php } ?>
                </div>

                <!-- Información de ayuda -->
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">
                            <i class="fa fa-info-circle"></i> Instrucciones
                        </h3>
                    </div>
                    <div class="box-body">
                        <ol style="font-size:13px;">
                            <li>Coloca el producto frente al lector de código de barras</li>
                            <li>El sistema buscará automáticamente el código</li>
                            <li>Ingresa la cantidad a agregar</li>
                            <li>Confirma el resurtimiento</li>
                        </ol>

                        <hr>
                        <h5>Consejos:</h5>
                        <ul style="font-size:12px;">
                            <li>Los códigos deben ser EAN-13</li>
                            <li>Verifica el stock actual antes de confirmar</li>
                            <li>Puedes resurtir varios productos seguidos</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    let productoActual = null;

    // Búsqueda automática cuando se completa el escaneo
    $('#codigo_escaneo').on('keyup', function(e) {
        if (e.key === 'Enter') {
            var busqueda = $(this).val().trim();

            if (!busqueda) {
                alert('Ingresa código o nombre');
                return;
            }

            buscar_producto_flexible(busqueda);
        }
    });

    // Función de búsqueda flexible (código o nombre)
    function buscar_producto_flexible(busqueda) {
        $.ajax({
            url: '<?php echo base_url("producto/buscar_producto"); ?>',
            method: 'POST',
            dataType: 'json',
            data: { busqueda: busqueda },
            success: function(response) {
                if (response.success) {
                    // Si encuentra 1 producto, mostrar directo
                    if (response.productos.length === 1) {
                        var stockVal = (response.stock_sucursal !== undefined) ? response.stock_sucursal : (response.productos[0].stock_sucursal || 0);
                        mostrar_producto(response.productos[0], stockVal);
                    } else if (response.productos.length > 1) {
                        // Múltiples resultados: mostrar lista
                        mostrar_resultados_multiples(response.productos);
                    }
                } else {
                    // Producto no encontrado
                    $('#codigo_buscado').text(busqueda);
                    $('#btn_agregar_nuevo').data('codigo', busqueda);
                    $('#resultado_producto').hide();
                    $('#resultado_exito').hide();
                    $('#resultado_multiples').hide();
                    $('#resultado_error').show();
                }
            },
            error: function() {
                alert('Error en la búsqueda');
                $('#codigo_escaneo').focus();
            }
        });
    }

    // Mostrar un único producto
    function mostrar_producto(producto, stock) {
        productoActual = producto;

        $('#nombre_producto').text(producto.nombre_producto);
        $('#talla_producto').text(producto.talla || 'N/A');
        $('#ean13_producto').text(producto.codigo);
        $('#stock_actual').text(stock + ' unidades');
        $('#precio_compra_display').text('$' + parseFloat(producto.precio_compra).toFixed(2));

        $('#resultado_error').hide();
        $('#resultado_exito').hide();
        $('#resultado_multiples').hide();
        $('#resultado_producto').show();

        $('#stock_nuevo').val('').focus();
    }

    // Mostrar múltiples resultados
    function mostrar_resultados_multiples(productos) {
        var html = '<div class="list-group">';
        productos.forEach(function(prod) {
            html += '<a href="#" class="list-group-item producto-item" data-id="' + prod.id_producto + '">';
            html += '<strong>' + prod.nombre_producto + '</strong> (Talla: ' + (prod.talla || 'N/A') + ')<br>';
            html += '<small>Código: ' + prod.codigo + ' | Stock: ' + prod.stock_sucursal + '</small>';
            html += '</a>';
        });
        html += '</div>';

        $('#lista_productos').html(html);
        $('#resultado_error').hide();
        $('#resultado_exito').hide();
        $('#resultado_producto').hide();
        $('#resultado_multiples').show();

        // Evento para seleccionar producto de la lista
        $('.producto-item').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var prod = productos.find(p => p.id_producto == id);
            mostrar_producto(prod, prod.stock_sucursal);
        });
    }

    // Evento para editar precio
    $(document).on('click', '#btn_editar_precio', function(e) {
        e.preventDefault();
        if (productoActual) {
            $('#modalPrecioCompra').modal('show');
            $('#nuevo_precio_compra').val(productoActual.precio_compra).focus().select();
        }
    });

    // Guardar nuevo precio
    $('#btn_guardar_precio').on('click', function() {
        var precio = parseFloat($('#nuevo_precio_compra').val());

        if (!precio || precio < 0) {
            alert('Ingresa un precio válido');
            return;
        }

        $.ajax({
            url: '<?php echo base_url("producto/actualizar_precio_compra"); ?>',
            method: 'POST',
            dataType: 'json',
            data: {
                id_producto: productoActual.id_producto,
                precio_compra: precio
            },
            success: function(response) {
                if (response.success) {
                    productoActual.precio_compra = precio;
                    $('#precio_compra_display').text('$' + precio.toFixed(2));
                    $('#modalPrecioCompra').modal('hide');
                    alert('Precio actualizado');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error al actualizar precio');
            }
        });
    });

    // Confirmar resurtimiento
    $('#btn_confirmar').on('click', function() {
        var stock_nuevo = parseInt($('#stock_nuevo').val());

        if (!stock_nuevo || stock_nuevo <= 0) {
            alert('Ingresa una cantidad válida');
            return;
        }

        if (!productoActual) {
            alert('Error: producto no está seleccionado');
            return;
        }

        // Enviar AJAX de resurtimiento
        $.ajax({
            url: '<?php echo base_url("producto/resurtir_producto"); ?>',
            method: 'POST',
            dataType: 'json',
            data: {
                id_producto: productoActual.id_producto,
                codigo: productoActual.codigo,
                stock_nuevo: stock_nuevo
            },
            success: function(response) {
                if (response.success) {
                    // Mostrar confirmación
                    $('#msg_producto_resurtido').text(
                        productoActual.nombre_producto + ' (Talla: ' + (productoActual.talla || 'N/A') + ')'
                    );
                    $('#stock_anterior_final').text(response.stock_anterior);
                    $('#cantidad_agregada_final').text(response.cantidad_agregada);
                    $('#stock_final').text(response.stock_nuevo);

                    $('#resultado_producto').hide();
                    $('#resultado_error').hide();
                    $('#resultado_multiples').hide();
                    $('#resultado_exito').show();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error al procesar resurtimiento');
            }
        });
    });

    // Presionar Enter en el campo de stock para confirmar
    $('#stock_nuevo').on('keyup', function(e) {
        if (e.key === 'Enter') {
            $('#btn_confirmar').click();
        }
    });

    // Redirigir al formulario de agregar producto con el código pre-llenado
    $('#btn_agregar_nuevo').on('click', function() {
        var codigo = $(this).data('codigo') || '';
        window.location.href = '<?php echo base_url("producto/add"); ?>/' + encodeURIComponent(codigo);
    });
});
</script>

<!-- Modal para editar precio de compra -->
<div class="modal fade" id="modalPrecioCompra" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Precio de Compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nuevo Precio de Compra</label>
                    <input type="number" class="form-control input-lg" id="nuevo_precio_compra" step="0.01" min="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_guardar_precio">Guardar</button>
            </div>
        </div>
    </div>
</div>
