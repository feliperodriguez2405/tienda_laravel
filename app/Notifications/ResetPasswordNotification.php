<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    // Token para el enlace de restablecimiento
    public $token;

    /**
     * Crear una nueva instancia de la notificación.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Obtener los canales de entrega de la notificación.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Obtener la representación del correo electrónico de la notificación.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Generar la URL del enlace de restablecimiento
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject(Lang::get('Restablecimiento de Contraseña'))
            ->greeting(Lang::get('¡Hola!'))
            ->line(new \Illuminate\Support\HtmlString(
                '<div style="text-align: center; margin-bottom: 20px;">' .
                '<img src="' . asset('images/djenny.png') . '" alt="Logo de D\'Jenny" style="width: 200px; height: auto; max-width: 100%;">' .
                '</div>'
            ))
            ->line(Lang::get('Recibiste este correo porque solicitaste un restablecimiento de contraseña para tu cuenta.'))
            ->action(Lang::get('Restablecer Contraseña'), $url)
            ->line(Lang::get('Este enlace de restablecimiento de contraseña expirará en :count minutos.', ['count' => config('auth.passwords.users.expire')]))
            ->line(Lang::get('Si no solicitaste un restablecimiento de contraseña, no es necesario que hagas nada.'))
            ->salutation(Lang::get('Saludos,') . "\n" . Lang::get('Equipo D\'Jenny'));
    }
}