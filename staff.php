<?php 
require_once 'config.php';
$page = 'staff';
include 'includes/header.php'; 

$allStaff = fetchAll("SELECT * FROM staff WHERE status = 'active' ORDER BY sort_order ASC");

$teachingStaff = [];
$nonTeachingStaff = [];

foreach ($allStaff as $s) {
    if (isset($s['is_teaching_staff']) && $s['is_teaching_staff']) {
        $teachingStaff[] = $s;
    } else {
        $nonTeachingStaff[] = $s;
    }
}
?>

<section class="page-header">
    <div class="container">
        <h1>Our Staff</h1>
        <p>Meet our dedicated team of educators</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="staff-intro">
            <p><i class="fas fa-graduation-cap"></i> The head teacher and the entire staff are highly qualified and selected to meet the high standards of the school. As part of the school culture, the school maintains a good teacher-pupil ratio that ensures individual interaction between the teachers and learners. The staff is very passionate about the holistic nurturing of all learners.</p>
        </div>
        
        <?php if (!empty($teachingStaff)): ?>
        <h2 class="staff-section-title">Teaching Staff</h2>
        <div class="card-grid">
            <?php foreach ($teachingStaff as $member): ?>
            <div class="staff-card">
                <div class="staff-image">
                    <?php if ($member['image']): ?>
                    <img src="<?php echo UPLOADS_URL . '/' . $member['image']; ?>" alt="<?php echo sanitize($member['full_name']); ?>">
                    <?php else: ?>
                    <img src="<?php echo ASSETS_URL; ?>/images/staff-placeholder.jpg" alt="Staff">
                    <?php endif; ?>
                </div>
                <div class="staff-info">
                    <h3><?php echo sanitize($member['full_name']); ?></h3>
                    <div class="position"><?php echo sanitize($member['position']); ?></div>
                    <?php if ($member['department']): ?>
                    <div class="qualification"><?php echo sanitize($member['department']); ?></div>
                    <?php endif; ?>
                    <?php if ($member['qualification']): ?>
                    <div class="qualification"><?php echo sanitize($member['qualification']); ?></div>
                    <?php endif; ?>
                    <?php if ($member['email']): ?>
                    <div class="qualification"><?php echo sanitize($member['email']); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($nonTeachingStaff)): ?>
        <h2 class="staff-section-title">Non-Teaching Staff</h2>
        <div class="card-grid">
            <?php foreach ($nonTeachingStaff as $member): ?>
            <div class="staff-card">
                <div class="staff-image">
                    <?php if ($member['image']): ?>
                    <img src="<?php echo UPLOADS_URL . '/' . $member['image']; ?>" alt="<?php echo sanitize($member['full_name']); ?>">
                    <?php else: ?>
                    <img src="<?php echo ASSETS_URL; ?>/images/staff-placeholder.jpg" alt="Staff">
                    <?php endif; ?>
                </div>
                <div class="staff-info">
                    <h3><?php echo sanitize($member['full_name']); ?></h3>
                    <div class="position"><?php echo sanitize($member['position']); ?></div>
                    <?php if ($member['department']): ?>
                    <div class="qualification"><?php echo sanitize($member['department']); ?></div>
                    <?php endif; ?>
                    <?php if ($member['qualification']): ?>
                    <div class="qualification"><?php echo sanitize($member['qualification']); ?></div>
                    <?php endif; ?>
                    <?php if ($member['email']): ?>
                    <div class="qualification"><?php echo sanitize($member['email']); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <?php if (empty($teachingStaff) && empty($nonTeachingStaff)): ?>
        <div class="alert alert-success">Staff information coming soon!</div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>