<?php
$id_proveedor = $proveedorInfo->id_proveedor;
$nombre = $proveedorInfo->nombre;
$email = $proveedorInfo->email;
$celular = $proveedorInfo->celular;
$direccion = $proveedorInfo->direccion;
$doc_fiscal = $proveedorInfo->doc_fiscal;

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Proveedor
        <small>Proveedor / Editar Proveedor</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Editar Proveedor</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>proveedor/editproveedor" method="post" id="editproveedor" role="form">
                        <div class="box-body">
                        <div class="row">
                                <div class="col-md-6">        
                                    
                                <div class="form-group">
                                        <label for="description">Nombre</label>
                                     <input type="text" class="form-control required" value="<?php echo $nombre; ?>" id="nombre" name="nombre" maxlength="256"  />
                                        <input type="hidden" value="<?php echo $id_proveedor; ?>" name="id_proveedor" id="id_proveedor" />
                                    </div>


                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control required" value="<?php echo $email; ?>" id="email" name="email" maxlength="256"  />
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
                                        <label for="doc_fiscal">Doc fiscal</label>
                                        <input type="text" class="form-control required" value="<?php echo $doc_fiscal; ?>" id="doc_fiscal" name="doc_fiscal" maxlength="256" />
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