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
    /* Centro vertical (igual que ZPL calcula el Y de inicio) */
    justify-content: center;
    align-items: stretch;
    /* Gap fijo entre elementos — idéntico al gapDots en ZPL (0.5mm) */
    gap: var(--label-gap-mm, 0.5mm);
    background: #fff;
    overflow: hidden;
    border: 0;
}

.label-name {
    font-size: var(--label-font-name-mm, 1.8mm);
    line-height: 1;
    font-weight: 700;
    text-align: center;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: clip;  /* recorta sin ellipsis, igual que ZPL */
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

/* SVG: dimensiones exactas vía JS después de renderizar */
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
}

.label-code {
    font-size: var(--label-font-code-mm, 1.5mm);
    line-height: 1;
    text-align: center;
    color: #455a64;
    width: 100%;
    white-space: nowrap;
}

/* ── Panel de configuración ── */
.cfg-template-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 14px;
    padding-bottom: 14px;
    border-bottom: 1px solid #e8ecef;
}

.cfg-section {
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f3f5;
}

.cfg-section-title {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #90a4ae;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.cfg-sliders-grid {
    display: flex;
    flex-direction: column;
    gap: 9px;
}

.cfg-slider-row {
    display: grid;
    grid-template-columns: 58px 1fr 34px 20px;
    align-items: center;
    gap: 8px;
}

.cfg-slider-row label {
    font-size: 12px;
    font-weight: 600;
    color: #455a64;
    margin: 0;
    white-space: nowrap;
}

.cfg-slider-row input[type="range"] {
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    height: 4px;
    border-radius: 2px;
    background: #cfd8dc;
    outline: none;
    cursor: pointer;
    accent-color: #3c8dbc;
}

.cfg-slider-row input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #3c8dbc;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0,0,0,.25);
    transition: transform .15s;
}

.cfg-slider-row input[type="range"]::-webkit-slider-thumb:hover {
    transform: scale(1.2);
}

.cfg-slider-row input[type="range"]::-moz-range-thumb {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #3c8dbc;
    cursor: pointer;
    border: none;
    box-shadow: 0 1px 3px rgba(0,0,0,.25);
}

.cfg-val {
    font-size: 11px;
    font-weight: 700;
    color: #3c8dbc;
    text-align: right;
    min-width: 28px;
}

.cfg-unit {
    font-size: 10px;
    color: #b0bec5;
    text-align: left;
}

/* Toggle switches */
.cfg-toggles {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
}

.cfg-toggle {
    display: flex;
    align-items: center;
    gap: 7px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 500;
    color: #37474f;
    margin: 0;
    user-select: none;
}

.cfg-toggle input[type="checkbox"] {
    display: none;
}

.cfg-toggle-track {
    position: relative;
    width: 32px;
    height: 17px;
    background: #cfd8dc;
    border-radius: 17px;
    transition: background .2s;
    flex-shrink: 0;
}

.cfg-toggle-track::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 13px;
    height: 13px;
    background: #fff;
    border-radius: 50%;
    transition: transform .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}

.cfg-toggle input:checked ~ .cfg-toggle-track {
    background: #3c8dbc;
}

.cfg-toggle input:checked ~ .cfg-toggle-track::after {
    transform: translateX(15px);
}

.cfg-actions {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid #e8ecef;
}

.config-helper {
    margin-top: 10px;
    font-size: 11px;
    color: #90a4ae;
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
                        <h3 class="box-title"><i class="fa fa-sliders"></i> Configuración de etiqueta</h3>
                    </div>
                    <div class="box-body" style="padding:14px 15px;">

                        <!-- Plantilla -->
                        <div class="cfg-template-row">
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#90a4ae;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;display:block;">Plantilla</label>
                                <select id="template_selector" class="form-control input-sm"></select>
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#90a4ae;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;display:block;">Nombre</label>
                                <input type="text" id="template_name" class="form-control input-sm" placeholder="Ej. Zebra 39×16">
                            </div>
                        </div>

                        <!-- Dimensiones -->
                        <div class="cfg-section">
                            <div class="cfg-section-title"><i class="fa fa-expand"></i> Dimensiones</div>
                            <div class="cfg-sliders-grid">
                                <div class="cfg-slider-row">
                                    <label for="cfg_width">Ancho</label>
                                    <input type="range" min="20" max="120" step="0.5" id="cfg_width" value="39">
                                    <span class="cfg-val" id="val_width">39</span>
                                    <span class="cfg-unit">mm</span>
                                </div>
                                <div class="cfg-slider-row">
                                    <label for="cfg_height">Alto</label>
                                    <input type="range" min="10" max="80" step="0.5" id="cfg_height" value="16">
                                    <span class="cfg-val" id="val_height">16</span>
                                    <span class="cfg-unit">mm</span>
                                </div>
                                <div class="cfg-slider-row">
                                    <label for="cfg_padding">Margen</label>
                                    <input type="range" min="0" max="5" step="0.1" id="cfg_padding" value="1">
                                    <span class="cfg-val" id="val_padding">1.0</span>
                                    <span class="cfg-unit">mm</span>
                                </div>
                            </div>
                        </div>

                        <!-- Código de barras -->
                        <div class="cfg-section">
                            <div class="cfg-section-title"><i class="fa fa-barcode"></i> Código de barras</div>
                            <div class="cfg-sliders-grid">
                                <div class="cfg-slider-row">
                                    <label for="cfg_barcode_height">Alto</label>
                                    <input type="range" min="3" max="20" step="0.5" id="cfg_barcode_height" value="6.5">
                                    <span class="cfg-val" id="val_barcode_height">6.5</span>
                                    <span class="cfg-unit">mm</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tipografía -->
                        <div class="cfg-section">
                            <div class="cfg-section-title"><i class="fa fa-font"></i> Tipografía</div>
                            <div class="cfg-sliders-grid">
                                <div class="cfg-slider-row">
                                    <label for="cfg_font_name">Nombre</label>
                                    <input type="range" min="0.5" max="5" step="0.1" id="cfg_font_name" value="1.8">
                                    <span class="cfg-val" id="val_font_name">1.8</span>
                                    <span class="cfg-unit">mm</span>
                                </div>
                                <div class="cfg-slider-row">
                                    <label for="cfg_font_price">Precio</label>
                                    <input type="range" min="0.5" max="6" step="0.1" id="cfg_font_price" value="2.3">
                                    <span class="cfg-val" id="val_font_price">2.3</span>
                                    <span class="cfg-unit">mm</span>
                                </div>
                                <div class="cfg-slider-row">
                                    <label for="cfg_font_code">Código</label>
                                    <input type="range" min="0.5" max="4" step="0.1" id="cfg_font_code" value="1.5">
                                    <span class="cfg-val" id="val_font_code">1.5</span>
                                    <span class="cfg-unit">mm</span>
                                </div>
                            </div>
                        </div>

                        <!-- Visibilidad -->
                        <div class="cfg-section" style="border-bottom:0;margin-bottom:0;padding-bottom:0;">
                            <div class="cfg-section-title"><i class="fa fa-eye"></i> Mostrar campos</div>
                            <div class="cfg-toggles">
                                <label class="cfg-toggle">
                                    <input type="checkbox" id="cfg_show_name" checked>
                                    <span class="cfg-toggle-track"></span>
                                    <span>Nombre</span>
                                </label>
                                <label class="cfg-toggle">
                                    <input type="checkbox" id="cfg_show_price" checked>
                                    <span class="cfg-toggle-track"></span>
                                    <span>Precio</span>
                                </label>
                                <label class="cfg-toggle">
                                    <input type="checkbox" id="cfg_show_code_text" checked>
                                    <span class="cfg-toggle-track"></span>
                                    <span>Texto del código</span>
                                </label>
                            </div>
                        </div>

                        <!-- Acciones de plantilla -->
                        <div class="cfg-actions">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-save-template" title="Guardar como nueva plantilla">
                                <i class="fa fa-plus"></i> Nueva
                            </button>
                            <button type="button" class="btn btn-default btn-sm" id="btn-update-template" title="Actualizar plantilla seleccionada">
                                <i class="fa fa-save"></i> Actualizar
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" id="btn-delete-template" title="Eliminar plantilla seleccionada">
                                <i class="fa fa-trash"></i> Eliminar
                            </button>
                            <button type="button" class="btn btn-default btn-sm" id="btn-reset-config" title="Restaurar valores por defecto">
                                <i class="fa fa-undo"></i> Restaurar
                            </button>
                        </div>

                        <p class="config-helper">
                            Todos los valores en <strong>mm</strong> — misma unidad que la impresora ZPL. Lo que ves es lo que se imprime.
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
        fontName: 1.8,   // mm (203dpi: ×8.0267 dots; CSS: valor directo en mm)
        fontPrice: 2.3,  // mm
        fontCode: 1.5,   // mm
        showName: true,
        showPrice: true,
        showCodeText: true
    };

    /** Migra plantillas antiguas guardadas en px (fontName > 10) a mm */
    function migrateSettingsPxToMm(s) {
        if (s && s.fontName > 10) {
            var PX_TO_MM = 25.4 / 96;
            s.fontName  = Math.round(s.fontName  * PX_TO_MM * 10) / 10;
            s.fontPrice = Math.round(s.fontPrice * PX_TO_MM * 10) / 10;
            s.fontCode  = Math.round(s.fontCode  * PX_TO_MM * 10) / 10;
        }
        return s;
    }
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

    // Spans que muestran el valor numérico actual de cada slider
    var sliderDisplays = {
        width:         { el: document.getElementById('val_width'),         dec: 0 },
        height:        { el: document.getElementById('val_height'),        dec: 0 },
        padding:       { el: document.getElementById('val_padding'),       dec: 1 },
        barcodeHeight: { el: document.getElementById('val_barcode_height'),dec: 1 },
        fontName:      { el: document.getElementById('val_font_name'),     dec: 1 },
        fontPrice:     { el: document.getElementById('val_font_price'),    dec: 1 },
        fontCode:      { el: document.getElementById('val_font_code'),     dec: 1 },
    };

    function updateSliderDisplays() {
        Object.keys(sliderDisplays).forEach(function(key) {
            var d = sliderDisplays[key];
            if (d.el) d.el.textContent = parseFloat(currentSettings[key]).toFixed(d.dec);
        });
    }

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
            printZebraLabelQueue();
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
                updateSliderDisplays();
                renderPreview();
            });
            settingInputs[key].addEventListener('change', function() {
                readSettings();
                updateSliderDisplays();
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

        // Migrar valores en px → mm si vienen de versión anterior
        templates.forEach(function(t) { migrateSettingsPxToMm(t.settings); });

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
        updateSliderDisplays();
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
        injectPrintStyles();
        renderBarcodes(previewStage);
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
        return renderBarcodes(printRoot);
    }

    function buildLabelNode(product, printVersion) {
        var label = document.createElement('div');
        label.className = 'label-card' + (printVersion ? ' print-label' : '');
        label.style.setProperty('--label-width-mm', currentSettings.width + 'mm');
        label.style.setProperty('--label-height-mm', currentSettings.height + 'mm');
        label.style.setProperty('--label-padding-mm', currentSettings.padding + 'mm');
        label.style.setProperty('--label-barcode-height-mm', currentSettings.barcodeHeight + 'mm');
        label.style.setProperty('--label-font-name-mm', currentSettings.fontName + 'mm');
        label.style.setProperty('--label-font-price-mm', currentSettings.fontPrice + 'mm');
        label.style.setProperty('--label-font-code-mm', currentSettings.fontCode + 'mm');
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
        barcodeWrap.style.height = currentSettings.barcodeHeight + 'mm';
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
        return new Promise(function(resolve) {
            var svgs = Array.prototype.slice.call(container.querySelectorAll('.js-label-barcode'));

            if (svgs.length === 0) {
                requestAnimationFrame(resolve);
                return;
            }

            // moduleW idéntico al ZPL: usar totalMod (incluyendo quiet zones) para no desbordarse
            var DPM       = 8.0267;
            var innerW_mm = currentSettings.width - 2 * currentSettings.padding;
            var innerW_dot = Math.round(innerW_mm * DPM);
            var barH_mm   = currentSettings.barcodeHeight;

            svgs.forEach(function(svg) {
                var code     = svg.getAttribute('data-code') || '';
                var isEan13  = /^\d{13}$/.test(code);
                // EAN-13: 113 módulos totales (11 quiet-L + 95 barras + 7 quiet-R)
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
                    console.error('Barcode error for: ' + code, e);
                    return;
                }

                // Forzar dimensiones exactas en mm (quitar px de JsBarcode, usar CSS mm)
                svg.removeAttribute('height');
                svg.style.height    = barH_mm + 'mm';
                svg.style.width     = 'auto';
                svg.style.maxWidth  = innerW_mm + 'mm';
                svg.style.display   = 'block';
                svg.style.margin    = '0 auto';
            });

            requestAnimationFrame(function() {
                setTimeout(resolve, 150);
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
                '@page { size: ' + currentSettings.width + 'mm ' + currentSettings.height + 'mm; margin: 0; padding: 0; }' +
                'html, body { margin: 0 !important; padding: 0 !important; }' +
                'body * { visibility: hidden !important; }' +
                '#print-root, #print-root * { visibility: visible !important; }' +
                '#print-root { display: block !important; position: relative !important; width: 100% !important; height: auto !important; margin: 0 !important; padding: 0 !important; }' +
                '.print-label { width: ' + currentSettings.width + 'mm !important; height: ' + currentSettings.height + 'mm !important; page-break-after: always; break-after: page; margin: 0 !important; padding: 0 !important; page-break-inside: avoid; display: block !important; }' +
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

    /**
     * Genera ZPL para una etiqueta según los currentSettings del usuario.
     * Respeta: showName, showPrice, showCodeText, width, height, padding,
     * barcodeHeight, fontName, fontPrice, fontCode.
     */
    function buildLabelZPL(product, s) {
        var DPM  = 8.0267; // dots por mm @ 203 DPI
        var GAP  = 4;      // 0.5mm entre elementos (igual al CSS gap: 0.5mm)

        var W    = Math.round(s.width         * DPM);
        var H    = Math.round(s.height        * DPM);
        var pad  = Math.round(s.padding       * DPM);
        var barH = Math.round(s.barcodeHeight * DPM);
        var nameH  = Math.max(8,  Math.round(s.fontName  * DPM));
        var priceH = Math.max(8,  Math.round(s.fontPrice * DPM));
        var codeH  = Math.max(6,  Math.round(s.fontCode  * DPM));
        var innerW = W - 2 * pad;

        var code    = String(product.codigo || '');
        var isEan13 = /^\d{13}$/.test(code);

        // ── Módulo y posición horizontal ──────────────────────────────────────
        // Usamos el TOTAL de módulos (incluye quiet zones) para que el símbolo
        // nunca desborde innerW. Para EAN-13: 11+95+7=113; CODE128: estimación.
        var totalMod = isEan13 ? 113 : (11 * code.length + 35);
        var moduleW  = Math.max(1, Math.floor(innerW / totalMod));

        // barX = inicio de las BARRAS de datos (^FO en ZPL = start of bars)
        // EAN-13: zona quieta izquierda (11 módulos) está a la IZQUIERDA de barX
        //   → symbolLeft = pad + (innerW - 113*moduleW)/2
        //   → barX = symbolLeft + 11*moduleW
        // CODE128: sin zona quieta asimétrica, centrar directamente
        var barX;
        if (isEan13) {
            var symLeft = pad + Math.round((innerW - totalMod * moduleW) / 2);
            barX = symLeft + 11 * moduleW;
        } else {
            barX = pad + Math.round((innerW - totalMod * moduleW) / 2);
        }
        barX = Math.max(pad, barX);

        // ── Altura efectiva ───────────────────────────────────────────────────
        // EAN-13: las barras guía se extienden ~5 dots DEBAJO de barH declarado.
        // barHeff se usa para calcular y-siguiente (separación real), no para ^BEN.
        var EAN_GUARD = isEan13 ? 13 : 0;  // 5 barras guía + 8 dots (1mm) extra
        var barHeff   = barH + EAN_GUARD;

        // ── Centrado vertical (igual que CSS justify-content:center + gap) ────
        var elements = [];
        if (s.showName && product.nombre_producto) elements.push(nameH);
        elements.push(barHeff);
        if (s.showCodeText) elements.push(codeH);
        if (s.showPrice)    elements.push(priceH);

        var contentH = 0;
        for (var i = 0; i < elements.length; i++) {
            contentH += elements[i];
            if (i < elements.length - 1) contentH += GAP;
        }

        var available = H - 2 * pad;
        var y = pad + Math.max(0, Math.round((available - contentH) / 2));

        var zpl = ['^XA', '^CI28', '^PW' + W, '^LL' + H, '^LH0,0'];

        // — Nombre (recorta en 1 línea igual que CSS text-overflow:clip) —
        if (s.showName && product.nombre_producto) {
            var name = String(product.nombre_producto).substring(0, 40);
            zpl.push('^FO' + pad + ',' + y +
                     '^FB' + innerW + ',1,0,C,0' +
                     '^A0N,' + nameH + ',' + nameH +
                     '^FD' + name + '^FS');
            y += nameH + GAP;
        }

        // — Código de barras —
        if (isEan13) {
            zpl.push('^FO' + barX + ',' + y +
                     '^BY' + moduleW + ',2,' + barH +
                     '^BEN,' + barH + ',N,N' +
                     '^FD' + code + '^FS');
        } else {
            zpl.push('^FO' + barX + ',' + y +
                     '^BY' + moduleW + ',2,' + barH +
                     '^BCN,' + barH + ',N,N,N' +
                     '^FD' + code + '^FS');
        }
        y += barHeff + GAP;   // avanza barH + barras guía + gap

        // — Texto del código —
        if (s.showCodeText) {
            zpl.push('^FO' + pad + ',' + y +
                     '^FB' + innerW + ',1,0,C,0' +
                     '^A0N,' + codeH + ',' + codeH +
                     '^FD' + code + '^FS');
            y += codeH + GAP;
        }

        // — Precio —
        if (s.showPrice) {
            var priceStr = symbolCurrency + ' ' + Number(product.precio_venta || 0).toFixed(2);
            zpl.push('^FO' + pad + ',' + y +
                     '^FB' + innerW + ',1,0,C,0' +
                     '^A0N,' + priceH + ',' + priceH +
                     '^FD' + priceStr + '^FS');
        }

        zpl.push('^XZ');
        return zpl.join('\n');
    }

    /** Envía toda la cola de etiquetas a la impresora Zebra de etiquetas */
    function printZebraLabelQueue() {
        readSettings();
        var keys = Object.keys(queue);
        if (!keys.length) {
            alert('Primero agrega al menos una etiqueta a la cola de impresión.');
            return;
        }

        var btn = document.getElementById('btn-imprimir');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Imprimiendo...';

        // Construir ZPL completo (un ^XA...^XZ por etiqueta, concatenados)
        var totalSent = 0;
        var allZpl = '';
        keys.forEach(function(key) {
            var item = queue[key];
            for (var i = 0; i < item.quantity; i++) {
                allZpl += buildLabelZPL(item.product, currentSettings) + '\n';
                totalSent++;
            }
        });

        // zebraGetPrinter y zebraSend viven en footer.php (disponibles globalmente)
        zebraGetPrinter(ZEBRA_LABEL_PRINTER)
        .then(function(device) {
            if (!device) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-print"></i> Imprimir etiquetas';
                return;
            }
            return zebraSend(device, allZpl).then(function(ok) {
                btn.disabled = false;
                if (ok) {
                    btn.innerHTML = '<i class="fa fa-check"></i> Enviadas (' + totalSent + ')';
                    setTimeout(function() {
                        btn.innerHTML = '<i class="fa fa-print"></i> Imprimir etiquetas';
                    }, 2500);
                } else {
                    btn.innerHTML = '<i class="fa fa-print"></i> Imprimir etiquetas';
                }
            });
        })
        .catch(function(err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-print"></i> Imprimir etiquetas';
            console.error('[Zebra Labels]', err);
        });
    }
})();
</script>
