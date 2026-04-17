document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS alternative - Custom scroll animations
    initScrollAnimations();
    
    // Sticky Header
    const header = document.querySelector('.main-header');
    const mobileToggle = document.querySelector('.mobile-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Theme Toggle
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Optional: Subtle feedback/animation
            themeToggle.style.transform = 'scale(0.8) rotate(180deg)';
            setTimeout(() => {
                themeToggle.style.transform = '';
            }, 300);
        });
    }
    
    // Mobile Menu
    if (mobileToggle && navMenu) {
        const navOverlay = document.querySelector('.nav-overlay');
        const mobileClose = document.querySelector('.mobile-close');
        
        mobileToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
            if (navOverlay) navOverlay.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
        });
        
        // Close button functionality
        const mobileCloseBtns = document.querySelectorAll('.mobile-close');
        mobileCloseBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                mobileToggle.classList.remove('active');
                navMenu.classList.remove('active');
                if (navOverlay) navOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
        
        if (navOverlay) {
            navOverlay.addEventListener('click', function() {
                mobileToggle.classList.remove('active');
                navMenu.classList.remove('active');
                navOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
        
        document.addEventListener('click', function(e) {
            if (!navMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
                mobileToggle.classList.remove('active');
                navMenu.classList.remove('active');
                if (navOverlay) navOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
    
    // Global Search
    const globalSearch = document.getElementById('globalSearch');
    const mobileSearch = document.getElementById('mobileSearch');
    const searchResults = document.getElementById('searchResults');
    let searchTimeout;
    
    const handleSearchInput = function(e) {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();
        
        // Sync inputs if they exist separately
        if (e.target === globalSearch && mobileSearch) mobileSearch.value = e.target.value;
        if (e.target === mobileSearch && globalSearch) globalSearch.value = e.target.value;
        
        if (query.length < 2) {
            if (searchResults) {
                searchResults.classList.remove('active');
                searchResults.innerHTML = '';
            }
            return;
        }
        
        if (searchResults) {
            searchResults.innerHTML = '<div class="search-loading"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
            searchResults.classList.add('active');
        }
        
        searchTimeout = setTimeout(function() {
            performSearch(query);
        }, 300);
    };

    if (globalSearch) {
        globalSearch.addEventListener('input', handleSearchInput);
        globalSearch.addEventListener('focus', function() {
            if (this.value.trim().length >= 2 && searchResults) {
                searchResults.classList.add('active');
            }
        });
    }

    if (mobileSearch) {
        mobileSearch.addEventListener('input', handleSearchInput);
    }
    
    if (searchResults) {
        document.addEventListener('click', function(e) {
            const isClickInside = (globalSearch && globalSearch.contains(e.target)) || 
                                (mobileSearch && mobileSearch.contains(e.target)) || 
                                searchResults.contains(e.target);
                                
            if (!isClickInside) {
                searchResults.classList.remove('active');
            }
        });
    }
    
    // Button Ripple Effect
    initRippleEffects();
    
    // Global Search Function
    function performSearch(query) {
        fetch(`${window.SITE_URL || ''}/api/search.php?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    displaySearchResults(data.results, data.total, query);
                }
            })
            .catch(err => {
                console.error('Search error:', err);
                searchResults.innerHTML = '<div class="search-no-results">Error searching. Please try again.</div>';
            });
    }
    
    function displaySearchResults(results, total, query) {
        let html = '';
        
        if (total === 0) {
            html = '<div class="search-no-results">No results found for "' + escapeHtml(query) + '"</div>';
        } else {
            if (results.events && results.events.length > 0) {
                html += '<div class="search-results-section"><h4>Events</h4>';
                results.events.forEach(item => {
                    html += createSearchResultItem(item.title, item.date, 'event', '/events.php');
                });
                html += '</div>';
            }
            
            if (results.students && results.students.length > 0) {
                html += '<div class="search-results-section"><h4>Pending Registrations</h4>';
                results.students.forEach(item => {
                    html += createSearchResultItem(item.title, item.date, 'student', '/admin/students.php');
                });
                html += '</div>';
            }
            
            if (results.news && results.news.length > 0) {
                html += '<div class="search-results-section"><h4>News</h4>';
                results.news.forEach(item => {
                    html += createSearchResultItem(item.title, formatDate(item.date), 'news', '/news.php');
                });
                html += '</div>';
            }
            
            if (results.vacancies && results.vacancies.length > 0) {
                html += '<div class="search-results-section"><h4>Careers</h4>';
                results.vacancies.forEach(item => {
                    html += createSearchResultItem(item.title, item.date, 'vacancy', '/vacancies.php');
                });
                html += '</div>';
            }
            
            html += '<a href="/search.php?q=' + encodeURIComponent(query) + '" class="search-see-all">See all ' + total + ' results</a>';
        }
        
        searchResults.innerHTML = html;
    }
    
    function createSearchResultItem(title, meta, type, link) {
        const icons = {
            'event': 'fa-calendar',
            'student': 'fa-user-graduate',
            'news': 'fa-newspaper',
            'vacancy': 'fa-briefcase'
        };
        
        return '<a href="' + link + '" class="search-result-item">' +
            '<i class="fas ' + icons[type] + '"></i>' +
            '<div class="info">' +
                '<div class="title">' + escapeHtml(title) + '</div>' +
                '<div class="meta">' + escapeHtml(meta) + '</div>' +
            '</div>' +
        '</a>';
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function formatDate(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }
    
    // Newsletter form
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input').value;
            subscribeNewsletter(email);
        });
    }
    
    // Delete confirmations
    const deleteButtons = document.querySelectorAll('.btn-delete, [data-delete]');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
    
    // Counter animation
    const counters = document.querySelectorAll('.stat-number');
    if (counters.length > 0) {
        const counterObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.getAttribute('data-target'));
                    if (target) {
                        animateCounter(counter, target);
                    }
                    counterObserver.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(counter => counterObserver.observe(counter));
    }
});

// Initialize Scroll Animations
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.card, .feature-card, .staff-card, .event-card, .gallery-item, .stat-card, .search-page-item, .fade-in, .glass-card');
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
    
    // Trigger initial animation check
    setTimeout(() => {
        animatedElements.forEach(el => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        });
    }, 100);
}

// Button Ripple Effect
function initRippleEffects() {
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                background: rgba(255, 255, 255, 0.4);
                border-radius: 50%;
                width: 100px;
                height: 100px;
                left: ${x - 50}px;
                top: ${y - 50}px;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
                z-index: 1;
            `;
            
            btn.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
}

function animateCounter(element, target) {
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;
    
    function update() {
        current += step;
        if (current < target) {
            element.textContent = Math.floor(current);
            requestAnimationFrame(update);
        } else {
            element.textContent = target;
        }
    }
    
    update();
}

// Newsletter subscription via AJAX
function subscribeNewsletter(email) {
    const formData = new FormData();
    formData.append('email', email);
    
    fetch('api/newsletter.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Thank you for subscribing!');
            document.querySelector('.newsletter-form input').value = '';
        } else {
            alert(data.message || 'Error subscribing. Please try again.');
        }
    })
    .catch(err => {
        console.error('Newsletter error:', err);
        alert('Something went wrong. Please try again.');
    });
}

// Image lightbox
function openLightbox(src) {
    const lightbox = document.createElement('div');
    lightbox.id = 'lightbox';
    lightbox.innerHTML = `
        <div class="lightbox-content">
            <img src="${src}" alt="Gallery Image">
            <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
        </div>
    `;
    lightbox.style.cssText = `
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    `;
    document.body.appendChild(lightbox);
    document.body.style.overflow = 'hidden';
    
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) closeLightbox();
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLightbox();
    });
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    if (lightbox) {
        lightbox.remove();
        document.body.style.overflow = '';
    }
}

// Add fade-in animation
document.addEventListener('DOMContentLoaded', function() {
    // Trigger animations on page load
    setTimeout(() => {
        window.dispatchEvent(new Event('scroll'));
    }, 100);
});

// Smooth page load
window.addEventListener('load', function() {
    document.body.style.opacity = '1';
});

document.body.style.opacity = '0';
document.body.style.transition = 'opacity 0.5s ease';

function fadeIn(keyframes, options) {
    return new Promise(resolve => {
        const element = document.querySelector('.hero-content');
        if (element) {
            element.animate(keyframes, options);
        }
        resolve();
    });
}