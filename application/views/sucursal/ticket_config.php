<?php
// 1 dot ZPL (203dpi) = 0.125 mm
$s = $sucursal;
$fs_titulo  = (int)($s->ticket_fs_titulo  ?? 48);
$fs_info    = (int)($s->ticket_fs_info    ?? 22);
$fs_normal  = (int)($s->ticket_fs_normal  ?? 24);
$fs_total   = (int)($s->ticket_fs_total   ?? 40);
$fs_gracias = (int)($s->ticket_fs_gracias ?? 28);
$logo_op    = (int)($s->ticket_logo_opacidad ?? 30);  // %
$logo_ancho = (int)($s->ticket_logo_ancho    ?? 70);  // mm
$margen     = (int)($s->ticket_margen        ?? 5);   // mm
$separador  = (int)($s->ticket_separador     ?? 3);   // dots grosor
?>
<style>
#ticket-preview-wrap { background:#e8e8e8; padding:12px; border-radius:6px; }
#ticket-preview {
    width:80mm; margin:0 auto; background:#fff;
    font-family:'Courier New',Courier,monospace;
    position:relative; overflow:hidden;
    box-shadow:0 2px 8px rgba(0,0,0,.25);
    /* Sin padding-top aquí; se maneja dentro del ticket-content */
}
/* Logo: flujo normal, centrado, semi-transparente superpuesto con mix-blend */
#prev-logo-wrap {
    text-align:center;
    margin:0;
    /* Superponemos el logo sobre el texto: lo sacamos del flujo con absolute
       pero lo centramos correctamente. El ticket-content tiene padding-top
       igual a la altura estimada del logo para que no quede texto debajo. */
    position:absolute; top:var(--margen); left:0; right:0;
    z-index:0; pointer-events:none;
}
#prev-logo {
    display:block; margin:0 auto;
    width:var(--logo-ancho); height:auto;
    opacity:var(--logo-op);
}
.ticket-content { position:relative; z-index:1; }
.tc  { text-align:center; }
.mx  { margin-left:var(--margen); margin-right:var(--margen); }
.row-sb { display:flex; justify-content:space-between; }
.hr  { border:none; border-top:var(--sep) solid #000;
       margin:1.25mm var(--margen); }
/* Tabla productos */
.tkt-table { width:calc(80mm - var(--margen) * 2); margin:0 var(--margen); border-collapse:collapse; }
.tkt-table th { font-weight:bold; border-bottom:var(--sep) solid #000; padding:0 0 .5mm; font-size:var(--fs-normal); }
.tkt-table td { padding:.3mm 0; font-size:var(--fs-normal); vertical-align:top; }
.tkt-table .col-prod { width:42%; }
.tkt-table .col-prec { width:23%; text-align:right; }
.tkt-table .col-cant { width:12%; text-align:center; }
.tkt-table .col-sub  { width:23%; text-align:right; }
/* Controls */
.config-row  { display:flex; align-items:center; margin-bottom:10px; }
.config-row label { flex:1; margin:0; font-weight:normal; }
.sl-wrap { display:flex; align-items:center; gap:6px; }
.sl-wrap input[type=range] { width:120px; }
.sl-val { font-size:12px; color:#333; min-width:32px; font-weight:bold; }
.sl-unit { font-size:11px; color:#999; }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-ticket"></i> Ticket &nbsp;<small><?php echo htmlspecialchars($s->nombre_sucursal); ?></small></h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('sucursal/sucursal_lista'); ?>">Sucursales</a></li>
      <li class="active">Configurar Ticket</li>
    </ol>
  </section>

  <section class="content">
    <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <?php echo $this->session->flashdata('success'); ?>
    </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissable">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <?php echo $this->session->flashdata('error'); ?>
    </div>
    <?php endif; ?>

    <?php $this->load->helper('form'); echo form_open_multipart('sucursal/ticket_config_save'); ?>
    <input type="hidden" name="id_sucursal" value="<?php echo $s->id_sucursal; ?>">

    <div class="row">
      <!-- ══ Columna opciones ══ -->
      <div class="col-md-6">

        <!-- Datos sucursal -->
        <div class="box box-default collapsed-box">
          <div class="box-header with-border" style="cursor:pointer" data-widget="collapse">
            <h3 class="box-title"><i class="fa fa-building-o"></i> Datos de la sucursal <small class="text-muted">(editar en Sucursales)</small></h3>
            <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i></button></div>
          </div>
          <div class="box-body">
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($s->nombre_sucursal); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($s->celular ?: '—'); ?></p>
            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($s->direccion ?: '—'); ?></p>
            <p><strong>Ciudad:</strong> <?php echo htmlspecialchars($s->ciudad ?: '—'); ?></p>
            <p><strong>Correo:</strong> <?php echo htmlspecialchars($s->correo ?: '—'); ?></p>
            <a href="<?php echo base_url('sucursal/edit/'.$s->id_sucursal); ?>" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i> Editar datos</a>
          </div>
        </div>

        <!-- ── Secciones visibles ── -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-eye"></i> Mostrar en el ticket</h3>
          </div>
          <div class="box-body">
            <?php
            $toggles = [
              ['ticket_mostrar_logo',    'Logo / marca de agua',           'prev-logo-wrap'],
              ['ticket_mostrar_tel',     'Teléfono',                       'prev-tel'],
              ['ticket_mostrar_dir',     'Dirección y ciudad',             'prev-dir'],
              ['ticket_mostrar_correo',  'Correo electrónico',             'prev-correo'],
              ['ticket_mostrar_num',     'Número de venta',                'prev-num'],
              ['ticket_mostrar_fecha',   'Fecha y hora',                   'prev-fecha'],
              ['ticket_mostrar_cliente', 'Nombre del cliente',             'prev-cliente'],
              ['ticket_mostrar_desc',    'Línea de descuento',             'prev-desc'],
              ['ticket_mostrar_cambio',  'Efectivo recibido y cambio',     'prev-cambio'],
            ];
            foreach ($toggles as $t):
              $default = in_array($t[0], ['ticket_mostrar_correo']) ? 0 : 1;
              $checked = (int)($s->{$t[0]} ?? $default);
            ?>
            <div class="config-row">
              <label><?php echo $t[1]; ?></label>
              <input type="checkbox" name="<?php echo $t[0]; ?>" value="1"
                     <?php echo $checked ? 'checked' : ''; ?>
                     data-target="<?php echo $t[2]; ?>"
                     class="toggle-preview" style="width:20px;height:20px;cursor:pointer;">
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- ── Logo ── -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-image"></i> Logo / Marca de agua</h3>
          </div>
          <div class="box-body">

            <!-- Logo actual / subir nuevo -->
            <?php $logoActual = !empty($s->ticket_logo) ? $s->ticket_logo : ''; ?>
            <?php if ($logoActual): ?>
            <div class="form-group" style="margin-bottom:14px;">
              <label style="font-weight:normal; display:block; margin-bottom:6px;">Logo actual</label>
              <div style="display:flex; align-items:center; gap:12px;">
                <img src="<?php echo base_url('uploads/logos/'.$logoActual); ?>"
                     style="max-height:60px; max-width:120px; border:1px solid #ddd; padding:3px; border-radius:3px;"
                     alt="Logo actual">
                <a href="<?php echo base_url('sucursal/ticket_logo_delete/'.$s->id_sucursal); ?>"
                   class="btn btn-xs btn-danger"
                   onclick="return confirm('¿Eliminar el logo del ticket?')">
                  <i class="fa fa-trash"></i> Eliminar logo
                </a>
              </div>
            </div>
            <?php endif; ?>

            <div class="form-group" style="margin-bottom:14px;">
              <label style="font-weight:normal; display:block; margin-bottom:4px;">
                <?php echo $logoActual ? 'Cambiar logo' : 'Subir logo'; ?>
                <small class="text-muted">(JPG, PNG, GIF, WEBP · máx 2 MB)</small>
              </label>
              <input type="file" name="ticket_logo_file" id="inp-logo-file"
                     accept=".jpg,.jpeg,.png,.gif,.webp"
                     style="margin-bottom:0;">
            </div>

            <?php
            $logoSliders = [
              ['ticket_logo_opacidad', 'Opacidad',      $logo_op,    5, 80,  '%',  '--logo-op-pct'],
              ['ticket_logo_ancho',    'Ancho',          $logo_ancho, 30, 78, 'mm', '--logo-ancho'],
            ];
            foreach ($logoSliders as $sl):
            ?>
            <div class="form-group" style="margin-bottom:12px;">
              <label style="font-weight:normal; margin-bottom:2px; display:block;"><?php echo $sl[1]; ?></label>
              <div class="sl-wrap">
                <input type="range" name="<?php echo $sl[0]; ?>" id="sl-<?php echo $sl[0]; ?>"
                       min="<?php echo $sl[3]; ?>" max="<?php echo $sl[4]; ?>" step="1"
                       value="<?php echo $sl[2]; ?>"
                       data-unit="<?php echo $sl[5]; ?>"
                       data-css="<?php echo $sl[6]; ?>"
                       class="logo-slider">
                <span class="sl-val" id="lbl-<?php echo $sl[0]; ?>"><?php echo $sl[2]; ?></span>
                <span class="sl-unit"><?php echo $sl[5]; ?></span>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- ── Diseño / Layout ── -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-sliders"></i> Diseño</h3>
          </div>
          <div class="box-body">
            <?php
            $designSliders = [
              ['ticket_margen',    'Márgenes laterales', $margen,    3, 15, 'mm',   '--margen'],
              ['ticket_separador', 'Grosor separadores', $separador, 1,  6, 'dots', '--sep-dots'],
            ];
            foreach ($designSliders as $sl):
            ?>
            <div class="form-group" style="margin-bottom:12px;">
              <label style="font-weight:normal; margin-bottom:2px; display:block;"><?php echo $sl[1]; ?></label>
              <div class="sl-wrap">
                <input type="range" name="<?php echo $sl[0]; ?>" id="sl-<?php echo $sl[0]; ?>"
                       min="<?php echo $sl[3]; ?>" max="<?php echo $sl[4]; ?>" step="1"
                       value="<?php echo $sl[2]; ?>"
                       data-unit="<?php echo $sl[5]; ?>"
                       data-css="<?php echo $sl[6]; ?>"
                       class="design-slider">
                <span class="sl-val" id="lbl-<?php echo $sl[0]; ?>"><?php echo $sl[2]; ?></span>
                <span class="sl-unit"><?php echo $sl[5]; ?></span>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- ── Fuentes ── -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-text-height"></i> Tamaño de fuentes</h3>
          </div>
          <div class="box-body">
            <?php
            $fontSliders = [
              ['ticket_fs_titulo',  'Nombre sucursal',     $fs_titulo,  32, 72, '--fs-titulo'],
              ['ticket_fs_info',    'Info (tel/dir/correo)',$fs_info,   16, 36, '--fs-info'],
              ['ticket_fs_normal',  'Texto normal / tabla', $fs_normal, 18, 40, '--fs-normal'],
              ['ticket_fs_total',   'Total',               $fs_total,   28, 60, '--fs-total'],
              ['ticket_fs_gracias', 'Mensaje de cierre',   $fs_gracias, 18, 44, '--fs-gracias'],
            ];
            foreach ($fontSliders as $sl):
            ?>
            <div class="form-group" style="margin-bottom:10px;">
              <label style="font-weight:normal; margin-bottom:2px; display:block;"><?php echo $sl[1]; ?></label>
              <div class="sl-wrap">
                <input type="range" name="<?php echo $sl[0]; ?>" id="sl-<?php echo $sl[0]; ?>"
                       min="<?php echo $sl[3]; ?>" max="<?php echo $sl[4]; ?>" step="2"
                       value="<?php echo $sl[2]; ?>"
                       data-css="<?php echo $sl[5]; ?>"
                       class="font-slider">
                <span class="sl-val" id="lbl-<?php echo $sl[0]; ?>"><?php echo $sl[2]; ?></span>
                <span class="sl-unit">dots</span>
                <small class="text-muted">(<?php echo round($sl[2]*0.125,1); ?> mm)</small>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- ── Textos ── -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-comment"></i> Textos</h3>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>2ª línea del nombre <small class="text-muted">(mismo tamaño que el nombre, ej. "Paty")</small></label>
              <input type="text" class="form-control" name="ticket_subtitulo"
                     id="inp-subtitulo"
                     value="<?php echo htmlspecialchars($s->ticket_subtitulo ?? 'Paty'); ?>"
                     maxlength="50">
            </div>
            <div class="form-group">
              <label>Mensaje de cierre</label>
              <input type="text" class="form-control" name="ticket_msg_gracias"
                     id="inp-gracias"
                     value="<?php echo htmlspecialchars($s->ticket_msg_gracias ?? '¡Gracias por su compra!'); ?>"
                     maxlength="60">
            </div>
            <div class="form-group">
              <label>Política / condiciones <small class="text-muted">(letra pequeña al pie)</small></label>
              <textarea class="form-control" name="ticket_politica" id="inp-politica"
                        rows="3"><?php echo htmlspecialchars($s->ticket_politica ?? ''); ?></textarea>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> Guardar configuración</button>
      </div>

      <!-- ══ Preview 1:1 ══ -->
      <div class="col-md-6">
        <div class="box box-default" style="position:sticky; top:50px;">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-print"></i> Vista previa (80mm real)</h3>
          </div>
          <div class="box-body" id="ticket-preview-wrap">
            <div id="ticket-preview" style="
              --fs-titulo:  <?php echo $fs_titulo  * 0.125; ?>mm;
              --fs-info:    <?php echo $fs_info    * 0.125; ?>mm;
              --fs-normal:  <?php echo $fs_normal  * 0.125; ?>mm;
              --fs-total:   <?php echo $fs_total   * 0.125; ?>mm;
              --fs-gracias: <?php echo $fs_gracias * 0.125; ?>mm;
              --logo-op:    <?php echo $logo_op / 100; ?>;
              --logo-ancho: <?php echo $logo_ancho; ?>mm;
              --margen:     <?php echo $margen; ?>mm;
              --sep:        <?php echo max(0.1, $separador * 0.125); ?>mm;
            ">
              <!-- Logo superpuesto centrado (position:absolute vía CSS) -->
              <div id="prev-logo-wrap" style="<?php echo (int)($s->ticket_mostrar_logo??1) ? '' : 'display:none'; ?>">
                <img id="prev-logo" src="<?php echo !empty($s->ticket_logo) ? base_url('uploads/logos/'.$s->ticket_logo) : base_url('assets/dist/img/logo.png'); ?>" alt="">
              </div>

              <!-- ticket-content: padding top = margen + separación inicial -->
              <div class="ticket-content" style="padding:var(--margen) 0 var(--margen) 0;">
                <!-- Nombre completo en una sola línea -->
                <div id="prev-nombre" class="tc" style="font-weight:bold; padding:0 var(--margen); font-size:var(--fs-titulo); line-height:1.2;">
                  <?php
                    $sub = trim($s->ticket_subtitulo ?? '');
                    echo htmlspecialchars($s->nombre_sucursal . ($sub ? ' '.$sub : ''));
                  ?>
                </div>

                <!-- Info -->
                <div id="prev-info-grp">
                  <div id="prev-tel" class="tc" style="font-size:var(--fs-info); line-height:1.4; <?php echo (int)($s->ticket_mostrar_tel??1) && $s->celular ? '' : 'display:none;'; ?>">
                    Tel: <?php echo htmlspecialchars($s->celular); ?>
                  </div>
                  <div id="prev-dir" class="tc mx" style="font-size:var(--fs-info); line-height:1.4; <?php echo (int)($s->ticket_mostrar_dir??1) && ($s->direccion||$s->ciudad) ? '' : 'display:none;'; ?>">
                    <?php echo htmlspecialchars(trim($s->direccion.', '.$s->ciudad, ', ')); ?>
                  </div>
                  <div id="prev-correo" class="tc" style="font-size:var(--fs-info); line-height:1.4; <?php echo (int)($s->ticket_mostrar_correo??0) && $s->correo ? '' : 'display:none;'; ?>">
                    <?php echo htmlspecialchars($s->correo); ?>
                  </div>
                </div>

                <div style="margin-top:1.5mm"></div>
                <hr class="hr">

                <!-- Venta / fecha / cliente -->
                <div style="margin-top:1mm">
                  <div class="row-sb mx" style="font-size:var(--fs-normal); line-height:1.4;">
                    <span id="prev-num" style="<?php echo (int)($s->ticket_mostrar_num??1) ? '' : 'display:none;'; ?>"># 0001</span>
                    <span id="prev-fecha" style="<?php echo (int)($s->ticket_mostrar_fecha??1) ? '' : 'display:none;'; ?>"><?php echo date('d-m-Y H:i'); ?></span>
                  </div>
                  <div id="prev-cliente" class="mx" style="font-size:var(--fs-normal); line-height:1.4; <?php echo (int)($s->ticket_mostrar_cliente??1) ? '' : 'display:none;'; ?>">Cliente: Juan Pérez</div>
                </div>
                <hr class="hr">

                <!-- Tabla productos -->
                <table class="tkt-table">
                  <thead>
                    <tr>
                      <th class="col-prod">PRODUCTO</th>
                      <th class="col-prec">PRECIO</th>
                      <th class="col-cant">CNT</th>
                      <th class="col-sub">SUB</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="col-prod">Blusa floral</td>
                      <td class="col-prec">$250.00</td>
                      <td class="col-cant">1</td>
                      <td class="col-sub">$250.00</td>
                    </tr>
                    <tr>
                      <td class="col-prod">Pantalón café largo</td>
                      <td class="col-prec">$450.00</td>
                      <td class="col-cant">2</td>
                      <td class="col-sub">$900.00</td>
                    </tr>
                  </tbody>
                </table>
                <hr class="hr">

                <!-- Descuento -->
                <div class="row-sb mx" id="prev-desc" style="font-size:var(--fs-normal); line-height:1.4; <?php echo (int)($s->ticket_mostrar_desc??1) ? '' : 'display:none;'; ?>">
                  <span>Descuento:</span><span>$50.00</span>
                </div>

                <!-- Recibido / Cambio -->
                <div id="prev-cambio" style="<?php echo (int)($s->ticket_mostrar_cambio??1) ? '' : 'display:none;'; ?>">
                  <div class="row-sb mx" style="font-size:var(--fs-normal); line-height:1.4;"><span>Recibido:</span><span>$1,200.00</span></div>
                  <div class="row-sb mx" style="font-size:var(--fs-normal); line-height:1.4;"><span>Cambio:</span><span>$100.00</span></div>
                </div>

                <!-- Total -->
                <div id="prev-total" class="row-sb mx" style="font-size:var(--fs-total); font-weight:bold; margin-top:1.5mm; line-height:1.3;">
                  <span>TOTAL</span><span>$1,100.00</span>
                </div>
                <hr class="hr">

                <!-- Gracias -->
                <div id="prev-gracias" class="tc" style="font-size:var(--fs-gracias); font-weight:bold; margin-top:1.5mm; line-height:1.4;">
                  <?php echo htmlspecialchars($s->ticket_msg_gracias ?? '¡Gracias por su compra!'); ?>
                </div>

                <!-- Política -->
                <div id="prev-politica" class="tc mx" style="font-size:var(--fs-info); margin-top:2mm; color:#555; white-space:pre-wrap; line-height:1.4;">
                  <?php echo htmlspecialchars($s->ticket_politica ?? ''); ?>
                </div>

                <div style="height:5mm"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </section>
</div>

<script>
var preview = document.getElementById('ticket-preview');

// ── Toggles ──────────────────────────────────────────────────────────────
document.querySelectorAll('.toggle-preview').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var el = document.getElementById(this.dataset.target);
        if (el) el.style.display = this.checked ? '' : 'none';
    });
});

// ── Sliders de fuente ─────────────────────────────────────────────────────
document.querySelectorAll('.font-slider').forEach(function(sl) {
    sl.addEventListener('input', function() {
        var dots = parseInt(this.value);
        var mm   = (dots * 0.125).toFixed(3);
        document.getElementById('lbl-' + this.name).textContent = dots;
        // actualizar mm hint
        var small = this.closest('.form-group').querySelector('small');
        if (small) small.textContent = '(' + (dots*0.125).toFixed(1) + ' mm)';
        preview.style.setProperty(this.dataset.css, mm + 'mm');
        // sincronizar tabla si es font-normal
        if (this.dataset.css === '--fs-normal') {
            document.querySelectorAll('.tkt-table th, .tkt-table td').forEach(function(c) {
                c.style.fontSize = mm + 'mm';
            });
        }
    });
});

// ── Sliders de logo ───────────────────────────────────────────────────────
document.querySelectorAll('.logo-slider').forEach(function(sl) {
    sl.addEventListener('input', function() {
        var v = parseInt(this.value);
        document.getElementById('lbl-' + this.name).textContent = v;
        if (this.name === 'ticket_logo_opacidad') {
            preview.style.setProperty('--logo-op', (v/100).toFixed(2));
        } else if (this.name === 'ticket_logo_ancho') {
            preview.style.setProperty('--logo-ancho', v + 'mm');
        }
    });
});

// ── Sliders de diseño ─────────────────────────────────────────────────────
document.querySelectorAll('.design-slider').forEach(function(sl) {
    sl.addEventListener('input', function() {
        var v = parseInt(this.value);
        document.getElementById('lbl-' + this.name).textContent = v;
        if (this.name === 'ticket_margen') {
            preview.style.setProperty('--margen', v + 'mm');
        } else if (this.name === 'ticket_separador') {
            var mm = Math.max(0.1, v * 0.125).toFixed(3);
            preview.style.setProperty('--sep', mm + 'mm');
        }
    });
});

// ── Textos ────────────────────────────────────────────────────────────────
var nombreBase = <?php echo json_encode($s->nombre_sucursal, JSON_UNESCAPED_UNICODE); ?>;
document.getElementById('inp-subtitulo').addEventListener('input', function() {
    var sub = this.value.trim();
    document.getElementById('prev-nombre').textContent = sub ? nombreBase + ' ' + sub : nombreBase;
});
document.getElementById('inp-gracias').addEventListener('input', function() {
    document.getElementById('prev-gracias').textContent = this.value;
});
document.getElementById('inp-politica').addEventListener('input', function() {
    document.getElementById('prev-politica').textContent = this.value;
});

// ── Preview logo en tiempo real ───────────────────────────────────────────
document.getElementById('inp-logo-file').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('prev-logo').src = e.target.result;
    };
    reader.readAsDataURL(file);
});
</script>
