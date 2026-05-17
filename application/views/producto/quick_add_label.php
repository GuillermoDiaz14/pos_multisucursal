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

/* ── label-card: mismo diseño que etiqueta.php ── */
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
    border: 1px solid #bbb;
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

.status-message.success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
.status-message.error   { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }

.empty-state { color:#999; padding:40px 20px; text-align:center; }

#print-root { display:none; }

@media (max-width: 1199px) {
    .quick-add-container { grid-template-columns: 1fr; }
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
                    <h4 style="margin-top:0;">Nuevo Producto</h4>

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

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
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

            <!-- Preview de etiqueta -->
            <div>
                <div class="label-preview-container">
                    <h4 style="margin-top:0;">Etiqueta</h4>

                    <div class="label-preview-stage" id="previewStage">
                        <div class="empty-state">Sin producto</div>
                    </div>

                    <div class="label-actions" id="labelActionsContainer" style="display:none;">
                        <button type="button" class="btn btn-success" id="btnImprimir">
                            <i class="fa fa-print"></i> Imprimir etiqueta
                        </button>
                    </div>

                    <small style="color:#999;text-align:center;">
                        Usa la plantilla activa · Configura en <strong>Impresión de etiquetas</strong>
                    </small>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="print-root"></div>

<!-- Modal: Producto agregado -->
<div class="modal fade" id="modalProductoAgregado" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#5cb85c;color:#fff;">
                <h5 class="modal-title"><i class="fa fa-check-circle"></i> ¡Producto Agregado!</h5>
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong id="modalProductoNombre"></strong></p>
                <p style="color:#666;font-size:13px;">Código: <code id="modalProductoCodigo"></code></p>
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
    var activeTemplateKey  = 'pos_multisucursal_label_active_template_v1';

    /* Valores por defecto en mm — igual que etiqueta.php */
    var defaultSettings = {
        width: 39, height: 16, padding: 1, barcodeHeight: 6.5,
        fontName: 1.8, fontPrice: 2.3, fontCode: 1.5,
        showName: true, showPrice: true, showCodeText: true
    };

    var currentProduct  = null;
    var currentSettings = Object.assign({}, defaultSettings);

    var form             = document.getElementById('formQuickAdd');
    var statusMsg        = document.getElementById('statusMessage');
    var codigoDisplay    = document.getElementById('codigoDisplay');
    var previewStage     = document.getElementById('previewStage');
    var btnImprimir      = document.getElementById('btnImprimir');
    var btnLimpiar       = document.getElementById('btnLimpiar');
    var actionsContainer = document.getElementById('labelActionsContainer');

    loadTemplate();
    bindEvents();

    /* ── Eventos ─────────────────────────────────────────────────────────── */
    function bindEvents() {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            agregarProducto();
        });

        btnLimpiar.addEventListener('click', limpiarFormulario);
        btnImprimir.addEventListener('click', imprimirZPL);

        document.getElementById('btnModalImprimir').addEventListener('click', function() {
            $('#modalProductoAgregado').modal('hide');
            imprimirZPL();
        });

        document.getElementById('btnModalContinuar').addEventListener('click', function() {
            $('#modalProductoAgregado').modal('hide');
            limpiarFormulario();
        });
    }

    /* ── Alta de producto ─────────────────────────────────────────────────── */
    function agregarProducto() {
        var formData = new FormData(form);

        fetch('<?php echo base_url(); ?>producto/addNewProducto', {
            method: 'POST',
            body: formData
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                currentProduct = data.producto;
                codigoDisplay.textContent = data.producto.codigo;
                showStatus(data.message, 'success');
                actionsContainer.style.display = 'flex';
                renderPreview().then(function() {
                    document.getElementById('modalProductoNombre').textContent = data.producto.nombre_producto;
                    document.getElementById('modalProductoCodigo').textContent  = data.producto.codigo;
                    $('#modalProductoAgregado').modal('show');
                });
            } else {
                showStatus(data.message || 'Error al agregar', 'error');
            }
        })
        .catch(function(e) { showStatus('Error: ' + e.message, 'error'); });
    }

    function limpiarFormulario() {
        form.reset();
        currentProduct = null;
        codigoDisplay.textContent = 'Presiona Agregar para generar';
        previewStage.innerHTML = '<div class="empty-state">Sin producto</div>';
        actionsContainer.style.display = 'none';
        statusMsg.style.display = 'none';
        document.getElementById('nombre_producto').focus();
    }

    /* ── Vista previa ─────────────────────────────────────────────────────── */
    function renderPreview() {
        if (!currentProduct) {
            previewStage.innerHTML = '<div class="empty-state">Sin producto</div>';
            return Promise.resolve();
        }

        previewStage.innerHTML = '';
        var scale    = getPreviewScale();
        var wrap     = document.createElement('div');
        wrap.style.cssText = 'background:#fff;border:1px solid #d5dee5;border-radius:4px;display:flex;align-items:center;justify-content:center;overflow:hidden;' +
                             'width:' + (currentSettings.width * scale) + 'mm;height:' + (currentSettings.height * scale) + 'mm;';

        var host = document.createElement('div');
        host.style.cssText = 'width:' + currentSettings.width + 'mm;height:' + currentSettings.height + 'mm;' +
                             'transform:scale(' + scale + ');transform-origin:center center;';
        host.appendChild(buildLabelNode(currentProduct));
        wrap.appendChild(host);
        previewStage.appendChild(wrap);
        return renderBarcodes(previewStage);
    }

    function getPreviewScale() {
        return Math.max(1.5, Math.min(4,
            Math.min(60 / currentSettings.width, 30 / currentSettings.height)
        ));
    }

    function buildLabelNode(product) {
        var s = currentSettings;

        // Estilos 100% inline para evitar conflictos con CSS de AdminLTE/Bootstrap
        var label = document.createElement('div');
        label.style.cssText =
            'box-sizing:border-box;' +
            'width:'  + s.width   + 'mm;' +
            'height:' + s.height  + 'mm;' +
            'padding:' + s.padding + 'mm;' +
            'display:flex;flex-direction:column;' +
            'justify-content:center;align-items:stretch;' +
            'gap:0.5mm;background:#fff;overflow:hidden;border:1px solid #bbb;';

        if (s.showName) {
            var name = document.createElement('div');
            name.style.cssText =
                'font-size:'   + s.fontName + 'mm;' +
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
            price.textContent = symbolCurrency + ' ' + parseFloat(product.precio_venta).toFixed(2);
            label.appendChild(price);
        }

        return label;
    }

    function renderBarcodes(container) {
        return new Promise(function(resolve) {
            var svgs = Array.prototype.slice.call(container.querySelectorAll('.js-label-barcode'));
            if (!svgs.length) { resolve(); return; }

            var DPM       = 8.0267;
            var innerW_mm = currentSettings.width - 2 * currentSettings.padding;
            var innerW_dot = Math.round(innerW_mm * DPM);
            var barH_mm   = currentSettings.barcodeHeight;

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
                        height:       Math.max(18, Math.round(barH_mm * 3.78)),
                        margin:       0,
                        displayValue: false
                    });
                } catch(e) { console.error('Barcode error:', e); return; }

                svg.removeAttribute('height');
                svg.style.height   = barH_mm + 'mm';
                svg.style.width    = 'auto';
                svg.style.maxWidth = innerW_mm + 'mm';
                svg.style.display  = 'block';
                svg.style.margin   = '0 auto';
            });

            setTimeout(resolve, 100);
        });
    }

    /* ── Impresión ZPL directa ───────────────────────────────────────────── */
    function imprimirZPL() {
        if (!currentProduct) return;

        var btn = document.getElementById('btnImprimir');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Enviando...';

        var zpl = buildLabelZPL(currentProduct, currentSettings);

        zebraGetPrinter(ZEBRA_LABEL_PRINTER)
        .then(function(device) {
            if (!device) { resetBtn(btn); return; }
            return zebraSend(device, zpl).then(function(ok) {
                if (ok) {
                    btn.innerHTML = '<i class="fa fa-check"></i> Enviada';
                    setTimeout(function() { resetBtn(btn); limpiarFormulario(); }, 2000);
                } else {
                    zebraLog('Error al imprimir etiqueta.', 'error');
                    resetBtn(btn);
                }
            });
        })
        .catch(function(err) {
            zebraLog('No se pudo conectar a Zebra Browser Print.', 'error');
            console.error('[Zebra quick-add]', err);
            resetBtn(btn);
        });
    }

    function resetBtn(btn) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-print"></i> Imprimir etiqueta';
    }

    /* ── Generador ZPL (igual lógica que etiqueta.php) ───────────────────── */
    function buildLabelZPL(product, s) {
        var DPM = 8.0267;
        var GAP = 4;

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

        /* totalMod incluye quiet zones → moduleW nunca desborda innerW */
        var totalMod = isEan13 ? 113 : (11 * code.length + 35);
        var moduleW  = Math.max(1, Math.floor(innerW / totalMod));

        /* barX = inicio de BARRAS de datos (quiet zones van a la izquierda) */
        var barX;
        if (isEan13) {
            var symLeft = pad + Math.round((innerW - totalMod * moduleW) / 2);
            barX = symLeft + 11 * moduleW;
        } else {
            barX = pad + Math.round((innerW - totalMod * moduleW) / 2);
        }
        barX = Math.max(pad, barX);

        /* barHeff: barras guía EAN-13 se extienden ~5 dots debajo de barH */
        var EAN_GUARD = isEan13 ? 13 : 0;
        var barHeff   = barH + EAN_GUARD;

        /* Centrado vertical */
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
        var zpl = ['^XA', '^CI28', ZEBRA_LABEL_MEDIA_TYPE, '^PW' + W, '^LL' + H, '^LH0,0'];

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
        y += barHeff + GAP;   // avanza sobre barH + barras guía EAN-13 + gap

        if (s.showCodeText) {
            zpl.push('^FO' + pad + ',' + y + '^FB' + innerW + ',1,0,C,0^A0N,' + codeH + ',' + codeH + '^FD' + code + '^FS');
            y += codeH + GAP;
        }

        if (s.showPrice) {
            var priceStr = symbolCurrency + ' ' + Number(product.precio_venta || 0).toFixed(2);
            zpl.push('^FO' + pad + ',' + y + '^FB' + innerW + ',1,0,C,0^A0N,' + priceH + ',' + priceH + '^FD' + priceStr + '^FS');
        }

        zpl.push('^XZ');
        return zpl.join('\n');
    }

    /* ── Carga de plantilla activa (localStorage compartido) ─────────────── */
    function loadTemplate() {
        var templates      = [];
        var activeId       = null;

        try {
            templates = JSON.parse(window.localStorage.getItem(labelTemplatesKey) || '[]');
            activeId  = window.localStorage.getItem(activeTemplateKey);
        } catch(e) {}

        /* Migrar valores viejos en px → mm */
        templates.forEach(function(t) {
            if (t.settings && t.settings.fontName > 10) {
                var px2mm = 25.4 / 96;
                t.settings.fontName  = Math.round(t.settings.fontName  * px2mm * 10) / 10;
                t.settings.fontPrice = Math.round(t.settings.fontPrice * px2mm * 10) / 10;
                t.settings.fontCode  = Math.round(t.settings.fontCode  * px2mm * 10) / 10;
            }
        });

        if (templates.length && activeId) {
            var tpl = null;
            for (var i = 0; i < templates.length; i++) {
                if (templates[i].id === activeId) { tpl = templates[i]; break; }
            }
            if (tpl && tpl.settings) {
                currentSettings = Object.assign({}, defaultSettings, tpl.settings);
            }
        }
    }

    /* ── Utilidades ──────────────────────────────────────────────────────── */
    function showStatus(msg, type) {
        statusMsg.textContent = msg;
        statusMsg.className = 'status-message ' + type;
        statusMsg.style.display = 'block';
        if (type === 'success') setTimeout(function() { statusMsg.style.display = 'none'; }, 5000);
    }
})();
</script>
