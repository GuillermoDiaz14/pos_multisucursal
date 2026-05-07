<style>
.cli-wrapper{padding:16px 20px}
.cli-cards{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-bottom:20px;max-width:500px}
.cli-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);padding:14px 16px;display:flex;align-items:center;gap:12px}
.cli-card-icon{width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;flex-shrink:0}
.cli-card-icon.blue{background:#2980b9}.cli-card-icon.teal{background:#16a085}
.cli-card-value{font-size:22px;font-weight:700;color:#2c3e50;line-height:1.1}
.cli-card-label{font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;margin-top:2px}
.cli-box{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden}
.cli-box-header{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #ecf0f1;flex-wrap:wrap;gap:8px}
.cli-box-title{font-size:15px;font-weight:600;color:#2c3e50;margin:0}
.cli-search-wrap{position:relative}
.cli-search-wrap input{padding-left:30px;border-radius:5px;border:1px solid #ddd;height:32px;font-size:13px;width:230px}
.cli-search-wrap .fa-search{position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#aaa;font-size:13px;pointer-events:none}
.cli-btn-clear{height:32px;padding:0 12px;font-size:13px;border:1px solid #ddd;border-radius:5px;background:#fff;color:#777;cursor:pointer}
.cli-btn-clear:hover{background:#f5f5f5}
.cli-table{width:100%;border-collapse:collapse;font-size:13px}
.cli-table thead th{background:#f8f9fa;padding:10px 12px;text-align:left;font-weight:600;color:#555;border-bottom:2px solid #e9ecef;white-space:nowrap}
.cli-table thead th.text-center{text-align:center}
.cli-table tbody tr{border-bottom:1px solid #f2f2f2;transition:background .1s}
.cli-table tbody tr:hover{background:#fafbfc}
.cli-table td{padding:9px 12px;vertical-align:middle}
.cli-table td.text-center{text-align:center}
.cli-name{font-weight:600;color:#2c3e50}
.cli-email{color:#777;font-size:12px}
.cli-actions{display:flex;gap:4px;justify-content:center}
.cli-actions .btn{padding:3px 8px;font-size:12px;border-radius:4px}
.cli-empty{text-align:center;padding:40px 0;color:#aaa}
.cli-empty i{font-size:40px;display:block;margin-bottom:10px}
.cli-pagination{display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-top:1px solid #f0f0f0;flex-wrap:wrap;gap:8px}
.cli-pag-info{font-size:12px;color:#888}
.cli-pag-btns{display:flex;gap:4px;flex-wrap:wrap}
.cli-pag-btns button{min-width:30px;height:28px;padding:0 8px;border:1px solid #ddd;border-radius:4px;background:#fff;font-size:12px;cursor:pointer;color:#555;transition:all .12s}
.cli-pag-btns button:hover{background:#ecf0f1}
.cli-pag-btns button.active{background:#2980b9;color:#fff;border-color:#2980b9;font-weight:600}
.cli-pag-btns button:disabled{opacity:.4;cursor:default}
@media(max-width:768px){.cli-wrapper{padding:10px}.cli-search-wrap input{width:160px}.cli-cards{grid-template-columns:1fr}.col-cli-doc,.col-cli-correo{display:none}}
</style>

<div class="content-wrapper">
<div class="cli-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-address-book text-primary"></i> Clientes
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;">Gestión de clientes</p>
        </div>
        <a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>cliente/add">
            <i class="fa fa-plus"></i> Agregar cliente
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

    <?php $totalCli = !empty($records) ? count($records) : 0; ?>

    <div class="cli-cards">
        <div class="cli-card">
            <div class="cli-card-icon blue"><i class="fa fa-address-book"></i></div>
            <div>
                <div class="cli-card-value"><?php echo $totalCli; ?></div>
                <div class="cli-card-label">Clientes registrados</div>
            </div>
        </div>
        <div class="cli-card">
            <div class="cli-card-icon teal"><i class="fa fa-check-circle"></i></div>
            <div>
                <div class="cli-card-value"><?php echo $totalCli; ?></div>
                <div class="cli-card-label">Activos</div>
            </div>
        </div>
    </div>

    <div class="cli-box">
        <div class="cli-box-header">
            <h4 class="cli-box-title"><i class="fa fa-table"></i> Lista de clientes</h4>
            <div style="display:flex;gap:8px;align-items:center;">
                <div class="cli-search-wrap">
                    <i class="fa fa-search"></i>
                    <input type="text" id="cliSearch" placeholder="Buscar por nombre, correo, DNI…"
                           autofocus oninput="filtrarClientes()" />
                </div>
                <button class="cli-btn-clear" onclick="limpiarCliFiltro()" title="Limpiar">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="cli-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th class="col-cli-correo">Correo</th>
                        <th class="col-cli-doc">Doc. Identidad</th>
                        <th>Celular</th>
                        <th class="text-center" style="width:100px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="cliTbody">
                <?php if (!empty($records)): ?>
                    <?php foreach ($records as $record): ?>
                    <tr data-visible="1">
                        <td><span class="cli-name"><?php echo htmlspecialchars($record->nombre, ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td class="col-cli-correo"><span class="cli-email"><?php echo htmlspecialchars($record->correo ?? '', ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td class="col-cli-doc" style="font-size:12px;color:#555;"><?php echo htmlspecialchars($record->doc_identidad ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="font-size:12px;color:#555;"><?php echo htmlspecialchars($record->celular ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text-center">
                            <div class="cli-actions">
                                <a class="btn btn-xs btn-info" href="<?php echo base_url().'cliente/edit/'.$record->id_cliente; ?>" title="Editar">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a class="btn btn-xs btn-danger" href="#"
                                   onclick="confirmarEliminarCli(event, '<?php echo base_url().'cliente/confirmar_eliminar_cliente/'.$record->id_cliente; ?>', '<?php echo htmlspecialchars($record->nombre, ENT_QUOTES); ?>')"
                                   title="Eliminar">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5"><div class="cli-empty"><i class="fa fa-address-book"></i>No se encontraron clientes.</div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="cli-pagination">
            <div class="cli-pag-info" id="cli-pag-info">—</div>
            <div class="cli-pag-btns" id="cliPaginacion"></div>
        </div>
    </div>

</div>
</div>

<script>
var cliFilasPorPagina = 15;

function paginarClientes(pagina) {
    var filas = Array.from(document.querySelectorAll('#cliTbody tr[data-visible="1"]'));
    var total  = filas.length;
    var inicio = (pagina - 1) * cliFilasPorPagina;

    document.querySelectorAll('#cliTbody tr').forEach(function(f) { f.style.display = 'none'; });
    filas.slice(inicio, inicio + cliFilasPorPagina).forEach(function(f) { f.style.display = ''; });

    var desde = total === 0 ? 0 : inicio + 1;
    var hasta  = Math.min(inicio + cliFilasPorPagina, total);
    document.getElementById('cli-pag-info').textContent = total === 0
        ? 'Sin resultados' : 'Mostrando ' + desde + '–' + hasta + ' de ' + total + ' clientes';

    var paginas = Math.ceil(total / cliFilasPorPagina);
    var html = '';
    if (paginas > 1) {
        html += '<button onclick="paginarClientes(' + Math.max(1, pagina - 1) + ')" ' + (pagina === 1 ? 'disabled' : '') + '>‹</button>';
        var start = Math.max(1, pagina - 2), end = Math.min(paginas, pagina + 2);
        if (start > 1) html += '<button onclick="paginarClientes(1)">1</button>' + (start > 2 ? '<button disabled>…</button>' : '');
        for (var i = start; i <= end; i++) html += '<button class="' + (i === pagina ? 'active' : '') + '" onclick="paginarClientes(' + i + ')">' + i + '</button>';
        if (end < paginas) html += (end < paginas - 1 ? '<button disabled>…</button>' : '') + '<button onclick="paginarClientes(' + paginas + ')">' + paginas + '</button>';
        html += '<button onclick="paginarClientes(' + Math.min(paginas, pagina + 1) + ')" ' + (pagina === paginas ? 'disabled' : '') + '>›</button>';
    }
    document.getElementById('cliPaginacion').innerHTML = html;
}

function filtrarClientes() {
    var q = document.getElementById('cliSearch').value.toLowerCase().trim();
    document.querySelectorAll('#cliTbody tr').forEach(function(fila) {
        if (!fila.querySelector('td')) return;
        var txt = fila.textContent.toLowerCase();
        fila.dataset.visible = (q === '' || txt.indexOf(q) !== -1) ? '1' : '0';
    });
    paginarClientes(1);
}

function limpiarCliFiltro() {
    document.getElementById('cliSearch').value = '';
    filtrarClientes();
}

function confirmarEliminarCli(e, url, nombre) {
    e.preventDefault();
    if (confirm('¿Eliminar al cliente "' + nombre + '"?\nEsta acción no se puede deshacer.')) {
        window.location.href = url;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#cliTbody tr').forEach(function(f) { f.dataset.visible = '1'; });
    paginarClientes(1);
});
</script>
