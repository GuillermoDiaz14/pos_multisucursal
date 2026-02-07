
<style>

.pagina-actual {
    background-color: #007bff;
    color: white;
}

</style>
<!-- Acceder a los datos de tbl_configuracion -->


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Lista ventas
        <small>Ventas</small>
      </h1>
    </section>
    <section class="content">

    <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="fecha_ingreso">Fecha incial</label>
                                        <input type="date" class="form-control required"  id="fecha_inicial" name="fecha_ingreso"  />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="fecha_ingreso">Fecha final</label>
                                        <input type="date" class="form-control required"  id="fecha_final" name="fecha_ingreso"  />
                                    </div>
                                </div>
<input type="hidden" value="<?php echo $id_sucursal; ?>" name="id_sucursal" id="id_sucursal" />
                                <div class="col-md-12">
                                                <div class="form-group">
                                         
                                                <button onclick="Filtrar_por_Fechas()" class="btn btn-primary">Buscar por fechas</button>  
                                                </div>
                                              
                                               

                            </div>
        </div>

        <div class="row">
                     
                       

                     <div class="col-md-12">
                                     <div class="form-group">
                              
                                     <button id="exportPDF" class="btn btn-warning">Exportar a PDF</button>

                                     <button id="exportExcel" class="btn btn-info">Exportar a CSV</button>
                                     <button id="exportExceltabla" class="btn btn-success">Exportar a EXCELL</button>


                                     </div>
                                   
                                                                

                 </div>
</div>
        <div class="row">
            <div class="col-md-12">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Lista ventas</h3>
                    <div class="box-tools">

                            <div class="input-group">
                              <input type="text" name="searchText"   class="form-control input-sm pull-right" style="width: 150px;" placeholder="por cliente/Nro orden" id="searchText" oninput="filterTable()" />
                              <div class="input-group-btn">
                                <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover"  id="miTabla">
                    <tr>
                        <th>Nro venta</th>
                        <th>Cliente</th>
                        <th>Sub total</th>
                        <th>Impuesto</th>
                        <th>Descuento</th>
                        <th>Total</th>
                  
                      
                    </tr>
                    <?php
                    if(!empty($records))
                    {
                        //$id_venta=19;
                        foreach($records as $record)
                        {
                    ?>
                    <tr>
                        <td><?php echo $record->id_venta ?></td>
                        <td><?php echo $record->nombre_cliente ?></td>
                        <td><?php echo $record->base_imponible ?></td>
                        <td><?php echo $record->impuesto ?></td>
                        <td><?php echo $record->descuento ?></td>
                        <td><?php echo $record->total ?></td>
                    
                       
                    </tr>
                    <?php
                        }
                    }
                    ?>
                  </table>
                  
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                <div id="paginacion">
                    <button id="anterior" class="btn btn-primary">Anterior</button>
                    <button id="siguiente" class="btn btn-primary">Siguiente</button>
                </div>
                </div>
              </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const filasPorPagina = 10; // Número de filas por página
  let paginaActual = 1; // Página actual

  const tabla = document.getElementById("miTabla").getElementsByTagName('tbody')[0];
  const filas = tabla.getElementsByTagName("tr");
  const paginacion = document.getElementById("paginacion");
  const btnAnterior = document.getElementById("anterior");
  const btnSiguiente = document.getElementById("siguiente");

  function mostrarPagina(pagina) {
    const inicio = (pagina - 1) * filasPorPagina;
    const fin = inicio + filasPorPagina;

    for (let i = 0; i < filas.length; i++) {
      if (i >= inicio && i < fin) {
        filas[i].style.display = "table-row";
      } else {
        filas[i].style.display = "none";
      }
    }
  }

  function actualizarBotones() {
    btnAnterior.disabled = paginaActual === 1;
    btnSiguiente.disabled = paginaActual === Math.ceil(filas.length / filasPorPagina);

    // Crear los números de página
    paginacion.innerHTML = "";
    for (let i = 1; i <= Math.ceil(filas.length / filasPorPagina); i++) {
      const numeroPagina = document.createElement("button");
      numeroPagina.textContent = i;
      numeroPagina.addEventListener("click", function () {
        paginaActual = i;
        mostrarPagina(paginaActual);
        actualizarBotones();
      });
      if (i === paginaActual) {
        numeroPagina.classList.add("btn", "btn-primary"); // Agregar clases de Bootstrap para resaltar la página actual
      }
      paginacion.appendChild(numeroPagina);
    }
  }

  btnAnterior.addEventListener("click", () => {
    if (paginaActual > 1) {
      paginaActual--;
      mostrarPagina(paginaActual);
      actualizarBotones();
    }
  });

  btnSiguiente.addEventListener("click", () => {
    if (paginaActual < Math.ceil(filas.length / filasPorPagina)) {
      paginaActual++;
      mostrarPagina(paginaActual);
      actualizarBotones();
    }
  });

  // Mostrar la primera página al cargar la página
  mostrarPagina(paginaActual);
  actualizarBotones();
});


</script>


<script>
    function filterTable() {
        let searchText = document.getElementById('searchText').value;
        let id_sucursal = document.getElementById('id_sucursal').value;

        // Realizar la solicitud AJAX para obtener los resultados filtrados
        $.ajax({
            url: '<?php echo base_url() ?>reporte_administrador/filterVenta_fechas',
            type: 'POST',
            data: { searchText: searchText, id_sucursal: id_sucursal },
            success: function (response) {
                // Actualizar el contenido de la tabla con los resultados filtrados
                $('#miTabla').html(response);
                
                // Aplicar estilos de Bootstrap nuevamente
                $('#miTabla').addClass('table');
                $('#miTabla').addClass('table-hover');
            }
        });
    }


    function Filtrar_por_Fechas() {
    let fecha_inicial = document.getElementById('fecha_inicial').value;
    let fecha_final = document.getElementById('fecha_final').value;
    let id_sucursal = document.getElementById('id_sucursal').value;
    
    // Realizar la solicitud AJAX para obtener los resultados filtrados
    $.ajax({
        url: '<?php echo base_url() ?>reporte_administrador/filterVenta_entre_dos_fechas',
        type: 'POST',
        data: { fechaInicial: fecha_inicial, fechaFinal: fecha_final, id_sucursal: id_sucursal }, // Enviar ambas fechas
        success: function (response) {
            // Actualizar el contenido de la tabla con los resultados filtrados
            $('#miTabla').html(response);
            
            // Aplicar estilos de Bootstrap nuevamente
            $('#miTabla').addClass('table');
            $('#miTabla').addClass('table-hover');
        }
    });
}
</script>




<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>



<script>
 


 document.getElementById('exportPDF').addEventListener('click', function() {
    // Obtener la tabla por su ID
    var table = document.getElementById('miTabla');
    
    // Obtener todas las filas de la tabla
    var rows = table.getElementsByTagName('tr');
    
    // Crear un arreglo para almacenar solo las filas con datos
    var data = [];

    // Agregar los títulos de las columnas como una fila de encabezado
    var headerRow = [];
    var headerCells = table.getElementsByTagName('th');
    for (var i = 0; i < headerCells.length; i++) {
        headerRow.push(headerCells[i].textContent);
    }
    data.push(headerRow);

    // Recorrer las filas de la tabla y agregar los datos al arreglo 'data'
    for (var i = 1; i < rows.length; i++) {
        var rowData = [];
        var cells = rows[i].getElementsByTagName('td');
        for (var j = 0; j < cells.length; j++) {
            rowData.push(cells[j].textContent);
        }
        data.push(rowData);
    }

    // Ahora, 'data' contiene las filas de datos y una fila de encabezado
    
    // Luego, puedes utilizar 'data' para generar el PDF
    var docDefinition = {
        content: [
            {
                table: {
                    headerRows: 1,
                    body: data
                }
            }
        ]
    };
    
    // Generar el PDF
    pdfMake.createPdf(docDefinition).open();
});


</script>

<script>

document.getElementById('exportExcel').addEventListener('click', function() {
    // Obtener la tabla por su ID
    var table = document.getElementById('miTabla');
    
    // Obtener todas las filas de la tabla
    var rows = table.getElementsByTagName('tr');
    
    // Crear un arreglo para almacenar los datos de la tabla
    var data = [];

    // Recorrer las filas de la tabla y agregar los datos al arreglo 'data'
    for (var i = 1; i < rows.length; i++) {
        var rowData = [];
        var cells = rows[i].getElementsByTagName('td');
        for (var j = 0; j < cells.length; j++) {
            rowData.push(cells[j].textContent);
        }
        data.push(rowData);
    }

    // Crear un archivo CSV a partir de los datos
    var csvContent = "data:text/csv;charset=utf-8,";
    data.forEach(function(row) {
        var rowString = row.join(",");
        csvContent += rowString + "\r\n";
    });

    // Crear un enlace de descarga y establecer los atributos para descargar el archivo CSV
    var encodedUri = encodeURI(csvContent);
    var link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "tabla.csv");

    // Simular un clic en el enlace para iniciar la descarga del archivo CSV
    link.click();
});
</script>






<script>

document.getElementById('exportExceltabla').addEventListener('click', function() {
    // Crear una matriz de datos para almacenar los datos de la tabla

    // Obtén la tabla por su ID
// Obtenga la tabla por su ID
// Obtenga la tabla por su ID
var table = document.getElementById('miTabla');

// Inicialice una variable para almacenar los datos del archivo CSV
var csvData = [];

// Obtenga las filas de la tabla
var rows = table.getElementsByTagName('tr');

// Recorre las filas y agrega los datos al archivo CSV
for (var i = 0; i < rows.length; i++) {
    var rowData = [];
    var cells = rows[i].getElementsByTagName('td');
    for (var j = 0; j < cells.length; j++) {
        rowData.push(cells[j].textContent);
    }
    // Combina los datos sin comas para el formato CSV
    var row = rowData.join('\t');
    csvData.push(row);
}

// Combina todas las filas en un solo texto CSV
var csvText = csvData.join('\n');

// Crea un enlace de descarga para el archivo CSV
var blob = new Blob([csvText], { type: 'text/xls' });
var link = document.createElement('a');
link.href = window.URL.createObjectURL(blob);
link.download = 'tabla.xls';

// Simula un clic en el enlace para iniciar la descarga
link.click();


});

</script>