<?php

function requireAuth() {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}


function redirectIfAuthenticated() {
    session_start();
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}


function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}


function isProjectOwner($project_id, $db) {
    $user_id = getCurrentUserId();
    if (!$user_id) return false;
    
    $stmt = $db->prepare("SELECT id FROM projects WHERE id = ? AND id_user = ?");
    $stmt->execute([$project_id, $user_id]);
    return $stmt->rowCount() > 0;
}


function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>