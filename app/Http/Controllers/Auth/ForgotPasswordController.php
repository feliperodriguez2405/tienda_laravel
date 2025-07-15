<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Envía un enlace de restablecimiento de contraseña al correo del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validar el campo de correo electrónico con mensajes en español
        $request->validate(['email' => 'required|email'], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
        ]);

        // Enviar el enlace de restablecimiento usando el broker de contraseñas
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        // Mapear las claves del broker a mensajes en español
        $messageMap = [
            Password::RESET_LINK_SENT => '¡Hemos enviado un enlace de restablecimiento de contraseña a tu correo!',
            Password::INVALID_USER => 'No encontramos un usuario con ese correo electrónico.',
            Password::RESET_THROTTLED => 'Por favor, espera antes de intentar de nuevo.',
            Password::INVALID_TOKEN => 'El token de restablecimiento de contraseña es inválido.',
            Password::PASSWORD_RESET => '¡Tu contraseña ha sido restablecida!',
        ];

        // Obtener el mensaje correspondiente
        $message = isset($messageMap[$response]) ? $messageMap[$response] : 'Error desconocido al procesar la solicitud.';

        // Registrar la respuesta para depuración
        Log::info('Respuesta de restablecimiento de contraseña: ' . $response);
        Log::info('Mensaje: ' . $message);

        // Devolver respuesta según el resultado
        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', $message)
            : back()->withErrors(['email' => $message]);
    }
}