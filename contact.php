<?php 
require_once 'config.php';
$page = 'contact';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $subject = sanitize($_POST['subject']);
    $message_text = sanitize($_POST['message']);
    
    $stmt = getDB()->prepare("INSERT INTO messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message_text);
    
    if ($stmt->execute()) {
        $message = 'Thank you for your message. We will get back to you soon!';
    } else {
        $message = 'Error sending message. Please try again.';
    }
}

include 'includes/header.php'; 
?>

<section class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you</p>
    </div>
</section>

<section class="form-section">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-form">
                <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                <div class="form-container">
                    <h2>Send us a Message</h2>
                    <form method="POST">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address *</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone">
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="subject">
                        </div>
                        <div class="form-group">
                            <label>Message *</label>
                            <textarea name="message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message <i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
            <div class="contact-info-box">
                <h2>Get in Touch</h2>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="contact-details">
                        <h4>Address</h4>
                        <p><?php echo getSetting('site_address', '123 Education Lane, Learning City'); ?></p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-phone"></i></div>
                    <div class="contact-details">
                        <h4>Phone</h4>
                        <p><?php echo getSetting('site_phone', '+1 234 567 890'); ?></p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div class="contact-details">
                        <h4>Email</h4>
                        <p><?php echo getSetting('site_email', 'info@timnahschools.edu'); ?></p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-clock"></i></div>
                    <div class="contact-details">
                        <h4>Office Hours</h4>
                        <p>Monday - Friday: 8:00 AM - 4:00 PM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="map-container">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1422937950147!2d-73.98731968482413!3d40.75889497932681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2s!5e0!3m2!1sen!2sus!4v1620000000000!5m2!1sen!2sus" allowfullscreen="" loading="lazy"></iframe>
</section>

<?php include 'includes/footer.php'; ?>