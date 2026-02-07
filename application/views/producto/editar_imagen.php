<?php
$id_producto = $productoInfo->id_producto;
$nombre_producto = $productoInfo->nombre_producto;
$imagen = $productoInfo->imagen;
  $imagen_url = base_url('uploads/' . $productoInfo->imagen);
?>

            

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Producto
        <small>Producto / Editar Imagen Producto</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Editar Producto</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>producto/editProductoImagen" method="post" id="editProductoImagen" role="form" enctype="multipart/form-data">
                        <div class="box-body">
                        <div class="row">




                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="monto">Producto</label>
                                        <input type="text" class="form-control required" value="<?php echo $nombre_producto; ?>" id="nombre_producto" name="nombre_producto" maxlength="256" readonly />
                                                <input type="hidden" value="<?php echo $id_producto; ?>" name="id_producto" id="id_producto" />
                                    </div>
                                </div>


          <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="imagen"> Seleccione Nueva Imagen</label>
                                           <input type="file" class="form-control required" id="imagen" name="imagen" accept="image/*" required />

                                    </div>
                                    
                                </div>

        
          <div class="col-md-6">                                
                                    <div class="form-group">
   <img src="<?php echo $imagen_url; ?>" alt="Imagen" width="200">

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


