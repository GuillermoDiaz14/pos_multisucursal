






<style>
        /* Estilos para el ticket */
  
        h1 {
            font-size: 16px;
            text-align: center;
        }
        p {
            font-size: 12px;
            margin: 0;
        }
        .ticket-item {
            margin-bottom: 5px;
        }
        
  
    /* Estilos para la impresión */
    @media print {
        body {
            font-family: Arial, sans-serif;
            width: 200px; /* Ancho del ticket */
            margin: 0 auto;
        }
        /* Agrega aquí los estilos específicos para la impresión */
    }

        /* Estilos para los detalles en columnas */
        .detalle-list {
        list-style: none;
        padding: 0;
    }
    .detalle-list li {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }
    .detalle-column {
        flex: 1;
        text-align: left;
        
    }
    /* Estilos normales del botón */
#printButton {
    /* Estilos del botón */
}

/* Estilos para ocultar el botón de impresión al imprimir */
@media print {
    #imprimirTicket {
        display: none;
    }
}


/* Estilos para los detalles en columnas */
.detalle-list {
    list-style: none;
    padding: 0;
}

.detalle-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    font-weight: bold; /* Para resaltar los títulos */
}

.detalle-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.detalle-column {
    flex: 1;
    text-align: left;
}

/* Ajusta el espacio entre las columnas según sea necesario */
@media print {
    body {
        font-size: 8pt; /* Reducir el tamaño de letra */
        line-height: 1; /* Reducir el espaciado entre líneas */
        margin: 0.15in; /* Ajustar los márgenes como desees */

    
    }
}
@media print {
    .detalle-column {
        /* Añadir margen derecho para separar las columnas al imprimir */
        margin-right: 5px; /* Ajusta el valor según tu preferencia */
    }
}


</style>

 

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> VENTAS
        <small>TCIKET VENTA</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">VENTAS</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <?php foreach ($ventas as $venta): ?>
    <?php
         $id_venta= $venta->id_venta; 
          $fecha_venta= $venta->fecha_venta; 
          $nombre_cliente= $venta->nombre_cliente;
          $total= $venta->total; 
               ?>
        <?php endforeach; ?>
    <h1>Ticket de Venta</h1>
    <hr>
    <div class="ticket-item">
    <p>ID de Venta: <?php echo $id_venta; ?></p>
    </div>
    <div class="ticket-item">
    <p>Fecha: <?php echo $fecha_venta; ?></p>
    </div>
    <div class="ticket-item">
    <p>cliente: <?php echo $nombre_cliente; ?></p>
    </div>
    <div class="ticket-item">
    <p>total venta: <?php echo $total; ?></p>
    </div>
    <!-- Imprime los detalles de la venta -->
    <div class="ticket-item">
    <h2>Detalles de la Venta:</h2>
    <ul class="detalle-list">
        <li class="detalle-header">
            <div class="detalle-column">Producto</div>
            <div class="detalle-column">Precio</div>
            <div class="detalle-column">Cantidad</div>
            <div class="detalle-column">Subtotal</div>
        </li>
        <?php foreach ($detalles as $detalle): ?>
            <li class="detalle-row">
            <td><?php echo $record->id_venta ?></td>
                        <td><?php echo $record->nombre_cliente ?></td>
                        <td><?php echo $record->base_imponible ?></td>
                        <td><?php echo $record->impuesto ?></td>
                        <td><?php echo $record->descuento ?></td>
                        <td><?php echo $record->total ?></td>
            </li>
        <?php endforeach; ?>
    </ul>
</div>


    <button id="imprimirTicket">Imprimir Ticket</button>

                </div>
            </div>
            <div class="col-md-4">
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
    </section>
</div>

<script>
document.getElementById('imprimirTicket').addEventListener('click', function () {
    window.print(); // Abre el cuadro de diálogo de impresión
});
</script>