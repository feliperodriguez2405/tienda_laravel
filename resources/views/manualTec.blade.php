<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Técnico: Sistema D'Jenny</title>
    <link rel="stylesheet" href="{{ asset('css/manuales.css') }}">
    <link rel="icon" href="{{ asset('images/djenny.png') }}" type="image/x-icon">
    <style>
        .highlight { margin-bottom: 20px; }
        .accordion-header { cursor: pointer; font-weight: bold; padding: 10px; background: #f4f4f4; }
        .accordion-content { display: none; padding: 10px; }
        .accordion-content.open { display: block; }
        .accordion-header.active { background: #e0e0e0; }
        .nav-sidebar { position: fixed; top: 20px; left: 20px; width: 200px; }
        .nav-sidebar ul { list-style: none; padding: 0; }
        .nav-sidebar li { margin: 10px 0; }
        .print-button { margin-bottom: 20px; }
        .container { margin-left: 240px; }
    </style>
</head>
<body>
    <div class="nav-sidebar">
        <ul>
            <li><a href="#indice" data-accordion="indice">Índice</a></li>
            <li><a href="#introduccion" data-accordion="introduccion">1 Introducción</a></li>
            <li><a href="#descripcion-general" data-accordion="descripcion-general">2 Descripción General</a></li>
            <li><a href="#instalacion" data-accordion="instalacion">3 Instalación</a></li>
            <li><a href="#arquitectura" data-accordion="arquitectura">4 Arquitectura</a></li>
            <li><a href="#modulos" data-accordion="modulos">5 Módulos</a></li>
            <li><a href="#seguridad" data-accordion="seguridad">6 Seguridad</a></li>
            <li><a href="#mantenimiento" data-accordion="mantenimiento">7 Mantenimiento</a></li>
            <li><a href="#resolucion-problemas" data-accordion="resolucion-problemas">8 Resolución de Problemas</a></li>
        </ul>
    </div>
    <div class="container">
        <button class="print-button" onclick="window.print()">Imprimir PDF</button>
        <h1>Manual Técnico: Sistema D'Jenny</h1>
        <div class="highlight">
            <p><strong>Versión:</strong> 1.0</p>
            <p><strong>Equipo:</strong> Jaider Molina, Mauro Suarez, Felipe Bermúdez, Frank Chambo</p>
            <p><strong>Fecha:</strong> Junio 2025</p>
        </div>

        <div class="accordion" id="indice">
            <div class="accordion-header">Índice</div>
            <div class="accordion-content">
                <ul>
                    <li>1 Introducción</li>
                    <li>2 Descripción General</li>
                    <li>3 Instalación</li>
                    <li>4 Arquitectura</li>
                    <li>5 Módulos</li>
                    <li>6 Seguridad</li>
                    <li>7 Mantenimiento</li>
                    <li>8 Resolución de Problemas</li>
                </ul>
            </div>
        </div>

        <div class="accordion" id="introduccion">
            <div class="accordion-header">1 Introducción</div>
            <div class="accordion-content">
                <p>Este manual ayuda a desarrolladores a entender, mantener y actualizar el sistema D'Jenny, una solución para gestionar ventas e inventario en tiendas de abarrotes. Cubre instalación, estructura, módulos y solución de problemas. El código fuente está disponible en GitHub.</p>
            </div>
        </div>

        <div class="accordion" id="descripcion-general">
            <div class="accordion-header">2 Descripción General</div>
            <div class="accordion-content">
                <p>D'Jenny automatiza la gestión de productos, ventas, proveedores, usuarios y reportes para tiendas de abarrotes, con un enfoque en simplicidad y eficiencia.</p>
                <h3>2.1 Tecnologías</h3>
                <ul>
                    <li><strong>Backend:</strong> PHP 8.1.25, Laravel 10.48.28</li>
                    <li><strong>Frontend:</strong> HTML5, CSS (public/css/form.css, styles.css), Bootstrap 5, JavaScript (jQuery, DataTables)</li>
                    <li><strong>Base de Datos:</strong> MySQL gestionada con HeidiSQL 12.1.0.6537, 14 tablas</li>
                    <li><strong>Notificaciones:</strong> Correos para proveedores (contratos y órdenes)</li>
                </ul>
            </div>
        </div>

        <div class="accordion" id="instalacion">
            <div class="accordion-header">3 Instalación</div>
            <div class="accordion-content">
                <ol>
                    <li>Instalar PHP 8.1.25, MySQL 5.7+, Apache/Nginx.</li>
                    <li>Clonar el repositorio: <code>git clone https://github.com/feliperodriguez2405/tienda; composer install</code>.</li>
                    <li>Configurar <code>.env</code>:
                        <pre>
APP_NAME=Tienda D'Jenny
APP_ENV=local
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3320
DB_DATABASE=djenny
DB_USERNAME=root
DB_PASSWORD=
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailpit.com
MAIL_PORT=2525
MAIL_USERNAME=alphasoft.cmjff@gmail.com
MAIL_PASSWORD=qlpcjxawidaiezxv
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="alphasoft.cmjff@gmail.com"
MAIL_FROM_NAME="Tienda D'Jenny"
                        </pre>
                    </li>
                    <li>Ejecutar migraciones: <code>php artisan migrate</code>.</li>
                    <li>Crear enlace para fotos de productos: <code>php artisan storage:link</code>.</li>
                    <li>Iniciar servidor: <code>php artisan serve</code> y acceder a <code>http://localhost:8000</code>.</li>
                </ol>
            </div>
        </div>

        <div class="accordion" id="arquitectura">
            <div class="accordion-header">4 Arquitectura</div>
            <div class="accordion-content">
                <h3>4.1 Archivos Clave</h3>
                <ul>
                    <li><strong>Controladores (app/Http/Controllers):</strong>
                        <ul>
                            <li><code>AdminController.php</code>: Gestión de panel administrativo</li>
                            <li><code>CajeroController.php</code>: Procesa ventas y pagos</li>
                            <li><code>ProductoController.php</code>: CRUD de productos</li>
                            <li><code>ProveedorController.php</code>: Gestión de proveedores y órdenes</li>
                            <li><code>UserController.php</code>: Manejo de usuarios y roles</li>
                            <li><code>ReporteController.php</code>: Generación de reportes</li>
                            <li><code>ReseñaController.php</code>: Reseñas de productos</li>
                            <li><code>Auth/*</code>: Autenticación (login, registro, recuperación)</li>
                        </ul>
                    </li>
                    <li><strong>Vistas (resources/views):</strong>
                        <ul>
                            <li><code>admin/*</code>: Panel admin (dashboard, pedidos, proveedores, usuarios)</li>
                            <li><code>cajero/*</code>: Ventas, pagos, cierre de caja</li>
                            <li><code>categorias/*</code>: Gestión de categorías</li>
                            <li><code>productos/*</code>: CRUD de productos</li>
                            <li [...]

                        </ul>
                    </li>
                    <li><strong>Estilos (public/css):</strong> <code>form.css</code> (formularios), <code>styles.css</code> (estilos generales)</li>
                    <li><strong>Notificaciones (app/Notifications):</strong> <code>ContratoVencimientoNotification.php</code>, <code>OrdenCompraNotification.php</code> para alertas a proveedores</li>
                    <li><strong>Rutas (routes/web.php):</strong> Define rutas para todos los módulos</li>
                </ul>
                <h3>4.2 Base de Datos</h3>
                <ul>
                    <li><code>carritos</code>: Carrito de compras</li>
                    <li><code>categorias</code>: Categorías de productos</li>
                    <li><code>detalle_ordenes</code>: Detalles de órdenes</li>
                    <li><code>failed_jobs</code>, <code>jobs</code>: Cola de tareas</li>
                    <li><code>metodos_pago</code>: Métodos de pago</li>
                    <li><code>password_reset_tokens</code>, <code>password_resets</code>: Recuperación de contraseñas</li>
                    <li><code>personal_access_tokens</code>: Tokens</li>
                    <li><code>reseñas</code>: Reseñas de productos</li>
                    <li><code>users</code>: Usuarios y roles</li>
                </ul>
            </div>
        </div>

        <div class="accordion" id="modulos">
            <div class="accordion-header">5 Módulos</div>
            <div class="accordion-content">
                <ul>
                    <li><strong>Productos:</strong> CRUD, alertas de stock bajo, filtros (<code>ProductoController.php</code>, <code>productos/*</code>)</li>
                    <li><strong>Ventas:</strong> Facturación, gestión de carrito, pagos (<code>CajeroController.php</code>, <code>cajero Demokratik</code>)</li>
                    <li><strong>Proveedores:</strong> CRUD, historial, notificaciones por correo (<code>ProveedorController.php</code>, <code>proveedores/*</code>)</li>
                    <li><strong>Usuarios:</strong> Registro, autenticación, roles (<code>UserController.php</code>, <code>Auth/*</code>, <code>users/*</code>)</li>
                    <li><strong>Reportes:</strong> Ventas, inventario, gráficos (<code>ReporteController.php</code>, <code>informes.blade.php</code>, <code>invoice.blade.php</code>)</li>
                </ul>
            </div>
        </div>

        <div class="accordion" id="seguridad">
            <div class="accordion-header">6 Seguridad</div>
            <div class="accordion-content">
                <ul>
                    <li><strong>Autenticación:</strong> Login con bcrypt, 2FA para admins (<code>auth/login.blade.php</code>)</li>
                    <li><strong>RBAC:</strong> Control de acceso por roles (<code>users</code>)</li>
                    <li><strong>Tokens:</strong> Sesiones seguras (<code>personal_access_tokens</code>)</li>
                    <li><strong>Protección:</strong> Contra SQL injection, XSS, CSRF</li>
                </ul>
            </div>
        </div>

        <div class="accordion" id="mantenimiento">
            <div class="accordion-header">7 Mantenimiento</div>
            <div class="accordion-content">
                <ul>
                    <li>Optimizar consultas MySQL cada mes</li>
                    <li>Actualizar dependencias: <code>composer update</code></li>
                    <li>Respaldos diarios de la BD</li>
                    <li>Monitorear rendimiento en picos de uso</li>
                </ul>
            </div>
        </div>

        <div class="accordion" id="resolucion-problemas">
            <div class="accordion-header">8 Resolución de Problemas</div>
            <div class="accordion-content">
                <ul>
                    <li><strong>Página no carga:</strong> Verificar conexión BD (<code>.env</code>) y servidor</li>
                    <li><strong>Login falla:</strong> Chequear credenciales y <code>personal_access_tokens</code></li>
                    <li><strong>Stock no actualiza:</strong> Revisar <code>updateInventory()</code> en <code>ProductoController.php</code></li>
                    <li><strong>Reportes vacíos:</strong> Validar datos y filtros en <code>ReporteController.php</code></li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.accordion-header').forEach(header => {
            header.addEventListener('click', () => {
                const content = header.nextElementSibling;
                const isOpen = content.classList.contains('open');
                
                document.querySelectorAll('.accordion-content').forEach(c => {
                    c.classList.remove('open');
                    c.previousElementSibling.classList.remove('active');
                });
                
                if (!isOpen) {
                    content.classList.add('open');
                    header.classList.add('active');
                }
            });
        });

        document.querySelectorAll('.nav-sidebar a').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const targetAccordion = document.getElementById(targetId);
                const targetHeader = targetAccordion.querySelector('.accordion-header');
                const targetContent = targetHeader.nextElementSibling;
                
                document.querySelectorAll('.accordion-content').forEach(c => {
                    c.classList.remove('open');
                    c.previousElementSibling.classList.remove('active');
                });
                
                targetContent.classList.add('open');
                targetHeader.classList.add('active');
                
                targetAccordion.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    </script>
</body>
</html>