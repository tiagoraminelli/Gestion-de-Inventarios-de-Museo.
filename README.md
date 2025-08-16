# Museo - Sistema de Gestión de Piezas

## Descripción

Este proyecto es un sistema web para la  **gestión de piezas de un museo** , desarrollado con  **PHP, MySQL, JavaScript, Bootstrap y SweetAlert2** . Permite crear, editar, eliminar y listar piezas de forma dinámica, gestionando información relacionada con **donantes** y  **clasificaciones de piezas** .

El sistema incluye:

* Paginación y búsqueda dinámica.
* Validaciones en **backend** y  **frontend** .
* Manejo de imágenes para las piezas.
* Control de errores con alertas amigables mediante  **SweetAlert2** .

---

## Tecnologías utilizadas

* **Backend:** PHP 8+
* **Frontend:** HTML5, CSS3, JavaScript, jQuery, Bootstrap 5, Bootstrap Icons, SweetAlert2
* **Base de datos:** MySQL
* **Servidor local recomendado:** XAMPP / WAMP / LAMP
* **Gestión de archivos:** `move_uploaded_file` para subir imágenes
* **Control de errores:** Validaciones PHP y mensajes con SweetAlert2

## Funcionalidades actuales

### Gestión de piezas

* **Crear nueva pieza**
  * Formulario con campos: número de inventario, especie, estado de conservación, fecha de ingreso, cantidad de piezas, clasificación, observación, imagen y donante.
  * Validaciones en backend y frontend.
  * Limpieza automática del formulario tras creación.
* **Editar pieza**
  * Modal dinámico con los mismos campos que el formulario de creación.
  * Validaciones avanzadas: tipos de clasificación, fecha válida, cantidad numérica, campos obligatorios.
  * Actualización de datos vía AJAX con confirmación mediante SweetAlert2.
* **Eliminar pieza**
  * Botón de eliminación por pieza con confirmación SweetAlert2.
* **Listado de piezas**
  * Cards con imagen, clasificación, ID, especie y observación.
  * Botones de  **ver** , **editar** y  **eliminar** .
  * Paginación y búsqueda dinámica.

### Gestión de donantes

* Selección de donante en los formularios de creación y edición.
* Validación de la relación entre pieza y donante (FK en la base de datos).

### UI / UX

* Diseño responsivo con  **Bootstrap 5** .
* Modal dinámico para editar piezas.
* SweetAlert2 para notificaciones amigables.
* Botón para ocultar o limpiar formularios.
* Diseño moderno con cards y grid adaptable.


## Instalación

1. Clonar o descargar el proyecto en tu servidor local:

<pre class="overflow-visible!" data-start="2835" data-end="2878"><div class="contain-inline-size rounded-2xl relative bg-token-sidebar-surface-primary"><div class="sticky top-9"><div class="absolute end-0 bottom-0 flex h-9 items-center pe-2"><div class="bg-token-bg-elevated-secondary text-token-text-secondary flex items-center gap-4 rounded-sm px-2 font-sans text-xs"><span class="" data-state="closed"></span></div></div></div><div class="overflow-y-auto p-4" dir="ltr"><code class="whitespace-pre! language-bash"><span><span>git </span><span>clone</span><span> <https://github.com/tiagoraminelli/Gestion-de-Inventarios-de-Museo.>
</span></span></code></div></div></pre>

2. Crear la base de datos MySQL (ejemplo: `museo`) e importar el script SQL que contiene las tablas:

* `pieza`
* `donante`
* Otras tablas relacionadas

3. Configurar la conexión a la base de datos en los modelos PHP (`Model/Bd.php`).
4. Ejecutar el proyecto accediendo desde el navegador:
