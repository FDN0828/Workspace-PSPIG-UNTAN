<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Route::post('/login-api', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Email atau password salah',
        ], 401);
    }

    $user = User::where('email', $request->email)->first();

    // Hapus token lama jika perlu
    $user->tokens()->delete();

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'message' => 'Login berhasil',
        'token' => $token,
        'user' => $user,
    ]);
});

Route::middleware('auth:sanctum')->get('/user-data', function (Request $request) {
    return response()->json([
        'user' => $request->user()
    ]);
});


