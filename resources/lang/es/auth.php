Contenido auth: <?php

return [
    // Traducciones para mensajes de autenticación general
    'failed' => 'Estas credenciales no coinciden con nuestros registros.',
    'password' => 'La contraseña proporcionada es incorrecta.',
    'throttle' => 'Demasiados intentos de inicio de sesión. Por favor, espera :seconds segundos antes de intentar de nuevo.',

    // Traducciones para el broker de restablecimiento de contraseñas
    'reset' => '¡Tu contraseña ha sido restablecida!',
    'sent' => '¡Hemos enviado un enlace de restablecimiento de contraseña a tu correo!',
    'throttled' => 'Por favor, espera antes de intentar de nuevo.',
    'token' => 'El token de restablecimiento de contraseña es inválido.',
    'user' => 'No encontramos un usuario con ese correo electrónico.',

    // Traducciones para etiquetas de formularios
    'new_password' => 'Nueva Contraseña',
    'confirm_new_password' => 'Confirmar Nueva Contraseña',

    // Traducciones para validación personalizada
    'custom' => [
        'email' => [
            'required' => 'El correo electrónico es obligatorio.',
            'email' => 'El correo electrónico debe ser una dirección válida.',
            'exists' => 'No encontramos un usuario con ese correo electrónico.',
        ],
        'password' => [
            'required' => 'La contraseña es obligatoria.',
            'min' => 'La contraseña debe tener al menos :min caracteres.',
            'confirmed' => 'La confirmación de la contraseña no coincide.',
        ],
        'token' => [
            'required' => 'El token es obligatorio.',
        ],
    ],

    // Nombres de atributos para mensajes de validación
    'attributes' => [
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'password_confirmation' => 'confirmación de contraseña',
        'token' => 'token',
    ],
];