<?php
$userId     = $userInfo->userId;
$name       = $userInfo->name;
$email      = $userInfo->email;
$mobile     = $userInfo->mobile ?? '';
$role       = $userInfo->role  ?? '';
$sucursal   = $userInfo->nombre_sucursal ?? '';
$foto_ts    = $userInfo->foto    ?? null;
$color_tema = $userInfo->color_tema ?? '#3c8dbc';

$foto_url = $foto_ts
    ? base_url('uploads/fotos/user_' . $userId . '.jpg?v=' . $foto_ts)
    : base_url('assets/dist/img/logodemo.png');

// Iniciales para el avatar
$parts    = explode(' ', trim($name));
$initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
?>
<style>
.prf-wrapper{padding:20px}
.prf-grid{display:grid;grid-template-columns:260px 1fr;gap:20px;align-items:start;max-width:900px;margin:0 auto}

/* Tarjeta de perfil lateral */
.prf-card{background:#fff;border-radius:10px;box-shadow:0 1px 6px rgba(0,0,0,.12);overflow:hidden;text-align:center}
.prf-card-banner{background:linear-gradient(135deg,#2980b9 0%,#1a5276 100%);height:70px}
.prf-avatar{width:72px;height:72px;border-radius:50%;background:#2980b9;color:#fff;font-size:24px;font-weight:700;display:flex;align-items:center;justify-content:center;margin:-36px auto 0;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.2);letter-spacing:1px}
.prf-card-body{padding:12px 20px 20px}
.prf-card-name{font-size:16px;font-weight:700;color:#2c3e50;margin:8px 0 2px}
.prf-card-role{font-size:12px;color:#7f8c8d;margin-bottom:14px}
.prf-card-info{text-align:left;border-top:1px solid #ecf0f1;padding-top:14px;display:flex;flex-direction:column;gap:10px}
.prf-info-row{display:flex;align-items:center;gap:10px;font-size:13px}
.prf-info-row .prf-icon{width:28px;height:28px;border-radius:50%;background:#eaf4fb;color:#2980b9;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0}
.prf-info-label{font-size:11px;color:#aaa;text-transform:uppercase;letter-spacing:.3px;line-height:1.1}
.prf-info-val{color:#2c3e50;font-weight:500;font-size:13px;word-break:break-all}

/* Panel de formularios */
.prf-panel{background:#fff;border-radius:10px;box-shadow:0 1px 6px rgba(0,0,0,.12);overflow:hidden}
.prf-tabs{display:flex;border-bottom:2px solid #ecf0f1}
.prf-tab{padding:12px 20px;font-size:13px;font-weight:600;color:#7f8c8d;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .15s;display:flex;align-items:center;gap:6px;user-select:none}
.prf-tab:hover{color:#2980b9}
.prf-tab.active{color:#2980b9;border-bottom-color:#2980b9}
.prf-tab-content{padding:22px}
.prf-tab-pane{display:none}
.prf-tab-pane.active{display:block}
.form-section-title{font-size:12px;font-weight:600;color:#7f8c8d;text-transform:uppercase;letter-spacing:.5px;margin:0 0 14px;padding-bottom:6px;border-bottom:1px solid #ecf0f1}
.prf-footer{padding:14px 22px;border-top:1px solid #ecf0f1;display:flex;gap:8px;background:#f8f9fa}

/* Indicador de fortaleza de contraseña */
.pass-strength{height:4px;border-radius:2px;margin-top:6px;background:#e9ecef;transition:all .3s}
.pass-strength.weak{background:#e74c3c;width:30%}
.pass-strength.fair{background:#f39c12;width:60%}
.pass-strength.strong{background:#27ae60;width:100%}
.pass-label{font-size:11px;color:#888;margin-top:3px}

@media(max-width:768px){
    .prf-grid{grid-template-columns:1fr}
    .prf-wrapper{padding:12px}
}
</style>

<div class="content-wrapper">
<div class="prf-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;max-width:900px;margin-left:auto;margin-right:auto;">
        <div>
            <h3 style="margin:0;font-size:18px;color:#2c3e50;font-weight:700;">
                <i class="fa fa-user-circle text-primary"></i> Mi perfil
            </h3>
            <p style="margin:2px 0 0;font-size:12px;color:#aaa;">Ver o modificar tu información</p>
        </div>
    </div>

    <?php $this->load->helper('form'); ?>

    <!-- Alertas -->
    <div style="max-width:900px;margin:0 auto 14px;">
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissable" style="border-radius:6px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissable" style="border-radius:6px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('nomatch')): ?>
            <div class="alert alert-warning alert-dismissable" style="border-radius:6px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->session->flashdata('nomatch'); ?>
            </div>
        <?php endif; ?>
        <?php echo validation_errors('<div class="alert alert-danger" style="border-radius:6px;">', '</div>'); ?>
    </div>

    <div class="prf-grid">

        <!-- Tarjeta lateral -->
        <div class="prf-card">
            <div class="prf-card-banner"></div>
            <?php if ($foto_url): ?>
            <img id="prf-foto-lateral" src="<?php echo $foto_url; ?>"
                 style="width:72px;height:72px;border-radius:50%;object-fit:cover;margin:-36px auto 0;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.2);display:block;" />
            <?php else: ?>
            <div class="prf-avatar" id="prf-foto-lateral"><?php echo $initials; ?></div>
            <?php endif; ?>
            <div class="prf-card-body">
                <div class="prf-card-name"><?php echo htmlspecialchars($name, ENT_QUOTES); ?></div>
                <div class="prf-card-role"><?php echo htmlspecialchars($role, ENT_QUOTES); ?></div>
                <div class="prf-card-info">
                    <div class="prf-info-row">
                        <div class="prf-icon"><i class="fa fa-envelope"></i></div>
                        <div>
                            <div class="prf-info-label">Correo</div>
                            <div class="prf-info-val"><?php echo htmlspecialchars($email, ENT_QUOTES); ?></div>
                        </div>
                    </div>
                    <div class="prf-info-row">
                        <div class="prf-icon"><i class="fa fa-phone"></i></div>
                        <div>
                            <div class="prf-info-label">Celular</div>
                            <div class="prf-info-val"><?php echo htmlspecialchars($mobile, ENT_QUOTES) ?: '—'; ?></div>
                        </div>
                    </div>
                    <?php if ($sucursal): ?>
                    <div class="prf-info-row">
                        <div class="prf-icon"><i class="fa fa-building"></i></div>
                        <div>
                            <div class="prf-info-label">Sucursal</div>
                            <div class="prf-info-val"><?php echo htmlspecialchars($sucursal, ENT_QUOTES); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="prf-info-row">
                        <div class="prf-icon"><i class="fa fa-shield"></i></div>
                        <div>
                            <div class="prf-info-label">Rol</div>
                            <div class="prf-info-val"><?php echo htmlspecialchars($role, ENT_QUOTES); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel con pestañas -->
        <div class="prf-panel">
            <div class="prf-tabs">
                <div class="prf-tab <?php echo ($active == 'details') ? 'active' : ''; ?>" data-tab="details">
                    <i class="fa fa-id-card"></i> Mis datos
                </div>
                <div class="prf-tab <?php echo ($active == 'changepass') ? 'active' : ''; ?>" data-tab="changepass">
                    <i class="fa fa-lock"></i> Cambiar contraseña
                </div>
                <div class="prf-tab" data-tab="apariencia">
                    <i class="fa fa-paint-brush"></i> Apariencia
                </div>
            </div>

            <!-- Tab: Mis datos -->
            <div class="prf-tab-pane <?php echo ($active == 'details') ? 'active' : ''; ?>" id="tab-details">
                <div class="prf-tab-content">
                    <p class="form-section-title">Información personal</p>
                    <form action="<?php echo base_url(); ?>profileUpdate" method="post" id="editProfile">
                        <input type="hidden" name="userId" value="<?php echo $userId; ?>" />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nombre completo <span style="color:#e74c3c;">*</span></label>
                                    <input type="text" class="form-control" name="fname" id="fname"
                                           value="<?php echo htmlspecialchars(set_value('fname', $name), ENT_QUOTES); ?>"
                                           maxlength="128" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Correo electrónico <span style="color:#e74c3c;">*</span></label>
                                    <input type="email" class="form-control" name="email" id="email"
                                           value="<?php echo htmlspecialchars(set_value('email', $email), ENT_QUOTES); ?>"
                                           maxlength="128" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Celular <span style="color:#e74c3c;">*</span></label>
                                    <input type="text" class="form-control" name="mobile" id="mobile"
                                           value="<?php echo htmlspecialchars(set_value('mobile', $mobile), ENT_QUOTES); ?>"
                                           maxlength="10" />
                                </div>
                            </div>
                        </div>
                        <div class="prf-footer" style="margin:0 -22px -22px;border-radius:0 0 10px 10px;">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar cambios</button>
                            <button type="reset" class="btn btn-default">Restablecer</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab: Cambiar contraseña -->
            <div class="prf-tab-pane <?php echo ($active == 'changepass') ? 'active' : ''; ?>" id="tab-changepass">
                <div class="prf-tab-content">
                    <p class="form-section-title">Seguridad de la cuenta</p>
                    <form action="<?php echo base_url(); ?>changePassword" method="post" id="changePassForm">
                        <div class="form-group">
                            <label>Contraseña actual <span style="color:#e74c3c;">*</span></label>
                            <input type="password" class="form-control" name="oldPassword" id="inputOldPassword"
                                   placeholder="Ingresa tu contraseña actual" maxlength="20" required />
                        </div>
                        <hr style="margin:16px 0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nueva contraseña <span style="color:#e74c3c;">*</span></label>
                                    <input type="password" class="form-control" name="newPassword" id="inputPassword1"
                                           placeholder="Mínimo 6 caracteres" maxlength="20" required
                                           oninput="evaluarContrasena(this.value)" />
                                    <div class="pass-strength" id="passStrengthBar"></div>
                                    <div class="pass-label" id="passStrengthLabel"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirmar contraseña <span style="color:#e74c3c;">*</span></label>
                                    <input type="password" class="form-control" name="cNewPassword" id="inputPassword2"
                                           placeholder="Repite la nueva contraseña" maxlength="20" required />
                                </div>
                            </div>
                        </div>
                        <div class="prf-footer" style="margin:8px -22px -22px;border-radius:0 0 10px 10px;">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-lock"></i> Actualizar contraseña</button>
                            <button type="reset" class="btn btn-default">Limpiar</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Tab: Apariencia -->
            <div class="prf-tab-pane" id="tab-apariencia">
                <div class="prf-tab-content">

                    <!-- FOTO DE PERFIL -->
                    <p class="form-section-title"><i class="fa fa-camera"></i> Foto de perfil</p>
                    <div style="display:flex;align-items:center;gap:20px;margin-bottom:24px;flex-wrap:wrap;">
                        <div id="prf-preview-wrap" style="width:80px;height:80px;border-radius:50%;overflow:hidden;border:3px solid #ecf0f1;flex-shrink:0;background:#eaf4fb;display:flex;align-items:center;justify-content:center;">
                            <?php if ($foto_url): ?>
                            <img id="prf-preview-img" src="<?php echo $foto_url; ?>" style="width:100%;height:100%;object-fit:cover;" />
                            <?php else: ?>
                            <span id="prf-preview-initials" style="font-size:26px;font-weight:700;color:#2980b9;"><?php echo $initials; ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p style="margin:0 0 8px;font-size:13px;color:#555;"></p>
                            <label class="btn btn-default btn-sm" style="cursor:pointer;margin:0;">
                                <i class="fa fa-upload"></i> Seleccionar foto
                                <input type="file" id="foto-input" accept="image/*" style="display:none;">
                            </label>
                        </div>
                    </div>

                    <!-- PALETA DE COLORES -->
                    <p class="form-section-title"><i class="fa fa-paint-brush"></i> Color del sistema</p>
                    <p style="font-size:12px;color:#888;margin-bottom:14px;">Escoge un color para tu sesión.</p>
                    <div id="paleta-colores" style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:8px;">
                        <?php
                        $paleta = [
                            '#3c8dbc' => 'Azul',
                            '#1a5276' => 'Azul marino',
                            '#283593' => 'Índigo',
                            '#27ae60' => 'Verde',
                            '#558b2f' => 'Verde militar',
                            '#00695c' => 'Esmeralda',
                            '#16a085' => 'Teal',
                            '#c0392b' => 'Rojo',
                            '#880e4f' => 'Borgoña',
                            '#8e44ad' => 'Morado',
                            '#4527a0' => 'Violeta',
                            '#d86700' => 'Naranja',
                            '#c2185b' => 'Rosa',
                            '#5d4037' => 'Café',
                            '#546e7a' => 'Gris azulado',
                            '#2c3e50' => 'Carbón',
                        ];
                        foreach ($paleta as $hex => $label):
                            $activo = ($color_tema === $hex) ? 'border:3px solid #333;transform:scale(1.18);' : 'border:3px solid transparent;';
                        ?>
                        <button type="button"
                                class="color-chip"
                                data-color="<?php echo $hex; ?>"
                                title="<?php echo $label; ?>"
                                style="width:38px;height:38px;border-radius:50%;background:<?php echo $hex; ?>;cursor:pointer;outline:none;transition:transform .15s;<?php echo $activo; ?>">
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <small id="color-status" style="font-size:12px;color:#888;">Color actual: <strong id="color-nombre"><?php echo $paleta[$color_tema] ?? $color_tema; ?></strong></small>

                </div>
            </div>

        </div>

    </div>
</div>
</div>

<!-- Modal Cropper -->
<div class="modal fade" id="modalCropper" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="max-width:520px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-crop"></i> Recortar foto de perfil</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="background:#1a1a2e;padding:20px;text-align:center;">
                <img id="cropper-img" src="" style="max-width:100%;max-height:400px;" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-guardar-foto">
                    <i class="fa fa-save"></i> Guardar foto
                </button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

<script>
// Tabs
document.querySelectorAll('.prf-tab').forEach(function(tab) {
    tab.addEventListener('click', function() {
        var target = this.dataset.tab;
        document.querySelectorAll('.prf-tab').forEach(function(t) { t.classList.remove('active'); });
        document.querySelectorAll('.prf-tab-pane').forEach(function(p) { p.classList.remove('active'); });
        this.classList.add('active');
        document.getElementById('tab-' + target).classList.add('active');
    });
});

// Fortaleza de contraseña
function evaluarContrasena(val) {
    var bar   = document.getElementById('passStrengthBar');
    var label = document.getElementById('passStrengthLabel');
    bar.className = 'pass-strength';
    if (!val) { label.textContent = ''; return; }
    var strong = val.length >= 8 && /[A-Z]/.test(val) && /[0-9]/.test(val);
    var fair   = val.length >= 6;
    if (strong) {
        bar.classList.add('strong');
        label.textContent = 'Contraseña fuerte';
        label.style.color = '#27ae60';
    } else if (fair) {
        bar.classList.add('fair');
        label.textContent = 'Contraseña moderada';
        label.style.color = '#f39c12';
    } else {
        bar.classList.add('weak');
        label.textContent = 'Contraseña débil';
        label.style.color = '#e74c3c';
    }
}

// Validación confirm password al submit
document.getElementById('changePassForm').addEventListener('submit', function(e) {
    var p1 = document.getElementById('inputPassword1').value;
    var p2 = document.getElementById('inputPassword2').value;
    if (p1 !== p2) {
        e.preventDefault();
        alert('Las contraseñas no coinciden.');
        document.getElementById('inputPassword2').focus();
    }
});

// ── CROPPER.JS — foto de perfil ───────────────────────────────────────────
var cropper = null;

document.getElementById('foto-input').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;

    var reader = new FileReader();
    reader.onload = function(e) {
        var img = document.getElementById('cropper-img');
        img.src = e.target.result;

        $('#modalCropper').modal('show');

        $('#modalCropper').one('shown.bs.modal', function() {
            if (cropper) { cropper.destroy(); cropper = null; }
            cropper = new Cropper(img, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.9,
                responsive: true,
                restore: false,
                guides: false,
                center: true,
                highlight: false,
                cropBoxMovable: false,
                cropBoxResizable: false,
                toggleDragModeOnDblclick: false,
            });
        });
    };
    reader.readAsDataURL(file);
    this.value = ''; // limpiar para permitir re-selección
});

document.getElementById('btn-guardar-foto').addEventListener('click', function() {
    if (!cropper) return;
    var btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Guardando...';

    cropper.getCroppedCanvas({ width: 300, height: 300, fillColor: '#fff' }).toBlob(function(blob) {
        var fd = new FormData();
        fd.append('imagen', blob, 'foto.jpg');

        $.ajax({
            url: '<?php echo base_url("user/guardar_foto"); ?>',
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(resp) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-save"></i> Guardar foto';
                if (resp.success) {
                    var wrap = document.getElementById('prf-preview-wrap');
                    wrap.innerHTML = '<img src="' + resp.url + '" style="width:100%;height:100%;object-fit:cover;" />';

                    var lateral = document.getElementById('prf-foto-lateral');
                    if (lateral) {
                        lateral.outerHTML = '<img id="prf-foto-lateral" src="' + resp.url + '" style="width:72px;height:72px;border-radius:50%;object-fit:cover;margin:-36px auto 0;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.2);display:block;" />';
                    }

                    document.querySelectorAll('.user-image, .img-circle').forEach(function(el) {
                        el.src = resp.url;
                    });

                    $('#modalCropper').modal('hide');
                    cropper.destroy(); cropper = null;
                } else {
                    alert('Error: ' + resp.message);
                }
            },
            error: function(xhr) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-save"></i> Guardar foto';
                alert('Error al guardar la foto (' + xhr.status + '). Intenta de nuevo.');
            }
        });
    }, 'image/jpeg', 0.9);
});

$('#modalCropper').on('hidden.bs.modal', function() {
    if (cropper) { cropper.destroy(); cropper = null; }
});

// ── PALETA DE COLORES ─────────────────────────────────────────────────────
var nombres = {
    '#3c8dbc': 'Azul',     '#1a5276': 'Azul marino', '#283593': 'Índigo',
    '#27ae60': 'Verde',    '#558b2f': 'Verde militar','#00695c': 'Esmeralda',
    '#16a085': 'Teal',     '#c0392b': 'Rojo',         '#880e4f': 'Borgoña',
    '#8e44ad': 'Morado',   '#4527a0': 'Violeta',      '#d86700': 'Naranja',
    '#c2185b': 'Rosa',     '#5d4037': 'Café',          '#546e7a': 'Gris azulado',
    '#2c3e50': 'Carbón'
};

document.querySelectorAll('.color-chip').forEach(function(chip) {
    chip.addEventListener('click', function() {
        var color = this.dataset.color;

        $.ajax({
            url: '<?php echo base_url("user/guardar_color_tema"); ?>',
            method: 'POST',
            dataType: 'json',
            data: { color: color },
            success: function(resp) {
                if (!resp.success) return;

                // Marcar chip activo visualmente
                document.querySelectorAll('.color-chip').forEach(function(c) {
                    c.style.border = '3px solid transparent';
                    c.style.transform = '';
                });
                chip.style.border = '3px solid #333';
                chip.style.transform = 'scale(1.18)';

                document.getElementById('color-nombre').textContent = nombres[color] || color;

                // Aplicar color al navbar/sidebar SIN recargar la página
                var hex    = color.replace('#', '');
                var r = parseInt(hex.substr(0,2),16), g = parseInt(hex.substr(2,2),16), b = parseInt(hex.substr(4,2),16);
                var dark = '#' + [r,g,b].map(function(c){ return ('0'+Math.round(c*0.80).toString(16)).slice(-2); }).join('');

                // Actualizar o crear el <style> de tema
                var styleId = 'tema-dinamico';
                var el = document.getElementById(styleId);
                if (!el) { el = document.createElement('style'); el.id = styleId; document.head.appendChild(el); }
                el.textContent =
                    '.skin-blue .main-header .navbar{background-color:' + color + '}' +
                    '.skin-blue .main-header .logo,.skin-blue .main-header .logo:hover{background-color:' + dark + '}' +
                    '.skin-blue .main-header li.user-header{background-color:' + color + '}' +
                    '.skin-blue .sidebar-menu>li.active>a,.skin-blue .sidebar-menu>li:hover>a{background-color:' + dark + ';border-left-color:' + color + '}' +
                    '.skin-blue .sidebar-menu>li.active>a{border-left-color:' + color + '!important}';
            }
        });
    });
});
</script>
