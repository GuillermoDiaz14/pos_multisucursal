<?php
$id_proveedor = $proveedorInfo->id_proveedor;
$nombre       = $proveedorInfo->nombre;
$email        = $proveedorInfo->email;
$celular      = $proveedorInfo->celular;
$direccion    = $proveedorInfo->direccion;
$doc_fiscal   = $proveedorInfo->doc_fiscal;
?>
<style>
.form-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden;max-width:700px;margin:0 auto}
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
                <i class="fa fa-truck text-primary"></i> Editar proveedor
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;"><?php echo htmlspecialchars($nombre, ENT_QUOTES); ?></p>
        </div>
        <a class="btn btn-default btn-sm" href="<?php echo base_url(); ?>proveedor/proveedor_lista">
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
            <i class="fa fa-truck text-primary" style="font-size:18px;"></i>
            <h4>Datos del proveedor</h4>
        </div>

        <form role="form" id="editProveedor" action="<?php echo base_url(); ?>proveedor/editproveedor" method="post">
            <input type="hidden" name="id_proveedor" value="<?php echo $id_proveedor; ?>" />
        <div class="form-card-body">

            <p class="form-section-title">Información del proveedor</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="nombre" id="nombre"
                               value="<?php echo htmlspecialchars($nombre, ENT_QUOTES); ?>"
                               maxlength="200" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email <span style="color:#e74c3c;">*</span></label>
                        <input type="email" class="form-control" name="email" id="email"
                               value="<?php echo htmlspecialchars($email, ENT_QUOTES); ?>"
                               maxlength="50" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Celular <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="celular" id="celular"
                               value="<?php echo htmlspecialchars($celular, ENT_QUOTES); ?>"
                               maxlength="50" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Doc. fiscal <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="doc_fiscal" id="doc_fiscal"
                               value="<?php echo htmlspecialchars($doc_fiscal, ENT_QUOTES); ?>"
                               maxlength="50" required />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Dirección <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="direccion" id="direccion"
                               value="<?php echo htmlspecialchars($direccion, ENT_QUOTES); ?>"
                               maxlength="200" required />
                    </div>
                </div>
            </div>

        </div>
        <div class="form-card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar cambios</button>
            <a href="<?php echo base_url(); ?>proveedor/proveedor_lista" class="btn btn-default">Cancelar</a>
        </div>
        </form>
    </div>

</div>
</div>
