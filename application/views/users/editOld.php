<?php
$userId      = $userInfo->userId;
$name        = $userInfo->name;
$email       = $userInfo->email;
$mobile      = $userInfo->mobile;
$roleId      = $userInfo->roleId;
$id_sucursal = $userInfo->id_sucursal;
?>
<style>
.form-card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);overflow:hidden;max-width:800px;margin:0 auto}
.form-card-header{padding:16px 20px;border-bottom:1px solid #ecf0f1;display:flex;align-items:center;gap:10px}
.form-card-header h4{margin:0;font-size:16px;font-weight:700;color:#2c3e50}
.form-card-body{padding:20px}
.form-card-footer{padding:14px 20px;border-top:1px solid #ecf0f1;display:flex;gap:8px;background:#f8f9fa}
.form-section-title{font-size:13px;font-weight:600;color:#7f8c8d;text-transform:uppercase;letter-spacing:.5px;margin:0 0 14px;padding-bottom:6px;border-bottom:1px solid #ecf0f1}
.pass-toggle-wrap{background:#f8f9fa;border:1px solid #e9ecef;border-radius:6px;padding:10px 14px;margin-bottom:10px}
</style>

<div class="content-wrapper">
<div style="padding:16px 20px;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-user-circle text-primary"></i> Editar usuario
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;"><?php echo htmlspecialchars($name, ENT_QUOTES); ?></p>
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

        <form role="form" id="editUser" action="<?php echo base_url(); ?>editUser" method="post">
            <input type="hidden" name="userId" id="userId" value="<?php echo $userId; ?>" />
        <div class="form-card-body">

            <p class="form-section-title">Información personal</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre completo <span style="color:#e74c3c;">*</span></label>
                        <input type="text" class="form-control" name="fname" id="fname"
                               value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>"
                               maxlength="128" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Correo electrónico <span style="color:#e74c3c;">*</span></label>
                        <input type="email" class="form-control" name="email" id="email"
                               value="<?php echo htmlspecialchars($email, ENT_QUOTES); ?>"
                               maxlength="128" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Celular</label>
                        <input type="text" class="form-control" name="mobile" id="mobile"
                               value="<?php echo htmlspecialchars($mobile ?? '', ENT_QUOTES); ?>"
                               maxlength="10" />
                    </div>
                </div>
            </div>

            <p class="form-section-title" style="margin-top:8px;">Acceso y permisos</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Rol <span style="color:#e74c3c;">*</span></label>
                        <select class="form-control" name="role" id="role" required>
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
                                    $selected = ($rl->roleId == $roleId) ? 'selected' : '';
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
                        <select class="form-control" name="id_sucursal" id="id_sucursal" required>
                            <?php foreach ($sucursal['sucursal'] as $suc): ?>
                                <option value="<?php echo $suc->id_sucursal; ?>"
                                    <?php echo ($suc->id_sucursal == $id_sucursal) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($suc->nombre_sucursal, ENT_QUOTES); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <p class="form-section-title" style="margin-top:8px;">Cambiar contraseña</p>
            <div class="pass-toggle-wrap">
                <label style="margin:0;font-weight:500;cursor:pointer;">
                    <input type="checkbox" id="changePass" name="changePass" style="margin-right:6px;" />
                    Cambiar contraseña para este usuario
                </label>
            </div>
            <div id="passFields" style="display:none;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nueva contraseña</label>
                            <input type="password" class="form-control" name="password" id="password"
                                   placeholder="Nueva contraseña" maxlength="20" disabled />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Confirmar contraseña</label>
                            <input type="password" class="form-control" name="cpassword" id="cpassword"
                                   placeholder="Confirmar nueva contraseña" maxlength="20" disabled />
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="form-card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar cambios</button>
            <a href="<?php echo base_url(); ?>userListing" class="btn btn-default">Cancelar</a>
        </div>
        </form>
    </div>

</div>
</div>

<script>
$(document).ready(function() {
    $('#changePass').change(function() {
        if ($(this).is(':checked')) {
            $('#passFields').slideDown(150);
            $('#password, #cpassword').prop('disabled', false);
        } else {
            $('#passFields').slideUp(150);
            $('#password, #cpassword').prop('disabled', true).val('');
        }
    });
});
</script>

<script src="<?php echo base_url(); ?>assets/js/editUser.js" type="text/javascript"></script>
