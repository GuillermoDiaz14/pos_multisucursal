<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-exchange"></i> Traslados enviados <small>Reporte</small></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('reporte_administrador/seleccion_traslado'); ?>"><i class="fa fa-arrow-left"></i> Cambiar sucursal</a></li>
        </ol>
    </section>

    <section class="content report-shell"
             data-report-root
             data-report-title="Traslados enviados — <?php echo htmlspecialchars($nombre_sucursal); ?>"
             data-report-subtitle="Movimientos de inventario enviados desde esta sucursal">

        <?php
        $reportExportTitle       = 'Traslados enviados — ' . $nombre_sucursal;
        $reportExportSubtitle    = 'Movimientos de inventario enviados desde esta sucursal';
        $reportExportOrientation = 'L';
        $this->load->view('reporte/partials/report_toolbar');
        ?>

        <!-- Sucursal info bar -->
        <div style="background:#f4f7fb;border:1px solid #d8e1eb;border-radius:12px;padding:12px 18px;margin-bottom:18px;display:flex;align-items:center;gap:12px">
            <i class="fa fa-building-o" style="font-size:20px;color:#0f766e"></i>
            <div>
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:.06em;color:#6b7280">Sucursal</div>
                <strong style="font-size:15px;color:#16324f"><?php echo htmlspecialchars($nombre_sucursal); ?></strong>
            </div>
            <span style="margin-left:auto;font-size:12px;color:#6b7280">
                <i class="fa fa-arrow-right"></i> Traslados <strong>enviados</strong> a otras sucursales
            </span>
        </div>

        <?php $this->load->helper('form'); ?>
        <?php if ($error = $this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <input type="hidden" id="id_sucursal" value="<?php echo (int)$id_sucursal; ?>">
                    <div class="box-header with-border" style="display:flex;align-items:center;justify-content:space-between;">
                        <h3 class="box-title" style="margin:0"><i class="fa fa-list"></i> Lista de traslados enviados</h3>
                        <div class="input-group input-group-sm" style="width:230px">
                            <input type="text" class="form-control" id="searchText"
                                   placeholder="Sucursal / N° / Usuario" oninput="filterTable()" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-condensed" id="miTabla">
                            <thead>
                                <tr>
                                    <th style="width:90px">N° traslado</th>
                                    <th>Sucursal destino</th>
                                    <th style="width:100px">Fecha</th>
                                    <th style="width:140px">Usuario</th>
                                    <th>Comentario</th>
                                    <th style="width:70px" class="text-center">Ticket</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-traslados">
                            <?php if (!empty($records)): foreach ($records as $record): ?>
                            <tr>
                                <td><span class="label label-default">#<?php echo $record->id_traslado; ?></span></td>
                                <td>
                                    <i class="fa fa-building-o text-muted" style="margin-right:4px"></i>
                                    <?php echo htmlspecialchars($record->sucursal_traslado); ?>
                                </td>
                                <td class="text-nowrap text-muted">
                                    <i class="fa fa-calendar-o" style="margin-right:3px"></i>
                                    <?php echo fmt_fecha($record->fecha_actual, false, '-'); ?>
                                </td>
                                <td>
                                    <i class="fa fa-user-o text-muted" style="margin-right:4px"></i>
                                    <?php echo htmlspecialchars($record->nombre_usuario ?? '—'); ?>
                                </td>
                                <td class="text-muted"><?php echo htmlspecialchars($record->comentario); ?></td>
                                <td class="text-center">
                                    <a class="btn btn-xs btn-info"
                                       href="<?php echo base_url('trasladar/exportToPDF/' . $record->id_traslado); ?>"
                                       title="Ver ticket" target="_blank">
                                        <i class="fa fa-file-text-o"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding:40px 20px">
                                    <i class="fa fa-exchange fa-2x" style="display:block;margin-bottom:10px;opacity:.3"></i>
                                    No se encontraron traslados enviados para esta sucursal.
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
    var FILAS = 10;
    var pag   = 1;

    function rows() { return document.querySelectorAll('#tbody-traslados tr'); }

    function mostrar(p) {
        var ini = (p - 1) * FILAS, fin = ini + FILAS;
        rows().forEach(function (tr, i) { tr.style.display = (i >= ini && i < fin) ? '' : 'none'; });
    }

    function paginar() {
        var total = Math.ceil(rows().length / FILAS);
        var div   = document.getElementById('paginacion');
        div.innerHTML = '';
        if (total <= 1) { mostrar(1); return; }
        for (var i = 1; i <= total; i++) {
            (function (p) {
                var b = document.createElement('button');
                b.textContent = p;
                b.className   = 'btn btn-xs ' + (p === pag ? 'btn-primary' : 'btn-default');
                b.style.margin = '0 2px';
                b.onclick = function () { pag = p; mostrar(p); paginar(); };
                div.appendChild(b);
            })(i);
        }
        mostrar(pag);
    }

    window.filterTable = function () {
        $.ajax({
            url: '<?php echo base_url('reporte_administrador/filterTrasladar'); ?>',
            type: 'POST',
            data: {
                searchText:  document.getElementById('searchText').value,
                id_sucursal: document.getElementById('id_sucursal').value
            },
            success: function (html) {
                pag = 1;
                $('#tbody-traslados').html(html);
                paginar();
            }
        });
    };

    document.addEventListener('DOMContentLoaded', function () { pag = 1; paginar(); });
})();
</script>
