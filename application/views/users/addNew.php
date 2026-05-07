<style>
.form-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden;max-width:800px;margin:0 auto}
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
                <i class="fa fa-user-plus text-primary"></i> Agregar usuario
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;">Registra un nuevo usuario del sistema</p>
        </div>
        <a class="btn btn-default btn-sm" href="<?php echo base_url(); ?>userListing">
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
            <i class="fa fa-user text-primary" style="font-size:18px;"></i>
            <h4>Datos del usuario</h4>
        </div>

        <form role="form" id="addUser" action="<?php echo base_url(); ?>addNewUser" method="post">
        <div class="form-card-body">

            <p class="form-section-title">Información personal</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre completo <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control required" name="fname" id="fname"
                               value="<?php echo set_value('fname'); ?>"
                               placeholder="Ej: Juan Pérez" maxlength="128" autofocus required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Correo electrónico <span style="color:#e74c3c;">*</span></label>
                        <input type="email" class="form-control required email" name="email" id="email"
                               value="<?php echo set_value('email'); ?>"
                               placeholder="Ej: usuario@negocio.com" maxlength="128" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Celular <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control digits" name="mobile" id="mobile"
                               value="<?php echo set_value('mobile'); ?>"
                               placeholder="Ej: 3001234567" maxlength="10" />
                    </div>
                </div>
            </div>

            <p class="form-section-title" style="margin-top:8px;">Acceso y permisos</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Contraseña <span style="color:#e74c3c;">*</span></label>
                        <input type="password" class="form-control required" name="password" id="password"
                               placeholder="Mínimo 6 caracteres" maxlength="20" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Confirmar contraseña <span style="color:#e74c3c;">*</span></label>
                        <input type="password" class="form-control required equalTo" name="cpassword" id="cpassword"
                               placeholder="Repite la contraseña" maxlength="20" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Rol <span style="color:#e74c3c;">*</span></label>
                        <select class="form-control required" name="role" id="role" required>
                            <option value="0">— Seleccione un rol —</option>
                            <?php if (!empty($roles)): ?>
                                <?php foreach ($roles as $rl): ?>
                                    <?php
                                    $roleText  = $rl->role;
                                    $roleClass = '';
                                    if ($rl->roleStatus == INACTIVE) {
                                        $roleText  = $rl->role . ' (Inactivo)';
                                        $roleClass = 'class="text-warning"';
                                    }
                                    $selected = (set_value('role') == $rl->roleId) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $rl->roleId; ?>" <?php echo $roleClass; ?> <?php echo $selected; ?>>
                                        <?php echo htmlspecialchars($roleText, ENT_QUOTES); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Sucursal <span style="color:#e74c3c;">*</span></label>
                        <select class="form-control required" name="id_sucursal" id="id_sucursal" required>
                            <?php foreach ($sucursal['sucursal'] as $suc): ?>
                                <option value="<?php echo $suc->id_sucursal; ?>">
                                    <?php echo htmlspecialchars($suc->nombre_sucursal, ENT_QUOTES); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div class="form-card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar usuario</button>
            <a href="<?php echo base_url(); ?>userListing" class="btn btn-default">Cancelar</a>
        </div>
        </form>
    </div>

</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>
