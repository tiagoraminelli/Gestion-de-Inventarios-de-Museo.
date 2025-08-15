
# Museo - Sistema de Gestión de Piezas

## Descripción

Este proyecto es un sistema web para la  **gestión de piezas de un museo** , desarrollado en  **PHP, MySQL, JavaScript, Bootstrap y SweetAlert2** . Permite crear, editar, eliminar y listar piezas de forma dinámica, gestionando información relacionada con donantes y clasificaciones de piezas.

El sistema incluye paginación, búsqueda, validaciones en el backend y frontend, manejo de imágenes, y control de errores con alertas amigables.

---

## Tecnologías utilizadas

* **Backend:** PHP 8+
* **Frontend:** HTML5, CSS3, JavaScript, jQuery, Bootstrap 5, Bootstrap Icons, SweetAlert2
* **Base de datos:** MySQL
* **Servidor local recomendado:** XAMPP / WAMP / LAMP
* **Gestión de archivos:** PHP `move_uploaded_file` para subir imágenes
* **Control de errores:** Validaciones PHP y mensajes con SweetAlert2

/Formateo
│
├─ Controller/
│   ├─ PiezaController.php
│   ├─ DonanteController.php
│
├─ Model/
│   ├─ Pieza.php
│
├─ View/
│   ├─ pieza.php
│
├─ public/
│   ├─ css/
│   │   ├─ nav-list.css
│   │   ├─ modal.css
│   │   └─ style.css
│   ├─ js/
│   │   ├─ crear.js
│   │   ├─ editar.js
│   │   ├─ eliminar.js
│   │   ├─ pieza.js
│   │   └─ ver.js
│   └─ assets/
│       └─ upload/   # Carpeta donde se guardan las imágenes de las piezas
│
└─ README.md



## Funcionalidades actuales

### Gestión de piezas

* **Crear nueva pieza:**
  * Formulario con campos: número de inventario, especie, estado de conservación, fecha de ingreso, cantidad de piezas, clasificación, observación, imagen y donante.
  * Validaciones en el backend y frontend.
  * Limpieza de formulario tras creación.
* **Editar pieza:**
  * Modal dinámico con los mismos campos que el formulario de creación.
  * Validaciones avanzadas: tipos de clasificación, fecha válida, cantidad numérica, texto obligatorio.
  * Actualización de datos vía AJAX y confirmación con SweetAlert2.
* **Eliminar pieza:**
  * Botón de eliminación por pieza con confirmación SweetAlert2.
* **Listado de piezas:**
  * Cards con imagen, clasificación, ID, especie, observación.
  * Botones de ver, editar y eliminar.
  * Paginación y búsqueda dinámica.

### Gestión de donantes

* Selección de donante en el formulario de creación y edición.
* Validación de relación entre pieza y donante (FK en la base de datos).

### UI / UX

* Bootstrap 5 para diseño responsivo.
* Modal dinámico para editar piezas.
* SweetAlert2 para notificaciones amigables.
* Botón para ocultar / limpiar formularios.
* Diseño adaptable con cards y grid de piezas.


## Instalación

1. Clonar o descargar el proyecto en tu servidor local:

<pre class="overflow-visible!" data-start="2892" data-end="2935"><div class="contain-inline-size rounded-2xl relative bg-token-sidebar-surface-primary"><div class="sticky top-9"><div class="absolute end-0 bottom-0 flex h-9 items-center pe-2"><div class="bg-token-bg-elevated-secondary text-token-text-secondary flex items-center gap-4 rounded-sm px-2 font-sans text-xs"><span class="" data-state="closed"></span></div></div></div><div class="overflow-y-auto p-4" dir="ltr"><code class="whitespace-pre! language-bash"><span><span>git </span><span>clone</span><span> <URL_DEL_REPOSITORIO>
</span></span></code></div></div></pre>

2. Crear la base de datos MySQL (ejemplo: `museo`) e importar el script SQL que contiene las tablas:
   * `pieza`
   * `donante`
   * Otras tablas relacionadas
3. Configurar conexión a la base de datos en los modelos PHP (`Model/Pieza.php`).
4. Ejecutar el proyecto accediendo desde el navegador:

<pre class="overflow-visible!" data-start="3238" data-end="3286"><div class="contain-inline-size rounded-2xl relative bg-token-sidebar-surface-primary"><div class="sticky top-9"><div class="absolute end-0 bottom-0 flex h-9 items-center pe-2"><div class="bg-token-bg-elevated-secondary text-token-text-secondary flex items-center gap-4 rounded-sm px-2 font-sans text-xs"><span class="" data-state="closed"></span></div></div></div><div class="overflow-y-auto p-4" dir="ltr"><code class="whitespace-pre!"><span><span>http://localhost/Formateo/View/pieza.php</span></span></code></div></div></pre>
