



<!-- Acceder a los datos de tbl_configuracion -->
<?php foreach ($configuracion['configuracion'] as $config): ?>
   <?php 
    
   $impuesto= $config->impuesto;
    ?>

 
<?php endforeach; ?>


            <?php

//cajaabierta
?>

?>
       <?php foreach ($cajaabierta as $caja): 
         $saldo=$caja->saldo;
        ?>
       
            <?php endforeach; ?>


            <?php

//cajaabierta
?>
<style>
    #lista_productos {
    max-height: 200px; /* Establece la altura máxima que deseas */
    overflow-y: auto; /* Hace que se muestre una barra de desplazamiento vertical si es necesario */
}
.productos-seleccionados-table {
    width: 100%;
    border-collapse: collapse;
}

.productos-seleccionados-table th, .productos-seleccionados-table td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: left;
}

.productos-seleccionados-table th {
    background-color: #f0f0f0;
}









.custom-select {
    position: relative;
}

.search-input {
    width: 100%;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.cliente-list {
    list-style: none;
    padding: 0;
    margin: 0;
    position: absolute;
    width: 100%;
    max-height: 150px; /* Altura máxima de la lista desplegable */
    overflow-y: auto;
    border: 1px solid #ccc;
    border-radius: 4px;
    display: none; /* La lista está oculta inicialmente */
    z-index: 1; /* Controla la superposición (ajusta según sea necesario) */
    background-color: #C0C0C0; /* Cambia este color al que desees */
}

.cliente-list li {
    padding: 5px;
    cursor: pointer;
}

.cliente-list li:hover {
    background-color: #f2f2f2;
    background-color: #FFE4C4; /* Cambia este color al que desees */
}


/*overflow lista temporañ */
#lista_temporal {
    max-height: 400px; /* Establece la altura máxima que deseas */
    max-width: 800px; /* Establece la altura máxima que deseas */
    overflow-y: auto; /* Hace que se muestre una barra de desplazamiento vertical si es necesario */
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i>Venta
        <small>Agregar / Venta</small>
      </h1>
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  Registro caja
</button>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Título del Modal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
      Saldo Total: <?php echo $saldo; ?>
      <input type="hidden" class="form-control required" value="<?php echo $saldo; ?>" id="saldo" name="saldo" maxlength="256" />
 

      </div>
      <div class="modal-footer">
      
      <button class="btn btn-primary" onclick="cerrarCaja()">Cerrar caja</button>
      </div>

    </div>
  </div>
</div>

    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Agregue productos a su carrito</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
           
                        <div class="box-body">
                        <div class="row">
          
                        
              
<div class="col-md-3">
    <div class="form-group custom-select">
        <label for="id_categoria">Cliente</label>
        <input type="text" class="search-input" id="search_cliente" placeholder="Buscar cliente"  />
        <ul class="cliente-list">
            <?php foreach ($clientes as $cliente): ?>
                <li data-value="<?php echo $cliente->id_cliente; ?>"><?php echo $cliente->nombre; ?></li>
            <?php endforeach; ?>
        </ul>
        <input type="hidden" id="id_cliente" name="id_cliente" required  />
        <input type="hidden" id="imp" name="imp" value="<?php echo $impuesto; ?>"  />
    </div>
</div>  
                    
<div class="col-md-9">
    <div class="form-group">
        <label for="producto_busqueda">Buscar Producto por nombre o codigo</label>
        <input type="text" class="form-control" id="producto_busqueda" placeholder="Buscar producto por nombre o codigo" oninput="buscarProductos(this.value)">
        <div id="lista_productos" class="lista-productos mt-3">
            <ul class="list-group">
                <?php foreach ($productos as $key => $producto): ?>
                    <?php
                    $nombreProducto = strtolower($producto->nombre_producto);
                    $codigoProducto = strtolower($producto->codigo);
                    $imagenProducto = empty($producto->imagen) ? '11carrito22.png' : $producto->imagen;
                    ?>
                    <li class="list-group-item" id="producto_<?php echo $key; ?>">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <img src="<?php echo base_url('uploads/' . $imagenProducto); ?>" alt="<?php echo $nombreProducto; ?>" class="img-thumbnail mr-2" style="max-width: 50px;">
                         
                                <span class="nombre-producto"><?php echo $nombreProducto; ?></span>
                                <span class="codigo-producto"><?php echo $codigoProducto; ?></span>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm" onclick="seleccionarProducto(<?php echo $producto->id_producto; ?>, '<?php echo $nombreProducto; ?>', <?php echo $producto->precio_venta; ?>)">Seleccionar</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>





                                        <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipo_pago">Tipo pago</label>
                                        <select class="form-control required" id="tipo_pago" name="tipo_pago"  onchange="bloquearMetodoPago()">
                                            <option value="contado">Al contado</option>
                                            <option value="credito">Al credito</option>
                                        </select>
                                    </div>
                                </div> 

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="metodo">Metodo pago</label>
                                        <select class="form-control required" id="id_metodo_pago" name="id_metodo_pago" >
                         
                                            <?php foreach ($configuracion['metodo_pago'] as $metodo): ?>
                                                <option value="<?php echo $metodo->id_metodo_pago; ?>"><?php echo $metodo->nombre_metodo_pago; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div> 

                            
                            <div class="col-md-10" >
                                <div id="lista_temporal">

                                                <div class="form-group">
                                                <!-- Lista de productos seleccionados -->
                                                
                                                <ul id="productos_seleccionados" class="productos-seleccionados"></ul>

                                                <div id="descuento_total_section">
                                                    <label for="descuento_total">Descuento Total:</label>
                                                    <input type="number" id="descuento_total" value="0" oninput="calcularsubTotalconDescuento()" inputmode="numeric" pattern="[0-9]+(\.[0-9]+)?"  require>
                                                </div>
                                                <div id="descuento_total_section">
                                                    <label for="base_imponible">Base Imponible:</label>
                                                    <input type="number" id="base_imponible" value="0" inputmode="numeric" pattern="[0-9]+(\.[0-9]+)?"  readonly>
                                                </div>

                                                <div id="descuento_total_section">
                                                    <label for="Impuesto">Impuesto:</label>
                                                    <input type="number" id="impuesto" value="0" inputmode="numeric" pattern="[0-9]+(\.[0-9]+)?"  readonly>
                                                </div>
                                                <!-- Sección para mostrar el subtotal -->
                                                        <div id="subtotal_section">
                                                            <label for="subtotal">Total:</label>
                                                            <input type="text" id="subtotal" readonly>
                                                        </div>
                                                </div>
                                                <button class="btn btn-primary" onclick="enviarProductos()">Registrar venta</button>

                                                
                                </div>
                            </div>
                                
                            </div>
                        </div><!-- /.box-body -->
    
                   
                   
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    
    function buscarProductos(termino) {
    var listaProductos = document.querySelectorAll('.lista-productos .list-group-item');
    var terminoBusqueda = termino.toLowerCase();

    listaProductos.forEach(function(producto) {
        var nombreProducto = producto.querySelector('.nombre-producto').innerText.toLowerCase();
        var codigoProducto = producto.querySelector('.codigo-producto').innerText.toLowerCase();
        
        if (nombreProducto.includes(terminoBusqueda) || codigoProducto.includes(terminoBusqueda)) {
            producto.style.display = 'block';
        } else {
            producto.style.display = 'none';
        }
    });
}




const productosExistentes = [];

var encabezadoAgregado = false;

function seleccionarProducto(idProducto, nombreProducto, precioVenta) {
    var productosSeleccionados = document.getElementById('productos_seleccionados');

    if (!encabezadoAgregado) {
        // Agregar encabezado solo si no se ha agregado
        var table = document.createElement('table');
        table.className = 'productos-seleccionados-table';

        var tr = document.createElement('tr');

        var thNombreProducto = document.createElement('th');
        thNombreProducto.textContent = 'Producto';
        tr.appendChild(thNombreProducto);

        var thPrecioVenta = document.createElement('th');
        thPrecioVenta.textContent = 'Precio de venta';
        tr.appendChild(thPrecioVenta);

        var thCantidad = document.createElement('th');
        thCantidad.textContent = 'Cantidad';
        tr.appendChild(thCantidad);

        var thSubtotal = document.createElement('th');
        thSubtotal.textContent = 'Subtotal';
        tr.appendChild(thSubtotal);

        var thEliminar = document.createElement('th');
        thEliminar.textContent = 'Eliminar';
        tr.appendChild(thEliminar);

        table.appendChild(tr);

        productosSeleccionados.appendChild(table);
        encabezadoAgregado = true;
       
    }
 var id_cliente= document.getElementById('id_cliente').value;
  
    var table = document.querySelector('.productos-seleccionados-table');
    if (!productosExistentes.includes(idProducto)) {
    var trProducto = document.createElement('tr');
    trProducto.id = `producto_${idProducto}`;
    trProducto.innerHTML = `
        <td>${nombreProducto}</td>
        <td>${precioVenta}</td>
        <td><input type="number" id="cantidad_${idProducto}" value="1" oninput="calcularSubtotal(${idProducto}, ${precioVenta})"></td>
        <td id="subtotal_${idProducto}">${precioVenta}</td>
        <td><button class="btn btn-primary btn-sm" onclick="eliminarProducto(${idProducto},${precioVenta})">Eliminar</button></td>
         <td style="display: none;">${idProducto}</td>
         <td style="display: none;">${id_cliente}</td>
    `;

    table.appendChild(trProducto);
 
  // recalcularSubtotalTotal();
//   calcularsubTotalconDescuento();
   calcularSubtotal(idProducto, precioVenta);
   agregarProducto(idProducto);
}
}
function agregarProducto(idProducto) {
  // Comprobar si el producto ya está en la lista antes de agregarlo
  if (!productosExistentes.includes(idProducto)) {
    productosExistentes.push(idProducto);
    // Aquí debes agregar el código para agregar la fila a la tabla HTML
  }
}

function eliminarProducto(idProducto,precioVenta) {
    eliminarP(idProducto,precioVenta); 
    eliminarP(idProducto,precioVenta); 
    eliminarProductoexistenteAreglo(idProducto);
}
function eliminarP(idProducto,precioVenta) {
    var fila = document.getElementById(`producto_${idProducto}`);
    if(fila!=null){
    console.log('Respuesta del servidor:', fila)
    fila.parentNode.removeChild(fila);
    recalcularSubtotalTotal();
    calcularsubTotalconDescuento();
    actualizar_base_imponible_igv();
    
}
}


function calcularSubtotal(idProducto, precioVenta) {
    var cantidad = document.getElementById(`cantidad_${idProducto}`).value;
    var subtotal = cantidad * precioVenta;
    document.getElementById(`subtotal_${idProducto}`).textContent = `${subtotal}`;
    actualizarSubtotalTotal();
  
    //recalcularSubtotalTotal();
    calcularsubTotalconDescuento();
    actualizar_base_imponible_igv();
}

function recalcularSubtotalTotal() {
    var subtotalTotal = 0;

    // Iterar a través de los elementos de productos_seleccionados y sumar los subtotales
    var productosSeleccionados = document.querySelectorAll('.productos-seleccionados-table tr');
    for (var i = 1; i < productosSeleccionados.length; i++) {
        var subtotalElement = productosSeleccionados[i].querySelector('td:nth-child(4)');
        subtotalTotal += parseFloat(subtotalElement.textContent);
    }
    var subtotalTotalInput = document.getElementById('subtotal');
    subtotalTotalInput.value = subtotalTotal.toFixed(2); // Asegurarse de que tenga dos decimales
    // Actualizar el campo de entrada "subtotal" total

    actualizar_base_imponible_igv();




}
function actualizar_base_imponible_igv() {
    var subtotalTotalInput = document.getElementById('subtotal');


    var subtotal = parseFloat(subtotalTotalInput.value) || 0;
    var imp = parseFloat(document.getElementById('imp').value) || 0; // Obtener el valor de 'imp' como número
    imp=(imp+100)/100;
    var base_imponible = subtotal  /  imp; // Calcular la base imponible
var impuesto = subtotal - base_imponible; // Calcular el impuesto


// Asignar los valores calculados a los campos de entrada
document.getElementById('impuesto').value = impuesto.toFixed(2);
document.getElementById('base_imponible').value = base_imponible.toFixed(2);


}
function actualizarSubtotalTotal() {
    var subtotal = 0;
    var listaSubtotales = document.querySelectorAll('[id^=subtotal_]');
    listaSubtotales.forEach(function (elemento) {
        subtotal += parseFloat(elemento.textContent.replace('Subtotal: ', ''));
    });
    document.getElementById('subtotal').value = subtotal;
}



/*function probardatos() {
    var filas = document.querySelectorAll('.productos-seleccionados-table tr');

    for (var i = 1; i < filas.length; i++) {
        var fila = filas[i];
        var celdas = fila.getElementsByTagName('td');

        var precioTotal = parseFloat(celdas[2].textContent); // Contenido de la tercera celda (Precio Total)
        var precioUnitario = parseFloat(celdas[0].textContent); // Contenido de la primera celda (Precio Unitario)

        if (!isNaN(precioTotal) && !isNaN(precioUnitario) && precioUnitario !== 0) {
            // Realiza la operación de división para calcular la cantidad
            var cantidad = precioTotal / precioUnitario;

            // Crea una nueva celda llamada "Cantidad" y muestra el resultado
            var nuevaCeldaCantidad = document.createElement('td');
            nuevaCeldaCantidad.textContent = cantidad.toFixed(2); // Ajusta el resultado a 2 decimales
            fila.appendChild(nuevaCeldaCantidad);
        }
    }

    // Imprime los datos en la consola después de realizar los cálculos
    var datosImpresos = [];
    filas.forEach(function(fila) {
        var celdas = fila.getElementsByTagName('td');
        var datosFila = [];
        for (var j = 0; j < celdas.length - 1; j++) {
            datosFila.push(celdas[j].textContent);
        }
        datosImpresos.push(datosFila);
    });
    console.log(datosImpresos);
}

*/

function cerrarCaja() {
    // Obtiene el valor del campo id_proveedor
    var datosProducto = [];
    var saldo = document.getElementById("saldo").value;

    // Verifica si id_proveedor está vacío
    if (saldo.trim() === "") {
        // Muestra un mensaje de error si el proveedor no está seleccionado
        alert("Error saldo caja vacia.");
    } else {
    
        

    const datosFila = {
    saldo,
  };

  datosProducto.push(datosFila);
  
   
  
  

            // Realizar la solicitud AJAX para enviar los datos al controlador
            $.ajax({
                url: '<?php echo base_url() ?>Caja/cerrarCaja',
                type: 'POST',
                data: { productos: datosProducto },
                success: function (data) {
                    console.log('Respuesta del servidor:', data);

                    window.location.href = '<?php echo base_url() ?>carrito/carrito'; 
                 
            
                },
                error: function (error) {
                    console.error('Error al enviar los datos:', error);
                }
            });

            eliminarProductosSeleccionados();
        }
    
}



function enviarProductos() {
    // Obtiene el valor del campo id_cliente

    var idCliente = document.getElementById("id_cliente").value;

    // Verifica si id_cliente está vacío
    if (idCliente.trim() === "") {
        // Muestra un mensaje de error si el cliente no está seleccionado
        alert("Falta seleccionar cliente. Por favor, ingrese un cliente antes de enviar los productos.");
    } else {
        // Obtiene todas las filas de productos seleccionados
        var filas = document.querySelectorAll('.productos-seleccionados-table tr');
        
        // Verifica si no hay productos seleccionados
        if (filas.length === 0) {
            alert("Falta seleccionar productos. Por favor, seleccione al menos un producto antes de enviar.");
        } else {
            var productosSeleccionados = [];


            for (var i = 1; i < filas.length; i++) {
                var fila = filas[i];
                var celdas = fila.getElementsByTagName('td');

                var datosProducto = [];

                for (var j = 0; j < celdas.length; j++) {
                    var contenido = celdas[j].textContent;
                    datosProducto.push(contenido);
                }
         // Obtener los valores de los campos "subtotal" y "descuento_total"
         var subtotalInput = parseFloat(document.getElementById('subtotal').value) || 0;
            var descuentoInput = parseFloat(document.getElementById('descuento_total').value) || 0;
 
            // Agregar los valores de los campos directamente a la matriz
            datosProducto.push(subtotalInput);
            datosProducto.push(descuentoInput);
            var idCliente = parseInt(document.getElementById('id_cliente').value) || 0;

// Agregar el valor a la matriz datosProducto
datosProducto.push(idCliente);
var tipo_pago = document.getElementById('tipo_pago').value;
var id_metodo_pago = document.getElementById('id_metodo_pago').value;
var impuesto = parseFloat(document.getElementById('impuesto').value) || 0;
            var base_imponible = parseFloat(document.getElementById('base_imponible').value) || 0;
            datosProducto.push(impuesto);
            datosProducto.push(base_imponible);
            datosProducto.push(tipo_pago);
            datosProducto.push(id_metodo_pago);
                productosSeleccionados.push(datosProducto);
            }

            // Realizar la solicitud AJAX para enviar los datos al controlador
            $.ajax({
                url: '<?php echo base_url() ?>Carrito/addNewVenta',
                type: 'POST',
                data: { productos: productosSeleccionados },
                success: function (data) {
                    console.log('Respuesta del servidor:', data);

                    window.location.href = '<?php echo base_url() ?>carrito/ventas_lista';
                 
            
                },
                error: function (error) {
                    console.error('Error al enviar los datos:', error);
                }
            });

            eliminarProductosSeleccionados();
        }
    }
}







function eliminarProductosSeleccionados() {
  var listaProductos = document.getElementById("productos_seleccionados");
  while (listaProductos.firstChild) {
    listaProductos.removeChild(listaProductos.firstChild);
  }
}

function calcularsubTotalconDescuento() {
    recalcularSubtotalTotal();
    var subtotalInput = document.getElementById('subtotal');
    var descuentoInput = document.getElementById('descuento_total');
    var subtotal = parseFloat(subtotalInput.value) || 0; // Obtener el valor del subtotal
    var descuentoTotal = parseFloat(descuentoInput.value) || 0; // Obtener el valor del descuento total

    // Validar que el valor no sea negativo
    if (descuentoTotal < 0) {
        descuentoInput.value = "0"; // Establecer el valor en cero si es negativo
    }
    if (descuentoTotal > 0) {
     // Calcular el subtotal restando el descuento total
     var subtotalConDescuento = subtotal - descuentoTotal;

// Actualizar el campo de entrada "subtotal"
subtotalInput.value = subtotalConDescuento.toFixed(2);
actualizar_base_imponible_igv();
    }

  

}

</script>


<script>
function bloquearMetodoPago() {
    var tipoPagoSelect = document.getElementById("tipo_pago");
    var metodoPagoSelect = document.getElementById("id_metodo_pago");
    
    if (tipoPagoSelect.value === "credito") {
        metodoPagoSelect.value = "0";
        metodoPagoSelect.disabled = true;
    } else {
        metodoPagoSelect.disabled = false;
    }
}


function eliminarProductoexistenteAreglo(idProducto) {
  const index = productosExistentes.indexOf(idProducto);
  if (index !== -1) {
    productosExistentes.splice(index, 1);
  }
  // Aquí debes agregar el código para eliminar la fila de la tabla HTML
}
</script>

<script>
    $(document).ready(function() {
        $('#search_cliente').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            
            $('.cliente-list li').each(function() {
                var itemText = $(this).text().toLowerCase();
                
                if (itemText.indexOf(searchText) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#search_cliente').on('focus', function() {
            $('.cliente-list').show(); // Mostrar la lista cuando el campo de búsqueda está enfocado
        });

        $('.cliente-list li').on('click', function() {
            var selectedValue = $(this).attr('data-value');
            var selectedText = $(this).text();

            $('#id_cliente').val(selectedValue);
            $('#search_cliente').val(selectedText);
            $('.cliente-list').hide(); // Ocultar la lista después de seleccionar un elemento
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('.custom-select').length) {
                $('.cliente-list').hide(); // Ocultar la lista si se hace clic fuera del campo de búsqueda o la lista
            }
        });
    });
</script>



