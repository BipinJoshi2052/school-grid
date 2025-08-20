<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Multi-Step Form</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .registration-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
            position: relative;
        }

        .progress-container {
            background: #f8fafc;
            padding: 30px 40px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .progress-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
        }

        .progress-bar::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #e2e8f0;
            z-index: 1;
            transform: translateY(-50%);
        }

        .progress-line {
            position: absolute;
            top: 50%;
            left: 0;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            z-index: 2;
            transform: translateY(-50%);
            transition: width 0.3s ease;
            width: 0%;
        }

        .step-indicator {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #64748b;
            z-index: 3;
            position: relative;
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: scale(1.1);
        }

        .step-indicator.completed {
            background: #10b981;
            color: white;
        }

        .step-titles {
            text-align: center;
            margin-top: 10px;
        }

        .step-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
        }

        .step-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-top: 5px;
        }

        .form-container {
            padding: 40px;
        }

        .step {
            display: none;
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.3s ease;
        }

        .step.active {
            display: block;
            opacity: 1;
            transform: translateX(0);
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fff;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .password-validation {
            background: #f8fafc;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
            font-size: 12px;
        }

        .validation-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            color: #64748b;
        }

        .validation-item.valid {
            color: #10b981;
        }

        .validation-icon {
            margin-right: 8px;
            width: 12px;
            height: 12px;
        }

        .btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-secondary {
            background: #6b7280;
            margin-top: 15px;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .loader {
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: none;
            margin-right: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .navigation-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .navigation-buttons .submit-btn{
            display: flex;
        }

        .btn-back {
            background: #6b7280;
            flex: 1;
        }

        .btn-next {
            flex: 2;
        }

        .otp-container {
            display: none;
            text-align: center;
            padding: 30px;
        }

        .otp-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .otp-subtitle {
            color: #64748b;
            margin-bottom: 30px;
        }

        .otp-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: #fff;
        }

        .otp-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .resend-container {
            margin-top: 20px;
        }

        .resend-btn {
            background: transparent;
            color: #667eea;
            border: 1px solid #667eea;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .resend-btn:hover:not(:disabled) {
            background: #667eea;
            color: white;
        }

        .resend-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .success-container {
            display: none;
            text-align: center;
            padding: 40px 20px;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 40px;
        }

        .success-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
        }

        .success-message {
            color: #64748b;
            line-height: 1.6;
            font-size: 16px;
        }

        @media (max-width: 480px) {
            .registration-container {
                margin: 10px;
            }
            
            .progress-container,
            .form-container {
                padding: 20px;
            }
            
            .otp-inputs {
                gap: 5px;
            }
            
            .otp-input {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <!-- Progress Indicator -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-line" id="progressLine"></div>
                <div class="step-indicator active" data-step="1">1</div>
                <div class="step-indicator" data-step="2">2</div>
                <div class="step-indicator" data-step="3">3</div>
            </div>
            <div class="step-titles">
                <div class="step-title" id="stepTitle">Personal Information</div>
                <div class="step-subtitle" id="stepSubtitle">Let's start with your basic details</div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="form-container">
            <form id="registrationForm">
                <!-- Step 1: Personal Information -->
                <div class="step active" data-step="1">
                    <div class="form-group">
                        <label for="fullName">Full Name *</label>
                        <input type="text" id="fullName" name="full_name" required>
                        <div class="error-message" id="fullNameError">Full name is required</div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required>
                        <div class="error-message" id="emailError">Please enter a valid email address</div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required>
                        <div class="error-message" id="phoneError">Phone number is required</div>
                    </div>
                    <div class="navigation-buttons">
                        <button type="button" class="btn btn-next" onclick="nextStep()">Next Step</button>
                    </div>
                </div>

                <!-- Step 2: Institution Information -->
                <div class="step" data-step="2">
                    <div class="form-group">
                        <label for="institution">Institution Name *</label>
                        <input type="text" id="institution" name="institution_name" required>
                        <div class="error-message" id="institutionError">Institution name is required</div>
                    </div>
                    {{-- <div class="form-group">
                        <label for="howFound">How did you come across our service? *</label>
                        <select id="howFound" name="how_found" required>
                            <option value="">Select an option</option>
                            <option value="search_engine">Search Engine</option>
                            <option value="social_media">Social Media</option>
                            <option value="referral">Referral</option>
                            <option value="advertisement">Advertisement</option>
                            <option value="other">Other</option>
                        </select>
                        <div class="error-message" id="howFoundError">Please select how you found our service</div>
                    </div>
                    <div class="form-group">
                        <label for="currentSoftware">Are you using any software for managing your school operations? *</label>
                        <select id="currentSoftware" name="current_software" required>
                            <option value="">Select an option</option>
                            <option value="yes_satisfied">Yes, and we're satisfied</option>
                            <option value="yes_unsatisfied">Yes, but we're unsatisfied</option>
                            <option value="no">No, we're not using any</option>
                            <option value="manual">We manage manually</option>
                        </select>
                        <div class="error-message" id="currentSoftwareError">Please select your current software status</div>
                    </div> --}}
                    <div class="navigation-buttons">
                        <button type="button" class="btn btn-back" onclick="prevStep()">Back</button>
                        <button type="button" class="btn btn-next" onclick="nextStep()">Next Step</button>
                    </div>
                </div>

                <!-- Step 3: Account Setup -->
                <div class="step" data-step="3">
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required>
                        <div class="error-message" id="passwordError">Password is required</div>
                        <div class="password-validation" id="passwordValidation">
                            <div class="validation-item" id="lengthCheck">
                                <span class="validation-icon">✗</span> At least 8 characters
                            </div>
                            <div class="validation-item" id="uppercaseCheck">
                                <span class="validation-icon">✗</span> One uppercase letter
                            </div>
                            <div class="validation-item" id="lowercaseCheck">
                                <span class="validation-icon">✗</span> One lowercase letter
                            </div>
                            <div class="validation-item" id="numberCheck">
                                <span class="validation-icon">✗</span> One number
                            </div>
                            <div class="validation-item" id="specialCheck">
                                <span class="validation-icon">✗</span> One special character
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password *</label>
                        <input type="password" id="confirmPassword" name="password_confirmation" required>
                        <div class="error-message" id="confirmPasswordError">Passwords do not match</div>
                    </div>
                    <div class="navigation-buttons">
                        <button type="button" class="btn btn-back" onclick="prevStep()">Back</button>
                        <button type="button" class="btn btn-next submit-btn" onclick="submitForm()">
                            <span class="loader" id="submitLoader"></span>
                            <span id="submitText">Complete Registration</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- OTP Verification -->
        <div class="otp-container" id="otpContainer">
            <div class="otp-title">Verify Your Email</div>
            <div class="otp-subtitle">We've sent a 6-digit code to <span id="userEmail"></span></div>
            
            <div class="otp-inputs">
                <input type="text" class="otp-input" maxlength="1" data-index="0">
                <input type="text" class="otp-input" maxlength="1" data-index="1">
                <input type="text" class="otp-input" maxlength="1" data-index="2">
                <input type="text" class="otp-input" maxlength="1" data-index="3">
                <input type="text" class="otp-input" maxlength="1" data-index="4">
                <input type="text" class="otp-input" maxlength="1" data-index="5">
            </div>
            
            <div class="error-message" id="otpError" style="text-align: center; margin-bottom: 20px;"></div>
            
            <button type="button" class="btn" id="verifyOtpBtn" onclick="verifyOtp()" disabled>
                <span class="loader" id="otpLoader"></span>
                <span id="verifyText">Verify OTP</span>
            </button>
            
            <div class="resend-container">
                <button type="button" class="resend-btn" id="resendBtn" onclick="resendOtp()">
                    <span id="resendText">Resend OTP</span>
                </button>
            </div>
        </div>

        <!-- Success Message -->
        <div class="success-container" id="successContainer">
            <div class="success-icon">🎉</div>
            <div class="success-title">Welcome, <span id="welcomeName"></span>!</div>
            <div class="success-message">
                You have successfully registered for our system.<br><br>
                This is a <strong>Free Tier</strong> plan, and you'll enjoy a <strong>14-day free trial</strong> of all features. Enjoy exploring the system!
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        let resendCount = 0;
        let resendTimer = null;
        let formData = {};

        const stepTitles = {
            1: { title: 'Personal Information', subtitle: "Let's start with your basic details" },
            2: { title: 'Institution Information', subtitle: 'Tell us about your institution' },
            3: { title: 'Account Setup', subtitle: 'Create your secure password' }
        };

        // Initialize
        $(document).ready(function() {
            updateProgressBar();
            setupPasswordValidation();
            setupOtpInputs();
        });

        function updateProgressBar() {
            const progressWidth = ((currentStep - 1) / 2) * 100;
            $('#progressLine').css('width', progressWidth + '%');
            
            $('.step-indicator').each(function() {
                const stepNum = parseInt($(this).data('step'));
                $(this).removeClass('active completed');
                
                if (stepNum < currentStep) {
                    $(this).addClass('completed').html('✓');
                } else if (stepNum === currentStep) {
                    $(this).addClass('active').html(stepNum);
                } else {
                    $(this).html(stepNum);
                }
            });

            $('#stepTitle').text(stepTitles[currentStep].title);
            $('#stepSubtitle').text(stepTitles[currentStep].subtitle);
        }

        function validateStep(step) {
            let isValid = true;
            
            if (step === 1) {
                const fullName = $('#fullName').val().trim();
                const email = $('#email').val().trim();
                const phone = $('#phone').val().trim();
                
                if (!fullName) {
                    showError('fullName', 'Full name is required');
                    isValid = false;
                }
                
                if (!email || !isValidEmail(email)) {
                    showError('email', 'Please enter a valid email address');
                    isValid = false;
                }
                
                if (!phone) {
                    showError('phone', 'Phone number is required');
                    isValid = false;
                }
            } else if (step === 2) {
                const institution = $('#institution').val().trim();
                const howFound = $('#howFound').val();
                const currentSoftware = $('#currentSoftware').val();
                
                if (!institution) {
                    showError('institution', 'Institution name is required');
                    isValid = false;
                }
                
                // if (!howFound) {
                //     showError('howFound', 'Please select how you found our service');
                //     isValid = false;
                // }
                
                // if (!currentSoftware) {
                //     showError('currentSoftware', 'Please select your current software status');
                //     isValid = false;
                // }
            } else if (step === 3) {
                const password = $('#password').val();
                const confirmPassword = $('#confirmPassword').val();
                
                if (!isValidPassword(password)) {
                    showError('password', 'Password does not meet requirements');
                    isValid = false;
                }
                
                if (password !== confirmPassword) {
                    showError('confirmPassword', 'Passwords do not match');
                    isValid = false;
                }
            }
            
            return isValid;
        }

        function showError(fieldId, message) {
            $(`#${fieldId}`).addClass('input-error');
            $(`#${fieldId}Error`).text(message).show();
        }

        function clearErrors() {
            $('.input-error').removeClass('input-error');
            $('.error-message').hide();
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function isValidPassword(password) {
            const hasLength = password.length >= 8;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            
            return hasLength && hasUpper && hasLower && hasNumber && hasSpecial;
        }

        function setupPasswordValidation() {
            $('#password').on('input', function() {
                const password = $(this).val();
                
                const checks = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /\d/.test(password),
                    special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
                };
                
                Object.keys(checks).forEach(check => {
                    const element = $(`#${check}Check`);
                    if (checks[check]) {
                        element.addClass('valid');
                        element.find('.validation-icon').text('✓');
                    } else {
                        element.removeClass('valid');
                        element.find('.validation-icon').text('✗');
                    }
                });
            });
        }

        function nextStep() {
            clearErrors();
            
            if (!validateStep(currentStep)) {
                return;
            }
            
            if (currentStep < 3) {
                $('.step.active').removeClass('active');
                currentStep++;
                $(`.step[data-step="${currentStep}"]`).addClass('active fade-in');
                updateProgressBar();
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                $('.step.active').removeClass('active');
                currentStep--;
                $(`.step[data-step="${currentStep}"]`).addClass('active fade-in');
                updateProgressBar();
            }
        }

        function submitForm() {
            clearErrors();
            
            if (!validateStep(3)) {
                return;
            }
            
            // Collect form data
            formData = {
                name: $('#fullName').val().trim(),
                email: $('#email').val().trim(),
                phone: $('#phone').val().trim(),
                institution: $('#institution').val().trim(),
                hearAbout: $('#howFound').val(),
                usingMIS: $('#currentSoftware').val(),
                password: $('#password').val(),
                password_confirmation: $('#confirmPassword').val(),
                _token: "{{ csrf_token() }}", 
            };
            
            // Show loader
            $('#submitLoader').show();
            $('#submitText').text('Processing...');
            $('.btn-next').prop('disabled', true);
            
            // Simulate AJAX call to Laravel route
            // Replace this with actual AJAX call: $.post("{{ route('register') }}", formData)
            // setTimeout(function() {
            //     // Simulate successful registration
            //     $('.form-container').hide();
            //     $('.progress-container').hide();
            //     $('#otpContainer').show().addClass('fade-in');
            //     $('#userEmail').text(formData.email);
                
            //     // Reset button
            //     $('#submitLoader').hide();
            //     $('#submitText').text('Complete Registration');
            //     $('.btn-next').prop('disabled', false);
            // }, 2000);
            $.ajax({
                url: "{{ route('register') }}",
                method: 'POST',
                data: formData,
                // headers: {  
                //     'X-CSRF-TOKEN': "{{ csrf_token() }}"
                // },
                success: function(response) {
                    $('.form-container').hide();
                    $('.progress-container').hide();
                    $('#otpContainer').show().addClass('fade-in');
                    $('#userEmail').text(formData.email);
                },
                error: function(xhr) {
                    // Handle validation errors
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(field => {
                            showError(field, errors[field][0]);
                        });
                    }
                    
                    // Reset button
                    $('#submitLoader').hide();
                    $('#submitText').text('Complete Registration');
                    $('.btn-next').prop('disabled', false);
                }
            });
        }

        function setupOtpInputs() {
            $('.otp-input').on('input', function(e) {
                const value = e.target.value;
                
                // Only allow numbers
                if (!/^\d*$/.test(value)) {
                    e.target.value = '';
                    return;
                }
                
                const index = parseInt($(this).data('index'));
                
                // Move to next input if current is filled
                if (value && index < 5) {
                    $(`.otp-input[data-index="${index + 1}"]`).focus();
                }
                
                checkOtpComplete();
            });
            
            $('.otp-input').on('keydown', function(e) {
                const index = parseInt($(this).data('index'));
                
                // Handle backspace
                if (e.key === 'Backspace' && !$(this).val() && index > 0) {
                    $(`.otp-input[data-index="${index - 1}"]`).focus();
                }
            });
        }

        function checkOtpComplete() {
            let otp = '';
            $('.otp-input').each(function() {
                otp += $(this).val();
            });
            
            if (otp.length === 6) {
                $('#verifyOtpBtn').prop('disabled', false);
            } else {
                $('#verifyOtpBtn').prop('disabled', true);
            }
        }

        function verifyOtp() {
            let otp = '';
            $('.otp-input').each(function() {
                otp += $(this).val();
            });
            
            // Show loader
            $('#otpLoader').show();
            $('#verifyText').text('Verifying...');
            $('#verifyOtpBtn').prop('disabled', true);
            // return;
            // Simulate AJAX call to verify OTP
            $.ajax({
                url: "/otp/verify",
                method: 'POST',
                data: { 
                    otp: otp,
                    _token: "{{ csrf_token() }}", 
                    // _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Success - redirect to dashboard or show success message
                    $('#otpContainer').hide();
                    $('#successContainer').show().addClass('fade-in');
                    $('#welcomeName').text(formData.full_name);
                    
                    // Redirect after showing success message
                    setTimeout(() => {
                        window.location.href = response.redirect || '/dashboard';
                    }, 3000);
                },
                error: function(xhr) {
                    // Error - show error message
                    console.log(xhr.responseJSON)
                    const error = xhr.responseJSON?.errors?.otp?.[0] || 'The OTP is incorrect. Please try again.';
                    $('#otpError').addClass('error-message').css('color', '#ef4444').text(error).show();
                    $('.otp-input').addClass('input-error');
                    
                    // Reset form
                    $('#otpLoader').hide();
                    $('#verifyText').text('Verify OTP');
                    checkOtpComplete();
                    
                    // Clear OTP inputs
                    $('.otp-input').val('').removeClass('input-error');
                    $('.otp-input').first().focus();
                }
            });
            // Replace with actual AJAX: $.post("/verify-otp", { otp: otp })
            // setTimeout(function() {
            //     // Simulate random success/failure for demo
            //     const isSuccess = Math.random() > 0.3; // 70% success rate
                
            //     if (isSuccess) {
            //         // Success - show welcome message
            //         $('#otpContainer').hide();
            //         $('#successContainer').show().addClass('fade-in');
            //         $('#welcomeName').text(formData.full_name);
            //     } else {
            //         // Error - show error message
            //         $('#otpError').text('The OTP is incorrect. Please try again.').show();
            //         $('.otp-input').addClass('input-error');
                    
            //         // Reset form
            //         $('#otpLoader').hide();
            //         $('#verifyText').text('Verify OTP');
            //         $('#verifyOtpBtn').prop('disabled', false);
                    
            //         // Clear OTP inputs
            //         $('.otp-input').val('').removeClass('input-error');
            //         $('.otp-input').first().focus();
            //     }
            // }, 1500);
        }

        function resendOtp() {
            resendCount++;
            const waitTime = resendCount === 1 ? 60 : 120; // 1 min first time, 2 min after
            
            // Show loader on button
            $('#resendText').html('<span style="display: inline-block; width: 12px; height: 12px; border: 2px solid #667eea; border-top: 2px solid transparent; border-radius: 50%; animation: spin 1s linear infinite; margin-right: 5px;"></span>Sending...');
            $('#resendBtn').prop('disabled', true);
            
            // Simulate AJAX call to resend OTP

            $.ajax({
                url: "/resend-otp",
                method: 'POST',
                data: { 
                    email: formData.email,
                    _token: "{{ csrf_token() }}", 
                },
                success: function(response) {
                    startResendCountdown(waitTime);
                    $('#otpError').removeClass('error-message').css('color', '#10b981').text('OTP has been resent to your email').show();
                    setTimeout(() => $('#otpError').hide(), 3000);
                },
                error: function(xhr) {
                    $('#resendText').text('Resend OTP');
                    $('#resendBtn').prop('disabled', false);
                    $('#otpError').addClass('error-message').css('color', '#ef4444').text('Failed to resend OTP. Please try again.').show();
                }
            });

            // setTimeout(function() {
            //     // Start countdown
            //     startResendCountdown(waitTime);
                
            //     // Show success message briefly
            //     $('#otpError').removeClass('error-message').css('color', '#10b981').text('OTP has been resent to your email').show();
            //     setTimeout(() => $('#otpError').hide(), 3000);
            // }, 1000);
        }

        function startResendCountdown(seconds) {
            let remaining = seconds;
            $('#resendBtn').prop('disabled', true);
            
            const countdown = setInterval(function() {
                const minutes = Math.floor(remaining / 60);
                const secs = remaining % 60;
                $('#resendText').text(`Resend in ${minutes}:${secs.toString().padStart(2, '0')}`);
                
                remaining--;
                
                if (remaining < 0) {
                    clearInterval(countdown);
                    $('#resendText').text('Resend OTP');
                    $('#resendBtn').prop('disabled', false);
                }
            }, 1000);
        }

        // Input event listeners for real-time validation
        $('input, select').on('input change', function() {
            const fieldId = $(this).attr('id');
            if ($(this).hasClass('input-error')) {
                $(this).removeClass('input-error');
                $(`#${fieldId}Error`).hide();
            }
        });

        // Handle form submission with Enter key
        $(document).on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                if ($('#otpContainer').is(':visible')) {
                    if (!$('#verifyOtpBtn').prop('disabled')) {
                        verifyOtp();
                    }
                } else if ($('.step.active').length) {
                    if (currentStep < 3) {
                        nextStep();
                    } else {
                        submitForm();
                    }
                }
            }
        });

        // OTP input navigation with arrow keys
        $('.otp-input').on('keydown', function(e) {
            const index = parseInt($(this).data('index'));
            
            if (e.key === 'ArrowRight' && index < 5) {
                $(`.otp-input[data-index="${index + 1}"]`).focus();
            } else if (e.key === 'ArrowLeft' && index > 0) {
                $(`.otp-input[data-index="${index - 1}"]`).focus();
            }
        });

        // Clear OTP error when user starts typing
        $('.otp-input').on('input', function() {
            $('#otpError').hide().removeClass('error-message').css('color', '#ef4444');
        });

        /*
        // Actual AJAX implementation examples (uncomment and modify as needed):
        
        function submitForm() {
            clearErrors();
            
            if (!validateStep(3)) {
                return;
            }
            
            // Collect form data
            formData = {
                full_name: $('#fullName').val().trim(),
                email: $('#email').val().trim(),
                phone: $('#phone').val().trim(),
                institution_name: $('#institution').val().trim(),
                how_found: $('#howFound').val(),
                current_software: $('#currentSoftware').val(),
                password: $('#password').val(),
                password_confirmation: $('#confirmPassword').val(),
                _token: $('meta[name="csrf-token"]').attr('content') // Add CSRF token
            };
            
            // Show loader
            $('#submitLoader').show();
            $('#submitText').text('Processing...');
            $('.btn-next').prop('disabled', true);
            
            $.ajax({
                url: "{{ route('register') }}",
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('.form-container').hide();
                    $('.progress-container').hide();
                    $('#otpContainer').show().addClass('fade-in');
                    $('#userEmail').text(formData.email);
                },
                error: function(xhr) {
                    // Handle validation errors
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(field => {
                            showError(field, errors[field][0]);
                        });
                    }
                    
                    // Reset button
                    $('#submitLoader').hide();
                    $('#submitText').text('Complete Registration');
                    $('.btn-next').prop('disabled', false);
                }
            });
        }
        
        function verifyOtp() {
            let otp = '';
            $('.otp-input').each(function() {
                otp += $(this).val();
            });
            
            // Show loader
            $('#otpLoader').show();
            $('#verifyText').text('Verifying...');
            $('#verifyOtpBtn').prop('disabled', true);
            
            $.ajax({
                url: "/verify-otp",
                method: 'POST',
                data: { 
                    otp: otp,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Success - redirect to dashboard or show success message
                    $('#otpContainer').hide();
                    $('#successContainer').show().addClass('fade-in');
                    $('#welcomeName').text(formData.full_name);
                    
                    // Redirect after showing success message
                    setTimeout(() => {
                        window.location.href = response.redirect || '/dashboard';
                    }, 3000);
                },
                error: function(xhr) {
                    // Error - show error message
                    const error = xhr.responseJSON?.errors?.otp?.[0] || 'The OTP is incorrect. Please try again.';
                    $('#otpError').addClass('error-message').css('color', '#ef4444').text(error).show();
                    $('.otp-input').addClass('input-error');
                    
                    // Reset form
                    $('#otpLoader').hide();
                    $('#verifyText').text('Verify OTP');
                    checkOtpComplete();
                    
                    // Clear OTP inputs
                    $('.otp-input').val('').removeClass('input-error');
                    $('.otp-input').first().focus();
                }
            });
        }
        
        function resendOtp() {
            resendCount++;
            const waitTime = resendCount === 1 ? 60 : 120;
            
            $('#resendText').html('<span style="display: inline-block; width: 12px; height: 12px; border: 2px solid #667eea; border-top: 2px solid transparent; border-radius: 50%; animation: spin 1s linear infinite; margin-right: 5px;"></span>Sending...');
            $('#resendBtn').prop('disabled', true);
            
            $.ajax({
                url: "/resend-otp",
                method: 'POST',
                data: { 
                    email: formData.email,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    startResendCountdown(waitTime);
                    $('#otpError').removeClass('error-message').css('color', '#10b981').text('OTP has been resent to your email').show();
                    setTimeout(() => $('#otpError').hide(), 3000);
                },
                error: function(xhr) {
                    $('#resendText').text('Resend OTP');
                    $('#resendBtn').prop('disabled', false);
                    $('#otpError').addClass('error-message').css('color', '#ef4444').text('Failed to resend OTP. Please try again.').show();
                }
            });
        }
        */
    </script>
</body>
</html>