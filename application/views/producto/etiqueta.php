<?php
$simbolo_moneda = $configuracionInfo->simbolo_moneda;
?>

<style>
.etiquetas-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.75fr) minmax(320px, 0.95fr);
    gap: 20px;
}

.etiquetas-toolbar {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 18px;
}

.etiquetas-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 18px;
}

.productos-scroll,
.cola-scroll {
    max-height: 520px;
    overflow: auto;
}

.productos-table td,
.productos-table th,
.cola-table td,
.cola-table th {
    vertical-align: middle !important;
}

.cola-empty,
.preview-empty {
    border: 1px dashed #c7d2da;
    border-radius: 10px;
    padding: 18px;
    background: #f8fbfd;
    color: #607d8b;
    text-align: center;
}

.preview-shell {
    background: #eef3f7;
    border-radius: 14px;
    padding: 18px;
    min-height: 260px;
}

.preview-stage {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: flex-start;
}

.label-preview {
    background: #fff;
    border: 1px solid #d5dee5;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

.label-preview-scale {
    display: flex;
    align-items: center;
    justify-content: center;
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
    overflow: hidden;
    border: 0;
}

.label-name {
    font-size: var(--label-font-name-px, 7px);
    line-height: 1.1;
    font-weight: 700;
    text-align: center;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.label-barcode {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: var(--label-barcode-height-mm, 6.5mm);
    width: 100%;
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
    width: 100%;
    white-space: nowrap;
}

.label-code {
    font-size: var(--label-font-code-px, 6px);
    line-height: 1;
    text-align: center;
    color: #455a64;
    width: 100%;
    white-space: nowrap;
}

.config-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.config-grid .full {
    grid-column: 1 / -1;
}

.config-helper {
    margin-top: 12px;
    font-size: 12px;
    color: #607d8b;
}

.badge-soft {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 999px;
    background: #eef6ff;
    color: #2367a5;
    font-size: 12px;
    font-weight: 600;
}

#print-root {
    display: none;
}

@media (max-width: 1199px) {
    .etiquetas-layout {
        grid-template-columns: 1fr;
    }

    .etiquetas-toolbar {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 767px) {
    .etiquetas-toolbar {
        grid-template-columns: 1fr;
    }

    .config-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-tags" aria-hidden="true"></i> Impresión de etiquetas
            <small>Filtro, selección y configuración en una sola pantalla</small>
        </h1>
    </section>

    <section class="content">
        <div class="etiquetas-layout">
            <div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Productos disponibles</h3>
                        <span class="pull-right badge-soft"><?php echo count($productos); ?> productos</span>
                    </div>
                    <div class="box-body">
                        <div class="etiquetas-toolbar">
                            <div class="form-group">
                                <label for="filtro_busqueda">Buscar</label>
                                <input type="text" id="filtro_busqueda" class="form-control" placeholder="Nombre o código" value="<?php echo htmlspecialchars($searchText, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="filtro_categoria">Categoría</label>
                                <select id="filtro_categoria" class="form-control">
                                    <option value="">Todas las categorías</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?php echo (int)$categoria->id_categoria; ?>"><?php echo htmlspecialchars($categoria->nombre_categoria, ENT_QUOTES, 'UTF-8'); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="filtro_stock">Disponibilidad</label>
                                <select id="filtro_stock" class="form-control">
                                    <option value="all">Todos</option>
                                    <option value="with_stock">Solo con stock</option>
                                    <option value="without_stock">Solo sin stock</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="filtro_scan">Escaneo rápido</label>
                                <input type="text" id="filtro_scan" class="form-control" placeholder="Escanea un EAN-13">
                            </div>
                        </div>

                        <div class="etiquetas-actions">
                            <button type="button" class="btn btn-default" id="btn-seleccionar-visibles">
                                <i class="fa fa-check-square-o"></i> Agregar visibles
                            </button>
                            <button type="button" class="btn btn-default" id="btn-limpiar-filtros">
                                <i class="fa fa-eraser"></i> Limpiar filtros
                            </button>
                        </div>

                        <div class="productos-scroll">
                            <table class="table table-bordered table-hover productos-table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Código</th>
                                        <th>Categoría</th>
                                        <th>Stock</th>
                                        <th>Precio</th>
                                        <th style="width: 130px;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla_productos">
                                    <?php foreach ($productos as $producto): ?>
                                        <tr
                                            data-id="<?php echo (int)$producto['id_producto']; ?>"
                                            data-nombre="<?php echo htmlspecialchars(mb_strtolower($producto['nombre_producto'], 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>"
                                            data-codigo="<?php echo htmlspecialchars($producto['codigo'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-categoria="<?php echo (int)$producto['categoria']; ?>"
                                            data-stock="<?php echo (int)$producto['stock']; ?>"
                                            data-precio="<?php echo htmlspecialchars((string)$producto['precio_venta'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-nombre-categoria="<?php echo htmlspecialchars($producto['nombre_categoria'], ENT_QUOTES, 'UTF-8'); ?>"
                                        >
                                            <td><?php echo htmlspecialchars($producto['nombre_producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><code><?php echo htmlspecialchars($producto['codigo'], ENT_QUOTES, 'UTF-8'); ?></code></td>
                                            <td><?php echo htmlspecialchars($producto['nombre_categoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo (int)$producto['stock']; ?></td>
                                            <td><?php echo $simbolo_moneda . ' ' . number_format((float)$producto['precio_venta'], 2); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary btn-sm js-agregar-etiqueta">
                                                        <i class="fa fa-plus"></i> Agregar
                                                    </button>
                                                    <button type="button" class="btn btn-default btn-sm js-agregar-cinco">
                                                        +5
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Vista previa</h3>
                    </div>
                    <div class="box-body">
                        <div class="preview-shell">
                            <div id="preview_stage" class="preview-stage">
                                <div class="preview-empty">Selecciona un producto para ver cómo quedará la etiqueta.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cola de impresión</h3>
                    </div>
                    <div class="box-body">
                        <div class="etiquetas-actions">
                            <button type="button" class="btn btn-success" id="btn-imprimir">
                                <i class="fa fa-print"></i> Imprimir etiquetas
                            </button>
                            <button type="button" class="btn btn-default" id="btn-vaciar-cola">
                                <i class="fa fa-trash"></i> Vaciar cola
                            </button>
                        </div>

                        <div class="cola-scroll">
                            <table class="table table-bordered cola-table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th style="width: 85px;">Cantidad</th>
                                        <th style="width: 105px;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla_cola">
                                    <tr id="cola_vacia">
                                        <td colspan="3">
                                            <div class="cola-empty">Todavía no hay etiquetas en la cola.</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="well well-sm" style="margin-top: 15px;">
                            <strong>Total de etiquetas:</strong> <span id="total_etiquetas">0</span>
                        </div>
                    </div>
                </div>

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Configuración de etiqueta</h3>
                    </div>
                    <div class="box-body">
                        <div class="config-grid">
                            <div class="form-group full">
                                <label for="template_selector">Plantilla</label>
                                <select id="template_selector" class="form-control"></select>
                            </div>
                            <div class="form-group full">
                                <label for="template_name">Nombre de plantilla</label>
                                <input type="text" id="template_name" class="form-control" placeholder="Ej. Zebra 39x16">
                            </div>
                            <div class="form-group">
                                <label for="cfg_width">Ancho (mm)</label>
                                <input type="number" min="20" max="120" step="0.1" id="cfg_width" class="form-control" value="39">
                            </div>
                            <div class="form-group">
                                <label for="cfg_height">Alto (mm)</label>
                                <input type="number" min="10" max="80" step="0.1" id="cfg_height" class="form-control" value="16">
                            </div>
                            <div class="form-group">
                                <label for="cfg_padding">Margen interno (mm)</label>
                                <input type="number" min="0" max="8" step="0.1" id="cfg_padding" class="form-control" value="1">
                            </div>
                            <div class="form-group">
                                <label for="cfg_barcode_height">Alto del código (mm)</label>
                                <input type="number" min="4" max="20" step="0.1" id="cfg_barcode_height" class="form-control" value="6.5">
                            </div>
                            <div class="form-group">
                                <label for="cfg_font_name">Tamaño nombre (px)</label>
                                <input type="number" min="5" max="16" step="0.5" id="cfg_font_name" class="form-control" value="7">
                            </div>
                            <div class="form-group">
                                <label for="cfg_font_price">Tamaño precio (px)</label>
                                <input type="number" min="6" max="20" step="0.5" id="cfg_font_price" class="form-control" value="9">
                            </div>
                            <div class="form-group">
                                <label for="cfg_font_code">Tamaño código (px)</label>
                                <input type="number" min="5" max="14" step="0.5" id="cfg_font_code" class="form-control" value="6">
                            </div>
                            <div class="form-group full">
                                <div class="checkbox" style="margin-top: 0;">
                                    <label><input type="checkbox" id="cfg_show_name" checked> Mostrar nombre</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" id="cfg_show_price" checked> Mostrar precio</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" id="cfg_show_code_text" checked> Mostrar texto del código</label>
                                </div>
                            </div>
                        </div>

                        <div class="etiquetas-actions" style="margin-top: 12px;">
                            <button type="button" class="btn btn-primary" id="btn-save-template">
                                <i class="fa fa-plus"></i> Guardar como nueva
                            </button>
                            <button type="button" class="btn btn-default" id="btn-update-template">
                                <i class="fa fa-save"></i> Actualizar actual
                            </button>
                            <button type="button" class="btn btn-danger" id="btn-delete-template">
                                <i class="fa fa-trash"></i> Eliminar
                            </button>
                            <button type="button" class="btn btn-default" id="btn-reset-config">
                                <i class="fa fa-undo"></i> Restaurar valores
                            </button>
                        </div>

                        <p class="config-helper">
                            Ajusta solo lo importante. La previsualización se actualiza al instante y puedes guardar varias plantillas en este equipo.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="print-root"></div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3/dist/JsBarcode.all.min.js"></script>
<script>
(function() {
    var symbolCurrency = <?php echo json_encode($simbolo_moneda); ?>;
    var labelTemplatesKey = 'pos_multisucursal_label_templates_v1';
    var activeTemplateKey = 'pos_multisucursal_label_active_template_v1';
    var defaultSettings = {
        width: 39,
        height: 16,
        padding: 1,
        barcodeHeight: 6.5,
        fontName: 7,
        fontPrice: 9,
        fontCode: 6,
        showName: true,
        showPrice: true,
        showCodeText: true
    };
    var templates = [];
    var activeTemplateId = null;
    var queue = {};
    var currentSettings = Object.assign({}, defaultSettings);

    var products = <?php echo json_encode($productos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
    var productsById = {};
    products.forEach(function(product) {
        productsById[String(product.id_producto)] = product;
    });

    var filters = {
        search: document.getElementById('filtro_busqueda'),
        category: document.getElementById('filtro_categoria'),
        stock: document.getElementById('filtro_stock'),
        scan: document.getElementById('filtro_scan')
    };

    var rows = Array.prototype.slice.call(document.querySelectorAll('#tabla_productos tr'));
    var queueBody = document.getElementById('tabla_cola');
    var emptyQueueRow = document.getElementById('cola_vacia');
    var totalLabelsNode = document.getElementById('total_etiquetas');
    var previewStage = document.getElementById('preview_stage');
    var printRoot = document.getElementById('print-root');
    var templateSelector = document.getElementById('template_selector');
    var templateNameInput = document.getElementById('template_name');

    var settingInputs = {
        width: document.getElementById('cfg_width'),
        height: document.getElementById('cfg_height'),
        padding: document.getElementById('cfg_padding'),
        barcodeHeight: document.getElementById('cfg_barcode_height'),
        fontName: document.getElementById('cfg_font_name'),
        fontPrice: document.getElementById('cfg_font_price'),
        fontCode: document.getElementById('cfg_font_code'),
        showName: document.getElementById('cfg_show_name'),
        showPrice: document.getElementById('cfg_show_price'),
        showCodeText: document.getElementById('cfg_show_code_text')
    };

    loadSettings();
    bindEvents();
    applyFilters();
    renderQueue();
    renderPreview();

    function bindEvents() {
        rows.forEach(function(row) {
            var addButton = row.querySelector('.js-agregar-etiqueta');
            var addFiveButton = row.querySelector('.js-agregar-cinco');

            addButton.addEventListener('click', function() {
                addToQueue(row.dataset.id, 1);
            });

            addFiveButton.addEventListener('click', function() {
                addToQueue(row.dataset.id, 5);
            });
        });

        filters.search.addEventListener('input', applyFilters);
        filters.category.addEventListener('change', applyFilters);
        filters.stock.addEventListener('change', applyFilters);

        filters.scan.addEventListener('keydown', function(event) {
            if (event.key !== 'Enter') {
                return;
            }

            event.preventDefault();
            var code = filters.scan.value.trim();
            if (!code) {
                return;
            }

            var match = rows.find(function(row) {
                return row.dataset.codigo === code;
            });

            if (match) {
                addToQueue(match.dataset.id, 1);
                filters.scan.value = '';
            } else {
                alert('No se encontró un producto con ese código.');
            }
        });

        document.getElementById('btn-seleccionar-visibles').addEventListener('click', function() {
            rows.forEach(function(row) {
                if (row.style.display !== 'none') {
                    addToQueue(row.dataset.id, 1);
                }
            });
        });

        document.getElementById('btn-limpiar-filtros').addEventListener('click', function() {
            filters.search.value = '';
            filters.category.value = '';
            filters.stock.value = 'all';
            filters.scan.value = '';
            applyFilters();
        });

        document.getElementById('btn-vaciar-cola').addEventListener('click', function() {
            queue = {};
            renderQueue();
            renderPreview();
        });

        document.getElementById('btn-imprimir').addEventListener('click', function() {
            if (!Object.keys(queue).length) {
                alert('Primero agrega al menos una etiqueta a la cola de impresión.');
                return;
            }

            renderPrintSheet();
            window.print();
        });

        templateSelector.addEventListener('change', function() {
            selectTemplate(this.value);
        });

        document.getElementById('btn-save-template').addEventListener('click', function() {
            saveTemplate(false);
        });

        document.getElementById('btn-update-template').addEventListener('click', function() {
            saveTemplate(true);
        });

        document.getElementById('btn-delete-template').addEventListener('click', function() {
            deleteTemplate();
        });

        document.getElementById('btn-reset-config').addEventListener('click', function() {
            currentSettings = Object.assign({}, defaultSettings);
            hydrateSettings();
            templateNameInput.value = '';
            renderPreview();
        });

        Object.keys(settingInputs).forEach(function(key) {
            settingInputs[key].addEventListener('input', function() {
                readSettings();
                renderPreview();
            });
            settingInputs[key].addEventListener('change', function() {
                readSettings();
                renderPreview();
            });
        });
    }

    function loadSettings() {
        try {
            templates = JSON.parse(window.localStorage.getItem(labelTemplatesKey) || '[]');
            activeTemplateId = window.localStorage.getItem(activeTemplateKey);
        } catch (error) {
            templates = [];
            activeTemplateId = null;
        }

        if (!Array.isArray(templates) || !templates.length) {
            templates = [{
                id: 'default_39x16',
                name: 'Zebra 39x16',
                settings: Object.assign({}, defaultSettings)
            }];
            activeTemplateId = templates[0].id;
            persistTemplates();
        }

        if (!templates.some(function(template) { return template.id === activeTemplateId; })) {
            activeTemplateId = templates[0].id;
        }

        populateTemplateSelector();
        selectTemplate(activeTemplateId);
        hydrateSettings();
    }

    function hydrateSettings() {
        settingInputs.width.value = currentSettings.width;
        settingInputs.height.value = currentSettings.height;
        settingInputs.padding.value = currentSettings.padding;
        settingInputs.barcodeHeight.value = currentSettings.barcodeHeight;
        settingInputs.fontName.value = currentSettings.fontName;
        settingInputs.fontPrice.value = currentSettings.fontPrice;
        settingInputs.fontCode.value = currentSettings.fontCode;
        settingInputs.showName.checked = !!currentSettings.showName;
        settingInputs.showPrice.checked = !!currentSettings.showPrice;
        settingInputs.showCodeText.checked = !!currentSettings.showCodeText;
    }

    function readSettings() {
        currentSettings.width = parseFloat(settingInputs.width.value) || defaultSettings.width;
        currentSettings.height = parseFloat(settingInputs.height.value) || defaultSettings.height;
        currentSettings.padding = parseFloat(settingInputs.padding.value) || 0;
        currentSettings.barcodeHeight = parseFloat(settingInputs.barcodeHeight.value) || defaultSettings.barcodeHeight;
        currentSettings.fontName = parseFloat(settingInputs.fontName.value) || defaultSettings.fontName;
        currentSettings.fontPrice = parseFloat(settingInputs.fontPrice.value) || defaultSettings.fontPrice;
        currentSettings.fontCode = parseFloat(settingInputs.fontCode.value) || defaultSettings.fontCode;
        currentSettings.showName = settingInputs.showName.checked;
        currentSettings.showPrice = settingInputs.showPrice.checked;
        currentSettings.showCodeText = settingInputs.showCodeText.checked;
    }

    function applyFilters() {
        var search = normalize(filters.search.value);
        var category = filters.category.value;
        var stockMode = filters.stock.value;

        rows.forEach(function(row) {
            var name = normalize(row.dataset.nombre);
            var code = normalize(row.dataset.codigo);
            var matchesSearch = !search || name.indexOf(search) !== -1 || code.indexOf(search) !== -1;
            var matchesCategory = !category || row.dataset.categoria === category;
            var stockValue = parseInt(row.dataset.stock, 10) || 0;
            var matchesStock = true;

            if (stockMode === 'with_stock') {
                matchesStock = stockValue > 0;
            } else if (stockMode === 'without_stock') {
                matchesStock = stockValue <= 0;
            }

            row.style.display = (matchesSearch && matchesCategory && matchesStock) ? '' : 'none';
        });
    }

    function addToQueue(productId, quantity) {
        var key = String(productId);
        if (!productsById[key]) {
            return;
        }

        if (!queue[key]) {
            queue[key] = {
                product: productsById[key],
                quantity: 0
            };
        }

        queue[key].quantity += quantity;
        renderQueue();
        renderPreview();
    }

    function renderQueue() {
        Array.prototype.slice.call(queueBody.querySelectorAll('tr[data-queue-row="1"]')).forEach(function(node) {
            node.parentNode.removeChild(node);
        });

        var totalLabels = 0;
        var keys = Object.keys(queue);

        emptyQueueRow.style.display = keys.length ? 'none' : '';

        keys.forEach(function(key) {
            var item = queue[key];
            totalLabels += item.quantity;

            var row = document.createElement('tr');
            row.setAttribute('data-queue-row', '1');
            row.innerHTML =
                '<td>' + escapeHtml(item.product.nombre_producto) + '<br><small><code>' + escapeHtml(item.product.codigo) + '</code></small></td>' +
                '<td><input type="number" min="1" step="1" class="form-control input-sm js-qty" value="' + item.quantity + '" data-product-id="' + key + '"></td>' +
                '<td>' +
                    '<button type="button" class="btn btn-default btn-sm js-minus" data-product-id="' + key + '"><i class="fa fa-minus"></i></button> ' +
                    '<button type="button" class="btn btn-danger btn-sm js-remove" data-product-id="' + key + '"><i class="fa fa-trash"></i></button>' +
                '</td>';

            queueBody.appendChild(row);
        });

        totalLabelsNode.textContent = totalLabels;

        Array.prototype.slice.call(queueBody.querySelectorAll('.js-qty')).forEach(function(input) {
            input.addEventListener('change', function() {
                var key = this.dataset.productId;
                var nextQty = parseInt(this.value, 10) || 1;
                queue[key].quantity = Math.max(1, nextQty);
                renderQueue();
                renderPreview();
            });
        });

        Array.prototype.slice.call(queueBody.querySelectorAll('.js-minus')).forEach(function(button) {
            button.addEventListener('click', function() {
                var key = this.dataset.productId;
                if (!queue[key]) {
                    return;
                }

                queue[key].quantity -= 1;
                if (queue[key].quantity <= 0) {
                    delete queue[key];
                }

                renderQueue();
                renderPreview();
            });
        });

        Array.prototype.slice.call(queueBody.querySelectorAll('.js-remove')).forEach(function(button) {
            button.addEventListener('click', function() {
                delete queue[this.dataset.productId];
                renderQueue();
                renderPreview();
            });
        });
    }

    function renderPreview() {
        readSettings();
        previewStage.innerHTML = '';

        var firstKey = Object.keys(queue)[0];
        if (!firstKey) {
            previewStage.innerHTML = '<div class="preview-empty">Selecciona un producto para ver cómo quedará la etiqueta.</div>';
            return;
        }

        var item = queue[firstKey];
        var previewWrap = document.createElement('div');
        previewWrap.className = 'label-preview';
        var previewScale = getPreviewScale();
        previewWrap.style.width = (currentSettings.width * previewScale) + 'mm';
        previewWrap.style.height = (currentSettings.height * previewScale) + 'mm';
        previewWrap.style.padding = '4px';

        var label = buildLabelNode(item.product);
        var scaleHost = document.createElement('div');
        scaleHost.className = 'label-preview-scale';
        scaleHost.style.width = currentSettings.width + 'mm';
        scaleHost.style.height = currentSettings.height + 'mm';
        scaleHost.style.transform = 'scale(' + previewScale + ')';
        scaleHost.style.transformOrigin = 'center center';

        scaleHost.appendChild(label);
        previewWrap.appendChild(scaleHost);
        previewStage.appendChild(previewWrap);
        renderBarcodes(previewStage);
        injectPrintStyles();
    }

    function renderPrintSheet() {
        readSettings();
        injectPrintStyles();
        printRoot.innerHTML = '';

        var fragment = document.createDocumentFragment();

        Object.keys(queue).forEach(function(key) {
            var item = queue[key];
            for (var i = 0; i < item.quantity; i++) {
                fragment.appendChild(buildLabelNode(item.product, true));
            }
        });

        printRoot.appendChild(fragment);
        renderBarcodes(printRoot);
    }

    function buildLabelNode(product, printVersion) {
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
            price.textContent = symbolCurrency + ' ' + formatPrice(product.precio_venta);
            label.appendChild(price);
        }

        return label;
    }

    function renderBarcodes(container) {
        Array.prototype.slice.call(container.querySelectorAll('.js-label-barcode')).forEach(function(svg) {
            var code = svg.getAttribute('data-code') || '';
            var isEan13 = /^\d{13}$/.test(code);

            JsBarcode(svg, code, {
                format: isEan13 ? 'EAN13' : 'CODE128',
                width: isEan13 ? 1 : 1.1,
                height: mmToPx(currentSettings.barcodeHeight),
                margin: 0,
                displayValue: false
            });
        });
    }

    function injectPrintStyles() {
        var styleId = 'dynamic-label-print-style';
        var styleNode = document.getElementById(styleId);
        if (!styleNode) {
            styleNode = document.createElement('style');
            styleNode.id = styleId;
            document.head.appendChild(styleNode);
        }

        styleNode.textContent =
            '@media print {' +
                '@page { size: ' + currentSettings.width + 'mm ' + currentSettings.height + 'mm; margin: 0; }' +
                'body * { visibility: hidden !important; }' +
                '#print-root, #print-root * { visibility: visible !important; }' +
                '#print-root { display: block !important; position: absolute; left: 0; top: 0; width: ' + currentSettings.width + 'mm; }' +
                '.print-label { page-break-after: always; break-after: page; margin: 0 !important; }' +
            '}';
    }

    function getPreviewScale() {
        var maxWidthMm = 72;
        var maxHeightMm = 36;
        var widthScale = maxWidthMm / currentSettings.width;
        var heightScale = maxHeightMm / currentSettings.height;
        return Math.max(2.2, Math.min(5.2, Math.min(widthScale, heightScale)));
    }

    function mmToPx(mm) {
        return Math.max(18, Math.round(mm * 3.78));
    }

    function populateTemplateSelector() {
        templateSelector.innerHTML = '';
        templates.forEach(function(template) {
            var option = document.createElement('option');
            option.value = template.id;
            option.textContent = template.name;
            templateSelector.appendChild(option);
        });
        templateSelector.value = activeTemplateId;
    }

    function selectTemplate(templateId) {
        var selected = templates.find(function(template) {
            return template.id === templateId;
        });

        if (!selected) {
            return;
        }

        activeTemplateId = selected.id;
        currentSettings = Object.assign({}, defaultSettings, selected.settings || {});
        templateNameInput.value = selected.name;
        templateSelector.value = selected.id;
        window.localStorage.setItem(activeTemplateKey, activeTemplateId);
        hydrateSettings();
        renderPreview();
    }

    function saveTemplate(updateCurrent) {
        readSettings();
        var templateName = templateNameInput.value.trim();

        if (!templateName) {
            alert('Escribe un nombre para la plantilla.');
            templateNameInput.focus();
            return;
        }

        if (updateCurrent && activeTemplateId) {
            templates = templates.map(function(template) {
                if (template.id !== activeTemplateId) {
                    return template;
                }

                return {
                    id: template.id,
                    name: templateName,
                    settings: Object.assign({}, currentSettings)
                };
            });
        } else {
            var newId = 'tpl_' + Date.now();
            templates.push({
                id: newId,
                name: templateName,
                settings: Object.assign({}, currentSettings)
            });
            activeTemplateId = newId;
        }

        persistTemplates();
        populateTemplateSelector();
        templateSelector.value = activeTemplateId;
    }

    function deleteTemplate() {
        if (templates.length === 1) {
            alert('Debe existir al menos una plantilla.');
            return;
        }

        if (!activeTemplateId) {
            return;
        }

        templates = templates.filter(function(template) {
            return template.id !== activeTemplateId;
        });

        activeTemplateId = templates[0].id;
        persistTemplates();
        populateTemplateSelector();
        selectTemplate(activeTemplateId);
    }

    function persistTemplates() {
        window.localStorage.setItem(labelTemplatesKey, JSON.stringify(templates));
        window.localStorage.setItem(activeTemplateKey, activeTemplateId);
    }

    function formatPrice(value) {
        return Number(value || 0).toFixed(2);
    }

    function normalize(text) {
        return String(text || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    function escapeHtml(text) {
        return String(text || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
})();
</script>
