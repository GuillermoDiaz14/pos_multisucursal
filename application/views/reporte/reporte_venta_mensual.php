<?php
$currentYear = date('Y');
?>
<style>

#myTable {
    border-collapse: collapse;
    width: 100%;
}

#myTable th, #myTable td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

#myTable th {
    background-color: #f2f2f2;
}

#myTable tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

#myTable tbody tr:hover {
    background-color: #ddd;
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
      
       
       
      

 

          <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="yearFilter">Filtrar por año</label>
                                        <select class="form-control required" id="yearFilter" name="yearFilter">
                                        <option value="0">Selecciona año</option>
                                        <?php for ($year = 2023; $year <= 2030; $year++) : ?>
                                            <?php if ($year == $currentYear) : ?>
                                                <option value="<?= $year ?>" selected><?= $year ?></option>
                                            <?php else : ?>
                                                <option value="<?= $year ?>"><?= $year ?></option>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </select>

                                    </div>
          </div> 
          <div class="col-md-11">
          <div id="tableContainer">
          <div class="table-responsive">
                    <table class="table table-striped" >
                        <thead>
                            <tr >
                                <th class="p-2">Año</th>
                                <th class="p-2">Enero</th>
                                <th class="p-2">Febrero</th>
                                <th class="p-2">Marzo</th>
                                <th class="p-2">Abril</th>
                                <th class="p-2">Mayo</th>
                                <th class="p-2">Junio</th>
                                <th class="p-2">Julio</th>
                                <th class="p-2">Agosto</th>
                                <th class="p-2">Septiembre</th>
                                <th class="p-2">Octubre</th>
                                <th class="p-2">Noviembre</th>
                                <th class="p-2">Diciembre</th>
                            </tr>
                        </thead>
                        <tbody id="costByYear">
                            <!-- Aquí se mostrarán los totals por año y mes -->
                        </tbody>
                    </table>
                </div>
         </div>
</div>

    </section>
</div>


<script>

  
    // Obtén los datos de ventas del servidor
    const ventas = <?= json_encode($ventas) ?>;

    // Función para calcular el total total por año y mes
    function calcularTotalesAdicionalesPorAnioYMes() {
    const totalesAdicionalesPorAnioYMes = {};
    ventas.forEach(venta => {
        const fecha = new Date(venta.fecha_venta);
        const year = fecha.getFullYear();
        const month = fecha.getMonth();
        const descuento = parseFloat(venta.descuento) || 0;
        const baseImponible = parseFloat(venta.base_imponible) || 0;
        const impuesto = parseFloat(venta.impuesto) || 0;
        const total = parseFloat(venta.total) || 0;

        if (!isNaN(year) && !isNaN(month)) {
            if (!totalesAdicionalesPorAnioYMes[year]) {
                totalesAdicionalesPorAnioYMes[year] = {};
            }
            if (!totalesAdicionalesPorAnioYMes[year][month]) {
                totalesAdicionalesPorAnioYMes[year][month] = {
                    descuento: 0,
                    baseImponible: 0,
                    impuesto: 0,
                    total: 0,
                };
            }
            totalesAdicionalesPorAnioYMes[year][month].descuento += descuento;
            totalesAdicionalesPorAnioYMes[year][month].baseImponible += baseImponible;
            totalesAdicionalesPorAnioYMes[year][month].impuesto += impuesto;
            totalesAdicionalesPorAnioYMes[year][month].total += total;
        }
    });
    return totalesAdicionalesPorAnioYMes;
}


    // Función para actualizar la tabla con los totals por año y mes
// Escucha el cambio en el filtro de año
const yearFilter = document.getElementById("yearFilter");
yearFilter.addEventListener("change", () => {
    const selectedYear = parseInt(yearFilter.value);
    actualizarTabla(selectedYear);
});

// Función para filtrar y actualizar la tabla según el año seleccionado
function actualizarTabla(selectedYear) {
    const totalesAdicionalesPorAnioYMes = calcularTotalesAdicionalesPorAnioYMes();
    const tableBody = document.getElementById("costByYear");
    tableBody.innerHTML = "";

    for (const year in totalesAdicionalesPorAnioYMes) {
        if (selectedYear === 0 || selectedYear === parseInt(year)) {
            const row = document.createElement("tr");
            const yearCell = document.createElement("td");
            yearCell.textContent = year;
            row.appendChild(yearCell);

            for (let month = 0; month < 12; month++) {
                const totalCell = document.createElement("td");
                const totalData = totalesAdicionalesPorAnioYMes[year][month] || {
                    descuento: 0,
                    baseImponible: 0,
                    impuesto: 0,
                    total: 0,
                };

                // Aplica estilos de Bootstrap a las partes del total
                totalCell.innerHTML = `
    <span class="text-danger h4 font-weight-bold">Descuento</span>: ${totalData.descuento}<br>
    <span class="text-success h4 font-weight-bold">Base Imponible</span>: ${totalData.baseImponible}<br>
    <span class="text-primary h4 font-weight-bold">Impuesto</span>: ${totalData.impuesto}<br>
    <span class="text-info h4 font-weight-bold">Total</span>: ${totalData.total}
`;

                // Obtén el valor total como número para aplicar el estilo de fondo
                const valorTotal = parseFloat(totalData.total);

                // Aplica estilos de fondo de Bootstrap a la celda según el valor total
                if (valorTotal > 1000) {
                    totalCell.classList.add("bg-danger"); // Fondo rojo para valores altos
                } else if (valorTotal < 500) {
                    totalCell.classList.add("bg-warning"); // Fondo rojo para valores altos
                }
                // Puedes ajustar las clases de Bootstrap y los umbrales según tus necesidades

                row.appendChild(totalCell);
            }

            tableBody.appendChild(row);
        }
    }
}



// Inicialmente, muestra todos los totals
//const yearFilter = document.getElementById("yearFilter");

// Obtén el año actual usando JavaScript
const currentYear = new Date().getFullYear();

// Establece el año actual como seleccionado por defecto
yearFilter.value = currentYear;

// Llama a la función de actualización de la tabla con el año actual
actualizarTabla(currentYear);
</script>
