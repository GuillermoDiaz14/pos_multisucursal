<style>
.cat-pag-btns button{min-width:30px;height:28px;padding:0 8px;border:1px solid #ddd;border-radius:4px;background:#fff;font-size:12px;cursor:pointer;color:#555}
.cat-pag-btns button:hover{background:#ecf0f1}
.cat-pag-btns button.active{background:#3c8dbc;color:#fff;border-color:#367fa9;font-weight:600}
.cat-pag-btns button:disabled{opacity:.4;cursor:default}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-tags"></i> Categorías <small>Catálogo de categorías</small></h1>
    </section>
    <section class="content">

        <?php $this->load->helper('form'); ?>
        <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('success'); ?>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-table"></i> Lista de categorías</h3>
                        <div class="box-tools pull-right" style="display:flex;gap:6px;align-items:center;">
                            <div class="input-group input-group-sm" style="width:220px">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                <input type="text" id="catSearch" class="form-control"
                                       placeholder="Buscar categoría…" autofocus
                                       oninput="onCatSearchInput()" />
                                <span class="input-group-btn">
                                    <button class="btn btn-sm btn-default" onclick="limpiarCatFiltro()" title="Limpiar">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </span>
                            </div>
                            <a class="btn btn-sm btn-primary" href="<?php echo base_url('categoria/add'); ?>">
                                <i class="fa fa-plus"></i> Agregar
                            </a>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="catTabla">
                            <thead>
                                <tr>
                                    <th style="width:60px">ID</th>
                                    <th>Nombre</th>
                                    <th class="text-center" style="width:120px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="catTbody">
                                <?php include('table_partial.php'); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="box-footer clearfix">
                        <small class="text-muted pull-left" id="cat-pag-info" style="line-height:28px">—</small>
                        <div class="pull-right cat-pag-btns" id="catPaginacion"></div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<script>
var _catDebounce = null;
var _catPerPage  = <?php echo (int)($per_page ?? 50); ?>;

function filtrarCategorias(pagina) {
    pagina = pagina || 1;
    $.ajax({
        url:  '<?php echo base_url(); ?>categoria/filterCategorias',
        type: 'POST',
        data: { searchText: document.getElementById('catSearch').value, page: pagina },
        success: function(resp) {
            $('#catTbody').html(resp.html);
            renderCatPaginacion(resp.page, resp.total, resp.pages, resp.limit);
        }
    });
}

function renderCatPaginacion(pagina, total, paginas, limit) {
    var desde = total === 0 ? 0 : (pagina - 1) * limit + 1;
    var hasta  = Math.min(pagina * limit, total);
    document.getElementById('cat-pag-info').textContent = total === 0
        ? 'Sin resultados' : 'Mostrando ' + desde + '–' + hasta + ' de ' + total + ' categorías';

    var html = '';
    if (paginas > 1) {
        html += '<button onclick="filtrarCategorias(' + Math.max(1, pagina-1) + ')" ' + (pagina===1?'disabled':'') + '>‹</button>';
        var start = Math.max(1, pagina-2), end = Math.min(paginas, pagina+2);
        if (start > 1) html += '<button onclick="filtrarCategorias(1)">1</button>' + (start>2?'<button disabled>…</button>':'');
        for (var i = start; i <= end; i++) html += '<button class="'+(i===pagina?'active':'')+'" onclick="filtrarCategorias('+i+')">'+i+'</button>';
        if (end < paginas) html += (end<paginas-1?'<button disabled>…</button>':'')+'<button onclick="filtrarCategorias('+paginas+')">'+paginas+'</button>';
        html += '<button onclick="filtrarCategorias(' + Math.min(paginas, pagina+1) + ')" ' + (pagina===paginas?'disabled':'') + '>›</button>';
    }
    document.getElementById('catPaginacion').innerHTML = html;
}

function onCatSearchInput() {
    clearTimeout(_catDebounce);
    _catDebounce = setTimeout(function() { filtrarCategorias(1); }, 350);
}

function limpiarCatFiltro() {
    document.getElementById('catSearch').value = '';
    filtrarCategorias(1);
}

document.addEventListener('DOMContentLoaded', function() {
    var total   = <?php echo (int)($total_count ?? 0); ?>;
    var paginas = Math.ceil(total / _catPerPage);
    renderCatPaginacion(<?php echo (int)($page ?? 1); ?>, total, paginas, _catPerPage);
});
</script>
