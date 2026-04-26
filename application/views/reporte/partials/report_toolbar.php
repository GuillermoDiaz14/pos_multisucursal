<?php
$reportExportTitle = isset($reportExportTitle) ? $reportExportTitle : 'Reporte';
$reportExportSubtitle = isset($reportExportSubtitle) ? $reportExportSubtitle : '';
$reportExportOrientation = isset($reportExportOrientation) ? strtoupper($reportExportOrientation) : 'L';
$reportExportFile = isset($reportExportFile) ? $reportExportFile : url_title($reportExportTitle, '_', true);
?>
<style>
.report-shell {
    --report-ink: #16324f;
    --report-accent: #0f766e;
    --report-soft: #f4f7fb;
    --report-line: #d8e1eb;
}
.report-shell .box,
.report-shell .small-box {
    border-radius: 18px;
    border: 1px solid var(--report-line);
    box-shadow: 0 14px 32px rgba(15, 23, 42, 0.08);
    overflow: hidden;
}
.report-shell .box-header.with-border {
    border-bottom: 1px solid var(--report-line);
}
.report-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    padding: 18px 20px;
    margin-bottom: 18px;
    border-radius: 20px;
    background: linear-gradient(135deg, #16324f 0%, #215a6d 100%);
    color: #fff;
}
.report-toolbar__eyebrow {
    margin: 0 0 4px;
    font-size: 12px;
    letter-spacing: .08em;
    text-transform: uppercase;
    opacity: .78;
}
.report-toolbar__title {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}
.report-toolbar__subtitle {
    margin: 6px 0 0;
    font-size: 13px;
    opacity: .88;
}
.report-toolbar__actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.report-toolbar .btn {
    border-radius: 999px;
    padding: 10px 16px;
    border: 0;
    font-weight: 600;
}
.report-shell .small-box .inner {
    padding: 18px;
}
.report-shell .small-box h3 {
    font-size: 26px;
    margin-bottom: 8px;
}
.report-shell .table {
    margin-bottom: 0;
}
.report-shell .table > thead > tr > th,
.report-shell .table > tbody > tr > th,
.report-shell .table > tbody > tr > td {
    border-top: 1px solid #e6edf5;
    padding: 11px 12px;
    vertical-align: middle;
}
.report-shell .table > thead > tr > th {
    border-top: 0;
    color: var(--report-ink);
    background: #f7fafc;
}
.report-kpi-strip {
    margin-bottom: 16px;
}
.report-empty {
    padding: 24px;
    text-align: center;
    color: #6b7280;
}
@media (max-width: 767px) {
    .report-toolbar {
        padding: 16px;
    }
    .report-toolbar__title {
        font-size: 20px;
    }
    .report-toolbar__actions {
        width: 100%;
    }
    .report-toolbar__actions .btn {
        flex: 1 1 100%;
    }
}
</style>

<div class="report-toolbar">
    <div>
        <p class="report-toolbar__eyebrow">Centro de reportes</p>
        <h2 class="report-toolbar__title"><?php echo $reportExportTitle; ?></h2>
        <?php if ($reportExportSubtitle !== '') { ?>
        <p class="report-toolbar__subtitle"><?php echo $reportExportSubtitle; ?></p>
        <?php } ?>
    </div>
    <div class="report-toolbar__actions">
        <button type="button" class="btn btn-success" data-report-export="excel" data-report-file="<?php echo $reportExportFile; ?>">
            <i class="fa fa-file-excel-o"></i> Excel
        </button>
        <button type="button" class="btn btn-danger" data-report-export="pdf" data-report-file="<?php echo $reportExportFile; ?>" data-report-orientation="<?php echo $reportExportOrientation; ?>">
            <i class="fa fa-file-pdf-o"></i> PDF
        </button>
    </div>
</div>

<form method="post" action="<?php echo base_url('reporte/exportar_pdf_reporte'); ?>" target="_blank" class="js-report-pdf-form" style="display:none;">
    <input type="hidden" name="title" value="">
    <input type="hidden" name="subtitle" value="">
    <input type="hidden" name="filters_html" value="">
    <input type="hidden" name="kpis_html" value="">
    <input type="hidden" name="table_html" value="">
    <input type="hidden" name="orientation" value="<?php echo $reportExportOrientation; ?>">
</form>

<script>
(function() {
    if (window.reportExportToolsReady) {
        return;
    }
    window.reportExportToolsReady = true;

    function asCurrency(text) {
        return String(text || '').replace(/\s+/g, ' ').trim();
    }

    function findRoot(button) {
        return button.closest('[data-report-root]') || document.querySelector('[data-report-root]') || document;
    }

    function extractFilters(root) {
        var fields = root.querySelectorAll('form input, form select');
        var lines = [];
        Array.prototype.forEach.call(fields, function(field) {
            if (!field.name || field.type === 'hidden' || field.disabled || !field.value) {
                return;
            }
            var label = '';
            if (field.id) {
                var bound = root.querySelector('label[for="' + field.id + '"]');
                if (bound) {
                    label = bound.textContent.trim();
                }
            }
            if (!label) {
                var formGroup = field.closest('.form-group');
                var inlineLabel = formGroup ? formGroup.querySelector('label') : null;
                label = inlineLabel ? inlineLabel.textContent.trim() : field.name;
            }
            lines.push('<strong>' + label + ':</strong> ' + asCurrency(field.options && field.selectedIndex >= 0 ? field.options[field.selectedIndex].text : field.value));
        });
        return lines.join(' | ');
    }

    function extractKpis(root) {
        var cards = root.querySelectorAll('.small-box');
        if (!cards.length) {
            return '';
        }
        var html = '<table class="kpi-table" cellpadding="0" cellspacing="0">';
        Array.prototype.forEach.call(cards, function(card, index) {
            if (index % 2 === 0) {
                html += '<tr>';
            }
            var value = card.querySelector('h3');
            var label = card.querySelector('p');
            html += '<td><strong>' + asCurrency(label ? label.textContent : '') + ':</strong> ' + asCurrency(value ? value.textContent : '') + '</td>';
            if (index % 2 === 1) {
                html += '</tr>';
            }
        });
        if (cards.length % 2 === 1) {
            html += '<td></td></tr>';
        }
        html += '</table><br>';
        return html;
    }

    function extractTable(root) {
        var table = root.querySelector('table');
        if (!table) {
            return '';
        }
        return table.outerHTML;
    }

    function exportExcel(button) {
        var root = findRoot(button);
        var table = root.querySelector('table');
        if (!table || typeof XLSX === 'undefined') {
            return;
        }
        var workbook = XLSX.utils.table_to_book(table, {sheet: 'Reporte'});
        XLSX.writeFile(workbook, (button.getAttribute('data-report-file') || 'reporte') + '.xlsx');
    }

    function exportPdf(button) {
        var root = findRoot(button);
        var form = root.querySelector('.js-report-pdf-form') || document.querySelector('.js-report-pdf-form');
        if (!form) {
            return;
        }
        form.querySelector('[name="title"]').value = root.getAttribute('data-report-title') || document.title || 'Reporte';
        form.querySelector('[name="subtitle"]').value = root.getAttribute('data-report-subtitle') || '';
        form.querySelector('[name="filters_html"]').value = extractFilters(root);
        form.querySelector('[name="kpis_html"]').value = extractKpis(root);
        form.querySelector('[name="table_html"]').value = extractTable(root);
        form.querySelector('[name="orientation"]').value = button.getAttribute('data-report-orientation') || 'L';
        form.submit();
    }

    document.addEventListener('click', function(event) {
        var button = event.target.closest('[data-report-export]');
        if (!button) {
            return;
        }
        if (button.getAttribute('data-report-export') === 'excel') {
            exportExcel(button);
            return;
        }
        exportPdf(button);
    });
})();
</script>
