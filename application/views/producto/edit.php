<?php
$id_producto      = $productoInfo->id_producto;
$nombre_producto  = $productoInfo->nombre_producto;
$precio_compra    = $productoInfo->precio_compra;
$precio_venta     = $productoInfo->precio_venta;
$codigo           = $productoInfo->codigo;
$detalles         = $productoInfo->detalles;
$talla            = $productoInfo->talla;
$id_categoria     = $productoInfo->categoria;
$stock_actual     = isset($productoInfo->stock) ? (int)$productoInfo->stock : 0;
$tiene_variantes  = !empty($productoInfo->tiene_variantes) ? 1 : 0;
$variantes        = isset($variantes) ? $variantes : [];
$nombre_categoria = '';
foreach ($categorias as $cat) {
    if ($cat->id_categoria == $id_categoria) { $nombre_categoria = $cat->nombre_categoria; break; }
}
$puede_ver_pc = !empty($permisos['ver_precio_compra']);
?>
<style>
.prod-edit-wrapper{padding:16px 20px;}
.prod-form-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden;}
.prod-form-card-header{padding:14px 18px;border-bottom:1px solid #ecf0f1;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;}
.prod-form-card-header h4{margin:0;font-size:15px;font-weight:700;color:#2c3e50;display:flex;align-items:center;gap:8px;}
.prod-form-card-body{padding:18px;}
.prod-form-card-footer{padding:14px 18px;border-top:1px solid #ecf0f1;background:#f8f9fa;display:flex;gap:8px;align-items:center;flex-wrap:wrap;}
.prod-section-title{font-size:11px;font-weight:700;color:#95a5a6;text-transform:uppercase;letter-spacing:.6px;margin:0 0 12px;padding-bottom:6px;border-bottom:1px solid #ecf0f1;display:flex;align-items:center;gap:6px;}
.prod-section-sep{margin-top:18px;}
.label-required::after{content:' *';color:#e74c3c;font-weight:700;}
.badge-optional{font-size:10px;font-weight:400;background:#ecf0f1;color:#7f8c8d;border-radius:3px;padding:1px 5px;margin-left:4px;vertical-align:middle;}

/* Buscador categoría */
.custom-select{position:relative;}
.search-input{width:100%;padding:6px 10px;border:1px solid #ccc;border-radius:4px;}
.categoria-list{list-style:none;padding:0;margin:0;position:absolute;width:100%;max-height:180px;overflow-y:auto;border:1px solid #ccc;border-radius:4px;background:#fff;z-index:1000;display:none;box-shadow:0 4px 12px rgba(0,0,0,.08);}
.categoria-list li{padding:7px 10px;cursor:pointer;font-size:13px;}
.categoria-list li:hover{background:#f2f7fb;}

/* Sidebar info */
.prod-info-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden;}
.prod-info-card-header{padding:12px 16px;border-bottom:1px solid #ecf0f1;background:#f8f9fa;font-size:13px;font-weight:700;color:#2c3e50;display:flex;align-items:center;gap:8px;}
.prod-info-card-body{padding:14px 16px;font-size:13px;color:#555;}
.prod-info-row{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px dashed #eee;}
.prod-info-row:last-child{border-bottom:none;}
.prod-info-row .lbl{color:#888;font-size:12px;}
.prod-info-row .val{font-weight:600;color:#2c3e50;font-size:12px;}

/* Variantes */
.var-box{background:#fff8e6;border:1px solid #ffe9a8;border-radius:6px;padding:10px 12px;}
.var-panel{display:none;margin-top:12px;background:#fff;border:1px dashed #ddd;border-radius:6px;padding:10px;}
.var-row-removed input{text-decoration:line-through;color:#999;background:#fafafa;}

/* Errores */
.form-group.has-error .form-control,
.form-group.has-error .search-input{border-color:#e74c3c;box-shadow:inset 0 1px 1px rgba(231,76,60,.075),0 0 0 3px rgba(231,76,60,.1);}
.form-group.has-error .field-error-msg{color:#e74c3c;font-size:12px;margin-top:4px;display:flex;align-items:center;gap:4px;}

/* Toasts */
#toast-container-custom{position:fixed;top:20px;right:20px;z-index:99999;display:flex;flex-direction:column;gap:8px;max-width:380px;}
.toast-msg{display:flex;align-items:flex-start;gap:10px;padding:14px 16px;border-radius:6px;box-shadow:0 4px 16px rgba(0,0,0,.18);font-size:14px;line-height:1.4;background:#fff;cursor:pointer;animation:toast-in .25s ease;}
.toast-msg.toast-error{border-left:4px solid #e74c3c;}
.toast-msg.toast-success{border-left:4px solid #27ae60;}
.toast-msg.toast-warning{border-left:4px solid #f39c12;}
.toast-icon{font-size:18px;flex-shrink:0;margin-top:1px;}
.toast-body{flex:1;}
.toast-body strong{display:block;margin-bottom:2px;}
.toast-close{background:none;border:none;font-size:16px;color:#999;cursor:pointer;padding:0;line-height:1;}
@keyframes toast-in{from{opacity:0;transform:translateX(30px);}to{opacity:1;transform:translateX(0);}}

@media(max-width:768px){.prod-edit-wrapper{padding:10px;}}
</style>

<div id="toast-container-custom"></div>

<div class="content-wrapper">
<div class="prod-edit-wrapper">

    <!-- Encabezado -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-pencil-square-o text-primary"></i> Editar producto
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;">Actualiza la información del producto seleccionado</p>
        </div>
        <a class="btn btn-default btn-sm" href="<?php echo base_url(); ?>producto/producto_lista">
            <i class="fa fa-arrow-left"></i> Volver al catálogo
        </a>
    </div>

    <?php $this->load->helper('form'); ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissable" style="border-radius:6px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissable" style="border-radius:6px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger" style="border-radius:6px;">', '</div>'); ?>

    <div class="row">
        <!-- ── Columna izquierda: formulario ── -->
        <div class="col-md-8">
            <div class="prod-form-card">
                <div class="prod-form-card-header">
                    <h4><i class="fa fa-cube text-primary"></i> Datos del producto</h4>
                    <small style="color:#95a5a6;"><span style="color:#e74c3c;font-weight:700;">*</span> Campo obligatorio</small>
                </div>

                <form role="form" id="editProducto" action="<?php echo base_url(); ?>producto/editProducto" method="post">
                    <input type="hidden" name="id_producto" id="id_producto" value="<?php echo (int)$id_producto; ?>" />
                    <input type="hidden" name="id_categoria" id="id_categoria" value="<?php echo htmlspecialchars($id_categoria, ENT_QUOTES); ?>" />

                    <div class="prod-form-card-body">

                        <p class="prod-section-title"><i class="fa fa-info-circle"></i> Información básica</p>
                        <div class="row">
                            <div class="col-sm-12 col-md-7">
                                <div class="form-group">
                                    <label for="nombre_producto" class="label-required">Nombre del producto</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($nombre_producto, ENT_QUOTES); ?>" id="nombre_producto" name="nombre_producto" maxlength="200" required />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-5">
                                <div class="form-group custom-select">
                                    <label for="search_categoria" class="label-required">Categoría</label>
                                    <input type="text" class="search-input form-control" id="search_categoria" placeholder="Buscar categoría…" autocomplete="off" value="<?php echo htmlspecialchars($nombre_categoria, ENT_QUOTES); ?>" />
                                    <ul class="categoria-list">
                                        <?php foreach ($categorias as $cat): ?>
                                            <li data-value="<?php echo $cat->id_categoria; ?>"><?php echo htmlspecialchars($cat->nombre_categoria, ENT_QUOTES); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4" id="talla_col">
                                <div class="form-group">
                                    <label for="talla" class="label-optional">Talla <span class="badge-optional">Opcional</span></label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($talla, ENT_QUOTES); ?>" id="talla" name="talla" maxlength="50" placeholder="Ej: Único, M, G, 28, NA" />
                                    <small class="form-text text-muted">Vacío = "NA". Se ignora si activas variantes.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Variantes -->
                        <div class="row" style="margin-top:4px;">
                            <div class="col-sm-12">
                                <div class="var-box">
                                    <label style="margin:0;font-weight:600;color:#7a5d00;cursor:pointer;">
                                        <input type="checkbox" id="tiene_variantes" name="tiene_variantes" value="1" <?php echo $tiene_variantes ? 'checked' : ''; ?> style="vertical-align:middle;margin-right:6px;">
                                        Producto con tallas (corrida) — manejar stock por talla
                                    </label>
                                    <small style="display:block;color:#8a7a3d;margin-top:4px;">
                                        Si desactivas esta opción y el producto ya tiene ventas con talla, las tallas no se borran (se desactivan).
                                    </small>

                                    <div id="variantes_panel" class="var-panel" style="display:<?php echo $tiene_variantes ? 'block' : 'none'; ?>;">
                                        <div class="table-responsive">
                                        <table class="table table-condensed" style="margin:0;">
                                            <thead>
                                                <tr style="background:#f5f5f5;">
                                                    <th style="width:18%;">Talla *</th>
                                                    <th style="width:18%;">Stock *</th>
                                                    <th style="width:18%;">Precio compra *</th>
                                                    <th style="width:18%;">Precio venta *</th>
                                                    <th style="width:14%;">Estado</th>
                                                    <th style="width:14%;text-align:center;">Quitar</th>
                                                </tr>
                                            </thead>
                                            <tbody id="variantes_tbody">
                                            <?php foreach ($variantes as $i => $v):
                                                $idx = $i + 1;
                                                $pc  = isset($v->precio_compra) && $v->precio_compra !== null ? $v->precio_compra : '';
                                                $pv  = isset($v->precio_venta)  && $v->precio_venta  !== null ? $v->precio_venta  : '';
                                            ?>
                                                <tr data-row="<?php echo $idx; ?>">
                                                    <td>
                                                        <input type="hidden" name="variantes[<?php echo $idx; ?>][id_variante]" value="<?php echo (int)$v->id_variante; ?>">
                                                        <input type="text" class="form-control input-sm v-talla" name="variantes[<?php echo $idx; ?>][talla]" maxlength="20" value="<?php echo htmlspecialchars($v->talla, ENT_QUOTES); ?>">
                                                    </td>
                                                    <td><input type="number" class="form-control input-sm v-stock" name="variantes[<?php echo $idx; ?>][stock]" min="0" value="<?php echo (int)$v->stock; ?>"></td>
                                                    <td><input type="number" class="form-control input-sm v-pc" name="variantes[<?php echo $idx; ?>][precio_compra]" min="0" step="0.01" value="<?php echo htmlspecialchars((string)$pc, ENT_QUOTES); ?>" <?php echo $puede_ver_pc ? '' : 'readonly'; ?>></td>
                                                    <td><input type="number" class="form-control input-sm v-pv" name="variantes[<?php echo $idx; ?>][precio_venta]" min="0" step="0.01" value="<?php echo htmlspecialchars((string)$pv, ENT_QUOTES); ?>"></td>
                                                    <td><?php echo $v->activo ? '<span class="label label-success">Activa</span>' : '<span class="label label-default">Desactivada</span>'; ?></td>
                                                    <td style="text-align:center;">
                                                        <label style="font-size:11px;color:#c0392b;cursor:pointer;margin:0;">
                                                            <input type="checkbox" class="v-remove-existing" name="variantes[<?php echo $idx; ?>][remove]" value="1"> Quitar
                                                        </label>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        </div>
                                        <button type="button" class="btn btn-default btn-sm" id="btn_add_variante">
                                            <i class="fa fa-plus"></i> Agregar variante
                                        </button>
                                        <small class="form-text text-muted" style="margin-top:6px;">
                                            "Quitar" elimina la talla si nunca tuvo movimientos; en caso contrario la desactiva.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="prod-section-title prod-section-sep"><i class="fa fa-dollar"></i> Precios y stock</p>
                        <div class="row">
                            <div class="col-sm-12 col-md-4" id="precio_compra_col">
                                <div class="form-group">
                                    <label for="precio_compra" class="label-required">
                                        Precio de compra
                                        <?php if ($puede_ver_pc): ?>
                                            <span class="text-success" title="Tienes permiso para ver/editar"><i class="fa fa-check-circle"></i></span>
                                        <?php else: ?>
                                            <span class="text-muted" title="Sin permiso para ver/editar"><i class="fa fa-eye-slash"></i></span>
                                        <?php endif; ?>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input type="number" class="form-control" value="<?php echo htmlspecialchars($precio_compra, ENT_QUOTES); ?>" id="precio_compra" name="precio_compra" min="0" step="0.01" placeholder="0.00" <?php echo $puede_ver_pc ? '' : 'readonly'; ?> />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4" id="precio_venta_col">
                                <div class="form-group">
                                    <label for="precio_venta" class="label-required">Precio de venta</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                                        <input type="number" class="form-control" value="<?php echo htmlspecialchars($precio_venta, ENT_QUOTES); ?>" id="precio_venta" name="precio_venta" min="0.01" step="0.01" placeholder="0.00" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4" id="stock_global_col">
                                <div class="form-group">
                                    <label for="stock">Stock (sucursal actual)</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                                        <input type="number" class="form-control" name="stock" id="stock" value="<?php echo $stock_actual; ?>" min="0" />
                                    </div>
                                    <small class="form-text text-muted">Se ignora si activas variantes por talla.</small>
                                </div>
                            </div>
                        </div>

                        <p class="prod-section-title prod-section-sep"><i class="fa fa-barcode"></i> Código de barras</p>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="codigo" class="label-required">Código de barras</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($codigo, ENT_QUOTES); ?>" id="codigo" name="codigo" maxlength="50" placeholder="Escanea o escribe el código" />
                                </div>
                            </div>
                        </div>

                        <p class="prod-section-title prod-section-sep"><i class="fa fa-file-text-o"></i> Información adicional</p>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="detalles" class="label-optional">Detalles <span class="badge-optional">Opcional</span></label>
                                    <textarea class="form-control" id="detalles" name="detalles" rows="3" maxlength="500" placeholder="Material, color, descripción adicional…"><?php echo htmlspecialchars($detalles, ENT_QUOTES); ?></textarea>
                                </div>
                            </div>
                        </div>

                    </div><!-- /body -->

                    <div class="prod-form-card-footer">
                        <button type="submit" class="btn btn-primary" id="btn_guardar">
                            <i class="fa fa-save"></i> Guardar cambios
                        </button>
                        <a href="<?php echo base_url(); ?>producto/producto_lista" class="btn btn-default">
                            <i class="fa fa-times"></i> Cancelar
                        </a>
                        <a href="<?php echo base_url(); ?>producto/editar_imagen/<?php echo (int)$id_producto; ?>" class="btn btn-info">
                            <i class="fa fa-image"></i> Editar imagen
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- ── Columna derecha: info ── -->
        <div class="col-md-4">
            <div class="prod-info-card">
                <div class="prod-info-card-header">
                    <i class="fa fa-info-circle text-info"></i> Información del producto
                </div>
                <div class="prod-info-card-body">
                    <div class="prod-info-row"><span class="lbl">ID</span><span class="val">#<?php echo (int)$id_producto; ?></span></div>
                    <div class="prod-info-row"><span class="lbl">Categoría actual</span><span class="val"><?php echo htmlspecialchars($nombre_categoria ?: '—', ENT_QUOTES); ?></span></div>
                    <div class="prod-info-row"><span class="lbl">Variantes</span><span class="val"><?php echo $tiene_variantes ? 'Activadas ('.count($variantes).' tallas)' : 'Desactivadas'; ?></span></div>
                    <?php if (!$tiene_variantes): ?>
                        <div class="prod-info-row"><span class="lbl">Stock sucursal</span><span class="val"><?php echo $stock_actual; ?></span></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="prod-info-card" style="margin-top:14px;">
                <div class="prod-info-card-header">
                    <i class="fa fa-lightbulb-o text-warning"></i> Consejos
                </div>
                <div class="prod-info-card-body" style="font-size:12px;color:#555;line-height:1.5;">
                    <p style="margin:0 0 8px;"><strong>Variantes:</strong> útiles para tallas que comparten código de barras.</p>
                    <p style="margin:0 0 8px;"><strong>Stock:</strong> el campo "sucursal actual" sólo afecta a la sucursal en la que estás trabajando.</p>
                    <p style="margin:0;"><strong>Código:</strong> debe ser único en todo el sistema.</p>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script>
$(document).ready(function() {

    // ── Toasts ────────────────────────────────────────────────────────
    function showToast(type, title, body, duration) {
        duration = duration || 4500;
        var icons = { error:'❌', success:'✅', warning:'⚠️' };
        var id = 'toast-' + Date.now();
        var html = '<div class="toast-msg toast-' + type + '" id="' + id + '">' +
                       '<span class="toast-icon">' + icons[type] + '</span>' +
                       '<div class="toast-body"><strong>' + title + '</strong>' + (body || '') + '</div>' +
                       '<button class="toast-close" onclick="$(\'#' + id + '\').remove()">×</button>' +
                   '</div>';
        $('#toast-container-custom').append(html);
        setTimeout(function(){ $('#'+id).fadeOut(300, function(){ $(this).remove(); }); }, duration);
    }

    function clearFieldErrors(){ $('.form-group.has-error').removeClass('has-error'); $('.field-error-msg').remove(); }
    function setFieldError($input, msg){
        var $g = $input.closest('.form-group');
        $g.addClass('has-error');
        if (!$g.find('.field-error-msg').length){
            $g.append('<div class="field-error-msg"><i class="fa fa-exclamation-circle"></i> ' + msg + '</div>');
        }
    }

    // ── Buscador categoría ────────────────────────────────────────────
    var $search = $('#search_categoria');
    var $list   = $('.categoria-list');

    $search.on('input', function() {
        var t = $(this).val().toLowerCase();
        $list.find('li').each(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(t) !== -1);
        });
        $list.show();
    });
    $search.on('focus', function(){ $list.show(); });
    $list.on('click', 'li', function(){
        $('#id_categoria').val($(this).data('value'));
        $search.val($(this).text());
        $list.hide();
        $search.closest('.form-group').removeClass('has-error').find('.field-error-msg').remove();
    });
    $(document).on('click', function(e){
        if (!$(e.target).closest('.custom-select').length) $list.hide();
    });

    // ── Variantes ─────────────────────────────────────────────────────
    var vIdx = $('#variantes_tbody tr').length;
    function addVarianteRow() {
        vIdx++;
        var i = vIdx;
        var html = ''
            + '<tr data-row="' + i + '" data-new="1">'
            + '  <td><input type="hidden" name="variantes[' + i + '][id_variante]" value="0">'
            + '      <input type="text" class="form-control input-sm v-talla" name="variantes[' + i + '][talla]" maxlength="20" placeholder="22"></td>'
            + '  <td><input type="number" class="form-control input-sm v-stock" name="variantes[' + i + '][stock]" min="0" placeholder="0"></td>'
            + '  <td><input type="number" class="form-control input-sm v-pc"    name="variantes[' + i + '][precio_compra]" min="0" step="0.01" placeholder="0.00"></td>'
            + '  <td><input type="number" class="form-control input-sm v-pv"    name="variantes[' + i + '][precio_venta]"  min="0" step="0.01" placeholder="0.00"></td>'
            + '  <td><span class="label label-info">Nueva</span></td>'
            + '  <td style="text-align:center;"><button type="button" class="btn btn-xs btn-danger v-remove-new" title="Quitar fila"><i class="fa fa-times"></i></button></td>'
            + '</tr>';
        $('#variantes_tbody').append(html);
    }

    function toggleGlobalFields(activo) {
        $('#variantes_panel').toggle(activo);
        $('#stock_global_col, #talla_col, #precio_compra_col, #precio_venta_col').toggle(!activo);
    }

    $('#tiene_variantes').on('change', function() {
        var on = this.checked;
        toggleGlobalFields(on);
        if (on && $('#variantes_tbody tr').length === 0) addVarianteRow();
    });
    $('#btn_add_variante').on('click', addVarianteRow);

    // Quitar (filas nuevas: borrar directo)
    $('#variantes_tbody').on('click', '.v-remove-new', function(){
        $(this).closest('tr').remove();
    });
    // Marcar visualmente las filas existentes marcadas para "quitar"
    $('#variantes_tbody').on('change', '.v-remove-existing', function(){
        $(this).closest('tr').toggleClass('var-row-removed', this.checked);
    });

    // Estado inicial
    toggleGlobalFields($('#tiene_variantes').is(':checked'));

    // ── Validación cliente antes de enviar ────────────────────────────
    $('#editProducto').on('submit', function(e){
        clearFieldErrors();
        var ok = true, errores = [];
        var conVar = $('#tiene_variantes').is(':checked');

        if (!$.trim($('#nombre_producto').val())) { setFieldError($('#nombre_producto'),'Obligatorio'); ok=false; errores.push('nombre'); }
        if (!$('#id_categoria').val()) { setFieldError($('#search_categoria'),'Selecciona una categoría'); ok=false; errores.push('categoría'); }
        if (!$.trim($('#codigo').val())) { setFieldError($('#codigo'),'Obligatorio'); ok=false; errores.push('código'); }

        if (!conVar) {
            if ($('#precio_compra').val() === '' || isNaN($('#precio_compra').val())) { setFieldError($('#precio_compra'),'Precio inválido'); ok=false; }
            if ($('#precio_venta').val() === '' || parseFloat($('#precio_venta').val()) <= 0) { setFieldError($('#precio_venta'),'Debe ser > 0'); ok=false; }
            if ($('#stock').val() === '' || parseInt($('#stock').val()) < 0) { setFieldError($('#stock'),'0 o más'); ok=false; }
        } else {
            var filasActivas = $('#variantes_tbody tr').filter(function(){ return !$(this).find('.v-remove-existing').is(':checked'); });
            if (filasActivas.length === 0) { showToast('warning','Sin variantes activas','Agrega al menos una talla o desactiva las variantes.'); ok=false; }
            var tallas = {};
            filasActivas.each(function(){
                var $r = $(this);
                var t  = $.trim($r.find('.v-talla').val()).toUpperCase();
                var pc = $.trim($r.find('.v-pc').val());
                var pv = $.trim($r.find('.v-pv').val());
                var st = $.trim($r.find('.v-stock').val());
                if (!t)           { setFieldError($r.find('.v-talla'), 'Obligatoria'); ok=false; }
                else if (tallas[t]){ setFieldError($r.find('.v-talla'), 'Duplicada'); ok=false; }
                else tallas[t] = true;
                if (pc === '' || isNaN(pc) || parseFloat(pc) < 0)  { setFieldError($r.find('.v-pc'), 'Inválido'); ok=false; }
                if (pv === '' || isNaN(pv) || parseFloat(pv) <= 0) { setFieldError($r.find('.v-pv'), '> 0'); ok=false; }
                if (st === '' || isNaN(st) || parseInt(st) < 0)    { setFieldError($r.find('.v-stock'), '≥ 0'); ok=false; }
            });
        }

        if (!ok) {
            e.preventDefault();
            showToast('warning','Revisa los campos','Hay datos faltantes o inválidos.');
            var $first = $('.form-group.has-error').first();
            if ($first.length) $('html,body').animate({ scrollTop:$first.offset().top - 80 }, 300);
            return false;
        }
        $('#btn_guardar').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando…');
    });
});
</script>
