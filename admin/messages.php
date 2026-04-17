<?php
require_once '../config.php';
requireLogin();
include 'header.php';

$message = '';
$page = (int)($_GET['page'] ?? 1);
$search = sanitize($_GET['search'] ?? '');
$perPage = 15;

if (isset($_GET['read'])) {
    query("UPDATE messages SET is_read = 1 WHERE id = ?", [(int)$_GET['read']]);
}

if (isset($_GET['delete'])) {
    query("DELETE FROM messages WHERE id = ?", [(int)$_GET['delete']]);
    $message = 'Message deleted!';
}

if (isset($_GET['read_all'])) {
    query("UPDATE messages SET is_read = 1");
    $message = 'All messages marked as read!';
}

$where = $search ? "name LIKE '%$search%' OR email LIKE '%$search%' OR subject LIKE '%$search%' OR message LIKE '%$search%'" : '';
$total = countRecords('messages', $where);
$totalPages = ceil($total / $perPage);
$page = min($page, max(1, $totalPages));
$offset = ($page - 1) * $perPage;

$messages = fetchAll("SELECT * FROM messages ORDER BY created_at DESC LIMIT $offset, $perPage");
$unreadCount = countRecords('messages', "is_read = 0");
?>

<div class="top-bar">
    <div>
        <h1>Messages</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>Messages</span>
        </div>
    </div>
    <?php if ($unreadCount > 0): ?>
    <div class="card-actions">
        <a href="?read_all=1" class="btn btn-outline"><i class="fas fa-check-double"></i> Mark all as read</a>
    </div>
    <?php endif; ?>
</div>

<?php if ($message): ?>
<div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>All Messages (<?php echo $total; ?>)</h2>
    </div>
    <div class="card-body">
        <form method="GET" class="search-bar">
            <input type="text" name="search" class="form-control" placeholder="Search messages..." value="<?php echo $search; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            <?php if ($search): ?>
            <a href="messages.php" class="btn btn-outline">Clear</a>
            <?php endif; ?>
        </form>
        
        <?php if (empty($messages)): ?>
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
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                    <tr style="<?php echo !$msg['is_read'] ? 'background: rgba(0, 200, 83, 0.05);' : ''; ?>">
                        <td>
                            <div><?php echo sanitize($msg['name']); ?></div>
                            <small style="color: var(--text-muted);"><?php echo sanitize($msg['email']); ?></small>
                        </td>
                        <td><?php echo sanitize($msg['subject']); ?></td>
                        <td>
                            <span style="max-width: 200px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?php echo sanitize($msg['message']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y g:i A', strtotime($msg['created_at'])); ?></td>
                        <td>
                            <?php if ($msg['is_read']): ?>
                            <span class="badge badge-success">Read</span>
                            <?php else: ?>
                            <span class="badge badge-warning">New</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a href="?read=<?php echo $msg['id']; ?><?php echo $search ? '&search=' . $search : ''; ?>" class="btn btn-outline btn-sm" title="View"><i class="fas fa-eye"></i></a>
                            <a href="mailto:<?php echo sanitize($msg['email']); ?>" class="btn btn-outline btn-sm" title="Reply"><i class="fas fa-reply"></i></a>
                            <a href="?delete=<?php echo $msg['id']; ?>" class="btn btn-danger btn-sm btn-delete" title="Delete"><i class="fas fa-trash"></i></a>
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

<?php include 'footer.php'; ?>