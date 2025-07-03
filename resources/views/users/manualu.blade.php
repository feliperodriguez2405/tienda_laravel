<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual de Usuario para Clientes: Sistema D'Jenny</title>
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
            <li><a href="#carrito-compras" data-accordion="carrito-compras">5 Carrito de Compras</a></li>
            <li><a href="#proceso-pago" data-accordion="proceso-pago">6 Proceso de Pago</a></li>
            <li><a href="#gestion-ordenes" data-accordion="gestion-ordenes">7 Gestión de Órdenes</a></li>
            <li><a href="#reseñas" data-accordion="reseñas">8 Reseñas</a></li>
            <li><a href="#perfil" data-accordion="perfil">9 Perfil de Usuario</a></li>
            <li><a href="#resolucion-problemas" data-accordion="resolucion-problemas">10 Resolución de Problemas</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Manual de Usuario para Clientes: Sistema D'Jenny</h1>

        <div class="accordion" id="introduccion">
            <div class="accordion-header">1 Introducción</div>
            <div class="accordion-content">
                <p>Este manual de usuario en formato de preguntas y respuestas está diseñado para clientes del sistema D'Jenny, una solución para la gestión de compras en tiendas de abarrotes. Basado en la Especificación de Requisitos de Software (SRS) versión 2.0, cubre las funcionalidades disponibles para clientes, como registro, compra de productos, gestión de órdenes, reseñas y perfil.</p>
            </div>
        </div>

        <div class="accordion" id="preguntas-generales">
            <div class="accordion-header">2 Preguntas Generales</div>
            <div class="accordion-content">
                <h3>¿Qué es el sistema D'Jenny?</h3>
                <p>D'Jenny es un software que permite a los clientes realizar compras en tiendas de abarrotes de forma eficiente, con funciones como navegación de productos, gestión de carrito, pago y seguimiento de órdenes.</p>
                <h3>¿Qué necesito para usar el sistema?</h3>
                <p>Necesitas un dispositivo con al menos 4GB de RAM, 16GB de almacenamiento, conexión a internet y un sistema operativo actualizado (no anterior a 2015).</p>
                <h3>¿Cómo accedo al sistema?</h3>
                <p>Accede a través de la página principal de D'Jenny. Si no estás registrado, selecciona "Registrarse". Si ya tienes cuenta, haz clic en "Iniciar Sesión".</p>
            </div>
        </div>

        <div class="accordion" id="autenticacion">
            <div class="accordion-header">3 Autenticación y Registro</div>
            <div class="accordion-content">
                <h3>¿Cómo me registro en el sistema?</h3>
                <p>En la barra de navegación, haz clic en <strong>Registrarse</strong>, ingresa tu nombre, correo electrónico y contraseña. Se te asignará el rol de "usuario" automáticamente.</p>
                <h3>¿Cómo inicio sesión?</h3>
                <p>En la barra de navegación, selecciona <strong>Iniciar Sesión</strong>, introduce tu correo electrónico y contraseña. Serás redirigido a tu panel de usuario.</p>
                <h3>¿Qué hago si olvidé mi contraseña?</h3>
                <p>En la página de inicio de sesión, haz clic en "Recuperar Contraseña" e ingresa tu correo. Recibirás un correo con instrucciones para restablecerla.</p>
                <h3>¿Cómo cierro sesión?</h3>
                <p>En la barra de navegación, selecciona <strong>Cerrar Sesión</strong> para finalizar tu sesión de forma segura.</p>
            </div>
        </div>

        <div class="accordion" id="gestion-productos">
            <div class="accordion-header">4 Gestión de Productos</div>
            <div class="accordion-content">
                <h3>¿Cómo veo los productos disponibles?</h3>
                <p>En la barra de navegación, haz clic en <strong>Productos</strong>. Verás un catálogo con nombre, descripción, precio, stock, categoría e imagen de productos con stock mayor a 0.</p>
                <h3>¿Cómo busco o filtro productos?</h3>
                <p>En la sección de <strong>Productos</strong>, usa la barra de búsqueda para buscar por nombre o selecciona una categoría para filtrar los productos disponibles.</p>
            </div>
        </div>

        <div class="accordion" id="carrito-compras">
            <div class="accordion-header">5 Carrito de Compras</div>
            <div class="accordion-content">
                <h3>¿Cómo agrego productos al carrito?</h3>
                <p>En <strong>Productos</strong>, selecciona un producto, indica la cantidad (respetando el stock disponible) y haz clic en "Agregar al Carrito".</p>
                <h3>¿Cómo veo los productos en mi carrito?</h3>
                <p>En la barra de navegación, haz clic en <strong>Carrito</strong> (ícono con el número de ítems). Verás los productos añadidos con nombre, precio, cantidad, subtotal, IVA (19%) y total.</p>
                <h3>¿Cómo actualizo o elimino productos del carrito?</h3>
                <p>En <strong>Carrito</strong>, modifica la cantidad de un producto y haz clic en "Actualizar", o selecciona "Eliminar" para quitar un producto.</p>
            </div>
        </div>

        <div class="accordion" id="proceso-pago">
            <div class="accordion-header">6 Proceso de Pago</div>
            <div class="accordion-content">
                <h3>¿Cómo selecciono un método de pago?</h3>
                <p>En el proceso de checkout desde <strong>Carrito</strong>, selecciona entre "Efectivo" o "Nequi" como método de pago.</p>
                <h3>¿Cómo confirmo mi orden?</h3>
                <p>En <strong>Carrito</strong>, revisa los productos, selecciona el método de pago y haz clic en "Confirmar Orden" para crear la orden y registrar el pago.</p>
            </div>
        </div>

        <div class="accordion" id="gestion-ordenes">
            <div class="accordion-header">7 Gestión de Órdenes</div>
            <div class="accordion-content">
                <h3>¿Cómo veo mi historial de órdenes?</h3>
                <p>En la barra de navegación, selecciona <strong>Mis Pedidos</strong> para ver una lista con ID, fecha, total, estado y método de pago de tus órdenes.</p>
                <h3>¿Cómo veo los detalles de una orden?</h3>
                <p>En <strong>Mis Pedidos</strong>, selecciona una orden para ver detalles como productos, cantidades, subtotales, total, estado y método de pago.</p>
                <h3>¿Cómo cancelo una orden?</h3>
                <p>En <strong>Mis Pedidos</strong>, selecciona una orden en estado "procesando" y haz clic en "Cancelar" para anularla y restaurar el stock.</p>
            </div>
        </div>

        <div class="accordion" id="reseñas">
            <div class="accordion-header">8 Reseñas</div>
            <div class="accordion-content">
                <h3>¿Cómo envío una reseña de un producto?</h3>
                <p>En la barra de navegación, selecciona <strong>Reseñas</strong>, elige un producto, asigna una calificación (1-5) y escribe un comentario opcional. Haz clic en "Enviar".</p>
                <h3>¿Cómo veo las reseñas de un producto?</h3>
                <p>En <strong>Reseñas</strong> o en la página de un producto, verás las reseñas con calificación, comentario, autor y fecha.</p>
            </div>
        </div>

        <div class="accordion" id="perfil">
            <div class="accordion-header">9 Perfil de Usuario</div>
            <div class="accordion-content">
                <h3>¿Cómo veo mi perfil?</h3>
                <p>En la barra de navegación, selecciona <strong>Perfil</strong> para ver tu información personal (nombre, correo, fecha de registro).</p>
                <h3>¿Cómo actualizo mi perfil?</h3>
                <p>En <strong>Perfil</strong>, haz clic en "Editar", actualiza tu nombre, correo o contraseña (opcional) y guarda los cambios.</p>
            </div>
        </div>

        <div class="accordion" id="resolucion-problemas">
            <div class="accordion-header">10 Resolución de Problemas</div>
            <div class="accordion-content">
                <h3>¿Qué hago si la página no carga?</h3>
                <p>Verifica tu conexión a internet. Si el problema persiste, contacta al administrador de la tienda.</p>
                <h3>¿Qué hago si no puedo iniciar sesión?</h3>
                <p>Confirma que tu correo y contraseña sean correctos. Usa "Recuperar Contraseña" si es necesario.</p>
                <h3>¿Qué hago si mi carrito no se actualiza?</h3>
                <p>Revisa que la cantidad ingresada no exceda el stock. Si el problema continúa, contacta al administrador.</p>
                <h3>¿Qué hago si no veo mis órdenes?</h3>
                <p>En <strong>Mis Pedidos</strong>, verifica que estás en la cuenta correcta. Si no aparecen, contacta al administrador.</p>
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