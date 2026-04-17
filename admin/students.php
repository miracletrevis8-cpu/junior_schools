<?php
require_once '../config.php';
requireLogin();
include 'header.php';

$message = '';
$page = (int)($_GET['page'] ?? 1);
$search = sanitize($_GET['search'] ?? '');
$status = sanitize($_GET['status'] ?? '');
$perPage = 15;

if (isset($_GET['approve'])) {
    query("UPDATE students SET status = 'approved' WHERE id = ?", [(int)$_GET['approve']]);
    $message = 'Student approved!';
}

if (isset($_GET['reject'])) {
    query("UPDATE students SET status = 'rejected' WHERE id = ?", [(int)$_GET['reject']]);
    $message = 'Student rejected!';
}

if (isset($_GET['delete'])) {
    query("DELETE FROM students WHERE id = ?", [(int)$_GET['delete']]);
    $message = 'Registration deleted!';
}

$conditions = [];
if ($search) {
    $conditions[] = "(first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%' OR parent_name LIKE '%$search%')";
}
if ($status) {
    $conditions[] = "status = '$status'";
}
$where = !empty($conditions) ? implode(' AND ', $conditions) : '';

$total = countRecords('students', $where);
$totalPages = ceil($total / $perPage);
$page = min($page, max(1, $totalPages));
$offset = ($page - 1) * $perPage;

$students = fetchAll("SELECT * FROM students ORDER BY created_at DESC LIMIT $offset, $perPage");
?>

<div class="top-bar">
    <div>
        <h1>Student Registrations</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>Students</span>
        </div>
    </div>
</div>

<?php if ($message): ?>
<div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>All Registrations (<?php echo $total; ?>)</h2>
    </div>
    <div class="card-body">
        <form method="GET" class="filters">
            <input type="text" name="search" class="form-control" style="flex: 1;" placeholder="Search by name, email, parent..." value="<?php echo $search; ?>">
            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Approved</option>
                <option value="rejected" <?php echo $status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            <?php if ($search || $status): ?>
            <a href="students.php" class="btn btn-outline">Clear</a>
            <?php endif; ?>
        </form>
        
        <?php if (empty($students)): ?>
        <div class="empty-state">
            <i class="fas fa-user-graduate"></i>
            <h3>No registrations yet</h3>
        </div>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Grade</th>
                        <th>Parent</th>
                        <th>Contact</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                    <tr>
                        <td>
                            <div><?php echo sanitize($s['first_name'] . ' ' . $s['last_name']); ?></div>
                            <small style="color: var(--text-muted);"><?php echo $s['date_of_birth'] ? date('M d, Y', strtotime($s['date_of_birth'])) : ''; ?></small>
                        </td>
                        <td><?php echo sanitize($s['grade']); ?></td>
                        <td><?php echo sanitize($s['parent_name']); ?></td>
                        <td>
                            <div><?php echo sanitize($s['phone']); ?></div>
                            <small style="color: var(--text-muted);"><?php echo sanitize($s['email']); ?></small>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($s['created_at'])); ?></td>
                        <td>
                            <?php if ($s['status'] === 'approved'): ?>
                            <span class="badge badge-success">Approved</span>
                            <?php elseif ($s['status'] === 'rejected'): ?>
                            <span class="badge badge-danger">Rejected</span>
                            <?php else: ?>
                            <span class="badge badge-warning">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <?php if ($s['status'] === 'pending'): ?>
                            <a href="?approve=<?php echo $s['id']; ?><?php echo $search ? '&search=' . $search : ''; ?><?php echo $status ? '&status=' . $status : ''; ?>" class="btn btn-primary btn-sm" title="Approve"><i class="fas fa-check"></i></a>
                            <a href="?reject=<?php echo $s['id']; ?><?php echo $search ? '&search=' . $search : ''; ?><?php echo $status ? '&status=' . $status : ''; ?>" class="btn btn-outline btn-sm" title="Reject"><i class="fas fa-times"></i></a>
                            <?php endif; ?>
                            <a href="?delete=<?php echo $s['id']; ?>" class="btn btn-danger btn-sm btn-delete" title="Delete"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . $search : ''; ?><?php echo $status ? '&status=' . $status : ''; ?>"><i class="fas fa-chevron-left"></i></a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . $search : ''; ?><?php echo $status ? '&status=' . $status : ''; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . $search : ''; ?><?php echo $status ? '&status=' . $status : ''; ?>"><i class="fas fa-chevron-right"></i></a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>