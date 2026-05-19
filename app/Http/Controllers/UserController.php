<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    private function formData(): array
    {
        $roles = Cache::remember('roles.all', 600, fn() =>
            Role::orderBy('name')->get(['id', 'name', 'slug'])
        );

        $branches = Cache::remember('branches.active', 600, fn() =>
            Branch::where('is_active', true)->orderBy('name')->get(['id', 'name'])
        );

        return compact('roles', 'branches');
    }

    public function index(): View
    {
        $users = User::with(['role', 'branch'])->orderBy('name')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create', $this->formData());
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create([
            'branch_id' => $request->branch_id,
            'role_id'   => $request->role_id,
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', array_merge($this->formData(), compact('user')));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->only(['branch_id', 'role_id', 'name', 'username', 'email']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggle(User $user): RedirectResponse
    {
        $user->update(['is_active' => ! $user->is_active]);

        $msg = $user->is_active ? 'activado' : 'desactivado';
        return back()->with('success', "Usuario {$msg} correctamente.");
    }
}