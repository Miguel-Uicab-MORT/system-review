<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        try {
            $request->authenticate();
            $token = $request->user()->createToken('AuthToken')->plainTextToken;

            return response()->json(['message' => 'Autenticación exitosa', 'token' => $token]);
        } catch (ValidationException $e) {
            // Si la autenticación falla debido a credenciales inválidas, puedes capturar la excepción y retornar una respuesta JSON con un mensaje de error más descriptivo.
            return response()->json(['message' => 'Credenciales inválidas. Por favor, verifica tus datos e intenta de nuevo.'], 401);
        } catch (Exception $e) {
            // Si la autenticación falla por alguna otra razón, puedes capturar la excepción y retornar una respuesta JSON con un mensaje de error más descriptivo.
            return response()->json(['message' => 'Error al autenticar. Por favor, intenta de nuevo.'], 401);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Retornar una respuesta JSON indicando que la sesión ha sido cerrada exitosamente
        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }
}
