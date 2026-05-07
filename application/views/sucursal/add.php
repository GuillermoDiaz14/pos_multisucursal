<style>
.form-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden;max-width:760px;margin:0 auto}
.form-card-header{padding:16px 20px;border-bottom:1px solid #ecf0f1;display:flex;align-items:center;gap:10px}
.form-card-header h4{margin:0;font-size:16px;font-weight:700;color:#2c3e50}
.form-card-body{padding:20px}
.form-card-footer{padding:14px 20px;border-top:1px solid #ecf0f1;display:flex;gap:8px;background:#f8f9fa}
.form-section-title{font-size:13px;font-weight:600;color:#7f8c8d;text-transform:uppercase;letter-spacing:.5px;margin:0 0 14px;padding-bottom:6px;border-bottom:1px solid #ecf0f1}
</style>

<div class="content-wrapper">
<div style="padding:16px 20px;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-plus-circle text-primary"></i> Agregar sucursal
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;">Registra una nueva sucursal del negocio</p>
        </div>
        <a class="btn btn-default btn-sm" href="<?php echo base_url(); ?>sucursal/sucursal_lista">
            <i class="fa fa-arrow-left"></i> Volver
        </a>
    </div>

    <?php $this->load->helper('form'); ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissable" style="border-radius:6px;max-width:760px;margin:0 auto 14px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger" style="border-radius:6px;max-width:760px;margin:0 auto 14px;">', '</div>'); ?>

    <div class="form-card">
        <div class="form-card-header">
            <i class="fa fa-building text-primary" style="font-size:18px;"></i>
            <h4>Datos de la sucursal</h4>
        </div>

        <form action="<?php echo base_url(); ?>sucursal/addNewsucursal" method="post" id="addsucursal">
        <div class="form-card-body">

            <p class="form-section-title">Información general</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre de la sucursal <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="nombre_sucursal" id="nombre_sucursal"
                               placeholder="Ej: Sucursal Centro" autofocus required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Ciudad <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="ciudad" id="ciudad"
                               placeholder="Ej: Bogotá" required />
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Dirección <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="direccion" id="direccion"
                               placeholder="Ej: Calle 10 #5-23" required />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Celular <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="celular" id="celular"
                               placeholder="Ej: 3001234567" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Correo electrónico</label>
                        <input type="email" class="form-control" name="correo" id="correo"
                               placeholder="Ej: sucursal@negocio.com" />
                    </div>
                </div>
            </div>

            <p class="form-section-title" style="margin-top:8px;">Configuración financiera</p>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Impuesto (%) <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="impuesto" id="impuesto"
                               placeholder="Ej: 19" pattern="[0-9]+(\.[0-9]+)?"
                               title="Número entero o decimal" required />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Símbolo de moneda <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="simbolo_moneda" id="simbolo_moneda"
                               placeholder="Ej: $" maxlength="10" required />
                    </div>
                </div>
            </div>

        </div>
        <div class="form-card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar sucursal</button>
            <a href="<?php echo base_url(); ?>sucursal/sucursal_lista" class="btn btn-default">Cancelar</a>
        </div>
        </form>
    </div>

</div>
</div>
