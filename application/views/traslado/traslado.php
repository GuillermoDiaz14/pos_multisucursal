<!-- Acceder a los datos de tbl_configuracion -->
<?php foreach ($configuracion['configuracion'] as $config): ?>
   <?php 
    
   $impuesto= $config->impuesto;
    ?>

 
<?php endforeach; ?>


            <?php


?>




            <?php


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

.sucursal-list {
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

.sucursal-list li {
    padding: 5px;
    cursor: pointer;
}

.sucursal-list li:hover {
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
        <i class="fa fa-user-circle-o" aria-hidden="true"></i>TRASLADAR
        <small>Agregar / TRASLADO</small>
      </h1>



    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Agregue productos</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
           
                        <div class="box-body">
                        <div class="row">
          
                        
              
<div class="col-md-3">
    <div class="form-group custom-select">
        <label for="id_categoria">Sucursal</label>
        <input type="text" class="search-input" id="search_sucursal" placeholder="Buscar sucursal"  />
        <ul class="sucursal-list">
            <?php foreach ($sucursales as $sucursal): ?>
                <li data-value="<?php echo $sucursal->id_sucursal; ?>"><?php echo $sucursal->nombre_sucursal; ?></li>
            <?php endforeach; ?>
        </ul>
        <input type="hidden" id="id_sucursal" name="id_sucursal" required  />
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
                            <a href="#" class="btn btn-primary btn-sm" onclick="seleccionarProducto(<?php echo $producto->id_producto; ?>, '<?php echo $nombreProducto; ?>',<?php echo $producto->stock; ?>)">Seleccionar</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>






                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="roomName">comentario</label>
                                        <input type="text" class="form-control required"  id="comentario" name="comentario" maxlength="256"  />
                                    </div>
                                    
                                </div>

                          

                            
                            <div class="col-md-10" >
                                <div id="lista_temporal">

                                                <div class="form-group">
                                                <!-- Lista de productos seleccionados -->
                                                
                                                <ul id="productos_seleccionados" class="productos-seleccionados"></ul>

                                          
                                           

                                             
                                           
                                                </div>
                                         
                                                <button class="btn btn-primary" onclick="enviarProductos()">Registrar trasladar</button>

                                                
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

function seleccionarProducto(idProducto, nombreProducto,stock) {
    var productosSeleccionados = document.getElementById('productos_seleccionados');

    if (!encabezadoAgregado) {
        // Agregar encabezado solo si no se ha agregado
        var table = document.createElement('table');
        table.className = 'productos-seleccionados-table';

        var tr = document.createElement('tr');

        var thNombreProducto = document.createElement('th');
        thNombreProducto.textContent = 'Producto';
        tr.appendChild(thNombreProducto);


        var thCantidad = document.createElement('th');
        thCantidad.textContent = 'Cantidad';
        tr.appendChild(thCantidad);

       
        var thEliminar = document.createElement('th');
        thEliminar.textContent = 'Eliminar';
        tr.appendChild(thEliminar);

        table.appendChild(tr);

        productosSeleccionados.appendChild(table);
        encabezadoAgregado = true;
       
    }
 var id_sucursal= document.getElementById('id_sucursal').value;
  
    var table = document.querySelector('.productos-seleccionados-table');
    if (!productosExistentes.includes(idProducto)) {
    var trProducto = document.createElement('tr');
    trProducto.id = `producto_${idProducto}`;
    trProducto.innerHTML = `
        <td>${nombreProducto}</td>
   
        <td><input type="number"  id="cantidad_${idProducto}" value="1"  oninput="calcularCantidad(${idProducto},${stock})" required></td>
       <td style="display: none;" id="subcantidad_${idProducto}"></td>
        <td><button class="btn btn-primary btn-sm" onclick="eliminarProducto(${idProducto})">Eliminar</button></td>
         <td style="display: none;">${idProducto}</td>
      
    `;

    table.appendChild(trProducto);
 

    calcularCantidad(idProducto,stock);
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



 function calcularCantidad(idProducto,stock) {
    // Obtener el valor del input
    var inputCantidad = document.getElementById(`cantidad_${idProducto}`);
    var valorInput = inputCantidad.value;

    // Verificar si la cantidad es mayor a 2
    if (valorInput > stock) {
        // Establecer la cantidad máxima permitida (en este caso, 2)
        inputCantidad.value = stock;
        valorInput = stock; // Actualizar la variable 'valorInput' con el nuevo valor
    }

    // Asignar el valor al td con el id "subcantidad_${idProducto}"
    var tdSubcantidad = document.getElementById(`subcantidad_${idProducto}`);
    tdSubcantidad.textContent = valorInput;

    // También puedes usar innerHTML si deseas agregar HTML al td
    // tdSubcantidad.innerHTML = valorInput;
}












function actualizarSubtotalTotal() {
    var subtotal = 0;
    var listaSubtotales = document.querySelectorAll('[id^=subtotal_]');
    listaSubtotales.forEach(function (elemento) {
        subtotal = parseFloat(elemento.textContent.replace('Subtotal: ', ''));
    });
    document.getElementById('subtotal').value = subtotal;
}
function GetCantidad(idProducto) {
    var cantidad = document.getElementById(`cantidad_${idProducto}`).value;
    var subcantidad = cantidad;
   // document.getElementById(`subcantidad_${idProducto}`).textContent = `${subcantidad}`;

return   subcantidad;
  
}
function eliminarProducto(idProducto) {
    eliminarP(idProducto); 
    eliminarP(idProducto); 
    eliminarProductoexistenteAreglo(idProducto);
}
function eliminarP(idProducto) {
    var fila = document.getElementById(`producto_${idProducto}`);
    if(fila!=null){
    console.log('Respuesta del servidor:', fila)
    fila.parentNode.removeChild(fila);

    
}
}












function probardatos() {
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






function enviarProductos() {
    // Obtiene el valor del campo id_sucursal

    var idsucursal = document.getElementById("id_sucursal").value;

    // Verifica si id_sucursal está vacío
    if (idsucursal.trim() === "") {
        // Muestra un mensaje de error si el sucursal no está seleccionado
        alert("Falta seleccionar sucursal. Por favor, ingrese un sucursal antes de enviar los productos.");
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

 

            var idsucursal = parseInt(document.getElementById('id_sucursal').value) || 0;

// Agregar el valor a la matriz datosProducto
datosProducto.push(idsucursal);
var comentario = document.getElementById('comentario').value;

            datosProducto.push(comentario);

                productosSeleccionados.push(datosProducto);
            }

            // Realizar la solicitud AJAX para enviar los datos al controlador
            $.ajax({
                url: '<?php echo base_url() ?>Trasladar/addNewTrasladar',
                type: 'POST',
                data: { productos: productosSeleccionados },
                success: function (data) {
                    console.log('Respuesta del servidor:', data);

               window.location.href = '<?php echo base_url() ?>trasladar/trasladar_lista';                           
            
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



</script>


<script>



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
        $('#search_sucursal').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            
            $('.sucursal-list li').each(function() {
                var itemText = $(this).text().toLowerCase();
                
                if (itemText.indexOf(searchText) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#search_sucursal').on('focus', function() {
            $('.sucursal-list').show(); // Mostrar la lista cuando el campo de búsqueda está enfocado
        });

        $('.sucursal-list li').on('click', function() {
            var selectedValue = $(this).attr('data-value');
            var selectedText = $(this).text();

            $('#id_sucursal').val(selectedValue);
            $('#search_sucursal').val(selectedText);
            $('.sucursal-list').hide(); // Ocultar la lista después de seleccionar un elemento
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('.custom-select').length) {
                $('.sucursal-list').hide(); // Ocultar la lista si se hace clic fuera del campo de búsqueda o la lista
            }
        });
    });
</script>



