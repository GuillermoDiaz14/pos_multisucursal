<?php

$simbolo_moneda= $configuracionInfo->simbolo_moneda;
?>

<style>
@media print {

@page {
    size: 4cm 1.6cm;
    margin: 0;
}

body * {
    visibility: hidden;
}


.etiqueta * {
    visibility: visible;
}

.etiqueta {
    position: relative;
    page-break-after: always;
    text-align: center;
        display: flex;
    flex-direction: column;
    justify-content: space-evenly;
    align-items: center;     /* centra horizontal */
}

.nombre {
    margin-top: 0px;
    font-size: 15px;
}

svg {
    margin-top: 0;
    margin-bottom: 0;
}

.precio {
    margin-bottom: 0px;
    font-weight: bold;
    font-size: 21px;
}

.etiqueta p {
    margin: 0;
}

.no-print {
    display: none;
}

}
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Etiqueta
        <small>Etiqueta</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements --|>
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"> Etiquetas</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <button id="imprimirEtiquetas"   class="btn btn-primary no-print">Imprimir Etiquetas</button>


                    <h1>Etiquetas de Productos</h1>
    <?php foreach ($productos as $producto): ?>
       <div class="etiqueta" data-categoria="<?php echo $producto['categoria']; ?>">
    <p class="nombre"><?php echo $producto['nombre_producto']; ?></p>
    <svg class="barcode-<?php echo $producto['id_producto']; ?>"></svg>
    <p class="precio"><?php echo $simbolo_moneda; ?> <?php echo number_format($producto['precio_venta'], 2); ?></p>
</div>
                  </center>
    <?php endforeach; ?>




                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>    
    </section>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3/dist/JsBarcode.all.min.js"></script>
 <!-- JavaScript para generar los códigos de barras -->
 <script>
<?php foreach ($productos as $producto): ?>
const svg<?php echo $producto['id_producto']; ?> = document.querySelector('.barcode-<?php echo $producto['id_producto']; ?>');
const codigoBarras<?php echo $producto['id_producto']; ?> = "<?php echo $producto['codigo']; ?>";

JsBarcode(svg<?php echo $producto['id_producto']; ?>, codigoBarras<?php echo $producto['id_producto']; ?>, {
    format: "CODE39",
    width: 1.5,
    height: 26,
    fontSize: 13,
    textMargin: 0,
    displayValue: true,
    margin: 0
});
<?php endforeach; ?>
</script>


<script>
    // JavaScript para generar los códigos de barras (código previo)
    
    // Agrega un manejador de eventos al botón de impresión
    const btnImprimir = document.getElementById('imprimirEtiquetas');
    btnImprimir.addEventListener('click', function() {
        window.print(); // Inicia la impresión al hacer clic en el botón
    });
</script>
