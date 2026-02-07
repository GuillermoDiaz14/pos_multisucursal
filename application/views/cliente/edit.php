<?php
$id_cliente = $clienteInfo->id_cliente;
$nombre = $clienteInfo->nombre;
$correo = $clienteInfo->correo;
$doc_identidad = $clienteInfo->doc_identidad;
$celular = $clienteInfo->celular;

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> cliente
        <small>cliente / Editar cliente</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Editar cliente</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>cliente/editCliente" method="post" id="editcliente" role="form">
                        <div class="box-body">
                        <div class="row">
                                <div class="col-md-6">        
                                    
                                <div class="form-group">
                                        <label for="description">nombre</label>
                                        <textarea class="form-control required" id="nombre" name="nombre"><?php echo $nombre; ?></textarea>
                                        <input type="hidden" value="<?php echo $id_cliente; ?>" name="id_cliente" id="id_cliente" />
                                    </div>


                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="monto">correo</label>
                                        <input type="email" class="form-control required" value="<?php echo $correo; ?>" id="correo" name="correo"  />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha">doc. identidad</label>
                                        <input type="text" class="form-control required" value="<?php echo $doc_identidad; ?>" id="doc_identidad" name="doc_identidad" />
                                    </div>
                                </div>
                    
                        <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha">Nro celular</label>
                                        <input type="text" class="form-control required" value="<?php echo $celular; ?>" id="celular" name="celular" />
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