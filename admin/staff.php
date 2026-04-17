<?php
require_once '../config.php';
requireLogin();
include 'header.php';

$message = '';
$page = (int)($_GET['page'] ?? 1);
$search = sanitize($_GET['search'] ?? '');
$perPage = 10;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $position = sanitize($_POST['position']);
    $department = sanitize($_POST['department']);
    $qualification = sanitize($_POST['qualification']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $bio = sanitize($_POST['bio']);
    $status = sanitize($_POST['status']);
    $is_teaching_staff = isset($_POST['is_teaching_staff']) ? 1 : 0;
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = uploadFile($_FILES['image']);
    }
    
    if (isset($_POST['id']) && $_POST['id']) {
        $id = (int)$_POST['id'];
        if ($image) {
            query("UPDATE staff SET full_name = ?, position = ?, department = ?, qualification = ?, email = ?, phone = ?, bio = ?, image = ?, is_teaching_staff = ?, status = ? WHERE id = ?", 
                [$full_name, $position, $department, $qualification, $email, $phone, $bio, $image, $is_teaching_staff, $status, $id]);
        } else {
            query("UPDATE staff SET full_name = ?, position = ?, department = ?, qualification = ?, email = ?, phone = ?, bio = ?, is_teaching_staff = ?, status = ? WHERE id = ?", 
                [$full_name, $position, $department, $qualification, $email, $phone, $bio, $is_teaching_staff, $status, $id]);
        }
        $message = 'Staff updated!';
    } else {
        if ($image) {
            query("INSERT INTO staff (full_name, position, department, qualification, email, phone, bio, image, is_teaching_staff, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", 
                [$full_name, $position, $department, $qualification, $email, $phone, $bio, $image, $is_teaching_staff, $status]);
        } else {
            query("INSERT INTO staff (full_name, position, department, qualification, email, phone, bio, is_teaching_staff, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", 
                [$full_name, $position, $department, $qualification, $email, $phone, $bio, $is_teaching_staff, $status]);
        }
        $message = 'Staff added!';
    }
}

if (isset($_GET['delete'])) {
    query("DELETE FROM staff WHERE id = ?", [(int)$_GET['delete']]);
    $message = 'Staff deleted!';
}

$where = $search ? "full_name LIKE '%$search%' OR position LIKE '%$search%' OR department LIKE '%$search%'" : '';
$total = countRecords('staff', $where);
$totalPages = ceil($total / $perPage);
$page = min($page, max(1, $totalPages));
$offset = ($page - 1) * $perPage;

$staff = fetchAll("SELECT * FROM staff ORDER BY sort_order ASC, id DESC LIMIT $offset, $perPage");
$editStaff = null;
if (isset($_GET['edit'])) {
    $editStaff = fetchOne("SELECT * FROM staff WHERE id = ?", [(int)$_GET['edit']]);
}
?>

<div class="top-bar">
    <div>
        <h1>Staff Management</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>Staff</span>
        </div>
    </div>
</div>

<?php if ($message): ?>
<div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2><?php echo $editStaff ? 'Edit Staff' : 'Add New Staff'; ?></h2>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <?php if ($editStaff): ?>
            <input type="hidden" name="id" value="<?php echo $editStaff['id']; ?>">
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" class="form-control" value="<?php echo $editStaff ? sanitize($editStaff['full_name']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Position *</label>
                    <input type="text" name="position" class="form-control" value="<?php echo $editStaff ? sanitize($editStaff['position']) : ''; ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Department</label>
                    <input type="text" name="department" class="form-control" value="<?php echo $editStaff ? sanitize($editStaff['department']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Qualification</label>
                    <input type="text" name="qualification" class="form-control" value="<?php echo $editStaff ? sanitize($editStaff['qualification']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $editStaff ? sanitize($editStaff['email']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" class="form-control" value="<?php echo $editStaff ? sanitize($editStaff['phone']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label>Bio</label>
                <textarea name="bio" class="form-control"><?php echo $editStaff ? sanitize($editStaff['bio']) : ''; ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Photo</label>
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this, 'staffPreview')">
                    <?php if ($editStaff && $editStaff['image']): ?>
                    <div class="image-preview" id="staffPreview">
                        <img src="../uploads/<?php echo $editStaff['image']; ?>">
                        <span>Current photo</span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="active" <?php echo $editStaff && $editStaff['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $editStaff && $editStaff['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary"><?php echo $editStaff ? 'Update Staff' : 'Add Staff'; ?></button>
            <?php if ($editStaff): ?>
            <a href="staff.php" class="btn btn-outline">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>All Staff (<?php echo $total; ?>)</h2>
    </div>
    <div class="card-body">
        <form method="GET" class="search-bar">
            <input type="text" name="search" class="form-control" placeholder="Search staff..." value="<?php echo $search; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            <?php if ($search): ?>
            <a href="staff.php" class="btn btn-outline">Clear</a>
            <?php endif; ?>
        </form>
        
        <?php if (empty($staff)): ?>
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <h3>No staff found</h3>
        </div>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staff as $s): ?>
                    <tr>
                        <td>
                            <?php if ($s['image']): ?>
                            <img src="../uploads/<?php echo $s['image']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                            <?php else: ?>
                            <i class="fas fa-user" style="color: var(--text-muted); font-size: 24px;"></i>
                            <?php endif; ?>
                        </td>
                        <td><?php echo sanitize($s['full_name']); ?></td>
                        <td><?php echo sanitize($s['position']); ?></td>
                        <td><?php echo sanitize($s['department']); ?></td>
                        <td><span class="badge badge-<?php echo $s['status'] === 'active' ? 'success' : 'danger'; ?>"><?php echo $s['status']; ?></span></td>
                        <td class="actions">
                            <a href="?edit=<?php echo $s['id']; ?>" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="?delete=<?php echo $s['id']; ?>" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
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
                preview.className = 'image-preview';
                input.parentNode.appendChild(preview);
            }
            preview.innerHTML = '<img src="' + e.target.result + '">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include 'footer.php'; ?>