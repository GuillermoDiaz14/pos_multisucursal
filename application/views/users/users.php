<style>
.usr-wrapper{padding:16px 20px}
.usr-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px}
.usr-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);padding:14px 16px;display:flex;align-items:center;gap:12px}
.usr-card-icon{width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;flex-shrink:0}
.usr-card-icon.blue{background:#2980b9}.usr-card-icon.green{background:#27ae60}.usr-card-icon.purple{background:#8e44ad}
.usr-card-value{font-size:22px;font-weight:700;color:#2c3e50;line-height:1.1}
.usr-card-label{font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;margin-top:2px}
.usr-box{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden}
.usr-box-header{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #ecf0f1;flex-wrap:wrap;gap:8px}
.usr-box-title{font-size:15px;font-weight:600;color:#2c3e50;margin:0}
.usr-search-wrap{position:relative}
.usr-search-wrap input{padding-left:30px;border-radius:5px;border:1px solid #ddd;height:32px;font-size:13px;width:220px}
.usr-search-wrap .fa-search{position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#aaa;font-size:13px;pointer-events:none}
.usr-btn-clear{height:32px;padding:0 12px;font-size:13px;border:1px solid #ddd;border-radius:5px;background:#fff;color:#777;cursor:pointer}
.usr-btn-clear:hover{background:#f5f5f5}
.usr-table{width:100%;border-collapse:collapse;font-size:13px}
.usr-table thead th{background:#f8f9fa;padding:10px 12px;text-align:left;font-weight:600;color:#555;border-bottom:2px solid #e9ecef;white-space:nowrap}
.usr-table thead th.text-center{text-align:center}
.usr-table tbody tr{border-bottom:1px solid #f2f2f2;transition:background .1s}
.usr-table tbody tr:hover{background:#fafbfc}
.usr-table td{padding:9px 12px;vertical-align:middle}
.usr-table td.text-center{text-align:center}
.usr-name{font-weight:600;color:#2c3e50}
.usr-email{color:#777;font-size:12px}
.usr-actions{display:flex;gap:4px;justify-content:center;flex-wrap:nowrap}
.usr-actions .btn{padding:3px 8px;font-size:12px;border-radius:4px}
.usr-badge-rol{display:inline-block;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;background:#eaf4fb;color:#1a5276;border:1px solid #aed6f1}
.usr-badge-suc{display:inline-block;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;background:#eafaf1;color:#1e8449;border:1px solid #a9dfbf}
.usr-empty{text-align:center;padding:40px 0;color:#aaa}
.usr-empty i{font-size:40px;display:block;margin-bottom:10px}
.usr-pagination{display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-top:1px solid #f0f0f0;flex-wrap:wrap;gap:8px}
.usr-pag-info{font-size:12px;color:#888}
.usr-pag-btns{display:flex;gap:4px;flex-wrap:wrap}
.usr-pag-btns button{min-width:30px;height:28px;padding:0 8px;border:1px solid #ddd;border-radius:4px;background:#fff;font-size:12px;cursor:pointer;color:#555;transition:all .12s}
.usr-pag-btns button:hover{background:#ecf0f1}
.usr-pag-btns button.active{background:#2980b9;color:#fff;border-color:#2980b9;font-weight:600}
.usr-pag-btns button:disabled{opacity:.4;cursor:default}
@media(max-width:768px){.usr-cards{grid-template-columns:1fr 1fr}.usr-wrapper{padding:10px}.usr-search-wrap input{width:160px}.col-usr-suc,.col-usr-fecha{display:none}}
@media(max-width:480px){.usr-cards{grid-template-columns:1fr}}
</style>

<div class="content-wrapper">
<div class="usr-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-users text-primary"></i> Usuarios
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;">Gestión de usuarios del sistema</p>
        </div>
        <a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>addNew">
            <i class="fa fa-plus"></i> Agregar usuario
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

    <?php
    $totalUsers = !empty($userRecords) ? count($userRecords) : 0;
    $roles_uniq = [];
    if (!empty($userRecords)) {
        foreach ($userRecords as $r) {
            if (!empty($r->role)) $roles_uniq[$r->role] = true;
        }
    }
    $totalRoles = count($roles_uniq);
    ?>

    <!-- Tarjetas resumen -->
    <div class="usr-cards">
        <div class="usr-card">
            <div class="usr-card-icon blue"><i class="fa fa-users"></i></div>
            <div>
                <div class="usr-card-value"><?php echo $totalUsers; ?></div>
                <div class="usr-card-label">Usuarios registrados</div>
            </div>
        </div>
        <div class="usr-card">
            <div class="usr-card-icon green"><i class="fa fa-check-circle"></i></div>
            <div>
                <div class="usr-card-value"><?php echo $totalUsers; ?></div>
                <div class="usr-card-label">Activos</div>
            </div>
        </div>
        <div class="usr-card">
            <div class="usr-card-icon purple"><i class="fa fa-shield"></i></div>
            <div>
                <div class="usr-card-value"><?php echo $totalRoles; ?></div>
                <div class="usr-card-label">Roles distintos</div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="usr-box">
        <div class="usr-box-header">
            <h4 class="usr-box-title"><i class="fa fa-table"></i> Lista de usuarios</h4>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                <div class="usr-search-wrap">
                    <i class="fa fa-search"></i>
                    <input type="text" id="usrSearch" placeholder="Buscar por nombre, email…"
                           autofocus oninput="filtrarUsuarios()"
                           value="<?php echo htmlspecialchars($searchText ?? '', ENT_QUOTES); ?>">
                </div>
                <button class="usr-btn-clear" onclick="limpiarUsrFiltro()" title="Limpiar">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="usr-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Celular</th>
                        <th class="col-usr-suc">Sucursal</th>
                        <th>Rol</th>
                        <th class="col-usr-fecha">Creado</th>
                        <th class="text-center" style="width:140px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="usrTbody">
                <?php if (!empty($userRecords)): ?>
                    <?php foreach ($userRecords as $record): ?>
                    <tr data-visible="1">
                        <td>
                            <span class="usr-name"><?php echo htmlspecialchars($record->name, ENT_QUOTES, 'UTF-8'); ?></span>
                        </td>
                        <td><span class="usr-email"><?php echo htmlspecialchars($record->email, ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td style="font-size:12px;color:#555;"><?php echo htmlspecialchars($record->mobile ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="col-usr-suc">
                            <span class="usr-badge-suc"><?php echo htmlspecialchars($record->nombre_sucursal ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                        </td>
                        <td>
                            <span class="usr-badge-rol"><?php echo htmlspecialchars($record->role ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php if (isset($record->roleStatus) && $record->roleStatus == INACTIVE): ?>
                                <span class="label label-warning" style="font-size:10px;">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="col-usr-fecha" style="font-size:12px;color:#999;"><?php echo fmt_fecha($record->createdDtm); ?></td>
                        <td class="text-center">
                            <div class="usr-actions">
                                <a class="btn btn-xs btn-primary" href="<?php echo base_url().'login-history/'.$record->userId; ?>" title="Historial de acceso">
                                    <i class="fa fa-history"></i>
                                </a>
                                <a class="btn btn-xs btn-info" href="<?php echo base_url().'editOld/'.$record->userId; ?>" title="Editar">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a class="btn btn-xs btn-danger deleteUser" href="#" data-userid="<?php echo $record->userId; ?>" title="Eliminar">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7"><div class="usr-empty"><i class="fa fa-users"></i>No se encontraron usuarios.</div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="usr-pagination">
            <div class="usr-pag-info" id="usr-pag-info">—</div>
            <div class="usr-pag-btns" id="usrPaginacion"></div>
        </div>
    </div>

</div>
</div>

<script>
var usrFilasPorPagina = 15;

function paginarUsuarios(pagina) {
    var filas = Array.from(document.querySelectorAll('#usrTbody tr[data-visible="1"]'));
    var total = filas.length;
    var inicio = (pagina - 1) * usrFilasPorPagina;

    document.querySelectorAll('#usrTbody tr').forEach(function(f) { f.style.display = 'none'; });
    filas.slice(inicio, inicio + usrFilasPorPagina).forEach(function(f) { f.style.display = ''; });

    var desde = total === 0 ? 0 : inicio + 1;
    var hasta  = Math.min(inicio + usrFilasPorPagina, total);
    document.getElementById('usr-pag-info').textContent = total === 0
        ? 'Sin resultados' : 'Mostrando ' + desde + '–' + hasta + ' de ' + total + ' usuarios';

    var paginas = Math.ceil(total / usrFilasPorPagina);
    var html = '';
    if (paginas > 1) {
        html += '<button onclick="paginarUsuarios(' + Math.max(1, pagina-1) + ')" ' + (pagina===1?'disabled':'') + '>‹</button>';
        var start = Math.max(1, pagina-2), end = Math.min(paginas, pagina+2);
        if (start > 1) html += '<button onclick="paginarUsuarios(1)">1</button>' + (start>2?'<button disabled>…</button>':'');
        for (var i = start; i <= end; i++) html += '<button class="'+(i===pagina?'active':'')+'" onclick="paginarUsuarios('+i+')">'+i+'</button>';
        if (end < paginas) html += (end<paginas-1?'<button disabled>…</button>':'')+'<button onclick="paginarUsuarios('+paginas+')">'+paginas+'</button>';
        html += '<button onclick="paginarUsuarios(' + Math.min(paginas, pagina+1) + ')" ' + (pagina===paginas?'disabled':'') + '>›</button>';
    }
    document.getElementById('usrPaginacion').innerHTML = html;
}

function filtrarUsuarios() {
    var q = document.getElementById('usrSearch').value.toLowerCase().trim();
    document.querySelectorAll('#usrTbody tr').forEach(function(fila) {
        if (!fila.querySelector('td')) return;
        var txt = fila.textContent.toLowerCase();
        fila.dataset.visible = (q === '' || txt.indexOf(q) !== -1) ? '1' : '0';
    });
    paginarUsuarios(1);
}

function limpiarUsrFiltro() {
    document.getElementById('usrSearch').value = '';
    filtrarUsuarios();
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#usrTbody tr').forEach(function(f) { f.dataset.visible = '1'; });
    paginarUsuarios(1);
});
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
