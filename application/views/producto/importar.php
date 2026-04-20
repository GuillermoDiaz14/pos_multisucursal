<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-upload" aria-hidden="true"></i> Importar Productos
        <small>Carga masiva desde archivo CSV</small>
      </h1>
    </section>
    
    <section class="content">
        <div class="row">
            <!-- Left column - Main form -->
            <div class="col-md-8">
                <!-- Instructions Box -->
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-info-circle"></i> Instrucciones de Importación</h3>
                    </div>
                    <div class="box-body">
                        <ol>
                            <li>Descargue la plantilla CSV haciendo clic en el botón <strong>"Descargar Plantilla"</strong></li>
                            <li>Abra la plantilla en Excel o cualquier editor de CSV</li>
                            <li>Complete los datos de sus productos (vea los campos requeridos abajo)</li>
                            <li>Guarde el archivo como CSV</li>
                            <li>Seleccione el archivo y haga clic en <strong>"Importar"</strong></li>
                        </ol>
                    </div>
                </div>

                <!-- Upload Form -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-upload"></i> Seleccionar Archivo</h3>
                    </div>
                    <div class="box-body">
                        <?php $this->load->helper("form"); ?>
                        <form method="post" enctype="multipart/form-data" action="<?php echo base_url('producto/importar_producto'); ?>" id="import-form" accept-charset="UTF-8">
                            <div class="form-group">
                                <label for="archivo">Archivo CSV <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="archivo" id="archivo" accept=".csv" required />
                                <small class="form-text text-muted">Solo se aceptan archivos CSV. Tamaño máximo: 2 MB</small>
                            </div>

                            <div class="form-group">
                                <a href="<?php echo base_url('producto/descargar_plantilla'); ?>" class="btn btn-info btn-lg">
                                    <i class="fa fa-download"></i> Descargar Plantilla CSV
                                </a>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fa fa-upload"></i> Importar Productos
                                </button>
                                <button type="button" class="btn btn-default btn-lg" onclick="limpiarArchivo()">
                                    <i class="fa fa-times"></i> Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right column - Messages -->
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    
                    // Error messages
                    $error = $this->session->flashdata('error');
                    if($error) {
                ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-ban"></i> Error en la importación</h4>
                        <?php echo $error; ?>
                    </div>
                <?php 
                    }

                    // Success messages
                    $success = $this->session->flashdata('success');
                    if($success) {
                ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> Importación exitosa</h4>
                        <?php echo $success; ?>
                    </div>
                <?php 
                    }

                    // Validation errors
                    if(validation_errors()) {
                ?>
                    <div class="alert alert-warning alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-warning"></i> Advertencias</h4>
                        <?php echo validation_errors(); ?>
                    </div>
                <?php 
                    }
                ?>

                <!-- Tips Box -->
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-lightbulb-o"></i> Consejos</h3>
                    </div>
                    <div class="box-body">
                        <ul style="font-size: 13px;">
                            <li>Asegúrese de que los separadores del CSV sean <strong>comas (,)</strong></li>
                            <li>No incluya encabezados adicionales, solo use los especificados</li>
                            <li>Verifique que los precios usen punto (.) como separador decimal</li>
                            <li>El ID de categoría debe existir en el sistema</li>
                            <li>Los campos opcionales pueden dejarse vacíos</li>
                            <li>Los acentos y caracteres especiales se soportan correctamente</li>
                            <li><strong style="color: red;">⚠️ Los códigos de barras deben ser únicos</strong> - No se permitirá importar un producto con código que ya existe</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>    
    </section>

    <script type="text/javascript">
        function limpiarArchivo() {
            document.getElementById('archivo').value = '';
            document.getElementById('archivo').focus();
        }
    </script>
    
</div>