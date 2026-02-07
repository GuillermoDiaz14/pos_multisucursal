<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Importar empleado
        <small>Importar</small>
      </h1>
    </section>
    <?php

                        $imagen_url = base_url('uploads/' ."csv_empleado_ventas.programacionparacompartir.csv");
                        ?>  
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"> Importar empleado</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form method="post" enctype="multipart/form-data" action="<?php echo base_url('empleado/importar_empleado'); ?>">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-7">                                
                                    <div class="form-group">
                                        <label for="archivo">Importar csv</label>
                                        <input type="file" class="form-control required" name="archivo" required />
 
                                    </div>
                                    
                                </div>
                           
                                                    

                                
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                           <label for="archivo">Descargar ejemplo CSV</label><br>
                                        <a href="<?php echo $imagen_url; ?>" class="btn btn-sm btn-info" title="Descargar" download>Descargar <i class="fa fa-download"></i></a>

 
                                    </div>
                                    
                                </div>


                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Importar" />
                    
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