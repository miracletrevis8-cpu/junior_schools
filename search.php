<?php 
require_once 'config.php';
$page = 'search';
include 'includes/header.php';

$searchQuery = sanitize($_GET['q'] ?? '');
$results = [
    'events' => [],
    'students' => [],
    'news' => [],
    'vacancies' => []
];
$total = 0;

if ($searchQuery && strlen($searchQuery) >= 2) {
    $searchTerm = "%{$searchQuery}%";
    
    $results['events'] = fetchAll("SELECT id, title, description, event_date as date, location, 'event' as type FROM events WHERE status = 'active' AND (title LIKE ? OR description LIKE ?) ORDER BY event_date DESC", [$searchTerm, $searchTerm]);
    
    $results['news'] = fetchAll("SELECT id, title, excerpt, created_at as date, 'news' as type FROM news WHERE status = 'published' AND (title LIKE ? OR excerpt LIKE ?) ORDER BY created_at DESC", [$searchTerm, $searchTerm]);
    
    $results['vacancies'] = fetchAll("SELECT id, title, department, description, 'vacancy' as type FROM vacancies WHERE is_active = 1 AND (title LIKE ? OR department LIKE ?) ORDER BY created_at DESC", [$searchTerm, $searchTerm]);
    
    $results['students'] = fetchAll("SELECT id, CONCAT(first_name, ' ', last_name) as title, grade, parent_name, 'student' as type FROM students WHERE status = 'pending' AND (first_name LIKE ? OR last_name LIKE ? OR parent_name LIKE ?) ORDER BY created_at DESC", [$searchTerm, $searchTerm, $searchTerm]);
    
    $total = count($results['events']) + count($results['students']) + count($results['news']) + count($results['vacancies']);
}
?>

<section class="page-header">
    <div class="container">
        <h1>Search Results</h1>
        <p><?php echo $searchQuery ? 'Results for "' . sanitize($searchQuery) . '"' : 'Enter a search term'; ?></p>
    </div>
</section>

<section class="search-page">
    <div class="container">
        <form method="GET" class="search-page-form">
            <div class="search-wrapper" style="max-width: 600px; margin: 0 auto 50px;">
                <input type="text" name="q" placeholder="Search..." value="<?php echo sanitize($searchQuery); ?>" required>
                <button type="submit"><i class="fas fa-search"></i> Search</button>
            </div>
        </form>
        
        <?php if (!$searchQuery): ?>
        <div class="alert alert-success">Please enter a search term to find events, news, and vacancies.</div>
        
        <?php elseif ($total === 0): ?>
        <div class="alert alert-error">No results found for "<?php echo sanitize($searchQuery); ?>". Try different keywords.</div>
        
        <?php else: ?>
        <p style="margin-bottom: 30px; color: var(--text-gray);">Found <?php echo $total; ?> result(s)</p>
        
        <div class="search-page-results">
            <?php if (!empty($results['events'])): ?>
            <h2>Events (<?php echo count($results['events']); ?>)</h2>
            <?php foreach ($results['events'] as $event): ?>
            <a href="<?php echo SITE_URL; ?>/events.php" class="search-page-item">
                <i class="fas fa-calendar"></i>
                <div class="info">
                    <h3><?php echo sanitize($event['title']); ?></h3>
                    <p><?php echo date('M d, Y', strtotime($event['date'])); ?> - <?php echo sanitize($event['location']); ?></p>
                </div>
            </a>
            <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (!empty($results['news'])): ?>
            <h2>News (<?php echo count($results['news']); ?>)</h2>
            <?php foreach ($results['news'] as $news): ?>
            <a href="<?php echo SITE_URL; ?>/news.php" class="search-page-item">
                <i class="fas fa-newspaper"></i>
                <div class="info">
                    <h3><?php echo sanitize($news['title']); ?></h3>
                    <p><?php echo sanitize($news['excerpt']); ?></p>
                </div>
            </a>
            <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (!empty($results['vacancies'])): ?>
            <h2>Careers (<?php echo count($results['vacancies']); ?>)</h2>
            <?php foreach ($results['vacancies'] as $job): ?>
            <a href="<?php echo SITE_URL; ?>/vacancies.php" class="search-page-item">
                <i class="fas fa-briefcase"></i>
                <div class="info">
                    <h3><?php echo sanitize($job['title']); ?></h3>
                    <p><?php echo sanitize($job['department']); ?></p>
                </div>
            </a>
            <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (!empty($results['students'])): ?>
            <h2>Pending Registrations (<?php echo count($results['students']); ?>)</h2>
            <?php foreach ($results['students'] as $student): ?>
            <a href="<?php echo SITE_URL; ?>/admin/students.php" class="search-page-item">
                <i class="fas fa-user-graduate"></i>
                <div class="info">
                    <h3><?php echo sanitize($student['title']); ?></h3>
                    <p>Grade: <?php echo sanitize($student['grade']); ?> | Parent: <?php echo sanitize($student['parent_name']); ?></p>
                </div>
            </a>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>