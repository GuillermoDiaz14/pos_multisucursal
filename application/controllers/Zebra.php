<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Proxy para Zebra Browser Print.
 * El navegador no puede llamar directamente a https://localhost:9101 desde un
 * origen público (bloqueo Chrome Private Network Access). Este controlador
 * actúa de intermediario: el JS llama al mismo servidor PHP, y PHP llama a
 * localhost:9101 sin restricciones de CORS.
 */
class Zebra extends BaseController
{
    private $zebraHost = 'https://localhost:9101';

    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
    }

    /** GET /zebra/available — devuelve la lista de impresoras disponibles */
    public function available()
    {
        $result = $this->_zebraGet('/available');
        $this->output
            ->set_content_type('application/json')
            ->set_output($result !== false ? $result : json_encode(['printer' => [], 'error' => 'No se pudo conectar a Zebra Browser Print']));
    }

    /** POST /zebra/write — envía ZPL a la impresora indicada */
    public function write()
    {
        $body = file_get_contents('php://input');
        $result = $this->_zebraPost('/write', $body);
        $this->output
            ->set_content_type('application/json')
            ->set_output($result !== false ? $result : json_encode(['error' => 'Error al enviar a Zebra']));
    }

    // -------------------------------------------------------------------------

    private function _zebraGet($path)
    {
        $ctx = stream_context_create([
            'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
            'http' => ['timeout' => 5],
        ]);
        return @file_get_contents($this->zebraHost . $path, false, $ctx);
    }

    private function _zebraPost($path, $body)
    {
        $ctx = stream_context_create([
            'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\nContent-Length: " . strlen($body),
                'content' => $body,
                'timeout' => 10,
            ],
        ]);
        return @file_get_contents($this->zebraHost . $path, false, $ctx);
    }
}
