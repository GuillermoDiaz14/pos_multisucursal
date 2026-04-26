<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-calendar" aria-hidden="true"></i> Reportes
        <small>Centro unificado por roles y permisos</small>
      </h1>
    </section>

    <section class="content">
        <?php if (empty($accessibleReports)) { ?>
        <div class="alert alert-warning">
            No tienes reportes disponibles para tu rol actual.
        </div>
        <?php return; } ?>

        <?php foreach ($reportGroups as $category => $reports) { ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $category; ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <?php foreach ($reports as $report) { ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h4><?php echo $report['title']; ?></h4>
                                <p><?php echo $report['description']; ?></p>
                            </div>
                            <div class="icon">
                                <i class="fa <?php echo !empty($report['icon']) ? $report['icon'] : 'fa-bar-chart'; ?>"></i>
                            </div>
                            <a href="<?php echo $report['url']; ?>" class="small-box-footer">
                                Abrir reporte <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </section>
</div>
