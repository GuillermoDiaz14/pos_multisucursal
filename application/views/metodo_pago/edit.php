<?php
$id_metodo_pago = $metodo_pagoInfo->id_metodo_pago;
$nombre_metodo_pago = $metodo_pagoInfo->nombre_metodo_pago;


?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> metodo_pago
        <small>Metodo pago / Editar Metodo pago</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Editar metodo pago</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>metodo_pago/editMetodo_pago" method="post" id="editmetodo_pago" role="form">
                        <div class="box-body">
                        <div class="row">
                            
                           
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha">Metodo pago</label>
                                        <input type="text" class="form-control required" value="<?php echo $nombre_metodo_pago; ?>" id="nombre_metodo_pago" name="nombre_metodo_pago" maxlength="256" />
                                        <input type="hidden" value="<?php echo $id_metodo_pago; ?>" name="id_metodo_pago" id="id_metodo_pago" />
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