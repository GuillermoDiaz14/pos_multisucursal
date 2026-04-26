<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money" aria-hidden="true"></i> Ventas al contado
            <small>Historial filtrado</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <a class="btn btn-primary" href="<?php echo base_url(); ?>carrito/carrito">
                    <i class="fa fa-plus"></i> Nueva venta
                </a>
            </div>
        </div>

        <div class="row" style="margin-top:10px;">
            <div class="col-md-12">
                <?php $this->load->helper('form'); ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Ventas cobradas de inmediato</h3>
                        <div class="box-tools">
                            <form action="<?php echo base_url(); ?>carrito/ventas_lista_contado" method="POST">
                                <div class="input-group">
                                    <input type="text" name="searchText" class="form-control input-sm pull-right"
                                           style="width: 190px;" placeholder="Buscar por cliente o Nro"
                                           id="searchText" oninput="filtrarVentasContado()" />
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="tablaVentasContado">
                            <thead>
                                <tr>
                                    <th>Nro</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-right">Impuesto</th>
                                    <th class="text-right">Descuento</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php include('table_partial_contado.php'); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="box-footer clearfix" id="paginacionContado"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function filtrarVentasContado() {
    $.ajax({
        url: '<?php echo base_url(); ?>carrito/filterVentas_contado',
        type: 'POST',
        data: { searchText: document.getElementById('searchText').value },
        success: function (html) {
            $('#tablaVentasContado tbody').html(html);
            paginarContado(1);
        }
    });
}

var paginaActualContado = 1;
var filasPorPaginaContado = 10;

function paginarContado(pagina) {
    paginaActualContado = pagina;
    var filas = document.querySelectorAll('#tablaVentasContado tbody tr');
    var inicio = (pagina - 1) * filasPorPaginaContado;

    filas.forEach(function(fila, index) {
        fila.style.display = (index >= inicio && index < inicio + filasPorPaginaContado) ? '' : 'none';
    });

    var totalPaginas = Math.ceil(filas.length / filasPorPaginaContado);
    var html = '';

    for (var i = 1; i <= totalPaginas; i++) {
        html += '<button class="btn btn-sm ' + (i === pagina ? 'btn-primary' : 'btn-default') + '" onclick="paginarContado(' + i + ')" style="margin:2px">' + i + '</button>';
    }

    document.getElementById('paginacionContado').innerHTML = html;
}

document.addEventListener('DOMContentLoaded', function() {
    paginarContado(1);
});
</script>
