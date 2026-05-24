<?php
$simbolo_moneda = $configuracionInfo->simbolo_moneda;
?>

<style>
.eqc-wrapper { padding: 16px; }
.eqc-filters { display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap; }
.eqc-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:10px; }
.eqc-card { border:1px solid #ddd; border-radius:6px; padding:10px; background:#fff; text-align:center; }
.eqc-card .nombre { font-size:12px; margin:0 0 4px; min-height:32px; }
.eqc-card svg { display:block; margin:0 auto; }
.eqc-card .precio { font-weight:bold; font-size:13px; margin:4px 0 0; }
.eqc-card.hidden { display:none; }
.eqc-log { margin-top:14px; max-height:140px; overflow:auto; background:#1e1e1e; color:#cfd8dc; font-family:monospace; font-size:11px; padding:8px; border-radius:4px; display:none; }
.eqc-log.show { display:block; }
.eqc-log .ok    { color:#8bc34a; }
.eqc-log .err   { color:#ef5350; }
.eqc-log .info  { color:#90caf9; }
</style>

<div class="content-wrapper">
<section class="content-header">
  <h1>Etiquetas por Categoría</h1>
</section>

<section class="content">
<div class="box box-primary">
  <div class="box-header">
    <h3 class="box-title">Etiquetas</h3>
  </div>

  <div class="box-body eqc-wrapper">
    <div class="eqc-filters">
      <select id="seleccionarCategoria" class="form-control" style="max-width:280px;">
        <option value="">Todas las Categorías</option>
        <?php foreach ($categorias as $categoria): ?>
        <option value="<?php echo $categoria->id_categoria; ?>">
          <?php echo $categoria->nombre_categoria; ?>
        </option>
        <?php endforeach; ?>
      </select>

      <button id="imprimirEtiquetas" class="btn btn-primary">
        <i class="fa fa-print"></i> Imprimir etiquetas
      </button>
      <span id="eqc-count" class="text-muted" style="font-size:12px;"></span>
    </div>

    <div class="eqc-grid" id="eqc-grid">
      <?php foreach ($productos as $producto): ?>
      <div class="eqc-card"
           data-categoria="<?php echo $producto['categoria']; ?>"
           data-codigo="<?php echo htmlspecialchars($producto['codigo'], ENT_QUOTES); ?>"
           data-nombre="<?php echo htmlspecialchars($producto['nombre_producto'], ENT_QUOTES); ?>"
           data-precio="<?php echo (float)$producto['precio_venta']; ?>">
        <p class="nombre"><?php echo htmlspecialchars($producto['nombre_producto']); ?></p>
        <svg class="barcode-<?php echo $producto['id_producto']; ?>"></svg>
        <p class="precio"><?php echo $simbolo_moneda; ?> <?php echo number_format($producto['precio_venta'], 2); ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="eqc-log" id="eqc-log"></div>
  </div>
</div>
</section>
</div>

<!-- Librería que genera los códigos de barras (preview) -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3/dist/JsBarcode.all.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/zebra-labels.js"></script>
<script>
<?php foreach ($productos as $producto): ?>
JsBarcode(document.querySelector('.barcode-<?php echo $producto['id_producto']; ?>'),
  "<?php echo $producto['codigo']; ?>", {
    format: "CODE39", width: 1, height: 16, fontSize: 9,
    textMargin: 0, displayValue: true, margin: 0
});
<?php endforeach; ?>
</script>

<script>
(function() {
  var symbolCurrency = <?php echo json_encode($simbolo_moneda); ?>;
  var selectCategoria = document.getElementById('seleccionarCategoria');
  var btnImprimir     = document.getElementById('imprimirEtiquetas');
  var grid            = document.getElementById('eqc-grid');
  var countLabel      = document.getElementById('eqc-count');
  var logBox          = document.getElementById('eqc-log');

  var settings = Object.assign({}, ZebraLabels.DEFAULT_SETTINGS);

  function log(msg, kind) {
    logBox.classList.add('show');
    var div = document.createElement('div');
    div.className = kind || 'info';
    div.textContent = msg;
    logBox.appendChild(div);
    logBox.scrollTop = logBox.scrollHeight;
  }

  function visibleCards() {
    return Array.prototype.slice.call(grid.querySelectorAll('.eqc-card:not(.hidden)'));
  }

  function updateCount() {
    var n = visibleCards().length;
    countLabel.textContent = n + ' producto' + (n === 1 ? '' : 's') + ' visible' + (n === 1 ? '' : 's');
  }

  selectCategoria.addEventListener('change', function() {
    var cat = this.value;
    Array.prototype.slice.call(grid.querySelectorAll('.eqc-card')).forEach(function(card) {
      if (!cat || card.dataset.categoria === cat) {
        card.classList.remove('hidden');
      } else {
        card.classList.add('hidden');
      }
    });
    updateCount();
  });
  updateCount();

  btnImprimir.addEventListener('click', function() {
    var cards = visibleCards();
    if (!cards.length) {
      alert('No hay productos para imprimir.');
      return;
    }
    if (typeof zebraGetPrinter !== 'function' || typeof zebraSend !== 'function') {
      alert('Zebra Browser Print no está disponible.');
      return;
    }

    var allZpl = '';
    cards.forEach(function(card) {
      var product = {
        codigo:          card.dataset.codigo,
        nombre_producto: card.dataset.nombre,
        precio_venta:    parseFloat(card.dataset.precio) || 0
      };
      allZpl += ZebraLabels.buildLabelZPL(product, settings, 1, symbolCurrency) + '\n';
    });

    btnImprimir.disabled = true;
    var originalHtml = btnImprimir.innerHTML;
    btnImprimir.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Imprimiendo...';
    log('Conectando a impresora "' + ZEBRA_LABEL_PRINTER + '"...', 'info');

    zebraGetPrinter(ZEBRA_LABEL_PRINTER)
      .then(function(device) {
        if (!device) {
          log('No se pudo conectar a la impresora.', 'err');
          btnImprimir.disabled = false;
          btnImprimir.innerHTML = originalHtml;
          return;
        }
        return zebraSend(device, allZpl).then(function(ok) {
          btnImprimir.disabled = false;
          btnImprimir.innerHTML = originalHtml;
          if (ok) {
            log('✔ ' + cards.length + ' etiqueta(s) enviadas a la impresora.', 'ok');
          } else {
            log('Error al enviar las etiquetas.', 'err');
          }
        });
      })
      .catch(function(err) {
        btnImprimir.disabled = false;
        btnImprimir.innerHTML = originalHtml;
        log('Error: ' + (err && err.message ? err.message : err), 'err');
      });
  });
})();
</script>
