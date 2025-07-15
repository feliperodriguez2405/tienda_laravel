<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validar el restablecimiento de contraseña con mensajes en español.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateReset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required' => 'El token es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);
    }

    /**
     * Manejar la respuesta después de restablecer la contraseña.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        $messageMap = [
            Password::PASSWORD_RESET => 'La contraseña se ha cambiado correctamente',
        ];

        $message = isset($messageMap[$response]) ? $messageMap[$response] : 'Error al restablecer la contraseña.';

        return $request->wantsJson()
            ? new JsonResponse(['message' => $message], 200)
            : redirect()->route('login')->with('status', $message);
    }

    /**
     * Manejar la respuesta en caso de fallo al restablecer la contraseña.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        $messageMap = [
            Password::INVALID_USER => 'No encontramos un usuario con ese correo electrónico.',
            Password::INVALID_TOKEN => 'El token de restablecimiento de contraseña es inválido.',
        ];

        $message = isset($messageMap[$response]) ? $messageMap[$response] : 'Error al procesar la solicitud.';

        return $request->wantsJson()
            ? new JsonResponse(['message' => $message], 422)
            : back()->withErrors(['email' => $message]);
    }
}