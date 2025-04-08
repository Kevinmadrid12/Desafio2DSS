<?php
function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function flash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    return null;
}

function setFlash($key, $message) {
    $_SESSION['flash'][$key] = $message;
}

function validateEmail($email) {
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password);
}

function uploadFile($file, $target_dir, $allowed_types = []) {
    $result = ['success' => false, 'message' => '', 'path' => ''];
    
    // Verificar errores de subida
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $result['message'] = 'Error al subir el archivo';
        return $result;
    }
    
    // Validar tipo de archivo
    if (!empty($allowed_types) && !in_array($file['type'], $allowed_types)) {
        $result['message'] = 'Tipo de archivo no permitido';
        return $result;
    }
    
    // Validar tamaño (ejemplo: máximo 5MB)
    $max_size = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $max_size) {
        $result['message'] = 'El archivo es demasiado grande (máximo 5MB)';
        return $result;
    }
    
    // Crear directorio si no existe
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Generar nombre único para el archivo
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
    $target_path = $target_dir . $filename;
    
    // Mover el archivo
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        $result['success'] = true;
        $result['path'] = $target_path;
        $result['message'] = 'Archivo subido correctamente';
    } else {
        $result['message'] = 'Error al mover el archivo subido';
    }
    
    return $result;
}
?>