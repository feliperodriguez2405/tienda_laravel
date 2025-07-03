<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual de Usuario para Administradores: Sistema D'Jenny</title>
    <link rel="stylesheet" href="{{ asset('css/manuales.css') }}">
</head>
<body>
    <div class="nav-sidebar">
        <ul>
            <li><a href="#indice" data-accordion="indice">Índice</a></li>
            <li><a href="#introduccion" data-accordion="introduccion">1 Introducción</a></li>
            <li><a href="#preguntas-generales" data-accordion="preguntas-generales">2 Preguntas Generales</a></li>
            <li><a href="#autenticacion" data-accordion="autenticacion">3 Autenticación y Registro</a></li>
            <li><a href="#gestion-productos" data-accordion="gestion-productos">4 Gestión de Productos</a></li>
            <li><a href="#gestion-categorias" data-accordion="gestion-categorias">5 Gestión de Categorías</a></li>
            <li><a href="#gestion-proveedores" data-accordion="gestion-proveedores">6 Gestión de Proveedores</a></li>
            <li><a href="#gestion-usuarios" data-accordion="gestion-usuarios">7 Gestión de Usuarios</a></li>
            <li><a href="#reportes" data-accordion="reportes">8 Informes y Reportes</a></li>
            <li><a href="#resolucion-problemas" data-accordion="resolucion-problemas">9 Resolución de Problemas</a></li>
        </ul>
    </div>
    <div class="container">
        <button class="print-button" onclick="window.print()">Imprimir PDF</button>
        <h1>Manual de Usuario para Administradores: Sistema D'Jenny</h1>

        <div class="accordion" id="introduccion">
            <div class="accordion-header">1 Introducción</div>
            <div class="accordion-content">
                <p>Este manual de usuario en formato de preguntas y respuestas está diseñado para administradores del sistema D'Jenny, una solución para la gestión de ventas e inventario en tiendas de abarrotes. Basado en la Especificación de Requisitos de Software (SRS) versión 2.0, cubre las funcionalidades específicas para el rol de administrador, incluyendo gestión de productos, categorías, proveedores, usuarios y reportes.</p>
            </div>
        </div>

        <div class="accordion" id="preguntas-generales">
            <div class="accordion-header">2 Preguntas Generales</div>
            <div class="accordion-content">
                <h3>¿Qué es el sistema D'Jenny?</h3>
                <p>D'Jenny es un software que optimiza la gestión de ventas, inventario, proveedores y usuarios en tiendas de abarrotes, con un enfoque en la eficiencia operativa y la toma de decisiones basada en datos.</p>
                <h3>¿Qué puede hacer un administrador en el sistema?</h3>
                <p>Los administradores pueden gestionar productos, categorías, proveedores, usuarios, órdenes de compra, reembolsos y generar informes detallados sobre ventas e inventario.</p>
                <h3>¿Qué requisitos técnicos necesita el sistema?</h3>
                <p>El dispositivo debe tener al menos 4GB de RAM, 16GB de almacenamiento, conexión a internet y un sistema operativo actualizado (no anterior a 2015).</p>
            </div>
        </div>

        <div class="accordion" id="autenticacion">
            <div class="accordion-header">3 Autenticación y Registro</div>
            <div class="accordion-content">
                <h3>¿Cómo inicio sesión como administrador?</h3>
                <p>En la página principal, selecciona "Iniciar Sesión", introduce tu correo electrónico y contraseña. Como administrador, serás redirigido al panel administrativo.</p>
                <h3>¿Qué hago si olvidé mi contraseña?</h3>
                <p>En la página de inicio de sesión, haz clic en "Recuperar Contraseña" e ingresa tu correo. Recibirás un correo con instrucciones para restablecerla.</p>
                <h3>¿Cómo cierro sesión?</h3>
                <p>En el panel administrativo, selecciona "Cerrar Sesión" para finalizar tu sesión de forma segura.</p>
            </div>
        </div>

        <div class="accordion" id="gestion-productos">
            <div class="accordion-header">4 Gestión de Productos</div>
            <div class="accordion-content">
                <h3>¿Cómo veo los productos disponibles?</h3>
                <p>En el panel administrativo, ve a "Productos" para ver un listado con nombre, descripción, precio, stock, categoría e imagen de todos los productos.</p>
                <h3>¿Cómo busco o filtro productos?</h3>
                <p>En "Productos", usa la barra de búsqueda para buscar por nombre o filtra por categoría o nivel de stock (bajo, medio, alto).</p>
                <h3>¿Cómo creo un nuevo producto?</h3>
                <p>En "Productos", selecciona "Crear Producto", ingresa nombre, descripción, precio, stock, categoría e imagen (opcional), y guarda.</p>
                <h3>¿Cómo edito o elimino un producto?</h3>
                <p>En "Productos", selecciona un producto, haz clic en "Editar" para modificar sus detalles o "Eliminar" para retirarlo del catálogo.</p>
                <h3>¿Cómo veo los detalles de un producto?</h3>
                <p>En "Productos", selecciona un producto y haz clic en "Ver Detalles" para revisar toda su información.</p>
            </div>
        </div>

        <div class="accordion" id="gestion-categorias">
            <div class="accordion-header">5 Gestión de Categorías</div>
            <div class="accordion-content">
                <h3>¿Cómo veo las categorías existentes?</h3>
                <p>En el panel administrativo, ve a "Categorías" para ver una lista con nombre, descripción y estado, con opciones para filtrar por nombre o estado.</p>
                <h3>¿Cómo creo una nueva categoría?</h3>
                <p>En "Categorías", selecciona "Crear Categoría", ingresa nombre, descripción (opcional) y estado, y guarda.</p>
                <h3>¿Cómo edito una categoría?</h3>
                <p>En "Categorías", selecciona una categoría, haz clic en "Editar" y actualiza nombre, descripción o estado.</p>
                <h3>¿Cómo elimino una categoría?</h3>
                <p>En "Categorías", selecciona una categoría sin productos asociados y haz clic en "Eliminar".</p>
                <h3>¿Cómo verifico si un nombre de categoría está en uso?</h3>
                <p>Al crear o editar una categoría, el sistema verifica automáticamente (vía AJAX) si el nombre está disponible para evitar duplicados.</p>
            </div>
        </div>

        <div class="accordion" id="gestion-proveedores">
            <div class="accordion-header">6 Gestión de Proveedores</div>
            <div class="accordion-content">
                <h3>¿Cómo gestiono proveedores?</h3>
                <p>En el panel administrativo, ve a "Proveedores" para visualizar, crear, editar o eliminar proveedores.</p>
                <h3>¿Cómo configuro notificaciones para proveedores?</h3>
                <p>En "Proveedores", selecciona "Configurar Correo" e ingresa un correo electrónico para recibir notificaciones sobre contratos próximos a vencer.</p>
                <h3>¿Cómo creo o gestiono órdenes de compra?</h3>
                <p>En "Proveedores", selecciona "Órdenes de Compra" para crear, visualizar, editar o eliminar órdenes con detalles de productos.</p>
                <h3>¿Cómo consulto el historial de órdenes de compra?</h3>
                <p>En "Proveedores", selecciona un proveedor y ve a "Historial de Órdenes" para ver un listado paginado con detalles y estados.</p>
            </div>
        </div>

        <div class="accordion" id="gestion-usuarios">
            <div class="accordion-header">7 Gestión de Usuarios</div>
            <div class="accordion-content">
                <h3>¿Cómo gestiono usuarios?</h3>
                <p>En el panel administrativo, ve a "Usuarios" para ver una lista de usuarios registrados, crear nuevos usuarios o editar datos (nombre, correo, rol).</p>
                <h3>¿Cómo asigno roles a los usuarios?</h3>
                <p>En "Usuarios", selecciona un usuario, haz clic en "Editar" y asigna un rol (administrador, cajero o cliente) según sea necesario.</p>
            </div>
        </div>

        <div class="accordion" id="reportes">
            <div class="accordion-header">8 Informes y Reportes</div>
            <div class="accordion-content">
                <h3>¿Cómo consulto informes de ventas e inventario?</h3>
                <p>En el panel administrativo, ve a "Informes" para consultar reportes sobre ventas diarias, productos más vendidos, productos con bajo stock, valor total del inventario, ganancia total y cierre de caja diario.</p>
                <h3>¿Cómo proceso reembolsos?</h3>
                <p>En "Órdenes", selecciona una orden cancelada, haz clic en "Procesar Reembolso" y actualiza el estado del pago.</p>
            </div>
        </div>

        <div class="accordion" id="resolucion-problemas">
            <div class="accordion-header">9 Resolución de Problemas</div>
            <div class="accordion-content">
                <h3>¿Qué hago si la página no carga?</h3>
                <p>Verifica la conexión a internet y la configuración de la base de datos en el archivo .env. Asegúrate de que el servidor esté activo.</p>
                <h3>¿Qué hago si no puedo iniciar sesión?</h3>
                <p>Confirma que tus credenciales sean correctas. Revisa la tabla de usuarios en la base de datos si el problema persiste.</p>
                <h3>¿Qué hago si el stock no se actualiza?</h3>
                <p>Verifica la función de actualización de inventario en el sistema y asegúrate de que las órdenes se procesen correctamente.</p>
                <h3>¿Qué hago si un informe está vacío?</h3>
                <p>Revisa los filtros aplicados (fechas, categorías) y verifica que existan datos en la base de datos para el período seleccionado.</p>
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