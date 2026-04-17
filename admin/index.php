<?php
require_once '../config.php';
requireLogin();
include 'header.php';

$stats = [
    'events' => countRecords('events', "status = 'active'"),
    'staff' => countRecords('staff', "status = 'active'"),
    'gallery' => countRecords('gallery', "status = 'active'"),
    'students' => countRecords('students'),
    'messages' => countRecords('messages'),
    'vacancies' => countRecords('vacancies', "is_active = 1"),
    'news' => countRecords('news', "status = 'published'"),
    'unread_messages' => countRecords('messages', "is_read = 0"),
];

$recentMessages = fetchAll("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5");
$recentStudents = fetchAll("SELECT * FROM students ORDER BY created_at DESC LIMIT 5");
$upcomingEvents = fetchAll("SELECT * FROM events WHERE status = 'active' ORDER BY event_date ASC LIMIT 3");
?>

<div class="top-bar">
    <div>
        <h1>Dashboard</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>Dashboard</span>
        </div>
    </div>
    <div class="user-menu">
        <div class="user-info">
            <div class="name"><?php echo getCurrentAdmin()['name']; ?></div>
            <div class="role"><?php echo ucfirst(getCurrentAdmin()['role']); ?></div>
        </div>
        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-calendar"></i></div>
        <div class="stat-info">
            <h3>Events</h3>
            <div class="number"><?php echo $stats['events']; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h3>Staff Members</h3>
            <div class="number"><?php echo $stats['staff']; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-images"></i></div>
        <div class="stat-info">
            <h3>Gallery Images</h3>
            <div class="number"><?php echo $stats['gallery']; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
        <div class="stat-info">
            <h3>Registrations</h3>
            <div class="number"><?php echo $stats['students']; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-envelope"></i></div>
        <div class="stat-info">
            <h3>Messages</h3>
            <div class="number"><?php echo $stats['messages']; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
        <div class="stat-info">
            <h3>Vacancies</h3>
            <div class="number"><?php echo $stats['vacancies']; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
        <div class="stat-info">
            <h3>Published News</h3>
            <div class="number"><?php echo $stats['news']; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-bell"></i></div>
        <div class="stat-info">
            <h3>Unread Messages</h3>
            <div class="number"><?php echo $stats['unread_messages']; ?></div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <div class="card">
        <div class="card-header">
            <h2>Recent Messages</h2>
            <a href="messages.php" class="btn btn-outline btn-sm">View All</a>
        </div>
        <?php if (empty($recentMessages)): ?>
        <div class="empty-state">
            <i class="fas fa-envelope"></i>
            <h3>No messages yet</h3>
            <p>Contact form submissions will appear here</p>
        </div>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentMessages as $msg): ?>
                    <tr>
                        <td><?php echo sanitize($msg['name']); ?></td>
                        <td><?php echo sanitize($msg['subject']); ?></td>
                        <td><?php echo date('M d', strtotime($msg['created_at'])); ?></td>
                        <td>
                            <?php if ($msg['is_read']): ?>
                            <span class="badge badge-success">Read</span>
                            <?php else: ?>
                            <span class="badge badge-warning">New</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2>Recent Registrations</h2>
            <a href="students.php" class="btn btn-outline btn-sm">View All</a>
        </div>
        <?php if (empty($recentStudents)): ?>
        <div class="empty-state">
            <i class="fas fa-user-graduate"></i>
            <h3>No registrations yet</h3>
            <p>Student registrations will appear here</p>
        </div>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Grade</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentStudents as $student): ?>
                    <tr>
                        <td><?php echo sanitize($student['first_name'] . ' ' . $student['last_name']); ?></td>
                        <td><?php echo sanitize($student['grade']); ?></td>
                        <td><?php echo date('M d', strtotime($student['created_at'])); ?></td>
                        <td>
                            <?php if ($student['status'] === 'approved'): ?>
                            <span class="badge badge-success">Approved</span>
                            <?php elseif ($student['status'] === 'rejected'): ?>
                            <span class="badge badge-danger">Rejected</span>
                            <?php else: ?>
                            <span class="badge badge-warning">Pending</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($upcomingEvents)): ?>
<div class="card">
    <div class="card-header">
        <h2>Upcoming Events</h2>
        <a href="events.php" class="btn btn-outline btn-sm">Manage Events</a>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($upcomingEvents as $event): ?>
                <tr>
                    <td><?php echo sanitize($event['title']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($event['event_date'])); ?></td>
                    <td><?php echo sanitize($event['location']); ?></td>
                    <td><span class="badge badge-success"><?php echo $event['status']; ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php include 'footer.php'; ?>