<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Agregar sucursal
        <small>sucursal</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Datos sucursal</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addsucursal" action="<?php echo base_url() ?>sucursal/addNewsucursal" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control required" name="nombre_sucursal" id="nombre_sucursal" />
                                    </div>
                                    
                                </div>
                            <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="roomName">Celular</label>
                                        <input type="text" class="form-control required" name="celular" id="celular" />
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="direccion">Direccion</label>
                                        <input type="text" class="form-control required" name="direccion" id="direccion" />
                                    </div>
                                    
                                </div>

                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="direccion">Ciudad</label>
                                        <input type="text" class="form-control required" name="ciudad" id="ciudad" />
                                    </div>
                                    
                                </div>

                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="Impuesto">Impuesto</label>
                                        <input type="text" class="form-control required" name="impuesto" id="impuesto" pattern="[0-9]+(\.[0-9]+)?" title="Ingrese un número entero o decimal"/>
                                    </div>
                                </div>



                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="direccion">Simbolo Moneda</label>
                                        <input type="text" class="form-control required" name="simbolo_moneda" id="simbolo_moneda" />
                                    </div>
                                    
                                </div>

                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control required" name="correo" id="correo" />
                                    </div>
                                    
                                </div>
                            
                              
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Agregar" />
                            <input type="reset" class="btn btn-default" value="Vaciar" />
                        </div>
                    </form>
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