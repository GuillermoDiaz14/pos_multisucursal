<?php
$id_producto = $productoInfo->id_producto;
$nombre_producto = $productoInfo->nombre_producto;
$precio_compra = $productoInfo->precio_compra;
$precio_venta = $productoInfo->precio_venta;
$codigo = $productoInfo->codigo;
$detalles = $productoInfo->detalles;
$talla = $productoInfo->talla;
$id_categoria = $productoInfo->categoria;
$tiene_variantes = !empty($productoInfo->tiene_variantes) ? 1 : 0;
$variantes = isset($variantes) ? $variantes : [];
$nombre_categoria="";
?>
    <?php foreach ($categorias as $categoria): 
            if($categoria->id_categoria==$id_categoria)
            {
           $nombre_categoria=$categoria->nombre_categoria;
            }
            ?>
            <?php endforeach; ?>
            
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

</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Producto
        <small>Producto / Editar Producto</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Editar Producto</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>producto/editProducto" method="post" id="editProducto" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group custom-select">
                                        <label for="id_categoria">Categoría</label>
                                        <input type="text" class="search-input" id="search_categoria" placeholder="Buscar categoría" value="<?php echo $nombre_categoria; ?>" />
                                        <ul class="categoria-list">
                                            <?php foreach ($categorias as $categoria): ?>
                                                <li data-value="<?php echo $categoria->id_categoria; ?>"><?php echo $categoria->nombre_categoria; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <input type="hidden" value="<?php echo $id_producto; ?>" name="id_producto" id="id_producto" />
                                        <input type="hidden" id="id_categoria" name="id_categoria" value="<?php echo $id_categoria; ?>" />
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="nombre_producto">Producto</label>
                                        <input type="text" class="form-control required" value="<?php echo $nombre_producto; ?>" id="nombre_producto" name="nombre_producto" maxlength="256" />
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4" id="precio_compra_col">
                                    <div class="form-group">
                                        <label for="precio_compra">Precio compra
                                            <?php if (!empty($permisos['ver_precio_compra'])): ?>
                                                <span class="text-success"><i class="fa fa-check-circle"></i></span>
                                            <?php else: ?>
                                                <span class="text-muted" title="Sin permiso para ver precio"><i class="fa fa-eye-slash"></i></span>
                                            <?php endif; ?>
                                        </label>
                                        <input type="number"
                                               class="form-control required"
                                               value="<?php echo $precio_compra; ?>"
                                               id="precio_compra"
                                               name="precio_compra"
                                               maxlength="12"
                                               inputmode="numeric"
                                               pattern="[0-9]+(\.[0-9]+)?"
                                               placeholder="0.00"
                                               <?php echo empty($permisos['ver_precio_compra']) ? 'readonly' : ''; ?> />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4" id="precio_venta_col">
                                    <div class="form-group">
                                        <label for="precio_venta">Precio Venta</label>
                                        <input type="number" class="form-control required" value="<?php echo $precio_venta; ?>" id="precio_venta" name="precio_venta" maxlength="12" inputmode="numeric" pattern="[0-9]+(\.[0-9]+)?" placeholder="0.00" />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="codigo">Código</label>
                                        <input type="text" class="form-control required" value="<?php echo $codigo; ?>" id="codigo" name="codigo" maxlength="50" />
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4" id="talla_col">
                                    <div class="form-group">
                                        <label for="talla">Talla / Valor</label>
                                        <input type="text" class="form-control" value="<?php echo $talla; ?>" id="talla" name="talla" maxlength="50" placeholder="Ej: Único, S, M, L, 28, 38, 40, NA" />
                                        <small class="form-text text-muted">Valores típicos en México; dejar vacío si no aplica (se guardará como 'NA').</small>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4" id="stock_global_col">
                                    <div class="form-group">
                                        <label>Stock (sucursal actual)</label>
                                        <input type="number" class="form-control" name="stock" id="stock" value="<?php echo isset($productoInfo->stock) ? $productoInfo->stock : 0; ?>" min="0">
                                        <small class="text-muted">Se ignora si activas variantes por talla.</small>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="detalles">Detalles</label>
                                        <textarea class="form-control required" id="detalles" name="detalles"><?php echo $detalles; ?></textarea>
                                    </div>
                                </div>

                                <!-- ── Variantes (tallas) ─────────────────────────── -->
                                <div class="col-sm-12">
                                    <div style="background:#fff8e6;border:1px solid #ffe9a8;border-radius:6px;padding:10px 12px;">
                                        <label style="margin:0;font-weight:600;color:#7a5d00;cursor:pointer;">
                                            <input type="checkbox" id="tiene_variantes" name="tiene_variantes" value="1" <?php echo $tiene_variantes ? 'checked' : ''; ?> style="vertical-align:middle;margin-right:6px;">
                                            Producto con tallas (corrida) — manejar stock por talla
                                        </label>
                                        <small style="display:block;color:#8a7a3d;margin-top:4px;">
                                            Si desactivas esta opción y el producto ya tiene ventas con talla, las tallas no se borran (se desactivan).
                                        </small>

                                        <div id="variantes_panel" style="display:<?php echo $tiene_variantes ? 'block' : 'none'; ?>;margin-top:12px;background:#fff;border:1px dashed #ddd;border-radius:6px;padding:10px;">
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
                                                    $pc = isset($v->precio_compra) && $v->precio_compra !== null ? $v->precio_compra : '';
                                                    $pv = isset($v->precio_venta)  && $v->precio_venta  !== null ? $v->precio_venta  : '';
                                                ?>
                                                    <tr data-row="<?php echo $idx; ?>">
                                                        <td>
                                                            <input type="hidden" name="variantes[<?php echo $idx; ?>][id_variante]" value="<?php echo (int)$v->id_variante; ?>">
                                                            <input type="text" class="form-control input-sm" name="variantes[<?php echo $idx; ?>][talla]" maxlength="20" value="<?php echo htmlspecialchars($v->talla, ENT_QUOTES); ?>">
                                                        </td>
                                                        <td><input type="number" class="form-control input-sm" name="variantes[<?php echo $idx; ?>][stock]" min="0" value="<?php echo (int)$v->stock; ?>"></td>
                                                        <td><input type="number" class="form-control input-sm" name="variantes[<?php echo $idx; ?>][precio_compra]" min="0" step="0.01" value="<?php echo htmlspecialchars((string)$pc, ENT_QUOTES); ?>"></td>
                                                        <td><input type="number" class="form-control input-sm" name="variantes[<?php echo $idx; ?>][precio_venta]"  min="0" step="0.01" value="<?php echo htmlspecialchars((string)$pv, ENT_QUOTES); ?>"></td>
                                                        <td><?php echo $v->activo ? '<span class="label label-success">Activa</span>' : '<span class="label label-default">Desactivada</span>'; ?></td>
                                                        <td style="text-align:center;">
                                                            <label style="font-size:11px;color:#c0392b;cursor:pointer;">
                                                                <input type="checkbox" name="variantes[<?php echo $idx; ?>][remove]" value="1"> Quitar
                                                            </label>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
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
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Editar" />
                          
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#id_categoria').select2(); // Inicializa Select2 en tu select
    });
</script>
<script>
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

        // ── Variantes ─────────────────────────────────────────────────────
        var vIdx = $('#variantes_tbody tr').length;
        function addVarianteRow() {
            vIdx++;
            var i = vIdx;
            var html = ''
                + '<tr data-row="' + i + '">'
                + '  <td><input type="hidden" name="variantes[' + i + '][id_variante]" value="0">'
                + '      <input type="text"   class="form-control input-sm" name="variantes[' + i + '][talla]" maxlength="20" placeholder="22"></td>'
                + '  <td><input type="number" class="form-control input-sm" name="variantes[' + i + '][stock]" min="0" placeholder="0"></td>'
                + '  <td><input type="number" class="form-control input-sm" name="variantes[' + i + '][precio_compra]" min="0" step="0.01" placeholder="0.00"></td>'
                + '  <td><input type="number" class="form-control input-sm" name="variantes[' + i + '][precio_venta]"  min="0" step="0.01" placeholder="0.00"></td>'
                + '  <td><span class="label label-info">Nueva</span></td>'
                + '  <td style="text-align:center;">—</td>'
                + '</tr>';
            $('#variantes_tbody').append(html);
        }
        function toggleGlobalFields(on) {
            $('#variantes_panel').toggle(on);
            $('#stock_global_col, #talla_col, #precio_compra_col, #precio_venta_col').toggle(!on);
        }
        $('#tiene_variantes').on('change', function() {
            var on = this.checked;
            toggleGlobalFields(on);
            if (on && $('#variantes_tbody tr').length === 0) addVarianteRow();
        });
        $('#btn_add_variante').on('click', addVarianteRow);

        // Estado inicial
        toggleGlobalFields($('#tiene_variantes').is(':checked'));
    });
</script>
