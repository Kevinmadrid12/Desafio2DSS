<?php
// Configuración básica
define('APP_NAME', 'Gestión de Proyectos');
define('UPLOAD_DIR', 'assets/uploads/');

// Verificar si la carpeta de uploads existe, si no crearla
if(!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}
?>