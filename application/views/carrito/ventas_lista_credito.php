<?php
$resumen = ['total_monto' => 0, 'total_base' => 0, 'total_impuesto' => 0, 'total_descuento' => 0, 'count' => 0];
if (!empty($records)) {
    foreach ($records as $r) {
        $resumen['total_monto']     += (float)$r->total;
        $resumen['total_base']      += (float)$r->base_imponible;
        $resumen['total_impuesto']  += (float)$r->impuesto;
        $resumen['total_descuento'] += (float)$r->descuento;
        $resumen['count']++;
    }
}
$promedio = $resumen['count'] > 0 ? $resumen['total_monto'] / $resumen['count'] : 0;
?>
<style>
.hv-wrapper{padding:16px 20px}
.hv-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px}
.hv-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);padding:14px 16px;display:flex;align-items:center;gap:12px}
.hv-card-icon{width:46px;height:46px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;flex-shrink:0}
.hv-card-icon.blue{background:#2980b9}.hv-card-icon.navy{background:#1a5276}.hv-card-icon.red{background:#e74c3c}.hv-card-icon.gray{background:#7f8c8d}
.hv-card-value{font-size:20px;font-weight:700;color:#2c3e50;line-height:1.1}
.hv-card-label{font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;margin-top:1px}
.hv-box{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden}
.hv-box-header{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #ecf0f1;flex-wrap:wrap;gap:8px}
.hv-box-title{font-size:15px;font-weight:600;color:#2c3e50;margin:0}
.hv-filters{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.hv-search-wrap{position:relative}
.hv-search-wrap input{padding-left:30px;border-radius:5px;border:1px solid #ddd;height:32px;font-size:13px;width:220px}
.hv-search-wrap .fa-search{position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#aaa;font-size:13px;pointer-events:none}
.hv-btn-clear{height:32px;padding:0 12px;font-size:13px;border:1px solid #ddd;border-radius:5px;background:#fff;color:#777;cursor:pointer}
.hv-btn-clear:hover{background:#f5f5f5}
.hv-table{width:100%;border-collapse:collapse;font-size:13px}
.hv-table thead th{background:#f8f9fa;padding:10px 12px;text-align:left;font-weight:600;color:#555;border-bottom:2px solid #e9ecef;white-space:nowrap}
.hv-table thead th.text-right{text-align:right}.hv-table thead th.text-center{text-align:center}
.hv-table tbody tr{border-bottom:1px solid #f2f2f2;transition:background .1s}
.hv-table tbody tr:hover{background:#fafbfc}
.hv-table td{padding:9px 12px;vertical-align:middle}
.hv-table td.text-right{text-align:right}.hv-table td.text-center{text-align:center}
.venta-id{font-weight:600;color:#7f8c8d;font-size:12px}
.fecha-cell{white-space:nowrap}
.fecha-day{font-weight:600;color:#2c3e50}
.fecha-time{font-size:11px;color:#aaa;display:block}
.monto-total{font-weight:700;color:#2c3e50}
.monto-descuento{color:#e74c3c;font-size:12px}
.hv-actions{display:flex;gap:4px;justify-content:center;white-space:nowrap}
.hv-actions .btn{padding:3px 8px;font-size:12px;border-radius:4px}
.hv-empty{text-align:center;padding:40px 0;color:#aaa}
.hv-empty i{font-size:40px;display:block;margin-bottom:10px}
.hv-pagination{display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-top:1px solid #f0f0f0;flex-wrap:wrap;gap:8px}
.hv-pag-info{font-size:12px;color:#888}
.hv-pag-btns{display:flex;gap:4px;flex-wrap:wrap}
.hv-pag-btns button{min-width:30px;height:28px;padding:0 8px;border:1px solid #ddd;border-radius:4px;background:#fff;font-size:12px;cursor:pointer;color:#555;transition:all .12s}
.hv-pag-btns button:hover{background:#ecf0f1}
.hv-pag-btns button.active{background:#2980b9;color:#fff;border-color:#2980b9;font-weight:600}
.hv-pag-btns button:disabled{opacity:.4;cursor:default}
/* Badge estado crédito */
.badge-pagado{display:inline-block;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;background:#eafaf1;color:#1e8449;border:1px solid #a9dfbf}
.badge-pendiente{display:inline-block;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;background:#ebf5fb;color:#1a5276;border:1px solid #aed6f1}
.badge-vencido{display:inline-block;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;background:#fdedec;color:#922b21;border:1px solid #f1948a}
@media(max-width:900px){.hv-cards{grid-template-columns:repeat(2,1fr)}}
@media(max-width:576px){.hv-wrapper{padding:10px}.hv-cards{grid-template-columns:1fr 1fr;gap:8px}.hv-card{padding:10px 12px}.hv-card-value{font-size:17px}.hv-card-icon{width:38px;height:38px;font-size:17px}.hv-search-wrap input{width:160px}.col-base,.col-impuesto{display:none}}
</style>

<div class="content-wrapper">
<div class="hv-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-credit-card text-primary"></i> Ventas a crédito
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;">Ventas con pago diferido</p>
        </div>
        <a class="btn btn-success btn-sm" href="<?php echo base_url(); ?>carrito/carrito?tipo_pago=credito">
            <i class="fa fa-plus"></i> Nueva venta a crédito
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

    <!-- Tarjetas -->
    <div class="hv-cards">
        <div class="hv-card">
            <div class="hv-card-icon blue"><i class="fa fa-dollar"></i></div>
            <div class="hv-card-body">
                <div class="hv-card-value">$<?php echo number_format($resumen['total_monto'], 2); ?></div>
                <div class="hv-card-label">Total a crédito</div>
            </div>
        </div>
        <div class="hv-card">
            <div class="hv-card-icon gray"><i class="fa fa-files-o"></i></div>
            <div class="hv-card-body">
                <div class="hv-card-value"><?php echo $resumen['count']; ?></div>
                <div class="hv-card-label">Ventas crédito</div>
            </div>
        </div>
        <div class="hv-card">
            <div class="hv-card-icon red"><i class="fa fa-tag"></i></div>
            <div class="hv-card-body">
                <div class="hv-card-value">$<?php echo number_format($resumen['total_descuento'], 2); ?></div>
                <div class="hv-card-label">Total descuentos</div>
            </div>
        </div>
        <div class="hv-card">
            <div class="hv-card-icon navy"><i class="fa fa-bar-chart"></i></div>
            <div class="hv-card-body">
                <div class="hv-card-value">$<?php echo number_format($promedio, 2); ?></div>
                <div class="hv-card-label">Promedio por venta</div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="hv-box">
        <div class="hv-box-header">
            <h4 class="hv-box-title"><i class="fa fa-table"></i> Ventas a crédito registradas</h4>
            <div class="hv-filters">
                <div class="hv-search-wrap">
                    <i class="fa fa-search"></i>
                    <input type="text" id="searchText" placeholder="Buscar cliente o #venta…"
                           autofocus oninput="onSearchInput()"
                           value="<?php echo htmlspecialchars($searchText ?? '', ENT_QUOTES); ?>">
                </div>
                <button class="hv-btn-clear" onclick="limpiarFiltros()" title="Limpiar">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="hv-table" id="tablaVentasCredito">
                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th class="text-right col-base">Subtotal neto</th>
                        <th class="text-right col-impuesto">Impuesto</th>
                        <th class="text-right">Descuento</th>
                        <th class="text-right">Total</th>
                        <th class="text-center" style="width:110px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaVentasCreditoTbody">
                    <?php include('table_partial_credito.php'); ?>
                </tbody>
            </table>
        </div>

        <div class="hv-pagination">
            <div class="hv-pag-info" id="pag-info-credito">—</div>
            <div class="hv-pag-btns" id="paginacionCredito"></div>
        </div>
    </div>

</div>
</div>

<script>
var _debounceTimerCr = null;
var _perPageCr = <?php echo (int)($per_page ?? 50); ?>;

function filtrarTabla(pagina) {
    pagina = pagina || 1;
    $.ajax({
        url: '<?php echo base_url(); ?>carrito/filterVentas_credito',
        type: 'POST',
        data: { searchText: document.getElementById('searchText').value, page: pagina },
        success: function(resp) {
            $('#tablaVentasCreditoTbody').html(resp.html);
            renderPaginacionCr(resp.page, resp.total, resp.pages, resp.limit);
        }
    });
}

function renderPaginacionCr(pagina, total, paginas, limit) {
    var desde = total === 0 ? 0 : (pagina - 1) * limit + 1;
    var hasta  = Math.min(pagina * limit, total);
    document.getElementById('pag-info-credito').textContent = total === 0
        ? 'Sin resultados' : 'Mostrando ' + desde + '–' + hasta + ' de ' + total + ' ventas';

    var html = '';
    if (paginas > 1) {
        html += '<button onclick="filtrarTabla(' + Math.max(1, pagina-1) + ')" ' + (pagina===1?'disabled':'') + '>‹</button>';
        var start = Math.max(1, pagina-2), end = Math.min(paginas, pagina+2);
        if (start > 1) html += '<button onclick="filtrarTabla(1)">1</button>' + (start>2?'<button disabled>…</button>':'');
        for (var i = start; i <= end; i++) html += '<button class="'+(i===pagina?'active':'')+'" onclick="filtrarTabla('+i+')">'+i+'</button>';
        if (end < paginas) html += (end<paginas-1?'<button disabled>…</button>':'')+'<button onclick="filtrarTabla('+paginas+')">'+paginas+'</button>';
        html += '<button onclick="filtrarTabla(' + Math.min(paginas, pagina+1) + ')" ' + (pagina===paginas?'disabled':'') + '>›</button>';
    }
    document.getElementById('paginacionCredito').innerHTML = html;
}

function onSearchInput() {
    clearTimeout(_debounceTimerCr);
    _debounceTimerCr = setTimeout(function() { filtrarTabla(1); }, 350);
}

function limpiarFiltros() {
    document.getElementById('searchText').value = '';
    filtrarTabla(1);
}

document.addEventListener('DOMContentLoaded', function() {
    var total  = <?php echo (int)($total_count ?? 0); ?>;
    var paginas = Math.ceil(total / _perPageCr);
    renderPaginacionCr(<?php echo (int)($page ?? 1); ?>, total, paginas, _perPageCr);
});
</script>
