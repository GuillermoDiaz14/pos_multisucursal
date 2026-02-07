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

.proveedor-list {
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
    /*background-color: yellow; /* Cambia este color al que desees */
    z-index: 1; /* Controla la superposición (ajusta según sea necesario) */
    background-color: #C0C0C0; /* Cambia este color al que desees */
}

.proveedor-list li {
    padding: 5px;
    cursor: pointer;
}

.proveedor-list li:hover {
    background-color: #FFE4C4; /* Cambia este color al que desees */
  
}
.proveedor-list li.selected {
    background-color: red; /* Cambia este color al que desees */
  /*  color: black; /* Cambia este color al que desees */
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
        <i class="fa fa-user-circle-o" aria-hidden="true"></i>Compra
        <small>Agregar / Compra</small>
      </h1>



    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Agregue productos a su compra</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
           
                        <div class="box-body">
                        <div class="row">
                        
                        
              
<div class="col-md-3">
    <div class="form-group custom-select">
        <label for="id_categoria">Proveedor</label>
        <input type="text" class="search-input" id="search_proveedor" placeholder="Buscar proveedor"  />
        <ul class="proveedor-list">
            <?php foreach ($proveedores as $proveedor): ?>
                <li data-value="<?php echo $proveedor->id_proveedor; ?>"><?php echo $proveedor->nombre; ?></li>
            <?php endforeach; ?>
        </ul>
        <input type="hidden" id="id_proveedor" name="id_proveedor" required  />
    </div>
</div>  
<div class="col-md-6">
<div class="form-group">
        <label for="id_nota">Nota</label>
        <textarea class="form-control required" id="nota" name="nota"></textarea>

    </div>
</div>  

<div class="col-md-12">
<div class="form-group">


    </div>
</div>  
<div class="col-md-12">
<div class="form-group">


    </div>
</div>    
<div class="col-md-12">
<div class="form-group">


    </div>
</div>              
<div class="col-md-9">
    <div class="form-group">
        <label for="producto_busqueda">Buscar Producto</label>
        <input type="text" class="form-control" id="producto_busqueda" placeholder="Buscar producto" oninput="buscarProductos(this.value)">
       
        <div id="lista_productos" class="lista-productos mt-3">
            <ul class="list-group">
                <?php foreach ($productos as $key => $producto): ?>
                    <?php
                    $nombreProducto = strtolower($producto->nombre_producto);
                    $imagenProducto = empty($producto->imagen) ? '11carrito22.png' : $producto->imagen;
                    ?>
                    <li class="list-group-item" id="producto_<?php echo $key; ?>">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <img src="<?php echo base_url('uploads/' . $imagenProducto); ?>" alt="<?php echo $nombreProducto; ?>" class="img-thumbnail mr-2" style="max-width: 50px;">
                                <span><?php echo $nombreProducto; ?></span>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm" onclick="seleccionarProducto(<?php echo $producto->id_producto; ?>, '<?php echo $nombreProducto; ?>', <?php echo $producto->precio_venta; ?>)">Seleccionar</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>


                            
                            <div class="col-md-10" >
                                <div id="lista_temporal">

                                                <div class="form-group">
                                                <!-- Lista de productos seleccionados -->
                                                
                                                <ul id="productos_seleccionados" class="productos-seleccionados"></ul>




                                     
                                                <!-- Sección para mostrar el subtotal -->
                                                        <div id="subtotal_section">
                                                            <label for="subtotal">Total:</label>
                                                            <input type="text" id="subtotal" readonly>
                                                        </div>
                                                </div>
                                                <button class="btn btn-primary" onclick="enviarProductos()">Registrar compra</button>
                                          
                                                
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
        var nombreProducto = producto.innerText.toLowerCase();
        if (nombreProducto.includes(terminoBusqueda)) {
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
        thPrecioVenta.textContent = 'Precio de compra';
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
  //  var   idp=idProducto;
 var id_proveedor= document.getElementById('id_proveedor').value;
  
    var table = document.querySelector('.productos-seleccionados-table');
 // Comprobar si ya existe un elemento con el mismo idProducto
// var elementoExistente = document.getElementById(`producto_${idProducto}`);


if (!productosExistentes.includes(idProducto)) {
   
 


    var trProducto = document.createElement('tr');
    trProducto.id = `producto_${idProducto}`;
    trProducto.innerHTML = `
        <td>${nombreProducto}</td>

        <td><input type="number" id="precio_compra_${idProducto}" value="0" oninput="calcularSubtotalPrecioCompra(${idProducto})"></td>
        <td><input type="number" id="cantidad_${idProducto}" value="0" oninput="calcularSubtotal(${idProducto})"></td>
        <td id="subtotal_${idProducto}">0</td>
        <td><button class="btn btn-primary btn-sm" onclick="eliminarProducto(${idProducto})">Eliminar</button></td>
         <td style="display: none;">${idProducto}</td>
         <td style="display: none;">${id_proveedor}</td>
    `;

    table.appendChild(trProducto);
 
   recalcularSubtotalTotal();
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

function eliminarProducto(idProducto) {
    var precio_compra = document.getElementById(`precio_compra_${idProducto}`).value;
    eliminarP(idProducto,precio_compra); 
 
    eliminarP(idProducto,precio_compra); 
    eliminarProductoexistenteAreglo(idProducto);
}
function eliminarP(idProducto) {
    var fila = document.getElementById(`producto_${idProducto}`);
    if(fila!=null){
    console.log('Respuesta del servidor:', fila)
    fila.parentNode.removeChild(fila);
    recalcularSubtotalTotal();
}
    
}
// Eliminar un producto de la lista
function eliminarProductoexistenteAreglo(idProducto) {
  const index = productosExistentes.indexOf(idProducto);
  if (index !== -1) {
    productosExistentes.splice(index, 1);
  }
  // Aquí debes agregar el código para eliminar la fila de la tabla HTML
}

function calcularSubtotalPrecioCompra(idProducto) {
    var cantidad = document.getElementById(`cantidad_${idProducto}`).value;
    var precio_compra = document.getElementById(`precio_compra_${idProducto}`).value;
    var subtotal = cantidad * precio_compra;
    document.getElementById(`subtotal_${idProducto}`).textContent = `${subtotal}`;
    actualizarSubtotalTotal();
  
    recalcularSubtotalTotal();

}
function calcularSubtotal(idProducto) {
    var cantidad = document.getElementById(`cantidad_${idProducto}`).value;
    var precio_compra = document.getElementById(`precio_compra_${idProducto}`).value;
    var subtotal = cantidad * precio_compra;
    document.getElementById(`subtotal_${idProducto}`).textContent = `${subtotal}`;
    actualizarSubtotalTotal();
  
    recalcularSubtotalTotal();

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

  




}

function actualizarSubtotalTotal() {
    var subtotal = 0;
    var listaSubtotales = document.querySelectorAll('[id^=subtotal_]');
    listaSubtotales.forEach(function (elemento) {
        subtotal += parseFloat(elemento.textContent.replace('Subtotal: ', ''));
    });
    document.getElementById('subtotal').value = subtotal;
}



function probardatos() {
    var datosProducto = [];
    productosExistentes.forEach((idProducto, index) => {
    // Obtener el precio de compra
    const precioCompraElement = document.getElementById(`precio_compra_${idProducto}`);
    const precioCompra = precioCompraElement ? precioCompraElement.value : 'No se encontró precio de compra';
  
    // Obtener la cantidad
    const cantidadElement = document.getElementById(`cantidad_${idProducto}`);
    const cantidad = cantidadElement ? cantidadElement.value : 'No se encontró cantidad';
    // Calcular el subtotal
    const subtotal = precioCompra * cantidad;
    if (subtotal >0) {
        var idproveedor = parseInt(document.getElementById('id_proveedor').value) || 0;
      //  const idproveedor = document.getElementById('id_proveedor').value;

// Agregar el valor a la matriz datosProducto

    const datosFila = {
    idProducto,
    precioCompra,
    cantidad,
    subtotal,
    idproveedor,
  };

  datosProducto.push(datosFila);
  
}   
  
  });

    // Imprime los datos en la consola después de realizar los cálculos
 
    console.log(datosProducto); // Puedes mostrarlo en la consola para verificar
}




function enviarProductos() {
    // Obtiene el valor del campo id_proveedor
    var datosProducto = [];
    var idproveedor = document.getElementById("id_proveedor").value;

    // Verifica si id_proveedor está vacío
    if (idproveedor.trim() === "") {
        // Muestra un mensaje de error si el proveedor no está seleccionado
        alert("Falta seleccionar proveedor. Por favor, ingrese un proveedor antes de enviar los productos.");
    } else {
        // Obtiene todas las filas de productos seleccionados
        var filas = document.querySelectorAll('.productos-seleccionados-table tr');
        
        // Verifica si no hay productos seleccionados
        if (filas.length === 0) {
            alert("Falta seleccionar productos. Por favor, seleccione al menos un producto antes de enviar.");
        } else {
            var productosSeleccionados = [];


            productosExistentes.forEach((idProducto, index) => {
    // Obtener el precio de compra
    const precioCompraElement = document.getElementById(`precio_compra_${idProducto}`);
    const precioCompra = precioCompraElement ? precioCompraElement.value : 'No se encontró precio de compra';
  
    // Obtener la cantidad
    const cantidadElement = document.getElementById(`cantidad_${idProducto}`);
    const cantidad = cantidadElement ? cantidadElement.value : 'No se encontró cantidad';
    // Calcular el subtotal
    const subtotal = precioCompra * cantidad;
    if (subtotal >0) {
        var idproveedor = parseInt(document.getElementById('id_proveedor').value) || 0;
      //  const total = document.getElementById('id_proveedor').value;
          const nota = document.getElementById('nota').value;
          const total = document.getElementById('subtotal').value;
// Agregar el valor a la matriz datosProducto

    const datosFila = {
    idProducto,
    precioCompra,
    cantidad,
    subtotal,
    idproveedor,
    nota,
    total,
  };

  datosProducto.push(datosFila);
  
}   
  
  });

            // Realizar la solicitud AJAX para enviar los datos al controlador
            $.ajax({
                url: '<?php echo base_url() ?>Entrada/addNewCompra',
                type: 'POST',
                data: { productos: datosProducto },
                success: function (data) {
                    console.log('Respuesta del servidor:', data);

                    window.location.href = '<?php echo base_url() ?>entrada/entradas_lista'; 
                 
            
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
    $(document).ready(function() {
        $('#search_proveedor').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            
            $('.proveedor-list li').each(function() {
                var itemText = $(this).text().toLowerCase();
                
                if (itemText.indexOf(searchText) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#search_proveedor').on('focus', function() {
            $('.proveedor-list').show(); // Mostrar la lista cuando el campo de búsqueda está enfocado
        });

        $('.proveedor-list li').on('click', function() {
            var selectedValue = $(this).attr('data-value');
            var selectedText = $(this).text();

            $('#id_proveedor').val(selectedValue);
            $('#search_proveedor').val(selectedText);
            $('.proveedor-list').hide(); // Ocultar la lista después de seleccionar un elemento
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('.custom-select').length) {
                $('.proveedor-list').hide(); // Ocultar la lista si se hace clic fuera del campo de búsqueda o la lista
            }
        });
    });
</script>