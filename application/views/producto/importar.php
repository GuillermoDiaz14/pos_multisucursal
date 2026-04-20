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
                            <li>Complete los datos de sus productos:
                                <ul style="margin-top: 5px;">
                                    <li><strong>Nombre:</strong> Nombre del producto (requerido)</li>
                                    <li><strong>Precio Compra:</strong> Costo unitario (requerido)</li>
                                    <li><strong>Precio Venta:</strong> Precio final (requerido)</li>
                                    <li><strong>Código:</strong> <strong style="color: green;">Dejar VACÍO para generar EAN-13 automáticamente</strong> o ingresar código de 13 dígitos válido</li>
                                    <li><strong>ID Categoría:</strong> ID numérico de la categoría (requerido)</li>
                                    <li><strong>Detalles:</strong> Descripción (opcional)</li>
                                    <li><strong>Stock:</strong> Cantidad inicial (opcional, default: 0)</li>
                                    <li><strong>Talla:</strong> Talla del producto (opcional, default: NA)</li>
                                </ul>
                            </li>
                            <li>Guarde el archivo como CSV</li>
                            <li>Seleccione el archivo y haga clic en <strong>"Validar CSV"</strong> para ver preview</li>
                            <li>Si todo está bien, haga clic en <strong>"Importar Productos"</strong></li>
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
                                <small class="form-text text-muted">Solo se aceptan archivos CSV. Tamaño máximo: 5 MB (aprox. 500-1000 productos)</small>
                            </div>
                            <textarea name="csv_procesado" id="csv_procesado" style="display:none;"></textarea>

                            <!-- Preview de validación -->
                            <div id="preview-container" style="display:none;">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> <strong>Vista previa de importación:</strong> Revisa los datos antes de confirmar
                                </div>
                                <table class="table table-bordered table-striped" id="preview-table">
                                    <thead>
                                        <tr>
                                            <th style="width:5%">Línea</th>
                                            <th>Nombre</th>
                                            <th style="width:15%">Código</th>
                                            <th style="width:15%">Acción Código</th>
                                            <th style="width:10%">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody id="preview-body">
                                    </tbody>
                                </table>
                                <div id="preview-summary" class="alert alert-warning"></div>
                            </div>

                            <div class="form-group">
                                <a href="<?php echo base_url('producto/descargar_plantilla'); ?>" class="btn btn-info btn-lg">
                                    <i class="fa fa-download"></i> Descargar Plantilla CSV
                                </a>
                            </div>

                            <div class="box-footer">
                                <button type="button" class="btn btn-warning btn-lg" id="btn-preview" style="display:none;">
                                    <i class="fa fa-eye"></i> Validar CSV
                                </button>
                                <button type="button" class="btn btn-primary btn-lg" id="btn-generar-codigos" style="display:none;" disabled>
                                    <i class="fa fa-barcode"></i> Generar código de barras
                                </button>
                                <button type="button" class="btn btn-info btn-lg" id="btn-descargar-corregido" style="display:none;" disabled>
                                    <i class="fa fa-download"></i> Descargar CSV corregido
                                </button>
                                <button type="submit" class="btn btn-success btn-lg" id="btn-importar" disabled>
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
                        <h3 class="box-title"><i class="fa fa-lightbulb-o"></i> Gestión de Códigos de Barras</h3>
                    </div>
                    <div class="box-body">
                        <p><strong>Cómo funcionan los códigos en el CSV:</strong></p>
                        <ul style="font-size: 13px; margin-bottom: 15px;">
                            <li>
                                <strong style="color: green;">✓ Campo vacío:</strong> El sistema generará un codigo de barras único automáticamente
                            </li>
                            <li>
                                <strong style="color: blue;">✓ 13 dígitos válidos:</strong> Se usa el código que proporciones 
                            </li>
                            <li>
                                <strong style="color: red;">✗ Otro formato:</strong> La importación se rechazará para esa línea
                            </li>
                            <li>
                                <strong style="color: red;">✗ Duplicado:</strong> No se permite el mismo código 
                            </li>
                        </ul>

                        <hr style="margin: 10px 0;">

                        <p><strong>Recomendaciones:</strong></p>
                        <ul style="font-size: 13px;">
                            <li>Antes de Importar haz clic en <strong>"Validar CSV"</strong></li>
                            <li>Verifique que los separadores sean <strong>comas (,)</strong></li>
                            <li>Los precios deben usar punto (.) como separador decimal en caso de aplicar</li>
                            <li>El ID de categoría debe existir en el sistema</li>
                            <li>Los campos opcionales (Detalles, Talla) pueden dejarse vacíos</li>
                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>    
    </section>

    <script type="text/javascript">
        var csvState = {
            separator: ',',
            headers: [],
            rows: [],
            pendingCodes: 0,
            hasGeneratedCodes: false
        };

        $(document).ready(function() {
            // Cuando selecciona archivo, mostrar botón de preview
            $('#archivo').on('change', function() {
                if (this.files.length > 0) {
                    $('#btn-preview').show();
                    $('#btn-generar-codigos').hide().prop('disabled', true);
                    $('#btn-descargar-corregido').hide().prop('disabled', true);
                    $('#btn-importar').prop('disabled', true).addClass('disabled');
                    $('#preview-container').hide();
                    $('#csv_procesado').val('');
                } else {
                    $('#btn-preview').hide();
                }
            });

            // Click en Validar CSV
            $('#btn-preview').on('click', function() {
                if (csvState.hasGeneratedCodes && csvState.rows.length > 0) {
                    revalidarEstadoActual();
                } else {
                    var file = $('#archivo')[0].files[0];
                    if (!file) {
                        alert('Selecciona un archivo CSV primero');
                        return;
                    }

                    validarCSV(file);
                }
            });

            $('#btn-generar-codigos').on('click', function() {
                generarCodigosFaltantes();
            });

            $('#btn-descargar-corregido').on('click', function() {
                descargarCSVCorregido();
            });

            // Prevenir envío directo sin validar
            $('#import-form').on('submit', function(e) {
                if ($('#preview-container').css('display') === 'none') {
                    e.preventDefault();
                    alert('Por favor, valida el CSV primero usando el botón "Validar CSV"');
                    $('#btn-preview').click();
                    return;
                }

                if (csvState.pendingCodes > 0) {
                    e.preventDefault();
                    alert('Aún hay productos sin código. Primero genera los códigos de barras faltantes.');
                    return;
                }
            });
        });

        function escaparHtml(texto) {
            return $('<div>').text(texto || '').html();
        }

        function normalizarCampo(valor) {
            return (valor || '').toString().replace(/\r/g, '').trim();
        }

        function obtenerCodigosActuales() {
            var codigos = [];

            csvState.rows.forEach(function(row) {
                var codigo = normalizarCampo(row[3]);
                if (codigo) {
                    codigos.push(codigo);
                }
            });

            return codigos;
        }

        function construirCSVProcesado() {
            if (!csvState.headers.length) {
                return '';
            }

            var lineas = [];
            lineas.push(csvState.headers.join(csvState.separator));

            csvState.rows.forEach(function(row) {
                var columnas = row.slice();
                while (columnas.length < csvState.headers.length) {
                    columnas.push('');
                }

                var linea = columnas.map(function(valor) {
                    valor = (valor === null || valor === undefined) ? '' : valor.toString();

                    if (csvState.separator === '\t') {
                        return valor.replace(/\r?\n/g, ' ').replace(/\t/g, ' ');
                    }

                    if (valor.indexOf('"') !== -1) {
                        valor = valor.replace(/"/g, '""');
                    }

                    if (
                        valor.indexOf(csvState.separator) !== -1 ||
                        valor.indexOf('"') !== -1 ||
                        valor.indexOf('\n') !== -1
                    ) {
                        valor = '"' + valor + '"';
                    }

                    return valor;
                }).join(csvState.separator);

                lineas.push(linea);
            });

            return lineas.join('\n');
        }

        function actualizarCSVProcesado() {
            if (csvState.hasGeneratedCodes) {
                $('#csv_procesado').val(construirCSVProcesado());
            } else {
                $('#csv_procesado').val('');
            }
        }

        /**
         * Valida formato EAN-13 (13 dígitos numéricos)
         */
        function esEAN13Valido(codigo) {
            return /^\d{13}$/.test(codigo);
        }

        /**
         * Calcula y valida checksum EAN-13
         */
        function validarChecksumEAN13(ean13) {
            if (!esEAN13Valido(ean13)) return false;

            var ean12 = ean13.substring(0, 12);
            var digitoVerificadorEsperado = parseInt(ean13.charAt(12));
            
            var suma = 0;
            for (var i = 0; i < 12; i++) {
                var digito = parseInt(ean12.charAt(i));
                var multiplicador = (i % 2 === 0) ? 1 : 3;
                suma += digito * multiplicador;
            }
            
            var digitoVerificadorCalculado = (10 - (suma % 10)) % 10;
            return digitoVerificadorCalculado === digitoVerificadorEsperado;
        }

        /**
         * Lee y valida el CSV
         */
        function validarCSV(file) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                var contenido = e.target.result;
                var lineas = contenido.split('\n');
                
                // Detectar separador
                var separador = ',';
                if (lineas[0].includes(';')) separador = ';';
                else if (lineas[0].includes('\t')) separador = '\t';
                else if (lineas[0].includes('|')) separador = '|';

                csvState.separator = separador;
                csvState.headers = lineas[0].replace(/^\uFEFF/, '').split(separador).map(function(campo) {
                    return normalizarCampo(campo);
                });
                csvState.rows = [];
                csvState.hasGeneratedCodes = false;

                var preview = [];
                var errores = [];
                var codigosEnCSV = [];
                var filaValida = 0;
                var filasConCodigoVacio = 0;

                // Procesar líneas (saltar encabezado)
                for (var i = 1; i < lineas.length; i++) {
                    var linea = lineas[i].replace(/\r/g, '').trim();
                    if (!linea) continue; // Saltar vacías

                    var campos = linea.split(separador).map(function(campo) {
                        return normalizarCampo(campo);
                    });
                    if (campos.length < 5) {
                        errores.push('Línea ' + (i + 1) + ': Campos incompletos');
                        continue;
                    }

                    var nombre = campos[0].trim();
                    var codigo = campos[3].trim();
                    var precioCompra = campos[1].trim();
                    var precioVenta = campos[2].trim();
                    var categoria = campos[4].trim();

                    var accionCodigo = '';
                    var estado = 'OK';

                    if (!nombre || !precioCompra || !precioVenta || !categoria) {
                        accionCodigo = '<span class="label label-danger">Faltan campos requeridos</span>';
                        estado = 'ERROR';
                    } else if (!codigo) {
                        accionCodigo = '<span class="label label-warning">Pendiente por generar</span>';
                        estado = 'PENDIENTE';
                        filasConCodigoVacio++;
                    } else if (esEAN13Valido(codigo) && validarChecksumEAN13(codigo)) {
                        // Código válido
                        accionCodigo = '<span class="label label-info">Usar: ' + escaparHtml(codigo) + '</span>';
                        
                        // Validar duplicados dentro del CSV
                        if (codigosEnCSV.includes(codigo)) {
                            accionCodigo += ' <span class="label label-danger">DUPLICADO EN CSV</span>';
                            estado = 'ERROR';
                        } else {
                            codigosEnCSV.push(codigo);
                        }
                    } else {
                        // Código inválido
                        if (codigo.length !== 13) {
                            accionCodigo = '<span class="label label-danger">Error: Debe tener 13 dígitos</span>';
                        } else {
                            accionCodigo = '<span class="label label-danger">Error: Checksum inválido</span>';
                        }
                        estado = 'ERROR';
                    }

                    preview.push({
                        linea: i + 1,
                        nombre: nombre.substring(0, 40),
                        codigo: codigo,
                        accion: accionCodigo,
                        estado: estado
                    });

                    csvState.rows.push(campos);

                    if (estado === 'OK') {
                        filaValida++;
                    }
                }

                csvState.pendingCodes = filasConCodigoVacio;
                actualizarCSVProcesado();

                // Mostrar preview
                mostrarPreview(preview, errores, filaValida);
            };

            reader.readAsText(file, 'UTF-8');
        }

        /**
         * Muestra el preview de validación
         */
        function mostrarPreview(preview, errores, filasOK) {
            var tbody = $('#preview-body');
            tbody.empty();

            preview.forEach(function(fila) {
                var estadoClass = fila.estado === 'OK' ? 'success' : (fila.estado === 'PENDIENTE' ? 'warning' : 'danger');
                var fila_html = '<tr>' +
                    '<td><span class="label label-' + estadoClass + '">' + fila.linea + '</span></td>' +
                    '<td><small>' + escaparHtml(fila.nombre) + '</small></td>' +
                    '<td><code>' + escaparHtml(fila.codigo || '(vacío)') + '</code></td>' +
                    '<td>' + fila.accion + '</td>' +
                    '<td><span class="label label-' + estadoClass + '">' + escaparHtml(fila.estado) + '</span></td>' +
                    '</tr>';
                tbody.append(fila_html);
            });

            // Mostrar resumen
            var totalLineas = preview.length;
            var totalErrores = preview.filter(function(f) { return f.estado === 'ERROR'; }).length;
            var totalPendientes = preview.filter(function(f) { return f.estado === 'PENDIENTE'; }).length;
            
            var summary = 'Total: ' + totalLineas + ' productos | ✓ OK: ' + filasOK + ' | ⏳ Pendientes: ' + totalPendientes + ' | ✗ Errores: ' + totalErrores;
            if (errores.length > 0) {
                summary += '<br><strong>Validación:</strong> ' + errores.join('<br>');
            }

            $('#preview-summary').html(summary);

            // Mostrar preview
            $('#preview-container').show();

            if (totalPendientes > 0 && totalErrores === 0) {
                $('#btn-generar-codigos').show().prop('disabled', false);
            } else {
                $('#btn-generar-codigos').toggle(totalPendientes > 0).prop('disabled', true);
            }

            // Habilitar botón importar solo si no hay errores ni pendientes
            if (totalErrores === 0 && totalPendientes === 0 && totalLineas > 0) {
                $('#btn-importar').prop('disabled', false).removeClass('disabled');
                if (csvState.hasGeneratedCodes) {
                    $('#btn-descargar-corregido').show().prop('disabled', false);
                } else {
                    $('#btn-descargar-corregido').hide().prop('disabled', true);
                }
                actualizarCSVProcesado();
            } else {
                $('#btn-importar').prop('disabled', true).addClass('disabled');
                $('#btn-descargar-corregido').toggle(csvState.hasGeneratedCodes).prop('disabled', true);
                actualizarCSVProcesado();
            }
        }

        function generarCodigosFaltantes() {
            if (!csvState.rows.length || csvState.pendingCodes === 0) {
                return;
            }

            $('#btn-generar-codigos').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Generando...');

            $.ajax({
                url: '<?php echo base_url("producto/generar_ean13_lote_ajax"); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    cantidad: csvState.pendingCodes,
                    codigos_existentes: obtenerCodigosActuales()
                }
            }).done(function(response) {
                if (!response || !response.success || !Array.isArray(response.codigos)) {
                    alert(response && response.message ? response.message : 'No se pudieron generar los códigos.');
                    return;
                }

                var indiceCodigo = 0;

                csvState.rows = csvState.rows.map(function(row) {
                    var nuevaFila = row.slice();
                    while (nuevaFila.length < Math.max(csvState.headers.length, 8)) {
                        nuevaFila.push('');
                    }

                    if (!normalizarCampo(nuevaFila[3])) {
                        nuevaFila[3] = response.codigos[indiceCodigo] || '';
                        indiceCodigo++;
                    }

                    return nuevaFila;
                });

                csvState.pendingCodes = 0;
                csvState.hasGeneratedCodes = true;
                actualizarCSVProcesado();
                revalidarEstadoActual();
            }).fail(function() {
                alert('Ocurrió un error al generar los códigos de barras.');
            }).always(function() {
                $('#btn-generar-codigos').html('<i class="fa fa-barcode"></i> Generar código de barras');
            });
        }

        function revalidarEstadoActual() {
            var preview = [];
            var errores = [];
            var codigosEnCSV = [];
            var filaValida = 0;

            csvState.rows.forEach(function(campos, index) {
                while (campos.length < Math.max(csvState.headers.length, 8)) {
                    campos.push('');
                }

                var nombre = normalizarCampo(campos[0]);
                var precioCompra = normalizarCampo(campos[1]);
                var precioVenta = normalizarCampo(campos[2]);
                var codigo = normalizarCampo(campos[3]);
                var categoria = normalizarCampo(campos[4]);

                var accionCodigo = '';
                var estado = 'OK';

                if (!nombre || !precioCompra || !precioVenta || !categoria) {
                    accionCodigo = '<span class="label label-danger">Faltan campos requeridos</span>';
                    estado = 'ERROR';
                } else if (!codigo) {
                    accionCodigo = '<span class="label label-warning">Pendiente por generar</span>';
                    estado = 'PENDIENTE';
                } else if (esEAN13Valido(codigo) && validarChecksumEAN13(codigo)) {
                    if (codigosEnCSV.includes(codigo)) {
                        accionCodigo = '<span class="label label-danger">DUPLICADO EN CSV</span>';
                        estado = 'ERROR';
                    } else {
                        codigosEnCSV.push(codigo);
                        accionCodigo = '<span class="label label-info">Usar: ' + escaparHtml(codigo) + '</span>';
                    }
                } else {
                    accionCodigo = '<span class="label label-danger">Código inválido</span>';
                    estado = 'ERROR';
                }

                preview.push({
                    linea: index + 1,
                    nombre: nombre.substring(0, 40),
                    codigo: codigo,
                    accion: accionCodigo,
                    estado: estado
                });

                if (estado === 'OK') {
                    filaValida++;
                }
            });

            csvState.pendingCodes = preview.filter(function(fila) { return fila.estado === 'PENDIENTE'; }).length;
            mostrarPreview(preview, errores, filaValida);
        }

        function descargarCSVCorregido() {
            var contenido = $('#csv_procesado').val();

            if (!contenido) {
                alert('Primero valida y corrige el archivo para poder descargarlo.');
                return;
            }

            var contenidoConBom = '\uFEFF' + contenido;
            var blob = new Blob([contenidoConBom], { type: 'text/csv;charset=utf-8;' });
            var url = URL.createObjectURL(blob);
            var link = document.createElement('a');
            var fecha = new Date();
            var nombreArchivo = 'productos_corregidos_' +
                fecha.getFullYear() +
                String(fecha.getMonth() + 1).padStart(2, '0') +
                String(fecha.getDate()).padStart(2, '0') + '_' +
                String(fecha.getHours()).padStart(2, '0') +
                String(fecha.getMinutes()).padStart(2, '0') +
                String(fecha.getSeconds()).padStart(2, '0') +
                '.csv';

            link.href = url;
            link.download = nombreArchivo;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        }

        function limpiarArchivo() {
            document.getElementById('archivo').value = '';
            document.getElementById('archivo').focus();
            $('#btn-preview').hide();
            $('#preview-container').hide();
            $('#btn-generar-codigos').hide().prop('disabled', true);
            $('#btn-descargar-corregido').hide().prop('disabled', true);
            $('#btn-importar').prop('disabled', true).addClass('disabled');
            $('#csv_procesado').val('');
            csvState = {
                separator: ',',
                headers: [],
                rows: [],
                pendingCodes: 0,
                hasGeneratedCodes: false
            };
        }
    </script>
    
</div>
