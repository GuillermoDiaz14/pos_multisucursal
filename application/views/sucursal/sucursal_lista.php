<style>
.sc-wrapper{padding:16px 20px}
.sc-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px}
.sc-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);padding:14px 16px;display:flex;align-items:center;gap:12px}
.sc-card-icon{width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;flex-shrink:0}
.sc-card-icon.blue{background:#2980b9}.sc-card-icon.green{background:#27ae60}.sc-card-icon.gray{background:#7f8c8d}
.sc-card-value{font-size:22px;font-weight:700;color:#2c3e50;line-height:1.1}
.sc-card-label{font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;margin-top:2px}
.sc-box{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden}
.sc-box-header{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #ecf0f1;flex-wrap:wrap;gap:8px}
.sc-box-title{font-size:15px;font-weight:600;color:#2c3e50;margin:0}
.sc-filters{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.sc-search-wrap{position:relative}
.sc-search-wrap input{padding-left:30px;border-radius:5px;border:1px solid #ddd;height:32px;font-size:13px;width:220px}
.sc-search-wrap .fa-search{position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#aaa;font-size:13px;pointer-events:none}
.sc-btn-clear{height:32px;padding:0 12px;font-size:13px;border:1px solid #ddd;border-radius:5px;background:#fff;color:#777;cursor:pointer}
.sc-btn-clear:hover{background:#f5f5f5}
.sc-table{width:100%;border-collapse:collapse;font-size:13px}
.sc-table thead th{background:#f8f9fa;padding:10px 12px;text-align:left;font-weight:600;color:#555;border-bottom:2px solid #e9ecef;white-space:nowrap}
.sc-table thead th.text-center{text-align:center}
.sc-table tbody tr{border-bottom:1px solid #f2f2f2;transition:background .1s}
.sc-table tbody tr:hover{background:#fafbfc}
.sc-table td{padding:9px 12px;vertical-align:middle}
.sc-table td.text-center{text-align:center}
.sc-id{font-weight:600;color:#7f8c8d;font-size:12px}
.sc-name{font-weight:600;color:#2c3e50}
.sc-actions{display:flex;gap:4px;justify-content:center;flex-wrap:nowrap;white-space:nowrap}
.sc-actions .btn{padding:3px 8px;font-size:12px;border-radius:4px}
.sc-empty{text-align:center;padding:40px 0;color:#aaa}
.sc-empty i{font-size:40px;display:block;margin-bottom:10px}
.sc-pagination{display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-top:1px solid #f0f0f0;flex-wrap:wrap;gap:8px}
.sc-pag-info{font-size:12px;color:#888}
.sc-pag-btns{display:flex;gap:4px;flex-wrap:wrap}
.sc-pag-btns button{min-width:30px;height:28px;padding:0 8px;border:1px solid #ddd;border-radius:4px;background:#fff;font-size:12px;cursor:pointer;color:#555;transition:all .12s}
.sc-pag-btns button:hover{background:#ecf0f1}
.sc-pag-btns button.active{background:#2980b9;color:#fff;border-color:#2980b9;font-weight:600}
.sc-pag-btns button:disabled{opacity:.4;cursor:default}
.badge-impuesto{display:inline-block;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;background:#eaf4fb;color:#1a5276;border:1px solid #aed6f1}
.sc-logo-thumb{width:42px;height:42px;object-fit:contain;border-radius:6px;border:1px solid #ecf0f1;background:#fff;padding:2px;cursor:pointer;transition:transform .12s,box-shadow .12s}
.sc-logo-thumb:hover{transform:scale(1.08);box-shadow:0 2px 6px rgba(0,0,0,.18)}
.sc-logo-empty{display:inline-flex;align-items:center;justify-content:center;width:42px;height:42px;border-radius:6px;border:1px dashed #ddd;color:#bbb;font-size:18px;background:#fafafa}
@media(max-width:768px){.sc-cards{grid-template-columns:1fr 1fr}.sc-wrapper{padding:10px}.sc-search-wrap input{width:160px}.col-sc-dir,.col-sc-correo{display:none}}
@media(max-width:480px){.sc-cards{grid-template-columns:1fr}}
</style>

<div class="content-wrapper">
<div class="sc-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-building text-primary"></i> Sucursales
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;">Gestión de sucursales del negocio</p>
        </div>
        <?php
        $accessInfo = $this->session->userdata('accessInfo') ?: [];
        $isAdmin    = !empty($this->session->userdata('emergency_admin'));
        $puedeCrear = $isAdmin || !empty($accessInfo['Sucursal']['crear']);
        ?>
        <?php if ($puedeCrear): ?>
        <a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>sucursal/add">
            <i class="fa fa-plus"></i> Agregar sucursal
        </a>
        <?php endif; ?>
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
    <?php echo validation_errors('<div class="alert alert-danger alert-dismissable" style="border-radius:6px;"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); ?>

    <?php
    $totalSucursales = !empty($records) ? count($records) : 0;
    ?>

    <!-- Tarjetas resumen -->
    <div class="sc-cards">
        <div class="sc-card">
            <div class="sc-card-icon blue"><i class="fa fa-building"></i></div>
            <div>
                <div class="sc-card-value"><?php echo $totalSucursales; ?></div>
                <div class="sc-card-label">Sucursales registradas</div>
            </div>
        </div>
        <div class="sc-card">
            <div class="sc-card-icon green"><i class="fa fa-check-circle"></i></div>
            <div>
                <div class="sc-card-value"><?php echo $totalSucursales; ?></div>
                <div class="sc-card-label">Activas</div>
            </div>
        </div>
        <div class="sc-card">
            <div class="sc-card-icon gray"><i class="fa fa-percent"></i></div>
            <div>
                <?php
                $avgImpuesto = 0;
                if ($totalSucursales > 0) {
                    $sumImp = 0;
                    foreach ($records as $r) $sumImp += (float)$r->impuesto;
                    $avgImpuesto = $sumImp / $totalSucursales;
                }
                ?>
                <div class="sc-card-value"><?php echo number_format($avgImpuesto, 1); ?>%</div>
                <div class="sc-card-label">Impuesto promedio</div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="sc-box">
        <div class="sc-box-header">
            <h4 class="sc-box-title"><i class="fa fa-table"></i> Lista de sucursales</h4>
            <div class="sc-filters">
                <div class="sc-search-wrap">
                    <i class="fa fa-search"></i>
                    <input type="text" id="searchText" placeholder="Buscar por nombre o ciudad…"
                           autofocus oninput="filtrarSucursales()"
                           value="<?php echo htmlspecialchars($searchText ?? '', ENT_QUOTES); ?>">
                </div>
                <button class="sc-btn-clear" onclick="limpiarFiltros()" title="Limpiar">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="sc-table" id="tablaSucursales">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th class="text-center" style="width:60px;">Logo</th>
                        <th>Sucursal</th>
                        <th>Impuesto</th>
                        <th>Celular</th>
                        <th class="col-sc-dir">Dirección</th>
                        <th>Ciudad</th>
                        <th class="col-sc-correo">Correo</th>
                        <th class="text-center" style="width:160px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaSucursalesTbody">
                    <?php include('table_partial.php'); ?>
                </tbody>
            </table>
        </div>

        <div class="sc-pagination">
            <div class="sc-pag-info" id="pag-info-sucursal">—</div>
            <div class="sc-pag-btns" id="paginacionSucursal"></div>
        </div>
    </div>

</div>
</div>

<!-- Modal ver logo en grande -->
<div class="modal fade" id="modalVerLogo" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top:80px;">
        <div class="modal-content">
            <div class="modal-header" style="padding:8px 14px;">
                <button type="button" class="close" data-dismiss="modal" style="opacity:.6;">&times;</button>
                <h5 class="modal-title" id="modalVerLogoTitle" style="font-size:14px;font-weight:600;color:#2c3e50;">Logo</h5>
            </div>
            <div class="modal-body text-center" style="padding:14px;">
                <img id="modalVerLogoImg" src="" alt="Logo" style="max-width:100%;max-height:360px;border-radius:6px;" />
            </div>
        </div>
    </div>
</div>

<!-- Modal eliminar con contraseña -->
<div class="modal fade" id="modalEliminarSucursal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#e74c3c;color:#fff;">
                <h5 class="modal-title" style="font-weight:600;"><i class="fa fa-warning"></i> Eliminar Sucursal</h5>
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="border-radius:6px;margin-bottom:12px;">
                    <strong>Esta acción es irreversible.</strong> Se eliminarán todos los datos asociados a esta sucursal incluyendo inventario y transacciones.
                </div>
                <div class="form-group">
                    <label style="font-weight:600;">Confirma tu contraseña para continuar:</label>
                    <input type="password" class="form-control" id="password" placeholder="Ingresa tu contraseña" style="border-radius:5px;">
                </div>
                <div id="errorMsg" class="alert alert-danger" style="display:none;border-radius:5px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="confirmarEliminar()"><i class="fa fa-trash"></i> Eliminar definitivamente</button>
            </div>
        </div>
    </div>
</div>

<script>
var filasPorPaginaSucursal = 15;

function paginarSucursal(pagina) {
    var filas = Array.from(document.querySelectorAll('#tablaSucursalesTbody tr[data-visible="1"]'));
    var total = filas.length;
    var inicio = (pagina - 1) * filasPorPaginaSucursal;

    document.querySelectorAll('#tablaSucursalesTbody tr').forEach(function(f) { f.style.display = 'none'; });
    filas.slice(inicio, inicio + filasPorPaginaSucursal).forEach(function(f) { f.style.display = ''; });

    var desde = total === 0 ? 0 : inicio + 1;
    var hasta  = Math.min(inicio + filasPorPaginaSucursal, total);
    document.getElementById('pag-info-sucursal').textContent = total === 0
        ? 'Sin resultados' : 'Mostrando ' + desde + '–' + hasta + ' de ' + total + ' sucursales';

    var paginas = Math.ceil(total / filasPorPaginaSucursal);
    var html = '';
    if (paginas > 1) {
        html += '<button onclick="paginarSucursal(' + Math.max(1, pagina-1) + ')" ' + (pagina===1?'disabled':'') + '>‹</button>';
        var start = Math.max(1, pagina-2), end = Math.min(paginas, pagina+2);
        if (start > 1) html += '<button onclick="paginarSucursal(1)">1</button>' + (start>2?'<button disabled>…</button>':'');
        for (var i = start; i <= end; i++) html += '<button class="'+(i===pagina?'active':'')+'" onclick="paginarSucursal('+i+')">'+i+'</button>';
        if (end < paginas) html += (end<paginas-1?'<button disabled>…</button>':'')+'<button onclick="paginarSucursal('+paginas+')">'+paginas+'</button>';
        html += '<button onclick="paginarSucursal(' + Math.min(paginas, pagina+1) + ')" ' + (pagina===paginas?'disabled':'') + '>›</button>';
    }
    document.getElementById('paginacionSucursal').innerHTML = html;
}

function marcarTodosVisiblesSucursal() {
    document.querySelectorAll('#tablaSucursalesTbody tr').forEach(function(f) { f.dataset.visible = '1'; });
}

function filtrarSucursales() {
    $.ajax({
        url: '<?php echo base_url(); ?>sucursal/filtersucursal',
        type: 'POST',
        data: { searchText: document.getElementById('searchText').value },
        success: function(html) {
            $('#tablaSucursalesTbody').html(html);
            marcarTodosVisiblesSucursal();
            paginarSucursal(1);
        }
    });
}

function limpiarFiltros() {
    document.getElementById('searchText').value = '';
    filtrarSucursales();
}

document.addEventListener('DOMContentLoaded', function() {
    marcarTodosVisiblesSucursal();
    paginarSucursal(1);
});

var idSucursalActual = null;

function abrirModalEliminar(id) {
    idSucursalActual = id;
    $('#password').val('');
    $('#errorMsg').hide();
    $('#modalEliminarSucursal').modal('show');
    setTimeout(function() { $('#password').focus(); }, 300);
}

function confirmarEliminar() {
    var password = $('#password').val();
    if (!password) { mostrarError('La contraseña es requerida'); return; }

    $.ajax({
        url: '<?php echo base_url() ?>sucursal/validar_contrasena_eliminar',
        type: 'POST',
        dataType: 'json',
        data: { password: password, id_sucursal: idSucursalActual },
        success: function(response) {
            if (response.success) {
                $('#modalEliminarSucursal').modal('hide');
                location.reload();
            } else {
                mostrarError(response.message || 'Contraseña incorrecta');
            }
        },
        error: function() { mostrarError('Error en la solicitud'); }
    });
}

function mostrarError(msg) {
    $('#errorMsg').text(msg).show();
}

$('#password').keypress(function(e) {
    if (e.which === 13) confirmarEliminar();
});

function verLogoSucursal(url, nombre) {
    document.getElementById('modalVerLogoImg').src = url;
    document.getElementById('modalVerLogoTitle').textContent = nombre || 'Logo';
    $('#modalVerLogo').modal('show');
}
</script>
