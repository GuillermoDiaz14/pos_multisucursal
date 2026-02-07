<?php
$userId = $userInfo->userId;
$name = $userInfo->name;
$email = $userInfo->email;
$mobile = $userInfo->mobile;
$roleId = $userInfo->roleId;
$isAdmin = $userInfo->isAdmin;
?>
<?php

$id_sucursal = $userInfo->id_sucursal;

?>
<?php foreach ($sucursal['sucursal'] as $suc): ?>
   <?php 
         if($suc->id_sucursal==$id_sucursal)
            {
           $nombre_sucursal=$suc->nombre_sucursal;
            }
   
    ?>

 

            <?php endforeach; ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i>Administrar Usuarios 
        <small>Agregar / Editar Usuario</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"> Uuario Detalles</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>editUser" method="post" id="editUser" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Nombre completo</label>
                                        <input type="text" class="form-control" id="fname" placeholder="Nombre completo" name="fname" value="<?php echo $name; ?>" maxlength="128">
                                        <input type="hidden" value="<?php echo $userId; ?>" name="userId" id="userId" />    
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Direccion Email</label>
                                        <input type="email" class="form-control" id="email" placeholder="Direccion Email" name="email" value="<?php echo $email; ?>" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" placeholder="Password" name="password" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpassword">Confirmar Password</label>
                                        <input type="password" class="form-control" id="cpassword" placeholder="Confirmar Password" name="cpassword" maxlength="20">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Celular</label>
                                        <input type="text" class="form-control" id="mobile" placeholder="Celular" name="mobile" value="<?php echo $mobile; ?>" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Rol</label>
                                        <select class="form-control" id="role" name="role">
                                            <option value="0">Seleccione Rol</option>
                                            <?php
                                            if(!empty($roles))
                                            {
                                                foreach ($roles as $rl)
                                                {
                                                    $roleText = $rl->role;
                                                    $roleClass = false;
                                                    if ($rl->roleStatus == INACTIVE) {
                                                        $roleText = $rl->role . ' (Inactive)';
                                                        $roleClass = true;
                                                    }
                                                    ?>
                                                    <option value="<?php echo $rl->roleId; ?>" <?php if ($roleClass) { echo "class=text-warning"; } ?>  <?php if($rl->roleId == $roleId) { echo "selected=selected";} ?>><?= $roleText ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="isAdmin">Tipo usuario</label>
                                        <select class="form-control required" id="isAdmin" name="isAdmin">
                                            <option value="<?= REGULAR_USER ?>" <?php if($isAdmin == REGULAR_USER) {echo "selected=selected";} ?>>Usuario Regular</option>
                                            <option value="<?= SYSTEM_ADMIN ?>" <?php if($isAdmin == SYSTEM_ADMIN) {echo "selected=selected";} ?>>Administrator Sistema</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div> 




                                   <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label ¿>Sucursal</label>
                             


                                                        <select class="form-control required" id="id_sucursal" name="id_sucursal" >

                      <?php foreach ($sucursal['sucursal'] as $metodo): ?>
                                     <option value="<?php echo $metodo->id_sucursal;?>" <?php if($id_sucursal==$metodo->id_sucursal) {echo "selected"; }?>><?php echo $metodo->nombre_sucursal; ?></option>
                                            <?php endforeach; ?>
              </select>  
                                    </div>
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Actualizar" />
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

<script src="<?php echo base_url(); ?>assets/js/editUser.js" type="text/javascript"></script>