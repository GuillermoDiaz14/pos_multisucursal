<?php foreach ($ventas as $key => $venta): ?>
                          <?php 
                          $fecha_venta= $venta->fecha_venta; 
                          $total= $venta->total; 
                    
                          ?>
<?php endforeach; 
?>


<?php foreach ($sucursales as $key => $sucursal): ?>
                          <?php 
                          $id_sucursal= $sucursal->id_sucursal;                      
                    $nombre_sucursal= $sucursal->nombre_sucursal; 
                          ?>
<?php endforeach; 
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard
        <small>panel Control <?php echo $nombre_sucursal; ?></small>
      </h1>
    </section>
    
    <section class="content">
        <div class="row">



<?php
            if($is_admin == 1 ||
                (array_key_exists('Carrito', $access_info) 
                && ($access_info['Carrito']['total_access'] == 1)))
            {
              ?>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>53<sup style="font-size: 20px">%</sup></h3>
                  <p>Pos ventas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="carrito/carrito" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->

<?php
            }
            ?>

            <?php
            if($is_admin == 1 ||
                (array_key_exists('Producto', $access_info) 
                && ($access_info['Producto']['total_access'] == 1)))
            {
              ?>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3>44</h3>
                  <p>Productos</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="<?php echo base_url(); ?>producto/producto_lista" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->


       <?php
            }
            ?>



<?php
            if($is_admin == 1 ||
                (array_key_exists('Cliente', $access_info) 
                && ($access_info['Cliente']['total_access'] == 1)))
            {
              ?>

            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>65</h3>
                  <p>Clientes</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="cliente/cliente_lista" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->

   <?php
            }
            ?>



            
          </div>



 


          <canvas id="graficoVentas" width="400" height="200"></canvas>

    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Supongamos que tienes los datos de ventas en un arreglo en PHP llamado $ventas
// Puedes convertir estos datos de PHP a datos de JavaScript así:
var datosVentas = <?php echo json_encode($ventas); ?>;

// Ahora, procesa los datos para obtener los totales por mes
var ventasPorMes = {};

datosVentas.forEach(function(venta) {
    var fechaVenta = new Date(venta.fecha_venta);
    var mes = fechaVenta.getMonth(); // Obtiene el mes (0-11)
    var total = parseFloat(venta.total); // Asegúrate de que el total sea un número

    if (!ventasPorMes[mes]) {
        ventasPorMes[mes] = 0;
    }

    ventasPorMes[mes] += total;
});

// Asegúrate de que tengas un arreglo con 12 meses, incluso si no tienes datos para algunos meses.
var labels = [
    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
];

// Crea un arreglo de totales de ventas para cada mes.
var datosMeses = labels.map(function(mes, index) {
    return ventasPorMes[index] || 0; // Usa 0 si no hay datos para el mes.
});

// Ahora, utiliza Chart.js para crear el gráfico
var ctx = document.getElementById("graficoVentas").getContext("2d");

var grafico = new Chart(ctx, {
    type: "bar",
    data: {
        labels: labels,
        datasets: [
            {
                label: "Ventas por mes",
                data: datosMeses,
                backgroundColor: "rgba(75, 192, 192, 0.2)",
                borderColor: "rgba(75, 192, 192, 1)",
                borderWidth: 1
            }
        ]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>




