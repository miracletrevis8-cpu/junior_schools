<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <button class="sidebar-close"><i class="fas fa-times"></i></button>
            <div class="sidebar-header">
                <a href="index.php" class="logo">
                    <img src="<?php echo ASSETS_URL; ?>/images/logo.png" alt="Timnah Schools Logo" style="width: 40px; height: auto;">
                    <span>Timnah Admin</span>
                </a>
            </div>
            <div class="sidebar-search">
                <input type="text" class="form-control" placeholder="Search...">
                <i class="fas fa-search"></i>
            </div>
            <nav class="sidebar-menu">
                <div class="menu-title">Main</div>
                <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
                
                <div class="menu-title">Management</div>
                <a href="events.php"><i class="fas fa-calendar"></i> Events</a>
                <a href="staff.php"><i class="fas fa-users"></i> Staff</a>
                <a href="gallery.php"><i class="fas fa-images"></i> Gallery</a>
                <a href="facilities.php"><i class="fas fa-building"></i> Facilities</a>
                <a href="news.php"><i class="fas fa-newspaper"></i> News</a>
                <a href="vacancies.php"><i class="fas fa-briefcase"></i> Vacancies</a>
                
                <div class="menu-title">Data</div>
                <a href="students.php">
                    <i class="fas fa-user-graduate"></i> Students
                    <?php $pending = countRecords('students', "status = 'pending'"); if($pending > 0): ?>
                    <span class="badge"><?php echo $pending; ?></span>
                    <?php endif; ?>
                </a>
                <a href="messages.php">
                    <i class="fas fa-envelope"></i> Messages
                    <?php $unread = countRecords('messages', "is_read = 0"); if($unread > 0): ?>
                    <span class="badge"><?php echo $unread; ?></span>
                    <?php endif; ?>
                </a>
                
                <div class="menu-title">System</div>
                <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
            </nav>
        </aside>
        <button class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
        <div class="sidebar-overlay"></div>
        <main class="main-content">