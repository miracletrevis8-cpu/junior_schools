<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'timnah_schools');

define('SITE_NAME', 'Timnah Schools');
define('SITE_URL', 'http://localhost:8080/junior_school');

define('ADMIN_URL', SITE_URL . '/admin');
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOADS_URL', SITE_URL . '/uploads');

define('UPLOAD_DIR', __DIR__ . '/uploads/');

$conn = null;

function getDB() {
    global $conn;
    if ($conn === null) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    return $conn;
}

function query($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    if (!$stmt) return false;
    
    if (!empty($params)) {
        $types = '';
        $values = [];
        foreach ($params as $param) {
            if (is_int($param)) $types .= 'i';
            elseif (is_double($param)) $types .= 'd';
            else $types .= 's';
            $values[] = $param;
        }
        $stmt->bind_param($types, ...$values);
    }
    
    $stmt->execute();
    return $stmt;
}

function fetchAll($sql, $params = []) {
    $stmt = query($sql, $params);
    if (!$stmt) return [];
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function fetchOne($sql, $params = []) {
    $stmt = query($sql, $params);
    if (!$stmt) return null;
    return $stmt->get_result()->fetch_assoc();
}

function countRecords($table, $condition = '', $params = []) {
    $sql = "SELECT COUNT(*) as total FROM $table";
    if ($condition) {
        $sql .= " WHERE $condition";
    }
    $result = fetchOne($sql, $params);
    return $result ? $result['total'] : 0;
}

function paginate($sql, $page = 1, $perPage = 10, $params = []) {
    $page = max(1, (int)$page);
    $offset = ($page - 1) * $perPage;
    
    $sql .= " LIMIT $offset, $perPage";
    
    return [
        'data' => fetchAll($sql, $params),
        'page' => $page,
        'per_page' => $perPage,
        'offset' => $offset
    ];
}

function getSetting($key, $default = '') {
    $result = fetchOne("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
    return $result ? $result['setting_value'] : $default;
}

function updateSetting($key, $value) {
    query("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?", [$key, $value, $value]);
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}

function sanitize($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize($value);
        }
        return $data;
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function uploadFile($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp']) {
    if (!isset($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedTypes)) {
        return null;
    }
    
    $newName = uniqid() . '_' . time() . '.' . $ext;
    $target = UPLOAD_DIR . $newName;
    
    if (move_uploaded_file($file['tmp_name'], $target)) {
        return $newName;
    }
    return null;
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect(ADMIN_URL . '/login.php');
    }
}

function loginAdmin($user) {
    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_name'] = $user['full_name'];
    $_SESSION['admin_role'] = $user['role'];
}

function logoutAdmin() {
    session_destroy();
    redirect(ADMIN_URL . '/login.php');
}

function getCurrentAdmin() {
    if (!isLoggedIn()) return null;
    return [
        'id' => $_SESSION['admin_id'],
        'name' => $_SESSION['admin_name'],
        'role' => $_SESSION['admin_role']
    ];
}

function getCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}