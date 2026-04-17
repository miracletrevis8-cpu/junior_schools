<?php
require_once '../config.php';

if (isLoggedIn()) {
    redirect(ADMIN_URL . '/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    $user = fetchOne("SELECT * FROM users WHERE username = ?", [$username]);
    
    if ($user && password_verify($password, $user['password'])) {
        loginAdmin($user);
        redirect(ADMIN_URL . '/index.php');
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        body { display: block; }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-box">
            <div class="logo" style="flex-direction: column; margin-bottom: 30px;">
                <span style="font-size: 28px; font-weight: 800; letter-spacing: 2px; background: linear-gradient(135deg, #fff, var(--primary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1.2;">Timnah Schools</span>
                <div class="glass-sphere" style="width: 160px; height: 160px; background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: 50%; border: 1px solid rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: center; margin: 25px auto 0; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2), inset 0 0 15px rgba(255, 255, 255, 0.05); position: relative; overflow: hidden;">
                    <!-- Shining effect animation -->
                    <div style="position: absolute; top: 0; left: -150%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent); animation: glass-shine 3s infinite; transform: skewX(-20deg);"></div>
                    <img src="<?php echo ASSETS_URL; ?>/images/logo.png" alt="Timnah Schools Logo" class="login-logo" style="width: 100px; height: auto; position: relative; z-index: 1;">
                </div>
                <style>
                    @keyframes glass-shine {
                        0% { left: -150%; opacity: 0; }
                        50% { opacity: 1; }
                        100% { left: 150%; opacity: 0; }
                    }
                </style>
            </div>
            <p>Admin Panel Login</p>
            
            <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" required autofocus>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 16px;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <p style="margin-top: 25px; font-size: 13px; color: var(--text-muted);">
                Default: admin / admin123
            </p>
        </div>
    </div>
</body>
</html>