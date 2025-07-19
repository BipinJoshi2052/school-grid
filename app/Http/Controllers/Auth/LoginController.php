<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

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
    protected function authenticated(Request $request, $user)
    {
        if ($user->user_type_id != 1) {
            $request->session()->put('user_id', $user->id);
            $request->session()->put('name', $user->name);
            $request->session()->put('parent_id', $user->parent_id);
            $request->session()->put('user_type_id', $user->user_type_id);
            $request->session()->put('avatar', $user->avatar);

            // Set school_id based on user_type_id
            $school_id = ($user->user_type_id == 2) ? $user->id : $user->parent_id;
            $request->session()->put('school_id', $school_id);
        }

        return redirect()->intended($this->redirectTo);
    }
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
