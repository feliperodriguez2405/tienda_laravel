<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Técnico: Sistema D'Jenny</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
            color: #333;
            background-color: #f0f4f8;
            animation: fadeIn 1s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .container {
            max-width: 800px;
            margin: 0 auto 0 220px; /* Adjusted for sidebar */
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .container:hover {
            transform: translateY(-5px);
        }
        h1 {
            color: #006400;
            text-align: center;
            font-size: 26px;
            margin-bottom: 20px;
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .nav-sidebar {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 180px;
            background: #e6f3e6;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .nav-sidebar ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-sidebar li {
            margin: 5px 0;
        }
        .nav-sidebar a {
            color: #006400;
            text-decoration: none;
            font-size: 16px;
            display: block;
            padding: 8px;
            border-radius: 3px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .nav-sidebar a:hover {
            background-color: #d0e8d0;
            color: #004d00;
        }
        .accordion {
            margin-bottom: 10px;
        }
        .accordion-header {
            background-color: #e6f3e6;
            color: #006400;
            font-size: 20px;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .accordion-header:hover {
            background-color: #d0e8d0;
            color: #004d00;
        }
        .accordion-header::after {
            content: '\25BC'; /* Down arrow */
            font-size: 14px;
            transition: transform 0.3s ease;
        }
        .accordion-header.active::after {
            transform: rotate(180deg); /* Up arrow when active */
        }
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            padding: 0 15px;
            background: #f9f9f9;
            border-radius: 0 0 5px 5px;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }
        .accordion-content.open {
            max-height: 3000px; /* Sufficient for large content */
            padding: 15px;
        }
        h2 {
            font-size: 20px;
            color: #444;
            margin-top: 15px;
        }
        h3 {
            font-size: 18px;
            color: #444;
            margin-top: 15px;
        }
        p, li {
            font-size: 16px;
            margin: 10px 0;
            transition: color 0.3s ease;
        }
        li:hover {
            color: #006400;
        }
        ul, ol {
            list-style-position: outside;
            margin-left: 20px;
        }
        .highlight {
            background-color: #e6f3e6;
            padding: 15px;
            border-left: 5px solid #006400;
            margin-bottom: 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .highlight:hover {
            background-color: #d0e8d0;
        }
        .print-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #006400;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .print-button:hover {
            background-color: #004d00;
            transform: scale(1.05);
        }
        pre {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 14px;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .container {
                box-shadow: none;
                border: none;
                padding: 10px;
                margin: 0;
            }
            .nav-sidebar {
                display: none;
            }
            .print-button {
                display: none;
            }
            .accordion-header {
                background: none;
                color: #333;
                cursor: default;
            }
            .accordion-header::after {
                display: none;
            }
            .accordion-content {
                max-height: none;
                padding: 15px 0;
                background: none;
            }
            .accordion-content.open {
                padding: 0;
            }
            .highlight {
                background-color: #fff;
                border-left: 4px solid #006400;
            }
            h1, h2, h3, li {
                animation: none;
                transition: none;
                transform: none;
                opacity: 1;
            }
        }
        @media (max-width: 768px) {
            .nav-sidebar {
                position: static;
                width: 100%;
                margin-bottom: 20px;
            }
            .container {
                margin: 0 auto;
            }
        }
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
                    <li>2.1 Tecnologías</li>
                    <li>3 Instalación</li>
                    <li>4 Arquitectura</li>
                    <li>4.1 Archivos Clave</li>
                    <li>4.2 Base de Datos</li>
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
                    <li>Clonar el repositorio: <pre>git clone https://github.com/feliperodriguez2405/tienda && cd tienda && composer install</pre></li>
                    <li>Configurar .env:
                        <pre>
APP_NAME="Tienda D'Jenny"
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
                    <li>Ejecutar migraciones: <pre>php artisan migrate</pre></li>
                    <li>Crear enlace para fotos de productos: <pre>php artisan storage:link</pre></li>
                    <li>Iniciar servidor: <pre>php artisan serve</pre> y acceder a http://localhost:8000.</li>
                </ol>
            </div>
        </div>

        <div class="accordion" id="arquitectura">
            <div class="accordion-header">4 Arquitectura</div>
            <div class="accordion-content">
                <h3>4.1 Archivos Clave</h3>
                <ul>
                    <li><strong>Controladores (app/Http/Controllers):</strong></li>
                    <ul>
                        <li>AdminController.php: Gestión de panel administrativo</li>
                        <li>CajeroController.php: Procesa ventas y pagos</li>
                        <li>ProductoController.php: CRUD de productos</li>
                        <li>ProveedorController.php: Gestión de proveedores y órdenes</li>
                        <li>UserController.php: Manejo de usuarios y roles</li>
                        <li>ReporteController.php: Generación de reportes</li>
                        <li>ReseñaController.php: Reseñas de productos</li>
                        <li>Auth/*: Autenticación (login, registro, recuperación)</li>
                    </ul>
                    <li><strong>Vistas (resources/views):</strong></li>
                    <ul>
                        <li>admin/*: Panel admin (dashboard, pedidos, proveedores, usuarios)</li>
                        <li>cajero/*: Ventas, pagos, cierre de caja</li>
                        <li>categorias/*: Gestión de categorías</li>
                        <li>productos/*: CRUD de productos</li>
                        <li>users/*: Carrito, checkout, órdenes, reseñas</li>
                        <li>auth/*: Login, registro, recuperación de contraseña</li>
                        <li>layouts/*: Plantillas base (app.blade.php, etc.)</li>
                        <li>home.blade.php: Página principal</li>
                    </ul>
                    <li><strong>Estilos (public/css):</strong> form.css (formularios), styles.css (estilos generales)</li>
                    <li><strong>Notificaciones (app/Notifications):</strong> ContratoVencimientoNotification.php, OrdenCompraNotification.php para alertas a proveedores</li>
                    <li><strong>Rutas (routes/web.php):</strong> Define rutas para todos los módulos</li>
                </ul>
                <h3>4.2 Base de Datos</h3>
                <ul>
                    <li>carritos: Carrito de compras</li>
                    <li>categorias: Categorías de productos</li>
                    <li>detalle_ordenes: Detalles de órdenes</li>
                    <li>failed_jobs, jobs: Cola de tareas</li>
                    <li>metodos_pago: Métodos de pago</li>
                    <li>password_reset_tokens, password_resets: Recuperación de contraseñas</li>
                    <li>personal_access_tokens: Tokens</li>
                    <li>reseñas: Reseñas de productos</li>
                    <li>users: Usuarios y roles</li>
                </ul>
            </div>
        </div>

        <div class="accordion" id="modulos">
            <div class="accordion-header">5 Módulos</div>
            <div class="accordion-content">
                <ul>
                    <li><strong>Productos:</strong> CRUD, alertas de stock bajo, filtros (ProductoController.php, productos/*)</li>
                    <li><strong>Ventas:</strong> Facturación, gestión de carrito, pagos (CajeroController.php, cajero/*)</li>
                    <li><strong>Proveedores:</strong> CRUD, historial, notificaciones por correo (ProveedorController.php, proveedores/*)</li>
                    <li><strong>Usuarios:</strong> Registro, autenticación, roles (UserController.php, Auth/*, users/*)</li>
                    <li><strong>Reportes:</strong> Ventas, inventario, gráficos (ReporteController.php, informes.blade.php, invoice.blade.php)</li>
                </ul>
            </div>
        </div>

        <div class="accordion" id="seguridad">
            <div class="accordion-header">6 Seguridad</div>
            <div class="accordion-content">
                <ul>
                    <li><strong>Autenticación:</strong> Login con bcrypt, 2FA para admins (auth/login.blade.php)</li>
                    <li><strong>RBAC:</strong> Control de acceso por roles (users)</li>
                    <li><strong>Tokens:</strong> Sesiones seguras (personal_access_tokens)</li>
                    <li><strong>Protección:</strong> Contra SQL injection, XSS, CSRF</li>
                </ul>
            </div>
        </div>

        <div class="accordion" id="mantenimiento">
            <div class="accordion-header">7 Mantenimiento</div>
            <div class="accordion-content">
                <ul>
                    <li>Optimizar consultas MySQL cada mes</li>
                    <li>Actualizar dependencias: <pre>composer update</pre></li>
                    <li>Respaldos diarios de la base de datos</li>
                    <li>Monitorear rendimiento en picos de uso</li>
                </ul>
            </div>
        </div>

        <div class="accordion" id="resolucion-problemas">
            <div class="accordion-header">8 Resolución de Problemas</div>
            <div class="accordion-content">
                <ul>
                    <li><strong>Página no carga:</strong> Verificar conexión a la base de datos (.env) y servidor</li>
                    <li><strong>Login falla:</strong> Chequear credenciales y personal_access_tokens</li>
                    <li><strong>Stock no actualiza:</strong> Revisar updateInventory() en ProductoController.php</li>
                    <li><strong>Reportes vacíos:</strong> Validar datos y filtros en ReporteController.php</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.accordion-header').forEach(header => {
            header.addEventListener('click', () => {
                const content = header.nextElementSibling;
                const isOpen = content.classList.contains('open');
                
                // Close all other accordions
                document.querySelectorAll('.accordion-content').forEach(c => {
                    c.classList.remove('open');
                    c.previousElementSibling.classList.remove('active');
                });
                
                // Toggle current accordion
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
                
                // Close all other accordions
                document.querySelectorAll('.accordion-content').forEach(c => {
                    c.classList.remove('open');
                    c.previousElementSibling.classList.remove('active');
                });
                
                // Open target accordion
                targetContent.classList.add('open');
                targetHeader.classList.add('active');
                
                // Smooth scroll to target accordion
                targetAccordion.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    </script>
</body>
</html>