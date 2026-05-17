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
    justify-content: center;
    align-items: stretch;
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

/* Toast de nuevos productos */
#toast-nuevos {
    display: none;
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    background: #2ecc71;
    color: #fff;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 4px 14px rgba(0,0,0,.18);
    cursor: pointer;
    animation: slideUp .3s ease;
}
@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
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
                        <span class="pull-right badge-soft" id="badge-count">Cargando…</span>
                    </div>
                    <div class="box-body">
                        <div class="etiquetas-toolbar">
                            <div class="form-group">
                                <label for="filtro_busqueda">Buscar</label>
                                <input type="text" id="filtro_busqueda" class="form-control"
                                       placeholder="Nombre o código"
                                       autofocus autocomplete="off">
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
                                        <th style="width: 185px; white-space: nowrap;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla_productos">
                                    <tr id="row-loading">
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fa fa-spinner fa-spin"></i> Cargando productos…
                                        </td>
                                    </tr>
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
<div id="toast-nuevos" title="Haz clic para actualizar la lista"></div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3/dist/JsBarcode.all.min.js"></script>
<script>
(function() {
    var BASE_URL          = '<?php echo base_url(); ?>';
    var symbolCurrency    = <?php echo json_encode($simbolo_moneda); ?>;
    var maxKnownId        = <?php echo (int)($max_product_id ?? 0); ?>;
    var POLL_INTERVAL     = 20000; // 20 s
    var SEARCH_DEBOUNCE   = 350;   // ms
    var labelTemplatesKey = 'pos_multisucursal_label_templates_v1';
    var activeTemplateKey = 'pos_multisucursal_label_active_template_v1';
    var defaultSettings   = {
        width: 39, height: 16, padding: 1, barcodeHeight: 6.5,
        fontName: 1.8, fontPrice: 2.3, fontCode: 1.5,
        showName: true, showPrice: true, showCodeText: true
    };

    function migrateSettingsPxToMm(s) {
        if (s && s.fontName > 10) {
            var PX_TO_MM = 25.4 / 96;
            s.fontName  = Math.round(s.fontName  * PX_TO_MM * 10) / 10;
            s.fontPrice = Math.round(s.fontPrice * PX_TO_MM * 10) / 10;
            s.fontCode  = Math.round(s.fontCode  * PX_TO_MM * 10) / 10;
        }
        return s;
    }

    var templates        = [];
    var activeTemplateId = null;
    var queue            = {};
    var currentSettings  = Object.assign({}, defaultSettings);
    var productsById     = {};   // id → product object (todos los que hemos visto)
    var currentResults   = [];   // los que están actualmente en la tabla

    var searchDebounceTimer = null;
    var searchXhr           = null;  // petición AJAX en curso
    var pollTimer           = null;

    var searchInput    = document.getElementById('filtro_busqueda');
    var categorySelect = document.getElementById('filtro_categoria');
    var stockSelect    = document.getElementById('filtro_stock');
    var scanInput      = searchInput;
    var tbody          = document.getElementById('tabla_productos');
    var queueBody      = document.getElementById('tabla_cola');
    var emptyQueueRow  = document.getElementById('cola_vacia');
    var totalLabelsNode= document.getElementById('total_etiquetas');
    var previewStage   = document.getElementById('preview_stage');
    var printRoot      = document.getElementById('print-root');
    var templateSelector   = document.getElementById('template_selector');
    var templateNameInput  = document.getElementById('template_name');
    var badgeCount         = document.getElementById('badge-count');
    var toastNuevos        = document.getElementById('toast-nuevos');
    var newProductsPending = 0;

    var settingInputs = {
        width:         document.getElementById('cfg_width'),
        height:        document.getElementById('cfg_height'),
        padding:       document.getElementById('cfg_padding'),
        barcodeHeight: document.getElementById('cfg_barcode_height'),
        fontName:      document.getElementById('cfg_font_name'),
        fontPrice:     document.getElementById('cfg_font_price'),
        fontCode:      document.getElementById('cfg_font_code'),
        showName:      document.getElementById('cfg_show_name'),
        showPrice:     document.getElementById('cfg_show_price'),
        showCodeText:  document.getElementById('cfg_show_code_text')
    };

    var sliderDisplays = {
        width:         { el: document.getElementById('val_width'),          dec: 0 },
        height:        { el: document.getElementById('val_height'),         dec: 0 },
        padding:       { el: document.getElementById('val_padding'),        dec: 1 },
        barcodeHeight: { el: document.getElementById('val_barcode_height'), dec: 1 },
        fontName:      { el: document.getElementById('val_font_name'),      dec: 1 },
        fontPrice:     { el: document.getElementById('val_font_price'),     dec: 1 },
        fontCode:      { el: document.getElementById('val_font_code'),      dec: 1 },
    };

    // ── Inicialización ─────────────────────────────────────────────────────
    loadSettings();
    bindEvents();
    doSearch();          // carga inicial vía AJAX
    schedulePoll();      // inicia el polling de nuevos productos

    // ── Búsqueda AJAX ──────────────────────────────────────────────────────
    function doSearch() {
        if (searchXhr) searchXhr.abort();

        var text      = searchInput.value;
        var categoria = categorySelect.value;
        var stockMode = stockSelect.value;

        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Buscando…</td></tr>';

        searchXhr = $.ajax({
            url:  BASE_URL + 'producto/etiqueta_search',
            type: 'POST',
            data: { text: text, categoria: categoria, stock_mode: stockMode },
            dataType: 'json',
            success: function(data) {
                if (!Array.isArray(data)) data = [];
                currentResults = data;
                data.forEach(function(p) {
                    productsById[String(p.id_producto)] = p;
                    if (parseInt(p.id_producto) > maxKnownId) {
                        maxKnownId = parseInt(p.id_producto);
                    }
                });
                renderRows(data);
                badgeCount.textContent = data.length + (data.length === 400 ? '+' : '') + ' productos';
            },
            error: function() {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger"><i class="fa fa-exclamation-triangle"></i> Error al cargar productos.</td></tr>';
            }
        });
    }

    function triggerSearchDebounced() {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(doSearch, SEARCH_DEBOUNCE);
    }

    // ── Render de filas ────────────────────────────────────────────────────
    function renderRows(products) {
        if (!products.length) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No se encontraron productos.</td></tr>';
            return;
        }

        var frag = document.createDocumentFragment();
        products.forEach(function(p) {
            var tr = document.createElement('tr');
            var stock = parseInt(p.stock, 10) || 0;
            tr.innerHTML =
                '<td>' + escapeHtml(p.nombre_producto) + '</td>' +
                '<td><code>' + escapeHtml(p.codigo) + '</code></td>' +
                '<td>' + escapeHtml(p.nombre_categoria || '—') + '</td>' +
                '<td>' + stock + '</td>' +
                '<td>' + symbolCurrency + ' ' + formatPrice(p.precio_venta) + '</td>' +
                '<td style="white-space:nowrap;">' +
                    '<div class="btn-group">' +
                        '<button type="button" class="btn btn-success btn-sm js-imprimir-directo" data-id="' + p.id_producto + '" title="Imprimir 1 etiqueta directamente">' +
                            '<i class="fa fa-print"></i>' +
                        '</button>' +
                        '<button type="button" class="btn btn-primary btn-sm js-agregar-etiqueta" data-id="' + p.id_producto + '">' +
                            '<i class="fa fa-plus"></i> Agregar' +
                        '</button>' +
                        '<button type="button" class="btn btn-default btn-sm js-agregar-cinco" data-id="' + p.id_producto + '">+5</button>' +
                    '</div>' +
                '</td>';
            frag.appendChild(tr);
        });

        tbody.innerHTML = '';
        tbody.appendChild(frag);
    }

    // ── Polling de nuevos productos ────────────────────────────────────────
    function schedulePoll() {
        clearTimeout(pollTimer);
        pollTimer = setTimeout(pollNuevos, POLL_INTERVAL);
    }

    function pollNuevos() {
        $.ajax({
            url:      BASE_URL + 'producto/etiqueta_nuevos',
            type:     'GET',
            data:     { since: maxKnownId },
            dataType: 'json',
            success: function(data) {
                if (Array.isArray(data) && data.length) {
                    data.forEach(function(p) {
                        if (!productsById[String(p.id_producto)]) {
                            productsById[String(p.id_producto)] = p;
                            newProductsPending++;
                        }
                        if (parseInt(p.id_producto) > maxKnownId) {
                            maxKnownId = parseInt(p.id_producto);
                        }
                    });
                    showToastNuevos(newProductsPending);
                }
            },
            complete: schedulePoll
        });
    }

    function showToastNuevos(count) {
        toastNuevos.textContent = count + ' producto' + (count !== 1 ? 's' : '') + ' nuevo' + (count !== 1 ? 's' : '') + ' — clic para ver';
        toastNuevos.style.display = 'block';
    }

    // ── Bind de eventos ────────────────────────────────────────────────────
    function bindEvents() {
        searchInput.addEventListener('input', triggerSearchDebounced);
        categorySelect.addEventListener('change', doSearch);
        stockSelect.addEventListener('change', doSearch);

        // Delegación en la tabla (filas son dinámicas)
        tbody.addEventListener('click', function(e) {
            var btn = e.target.closest('.js-agregar-etiqueta, .js-agregar-cinco, .js-imprimir-directo');
            if (!btn) return;
            var id = btn.getAttribute('data-id');

            if (btn.classList.contains('js-imprimir-directo')) {
                var prod = productsById[id];
                if (prod) printDirect(prod, btn);
                return;
            }

            var qty = btn.classList.contains('js-agregar-cinco') ? 5 : 1;
            addToQueue(id, qty);
            scanInput.focus();
        });

        // Escaneo por Enter en el buscador (código de barras exacto)
        searchInput.addEventListener('keydown', function(e) {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            var code = searchInput.value.trim();
            if (!code) return;

            // Solo actuar como scanner si parece un código numérico
            if (!/^\d{6,}$/.test(code)) return;

            // Buscar en productos ya cargados
            var found = null;
            var ids = Object.keys(productsById);
            for (var i = 0; i < ids.length; i++) {
                if (productsById[ids[i]].codigo === code) { found = productsById[ids[i]]; break; }
            }

            if (found) {
                addToQueue(String(found.id_producto), 1);
                searchInput.value = '';
                searchInput.focus();
            } else {
                $.ajax({
                    url:  BASE_URL + 'producto/etiqueta_search',
                    type: 'POST',
                    data: { text: code, categoria: '', stock_mode: 'all' },
                    dataType: 'json',
                    success: function(data) {
                        var exact = null;
                        if (Array.isArray(data)) {
                            for (var j = 0; j < data.length; j++) {
                                if (data[j].codigo === code) { exact = data[j]; break; }
                            }
                        }
                        if (exact) {
                            productsById[String(exact.id_producto)] = exact;
                            addToQueue(String(exact.id_producto), 1);
                            searchInput.value = '';
                        } else {
                            zebraLog('Código no encontrado: ' + code, 'error');
                        }
                        searchInput.focus();
                    }
                });
            }
        });

        document.getElementById('btn-seleccionar-visibles').addEventListener('click', function() {
            currentResults.forEach(function(p) {
                addToQueue(String(p.id_producto), 1);
            });
        });

        document.getElementById('btn-limpiar-filtros').addEventListener('click', function() {
            searchInput.value    = '';
            categorySelect.value = '';
            stockSelect.value    = 'all';
            scanInput.value      = '';
            doSearch();
            searchInput.focus();
        });

        document.getElementById('btn-vaciar-cola').addEventListener('click', function() {
            queue = {};
            renderQueue();
            renderPreview();
            searchInput.focus();
        });

        document.getElementById('btn-imprimir').addEventListener('click', function() {
            printZebraLabelQueue();
        });

        // Toast: actualizar lista al hacer clic
        toastNuevos.addEventListener('click', function() {
            newProductsPending = 0;
            toastNuevos.style.display = 'none';
            doSearch();
            searchInput.focus();
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

    // ── Cola de impresión ──────────────────────────────────────────────────
    function printDirect(product, triggerBtn) {
        var zpl = buildLabelZPL(product, currentSettings);
        if (triggerBtn) {
            triggerBtn.disabled = true;
            triggerBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        }
        zebraGetPrinter(ZEBRA_LABEL_PRINTER)
        .then(function(device) {
            if (!device) {
                zebraLog('No se pudo conectar a la impresora.', 'error');
                if (triggerBtn) { triggerBtn.disabled = false; triggerBtn.innerHTML = '<i class="fa fa-print"></i>'; }
                return;
            }
            return zebraSend(device, zpl).then(function(ok) {
                if (triggerBtn) { triggerBtn.disabled = false; triggerBtn.innerHTML = '<i class="fa fa-print"></i>'; }
                if (ok) {
                    zebraLog('✔ Etiqueta impresa: ' + product.nombre_producto, 'ok');
                } else {
                    zebraLog('Error al imprimir etiqueta.', 'error');
                }
                scanInput.focus();
            });
        })
        .catch(function() {
            if (triggerBtn) { triggerBtn.disabled = false; triggerBtn.innerHTML = '<i class="fa fa-print"></i>'; }
            zebraLog('No se pudo conectar a Zebra Browser Print.', 'error');
            scanInput.focus();
        });
    }

    function addToQueue(productId, quantity) {
        var key = String(productId);
        if (!productsById[key]) return;

        if (!queue[key]) {
            queue[key] = { product: productsById[key], quantity: 0 };
        }
        queue[key].quantity += quantity;
        renderQueue();
        renderPreview();
    }

    function renderQueue() {
        Array.prototype.slice.call(queueBody.querySelectorAll('tr[data-queue-row="1"]')).forEach(function(n) {
            n.parentNode.removeChild(n);
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
                var k = this.dataset.productId;
                queue[k].quantity = Math.max(1, parseInt(this.value, 10) || 1);
                renderQueue();
                renderPreview();
            });
        });

        Array.prototype.slice.call(queueBody.querySelectorAll('.js-minus')).forEach(function(btn) {
            btn.addEventListener('click', function() {
                var k = this.dataset.productId;
                if (!queue[k]) return;
                queue[k].quantity -= 1;
                if (queue[k].quantity <= 0) delete queue[k];
                renderQueue();
                renderPreview();
            });
        });

        Array.prototype.slice.call(queueBody.querySelectorAll('.js-remove')).forEach(function(btn) {
            btn.addEventListener('click', function() {
                delete queue[this.dataset.productId];
                renderQueue();
                renderPreview();
            });
        });
    }

    // ── Vista previa ───────────────────────────────────────────────────────
    function renderPreview() {
        readSettings();
        previewStage.innerHTML = '';

        var firstKey = Object.keys(queue)[0];
        if (!firstKey) {
            previewStage.innerHTML = '<div class="preview-empty">Selecciona un producto para ver cómo quedará la etiqueta.</div>';
            return;
        }

        var item       = queue[firstKey];
        var previewWrap = document.createElement('div');
        previewWrap.className = 'label-preview';
        var scale = getPreviewScale();
        previewWrap.style.width   = (currentSettings.width  * scale) + 'mm';
        previewWrap.style.height  = (currentSettings.height * scale) + 'mm';
        previewWrap.style.padding = '4px';

        var scaleHost = document.createElement('div');
        scaleHost.className = 'label-preview-scale';
        scaleHost.style.width           = currentSettings.width  + 'mm';
        scaleHost.style.height          = currentSettings.height + 'mm';
        scaleHost.style.transform       = 'scale(' + scale + ')';
        scaleHost.style.transformOrigin = 'center center';

        scaleHost.appendChild(buildLabelNode(item.product));
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
        label.style.setProperty('--label-width-mm',          currentSettings.width         + 'mm');
        label.style.setProperty('--label-height-mm',         currentSettings.height        + 'mm');
        label.style.setProperty('--label-padding-mm',        currentSettings.padding       + 'mm');
        label.style.setProperty('--label-barcode-height-mm', currentSettings.barcodeHeight + 'mm');
        label.style.setProperty('--label-font-name-mm',      currentSettings.fontName      + 'mm');
        label.style.setProperty('--label-font-price-mm',     currentSettings.fontPrice     + 'mm');
        label.style.setProperty('--label-font-code-mm',      currentSettings.fontCode      + 'mm');
        label.style.marginRight = label.style.marginBottom = '0';

        if (printVersion) {
            label.style.width  = currentSettings.width  + 'mm';
            label.style.height = currentSettings.height + 'mm';
        }

        if (currentSettings.showName) {
            var name = document.createElement('div');
            name.className   = 'label-name';
            name.textContent = product.nombre_producto;
            label.appendChild(name);
        }

        var barcodeWrap = document.createElement('div');
        barcodeWrap.className  = 'label-barcode';
        barcodeWrap.style.height = currentSettings.barcodeHeight + 'mm';
        var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('class',     'js-label-barcode');
        svg.setAttribute('data-code', product.codigo);
        barcodeWrap.appendChild(svg);
        label.appendChild(barcodeWrap);

        if (currentSettings.showCodeText) {
            var code = document.createElement('div');
            code.className   = 'label-code';
            code.textContent = product.codigo;
            label.appendChild(code);
        }

        if (currentSettings.showPrice) {
            var price = document.createElement('div');
            price.className   = 'label-price';
            price.textContent = symbolCurrency + ' ' + formatPrice(product.precio_venta);
            label.appendChild(price);
        }

        return label;
    }

    function renderBarcodes(container) {
        return new Promise(function(resolve) {
            var svgs = Array.prototype.slice.call(container.querySelectorAll('.js-label-barcode'));
            if (!svgs.length) { requestAnimationFrame(resolve); return; }

            var DPM      = 8.0267;
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
                        format: isEan13 ? 'EAN13' : 'CODE128',
                        width: modPx, height: mmToPx(barH_mm), margin: 0, displayValue: false
                    });
                } catch (e) { console.error('Barcode error: ' + code, e); return; }

                svg.removeAttribute('height');
                svg.style.height   = barH_mm + 'mm';
                svg.style.width    = 'auto';
                svg.style.maxWidth = innerW_mm + 'mm';
                svg.style.display  = 'block';
                svg.style.margin   = '0 auto';
            });

            requestAnimationFrame(function() { setTimeout(resolve, 150); });
        });
    }

    function injectPrintStyles() {
        var styleId   = 'dynamic-label-print-style';
        var styleNode = document.getElementById(styleId);
        if (!styleNode) {
            styleNode    = document.createElement('style');
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

    // ── Impresión Zebra ────────────────────────────────────────────────────
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

        var totalSent = 0;
        var allZpl    = '';
        keys.forEach(function(key) {
            var item = queue[key];
            for (var i = 0; i < item.quantity; i++) {
                allZpl += buildLabelZPL(item.product, currentSettings) + '\n';
                totalSent++;
            }
        });

        zebraGetPrinter(ZEBRA_LABEL_PRINTER)
        .then(function(device) {
            if (!device) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-print"></i> Imprimir etiquetas';
                searchInput.focus();
                return;
            }
            return zebraSend(device, allZpl).then(function(ok) {
                btn.disabled = false;
                if (ok) {
                    btn.innerHTML = '<i class="fa fa-check"></i> Enviadas (' + totalSent + ')';
                    queue = {};
                    renderQueue();
                    renderPreview();
                    setTimeout(function() {
                        btn.innerHTML = '<i class="fa fa-print"></i> Imprimir etiquetas';
                    }, 1800);
                } else {
                    btn.innerHTML = '<i class="fa fa-print"></i> Imprimir etiquetas';
                }
                searchInput.value = '';
                searchInput.focus();
            });
        })
        .catch(function(err) {
            btn.disabled  = false;
            btn.innerHTML = '<i class="fa fa-print"></i> Imprimir etiquetas';
            console.error('[Zebra Labels]', err);
            searchInput.focus();
        });
    }

    // ── ZPL builder ────────────────────────────────────────────────────────
    function zplFhEncode(str) {
        str = String(str || '').replace(/[\^~]/g, '');
        var result = '';
        for (var i = 0; i < str.length; ) {
            var code = str.codePointAt(i);
            if (code <= 127) {
                result += str[i];
                i++;
            } else {
                var bytes = encodeURIComponent(String.fromCodePoint(code)).replace(/%/g, '_');
                result += bytes;
                i += code > 0xFFFF ? 2 : 1;
            }
        }
        return result;
    }

    function buildLabelZPL(product, s) {
        var DPM  = 8.0267;
        var GAP  = 4;

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
            contentH += elements[i];
            if (i < elements.length - 1) contentH += GAP;
        }

        var available = H - 2 * pad;
        var y = pad + Math.max(0, Math.round((available - contentH) / 2));

        var zpl = ['^XA', '^CI28', ZEBRA_LABEL_MEDIA_TYPE, '^PW' + W, '^LL' + H, '^LH0,0'];

        if (s.showName && product.nombre_producto) {
            var nm = String(product.nombre_producto).substring(0, 40);
            zpl.push('^FO' + pad + ',' + y + '^FB' + innerW + ',1,0,C,0^A0N,' + nameH + ',' + nameH + '^FH^FD' + zplFhEncode(nm) + '^FS');
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
            var priceStr = symbolCurrency + ' ' + Number(product.precio_venta || 0).toFixed(2);
            zpl.push('^FO' + pad + ',' + y + '^FB' + innerW + ',1,0,C,0^A0N,' + priceH + ',' + priceH + '^FD' + priceStr + '^FS');
        }

        zpl.push('^XZ');
        return zpl.join('\n');
    }

    // ── Plantillas ─────────────────────────────────────────────────────────
    function loadSettings() {
        try {
            templates        = JSON.parse(window.localStorage.getItem(labelTemplatesKey) || '[]');
            activeTemplateId = window.localStorage.getItem(activeTemplateKey);
        } catch (e) {
            templates        = [];
            activeTemplateId = null;
        }

        templates.forEach(function(t) { migrateSettingsPxToMm(t.settings); });

        if (!Array.isArray(templates) || !templates.length) {
            templates = [{ id: 'default_39x16', name: 'Zebra 39x16', settings: Object.assign({}, defaultSettings) }];
            activeTemplateId = templates[0].id;
            persistTemplates();
        }

        if (!templates.some(function(t) { return t.id === activeTemplateId; })) {
            activeTemplateId = templates[0].id;
        }

        populateTemplateSelector();
        selectTemplate(activeTemplateId);
        hydrateSettings();
    }

    function hydrateSettings() {
        settingInputs.width.value         = currentSettings.width;
        settingInputs.height.value        = currentSettings.height;
        settingInputs.padding.value       = currentSettings.padding;
        settingInputs.barcodeHeight.value = currentSettings.barcodeHeight;
        settingInputs.fontName.value      = currentSettings.fontName;
        settingInputs.fontPrice.value     = currentSettings.fontPrice;
        settingInputs.fontCode.value      = currentSettings.fontCode;
        settingInputs.showName.checked    = !!currentSettings.showName;
        settingInputs.showPrice.checked   = !!currentSettings.showPrice;
        settingInputs.showCodeText.checked= !!currentSettings.showCodeText;
        updateSliderDisplays();
    }

    function readSettings() {
        currentSettings.width         = parseFloat(settingInputs.width.value)         || defaultSettings.width;
        currentSettings.height        = parseFloat(settingInputs.height.value)        || defaultSettings.height;
        currentSettings.padding       = parseFloat(settingInputs.padding.value)       || 0;
        currentSettings.barcodeHeight = parseFloat(settingInputs.barcodeHeight.value) || defaultSettings.barcodeHeight;
        currentSettings.fontName      = parseFloat(settingInputs.fontName.value)      || defaultSettings.fontName;
        currentSettings.fontPrice     = parseFloat(settingInputs.fontPrice.value)     || defaultSettings.fontPrice;
        currentSettings.fontCode      = parseFloat(settingInputs.fontCode.value)      || defaultSettings.fontCode;
        currentSettings.showName      = settingInputs.showName.checked;
        currentSettings.showPrice     = settingInputs.showPrice.checked;
        currentSettings.showCodeText  = settingInputs.showCodeText.checked;
    }

    function updateSliderDisplays() {
        Object.keys(sliderDisplays).forEach(function(key) {
            var d = sliderDisplays[key];
            if (d.el) d.el.textContent = parseFloat(currentSettings[key]).toFixed(d.dec);
        });
    }

    function populateTemplateSelector() {
        templateSelector.innerHTML = '';
        templates.forEach(function(t) {
            var opt = document.createElement('option');
            opt.value       = t.id;
            opt.textContent = t.name;
            templateSelector.appendChild(opt);
        });
        templateSelector.value = activeTemplateId;
    }

    function selectTemplate(templateId) {
        var selected = templates.find(function(t) { return t.id === templateId; });
        if (!selected) return;
        activeTemplateId    = selected.id;
        currentSettings     = Object.assign({}, defaultSettings, selected.settings || {});
        templateNameInput.value = selected.name;
        templateSelector.value  = selected.id;
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
            templates = templates.map(function(t) {
                return t.id === activeTemplateId
                    ? { id: t.id, name: templateName, settings: Object.assign({}, currentSettings) }
                    : t;
            });
        } else {
            var newId = 'tpl_' + Date.now();
            templates.push({ id: newId, name: templateName, settings: Object.assign({}, currentSettings) });
            activeTemplateId = newId;
        }

        persistTemplates();
        populateTemplateSelector();
        templateSelector.value = activeTemplateId;
    }

    function deleteTemplate() {
        if (templates.length === 1) { alert('Debe existir al menos una plantilla.'); return; }
        if (!activeTemplateId) return;
        templates        = templates.filter(function(t) { return t.id !== activeTemplateId; });
        activeTemplateId = templates[0].id;
        persistTemplates();
        populateTemplateSelector();
        selectTemplate(activeTemplateId);
    }

    function persistTemplates() {
        window.localStorage.setItem(labelTemplatesKey, JSON.stringify(templates));
        window.localStorage.setItem(activeTemplateKey, activeTemplateId);
    }

    // ── Helpers ────────────────────────────────────────────────────────────
    function getPreviewScale() {
        var maxW = 72, maxH = 36;
        return Math.max(2.2, Math.min(5.2, Math.min(maxW / currentSettings.width, maxH / currentSettings.height)));
    }

    function mmToPx(mm) { return Math.max(18, Math.round(mm * 3.78)); }

    function formatPrice(value) { return Number(value || 0).toFixed(2); }

    function escapeHtml(text) {
        return String(text || '')
            .replace(/&/g,  '&amp;')
            .replace(/</g,  '&lt;')
            .replace(/>/g,  '&gt;')
            .replace(/"/g,  '&quot;')
            .replace(/'/g,  '&#039;');
    }
})();
</script>
