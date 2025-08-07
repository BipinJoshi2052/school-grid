$(document).ready(function() {
    // Form validation
    function validateForm() {
        let isValid = true;
        const errors = [];

        // Required fields
        const requiredFields = [
            { id: '#firstName', name: 'First Name' },
            { id: '#lastName', name: 'Last Name' },
            { id: '#email', name: 'Email' },
            { id: '#password', name: 'Password' },
            { id: '#confirmPassword', name: 'Confirm Password' },
            { id: '#institution', name: 'Institution' },
            { id: '#role', name: 'Role' },
            { id: '#country', name: 'Country' }
        ];

        requiredFields.forEach(function(field) {
            const value = $(field.id).val().trim();
            if (!value) {
                errors.push(`${field.name} is required`);
                isValid = false;
                $(field.id).addClass('error');
            } else {
                $(field.id).removeClass('error');
            }
        });

        // Email validation
        const email = $('#email').val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            errors.push('Please enter a valid email address');
            isValid = false;
            $('#email').addClass('error');
        }

        // Password validation
        const password = $('#password').val();
        if (password && password.length < 8) {
            errors.push('Password must be at least 8 characters long');
            isValid = false;
            $('#password').addClass('error');
        }

        // Confirm password validation
        const confirmPassword = $('#confirmPassword').val();
        if (password && confirmPassword && password !== confirmPassword) {
            errors.push('Passwords do not match');
            isValid = false;
            $('#confirmPassword').addClass('error');
        }

        // Terms acceptance
        if (!$('#terms').is(':checked')) {
            errors.push('You must accept the Terms of Service and Privacy Policy');
            isValid = false;
        }

        return { isValid, errors };
    }

    // Real-time validation
    $('input, select').on('blur', function() {
        const $field = $(this);
        $field.removeClass('error');
        
        // Validate specific field
        if ($field.attr('required') && !$field.val().trim()) {
            $field.addClass('error');
        }
        
        if ($field.attr('type') === 'email') {
            const email = $field.val().trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email)) {
                $field.addClass('error');
            }
        }
        
        if ($field.attr('id') === 'confirmPassword') {
            const password = $('#password').val();
            const confirmPassword = $field.val();
            if (password && confirmPassword && password !== confirmPassword) {
                $field.addClass('error');
            }
        }
    });

    // Password strength indicator
    $('#password').on('input', function() {
        const password = $(this).val();
        updatePasswordStrength(password);
    });

    function updatePasswordStrength(password) {
        let strength = 0;
        const indicators = [];

        if (password.length >= 8) {
            strength++;
            indicators.push('✓ At least 8 characters');
        } else {
            indicators.push('✗ At least 8 characters');
        }

        if (/[A-Z]/.test(password)) {
            strength++;
            indicators.push('✓ Uppercase letter');
        } else {
            indicators.push('✗ Uppercase letter');
        }

        if (/[a-z]/.test(password)) {
            strength++;
            indicators.push('✓ Lowercase letter');
        } else {
            indicators.push('✗ Lowercase letter');
        }

        if (/[0-9]/.test(password)) {
            strength++;
            indicators.push('✓ Number');
        } else {
            indicators.push('✗ Number');
        }

        if (/[^A-Za-z0-9]/.test(password)) {
            strength++;
            indicators.push('✓ Special character');
        } else {
            indicators.push('✗ Special character');
        }

        // Update or create strength indicator
        let $indicator = $('#passwordStrength');
        if ($indicator.length === 0) {
            $indicator = $('<div id="passwordStrength" class="password-strength"></div>');
            $('#password').after($indicator);
        }

        const strengthLevel = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'][Math.min(strength, 4)];
        const strengthClass = ['very-weak', 'weak', 'fair', 'good', 'strong'][Math.min(strength, 4)];

        $indicator.html(`
            <div class="strength-bar">
                <div class="strength-fill ${strengthClass}" style="width: ${(strength / 5) * 100}%"></div>
            </div>
            <div class="strength-text">Strength: ${strengthLevel}</div>
        `);
    }

    // Form submission
    $('#registerForm').submit(function(e) {
        e.preventDefault();
        
        const validation = validateForm();
        
        if (!validation.isValid) {
            showToast(validation.errors[0], 'error');
            return;
        }

        const $registerBtn = $('#registerBtn');
        const $btnText = $registerBtn.find('.btn-text');
        const $btnLoading = $registerBtn.find('.btn-loading');
        
        // Show loading state
        $registerBtn.prop('disabled', true);
        $btnText.hide();
        $btnLoading.show();
        
        // Simulate registration
        setTimeout(function() {
            // Hide loading state
            $registerBtn.prop('disabled', false);
            $btnText.show();
            $btnLoading.hide();
            
            // Show success message
            showToast('Account created successfully! Welcome to SeatPlan Pro!', 'success');
            
            // Simulate redirect after success
            setTimeout(function() {
                showToast('Redirecting to dashboard...', 'success');
            }, 2000);
            
        }, 3000);
    });

    // Phone number formatting
    $('#phone').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length >= 6) {
            value = value.replace(/(\d{3})(\d{3})(\d+)/, '($1) $2-$3');
        } else if (value.length >= 3) {
            value = value.replace(/(\d{3})(\d+)/, '($1) $2');
        }
        $(this).val(value);
    });

    // Auto-focus next field on Enter
    $('input, select').keypress(function(e) {
        if (e.which === 13) {
            e.preventDefault();
            const inputs = $('input, select').filter(':visible');
            const nextIndex = inputs.index(this) + 1;
            if (nextIndex < inputs.length) {
                inputs.eq(nextIndex).focus();
            } else {
                $('#registerForm').submit();
            }
        }
    });

    // Character counter for institution name
    $('#institution').on('input', function() {
        const maxLength = 100;
        const currentLength = $(this).val().length;
        
        let $counter = $('#institutionCounter');
        if ($counter.length === 0) {
            $counter = $('<div id="institutionCounter" class="char-counter"></div>');
            $(this).after($counter);
        }
        
        $counter.text(`${currentLength}/${maxLength} characters`);
        
        if (currentLength > maxLength * 0.9) {
            $counter.addClass('warning');
        } else {
            $counter.removeClass('warning');
        }
    });

    // Country flag display
    $('#country').change(function() {
        const country = $(this).val();
        const flags = {
            'us': '🇺🇸',
            'ca': '🇨🇦',
            'uk': '🇬🇧',
            'au': '🇦🇺',
            'in': '🇮🇳'
        };
        
        let $flag = $('#countryFlag');
        if ($flag.length === 0) {
            $flag = $('<span id="countryFlag" class="country-flag"></span>');
            $(this).after($flag);
        }
        
        $flag.text(flags[country] || '🌍');
    });

    // Animate benefit items on scroll
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, { threshold: 0.2 });

    $('.benefit-item').each(function() {
        observer.observe(this);
    });

    // Progress indicator
    function updateProgress() {
        const totalFields = $('input[required], select[required]').length;
        const filledFields = $('input[required], select[required]').filter(function() {
            return $(this).val().trim() !== '';
        }).length;
        
        const termsChecked = $('#terms').is(':checked') ? 1 : 0;
        const progress = ((filledFields + termsChecked) / (totalFields + 1)) * 100;
        
        let $progress = $('#formProgress');
        if ($progress.length === 0) {
            $progress = $('<div id="formProgress" class="form-progress"><div class="progress-bar"></div><span class="progress-text">0% Complete</span></div>');
            $('.register-form').prepend($progress);
        }
        
        $progress.find('.progress-bar').css('width', progress + '%');
        $progress.find('.progress-text').text(Math.round(progress) + '% Complete');
    }

    $('input, select, #terms').on('input change', updateProgress);
    updateProgress();
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

// Additional styles for register page
const registerStyles = `
    .error {
        border-color: hsl(var(--destructive)) !important;
        box-shadow: 0 0 0 3px hsla(var(--destructive), 0.1) !important;
    }
    
    .password-strength {
        margin-top: 0.5rem;
    }
    
    .strength-bar {
        width: 100%;
        height: 0.25rem;
        background: hsl(var(--muted));
        border-radius: 0.125rem;
        overflow: hidden;
    }
    
    .strength-fill {
        height: 100%;
        transition: all 0.3s ease;
        border-radius: 0.125rem;
    }
    
    .strength-fill.very-weak { background: #ef4444; width: 20%; }
    .strength-fill.weak { background: #f59e0b; width: 40%; }
    .strength-fill.fair { background: #eab308; width: 60%; }
    .strength-fill.good { background: #22c55e; width: 80%; }
    .strength-fill.strong { background: #10b981; width: 100%; }
    
    .strength-text {
        font-size: 0.75rem;
        margin-top: 0.25rem;
        color: hsl(var(--muted-foreground));
    }
    
    .char-counter {
        font-size: 0.75rem;
        color: hsl(var(--muted-foreground));
        margin-top: 0.25rem;
    }
    
    .char-counter.warning {
        color: hsl(var(--destructive));
    }
    
    .country-flag {
        margin-left: 0.5rem;
        font-size: 1.25rem;
    }
    
    .form-progress {
        background: hsl(var(--muted));
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .progress-bar {
        height: 0.5rem;
        background: var(--gradient-primary);
        border-radius: 0.25rem;
        transition: width 0.3s ease;
        margin-bottom: 0.5rem;
    }
    
    .progress-text {
        font-size: 0.875rem;
        font-weight: 600;
        color: hsl(var(--primary));
    }
    
    .benefit-item {
        opacity: 0;
        transform: translateX(-20px);
        transition: all 0.5s ease;
    }
    
    .benefit-item.animate-in {
        opacity: 1;
        transform: translateX(0);
    }
    
    .benefit-item:nth-child(2) { transition-delay: 0.1s; }
    .benefit-item:nth-child(3) { transition-delay: 0.2s; }
    .benefit-item:nth-child(4) { transition-delay: 0.3s; }
`;

$('<style>').text(registerStyles).appendTo('head');