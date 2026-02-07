<?php foreach ($ventas as $key => $venta): ?>
                          <?php 
                          $fecha_venta= $venta->fecha_venta; 
                          $id_producto= $venta->id_producto; 
                          $cantidad= $venta->cantidad; 
                          $nombre_producto= $venta->nombre_producto; 
                          ?>
<?php endforeach; ?>


<?php
// Crear un arreglo para almacenar la suma de ventas por producto


$top10Productos = [];

// Obtén el mes actual y el año actual
$mes_actual = date('n');
$anio_actual = date('Y');

// Crear un arreglo para almacenar la suma de ventas por producto
$ventasPorProducto = [];

foreach ($ventas as $venta) {
    $fecha_venta = new DateTime($venta->fecha_venta);
    $mes_venta = $fecha_venta->format('n');
    $anio_venta = $fecha_venta->format('Y');

    // Verifica si la venta corresponde al mes y año actual
    if ($mes_venta == $mes_actual && $anio_venta == $anio_actual) {
        $id_producto = $venta->id_producto;
        $nombre_producto = $venta->nombre_producto;
        $cantidad = $venta->cantidad;

        if (!isset($ventasPorProducto[$id_producto])) {
            $ventasPorProducto[$id_producto] = [
                "nombre_producto" => $nombre_producto,
                "suma_ventas" => 0
            ];
        }

        $ventasPorProducto[$id_producto]["suma_ventas"] += $cantidad;
    }
}

// Ordenar los productos por la suma de ventas en orden descendente
usort($ventasPorProducto, function($a, $b) {
    return $b["suma_ventas"] - $a["suma_ventas"];
});

// Tomar los primeros 10 productos
$top10Productos = array_slice($ventasPorProducto, 0, 10);
//ahora filtrar del mes anterior


$top10ProductosMesAnterior = [];

// Obtén el mes y el año del mes anterior
$mes_actual = date('n');
$anio_actual = date('Y');

// Calcular el mes y el año del mes anterior
$mes_anterior = ($mes_actual - 1) % 12; // Restar 1 mes y ajustar para el caso de enero
$anio_anterior = ($mes_actual === 1) ? $anio_actual - 1 : $anio_actual;

$ventasPorProducto = [];

foreach ($ventas as $venta) {
    $fecha_venta = new DateTime($venta->fecha_venta);
    $mes_venta = $fecha_venta->format('n');
    $anio_venta = $fecha_venta->format('Y');

    // Verifica si la venta corresponde al mes y año anterior
    if ($mes_venta == $mes_anterior && $anio_venta == $anio_anterior) {
        $id_producto = $venta->id_producto;
        $nombre_producto = $venta->nombre_producto;
        $cantidad = $venta->cantidad;

        if (!isset($ventasPorProducto[$id_producto])) {
            $ventasPorProducto[$id_producto] = [
                "nombre_producto" => $nombre_producto,
                "suma_ventas" => 0
            ];
        }

        $ventasPorProducto[$id_producto]["suma_ventas"] += $cantidad;
    }
}

// Ordenar los productos por la suma de ventas en orden descendente
usort($ventasPorProducto, function($a, $b) {
    return $b["suma_ventas"] - $a["suma_ventas"];
});

// Tomar los primeros 10 productos
$top10ProductosMesAnterior = array_slice($ventasPorProducto, 0, 10);



// ultimos 3 meses



$top10ProductosUltimos3Meses = [];

// Obtén el mes y el año actual
$mes_actual = date('n');
$anio_actual = date('Y');

$ventasPorProducto = [];

foreach ($ventas as $venta) {
    $fecha_venta = new DateTime($venta->fecha_venta);
    $mes_venta = $fecha_venta->format('n');
    $anio_venta = $fecha_venta->format('Y');

    // Calcula los límites del rango de los últimos 3 meses
    $limite_inferior = ($mes_actual - 3 + 12) % 12 + 1; // Restar 3 meses y ajustar para el caso de enero
    $anio_limite_inferior = $anio_actual - ($mes_actual < 4 ? 1 : 0);

    // Verifica si la venta corresponde a los últimos 3 meses
    if (($anio_venta == $anio_actual && $mes_venta >= $limite_inferior) ||
        ($anio_venta == $anio_limite_inferior && $mes_venta >= $limite_inferior + 12)
    ) {
        $id_producto = $venta->id_producto;
        $nombre_producto = $venta->nombre_producto;
        $cantidad = $venta->cantidad;

        if (!isset($ventasPorProducto[$id_producto])) {
            $ventasPorProducto[$id_producto] = [
                "nombre_producto" => $nombre_producto,
                "suma_ventas" => 0
            ];
        }

        $ventasPorProducto[$id_producto]["suma_ventas"] += $cantidad;
    }
}

// Ordenar los productos por la suma de ventas en orden descendente
usort($ventasPorProducto, function($a, $b) {
    return $b["suma_ventas"] - $a["suma_ventas"];
});

// Tomar los primeros 10 productos
$top10ProductosUltimos3Meses = array_slice($ventasPorProducto, 0, 10);

//top 10 ultimos 10 meses
//
///
$top10ProductosUltimos10Meses = [];

// Obtén el mes y el año actual
$mes_actual = date('n');
$anio_actual = date('Y');

$ventasPorProducto = [];

foreach ($ventas as $venta) {
    $fecha_venta = new DateTime($venta->fecha_venta);
    $mes_venta = $fecha_venta->format('n');
    $anio_venta = $fecha_venta->format('Y');

    // Calcula los límites del rango de los últimos 12 meses
    $limite_inferior = ($mes_actual - 12 + 10) % 12 + 1; // Restar 12 meses y ajustar para el caso de enero
    $anio_limite_inferior = $anio_actual - ($mes_actual < 11 ? 1 : 0);

    // Verifica si la venta corresponde a los últimos 12 meses
    if (($anio_venta == $anio_actual && $mes_venta >= $limite_inferior) ||
        ($anio_venta == $anio_limite_inferior && $mes_venta >= $limite_inferior + 12)
    ) {
        $id_producto = $venta->id_producto;
        $nombre_producto = $venta->nombre_producto;
        $cantidad = $venta->cantidad;

        if (!isset($ventasPorProducto[$id_producto])) {
            $ventasPorProducto[$id_producto] = [
                "nombre_producto" => $nombre_producto,
                "suma_ventas" => 0
            ];
        }

        $ventasPorProducto[$id_producto]["suma_ventas"] += $cantidad;
    }
}

// Ordenar los productos por la suma de ventas en orden descendente
usort($ventasPorProducto, function($a, $b) {
    return $b["suma_ventas"] - $a["suma_ventas"];
});

// Tomar los primeros 10 productos
$top10ProductosUltimos10Meses = array_slice($ventasPorProducto, 0, 10);




//var_dump($top10ProductosUltimos10Meses);
?>



<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-tachometer" aria-hidden="true"></i> Top productos mas vendidos
        <small>productos mas vendidos</small>
      </h1>
    </section>
    
    <section class="content">


          <canvas id="graficoVentas" width="400" height="200"></canvas>
          <canvas id="graficoVentasmesanterior" width="400" height="200"></canvas>
          <canvas id="graficoVentasultimos3meses" width="400" height="200"></canvas>
          <canvas id="graficoVentasultimos10meses" width="400" height="200"></canvas>
          

    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
// Supongamos que tienes los datos de los 10 productos principales en un arreglo en PHP llamado $top10Productos
// Puedes convertir estos datos de PHP a datos de JavaScript así:
var top10Productos = <?php echo json_encode($top10Productos); ?>;
console.log(top10Productos);

// Ahora, procesa los datos para obtener etiquetas y valores para el gráfico
var labels = [];
var data = [];

Object.keys(top10Productos).forEach(function(id_producto) {
    labels.push(top10Productos[id_producto].nombre_producto);
    data.push(top10Productos[id_producto].suma_ventas);
    console.log(top10Productos[id_producto].suma_ventas);
});

// Obtén el nombre del mes actual
var fechaActual = new Date();
var meses = [
    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
];
var mesActual = meses[fechaActual.getMonth()];

// Combina el label
var label = "Top 10 Productos " + mesActual;

// Ahora, utiliza Chart.js para crear el gráfico
var ctx = document.getElementById("graficoVentas").getContext("2d");

var grafico = new Chart(ctx, {
    type: "bar",
    data: {
        labels: labels,
        datasets: [
            {
                label: label,
                data: data,
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



<script>
// Supongamos que tienes los datos de los 10 productos principales en un arreglo en PHP llamado $top10Productos
// Puedes convertir estos datos de PHP a datos de JavaScript así:
var top10ProductosMesAnterior = <?php echo json_encode($top10ProductosMesAnterior); ?>;
console.log(top10ProductosMesAnterior);

// Ahora, procesa los datos para obtener etiquetas y valores para el gráfico
var labels = [];
var data = [];

Object.keys(top10ProductosMesAnterior).forEach(function(id_producto) {
    labels.push(top10ProductosMesAnterior[id_producto].nombre_producto);
    data.push(top10ProductosMesAnterior[id_producto].suma_ventas);
    console.log(top10ProductosMesAnterior[id_producto].suma_ventas);
});

// Obtén el nombre del mes actual
var mesAnterior = meses[(fechaActual.getMonth() + 11) % 12]; // Suma 11 y ajusta para el caso de enero

var meses = [
    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
];
var mesActual = meses[fechaActual.getMonth()];

// Combina el label
var label = "Top 10 Productos " + mesAnterior;

// Ahora, utiliza Chart.js para crear el gráfico
var ctx = document.getElementById("graficoVentasmesanterior").getContext("2d");

var grafico = new Chart(ctx, {
    type: "bar",
    data: {
        labels: labels,
        datasets: [
            {
                label: label,
                data: data,
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









<script>
// Supongamos que tienes los datos de los 10 productos principales en un arreglo en PHP llamado $top10Productos
// Puedes convertir estos datos de PHP a datos de JavaScript así:
var top10ProductosUltimos3Meses = <?php echo json_encode($top10ProductosUltimos3Meses); ?>;
console.log(top10ProductosUltimos3Meses);

// Ahora, procesa los datos para obtener etiquetas y valores para el gráfico
var labels = [];
var data = [];

Object.keys(top10ProductosUltimos3Meses).forEach(function(id_producto) {
    labels.push(top10ProductosUltimos3Meses[id_producto].nombre_producto);
    data.push(top10ProductosUltimos3Meses[id_producto].suma_ventas);
    console.log(top10ProductosUltimos3Meses[id_producto].suma_ventas);
});





// Combina el label
var label = "Top 10 Productos ultimos 3 meses" ;

// Ahora, utiliza Chart.js para crear el gráfico
var ctx = document.getElementById("graficoVentasultimos3meses").getContext("2d");

var grafico = new Chart(ctx, {
    type: "bar",
    data: {
        labels: labels,
        datasets: [
            {
                label: label,
                data: data,
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




<script>
//    console.log("12 meses");
// Supongamos que tienes los datos de los 10 productos principales en un arreglo en PHP llamado $top10Productos
// Puedes convertir estos datos de PHP a datos de JavaScript así:
var top10ProductosUltimos10Meses = <?php echo json_encode($top10ProductosUltimos10Meses); ?>;
console.log(top10ProductosUltimos10Meses);

// Ahora, procesa los datos para obtener etiquetas y valores para el gráfico
var labels = [];
var data = [];

Object.keys(top10ProductosUltimos10Meses).forEach(function(id_producto) {
    labels.push(top10ProductosUltimos10Meses[id_producto].nombre_producto);
    data.push(top10ProductosUltimos10Meses[id_producto].suma_ventas);
    console.log(top10ProductosUltimos10Meses[id_producto].suma_ventas);
});





// Combina el label
var label = "Top 10 Productos ultimos 10 meses" ;

// Ahora, utiliza Chart.js para crear el gráfico
var ctx = document.getElementById("graficoVentasultimos10meses").getContext("2d");

var grafico = new Chart(ctx, {
    type: "bar",
    data: {
        labels: labels,
        datasets: [
            {
                label: label,
                data: data,
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