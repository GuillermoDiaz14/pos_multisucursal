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
    justify-content: center;
    align-items: stretch;
    gap: var(--label-gap-mm, 0.5mm);
    background: #fff;
    border: 1px solid #999;
    overflow: hidden;
}

.label-name {
    font-size: var(--label-font-name-mm, 1.8mm);
    line-height: 1;
    font-weight: 700;
    text-align: center;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: clip;
    flex-shrink: 0;
    min-height: 0;
}

.label-barcode {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    flex-shrink: 0;
    width: 100%;
    overflow: hidden;
}

.label-barcode svg {
    display: block;
    margin: 0 auto;
    flex-shrink: 0;
}

.label-price {
    font-size: var(--label-font-price-mm, 2.3mm);
    line-height: 1;
    font-weight: 700;
    text-align: center;
    width: 100%;
    white-space: nowrap;
    flex-shrink: 0;
}

.label-code {
    font-size: var(--label-font-code-mm, 1.5mm);
    line-height: 1;
    text-align: center;
    color: #666;
    width: 100%;
    white-space: nowrap;
    flex-shrink: 0;
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
                                           placeholder="0.00" />
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
            fontName: 1.8, fontPrice: 2.3, fontCode: 1.5,
            showName: true, showPrice: true, showCodeText: true
        };
        var currentSettings = Object.assign({}, defaultSettings);
        var currentProduct = null;

        function loadLabelSettings() {
            try {
                var templates = JSON.parse(window.localStorage.getItem(labelTemplatesKey) || '[]');
                var activeId  = window.localStorage.getItem(activeTemplateKey);

                // Migrar valores px → mm (versiones anteriores guardaban px)
                templates.forEach(function(t) {
                    if (t.settings && t.settings.fontName > 10) {
                        var r = 25.4 / 96;
                        t.settings.fontName  = Math.round(t.settings.fontName  * r * 10) / 10;
                        t.settings.fontPrice = Math.round(t.settings.fontPrice * r * 10) / 10;
                        t.settings.fontCode  = Math.round(t.settings.fontCode  * r * 10) / 10;
                    }
                });

                var active = null;
                if (activeId) active = templates.find(function(t) { return t.id === activeId; });
                if (!active && templates.length) active = templates[0];
                if (active && active.settings) {
                    currentSettings = Object.assign({}, defaultSettings, active.settings);
                }
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

        function buildLabelNode(product) {
            var simboloMoneda = '<?php echo $configuracionInfo->simbolo_moneda ?? "$"; ?>';
            var s = currentSettings;

            // ── Todos los estilos inline para evitar que AdminLTE/Bootstrap los pise ──
            var label = document.createElement('div');
            label.style.cssText =
                'box-sizing:border-box;' +
                'width:'            + s.width    + 'mm;' +
                'height:'           + s.height   + 'mm;' +
                'padding:'          + s.padding  + 'mm;' +
                'display:flex;flex-direction:column;' +
                'justify-content:center;align-items:stretch;' +
                'gap:0.5mm;' +
                'background:#fff;overflow:hidden;border:1px solid #bbb;';

            if (s.showName) {
                var name = document.createElement('div');
                name.style.cssText =
                    'font-size:'     + s.fontName + 'mm;' +
                    'line-height:1;font-weight:700;text-align:center;' +
                    'width:100%;white-space:nowrap;overflow:hidden;' +
                    'text-overflow:clip;flex-shrink:0;min-height:0;';
                name.textContent = product.nombre_producto;
                label.appendChild(name);
            }

            var barcodeWrap = document.createElement('div');
            barcodeWrap.style.cssText =
                'display:flex;justify-content:center;align-items:flex-start;' +
                'flex-shrink:0;width:100%;overflow:hidden;' +
                'height:' + s.barcodeHeight + 'mm;';
            var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('class', 'js-label-barcode');
            svg.setAttribute('data-code', product.codigo);
            svg.style.cssText = 'display:block;margin:0 auto;flex-shrink:0;';
            barcodeWrap.appendChild(svg);
            label.appendChild(barcodeWrap);

            if (s.showCodeText) {
                var codeEl = document.createElement('div');
                codeEl.style.cssText =
                    'font-size:'  + s.fontCode + 'mm;' +
                    'line-height:1;text-align:center;color:#455a64;' +
                    'width:100%;white-space:nowrap;flex-shrink:0;';
                codeEl.textContent = product.codigo;
                label.appendChild(codeEl);
            }

            if (s.showPrice) {
                var price = document.createElement('div');
                price.style.cssText =
                    'font-size:'  + s.fontPrice + 'mm;' +
                    'line-height:1;font-weight:700;text-align:center;' +
                    'width:100%;white-space:nowrap;flex-shrink:0;';
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

                var DPM        = 8.0267;
                var innerW_mm  = currentSettings.width - 2 * currentSettings.padding;
                var innerW_dot = Math.round(innerW_mm * DPM);
                var barH_mm    = currentSettings.barcodeHeight;

                svgs.forEach(function(svg) {
                    var code     = svg.getAttribute('data-code') || '';
                    var isEan13  = /^\d{13}$/.test(code);
                    var totalMod = isEan13 ? 113 : (11 * code.length + 35);
                    var modDots  = Math.max(1, Math.floor(innerW_dot / totalMod));
                    var modPx    = Math.max(1, modDots / DPM * 3.78);

                    try {
                        JsBarcode(svg, code, {
                            format:       isEan13 ? 'EAN13' : 'CODE128',
                            width:        modPx,
                            height:       mmToPx(barH_mm),
                            margin:       0,
                            displayValue: false
                        });
                    } catch (e) {
                        console.error('Barcode error for code: ' + code, e);
                        return;
                    }

                    svg.removeAttribute('height');
                    svg.style.height   = barH_mm + 'mm';
                    svg.style.width    = 'auto';
                    svg.style.maxWidth = innerW_mm + 'mm';
                    svg.style.display  = 'block';
                    svg.style.margin   = '0 auto';
                });

                requestAnimationFrame(function() {
                    setTimeout(resolve, 150);
                });
            });
        }

        function printLabel() {
            if (!currentProduct) return;

            var quantity = parseInt($('#labelQuantity').val()) || 1;
            var btn = document.getElementById('btnPrintLabel');
            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Enviando...'; }

            // Construir ZPL concatenando N copias
            var allZpl = '';
            for (var i = 0; i < quantity; i++) {
                allZpl += buildLabelZPL(currentProduct, currentSettings) + '\n';
            }

            zebraGetPrinter(ZEBRA_LABEL_PRINTER)
            .then(function(device) {
                if (!device) { resetPrintBtn(btn); return; }
                return zebraSend(device, allZpl).then(function(ok) {
                    if (ok) {
                        zebraLog('✔ ' + quantity + ' etiqueta(s) enviadas.', 'ok');
                        closeLabelModal();
                    } else {
                        zebraLog('Error al imprimir etiqueta.', 'error');
                    }
                    resetPrintBtn(btn);
                });
            })
            .catch(function(err) {
                zebraLog('No se pudo conectar a Zebra Browser Print.', 'error');
                console.error('[Zebra add.php]', err);
                resetPrintBtn(btn);
            });
        }

        function resetPrintBtn(btn) {
            if (!btn) return;
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-print"></i> Imprimir';
        }

        function buildLabelZPL(product, s) {
            var DPM = 8.0267, GAP = 4;
            var W      = Math.round(s.width         * DPM);
            var H      = Math.round(s.height        * DPM);
            var pad    = Math.round(s.padding       * DPM);
            var barH   = Math.round(s.barcodeHeight * DPM);
            var nameH  = Math.max(8,  Math.round(s.fontName  * DPM));
            var priceH = Math.max(8,  Math.round(s.fontPrice * DPM));
            var codeH  = Math.max(6,  Math.round(s.fontCode  * DPM));
            var innerW = W - 2 * pad;

            var code    = String(product.codigo || '');
            var isEan13 = /^\d{13}$/.test(code);

            var totalMod = isEan13 ? 113 : (11 * code.length + 35);
            var moduleW  = Math.max(1, Math.floor(innerW / totalMod));
            var barX;
            if (isEan13) {
                var symLeft = pad + Math.round((innerW - totalMod * moduleW) / 2);
                barX = symLeft + 11 * moduleW;
            } else {
                barX = pad + Math.round((innerW - totalMod * moduleW) / 2);
            }
            barX = Math.max(pad, barX);

            var EAN_GUARD = isEan13 ? 13 : 0;
            var barHeff   = barH + EAN_GUARD;

            var elements = [];
            if (s.showName && product.nombre_producto) elements.push(nameH);
            elements.push(barHeff);
            if (s.showCodeText) elements.push(codeH);
            if (s.showPrice)    elements.push(priceH);

            var contentH = 0;
            for (var i = 0; i < elements.length; i++) {
                contentH += elements[i] + (i < elements.length - 1 ? GAP : 0);
            }

            var available = H - 2 * pad;
            var y   = pad + Math.max(0, Math.round((available - contentH) / 2));
            var zpl = ['^XA', '^CI28', '^PW' + W, '^LL' + H, '^LH0,0'];

            if (s.showName && product.nombre_producto) {
                var nm = String(product.nombre_producto).substring(0, 40);
                zpl.push('^FO' + pad + ',' + y + '^FB' + innerW + ',1,0,C,0^A0N,' + nameH + ',' + nameH + '^FD' + nm + '^FS');
                y += nameH + GAP;
            }
            if (isEan13) {
                zpl.push('^FO' + barX + ',' + y + '^BY' + moduleW + ',2,' + barH + '^BEN,' + barH + ',N,N^FD' + code + '^FS');
            } else {
                zpl.push('^FO' + barX + ',' + y + '^BY' + moduleW + ',2,' + barH + '^BCN,' + barH + ',N,N,N^FD' + code + '^FS');
            }
            y += barHeff + GAP;
            if (s.showCodeText) {
                zpl.push('^FO' + pad + ',' + y + '^FB' + innerW + ',1,0,C,0^A0N,' + codeH + ',' + codeH + '^FD' + code + '^FS');
                y += codeH + GAP;
            }
            if (s.showPrice) {
                var simbolo = '<?php echo $configuracionInfo->simbolo_moneda ?? "$"; ?>';
                var priceStr = simbolo + ' ' + Number(product.precio_venta || 0).toFixed(2);
                zpl.push('^FO' + pad + ',' + y + '^FB' + innerW + ',1,0,C,0^A0N,' + priceH + ',' + priceH + '^FD' + priceStr + '^FS');
            }
            zpl.push('^XZ');
            return zpl.join('\n');
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