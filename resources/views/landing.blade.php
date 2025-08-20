@extends('layouts.app')

@section('title')
Home
@endsection

@section('content')
     <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg"></div>
        <div class="floating-element floating-1"></div>
        <div class="floating-element floating-2"></div>
        <div class="floating-element floating-3"></div>
        
        <div class="container">
            <div class="hero-content">
                {{-- <div class="hero-badge">
                    <i class="far fa-check-circle"></i>
                    <span>Trusted by 500+ Educational Institutions</span>
                </div> --}}
                
                <h1 class="hero-title">
                    <span class="gradient-text">Automated Exam</span><br>
                    <span class="normal-text">Seat Planning</span><br>
                    <span class="gradient-secondary">Made Simple</span>
                </h1>
                
                <p class="hero-subtitle">
                    Generate optimal exam seat plans in seconds with our intelligent system. 
                    Drag-and-drop customization, automated notifications, and complete student management.
                </p>
                
                <div class="hero-features">
                    <div class="hero-feature">
                        <i class="far fa-check-circle"></i>
                        <span>Instant Seat Plan Generation</span>
                    </div>
                    <div class="hero-feature">
                        <i class="far fa-check-circle"></i>
                        <span>Drag & Drop Customization</span>
                    </div>
                    <div class="hero-feature">
                        <i class="far fa-check-circle"></i>
                        <span>Notifications</span>
                    </div>
                    <div class="hero-feature">
                        <i class="far fa-check-circle"></i>
                        <span>Complete CRUD Management</span>
                    </div>
                </div>
                
                <div class="hero-buttons">
                    <!-- Contact Us Button -->
                    <a class="btn btn-hero btn-large" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#contactUsModal">
                      Contact Us
                    </a>
                </div>
                
                {{-- <p class="hero-trust">
                    No credit card required • 14-day free trial • Cancel anytime
                </p> --}}
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="gradient-text">Powerful Features</span>
                </h2>
                <p class="section-subtitle">
                    Everything you need to streamline your exam seat planning process and improve efficiency across your educational institution.
                </p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="feature-title">Automated Seat Plan Generation</h3>
                    <p class="feature-description">
                        Generate optimal exam seat arrangements instantly with our intelligent algorithm that considers room capacity, student requirements, and examination rules.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mouse-pointer"></i>
                    </div>
                    <h3 class="feature-title">Drag-and-Drop Customization</h3>
                    <p class="feature-description">
                        Easily customize seat arrangements with intuitive drag-and-drop interface. Perfect for accommodating special needs students and last-minute changes.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <h3 class="feature-title">Complete CRUD Management</h3>
                    <p class="feature-description">
                        Manage classes, sections, students, and staff data with our comprehensive system. Import from Excel, update in bulk, and maintain accurate records.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comment"></i>
                    </div>
                    <h3 class="feature-title">Smart Notifications & SMS</h3>
                    <p class="feature-description">
                        Automatically notify students about their seat assignments via SMS or email. Customizable templates and delivery scheduling included.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Secure & Compliant</h3>
                    <p class="feature-description">
                        Enterprise-grade security with role-based access control. GDPR compliant with data encryption and secure backup systems.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">Real-time Updates</h3>
                    <p class="feature-description">
                        Make changes on the fly with real-time synchronization. All stakeholders see updates instantly across all devices.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Invigilator Room Planning</h3>
                    <p class="feature-description">
                        Create Invigilator room planning as well with randomized staff assign.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h3 class="feature-title">Flexible Configuration</h3>
                    <p class="feature-description">
                        Customize the system to match your institution's specific requirements with flexible rules, templates, and workflow settings.
                    </p>
                </div>
            </div>
            
            {{-- <div class="features-cta">
                <button class="btn btn-secondary">
                    <i class="fas fa-bolt"></i>
                    See All Features in Action
                </button>
            </div> --}}
        </div>
    </section>

    <style>
      .modal-header{
        background: var(--gradient-hero);
        color: white;
      }
    </style>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h3>Login</h3>
          </div>
          <div class="modal-body">
            <!-- Tab content -->
            <div class="tab-content" id="modalTabContent">
              <!-- Login Tab -->
              <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                {{-- <h5>Login to Your Account</h5> --}}
                <p>Welcome back! Please enter your credentials to access your account.</p>
                <form id="loginForm">
                  <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" required>
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" required>
                  </div>
                  <button type="submit" class="btn btn-primary w-100">Log in</button>
                  {{-- <p style="text-align: center;margin-top:5px;">New Here ? <a href="{{ route('register') }}">Register</a> </p> --}}
                </form>
                <div id="loginError" class="mt-3 text-danger" style="display: none;"></div> <!-- Error div for login -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Contact Us Modal -->
    <div class="modal fade" id="contactUsModal" tabindex="-1" aria-labelledby="contactUsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="contactUsModalLabel">Contact Us</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>We’d love to hear from you! Please fill out the form below to send us your message.</p>
                    <form id="contactUsForm" action="{{route('message.store')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Message</button>
                    </form>
                    <div id="contactError" class="mt-3 text-danger" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

@endsection
