<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SeatPlan-Pro - Home</title>
    <meta name="description" content="Generate optimal exam seat plans in seconds with our intelligent system. Drag-and-drop customization, automated notifications, and complete student management.">
    <meta name="author" content="SeatPlan Pro">
    
    <meta property="og:title" content="SeatPlan Pro - Automated Exam Seat Planning Made Simple">
    <meta property="og:description" content="Generate optimal exam seat plans in seconds with our intelligent system. Drag-and-drop customization, automated notifications, and complete student management.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('images/faviconseatplan.png') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@seatplanpro">
    <meta name="twitter:image" content="{{ asset('images/faviconseatplan.png') }}">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/faviconseatplan.png') }}">
    <link href="{{ asset('images/faviconseatplan.png')}}" rel="apple-touch-icon">
    
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
                    <img src="{{ asset('images/seat-plan-pro.svg') }}" alt="" class="img-fluid" style="width: 50%;">
                    {{-- <div class="logo-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span class="logo-text">SeatPlan Pro</span> --}}
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
                    <a href="{{ route('register') }}" class="btn btn-hero">Register</a>
                  @endguest

                  @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-hero">Dashboard</a>
                  @endauth
                </div>
                
                <button class="mobile-menu-btn" id="mobileMenuBtn">
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
                    {{-- <i class="fas fa-bars"></i> --}}
                </button>
            </div>
        </div>
    </header>
    
    @yield('content')

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
<style>
  .toast-top-center{
    top: -20px;
    right: 300px;
  }
</style>
  <script>
    
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "100000",
        "hideDuration": "100000",
        "timeOut": "100000",
        "extendedTimeOut": "100000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    @if (session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    $("#loginForm").on("submit", function(event) {
      event.preventDefault();
      var email = $("#email").val();
      var password = $("#password").val();

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
          // window.location.href = "/dashboard";
          window.location.href = response.redirect;
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

    // Allow form submission when Enter is pressed in either input field
    $("#email, #password").on("keypress", function(event) {
      if (event.which === 13) { // Enter key code is 13
        $("#loginForm").submit(); // Trigger the form submit event
      }
    });
  </script>

</body>

</html>