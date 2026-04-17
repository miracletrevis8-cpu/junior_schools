<?php
require_once '../config.php';
requireLogin();
include 'header.php';

$message = '';
$page = (int)($_GET['page'] ?? 1);
$perPage = 20;

// Add new image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $title = sanitize($_POST['title']);
    $category = sanitize($_POST['category']);
    $description = sanitize($_POST['description']);
    
    $image = '';
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = uploadFile($_FILES['image']);
    }
    
    if ($image) {
        query("INSERT INTO gallery (title, category, description, image) VALUES (?, ?, ?, ?)", [$title, $category, $description, $image]);
        $message = 'Image uploaded successfully!';
    } else {
        $message = 'Failed to upload image.';
    }
}

// Delete
if (isset($_GET['delete'])) {
    query("DELETE FROM gallery WHERE id = ?", [(int)$_GET['delete']]);
    $message = 'Image deleted!';
}

$total = countRecords('gallery');
$totalPages = ceil($total / $perPage);
$page = min($page, max(1, $totalPages));
$offset = ($page - 1) * $perPage;

$gallery = fetchAll("SELECT * FROM gallery ORDER BY created_at DESC LIMIT $offset, $perPage");
?>

<div class="top-bar">
    <div>
        <h1>Gallery Management</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>Gallery</span>
        </div>
    </div>
</div>

<?php if ($message): ?>
<div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Upload New Image</h2>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" id="uploadForm">
            <div class="form-row">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Image title">
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="form-control">
                        <option value="general">General</option>
                        <option value="events">Events</option>
                        <option value="sports">Sports</option>
                        <option value="academic">Academic</option>
                        <option value="arts">Arts</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" placeholder="Image description"></textarea>
            </div>
            <div class="form-group">
                <label>Image *</label>
                <div class="upload-area" id="dropZone" onclick="document.getElementById('imageInput').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload or drag and drop</p>
                    <p style="font-size: 12px; color: var(--text-muted);">PNG, JPG, GIF up to 5MB</p>
                </div>
                <input type="file" name="image" id="imageInput" accept="image/*" style="display: none;" onchange="previewUpload(this)">
                <div id="uploadPreview" class="image-preview" style="display: none;"></div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Image</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>All Images (<?php echo $total; ?>)</h2>
    </div>
    <div class="card-body">
        <?php if (empty($gallery)): ?>
        <div class="empty-state">
            <i class="fas fa-images"></i>
            <h3>No images yet</h3>
            <p>Upload your first image above</p>
        </div>
        <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
            <?php foreach ($gallery as $g): ?>
            <div style="position: relative; border-radius: 12px; overflow: hidden; background: var(--bg-card-hover);">
                <img src="../uploads/<?php echo $g['image']; ?>" style="width: 100%; height: 180px; object-fit: cover;">
                <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 15px; background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                    <p style="font-weight: 500; margin-bottom: 5px;"><?php echo sanitize($g['title']); ?></p>
                    <span class="badge badge-info"><?php echo $g['category']; ?></span>
                </div>
                <div style="position: absolute; top: 10px; right: 10px; display: flex; gap: 8px;">
                    <a href="?delete=<?php echo $g['id']; ?>" class="btn btn-danger btn-sm btn-delete" style="padding: 8px 12px;"><i class="fas fa-trash"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>"><i class="fas fa-chevron-left"></i></a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>"><i class="fas fa-chevron-right"></i></a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
var dropZone = document.getElementById('dropZone');

dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.style.borderColor = 'var(--primary)';
    this.style.background = 'rgba(0, 200, 83, 0.1)';
});

dropZone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.style.borderColor = 'var(--border)';
    this.style.background = 'transparent';
});

dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.style.borderColor = 'var(--border)';
    this.style.background = 'transparent';
    
    var files = e.dataTransfer.files;
    if (files.length) {
        document.getElementById('imageInput').files = files;
        previewUpload(document.getElementById('imageInput'));
    }
});

function previewUpload(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('uploadPreview');
            preview.style.display = 'flex';
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include 'footer.php'; ?>