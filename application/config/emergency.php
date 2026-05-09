<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 | Token de acceso de emergencia.
 | Generado con: password_hash('TU_TOKEN', PASSWORD_BCRYPT)
 | Para acceder: navega a  /acceso-emergencia/TU_TOKEN
 | La sesión dura 30 minutos y luego expira automáticamente.
 | NUNCA subas este archivo a control de versiones.
 */
$config['emergency_token_hash'] = '$2y$12$Z/aZB08qu8kwPC1iHz1C2eJ64FZiEGtcGKz0LRNt4kiMzHIwqPLNq';
$config['emergency_ttl']        = 1800; // segundos (30 min)
