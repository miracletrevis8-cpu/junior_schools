<?php 
require_once 'config.php';
$page = 'vacancies';
include 'includes/header.php'; 

$vacancies = fetchAll("SELECT * FROM vacancies WHERE is_active = 1 ORDER BY created_at DESC");
?>

<section class="page-header">
    <div class="container">
        <h1>Careers</h1>
        <p>Join our team of educators</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="staff-intro">
            <p><i class="fas fa-briefcase"></i> We are always looking for dedicated and passionate educators to join our team. If you share our vision for academic excellence and holistic student development, we invite you to explore career opportunities at Timnah Schools.</p>
        </div>
        
        <?php if (empty($vacancies)): ?>
        <div class="alert alert-success">No current vacancies. Check back later!</div>
        <?php else: ?>
        <div class="card-grid">
            <?php foreach ($vacancies as $job): ?>
            <div class="card glass-card">
                <div class="card-content">
                    <span class="card-badge"><?php echo sanitize($job['job_type']); ?></span>
                    <h3><?php echo sanitize($job['title']); ?></h3>
                    <div class="card-meta">
                        <span><i class="fas fa-building"></i> <?php echo sanitize($job['department']); ?></span>
                        <?php if ($job['salary_range']): ?>
                        <span><i class="fas fa-money-bill"></i> <?php echo sanitize($job['salary_range']); ?></span>
                        <?php endif; ?>
                    </div>
                    <p><?php echo sanitize($job['description']); ?></p>
                    <h4 style="margin-top: 15px; margin-bottom: 10px;">Requirements:</h4>
                    <p><?php echo sanitize($job['requirements']); ?></p>
                    <?php if ($job['closing_date']): ?>
                    <p style="margin-top: 15px; color: var(--primary);"><i class="fas fa-calendar"></i> Closing Date: <?php echo date('M d, Y', strtotime($job['closing_date'])); ?></p>
                    <?php endif; ?>
                    <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-primary" style="margin-top: 20px;">Apply Now</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>