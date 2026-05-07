<style>
.ing-wrapper{padding:16px 20px}
.ing-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px}
.ing-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);padding:14px 16px;display:flex;align-items:center;gap:12px}
.ing-card-icon{width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;flex-shrink:0}
.ing-card-icon.green{background:#27ae60}.ing-card-icon.teal{background:#16a085}.ing-card-icon.purple{background:#8e44ad}
.ing-card-value{font-size:20px;font-weight:700;color:#2c3e50;line-height:1.1}
.ing-card-label{font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;margin-top:2px}
.ing-box{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden}
.ing-box-header{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #ecf0f1;flex-wrap:wrap;gap:8px}
.ing-box-title{font-size:15px;font-weight:600;color:#2c3e50;margin:0}
.ing-search-wrap{position:relative}
.ing-search-wrap input{padding-left:30px;border-radius:5px;border:1px solid #ddd;height:32px;font-size:13px;width:230px}
.ing-search-wrap .fa-search{position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#aaa;font-size:13px;pointer-events:none}
.ing-btn-clear{height:32px;padding:0 12px;font-size:13px;border:1px solid #ddd;border-radius:5px;background:#fff;color:#777;cursor:pointer}
.ing-btn-clear:hover{background:#f5f5f5}
.ing-table{width:100%;border-collapse:collapse;font-size:13px}
.ing-table thead th{background:#f8f9fa;padding:10px 12px;text-align:left;font-weight:600;color:#555;border-bottom:2px solid #e9ecef;white-space:nowrap}
.ing-table thead th.text-center{text-align:center}
.ing-table tbody tr{border-bottom:1px solid #f2f2f2;transition:background .1s}
.ing-table tbody tr:hover{background:#fafbfc}
.ing-table td{padding:9px 12px;vertical-align:middle}
.ing-table td.text-center{text-align:center}
.ing-desc{font-weight:600;color:#2c3e50;max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ing-monto{font-weight:700;color:#27ae60}
.ing-actions{display:flex;gap:4px;justify-content:center}
.ing-actions .btn{padding:3px 8px;font-size:12px;border-radius:4px}
.ing-empty{text-align:center;padding:40px 0;color:#aaa}
.ing-empty i{font-size:40px;display:block;margin-bottom:10px}
.ing-pagination{display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-top:1px solid #f0f0f0;flex-wrap:wrap;gap:8px}
.ing-pag-info{font-size:12px;color:#888}
.ing-pag-btns{display:flex;gap:4px;flex-wrap:wrap}
.ing-pag-btns button{min-width:30px;height:28px;padding:0 8px;border:1px solid #ddd;border-radius:4px;background:#fff;font-size:12px;cursor:pointer;color:#555;transition:all .12s}
.ing-pag-btns button:hover{background:#ecf0f1}
.ing-pag-btns button.active{background:#27ae60;color:#fff;border-color:#27ae60;font-weight:600}
.ing-pag-btns button:disabled{opacity:.4;cursor:default}
@media(max-width:768px){.ing-wrapper{padding:10px}.ing-search-wrap input{width:160px}.ing-cards{grid-template-columns:1fr}.col-ing-fecha{display:none}}
</style>

<div class="content-wrapper">
<div class="ing-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-arrow-circle-down text-success"></i> Ingresos
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;">Registro y control de ingresos de la sucursal</p>
        </div>
        <a class="btn btn-success btn-sm" href="<?php echo base_url(); ?>ingreso/add">
            <i class="fa fa-plus"></i> Registrar ingreso
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
    $totalIngresos = !empty($records) ? count($records) : 0;
    $totalMonto    = 0;
    $mesActual     = date('n');
    $montoMes      = 0;
    if (!empty($records)) {
        foreach ($records as $r) {
            $totalMonto += (float)$r->monto;
            if (date('n', strtotime($r->fecha)) == $mesActual) {
                $montoMes += (float)$r->monto;
            }
        }
    }
    ?>

    <div class="ing-cards">
        <div class="ing-card">
            <div class="ing-card-icon green"><i class="fa fa-list-alt"></i></div>
            <div>
                <div class="ing-card-value"><?php echo $totalIngresos; ?></div>
                <div class="ing-card-label">Total registros</div>
            </div>
        </div>
        <div class="ing-card">
            <div class="ing-card-icon teal"><i class="fa fa-money"></i></div>
            <div>
                <div class="ing-card-value">$<?php echo number_format($totalMonto, 2); ?></div>
                <div class="ing-card-label">Total ingresos</div>
            </div>
        </div>
        <div class="ing-card">
            <div class="ing-card-icon purple"><i class="fa fa-calendar"></i></div>
            <div>
                <div class="ing-card-value">$<?php echo number_format($montoMes, 2); ?></div>
                <div class="ing-card-label">Mes actual</div>
            </div>
        </div>
    </div>

    <div class="ing-box">
        <div class="ing-box-header">
            <h4 class="ing-box-title"><i class="fa fa-table"></i> Lista de ingresos</h4>
            <div style="display:flex;gap:8px;align-items:center;">
                <div class="ing-search-wrap">
                    <i class="fa fa-search"></i>
                    <input type="text" id="ingSearch" placeholder="Buscar por descripción…"
                           autofocus oninput="filtrarIngresos()" />
                </div>
                <button class="ing-btn-clear" onclick="limpiarIngFiltro()" title="Limpiar">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="ing-table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Monto</th>
                        <th class="col-ing-fecha">Fecha</th>
                        <th class="text-center" style="width:100px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="ingTbody">
                <?php if (!empty($records)): ?>
                    <?php foreach ($records as $record): ?>
                    <tr data-visible="1">
                        <td><span class="ing-desc" title="<?php echo htmlspecialchars($record->descripcion, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($record->descripcion, ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td><span class="ing-monto">$<?php echo number_format((float)$record->monto, 2); ?></span></td>
                        <td class="col-ing-fecha" style="font-size:12px;color:#777;"><?php echo fmt_fecha($record->fecha); ?></td>
                        <td class="text-center">
                            <div class="ing-actions">
                                <a class="btn btn-xs btn-info" href="<?php echo base_url().'ingreso/edit/'.$record->id_ingreso; ?>" title="Editar">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a class="btn btn-xs btn-danger" href="#"
                                   onclick="confirmarEliminarIng(event, '<?php echo base_url().'ingreso/confirmar_eliminar_ingreso/'.$record->id_ingreso; ?>', '<?php echo htmlspecialchars($record->descripcion, ENT_QUOTES); ?>')"
                                   title="Eliminar">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4"><div class="ing-empty"><i class="fa fa-arrow-circle-down"></i>No se encontraron ingresos.</div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="ing-pagination">
            <div class="ing-pag-info" id="ing-pag-info">—</div>
            <div class="ing-pag-btns" id="ingPaginacion"></div>
        </div>
    </div>

</div>
</div>

<script>
var ingFilasPorPagina = 15;

function paginarIngresos(pagina) {
    var filas  = Array.from(document.querySelectorAll('#ingTbody tr[data-visible="1"]'));
    var total  = filas.length;
    var inicio = (pagina - 1) * ingFilasPorPagina;

    document.querySelectorAll('#ingTbody tr').forEach(function(f) { f.style.display = 'none'; });
    filas.slice(inicio, inicio + ingFilasPorPagina).forEach(function(f) { f.style.display = ''; });

    var desde = total === 0 ? 0 : inicio + 1;
    var hasta  = Math.min(inicio + ingFilasPorPagina, total);
    document.getElementById('ing-pag-info').textContent = total === 0
        ? 'Sin resultados' : 'Mostrando ' + desde + '–' + hasta + ' de ' + total + ' ingresos';

    var paginas = Math.ceil(total / ingFilasPorPagina);
    var html = '';
    if (paginas > 1) {
        html += '<button onclick="paginarIngresos(' + Math.max(1, pagina - 1) + ')" ' + (pagina === 1 ? 'disabled' : '') + '>‹</button>';
        var start = Math.max(1, pagina - 2), end = Math.min(paginas, pagina + 2);
        if (start > 1) html += '<button onclick="paginarIngresos(1)">1</button>' + (start > 2 ? '<button disabled>…</button>' : '');
        for (var i = start; i <= end; i++) html += '<button class="' + (i === pagina ? 'active' : '') + '" onclick="paginarIngresos(' + i + ')">' + i + '</button>';
        if (end < paginas) html += (end < paginas - 1 ? '<button disabled>…</button>' : '') + '<button onclick="paginarIngresos(' + paginas + ')">' + paginas + '</button>';
        html += '<button onclick="paginarIngresos(' + Math.min(paginas, pagina + 1) + ')" ' + (pagina === paginas ? 'disabled' : '') + '>›</button>';
    }
    document.getElementById('ingPaginacion').innerHTML = html;
}

function filtrarIngresos() {
    var q = document.getElementById('ingSearch').value.toLowerCase().trim();
    document.querySelectorAll('#ingTbody tr').forEach(function(fila) {
        if (!fila.querySelector('td')) return;
        var txt = fila.textContent.toLowerCase();
        fila.dataset.visible = (q === '' || txt.indexOf(q) !== -1) ? '1' : '0';
    });
    paginarIngresos(1);
}

function limpiarIngFiltro() {
    document.getElementById('ingSearch').value = '';
    filtrarIngresos();
}

function confirmarEliminarIng(e, url, desc) {
    e.preventDefault();
    if (confirm('¿Eliminar el ingreso "' + desc + '"?\nEsta acción revertirá el efecto en la caja.')) {
        window.location.href = url;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#ingTbody tr').forEach(function(f) { f.dataset.visible = '1'; });
    paginarIngresos(1);
});
</script>
