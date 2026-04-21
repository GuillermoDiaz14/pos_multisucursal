<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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

    	/* Expandir menú treeview al hacer hover */
    	.treeview:hover > .treeview-menu {
    		display: block !important;
    	}

    	.treeview:hover > a > .pull-right-container i {
    		transform: rotate(-90deg);
    		transition: transform 0.3s ease;
    	}

    	.treeview-menu {
    		transition: all 0.3s ease;
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
          <span class="logo-mini"><b>POS</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Pos</b> multisucursal</span>
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
                  <li class="header"> Último acceso: <i class="fa fa-clock-o"></i> <?= empty($last_login) ? "Primer acceso" : $last_login; ?></li
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
                      <a href="<?php echo base_url(); ?>logout" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Cerrar sesión</a>
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
            <li class="header">Navegación principal</li>
    


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
                <i class="fa fa-anchor"></i> <span>Empleados</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>empleado"><i class="fa fa-circle-o"></i>Empleados</a></li>
                <li><a href="<?php echo base_url(); ?>empleado/importar"><i class="fa fa-circle-o"></i>Importar</a></li>
            
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
                <i class="fa fa-anchor"></i> <span>Clientes</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>Cliente"><i class="fa fa-circle-o"></i>Clientes</a></li>
                <li><a href="<?php echo base_url(); ?>Cliente/importar"><i class="fa fa-circle-o"></i>Importar</a></li>
            
              </ul>
            </li>

            <?php
            }
            ?>



  







<?php
            if($is_admin == 1 ||
                (array_key_exists('Gastos', $access_info) 
                && ($access_info['Gastos']['total_access'] == 1)))
            {
              ?>
            <li>
              <a href="<?php echo base_url(); ?>gasto">
                <i class="fa fa-money"></i>
                <span>Gastos</span>
              </a>
            </li>
              <?php
            }
            ?>


<?php
            if($is_admin == 1 ||
                (array_key_exists('Ingresos', $access_info) 
                && ($access_info['Ingresos']['total_access'] == 1)))
            {
              ?>
            <li>
              <a href="<?php echo base_url(); ?>ingreso">
                <i class="fa fa-money"></i>
                <span>Ingresos</span>
              </a>
            </li>
              <?php
            }
            ?>






<?php
            if($is_admin == 1 ||
                (array_key_exists('Ventas', $access_info) 
                && ($access_info['Ventas']['total_access'] == 1)))
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
                <li><a href="<?php echo base_url(); ?>carrito"><i class="fa fa-cart-arrow-down"></i>Punto de venta</a></li>
                <li><a href="<?php echo base_url(); ?>carrito/ventas_lista"><i class="fa fa-circle-o"></i>Todas las ventas</a></li>
                <li><a href="<?php echo base_url(); ?>carrito/ventas_lista_contado"><i class="fa fa-circle-o"></i>Ventas al contado</a></li>
            <li><a href="<?php echo base_url(); ?>carrito/ventas_lista_credito"><i class="fa fa-circle-o"></i>Ventas a crédito</a></li>
              </ul>
            </li>

            <?php
            }
            ?>


<?php
            if($is_admin == 1 ||
                (array_key_exists('Compras', $access_info) 
                && ($access_info['Compras']['total_access'] == 1)))
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
                <li><a href="<?php echo base_url(); ?>entrada"><i class="fa fa-cart-plus"></i>Registrar compra</a></li>
                <li><a href="<?php echo base_url(); ?>entrada/entradas_lista"><i class="fa fa-circle-o"></i>Historial de compras</a></li>
            
              </ul>
            </li>

            <?php
            }
            ?>




<?php
            if($is_admin == 1 ||
                (array_key_exists('Traslados', $access_info) 
                && ($access_info['Traslados']['total_access'] == 1)))
            {
              ?>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-cart-plus"></i> <span>Traslados</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>trasladar"><i class="fa fa-cart-plus"></i>Nuevo traslado</a></li>
                <li><a href="<?php echo base_url(); ?>trasladar/trasladar_lista"><i class="fa fa-circle-o"></i>Traslados enviados</a></li>
                   <li><a href="<?php echo base_url(); ?>trasladar/trasladar_lista_Recibidos"><i class="fa fa-circle-o"></i>Traslados recibidos</a></li>
            
              </ul>
            </li>

            <?php
            }
            ?>







<?php
            if($is_admin == 1 ||
                (array_key_exists('Productos', $access_info) 
                && ($access_info['Productos']['total_access'] == 1)))
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
                   <li><a href="<?php echo base_url(); ?>categoria/categoria_lista"><i class="fa fa-circle-o"></i>Categorías</a></li>
                <li><a href="<?php echo base_url(); ?>producto"><i class="fa fa-circle-o"></i>Productos</a></li>
                <li><a href="<?php echo base_url(); ?>producto/add"><i class="fa fa-circle-o"></i>Agregar Producto</a></li>
                <li><a href="<?php echo base_url(); ?>producto/resurtir"><i class="fa fa-circle-o"></i>Resurtir Producto</a></li>
                <li><a href="<?php echo base_url(); ?>producto/importar"><i class="fa fa-circle-o"></i>Importar</a></li>
                <li><a href="<?php echo base_url(); ?>producto/etiqueta"><i class="fa fa-circle-o"></i>Impresión de etiquetas</a></li>
           
  
              </ul>
            </li>

            <?php
            }
            ?>




            



            <?php
            if($is_admin == 1 ||
                (array_key_exists('Reportes', $access_info) 
                && ($access_info['Reportes']['total_access'] == 1)))
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
              <li><a href="<?php echo base_url(); ?>reporte/reporte_venta_diario"><i class="fa fa-calendar"></i>Ventas diarias</a></li>
                <li><a href="<?php echo base_url(); ?>reporte/reporte_venta_mensual"><i class="fa fa-calendar"></i>Ventas mensuales</a></li>
                <li><a href="<?php echo base_url(); ?>reporte/reporte_venta_por_fecha"><i class="fa fa-calendar"></i>Ventas por fecha</a></li>
                <li><a href="<?php echo base_url(); ?>reporte/reporte_venta_productos_mas_vendidos"><i class="fa fa-calendar"></i>Productos más vendidos</a></li>
                <li><a href="<?php echo base_url(); ?>reporte/reporte_ganancias_por_fecha"><i class="fa fa-calendar"></i>Ganancias por producto</a></li>     
                      <li><a href="<?php echo base_url(); ?>reporte/reporte_compra_mensual"><i class="fa fa-calendar"></i>Compras mensuales</a></li>
                <li><a href="<?php echo base_url(); ?>reporte/reporte_compra_por_fecha"><i class="fa fa-calendar"></i>Compras por fecha</a></li>      
              
              </ul>
            </li>

            <?php
            }
            ?>






         <?php
            if($is_admin == 1 ||
                (array_key_exists('Reportes Administrativos', $access_info) 
                && ($access_info['Reportes Administrativos']['total_access'] == 1)))
            {
              ?>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-calendar"></i> <span>Reportes de administrador</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
              <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_venta_diario"><i class="fa fa-calendar"></i>Ventas diarias</a></li>
                <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_venta_mensual"><i class="fa fa-calendar"></i>Ventas mensuales</a></li>
                <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_venta_por_fecha"><i class="fa fa-calendar"></i>Ventas por fecha</a></li>
                <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_venta_productos_mas_vendidos"><i class="fa fa-calendar"></i>Productos más vendidos</a></li>
                <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_ganancias_ventas_productos"><i class="fa fa-calendar"></i>Ganancias por producto</a></li> 
                     <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_compra_mensual"><i class="fa fa-calendar"></i>Compras mensuales</a></li>
                <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_sucursal_compra_por_fecha"><i class="fa fa-calendar"></i>Compras por fecha</a></li>        

                              <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_traslado"><i class="fa fa-calendar"></i>Traslados enviados</a></li>
                <li><a href="<?php echo base_url(); ?>reporte_administrador/seleccion_traslado_recibido"><i class="fa fa-calendar"></i>Traslados recibidos</a></li>    
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
                <i class="fa fa-anchor"></i> <span>Proveedores</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>proveedor"><i class="fa fa-circle-o"></i>Proveedores</a></li>
                <li><a href="<?php echo base_url(); ?>proveedor/importar"><i class="fa fa-circle-o"></i>Importar</a></li>
            
              </ul>
            </li>

            <?php
            }
            ?>





<?php
            if($is_admin == 1 ||
                (array_key_exists('Métodos de Pago', $access_info) 
                && ($access_info['Métodos de Pago']['total_access'] == 1)))
            {
              ?>
            <li>
              <a href="<?php echo base_url(); ?>metodo_pago">
                <i class="fa fa-money"></i>
                <span>Métodos de pago</span>
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
                <i class="fa fa-gears"></i> <span>Sucursales</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>sucursal"><i class="fa fa-circle-o"></i>Sucursales</a></li>
          
            
              </ul>
            </li>

            <?php
            }
            ?>




          </ul>


   
        </section>
        <!-- /.sidebar -->
      </aside>
