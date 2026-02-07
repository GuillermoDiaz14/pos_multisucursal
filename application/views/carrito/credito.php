
              <?php foreach ($ventas as $venta): ?>
    <?php

                    $saldo= $venta->saldo;
          $total= $venta->total;
$id_venta= $venta->id_venta;

               ?>
        <?php endforeach; 

$deuda=$total-$saldo;
        ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Credito
        <small>Credito</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Credito</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>carrito/cuota_agregar" method="post" id="credito" >
                        <div class="box-body">
                        <div class="row">
                 
                              <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="monto">Total pago</label>
                                        <input type="text" class="form-control required" value="<?php echo $total; ?>" id="totalPago" name="totalPago" maxlength="256" inputmode="numeric" pattern="[0-9]+(\.[0-9]+)?"  readonly>
                                         <input type="hidden" value="<?php echo $id_venta; ?>" name="id_venta" id="id_venta" />
                                    </div>
                                </div>
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="monto">Deuda total</label>
                                        <input type="text" class="form-control required" value="<?php echo $deuda; ?>" id="totaldeuda" name="totaldeuda"  maxlength="256" inputmode="numeric" pattern="[0-9]+(\.[0-9]+)?"  readonly>
                                     
                                    </div>
                                </div>
                          <div class="col-md-12">
                                                <div class="form-group">
                                                <label for="totalPago">Cuota:</label>
                                              <input type="text" name="cuota" id="cuota" placeholder="cuota" oninput="calcularDeuda()" value="0" step="any">

                                                     
                                                </div>
                    
                                
                            </div>
                                 <div class="col-md-12">
                                        <div class="form-group">
                                                <label for="deuda">Deuda:</label>
                                                <input type="text" id="deuda" readonly>
                                                     
                                        </div>
                                </div>
                        </div><!-- /.box-body -->
      <p id="error-message" style="color: red;"></p>
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Registrar" />
                
                        </div>
                    </form>



           <div class="box-body table-responsive no-padding">
                  <table class="table table-hover"  id="miTabla">
                    <tr>
                        <th>Id</th>
                           <th>Fecha pago</th>
                        <th>cuota</th>
                      

                      
                    </tr>
                    <?php
                    if(!empty($cuotas))
                    {
                        //$id_venta=19;
                        foreach($cuotas as $cuota)
                        {
                    ?>
                    <tr>
                        <td><?php echo $cuota->id_cuota ?></td>
                        <td><?php echo $cuota->fecha_pago ?></td>
                        <td><?php echo $cuota->cuota ?></td>
                       
                
                
                    </tr>
                    <?php
                        }
                    }
                    ?>
                  </table>
                  
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


<script>
    //estre es el script para controlar el total pago, adelanto y deuda
        function calcularDeuda() {
            // Obtener los valores de los campos de entrada
            var totaldeuda = parseFloat(document.getElementById("totaldeuda").value) || 0;
            var cuota = parseFloat(document.getElementById("cuota").value) || 0;

            // Calcular la deuda
            var deuda = totaldeuda - cuota;

            // Mostrar la deuda en el campo de entrada correspondiente
            document.getElementById("deuda").value = deuda.toFixed(2);


        }
    </script>

         <script>
        // Obtenemos el saldo de PHP y lo pasamos a JavaScript
        const deuda = <?php echo $deuda; ?>;
//const deuda = document.getElementById("deuda");
        // Obtenemos referencias a los elementos del formulario
        const cantidadInput = document.getElementById("cuota");
        const errorMessage = document.getElementById("error-message");

        // Agregamos un controlador de eventos "input" al campo de entrada para validar en tiempo real
        cantidadInput.addEventListener("input", () => {
            const valorIngresado = parseFloat(cantidadInput.value);
            if (valorIngresado > deuda) {
                errorMessage.textContent = "El valor ingresado es mayor , ingreso  algo menor " + deuda + ".";

                     // Obtén el botón por su ID o cualquier otro método de selección
        var boton = document.getElementById("credito").querySelector("input[type=submit]");

        // Desactiva el botón
        boton.setAttribute("disabled", true);
            } else {
                errorMessage.textContent = "";

            }
       // Restablecer automáticamente el valor si excede el saldo
            if (valorIngresado > deuda) {
                cantidadInput.value = deuda;
            }
    if (valorIngresado <= deuda) {
             var boton = document.getElementById("credito").querySelector("input[type=submit]");
               boton.removeAttribute("disabled");
            }
        });


    
    </script>