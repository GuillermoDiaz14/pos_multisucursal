<?php
$id_gasto = $gastoInfo->id_gasto;
$descripcion = $gastoInfo->descripcion;
$monto = $gastoInfo->monto;
$fecha = $gastoInfo->fecha;

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Gasto
        <small>Gasto / Editar Gasto</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Editar Gasto</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>gasto/editGasto" method="post" id="editGasto" role="form">
                        <div class="box-body">
                        <div class="row">
                                <div class="col-md-6">        
                                    
                                <div class="form-group">
                                        <label for="description">Descripcion</label>
                                        <textarea class="form-control required" id="descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>
                                        <input type="hidden" value="<?php echo $id_gasto; ?>" name="id_gasto" id="id_gasto" />
                                    </div>


                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="monto">Monto</label>
                                        <input type="text" class="form-control required" value="<?php echo $monto; ?>" id="monto" name="monto" maxlength="256" inputmode="numeric" pattern="[0-9]+(\.[0-9]+)?" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha">Fecha</label>
                                        <input type="date" class="form-control required" value="<?php echo $fecha; ?>" id="fecha" name="fecha" maxlength="256" />
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