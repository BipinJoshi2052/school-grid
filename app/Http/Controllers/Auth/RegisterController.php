<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
// use Illuminate\Http\Client\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendOTP;
use App\Models\InstitutionDetail;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'user_type_id' => 2,
            'password' => Hash::make($data['password']),
        ]);
    }
    public function register(Request $request)
    {
        // Validate incoming data
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:100',
            'institution' => 'required|string|max:250',
            'password' => 'required|string|min:6|confirmed',
            'usingMIS' => 'required',
            'hearAbout' => 'required',
        ]);

        // Create the registration record
        $registration = Registration::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->countryCode.$request->phone,
            'institution_name' => $request->institution,
            'hearAbout' => $request->hearAbout, // assuming hearAbout is passed
            'usingMIS' => $request->usingMIS == 'yes' ? 1 : 0,
        ]);

        $clientIp = $request->getClientIp();
        $userAgent = $request->header('User-Agent');
        $data = [
            'ip' => $clientIp,
            'user_agent' => $userAgent
        ];
        // Create the user record
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'last_access_date' => Carbon::now(),
            'last_access_from' => json_encode($data), // store IP of the user
        ]);

        $count = User::where('user_type_id', 2)->count(); // Get the count of users where user_type_id = 2
        $clientId = 'SPP-' . ($count + 1); // Create the client_id as 'SPP-' followed by the incremented count

        // Create the institution details record
        InstitutionDetail::create([
            'user_id' => $user->id,
            'client_id' => $clientId,
            'institution_name' => $request->institution,
            'registration_id' => $registration->id, // Link registration to institution details
            'expiration_date' => Carbon::now()->addDays(15), // 15 days expiration
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);
        // Send OTP via email
        Mail::to($user->email)->send(new SendOTP($otp));

        // Store OTP and email for OTP verification
        $user->otp = $otp;
        $user->save();

        // Store email in session for later use in the OTP verification page
        Session::put('email', $user->email);

        return redirect()->route('otp.verify');

        // Return response
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Registration successful!',
        // ]);
    }
    public function register2(Request $request)
    {
        dd('asdf');
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $otp = rand(100000, 999999);  // Generate OTP
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Send OTP via email
        Mail::to($user->email)->send(new SendOTP($otp));
        
        // Store email in session for later use in the OTP verification page
        Session::put('email', $user->email);

        // Store OTP temporarily (you can use session or a new column in the User model)
        $user->otp = $otp;
        $user->save();

        return redirect()->route('otp.verify');
    }
    public function showOtpVerificationForm()
    {
        if (!Session::has('email')) {
            // Redirect to home page if not in the session
            return redirect('/');
        }
        return view('auth.verifyOtp');
    }

    public function verifyOtp(Request $request)
    {
        // Check if the email is stored in the session
        if (!Session::has('email')) {
            // Redirect to home page if not in the session
            return redirect('/');
        }
        // Get the stored email from the session
        $email = Session::get('email');

        // Find the user by email
        $user = User::where('email', $email)->first();

        // Check if the user exists and OTP matches
        if ($user && $request->otp == $user->otp) {
            // OTP is correct, mark the email as verified
            $user->email_verified_at = now();
            $user->otp = null;  // Clear OTP after verification
            $user->save();

            // Log the user in
            Auth::login($user);

            // Remove the email from the session after login
            Session::forget('email');

            // Redirect the user to the dashboard
            return redirect()->route('dashboard');
        }

        // OTP is incorrect
        return back()->withErrors(['otp' => 'The OTP is incorrect.']);
    }
}
