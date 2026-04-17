<?php
require_once 'config.php';
$page = 'home';
include 'includes/header.php';

$featuredEvents = fetchAll("SELECT * FROM events WHERE status = 'active' AND is_featured = 1 ORDER BY event_date ASC LIMIT 3");
$facilities = fetchAll("SELECT * FROM facilities WHERE status = 'active' ORDER BY sort_order ASC LIMIT 4");
$announcements = fetchAll("SELECT * FROM announcements WHERE is_active = 1 ORDER BY created_at DESC LIMIT 3");
?>

<section class="hero">
    <div class="hero-slideshow">
        <div class="slide" style="background-image: url('<?php echo UPLOADS_URL; ?>/transport.png');"></div>
        <div class="slide" style="background-image: url('<?php echo UPLOADS_URL; ?>/security.png');"></div>
        <div class="slide" style="background-image: url('<?php echo UPLOADS_URL; ?>/morning_circle.png');"></div>
        <div class="slide" style="background-image: url('<?php echo UPLOADS_URL; ?>/nursery_section.png');"></div>
    </div>
    <div class="container">
        <div class="hero-content" style="position: relative; z-index: 2;">
            <span class="hero-subtitle hero-animate-1">
                <i class="fas fa-star"></i>
                <?php echo getSetting('site_tagline', 'Excellence in Education'); ?>
            </span>
            <h1 class="hero-animate-2"><?php echo getSetting('hero_title', 'Welcome to Timnah Schools'); ?></h1>
            <p class="hero-animate-3">
                <?php echo getSetting('hero_subtitle', 'Nurturing Future Leaders Through Excellence in Education'); ?>
            </p>
            <div class="hero-buttons hero-animate-4">
                <a href="<?php echo SITE_URL; ?>/register.php" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Enroll Now
                </a>
                <a href="<?php echo SITE_URL; ?>/about.php" class="btn btn-outline">
                    Learn More <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="search-section">
    <div class="container">
        <div class="search-tabs">
            <button class="search-tab active" data-type="all">All</button>
            <button class="search-tab" data-type="events">Events</button>
            <button class="search-tab" data-type="news">News</button>
        </div>
        <form class="search-wrapper" action="<?php echo SITE_URL; ?>/search.php" method="GET">
            <input type="text" name="q" placeholder="Search for events, news, staff..." required>
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>
    </div>
</section>

<?php if (!empty($featuredEvents)): ?>
    <section class="section" style="background: var(--bg-dark); position: relative; overflow: hidden;">
        <div class="section-bg"></div>
        <div class="container">
            <div class="section-header">
                <h2>Upcoming Events</h2>
                <p>Stay updated with our latest events and activities</p>
            </div>
            <div class="card-grid stagger-children">
                <?php foreach ($featuredEvents as $event): ?>
                    <div class="card glass-card">
                        <div class="card-image">
                            <div class="card-image">
                                <?php if (!empty($event['image'])): ?>
                                    <img src="<?php echo UPLOADS_URL . '/' . $event['image']; ?>"
                                        alt="<?php echo sanitize($event['title']); ?>">
                                <?php else: ?>
                                    <img src="<?php echo ASSETS_URL; ?>/images/event-placeholder.jpg" alt="Event">
                                <?php endif; ?>
                                <span class="card-badge">Featured</span>
                            </div>
                            <div class="card-content">
                                <div class="card-meta">
                                    <span><i class="fas fa-calendar"></i>
                                        <?php echo date('M d, Y', strtotime($event['event_date'])); ?></span>
                                    <span><i class="fas fa-map-marker-alt"></i>
                                        <?php echo sanitize($event['location']); ?></span>
                                </div>
                                <h3><?php echo sanitize($event['title']); ?></h3>
                                <p><?php echo sanitize(substr($event['description'], 0, 100)); ?>...</p>
                                <a href="<?php echo SITE_URL; ?>/events.php" class="btn btn-outline"
                                    style="margin-top: 15px;">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
    </section>
<?php endif; ?>

<section class="features">
    <div class="container">
        <div class="section-header">
            <h2>Why Choose Timnah?</h2>
            <p>Discover what makes us different from other schools</p>
        </div>
        <div class="feature-grid stagger-children">
            <div class="feature-card" style="animation-delay: 0.1s;">
                <div class="feature-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <h3>Expert Faculty</h3>
                <p>Our teachers are highly qualified with years of experience in education</p>
            </div>
            <div class="feature-card" style="animation-delay: 0.2s;">
                <div class="feature-icon"><i class="fas fa-laptop-code"></i></div>
                <h3>Modern Facilities</h3>
                <p>State-of-the-art classrooms and technology to enhance learning</p>
            </div>
            <div class="feature-card" style="animation-delay: 0.3s;">
                <div class="feature-icon"><i class="fas fa-users"></i></div>
                <h3>Student Support</h3>
                <p>Personalized attention to help every student succeed</p>
            </div>
            <div class="feature-card" style="animation-delay: 0.4s;">
                <div class="feature-icon"><i class="fas fa-award"></i></div>
                <h3>Academic Excellence</h3>
                <p>Consistent top performance in academics and extracurricular activities</p>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($facilities)): ?>
    <section class="section" style="background: var(--bg-dark); position: relative; overflow: hidden;">
        <div class="section-bg"></div>
        <div class="container">
            <div class="section-header">
                <h2>Our Facilities</h2>
                <p>Explore our world-class infrastructure</p>
            </div>
            <div class="card-grid">
                <?php foreach ($facilities as $facility): ?>
                    <div class="card glass-card">
                        <div class="card-image">
                            <?php if (!empty($facility['image'])): ?>
                                <img src="<?php echo UPLOADS_URL . '/' . $facility['image']; ?>"
                                    alt="<?php echo sanitize($facility['title']); ?>">
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
        </div>
    </section>
<?php endif; ?>

<?php if (!empty($announcements)): ?>
    <section class="section" style="background: var(--bg-darker); position: relative; overflow: hidden;">
        <div class="section-bg"></div>
        <div class="container">
            <div class="section-header">
                <h2>Announcements</h2>
                <p>Important notices and updates</p>
            </div>
            <div class="card-grid">
                <?php foreach ($announcements as $announcement): ?>
                    <div class="card glass-card">
                        <div class="card-content">
                            <div class="card-meta">
                                <span><i class="fas fa-bullhorn"></i> Announcement</span>
                            </div>
                            <h3><?php echo sanitize($announcement['title']); ?></h3>
                            <p><?php echo sanitize($announcement['content']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<section class="cta-section">
    <div class="container">
        <h2>Ready to Join Timnah Schools?</h2>
        <p>Enroll your child today and give them the gift of quality education</p>
        <a href="<?php echo SITE_URL; ?>/register.php" class="btn">Enroll Now <i class="fas fa-arrow-right"></i></a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>