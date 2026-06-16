<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(): View
    {
        $usuarios = User::orderBy('name')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create(): View
    {
        return view('usuarios.create', [
            'roles' => $this->roles(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in($this->roles())],
        ]);

        $usuario = User::create($data);

        Actividad::registrar(
            'usuarios',
            'crear',
            "Creo el usuario {$usuario->email} con rol {$usuario->role}"
        );

        return redirect()
            ->route('usuarios.index')
            ->with('status', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario): View
    {
        return view('usuarios.edit', [
            'usuario' => $usuario,
            'roles' => $this->roles(),
        ]);
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in($this->roles())],
        ]);

        if (! $data['password']) {
            unset($data['password']);
        }

        $usuario->update($data);

        Actividad::registrar(
            'usuarios',
            'editar',
            "Edito el usuario {$usuario->email}"
        );

        return redirect()
            ->route('usuarios.index')
            ->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario): RedirectResponse
    {
        if ($usuario->id === Auth::id()) {
            return redirect()
                ->route('usuarios.index')
                ->with('status', 'No podes eliminar tu propio usuario.');
        }

        $email = $usuario->email;
        $usuario->delete();

        Actividad::registrar(
            'usuarios',
            'eliminar',
            "Elimino el usuario {$email}"
        );

        return redirect()
            ->route('usuarios.index')
            ->with('status', 'Usuario eliminado correctamente.');
    }

    /**
     * @return array<int, string>
     */
    private function roles(): array
    {
        return ['admin', 'carga', 'consulta'];
    }
}
