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

    	/* Menú treeview: visible por hover o por click (pinned) */
    	.treeview:hover > .treeview-menu,
    	.treeview.pinned > .treeview-menu {
    		display: block !important;
    	}

    	.treeview:hover > a > .pull-right-container i,
    	.treeview.pinned > a > .pull-right-container i {
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

        // Toggle pinned al hacer click en un ítem con submenú
        $(document).ready(function() {
            $(document).on('click', '.sidebar-menu li.treeview > a', function(e) {
                e.preventDefault();
                var $li = $(this).parent();
                $li.toggleClass('pinned');
                // Cerrar otros ítems pinned cuando se abre uno nuevo
                if ($li.hasClass('pinned')) {
                    $li.siblings('li.treeview').removeClass('pinned');
                }
            });
        });
    </script>
  
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
   
    <script src="<?php echo base_url(); ?>assets/sheetjs/dist/xlsx.full.min.js"></script>
<?php
// Color de tema personal del usuario
$_color = $this->session->userdata('color_tema') ?: '#3c8dbc';
if (!preg_match('/^#[0-9a-fA-F]{6}$/', $_color)) $_color = '#3c8dbc';
$_hex   = ltrim($_color, '#');
$_dark  = sprintf('#%02x%02x%02x',
    (int)(hexdec(substr($_hex,0,2)) * 0.80),
    (int)(hexdec(substr($_hex,2,2)) * 0.80),
    (int)(hexdec(substr($_hex,4,2)) * 0.80)
);
?>
<style>
.skin-blue .main-header .navbar                        { background-color:<?php echo $_color; ?>; }
.skin-blue .main-header .logo,
.skin-blue .main-header .logo:hover                    { background-color:<?php echo $_dark; ?>; }
.skin-blue .main-header li.user-header                 { background-color:<?php echo $_color; ?>; }
.skin-blue .sidebar-menu>li.active>a,
.skin-blue .sidebar-menu>li:hover>a                    { background-color:<?php echo $_dark; ?>; border-left-color:<?php echo $_color; ?>; }
.skin-blue .sidebar-menu>li.active>a                   { border-left-color:<?php echo $_color; ?> !important; }
</style>
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
<?php
$_foto_ts  = $this->session->userdata('foto');
$_foto_uid = $this->session->userdata('userId');
if ($_foto_ts) {
    // Nuevo formato versionado: user_{id}_{ts}.jpg (sin query string, mejor caché/CDN)
    $_foto_file = 'user_' . $_foto_uid . '_' . $_foto_ts . '.jpg';
    // Fallback legado: si aún no existe el archivo versionado, usar nombre antiguo + ?v=
    if (!is_file(FCPATH . 'uploads/fotos/' . $_foto_file)) {
        $_foto_file = 'user_' . $_foto_uid . '.jpg?v=' . $_foto_ts;
    }
    $_foto_url = base_url('uploads/fotos/' . $_foto_file);
} else {
    $_foto_url = base_url('assets/dist/img/logodemo.png');
}
?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo $_foto_url; ?>" class="user-image" alt="User Image" style="object-fit:cover;"/>
                  <span class="hidden-xs"><?php echo $name; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo $_foto_url; ?>" class="img-circle" alt="User Image" style="object-fit:cover;"/>
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
            <?php
            $_sucNombre = (string) $this->session->userdata('nombre_sucursal');
            if ($_sucNombre === '') {
                // Fallback: leer de BD si la sesión no la tiene (sesiones previas al cambio).
                $_idSuc = (int) $this->session->userdata('id_sucursal');
                if ($_idSuc > 0) {
                    $_row = $this->db->select('nombre_sucursal')->from('tbl_sucursal')->where('id_sucursal', $_idSuc)->get()->row();
                    if ($_row) {
                        $_sucNombre = $_row->nombre_sucursal;
                        $this->session->set_userdata('nombre_sucursal', $_sucNombre);
                    }
                }
            }
            ?>
            <li class="header" style="text-transform:uppercase;font-weight:700;letter-spacing:.5px;">
              <i class="fa fa-map-marker"></i>
              <?php echo $_sucNombre !== '' ? htmlspecialchars($_sucNombre, ENT_QUOTES, 'UTF-8') : 'Sucursal'; ?>
            </li>
    


            <?php if($is_admin == 1 || !empty($access_info['Usuarios']['total_access'])): ?>
            <li>
              <a href="<?php echo base_url(); ?>userListing">
                <i class="fa fa-users"></i>
                <span>Usuarios</span>
              </a>
            </li>
            <?php endif; ?>
            <?php if($is_admin == 1 || !empty($access_info['Roles']['total_access'])): ?>
            <li>
              <a href="<?php echo base_url(); ?>roles/roleListing">
                <i class="fa fa-shield" aria-hidden="true"></i>
                <span>Roles</span>
              </a>
            </li>
            <?php endif; ?>
    




<?php
            if($is_admin == 1 ||
                (array_key_exists('Empleado', $access_info) 
                && ($access_info['Empleado']['total_access'] == 1)))
            {
              ?>

            <li>
              <a href="<?php echo base_url(); ?>empleado">
                <i class="fa fa-id-card-o"></i> <span>Empleados</span>
              </a>
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

            <li>
              <a href="<?php echo base_url(); ?>Cliente">
                <i class="fa fa-address-book-o"></i> <span>Clientes</span>
              </a>
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
            <li class="treeview">
              <a href="#">
                <i class="fa fa-minus-circle"></i> <span>Gastos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>gasto/add"><i class="fa fa-circle-o"></i>Registrar gasto</a></li>
                <li><a href="<?php echo base_url(); ?>gasto/gasto_lista"><i class="fa fa-circle-o"></i>Lista de gastos</a></li>
              </ul>
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
            <li class="treeview">
              <a href="#">
                <i class="fa fa-plus-circle"></i> <span>Ingresos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>ingreso/add"><i class="fa fa-circle-o"></i>Registrar ingreso</a></li>
                <li><a href="<?php echo base_url(); ?>ingreso/ingreso_lista"><i class="fa fa-circle-o"></i>Lista de ingresos</a></li>
              </ul>
            </li>
              <?php
            }
            ?>






<?php
            if($is_admin == 1 ||
                (array_key_exists('Caja', $access_info)
                && ($access_info['Caja']['total_access'] == 1)))
            {
              ?>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-money"></i> <span>Caja</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>caja/add"><i class="fa fa-circle-o"></i>Abrir caja</a></li>
                <li><a href="<?php echo base_url(); ?>caja/historial"><i class="fa fa-circle-o"></i>Historial de cajas</a></li>
              </ul>
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
                <i class="fa fa-shopping-cart"></i> <span>Ventas</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>carrito"><i class="fa fa-cart-arrow-down"></i>Punto de venta</a></li>
                <li><a href="<?php echo base_url(); ?>carrito/ventas_lista"><i class="fa fa-circle-o"></i>Todas las ventas</a></li>
                <li><a href="<?php echo base_url(); ?>carrito/ventas_lista_contado"><i class="fa fa-circle-o"></i>Ventas al contado</a></li>
                <li><a href="<?php echo base_url(); ?>carrito/ventas_lista_credito"><i class="fa fa-circle-o"></i>Ventas a crédito</a></li>
                <li><a href="<?php echo base_url(); ?>carrito/apartado_lista"><i class="fa fa-tags"></i>Apartados</a></li>
                <?php if ($is_admin == 1 || !empty($access_info['Ventas']['configurar_ticket'])): ?>
                <li><a href="<?php echo base_url('sucursal/ticket_config/'.(int)$this->session->userdata('id_sucursal')); ?>"><i class="fa fa-ticket"></i>Configurar Ticket</a></li>
                <?php endif; ?>
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
                <i class="fa fa-shopping-bag"></i> <span>Compras</span>
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
                <i class="fa fa-exchange"></i> <span>Traslados</span>
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
                <i class="fa fa-cubes"></i> <span>Productos</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>categoria/categoria_lista"><i class="fa fa-circle-o"></i>Categorías</a></li>
                <li><a href="<?php echo base_url(); ?>producto"><i class="fa fa-circle-o"></i>Listado de productos</a></li>
                <li><a href="<?php echo base_url(); ?>producto/resurtir"><i class="fa fa-circle-o"></i>Resurtir Producto</a></li>
                <?php if ($is_admin == 1 || (array_key_exists('Productos', $access_info) && !empty($access_info['Productos']['gestionar']))): ?>
                <li><a href="<?php echo base_url(); ?>producto/add"><i class="fa fa-circle-o"></i>Agregar Producto</a></li>
                <li><a href="<?php echo base_url(); ?>producto/importar"><i class="fa fa-circle-o"></i>Importar</a></li>
                <?php endif; ?>
                <li><a href="<?php echo base_url(); ?>producto/etiqueta"><i class="fa fa-circle-o"></i>Impresión de etiquetas</a></li>
              </ul>
            </li>

            <?php
            }
            ?>




            



            <?php if (($is_admin == 1 || (array_key_exists('Reportes', $access_info) && ($access_info['Reportes']['total_access'] == 1))) && !empty($accessible_reports)) { ?>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-bar-chart"></i> <span>Reportes</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>reporte"><i class="fa fa-th-large"></i>Centro de reportes</a></li>
                <?php foreach ($accessible_reports as $report) { ?>
                <?php if (!empty($report['url'])) { ?>
                <li><a href="<?php echo $report['url']; ?>"><i class="fa <?php echo !empty($report['icon']) ? $report['icon'] : 'fa-circle-o'; ?>"></i><?php echo $report['title']; ?></a></li>
                <?php } ?>
                <?php } ?>
              </ul>
            </li>
            <?php } ?>











<?php
            if($is_admin == 1 ||
                (array_key_exists('Proveedores', $access_info)
                && ($access_info['Proveedores']['total_access'] == 1)))
            {
              ?>

            <li>
              <a href="<?php echo base_url(); ?>proveedor">
                <i class="fa fa-truck"></i> <span>Proveedores</span>
              </a>
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
                <i class="fa fa-credit-card"></i>
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

            <li>
              <a href="<?php echo base_url(); ?>sucursal">
                <i class="fa fa-building"></i> <span>Sucursales</span>
              </a>
            </li>

            <?php
            }
            ?>




          </ul>


   
        </section>
        <!-- /.sidebar -->
      </aside>
