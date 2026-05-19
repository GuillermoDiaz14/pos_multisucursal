<?php
/**
 * @var object $traslado
 * @var array  $detalles
 * @var bool   $es_origen
 * @var bool   $es_destino
 */
$total_items = 0;
foreach ($detalles as $d) { $total_items += (int)$d->cantidad; }
?>
<style>
.detalle-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px 24px;
    margin-bottom: 8px;
}
.detalle-grid .lbl {
    font-size: 11px;
    text-transform: uppercase;
    color: #90a4ae;
    letter-spacing: .5px;
}
.detalle-grid .val { font-size: 14px; font-weight: 600; color: #37474f; }
.detalle-table th { background:#f5f7f8; font-size: 12px; }
.detalle-table td, .detalle-table th { vertical-align: middle !important; }
.detalle-table .col-num { text-align: right; }
.role-badge { display:inline-block; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; }
.role-origen  { background:#fef9e7; color:#b45309; }
.role-destino { background:#e8f5e9; color:#1b5e20; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-exchange"></i> Detalle de traslado
            <small>#<?php echo (int)$traslado->id_traslado; ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('trasladar/trasladar_lista'); ?>">Traslados enviados</a></li>
            <li><a href="<?php echo base_url('trasladar/trasladar_lista_Recibidos'); ?>">Recibidos</a></li>
            <li class="active">#<?php echo (int)$traslado->id_traslado; ?></li>
        </ol>
    </section>

    <section class="content">

        <?php if (!empty($recien_registrado)): ?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-check-circle"></i>
            <strong>¡Traslado registrado correctamente!</strong>
            El detalle del traslado se muestra a continuación.
        </div>
        <?php endif; ?>

        <?php if ($success = $this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <i class="fa fa-check-circle"></i> <?php echo $success; ?>
        </div>
        <?php endif; ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-info-circle"></i> Información del traslado</h3>
                <div class="box-tools pull-right">
                    <?php if ($es_origen): ?>
                        <span class="role-badge role-origen">Origen</span>
                    <?php endif; ?>
                    <?php if ($es_destino): ?>
                        <span class="role-badge role-destino">Destino</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="box-body">
                <div class="detalle-grid">
                    <div>
                        <div class="lbl">N° Traslado</div>
                        <div class="val">#<?php echo (int)$traslado->id_traslado; ?></div>
                    </div>
                    <div>
                        <div class="lbl">Fecha</div>
                        <div class="val"><?php echo htmlspecialchars($traslado->fecha_actual); ?></div>
                    </div>
                    <div>
                        <div class="lbl">Sucursal origen</div>
                        <div class="val"><?php echo htmlspecialchars($traslado->nombre_sucursal_descuento ?? '—'); ?></div>
                    </div>
                    <div>
                        <div class="lbl">Sucursal destino</div>
                        <div class="val"><?php echo htmlspecialchars($traslado->nombre_sucursal_aumento ?? '—'); ?></div>
                    </div>
                    <div>
                        <div class="lbl">Realizado por</div>
                        <div class="val"><?php echo htmlspecialchars($traslado->nombre_usuario ?? '—'); ?></div>
                    </div>
                    <div>
                        <div class="lbl">Total de unidades</div>
                        <div class="val"><?php echo $total_items; ?></div>
                    </div>
                    <?php if (!empty($traslado->comentario)): ?>
                    <div style="grid-column: 1 / -1;">
                        <div class="lbl">Comentario</div>
                        <div class="val"><?php echo nl2br(htmlspecialchars($traslado->comentario)); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Productos trasladados (<?php echo count($detalles); ?>)</h3>
            </div>
            <div class="box-body no-padding">
                <table class="table table-condensed detalle-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Producto</th>
                            <th style="width:140px;">Código</th>
                            <th style="width:90px;">Talla</th>
                            <th style="width:100px;" class="col-num">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($detalles as $d):
                            $talla = !empty($d->talla) ? $d->talla : '—';
                            $tieneVar = (int)($d->tiene_variantes ?? 0) === 1;
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($d->nombre_producto); ?></td>
                            <td><code><?php echo htmlspecialchars($d->codigo); ?></code></td>
                            <td>
                                <?php if ($tieneVar): ?>
                                    <span class="label label-info"><?php echo htmlspecialchars($talla); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="col-num"><strong><?php echo (int)$d->cantidad; ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($detalles)): ?>
                        <tr><td colspan="5" class="text-center text-muted" style="padding:24px;">Este traslado no tiene productos.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($detalles)): ?>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total de unidades</th>
                            <th class="col-num"><?php echo $total_items; ?></th>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
            <div class="box-footer text-right">
                <button type="button" class="btn btn-success"
                        data-zebra-traslado="<?php echo (int)$traslado->id_traslado; ?>"
                        onclick="printZebraTraslado(<?php echo (int)$traslado->id_traslado; ?>)">
                    <i class="fa fa-print"></i> Imprimir ticket
                </button>
                <a href="<?php echo base_url('trasladar/exportToPDF/' . (int)$traslado->id_traslado); ?>"
                   class="btn btn-info" target="_blank" title="Versión PDF">
                    <i class="fa fa-file-pdf-o"></i> PDF
                </a>
                <a href="<?php echo base_url($es_destino ? 'trasladar/trasladar_lista_Recibidos' : 'trasladar/trasladar_lista'); ?>"
                   class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Volver al listado
                </a>
            </div>
        </div>

    </section>
</div>
