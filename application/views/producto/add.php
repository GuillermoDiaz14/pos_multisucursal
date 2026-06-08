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

.category-inline-actions .btn {
    min-width: 38px;
    padding: 6px 10px;
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

/* Modales inline para crear nuevos items */
.modal-inline {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.2s ease;
}

.modal-inline.active {
    display: flex;
}

.modal-inline-content {
    background: #fff;
    border-radius: 8px;
    width: 90%;
    max-width: 450px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    animation: slideUp 0.3s ease;
}

.modal-inline-header {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 16px;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-inline-body {
    margin-bottom: 16px;
}

.modal-inline-body .form-group {
    margin-bottom: 12px;
}

.modal-inline-body .form-group label {
    display: block;
    margin-bottom: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #555;
}

.modal-inline-body .form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 13px;
}

.modal-inline-footer {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}

.modal-inline-footer button {
    padding: 8px 14px;
    border: none;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.modal-inline-footer .btn-primary {
    background: #3498db;
    color: white;
}

.modal-inline-footer .btn-primary:hover {
    background: #2980b9;
}

.modal-inline-footer .btn-secondary {
    background: #ddd;
    color: #333;
}

.modal-inline-footer .btn-secondary:hover {
    background: #ccc;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
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

.prod-form-card-body { padding: 22px 28px; }

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

@media(max-width:768px) {
    .prod-add-wrapper { padding: 10px; }
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
        <div class="col-md-12">
            <div class="prod-form-card">

                <div class="prod-form-card-header">
                    <h4><i class="fa fa-cube text-success"></i> Datos del producto</h4>
                    <small style="color:#95a5a6;"><span style="color:#e74c3c;font-weight:700;">*</span> Campo obligatorio</small>
                </div>

                <form role="form" id="addProducto" action="<?php echo base_url() ?>producto/addNewProducto" method="post" enctype="multipart/form-data">
                <div class="prod-form-card-body">

                    <p class="prod-section-title"><i class="fa fa-barcode"></i> Código de barras</p>
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
                                           autofocus
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

                    <p class="prod-section-title prod-section-sep"><i class="fa fa-info-circle"></i> Información básica</p>
                    <div class="row">
                        <div class="col-sm-12 col-md-7">
                            <div class="form-group">
                                <label for="nombre_producto" class="label-required">Nombre del producto</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars(set_value('nombre_producto'), ENT_QUOTES); ?>" id="nombre_producto" name="nombre_producto" maxlength="200" placeholder="Ej: Camiseta de algodón manga larga" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-5">
                            <div class="form-group custom-select">
                                <label for="search_categoria" class="label-required">Categoría</label>
                                <div class="input-group category-inline-actions">
                                    <input type="text" class="search-input form-control" id="search_categoria" placeholder="Buscar categoría…" autocomplete="off" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-sm" id="btn_nueva_categoria" title="Crear nueva categoría">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </span>
                                </div>
                                <ul class="categoria-list" style="display:none;">
                                    <?php foreach ($categorias as $categoria): ?>
                                        <li data-value="<?php echo $categoria->id_categoria; ?>"><?php echo htmlspecialchars($categoria->nombre_categoria, ENT_QUOTES); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <input type="hidden" id="id_categoria" name="id_categoria" />
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top:12px;">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label for="id_subcategoria" id="lbl_subcategoria" class="label-optional">Subcategoría <span class="badge-optional">Opcional</span></label>
                                <div class="input-group">
                                    <select class="form-control" id="id_subcategoria" name="id_subcategoria">
                                        <option value="">-- Selecciona una subcategoría --</option>
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-sm" id="btn_nueva_subcategoria" title="Crear nueva subcategoría">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </span>
                                </div>
                                <small class="form-text text-muted">Se carga según la categoría. Obligatoria si existen subcategorías.</small>
                            </div>
                        </div>
                    </div>

                    <!-- ── Variantes (corrida de tallas) ────────────────────────── -->
                    <div class="row" style="margin-top:4px;">
                        <div class="col-sm-12">
                            <div style="background:#fff8e6;border:1px solid #ffe9a8;border-radius:6px;padding:10px 12px;">
                                <label style="margin:0;font-weight:600;color:#7a5d00;cursor:pointer;">
                                    <input type="checkbox" id="tiene_variantes" name="tiene_variantes" value="1" style="vertical-align:middle;margin-right:6px;">
                                    Selecciona si el producto tiene variantes por talla o tamaño.
                                </label>
                                <small style="display:block;color:#8a7a3d;margin-top:4px;">
                                    Útil para productos con variantes que comparten un mismo código de barras.
                                </small>

                                <div id="variantes_panel" style="display:none;margin-top:12px;background:#fff;border:1px dashed #ddd;border-radius:6px;padding:10px;">
                                    <table class="table table-condensed" style="margin:0;">
                                        <thead>
                                            <tr style="background:#f5f5f5;">
                                                <th style="width:22%;">Talla *</th>
                                                <th style="width:23%;">Stock inicial *</th>
                                                <th style="width:20%;">Precio compra *</th>
                                                <th style="width:20%;">Precio venta *</th>
                                                <th style="width:15%;text-align:center;">Quitar</th>
                                            </tr>
                                        </thead>
                                        <tbody id="variantes_tbody"></tbody>
                                    </table>
                                    <button type="button" class="btn btn-default btn-sm" id="btn_add_variante">
                                        <i class="fa fa-plus"></i> Agregar variante
                                    </button>
                                    <small class="form-text text-muted" style="margin-top:6px;">
                                        Todos los campos son obligatorios. El stock corresponde a la sucursal actual.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="prod-section-title prod-section-sep"><i class="fa fa-dollar"></i> Precios y stock</p>
                    <div class="row">
                        <div class="col-sm-12 col-md-4" id="precio_compra_col">
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
                        <div class="col-sm-12 col-md-4" id="precio_venta_col">
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
                        <div class="col-sm-12 col-md-4" id="stock_global_col">
                            <div class="form-group">
                                <label for="stock" class="label-required">Stock inicial</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                    <input type="number" class="form-control"
                                           value="<?php echo htmlspecialchars(set_value('stock'), ENT_QUOTES); ?>"
                                           id="stock" name="stock" min="0" placeholder="0" />
                                </div>
                                <small class="form-text text-muted">Se ignora si activas variantes por talla.</small>
                            </div>
                        </div>
                    </div>

                    <p class="prod-section-title prod-section-sep"><i class="fa fa-tags"></i> Atributos del producto</p>
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="id_color" class="label-optional">Color <span class="badge-optional">Opcional</span></label>
                                <div class="input-group">
                                    <select class="form-control" id="id_color" name="id_color">
                                        <option value="">-- Selecciona un color --</option>
                                        <?php foreach ($colores as $color): ?>
                                            <option value="<?php echo $color->id_color; ?>"><?php echo htmlspecialchars($color->nombre_color, ENT_QUOTES); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-sm" id="btn_nuevo_color" title="Crear nuevo color"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="id_temporada" class="label-optional">Temporada <span class="badge-optional">Opcional</span></label>
                                <div class="input-group">
                                    <select class="form-control" id="id_temporada" name="id_temporada">
                                        <option value="">-- Selecciona una temporada --</option>
                                        <?php foreach ($temporadas as $temp): ?>
                                            <option value="<?php echo $temp->id_temporada; ?>"><?php echo htmlspecialchars($temp->nombre_temporada, ENT_QUOTES); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-sm" id="btn_nueva_temporada" title="Crear nueva temporada"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="genero" class="label-optional">Género <span class="badge-optional">Opcional</span></label>
                                <div class="input-group">
                                    <select class="form-control" id="genero" name="genero">
                                        <option value="NA">-- Sin especificar --</option>
                                        <?php foreach ($generos as $g): ?>
                                            <option value="<?php echo $g; ?>"><?php echo htmlspecialchars($g, ENT_QUOTES); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="input-group-btn">
                                        <a class="btn btn-default btn-sm" href="<?php echo base_url('genero/lista'); ?>" title="Gestionar géneros"><i class="fa fa-plus"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:12px;">
                        <div class="col-sm-12 col-md-4" id="talla_col">
                            <div class="form-group">
                                <label for="talla" class="label-optional">Talla <span class="badge-optional">Opcional</span></label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars(set_value('talla'), ENT_QUOTES); ?>" id="talla" name="talla" maxlength="50" placeholder="Vacío = NA · Ej: M, G, 28" />
                                <small class="form-text text-muted">Ignorado si activas variantes por talla.</small>
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
                                <small class="form-text text-muted"><i class="fa fa-info-circle"></i> JPG, PNG o GIF · Máx. 15 MB · Se comprimirá y orientará automáticamente.</small>
                            </div>
                        </div>
                    </div>

                </div><!-- /prod-form-card-body -->
                <div class="prod-form-card-footer">
                    <button type="button" class="btn btn-success" id="btn_agregar_producto">
                        <i class="fa fa-plus"></i> Registrar producto
                    </button>
                    <button type="button" class="btn btn-default" onclick="$('#addProducto')[0].reset();$('#id_categoria').val('');$('#search_categoria').val('');$('#usar_codigo_generado').val(0);clearFieldErrors();resetVariantes();">
                        <i class="fa fa-eraser"></i> Limpiar campos
                    </button>
                </div>
                </form>

            </div><!-- /prod-form-card -->
        </div><!-- /col-md-12 -->
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
<script src="<?php echo base_url(); ?>assets/js/zebra-labels.js?v=<?php echo @filemtime(FCPATH . 'assets/js/zebra-labels.js'); ?>"></script>

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

        $(document).on('click', '.categoria-list li', function() {
            var selectedValue = $(this).attr('data-value');
            var selectedText = $(this).text();

            $('#id_categoria').val(selectedValue);
            $('#search_categoria').val(selectedText);
            $('.categoria-list').hide(); // Ocultar la lista después de seleccionar un elemento
            
            // Cargar subcategorías según la categoría seleccionada
            cargarSubcategorias(selectedValue, '');
        });

        /**
         * Carga subcategorías por categoría via AJAX
         */
        function cargarSubcategorias(id_categoria, selected_subcategoria) {
            if (!id_categoria || parseInt(id_categoria) <= 0) {
                $('#id_subcategoria').html('<option value="">-- Selecciona una subcategoría --</option>');
                return;
            }

            var subcategoriaActual = typeof selected_subcategoria !== 'undefined' ? selected_subcategoria : $('#id_subcategoria').val();
            
            $.ajax({
                url: '<?php echo base_url("producto/get_subcategorias_ajax"); ?>',
                method: 'POST',
                dataType: 'json',
                data: { id_categoria: id_categoria },
                success: function(subcategorias) {
                    var html = '<option value="">-- Selecciona una subcategoría --</option>';
                    
                    if (subcategorias && subcategorias.length > 0) {
                        $.each(subcategorias, function(i, subcategoria) {
                            html += '<option value="' + subcategoria.id_subcategoria + '">' + 
                                    htmlEscape(subcategoria.nombre_subcategoria) + '</option>';
                        });
                    } else {
                        html = '<option value="">-- Sin subcategorías disponibles --</option>';
                    }
                    
                    $('#id_subcategoria').html(html);
                    if (subcategoriaActual) {
                        $('#id_subcategoria').val(subcategoriaActual);
                    }
                    // Marcar subcategoría como requerida si existen opciones
                    var hasOpts = subcategorias && subcategorias.length > 0;
                    $('#lbl_subcategoria')
                        .toggleClass('label-required', hasOpts)
                        .toggleClass('label-optional', !hasOpts)
                        .html(hasOpts ? 'Subcategoría' : 'Subcategoría <span class="badge-optional">Opcional</span>');
                },
                error: function() {
                    $('#id_subcategoria').html('<option value="">-- Error cargando subcategorías --</option>');
                }
            });
        }

        function renderCategoriaLista(categorias) {
            var html = '';
            $.each(categorias || [], function(i, categoria) {
                html += '<li data-value="' + categoria.id_categoria + '">' + htmlEscape(categoria.nombre_categoria) + '</li>';
            });
            $('.categoria-list').html(html);
        }

        function renderSimpleSelect($select, items, placeholder, valueKey, textKey, selectedValue) {
            var html = '<option value="">' + placeholder + '</option>';
            $.each(items || [], function(i, item) {
                html += '<option value="' + htmlEscape(item[valueKey]) + '">' + htmlEscape(item[textKey]) + '</option>';
            });
            $select.html(html);
            if (selectedValue) {
                $select.val(selectedValue);
            }
        }

        function renderGeneroSelect(generos, selectedValue) {
            var html = '<option value="NA">-- Sin especificar --</option>';
            $.each(generos || [], function(i, genero) {
                if (genero !== 'NA') {
                    html += '<option value="' + htmlEscape(genero) + '">' + htmlEscape(genero) + '</option>';
                }
            });
            $('#genero').html(html);
            if (selectedValue && $('#genero option[value="' + selectedValue + '"]').length) {
                $('#genero').val(selectedValue);
            } else {
                $('#genero').val('NA');
            }
        }

        function refreshCatalogos() {
            var currentCategoriaId = $('#id_categoria').val();
            var currentCategoriaText = $.trim($('#search_categoria').val());
            var currentTemporada = $('#id_temporada').val();
            var currentColor = $('#id_color').val();
            var currentGenero = $('#genero').val();

            $.ajax({
                url: '<?php echo base_url("producto/get_catalogos_ajax"); ?>',
                method: 'POST',
                dataType: 'json',
                success: function(resp) {
                    renderCategoriaLista(resp.categorias || []);
                    renderSimpleSelect($('#id_temporada'), resp.temporadas || [], '-- Selecciona una temporada --', 'id_temporada', 'nombre_temporada', currentTemporada);
                    renderSimpleSelect($('#id_color'), resp.colores || [], '-- Selecciona un color --', 'id_color', 'nombre_color', currentColor);
                    renderGeneroSelect(resp.generos || [], currentGenero);

                    if (currentCategoriaId) {
                        var categoriaEncontrada = null;
                        $.each(resp.categorias || [], function(i, categoria) {
                            if (String(categoria.id_categoria) === String(currentCategoriaId)) {
                                categoriaEncontrada = categoria;
                                return false;
                            }
                        });

                        if (categoriaEncontrada) {
                            $('#search_categoria').val(categoriaEncontrada.nombre_categoria);
                            $('#id_categoria').val(categoriaEncontrada.id_categoria);
                            cargarSubcategorias(categoriaEncontrada.id_categoria);
                        } else {
                            $('#search_categoria').val('');
                            $('#id_categoria').val('');
                            $('#id_subcategoria').html('<option value="">-- Selecciona una subcategoría --</option>');
                        }
                    } else {
                        $('#id_categoria').val('');
                        if (currentCategoriaText) {
                            $('#id_subcategoria').html('<option value="">-- Selecciona una subcategoría --</option>');
                        }
                    }
                }
            });
        }

        refreshCatalogos();
        $(window).on('focus', function() {
            refreshCatalogos();
        });
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                refreshCatalogos();
            }
        });
        // Polling eliminado: refrescamos solo en focus de ventana y al crear ítems desde modales.

        $('#btn_nueva_categoria').on('click', function() {
            $('#modal_nueva_categoria').addClass('active');
            $('#nueva_categoria_nombre').val('').focus();
        });

        $('#btn_cancelar_categoria').on('click', function() {
            $('#modal_nueva_categoria').removeClass('active');
            $('#nueva_categoria_nombre').val('');
        });

        $('#btn_crear_categoria').on('click', function() {
            var nombre = $.trim($('#nueva_categoria_nombre').val());
            if (!nombre) {
                showToast('warning', 'Campo requerido', 'Ingresa un nombre para la categoría');
                return;
            }

            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: '<?php echo base_url("categoria/crear_ajax"); ?>',
                method: 'POST',
                dataType: 'json',
                data: {
                    nombre_categoria: nombre
                },
                success: function(response) {
                    btn.prop('disabled', false).html('Crear');
                    if (response.success) {
                        $('.categoria-list').prepend('<li data-value="' + response.id_categoria + '">' + htmlEscape(response.nombre_categoria) + '</li>');
                        $('#id_categoria').val(response.id_categoria);
                        $('#search_categoria').val(response.nombre_categoria);
                        cargarSubcategorias(response.id_categoria, '');
                        $('#modal_nueva_categoria').removeClass('active');
                        $('#nueva_categoria_nombre').val('');
                        showToast('success', 'Categoría creada', response.message);
                    } else {
                        showToast('error', 'Error', response.message);
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('Crear');
                    showToast('error', 'Error de conexión', 'No se pudo crear la categoría');
                }
            });
        });
        
        // Helper para escapar HTML
        function htmlEscape(text) {
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

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
                id_subcategoria: '#id_subcategoria',
                imagen:          '#imagen',
                talla:           '#talla',
                detalles:        '#detalles',
                tiene_variantes: '#tiene_variantes'
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

        // ── Variantes (tallas) ───────────────────────────────────────────────
        var varianteRowIdx = 0;
        function addVarianteRow() {
            varianteRowIdx++;
            var i = varianteRowIdx;
            var html = ''
                + '<tr data-row="' + i + '">'
                + '  <td><input type="text"   class="form-control input-sm v-talla" name="variantes[' + i + '][talla]" maxlength="20" placeholder="22"></td>'
                + '  <td><input type="number" class="form-control input-sm v-stock" name="variantes[' + i + '][stock]" min="0" placeholder="0"></td>'
                + '  <td><input type="number" class="form-control input-sm v-pc"    name="variantes[' + i + '][precio_compra]" min="0" step="0.01" placeholder="0.00"></td>'
                + '  <td><input type="number" class="form-control input-sm v-pv"    name="variantes[' + i + '][precio_venta]"  min="0" step="0.01" placeholder="0.00"></td>'
                + '  <td style="text-align:center;"><button type="button" class="btn btn-xs btn-danger v-remove"><i class="fa fa-times"></i></button></td>'
                + '</tr>';
            $('#variantes_tbody').append(html);
        }

        function toggleGlobalFields(activo) {
            $('#variantes_panel').toggle(activo);
            $('#stock_global_col, #talla_col, #precio_compra_col, #precio_venta_col').toggle(!activo);
        }

        function resetVariantes() {
            varianteRowIdx = 0;
            $('#variantes_tbody').empty();
            $('#tiene_variantes').prop('checked', false);
            toggleGlobalFields(false);
        }

        $('#tiene_variantes').on('change', function() {
            var activo = this.checked;
            toggleGlobalFields(activo);
            if (activo && $('#variantes_tbody tr').length === 0) addVarianteRow();
        });
        $('#btn_add_variante').on('click', addVarianteRow);
        $('#variantes_tbody').on('click', '.v-remove', function() {
            $(this).closest('tr').remove();
            // Mantener el panel abierto y al menos una fila editable
            if ($('#tiene_variantes').is(':checked') && $('#variantes_tbody tr').length === 0) {
                addVarianteRow();
            }
        });

        // ── Validación client-side antes de enviar ───────────────────────────
        function validarFormulario() {
            var errores = {};
            var conVariantes = $('#tiene_variantes').is(':checked');
            if (!$.trim($('#nombre_producto').val())) errores.nombre_producto = 'El nombre es obligatorio.';
            if (!$('#id_categoria').val()) errores.id_categoria = 'Selecciona una categoría.';
            if (!conVariantes) {
                var pc = parseFloat($('#precio_compra').val());
                var pv = parseFloat($('#precio_venta').val());
                if (!$.trim($('#precio_compra').val()) || isNaN(pc) || pc < 0) errores.precio_compra = 'Ingresa un precio de compra válido.';
                if (!$.trim($('#precio_venta').val()) || isNaN(pv) || pv <= 0) errores.precio_venta = 'El precio de venta debe ser mayor a cero.';
                if (!errores.precio_compra && !errores.precio_venta && pv < pc) errores.precio_venta = 'El precio de venta no puede ser menor al de compra (margen negativo).';
                if ($('#stock').val() === '' || parseInt($('#stock').val()) < 0) errores.stock = 'El stock debe ser 0 o más.';
            }
            // Subcategoría obligatoria si hay opciones cargadas
            var $sub = $('#id_subcategoria');
            if ($sub.find('option').length > 1 && !$sub.val()) {
                errores.id_subcategoria = 'Selecciona una subcategoría.';
            }
            if (!$.trim($('#codigo_proveedor').val())) errores.codigo_proveedor = 'Escanea un código o usa el botón "Generar".';

            if (conVariantes) {
                var filas = $('#variantes_tbody tr');
                if (filas.length === 0) { errores.tiene_variantes = 'Agrega al menos una talla.'; }
                var tallas = {};
                filas.each(function() {
                    var $r = $(this);
                    var t  = $.trim($r.find('.v-talla').val()).toUpperCase();
                    var pc = $.trim($r.find('.v-pc').val());
                    var pv = $.trim($r.find('.v-pv').val());
                    var st = $.trim($r.find('.v-stock').val());
                    if (!t)                          { errores.tiene_variantes = 'Talla obligatoria en todas las filas.'; return; }
                    if (tallas[t])                   { errores.tiene_variantes = 'Talla duplicada: ' + t; return; }
                    if (pc === '' || isNaN(pc) || parseFloat(pc) < 0)  { errores.tiene_variantes = 'Precio de compra inválido en talla ' + t; return; }
                    if (pv === '' || isNaN(pv) || parseFloat(pv) <= 0) { errores.tiene_variantes = 'Precio de venta debe ser > 0 en talla ' + t; return; }
                    if (st === '' || isNaN(st) || parseInt(st) < 0)    { errores.tiene_variantes = 'Stock inválido en talla ' + t; return; }
                    tallas[t] = true;
                });
            }
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
                        } else {
                            showToast('success', 'Producto registrado', response.message, 6000);
                        }
                        form[0].reset();
                        $('#id_categoria').val('');
                        $('#search_categoria').val('');
                        $('#usar_codigo_generado').val(0);
                        resetVariantes();
                        $('#codigo_proveedor').focus();
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
        var defaultSettings = (typeof ZebraLabels !== 'undefined' && ZebraLabels.DEFAULT_SETTINGS)
            ? Object.assign({}, ZebraLabels.DEFAULT_SETTINGS)
            : {
                width: 39, height: 16, padding: 1, barcodeHeight: 6.5,
                fontName: 1.8, fontPrice: 2.3, fontCode: 1.5,
                showName: true, showPrice: true, showCodeText: true
            };
        var currentSettings = Object.assign({}, defaultSettings);
        var currentProduct = null;
        var currentCurrencySymbol = '<?php echo htmlspecialchars($configuracionInfo->simbolo_moneda ?? "$", ENT_QUOTES); ?>';

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
                } else {
                    currentSettings = Object.assign({}, defaultSettings);
                }
            } catch (e) {
                console.log('Error loading label settings:', e.message);
                currentSettings = Object.assign({}, defaultSettings);
            }
        }

        function showLabelModal(producto) {
            currentProduct = producto;
            $('#labelModalTitle').text('✓ ' + producto.nombre_producto);
            $('#labelModalProduct').text('Código: ' + producto.codigo + ' | Precio: ' + currentCurrencySymbol + ' ' + parseFloat(producto.precio_venta).toFixed(2) + ' | Stock: ' + (parseInt(producto.stock) || 0));
            $('#labelQuantity').val(1);
            $('#labelModal').addClass('active');

            loadLabelSettings();
            try {
                renderLabelPreview(producto);
            } catch (e) {
                console.error('[Producto etiqueta] No se pudo renderizar el preview:', e);
                $('#labelPreviewBox').html('<div class="prod-preview-placeholder" style="padding:12px;text-align:center;">Se guardó el producto, pero no se pudo generar la vista previa de la etiqueta.</div>');
            }
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

            var scaleHost = document.createElement('div');
            scaleHost.style.width = currentSettings.width + 'mm';
            scaleHost.style.height = currentSettings.height + 'mm';
            scaleHost.style.transform = 'scale(' + previewScale + ')';
            scaleHost.style.transformOrigin = 'center center';
            scaleHost.style.flexShrink = '0';

            if (typeof ZebraLabels !== 'undefined' && ZebraLabels.buildPreviewNode) {
                var previewSettings = Object.assign({}, currentSettings);
                previewSettings.yOffset = (currentSettings.yOffset || 0) - 1;
                scaleHost.appendChild(ZebraLabels.buildPreviewNode(producto, previewSettings, currentCurrencySymbol, { border: '1px solid #bbb' }));
            } else {
                scaleHost.innerHTML = '<div class="prod-preview-placeholder" style="padding:12px;text-align:center;">No se cargó el módulo de etiquetas.</div>';
            }
            previewWrap.appendChild(scaleHost);
            previewBox.appendChild(previewWrap);

            if (typeof ZebraLabels !== 'undefined' && ZebraLabels.renderPreviewBarcodes) {
                ZebraLabels.renderPreviewBarcodes(previewBox);
            }
        }

        function printLabel() {
            if (!currentProduct) return;

            var quantity = parseInt($('#labelQuantity').val()) || 1;
            var btn = document.getElementById('btnPrintLabel');
            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Enviando...'; }

            if (typeof ZebraLabels === 'undefined' || !ZebraLabels.buildLabelZPL) {
                zebraLog('No se pudo imprimir: el módulo de etiquetas no está disponible.', 'error');
                resetPrintBtn(btn);
                return;
            }

            var allZpl = ZebraLabels.buildLabelZPL(currentProduct, currentSettings, quantity, currentCurrencySymbol);

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
            btn.innerHTML = '<i class="fa fa-print"></i> Imprimir Etiqueta(s)';
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

        // ──────────────────────────────────────────────────────────────────
        // MODALES INLINE: Crear nueva subcategoría, color, temporada
        // ──────────────────────────────────────────────────────────────────

        // Modal: Nueva Subcategoría
        $('#btn_nueva_subcategoria').on('click', function() {
            if (!$('#id_categoria').val()) {
                showToast('warning', 'Selecciona una categoría', 'Primero debes seleccionar una categoría');
                return;
            }
            $('#modal_nueva_subcategoria').addClass('active');
            $('#nueva_subcategoria_nombre').val('').focus();
        });

        $('#btn_cancelar_subcategoria').on('click', function() {
            $('#modal_nueva_subcategoria').removeClass('active');
            $('#nueva_subcategoria_nombre').val('');
        });

        $('#btn_crear_subcategoria').on('click', function() {
            var nombre = $.trim($('#nueva_subcategoria_nombre').val());
            if (!nombre) {
                showToast('warning', 'Campo requerido', 'Ingresa un nombre para la subcategoría');
                return;
            }

            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: '<?php echo base_url("subcategoria/crear_ajax"); ?>',
                method: 'POST',
                dataType: 'json',
                data: {
                    id_categoria: $('#id_categoria').val(),
                    nombre: nombre
                },
                success: function(response) {
                    btn.prop('disabled', false).html('Crear');
                    if (response.success) {
                        // Agregar opción al select
                        $('#id_subcategoria').append('<option value="' + response.id_subcategoria + '">' + htmlEscape(response.nombre_subcategoria) + '</option>');
                        $('#id_subcategoria').val(response.id_subcategoria);
                        $('#modal_nueva_subcategoria').removeClass('active');
                        $('#nueva_subcategoria_nombre').val('');
                        showToast('success', 'Subcategoría creada', response.message);
                    } else {
                        showToast('error', 'Error', response.message);
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('Crear');
                    showToast('error', 'Error de conexión', 'No se pudo crear la subcategoría');
                }
            });
        });

        // Modal: Nuevo Color
        $('#btn_nuevo_color').on('click', function() {
            $('#modal_nuevo_color').addClass('active');
            $('#nuevo_color_nombre').val('').focus();
        });

        $('#btn_cancelar_color').on('click', function() {
            $('#modal_nuevo_color').removeClass('active');
        });

        $('#btn_crear_color').on('click', function() {
            var nombre = $.trim($('#nuevo_color_nombre').val());
            if (!nombre) {
                showToast('warning', 'Campo requerido', 'Ingresa un nombre para el color');
                return;
            }

            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: '<?php echo base_url("color/crear_ajax"); ?>',
                method: 'POST',
                dataType: 'json',
                data: {
                    nombre: nombre
                },
                success: function(response) {
                    btn.prop('disabled', false).html('Crear');
                    if (response.success) {
                        $('#id_color').append('<option value="' + response.id_color + '">' + htmlEscape(response.nombre_color) + '</option>');
                        $('#id_color').val(response.id_color);
                        $('#modal_nuevo_color').removeClass('active');
                        $('#nuevo_color_nombre').val('');
                        showToast('success', 'Color creado', response.message);
                    } else {
                        showToast('error', 'Error', response.message);
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('Crear');
                    showToast('error', 'Error de conexión', 'No se pudo crear el color');
                }
            });
        });

        // Modal: Nueva Temporada
        $('#btn_nueva_temporada').on('click', function() {
            $('#modal_nueva_temporada').addClass('active');
            $('#nueva_temporada_nombre').val('').focus();
        });

        $('#btn_cancelar_temporada').on('click', function() {
            $('#modal_nueva_temporada').removeClass('active');
        });

        $('#btn_crear_temporada').on('click', function() {
            var nombre = $.trim($('#nueva_temporada_nombre').val());
            if (!nombre) {
                showToast('warning', 'Campo requerido', 'Ingresa un nombre para la temporada');
                return;
            }

            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: '<?php echo base_url("temporada/crear_ajax"); ?>',
                method: 'POST',
                dataType: 'json',
                data: {
                    nombre: nombre,
                    descripcion: $.trim($('#nueva_temporada_descripcion').val())
                },
                success: function(response) {
                    btn.prop('disabled', false).html('Crear');
                    if (response.success) {
                        $('#id_temporada').append('<option value="' + response.id_temporada + '">' + htmlEscape(response.nombre_temporada) + '</option>');
                        $('#id_temporada').val(response.id_temporada);
                        $('#modal_nueva_temporada').removeClass('active');
                        $('#nueva_temporada_nombre').val('');
                        $('#nueva_temporada_descripcion').val('');
                        showToast('success', 'Temporada creada', response.message);
                    } else {
                        showToast('error', 'Error', response.message);
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('Crear');
                    showToast('error', 'Error de conexión', 'No se pudo crear la temporada');
                }
            });
        });

        // Cerrar modales al hacer clic fuera
        $(document).on('click', function(event) {
            if ($(event.target).hasClass('modal-inline')) {
                $('.modal-inline').removeClass('active');
            }
        });
    });
</script>

<!-- Modales Inline -->

<!-- Modal: Nueva Categoría -->
<div id="modal_nueva_categoria" class="modal-inline">
    <div class="modal-inline-content">
        <div class="modal-inline-header">
            <i class="fa fa-tags text-primary"></i> Nueva Categoría
        </div>
        <div class="modal-inline-body">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" class="form-control" id="nueva_categoria_nombre" placeholder="Ej: Camisas, Pantalones..." maxlength="200">
            </div>
        </div>
        <div class="modal-inline-footer">
            <button type="button" class="btn-secondary" id="btn_cancelar_categoria">Cancelar</button>
            <button type="button" class="btn-primary" id="btn_crear_categoria">Crear</button>
        </div>
    </div>
</div>

<!-- Modal: Nueva Subcategoría -->
<div id="modal_nueva_subcategoria" class="modal-inline">
    <div class="modal-inline-content">
        <div class="modal-inline-header">
            <i class="fa fa-sitemap text-primary"></i> Nueva Subcategoría
        </div>
        <div class="modal-inline-body">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" class="form-control" id="nueva_subcategoria_nombre" placeholder="Ej: Camisetas, Pantalones..." maxlength="200">
            </div>
        </div>
        <div class="modal-inline-footer">
            <button type="button" class="btn-secondary" id="btn_cancelar_subcategoria">Cancelar</button>
            <button type="button" class="btn-primary" id="btn_crear_subcategoria">Crear</button>
        </div>
    </div>
</div>

<!-- Modal: Nuevo Color -->
<div id="modal_nuevo_color" class="modal-inline">
    <div class="modal-inline-content">
        <div class="modal-inline-header">
            <i class="fa fa-paint-brush text-success"></i> Nuevo Color
        </div>
        <div class="modal-inline-body">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" class="form-control" id="nuevo_color_nombre" placeholder="Ej: Rojo Oscuro" maxlength="50">
            </div>
            <div class="alert alert-info" style="margin:0;">
                <i class="fa fa-info-circle"></i> Solo escribe el nombre del color. El detalle técnico ya no es necesario.
            </div>
        </div>
        <div class="modal-inline-footer">
            <button type="button" class="btn-secondary" id="btn_cancelar_color">Cancelar</button>
            <button type="button" class="btn-primary" id="btn_crear_color">Crear</button>
        </div>
    </div>
</div>

<!-- Modal: Nueva Temporada -->
<div id="modal_nueva_temporada" class="modal-inline">
    <div class="modal-inline-content">
        <div class="modal-inline-header">
            <i class="fa fa-calendar text-info"></i> Nueva Temporada
        </div>
        <div class="modal-inline-body">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" class="form-control" id="nueva_temporada_nombre" placeholder="Ej: Navidad 2026" maxlength="100">
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <input type="text" class="form-control" id="nueva_temporada_descripcion" placeholder="Opcional" maxlength="255">
            </div>
        </div>
        <div class="modal-inline-footer">
            <button type="button" class="btn-secondary" id="btn_cancelar_temporada">Cancelar</button>
            <button type="button" class="btn-primary" id="btn_crear_temporada">Crear</button>
        </div>
    </div>
</div>
