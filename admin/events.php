<?php
require_once '../config.php';
requireLogin();
include 'header.php';

$message = '';
$page = (int)($_GET['page'] ?? 1);
$search = sanitize($_GET['search'] ?? '');
$perPage = 10;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $event_date = sanitize($_POST['event_date']);
    $event_time = sanitize($_POST['event_time']);
    $location = sanitize($_POST['location']);
    $status = sanitize($_POST['status']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = uploadFile($_FILES['image']);
    }
    
    if (isset($_POST['id']) && $_POST['id']) {
        $id = (int)$_POST['id'];
        if ($image) {
            query("UPDATE events SET title = ?, description = ?, event_date = ?, event_time = ?, location = ?, image = ?, is_featured = ?, status = ? WHERE id = ?", 
                [$title, $description, $event_date, $event_time, $location, $image, $is_featured, $status, $id]);
        } else {
            query("UPDATE events SET title = ?, description = ?, event_date = ?, event_time = ?, location = ?, is_featured = ?, status = ? WHERE id = ?", 
                [$title, $description, $event_date, $event_time, $location, $is_featured, $status, $id]);
        }
        $message = 'Event updated successfully!';
    } else {
        if ($image) {
            query("INSERT INTO events (title, description, event_date, event_time, location, image, is_featured, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", 
                [$title, $description, $event_date, $event_time, $location, $image, $is_featured, $status]);
        } else {
            query("INSERT INTO events (title, description, event_date, event_time, location, is_featured, status) VALUES (?, ?, ?, ?, ?, ?, ?)", 
                [$title, $description, $event_date, $event_time, $location, $is_featured, $status]);
        }
        $message = 'Event created successfully!';
    }
}

// Delete
if (isset($_GET['delete'])) {
    query("DELETE FROM events WHERE id = ?", [(int)$_GET['delete']]);
    $message = 'Event deleted successfully!';
}

// Fetch with pagination
$where = $search ? "title LIKE '%$search%' OR description LIKE '%$search%'" : '';
$total = countRecords('events', $where);
$totalPages = ceil($total / $perPage);
$page = min($page, max(1, $totalPages));
$offset = ($page - 1) * $perPage;

$events = fetchAll("SELECT * FROM events ORDER BY event_date DESC LIMIT $offset, $perPage");
$editEvent = null;
if (isset($_GET['edit'])) {
    $editEvent = fetchOne("SELECT * FROM events WHERE id = ?", [(int)$_GET['edit']]);
}
?>

<div class="top-bar">
    <div>
        <h1>Events Management</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>Events</span>
        </div>
    </div>
</div>

<?php if ($message): ?>
<div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2><?php echo $editEvent ? 'Edit Event' : 'Add New Event'; ?></h2>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" id="eventForm">
            <?php if ($editEvent): ?>
            <input type="hidden" name="id" value="<?php echo $editEvent['id']; ?>">
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" class="form-control" value="<?php echo $editEvent ? sanitize($editEvent['title']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Event Date *</label>
                    <input type="date" name="event_date" class="form-control" value="<?php echo $editEvent ? $editEvent['event_date'] : ''; ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"><?php echo $editEvent ? sanitize($editEvent['description']) : ''; ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Event Time</label>
                    <input type="time" name="event_time" class="form-control" value="<?php echo $editEvent ? $editEvent['event_time'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" value="<?php echo $editEvent ? sanitize($editEvent['location']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="active" <?php echo $editEvent && $editEvent['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="past" <?php echo $editEvent && $editEvent['status'] === 'past' ? 'selected' : ''; ?>>Past</option>
                        <option value="cancelled" <?php echo $editEvent && $editEvent['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Featured</label>
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" value="1" <?php echo $editEvent && $editEvent['is_featured'] ? 'checked' : ''; ?>>
                        <span>Mark as featured event</span>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this, 'eventPreview')">
                <?php if ($editEvent && $editEvent['image']): ?>
                <div class="image-preview" id="eventPreview">
                    <img src="../uploads/<?php echo $editEvent['image']; ?>" alt="Event Image">
                    <span>Current image</span>
                </div>
                <?php endif; ?>
            </div>
            
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary"><?php echo $editEvent ? 'Update Event' : 'Create Event'; ?></button>
                <?php if ($editEvent): ?>
                <a href="events.php" class="btn btn-outline">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>All Events (<?php echo $total; ?>)</h2>
    </div>
    <div class="card-body">
        <form method="GET" class="search-bar">
            <input type="text" name="search" class="form-control" placeholder="Search events..." value="<?php echo $search; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            <?php if ($search): ?>
            <a href="events.php" class="btn btn-outline">Clear</a>
            <?php endif; ?>
        </form>
        
        <?php if (empty($events)): ?>
        <div class="empty-state">
            <i class="fas fa-calendar"></i>
            <h3>No events found</h3>
            <p>Create your first event above</p>
        </div>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                    <tr>
                        <td>
                            <?php if ($event['image']): ?>
                            <img src="../uploads/<?php echo $event['image']; ?>" style="width: 60px; height: 40px; object-fit: cover; border-radius: 6px;">
                            <?php else: ?>
                            <span style="color: var(--text-muted);">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo sanitize($event['title']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($event['event_date'])); ?></td>
                        <td><?php echo sanitize($event['location']); ?></td>
                        <td><span class="badge badge-<?php echo $event['status'] === 'active' ? 'success' : ($event['status'] === 'cancelled' ? 'danger' : 'warning'); ?>"><?php echo $event['status']; ?></span></td>
                        <td><?php echo $event['is_featured'] ? '<i class="fas fa-star" style="color: var(--primary);"></i>' : '-'; ?></td>
                        <td class="actions">
                            <a href="?edit=<?php echo $event['id']; ?>" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="?delete=<?php echo $event['id']; ?>" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . $search : ''; ?>"><i class="fas fa-chevron-left"></i></a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . $search : ''; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . $search : ''; ?>"><i class="fas fa-chevron-right"></i></a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById(previewId);
            if (!preview) {
                preview = document.createElement('div');
                preview.id = previewId;
                preview.className = 'image-preview';
                input.parentNode.appendChild(preview);
            }
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include 'footer.php'; ?>