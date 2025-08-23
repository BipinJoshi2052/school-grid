<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\InstitutionDetail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }
    
    /**
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return mixed
     */
    public function login(Request $request)
    {
        // Validate the request
        $this->validateLogin($request);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists
        if (!$user) {
            return response()->json([
                'errors' => ['email' => 'No account found with this email.']
            ], 422);
        }

        // Check if user exists and has valid user_type_id
        if (!$user || !in_array($user->user_type_id, [1, 2, 3, 4])) {
            return response()->json([
                'errors' => ['email' => 'Not a valid user type.']
            ], 422);
        }
        if($user->user_type_id != 1){
            // Determine the institution ID based on the user type
            $institution_id = ($user->user_type_id == 2) ? $user->id : $user->parent_id;
            // dd($institution_id);
            // Check the expiration date from the institution_details table
            $institution = InstitutionDetail::where('user_id', $institution_id)->first();

            // If the institution does not exist or expiration date is not found
            if (!$institution) {
                return response()->json([
                    'errors' => ['institution' => 'Institution details not found.']
                ], 422);
            }
            // dd($institution);
            // Check if the expiration date is today or in the past
            if($institution->expiration_date){
                $expirationDate = Carbon::parse($institution->expiration_date);
                if ($expirationDate->isToday() || $expirationDate->isPast()) {
                    return response()->json([
                        'errors' => ['subscription' => 'Your subscription has expired. Please contact us at seatplanpro@gmail.com or use the contact us button in the page.']
                    ], 422);
                }
            }
        }
        // Verify password and attempt login
        if (Hash::check($request->password, $user->password)) {
            Auth::login($user, $request->has('remember'));

            // Update last access date and from
            $user->update([
                'last_access_date' => Carbon::now(),  // Set current date and time for last access
                'last_access_from' => json_encode([
                    'ip' => $request->ip(),  // Get the user's IP address
                    'user_agent' => $request->header('User-Agent')  // Get the user's User-Agent
                ])
            ]);
            
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_type_id', $user->user_type_id);
            
            if($user->user_type_id == 2){
                $request->session()->put('name', $user->name);
                $request->session()->put('parent_id', $user->parent_id);
                $request->session()->put('avatar', $user->avatar);

                // Set school_id based on user_type_id
                $school_id = ($user->user_type_id == 2) ? $user->id : $user->parent_id;
                $request->session()->put('school_id', $school_id);
            }

            // Determine redirect URL based on user_type_id
            $redirectUrl = $user->user_type_id === 1
                ? route('admin.dashboard')
                : route('dashboard');
            
            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl
            ], 200);
        }

        // If password is incorrect
        return response()->json([
            'errors' => ['email' => 'Invalid email or password.']
        ], 422);
    }

    public function login2(Request $request)
    {
        // Validate the request
        $this->validateLogin($request);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists
        if (!$user) {
            return response()->json([
                'errors' => ['email' => 'No account found with this email.']
            ], 422);
        }

        // Check if user exists and has valid user_type_id
        if (!$user || !in_array($user->user_type_id, [1, 2])) {
            return response()->json([
                'errors' => ['email' => 'Not a valid user type.']
            ], 422);
            // return redirect()->back()
            //     ->withErrors(['email' => 'Not a valid user.'])
            //     ->withInput($request->only('email', 'remember'));
        }

        // Verify password and attempt login
        if (Hash::check($request->password, $user->password)) {
            Auth::login($user, $request->has('remember'));

            // Update last access date and from
            $user->update([
                'last_access_date' => Carbon::now(),  // Set current date and time for last access
                'last_access_from' => json_encode([
                    'ip' => $request->ip(),  // Get the user's IP address
                    'user_agent' => $request->header('User-Agent')  // Get the user's User-Agent
                ])
            ]);
            
            if($user->user_type_id == 2){
                $request->session()->put('user_id', $user->id);
                $request->session()->put('name', $user->name);
                $request->session()->put('parent_id', $user->parent_id);
                $request->session()->put('user_type_id', $user->user_type_id);
                $request->session()->put('avatar', $user->avatar);

                // Set school_id based on user_type_id
                $school_id = ($user->user_type_id == 2) ? $user->id : $user->parent_id;
                $request->session()->put('school_id', $school_id);
            }

            // Determine redirect URL based on user_type_id
            $redirectUrl = $user->user_type_id === 1
                ? route('admin.dashboard')
                : route('dashboard');
            // dd($redirectUrl);
            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl
            ], 200);
        }

        // If password is incorrect
        return response()->json([
            'errors' => ['email' => 'Invalid email or password.']
        ], 422);
    }

    /**
     * Validate the login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    }

    // protected function authenticated(Request $request, $user)
    // {
    //     if ($user->user_type_id != 1) {
    //         $request->session()->put('user_id', $user->id);
    //         $request->session()->put('name', $user->name);
    //         $request->session()->put('parent_id', $user->parent_id);
    //         $request->session()->put('user_type_id', $user->user_type_id);
    //         $request->session()->put('avatar', $user->avatar);

    //         // Set school_id based on user_type_id
    //         $school_id = ($user->user_type_id == 2) ? $user->id : $user->parent_id;
    //         $request->session()->put('school_id', $school_id);
    //     }
    //     // dd($user);
    //     // if ($user->user_type_id == 1) {}
    //     if ($user->user_type_id != 1 && $user->user_type_id != 2) {
    //         // Log out the user
    //         Auth::logout();
    //         // Redirect back with error message
    //         return redirect()->route('home')->withErrors([
    //             'email' => 'Not a valid user.',
    //         ]);
    //     }

    //     return redirect()->intended($this->redirectTo);
    // }
        /**
     * Logout the user and redirect to home page.
     */
    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->flush();  // Clear all session data

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        return redirect('/');
        // Redirect to home page (or another page)
        // Check if the environment is development or production
        if (App::environment('local')) {
            // If development (local environment), redirect to '/'
            return redirect('/');
        } else {
            // If not development (production environment), redirect to '/mero-calendar/public'
            return redirect('/');
        }
    }
}
