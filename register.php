<?php 
require_once 'config.php';
$page = 'register';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $date_of_birth = sanitize($_POST['date_of_birth']);
    $grade = sanitize($_POST['grade']);
    $parent_name = sanitize($_POST['parent_name']);
    $parent_phone = sanitize($_POST['parent_phone']);
    $address = sanitize($_POST['address']);
    
    $stmt = getDB()->prepare("INSERT INTO students (first_name, last_name, email, phone, date_of_birth, grade, parent_name, parent_phone, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $first_name, $last_name, $email, $phone, $date_of_birth, $grade, $parent_name, $parent_phone, $address);
    
    if ($stmt->execute()) {
        $_SESSION['registration_success'] = 'Registration successful! We will contact you soon.';
        redirect('register.php');
    } else {
        $message = 'Error registering. Please try again.';
    }
}

if (isset($_SESSION['registration_success'])) {
    $message = $_SESSION['registration_success'];
    unset($_SESSION['registration_success']);
}

include 'includes/header.php'; 
?>

<section class="page-header">
    <div class="container">
        <h1>Student Registration</h1>
        <p>Enroll your child at Timnah Schools</p>
    </div>
</section>

<section class="form-section">
    <div class="container">
        <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <div class="form-container">
            <h2>Registration Form</h2>
            <form method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Student First Name *</label>
                        <input type="text" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label>Student Last Name *</label>
                        <input type="text" name="last_name" required>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email">
                    </div>
                    <div class="form-group">
                        <label>Phone Number *</label>
                        <input type="tel" name="phone" required>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Date of Birth *</label>
                        <input type="date" name="date_of_birth" required>
                    </div>
                    <div class="form-group">
                        <label>Grade/Class *</label>
                        <select name="grade" required>
                            <option value="">Select Grade</option>
                            <option value="Nursery">Nursery</option>
                            <option value="Kindergarten">Kindergarten</option>
                            <option value="Grade 1">Grade 1</option>
                            <option value="Grade 2">Grade 2</option>
                            <option value="Grade 3">Grade 3</option>
                            <option value="Grade 4">Grade 4</option>
                            <option value="Grade 5">Grade 5</option>
                            <option value="Grade 6">Grade 6</option>
                            <option value="Grade 7">Grade 7</option>
                            <option value="Grade 8">Grade 8</option>
                            <option value="Grade 9">Grade 9</option>
                            <option value="Grade 10">Grade 10</option>
                            <option value="Grade 11">Grade 11</option>
                            <option value="Grade 12">Grade 12</option>
                        </select>
                    </div>
                </div>
                <hr style="margin: 25px 0; border: none; border-top: 1px solid var(--light-gray);">
                <h3 style="margin-bottom: 20px;">Parent/Guardian Information</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Parent Name *</label>
                        <input type="text" name="parent_name" required>
                    </div>
                    <div class="form-group">
                        <label>Parent Phone *</label>
                        <input type="tel" name="parent_phone" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Registration <i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>