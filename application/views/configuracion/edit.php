<?php
$id_configuracion = $configuracionInfo->id_configuracion;
$nombre_empresa = $configuracionInfo->nombre_empresa;
$telefono = $configuracionInfo->telefono;
$impuesto = $configuracionInfo->impuesto;
$simbolo_moneda= $configuracionInfo->simbolo_moneda;
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> configuracion
        <small>configuracion / Editar configuracion</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Editar configuracion</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>configuracion/editconfiguracion" method="post" id="editconfiguracion" role="form">
                        <div class="box-body">
                        <div class="row">
                          
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="monto">Empresa</label>
                                        <input type="text" class="form-control required" value="<?php echo $nombre_empresa; ?>" id="nombre_empresa" name="nombre_empresa" maxlength="256"  />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha">Telefono</label>
                                        <input type="text" class="form-control required" value="<?php echo $telefono; ?>" id="telefono" name="telefono" maxlength="256" />
                                    </div>
                                </div>
                    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha">Impuesto</label>
  
                                        <input type="text" class="form-control required" value="<?php echo $impuesto; ?>" id="impuesto" name="impuesto" maxlength="256" inputmode="numeric" pattern="[0-9]+(\.[0-9]+)?" />

                                    </div>
                                </div>

                                     <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha">Simbolo moneda</label>
                                     <input type="text" class="form-control required" value="<?php echo $simbolo_moneda; ?>" id="simbolo_moneda" name="simbolo_moneda" maxlength="256" oninput="this.value = this.value.replace(/[0-9]/g, '')" />

                                    </div>
                                </div>
                                
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Editar" />
                            
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