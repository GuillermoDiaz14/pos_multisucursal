
<style>
.label-en-proceso { background-color: #f0ad4e; }
.label-entregado  { background-color: #5cb85c; }
.label-cancelado  { background-color: #d9534f; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-tags" aria-hidden="true"></i> Apartados
            <small>Gestión de pagos a plazos</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <a class="btn btn-primary" href="<?php echo base_url(); ?>carrito/carrito">
                    <i class="fa fa-plus"></i> Nuevo apartado
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
                        <h3 class="box-title">Apartados registrados</h3>
                        <div class="box-tools">
                            <form action="<?php echo base_url(); ?>carrito/apartado_lista" method="POST">
                                <div class="input-group">
                                    <input type="text" name="searchText" class="form-control input-sm pull-right"
                                           style="width: 170px;" placeholder="Buscar por cliente o Nro"
                                           id="searchText" oninput="filtrarTabla()" />
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="tablaApartados">
                            <thead>
                                <tr>
                                    <th>Nro</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Pagado</th>
                                    <th>Restante</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php include('table_partial_apartado.php'); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="box-footer clearfix" id="paginacion"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function filtrarTabla() {
    $.ajax({
        url: '<?php echo base_url(); ?>carrito/filterVentas_apartado',
        type: 'POST',
        data: { searchText: document.getElementById('searchText').value },
        success: function (html) { $('#tablaApartados tbody').html(html); paginar(1); }
    });
}

var paginaActual = 1;
var filasPorPagina = 10;

function paginar(pagina) {
    paginaActual = pagina;
    var filas = document.querySelectorAll('#tablaApartados tbody tr');
    var total = filas.length;
    var inicio = (pagina - 1) * filasPorPagina;
    filas.forEach(function(f, i) {
        f.style.display = (i >= inicio && i < inicio + filasPorPagina) ? '' : 'none';
    });
    var paginas = Math.ceil(total / filasPorPagina);
    var html = '';
    for (var i = 1; i <= paginas; i++) {
        html += '<button class="btn btn-sm ' + (i === pagina ? 'btn-primary' : 'btn-default') + '" onclick="paginar(' + i + ')" style="margin:2px">' + i + '</button>';
    }
    document.getElementById('paginacion').innerHTML = html;
}

document.addEventListener('DOMContentLoaded', function() { paginar(1); });
</script>
