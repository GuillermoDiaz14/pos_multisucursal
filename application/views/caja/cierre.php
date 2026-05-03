<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> Cierre de caja
            <small>Arqueo del turno</small>
        </h1>
    </section>

    <section class="content">

        <?php
            $error   = $this->session->flashdata('error');
            $success = $this->session->flashdata('success');
        ?>
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Resumen del turno -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list-alt"></i> Resumen del turno</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-condensed">
                            <tr>
                                <td><strong>Cajero (apertura):</strong></td>
                                <td><?php echo htmlspecialchars($caja->nombre_usuario_apertura ?: '—', ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Apertura:</strong></td>
                                <td><?php echo fmt_fecha($caja->fecha_apertura, true); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Monto de apertura:</strong></td>
                                <td class="text-right">$<?php echo number_format((float)$resumen['monto_apertura'], 2); ?></td>
                            </tr>
                            <tr class="active">
                                <td colspan="2"><strong>Movimientos del turno</strong></td>
                            </tr>
                            <?php if (!empty($resumen['detalle_metodos'])): ?>
                                <?php foreach ($resumen['detalle_metodos'] as $nombre => $monto): ?>
                                    <tr>
                                        <td>Ventas: <?php echo htmlspecialchars($nombre, ENT_QUOTES); ?></td>
                                        <td class="text-right">$<?php echo number_format((float)$monto, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-muted">Sin ventas registradas en el turno.</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td>Ingresos extra:</td>
                                <td class="text-right">+ $<?php echo number_format((float)$resumen['ingresos'], 2); ?></td>
                            </tr>
                            <tr>
                                <td>Gastos:</td>
                                <td class="text-right text-danger">- $<?php echo number_format((float)$resumen['gastos'], 2); ?></td>
                            </tr>
                            <tr class="success">
                                <td><strong>Efectivo esperado en caja:</strong></td>
                                <td class="text-right"><strong>$<?php echo number_format((float)$resumen['saldo_sistema'], 2); ?></strong></td>
                            </tr>
                            <?php if ((float)$resumen['ventas_otros_metodos'] > 0): ?>
                                <tr>
                                    <td class="text-muted small" colspan="2">
                                        <em>Nota:</em> ventas en métodos no efectivo
                                        ($<?php echo number_format((float)$resumen['ventas_otros_metodos'], 2); ?>)
                                        no se contabilizan en el efectivo esperado.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Arqueo -->
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-balance-scale"></i> Arqueo de efectivo</h3>
                    </div>
                    <form id="formCierre" action="<?php echo base_url(); ?>caja/confirmar_cierre" method="post">
                        <div class="box-body">
                            <input type="hidden" name="id_caja" value="<?php echo (int)$caja->id_caja; ?>">

                            <div class="form-group">
                                <label>Efectivo esperado</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" id="efectivo_esperado" class="form-control"
                                           value="<?php echo number_format((float)$resumen['saldo_sistema'], 2, '.', ''); ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="efectivo_contado">Efectivo contado <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" name="efectivo_contado" id="efectivo_contado"
                                           class="form-control" step="0.01" min="0" required
                                           placeholder="0.00" oninput="calcularDiferencia()" autofocus>
                                </div>
                                <small class="text-muted">Cuenta el efectivo físico de la caja antes de cerrar.</small>
                            </div>

                            <div class="form-group">
                                <label>Diferencia</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" id="diferencia_visual" class="form-control" value="0.00" readonly>
                                </div>
                                <small id="diferencia_msg" class="text-muted">Cuadrada.</small>
                            </div>

                            <div class="form-group" id="grupoObservaciones">
                                <label for="observaciones">
                                    Observaciones
                                    <span id="obsRequerida" class="text-danger" style="display:none;">* (requerida si hay diferencia)</span>
                                </label>
                                <textarea name="observaciones" id="observaciones" class="form-control" rows="3"
                                          placeholder="Justifica el faltante o sobrante (ej. retiro autorizado, error de cobro...)"></textarea>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success" id="btnConfirmar">
                                <i class="fa fa-lock"></i> Confirmar cierre
                            </button>
                            <a href="<?php echo base_url(); ?>caja/ticket_cierre/<?php echo (int)$caja->id_caja; ?>"
                               target="_blank" class="btn btn-info">
                                <i class="fa fa-print"></i> Vista previa ticket
                            </a>
                            <a href="<?php echo base_url(); ?>carrito/carrito" class="btn btn-default">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
</div>

<script>
var efectivoEsperado = <?php echo (float)$resumen['saldo_sistema']; ?>;

function calcularDiferencia() {
    var contado = parseFloat(document.getElementById('efectivo_contado').value) || 0;
    var diferencia = +(contado - efectivoEsperado).toFixed(2);
    var visual = document.getElementById('diferencia_visual');
    var msg = document.getElementById('diferencia_msg');
    var obsReq = document.getElementById('obsRequerida');
    var obs = document.getElementById('observaciones');

    visual.value = diferencia.toFixed(2);

    if (Math.abs(diferencia) < 0.01) {
        visual.style.color = '#3c763d';
        msg.textContent = 'Caja cuadrada.';
        msg.className = 'text-success';
        obsReq.style.display = 'none';
        obs.required = false;
    } else if (diferencia > 0) {
        visual.style.color = '#3c763d';
        msg.textContent = 'Sobrante de $' + diferencia.toFixed(2) + '. Justifica en observaciones.';
        msg.className = 'text-warning';
        obsReq.style.display = 'inline';
        obs.required = true;
    } else {
        visual.style.color = '#a94442';
        msg.textContent = 'Faltante de $' + Math.abs(diferencia).toFixed(2) + '. Justifica en observaciones.';
        msg.className = 'text-danger';
        obsReq.style.display = 'inline';
        obs.required = true;
    }
}

// Confirmación final
document.getElementById('formCierre').addEventListener('submit', function (e) {
    var contado = parseFloat(document.getElementById('efectivo_contado').value);
    if (isNaN(contado)) {
        e.preventDefault();
        alert('Ingresa el efectivo contado.');
        return;
    }
    var diferencia = +(contado - efectivoEsperado).toFixed(2);
    var msg = 'Vas a cerrar la caja con:\n\n'
        + ' • Esperado: $' + efectivoEsperado.toFixed(2) + '\n'
        + ' • Contado:  $' + contado.toFixed(2) + '\n'
        + ' • Diferencia: $' + diferencia.toFixed(2) + '\n\n¿Confirmar?';
    if (!confirm(msg)) {
        e.preventDefault();
    }
});
</script>
