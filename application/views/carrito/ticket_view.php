<?php
// ─── Extraer datos de la venta ────────────────────────────────────────────
foreach ($ventas as $venta) {
    $id_venta       = $venta->id_venta;
    $fecha_venta    = $venta->fecha_venta;
    $nombre_cliente = $venta->nombre_cliente;
    $total          = $venta->total;
    $descuento      = $venta->descuento      ?? 0;
    $monto_recibido = $venta->monto_recibido ?? 0;
    $cambio         = $venta->cambio         ?? 0;
}

// ─── Sanitizar para ZPL (^ y ~ son caracteres de control en ZPL) ─────────
function zpl_clean($s) {
    return str_replace(['^','~'], ['',''], strip_tags((string)$s));
}

// ─── Generar ZPL ─────────────────────────────────────────────────────────
// Zebra ZD421 · 203 DPI · 80 mm = 640 puntos de ancho
$pw  = 640;
$y   = 15;
$zpl = '';

// Encabezado centrado
$zpl .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,30,26^FDMI MARCA^FS\n"; $y += 36;
$zpl .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,20,17^FDTel: 722-000-0000^FS\n"; $y += 26;
$zpl .= "^FO0,{$y}^GB{$pw},3,2^FS\n"; $y += 9;

// Venta y fecha
$zpl .= "^FO5,{$y}^A0N,20,17^FDVenta: ".zpl_clean($id_venta)."^FS\n";
$zpl .= "^FO".($pw-250).",{$y}^A0N,20,17^FD".zpl_clean($fecha_venta)."^FS\n"; $y += 26;
$zpl .= "^FO5,{$y}^A0N,20,17^FDCliente: ".zpl_clean($nombre_cliente)."^FS\n"; $y += 26;
$zpl .= "^FO0,{$y}^GB{$pw},3,2^FS\n"; $y += 9;

// Encabezado de columnas
$zpl .= "^FO5,{$y}^A0N,18,15^FDProducto^FS\n";
$zpl .= "^FO300,{$y}^A0N,18,15^FDPrecio^FS\n";
$zpl .= "^FO420,{$y}^A0N,18,15^FDCant^FS\n";
$zpl .= "^FO520,{$y}^A0N,18,15^FDSub^FS\n"; $y += 22;
$zpl .= "^FO0,{$y}^GB{$pw},2,1^FS\n"; $y += 7;

// Líneas de productos
foreach ($detalles as $det) {
    $nombre = zpl_clean($det->nombre_producto);
    if (mb_strlen($nombre) > 18) $nombre = mb_substr($nombre, 0, 17).'.';
    $precio = '$'.number_format($det->precio_individual, 2);
    $cant   = zpl_clean($det->cantidad);
    $sub    = '$'.number_format($det->sub_total, 2);

    $zpl .= "^FO5,{$y}^A0N,18,15^FD{$nombre}^FS\n";
    $zpl .= "^FO300,{$y}^A0N,18,15^FD{$precio}^FS\n";
    $zpl .= "^FO420,{$y}^A0N,18,15^FD{$cant}^FS\n";
    $zpl .= "^FO520,{$y}^A0N,18,15^FD{$sub}^FS\n";
    $y += 23;
}
$zpl .= "^FO0,{$y}^GB{$pw},3,2^FS\n"; $y += 9;

// Descuento
if ($descuento > 0) {
    $zpl .= "^FO5,{$y}^A0N,20,17^FDDescuento:^FS\n";
    $zpl .= "^FO440,{$y}^A0N,20,17^FD$".number_format($descuento,2)."^FS\n"; $y += 26;
}

// Recibido / Cambio
if ($monto_recibido > 0) {
    $zpl .= "^FO5,{$y}^A0N,20,17^FDRecibido:^FS\n";
    $zpl .= "^FO440,{$y}^A0N,20,17^FD$".number_format($monto_recibido,2)."^FS\n"; $y += 26;
    $zpl .= "^FO5,{$y}^A0N,20,17^FDCambio:^FS\n";
    $zpl .= "^FO440,{$y}^A0N,20,17^FD$".number_format($cambio,2)."^FS\n"; $y += 26;
}

// Total
$zpl .= "^FO0,{$y}^FB{$pw},1,0,R,0^A0N,28,24^FDTOTAL: $".number_format($total,2)."^FS\n"; $y += 36;
$zpl .= "^FO0,{$y}^GB{$pw},3,2^FS\n"; $y += 9;

// Cierre
$zpl .= "^FO0,{$y}^FB{$pw},1,0,C,0^A0N,20,17^FDGracias por su compra!^FS\n"; $y += 30;
$ll   = $y + 20;
$zpl  = "^XA\n^PW{$pw}\n^LL{$ll}\n^MNN\n^MMT\n" . $zpl . "^XZ";
?>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Courier New', monospace; font-size: 10px; }
.ticket-preview {
    width: 80mm; margin: 20px auto; border: 1px dashed #ccc;
    padding: 6px; background: #fff; font-size: 10px;
}
.center { text-align: center; }
.row-between { display: flex; justify-content: space-between; }
hr { border: none; border-top: 1px dashed #000; margin: 4px 0; }
table { width: 100%; border-collapse: collapse; font-size: 9px; }
th, td { padding: 1px 0; text-align: left; }
th { border-bottom: 1px solid #000; font-weight: bold; }
.total { font-size: 12px; font-weight: bold; text-align: right; margin-top: 3px; }
.btn-print {
    display: block; width: 80mm; margin: 12px auto;
    padding: 10px; background: #28a745; color: #fff;
    border: none; border-radius: 4px; font-size: 14px; cursor: pointer;
}
.btn-print:hover { background: #1e7e34; }
#zpl-status { width: 80mm; margin: 6px auto; font-size: 11px; text-align: center; color: #555; }
</style>

<!-- Vista previa del ticket -->
<div class="ticket-preview">
    <div class="center"><strong>MI MARCA</strong><br>Tel: 722-000-0000</div>
    <hr>
    <div class="row-between">
        <span>Venta: <?php echo $id_venta; ?></span>
        <span><?php echo fmt_fecha($fecha_venta); ?></span>
    </div>
    <div>Cliente: <?php echo $nombre_cliente; ?></div>
    <hr>
    <table>
        <tr><th>Producto</th><th>Precio</th><th>Cant</th><th>Sub</th></tr>
        <?php foreach ($detalles as $det): ?>
        <tr>
            <td><?php echo $det->nombre_producto; ?></td>
            <td>$<?php echo number_format($det->precio_individual,2); ?></td>
            <td><?php echo $det->cantidad; ?></td>
            <td>$<?php echo number_format($det->sub_total,2); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <hr>
    <?php if ($descuento > 0): ?>
    <div class="row-between"><span>Descuento:</span><span>$<?php echo number_format($descuento,2); ?></span></div>
    <?php endif; ?>
    <?php if ($monto_recibido > 0): ?>
    <div class="row-between"><span>Recibido:</span><span>$<?php echo number_format($monto_recibido,2); ?></span></div>
    <div class="row-between"><span>Cambio:</span><span>$<?php echo number_format($cambio,2); ?></span></div>
    <?php endif; ?>
    <div class="total">TOTAL: $<?php echo number_format($total,2); ?></div>
    <hr>
    <div class="center">¡Gracias por su compra!</div>
</div>

<button class="btn-print" id="btnImprimir">&#x1F5A8; Imprimir Ticket (Zebra ZPL)</button>
<div id="zpl-status"></div>

<!-- Zebra Browser Print SDK -->
<script src="http://localhost:9101/socket.min.js"></script>
<script src="http://localhost:9101/BrowserPrint-3.0.216.min.js"></script>
<script>
var zplData = <?php echo json_encode($zpl); ?>;

document.getElementById('btnImprimir').addEventListener('click', function () {
    var status = document.getElementById('zpl-status');
    status.style.color = '#555';
    status.textContent = 'Conectando con impresora...';

    BrowserPrint.getDefaultDevice('printer', function (device) {
        if (!device || !device.uid) {
            status.style.color = '#c00';
            status.textContent = 'No se encontró impresora por defecto. Verifica Zebra Browser Print.';
            return;
        }
        status.textContent = 'Enviando a: ' + (device.name || device.uid) + '...';
        device.send(zplData,
            function () {
                status.style.color = '#28a745';
                status.textContent = '✔ Ticket enviado correctamente.';
            },
            function (err) {
                status.style.color = '#c00';
                status.textContent = 'Error al imprimir: ' + err;
            }
        );
    }, function () {
        status.style.color = '#c00';
        status.textContent = 'Zebra Browser Print no disponible. Instala y ejecuta el servicio Zebra.';
    });
});
</script>
