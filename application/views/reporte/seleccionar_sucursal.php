<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-building-o" aria-hidden="true"></i> <?php echo $report['title']; ?>
        <small>Selecciona la sucursal a consultar</small>
      </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sucursal</h3>
                    </div>
                    <form action="<?php echo base_url() . $targetAction; ?>" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="id_sucursal">Sucursal</label>
                                <select class="form-control" id="id_sucursal" name="id_sucursal" required>
                                    <option value="">Selecciona una sucursal</option>
                                    <?php foreach ($sucursales as $sucursal) { ?>
                                    <option value="<?php echo $sucursal->id_sucursal; ?>"><?php echo $sucursal->nombre_sucursal; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <p class="help-block"><?php echo $report['description']; ?></p>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Continuar</button>
                            <a href="<?php echo base_url(); ?>reporte" class="btn btn-default">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
