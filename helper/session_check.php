<?php
// session_check.php - Include this at the top of protected pages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

// Optional: Check role if needed
function checkRole($allowed_roles = []) {
    if (!isset($_SESSION['role'])) {
        // Fetch role from database if not in session
        require_once __DIR__ . '/../config/db.php';
        $stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if ($user) {
            $_SESSION['role'] = $user['role'];
        } else {
            session_destroy();
            header("Location: ../index.php");
            exit;
        }
    }
    
    if (!empty($allowed_roles) && !in_array($_SESSION['role'], $allowed_roles)) {
        header("Location: ../index.php");
        exit;
    }
}
?>
