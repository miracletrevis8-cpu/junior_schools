<?php 
require_once 'config.php';
$page = 'about';
include 'includes/header.php'; 

$staff = fetchAll("SELECT * FROM staff WHERE status = 'active' ORDER BY sort_order ASC LIMIT 4");
?>

<section class="page-header">
    <div class="container">
        <h1>About Us</h1>
        <p>Learn more about Timnah Schools and our mission</p>
    </div>
</section>

<section class="about-section">
    <div class="container">
        <div class="about-grid">
            <div class="about-image fade-in">
                <img src="<?php echo ASSETS_URL; ?>/images/about.jpg" alt="About Timnah Schools">
            </div>
            <div class="about-content fade-in">
                <h2>Our Mission</h2>
                <p>We build a holistic child who will excel academically as well as embrace the available opportunities and activities in the school to discover their full potential, build a profound character through enhancement of life skills. The school purposes to produce children who will be higher achievers for a lifetime.</p>
                <p>Located in Kasana-Luweero, we are a "Home Away from Home," providing a secure and physically beautiful environment conducive to quality learning at both Nursery and Primary levels.</p>
                <div class="about-stats">
                    <div class="stat-item">
                        <div class="stat-number" data-target="500">0</div>
                        <div class="stat-label">Students</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-target="50">0</div>
                        <div class="stat-label">Teachers</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-target="20">0</div>
                        <div class="stat-label">Years Experience</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="glass-card-wrapper fade-in" style="margin-top: 80px;">
            <div class="glass-card">
                <div class="glass-content">
                    <div class="glass-header">
                        <i class="fas fa-history glass-icon"></i>
                        <h2>Background</h2>
                    </div>
                    <p>Timnah Schools is located in Kasana town, Mabale ward in Luweero Town Council, Luweero District, 54km from Kampala along the Kampala-Gulu highway. The school is a mixed day and boarding licensed and registered by the Ministry of Education and Sports. The school environment is elegant and assures evident outcomes among pupils.</p>
                </div>
            </div>
            
            <div class="glass-card" style="margin-top: 40px;">
                <div class="glass-content">
                    <div class="glass-header">
                        <i class="fas fa-bullseye glass-icon"></i>
                        <h2>Our Objectives</h2>
                    </div>
                    <ul class="philosophy-list" style="list-style: none;">
                        <li>Nurture God-fearing citizens (Proverbs 22:6).</li>
                        <li>Provide a secure and physically beautiful environment conducive to learning.</li>
                        <li>Model and mentor confident pupils with high self-esteem.</li>
                        <li>Instill discipline and encourage participation in co-curricular activities.</li>
                        <li>Attain high pass rates at both nursery and primary levels.</li>
                    </ul>
                </div>
            </div>
            
            <div class="glass-card" style="margin-top: 40px;">
                <div class="glass-content">
                    <div class="glass-header">
                        <i class="fas fa-feather-pointed glass-icon"></i>
                        <h2>Our Philosophy</h2>
                    </div>
                    <p>Our philosophy is to value all learners for their individual abilities and special talents. Our curriculum aims at among others:-</p>
                    
                    <ol class="philosophy-list">
                        <li>Creating individuals who are resourceful and responsible.</li>
                        <li>To promote national unity and harmonious communities.</li>
                        <li>Creating individuals who will display a high sense of discipline, ethical and spiritual values.</li>
                        <li>To promote collective take responsibility, love and care for others and respect public property among pupils.</li>
                        <li>Produce individuals with skills for analyzing and solving problems, emotionally intelligent and have a high sense of innovation and positive attitudes.</li>
                        <li>Form individuals who will uphold high integrity.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features" style="background: var(--bg-dark);">
    <div class="container">
        <div class="section-header">
            <h2>Our Values</h2>
        </div>
        <div class="feature-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-lightbulb"></i></div>
                <h3>Innovation</h3>
                <p>We encourage creative thinking and innovative solutions</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-handshake"></i></div>
                <h3>Integrity</h3>
                <p>We uphold the highest ethical standards in everything we do</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-heart"></i></div>
                <h3>Compassion</h3>
                <p>We care for each student with passion and dedication</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-trophy"></i></div>
                <h3>Excellence</h3>
                <p>We strive for excellence in all aspects of education</p>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($staff)): ?>
<section class="section" style="background: var(--bg-darker);">
    <div class="container">
        <div class="section-header">
            <h2>Meet Our Leadership</h2>
            <p>Our dedicated team of educators and administrators</p>
        </div>
        <div class="card-grid">
            <?php foreach ($staff as $member): ?>
            <div class="staff-card fade-in">
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
                    <?php if ($member['qualification']): ?>
                    <div class="qualification"><?php echo sanitize($member['qualification']); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>