<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class EstudianteController extends Controller
{
    public function index()
    {
        $estudiantes = Estudiante::with('user')
            ->orderBy('nombres')
            ->get();

        return view('admin.estudiantes.index', compact('estudiantes'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'ru' => ['required', 'string', 'max:50', 'unique:estudiantes,ru'],
            'ci' => ['required', 'string', 'max:30', 'unique:estudiantes,ci'],
            'nombres' => ['required', 'string', 'max:100'],
            'apellido_paterno' => ['required', 'string', 'max:100'],
            'apellido_materno' => ['nullable', 'string', 'max:100'],
            'carrera' => ['required', 'string', 'max:150'],
            'semestre' => ['nullable', 'integer', 'min:1', 'max:12'],
            'telefono' => ['nullable', 'string', 'max:20'],
        ]);

       DB::transaction(function () use ($datos) {
        $user = User::create([
        'name' => $datos['name'],
        'email' => $datos['email'],
        'password' => Hash::make($datos['password']),
        'role' => 'estudiante',
       ]);

       $user->estudiante()->create([
        'ru' => $datos['ru'],
        'ci' => $datos['ci'],
        'nombres' => $datos['nombres'],
        'apellido_paterno' => $datos['apellido_paterno'],
        'apellido_materno' => $datos['apellido_materno'] ?? null,
        'carrera' => $datos['carrera'],
        'semestre' => $datos['semestre'] ?? null,
        'telefono' => $datos['telefono'] ?? null,
          ]);
        });

        return redirect()
            ->route('admin.estudiantes.index')
            ->with('success', 'Estudiante registrado correctamente.');
    }

    public function show(Estudiante $estudiante)
    {
        $estudiante->load('user', 'solicitudes.estado');

        return view('admin.estudiantes.show', compact('estudiante'));
    }

   
     public function update(Request $request, Estudiante $estudiante){
      $datos = $request->validate([
        'email' => [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($estudiante->user_id),
        ],
        'ru' => [
            'required',
            'string',
            'max:50',
            Rule::unique('estudiantes', 'ru')->ignore($estudiante->id),
        ],
        'ci' => [
            'required',
            'string',
            'max:30',
            Rule::unique('estudiantes', 'ci')->ignore($estudiante->id),
        ],
        'nombres' => ['required', 'string', 'max:100'],
        'apellido_paterno' => ['required', 'string', 'max:100'],
        'apellido_materno' => ['nullable', 'string', 'max:100'],
        'carrera' => ['required', 'string', 'max:150'],
        'semestre' => ['nullable', 'integer', 'min:1', 'max:12'],
        'telefono' => ['nullable', 'string', 'max:20'],
    ]);

    DB::transaction(function () use ($datos, $estudiante) {

        $estudiante->user->update([
            'email' => $datos['email'],
        ]);

        $estudiante->update([
            'ru' => $datos['ru'],
            'ci' => $datos['ci'],
            'nombres' => $datos['nombres'],
            'apellido_paterno' => $datos['apellido_paterno'],
            'apellido_materno' => $datos['apellido_materno'] ?? null,
            'carrera' => $datos['carrera'],
            'semestre' => $datos['semestre'] ?? null,
            'telefono' => $datos['telefono'] ?? null,
        ]);
    });

    return back()->with('success', 'Estudiante actualizado correctamente.');
  }

}
