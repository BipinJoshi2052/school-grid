
@extends('layouts.app')

@section('title')
Verify OTP
@endsection

@section('content')

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Otp Verify</span>
        <h2>Verify Your OTP</h2>
        <p>An OTP has been sent to {{ session('email') }}. Please enter the OTP below to verify your email.</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
          <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="100">
            <img src="{{ asset('images/about.png')}}" class="img-fluid" alt="">
          </div>

          <div class="col-lg-6">
            <form method="POST" action="{{ route('otp.verify') }}" class="verify-otp" data-aos="fade-up" data-aos-delay="200">
                @csrf
            {{-- <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200"> --}}
              <div class="row gy-4">

                <div class="col-md-12">
                  <label for="name-field" class="pb-2">Enter OTP</label>
                  <input type="text" id="otp" name="otp" class="form-control" required="">
                </div>

                <div class="col-md-12 text-center">
                  {{-- <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div> --}}

                  <button type="submit">Verify OTP</button>
                    @if ($errors->any())
                        
                        @foreach ($errors->all() as $error)
                            <div class="error-message">{{ $error }}</div>
                        @endforeach
                        
                    @endif
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section>
@endsection