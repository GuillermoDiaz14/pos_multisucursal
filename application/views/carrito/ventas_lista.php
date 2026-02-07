
<style>

.pagina-actual {
    background-color: #007bff;
    color: white;
}

</style>
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
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>carrito/carrito"><i class="fa fa-plus"></i> Agregar nueva venta</a>
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
                        <form action="<?php echo base_url() ?>carrito/ventas_lista" method="POST" id="searchList">
                            <div class="input-group">
                              <input type="text" name="searchText"   class="form-control input-sm pull-right" style="width: 150px;" placeholder="por cliente/Nro venta" id="searchText" oninput="filterTable()" />
                              <div class="input-group-btn">
                                <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </form>
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
                  
                        <th class="text-center">Acciones</th>
                    </tr>
                    <?php
                    if(!empty($records))
                    {
                        //$id_venta=19;
                        foreach($records as $record)
                        {
                            $tipo_pago=$record->tipo_pago;
                           // contado
                    ?>
                    <tr>
                     <td><?php echo $record->id_venta ?></td>
                        <td><?php echo $record->nombre_cliente ?></td>
                        <td><?php echo $record->base_imponible ?></td>
                        <td><?php echo $record->impuesto ?></td>
                        <td><?php echo $record->descuento ?></td>
                        <td><?php echo $record->total ?></td>
    
        <td class="text-center">

<?php
if ($tipo_pago=="contado") {
?> 
    <!-- BOTÓN EDITAR (comentado por decisión del negocio)
    <a class="btn btn-sm btn-warning" href="<?php echo base_url().'carrito/carrito_editar/'.$record->id_venta; ?>" title="Edit">
        <i class="fa fa-pencil"></i>
    </a>
    -->

    <a class="btn btn-sm btn-danger" href="<?php echo base_url('carrito/eliminar_venta/' . $record->id_venta); ?>">
        <i class="fa fa-trash"></i>
    </a>
<?php
}
?> 

<?php
if ($tipo_pago=="credito") {
?> 
    <!-- BOTÓN EDITAR CRÉDITO (comentado por decisión del negocio)
    <a class="btn btn-sm btn-primary" href="<?php echo base_url().'carrito/credito/'.$record->id_venta; ?>" title="Edit">
        Credito<i class="fa fa-pencil"></i>
    </a>
    -->

    <a class="btn btn-sm btn-primary" href="<?php echo base_url().'carrito/credito/'.$record->id_venta; ?>">
        Credito
    </a>
<?php
}
?> 

<a class="btn btn-sm btn-info" href="<?php echo base_url().'carrito/exportToPDF/'.$record->id_venta; ?>" title="ticket" target="_blank">
    <i class="fa fa-file-text-o"></i>
</a>

</td>


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

        // Realizar la solicitud AJAX para obtener los resultados filtrados
        $.ajax({
            url: '<?php echo base_url() ?>carrito/filterVentas',
            type: 'POST',
            data: { searchText: searchText },
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
