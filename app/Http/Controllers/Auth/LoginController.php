<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    //protected $redirectTo = '/home';

    // protected function authenticated()
    // {
    //         if(Auth::user()->role_as == '1'){
    //             return redirect('admin/dashboard')->with('message','Welcome to Dashboard');
    //         }
    //         else{
    //             return redirect('/home')->with('status','Logged In Succesfully');
    //         }
    // }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            if ($user->status == '1') {
                $redirectUrl = $user->role_as == '1' ? route('admin.dashboard') : route('home');
                $token = $user->createToken('api-token')->plainTextToken;
                $request->session()->regenerate();
                $request->session()->put('api-token', $token);
        
                return response()->json(['message' => 'Logged in successfully', 'redirect_url' => $redirectUrl], 200);
            } else {
                Auth::logout();
                return response()->json(['error' => 'Your account is deactivated. Please contact support.'], 403);
            }
        }
        
        // Specific error message for invalid credentials
        return response()->json(['error' => 'Invalid email or password. Please try again.'], 401);
    }
}