<?php

namespace App\Http\Controllers;
 
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\AuditLog;
 
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
 
    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
 
        return redirect()->route('login');
    }
}