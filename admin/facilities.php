<?php
require_once '../config.php';
requireLogin();
include 'header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $icon = sanitize($_POST['icon']);
    $status = sanitize($_POST['status']);
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = uploadFile($_FILES['image']);
    }
    
    if (isset($_POST['id']) && $_POST['id']) {
        $id = (int)$_POST['id'];
        if ($image) {
            query("UPDATE facilities SET title = ?, description = ?, icon = ?, image = ?, status = ? WHERE id = ?", [$title, $description, $icon, $image, $status, $id]);
        } else {
            query("UPDATE facilities SET title = ?, description = ?, icon = ?, status = ? WHERE id = ?", [$title, $description, $icon, $status, $id]);
        }
        $message = 'Facility updated!';
    } else {
        if ($image) {
            query("INSERT INTO facilities (title, description, icon, image, status) VALUES (?, ?, ?, ?, ?)", [$title, $description, $icon, $image, $status]);
        } else {
            query("INSERT INTO facilities (title, description, icon, status) VALUES (?, ?, ?, ?)", [$title, $description, $icon, $status]);
        }
        $message = 'Facility added!';
    }
}

if (isset($_GET['delete'])) {
    query("DELETE FROM facilities WHERE id = ?", [(int)$_GET['delete']]);
    $message = 'Facility deleted!';
}

$facilities = fetchAll("SELECT * FROM facilities ORDER BY sort_order ASC");
$edit = isset($_GET['edit']) ? fetchOne("SELECT * FROM facilities WHERE id = ?", [(int)$_GET['edit']]) : null;
?>

<div class="top-bar">
    <div>
        <h1>Facilities Management</h1>
        <div class="breadcrumb"><a href="index.php">Home</a><span>/</span><span>Facilities</span></div>
    </div>
</div>

<?php if ($message): ?>
<div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h2><?php echo $edit ? 'Edit Facility' : 'Add New Facility'; ?></h2></div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <?php if ($edit): ?>
            <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
            <?php endif; ?>
            <div class="form-row">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" class="form-control" value="<?php echo $edit ? sanitize($edit['title']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Icon (Font Awesome)</label>
                    <input type="text" name="icon" class="form-control" value="<?php echo $edit ? sanitize($edit['icon']) : ''; ?>" placeholder="fas fa-building">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"><?php echo $edit ? sanitize($edit['description']) : ''; ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="active" <?php echo $edit && $edit['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $edit && $edit['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $edit ? 'Update' : 'Add Facility'; ?></button>
            <?php if ($edit): ?><a href="facilities.php" class="btn btn-outline">Cancel</a><?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>All Facilities</h2></div>
    <div class="card-body">
        <?php if (empty($facilities)): ?>
        <div class="empty-state"><i class="fas fa-building"></i><h3>No facilities yet</h3></div>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <tr><th>Image</th><th>Title</th><th>Icon</th><th>Status</th><th>Actions</th></tr>
                <?php foreach ($facilities as $f): ?>
                <tr>
                    <td><?php echo $f['image'] ? '<img src="../uploads/' . $f['image'] . '" style="width:60px;height:40px;object-fit:cover;border-radius:6px;">' : '-'; ?></td>
                    <td><?php echo sanitize($f['title']); ?></td>
                    <td><i class="<?php echo $f['icon']; ?>"></i> <?php echo sanitize($f['icon']); ?></td>
                    <td><span class="badge badge-<?php echo $f['status'] === 'active' ? 'success' : 'danger'; ?>"><?php echo $f['status']; ?></span></td>
                    <td class="actions">
                        <a href="?edit=<?php echo $f['id']; ?>" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                        <a href="?delete=<?php echo $f['id']; ?>" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>