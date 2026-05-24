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

  function buildLabelZPL(product, s, qty, currencySymbol) {
    var DPM = 8;   // 203 DPI ≈ 8 dots/mm
    var GAP = 4;
    var Y_BASELINE_MM = 2;
    var BAR_CODE_GAP_MM = 0.3;

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

    var barX;
    if (isEan13) {
      var symLeft = pad + Math.round((innerW - totalMod * moduleW) / 2);
      barX = symLeft + 11 * moduleW;
    } else {
      barX = pad + Math.round((innerW - totalMod * moduleW) / 2);
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

    var zpl = ['^XA', '^CI28', '^PW' + W, '^LL' + pitch, '^LH0,0'];

    if (s.showName && product.nombre_producto) {
      var nm = String(product.nombre_producto).substring(0, 40);
      zpl.push('^FO' + pad + ',' + y + '^FB' + innerW + ',1,0,C,0^A0N,' + nameH + ',' + nameH + '^FH^FD' + zplFhEncode(nm) + '^FS');
      y += nameH + fixedGap;
    }

    if (isEan13) {
      zpl.push('^FO' + barX + ',' + y + '^BY' + moduleW + ',2,' + barH + '^BEN,' + barH + ',N,N^FD' + code + '^FS');
    } else {
      zpl.push('^FO' + barX + ',' + y + '^BY' + moduleW + ',2,' + barH + '^BCN,' + barH + ',N,N,N^FD' + code + '^FS');
    }
    y += barHeff + barCodeGap + fixedGap;

    if (s.showCodeText) {
      zpl.push('^FO' + pad + ',' + y + '^FB' + innerW + ',1,0,C,0^A0N,' + codeH + ',' + codeH + '^FD' + code + '^FS');
      y += codeH + fixedGap;
    }

    if (s.showPrice) {
      var sym = (currencySymbol == null) ? '' : String(currencySymbol);
      var priceStr = (sym ? sym + ' ' : '') + Number(product.precio_venta || 0).toFixed(2);
      zpl.push('^FO' + pad + ',' + y + '^FB' + innerW + ',1,0,C,0^A0N,' + priceH + ',' + priceH + '^FD' + priceStr + '^FS');
    }

    zpl.push('^PQ' + Math.max(1, qty || 1));
    zpl.push('^XZ');
    return zpl.join('\n');
  }

  global.ZebraLabels = {
    DEFAULT_SETTINGS: DEFAULT_SETTINGS,
    buildLabelZPL: buildLabelZPL,
    zplFhEncode: zplFhEncode
  };
})(window);
