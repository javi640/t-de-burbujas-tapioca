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
    public function showLogin(): View|RedirectResponse
    {
        // Si ya está autenticado, redirigir a su área
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->hasOpenShift()) {
                return redirect()->route('cajero.shift.current');
            }
            return redirect()->route('cajero.shift.open');
        }

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
                ->withErrors(['username' => 'Credenciales incorrectas o cuenta inactiva.']);
        }

        $request->session()->regenerate();

        $user = $this->authUser();

        // Auditoría de login
        AuditLog::create([
            'user_id'    => $user->id,
            'action'     => 'login',
            'ip_address' => $request->ip(),
        ]);

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

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
            ? redirect()->route('login')->with('success', 'Contraseña restablecida correctamente.')
            : back()->withErrors(['email' => __($status)]);
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|regex:/@gmail\.com$/i',
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email'    => 'El formato del correo no es válido.',
            'email.regex'    => 'Solo se permiten correos de Gmail (@gmail.com).',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Te enviamos el enlace de recuperación a tu Gmail.')
            : back()->withErrors(['email' => 'No encontramos una cuenta con ese correo.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Auditoría de logout
        if ($userId) {
            AuditLog::create([
                'user_id'    => $userId,
                'action'     => 'logout',
                'ip_address' => $request->ip(),
            ]);
        }

        return redirect()->route('login')
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => 'Sat, 01 Jan 2000 00:00:00 GMT',
            ]);
    }
}