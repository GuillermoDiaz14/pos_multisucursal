<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar sesión — POS Multisucursal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
  <style>
    *, *::before, *::after { box-sizing: border-box; }

    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Inter', 'Source Sans Pro', sans-serif;
    }

    body {
      background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    /* Partículas decorativas */
    body::before {
      content: '';
      position: fixed;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle at 20% 80%, rgba(44,83,100,0.4) 0%, transparent 50%),
                  radial-gradient(circle at 80% 20%, rgba(32,58,67,0.4) 0%, transparent 50%);
      pointer-events: none;
      z-index: 0;
    }

    .login-wrapper {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 420px;
      animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .login-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 25px 60px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.05);
      overflow: hidden;
    }

    .login-header {
      background: linear-gradient(135deg, #1a3a4a 0%, #2c5364 100%);
      padding: 36px 40px 32px;
      text-align: center;
      position: relative;
    }

    .login-header::after {
      content: '';
      position: absolute;
      bottom: -1px;
      left: 0;
      right: 0;
      height: 24px;
      background: #fff;
      border-radius: 50% 50% 0 0 / 100% 100% 0 0;
    }

    .brand-icon {
      width: 64px;
      height: 64px;
      background: rgba(255,255,255,0.15);
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.2);
    }

    .brand-icon i {
      font-size: 28px;
      color: #fff;
    }

    .brand-title {
      color: #fff;
      font-size: 22px;
      font-weight: 700;
      margin: 0 0 4px;
      letter-spacing: -0.3px;
    }

    .brand-subtitle {
      color: rgba(255,255,255,0.65);
      font-size: 13px;
      font-weight: 400;
      margin: 0;
    }

    .login-body {
      padding: 32px 40px 36px;
    }

    .section-title {
      font-size: 15px;
      font-weight: 600;
      color: #1a3a4a;
      margin: 0 0 24px;
    }

    /* Alerts */
    .alert {
      border-radius: 10px;
      border: none;
      font-size: 13.5px;
      padding: 12px 16px;
      margin-bottom: 20px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
    }

    .alert-danger {
      background: #fff2f2;
      color: #c0392b;
      border-left: 3px solid #e74c3c;
    }

    .alert-success {
      background: #f0fff4;
      color: #27ae60;
      border-left: 3px solid #2ecc71;
    }

    .alert .fa { margin-top: 1px; flex-shrink: 0; }
    .alert .close { margin-left: auto; color: inherit; opacity: 0.6; font-size: 16px; line-height: 1; }

    /* Form */
    .form-group {
      margin-bottom: 18px;
    }

    .form-group label {
      display: block;
      font-size: 12.5px;
      font-weight: 600;
      color: #556070;
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .input-wrap {
      position: relative;
    }

    .input-wrap .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #9aa5b4;
      font-size: 15px;
      pointer-events: none;
      transition: color 0.2s;
    }

    .form-control {
      height: 48px;
      border-radius: 10px;
      border: 1.5px solid #e0e6ee;
      background: #f8fafc;
      padding: 0 44px 0 42px;
      font-size: 14px;
      color: #1a2a3a;
      width: 100%;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
      outline: none;
      box-shadow: none;
      -webkit-appearance: none;
    }

    .form-control:focus {
      border-color: #2c5364;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(44,83,100,0.12);
    }

    .form-control:focus ~ .input-icon,
    .input-wrap:focus-within .input-icon {
      color: #2c5364;
    }

    select.form-control {
      padding-left: 42px;
      cursor: pointer;
    }

    /* Toggle password */
    .toggle-password {
      position: absolute;
      right: 13px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      padding: 4px;
      cursor: pointer;
      color: #9aa5b4;
      font-size: 15px;
      transition: color 0.2s;
      line-height: 1;
    }

    .toggle-password:hover { color: #2c5364; }

    /* Submit button */
    .btn-login {
      width: 100%;
      height: 50px;
      border-radius: 12px;
      background: linear-gradient(135deg, #1a3a4a 0%, #2c5364 100%);
      color: #fff;
      font-size: 15px;
      font-weight: 600;
      border: none;
      cursor: pointer;
      transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
      margin-top: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      letter-spacing: 0.2px;
      box-shadow: 0 4px 15px rgba(44,83,100,0.4);
    }

    .btn-login:hover {
      opacity: 0.92;
      transform: translateY(-1px);
      box-shadow: 0 8px 20px rgba(44,83,100,0.45);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .btn-login.loading {
      pointer-events: none;
      opacity: 0.8;
    }

    .btn-login .spinner {
      display: none;
      width: 18px;
      height: 18px;
      border: 2px solid rgba(255,255,255,0.4);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.7s linear infinite;
    }

    .btn-login.loading .spinner { display: block; }
    .btn-login.loading .btn-text { display: none; }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Footer link */
    .login-footer {
      text-align: center;
      margin-top: 22px;
    }

    .login-footer a {
      font-size: 13px;
      color: #6b7c93;
      text-decoration: none;
      transition: color 0.2s;
    }

    .login-footer a:hover { color: #2c5364; }

    /* Bottom branding */
    .login-branding {
      text-align: center;
      margin-top: 24px;
      color: rgba(255,255,255,0.4);
      font-size: 12px;
    }

    @media (max-width: 480px) {
      .login-header { padding: 28px 24px 26px; }
      .login-body   { padding: 28px 24px 30px; }
    }
  </style>
</head>
<body>

<div class="login-wrapper">
  <div class="login-card">

    <!-- Header -->
    <div class="login-header">
      <div class="brand-icon">
        <i class="fa fa-shopping-cart"></i>
      </div>
      <h1 class="brand-title">POS Multisucursal</h1>
      <p class="brand-subtitle">Sistema de punto de venta</p>
    </div>

    <!-- Body -->
    <div class="login-body">
      <p class="section-title">Inicia sesión en tu cuenta</p>

      <?php $this->load->helper('form'); ?>

      <?php echo validation_errors(
        '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><span>',
        '</span><button type="button" class="close" data-dismiss="alert">&times;</button></div>'
      ); ?>

      <?php $error = $this->session->flashdata('error'); if ($error): ?>
        <div class="alert alert-danger">
          <i class="fa fa-exclamation-circle"></i>
          <span><?php echo htmlspecialchars($error); ?></span>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php endif; ?>

      <?php $success = $this->session->flashdata('success'); if ($success): ?>
        <div class="alert alert-success">
          <i class="fa fa-check-circle"></i>
          <span><?php echo htmlspecialchars($success); ?></span>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php endif; ?>

      <form action="<?php echo site_url('login/loginMe'); ?>" method="post" id="loginForm" autocomplete="off" novalidate>

        <div class="form-group">
          <label for="email">Correo electrónico</label>
          <div class="input-wrap">
            <i class="fa fa-envelope input-icon"></i>
            <input type="email" id="email" name="email" class="form-control"
                   placeholder="nombre@sucursal.com" required autocomplete="username">
          </div>
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <div class="input-wrap">
            <i class="fa fa-lock input-icon"></i>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="••••••••" required autocomplete="current-password">
            <button type="button" class="toggle-password" tabindex="-1" aria-label="Mostrar contraseña">
              <i class="fa fa-eye" id="toggleIcon"></i>
            </button>
          </div>
        </div>

        <div class="form-group">
          <label for="id_sucursal">Sucursal</label>
          <div class="input-wrap">
            <i class="fa fa-map-marker input-icon"></i>
            <select id="id_sucursal" name="id_sucursal" class="form-control" required>
              <option value="" disabled selected>Selecciona una sucursal</option>
              <?php foreach ($sucursal['sucursal'] as $s): ?>
                <option value="<?php echo (int) $s->id_sucursal; ?>">
                  <?php echo htmlspecialchars($s->nombre_sucursal); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <button type="submit" class="btn-login" id="submitBtn">
          <div class="spinner"></div>
          <span class="btn-text"><i class="fa fa-sign-in"></i> &nbsp;Entrar</span>
        </button>

      </form>

      <div class="login-footer">
        <a href="<?php echo base_url(); ?>forgotPassword">
          <i class="fa fa-key"></i> ¿Olvidaste tu contraseña?
        </a>
      </div>
    </div>

  </div>

  <div class="login-branding">
    &copy; <?php echo date('Y'); ?> POS Multisucursal
  </div>
</div>

<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
  // Toggle password visibility
  document.querySelector('.toggle-password').addEventListener('click', function () {
    var input = document.getElementById('password');
    var icon  = document.getElementById('toggleIcon');
    if (input.type === 'password') {
      input.type = 'text';
      icon.className = 'fa fa-eye-slash';
    } else {
      input.type = 'password';
      icon.className = 'fa fa-eye';
    }
  });

  // Loading state on submit
  document.getElementById('loginForm').addEventListener('submit', function () {
    var btn = document.getElementById('submitBtn');
    btn.classList.add('loading');
  });

  // Auto-dismiss alerts after 5s
  setTimeout(function () {
    $('.alert').fadeOut(400);
  }, 5000);
</script>
</body>
</html>
