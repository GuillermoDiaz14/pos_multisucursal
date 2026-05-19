<style>
.pagina-actual { background-color: #007bff; color: white; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-exchange" aria-hidden="true"></i> Traslados recibidos <small>Inventario recibido de otras sucursales</small></h1>
    </section>
    <section class="content">

        <div class="row">
            <div class="col-xs-12 text-right">
                <a class="btn btn-primary" href="<?php echo base_url(); ?>trasladar">
                    <i class="fa fa-plus"></i> Nuevo traslado
                </a>
            </div>
        </div>

        <div class="row" style="margin-top:10px">
            <div class="col-md-12">
                <?php $this->load->helper('form'); ?>
                <?php if ($error = $this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
                <?php if ($success = $this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo $success; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Traslados recibidos</h3>
                        <div class="box-tools pull-right">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" style="width:200px"
                                       id="searchText" placeholder="Sucursal / N° traslado"
                                       oninput="filterTable()" />
                                <span class="input-group-btn">
                                    <button class="btn btn-default"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="miTabla">
                            <thead>
                                <tr>
                                    <th>N° traslado</th>
                                    <th>Sucursal origen</th>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Comentario</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-traslados">
                            <?php if (!empty($records)): foreach ($records as $record): ?>
                            <tr>
                                <td><?php echo $record->id_traslado; ?></td>
                                <td><?php echo htmlspecialchars($record->sucursal_traslado); ?></td>
                                <td><?php echo fmt_fecha($record->fecha_actual); ?></td>
                                <td><?php echo htmlspecialchars($record->nombre_usuario ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($record->comentario); ?></td>
                                <td class="text-center" style="white-space:nowrap;">
                                    <a class="btn btn-sm btn-primary"
                                       href="<?php echo base_url('trasladar/detalle/' . $record->id_traslado); ?>"
                                       title="Ver detalle">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a class="btn btn-sm btn-info"
                                       href="<?php echo base_url('trasladar/exportToPDF/' . $record->id_traslado); ?>"
                                       title="Ver ticket PDF" target="_blank">
                                        <i class="fa fa-file-text-o"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding:30px 20px">
                                    <i class="fa fa-search fa-2x" style="display:block;margin-bottom:8px"></i>
                                    No se han recibido traslados.
                                </td>
                            </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <div id="paginacion" class="text-center"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
(function () {
    var FILAS_POR_PAGINA = 10;
    var paginaActual = 1;

    function getFilas() {
        return document.querySelectorAll('#tbody-traslados tr');
    }

    function mostrarPagina(pagina) {
        var filas = getFilas();
        var inicio = (pagina - 1) * FILAS_POR_PAGINA;
        var fin    = inicio + FILAS_POR_PAGINA;
        filas.forEach(function (tr, i) {
            tr.style.display = (i >= inicio && i < fin) ? '' : 'none';
        });
    }

    function initPaginacion() {
        var filas = getFilas();
        var total = Math.ceil(filas.length / FILAS_POR_PAGINA);
        var div   = document.getElementById('paginacion');
        div.innerHTML = '';
        if (total <= 1) { mostrarPagina(1); return; }

        for (var i = 1; i <= total; i++) {
            (function (p) {
                var btn = document.createElement('button');
                btn.textContent = p;
                btn.className = 'btn btn-sm ' + (p === paginaActual ? 'btn-primary' : 'btn-default');
                btn.style.margin = '0 2px';
                btn.addEventListener('click', function () {
                    paginaActual = p;
                    mostrarPagina(paginaActual);
                    initPaginacion();
                });
                div.appendChild(btn);
            })(i);
        }
        mostrarPagina(paginaActual);
    }

    window.filterTable = function () {
        var searchText = document.getElementById('searchText').value;
        $.ajax({
            url: '<?php echo base_url('trasladar/filterTrasladarRecibidos'); ?>',
            type: 'POST',
            data: { searchText: searchText },
            success: function (html) {
                paginaActual = 1;
                $('#tbody-traslados').html(html);
                initPaginacion();
            }
        });
    };

    document.addEventListener('DOMContentLoaded', function () {
        paginaActual = 1;
        initPaginacion();
    });
})();
</script>
