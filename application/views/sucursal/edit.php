<?php
$id_sucursal = $sucursalInfo->id_sucursal;
$nombre_sucursal = $sucursalInfo->nombre_sucursal;
$impuesto = $sucursalInfo->impuesto;
$celular = $sucursalInfo->celular;
$direccion = $sucursalInfo->direccion;
$ciudad = $sucursalInfo->ciudad;
$correo = $sucursalInfo->correo;
$simbolo_moneda = $sucursalInfo->simbolo_moneda;
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> sucursal
        <small>sucursal / Editar sucursal</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Editar sucursal</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>sucursal/editsucursal" method="post" id="editsucursal" role="form">
                        <div class="box-body">
                        <div class="row">
                                <div class="col-md-6">        
                                    
                                <div class="form-group">
                                        <label for="nombre_sucursal">Nombre</label>
                                     <input type="text" class="form-control required" value="<?php echo $nombre_sucursal; ?>" id="nombre_sucursal" name="nombre_sucursal" maxlength="256"  />
                                        <input type="hidden" value="<?php echo $id_sucursal; ?>" name="id_sucursal" id="id_sucursal" />
                                    </div>


                                    
                                </div>
                              <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="impuesto">Impuesto</label>
                                        <input type="text" class="form-control required" value="<?php echo $impuesto; ?>" id="impuesto" name="impuesto" maxlength="256" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="celular">Celular</label>
                                        <input type="text" class="form-control required" value="<?php echo $celular; ?>" id="celular" name="celular" maxlength="256" />
                                    </div>
                                </div>
                    
                      <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="direccion">Direccion</label>
                                        <input type="text" class="form-control required" value="<?php echo $direccion; ?>" id="direccion" name="direccion" maxlength="256" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ciudad">Ciudad</label>
                                        <input type="text" class="form-control required" value="<?php echo $ciudad; ?>" id="ciudad" name="ciudad" maxlength="256" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ciudad">Simbolo Moneda</label>
                                        <input type="text" class="form-control required" value="<?php echo $simbolo_moneda; ?>" id="simbolo_moneda" name="simbolo_moneda" maxlength="256" />
                                    </div>
                                </div>
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="correo">Email</label>
                                        <input type="email" class="form-control required" value="<?php echo $correo; ?>" id="correo" name="correo" maxlength="256"  />
                                    </div>
                                </div>
                    
                                
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Editar" />
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