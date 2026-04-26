
<?php
$venta = isset($ventas[0]) ? $ventas[0] : null;
if ($venta) {
    $total        = (float)$venta->total;
    $saldo        = (float)$venta->saldo;
    $deuda        = $total - $saldo;
    $anticipo     = isset($venta->anticipo) ? (float)$venta->anticipo : 0;
    $estado       = isset($venta->estado_apartado) ? $venta->estado_apartado : 'en_proceso';
    $id_venta     = $venta->id_venta;
    $nombre_cliente = isset($venta->nombre_cliente) ? $venta->nombre_cliente : '';
    $fecha_venta  = $venta->fecha_venta;
}
$badgeClass  = 'label-warning';
$estadoLabel = 'En proceso';
if ($estado === 'entregado') { $badgeClass = 'label-success'; $estadoLabel = 'Entregado'; }
if ($estado === 'cancelado') { $badgeClass = 'label-danger';  $estadoLabel = 'Cancelado'; }
$porcentaje = ($total > 0) ? min(100, round(($saldo / $total) * 100)) : 0;
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-tags"></i> Apartado #<?php echo $id_venta; ?>
            &nbsp;<span class="label <?php echo $badgeClass; ?>" style="font-size:13px;">
                <?php echo $estadoLabel; ?>
            </span>
            <small style="margin-left:8px;"><?php echo htmlspecialchars($nombre_cliente, ENT_QUOTES); ?></small>
        </h1>
        <a href="<?php echo base_url(); ?>carrito/apartado_lista" class="btn btn-default btn-sm">
            <i class="fa fa-arrow-left"></i> Volver
        </a>
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

            <!-- Columna izquierda: resumen + acciones -->
            <div class="col-md-4">

                <!-- Resumen financiero -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"></i> Resumen</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-condensed" style="margin-bottom:8px;">
                            <tr><td><strong>Cliente:</strong></td><td><?php echo htmlspecialchars($nombre_cliente, ENT_QUOTES); ?></td></tr>
                            <tr><td><strong>Fecha:</strong></td><td><?php echo $fecha_venta; ?></td></tr>
                            <tr><td><strong>Total venta:</strong></td><td><?php echo '$' . number_format($total, 2); ?></td></tr>
                            <tr><td><strong>Anticipo inicial:</strong></td><td><?php echo '$' . number_format($anticipo, 2); ?></td></tr>
                            <tr>
                                <td><strong>Total pagado:</strong></td>
                                <td class="text-success"><strong><?php echo '$' . number_format($saldo, 2); ?></strong></td>
                            </tr>
                            <tr>
                                <td><strong>Deuda restante:</strong></td>
                                <td class="<?php echo $deuda > 0 ? 'text-danger' : 'text-success'; ?>">
                                    <strong><?php echo '$' . number_format($deuda, 2); ?></strong>
                                </td>
                            </tr>
                        </table>
                        <div class="progress" style="margin-bottom:0;">
                            <div class="progress-bar progress-bar-success" style="width:<?php echo $porcentaje; ?>%; min-width:2em;">
                                <?php echo $porcentaje; ?>%
                            </div>
                        </div>
                        <?php if ($deuda <= 0): ?>
                            <p class="text-success text-center" style="margin-top:6px;"><i class="fa fa-check-circle"></i> <strong>¡Pagado completamente!</strong></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Acciones -->
                <?php if ($estado === 'en_proceso'): ?>
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Acciones</h3>
                    </div>
                    <div class="box-body">
                        <?php if ($deuda <= 0.009): ?>
                            <a href="<?php echo base_url() . 'carrito/entregar_apartado/' . $id_venta; ?>"
                               class="btn btn-success btn-block"
                               onclick="return confirm('¿Confirmar entrega del producto al cliente?')">
                                <i class="fa fa-check-circle"></i> Marcar como Entregado
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo base_url() . 'carrito/cancelar_apartado/' . $id_venta; ?>"
                           class="btn btn-danger btn-block"
                           style="margin-top:6px;"
                           onclick="return confirm('¿Cancelar este apartado? El inventario será restaurado y los pagos revertidos.')">
                            <i class="fa fa-times-circle"></i> Cancelar Apartado
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <a href="<?php echo base_url() . 'carrito/exportToPDF/' . $id_venta; ?>"
                   target="_blank" class="btn btn-default btn-block">
                    <i class="fa fa-print"></i> Imprimir Ticket
                </a>

            </div>

            <!-- Columna derecha: pago + productos + historial -->
            <div class="col-md-8">

                <!-- Registrar pago -->
                <?php if ($estado === 'en_proceso' && $deuda > 0.009): ?>
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-money"></i> Registrar pago</h3>
                    </div>
                    <form role="form" action="<?php echo base_url(); ?>carrito/cuota_agregar" method="post">
                        <div class="box-body">
                            <input type="hidden" name="id_venta" value="<?php echo $id_venta; ?>">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Deuda actual</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" class="form-control" id="totaldeuda"
                                                   value="<?php echo number_format($deuda, 2); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Monto a pagar <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="number" name="cuota" id="cuota" class="form-control"
                                                   min="0.01" step="0.01" max="<?php echo $deuda; ?>"
                                                   placeholder="0.00" oninput="actualizarNuevaDeuda()" autofocus>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Queda por pagar</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" class="form-control" id="nueva_deuda" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Método de pago <span class="text-danger">*</span></label>
                                        <select name="id_metodo_pago" id="id_metodo_pago_apartado" class="form-control" required>
                                            <option value="">-- Selecciona método --</option>
                                            <?php if (!empty($metodos_pago)) {
                                                foreach ($metodos_pago as $mp) { ?>
                                                <option value="<?php echo (int)$mp->id_metodo_pago; ?>">
                                                    <?php echo htmlspecialchars($mp->nombre_metodo_pago, ENT_QUOTES); ?>
                                                </option>
                                            <?php   }
                                            } ?>
                                        </select>
                                        <small class="text-muted">Solo los pagos en efectivo afectan la caja.</small>
                                    </div>
                                </div>
                            </div>
                            <p id="error-pago" class="text-danger" style="margin:0;"></p>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success" id="btnRegistrarPago" disabled>
                                <i class="fa fa-save"></i> Registrar pago
                            </button>
                        </div>
                    </form>
                </div>
                <?php elseif ($estado === 'entregado'): ?>
                    <div class="alert alert-success"><i class="fa fa-check-circle"></i> Apartado entregado al cliente.</div>
                <?php elseif ($estado === 'cancelado'): ?>
                    <div class="alert alert-danger"><i class="fa fa-ban"></i> Este apartado fue cancelado.</div>
                <?php else: ?>
                    <div class="alert alert-info"><i class="fa fa-info-circle"></i> Pago completado. Usa <strong>Marcar como Entregado</strong>.</div>
                <?php endif; ?>

                <!-- Productos apartados -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-shopping-cart"></i> Productos apartados</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-right">Precio unit.</th>
                                    <th class="text-right">Cantidad</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($detalles)): ?>
                                    <?php foreach ($detalles as $det): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($det->nombre_producto, ENT_QUOTES); ?></td>
                                            <td class="text-right">$<?php echo number_format((float)$det->precio_individual, 2); ?></td>
                                            <td class="text-right"><?php echo $det->cantidad; ?></td>
                                            <td class="text-right">$<?php echo number_format((float)$det->sub_total, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center text-muted">Sin productos</td></tr>
                                <?php endif; ?>
                            </tbody>
                            <?php if (!empty($detalles)): ?>
                            <tfoot>
                                <tr class="active">
                                    <td colspan="3"><strong>Total</strong></td>
                                    <td class="text-right"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                                </tr>
                            </tfoot>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>

                <!-- Historial de pagos -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-history"></i> Historial de pagos</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-condensed">
                            <thead>
                                <tr><th>#</th><th>Fecha</th><th class="text-right">Monto</th></tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($cuotas)): ?>
                                    <?php foreach ($cuotas as $c): ?>
                                        <tr>
                                            <td><?php echo $c->id_cuota; ?></td>
                                            <td><?php echo $c->fecha_pago; ?></td>
                                            <td class="text-right text-success"><strong>$<?php echo number_format((float)$c->cuota, 2); ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="info">
                                        <td colspan="2"><strong>Total pagado</strong></td>
                                        <td class="text-right"><strong>$<?php echo number_format($saldo, 2); ?></strong></td>
                                    </tr>
                                <?php else: ?>
                                    <tr><td colspan="3" class="text-center text-muted">Sin pagos registrados</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<script>
var deudaActual = <?php echo $deuda; ?>;

function actualizarNuevaDeuda() {
    var cuota = parseFloat(document.getElementById('cuota').value) || 0;
    var btn = document.getElementById('btnRegistrarPago');
    var err = document.getElementById('error-pago');
    var metodo = document.getElementById('id_metodo_pago_apartado');
    var metodoOk = metodo && metodo.value !== '';

    if (cuota <= 0) {
        err.textContent = 'El monto debe ser mayor a 0.';
        btn.disabled = true;
        document.getElementById('nueva_deuda').value = '';
        return;
    }
    if (cuota > deudaActual + 0.009) {
        err.textContent = 'El monto no puede superar la deuda de $' + deudaActual.toFixed(2) + '.';
        document.getElementById('cuota').value = deudaActual.toFixed(2);
        document.getElementById('nueva_deuda').value = '0.00';
        btn.disabled = !metodoOk;
        return;
    }
    if (!metodoOk) {
        err.textContent = 'Selecciona un método de pago.';
        btn.disabled = true;
        document.getElementById('nueva_deuda').value = Math.max(0, deudaActual - cuota).toFixed(2);
        return;
    }
    err.textContent = '';
    btn.disabled = false;
    document.getElementById('nueva_deuda').value = Math.max(0, deudaActual - cuota).toFixed(2);
}

document.addEventListener('DOMContentLoaded', function () {
    var metodo = document.getElementById('id_metodo_pago_apartado');
    if (metodo) {
        metodo.addEventListener('change', actualizarNuevaDeuda);
    }
});
</script>
