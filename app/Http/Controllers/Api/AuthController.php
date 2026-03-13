<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = Str::lower(trim($validated['email']));
        $plainPassword = $validated['password'];

        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if (!$user) {
            // Auto-provision account on first login attempt for this school project flow.
            $user = User::create([
                'name' => Str::title(Str::before($email, '@')) ?: 'Student User',
                'email' => $email,
                'password' => $plainPassword,
            ]);
        }

        $passwordMatches = Hash::check($plainPassword, (string) $user->password);

        if (!$passwordMatches && hash_equals((string) $user->password, $plainPassword)) {
            // Migrate legacy plain-text passwords to hashed values after first successful login.
            $user->password = $plainPassword;
            $user->save();
            $passwordMatches = true;
        }

        if (!$passwordMatches) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('react-app')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}