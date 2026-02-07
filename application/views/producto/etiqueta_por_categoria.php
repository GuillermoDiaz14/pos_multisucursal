<?php
// Obtiene el símbolo de moneda desde la configuración (ej: $, MXN, etc.)
$simbolo_moneda = $configuracionInfo->simbolo_moneda;
?>

<style>
@media print { /* Todo lo de aquí SOLO aplica al imprimir */

@page {
    size: 4cm 1.6cm;   /* Tamaño físico de la etiqueta */
    margin: 0;        /* Sin márgenes */
}

/* Oculta absolutamente todo al imprimir */
body * {
    visibility: hidden;
}

/* Hace visible solo el contenido de cada etiqueta */
.etiqueta * {
    visibility: visible;
}

/* Estilo general de cada etiqueta */
.etiqueta {
    position: relative;
    page-break-after: always; /* Cada etiqueta en una página */
    text-align: center;

    /* Flexbox para centrar y distribuir contenido */
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;
    align-items: center; /* Centra horizontalmente */
}

/* Estilo del nombre del producto */
.nombre {
    margin-top: 0px;
    font-size: 11px;
}

/* Ajustes al SVG del código de barras */
svg {
    margin-top: 0;
    margin-bottom: 0;
}

/* Estilo del precio */
.precio {
    margin-bottom: 0px;
    font-weight: bold;
    font-size: 14px;
}

/* Quita márgenes automáticos de los párrafos */
.etiqueta p {
    margin: 0;
}

/* Elementos que NO se deben imprimir */
.no-print {
    display: none;
}

}
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

<!-- Select para filtrar por categoría -->
<select id="seleccionarCategoria" class="no-print">
<option value="">Todas las Categorías</option>
<?php foreach ($categorias as $categoria): ?>
<option value="<?php echo $categoria->id_categoria; ?>">
<?php echo $categoria->nombre_categoria; ?>
</option>
<?php endforeach; ?>
</select>

<!-- Botón para imprimir -->
<button id="imprimirEtiquetas" class="btn btn-primary no-print">
Imprimir Etiquetas
</button>

<!-- Genera una etiqueta por cada producto -->
<?php foreach ($productos as $producto): ?>
<div class="etiqueta" data-categoria="<?php echo $producto['categoria']; ?>">
    <!-- Nombre del producto -->
    <p class="nombre"><?php echo $producto['nombre_producto']; ?></p>

    <!-- Aquí se dibuja el código de barras -->
    <svg class="barcode-<?php echo $producto['id_producto']; ?>"></svg>

    <!-- Precio del producto -->
    <p class="precio"><?php echo $simbolo_moneda; ?> <?php echo number_format($producto['precio_venta'], 2); ?></p>
</div>
<?php endforeach; ?>

</div>
</section>
</div>

<!-- Librería que genera los códigos de barras -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3/dist/JsBarcode.all.min.js"></script>

<script>
<?php foreach ($productos as $producto): ?>
// Obtiene el SVG donde se dibuja el código de barras
const svg<?php echo $producto['id_producto']; ?> = document.querySelector('.barcode-<?php echo $producto['id_producto']; ?>');

// Código que se va a convertir en barras
const codigoBarras<?php echo $producto['id_producto']; ?> = "<?php echo $producto['codigo']; ?>";

// Genera el código de barras con configuración de tamaño
JsBarcode(svg<?php echo $producto['id_producto']; ?>, codigoBarras<?php echo $producto['id_producto']; ?>, {
    format: "CODE39",     // Tipo de código de barras
    width: 1,             // Grosor de cada barra
    height: 16,           // Altura del código
    fontSize: 9,          // Tamaño del texto inferior
    textMargin: 0,        // Espacio entre barras y texto
    displayValue: true,   // Muestra el número debajo
    margin: 0             // Sin márgenes
});
<?php endforeach; ?>
</script>

<script>
// Botón que manda a imprimir
const btnImprimir = document.getElementById('imprimirEtiquetas');
btnImprimir.addEventListener('click', function() {
    window.print();
});
</script>

<script>
// Filtro por categoría
const selectCategoria = document.getElementById('seleccionarCategoria');
const etiquetas = document.querySelectorAll('.etiqueta');

selectCategoria.addEventListener('change', function() {
    const categoriaSeleccionada = this.value;

    // Muestra todas primero
    etiquetas.forEach(etiqueta => {
        etiqueta.style.display = 'block';
    });

    // Oculta las que no sean de la categoría seleccionada
    if (categoriaSeleccionada) {
        etiquetas.forEach(etiqueta => {
            if (etiqueta.dataset.categoria !== categoriaSeleccionada) {
                etiqueta.style.display = 'none';
            }
        });
    }
});
</script>
