<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $pageTitle; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- FontAwesome 4.3.0 -->
    <link href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 -->
    <link href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <style>
    	.error{
    		color:red;
    		font-weight: normal;
    	}
    </style>
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    
    <script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
    </script>
    
  
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
   
    <script src="<?php echo base_url(); ?>assets/sheetjs/dist/xlsx.full.min.js"></script>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      
      <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo base_url(); ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>PROMETUS</b>POS</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>PuntodeVenta</b>Lerma</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                  <i class="fa fa-history"></i>
                </a>
                <ul class="dropdown-menu">
                  <li class="header"> Last Login : <i class="fa fa-clock-o"></i> <?= empty($last_login) ? "First Time Login" : $last_login; ?></li
                  >
                </ul>
              </li>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="user-image" alt="User Image"/>
                  <span class="hidden-xs"><?php echo $name; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    
                    <img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="img-circle" alt="User Image" />
                    <p>
                      <?php echo $name; ?>
                      <small><?php echo $role_text; ?></small>
                    </p>
                    
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?php echo base_url(); ?>profile" class="btn btn-warning btn-flat"><i class="fa fa-user-circle"></i> Perfil</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo base_url(); ?>logout" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Cerrar sesion</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
    


            <?php
            if($is_admin == 1)
            {
            ?>
            <li>
              <a href="<?php echo base_url(); ?>userListing">
                <i class="fa fa-users"></i>
                <span>Usuarios</span>
              </a>
            </li>
            <li>
              <a href="<?php echo base_url(); ?>roles/roleListing">
                <i class="fa fa-user-circle-o " aria-hidden="true"></i>
                <span>Roles</span>
              </a>
            </li>
            <?php
            }
            ?>
    




<?php
            if($is_admin == 1 ||
                (array_key_exists('Empleado', $access_info) 
                && ($access_info['Empleado']['total_access'] == 1)))
            {
              ?>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-anchor"></i> <span>Empleado</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>empleado"><i class="fa fa-circle-o"></i>empleado</a></li>
                <li><a href="<?php echo base_url(); ?>empleado/importar"><i class="fa fa-circle-o"></i> importar</a></li>
            
              </ul>
            </li>

            <?php
            }
            ?>




<?php
            if($is_admin == 1 ||
                (array_key_exists('Cliente', $access_info) 
                && ($access_info['Cliente']['total_access'] == 1)))
            {
              ?>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-anchor"></i> <span>Cliente</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>Cliente"><i class="fa fa-circle-o"></i>Cliente</a></li>
                <li><a href="<?php echo base_url(); ?>Cliente/importar"><i class="fa fa-circle-o"></i> importar</a></li>
            
              </ul>
            </li>

            <?php
            }
            ?>



  







<?php
            if($is_admin == 1 ||
                (array_key_exists('Gasto', $access_info) 
                && ($access_info['Gasto']['total_access'] == 1)))
            {
              ?>
            <li>
              <a href="<?php echo base_url(); ?>gasto">
                <i class="fa fa-money"></i>
                <span>Gasto</span>
              </a>
            </li>
              <?php
            }
            ?>


<?php
            if($is_admin == 1 ||
                (array_key_exists('Ingreso', $access_info) 
                && ($access_info['Ingreso']['total_access'] == 1)))
            {
              ?>
            <li>
              <a href="<?php echo base_url(); ?>ingreso">
                <i class="fa fa-money"></i>
                <span>Ingreso</span>
              </a>
            </li>
              <?php
            }
            ?>






<?php
            if($is_admin == 1 ||
                (array_key_exists('Carrito', $access_info) 
                && ($access_info['Carrito']['total_access'] == 1)))
            {
              ?>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-cart-arrow-down"></i> <span>Ventas</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>carrito"><i class="fa fa-cart-arrow-down"></i>Pos</a></li>
                <li><a href="<?php echo base_url(); ?>carrito/ventas_lista"><i class="fa fa-circle-o"></i>todas las ventas</a></li>
                <li><a href="<?php echo base_url(); ?>carrito/ventas_lista_contado"><i class="fa fa-circle-o"></i> ventas al contado</a></li>
            <li><a href="<?php echo base_url(); ?>carrito/ventas_lista_credito"><i class="fa fa-circle-o"></i> ventas al credito</a></li>
              </ul>
            </li>

            <?php
            }
            ?>


<?php
            if($is_admin == 1 ||
                (array_key_exists('Entrada', $access_info) 
                && ($access_info['Entrada']['total_access'] == 1)))
            {
              ?>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-cart-plus"></i> <span>Compras</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>entrada"><i class="fa fa-cart-plus"></i>compras</a></li>
                <li><a href="<?php echo base_url(); ?>entrada/entradas_lista"><i class="fa fa-circle-o"></i> lista compras</a></li>
            
              </ul>
            </li>

            <?php
            }
            ?>




<?php
            if($is_admin == 1 ||
                (array_key_exists('Trasladar', $access_info) 
                && ($access_info['Trasladar']['total_access'] == 1)))
            {
              ?>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-cart-plus"></i> <span>Trasladar</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>trasladar"><i class="fa fa-cart-plus"></i>trasladar</a></li>
                <li><a href="<?php echo base_url(); ?>trasladar/trasladar_lista"><i class="fa fa-circle-o"></i> lista traslados enviados</a></li>
                   <li><a href="<?php echo base_url(); ?>trasladar/trasladar_lista_Recibidos"><i class="fa fa-circle-o"></i> lista traslados recibidos</a></li>
            
              </ul>
            </li>

            <?php
            }
            ?>







<?php
            if($is_admin == 1 ||
                (array_key_exists('Producto', $access_info) 
                && ($access_info['Producto']['total_access'] == 1)))
            {
              ?>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-shopping-basket"></i> <span>Productos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                   <li><a href="<?php echo base_url(); ?>categoria/categoria_lista"><i class="fa fa-circle-o"></i>Categoria</a></li>
                <li><a href="<?php echo base_url(); ?>producto"><i class="fa fa-circle-o"></i>Productos</a></li>
                <li><a href="<?php echo base_url(); ?>producto/importar"><i class="fa fa-circle-o"></i> importar</a></li>
                <li><a href="<?php echo base_url(); ?>producto/etiqueta"><i class="fa fa-circle-o"></i> Etiquetas codigo de barra </a></li>
                <li><a href="<?php echo base_url(); ?>producto/etiqueta_por_categoria"><i class="fa fa-circle-o"></i> Etiquetas por categoria</a></li>           
           
  
              </ul>
            </li>

            <?php
            }
            ?>




            



            <?php
            if($is_admin == 1 ||
                (array_key_exists('Reporte', $access_info) 
                && ($access_info['Reporte']['total_access'] == 1)))
            {
              ?>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-calendar"></i> <span>Reportes</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
              <li><a href="<?php echo base_url(); ?>reporte/reporte_venta_diario"><i class="fa fa-calendar"></i> ventas diario</a></li>
                <li><a href="<?php echo base_url(); ?>reporte/reporte_venta_mensual"><i class="fa fa-calendar"></i> ventas mensual</a></li>
                <li><a href="<?php echo base_url(); ?>reporte/reporte_venta_por_fecha"><i class="fa fa-calendar"></i> lista ventas </a></li>
                <li><a href="<?php echo base_url(); ?>reporte/reporte_venta_productos_mas_vendidos"><i class="fa fa-calendar"></i> productos mas vendidos</a></li>
                <li><a href="<?php echo base_url(); ?>reporte/reporte_ganancias_por_fecha"><i class="fa fa-calendar"></i>Ganancias ventas producto</a></li>     
                      <li><a href="<?php echo base_url(); ?>reporte/reporte_compra_mensual"><i class="fa fa-calendar"></i> compras mensual</a></li>
                <li><a href="<?php echo base_url(); ?>reporte/reporte_compra_por_fecha"><i class="fa fa-calendar"></i> lista compras </a></li>      
              
              </ul>
            </li>

            <?php
            }
            ?>






         <?php
            if($is_admin == 1 ||
                (array_key_exists('Reporte_administrador', $access_info) 
                && ($access_info['Reporte_administrador']['total_access'] == 1)))
            {
              ?>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-calendar"></i> <span>Reportes Administrador</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
              <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_venta_diario"><i class="fa fa-calendar"></i> ventas diario</a></li>
                <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_venta_mensual"><i class="fa fa-calendar"></i> ventas mensual</a></li>
                <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_venta_por_fecha"><i class="fa fa-calendar"></i> lista ventas </a></li>
                <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_venta_productos_mas_vendidos"><i class="fa fa-calendar"></i> productos mas vendidos</a></li>
                <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_ganancias_ventas_productos"><i class="fa fa-calendar"></i>Ganancias ventas producto</a></li> 
                     <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_compra_mensual"><i class="fa fa-calendar"></i> compras mensual</a></li>
                <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_compra_por_fecha"><i class="fa fa-calendar"></i> lista compras </a></li>        

                              <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_traslado"><i class="fa fa-calendar"></i> traslados enviados</a></li>
                <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_traslado_recibido"><i class="fa fa-calendar"></i> Traslados recibidos </a></li>    
              </ul>
            </li>

            <?php
            }
            ?>











<?php
            if($is_admin == 1 ||
                (array_key_exists('Proveedor', $access_info) 
                && ($access_info['Proveedor']['total_access'] == 1)))
            {
              ?>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-anchor"></i> <span>Proveedor</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>proveedor"><i class="fa fa-circle-o"></i>proveedor</a></li>
                <li><a href="<?php echo base_url(); ?>proveedor/importar"><i class="fa fa-circle-o"></i> importar</a></li>
            
              </ul>
            </li>

            <?php
            }
            ?>





<?php
            if($is_admin == 1 ||
                (array_key_exists('Metodo_pago', $access_info) 
                && ($access_info['Metodo_pago']['total_access'] == 1)))
            {
              ?>
            <li>
              <a href="<?php echo base_url(); ?>metodo_pago">
                <i class="fa fa-money"></i>
                <span>Metodo pago</span>
              </a>
            </li>
              <?php
            }
            ?>



<?php
            if($is_admin == 1 ||
                (array_key_exists('Sucursal', $access_info) 
                && ($access_info['Sucursal']['total_access'] == 1)))
            {
              ?>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-gears"></i> <span>Sucursal</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>sucursal"><i class="fa fa-circle-o"></i>sucursal</a></li>
          
            
              </ul>
            </li>

            <?php
            }
            ?>




          </ul>


   
        </section>
        <!-- /.sidebar -->
      </aside>