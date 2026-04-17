<?php
require_once '../config.php';
requireLogin();
include 'header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $slug = sanitize($_POST['slug'] ?: strtolower(str_replace(' ', '-', $title)));
    $content = sanitize($_POST['content']);
    $excerpt = sanitize($_POST['excerpt']);
    $author = sanitize($_POST['author']);
    $status = sanitize($_POST['status']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = uploadFile($_FILES['image']);
    }
    
    if (isset($_POST['id']) && $_POST['id']) {
        $id = (int)$_POST['id'];
        if ($image) {
            query("UPDATE news SET title = ?, slug = ?, content = ?, excerpt = ?, author = ?, image = ?, is_featured = ?, status = ? WHERE id = ?", 
                [$title, $slug, $content, $excerpt, $author, $image, $is_featured, $status, $id]);
        } else {
            query("UPDATE news SET title = ?, slug = ?, content = ?, excerpt = ?, author = ?, is_featured = ?, status = ? WHERE id = ?", 
                [$title, $slug, $content, $excerpt, $author, $is_featured, $status, $id]);
        }
        $message = 'News updated!';
    } else {
        if ($image) {
            query("INSERT INTO news (title, slug, content, excerpt, author, image, is_featured, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", 
                [$title, $slug, $content, $excerpt, $author, $image, $is_featured, $status]);
        } else {
            query("INSERT INTO news (title, slug, content, excerpt, author, is_featured, status) VALUES (?, ?, ?, ?, ?, ?, ?)", 
                [$title, $slug, $content, $excerpt, $author, $is_featured, $status]);
        }
        $message = 'News published!';
    }
}

if (isset($_GET['delete'])) {
    query("DELETE FROM news WHERE id = ?", [(int)$_GET['delete']]);
    $message = 'News deleted!';
}

$news = fetchAll("SELECT * FROM news ORDER BY created_at DESC");
$edit = isset($_GET['edit']) ? fetchOne("SELECT * FROM news WHERE id = ?", [(int)$_GET['edit']]) : null;
?>

<div class="top-bar">
    <div><h1>News Management</h1><div class="breadcrumb"><a href="index.php">Home</a><span>/</span><span>News</span></div></div>
</div>

<?php if ($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>

<div class="card">
    <div class="card-header"><h2><?php echo $edit ? 'Edit News' : 'Add News'; ?></h2></div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <?php if ($edit): ?><input type="hidden" name="id" value="<?php echo $edit['id']; ?>"><?php endif; ?>
            <div class="form-row">
                <div class="form-group"><label>Title *</label><input type="text" name="title" class="form-control" value="<?php echo $edit ? sanitize($edit['title']) : ''; ?>" required></div>
                <div class="form-group"><label>Slug</label><input type="text" name="slug" class="form-control" value="<?php echo $edit ? sanitize($edit['slug']) : ''; ?>"></div>
            </div>
            <div class="form-group"><label>Content</label><textarea name="content" class="form-control" rows="8"><?php echo $edit ? sanitize($edit['content']) : ''; ?></textarea></div>
            <div class="form-row">
                <div class="form-group"><label>Excerpt</label><input type="text" name="excerpt" class="form-control" value="<?php echo $edit ? sanitize($edit['excerpt']) : ''; ?>"></div>
                <div class="form-group"><label>Author</label><input type="text" name="author" class="form-control" value="<?php echo $edit ? sanitize($edit['author']) : ''; ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="published" <?php echo $edit && $edit['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                        <option value="draft" <?php echo $edit && $edit['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="is_featured" value="1" <?php echo $edit && $edit['is_featured'] ? 'checked' : ''; ?>>
                    <span>Featured article</span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $edit ? 'Update' : 'Publish'; ?></button>
            <?php if ($edit): ?><a href="news.php" class="btn btn-outline">Cancel</a><?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>All News</h2></div>
    <div class="card-body">
        <?php if (empty($news)): ?><div class="empty-state"><i class="fas fa-newspaper"></i><h3>No news yet</h3></div>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <tr><th>Title</th><th>Author</th><th>Status</th><th>Date</th><th>Actions</th></tr>
                <?php foreach ($news as $n): ?>
                <tr>
                    <td><?php echo sanitize($n['title']); ?></td>
                    <td><?php echo sanitize($n['author']); ?></td>
                    <td><span class="badge badge-<?php echo $n['status'] === 'published' ? 'success' : 'warning'; ?>"><?php echo $n['status']; ?></span></td>
                    <td><?php echo date('M d, Y', strtotime($n['created_at'])); ?></td>
                    <td class="actions">
                        <a href="?edit=<?php echo $n['id']; ?>" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                        <a href="?delete=<?php echo $n['id']; ?>" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>