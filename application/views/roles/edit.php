<?php
$roleId = $roleInfo->roleId;
$role = $roleInfo->role;
$status = $roleInfo->status;
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Administrar Roles
        <small>Agregar / Editar Roles</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Detalles Roles</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>roles/editRole" method="post" id="editRole" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="role">Nombre rol</label>
                                        <input type="text" class="form-control required" value="<?php echo $role; ?>" id="role" name="role" maxlength="50" required />
                                        <input type="hidden" value="<?php echo $roleId; ?>" name="roleId" id="roleId" />
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                        <label for="role">Estado</label>
                                        <select class="form-control required" id="status" name="status" required>
                                            <option value="">Seleccione Estado</option>
                                            <option value="<?= ACTIVE ?>" <?php if($status == ACTIVE) {echo "selected=selected";} ?>>Activo</option>
                                            <option value="<?= INACTIVE ?>" <?php if($status == INACTIVE) {echo "selected=selected";} ?>>Inactivo</option>
                                        </select>
                                    </div>
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

        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Matriz de acceso a roles</h3>
                    <div class="box-tools">
                    </div>
                </div><!-- /.box-header -->
                <form method="POST" action='<?php echo base_url() ?>roles/storeAccessMatrix'>
                <input type="hidden" value="<?php echo $roleId; ?>" name="roleIdForMatrix" id="roleIdForMatrix" />
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>Modules</th>
                            <th>Permiso</th>
                       
                        </tr>
                        <?php
                        if(!empty($moduleList))
                        {
                            foreach($moduleList as $record)
                            {
                                $key = array_search($record['module'], array_column($roleAccessMatrix, 'module'));
                                $matrix = (array) $roleAccessMatrix[$key];
                        ?>
                        <tr>
                            <td><b><?php echo $record['module'] ?></b> <input type="hidden" name="access[<?= $record['module'] ?>][module]" value="<?php echo $record['module'] ?>"  /> </td>
                            <td><input type='checkbox' name='access[<?= $record['module'] ?>][total_access]' <?= ($matrix['total_access'] == 1) ? 'checked':''; ?> /></td>
 
                        </tr>
                        <?php
                            }
                        }
                        ?>
                    </table>
                
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <input type="submit" class="btn btn-primary" value="Guardar" />
                </div>
                </form>
              </div><!-- /.box -->
            </div>
        </div>

    </section>
</div>
<script src="<?php echo base_url(); ?>assets/js/addRole.js" type="text/javascript"></script>