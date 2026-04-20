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
                            <!-- Búsqueda por código -->
                            <div class="form-group">
                                <label>Escanea el código de barras del producto</label>
                                <input type="text" 
                                       class="form-control input-lg" 
                                       id="codigo_escaneo"
                                       placeholder="Escanea aquí el código..."
                                       autofocus
                                       autocomplete="off">
                                <small class="form-text text-muted">
                                    Escanea el código EAN-13 del producto que deseas resurtir
                                </small>
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
                                <p>
                                    ¿Es un producto nuevo? 
                                    <a href="<?php echo base_url('producto/add'); ?>">Agregarlo aquí</a>
                                </p>
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
            var codigo = $(this).val().trim();
            
            if (!codigo) {
                alert('Escanea un código válido');
                return;
            }

            buscar_producto(codigo);
        }
    });

    // Función de búsqueda AJAX
    function buscar_producto(codigo) {
        $.ajax({
            url: '<?php echo base_url("producto/buscar_por_codigo"); ?>',
            method: 'POST',
            dataType: 'json',
            data: { codigo: codigo },
            success: function(response) {
                if (response.success) {
                    productoActual = response.producto;
                    
                    // Mostrar datos del producto
                    $('#nombre_producto').text(response.producto.nombre_producto);
                    $('#talla_producto').text(response.producto.talla || 'N/A');
                    $('#ean13_producto').text(response.producto.codigo);
                    $('#stock_actual').text(response.stock_sucursal + ' unidades');
                    
                    // Mostrar formulario
                    $('#resultado_error').hide();
                    $('#resultado_exito').hide();
                    $('#resultado_producto').show();
                    
                    // Enfocar en cantidad
                    $('#stock_nuevo').val('').focus();
                } else {
                    // Producto no encontrado
                    $('#codigo_buscado').text(codigo);
                    $('#resultado_producto').hide();
                    $('#resultado_exito').hide();
                    $('#resultado_error').show();
                    
                    // Limpiar para nuevo escaneo
                    setTimeout(function() {
                        $('#codigo_escaneo').val('').focus();
                    }, 2000);
                }
            },
            error: function() {
                alert('Error en la búsqueda');
                $('#codigo_escaneo').focus();
            }
        });
    }

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
});
</script>
