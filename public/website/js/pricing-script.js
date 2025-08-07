$(document).ready(function() {
    // Billing toggle functionality
    $('#billingToggle').change(function() {
        const isAnnual = $(this).is(':checked');
        
        if (isAnnual) {
            $('.monthly-price').hide();
            $('.annual-price').show();
            $('.price-note').text('Billed annually');
        } else {
            $('.monthly-price').show();
            $('.annual-price').hide();
            $('.price-note').text('Billed monthly');
        }
        
        // Add animation to price changes
        $('.plan-price').addClass('price-changing');
        setTimeout(function() {
            $('.plan-price').removeClass('price-changing');
        }, 300);
    });

    // FAQ accordion functionality
    $('.faq-question').click(function() {
        const $faqItem = $(this).closest('.faq-item');
        const $faqAnswer = $faqItem.find('.faq-answer');
        
        // Close other FAQ items
        $('.faq-item').not($faqItem).removeClass('active');
        $('.faq-answer').not($faqAnswer).slideUp();
        
        // Toggle current FAQ item
        $faqItem.toggleClass('active');
        $faqAnswer.slideToggle();
    });

    // Pricing card hover effects
    $('.pricing-card').hover(
        function() {
            if (!$(this).hasClass('popular')) {
                $(this).addClass('hovered');
            }
        },
        function() {
            $(this).removeClass('hovered');
        }
    );

    // Plan selection tracking
    $('.pricing-card .btn').click(function(e) {
        const $card = $(this).closest('.pricing-card');
        const planName = $card.find('h3').text();
        const isPopular = $card.hasClass('popular');
        
        if ($(this).text().includes('Contact Sales')) {
            showToast(`Contacting sales for ${planName} plan...`, 'success');
        } else {
            showToast(`Starting free trial for ${planName} plan...`, 'success');
        }
        
        // Track plan selection (would send to analytics in real app)
        console.log('Plan selected:', { planName, isPopular });
    });

    // Highlight popular plan
    function highlightPopularPlan() {
        $('.pricing-card.popular').addClass('pulse-glow');
        setTimeout(function() {
            $('.pricing-card.popular').removeClass('pulse-glow');
        }, 2000);
    }
    
    // Highlight popular plan on page load
    setTimeout(highlightPopularPlan, 1000);

    // Smooth scroll to pricing from hero
    $('a[href*="pricing"]').click(function(e) {
        if ($(this).attr('href').includes('#')) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.pricing-plans').offset().top - 100
            }, 800);
        }
    });

    // Price comparison tooltip
    $('.plan-price').hover(
        function() {
            const $this = $(this);
            const monthlyPrice = $this.find('.monthly-price').text();
            const annualPrice = $this.find('.annual-price').text();
            
            if (annualPrice && annualPrice !== monthlyPrice) {
                const savings = (monthlyPrice * 12) - (annualPrice * 12);
                if (savings > 0) {
                    showTooltip($this, `Save $${savings}/year with annual billing`);
                }
            }
        },
        function() {
            hideTooltip();
        }
    );

    // Feature comparison
    $('.feature-item').click(function() {
        const featureText = $(this).find('span').text();
        showToast(`${featureText} - Learn more about this feature`, 'success');
    });

    // Animate pricing cards on scroll
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry, index) {
            if (entry.isIntersecting) {
                setTimeout(function() {
                    entry.target.classList.add('animate-in');
                }, index * 200);
            }
        });
    }, { threshold: 0.2 });

    $('.pricing-card').each(function() {
        observer.observe(this);
    });

    // Auto-calculate savings
    function updateSavings() {
        $('.pricing-card').each(function() {
            const $card = $(this);
            const monthlyPrice = parseInt($card.find('.monthly-price').text());
            const annualPrice = parseInt($card.find('.annual-price').text());
            
            if (monthlyPrice && annualPrice) {
                const yearlySavings = (monthlyPrice * 12) - (annualPrice * 12);
                const percentSavings = Math.round((yearlySavings / (monthlyPrice * 12)) * 100);
                
                let $savings = $card.find('.savings-indicator');
                if ($savings.length === 0) {
                    $savings = $('<div class="savings-indicator"></div>');
                    $card.find('.plan-header').append($savings);
                }
                
                if ($('#billingToggle').is(':checked')) {
                    $savings.html(`<span class="savings-badge">Save ${percentSavings}%</span>`).show();
                } else {
                    $savings.hide();
                }
            }
        });
    }

    // Update savings on billing toggle
    $('#billingToggle').change(updateSavings);
    updateSavings();

    // CTA button interactions
    $('.cta-buttons .btn').click(function() {
        const buttonText = $(this).text();
        if (buttonText.includes('Trial')) {
            showToast('Redirecting to registration...', 'success');
        } else if (buttonText.includes('Sales')) {
            showToast('Opening contact form...', 'success');
        }
    });

    // Keyboard navigation for FAQ
    $('.faq-question').keypress(function(e) {
        if (e.which === 13 || e.which === 32) {
            e.preventDefault();
            $(this).click();
        }
    });

    // Price animation on page load
    setTimeout(function() {
        $('.amount').each(function(index) {
            const $this = $(this);
            const finalValue = parseInt($this.text());
            $this.text('0');
            
            setTimeout(function() {
                animateCounter($this, 0, finalValue, 1000);
            }, index * 200);
        });
    }, 500);

    function animateCounter($element, start, end, duration) {
        const range = end - start;
        const increment = range / (duration / 16);
        let current = start;
        
        const timer = setInterval(function() {
            current += increment;
            if (current >= end) {
                current = end;
                clearInterval(timer);
            }
            $element.text(Math.floor(current));
        }, 16);
    }
});

// Toast notification function
function showToast(message, type = 'success') {
    const toast = $('#toast');
    const toastIcon = toast.find('.toast-icon');
    const toastMessage = toast.find('.toast-message');
    
    toastMessage.text(message);
    
    toast.removeClass('success error');
    toast.addClass(type);
    
    if (type === 'success') {
        toastIcon.removeClass().addClass('toast-icon fas fa-check-circle');
    } else if (type === 'error') {
        toastIcon.removeClass().addClass('toast-icon fas fa-exclamation-circle');
    }
    
    toast.addClass('show');
    
    setTimeout(function() {
        toast.removeClass('show');
    }, 4000);
}

// Tooltip functions
function showTooltip($element, text) {
    const $tooltip = $('<div class="tooltip">' + text + '</div>');
    $('body').append($tooltip);
    
    const elementOffset = $element.offset();
    $tooltip.css({
        top: elementOffset.top - $tooltip.outerHeight() - 10,
        left: elementOffset.left + ($element.outerWidth() / 2) - ($tooltip.outerWidth() / 2)
    });
    
    setTimeout(function() {
        $tooltip.addClass('show');
    }, 10);
}

function hideTooltip() {
    $('.tooltip').remove();
}

// Additional styles for pricing page
const pricingStyles = `
    .price-changing {
        animation: priceChange 0.3s ease-in-out;
    }
    
    @keyframes priceChange {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .pricing-card.hovered {
        transform: translateY(-10px) !important;
        box-shadow: var(--shadow-glow) !important;
    }
    
    .pricing-card.pulse-glow {
        animation: pulseGlow 2s ease-in-out;
    }
    
    @keyframes pulseGlow {
        0%, 100% { box-shadow: var(--shadow-elegant); }
        50% { box-shadow: var(--shadow-glow); }
    }
    
    .pricing-card {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }
    
    .pricing-card.animate-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    .savings-indicator {
        text-align: center;
        margin-top: 0.5rem;
    }
    
    .savings-badge {
        background: hsl(var(--secondary));
        color: hsl(var(--secondary-foreground));
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        animation: bounce 0.5s ease-out;
    }
    
    .tooltip {
        position: absolute;
        background: hsl(var(--popover));
        border: 1px solid hsl(var(--border));
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
        box-shadow: var(--shadow-card);
        z-index: 1000;
        opacity: 0;
        transform: translateY(-5px);
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    
    .tooltip.show {
        opacity: 1;
        transform: translateY(0);
    }
    
    .tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border: 5px solid transparent;
        border-top-color: hsl(var(--border));
    }
    
    .feature-item {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .feature-item:hover {
        background: hsla(var(--primary), 0.05);
        border-radius: 0.25rem;
        padding: 0.25rem;
        margin: -0.25rem;
    }
    
    .faq-item {
        transition: all 0.3s ease;
    }
    
    .faq-item:hover {
        box-shadow: var(--shadow-card);
    }
    
    .faq-question:focus {
        outline: 2px solid hsl(var(--ring));
        outline-offset: 2px;
    }
`;

$('<style>').text(pricingStyles).appendTo('head');