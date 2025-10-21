<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        }

        return response()->json(['error' => 'Credenciales inválidas'], 401);
    }

    public function user(Request $request) {
        return response()->json($request->user());
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }

    public function registerAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                'message' => 'Administrador registrado exitosamente',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al registrar el administrador'
            ], 500);
        }
    }

/**
 * Obtener lista de administradores
 */
    public function getAdmins()
    {
        try {
            // Obtener todos los usuarios (en un sistema real podrías filtrar por rol)
            $admins = User::select('id', 'name', 'email', 'created_at')
                        ->orderBy('created_at', 'desc')
                        ->get();

            return response()->json([
                'admins' => $admins
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al obtener administradores: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener la lista de administradores'
            ], 500);
        }
    }

/**
 * Eliminar administrador
 */
    public function deleteAdmin($id)
    {
        try {
            // Evitar que el usuario se elimine a sí mismo
            $currentUser = auth()->user();
            if ($currentUser->id == $id) {
                return response()->json([
                    'error' => 'No puedes eliminar tu propia cuenta'
                ], 400);
            }

            $admin = User::findOrFail($id);
            $admin->delete();

            \Log::info('Administrador eliminado', ['admin_id' => $id, 'deleted_by' => $currentUser->id]);

            return response()->json([
                'message' => 'Administrador eliminado exitosamente'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Administrador no encontrado'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar administrador: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al eliminar el administrador'
            ], 500);
        }
    }
    
}