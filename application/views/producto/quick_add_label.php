<?php
$simbolo_moneda = $configuracionInfo->simbolo_moneda;
?>

<style>
.quick-add-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.quick-add-form {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
}

.quick-add-form .form-group {
    margin-bottom: 15px;
}

.quick-add-form label {
    font-weight: 600;
    margin-bottom: 5px;
    display: block;
    font-size: 13px;
}

.quick-add-form input,
.quick-add-form select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 13px;
}

.quick-add-form .btn {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    font-weight: 600;
}

.code-display {
    background: #f5f5f5;
    padding: 8px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 12px;
    word-break: break-all;
    margin-top: 5px;
}

.label-preview-container {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    min-height: 350px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.label-preview-stage {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 200px;
    background: #f9f9f9;
    border-radius: 6px;
    padding: 10px;
    margin-bottom: 15px;
}

.label-card {
    box-sizing: border-box;
    width: var(--label-width-mm, 39mm);
    height: var(--label-height-mm, 16mm);
    padding: var(--label-padding-mm, 1mm);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: stretch;
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

.label-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.label-actions button {
    flex: 1;
    padding: 10px;
    font-size: 13px;
    font-weight: 600;
}

.status-message {
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 15px;
    font-size: 13px;
    display: none;
}

.status-message.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-message.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.empty-state {
    color: #999;
    padding: 40px 20px;
    text-align: center;
}

#print-root {
    display: none;
}

@media (max-width: 1199px) {
    .quick-add-container {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus-circle"></i> Agregar y Etiquetar
            <small>Inserta producto e imprime etiqueta al instante</small>
        </h1>
    </section>

    <section class="content">
        <div class="quick-add-container">
            <!-- Formulario -->
            <div>
                <div class="quick-add-form">
                    <h4 style="margin-top: 0;">Nuevo Producto</h4>

                    <div class="status-message" id="statusMessage"></div>

                    <form id="formQuickAdd">
                        <div class="form-group">
                            <label>Nombre *</label>
                            <input type="text" id="nombre_producto" name="nombre_producto" maxlength="200" required>
                        </div>

                        <div class="form-group">
                            <label>Categoría *</label>
                            <select id="id_categoria" name="id_categoria" required>
                                <option value="">Seleccionar</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat->id_categoria; ?>"><?php echo htmlspecialchars($cat->nombre_categoria, ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Precio Compra *</label>
                            <input type="number" id="precio_compra" name="precio_compra" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label>Precio Venta *</label>
                            <input type="number" id="precio_venta" name="precio_venta" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label>Stock *</label>
                            <input type="number" id="stock" name="stock" step="1" min="1" value="1" required>
                        </div>

                        <div class="form-group">
                            <label>Talla</label>
                            <input type="text" id="talla" name="talla" maxlength="50" placeholder="Ej: M, L, NA">
                        </div>

                        <div class="form-group">
                            <label>Código (Generado automáticamente)</label>
                            <div id="codigoDisplay" class="code-display">Presiona Agregar para generar</div>
                            <input type="hidden" id="tipo_codigo" name="tipo_codigo" value="generar">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-plus"></i> Agregar
                            </button>
                            <button type="button" class="btn btn-default" id="btnLimpiar">
                                <i class="fa fa-eraser"></i> Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview de Etiqueta -->
            <div>
                <div class="label-preview-container">
                    <h4 style="margin-top: 0;">Etiqueta</h4>

                    <div class="label-preview-stage" id="previewStage">
                        <div class="empty-state">Sin producto</div>
                    </div>

                    <div class="label-actions" id="labelActionsContainer" style="display:none;">
                        <button type="button" class="btn btn-success" id="btnImprimir">
                            <i class="fa fa-print"></i> Imprimir
                        </button>
                    </div>

                    <small style="color: #999; text-align: center;">
                        Usa la plantilla guardada • Config. en Impresión de etiquetas
                    </small>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="print-root"></div>

<!-- Modal: Producto agregado exitosamente -->
<div class="modal fade" id="modalProductoAgregado" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #5cb85c; color: white;">
                <h5 class="modal-title"><i class="fa fa-check-circle"></i> ¡Producto Agregado!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong id="modalProductoNombre"></strong></p>
                <p style="color: #666; font-size: 13px;">Código: <code id="modalProductoCodigo"></code></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnModalImprimir">
                    <i class="fa fa-print"></i> Imprimir Etiqueta
                </button>
                <button type="button" class="btn btn-default" id="btnModalContinuar">
                    <i class="fa fa-arrow-right"></i> Continuar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3/dist/JsBarcode.all.min.js"></script>
<script>
(function() {
    var symbolCurrency = <?php echo json_encode($simbolo_moneda); ?>;
    var labelTemplatesKey = 'pos_multisucursal_label_templates_v1';
    var activeTemplateKey = 'pos_multisucursal_label_active_template_v1';

    var defaultSettings = {
        width: 39, height: 16, padding: 1, barcodeHeight: 6.5,
        fontName: 7, fontPrice: 9, fontCode: 6,
        showName: true, showPrice: true, showCodeText: true
    };

    var currentProduct = null;
    var currentSettings = Object.assign({}, defaultSettings);
    var templates = [];
    var activeTemplateId = null;

    var form = document.getElementById('formQuickAdd');
    var statusMsg = document.getElementById('statusMessage');
    var codigoDisplay = document.getElementById('codigoDisplay');
    var previewStage = document.getElementById('previewStage');
    var btnImprimir = document.getElementById('btnImprimir');
    var btnLimpiar = document.getElementById('btnLimpiar');
    var printRoot = document.getElementById('print-root');

    loadTemplates();
    bindEvents();

    function bindEvents() {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            agregarProducto();
        });

        btnLimpiar.addEventListener('click', limpiarFormulario);
        btnImprimir.addEventListener('click', imprimirEtiqueta);

        // Botones de la modal
        document.getElementById('btnModalImprimir').addEventListener('click', function() {
            $('#modalProductoAgregado').modal('hide');
            imprimirEtiqueta();
        });

        document.getElementById('btnModalContinuar').addEventListener('click', function() {
            $('#modalProductoAgregado').modal('hide');
            limpiarFormulario();
        });
    }

    function agregarProducto() {
        var formData = new FormData(form);

        fetch('<?php echo base_url(); ?>producto/addNewProducto', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                currentProduct = data.producto;
                codigoDisplay.textContent = data.producto.codigo;
                showStatus(data.message, 'success');
                renderPreview().then(function() {
                    // Mostrar modal después de renderizar preview
                    document.getElementById('modalProductoNombre').textContent = data.producto.nombre_producto;
                    document.getElementById('modalProductoCodigo').textContent = data.producto.codigo;
                    $('#modalProductoAgregado').modal('show');
                });
            } else {
                showStatus(data.message || 'Error al agregar', 'error');
            }
        })
        .catch(e => {
            showStatus('Error: ' + e.message, 'error');
        });
    }

    function limpiarFormulario() {
        form.reset();
        currentProduct = null;
        codigoDisplay.textContent = 'Presiona Agregar para generar';
        previewStage.innerHTML = '<div class="empty-state">Sin producto</div>';
        btnImprimir.disabled = true;
        statusMsg.style.display = 'none';
        document.getElementById('nombre_producto').focus();
    }

    function renderPreview() {
        readSettings();
        if (!currentProduct) {
            previewStage.innerHTML = '<div class="empty-state">Sin producto</div>';
            return;
        }

        previewStage.innerHTML = '';
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

        var label = buildLabelNode(currentProduct);
        var scaleHost = document.createElement('div');
        scaleHost.style.width = currentSettings.width + 'mm';
        scaleHost.style.height = currentSettings.height + 'mm';
        scaleHost.style.transform = 'scale(' + previewScale + ')';
        scaleHost.style.transformOrigin = 'center center';

        scaleHost.appendChild(label);
        previewWrap.appendChild(scaleHost);
        previewStage.appendChild(previewWrap);
        return renderBarcodes(previewStage);
    }

    function getPreviewScale() {
        var maxWidthMm = 60;
        var maxHeightMm = 30;
        var widthScale = maxWidthMm / currentSettings.width;
        var heightScale = maxHeightMm / currentSettings.height;
        return Math.max(1.5, Math.min(4, Math.min(widthScale, heightScale)));
    }

    function buildLabelNode(product) {
        var label = document.createElement('div');
        label.className = 'label-card';
        label.style.setProperty('--label-width-mm', currentSettings.width + 'mm');
        label.style.setProperty('--label-height-mm', currentSettings.height + 'mm');
        label.style.setProperty('--label-padding-mm', currentSettings.padding + 'mm');
        label.style.setProperty('--label-barcode-height-mm', currentSettings.barcodeHeight + 'mm');
        label.style.setProperty('--label-font-name-px', currentSettings.fontName + 'px');
        label.style.setProperty('--label-font-price-px', currentSettings.fontPrice + 'px');
        label.style.setProperty('--label-font-code-px', currentSettings.fontCode + 'px');

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
            price.textContent = symbolCurrency + ' ' + parseFloat(product.precio_venta).toFixed(2);
            label.appendChild(price);
        }

        return label;
    }

    function renderBarcodes(container) {
        return new Promise(function(resolve) {
            var svgs = Array.prototype.slice.call(container.querySelectorAll('.js-label-barcode'));
            if (svgs.length === 0) {
                resolve();
                return;
            }

            svgs.forEach(function(svg) {
                var code = svg.getAttribute('data-code') || '';
                var isEan13 = /^\d{13}$/.test(code);
                try {
                    JsBarcode(svg, code, {
                        format: isEan13 ? 'EAN13' : 'CODE128',
                        width: 1, height: 45, margin: 0, displayValue: false
                    });
                } catch (e) {
                    console.error('Barcode error:', e);
                }
            });

            setTimeout(resolve, 100);
        });
    }

    function imprimirEtiqueta() {
        if (!currentProduct) return;

        readSettings();
        printRoot.innerHTML = '';
        var label = buildLabelNode(currentProduct);
        label.style.width = currentSettings.width + 'mm';
        label.style.height = currentSettings.height + 'mm';
        label.classList.add('print-label');
        printRoot.appendChild(label);

        injectPrintStyles();
        renderBarcodes(printRoot).then(function() {
            setTimeout(function() {
                window.print();
                // Después de imprimir, limpiar
                setTimeout(limpiarFormulario, 500);
            }, 200);
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
                '@page { size: ' + currentSettings.width + 'mm ' + currentSettings.height + 'mm; margin: 0; }' +
                'html, body { margin: 0 !important; padding: 0 !important; }' +
                'body * { visibility: hidden !important; }' +
                '#print-root, #print-root * { visibility: visible !important; }' +
                '#print-root { display: block !important; width: 100%; }' +
                '.print-label { width: ' + currentSettings.width + 'mm !important; height: ' + currentSettings.height + 'mm !important; page-break-after: always; margin: 0 !important; }' +
            '}';
    }

    function loadTemplates() {
        try {
            templates = JSON.parse(window.localStorage.getItem(labelTemplatesKey) || '[]');
            activeTemplateId = window.localStorage.getItem(activeTemplateKey);
        } catch (e) {
            templates = [];
        }

        if (!templates.length) {
            templates = [{
                id: 'default_39x16',
                name: 'Zebra 39x16',
                settings: Object.assign({}, defaultSettings)
            }];
            activeTemplateId = templates[0].id;
        }

        if (activeTemplateId && templates.some(t => t.id === activeTemplateId)) {
            var tpl = templates.find(t => t.id === activeTemplateId);
            currentSettings = Object.assign({}, defaultSettings, tpl.settings || {});
        }
    }

    function showStatus(msg, type) {
        statusMsg.textContent = msg;
        statusMsg.className = 'status-message ' + type;
        statusMsg.style.display = 'block';
        if (type === 'success') {
            setTimeout(() => statusMsg.style.display = 'none', 5000);
        }
    }
})();
</script>
