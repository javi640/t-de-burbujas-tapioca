<?php

namespace App\Http\Controllers;
 
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\AuditLog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
 
class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }
 
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = [
            'username'  => $request->username,
            'password'  => $request->password,
            'is_active' => true,
        ];
 
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput(['username' => $request->username])
                ->withErrors(['username' => 'Credenciales incorrectas.']);
        }
 
        $request->session()->regenerate();
 
        $user = $this->authUser();

        // Auditoría de login
        AuditLog::create([
            'user_id'    => $user->id,
            'action'     => 'login',
            'ip_address' => $request->ip(),
        ]);
 
        // Redirigir según rol
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
 
        // Cajero: verificar si tiene turno abierto
        if ($user->hasOpenShift()) {
            return redirect()->route('cajero.shift.current');
        }
 
        return redirect()->route('cajero.shift.open');
    }
    public function showResetForm(string $token): View
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])
                    ->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
            ? redirect()->route('login')->with('success', 'Contraseña restablecida.')
            : back()->withErrors(['email' => __($status)]);
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Te enviamos el enlace de recuperación.')
            : back()->withErrors(['email' => 'No encontramos una cuenta con ese email.']);
    }
 
    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
 
        return redirect()->route('login');
    }
}

