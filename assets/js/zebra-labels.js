/**
 * Generador de ZPL para etiquetas Zebra (203 DPI).
 *
 * Expone window.ZebraLabels con:
 *   - DEFAULT_SETTINGS  → configuración 39×16 mm por defecto
 *   - buildLabelZPL(product, settings, qty, currencySymbol)
 *   - zplFhEncode(str)  → encoding ^FH para caracteres no-ASCII
 *
 * `product` = { codigo, nombre_producto, precio_venta }
 * `settings` = ver DEFAULT_SETTINGS
 */
(function (global) {
  'use strict';

  var DPM = 8;   // 203 DPI ≈ 8 dots/mm
  var GAP = 4;
  var Y_BASELINE_MM = 2;
  var BAR_CODE_GAP_MM = 0.3;

  var DEFAULT_SETTINGS = {
    width: 39, height: 16, gap: 3, padding: 1, yOffset: 0, barcodeHeight: 6.5,
    fontName: 1.8, fontPrice: 2.3, fontCode: 1.5,
    showName: true, showPrice: true, showCodeText: true
  };

  function zplFhEncode(str) {
    str = String(str || '').replace(/[\^~]/g, '');
    var result = '';
    for (var i = 0; i < str.length; ) {
      var code = str.codePointAt(i);
      if (code <= 127) {
        result += str[i];
        i++;
      } else {
        result += encodeURIComponent(String.fromCodePoint(code)).replace(/%/g, '_');
        i += code > 0xFFFF ? 2 : 1;
      }
    }
    return result;
  }

  function mmToPx(mm) {
    return Math.max(1, Math.round(mm * 3.78));
  }

  function dotsToMm(dots) {
    return dots / DPM;
  }

  function computeLabelLayout(product, settings) {
    var s = Object.assign({}, DEFAULT_SETTINGS, settings || {});

    var W = Math.round(s.width * DPM);
    var H = Math.round(s.height * DPM);
    var gapDots = Math.round((s.gap || 0) * DPM);
    var pitch = H + gapDots;
    var pad = Math.round(s.padding * DPM);
    var barH = Math.round(s.barcodeHeight * DPM);
    var nameH  = Math.max(8, Math.round(s.fontName  * DPM));
    var priceH = Math.max(8, Math.round(s.fontPrice * DPM));
    var codeH  = Math.max(6, Math.round(s.fontCode  * DPM));
    var innerW = W - 2 * pad;

    var code = String(product.codigo || '');
    var isEan13 = /^\d{13}$/.test(code);

    var totalMod = isEan13 ? 113 : (11 * code.length + 35);
    var moduleW = Math.max(1, Math.floor(innerW / totalMod));
    var barcodeLeft = pad + Math.round((innerW - totalMod * moduleW) / 2);

    var barX;
    if (isEan13) {
      var symLeft = barcodeLeft;
      barX = symLeft + 11 * moduleW;
    } else {
      barX = barcodeLeft;
    }
    barX = Math.max(pad, barX);

    var EAN_GUARD = isEan13 ? 13 : 0;
    var barHeff = barH + EAN_GUARD;
    var barCodeGap = s.showCodeText ? Math.round(BAR_CODE_GAP_MM * DPM) : 0;

    var elements = [];
    if (s.showName && product.nombre_producto) elements.push(nameH);
    elements.push(barHeff + barCodeGap);
    if (s.showCodeText) elements.push(codeH);
    if (s.showPrice)    elements.push(priceH);

    var available = Math.max(0, H - 2 * pad);
    var numGaps = elements.length - 1;
    var fixedH = 0;
    for (var i = 0; i < elements.length; i++) fixedH += elements[i];

    // Nivel 1: gap dinámico
    var fixedGap = (numGaps > 0 && available > fixedH)
      ? Math.min(GAP, Math.floor((available - fixedH) / numGaps))
      : 0;
    var contentH = fixedH + fixedGap * numGaps;

    // Nivel 2: escalar todos los elementos si aún desborda
    if (contentH > available && available > 0) {
      var scale = available / fixedH;
      nameH   = Math.max(6,  Math.round(nameH  * scale));
      barH    = Math.max(12, Math.round(barH   * scale));
      priceH  = Math.max(6,  Math.round(priceH * scale));
      codeH   = Math.max(4,  Math.round(codeH  * scale));
      barHeff = barH + EAN_GUARD;
      fixedH = 0;
      if (s.showName && product.nombre_producto) fixedH += nameH;
      fixedH += barHeff + barCodeGap;
      if (s.showCodeText) fixedH += codeH;
      if (s.showPrice)    fixedH += priceH;
      fixedGap = (numGaps > 0 && available > fixedH)
        ? Math.floor((available - fixedH) / numGaps)
        : 0;
      contentH = fixedH + fixedGap * numGaps;
    }
    if (contentH > available) contentH = available;

    var yOffDots = Math.round(((s.yOffset || 0) + Y_BASELINE_MM) * DPM);
    var y = pad + Math.max(0, Math.round((available - contentH) / 2)) + yOffDots;
    if (y < 0) y = 0;

    var fields = [];
    var cursorY = y;

    if (s.showName && product.nombre_producto) {
      fields.push({
        type: 'name',
        text: String(product.nombre_producto).substring(0, 40),
        top: cursorY,
        left: pad,
        width: innerW,
        height: nameH,
        fontHeight: nameH
      });
      cursorY += nameH + fixedGap;
    }

    fields.push({
      type: 'barcode',
      code: code,
      format: isEan13 ? 'EAN13' : 'CODE128',
      top: cursorY,
      left: barcodeLeft,
      width: totalMod * moduleW,
      height: barHeff,
      barcodeHeight: barH,
      moduleWidth: moduleW,
      maxWidth: innerW
    });
    cursorY += barHeff + barCodeGap + fixedGap;

    if (s.showCodeText) {
      fields.push({
        type: 'code',
        text: code,
        top: cursorY,
        left: pad,
        width: innerW,
        height: codeH,
        fontHeight: codeH
      });
      cursorY += codeH + fixedGap;
    }

    if (s.showPrice) {
      fields.push({
        type: 'price',
        top: cursorY,
        left: pad,
        width: innerW,
        height: priceH,
        fontHeight: priceH
      });
    }

    return {
      settings: s,
      widthDots: W,
      heightDots: H,
      pitchDots: pitch,
      paddingDots: pad,
      innerWidthDots: innerW,
      barcodeX: barX,
      barcodeLeft: barcodeLeft,
      barcodeGapDots: barCodeGap,
      fields: fields
    };
  }

  function buildPreviewNode(product, settings, currencySymbol, options) {
    var layout = computeLabelLayout(product, settings);
    var s = layout.settings;
    var opts = options || {};
    var sym = (currencySymbol == null) ? '' : String(currencySymbol);

    var label = document.createElement('div');
    label.className = opts.className || 'label-card';
    label.style.position = 'relative';
    label.style.boxSizing = 'border-box';
    label.style.width = s.width + 'mm';
    label.style.height = s.height + 'mm';
    label.style.background = '#fff';
    label.style.overflow = 'hidden';
    label.style.border = opts.border === false ? '0' : (opts.border || '1px solid #bbb');

    layout.fields.forEach(function(field) {
      var node;
      if (field.type === 'barcode') {
        node = document.createElement('div');
        node.className = 'label-barcode';
        node.style.position = 'absolute';
        node.style.left = dotsToMm(field.left) + 'mm';
        node.style.top = dotsToMm(field.top) + 'mm';
        node.style.width = dotsToMm(field.width) + 'mm';
        node.style.height = dotsToMm(field.height) + 'mm';
        node.style.display = 'flex';
        node.style.justifyContent = 'center';
        node.style.alignItems = 'flex-start';
        node.style.overflow = 'hidden';

        var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('class', 'js-label-barcode');
        svg.setAttribute('data-code', field.code);
        svg.setAttribute('data-format', field.format);
        svg.setAttribute('data-module-width-mm', String(dotsToMm(field.moduleWidth)));
        svg.setAttribute('data-bar-height-mm', String(dotsToMm(field.barcodeHeight)));
        svg.setAttribute('data-max-width-mm', String(dotsToMm(field.width)));
        svg.style.display = 'block';
        svg.style.width = dotsToMm(field.width) + 'mm';
        svg.style.maxWidth = dotsToMm(field.width) + 'mm';
        svg.style.margin = '0 auto';

        node.appendChild(svg);
      } else {
        node = document.createElement('div');
        node.className = field.type === 'name' ? 'label-name' : (field.type === 'price' ? 'label-price' : 'label-code');
        node.style.position = 'absolute';
        node.style.left = dotsToMm(field.left) + 'mm';
        node.style.top = dotsToMm(field.top) + 'mm';
        node.style.width = dotsToMm(field.width) + 'mm';
        node.style.height = dotsToMm(field.height) + 'mm';
        node.style.fontSize = dotsToMm(field.fontHeight) + 'mm';
        node.style.lineHeight = '1';
        node.style.textAlign = 'center';
        node.style.whiteSpace = 'nowrap';
        node.style.overflow = 'hidden';
        node.style.textOverflow = 'clip';
        if (field.type === 'name' || field.type === 'price') node.style.fontWeight = '700';
        if (field.type === 'code') node.style.color = '#455a64';
        node.textContent = field.type === 'price'
          ? ((sym ? sym + ' ' : '') + Number(product.precio_venta || 0).toFixed(2))
          : field.text;
      }
      label.appendChild(node);
    });

    return label;
  }

  function renderPreviewBarcodes(container) {
    return new Promise(function(resolve) {
      var svgs = Array.prototype.slice.call(container.querySelectorAll('.js-label-barcode'));
      if (!svgs.length || typeof global.JsBarcode !== 'function') {
        requestAnimationFrame(resolve);
        return;
      }

      svgs.forEach(function(svg) {
        var code = svg.getAttribute('data-code') || '';
        var format = svg.getAttribute('data-format') || (/^\d{13}$/.test(code) ? 'EAN13' : 'CODE128');
        var moduleWidthMm = parseFloat(svg.getAttribute('data-module-width-mm')) || 0.125;
        var barHeightMm = parseFloat(svg.getAttribute('data-bar-height-mm')) || DEFAULT_SETTINGS.barcodeHeight;
        var maxWidthMm = parseFloat(svg.getAttribute('data-max-width-mm')) || DEFAULT_SETTINGS.width;

        try {
          global.JsBarcode(svg, code, {
            format: format,
            width: Math.max(1, mmToPx(moduleWidthMm)),
            height: mmToPx(barHeightMm),
            margin: 0,
            displayValue: false
          });
        } catch (e) {
          return;
        }

        svg.removeAttribute('height');
        svg.style.height = barHeightMm + 'mm';
        svg.style.width = 'auto';
        svg.style.maxWidth = maxWidthMm + 'mm';
        svg.style.display = 'block';
        svg.style.margin = '0 auto';
      });

      requestAnimationFrame(function() {
        setTimeout(resolve, 120);
      });
    });
  }

  function buildLabelZPL(product, s, qty, currencySymbol) {
    var layout = computeLabelLayout(product, s);
    var settings = layout.settings;

    var zpl = ['^XA', '^CI28', '^PW' + layout.widthDots, '^LL' + layout.pitchDots, '^LH0,0'];

    layout.fields.forEach(function(field) {
      if (field.type === 'name') {
        zpl.push('^FO' + field.left + ',' + field.top
          + '^FB' + field.width + ',1,0,C,0^A0N,' + field.fontHeight + ',' + field.fontHeight
          + '^FH^FD' + zplFhEncode(field.text) + '^FS');
      } else if (field.type === 'barcode') {
        if (field.format === 'EAN13') {
          zpl.push('^FO' + layout.barcodeX + ',' + field.top
            + '^BY' + field.moduleWidth + ',2,' + field.barcodeHeight
            + '^BEN,' + field.barcodeHeight + ',N,N^FD' + field.code + '^FS');
        } else {
          zpl.push('^FO' + layout.barcodeX + ',' + field.top
            + '^BY' + field.moduleWidth + ',2,' + field.barcodeHeight
            + '^BCN,' + field.barcodeHeight + ',N,N,N^FD' + field.code + '^FS');
        }
      } else if (field.type === 'code') {
        zpl.push('^FO' + field.left + ',' + field.top
          + '^FB' + field.width + ',1,0,C,0^A0N,' + field.fontHeight + ',' + field.fontHeight
          + '^FD' + field.text + '^FS');
      } else if (field.type === 'price') {
        var sym = (currencySymbol == null) ? '' : String(currencySymbol);
        var priceStr = (sym ? sym + ' ' : '') + Number(product.precio_venta || 0).toFixed(2);
        zpl.push('^FO' + field.left + ',' + field.top
          + '^FB' + field.width + ',1,0,C,0^A0N,' + field.fontHeight + ',' + field.fontHeight
          + '^FD' + priceStr + '^FS');
      }
    });

    zpl.push('^PQ' + Math.max(1, qty || 1));
    zpl.push('^XZ');
    return zpl.join('\n');
  }

  global.ZebraLabels = {
    DEFAULT_SETTINGS: DEFAULT_SETTINGS,
    computeLabelLayout: computeLabelLayout,
    buildPreviewNode: buildPreviewNode,
    renderPreviewBarcodes: renderPreviewBarcodes,
    buildLabelZPL: buildLabelZPL,
    zplFhEncode: zplFhEncode
  };
})(window);
