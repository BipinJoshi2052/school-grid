<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SeatPlan-Pro - @yield('title')</title>
    <meta name="description" content="Generate optimal exam seat plans in seconds with our intelligent system. Drag-and-drop customization, automated notifications, and complete student management.">
    <meta name="author" content="SeatPlan Pro">
    
    <meta property="og:title" content="SeatPlan Pro - Automated Exam Seat Planning Made Simple">
    <meta property="og:description" content="Generate optimal exam seat plans in seconds with our intelligent system. Drag-and-drop customization, automated notifications, and complete student management.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('images/favicon.ico') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@seatplanpro">
    <meta name="twitter:image" content="{{ asset('images/favicon.ico') }}">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.ico') }}">
    <link href="{{ asset('images/apple-touch-icon.png')}}" rel="apple-touch-icon">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('website/css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/libs/toastr/toastr.min.css')}}">   
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2W4L40C767"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-2W4L40C767');
    </script>
    <style>
      .toast-container {
          position: fixed;
          top: 20px;
          left: 0!important;
          right: 100px!important;
          z-index: 1056; /* Above Bootstrap modal (z-index: 1055) */
          max-width: 300px; /* Prevent Toastr from being too wide */
      }
      .toast {
          margin-right: 0; /* Remove any extra right margin */
          width: 100%; /* Ensure Toastr fits within container */
      }
    </style>
</head>

<body class="index-page">

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="nav-content">
                <a href="#" class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span class="logo-text">SeatPlan Pro</span>
                </a>
                
                {{-- <nav class="nav-menu" id="navMenu">
                    <a href="#features" class="nav-link">Features</a>
                    <a href="#revenue" class="nav-link">Solutions</a>
                    <a href="pricing.html" class="nav-link">Pricing</a>
                    <a href="student-search.html" class="nav-link">Find Seat</a>
                    <a href="#contact" class="nav-link">Contact</a>
                </nav> --}}
                
                <div class="nav-actions">
                  @guest
                    <a class="btn-getstarted btn btn-outline" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      Login
                    </a>
                    {{-- <a href="#" class="btn btn-hero">Register</a> --}}
                    {{-- <a href="{{ route('register') }}" class="btn btn-hero">Register</a> --}}
                  @endguest

                  @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-hero">Dashboard</a>
                  @endauth
                </div>
                
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>
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
                    <h3 class="feature-title">Multi-user Collaboration</h3>
                    <p class="feature-description">
                        Allow multiple staff members to work simultaneously with role-based permissions and audit trails for accountability.
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

    {{-- <main class="main">
      @yield('content')
    </main> --}}
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
                    <form id="contactUsForm" action="{{route('feedback')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
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

{{-- </div> --}}

  <footer id="footer" class="footer">
    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <b>Seat Plan Pro</b> <span>All Rights Reserved</span></p>
      <div class="credits">
        Designed by <a href="/">Seat Plan Pro</a>
      </div>
      <a href="mailto:seatplanpro@gmail.com">seatplanpro@gmail.com</a> 
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="{{ asset('website/js/script.js')}}"></script>
  <script src="{{asset('admin/assets/libs/toastr/toastr.min.js')}}"></script>
  
  {{-- @if ($errors->any())      
      @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
      @endforeach      
  @endif --}}

  <script>
    @if (session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    $("#loginForm").on("submit", function(event) {
      event.preventDefault();
      var email = $("#email").val();
      var password = $("#password").val();
      console.log(email)
      console.log(password)

      $.ajax({
        url: "{{ route('login') }}", // Replace with your actual login route
        method: "POST",
        data: {
          email: email,
          password: password,
          _token: "{{ csrf_token() }}", // Include CSRF token
        },
        success: function(response) {
          // Handle success (e.g., redirect to dashboard)
          window.location.href = "/dashboard"; // Replace with your redirect URL
        },
        error: function(xhr) {
          // Handle error (show error message)
          var errors = xhr.responseJSON.errors;
          console.log(errors)
          if (errors) {
            $("#loginError").html(errors.email || errors.password || "An error occurred.").show();  
            setTimeout(() => {
              $("#loginError").hide();
            }, 2000);
          }
        }
      });
    });
  </script>

</body>

</html>