@extends('layouts.app')
@section('title', 'Esperando Turno')

@section('sidebar-nav')
    <span class="nav-section-label">Mi Turno</span>
    <a href="{{ route('cajero.shift.waiting') }}" class="nav-item active">
        <span class="nav-icon"><i class="bi bi-hourglass-split"></i></span> Esperando Turno
    </a>
@endsection

@section('page-title', 'Esperando Turno')
@section('page-subtitle', 'Tu turno aún no ha sido habilitado por el administrador')

@section('content')
<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 60vh; text-align: center;">

    <div style="font-size: 4rem; margin-bottom: 1.5rem; animation: pulse 2s infinite; color: var(--accent);">
        <i class="bi bi-hourglass-split"></i>
    </div>

    <div class="card" style="max-width: 480px; width: 100%;">
        <div class="card-title" style="font-size: 1.25rem; margin-bottom: .5rem;">
            Turno no habilitado aún
        </div>
        <p class="text-muted" style="margin-bottom: 1.5rem; line-height: 1.6;">
            El administrador todavía no ha abierto tu turno para hoy.
            Cuando lo haga, podrás comenzar a registrar ventas automáticamente.
        </p>

        <div style="background: rgba(184,204,202,.35); border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
            <div class="flex items-center gap-3">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div class="text-left">
                    <div class="font-bold">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-muted">Cajero — esperando asignación de turno</div>
                </div>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border); padding-top: 1rem;">
            <div class="text-xs text-muted" style="margin-bottom: .25rem;">Hora actual</div>
            <div class="mono" style="font-size: 2rem; font-weight: 700; color: var(--accent);" id="clock">
                --:--:--
            </div>
        </div>
    </div>

    <button
        onclick="window.location.reload()"
        class="btn btn-ghost"
        style="margin-top: 1.5rem;"
    >
        <i class="bi bi-arrow-clockwise"></i> Verificar nuevamente
    </button>

    <p class="text-xs text-muted" style="margin-top: .75rem;">
        La página se actualizará automáticamente cada 30 segundos
    </p>
</div>
@endsection

@section('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('clock').textContent = `${h}:${m}:${s}`;
    }
    updateClock();
    setInterval(updateClock, 1000);

    setTimeout(() => window.location.reload(), 30000);
</script>

<style>
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50%       { transform: scale(1.1); opacity: .7; }
    }
</style>
@endsection