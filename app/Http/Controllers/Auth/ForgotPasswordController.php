<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    // Incluye el trait SendsPasswordResetEmails para la funcionalidad de envío de enlaces
    use SendsPasswordResetEmails;
    

    /**
     * Envía un enlace de restablecimiento de contraseña al correo del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validar el campo de correo electrónico con mensajes en español desde auth.php
        $request->validate(['email' => 'required|email'], [
            'email.required' => trans('auth.custom.email.required'),
            'email.email' => trans('auth.custom.email.email'),
        ]);

        // Enviar el enlace de restablecimiento usando el broker de contraseñas
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        // Mapear las claves del broker (passwords.*) a traducciones en auth.php
        $translationMap = [
            'passwords.sent' => 'auth.sent',
            'passwords.user' => 'auth.user',
            'passwords.throttled' => 'auth.throttled',
            'passwords.token' => 'auth.token',
            'passwords.reset' => 'auth.reset',
        ];

        // Obtener la traducción correspondiente
        $translatedMessage = isset($translationMap[$response]) ? trans($translationMap[$response]) : $response;

        // Registrar la respuesta para depuración
        \Log::info('Respuesta de restablecimiento de contraseña: ' . $response);
        \Log::info('Mensaje traducido: ' . $translatedMessage);

        // Devolver respuesta según el resultado
        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', $translatedMessage)
            : back()->withErrors(['email' => $translatedMessage]);
    }
}