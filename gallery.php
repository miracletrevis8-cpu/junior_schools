<?php 
require_once 'config.php';
$page = 'gallery';
include 'includes/header.php'; 

$gallery = fetchAll("SELECT * FROM gallery WHERE status = 'active' ORDER BY sort_order DESC");
?>

<section class="page-header">
    <div class="container">
        <h1>Gallery</h1>
        <p>Explore moments from our school life</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($gallery)): ?>
        <div class="alert alert-success">Gallery coming soon!</div>
        <?php else: ?>
        <div class="gallery-grid">
            <?php foreach ($gallery as $item): ?>
            <div class="gallery-item">
                <img src="<?php echo UPLOADS_URL . '/' . $item['image']; ?>" alt="<?php echo sanitize($item['title']); ?>">
                <div class="gallery-overlay">
                    <h4><?php echo sanitize($item['title']); ?></h4>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>