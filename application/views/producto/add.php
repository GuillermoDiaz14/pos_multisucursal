<style>

/* este css es para el imput buscar cliente */
.custom-select {
    position: relative;
}

.search-input {
    width: 100%;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.categoria-list {
    list-style: none;
    padding: 0;
    margin: 0;
    position: absolute;
    width: 100%;
    max-height: 150px; /* Altura máxima de la lista desplegable */
    overflow-y: auto;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff; /* Fondo de la lista */
    z-index: 1000; /* Asegura que esté por encima de otros elementos */
}

.categoria-list li {
    padding: 5px;
    cursor: pointer;
}

.categoria-list li:hover {
    background-color: #f2f2f2;
}




</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Agregar producto
        <small>producto</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Datos producto</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addProducto" action="<?php echo base_url() ?>producto/addNewProducto" method="post" role="form" enctype="multipart/form-data">
                        <div class="box-body">

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="nombre_producto">Nombre</label>
                                    <input type="text" class="form-control required" value="<?php echo set_value('nombre_producto'); ?>" id="nombre_producto" name="nombre_producto" maxlength="256" />
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-group custom-select">
                                    <label for="id_categoria">Categoría</label>
                                    <input type="text" class="search-input" id="search_categoria" placeholder="Buscar categoría" value="<?php echo set_value('id_categoria'); ?>" />
                                    <ul class="categoria-list">
                                        <?php foreach ($categorias as $categoria): ?>
                                            <li data-value="<?php echo $categoria->id_categoria; ?>"><?php echo $categoria->nombre_categoria; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <input type="hidden" id="id_categoria" name="id_categoria" readonly />
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="imagen">Imagen <span style="color:gray;">(Opcional)</span></label>
                                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" />
                                    <small class="form-text text-muted">Formatos: JPG, PNG, GIF. Se comprimirá automáticamente.</small>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="talla">Talla</label>
                                    <input type="text" class="form-control" value="<?php echo set_value('talla'); ?>" id="talla" name="talla" maxlength="50" placeholder="Ej: Único, S, M, L, 28, 38, 40, NA" />
                                    <small class="form-text text-muted">Valores típicos en México; dejar vacío si no aplica (se guardará como 'NA').</small>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="precio_compra">Precio Compra</label>
                                    <input type="number" class="form-control required" value="<?php echo set_value('precio_compra'); ?>" id="precio_compra" name="precio_compra" maxlength="12" inputmode="numeric" pattern="[0-9]+(\.[0-9]+)?" placeholder="0.00" />
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="precio_venta">Precio Venta</label>
                                    <input type="number" class="form-control required" value="<?php echo set_value('precio_venta'); ?>" id="precio_venta" name="precio_venta" maxlength="12" inputmode="numeric" pattern="[0-9]+(\.[0-9]+)?" placeholder="0.00" />
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-8">
                                <div class="form-group">
                                    <label>Código de Barras</label>
                                    
                                    <!-- OPCIÓN A: Código del Proveedor -->
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="tipo_codigo" value="proveedor" checked>
                                            ✓ Producto CON código (escanear código del proveedor)
                                        </label>
                                    </div>
                                    <div id="input_proveedor">
                                        <input type="text" 
                                               class="form-control required" 
                                               id="codigo_proveedor"
                                               name="codigo_proveedor"
                                               maxlength="13" 
                                               placeholder="Escanea aquí el código..."
                                               autofocus />
                                        <small class="form-text text-muted">Escanea el código de barras del proveedor</small>
                                    </div>
                                    
                                    <hr style="margin: 10px 0;">
                                    
                                    <!-- OPCIÓN B: Generar Automático -->
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="tipo_codigo" value="generar">
                                            ○ Producto SIN código (generar automáticamente)
                                        </label>
                                    </div>
                                    <div id="input_generar" style="display:none;">
                                        <p class="text-muted small">
                                            El sistema generará un código único. 
                                            
                                        </p>
                                        <button type="button" class="btn btn-sm btn-info" id="btn_generar_ean">
                                            📋 Generar Código
                                        </button>
                                        <input type="text" class="form-control" id="ean13_generado" readonly style="margin-top:5px;" />
                                    </div>
                                    
                                    <input type="hidden" name="usar_codigo_generado" id="usar_codigo_generado" value="0">
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="stock">Stock</label>
                                    <input type="number" class="form-control required" value="<?php echo set_value('stock'); ?>" id="stock" name="stock" min="0" />
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="detalles">Detalles</label>
                                    <textarea class="form-control" id="detalles" name="detalles"><?php echo set_value('detalles'); ?></textarea>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <button type="button" class="btn btn-primary" id="btn_agregar_producto">Agregar</button>
                            <input type="reset" class="btn btn-default" value="Vaciar" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Contenedor de notificaciones (solo AJAX) -->
                <div id="notificaciones-container"></div>
            </div>
        </div>    
    </section>
    
</div>

<script>

    // eSTE ES EL JAVAscript para controlar busqueda categoria
    $(document).ready(function() {
        $('#search_categoria').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            
            $('.categoria-list li').each(function() {
                var itemText = $(this).text().toLowerCase();
                
                if (itemText.indexOf(searchText) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#search_categoria').on('focus', function() {
            $('.categoria-list').show(); // Mostrar la lista cuando el campo de búsqueda está enfocado
        });

        $('.categoria-list li').on('click', function() {
            var selectedValue = $(this).attr('data-value');
            var selectedText = $(this).text();

            $('#id_categoria').val(selectedValue);
            $('#search_categoria').val(selectedText);
            $('.categoria-list').hide(); // Ocultar la lista después de seleccionar un elemento
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('.custom-select').length) {
                $('.categoria-list').hide(); // Ocultar la lista si se hace clic fuera del campo de búsqueda o la lista
            }
        });

        // Manejo de tipo de código (proveedor vs generado)
        $('input[name="tipo_codigo"]').on('change', function() {
            if ($(this).val() === 'generar') {
                $('#input_proveedor').hide();
                $('#input_generar').show();
                $('#codigo_proveedor').removeClass('required');
                $('#usar_codigo_generado').val(1);
            } else {
                $('#input_proveedor').show();
                $('#input_generar').hide();
                $('#codigo_proveedor').addClass('required').focus();
                $('#usar_codigo_generado').val(0);
            }
        });

        // Generar EAN-13
        $('#btn_generar_ean').on('click', function() {
            var btn = $(this);
            btn.prop('disabled', true).text('Generando...');
            
            $.ajax({
                url: '<?php echo base_url("producto/generar_ean13_ajax"); ?>',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#ean13_generado').val(response.ean13);
                        btn.prop('disabled', false).text('📋 Generar Código');
                    } else {
                        alert('Error: ' + response.message);
                        btn.prop('disabled', false).text('📋 Generar Código');
                    }
                },
                error: function() {
                    alert('Error al generar el código');
                    btn.prop('disabled', false).text('📋 Generar Código');
                }
            });
        });

        // Enviar formulario por AJAX - No redireccionar
        $('#btn_agregar_producto').on('click', function(e) {
            e.preventDefault();
            
            var form = $('#addProducto');
            var formData = new FormData(form[0]);
            var btn = $(this);
            
            btn.prop('disabled', true).text('Agregando...');
            
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Limpiar formulario
                        form[0].reset();
                        $('#id_categoria').val('');
                        $('#ean13_generado').val('');
                        
                        // Mostrar mensaje de éxito
                        var successMsg = '<div class="alert alert-success alert-dismissable">' +
                            '<button type="button" class="close" data-dismiss="alert">×</button>' +
                            '✓ ' + response.message +
                            '</div>';
                        
                        // Insertar mensaje en panel de errores
                        $('.col-md-4').prepend(successMsg);
                        
                        // Auto-desaparecer el mensaje después de 8 segundos
                        setTimeout(function() {
                            $('.alert-success').fadeOut(300, function() { $(this).remove(); });
                        }, 8000);
                        
                        // Volver a enfoque en código de proveedor para siguiente producto
                        $('#codigo_proveedor').focus();
                        btn.prop('disabled', false).text('Agregar');
                    } else {
                        alert('Error: ' + response.message);
                        btn.prop('disabled', false).text('Agregar');
                    }
                },
                error: function() {
                    alert('Error al agregar el producto');
                    btn.prop('disabled', false).text('Agregar');
                }
            });
        });
    });
</script>