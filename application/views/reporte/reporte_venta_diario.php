<?php
$currentYear = date('Y');
?>
    <style>
        .mes-y-anio {
            text-align: center;
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: center;
            border: 1px solid #ccc;
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            cursor: pointer;
        }
    </style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-tachometer" aria-hidden="true"></i> Reporte
        <small>Reporte ventas mensual



        </small>
      </h1>
    </section>
    
    <section class="content">
      
       
       
      

 

        
                              
       
          <div class="col-md-11">
          <div id="tableContainer">
          <div class="table-responsive">
             
                </div>
         </div>
</div>
<div class="col-md-12">

<div id="calendario">
    <button id="mes-anterior">Mes Anterior</button>
    <div id="calendario-mes"></div>
    <button id="mes-siguiente">Mes Siguiente</button>
</div>
</div> 

    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

  

</script>
<script>
        var calendario = document.getElementById('calendario-mes');
        var mesActual = new Date();
        var totalesPorDia = <?= json_encode($totalesPorDia) ?>;

        function mostrarCalendario(totalesPorDia) {
            calendario.innerHTML = '';

            var opcionesFecha = { year: 'numeric', month: 'long' };
            var mesYAnio = document.createElement('div');
            mesYAnio.className = 'mes-y-anio';
            mesYAnio.textContent = mesActual.toLocaleDateString(undefined, opcionesFecha);
            calendario.appendChild(mesYAnio);

            var tablaCalendario = document.createElement('table');
            var filaEncabezados = document.createElement('tr');
            var diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

            for (var i = 0; i < 7; i++) {
                var celda = document.createElement('th');
                celda.textContent = diasSemana[i];
                filaEncabezados.appendChild(celda);
            }

            tablaCalendario.appendChild(filaEncabezados);

            var filaDias = document.createElement('tr');
            var diaSemana = mesActual.getDay();

            for (var i = 0; i < diaSemana; i++) {
                var celdaVacia = document.createElement('td');
                filaDias.appendChild(celdaVacia);
            }

            var ultimoDiaMes = new Date(mesActual.getFullYear(), mesActual.getMonth() + 1, 0).getDate();

            for (var dia = 1; dia <= ultimoDiaMes; dia++) {
                var celdaDia = document.createElement('td');
                celdaDia.textContent = dia;

                var fecha = mesActual.getFullYear() + '-' + (mesActual.getMonth() + 1).toString().padStart(2, '0') + '-' + dia.toString().padStart(2, '0');
                if (totalesPorDia[fecha]) {
                    var totalDia = totalesPorDia[fecha];
                    if (!isNaN(totalDia.suma_base_imponible)) {
    celdaDia.innerHTML += '<br>' + 'base imponible: ' + totalDia.suma_base_imponible.toFixed(2);
                    }
                    if (!isNaN(totalDia.suma_impuesto)) {
    celdaDia.innerHTML += '<br>' + 'impuesto: ' + totalDia.suma_impuesto.toFixed(2);
                    }
                    if (!isNaN(totalDia.suma_descuento)) {
    celdaDia.innerHTML += '<br>' + 'descuento: ' + totalDia.suma_descuento.toFixed(2);
                    }
                    if (!isNaN(totalDia.suma_total)) {
    celdaDia.innerHTML += '<br>' + 'Total: ' + totalDia.suma_total.toFixed(2);
                    }
                }

                filaDias.appendChild(celdaDia);

                if (diaSemana === 6) {
                    tablaCalendario.appendChild(filaDias);
                    filaDias = document.createElement('tr');
                }

                diaSemana = (diaSemana + 1) % 7;
            }

            tablaCalendario.appendChild(filaDias);
            calendario.appendChild(tablaCalendario);
        }

        function retrocederMes() {
            mesActual.setMonth(mesActual.getMonth() - 1);
            mostrarCalendario(totalesPorDia);
        }

        function avanzarMes() {
            mesActual.setMonth(mesActual.getMonth() + 1);
            mostrarCalendario(totalesPorDia);
        }

        document.getElementById('mes-anterior').addEventListener('click', retrocederMes);
        document.getElementById('mes-siguiente').addEventListener('click', avanzarMes);

        mostrarCalendario(totalesPorDia);
    </script>

