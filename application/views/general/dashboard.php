<?php
$sucursal = !empty($sucursales) ? $sucursales[0] : null;
$nombreSucursal = $sucursal && !empty($sucursal->nombre_sucursal) ? $sucursal->nombre_sucursal : 'Sucursal actual';

$summary = isset($dashboardSummary) ? $dashboardSummary : array();
$ventas = isset($summary['ventas']) ? $summary['ventas'] : array();
$compras = isset($summary['compras']) ? $summary['compras'] : array();
$inventario = isset($summary['inventario']) ? $summary['inventario'] : array();
$clientes = isset($summary['clientes']) ? (int) $summary['clientes'] : 0;
$caja = isset($summary['caja']) ? $summary['caja'] : array();
$rentabilidad = isset($summary['rentabilidad']) ? $summary['rentabilidad'] : array();

if (!function_exists('dashboard_money')) {
    function dashboard_money($value)
    {
        return '$' . number_format((float) $value, 2);
    }
}

if (!function_exists('dashboard_int')) {
    function dashboard_int($value)
    {
        return number_format((float) $value, 0);
    }
}

if (!function_exists('dashboard_change')) {
    function dashboard_change($current, $previous)
    {
        $current = (float) $current;
        $previous = (float) $previous;

        if ($previous == 0.0) {
            if ($current == 0.0) {
                return array('value' => 0, 'label' => 'Sin cambio', 'class' => 'is-neutral');
            }

            return array('value' => 100, 'label' => 'Nuevo movimiento', 'class' => 'is-positive');
        }

        $change = (($current - $previous) / abs($previous)) * 100;
        if ($change > 0) {
            return array('value' => $change, 'label' => 'Arriba vs periodo anterior', 'class' => 'is-positive');
        }

        if ($change < 0) {
            return array('value' => abs($change), 'label' => 'Abajo vs periodo anterior', 'class' => 'is-negative');
        }

        return array('value' => 0, 'label' => 'Sin cambio', 'class' => 'is-neutral');
    }
}

$ventasHoy = isset($ventas['total_hoy']) ? (float) $ventas['total_hoy'] : 0;
$ventasAyer = isset($ventas['total_ayer']) ? (float) $ventas['total_ayer'] : 0;
$ventasMes = isset($ventas['total_mes']) ? (float) $ventas['total_mes'] : 0;
$ventasMesAnterior = isset($ventas['total_mes_anterior']) ? (float) $ventas['total_mes_anterior'] : 0;
$ventasMesCantidad = isset($ventas['ventas_mes']) ? (int) $ventas['ventas_mes'] : 0;
$ticketPromedio = $ventasMesCantidad > 0 ? $ventasMes / $ventasMesCantidad : 0;
$comprasMes = isset($compras['total_compras_mes']) ? (float) $compras['total_compras_mes'] : 0;
$comprasMesAnterior = isset($compras['total_compras_mes_anterior']) ? (float) $compras['total_compras_mes_anterior'] : 0;
$utilidadMes = isset($rentabilidad['utilidad_mes']) ? (float) $rentabilidad['utilidad_mes'] : 0;
$margenMes = $ventasMes > 0 ? ($utilidadMes / $ventasMes) * 100 : 0;
$valorInventario = isset($inventario['valor_inventario_costo']) ? (float) $inventario['valor_inventario_costo'] : 0;
$valorInventarioVenta = isset($inventario['valor_inventario_venta']) ? (float) $inventario['valor_inventario_venta'] : 0;
$unidadesInventario = isset($inventario['unidades_inventario']) ? (int) $inventario['unidades_inventario'] : 0;
$productosConStock = isset($inventario['productos_con_stock']) ? (int) $inventario['productos_con_stock'] : 0;
$productosStockBajo = isset($inventario['productos_stock_bajo']) ? (int) $inventario['productos_stock_bajo'] : 0;
$saldoCreditoMes = isset($ventas['saldo_credito_mes']) ? (float) $ventas['saldo_credito_mes'] : 0;
$ventasCreditoMes = isset($ventas['ventas_credito_mes']) ? (int) $ventas['ventas_credito_mes'] : 0;
$cajaSaldo = !empty($caja['saldo']) ? (float) $caja['saldo'] : 0;
$cajaEstado = !empty($caja['estado']) ? $caja['estado'] : 'sin registro';

$ventasHoyChange = dashboard_change($ventasHoy, $ventasAyer);
$ventasMesChange = dashboard_change($ventasMes, $ventasMesAnterior);
$comprasMesChange = dashboard_change($comprasMes, $comprasMesAnterior);

$trendMap = array();
if (!empty($salesTrend)) {
    foreach ($salesTrend as $row) {
        $trendMap[$row['fecha_venta']] = array(
            'ventas' => (int) $row['ventas'],
            'total' => (float) $row['total']
        );
    }
}

$trendLabels = array();
$trendTotals = array();
$trendTickets = array();
$trendCursor = new DateTime(date('Y-m-d', strtotime('-13 days')));
$trendEnd = new DateTime(date('Y-m-d'));
while ($trendCursor <= $trendEnd) {
    $key = $trendCursor->format('Y-m-d');
    $trendLabels[] = $trendCursor->format('d M');
    $trendTotals[] = isset($trendMap[$key]) ? $trendMap[$key]['total'] : 0;
    $trendTickets[] = isset($trendMap[$key]) ? $trendMap[$key]['ventas'] : 0;
    $trendCursor->modify('+1 day');
}

$monthlySalesMap = array();
$monthlyPurchaseMap = array();
if (!empty($monthlyComparison['ventas'])) {
    foreach ($monthlyComparison['ventas'] as $row) {
        $monthlySalesMap[$row['periodo']] = (float) $row['total'];
    }
}
if (!empty($monthlyComparison['compras'])) {
    foreach ($monthlyComparison['compras'] as $row) {
        $monthlyPurchaseMap[$row['periodo']] = (float) $row['total'];
    }
}

$monthlyLabels = array();
$monthlySales = array();
$monthlyPurchases = array();
$monthNames = array(
    '01' => 'Ene',
    '02' => 'Feb',
    '03' => 'Mar',
    '04' => 'Abr',
    '05' => 'May',
    '06' => 'Jun',
    '07' => 'Jul',
    '08' => 'Ago',
    '09' => 'Sep',
    '10' => 'Oct',
    '11' => 'Nov',
    '12' => 'Dic'
);
$monthlyCursor = new DateTime(date('Y-m-01', strtotime('-5 months')));
$monthlyEnd = new DateTime(date('Y-m-01'));
while ($monthlyCursor <= $monthlyEnd) {
    $periodKey = $monthlyCursor->format('Y-m');
    $monthNumber = $monthlyCursor->format('m');
    $monthlyLabels[] = $monthNames[$monthNumber] . ' ' . $monthlyCursor->format('Y');
    $monthlySales[] = isset($monthlySalesMap[$periodKey]) ? $monthlySalesMap[$periodKey] : 0;
    $monthlyPurchases[] = isset($monthlyPurchaseMap[$periodKey]) ? $monthlyPurchaseMap[$periodKey] : 0;
    $monthlyCursor->modify('+1 month');
}

$paymentLabels = array();
$paymentTotals = array();
if (!empty($paymentDistribution)) {
    foreach ($paymentDistribution as $row) {
        $paymentLabels[] = $row['tipo_pago'];
        $paymentTotals[] = (float) $row['total'];
    }
}

$periodLabel = '';
if (!empty($dashboardPeriods['month_start']) && !empty($dashboardPeriods['month_end'])) {
    $periodLabel = date('d M', strtotime($dashboardPeriods['month_start'])) . ' al ' . date('d M Y', strtotime($dashboardPeriods['month_end']));
}
?>

<div class="content-wrapper dashboard-owner-view">
    <section class="content-header">
        <h1>
            <i class="fa fa-line-chart" aria-hidden="true"></i> Panel principal
            <small>Estado actual de <?php echo html_escape($nombreSucursal); ?></small>
        </h1>
    </section>

    <section class="content">
        <div class="dashboard-hero box">
            <div class="dashboard-hero__main">
                <span class="dashboard-chip">Sucursal activa</span>
                <h2><?php echo html_escape($nombreSucursal); ?></h2>
                <p>
                    Vista ejecutiva con ventas, compras, caja, inventario y alertas del negocio.
                    Todo se actualiza con la información real registrada en la base de datos.
                </p>
            </div>
            <div class="dashboard-hero__meta">
                <div class="dashboard-mini-stat">
                    <span>Periodo del mes</span>
                    <strong><?php echo html_escape($periodLabel); ?></strong>
                </div>
                <div class="dashboard-mini-stat">
                    <span>Caja actual</span>
                    <strong class="<?php echo $cajaEstado === 'abierto' ? 'text-green' : 'text-muted'; ?>">
                        <?php echo ucfirst(html_escape($cajaEstado)); ?>
                    </strong>
                </div>
                <div class="dashboard-mini-stat">
                    <span>Saldo en caja</span>
                    <strong><?php echo dashboard_money($cajaSaldo); ?></strong>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="owner-kpi">
                    <div class="owner-kpi__icon bg-green-soft">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="owner-kpi__content">
                        <span class="owner-kpi__label">Ventas de hoy</span>
                        <strong><?php echo dashboard_money($ventasHoy); ?></strong>
                        <p><?php echo dashboard_int(isset($ventas['ventas_hoy']) ? $ventas['ventas_hoy'] : 0); ?> tickets registrados hoy</p>
                        <span class="owner-kpi__delta <?php echo $ventasHoyChange['class']; ?>">
                            <?php echo number_format($ventasHoyChange['value'], 1); ?>% · <?php echo $ventasHoyChange['label']; ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="owner-kpi">
                    <div class="owner-kpi__icon bg-blue-soft">
                        <i class="fa fa-bar-chart"></i>
                    </div>
                    <div class="owner-kpi__content">
                        <span class="owner-kpi__label">Ventas del mes</span>
                        <strong><?php echo dashboard_money($ventasMes); ?></strong>
                        <p><?php echo dashboard_int($ventasMesCantidad); ?> ventas en el mes</p>
                        <span class="owner-kpi__delta <?php echo $ventasMesChange['class']; ?>">
                            <?php echo number_format($ventasMesChange['value'], 1); ?>% · <?php echo $ventasMesChange['label']; ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="owner-kpi">
                    <div class="owner-kpi__icon bg-orange-soft">
                        <i class="fa fa-ticket"></i>
                    </div>
                    <div class="owner-kpi__content">
                        <span class="owner-kpi__label">Ticket promedio</span>
                        <strong><?php echo dashboard_money($ticketPromedio); ?></strong>
                        <p>Promedio por venta del mes</p>
                        <span class="owner-kpi__delta is-neutral">
                            <?php echo number_format($margenMes, 1); ?>% de margen estimado
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="owner-kpi">
                    <div class="owner-kpi__icon bg-purple-soft">
                        <i class="fa fa-archive"></i>
                    </div>
                    <div class="owner-kpi__content">
                        <span class="owner-kpi__label">Valor del inventario</span>
                        <strong><?php echo dashboard_money($valorInventario); ?></strong>
                        <p><?php echo dashboard_int($unidadesInventario); ?> unidades en existencia</p>
                        <span class="owner-kpi__delta is-neutral">
                            Potencial de venta: <?php echo dashboard_money($valorInventarioVenta); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="box dashboard-card">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tendencia de ventas de los últimos 14 días</h3>
                        <span class="dashboard-card__hint">Importe diario y cantidad de tickets</span>
                    </div>
                    <div class="box-body">
                        <canvas id="salesTrendChart" height="130"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="box dashboard-card dashboard-card--compact">
                    <div class="box-header with-border">
                        <h3 class="box-title">Salud del negocio</h3>
                        <span class="dashboard-card__hint">Indicadores clave del mes</span>
                    </div>
                    <div class="box-body">
                        <div class="health-metric">
                            <span>Compras del mes</span>
                            <strong><?php echo dashboard_money($comprasMes); ?></strong>
                            <small class="<?php echo $comprasMesChange['class']; ?>">
                                <?php echo number_format($comprasMesChange['value'], 1); ?>% vs mes anterior
                            </small>
                        </div>
                        <div class="health-metric">
                            <span>Utilidad estimada</span>
                            <strong><?php echo dashboard_money($utilidadMes); ?></strong>
                            <small><?php echo number_format($margenMes, 1); ?>% sobre ventas del mes</small>
                        </div>
                        <div class="health-metric">
                            <span>Crédito pendiente</span>
                            <strong><?php echo dashboard_money($saldoCreditoMes); ?></strong>
                            <small><?php echo dashboard_int($ventasCreditoMes); ?> ventas a crédito este mes</small>
                        </div>
                        <div class="health-metric">
                            <span>Clientes registrados</span>
                            <strong><?php echo dashboard_int($clientes); ?></strong>
                            <small>Base de clientes de la sucursal</small>
                        </div>
                        <div class="health-metric">
                            <span>Productos con stock</span>
                            <strong><?php echo dashboard_int($productosConStock); ?></strong>
                            <small><?php echo dashboard_int($productosStockBajo); ?> con stock bajo</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="box dashboard-card">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ventas vs compras de los últimos 6 meses</h3>
                        <span class="dashboard-card__hint">Comparativo mensual para ver crecimiento y reposición</span>
                    </div>
                    <div class="box-body">
                        <canvas id="monthlyComparisonChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="box dashboard-card">
                    <div class="box-header with-border">
                        <h3 class="box-title">Distribución por tipo de pago</h3>
                        <span class="dashboard-card__hint">Participación del mes actual</span>
                    </div>
                    <div class="box-body">
                        <canvas id="paymentChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="box dashboard-card">
                    <div class="box-header with-border">
                        <h3 class="box-title">Productos más vendidos del mes</h3>
                        <span class="dashboard-card__hint">Prioriza reposición y visibilidad comercial</span>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover dashboard-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Código</th>
                                    <th>Unidades</th>
                                    <th>Total vendido</th>
                                    <th>Utilidad estimada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($topProducts)) : ?>
                                    <?php foreach ($topProducts as $product) : ?>
                                        <tr>
                                            <td><?php echo html_escape($product['nombre_producto']); ?></td>
                                            <td><?php echo html_escape($product['codigo']); ?></td>
                                            <td><?php echo dashboard_int($product['unidades']); ?></td>
                                            <td><?php echo dashboard_money($product['total_vendido']); ?></td>
                                            <td><?php echo dashboard_money($product['utilidad_estimada']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Aún no hay ventas registradas en el periodo actual.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="box dashboard-card">
                    <div class="box-header with-border">
                        <h3 class="box-title">Alertas de inventario</h3>
                        <span class="dashboard-card__hint">Productos que conviene resurtir cuanto antes</span>
                    </div>
                    <div class="box-body">
                        <?php if (!empty($lowStockProducts)) : ?>
                            <?php foreach ($lowStockProducts as $product) : ?>
                                <div class="stock-alert-item">
                                    <div>
                                        <strong><?php echo html_escape($product['nombre_producto']); ?></strong>
                                        <p>
                                            <?php echo html_escape($product['nombre_categoria'] ? $product['nombre_categoria'] : 'Sin categoría'); ?>
                                            · Código: <?php echo html_escape($product['codigo']); ?>
                                        </p>
                                    </div>
                                    <span class="label label-danger"><?php echo dashboard_int($product['stock']); ?> pzas</span>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="empty-state">
                                No hay productos en nivel de stock bajo en esta sucursal.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="box dashboard-card dashboard-card--compact">
                    <div class="box-header with-border">
                        <h3 class="box-title">Acciones rápidas</h3>
                    </div>
                    <div class="box-body">
                        <div class="dashboard-actions">
                            <a href="<?php echo base_url(); ?>carrito/carrito" class="btn btn-success btn-flat">Ir al punto de venta</a>
                            <a href="<?php echo base_url(); ?>reporte/reporte_venta_mensual" class="btn btn-primary btn-flat">Ver reportes</a>
                            <a href="<?php echo base_url(); ?>producto/producto_lista" class="btn btn-default btn-flat">Administrar productos</a>
                            <a href="<?php echo base_url(); ?>caja" class="btn btn-warning btn-flat">Revisar caja</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.dashboard-owner-view .content-header h1 {
    font-weight: 700;
}

.dashboard-hero {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 20px;
    padding: 24px;
    border: 1px solid #d9e4ea;
    border-radius: 18px;
    background: linear-gradient(135deg, #f4faf8 0%, #eef5fb 100%);
    box-shadow: 0 16px 38px rgba(32, 60, 79, 0.08);
}

.dashboard-hero__main h2 {
    margin: 10px 0 8px;
    font-size: 28px;
    font-weight: 700;
    color: #17324d;
}

.dashboard-hero__main p {
    margin: 0;
    max-width: 680px;
    color: #587086;
    font-size: 15px;
    line-height: 1.6;
}

.dashboard-chip {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 999px;
    background: #dff2e8;
    color: #0d7a43;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.dashboard-hero__meta {
    min-width: 260px;
    display: grid;
    gap: 12px;
}

.dashboard-mini-stat {
    padding: 14px 16px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.84);
    border: 1px solid #dbe6ef;
}

.dashboard-mini-stat span {
    display: block;
    margin-bottom: 6px;
    color: #5d7488;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.dashboard-mini-stat strong {
    font-size: 18px;
    color: #17324d;
}

.owner-kpi {
    display: flex;
    gap: 16px;
    min-height: 172px;
    margin-bottom: 18px;
    padding: 20px;
    border-radius: 18px;
    background: #fff;
    border: 1px solid #e2e9ef;
    box-shadow: 0 12px 28px rgba(17, 35, 52, 0.07);
}

.owner-kpi__icon {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    font-size: 22px;
    color: #17324d;
    flex-shrink: 0;
}

.bg-green-soft { background: #dff5e8; }
.bg-blue-soft { background: #dfefff; }
.bg-orange-soft { background: #ffedd8; }
.bg-purple-soft { background: #ebe4ff; }

.owner-kpi__label {
    display: block;
    margin-bottom: 4px;
    color: #5d7488;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.owner-kpi__content strong {
    display: block;
    margin-bottom: 8px;
    color: #17324d;
    font-size: 28px;
    line-height: 1.1;
}

.owner-kpi__content p {
    margin: 0 0 10px;
    color: #566b7e;
    font-size: 14px;
}

.owner-kpi__delta {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.is-positive {
    color: #0d7a43;
    background: #e1f7eb;
}

.is-negative {
    color: #b53d2f;
    background: #fde8e6;
}

.is-neutral {
    color: #5b6f83;
    background: #edf2f6;
}

.dashboard-card {
    border-radius: 18px;
    border: 1px solid #dfe7ee;
    box-shadow: 0 10px 24px rgba(24, 42, 61, 0.06);
}

.dashboard-card .box-header {
    padding: 18px 20px 10px;
}

.dashboard-card .box-title {
    font-size: 18px;
    font-weight: 700;
    color: #17324d;
}

.dashboard-card__hint {
    display: block;
    margin-top: 6px;
    color: #6d8194;
    font-size: 13px;
}

.dashboard-card .box-body {
    padding: 18px 20px 22px;
}

.dashboard-card--compact .box-body {
    padding-top: 8px;
}

.health-metric {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 14px 0;
    border-bottom: 1px solid #edf2f6;
}

.health-metric:last-child {
    border-bottom: 0;
}

.health-metric span {
    color: #5c7185;
    font-size: 13px;
    font-weight: 600;
}

.health-metric strong {
    color: #17324d;
    font-size: 26px;
    line-height: 1.1;
}

.health-metric small {
    color: #6d8194;
    font-size: 12px;
}

.dashboard-table thead th {
    border-bottom: 1px solid #e3ebf1;
    color: #5d7488;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.dashboard-table tbody td {
    vertical-align: middle;
    color: #24384b;
}

.stock-alert-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid #edf2f6;
}

.stock-alert-item:last-child {
    border-bottom: 0;
}

.stock-alert-item strong {
    display: block;
    color: #17324d;
}

.stock-alert-item p {
    margin: 4px 0 0;
    color: #6d8194;
    font-size: 13px;
}

.empty-state {
    padding: 16px;
    border-radius: 12px;
    background: #f7fafc;
    color: #5d7488;
}

.dashboard-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.dashboard-actions .btn {
    min-width: 170px;
}

@media (max-width: 991px) {
    .dashboard-hero {
        flex-direction: column;
    }

    .dashboard-hero__meta {
        min-width: 0;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    if (typeof Chart === 'undefined') {
        return;
    }

    const trendLabels = <?php echo json_encode($trendLabels); ?>;
    const trendTotals = <?php echo json_encode($trendTotals); ?>;
    const trendTickets = <?php echo json_encode($trendTickets); ?>;
    const monthlyLabels = <?php echo json_encode($monthlyLabels); ?>;
    const monthlySales = <?php echo json_encode($monthlySales); ?>;
    const monthlyPurchases = <?php echo json_encode($monthlyPurchases); ?>;
    const paymentLabels = <?php echo json_encode($paymentLabels); ?>;
    const paymentTotals = <?php echo json_encode($paymentTotals); ?>;

    const moneyFormatter = (value) => {
        const numeric = Number(value || 0);
        return '$' + numeric.toLocaleString('es-MX', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    };

    const axisCommon = {
        ticks: {
            callback: (value) => moneyFormatter(value)
        },
        grid: {
            color: 'rgba(23, 50, 77, 0.08)'
        }
    };

    const trendContext = document.getElementById('salesTrendChart');
    if (trendContext) {
        new Chart(trendContext, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [
                    {
                        label: 'Ventas',
                        data: trendTotals,
                        borderColor: '#138a60',
                        backgroundColor: 'rgba(19, 138, 96, 0.14)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 3,
                        pointRadius: 3,
                        pointBackgroundColor: '#138a60',
                        yAxisID: 'y'
                    },
                    {
                        label: 'Tickets',
                        data: trendTickets,
                        borderColor: '#2f6bff',
                        backgroundColor: 'rgba(47, 107, 255, 0.08)',
                        fill: false,
                        tension: 0.25,
                        borderWidth: 2,
                        pointRadius: 2,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.label === 'Ventas') {
                                    return context.dataset.label + ': ' + moneyFormatter(context.raw);
                                }
                                return context.dataset.label + ': ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: axisCommon,
                    y1: {
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    }

    const monthlyContext = document.getElementById('monthlyComparisonChart');
    if (monthlyContext) {
        new Chart(monthlyContext, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [
                    {
                        label: 'Ventas',
                        data: monthlySales,
                        backgroundColor: '#1b9e6b',
                        borderRadius: 8
                    },
                    {
                        label: 'Compras',
                        data: monthlyPurchases,
                        backgroundColor: '#e39c32',
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + moneyFormatter(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: axisCommon
                }
            }
        });
    }

    const paymentContext = document.getElementById('paymentChart');
    if (paymentContext) {
        new Chart(paymentContext, {
            type: 'doughnut',
            data: {
                labels: paymentLabels.length ? paymentLabels : ['Sin ventas'],
                datasets: [{
                    data: paymentTotals.length ? paymentTotals : [1],
                    backgroundColor: ['#1b9e6b', '#2f6bff', '#e39c32', '#9457eb', '#e65a5a', '#29a0b1'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (!paymentTotals.length) {
                                    return 'Sin ventas registradas';
                                }
                                return context.label + ': ' + moneyFormatter(context.raw);
                            }
                        }
                    }
                },
                cutout: '62%'
            }
        });
    }
})();
</script>
