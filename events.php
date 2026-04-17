<?php 
require_once 'config.php';
$page = 'events';
include 'includes/header.php'; 

$events = fetchAll("SELECT * FROM events WHERE status = 'active' ORDER BY event_date ASC");
?>

<section class="page-header">
    <div class="container">
        <h1>Events</h1>
        <p>Stay updated with our upcoming events and activities</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($events)): ?>
        <div class="alert alert-success">No upcoming events at the moment. Check back soon!</div>
        <?php else: ?>
        <div class="event-list">
            <?php foreach ($events as $event): ?>
            <div class="event-card">
                <div class="event-date">
                    <span class="day"><?php echo date('d', strtotime($event['event_date'])); ?></span>
                    <span class="month"><?php echo date('M', strtotime($event['event_date'])); ?></span>
                </div>
                <div class="event-details">
                    <?php if ($event['image']): ?>
                    <img src="<?php echo UPLOADS_URL . '/' . $event['image']; ?>" alt="<?php echo sanitize($event['title']); ?>" style="width: 100%; max-width: 200px; border-radius: 10px; margin-bottom: 15px;">
                    <?php endif; ?>
                    <h3><?php echo sanitize($event['title']); ?></h3>
                    <p><?php echo sanitize($event['description']); ?></p>
                    <div class="event-meta">
                        <span><i class="fas fa-clock"></i> <?php echo $event['event_time'] ?: 'All day'; ?></span>
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo sanitize($event['location']); ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>