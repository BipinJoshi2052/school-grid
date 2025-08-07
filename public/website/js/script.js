$(document).ready(function() {
    // Mobile menu toggle
    $('#mobileMenuBtn').click(function() {
        $('#navMenu').toggleClass('show');
    });

    // Smooth scrolling for navigation links
    $('a[href^="#"]').click(function(e) {
        e.preventDefault();
        const target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 80
            }, 800);
        }
    });

    // Contact form submission
    $('#contactForm').submit(function(e) {
        e.preventDefault();
        
        const $submitBtn = $('#submitBtn');
        const $btnText = $submitBtn.find('.btn-text');
        const $btnLoading = $submitBtn.find('.btn-loading');
        
        // Show loading state
        $submitBtn.prop('disabled', true);
        $btnText.hide();
        $btnLoading.show();
        
        // Simulate form submission
        setTimeout(function() {
            // Hide loading state
            $submitBtn.prop('disabled', false);
            $btnText.show();
            $btnLoading.hide();
            
            // Reset form
            $('#contactForm')[0].reset();
            
            // Show success toast
            showToast('Message sent successfully! We\'ll get back to you soon.', 'success');
        }, 2000);
    });

    // Header scroll effect
    $(window).scroll(function() {
        const header = $('.header');
        if ($(window).scrollTop() > 50) {
            header.addClass('scrolled');
        } else {
            header.removeClass('scrolled');
        }
    });

    // Floating animation for hero elements
    function animateFloatingElements() {
        $('.floating-element').each(function(index) {
            const delay = index * 2000;
            $(this).css('animation-delay', delay + 'ms');
        });
    }
    
    animateFloatingElements();

    // Feature cards hover effect
    $('.feature-card').hover(
        function() {
            $(this).addClass('hovered');
        },
        function() {
            $(this).removeClass('hovered');
        }
    );

    // Revenue cards hover effect
    $('.revenue-card').hover(
        function() {
            $(this).addClass('hovered');
        },
        function() {
            $(this).removeClass('hovered');
        }
    );

    // Button hover effects
    $('.btn-hero').hover(
        function() {
            $(this).find('i').addClass('animate-bounce');
        },
        function() {
            $(this).find('i').removeClass('animate-bounce');
        }
    );

    // Intersection Observer for animations
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, {
            threshold: 0.1
        });

        // Observe elements for animation
        $('.feature-card, .revenue-card, .contact-card').each(function() {
            observer.observe(this);
        });
    }

    // Add bounce animation for check icons
    $('.feature-item i, .revenue-features i').addClass('animate-pulse');

    // Add subtle parallax effect to hero background
    $(window).scroll(function() {
        const scrolled = $(window).scrollTop();
        const rate = scrolled * -0.5;
        $('.hero-bg').css('transform', 'translateY(' + rate + 'px)');
    });

    // Add loading states to all buttons
    $('.btn').click(function(e) {
        const $btn = $(this);
        
        // Skip if it's a link or form submission
        if ($btn.is('a') || $btn.attr('type') === 'submit') {
            return;
        }
        
        // Add loading state
        $btn.prop('disabled', true);
        const originalText = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        
        // Simulate loading
        setTimeout(function() {
            $btn.prop('disabled', false);
            $btn.html(originalText);
        }, 1500);
    });
});

// Toast notification function
function showToast(message, type = 'success') {
    const toast = $('#toast');
    const toastIcon = toast.find('.toast-icon');
    const toastMessage = toast.find('.toast-message');
    
    // Set toast content
    toastMessage.text(message);
    
    // Set toast type and icon
    toast.removeClass('success error');
    toast.addClass(type);
    
    if (type === 'success') {
        toastIcon.removeClass().addClass('toast-icon fas fa-check-circle');
    } else if (type === 'error') {
        toastIcon.removeClass().addClass('toast-icon fas fa-exclamation-circle');
    }
    
    // Show toast
    toast.addClass('show');
    
    // Hide toast after 4 seconds
    setTimeout(function() {
        toast.removeClass('show');
    }, 4000);
}

// Add CSS animations
const additionalStyles = `
    .animate-bounce {
        animation: bounce 1s infinite;
    }
    
    .animate-pulse {
        animation: pulse 2s infinite;
    }
    
    .animate-in {
        animation: slideUp 0.6s ease-out;
    }
    
    .hovered {
        transform: translateY(-5px) !important;
        box-shadow: var(--shadow-glow) !important;
    }
    
    .header.scrolled {
        background: rgba(255, 255, 255, 0.98);
        box-shadow: var(--shadow-card);
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;

// Inject additional styles
$('<style>').text(additionalStyles).appendTo('head');