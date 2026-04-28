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

/* Modal de impresión de etiqueta */
.label-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
}

.label-modal.active {
    display: flex;
}

.label-modal-content {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.label-preview-box {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
    min-height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.label-card {
    box-sizing: border-box;
    width: var(--label-width-mm, 39mm);
    height: var(--label-height-mm, 16mm);
    padding: var(--label-padding-mm, 1mm);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background: #fff;
    border: 1px solid #999;
    overflow: hidden;
}

.label-name {
    font-size: var(--label-font-name-px, 7px);
    line-height: 1.1;
    font-weight: 700;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.label-barcode {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: var(--label-barcode-height-mm, 6.5mm);
    overflow: hidden;
}

.label-barcode svg {
    max-width: 100%;
    max-height: 100%;
}

.label-price {
    font-size: var(--label-font-price-px, 9px);
    line-height: 1;
    font-weight: 700;
    text-align: center;
    white-space: nowrap;
}

.label-code {
    font-size: var(--label-font-code-px, 6px);
    line-height: 1;
    text-align: center;
    color: #666;
    white-space: nowrap;
}

.label-modal-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 20px;
}

.label-modal-actions button {
    padding: 12px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.label-modal-actions .btn-success {
    background: #28a745;
    color: white;
}

.label-modal-actions .btn-success:hover {
    background: #218838;
}

.label-modal-actions .btn-default {
    background: #ddd;
    color: #333;
}

.label-modal-actions .btn-default:hover {
    background: #ccc;
}

#print-root {
    display: none;
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
                                    <input type="text" class="form-control" value="<?php echo set_value('talla'); ?>" id="talla" name="talla" maxlength="50" placeholder="Ej: Unitalla, CH, M, G, 28, 25.5, 40, NA" />
                                    <small class="form-text text-muted">Dejar vacío si no aplica (se guardará como 'NA').</small>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="precio_compra">Precio Compra
                                        <?php if (!empty($permisos['ver_precio_compra'])): ?>
                                            <span class="text-success"><i class="fa fa-check-circle"></i></span>
                                        <?php else: ?>
                                            <span class="text-muted" title="Sin permiso para ver precio"><i class="fa fa-eye-slash"></i></span>
                                        <?php endif; ?>
                                    </label>
                                    <input type="number"
                                           class="form-control required"
                                           value="<?php echo set_value('precio_compra'); ?>"
                                           id="precio_compra"
                                           name="precio_compra"
                                           maxlength="12"
                                           inputmode="numeric"
                                           pattern="[0-9]+(\.[0-9]+)?"
                                           placeholder="0.00"
                                           <?php echo empty($permisos['ver_precio_compra']) ? 'readonly' : ''; ?> />
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
                                               value="<?php echo set_value('codigo_proveedor', isset($codigo_prefill) ? $codigo_prefill : ''); ?>"
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
                <!-- Preview de Etiqueta -->
                <div class="box box-info" style="display:none;">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-tag"></i> Previsualización de Etiqueta</h3>
                    </div>
                    <div class="box-body" style="text-align: center; min-height: 280px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                        <div id="livePreviewStage" style="margin-bottom: 15px;">
                            <div style="color: #999; padding: 20px;">Completa los datos para ver la etiqueta</div>
                        </div>
                        <small style="color: #999; text-align: center; display: block;">
                            Usa la configuración guardada en<br><strong>Impresión de etiquetas</strong>
                        </small>
                    </div>
                </div>

                <!-- Contenedor de notificaciones (solo AJAX) -->
                <div id="notificaciones-container"></div>
            </div>
        </div>    
    </section>

</div>

<!-- Modal de Impresión de Etiqueta -->
<div id="labelModal" class="label-modal">
    <div class="label-modal-content">
        <h4 id="labelModalTitle">Producto agregado</h4>
        <p id="labelModalProduct" style="color: #666; margin: 10px 0;"></p>

        <div class="label-preview-box" id="labelPreviewBox">
            <div style="color: #999;">Cargando preview...</div>
        </div>

        <div style="margin: 15px 0; padding: 15px; background: #f9f9f9; border-radius: 5px;">
            <label for="labelQuantity" style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px;">Cantidad de etiquetas:</label>
            <div style="display: flex; gap: 8px;">
                <button type="button" id="btnMinus" style="width: 40px; padding: 8px; background: #ddd; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">−</button>
                <input type="number" id="labelQuantity" min="1" max="100" value="1" style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px; text-align: center; font-size: 14px; font-weight: 600;">
                <button type="button" id="btnPlus" style="width: 40px; padding: 8px; background: #ddd; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">+</button>
            </div>
        </div>

        <div class="label-modal-actions">
            <button class="btn-success" id="btnPrintLabel">
                <i class="fa fa-print"></i> Imprimir Etiqueta(s)
            </button>
            <button class="btn-default" id="btnSkipLabel">
                Continuar sin imprimir
            </button>
        </div>
    </div>
</div>

<div id="print-root"></div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3/dist/JsBarcode.all.min.js"></script>

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
                        btn.prop('disabled', false).text('Agregar');

                        // Mostrar modal con preview de etiqueta
                        if (response.producto) {
                            showLabelModal(response.producto);
                            // Limpiar formulario después de mostrar modal
                            form[0].reset();
                            $('#id_categoria').val('');
                            $('#ean13_generado').val('');
                        } else {
                            // Fallback: mostrar mensaje si no hay producto
                            $('#notificaciones-container').html(
                                '<div class="alert alert-success alert-dismissable" id="alert-success">' +
                                '<button type="button" class="close" data-dismiss="alert">×</button>' +
                                response.message +
                                '</div>'
                            );
                            form[0].reset();
                            $('#id_categoria').val('');
                            $('#ean13_generado').val('');
                        }
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

        // Variables globales para etiquetas
        var labelTemplatesKey = 'pos_multisucursal_label_templates_v1';
        var activeTemplateKey = 'pos_multisucursal_label_active_template_v1';
        var defaultSettings = {
            width: 39, height: 16, padding: 1, barcodeHeight: 6.5,
            fontName: 7, fontPrice: 9, fontCode: 6,
            showName: true, showPrice: true, showCodeText: true
        };
        var currentSettings = Object.assign({}, defaultSettings);
        var currentProduct = null;

        function loadLabelSettings() {
            try {
                var templatesStr = window.localStorage.getItem(labelTemplatesKey);
                var activeId = window.localStorage.getItem(activeTemplateKey);

                if (templatesStr) {
                    var templates = JSON.parse(templatesStr);
                    if (Array.isArray(templates) && templates.length > 0) {
                        // Si hay un template activo, usarlo
                        if (activeId) {
                            var active = templates.find(t => t.id === activeId);
                            if (active && active.settings) {
                                currentSettings = Object.assign({}, defaultSettings, active.settings);
                                console.log('Loaded template:', active.name);
                                return;
                            }
                        }
                        // Si no hay activo, usar el primero
                        if (templates[0].settings) {
                            currentSettings = Object.assign({}, defaultSettings, templates[0].settings);
                            console.log('Loaded first template:', templates[0].name);
                            return;
                        }
                    }
                }
                console.log('Using default label settings');
            } catch (e) {
                console.log('Error loading label settings:', e.message);
            }
        }

        function showLabelModal(producto) {
            currentProduct = producto;
            var simboloMoneda = '<?php echo $configuracionInfo->simbolo_moneda ?? "$"; ?>';

            $('#labelModalTitle').text('✓ ' + producto.nombre_producto);
            $('#labelModalProduct').text('Código: ' + producto.codigo + ' | Precio: ' + simboloMoneda + ' ' + parseFloat(producto.precio_venta).toFixed(2));
            $('#labelQuantity').val(1);

            loadLabelSettings();
            renderLabelPreview(producto);
            $('#labelModal').addClass('active');
        }

        function mmToPx(mm) {
            return Math.max(18, Math.round(mm * 3.78));
        }

        function getPreviewScale() {
            var maxWidthMm = 72;
            var maxHeightMm = 36;
            var widthScale = maxWidthMm / currentSettings.width;
            var heightScale = maxHeightMm / currentSettings.height;
            return Math.max(2.2, Math.min(5.2, Math.min(widthScale, heightScale)));
        }

        function renderLabelPreview(producto) {
            var previewBox = document.getElementById('labelPreviewBox');
            previewBox.innerHTML = '';

            var previewScale = getPreviewScale();
            var previewWrap = document.createElement('div');
            previewWrap.style.width = (currentSettings.width * previewScale) + 'mm';
            previewWrap.style.height = (currentSettings.height * previewScale) + 'mm';
            previewWrap.style.display = 'flex';
            previewWrap.style.alignItems = 'center';
            previewWrap.style.justifyContent = 'center';
            previewWrap.style.overflow = 'hidden';
            previewWrap.style.position = 'relative';

            var label = buildLabelNode(producto);
            var scaleHost = document.createElement('div');
            scaleHost.style.width = currentSettings.width + 'mm';
            scaleHost.style.height = currentSettings.height + 'mm';
            scaleHost.style.transform = 'scale(' + previewScale + ')';
            scaleHost.style.transformOrigin = 'center center';
            scaleHost.style.flexShrink = '0';

            scaleHost.appendChild(label);
            previewWrap.appendChild(scaleHost);
            previewBox.appendChild(previewWrap);

            renderBarcodes(previewBox);
        }

        function buildLabelNode(product, printVersion) {
            var simboloMoneda = '<?php echo $configuracionInfo->simbolo_moneda ?? "$"; ?>';
            var label = document.createElement('div');
            label.className = 'label-card' + (printVersion ? ' print-label' : '');
            label.style.setProperty('--label-width-mm', currentSettings.width + 'mm');
            label.style.setProperty('--label-height-mm', currentSettings.height + 'mm');
            label.style.setProperty('--label-padding-mm', currentSettings.padding + 'mm');
            label.style.setProperty('--label-barcode-height-mm', currentSettings.barcodeHeight + 'mm');
            label.style.setProperty('--label-font-name-px', currentSettings.fontName + 'px');
            label.style.setProperty('--label-font-price-px', currentSettings.fontPrice + 'px');
            label.style.setProperty('--label-font-code-px', currentSettings.fontCode + 'px');
            label.style.marginRight = '0';
            label.style.marginBottom = '0';

            if (printVersion) {
                label.style.width = currentSettings.width + 'mm';
                label.style.height = currentSettings.height + 'mm';
            }

            if (currentSettings.showName) {
                var name = document.createElement('div');
                name.className = 'label-name';
                name.textContent = product.nombre_producto;
                label.appendChild(name);
            }

            var barcodeWrap = document.createElement('div');
            barcodeWrap.className = 'label-barcode';
            var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('class', 'js-label-barcode');
            svg.setAttribute('data-code', product.codigo);
            barcodeWrap.appendChild(svg);
            label.appendChild(barcodeWrap);

            if (currentSettings.showCodeText) {
                var code = document.createElement('div');
                code.className = 'label-code';
                code.textContent = product.codigo;
                label.appendChild(code);
            }

            if (currentSettings.showPrice) {
                var price = document.createElement('div');
                price.className = 'label-price';
                price.textContent = simboloMoneda + ' ' + parseFloat(product.precio_venta).toFixed(2);
                label.appendChild(price);
            }

            return label;
        }

        function renderBarcodes(container) {
            return new Promise(function(resolve) {
                var svgs = Array.prototype.slice.call(container.querySelectorAll('.js-label-barcode'));

                if (svgs.length === 0) {
                    requestAnimationFrame(resolve);
                    return;
                }

                svgs.forEach(function(svg) {
                    var code = svg.getAttribute('data-code') || '';
                    var isEan13 = /^\d{13}$/.test(code);
                    try {
                        JsBarcode(svg, code, {
                            format: isEan13 ? 'EAN13' : 'CODE128',
                            width: isEan13 ? 1 : 1.1,
                            height: mmToPx(currentSettings.barcodeHeight),
                            margin: 0,
                            displayValue: false
                        });
                    } catch (e) {
                        console.error('Barcode error for code: ' + code, e);
                    }
                });

                requestAnimationFrame(function() {
                    setTimeout(resolve, 150);
                });
            });
        }

        function printLabel() {
            if (!currentProduct) return;

            var quantity = parseInt($('#labelQuantity').val()) || 1;
            var printRoot = document.getElementById('print-root');
            printRoot.innerHTML = '';

            var fragment = document.createDocumentFragment();
            for (var i = 0; i < quantity; i++) {
                fragment.appendChild(buildLabelNode(currentProduct, true));
            }
            printRoot.appendChild(fragment);

            injectPrintStyles();
            renderBarcodes(printRoot).then(function() {
                window.print();
                closeLabelModal();
            });
        }

        function injectPrintStyles() {
            var styleId = 'dynamic-label-print-style';
            var style = document.getElementById(styleId);
            if (!style) {
                style = document.createElement('style');
                style.id = styleId;
                document.head.appendChild(style);
            }

            style.textContent =
                '@media print {' +
                    '@page { size: ' + currentSettings.width + 'mm ' + currentSettings.height + 'mm; margin: 0; padding: 0; }' +
                    'html, body { margin: 0 !important; padding: 0 !important; }' +
                    'body * { visibility: hidden !important; }' +
                    '#print-root, #print-root * { visibility: visible !important; }' +
                    '#print-root { display: block !important; position: relative !important; width: 100% !important; height: auto !important; margin: 0 !important; padding: 0 !important; }' +
                    '.print-label { width: ' + currentSettings.width + 'mm !important; height: ' + currentSettings.height + 'mm !important; page-break-after: always; break-after: page; margin: 0 !important; padding: 0 !important; page-break-inside: avoid; display: block !important; }' +
                '}';
        }

        function closeLabelModal() {
            $('#labelModal').removeClass('active');
            currentProduct = null;
        }

        // Event handlers - Botones de cantidad
        $('#btnMinus').on('click', function() {
            var input = $('#labelQuantity');
            var val = parseInt(input.val()) || 1;
            if (val > 1) input.val(val - 1);
        });

        $('#btnPlus').on('click', function() {
            var input = $('#labelQuantity');
            var val = parseInt(input.val()) || 1;
            if (val < 100) input.val(val + 1);
        });

        $('#labelQuantity').on('change', function() {
            var val = parseInt($(this).val()) || 1;
            if (val < 1) $(this).val(1);
            if (val > 100) $(this).val(100);
        });

        // Event handlers - Modal
        $('#btnPrintLabel').on('click', function() {
            printLabel();
        });

        $('#btnSkipLabel').on('click', function() {
            closeLabelModal();
            $('#codigo_proveedor').focus();
        });

        // Cerrar modal al presionar ESC
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLabelModal();
            }
        });

        // ============= PREVIEW EN VIVO =============
        function updateLivePreview() {
            var nombre = $('#nombre_producto').val().trim();
            var precio = parseFloat($('#precio_venta').val()) || 0;
            var codigo = $('#codigo_proveedor').val().trim() || $('#ean13_generado').val().trim() || '0000000000000';

            var liveStage = document.getElementById('livePreviewStage');

            if (!nombre || precio <= 0) {
                liveStage.innerHTML = '<div style="color: #999; padding: 20px;">Completa los datos para ver la etiqueta</div>';
                return;
            }

            loadLabelSettings();

            var mockProduct = {
                nombre_producto: nombre,
                precio_venta: precio,
                codigo: codigo
            };

            liveStage.innerHTML = '';
            var previewScale = getPreviewScale();
            var previewWrap = document.createElement('div');
            previewWrap.style.width = (currentSettings.width * previewScale) + 'mm';
            previewWrap.style.height = (currentSettings.height * previewScale) + 'mm';
            previewWrap.style.padding = '4px';
            previewWrap.style.background = '#fff';
            previewWrap.style.border = '1px solid #d5dee5';
            previewWrap.style.borderRadius = '4px';
            previewWrap.style.display = 'flex';
            previewWrap.style.alignItems = 'center';
            previewWrap.style.justifyContent = 'center';
            previewWrap.style.overflow = 'hidden';
            previewWrap.style.position = 'relative';

            var label = buildLabelNode(mockProduct);
            var scaleHost = document.createElement('div');
            scaleHost.style.width = currentSettings.width + 'mm';
            scaleHost.style.height = currentSettings.height + 'mm';
            scaleHost.style.transform = 'scale(' + previewScale + ')';
            scaleHost.style.transformOrigin = 'center center';
            scaleHost.style.flexShrink = '0';

            scaleHost.appendChild(label);
            previewWrap.appendChild(scaleHost);
            liveStage.appendChild(previewWrap);
            renderBarcodes(liveStage);
        }

        // Actualizar preview cuando cambie nombre o precio
        $('#nombre_producto').on('keyup change', updateLivePreview);
        $('#precio_venta').on('keyup change', updateLivePreview);
        $('#codigo_proveedor').on('keyup change', updateLivePreview);
        $('#ean13_generado').on('change', updateLivePreview);

        // Si el código fue pre-llenado desde resurtir, disparar preview
        if ($('#codigo_proveedor').val().trim()) {
            updateLivePreview();
        }
    });
</script>