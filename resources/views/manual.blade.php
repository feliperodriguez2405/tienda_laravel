<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual de Usuario: Sistema D'Jenny</title>
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
            <li><a href="#proveedores" data-accordion="proveedores">8 Gestión de Proveedores</a></li>
            <li><a href="#reportes" data-accordion="reportes">9 Informes y Reportes</a></li>
            <li><a href="#resolucion-problemas" data-accordion="resolucion-problemas">10 Resolución de Problemas</a></li>
        </ul>
    </div>
    <div class="container">
        <button class="print-button" onclick="window.print()">Imprimir PDF</button>
        <h1>Manual de Usuario: Sistema D'Jenny</h1>
        <div class="highlight">
            <p><strong>Versión:</strong> 2.0</p>
            <p><strong>Equipo:</strong> Jaider Molina, Mauro Suarez, Felipe Bermúdez</p>
            <p><strong>Fecha:</strong> Junio 2025</p>
        </div>

        <div class="accordion" id="indice">
            <div class="accordion-header">Índice</div>
            <div class="accordion-content">
                <ul>
                    <li>1 Introducción</li>
                    <li>2 Preguntas Generales</li>
                    <li>3 Autenticación y Registro</li>
                    <li>4 Gestión de Productos</li>
                    <li>5 Carrito de Compras</li>
                    <li>6 Proceso de Pago</li>
                    <li>7 Gestión de Órdenes</li>
                    <li>8 Gestión de Proveedores</li>
                    <li>9 Informes y Reportes</li>
                    <li>10 Resolución de Problemas</li>
                </ul>
            </div>
        </div>

        <div class="accordion" id="introduccion">
            <div class="accordion-header">1 Introducción</div>
            <div class="accordion-content">
                <p>Este manual de usuario en formato de preguntas y respuestas está diseñado para ayudar a los usuarios del sistema D'Jenny, una solución para la gestión de ventas e inventario en tiendas de abarrotes. Cubre las funcionalidades clave para administradores, cajeros y clientes, basadas en la Especificación de Requisitos de Software (SRS) versión 2.0.</p>
            </div>
        </div>

        <div class="accordion" id="preguntas-generales">
            <div class="accordion-header">2 Preguntas Generales</div>
            <div class="accordion-content">
                <h3>¿Qué es el sistema D'Jenny?</h3>
                <p>D'Jenny es un software diseñado para optimizar la gestión de ventas e inventario en tiendas de abarrotes, permitiendo el control de productos, ventas, proveedores y reportes de manera eficiente.</p>
                <h3>¿Quiénes pueden usar el sistema?</h3>
                <p>El sistema está diseñado para tres tipos de usuarios: administradores (gestión completa), cajeros (procesamiento de ventas) y clientes (compra de productos).</p>
                <h3>¿Qué requisitos técnicos necesita el sistema?</h3>
                <p>El dispositivo debe tener al menos 4GB de RAM, 16GB de almacenamiento, conexión a internet y un sistema operativo actualizado (no anterior a 2015).</p>
            </div>
        </div>

        <div class="accordion" id="autenticacion">
            <div class="accordion-header">3 Autenticación y Registro</div>
            <div class="accordion-content">
                <h3>¿Cómo me registro en el sistema?</h3>
                <p>En la página principal, selecciona "Registrarse", ingresa tu nombre, correo electrónico y contraseña. Una vez registrado, se te asignará el rol de "usuario" automáticamente.</p>
                <h3>¿Cómo inicio sesión?</h3>
                <p>Ve a la página de "Inicio de Sesión", introduce tu correo electrónico y contraseña. Según tu rol (admin, cajero, usuario), serás redirigido a tu panel correspondiente.</p>
                <h3>¿Qué hago si olvidé mi contraseña?</h3>
                <p>En la página de inicio de sesión, haz clic en "Recuperar Contraseña" e ingresa tu correo. Recibirás un correo con instrucciones para restablecerla.</p>
                <h3>¿Cómo cierro sesión?</h3>
                <p>En el menú de usuario, selecciona "Cerrar Sesión" para finalizar tu sesión de forma segura.</p>
            </div>
        </div>

        <div class="accordion" id="gestion-productos">
            <div class="accordion-header">4 Gestión de Productos</div>
            <div class="accordion-content">
                <h3>¿Cómo veo los productos disponibles?</h3>
                <p>Como cliente, en la página principal, verás un catálogo con nombre, descripción, precio, stock, categoría e imagen de productos con stock mayor a 0.</p>
                <h3>¿Cómo busco o filtro productos?</h3>
                <p>Utiliza la barra de búsqueda para buscar por nombre o selecciona una categoría para filtrar. Los administradores también pueden filtrar por nivel de stock (bajo, medio, alto).</p>
                <h3>¿Cómo creo un nuevo producto? (Administrador)</h3>
                <p>En el panel de administrador, ve a "Productos", selecciona "Crear Producto" e ingresa nombre, descripción, precio, stock, categoría e imagen (opcional).</p>
                <h3>¿Cómo edito o elimino un producto? (Administrador)</h3>
                <p>En "Productos", selecciona un producto, elige "Editar" para modificar sus detalles o "Eliminar" para retirarlo del catálogo.</p>
            </div>
        </div>

        <div class="accordion" id="carrito-compras">
            <div class="accordion-header">5 Carrito de Compras</div>
            <div class="accordion-content">
                <h3>¿Cómo agrego productos al carrito?</h3>
                <p>En el catálogo, selecciona un producto, indica la cantidad (respetando el stock disponible) y haz clic en "Agregar al Carrito".</p>
                <h3>¿Cómo veo los productos en mi carrito?</h3>
                <p>Ve a "Carrito" para ver los productos añadidos, con detalles como nombre, precio, cantidad, subtotal, IVA (19%) y total.</p>
                <h3>¿Cómo actualizo o elimino productos del carrito?</h3>
                <p>En "Carrito", modifica la cantidad de un producto y haz clic en "Actualizar", o selecciona "Eliminar" para quitar un producto.</p>
            </div>
        </div>

        <div class="accordion" id="proceso-pago">
            <div class="accordion-header">6 Proceso de Pago</div>
            <div class="accordion-content">
                <h3>¿Cómo selecciono un método de pago?</h3>
                <p>En el proceso de checkout, selecciona entre "Efectivo" o "Nequi" como método de pago.</p>
                <h3>¿Cómo confirmo mi orden?</h3>
                <p>Revisa tu carrito, selecciona el método de pago, y haz clic en "Confirmar Orden" para crear la orden, registrar el pago y actualizar el inventario.</p>
                <h3>¿Cómo confirmo un pago en efectivo? (Cajero)</h3>
                <p>En el panel de cajero, selecciona la orden, marca "Pago en Efectivo" y confirma para actualizar el estado de la orden y el pago.</p>
                <h3>¿Cómo confirmo un pago por Nequi? (Cajero)</h3>
                <p>En el panel de cajero, selecciona la orden, ingresa el ID de transacción de Nequi y confirma para actualizar los estados de la orden y el pago.</p>
            </div>
        </div>

        <div class="accordion" id="gestion-ordenes">
            <div class="accordion-header">7 Gestión de Órdenes</div>
            <div class="accordion-content">
                <h3>¿Cómo veo mi historial de órdenes?</h3>
                <p>En tu panel de usuario, ve a "Órdenes" para ver una lista con ID, fecha, total, estado y método de pago de tus órdenes.</p>
                <h3>¿Cómo veo los detalles de una orden?</h3>
                <p>En "Órdenes", selecciona una orden para ver detalles como productos, cantidades, subtotales, total, estado y método de pago.</p>
                <h3>¿Cómo cancelo una orden?</h3>
                <p>En "Órdenes", selecciona una orden en estado "procesando", haz clic en "Cancelar" para anularla y restaurar el stock.</p>
            </div>
        </div>

        <div class="accordion" id="proveedores">
            <div class="accordion-header">8 Gestión de Proveedores</div>
            <div class="accordion-content">
                <h3>¿Cómo gestiono proveedores? (Administrador)</h3>
                <p>En el panel de administrador, ve a "Proveedores" para visualizar, crear, editar o eliminar proveedores.</p>
                <h3>¿Cómo configuro notificaciones para proveedores? (Administrador)</h3>
                <p>En "Proveedores", selecciona "Configurar Correo" e ingresa un correo electrónico para recibir notificaciones sobre contratos próximos a vencer.</p>
                <h3>¿Cómo gestiono órdenes de compra? (Administrador)</h3>
                <p>En "Proveedores", selecciona "Órdenes de Compra" para crear, visualizar, editar o eliminar órdenes con detalles de productos.</p>
                <h3>¿Cómo consulto el historial de órdenes de compra? (Administrador)</h3>
                <p>En "Proveedores", selecciona un proveedor y ve a "Historial de Órdenes" para ver un listado paginado con detalles y estados.</p>
            </div>
        </div>

        <div class="accordion" id="reportes">
            <div class="accordion-header">9 Informes y Reportes</div>
            <div class="accordion-content">
                <h3>¿Cómo consulto informes de ventas e inventario? (Administrador)</h3>
                <p>En el panel de administrador, ve a "Informes" para consultar reportes sobre ventas diarias, productos más vendidos, productos con bajo stock, valor total del inventario, ganancia total y cierre de caja diario.</p>
                <h3>¿Cómo exporto transacciones a CSV? (Cajero)</h3>
                <p>En el panel de cajero, ve a "Transacciones", aplica filtros (nombre de cliente, estado, fechas) y selecciona "Exportar a CSV" para descargar los datos.</p>
                <h3>¿Cómo realizo el cierre de caja? (Cajero)</h3>
                <p>En el panel de cajero, selecciona "Cierre de Caja", revisa el resumen de ventas diarias y haz clic en "Confirmar" para generar y enviar el informe al administrador por correo.</p>
            </div>
        </div>

        <div class="accordion" id="resolucion-problemas">
            <div class="accordion-header">10 Resolución de Problemas</div>
            <div class="accordion-content">
                <h3>¿Qué hago si la página no carga?</h3>
                <p>Verifica tu conexión a internet y asegúrate de que el sistema esté activo. Contacta al administrador si el problema persiste.</p>
                <h3>¿Qué hago si no puedo iniciar sesión?</h3>
                <p>Confirma que tu correo y contraseña sean correctos. Usa "Recuperar Contraseña" si es necesario.</p>
                <h3>¿Qué hago si el stock no se actualiza?</h3>
                <p>Contacta al administrador para verificar la configuración del inventario en el sistema.</p>
                <h3>¿Qué hago si un informe está vacío?</h3>
                <p>Revisa los filtros aplicados (fechas, categorías) y asegúrate de que haya datos disponibles. Contacta al administrador si el problema continúa.</p>
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