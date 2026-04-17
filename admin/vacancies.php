<?php
require_once '../config.php';
requireLogin();
include 'header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $department = sanitize($_POST['department']);
    $job_type = sanitize($_POST['job_type']);
    $description = sanitize($_POST['description']);
    $requirements = sanitize($_POST['requirements']);
    $salary_range = sanitize($_POST['salary_range']);
    $closing_date = sanitize($_POST['closing_date']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (isset($_POST['id']) && $_POST['id']) {
        query("UPDATE vacancies SET title = ?, department = ?, job_type = ?, description = ?, requirements = ?, salary_range = ?, closing_date = ?, is_active = ? WHERE id = ?", 
            [$title, $department, $job_type, $description, $requirements, $salary_range, $closing_date, $is_active, (int)$_POST['id']]);
        $message = 'Vacancy updated!';
    } else {
        query("INSERT INTO vacancies (title, department, job_type, description, requirements, salary_range, closing_date, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", 
            [$title, $department, $job_type, $description, $requirements, $salary_range, $closing_date, $is_active]);
        $message = 'Vacancy added!';
    }
}

if (isset($_GET['delete'])) {
    query("DELETE FROM vacancies WHERE id = ?", [(int)$_GET['delete']]);
    $message = 'Vacancy deleted!';
}

$vacancies = fetchAll("SELECT * FROM vacancies ORDER BY created_at DESC");
$edit = isset($_GET['edit']) ? fetchOne("SELECT * FROM vacancies WHERE id = ?", [(int)$_GET['edit']]) : null;
?>

<div class="top-bar">
    <div><h1>Vacancies Management</h1><div class="breadcrumb"><a href="index.php">Home</a><span>/</span><span>Vacancies</span></div></div>
</div>

<?php if ($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>

<div class="card">
    <div class="card-header"><h2><?php echo $edit ? 'Edit Vacancy' : 'Add New Vacancy'; ?></h2></div>
    <div class="card-body">
        <form method="POST">
            <?php if ($edit): ?><input type="hidden" name="id" value="<?php echo $edit['id']; ?>"><?php endif; ?>
            <div class="form-row">
                <div class="form-group"><label>Job Title *</label><input type="text" name="title" class="form-control" value="<?php echo $edit ? sanitize($edit['title']) : ''; ?>" required></div>
                <div class="form-group"><label>Department</label><input type="text" name="department" class="form-control" value="<?php echo $edit ? sanitize($edit['department']) : ''; ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Job Type</label>
                    <select name="job_type" class="form-control">
                        <option value="Full Time" <?php echo $edit && $edit['job_type'] === 'Full Time' ? 'selected' : ''; ?>>Full Time</option>
                        <option value="Part Time" <?php echo $edit && $edit['job_type'] === 'Part Time' ? 'selected' : ''; ?>>Part Time</option>
                        <option value="Contract" <?php echo $edit && $edit['job_type'] === 'Contract' ? 'selected' : ''; ?>>Contract</option>
                    </select>
                </div>
                <div class="form-group"><label>Salary Range</label><input type="text" name="salary_range" class="form-control" value="<?php echo $edit ? sanitize($edit['salary_range']) : ''; ?>" placeholder="$30,000 - $50,000"></div>
            </div>
            <div class="form-group"><label>Description</label><textarea name="description" class="form-control"><?php echo $edit ? sanitize($edit['description']) : ''; ?></textarea></div>
            <div class="form-group"><label>Requirements</label><textarea name="requirements" class="form-control"><?php echo $edit ? sanitize($edit['requirements']) : ''; ?></textarea></div>
            <div class="form-row">
                <div class="form-group"><label>Closing Date</label><input type="date" name="closing_date" class="form-control" value="<?php echo $edit ? $edit['closing_date'] : ''; ?>"></div>
                <div class="form-group">
                    <label>Active</label>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" <?php echo !$edit || $edit['is_active'] ? 'checked' : ''; ?>>
                        <span>Publish this vacancy</span>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $edit ? 'Update' : 'Add Vacancy'; ?></button>
            <?php if ($edit): ?><a href="vacancies.php" class="btn btn-outline">Cancel</a><?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>All Vacancies</h2></div>
    <div class="card-body">
        <?php if (empty($vacancies)): ?><div class="empty-state"><i class="fas fa-briefcase"></i><h3>No vacancies</h3></div>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <tr><th>Title</th><th>Type</th><th>Department</th><th>Status</th><th>Actions</th></tr>
                <?php foreach ($vacancies as $v): ?>
                <tr>
                    <td><?php echo sanitize($v['title']); ?></td>
                    <td><?php echo sanitize($v['job_type']); ?></td>
                    <td><?php echo sanitize($v['department']); ?></td>
                    <td><span class="badge badge-<?php echo $v['is_active'] ? 'success' : 'danger'; ?>"><?php echo $v['is_active'] ? 'Active' : 'Inactive'; ?></span></td>
                    <td class="actions">
                        <a href="?edit=<?php echo $v['id']; ?>" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                        <a href="?delete=<?php echo $v['id']; ?>" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>