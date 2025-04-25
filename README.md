############################################################################################################################################
############################################################################################################################################

# SPA Ibaiondo

Este es un proyecto para el SPA Ibaiondo, una aplicación web para gestionar un centro de bienestar y relajación.

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite habilitado

## Instalación

1. Clonar el repositorio:

############################################################################################################################################
############################################################################################################################################

### Archivos redundantes que se pueden eliminar:

1. **Versiones duplicadas de archivos**:

    1. `core/Helper.php` (hay una versión TypeScript y otra PHP) - Mantener solo la versión PHP
    2. `.htaccess` duplicado (raíz y public) - Consolidar en uno solo



2. **Archivos marcados como "deleted" o vacíos**:

    1. `app/Views/home.php` (marcado como deleted)
    2. `app/index.php` (marcado como deleted)
    3. `app/routes.php` (marcado como deleted)
    4. `config/config.php` (marcado como deleted)
    5. `core/App.php` (marcado como deleted)
    6. `core/autoload.php` (marcado como deleted)
    7. `logs/.gitkeep` (marcado como deleted)
    8. `public/assets` (marcado como deleted)
    9. `public/css/style.css` (marcado como deleted)
    10. `public/js/main.js` (marcado como deleted)



3. **Archivos con contenido incorrecto**:

    1. `app/views/usuarios/reservas.php` (contiene configuración de la aplicación en lugar de una vista)





### Archivos que se pueden crear o modificar para nuevas funcionalidades:

1. **Implementar archivos vacíos**:

    1. `core/Session.php` - Para gestión avanzada de sesiones
    2. `core/Validator.php` - Para validación de formularios
    3. `app/models/Database.php` - Completar la implementación
    4. `app/Controllers/TrabajadorController.php` - Implementar funcionalidades para trabajadores



2. **Completar vistas de administración**:

    1. `app/views/admin/dashboard.php`
    2. `app/views/admin/reservas.php`
    3. `app/views/admin/servicios.php`
    4. `app/views/admin/trabajadores.php`
    5. `app/views/admin/usuarios.php`



3. **Nuevas funcionalidades que se podrían implementar**:

    1. **Sistema de valoraciones para servicios**:

        1. Crear tabla en la base de datos
        2. Crear modelo `Valoracion.php`
        3. Añadir funcionalidad en `ServicioController.php`
        4. Crear vistas para mostrar y añadir valoraciones



    2. **Sistema de notificaciones**:

        1. Crear tabla en la base de datos
        2. Crear modelo `Notificacion.php`
        3. Implementar sistema de notificaciones por email
        4. Añadir notificaciones en tiempo real



    3. **Panel de estadísticas para administradores**:

        1. Mejorar el dashboard con gráficos y estadísticas
        2. Crear reportes de reservas y servicios más populares



    4. **API REST para aplicación móvil**:

        1. Crear controladores API
        2. Implementar autenticación con tokens
        3. Documentar endpoints