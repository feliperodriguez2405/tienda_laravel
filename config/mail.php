<?php return array (
  'default' => 'smtp',
  'mailers' => 
  array (
    'smtp' => 
    array (
      'transport' => 'smtp',
      'url' => NULL,
      'host' => 'smtp.gmail.com',
      'port' => '587',
      'encryption' => 'tls',
      'username' => 'alphasoft.cmjff@gmail.com',
      'password' => 'qlpcjxawidaiezxv',
      'timeout' => NULL,
      'local_domain' => NULL,
    ),
    'ses' => 
    array (
      'transport' => 'ses',
    ),
    'postmark' => 
    array (
      'transport' => 'postmark',
    ),
    'mailgun' => 
    array (
      'transport' => 'mailgun',
    ),
    'sendmail' => 
    array (
      'transport' => 'sendmail',
      'path' => '/usr/sbin/sendmail -bs -i',
    ),
    'log' => 
    array (
      'transport' => 'log',
      'channel' => NULL,
    ),
    'array' => 
    array (
      'transport' => 'array',
    ),
    'failover' => 
    array (
      'transport' => 'failover',
      'mailers' => 
      array (
        0 => 'smtp',
        1 => 'log',
      ),
    ),
    'roundrobin' => 
    array (
      'transport' => 'roundrobin',
      'mailers' => 
      array (
        0 => 'ses',
        1 => 'postmark',
      ),
    ),
  ),
  'from' => 
  array (
    'address' => 'alphasoft.cmjff@gmail.com',
    'name' => 'Tienda Laravel',
  ),
  'markdown' => 
  array (
    'theme' => 'default',
    'paths' => 
    array (
      0 => 'C:\\laragon\\www\\proyecto\\tienda_laravel\\resources\\views/vendor/mail',
    ),
  ),
  'notificaciones_correo' => 'alphasoft.cmjff@gmail.com',
);