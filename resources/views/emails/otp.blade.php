<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Account - EduShed</title>
    <style>
        * {
margin: 0;
padding: 0;
box-sizing: border-box;
        }

        body {
font-family: 'Arial', sans-serif;background-color: #f8fafc;line-height: 1.6;color: #333333;
        }

        .email-container {
max-width: 600px;margin: 0 auto;background-color: #ffffff;border-radius: 12px;overflow: hidden;box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .header {
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);padding: 40px 30px;text-align: center;color: white;
        }

        .logo-container {
display: flex;align-items: center;justify-content: center;margin-bottom: 20px;gap: 12px;
        }

        .logo {
width: 40px;height: 40px;background: white;border-radius: 50%;display: flex;align-items: center;justify-content: center;font-weight: bold;color: #667eea;font-size: 18px;
        }

        .company-name {
font-size: 28px;font-weight: bold;letter-spacing: -0.5px;
        }

        .header h1 {
font-size: 24px;margin-bottom: 8px;font-weight: 600;
        }

        .header p {
font-size: 16px;opacity: 0.9;font-weight: 300;
        }

        .content {
padding: 40px 30px;text-align: center;
        }

        .greeting {
font-size: 20px;color: #333;margin-bottom: 20px;font-weight: 600;
        }

        .message {
font-size: 16px;color: #666;margin-bottom: 30px;line-height: 1.7;
        }

        .otp-container {
background: linear-gradient(135deg, #667eea10 0%, #764ba220 100%);border: 2px dashed #667eea;border-radius: 12px;padding: 30px;margin: 30px 0;text-align: center;
        }

        .otp-label {
font-size: 14px;color: #666;margin-bottom: 10px;text-transform: uppercase;letter-spacing: 1px;font-weight: 600;
        }

        .otp-code {
font-size: 36px;font-weight: bold;color: #667eea;font-family: 'Courier New', monospace;letter-spacing: 8px;margin: 10px 0;
        }

        .otp-validity {
font-size: 14px;color: #e74c3c;margin-top: 15px;font-weight: 600;
        }

        .instructions {
background-color: #f8fafc;border-left: 4px solid #667eea;padding: 20px;margin: 30px 0;text-align: left;border-radius: 0 8px 8px 0;
        }

        .instructions h3 {
color: #333;margin-bottom: 12px;font-size: 16px;
        }

        .instructions ol {
margin-left: 20px;color: #666;
        }

        .instructions li {
margin-bottom: 8px;font-size: 14px;
        }

        .security-note {
background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);border-radius: 8px;padding: 20px;margin: 25px 0;border-left: 4px solid #e17055;
        }

        .security-note h4 {
color: #d63031;margin-bottom: 8px;font-size: 16px;display: flex;align-items: center;gap: 8px;
        }

        .security-note p {
color: #2d3436;font-size: 14px;line-height: 1.5;
        }

        .footer {
background-color: #f8fafc;padding: 30px;text-align: center;border-top: 1px solid #e1e8ed;
        }

        .footer p {
color: #666;font-size: 14px;margin-bottom: 8px;
        }

        .footer .company-info {
color: #333;font-weight: 600;margin-bottom: 15px;
        }

        .social-links {
margin: 20px 0;
        }

        .social-links a {
display: inline-block;width: 36px;height: 36px;background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);color: white;text-decoration: none;border-radius: 50%;margin: 0 8px;line-height: 36px;font-size: 16px;transition: transform 0.3s ease;
        }

        .social-links a:hover {
transform: translateY(-2px);
        }

        .divider {
            height: 1px;background: linear-gradient(90deg, transparent 0%, #667eea 50%, transparent 100%);margin: 25px 0;
        }

        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }

            .header {
                padding: 30px 20px;
            }

            .content {
                padding: 30px 20px;
            }

            .otp-code {
                font-size: 28px;letter-spacing: 4px;
            }

            .company-name {
                font-size: 24px;
            }
        }

        .warning-icon {
            width: 16px;height: 16px;fill: currentColor;
        }
    </style>
</head>
<body style="font-family: 'Arial', sans-serif;background-color: #f8fafc;line-height: 1.6;color: #333333;">
    <div class="email-container" style="max-width: 600px;margin: 0 auto;background-color: #ffffff;border-radius: 12px;overflow: hidden;box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);">
        <!-- Header -->
        <div class="header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);padding: 40px 30px;text-align: center;color: white;">
<div class="logo-container" style="display: flex;align-items: center;justify-content: center;margin-bottom: 20px;gap: 12px;">
    <div class="logo" style="width: 40px;height: 40px;background: white;border-radius: 50%;display: flex;align-items: center;justify-content: center;font-weight: bold;color: #667eea;font-size: 18px;">📚</div>
    <div class="company-name" style="font-size: 28px;font-weight: bold;letter-spacing: -0.5px;"><b>Edu</b>Sched</div>
</div>
<h1 style="font-size: 24px;margin-bottom: 8px;font-weight: 600;">Account Verification</h1>
<p style="font-size: 16px;opacity: 0.9;font-weight: 300;color:white;">Secure your account with OTP verification</p>
        </div>

        <!-- Content -->
        <div style="padding: 40px 30px;text-align: center;" class="content">
<div style="font-size: 20px;color: #333;margin-bottom: 20px;font-weight: 600;" class="greeting">Welcome to EduSched!</div>

<div style="font-size: 16px;color: #666;margin-bottom: 30px;line-height: 1.7;" class="message">
    Thank you for registering with <strong>EduShed</strong> - your smart exam seating solution. 
    To complete your account setup and ensure security, please verify your email address using the OTP code below.
</div>

<!-- OTP Container -->
<div style="background: linear-gradient(135deg, #667eea10 0%, #764ba220 100%);border: 2px dashed #667eea;border-radius: 12px;padding: 30px;margin: 30px 0;text-align: center;" class="otp-container">
    <div style="font-size: 14px;color: #666;margin-bottom: 10px;text-transform: uppercase;letter-spacing: 1px;font-weight: 600;" class="otp-label">Your Verification Code</div>
    <div style="font-size: 36px;font-weight: bold;color: #667eea;font-family: 'Courier New', monospace;letter-spacing: 8px;margin: 10px 0;" class="otp-code">
        {{ $otp }}
    </div>
    <div style="font-size: 14px;color: #e74c3c;margin-top: 15px;font-weight: 600;" class="otp-validity">⏰ Valid for 10 minutes</div>
</div>

<!-- Instructions -->
<div style="background-color: #f8fafc;border-left: 4px solid #667eea;padding: 20px;margin: 30px 0;text-align: left;border-radius: 0 8px 8px 0;" class="instructions">
    <h3 style="color: #333;margin-bottom: 12px;font-size: 16px;">📝 How to verify your account:</h3>
    <ol style="margin-left: 20px;color: #666;">
        <li style="margin-bottom: 8px;font-size: 14px;">Go back to the EduShed registration page</li>
        <li style="margin-bottom: 8px;font-size: 14px;">Enter the 6-digit OTP code shown above</li>
        <li style="margin-bottom: 8px;font-size: 14px;">Click "Verify Account" to complete your registration</li>
        <li style="margin-bottom: 8px;font-size: 14px;">Start managing your exam seating arrangements!</li>
    </ol>
</div>

<!-- Security Note -->
<div style="background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);border-radius: 8px;padding: 20px;margin: 25px 0;border-left: 4px solid #e17055;" class="security-note">
    <h4 style="color: #d63031;margin-bottom: 8px;font-size: 16px;display: flex;align-items: center;gap: 8px;">
        <svg style="" class="warning-icon" viewBox="0 0 20 20">
<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        Security Notice
    </h4>
    <p style="color: #2d3436;font-size: 14px;line-height: 1.5;">
        Never share this OTP code with anyone. EduShed staff will never ask for your verification code. 
        If you didn't request this verification, please ignore this email or contact our support team.
    </p>
</div>

<div style="height: 1px;background: linear-gradient(90deg, transparent 0%, #667eea 50%, transparent 100%);margin: 25px 0;" class="divider"></div>

<div style="" class="message">
    Once verified, you'll have access to our powerful seat plan management system with features like 
    automated seat assignment, multi-room management, and detailed reporting capabilities.
</div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc;padding: 30px;text-align: center;border-top: 1px solid #e1e8ed;" class="footer">
            <div style="color: #333;font-weight: 600;margin-bottom: 15px;" class="company-info"><b>Edu</b>Shed - Smart Exam Seating Solutions</div>

            <div style="margin: 20px 0;" class="social-links">
                <a style="display: inline-block;width: 36px;height: 36px;background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);color: white;text-decoration: none;border-radius: 50%;margin: 0 8px;line-height: 36px;font-size: 16px;transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'" href="#" title="Facebook">📘</a>
                <a style="display: inline-block;width: 36px;height: 36px;background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);color: white;text-decoration: none;border-radius: 50%;margin: 0 8px;line-height: 36px;font-size: 16px;transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'" href="#" title="Twitter">🐦</a>
                <a style="display: inline-block;width: 36px;height: 36px;background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);color: white;text-decoration: none;border-radius: 50%;margin: 0 8px;line-height: 36px;font-size: 16px;transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'" href="#" title="LinkedIn">💼</a>
                <a style="display: inline-block;width: 36px;height: 36px;background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);color: white;text-decoration: none;border-radius: 50%;margin: 0 8px;line-height: 36px;font-size: 16px;transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'" href="#" title="Email">✉️</a>
            </div>

            <p style="color: #666;font-size: 14px;margin-bottom: 8px;">Questions? Contact our support team at <strong>support@edushed.com</strong></p>
            <p style="color: #666;font-size: 14px;margin-bottom: 8px;">📞 +977-1-XXXXXXX | 🌐 www.edushed.com</p>

            <div style="margin-top: 20px; font-size: 12px; color: #999;">
                <p>This is an automated message. Please do not reply to this email.</p>
                <p>© 2025 EduShed. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>