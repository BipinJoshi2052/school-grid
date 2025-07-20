<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Seat Plan Management System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #5d6483 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 1300px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }

        .left-panel {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left-panel h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .left-panel h2 {
            font-size: 1.5rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .benefit {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
            opacity: 0.95;
        }

        .benefit-icon {
            width: 24px;
            height: 24px;
            margin-right: 15px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .benefit h3 {
            font-size: 1.1rem;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .benefit p {
            line-height: 1.5;
            opacity: 0.9;
        }

        .right-panel {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .register-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .register-header h2 {
            color: #333;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .register-header p {
            color: #666;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .required::after {
            content: " *";
            color: #e74c3c;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .phone-group {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 10px;
        }

        .country-select {
            padding: 12px 8px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 1rem;
            background-color: #fafafa;
            transition: all 0.3s ease;
        }

        .country-select:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .radio-option input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
        }

        .radio-option label {
            margin: 0;
            font-weight: normal;
            cursor: pointer;
        }

        .btn-register {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-register:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 5px;
            display: none;
        }

        .form-control.error {
            border-color: #e74c3c;
            background-color: #fdf2f2;
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-input {
            padding-right: 50px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .password-toggle:focus {
            outline: 2px solid #667eea;
            outline-offset: 2px;
            border-radius: 4px;
        }

        .eye-icon {
            transition: all 0.3s ease;
        }

        .password-toggle.active .eye-icon {
            opacity: 0.7;
        }

        .password-requirements {
            margin-top: 5px;
        }

        .password-requirements small {
            color: #666;
            font-size: 0.8rem;
            line-height: 1.4;
        }

        .password-strength {
            margin-top: 8px;
            height: 4px;
            background-color: #e1e8ed;
            border-radius: 2px;
            overflow: hidden;
            display: none;
        }

        .password-strength.show {
            display: block;
        }

        .password-strength-bar {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .password-strength-weak {
            background-color: #e74c3c;
            width: 25%;
        }

        .password-strength-medium {
            background-color: #f39c12;
            width: 60%;
        }

        .password-strength-strong {
            background-color: #27ae60;
            width: 100%;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
                max-width: 500px;
            }

            .left-panel {
                padding: 40px 30px;
            }

            .left-panel h1 {
                font-size: 2rem;
            }

            .right-panel {
                padding: 40px 30px;
            }

            .phone-group {
                grid-template-columns: 100px 1fr;
            }
        }
        .hidden{
            display: none;
        }
        .error-message-div{
            background: red;
            padding: 10px;
            color: white;
            margin-bottom: 5px;
            border-radius: 5px;
        }
        .link-to-home{
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <h1><a class="link-to-home" href="{{ route('home') }}"><b>Edu</b>Sched</a></h1>
            <h2>Smart Exam Seating Solutions</h2>
            
            <div class="benefit">
                <svg class="benefit-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3>Automated Seat Assignment</h3>
                    <p>Instantly generate optimal seating arrangements based on student data, exam requirements, and room capacity.</p>
                </div>
            </div>

            <div class="benefit">
                <svg class="benefit-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3>Multiple Room Management</h3>
                    <p>Manage seating across multiple examination halls simultaneously with real-time updates and conflict detection.</p>
                </div>
            </div>

            <div class="benefit">
                <svg class="benefit-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3>Easy Integration</h3>
                    <p>Seamlessly integrate with your existing school management system or import data from Excel/CSV files.</p>
                </div>
            </div>

            <div class="benefit">
                <svg class="benefit-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3>Detailed Reports</h3>
                    <p>Generate comprehensive seating charts, attendance sheets, and examination reports with just one click.</p>
                </div>
            </div>
        </div>

        <div class="right-panel">
            <div class="register-header">
                <h2>Get Started Today</h2>
                <p>Create your account and streamline your seating plans</p>
            </div>

            <form id="registerForm">

                <div class="form-group">
                    <label for="name" class="required">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name">
                    <div class="error-message" id="nameError">Please enter your full name</div>
                </div>

                <div class="form-group">
                    <label for="email" class="required">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address">
                    <div class="error-message" id="emailError">Please enter a valid email address</div>
                </div>

                <div class="form-group">
                    <label class="required">Phone Number</label>
                    <div class="phone-group">
                        <select id="countryCode" name="countryCode" class="country-select">
                            <option value="+977">🇳🇵 +977</option>
                            <option value="+91">🇮🇳 +91</option>
                            <option value="+1">🇺🇸 +1</option>
                            <option value="+44">🇬🇧 +44</option>
                            <option value="+86">🇨🇳 +86</option>
                            <option value="+81">🇯🇵 +81</option>
                            <option value="+82">🇰🇷 +82</option>
                            <option value="+65">🇸🇬 +65</option>
                            <option value="+60">🇲🇾 +60</option>
                            <option value="+66">🇹🇭 +66</option>
                            <option value="+84">🇻🇳 +84</option>
                            <option value="+880">🇧🇩 +880</option>
                            <option value="+94">🇱🇰 +94</option>
                            <option value="+61">🇦🇺 +61</option>
                            <option value="+49">🇩🇪 +49</option>
                            <option value="+33">🇫🇷 +33</option>
                            <option value="+39">🇮🇹 +39</option>
                            <option value="+34">🇪🇸 +34</option>
                            <option value="+7">🇷🇺 +7</option>
                            <option value="+971">🇦🇪 +971</option>
                        </select>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="Enter phone number">
                    </div>
                    <div class="error-message" id="phoneError">Please enter a valid phone number</div>
                </div>

                <div class="form-group">
                    <label for="institution" class="required">Institution Name</label>
                    <input type="text" id="institution" name="institution" class="form-control" placeholder="Enter your school/college/institution name">
                    <div class="error-message" id="institutionError">Please enter your institution name</div>
                </div>

                <div class="form-group">
                    <label for="password" class="required">Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password" name="password" class="form-control password-input" placeholder="Enter your password">
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <svg class="eye-icon" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <div class="password-requirements">
                        <small>Password must be at least 8 characters long and contain uppercase, lowercase, number, and special character</small>
                    </div>
                    <div class="error-message" id="passwordError">Please enter a valid password</div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword" class="required">Confirm Password</label>
                    <div class="password-input-wrapper">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        {{-- <input type="password" id="confirmPassword" name="password_confirmation" class="form-control password-input" placeholder="Confirm your password" required> --}}
                        {{-- <input type="password" id="password-confirm" name="password_confirmation" class="form-control password-input" placeholder="Confirm your password"> --}}
                        <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                            <svg class="eye-icon" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <div class="error-message" id="confirmPasswordError">Please confirm your password</div>
                </div>

                <div class="form-group">
                    <label for="hearAbout" class="required">How did you come across our service?</label>
                    <select id="hearAbout" name="hearAbout" class="form-control">
                        <option value="">Select an option</option>
                        <option value="google">Google Search</option>
                        <option value="social-media">Social Media</option>
                        <option value="referral">Friend/Colleague Referral</option>
                        <option value="conference">Educational Conference</option>
                        <option value="advertisement">Online Advertisement</option>
                        <option value="other">Other</option>
                    </select>
                    <div class="error-message" id="hearAboutError">Please select how you heard about us</div>
                </div>

                <div class="form-group">
                    <label class="required">Are you using any software for managing your school operations?</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="misYes" name="usingMIS" value="yes">
                            <label for="misYes">Yes</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="misNo" name="usingMIS" value="no">
                            <label for="misNo">No</label>
                        </div>
                    </div>
                    <div class="error-message" id="misError">Please select an option</div>
                </div>
                <div class="error-message-div hidden">
                    <p></p>
                </div>

                <button type="submit" class="btn-register">Create Account</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Form validation functions
            function validateName() {
                const name = $('#name').val().trim();
                console.log(name)
                if (name.length === 0) {
                    showError('name', 'Please enter your full name');
                    return false;
                }
                if (name.length < 2) {
                    showError('name', 'Name must be at least 2 characters long');
                    return false;
                }
                hideError('name');
                return true;
            }

            function validateEmail() {
                const email = $('#email').val().trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (email.length === 0) {
                    showError('email', 'Please enter your email address');
                    return false;
                }
                if (!emailRegex.test(email)) {
                    showError('email', 'Please enter a valid email address');
                    return false;
                }
                hideError('email');
                return true;
            }
            $('#phone').on('keydown', function(e) {
                // Allow: Backspace, Delete, Arrow keys, Tab, and numbers
                if ($.inArray(e.keyCode, [46, 8, 9, 37, 38, 39, 40]) !== -1 ||
                    (e.keyCode >= 48 && e.keyCode <= 57) || // Allow numbers
                    (e.keyCode >= 96 && e.keyCode <= 105)) { // Allow numpad numbers
                    return; // Allow these keys
                }
                e.preventDefault(); // Prevent the keypress for other characters
            });
            function validatePhone() {
                const phone = $('#phone').val().trim();
                const phoneRegex = /^[0-9]{7,15}$/;
                
                if (phone.length === 0) {
                    showError('phone', 'Please enter your phone number');
                    return false;
                }
                // if (!phoneRegex.test(phone)) {
                //     showError('phone', 'Please enter a valid phone number (7-15 digits)');
                //     return false;
                // }
                hideError('phone');
                return true;
            }

            function validateInstitution() {
                const institution = $('#institution').val().trim();
                if (institution.length === 0) {
                    showError('institution', 'Please enter your institution name');
                    return false;
                }
                if (institution.length < 2) {
                    showError('institution', 'Institution name must be at least 2 characters long');
                    return false;
                }
                hideError('institution');
                return true;
            }

            function validatePassword() {
                const password = $('#password').val();
                const minLength = 8;
                const hasUpperCase = /[A-Z]/.test(password);
                const hasLowerCase = /[a-z]/.test(password);
                const hasNumbers = /\d/.test(password);
                const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

                if (password.length === 0) {
                    showError('password', 'Please enter a password');
                    return false;
                }
                if (password.length < minLength) {
                    showError('password', 'Password must be at least 8 characters long');
                    return false;
                }
                if (!hasUpperCase) {
                    showError('password', 'Password must contain at least one uppercase letter');
                    return false;
                }
                if (!hasLowerCase) {
                    showError('password', 'Password must contain at least one lowercase letter');
                    return false;
                }
                if (!hasNumbers) {
                    showError('password', 'Password must contain at least one number');
                    return false;
                }
                if (!hasSpecialChar) {
                    showError('password', 'Password must contain at least one special character');
                    return false;
                }
                
                hideError('password');
                return true;
            }

            function validateConfirmPassword() {
                const password = $('#password').val();
                const confirmPassword = $('#confirmPassword').val();
                
                if (confirmPassword.length === 0) {
                    showError('confirmPassword', 'Please confirm your password');
                    return false;
                }
                if (password !== confirmPassword) {
                    showError('confirmPassword', 'Passwords do not match');
                    return false;
                }
                hideError('confirmPassword');
                return true;
            }

            function validateHearAbout() {
                const hearAbout = $('#hearAbout').val();
                console.log(hearAbout)
                if (hearAbout == '') {
                    showError('hearAbout', 'Please select how you heard about us');
                    return false;
                }
                hideError('hearAbout');
                return true;
            }

            function validateMIS() {
                const usingMIS = $('input[name="usingMIS"]:checked').val();
                if (!usingMIS) {
                    showError('mis', 'Please select an option');
                    return false;
                }
                hideError('mis');
                return true;
            }

            function showError(field, message) {
                $('#' + field).addClass('error');
                $('#' + field + 'Error').text(message).show();
            }

            function hideError(field) {
                $('#' + field).removeClass('error');
                $('#' + field + 'Error').hide();
            }

            // Real-time validation
            $('#name').on('blur', validateName);
            $('#email').on('blur', validateEmail);
            // $('#phone').on('blur', validatePhone);
            $('#institution').on('blur', validateInstitution);
            $('#password').on('blur', validatePassword);
            $('#confirmPassword').on('blur', validateConfirmPassword);
            $('#hearAbout').on('change', validateHearAbout);
            $('input[name="usingMIS"]').on('change', validateMIS);

            // Validate confirm password when main password changes
            $('#password').on('input', function() {
                if ($('#confirmPassword').val().length > 0) {
                    setTimeout(validateConfirmPassword, 100);
                }
            });

            // Remove error styling on input
            $('.form-control').on('input', function() {
                $(this).removeClass('error');
                $(this).next('.error-message').hide();
            });

            // Form submission
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validate all fields
                // const isNameValid = validateName();
                // const isEmailValid = validateEmail();
                // const isPhoneValid = validatePhone();
                // const isInstitutionValid = validateInstitution();
                // const isPasswordValid = validatePassword();
                // const isConfirmPasswordValid = validateConfirmPassword();
                // const isHearAboutValid = validateHearAbout();
                // const isMISValid = validateMIS();

                // if (isNameValid && isEmailValid && isPhoneValid && isInstitutionValid && 
                //     isPasswordValid && isConfirmPasswordValid && isHearAboutValid && isMISValid) {
                    // Disable submit button
                    $('.btn-register').prop('disabled', true).text('Creating Account...');
                    
                    // Collect form data
                    const formData = {
                        name: $('#name').val(),
                        email: $('#email').val(),
                        countryCode: $('#countryCode').val(),
                        phone: $('#phone').val(),
                        password_confirmation: $('#password-confirm').val(),
                        institution: $('#institution').val(),
                        password: $('#password').val(),
                        hearAbout: $('#hearAbout').val(),
                        usingMIS: $("input[name='usingMIS']:checked").val(),
                        _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                    };

                    // Send AJAX request
                    $.ajax({
                        url: '{{ route("register") }}',
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            $('.error-message-div').addClass('hidden');
                            $('#registerForm')[0].reset();  // Reset form
                            window.location.href = "{{ route('otp.verify') }}";
                            // if (response.success) {
                            //     $('.error-message-div').addClass('hidden');
                            //     // alert('Registration successful! Welcome to SeatPlan Pro. You will receive a confirmation email shortly.');
                            //     $('#registerForm')[0].reset();  // Reset form
                            //     window.location.href = "{{ route('otp.verify') }}";
                            // } else {
                            //     // Show errors
                            //     for (const field in response.errors) {
                            //         $(`#${field}`).addClass('error'); // Highlight field with error
                            //         $(`#${field}_error`).text(response.errors[field][0]); // Show error message
                            //     }
                            // }
                            $('.btn-register').prop('disabled', false).text('Create Account');
                        },
                        error: function(xhr, status, error) {
                            $('.error-message-div').removeClass('hidden');
                            var errors = xhr.responseJSON.errors; 
                            var errorMessages = ''; // Initialize an empty string to accumulate error messages
                            for (var field in errors) {
                                if (errors.hasOwnProperty(field)) {
                                    // For each field, loop through the error messages (since there might be multiple errors per field)
                                    var fieldErrors = errors[field];
                                    fieldErrors.forEach(function(errorMessage) {
                                        // Append each error message with the field name to the errorMessages string
                                        errorMessages += errorMessage + '<br>';
                                    });
                                }
                            }

                            // Set the error messages to the container and remove the display: none
                            $('.error-message-div p').html(errorMessages).css('display', 'block');
                            console.log("AJAX Error: " + error);
                            $('.btn-register').prop('disabled', false).text('Create Account');
                        }
                    });

                    // Simulate form submission
                    // setTimeout(function() {
                    //     alert('Registration successful! Welcome to SeatPlan Pro. You will receive a confirmation email shortly.');
                        
                    //     // Reset form
                    //     $('#registerForm')[0].reset();
                    //     $('.btn-register').prop('disabled', false).text('Create Account');
                    // }, 2000);
                // } else {
                    // Focus on first error field
                    // $('.form-control.error').first().focus();
                // }
            });

            // Add some interactive effects
            $('.form-control, .country-select').on('focus', function() {
                $(this).parent().addClass('focused');
            }).on('blur', function() {
                $(this).parent().removeClass('focused');
            });
        });

        // Password toggle functionality
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleButton = passwordField.nextElementSibling;
            const eyeIcon = toggleButton.querySelector('.eye-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleButton.classList.add('active');
                eyeIcon.innerHTML = `
                    <path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.142 4.142M9.878 9.878l4.242 4.242M9.878 9.878L6.05 6.05M9.878 9.878l-1.415-1.415M14.12 14.12L17.95 17.95M14.12 14.12l1.415 1.415"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
                `;
                eyeIcon.setAttribute('fill', 'none');
                eyeIcon.setAttribute('stroke', 'currentColor');
            } else {
                passwordField.type = 'password';
                toggleButton.classList.remove('active');
                eyeIcon.innerHTML = `
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                `;
                eyeIcon.setAttribute('fill', 'currentColor');
                eyeIcon.removeAttribute('stroke');
                eyeIcon.removeAttribute('stroke-linecap');
                eyeIcon.removeAttribute('stroke-linejoin');
                eyeIcon.removeAttribute('stroke-width');
            }
        }
    </script>
</body>
</html>