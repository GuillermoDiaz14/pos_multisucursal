<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-money" aria-hidden="true"></i>
        <?php if (!empty($caja_abierta)): ?>
            Caja abierta
        <?php else: ?>
            Abrir caja
        <?php endif; ?>
        <small>caja</small>
      </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8">

                <?php if (!empty($caja_abierta)): ?>
                <!-- ── Caja ya abierta ─────────────────────────────── -->
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-check-circle text-success"></i> Caja en curso</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-condensed" style="max-width:420px;">
                            <tr>
                                <th style="width:160px;">Caja #</th>
                                <td><?php echo (int)$caja_abierta->id_caja; ?></td>
                            </tr>
                            <tr>
                                <th>Apertura</th>
                                <td><?php echo htmlspecialchars($caja_abierta->fecha_apertura, ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <th>Cajero</th>
                                <td><?php echo htmlspecialchars($caja_abierta->nombre_usuario_apertura ?: '—', ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <th>Monto apertura</th>
                                <td>$<?php echo number_format((float)$caja_abierta->monto_apertura, 2); ?></td>
                            </tr>
                            <tr>
                                <th>Saldo actual</th>
                                <td><strong>$<?php echo number_format((float)$caja_abierta->saldo, 2); ?></strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-footer">
                        <a href="<?php echo base_url('caja/cierre_arqueo'); ?>" class="btn btn-warning">
                            <i class="fa fa-lock"></i> Cerrar caja
                        </a>
                        <a href="<?php echo base_url('caja/historial'); ?>" class="btn btn-default" style="margin-left:8px;">
                            <i class="fa fa-list"></i> Historial de cajas
                        </a>
                    </div>
                </div>

                <?php else: ?>
                <!-- ── Sin caja abierta: formulario de apertura ───── -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Datos caja</h3>
                    </div>
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addCaja" action="<?php echo base_url() ?>caja/addNewCaja" method="post">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="saldo">Saldo apertura</label>
                                        <input type="text" class="form-control required"
                                               value="<?php echo set_value('saldo'); ?>"
                                               id="saldo" name="saldo"
                                               maxlength="256"
                                               inputmode="numeric"
                                               pattern="[0-9]+(\.[0-9]+)?"
                                               required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Abrir caja" />
                        </div>
                    </form>
                </div>
                <?php endif; ?>

            </div><!-- /.col-md-8 -->

            <div class="col-md-4">
                <?php $this->load->helper('form'); ?>
                <?php $error = $this->session->flashdata('error'); if($error): ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <?php $success = $this->session->flashdata('success'); if($success): ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $success; ?>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors(
                            '<div class="alert alert-danger alert-dismissable">',
                            ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'
                        ); ?>
                    </div>
                </div>
            </div><!-- /.col-md-4 -->

        </div>
    </section>
</div>
