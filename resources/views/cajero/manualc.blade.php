<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual de Usuario para Cajeros: Sistema D'Jenny</title>
    <link rel="stylesheet" href="{{ asset('css/manuales.css') }}">

</head>
<body>
    <div class="nav-sidebar">
        <ul>
            <li><a href="#indice" data-accordion="indice">Índice</a></li>
            <li><a href="#introduccion" data-accordion="introduccion">1 Introducción</a></li>
            <li><a href="#preguntas-generales" data-accordion="preguntas-generales">2 Preguntas Generales</a></li>
            <li><a href="#autenticacion" data-accordion="autenticacion">3 Autenticación y Registro</a></li>
            <li><a href="#procesar-pagos" data-accordion="procesar-pagos">4 Procesar Pagos</a></li>
            <li><a href="#gestion-ordenes" data-accordion="gestion-ordenes">5 Gestión de Órdenes</a></li>
            <li><a href="#reportes" data-accordion="reportes">6 Informes y Reportes</a></li>
            <li><a href="#resolucion-problemas" data-accordion="resolucion-problemas">7 Resolución de Problemas</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Manual de Usuario para Cajeros: Sistema D'Jenny</h1>

        <div class="accordion" id="introduccion">
            <div class="accordion-header">1 Introducción</div>
            <div class="accordion-content">
                <p>Este manual de usuario en formato de preguntas y respuestas está diseñado para cajeros del sistema D'Jenny, una solución para la gestión de ventas en tiendas de abarrotes. Basado en la Especificación de Requisitos de Software (SRS) versión 2.0, cubre las funcionalidades específicas para el rol de cajero, incluyendo procesamiento de pagos, gestión de órdenes y generación de reportes.</p>
            </div>
        </div>

        <div class="accordion" id="preguntas-generales">
            <div class="accordion-header">2 Preguntas Generales</div>
            <div class="accordion-content">
                <h3>¿Qué es el sistema D'Jenny?</h3>
                <p>D'Jenny es un software que facilita la gestión de ventas en tiendas de abarrotes, permitiendo a los cajeros procesar pagos, gestionar órdenes y generar reportes de transacciones.</p>
                <h3>¿Qué puede hacer un cajero en el sistema?</h3>
                <p>Los cajeros pueden confirmar pagos en efectivo o Nequi, visualizar y gestionar órdenes, exportar transacciones a CSV y realizar el cierre de caja diario.</p>
                <h3>¿Qué requisitos técnicos necesita el sistema?</h3>
                <p>El dispositivo debe tener al menos 4GB de RAM, 16GB de almacenamiento, conexión a internet y un sistema operativo actualizado (no anterior a 2015).</p>
            </div>
        </div>

        <div class="accordion" id="autenticacion">
            <div class="accordion-header">3 Autenticación y Registro</div>
            <div class="accordion-content">
                <h3>¿Cómo inicio sesión como cajero?</h3>
                <p>En la página principal, selecciona "Iniciar Sesión", introduce tu correo electrónico y contraseña. Como cajero, serás redirigido al panel de cajero.</p>
                <h3>¿Qué hago si olvidé mi contraseña?</h3>
                <p>En la página de inicio de sesión, haz clic en "Recuperar Contraseña" e ingresa tu correo. Recibirás un correo con instrucciones para restablecerla.</p>
                <h3>¿Cómo cierro sesión?</h3>
                <p>En el panel de cajero, selecciona "Cerrar Sesión" para finalizar tu sesión de forma segura.</p>
            </div>
        </div>

        <div class="accordion" id="procesar-pagos">
            <div class="accordion-header">4 Procesar Pagos</div>
            <div class="accordion-content">
                <h3>¿Cómo confirmo un pago en efectivo?</h3>
                <p>En el panel de cajero, selecciona la orden correspondiente, marca "Pago en Efectivo" y haz clic en "Confirmar" para actualizar el estado de la orden y el pago.</p>
                <h3>¿Cómo confirmo un pago por Nequi?</h3>
                <p>En el panel de cajero, selecciona la orden, ingresa el ID de transacción de Nequi y haz clic en "Confirmar" para actualizar los estados de la orden y el pago.</p>
            </div>
        </div>

        <div class="accordion" id="gestion-ordenes">
            <div class="accordion-header">5 Gestión de Órdenes</div>
            <div class="accordion-content">
                <h3>¿Cómo veo las órdenes de los clientes?</h3>
                <p>En el panel de cajero, ve a "Órdenes" para ver una lista con ID, fecha, total, estado y método de pago de todas las órdenes.</p>
                <h3>¿Cómo veo los detalles de una orden?</h3>
                <p>En "Órdenes", selecciona una orden para ver detalles como productos, cantidades, subtotales, total, estado y método de pago.</p>
            </div>
        </div>

        <div class="accordion" id="reportes">
            <div class="accordion-header">6 Informes y Reportes</div>
            <div class="accordion-content">
                <h3>¿Cómo exporto transacciones a CSV?</h3>
                <p>En el panel de cajero, ve a "Transacciones", aplica filtros (nombre de cliente, estado, fechas) y selecciona "Exportar a CSV" para descargar los datos.</p>
                <h3>¿Cómo realizo el cierre de caja?</h3>
                <p>En el panel de cajero, selecciona "Cierre de Caja", revisa el resumen de ventas diarias y haz clic en "Confirmar" para generar y enviar el informe al administrador por correo.</p>
            </div>
        </div>

        <div class="accordion" id="resolucion-problemas">
            <div class="accordion-header">7 Resolución de Problemas</div>
            <div class="accordion-content">
                <h3>¿Qué hago si la página no carga?</h3>
                <p>Verifica tu conexión a internet y asegúrate de que el sistema esté activo. Contacta al administrador si el problema persiste.</p>
                <h3>¿Qué hago si no puedo iniciar sesión?</h3>
                <p>Confirma que tu correo y contraseña sean correctos. Usa "Recuperar Contraseña" si es necesario.</p>
                <h3>¿Qué hago si una orden no aparece en el sistema?</h3>
                <p>Verifica que la orden haya sido confirmada por el cliente. Si no aparece, contacta al administrador para revisar la base de datos.</p>
                <h3>¿Qué hago si el informe de cierre de caja está vacío?</h3>
                <p>Revisa los filtros aplicados (fechas, categorías) y asegúrate de que haya transacciones registradas para el día. Contacta al administrador si el problema persiste.</p>
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