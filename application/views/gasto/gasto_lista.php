<style>
.gas-wrapper{padding:16px 20px}
.gas-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px}
.gas-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);padding:14px 16px;display:flex;align-items:center;gap:12px}
.gas-card-icon{width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;flex-shrink:0}
.gas-card-icon.red{background:#e74c3c}.gas-card-icon.orange{background:#e67e22}.gas-card-icon.purple{background:#8e44ad}
.gas-card-value{font-size:20px;font-weight:700;color:#2c3e50;line-height:1.1}
.gas-card-label{font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;margin-top:2px}
.gas-box{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden}
.gas-box-header{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #ecf0f1;flex-wrap:wrap;gap:8px}
.gas-box-title{font-size:15px;font-weight:600;color:#2c3e50;margin:0}
.gas-search-wrap{position:relative}
.gas-search-wrap input{padding-left:30px;border-radius:5px;border:1px solid #ddd;height:32px;font-size:13px;width:230px}
.gas-search-wrap .fa-search{position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#aaa;font-size:13px;pointer-events:none}
.gas-btn-clear{height:32px;padding:0 12px;font-size:13px;border:1px solid #ddd;border-radius:5px;background:#fff;color:#777;cursor:pointer}
.gas-btn-clear:hover{background:#f5f5f5}
.gas-table{width:100%;border-collapse:collapse;font-size:13px}
.gas-table thead th{background:#f8f9fa;padding:10px 12px;text-align:left;font-weight:600;color:#555;border-bottom:2px solid #e9ecef;white-space:nowrap}
.gas-table thead th.text-center{text-align:center}
.gas-table tbody tr{border-bottom:1px solid #f2f2f2;transition:background .1s}
.gas-table tbody tr:hover{background:#fafbfc}
.gas-table td{padding:9px 12px;vertical-align:middle}
.gas-table td.text-center{text-align:center}
.gas-desc{font-weight:600;color:#2c3e50;max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.gas-monto{font-weight:700;color:#e74c3c}
.gas-actions{display:flex;gap:4px;justify-content:center}
.gas-actions .btn{padding:3px 8px;font-size:12px;border-radius:4px}
.gas-empty{text-align:center;padding:40px 0;color:#aaa}
.gas-empty i{font-size:40px;display:block;margin-bottom:10px}
.gas-pagination{display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-top:1px solid #f0f0f0;flex-wrap:wrap;gap:8px}
.gas-pag-info{font-size:12px;color:#888}
.gas-pag-btns{display:flex;gap:4px;flex-wrap:wrap}
.gas-pag-btns button{min-width:30px;height:28px;padding:0 8px;border:1px solid #ddd;border-radius:4px;background:#fff;font-size:12px;cursor:pointer;color:#555;transition:all .12s}
.gas-pag-btns button:hover{background:#ecf0f1}
.gas-pag-btns button.active{background:#e74c3c;color:#fff;border-color:#e74c3c;font-weight:600}
.gas-pag-btns button:disabled{opacity:.4;cursor:default}
@media(max-width:768px){.gas-wrapper{padding:10px}.gas-search-wrap input{width:160px}.gas-cards{grid-template-columns:1fr}.col-gas-fecha{display:none}}
</style>

<div class="content-wrapper">
<div class="gas-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-money text-danger"></i> Gastos
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;">Registro y control de gastos</p>
        </div>
        <a class="btn btn-danger btn-sm" href="<?php echo base_url(); ?>gasto/add">
            <i class="fa fa-plus"></i> Registrar gasto
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
    $totalGastos = isset($stats['total'])       ? $stats['total']       : 0;
    $totalMonto  = isset($stats['total_monto']) ? $stats['total_monto'] : 0;
    $montoMes    = isset($stats['monto_mes'])   ? $stats['monto_mes']   : 0;
    ?>

    <div class="gas-cards">
        <div class="gas-card">
            <div class="gas-card-icon red"><i class="fa fa-list-alt"></i></div>
            <div>
                <div class="gas-card-value"><?php echo $totalGastos; ?></div>
                <div class="gas-card-label">Total registros</div>
            </div>
        </div>
        <div class="gas-card">
            <div class="gas-card-icon orange"><i class="fa fa-money"></i></div>
            <div>
                <div class="gas-card-value">$<?php echo number_format($totalMonto, 2); ?></div>
                <div class="gas-card-label">Total gastos</div>
            </div>
        </div>
        <div class="gas-card">
            <div class="gas-card-icon purple"><i class="fa fa-calendar"></i></div>
            <div>
                <div class="gas-card-value">$<?php echo number_format($montoMes, 2); ?></div>
                <div class="gas-card-label">Mes actual</div>
            </div>
        </div>
    </div>

    <div class="gas-box">
        <div class="gas-box-header">
            <h4 class="gas-box-title"><i class="fa fa-table"></i> Lista de gastos</h4>
            <div style="display:flex;gap:8px;align-items:center;">
                <div class="gas-search-wrap">
                    <i class="fa fa-search"></i>
                    <input type="text" id="gasSearch" placeholder="Buscar por descripción…"
                           autofocus oninput="onGasSearchInput()" />
                </div>
                <button class="gas-btn-clear" onclick="limpiarGasFiltro()" title="Limpiar">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="gas-table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Monto</th>
                        <th class="col-gas-fecha">Fecha</th>
                        <th class="text-center" style="width:100px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="gasTbody">
                    <?php include('table_partial.php'); ?>
                </tbody>
            </table>
        </div>

        <div class="gas-pagination">
            <div class="gas-pag-info" id="gas-pag-info">—</div>
            <div class="gas-pag-btns" id="gasPaginacion"></div>
        </div>
    </div>

</div>
</div>

<script>
var _gasDebounce = null;
var _gasPerPage  = <?php echo (int)($per_page ?? 50); ?>;

function filtrarGastos(pagina) {
    pagina = pagina || 1;
    $.ajax({
        url:  '<?php echo base_url(); ?>gasto/filterGastos',
        type: 'POST',
        data: { searchText: document.getElementById('gasSearch').value, page: pagina },
        success: function(resp) {
            $('#gasTbody').html(resp.html);
            renderGasPaginacion(resp.page, resp.total, resp.pages, resp.limit);
        }
    });
}

function renderGasPaginacion(pagina, total, paginas, limit) {
    var desde = total === 0 ? 0 : (pagina - 1) * limit + 1;
    var hasta  = Math.min(pagina * limit, total);
    document.getElementById('gas-pag-info').textContent = total === 0
        ? 'Sin resultados' : 'Mostrando ' + desde + '–' + hasta + ' de ' + total + ' gastos';

    var html = '';
    if (paginas > 1) {
        html += '<button onclick="filtrarGastos(' + Math.max(1, pagina-1) + ')" ' + (pagina===1?'disabled':'') + '>‹</button>';
        var start = Math.max(1, pagina-2), end = Math.min(paginas, pagina+2);
        if (start > 1) html += '<button onclick="filtrarGastos(1)">1</button>' + (start>2?'<button disabled>…</button>':'');
        for (var i = start; i <= end; i++) html += '<button class="'+(i===pagina?'active':'')+'" onclick="filtrarGastos('+i+')">'+i+'</button>';
        if (end < paginas) html += (end<paginas-1?'<button disabled>…</button>':'')+'<button onclick="filtrarGastos('+paginas+')">'+paginas+'</button>';
        html += '<button onclick="filtrarGastos(' + Math.min(paginas, pagina+1) + ')" ' + (pagina===paginas?'disabled':'') + '>›</button>';
    }
    document.getElementById('gasPaginacion').innerHTML = html;
}

function onGasSearchInput() {
    clearTimeout(_gasDebounce);
    _gasDebounce = setTimeout(function() { filtrarGastos(1); }, 350);
}

function limpiarGasFiltro() {
    document.getElementById('gasSearch').value = '';
    filtrarGastos(1);
}

function confirmarEliminarGas(e, url, desc) {
    e.preventDefault();
    if (confirm('¿Eliminar el gasto "' + desc + '"?\nEsta acción revertirá el efecto en la caja.')) {
        window.location.href = url;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var total  = <?php echo (int)($total_count ?? 0); ?>;
    var paginas = Math.ceil(total / _gasPerPage);
    renderGasPaginacion(<?php echo (int)($page ?? 1); ?>, total, paginas, _gasPerPage);
});
</script>
