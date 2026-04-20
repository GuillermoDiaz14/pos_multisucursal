

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Pos multisucursal</b> | Versión 2026-1
        </div>
        <strong>Copyright &copy; 2026-2030 <a href="<?php echo base_url(); ?>">Pos multisucursal</a>.</strong> Todos los derechos reservados.
    </footer>
    
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js" type="text/javascript"></script>
    <!-- <script src="<?php echo base_url(); ?>assets/dist/js/pages/dashboard.js" type="text/javascript"></script> -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/validation.js" type="text/javascript"></script>
    <script type="text/javascript">
        var windowURL = window.location.href;
        pageURL = windowURL.substring(0, windowURL.lastIndexOf('/'));
        var x= $('a[href="'+pageURL+'"]');
            x.addClass('active');
            x.parent().addClass('active');
        var y= $('a[href="'+windowURL+'"]');
            y.addClass('active');
            y.parent().addClass('active');

        // Manejo de menús treeview con hover y click
        $(document).ready(function() {
            var menuActivo = null;

            // Click en el treeview: expandir/contraer y fijar
            $('.treeview > a').on('click', function(e) {
                e.preventDefault();
                var $treeview = $(this).closest('.treeview');
                var $menu = $treeview.find('.treeview-menu');
                
                // Si este menú está activo, desactivarlo
                if ($treeview.hasClass('active')) {
                    $treeview.removeClass('active');
                    $menu.stop(true, true).slideUp(200);
                    $treeview.find('.fa-angle-left').css('transform', 'rotate(0deg)');
                    menuActivo = null;
                } else {
                    // Cerrar otro menú si está abierto
                    if (menuActivo) {
                        menuActivo.removeClass('active');
                        menuActivo.find('.treeview-menu').stop(true, true).slideUp(200);
                        menuActivo.find('.fa-angle-left').css('transform', 'rotate(0deg)');
                    }
                    
                    // Abrir este menú
                    $treeview.addClass('active');
                    $menu.stop(true, true).slideDown(200);
                    $treeview.find('.fa-angle-left').css('transform', 'rotate(-90deg)');
                    menuActivo = $treeview;
                }
            });

            // Hover: expandir/contraer temporalmente (si no está fijado por click)
            $('.treeview').on('mouseenter', function() {
                // Solo hacer hover si no está fijado por click
                if (!$(this).hasClass('active')) {
                    $(this).find('.treeview-menu').stop(true, true).slideDown(200);
                    $(this).find('.fa-angle-left').css('transform', 'rotate(-90deg)');
                }
            }).on('mouseleave', function() {
                // Solo contraer si no está fijado por click
                if (!$(this).hasClass('active')) {
                    $(this).find('.treeview-menu').stop(true, true).slideUp(200);
                    $(this).find('.fa-angle-left').css('transform', 'rotate(0deg)');
                }
            });

            // Cerrar menú al hacer click fuera
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.treeview').length && menuActivo) {
                    menuActivo.removeClass('active');
                    menuActivo.find('.treeview-menu').stop(true, true).slideUp(200);
                    menuActivo.find('.fa-angle-left').css('transform', 'rotate(0deg)');
                    menuActivo = null;
                }
            });
        });
    </script>
    
    <!-- Auto-dismiss alerts after 8 seconds and translate CodeIgniter validation errors -->
    <script type="text/javascript">
        $(document).ready(function() {
            // Translate common CodeIgniter validation error messages to Spanish
            var errorTranslations = {
                'field is required': 'campo es obligatorio',
                'field must contain only numeric characters': 'campo debe contener solo caracteres numéricos',
                'field must be a valid email address': 'campo debe ser una dirección de correo válida',
                'field must be at least': 'campo debe tener al menos',
                'characters in length': 'caracteres de largo',
                'field must not exceed': 'campo no debe exceder',
                'The file upload path does not appear to be valid': 'La carpeta de carga no tiene permisos de escritura. Por favor contacte al administrador',
                'The upload destination folder does not appear to be writable': 'La carpeta uploads no tiene permisos de escritura. Por favor contacte al administrador'
            };
            
            // Find and translate validation error messages - ONLY in alert boxes, not in menu
            var errorElements = $('.alert-danger li, .error');
            errorElements.each(function() {
                var text = $(this).text();
                for (var key in errorTranslations) {
                    if (text.indexOf(key) !== -1) {
                        text = text.replace(key, errorTranslations[key]);
                    }
                }
                $(this).text(text);
            });
            
            // Auto-dismiss all alerts after 8 seconds
            var alertTimeout = 8000; // 8 seconds
            
            var alerts = $('.alert');
            alerts.each(function() {
                var $this = $(this);
                setTimeout(function() {
                    $this.fadeOut('slow', function() {
                        $this.remove();
                    });
                }, alertTimeout);
            });
            
            // Also allow closing by clicking the X button
            $('.alert .close').on('click', function() {
                $(this).closest('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            });
        });
    </script>
  </body>
</html>
