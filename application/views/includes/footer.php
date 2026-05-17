

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
          Desarrollado por Guillermo Díaz | <b>Versión 2026-3</b>
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

  <!-- ── Zebra: impresión directa ZPL via REST API (sin SDK externo) ── -->
  <?php
  $CI =& get_instance();
  // Leer impresoras desde sesión (por sucursal) con fallback al archivo de config
  $zebra_ticket = $CI->session->userdata('zebra_ticket_printer');
  $zebra_label  = $CI->session->userdata('zebra_label_printer');
  if (empty($zebra_ticket) || empty($zebra_label)) {
      $CI->config->load('zebra_printers', TRUE);
      if (empty($zebra_ticket)) $zebra_ticket = $CI->config->item('zebra_ticket_printer', 'zebra_printers');
      if (empty($zebra_label))  $zebra_label  = $CI->config->item('zebra_label_printer',  'zebra_printers');
  }
  $allowed_media          = ['^MNN', '^MNC'];
  $zebra_ticket_media     = $CI->session->userdata('zebra_ticket_media_type');
  $zebra_label_media      = $CI->session->userdata('zebra_label_media_type');
  if (!in_array($zebra_ticket_media, $allowed_media)) $zebra_ticket_media = '^MNC';
  if (!in_array($zebra_label_media,  $allowed_media)) $zebra_label_media  = '^MNN';
  ?>
  <script>
  var ZEBRA_HOST              = 'https://localhost:9101';
  var ZEBRA_TICKET_PRINTER    = '<?php echo htmlspecialchars($zebra_ticket,       ENT_QUOTES); ?>';
  var ZEBRA_LABEL_PRINTER     = '<?php echo htmlspecialchars($zebra_label,        ENT_QUOTES); ?>';
  var ZEBRA_TICKET_MEDIA_TYPE = '<?php echo htmlspecialchars($zebra_ticket_media, ENT_QUOTES); ?>';
  var ZEBRA_LABEL_MEDIA_TYPE  = '<?php echo htmlspecialchars($zebra_label_media,  ENT_QUOTES); ?>';

  function zebraLog(msg, type) {
      var box = document.getElementById('zebra-debug-box');
      if (!box) {
          box = document.createElement('div');
          box.id = 'zebra-debug-box';
          box.style.cssText = 'position:fixed;bottom:10px;right:10px;z-index:9999;min-width:320px;max-width:420px;font-size:12px;';
          document.body.appendChild(box);
      }
      var colors = { ok:'#28a745', error:'#dc3545', info:'#17a2b8' };
      var item = document.createElement('div');
      item.style.cssText = 'background:#222;color:'+(colors[type]||'#fff')+';padding:8px 12px;margin-top:4px;border-radius:4px;border-left:4px solid '+(colors[type]||'#555');
      item.textContent = '[Zebra] ' + msg;
      box.appendChild(item);
      setTimeout(function(){ if(item.parentNode) item.parentNode.removeChild(item); }, 6000);
      console.log('[Zebra]['+type+']', msg);
  }

  /**
   * Busca una impresora por nombre en la lista de disponibles.
   * Retorna Promise<device|null>
   */
  function zebraGetPrinter(printerName) {
      return fetch(ZEBRA_HOST + '/available')
      .then(function(r) { return r.json(); })
      .then(function(data) {
          var list = (data && data.printer) ? data.printer : [];
          var found = null;
          for (var i = 0; i < list.length; i++) {
              if (list[i].name === printerName) { found = list[i]; break; }
          }
          if (!found) {
              zebraLog('Impresora no encontrada: ' + printerName, 'error');
              return null;
          }
          return found;
      });
  }

  /**
   * Envía ZPL a una impresora específica.
   * Retorna Promise<bool>
   */
  function zebraSend(device, zpl) {
      zebraLog('Enviando a: ' + device.name + '...', 'info');
      return fetch(ZEBRA_HOST + '/write', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ device: device, data: zpl })
      }).then(function(r) {
          return r.text().then(function(body) { return r.ok; });
      });
  }

  /** Imprime ticket de venta en la impresora de tickets (80mm) */
  function printZebraTicket(id_venta) {
      var btn = document.querySelector('[data-zebra-id="' + id_venta + '"]');
      if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>'; }

      $.getJSON('<?php echo base_url("carrito/getZPL/"); ?>' + id_venta)
      .done(function(res) {
          if (res.error) { zebraLog('Error servidor: ' + res.error, 'error'); resetBtn(btn); return; }

          zebraGetPrinter(ZEBRA_TICKET_PRINTER)
          .then(function(device) {
              if (!device) { resetBtn(btn); return; }
              return zebraSend(device, res.zpl).then(function(ok) {
                  if (ok) { zebraLog('✔ Ticket impreso.', 'ok'); resetBtn(btn, true); }
                  else    { zebraLog('Error al imprimir ticket.', 'error'); resetBtn(btn); }
              });
          })
          .catch(function(err) {
              zebraLog('No se pudo conectar a Zebra Browser Print. ¿Está corriendo?', 'error');
              console.error('[Zebra]', err);
              resetBtn(btn);
          });
      })
      .fail(function(xhr) {
          zebraLog('Error al obtener ZPL: ' + xhr.status, 'error');
          resetBtn(btn);
      });
  }

  /** Imprime ticket de APARTADO en la impresora de tickets (80mm) */
  function printZebraApartado(id_venta) {
      var btn = document.querySelector('[data-zebra-apartado="' + id_venta + '"]');
      if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>'; }

      $.getJSON('<?php echo base_url("carrito/getZPL_apartado/"); ?>' + id_venta)
      .done(function(res) {
          if (res.error) { zebraLog('Error servidor: ' + res.error, 'error'); resetBtn(btn); return; }
          zebraGetPrinter(ZEBRA_TICKET_PRINTER)
          .then(function(device) {
              if (!device) { resetBtn(btn); return; }
              return zebraSend(device, res.zpl).then(function(ok) {
                  if (ok) { zebraLog('✔ Ticket de apartado impreso.', 'ok'); resetBtn(btn, true); }
                  else    { zebraLog('Error al imprimir ticket de apartado.', 'error'); resetBtn(btn); }
              });
          })
          .catch(function(err) {
              zebraLog('No se pudo conectar a Zebra Browser Print. ¿Está corriendo?', 'error');
              console.error('[Zebra]', err);
              resetBtn(btn);
          });
      })
      .fail(function(xhr) {
          zebraLog('Error al obtener ZPL: ' + xhr.status, 'error');
          resetBtn(btn);
      });
  }

  /** Imprime etiqueta de producto en la impresora de etiquetas (39x16mm) */
  function printZebraLabel(id_producto, btn) {
      if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>'; }

      $.getJSON('<?php echo base_url("productos/getLabel/"); ?>' + id_producto)
      .done(function(res) {
          if (res.error) { zebraLog('Error servidor: ' + res.error, 'error'); resetBtn(btn); return; }

          zebraGetPrinter(ZEBRA_LABEL_PRINTER)
          .then(function(device) {
              if (!device) { resetBtn(btn); return; }
              return zebraSend(device, res.zpl).then(function(ok) {
                  if (ok) { zebraLog('✔ Etiqueta impresa.', 'ok'); resetBtn(btn, true); }
                  else    { zebraLog('Error al imprimir etiqueta.', 'error'); resetBtn(btn); }
              });
          })
          .catch(function(err) {
              zebraLog('No se pudo conectar a Zebra Browser Print. ¿Está corriendo?', 'error');
              console.error('[Zebra]', err);
              resetBtn(btn);
          });
      })
      .fail(function(xhr) {
          zebraLog('Error al obtener ZPL etiqueta: ' + xhr.status, 'error');
          resetBtn(btn);
      });
  }

  function resetBtn(btn, ok) {
      if (!btn) return;
      btn.disabled = false;
      btn.innerHTML = ok
          ? '<i class="fa fa-check" style="color:#28a745"></i>'
          : '<i class="fa fa-print"></i>';
      if (ok) setTimeout(function(){ btn.innerHTML = '<i class="fa fa-print"></i>'; }, 2000);
  }

  // Auto-diagnóstico silencioso al cargar
  $(document).ready(function() {
      fetch(ZEBRA_HOST + '/available')
      .then(function(r){ return r.json(); })
      .then(function(data){
          var list = (data && data.printer) ? data.printer : [];
          var nombres = list.map(function(p){ return p.name; });
          console.info('[Zebra] Impresoras disponibles:', nombres);
          if (nombres.indexOf(ZEBRA_TICKET_PRINTER) === -1)
              console.warn('[Zebra] Impresora de tickets NO encontrada:', ZEBRA_TICKET_PRINTER);
          if (nombres.indexOf(ZEBRA_LABEL_PRINTER) === -1)
              console.warn('[Zebra] Impresora de etiquetas NO encontrada:', ZEBRA_LABEL_PRINTER);
      })
      .catch(function(){ console.warn('[Zebra] Servicio no disponible en ' + ZEBRA_HOST); });
  });
  </script>
  </body>
</html>
