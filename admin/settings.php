<?php
require_once '../config.php';
requireLogin();
include 'header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (in_array($key, ['site_name', 'site_tagline', 'site_email', 'site_phone', 'site_address', 'site_facebook', 'site_twitter', 'site_instagram', 'hero_title', 'hero_subtitle', 'about_content'])) {
            updateSetting($key, sanitize($value));
        }
    }
    $message = 'Settings saved!';
}

$settings = [
    'site_name' => getSetting('site_name', 'Timnah Schools'),
    'site_tagline' => getSetting('site_tagline', 'Excellence in Education'),
    'site_email' => getSetting('site_email', ''),
    'site_phone' => getSetting('site_phone', ''),
    'site_address' => getSetting('site_address', ''),
    'site_facebook' => getSetting('site_facebook', ''),
    'site_twitter' => getSetting('site_twitter', ''),
    'site_instagram' => getSetting('site_instagram', ''),
    'hero_title' => getSetting('hero_title', ''),
    'hero_subtitle' => getSetting('hero_subtitle', ''),
    'about_content' => getSetting('about_content', ''),
];
?>

<div class="top-bar">
    <div><h1>Settings</h1><div class="breadcrumb"><a href="index.php">Home</a><span>/</span><span>Settings</span></div></div>
</div>

<?php if ($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>

<div class="card">
    <div class="card-header"><h2>Site Settings</h2></div>
    <div class="card-body">
        <form method="POST">
            <h3 style="margin-bottom: 20px; color: var(--primary);">General</h3>
            <div class="form-row">
                <div class="form-group"><label>Site Name</label><input type="text" name="site_name" class="form-control" value="<?php echo sanitize($settings['site_name']); ?>"></div>
                <div class="form-group"><label>Tagline</label><input type="text" name="site_tagline" class="form-control" value="<?php echo sanitize($settings['site_tagline']); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Email</label><input type="email" name="site_email" class="form-control" value="<?php echo sanitize($settings['site_email']); ?>"></div>
                <div class="form-group"><label>Phone</label><input type="tel" name="site_phone" class="form-control" value="<?php echo sanitize($settings['site_phone']); ?>"></div>
            </div>
            <div class="form-group"><label>Address</label><input type="text" name="site_address" class="form-control" value="<?php echo sanitize($settings['site_address']); ?>"></div>
            
            <h3 style="margin: 30px 0 20px; color: var(--primary);">Social Links</h3>
            <div class="form-row">
                <div class="form-group"><label>Facebook</label><input type="url" name="site_facebook" class="form-control" value="<?php echo sanitize($settings['site_facebook']); ?>"></div>
                <div class="form-group"><label>Twitter</label><input type="url" name="site_twitter" class="form-control" value="<?php echo sanitize($settings['site_twitter']); ?>"></div>
            </div>
            <div class="form-group"><label>Instagram</label><input type="url" name="site_instagram" class="form-control" value="<?php echo sanitize($settings['site_instagram']); ?>"></div>
            
            <h3 style="margin: 30px 0 20px; color: var(--primary);">Home Page</h3>
            <div class="form-group"><label>Hero Title</label><input type="text" name="hero_title" class="form-control" value="<?php echo sanitize($settings['hero_title']); ?>"></div>
            <div class="form-group"><label>Hero Subtitle</label><input type="text" name="hero_subtitle" class="form-control" value="<?php echo sanitize($settings['hero_subtitle']); ?>"></div>
            <div class="form-group"><label>About Content</label><textarea name="about_content" class="form-control" rows="4"><?php echo sanitize($settings['about_content']); ?></textarea></div>
            
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Change Password</h2></div>
    <div class="card-body">
        <form method="POST" action="change-password.php">
            <div class="form-group"><label>Current Password</label><input type="password" name="current_password" class="form-control" required></div>
            <div class="form-group"><label>New Password</label><input type="password" name="new_password" class="form-control" required></div>
            <div class="form-group"><label>Confirm Password</label><input type="password" name="confirm_password" class="form-control" required></div>
            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>