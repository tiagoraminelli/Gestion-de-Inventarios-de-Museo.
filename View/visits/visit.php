<?
echo "visitas";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agendar Visita al Museo</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
  <style>
    body {
      background-color: #f9f9f9;
    }
    .swiper-slide img {
      width: 100%;
      height: 300px;
      object-fit: cover;
    }
    .form-section {
      background: #fff;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>



  <!-- nav-bar -->
    <?php include "../../public/html/nav-bar.html"; ?>

  <!-- Header -->
  <header class="bg-dark text-white text-center py-4">
    <h1>Museo Nacional - Agenda tu Visita</h1>
  </header>

  <!-- Formulario de agenda -->
  <section class="container my-5">
    <div class="form-section">
      <h2 class="text-center mb-4">Formulario de Agenda</h2>
      <form id="agendaForm">
        <div class="mb-3">
          <label for="nombre" class="form-label">Nombre Completo</label>
          <input type="text" class="form-control" id="nombre" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Correo Electrónico</label>
          <input type="email" class="form-control" id="email" required>
        </div>
        <div class="mb-3">
          <label for="telefono" class="form-label">Teléfono</label>
          <input type="tel" class="form-control" id="telefono" required>
        </div>
        <div class="mb-3">
          <label for="fecha" class="form-label">Fecha de Visita</label>
          <input type="date" class="form-control" id="fecha" required>
        </div>
        <div class="mb-3">
          <label for="hora" class="form-label">Hora de Visita</label>
          <input type="time" class="form-control" id="hora" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Agendar Visita</button>
      </form>
      <div id="mensaje" class="alert alert-success mt-3 d-none">¡Visita agendada con éxito!</div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-center text-white py-3">
    &copy; 2025 Museo Nacional. Todos los derechos reservados.
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Swiper JS -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script>
    // Inicializar Swiper
    const swiper = new Swiper('.mySwiper', {
      slidesPerView: 1,
      spaceBetween: 10,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      loop: true,
    });

    // Manejo del formulario
    document.getElementById('agendaForm').addEventListener('submit', function(e) {
      e.preventDefault();
      // Aquí puedes agregar lógica para enviar los datos al servidor
      document.getElementById('agendaForm').reset();
      document.getElementById('mensaje').classList.remove('d-none');
    });
  </script>
</body>
</html>
