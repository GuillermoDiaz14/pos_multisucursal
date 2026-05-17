<?php
$id_sucursal          = $sucursalInfo->id_sucursal;
$nombre_sucursal      = $sucursalInfo->nombre_sucursal;
$impuesto             = $sucursalInfo->impuesto;
$celular              = $sucursalInfo->celular;
$direccion            = $sucursalInfo->direccion;
$ciudad               = $sucursalInfo->ciudad;
$correo               = $sucursalInfo->correo ?? '';
$simbolo_moneda       = $sucursalInfo->simbolo_moneda;
$zebra_ticket_printer    = $sucursalInfo->zebra_ticket_printer    ?? '';
$zebra_label_printer     = $sucursalInfo->zebra_label_printer     ?? '';
$zebra_ticket_media_type = $sucursalInfo->zebra_ticket_media_type ?? '^MNC';
$zebra_label_media_type  = $sucursalInfo->zebra_label_media_type  ?? '^MNN';
?>
<style>
.form-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden;max-width:800px;margin:0 auto}
.form-card-header{padding:16px 20px;border-bottom:1px solid #ecf0f1;display:flex;align-items:center;gap:10px}
.form-card-header h4{margin:0;font-size:16px;font-weight:700;color:#2c3e50}
.form-card-body{padding:20px}
.form-card-footer{padding:14px 20px;border-top:1px solid #ecf0f1;display:flex;gap:8px;background:#f8f9fa}
.form-section-title{font-size:13px;font-weight:600;color:#7f8c8d;text-transform:uppercase;letter-spacing:.5px;margin:0 0 14px;padding-bottom:6px;border-bottom:1px solid #ecf0f1}
.zebra-help{background:#f8f9fa;border:1px solid #e9ecef;border-radius:6px;padding:10px 14px;font-size:12px;color:#666;margin-bottom:14px}
</style>

<div class="content-wrapper">
<div style="padding:16px 20px;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-pencil text-primary"></i> Editar sucursal
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;"><?php echo htmlspecialchars($nombre_sucursal, ENT_QUOTES); ?></p>
        </div>
        <a class="btn btn-default btn-sm" href="<?php echo base_url(); ?>sucursal/sucursal_lista">
            <i class="fa fa-arrow-left"></i> Volver
        </a>
    </div>

    <?php $this->load->helper('form'); ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissable" style="border-radius:6px;max-width:800px;margin:0 auto 14px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissable" style="border-radius:6px;max-width:800px;margin:0 auto 14px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php echo validation_errors('<div class="alert alert-danger" style="border-radius:6px;max-width:800px;margin:0 auto 14px;">', '</div>'); ?>

    <div class="form-card">
        <div class="form-card-header">
            <i class="fa fa-building text-primary" style="font-size:18px;"></i>
            <h4>Datos de la sucursal</h4>
        </div>

        <form action="<?php echo base_url(); ?>sucursal/editsucursal" method="post" id="editsucursal">
            <input type="hidden" name="id_sucursal" value="<?php echo $id_sucursal; ?>">
        <div class="form-card-body">

            <p class="form-section-title">Información general</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre de la sucursal <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="nombre_sucursal"
                               value="<?php echo htmlspecialchars($nombre_sucursal, ENT_QUOTES); ?>" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Ciudad <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="ciudad"
                               value="<?php echo htmlspecialchars($ciudad, ENT_QUOTES); ?>" required />
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Dirección <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="direccion"
                               value="<?php echo htmlspecialchars($direccion, ENT_QUOTES); ?>" required />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Celular <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="celular"
                               value="<?php echo htmlspecialchars($celular, ENT_QUOTES); ?>" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Correo electrónico</label>
                        <input type="email" class="form-control" name="correo"
                               value="<?php echo htmlspecialchars($correo, ENT_QUOTES); ?>"
                               placeholder="Ej: sucursal@negocio.com" />
                    </div>
                </div>
            </div>

            <p class="form-section-title" style="margin-top:8px;">Configuración financiera</p>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Impuesto (%) <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="impuesto"
                               value="<?php echo htmlspecialchars($impuesto, ENT_QUOTES); ?>"
                               pattern="[0-9]+(\.[0-9]+)?" required />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Símbolo de moneda <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="simbolo_moneda"
                               value="<?php echo htmlspecialchars($simbolo_moneda, ENT_QUOTES); ?>"
                               maxlength="10" required />
                    </div>
                </div>
            </div>

            <p class="form-section-title" style="margin-top:8px;"><i class="fa fa-print"></i> Impresoras Zebra</p>
            <div class="zebra-help">
                <i class="fa fa-info-circle text-info"></i>
                Copia el nombre exacto de la impresora desde <code>https://localhost:9101/available</code> en la PC de esta sucursal.
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Impresora de Tickets</label>
                        <input type="text" class="form-control" name="zebra_ticket_printer"
                               value="<?php echo htmlspecialchars($zebra_ticket_printer, ENT_QUOTES); ?>"
                               placeholder="Ej: ZD421-203dpi ZPL (D8N231501292)" />
                    </div>
                    <div class="form-group">
                        <label>Tipo de papel — Tickets</label>
                        <select class="form-control" name="zebra_ticket_media_type">
                            <option value="^MNC" <?php echo $zebra_ticket_media_type === '^MNC' ? 'selected' : ''; ?>>Papel continuo / tickets (sin huecos)</option>
                            <option value="^MNN" <?php echo $zebra_ticket_media_type === '^MNN' ? 'selected' : ''; ?>>Etiquetas con huecos (gap/notch)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Impresora de Etiquetas</label>
                        <input type="text" class="form-control" name="zebra_label_printer"
                               value="<?php echo htmlspecialchars($zebra_label_printer, ENT_QUOTES); ?>"
                               placeholder="Ej: ZD421-203dpi ZPL (D8N231501328)" />
                    </div>
                    <div class="form-group">
                        <label>Tipo de papel — Etiquetas</label>
                        <select class="form-control" name="zebra_label_media_type">
                            <option value="^MNN" <?php echo $zebra_label_media_type === '^MNN' ? 'selected' : ''; ?>>Etiquetas con huecos (gap/notch)</option>
                            <option value="^MNC" <?php echo $zebra_label_media_type === '^MNC' ? 'selected' : ''; ?>>Papel continuo / tickets (sin huecos)</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div class="form-card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar cambios</button>
            <a href="<?php echo base_url(); ?>sucursal/sucursal_lista" class="btn btn-default">Cancelar</a>
            <a href="<?php echo base_url('sucursal/ticket_config/'.$id_sucursal); ?>" class="btn btn-warning" style="margin-left:auto;">
                <i class="fa fa-ticket"></i> Configurar ticket
            </a>
        </div>
        </form>
    </div>

</div>
</div>
