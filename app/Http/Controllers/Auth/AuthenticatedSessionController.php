<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $user = User::where(['phone_no' => $request->phone_no, 'otp' => $request->otp])->where('is_active', 1)->with('roles')->first();
        if (! $user ) {
            $url = "login";
            return redirect()->intended($url);
        }
        Auth::login($user, (bool) $request->input('remember'));
        $url = "";
        //$user = $request->user();
        if ($user->hasRole('Admin') || $user->hasRole('Manager') || $user->hasRole('Service Team Leader') || $user->hasRole('Sales Team Leader')) {
            return redirect()->intended(route('dashboard', absolute: false));
        } else {
            $url = "login";
            return redirect()->intended($url);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
