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
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
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

        // ── Auditoría de login ───────────────────────────────────
        AuditLog::create([
            'user_id'    => $user->id,
            'action'     => 'login',
            'ip_address' => $request->ip(),
        ]);

        // ── Registro de asistencia para cajeros ─────────────────
        // Si el cajero tiene un turno abierto por el admin y aún
        // no registró su login, se registra ahora y se calcula
        // si llegó puntual o con tardanza.
        if ($user->isCajero()) {
            $openShift = $user->openShift()->first();

            if ($openShift && $openShift->isPendingCajero()) {
                $loginTime = now();
                $openShift->registerCajeroLogin($loginTime);

                // Auditoría de registro de asistencia
                AuditLog::create([
                    'user_id'    => $user->id,
                    'action'     => 'cajero_login_registered',
                    'model'      => 'Shift',
                    'model_id'   => $openShift->id,
                    'new_values' => [
                        'cajero_login_time' => $loginTime->format('H:i:s'),
                        'attendance_status' => $openShift->fresh()->attendance_status,
                    ],
                    'ip_address' => $request->ip(),
                ]);
            }
        }

        return $this->redirectByRole($user);
    }

    // ── Helpers privados ─────────────────────────────────────────

    /**
     * Redirige al usuario según su rol y estado de turno.
     *
     * Admin  → panel de administración
     * Cajero → si tiene turno abierto: módulo de ventas
     *          si NO tiene turno abierto: pantalla de espera
     */
    private function redirectByRole(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Cajero con turno abierto por el admin
        if ($user->hasOpenShift()) {
            return redirect()->route('cajero.shift.current');
        }

        // Cajero sin turno asignado: pantalla de espera
        return redirect()->route('cajero.shift.waiting');
    }

    // ── Reset de contraseña ──────────────────────────────────────

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