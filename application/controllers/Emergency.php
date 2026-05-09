<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emergency extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->config('emergency');
    }

    // GET /acceso-emergencia/{token}
    public function access($token = '') {
        if (empty($token)) {
            show_404();
            return;
        }

        $hash = $this->config->item('emergency_token_hash');
        $ttl  = (int) $this->config->item('emergency_ttl');

        if (!password_verify($token, $hash)) {
            // Respuesta idéntica a 404 para no revelar que la ruta existe
            show_404();
            return;
        }

        $this->session->set_userdata([
            'emergency_admin'   => true,
            'emergency_expires' => time() + $ttl,
        ]);

        redirect('dashboard');
    }

    // GET /acceso-emergencia/salir
    public function salir() {
        $this->session->unset_userdata(['emergency_admin', 'emergency_expires']);
        redirect('login');
    }
}
