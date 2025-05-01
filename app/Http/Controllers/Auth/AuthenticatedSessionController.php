<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    // Di AuthenticatedSessionController
    // public function apiLogin(Request $request)
    // {
    //     try {
    //         $credentials = $request->only('email', 'password');
    
    //         if (!Auth::attempt($credentials)) {
    //             return response()->json(['message' => 'Login gagal'], 401);
    //         }

    
    //         return response()->json([
    //             'message' => 'Login berhasil',
    //             'user' => Auth::user()
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'Terjadi error',
    //             'debug' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    
}
