<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Tusolutionweb | Admin Iniciar sesión en el sistema</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
          <style>

        .background-image {
            background-image: url('uploads/desarrollo33.png');
            background-repeat: no-repeat;
            background-size: cover;
       background-position: center center;
            height: 100%;
            position: fixed;
            width: 100%;
            z-index: -1; /* Para asegurarse de que esté detrás de otros elementos */
        }

    </style>
  </head>
  <body class="hold-transition login-page">
    
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>PuntodeVentaLerma</b><br>Admin sistema</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Iniciar sesion</p>
        <?php $this->load->helper('form'); ?>
        <div class="row">
            <div class="col-md-12">
                <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
            </div>
        </div>
        <?php
        $this->load->helper('form');
        $error = $this->session->flashdata('error');
        if($error)
        {
            ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $error; ?>                    
            </div>
        <?php }
        $success = $this->session->flashdata('success');
        if($success)
        {
            ?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $success; ?>                    
            </div>
        <?php } ?>
        <form action="<?php echo base_url(); ?>loginMe" method="post">
          <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Email" name="email" value="admin@example.com" required />
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="password" value="admin" required />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>

     <div class="form-group has-feedback">
   <label for="metodo">Sucursal</label>
                                        <select class="form-control required" id="id_sucursal" name="id_sucursal" >
                         
                                            <?php foreach ($sucursal['sucursal'] as $metodo): ?>
                                                <option value="<?php echo $metodo->id_sucursal; ?>"><?php echo $metodo->nombre_sucursal; ?></option>
                                            <?php endforeach; ?>
                                        </select>
          </div>



          <div class="row">
            <div class="col-xs-8">    
              <!-- <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Remember Me
                </label>
              </div>  -->                       
            </div><!-- /.col -->
            <div class="col-xs-4">
              <input type="submit" class="btn btn-primary btn-block btn-flat" value="Entrar" />
            </div><!-- /.col -->
          </div>
        </form>

        <a href="<?php echo base_url() ?>forgotPassword">Has olvidado tu password</a><br>
        
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>