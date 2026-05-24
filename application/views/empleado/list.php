<style>
.emp-wrapper{padding:16px 20px}
.emp-cards{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-bottom:20px;max-width:500px}
.emp-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);padding:14px 16px;display:flex;align-items:center;gap:12px}
.emp-card-icon{width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;flex-shrink:0}
.emp-card-icon.blue{background:#2980b9}.emp-card-icon.green{background:#27ae60}
.emp-card-value{font-size:22px;font-weight:700;color:#2c3e50;line-height:1.1}
.emp-card-label{font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;margin-top:2px}
.emp-box{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden}
.emp-box-header{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #ecf0f1;flex-wrap:wrap;gap:8px}
.emp-box-title{font-size:15px;font-weight:600;color:#2c3e50;margin:0}
.emp-search-wrap{position:relative}
.emp-search-wrap input{padding-left:30px;border-radius:5px;border:1px solid #ddd;height:32px;font-size:13px;width:220px}
.emp-search-wrap .fa-search{position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#aaa;font-size:13px;pointer-events:none}
.emp-btn-clear{height:32px;padding:0 12px;font-size:13px;border:1px solid #ddd;border-radius:5px;background:#fff;color:#777;cursor:pointer}
.emp-btn-clear:hover{background:#f5f5f5}
.emp-table{width:100%;border-collapse:collapse;font-size:13px}
.emp-table thead th{background:#f8f9fa;padding:10px 12px;text-align:left;font-weight:600;color:#555;border-bottom:2px solid #e9ecef;white-space:nowrap}
.emp-table thead th.text-center{text-align:center}
.emp-table tbody tr{border-bottom:1px solid #f2f2f2;transition:background .1s}
.emp-table tbody tr:hover{background:#fafbfc}
.emp-table td{padding:9px 12px;vertical-align:middle}
.emp-table td.text-center{text-align:center}
.emp-name{font-weight:600;color:#2c3e50}
.emp-dni{color:#777;font-size:12px}
.emp-actions{display:flex;gap:4px;justify-content:center}
.emp-actions .btn{padding:3px 8px;font-size:12px;border-radius:4px}
.emp-empty{text-align:center;padding:40px 0;color:#aaa}
.emp-empty i{font-size:40px;display:block;margin-bottom:10px}
.emp-pagination{display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-top:1px solid #f0f0f0;flex-wrap:wrap;gap:8px}
.emp-pag-info{font-size:12px;color:#888}
.emp-pag-btns{display:flex;gap:4px;flex-wrap:wrap}
.emp-pag-btns button{min-width:30px;height:28px;padding:0 8px;border:1px solid #ddd;border-radius:4px;background:#fff;font-size:12px;cursor:pointer;color:#555;transition:all .12s}
.emp-pag-btns button:hover{background:#ecf0f1}
.emp-pag-btns button.active{background:#2980b9;color:#fff;border-color:#2980b9;font-weight:600}
.emp-pag-btns button:disabled{opacity:.4;cursor:default}
@media(max-width:768px){.emp-wrapper{padding:10px}.emp-search-wrap input{width:160px}.emp-cards{grid-template-columns:1fr}}
</style>

<div class="content-wrapper">
<div class="emp-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-users text-primary"></i> Empleados
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;">Gestión de empleados</p>
        </div>
        <a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>empleado/add">
            <i class="fa fa-plus"></i> Agregar empleado
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
    $totalEmp = !empty($records) ? count($records) : 0;
    ?>

    <div class="emp-cards">
        <div class="emp-card">
            <div class="emp-card-icon blue"><i class="fa fa-users"></i></div>
            <div>
                <div class="emp-card-value"><?php echo $totalEmp; ?></div>
                <div class="emp-card-label">Empleados</div>
            </div>
        </div>
        <div class="emp-card">
            <div class="emp-card-icon green"><i class="fa fa-check-circle"></i></div>
            <div>
                <div class="emp-card-value"><?php echo $totalEmp; ?></div>
                <div class="emp-card-label">Activos</div>
            </div>
        </div>
    </div>

    <div class="emp-box">
        <div class="emp-box-header">
            <h4 class="emp-box-title"><i class="fa fa-table"></i> Lista de empleados</h4>
            <div style="display:flex;gap:8px;align-items:center;">
                <div class="emp-search-wrap">
                    <i class="fa fa-search"></i>
                    <input type="text" id="empSearch" placeholder="Buscar por nombre, INE…"
                           autofocus oninput="filtrarEmpleados()" />
                </div>
                <button class="emp-btn-clear" onclick="limpiarEmpFiltro()" title="Limpiar">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="emp-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>INE</th>
                        <th>Celular</th>
                        <th class="text-center" style="width:120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="empTbody">
                <?php if (!empty($records)): ?>
                    <?php foreach ($records as $record): ?>
                    <tr data-visible="1">
                        <td><span class="emp-name"><?php echo htmlspecialchars($record->nombre, ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td><span class="emp-dni"><?php echo htmlspecialchars($record->dni, ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td style="font-size:12px;color:#555;"><?php echo htmlspecialchars($record->celular, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text-center">
                            <div class="emp-actions">
                                <a class="btn btn-xs btn-info" href="<?php echo base_url().'empleado/edit/'.$record->id_empleado; ?>" title="Editar">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a class="btn btn-xs btn-danger" href="#"
                                   onclick="confirmarEliminarEmp(event, '<?php echo base_url().'empleado/confirmar_eliminar_empleado/'.$record->id_empleado; ?>', '<?php echo htmlspecialchars($record->nombre, ENT_QUOTES); ?>')"
                                   title="Eliminar">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4"><div class="emp-empty"><i class="fa fa-users"></i>No se encontraron empleados.</div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="emp-pagination">
            <div class="emp-pag-info" id="emp-pag-info">—</div>
            <div class="emp-pag-btns" id="empPaginacion"></div>
        </div>
    </div>

</div>
</div>

<script>
var empFilasPorPagina = 15;

function paginarEmpleados(pagina) {
    var filas = Array.from(document.querySelectorAll('#empTbody tr[data-visible="1"]'));
    var total  = filas.length;
    var inicio = (pagina - 1) * empFilasPorPagina;

    document.querySelectorAll('#empTbody tr').forEach(function(f) { f.style.display = 'none'; });
    filas.slice(inicio, inicio + empFilasPorPagina).forEach(function(f) { f.style.display = ''; });

    var desde = total === 0 ? 0 : inicio + 1;
    var hasta  = Math.min(inicio + empFilasPorPagina, total);
    document.getElementById('emp-pag-info').textContent = total === 0
        ? 'Sin resultados' : 'Mostrando ' + desde + '–' + hasta + ' de ' + total + ' empleados';

    var paginas = Math.ceil(total / empFilasPorPagina);
    var html = '';
    if (paginas > 1) {
        html += '<button onclick="paginarEmpleados(' + Math.max(1, pagina - 1) + ')" ' + (pagina === 1 ? 'disabled' : '') + '>‹</button>';
        var start = Math.max(1, pagina - 2), end = Math.min(paginas, pagina + 2);
        if (start > 1) html += '<button onclick="paginarEmpleados(1)">1</button>' + (start > 2 ? '<button disabled>…</button>' : '');
        for (var i = start; i <= end; i++) html += '<button class="' + (i === pagina ? 'active' : '') + '" onclick="paginarEmpleados(' + i + ')">' + i + '</button>';
        if (end < paginas) html += (end < paginas - 1 ? '<button disabled>…</button>' : '') + '<button onclick="paginarEmpleados(' + paginas + ')">' + paginas + '</button>';
        html += '<button onclick="paginarEmpleados(' + Math.min(paginas, pagina + 1) + ')" ' + (pagina === paginas ? 'disabled' : '') + '>›</button>';
    }
    document.getElementById('empPaginacion').innerHTML = html;
}

function filtrarEmpleados() {
    var q = document.getElementById('empSearch').value.toLowerCase().trim();
    document.querySelectorAll('#empTbody tr').forEach(function(fila) {
        if (!fila.querySelector('td')) return;
        var txt = fila.textContent.toLowerCase();
        fila.dataset.visible = (q === '' || txt.indexOf(q) !== -1) ? '1' : '0';
    });
    paginarEmpleados(1);
}

function limpiarEmpFiltro() {
    document.getElementById('empSearch').value = '';
    filtrarEmpleados();
}

function confirmarEliminarEmp(e, url, nombre) {
    e.preventDefault();
    if (confirm('¿Eliminar al empleado "' + nombre + '"? Esta acción no se puede deshacer.')) {
        window.location.href = url;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#empTbody tr').forEach(function(f) { f.dataset.visible = '1'; });
    paginarEmpleados(1);
});
</script>
