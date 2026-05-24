<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Image_tools — utilidades de mantenimiento de imágenes.
 *
 * SOLO EJECUTABLE DESDE CLI. Bloqueado vía HTTP.
 *
 * Uso:
 *   cd /Applications/XAMPP/xamppfiles/htdocs/pos_multisucursal
 *
 *   # Reporte sin tocar nada (recomendado primer paso)
 *   php index.php image_tools optimize_productos 1
 *
 *   # Procesar solo N imágenes para revisar visualmente
 *   php index.php image_tools optimize_productos 0 10
 *
 *   # Procesar todo con backup automático (recomendado)
 *   php index.php image_tools optimize_productos 0 0 1
 *
 *   # Solo crear thumbnails faltantes (no toca el original)
 *   php index.php image_tools generate_missing_thumbs
 *
 *   # Restaurar desde backup si algo salió mal
 *   php index.php image_tools restore_backup
 *
 *   # Migrar fotos de perfil al nombre versionado user_{id}_{ts}.jpg
 *   php index.php image_tools migrate_fotos_perfil 1   # dry-run
 *   php index.php image_tools migrate_fotos_perfil 0   # ejecutar
 *
 *   # Listar archivos huérfanos (no referenciados en BD)
 *   php index.php image_tools cleanup_orphans 1        # dry-run (solo listar)
 *   php index.php image_tools cleanup_orphans 0 1      # eliminar de verdad
 *
 * Parámetros (deben coincidir con Producto::comprimir_imagen):
 *   - main:  600px máx, JPEG progresivo q60
 *   - thumb: 150px máx, JPEG progresivo q55
 *
 * SALVAGUARDAS contra pérdida generacional / sobre-compresión:
 *   - Se omite cualquier archivo que YA cumpla los criterios objetivo
 *     (dimensiones ≤ MAX_MAIN Y peso ≤ SKIP_KB_MAX)
 *   - Backup opcional automático en uploads/productos/_backup_TIMESTAMP/
 *   - El script NUNCA recomprime un thumbnail (los archivos thumb_* se excluyen)
 *   - El script NUNCA recomprime un archivo ya recomprimido en esta misma corrida
 */
class Image_tools extends CI_Controller
{
    // Debe coincidir con Producto::comprimir_imagen
    const MAX_MAIN   = 600;
    const Q_MAIN     = 60;
    const MAX_THUMB  = 150;
    const Q_THUMB    = 55;

    // Si la imagen YA está dentro de target Y pesa menos que esto → no tocar
    // (evita re-compresión innecesaria y pérdida generacional)
    const SKIP_KB_MAX = 40;

    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_cli_request()) {
            show_error('Esta herramienta solo se ejecuta desde CLI.', 403);
        }
    }

    /**
     * Recomprime todas las imágenes de productos con los nuevos parámetros
     * + genera thumbnails. Idempotente: puede correr varias veces sin daño.
     *
     * @param int $dry_run 1 = solo reporta, no escribe
     */
    public function optimize_productos($dry_run = 0, $sample = 0, $do_backup = 0)
    {
        $dry_run   = (int) $dry_run === 1;
        $sample    = max(0, (int) $sample);
        $do_backup = (int) $do_backup === 1;
        $dir       = FCPATH . 'uploads/productos/';

        if (!is_dir($dir)) {
            $this->_out("Directorio no existe: $dir");
            return;
        }

        $files = glob($dir . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);
        // Excluir thumbnails y backups previos
        $files = array_filter($files, function ($f) {
            $b = basename($f);
            return strpos($b, 'thumb_') !== 0 && strpos($b, '_backup_') === false;
        });
        $files = array_values($files);

        if ($sample > 0) {
            $files = array_slice($files, 0, $sample);
        }

        // Crear directorio de backup si aplica
        $backup_dir = null;
        if ($do_backup && !$dry_run && !empty($files)) {
            $backup_dir = $dir . '_backup_' . date('Ymd_His') . '/';
            if (!@mkdir($backup_dir, 0755, true)) {
                $this->_out("ERROR: no pude crear directorio de backup: $backup_dir");
                return;
            }
            $this->_out("Backup en: $backup_dir");
        }

        $total        = count($files);
        $ok           = 0;
        $errores      = 0;
        $saltados     = 0;
        $bytes_antes  = 0;
        $bytes_dpues  = 0;
        $thumbs_new   = 0;

        $this->_out(str_repeat('=', 60));
        $this->_out("OPTIMIZACIÓN DE IMÁGENES DE PRODUCTOS");
        $this->_out(($dry_run ? '[DRY RUN — no se escribe nada]' : '[MODO REAL — se modificarán archivos]'));
        $this->_out(str_repeat('=', 60));
        $this->_out("Directorio: $dir");
        $this->_out("Archivos a procesar: $total" . ($sample > 0 ? " (sample, total real más alto)" : ''));
        $this->_out("Parámetros: main=" . self::MAX_MAIN . "px q" . self::Q_MAIN
                  . " | thumb=" . self::MAX_THUMB . "px q" . self::Q_THUMB);
        $this->_out("Skip si dimensiones ≤ " . self::MAX_MAIN . "px Y peso ≤ " . self::SKIP_KB_MAX . "KB");
        $this->_out('');

        $start = microtime(true);

        foreach ($files as $i => $file) {
            $size_antes = filesize($file);
            $bytes_antes += $size_antes;

            // Check si saltarse este archivo
            $skip_reason = $this->_should_skip($file, $size_antes);
            if ($skip_reason !== null) {
                $saltados++;
                $bytes_dpues += $size_antes;
                $this->_out(sprintf(
                    "[%d/%d] ⊝ SKIP (%s): %s  %s",
                    $i + 1, $total, $skip_reason, basename($file),
                    $this->_human($size_antes)
                ));
                continue;
            }

            // Backup antes de modificar
            if ($backup_dir !== null) {
                @copy($file, $backup_dir . basename($file));
            }

            $result = $this->_recomprimir($file, $dry_run);

            if ($result === false) {
                $errores++;
                $this->_out(sprintf("[%d/%d] ✗ ERROR: %s", $i + 1, $total, basename($file)));
                $bytes_dpues += $size_antes;
                continue;
            }

            $size_dpues  = $result['size'];
            $bytes_dpues += $size_dpues;
            $ok++;
            if ($result['thumb_new']) $thumbs_new++;

            $pct = $size_antes > 0 ? round(100 * (1 - $size_dpues / $size_antes), 1) : 0;
            $this->_out(sprintf(
                "[%d/%d] ✓ %s  %s → %s (-%s%%)%s",
                $i + 1, $total,
                basename($file),
                $this->_human($size_antes),
                $this->_human($size_dpues),
                $pct,
                $result['thumb_new'] ? '  +thumb' : ''
            ));
        }

        $elapsed = round(microtime(true) - $start, 1);

        $this->_out('');
        $this->_out(str_repeat('-', 60));
        $this->_out("RESUMEN");
        $this->_out(str_repeat('-', 60));
        $this->_out("Procesados OK   : $ok");
        $this->_out("Saltados (ya OK): $saltados");
        $this->_out("Errores         : $errores");
        $this->_out("Thumbs creados  : $thumbs_new");
        if ($backup_dir) {
            $this->_out("Backup en       : $backup_dir");
            $this->_out("  Restaurar con : php index.php image_tools restore_backup");
        }
        $this->_out("Tamaño antes    : " . $this->_human($bytes_antes));
        $this->_out("Tamaño después  : " . $this->_human($bytes_dpues));
        $ahorro = $bytes_antes - $bytes_dpues;
        $pct = $bytes_antes > 0 ? round(100 * $ahorro / $bytes_antes, 1) : 0;
        $this->_out("Ahorro          : " . $this->_human($ahorro) . " ($pct%)");
        $this->_out("Tiempo          : {$elapsed}s");
        if ($dry_run) {
            $this->_out('');
            $this->_out("Nada fue modificado. Para aplicar cambios:");
            $this->_out("  php index.php image_tools optimize_productos");
        }
    }

    /**
     * Solo genera thumbnails faltantes (no recomprime los originales).
     * Útil si ya recomprimiste antes y solo quieres asegurarte que cada imagen
     * tenga su thumb_.
     */
    public function generate_missing_thumbs()
    {
        $dir   = FCPATH . 'uploads/productos/';
        $files = glob($dir . '*.{jpg,jpeg,JPG,JPEG}', GLOB_BRACE);
        $files = array_filter($files, function ($f) {
            return strpos(basename($f), 'thumb_') !== 0;
        });

        $creados = 0;
        $existentes = 0;
        foreach ($files as $f) {
            $thumb = dirname($f) . DIRECTORY_SEPARATOR . 'thumb_' . basename($f);
            if (is_file($thumb)) { $existentes++; continue; }
            if ($this->_solo_thumb($f, $thumb)) $creados++;
        }
        $this->_out("Thumbs ya existentes: $existentes");
        $this->_out("Thumbs nuevos: $creados");
    }

    /**
     * Restaura el backup más reciente (uploads/productos/_backup_TIMESTAMP/).
     * Útil si algo salió mal después de optimize_productos.
     */
    public function restore_backup()
    {
        $dir = FCPATH . 'uploads/productos/';
        $backups = glob($dir . '_backup_*', GLOB_ONLYDIR);
        if (empty($backups)) {
            $this->_out("No hay backups en $dir");
            return;
        }
        rsort($backups); // más reciente primero
        $last = $backups[0];
        $this->_out("Restaurando desde: $last");

        $files = glob($last . '/*');
        $restored = 0;
        foreach ($files as $f) {
            $name = basename($f);
            $dest = $dir . $name;
            // Borrar el .jpg que dejó la recompresión si nombre cambió (png→jpg)
            $base_no_ext = preg_replace('/\.[^.]+$/', '', $name);
            $maybe_jpg = $dir . $base_no_ext . '.jpg';
            if ($maybe_jpg !== $dest && is_file($maybe_jpg)) {
                @unlink($maybe_jpg);
                // y su thumb
                $maybe_thumb = $dir . 'thumb_' . $base_no_ext . '.jpg';
                if (is_file($maybe_thumb)) @unlink($maybe_thumb);
            }
            if (@copy($f, $dest)) $restored++;
        }
        $this->_out("Archivos restaurados: $restored");
        $this->_out("Backup conservado en: $last (bórralo manual cuando estés seguro)");
    }

    /**
     * Migra fotos de perfil del nombre legado user_{id}.jpg al versionado
     * user_{id}_{ts}.jpg, usando el campo tbl_users.foto como timestamp.
     *
     * @param int $dry_run 1 = solo reporta
     */
    public function migrate_fotos_perfil($dry_run = 1)
    {
        $dry_run = (int) $dry_run === 1;
        $dir     = FCPATH . 'uploads/fotos/';

        if (!is_dir($dir)) {
            $this->_out("Directorio no existe: $dir");
            return;
        }

        $this->load->database();
        $rows = $this->db->select('userId, foto')->where('foto IS NOT NULL')->get('tbl_users')->result();

        $this->_out(str_repeat('=', 60));
        $this->_out("MIGRACIÓN FOTOS PERFIL → nombre versionado");
        $this->_out(($dry_run ? '[DRY RUN]' : '[MODO REAL]'));
        $this->_out(str_repeat('=', 60));

        $renombrados = 0;
        $ya_ok       = 0;
        $sin_archivo = 0;

        foreach ($rows as $u) {
            $legado     = $dir . 'user_' . $u->userId . '.jpg';
            $versionado = $dir . 'user_' . $u->userId . '_' . $u->foto . '.jpg';

            if (is_file($versionado)) {
                $ya_ok++;
                continue;
            }
            if (!is_file($legado)) {
                $sin_archivo++;
                $this->_out("  - user $u->userId: sin archivo (foto={$u->foto})");
                continue;
            }
            if ($dry_run) {
                $this->_out("  + user $u->userId: " . basename($legado) . " → " . basename($versionado));
            } else {
                if (@rename($legado, $versionado)) {
                    $this->_out("  ✓ user $u->userId: renombrado");
                    $renombrados++;
                } else {
                    $this->_out("  ✗ user $u->userId: ERROR al renombrar");
                }
            }
        }

        $this->_out('');
        $this->_out("Renombrados : " . ($dry_run ? '(simulado) ' : '') . $renombrados);
        $this->_out("Ya versionados: $ya_ok");
        $this->_out("Sin archivo  : $sin_archivo");
        if ($dry_run) $this->_out("Aplicar con: php index.php image_tools migrate_fotos_perfil 0");
    }

    /**
     * Lista (o elimina) archivos huérfanos: presentes en disco pero no referenciados
     * por la base de datos.
     *
     * Inspecciona:
     *   - uploads/productos/   vs   tbl_producto.imagen
     *   - uploads/fotos/       vs   tbl_users (userId + foto)
     *   - uploads/logos/       vs   tbl_sucursal.ticket_logo
     *
     * @param int $dry_run 1 = solo listar (default)
     * @param int $delete  1 = eliminar de verdad (requiere dry_run=0)
     */
    public function cleanup_orphans($dry_run = 1, $delete = 0)
    {
        $dry_run = (int) $dry_run === 1;
        $delete  = (int) $delete === 1 && !$dry_run;

        $this->load->database();

        $this->_out(str_repeat('=', 60));
        $this->_out("LIMPIEZA DE ARCHIVOS HUÉRFANOS");
        $this->_out($dry_run ? '[DRY RUN — solo listar]' : ($delete ? '[ELIMINAR DE VERDAD]' : '[modo informativo]'));
        $this->_out(str_repeat('=', 60));

        $total_huerfanos = 0;
        $total_bytes     = 0;

        // ─── productos ───────────────────────────────────────────────────
        $dir = FCPATH . 'uploads/productos/';
        if (is_dir($dir)) {
            $refs = [];
            $rows = $this->db->select('imagen')->where('imagen IS NOT NULL', null, false)
                              ->where("imagen !=", '')->get('tbl_producto')->result();
            foreach ($rows as $r) {
                $refs[$r->imagen] = true;
                // un producto legítimo también "protege" su thumb_
                $refs['thumb_' . $r->imagen] = true;
            }

            $files = glob($dir . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE) ?: [];
            $huerf = 0; $bytes = 0;
            foreach ($files as $f) {
                $b = basename($f);
                if (strpos($b, '_backup_') !== false) continue; // ignorar backups
                if (isset($refs[$b])) continue;

                $sz = filesize($f);
                $huerf++; $bytes += $sz; $total_huerfanos++; $total_bytes += $sz;
                $this->_out("  - productos/$b  " . $this->_human($sz));
                if ($delete) @unlink($f);
            }
            $this->_out("productos/: $huerf huérfanos (" . $this->_human($bytes) . ")");
        }

        // ─── fotos perfil ────────────────────────────────────────────────
        $dir = FCPATH . 'uploads/fotos/';
        if (is_dir($dir)) {
            $valid = [];
            $rows = $this->db->select('userId, foto')->get('tbl_users')->result();
            foreach ($rows as $u) {
                // Aceptar tanto nombre legado como versionado
                $valid['user_' . $u->userId . '.jpg'] = true;
                if (!empty($u->foto)) {
                    $valid['user_' . $u->userId . '_' . $u->foto . '.jpg'] = true;
                }
            }

            $files = glob($dir . '*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE) ?: [];
            $huerf = 0; $bytes = 0;
            foreach ($files as $f) {
                $b = basename($f);
                if (isset($valid[$b])) continue;

                $sz = filesize($f);
                $huerf++; $bytes += $sz; $total_huerfanos++; $total_bytes += $sz;
                $this->_out("  - fotos/$b  " . $this->_human($sz));
                if ($delete) @unlink($f);
            }
            $this->_out("fotos/: $huerf huérfanos (" . $this->_human($bytes) . ")");
        }

        // ─── logos sucursal ──────────────────────────────────────────────
        $dir = FCPATH . 'uploads/logos/';
        if (is_dir($dir)) {
            $refs = [];
            $rows = $this->db->select('ticket_logo')->where('ticket_logo IS NOT NULL', null, false)
                              ->where("ticket_logo !=", '')->get('tbl_sucursal')->result();
            foreach ($rows as $r) $refs[$r->ticket_logo] = true;

            $files = glob($dir . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE) ?: [];
            $huerf = 0; $bytes = 0;
            foreach ($files as $f) {
                $b = basename($f);
                if (isset($refs[$b])) continue;

                $sz = filesize($f);
                $huerf++; $bytes += $sz; $total_huerfanos++; $total_bytes += $sz;
                $this->_out("  - logos/$b  " . $this->_human($sz));
                if ($delete) @unlink($f);
            }
            $this->_out("logos/: $huerf huérfanos (" . $this->_human($bytes) . ")");
        }

        $this->_out('');
        $this->_out(str_repeat('-', 60));
        $this->_out("TOTAL huérfanos: $total_huerfanos (" . $this->_human($total_bytes) . ")");
        if ($dry_run) {
            $this->_out("Para eliminar de verdad:");
            $this->_out("  php index.php image_tools cleanup_orphans 0 1");
        } elseif ($delete) {
            $this->_out("ELIMINADOS.");
        } else {
            $this->_out("(no se eliminó nada — pasa el flag 'delete' como 1 para borrar)");
        }
    }

    // ────────────────────────────────────────────────────────────────────
    // Internals
    // ────────────────────────────────────────────────────────────────────

    /**
     * Devuelve null si hay que procesar, o string con razón para saltar.
     * Criterio: ya está dentro de target (dimensiones + peso).
     */
    private function _should_skip($file, $size_bytes)
    {
        $info = @getimagesize($file);
        if (!$info) return null; // dejará que _recomprimir falle y reporte error

        $w = $info[0]; $h = $info[1];
        $kb = $size_bytes / 1024;

        // Si ya está dentro de las dimensiones objetivo Y pesa poco → no tocar
        $within_dims = ($w <= self::MAX_MAIN && $h <= self::MAX_MAIN);
        $within_size = ($kb <= self::SKIP_KB_MAX);

        if ($within_dims && $within_size) {
            return sprintf("%dx%d %sKB", $w, $h, round($kb, 1));
        }
        return null;
    }

    private function _recomprimir($file, $dry_run)
    {
        $info = @getimagesize($file);
        if (!$info) return false;

        $mime = $info['mime'];
        switch ($mime) {
            case 'image/jpeg': $src = @imagecreatefromjpeg($file); break;
            case 'image/png':  $src = @imagecreatefrompng($file);  break;
            case 'image/gif':  $src = @imagecreatefromgif($file);  break;
            default: return false;
        }
        if (!$src) return false;

        $ow = imagesx($src);
        $oh = imagesy($src);

        // Resize a 600px
        $max = self::MAX_MAIN;
        if ($ow > $max || $oh > $max) {
            if ($ow >= $oh) { $nw = $max; $nh = (int) round($oh * ($max / $ow)); }
            else            { $nh = $max; $nw = (int) round($ow * ($max / $oh)); }
        } else {
            $nw = $ow; $nh = $oh;
        }

        $dst = imagecreatetruecolor($nw, $nh);
        $bg  = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $nw, $nh, $bg);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $ow, $oh);
        imagedestroy($src);

        $target_jpg = preg_replace('/\.[^.]+$/', '.jpg', $file);
        $thumb_path = dirname($target_jpg) . DIRECTORY_SEPARATOR . 'thumb_' . basename($target_jpg);
        $thumb_new  = !is_file($thumb_path);

        if (!$dry_run) {
            imageinterlace($dst, true);
            $ok = @imagejpeg($dst, $target_jpg, self::Q_MAIN);
            if (!$ok) { imagedestroy($dst); return false; }

            // Si el original era png/gif, el .jpg es otro archivo: borrar el viejo
            if (realpath($file) && realpath($target_jpg) && realpath($file) !== realpath($target_jpg)) {
                @unlink($file);
            }

            // Generar thumbnail
            $tmax = self::MAX_THUMB;
            if ($nw > $tmax || $nh > $tmax) {
                if ($nw >= $nh) { $tw = $tmax; $th = (int) round($nh * ($tmax / $nw)); }
                else            { $th = $tmax; $tw = (int) round($nw * ($tmax / $nh)); }
            } else {
                $tw = $nw; $th = $nh;
            }
            $t  = imagecreatetruecolor($tw, $th);
            $bg2 = imagecolorallocate($t, 255, 255, 255);
            imagefilledrectangle($t, 0, 0, $tw, $th, $bg2);
            imagecopyresampled($t, $dst, 0, 0, 0, 0, $tw, $th, $nw, $nh);
            imageinterlace($t, true);
            @imagejpeg($t, $thumb_path, self::Q_THUMB);
            imagedestroy($t);
        } else {
            // En dry-run, estimar tamaño con archivo temporal
            $tmp = tempnam(sys_get_temp_dir(), 'img_');
            imageinterlace($dst, true);
            @imagejpeg($dst, $tmp, self::Q_MAIN);
            $sim_size = filesize($tmp);
            @unlink($tmp);
            imagedestroy($dst);
            return ['size' => $sim_size, 'thumb_new' => $thumb_new];
        }

        imagedestroy($dst);
        clearstatcache(true, $target_jpg);
        return ['size' => filesize($target_jpg), 'thumb_new' => $thumb_new];
    }

    private function _solo_thumb($file, $thumb_path)
    {
        $info = @getimagesize($file);
        if (!$info) return false;
        switch ($info['mime']) {
            case 'image/jpeg': $src = @imagecreatefromjpeg($file); break;
            case 'image/png':  $src = @imagecreatefrompng($file);  break;
            case 'image/gif':  $src = @imagecreatefromgif($file);  break;
            default: return false;
        }
        if (!$src) return false;

        $ow = imagesx($src); $oh = imagesy($src);
        $tmax = self::MAX_THUMB;
        if ($ow > $tmax || $oh > $tmax) {
            if ($ow >= $oh) { $tw = $tmax; $th = (int) round($oh * ($tmax / $ow)); }
            else            { $th = $tmax; $tw = (int) round($ow * ($tmax / $oh)); }
        } else { $tw = $ow; $th = $oh; }

        $t  = imagecreatetruecolor($tw, $th);
        $bg = imagecolorallocate($t, 255, 255, 255);
        imagefilledrectangle($t, 0, 0, $tw, $th, $bg);
        imagecopyresampled($t, $src, 0, 0, 0, 0, $tw, $th, $ow, $oh);
        imageinterlace($t, true);
        $ok = @imagejpeg($t, $thumb_path, self::Q_THUMB);
        imagedestroy($src);
        imagedestroy($t);
        return $ok;
    }

    private function _human($bytes)
    {
        if ($bytes < 1024) return $bytes . 'B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . 'KB';
        if ($bytes < 1073741824) return round($bytes / 1048576, 1) . 'MB';
        return round($bytes / 1073741824, 2) . 'GB';
    }

    private function _out($msg)
    {
        echo $msg . PHP_EOL;
    }
}
