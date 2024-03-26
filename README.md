
# Developer Challenge: Aplicación de Administración de Tareas para Empleados

Este repositorio contiene una aplicación desarrollada como parte del Developer Challenge propuesto por [Noc Noc](https://nocnoc.app/), que tiene como objetivo crear un sistema de administración de tareas para los empleados de la empresa "XY". La aplicación está construida utilizando el framework Laravel para la API backend y Vue.js para la interfaz de usuario frontend.

## Funcionalidades Implementadas

### Backend (API - Laravel)

- **Autenticación:**
  - Funcionalidad de inicio de sesión mediante correo electrónico y contraseña.
  - Funcionalidad de "Olvidé mi clave" para el cambio de contraseña mediante correo electrónico.
  - Registro manual de usuarios por un usuario super admin. Los usuarios reciben un correo electrónico de bienvenida y se les solicita configurar su contraseña en su primer inicio de sesión.
  
- **Administración de Tareas:**
  - Creación y eliminación de tareas por usuarios super admin.
  - Asignación de empleados a tareas únicamente por usuarios super admin.
  - Visualización de la lista de tareas asignadas para todos los usuarios (empleados y super admins).
  - Visualización de todas las tareas para super admins y extensión opcional para empleados.
  - Modificación del estado de las tareas por empleados (solo tareas asignadas) y super admins (cualquier tarea).
  
- **Comentarios y Adjuntos:**
  - Comentarios en las tareas por todos los usuarios.
  - Adjuntar archivos (PDF, JPG, JPEG, PNG) a las tareas por todos los usuarios.
  - Eliminación de archivos adjuntos por el empleado asignado, el empleado que lo adjuntó o super admins.

- **Reportes:**
  - Generación de un reporte/resumen en PDF de las tareas realizadas en un periodo de tiempo por super admins. Permite seleccionar un rango de fechas y visualiza el estado, tiempo de completado y empleado asignado de cada tarea.

### Frontend (Vue.js)

- **Interfaz Gráfica:**
  - Implementación de interfaz de usuario con Vue.js.
  - Utilización de VueRouter para la navegación.
  - Manejo del estado de la aplicación con Vuex.
  - Estilizado con TailwindCSS para una apariencia moderna y responsiva.
  - Integración de Axios para realizar peticiones HTTP a la API backend.

## Criterios de Evaluación Adicionales

- Almacenamiento de datos en una base de datos MySQL.
- Utilización de Eloquent, MVC, Middlewares, POO, y Validaciones de datos.
- Implementación de Jobs para la generación de reportes por parte de super admins.
- Normalización de la base de datos para reducir redundancia de datos.
- Implementación de autenticación por JWT o por sesión.
- Código limpio y legible.

## Instalación y Configuración

1. Clonar este repositorio.
2. Configurar el entorno de desarrollo local según las instrucciones del archivo `README` en las carpetas `backend` y `frontend`.

Para obtener información detallada sobre cómo ejecutar la aplicación localmente, consulte los archivos `README` en las carpetas `backend` y `frontend`.

## Contribución

¡Las contribuciones son bienvenidas! Si desea contribuir a este proyecto, por favor abra un issue o envíe una solicitud de extracción con sus mejoras.

## Licencia

Este proyecto está bajo la licencia [MIT](LICENSE).# NocNoc
