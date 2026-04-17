</main>
    <footer class="main-footer" itemscope itemtype="https://schema.org/School">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <a href="<?php echo SITE_URL; ?>/index.php" class="logo" itemprop="url">
                        <img src="<?php echo ASSETS_URL; ?>/images/logo.png" alt="<?php echo getSetting('site_name', 'Timnah Schools'); ?> Logo" class="logo-img">
                        <span class="logo-text" itemprop="name"><?php echo getSetting('site_name', 'Timnah Schools'); ?></span>
                    </a>
                    <p><?php echo getSetting('site_tagline', 'Excellence in Education'); ?></p>
                    <div class="footer-social">
                        <a href="<?php echo getSetting('site_facebook', '#'); ?>" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?php echo getSetting('site_twitter', '#'); ?>" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="<?php echo getSetting('site_instagram', '#'); ?>" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <nav aria-label="Footer navigation">
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/about.php">About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/events.php">Events</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/staff.php">Staff</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/gallery.php">Gallery</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/vacancies.php">Careers</a></li>
                    </ul>
                    </nav>
                </div>
                <div class="footer-section" itemscope itemtype="https://schema.org/PostalAddress">
                    <h3>Contact Info</h3>
                    <ul class="contact-list" itemprop="address">
                        <li itemscope itemtype="https://schema.org/Place">
                            <i class="fas fa-map-marker-alt"></i> 
                            <span itemprop="streetAddress"><?php echo getSetting('site_address', '123 Education Lane'); ?></span>
                        </li>
                        <li itemscope itemtype="https://schema.org/Organization">
                            <i class="fas fa-phone"></i> 
                            <span itemprop="telephone"><?php echo getSetting('site_phone', '+1 234 567 890'); ?></span>
                        </li>
                        <li itemscope itemtype="https://schema.org/Organization">
                            <i class="fas fa-envelope"></i> 
                            <span itemprop="email"><?php echo getSetting('site_email', 'info@timnahschools.edu'); ?></span>
                        </li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Newsletter</h3>
                    <p>Subscribe to our newsletter for updates</p>
                    <form class="newsletter-form" aria-label="Newsletter subscription">
                        <label for="newsletter-email" class="visually-hidden">Email address</label>
                        <input type="email" id="newsletter-email" placeholder="Enter your email" required>
                        <button type="submit" aria-label="Subscribe"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <span itemprop="name"><?php echo getSetting('site_name', 'Timnah Schools'); ?></span>. All rights reserved. | <a href="<?php echo SITE_URL; ?>/sitemap.xml">Sitemap</a></p>
            </div>
        </div>
    </footer>
    
    <!-- Defer JavaScript for better performance -->
    <script src="<?php echo ASSETS_URL; ?>/js/main.js" defer></script>
</body>
</html>