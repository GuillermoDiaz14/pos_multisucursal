<?php
$id_ingreso  = $ingresoInfo->id_ingreso;
$descripcion = $ingresoInfo->descripcion;
$monto       = $ingresoInfo->monto;
$fecha       = $ingresoInfo->fecha;
?>
<style>
.form-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden;max-width:700px;margin:0 auto}
.form-card-header{padding:16px 20px;border-bottom:1px solid #ecf0f1;display:flex;align-items:center;gap:10px}
.form-card-header h4{margin:0;font-size:16px;font-weight:700;color:#2c3e50}
.form-card-body{padding:20px}
.form-card-footer{padding:14px 20px;border-top:1px solid #ecf0f1;display:flex;gap:8px;background:#f8f9fa}
.form-section-title{font-size:13px;font-weight:600;color:#7f8c8d;text-transform:uppercase;letter-spacing:.5px;margin:0 0 14px;padding-bottom:6px;border-bottom:1px solid #ecf0f1}
.monto-preview{font-size:22px;font-weight:700;color:#27ae60;margin-top:6px;min-height:28px}
</style>

<div class="content-wrapper">
<div style="padding:16px 20px;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-arrow-circle-down text-success"></i> Editar ingreso
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;"><?php echo htmlspecialchars($descripcion, ENT_QUOTES); ?></p>
        </div>
        <a class="btn btn-default btn-sm" href="<?php echo base_url(); ?>ingreso/ingreso_lista">
            <i class="fa fa-arrow-left"></i> Volver
        </a>
    </div>

    <?php $this->load->helper('form'); ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissable" style="border-radius:6px;max-width:700px;margin:0 auto 14px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissable" style="border-radius:6px;max-width:700px;margin:0 auto 14px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger" style="border-radius:6px;max-width:700px;margin:0 auto 14px;">', '</div>'); ?>

    <div class="form-card">
        <div class="form-card-header">
            <i class="fa fa-arrow-circle-down text-success" style="font-size:18px;"></i>
            <h4>Datos del ingreso</h4>
        </div>

        <form role="form" id="editIngreso" action="<?php echo base_url(); ?>ingreso/editIngreso" method="post">
            <input type="hidden" name="id_ingreso" value="<?php echo $id_ingreso; ?>" />
        <div class="form-card-body">

            <p class="form-section-title">Información del ingreso</p>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Descripción <span style="color:#e74c3c;">*</span></label>
                        <textarea class="form-control" name="descripcion" id="descripcion"
                                  rows="3" maxlength="200" required><?php echo htmlspecialchars($descripcion, ENT_QUOTES); ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Monto <span style="color:#e74c3c;">*</span></label>
                        <input type="number" class="form-control" name="monto" id="monto"
                               value="<?php echo htmlspecialchars($monto, ENT_QUOTES); ?>"
                               min="0.01" step="0.01" required
                               oninput="actualizarPreviewMonto(this.value)" />
                        <div class="monto-preview" id="montoPreview"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fecha <span style="color:#e74c3c;">*</span></label>
                        <input type="date" class="form-control" name="fecha" id="fecha"
                               value="<?php echo htmlspecialchars($fecha, ENT_QUOTES); ?>"
                               required />
                    </div>
                </div>
            </div>

        </div>
        <div class="form-card-footer">
            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar cambios</button>
            <a href="<?php echo base_url(); ?>ingreso/ingreso_lista" class="btn btn-default">Cancelar</a>
        </div>
        </form>
    </div>

</div>
</div>

<script>
function actualizarPreviewMonto(val) {
    var el = document.getElementById('montoPreview');
    var n  = parseFloat(val);
    el.textContent = (!isNaN(n) && n > 0) ? '$' + n.toLocaleString('es-CO', {minimumFractionDigits:2, maximumFractionDigits:2}) : '';
}
(function(){ var v = document.getElementById('monto').value; if(v) actualizarPreviewMonto(v); })();
</script>
