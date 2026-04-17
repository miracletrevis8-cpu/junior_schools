<?php 
require_once 'config.php';
$page = 'facilities';
include 'includes/header.php'; 

$facilities = fetchAll("SELECT * FROM facilities WHERE status = 'active' ORDER BY sort_order ASC");
?>

<section class="page-header">
    <div class="container">
        <h1>Our Facilities</h1>
        <p>Explore our world-class infrastructure</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($facilities)): ?>
        <div class="alert alert-success">Facilities information coming soon!</div>
        <?php else: ?>
        <div class="card-grid">
            <?php foreach ($facilities as $facility): ?>
            <div class="card">
                <div class="card-image">
                    <?php if ($facility['image']): ?>
                    <img src="<?php echo UPLOADS_URL . '/' . $facility['image']; ?>" alt="<?php echo sanitize($facility['title']); ?>">
                    <?php else: ?>
                    <img src="<?php echo ASSETS_URL; ?>/images/facility-placeholder.jpg" alt="Facility">
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <h3><?php echo sanitize($facility['title']); ?></h3>
                    <p><?php echo sanitize($facility['description']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>