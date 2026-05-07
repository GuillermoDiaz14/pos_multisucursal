<style>
.prov-wrapper{padding:16px 20px}
.prov-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px}
.prov-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);padding:14px 16px;display:flex;align-items:center;gap:12px}
.prov-card-icon{width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;flex-shrink:0}
.prov-card-icon.blue{background:#2980b9}.prov-card-icon.green{background:#27ae60}.prov-card-icon.orange{background:#e67e22}
.prov-card-value{font-size:20px;font-weight:700;color:#2c3e50;line-height:1.1}
.prov-card-label{font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;margin-top:2px}
.prov-box{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden}
.prov-box-header{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #ecf0f1;flex-wrap:wrap;gap:8px}
.prov-box-title{font-size:15px;font-weight:600;color:#2c3e50;margin:0}
.prov-search-wrap{position:relative}
.prov-search-wrap input{padding-left:30px;border-radius:5px;border:1px solid #ddd;height:32px;font-size:13px;width:230px}
.prov-search-wrap .fa-search{position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#aaa;font-size:13px;pointer-events:none}
.prov-btn-clear{height:32px;padding:0 12px;font-size:13px;border:1px solid #ddd;border-radius:5px;background:#fff;color:#777;cursor:pointer}
.prov-btn-clear:hover{background:#f5f5f5}
.prov-table{width:100%;border-collapse:collapse;font-size:13px}
.prov-table thead th{background:#f8f9fa;padding:10px 12px;text-align:left;font-weight:600;color:#555;border-bottom:2px solid #e9ecef;white-space:nowrap}
.prov-table thead th.text-center{text-align:center}
.prov-table tbody tr{border-bottom:1px solid #f2f2f2;transition:background .1s}
.prov-table tbody tr:hover{background:#fafbfc}
.prov-table td{padding:9px 12px;vertical-align:middle}
.prov-table td.text-center{text-align:center}
.prov-nombre{font-weight:600;color:#2c3e50;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.prov-email{color:#2980b9;font-size:12px;max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.prov-actions{display:flex;gap:4px;justify-content:center}
.prov-actions .btn{padding:3px 8px;font-size:12px;border-radius:4px}
.prov-empty{text-align:center;padding:40px 0;color:#aaa}
.prov-empty i{font-size:40px;display:block;margin-bottom:10px}
.prov-pagination{display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-top:1px solid #f0f0f0;flex-wrap:wrap;gap:8px}
.prov-pag-info{font-size:12px;color:#888}
.prov-pag-btns{display:flex;gap:4px;flex-wrap:wrap}
.prov-pag-btns button{min-width:30px;height:28px;padding:0 8px;border:1px solid #ddd;border-radius:4px;background:#fff;font-size:12px;cursor:pointer;color:#555;transition:all .12s}
.prov-pag-btns button:hover{background:#ecf0f1}
.prov-pag-btns button.active{background:#2980b9;color:#fff;border-color:#2980b9;font-weight:600}
.prov-pag-btns button:disabled{opacity:.4;cursor:default}
@media(max-width:768px){.prov-wrapper{padding:10px}.prov-search-wrap input{width:160px}.prov-cards{grid-template-columns:1fr}.col-prov-email{display:none}.col-prov-dir{display:none}}
</style>

<div class="content-wrapper">
<div class="prov-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-truck text-primary"></i> Proveedores
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;">Catálogo de proveedores</p>
        </div>
        <div style="display:flex;gap:8px;">
            <a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>proveedor/add">
                <i class="fa fa-plus"></i> Agregar proveedor
            </a>
            <a class="btn btn-default btn-sm" href="<?php echo base_url(); ?>proveedor/importar" title="Importar CSV">
                <i class="fa fa-upload"></i> Importar
            </a>
        </div>
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
    $totalProveedores = !empty($records) ? count($records) : 0;
    $conEmail = 0;
    $conDocFiscal = 0;
    if (!empty($records)) {
        foreach ($records as $r) {
            if (!empty($r->email)) $conEmail++;
            if (!empty($r->doc_fiscal)) $conDocFiscal++;
        }
    }
    ?>

    <div class="prov-cards">
        <div class="prov-card">
            <div class="prov-card-icon blue"><i class="fa fa-truck"></i></div>
            <div>
                <div class="prov-card-value"><?php echo $totalProveedores; ?></div>
                <div class="prov-card-label">Total proveedores</div>
            </div>
        </div>
        <div class="prov-card">
            <div class="prov-card-icon green"><i class="fa fa-envelope"></i></div>
            <div>
                <div class="prov-card-value"><?php echo $conEmail; ?></div>
                <div class="prov-card-label">Con email</div>
            </div>
        </div>
        <div class="prov-card">
            <div class="prov-card-icon orange"><i class="fa fa-file-text-o"></i></div>
            <div>
                <div class="prov-card-value"><?php echo $conDocFiscal; ?></div>
                <div class="prov-card-label">Con doc. fiscal</div>
            </div>
        </div>
    </div>

    <div class="prov-box">
        <div class="prov-box-header">
            <h4 class="prov-box-title"><i class="fa fa-table"></i> Lista de proveedores</h4>
            <div style="display:flex;gap:8px;align-items:center;">
                <div class="prov-search-wrap">
                    <i class="fa fa-search"></i>
                    <input type="text" id="provSearch" placeholder="Nombre, email o doc. fiscal…"
                           autofocus oninput="filtrarProveedores()" />
                </div>
                <button class="prov-btn-clear" onclick="limpiarProvFiltro()" title="Limpiar">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="prov-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th class="col-prov-email">Email</th>
                        <th>Celular</th>
                        <th class="col-prov-dir">Dirección</th>
                        <th>Doc. fiscal</th>
                        <th class="text-center" style="width:90px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="provTbody">
                <?php if (!empty($records)): ?>
                    <?php foreach ($records as $record): ?>
                    <tr data-visible="1">
                        <td><span class="prov-nombre" title="<?php echo htmlspecialchars($record->nombre, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($record->nombre, ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td class="col-prov-email"><span class="prov-email"><?php echo htmlspecialchars($record->email, ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td style="font-size:12px;"><?php echo htmlspecialchars($record->celular, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="col-prov-dir" style="font-size:12px;color:#777;max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo htmlspecialchars($record->direccion, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="font-size:12px;font-weight:600;"><?php echo htmlspecialchars($record->doc_fiscal, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text-center">
                            <div class="prov-actions">
                                <a class="btn btn-xs btn-info" href="<?php echo base_url().'proveedor/edit/'.$record->id_proveedor; ?>" title="Editar">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a class="btn btn-xs btn-danger" href="#"
                                   onclick="confirmarEliminarProv(event, '<?php echo base_url().'proveedor/confirmar_eliminar_proveedor/'.$record->id_proveedor; ?>', '<?php echo htmlspecialchars($record->nombre, ENT_QUOTES); ?>')"
                                   title="Eliminar">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6"><div class="prov-empty"><i class="fa fa-truck"></i>No se encontraron proveedores.</div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="prov-pagination">
            <div class="prov-pag-info" id="prov-pag-info">—</div>
            <div class="prov-pag-btns" id="provPaginacion"></div>
        </div>
    </div>

</div>
</div>

<script>
var provFilasPorPagina = 15;

function paginarProveedores(pagina) {
    var filas  = Array.from(document.querySelectorAll('#provTbody tr[data-visible="1"]'));
    var total  = filas.length;
    var inicio = (pagina - 1) * provFilasPorPagina;

    document.querySelectorAll('#provTbody tr').forEach(function(f) { f.style.display = 'none'; });
    filas.slice(inicio, inicio + provFilasPorPagina).forEach(function(f) { f.style.display = ''; });

    var desde = total === 0 ? 0 : inicio + 1;
    var hasta  = Math.min(inicio + provFilasPorPagina, total);
    document.getElementById('prov-pag-info').textContent = total === 0
        ? 'Sin resultados' : 'Mostrando ' + desde + '–' + hasta + ' de ' + total + ' proveedores';

    var paginas = Math.ceil(total / provFilasPorPagina);
    var html = '';
    if (paginas > 1) {
        html += '<button onclick="paginarProveedores(' + Math.max(1, pagina - 1) + ')" ' + (pagina === 1 ? 'disabled' : '') + '>‹</button>';
        var start = Math.max(1, pagina - 2), end = Math.min(paginas, pagina + 2);
        if (start > 1) html += '<button onclick="paginarProveedores(1)">1</button>' + (start > 2 ? '<button disabled>…</button>' : '');
        for (var i = start; i <= end; i++) html += '<button class="' + (i === pagina ? 'active' : '') + '" onclick="paginarProveedores(' + i + ')">' + i + '</button>';
        if (end < paginas) html += (end < paginas - 1 ? '<button disabled>…</button>' : '') + '<button onclick="paginarProveedores(' + paginas + ')">' + paginas + '</button>';
        html += '<button onclick="paginarProveedores(' + Math.min(paginas, pagina + 1) + ')" ' + (pagina === paginas ? 'disabled' : '') + '>›</button>';
    }
    document.getElementById('provPaginacion').innerHTML = html;
}

function filtrarProveedores() {
    var q = document.getElementById('provSearch').value.toLowerCase().trim();
    document.querySelectorAll('#provTbody tr').forEach(function(fila) {
        if (!fila.querySelector('td')) return;
        var txt = fila.textContent.toLowerCase();
        fila.dataset.visible = (q === '' || txt.indexOf(q) !== -1) ? '1' : '0';
    });
    paginarProveedores(1);
}

function limpiarProvFiltro() {
    document.getElementById('provSearch').value = '';
    filtrarProveedores();
}

function confirmarEliminarProv(e, url, nombre) {
    e.preventDefault();
    if (confirm('¿Eliminar al proveedor "' + nombre + '"?\nEsta acción no se puede deshacer.')) {
        window.location.href = url;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#provTbody tr').forEach(function(f) { f.dataset.visible = '1'; });
    paginarProveedores(1);
});
</script>
