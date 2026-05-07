<style>

/* Indicador de campo requerido */
.label-required::after {
    content: ' *';
    color: #e74c3c;
    font-weight: 700;
}

/* Indicador de campo opcional */
.label-optional .badge-optional {
    font-size: 10px;
    font-weight: 400;
    background: #ecf0f1;
    color: #7f8c8d;
    border-radius: 3px;
    padding: 1px 5px;
    margin-left: 4px;
    vertical-align: middle;
}

/* Toast de notificaciones */
#toast-container-custom {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 99999;
    display: flex;
    flex-direction: column;
    gap: 8px;
    max-width: 380px;
}

.toast-msg {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 14px 16px;
    border-radius: 6px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.18);
    font-size: 14px;
    line-height: 1.4;
    animation: toast-in 0.25s ease;
    cursor: pointer;
}

.toast-msg.toast-error   { background: #fff; border-left: 4px solid #e74c3c; }
.toast-msg.toast-success { background: #fff; border-left: 4px solid #27ae60; }
.toast-msg.toast-warning { background: #fff; border-left: 4px solid #f39c12; }

.toast-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
.toast-body { flex: 1; }
.toast-body strong { display: block; margin-bottom: 2px; }
.toast-close { background: none; border: none; font-size: 16px; color: #999; cursor: pointer; padding: 0; line-height: 1; }

@keyframes toast-in {
    from { opacity: 0; transform: translateX(30px); }
    to   { opacity: 1; transform: translateX(0); }
}

/* Campo con error */
.form-group.has-error .form-control,
.form-group.has-error .search-input {
    border-color: #e74c3c;
    box-shadow: inset 0 1px 1px rgba(231,76,60,.075), 0 0 0 3px rgba(231,76,60,.1);
}
.form-group.has-error .field-error-msg {
    color: #e74c3c;
    font-size: 12px;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Buscador de categoría */
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
    max-height: 150px;
    overflow-y: auto;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
    z-index: 1000;
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

/* Layout principal */
.prod-add-wrapper { padding: 16px 20px; }

.prod-form-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,.12);
    overflow: hidden;
}

.prod-form-card-header {
    padding: 14px 18px;
    border-bottom: 1px solid #ecf0f1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
}

.prod-form-card-header h4 {
    margin: 0;
    font-size: 15px;
    font-weight: 700;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 8px;
}

.prod-form-card-body { padding: 18px; }

.prod-form-card-footer {
    padding: 14px 18px;
    border-top: 1px solid #ecf0f1;
    background: #f8f9fa;
    display: flex;
    gap: 8px;
    align-items: center;
}

.prod-section-title {
    font-size: 11px;
    font-weight: 700;
    color: #95a5a6;
    text-transform: uppercase;
    letter-spacing: .6px;
    margin: 0 0 12px;
    padding-bottom: 6px;
    border-bottom: 1px solid #ecf0f1;
}

.prod-section-sep { margin-top: 18px; }

/* Panel preview etiqueta */
.prod-preview-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,.12);
    overflow: hidden;
    position: sticky;
    top: 70px;
}

.prod-preview-header {
    padding: 12px 16px;
    border-bottom: 1px solid #ecf0f1;
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8f9fa;
}

.prod-preview-header span {
    font-size: 13px;
    font-weight: 600;
    color: #2c3e50;
}

.prod-preview-body {
    padding: 16px;
    min-height: 180px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #fafbfc;
}

.prod-preview-placeholder {
    text-align: center;
    color: #bdc3c7;
    font-size: 12px;
    padding: 20px 10px;
}

.prod-preview-placeholder i {
    font-size: 32px;
    display: block;
    margin-bottom: 8px;
    color: #d5dee5;
}

.prod-preview-footer {
    padding: 10px 16px;
    border-top: 1px solid #ecf0f1;
    font-size: 11px;
    color: #aaa;
    text-align: center;
}

/* Tips de ayuda */
.prod-tip {
    background: #eaf6fb;
    border-left: 3px solid #3498db;
    border-radius: 0 4px 4px 0;
    padding: 8px 12px;
    font-size: 12px;
    color: #555;
    margin-top: 6px;
}

.prod-tip i { color: #3498db; margin-right: 4px; }

@media(max-width:768px) {
    .prod-add-wrapper { padding: 10px; }
    .prod-preview-card { position: static; margin-top: 16px; }
}
</style>

<div id="toast-container-custom"></div>

<div class="content-wrapper">
<div class="prod-add-wrapper">

    <!-- Encabezado de página -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-cube text-success"></i> Agregar producto
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;">Registra un nuevo producto en el inventario</p>
        </div>
        <a class="btn btn-default btn-sm" href="<?php echo base_url(); ?>producto/producto_lista">
            <i class="fa fa-arrow-left"></i> Volver al catálogo
        </a>
    </div>

    <?php $this->load->helper('form'); ?>

    <div class="row">
        <!-- ── Columna izquierda: formulario ── -->
        <div class="col-md-8">
            <div class="prod-form-card">

                <div class="prod-form-card-header">
                    <h4><i class="fa fa-cube text-success"></i> Datos del producto</h4>
                    <small style="color:#95a5a6;"><span style="color:#e74c3c;font-weight:700;">*</span> Campo obligatorio</small>
                </div>

                <form role="form" id="addProducto" action="<?php echo base_url() ?>producto/addNewProducto" method="post" enctype="multipart/form-data">
                <div class="prod-form-card-body">

                    <p class="prod-section-title"><i class="fa fa-info-circle"></i> Información básica</p>
                    <div class="row">
                        <div class="col-sm-12 col-md-7">
                            <div class="form-group">
                                <label for="nombre_producto" class="label-required">Nombre del producto</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars(set_value('nombre_producto'), ENT_QUOTES); ?>" id="nombre_producto" name="nombre_producto" maxlength="200" autofocus placeholder="Ej: Camiseta de algodón manga larga" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-5">
                            <div class="form-group custom-select">
                                <label for="search_categoria" class="label-required">Categoría</label>
                                <input type="text" class="search-input form-control" id="search_categoria" placeholder="Buscar categoría…" autocomplete="off" />
                                <ul class="categoria-list" style="display:none;">
                                    <?php foreach ($categorias as $categoria): ?>
                                        <li data-value="<?php echo $categoria->id_categoria; ?>"><?php echo htmlspecialchars($categoria->nombre_categoria, ENT_QUOTES); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <input type="hidden" id="id_categoria" name="id_categoria" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="talla" class="label-optional">Talla <span class="badge-optional">Opcional</span></label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars(set_value('talla'), ENT_QUOTES); ?>" id="talla" name="talla" maxlength="50" placeholder="Ej: Unitalla, M, G, 28, NA" />
                                <small class="form-text text-muted">Vacío = se guardará como "NA".</small>
                            </div>
                        </div>
                    </div>

                    <p class="prod-section-title prod-section-sep"><i class="fa fa-dollar"></i> Precios y stock</p>
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="precio_compra" class="label-required">Precio de compra</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                    <input type="number" class="form-control"
                                           value="<?php echo htmlspecialchars(set_value('precio_compra'), ENT_QUOTES); ?>"
                                           id="precio_compra" name="precio_compra"
                                           min="0" step="0.01" placeholder="0.00" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="precio_venta" class="label-required">Precio de venta</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                                    <input type="number" class="form-control"
                                           value="<?php echo htmlspecialchars(set_value('precio_venta'), ENT_QUOTES); ?>"
                                           id="precio_venta" name="precio_venta"
                                           min="0.01" step="0.01" placeholder="0.00" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="stock" class="label-required">Stock inicial</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                    <input type="number" class="form-control"
                                           value="<?php echo htmlspecialchars(set_value('stock'), ENT_QUOTES); ?>"
                                           id="stock" name="stock" min="0" placeholder="0" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="prod-section-title prod-section-sep"><i class="fa fa-barcode"></i> Código de barras</p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="label-required">Código de barras</label>
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control"
                                           id="codigo_proveedor"
                                           name="codigo_proveedor"
                                           maxlength="13"
                                           placeholder="Escanea o escribe el código, o usa el botón Generar…"
                                           value="<?php echo htmlspecialchars(set_value('codigo_proveedor', isset($codigo_prefill) ? $codigo_prefill : ''), ENT_QUOTES); ?>" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info" id="btn_generar_ean" title="Generar código EAN-13 automáticamente">
                                            <i class="fa fa-barcode"></i> Generar
                                        </button>
                                    </span>
                                </div>
                                <div class="prod-tip" style="margin-top:6px;">
                                    <i class="fa fa-info-circle"></i>
                                    Escanea el código del proveedor con un lector, o haz clic en <strong>Generar</strong> si el producto no tiene código propio.
                                </div>
                                <input type="hidden" name="usar_codigo_generado" id="usar_codigo_generado" value="0">
                            </div>
                        </div>
                    </div>

                    <p class="prod-section-title prod-section-sep"><i class="fa fa-file-text-o"></i> Información adicional</p>
                    <div class="row">
                        <div class="col-sm-12 col-md-7">
                            <div class="form-group">
                                <label for="detalles" class="label-optional">Detalles <span class="badge-optional">Opcional</span></label>
                                <textarea class="form-control" id="detalles" name="detalles" rows="3" placeholder="Material, color, descripción adicional…" maxlength="500"><?php echo htmlspecialchars(set_value('detalles'), ENT_QUOTES); ?></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-5">
                            <div class="form-group">
                                <label for="imagen" class="label-optional">Imagen del producto <span class="badge-optional">Opcional</span></label>
                                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" />
                                <small class="form-text text-muted"><i class="fa fa-info-circle"></i> JPG, PNG o GIF · Máx. 2 MB · Se comprimirá automáticamente.</small>
                            </div>
                        </div>
                    </div>

                </div><!-- /prod-form-card-body -->
                <div class="prod-form-card-footer">
                    <button type="button" class="btn btn-success" id="btn_agregar_producto">
                        <i class="fa fa-plus"></i> Registrar producto
                    </button>
                    <button type="button" class="btn btn-default" onclick="$('#addProducto')[0].reset();$('#id_categoria').val('');$('#search_categoria').val('');$('#usar_codigo_generado').val(0);clearFieldErrors();">
                        <i class="fa fa-eraser"></i> Limpiar campos
                    </button>
                </div>
                </form>

            </div><!-- /prod-form-card -->
        </div><!-- /col-md-8 -->

        <!-- ── Columna derecha: preview etiqueta ── -->
        <div class="col-md-4">
            <div class="prod-preview-card">
                <div class="prod-preview-header">
                    <i class="fa fa-tag text-warning"></i>
                    <span>Vista previa de etiqueta</span>
                </div>
                <div class="prod-preview-body">
                    <div id="livePreviewStage">
                        <div class="prod-preview-placeholder">
                            <i class="fa fa-barcode"></i>
                            Completa el nombre y el precio<br>para ver la etiqueta aquí
                        </div>
                    </div>
                </div>
                <div class="prod-preview-footer">
                    <i class="fa fa-info-circle"></i>
                    Usa la configuración de <strong>Impresión de etiquetas</strong>
                </div>
            </div>

            <!-- Resumen de pasos de ayuda -->
            <div style="margin-top:14px;background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);padding:14px 16px;">
                <div style="font-size:12px;font-weight:700;color:#7f8c8d;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">Pasos rápidos</div>
                <div style="display:flex;flex-direction:column;gap:8px;font-size:12px;color:#555;">
                    <div style="display:flex;align-items:flex-start;gap:8px;">
                        <span style="background:#27ae60;color:#fff;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;">1</span>
                        Escribe el nombre y selecciona la categoría
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:8px;">
                        <span style="background:#27ae60;color:#fff;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;">2</span>
                        Ingresa precios de compra y venta, y el stock inicial
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:8px;">
                        <span style="background:#27ae60;color:#fff;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;">3</span>
                        Escanea el código de barras o usa <strong>Generar</strong>
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:8px;">
                        <span style="background:#95a5a6;color:#fff;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;">4</span>
                        <span style="color:#aaa;">Imagen y detalles son opcionales</span>
                    </div>
                </div>
            </div>

            <!-- Contenedor de notificaciones (AJAX) -->
            <div id="notificaciones-container"></div>
        </div><!-- /col-md-4 -->
    </div>

</div>
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

    // Función global para poder llamarla desde onclick inline
    function clearFieldErrors() {
        $('.form-group.has-error').removeClass('has-error');
        $('.field-error-msg').remove();
    }

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

        // ── Toast notifications ──────────────────────────────────────────────
        function showToast(type, title, body, duration) {
            duration = duration || 5000;
            var icons = { error: '❌', success: '✅', warning: '⚠️' };
            var id = 'toast-' + Date.now();
            var html = '<div class="toast-msg toast-' + type + '" id="' + id + '">' +
                           '<span class="toast-icon">' + icons[type] + '</span>' +
                           '<div class="toast-body"><strong>' + title + '</strong>' + (body ? body : '') + '</div>' +
                           '<button class="toast-close" onclick="$(\'#' + id + '\').remove()">×</button>' +
                       '</div>';
            $('#toast-container-custom').append(html);
            setTimeout(function() { $('#' + id).fadeOut(300, function() { $(this).remove(); }); }, duration);
        }

        // ── Resaltar errores por campo ───────────────────────────────────────
        function markFieldErrors(errors) {
            clearFieldErrors();
            // Mapeo campo → id del contenedor form-group
            var fieldMap = {
                nombre_producto: '#nombre_producto',
                precio_compra:   '#precio_compra',
                precio_venta:    '#precio_venta',
                stock:           '#stock',
                id_categoria:    '#search_categoria',
                codigo_proveedor:'#codigo_proveedor',
                imagen:          '#imagen',
                talla:           '#talla',
                detalles:        '#detalles'
            };
            $.each(errors, function(field, msg) {
                var $input = $(fieldMap[field]);
                if (!$input.length) return;
                var $group = $input.closest('.form-group');
                $group.addClass('has-error');
                $group.append('<div class="field-error-msg"><i class="fa fa-exclamation-circle"></i> ' + msg + '</div>');
            });
            // Scroll al primer error
            var $first = $('.form-group.has-error').first();
            if ($first.length) {
                $('html, body').animate({ scrollTop: $first.offset().top - 80 }, 300);
            }
        }

        // ── Generar EAN-13 ───────────────────────────────────────────────────
        $('#btn_generar_ean').on('click', function() {
            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: '<?php echo base_url("producto/generar_ean13_ajax"); ?>',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#codigo_proveedor').val(response.ean13).trigger('change');
                        $('#usar_codigo_generado').val(1);
                        // Limpiar error del campo si lo había
                        $('#codigo_proveedor').closest('.form-group').removeClass('has-error');
                        $('#codigo_proveedor').closest('.form-group').find('.field-error-msg').remove();
                    } else {
                        showToast('error', 'No se pudo generar el código', response.message);
                    }
                    btn.prop('disabled', false).html('<i class="fa fa-barcode"></i> Generar');
                },
                error: function() {
                    showToast('error', 'Error de conexión', 'No se pudo contactar al servidor para generar el código.');
                    btn.prop('disabled', false).html('<i class="fa fa-barcode"></i> Generar');
                }
            });
        });

        // Si el usuario escribe manualmente, el código ya no es "generado"
        $('#codigo_proveedor').on('input', function() {
            $('#usar_codigo_generado').val(0);
        });

        // ── Validación client-side antes de enviar ───────────────────────────
        function validarFormulario() {
            var errores = {};
            if (!$.trim($('#nombre_producto').val())) errores.nombre_producto = 'El nombre es obligatorio.';
            if (!$('#id_categoria').val()) errores.id_categoria = 'Selecciona una categoría.';
            if (!$.trim($('#precio_compra').val()) || isNaN($('#precio_compra').val())) errores.precio_compra = 'Ingresa un precio de compra válido.';
            if (!$.trim($('#precio_venta').val()) || parseFloat($('#precio_venta').val()) <= 0) errores.precio_venta = 'El precio de venta debe ser mayor a cero.';
            if ($('#stock').val() === '' || parseInt($('#stock').val()) < 0) errores.stock = 'El stock debe ser 0 o más.';
            if (!$.trim($('#codigo_proveedor').val())) errores.codigo_proveedor = 'Escanea un código o usa el botón "Generar".';
            return errores;
        }

        // ── Enviar formulario ────────────────────────────────────────────────
        $('#btn_agregar_producto').on('click', function(e) {
            e.preventDefault();
            clearFieldErrors();

            // Validación client-side
            var erroresLocales = validarFormulario();
            if (Object.keys(erroresLocales).length > 0) {
                markFieldErrors(erroresLocales);
                showToast('warning', 'Campos incompletos', 'Revisa los campos marcados en rojo.');
                return;
            }

            var form = $('#addProducto');
            var formData = new FormData(form[0]);
            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    btn.prop('disabled', false).html('<i class="fa fa-plus"></i> Registrar producto');

                    if (response.success) {
                        clearFieldErrors();
                        if (response.producto) {
                            showLabelModal(response.producto);
                            form[0].reset();
                            $('#id_categoria').val('');
                            $('#search_categoria').val('');
                            $('#usar_codigo_generado').val(0);
                        } else {
                            showToast('success', 'Producto registrado', response.message, 6000);
                            form[0].reset();
                            $('#id_categoria').val('');
                            $('#search_categoria').val('');
                            $('#usar_codigo_generado').val(0);
                        }
                        $('#nombre_producto').focus();
                    } else {
                        // Errores del servidor
                        if (response.errors && Object.keys(response.errors).length > 0) {
                            markFieldErrors(response.errors);
                        }
                        showToast('error', 'No se pudo guardar', response.message, 8000);
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="fa fa-plus"></i> Registrar producto');
                    var msg = (xhr.status === 0)
                        ? 'Sin conexión al servidor. Verifica tu red e intenta de nuevo.'
                        : 'Error del servidor (' + xhr.status + '). Intenta de nuevo o contacta al administrador.';
                    showToast('error', 'Error inesperado', msg, 8000);
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
            $('#nombre_producto').focus();
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
            var codigo = $('#codigo_proveedor').val().trim() || '0000000000000';

            var liveStage = document.getElementById('livePreviewStage');

            if (!nombre || precio <= 0) {
                liveStage.innerHTML = '<div class="prod-preview-placeholder"><i class="fa fa-barcode"></i>Completa el nombre y el precio<br>para ver la etiqueta aquí</div>';
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

        // Si el código fue pre-llenado desde resurtir, disparar preview
        if ($('#codigo_proveedor').val().trim()) {
            updateLivePreview();
        }
    });
</script>
