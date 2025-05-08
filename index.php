<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Museo Demo</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Open+Sans&display=swap" rel="stylesheet">
  
  <!-- AOS Animations -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  
  <!-- Swiper CSS -->
  <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
  
  <style>
    body { font-family: 'Open Sans', sans-serif; }
    h1, h2, h3 { font-family: 'Playfair Display', serif; }
    .hero {
      background: url('https://picsum.photos/1200/600?random') center/cover no-repeat;
      height: 70vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-shadow: 0 0 10px rgba(0,0,0,0.7);
    }
    .hero h1 { font-size: 4rem; }
    footer { background: #333; color: white; padding: 20px 0; }
    footer a { color: #ddd; text-decoration: none; }
    footer a:hover { color: #fff; }
    /* Aseguramos que las imágenes de la galería tengan el mismo tamaño */
.swiper-slide img {
  width: 100%;
  height: 300px; /* Puedes ajustar la altura según tus necesidades */
  object-fit: cover; /* Esto asegura que las imágenes no se distorsionen */
}

/* Estilo adicional para la galería */
.swiper {
  position: relative;
  padding-top: 20px;
}

.swiper-pagination {
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translateX(-50%);
}

  </style>
</head>
<body>

<?php include './public/html/nav-bar.html'; ?>

<!-- HERO -->
<section class="hero text-center" data-aos="zoom-in" data-aos-duration="1200">
  <div>
    <h1 data-aos="fade-up">Bienvenidos al Museo Demo</h1>
    <p data-aos="fade-up" data-aos-delay="300">Explora arte, historia y cultura en un solo lugar</p>
  </div>
</section>

<!-- GALERÍA -->
<section class="container my-5">
    <h2 class="text-center mb-4" data-aos="flip-left">Galería Destacada</h2>
    <div class="swiper mySwiper" data-aos="zoom-in-up">
      <div class="swiper-wrapper">
        <div class="swiper-slide" data-aos="fade-up">
          <img src="./assets/img/gallery/gallery-1.jpg" class="img-fluid rounded" />
        </div>
        <div class="swiper-slide" data-aos="fade-up" data-aos-delay="150">
          <img src="./assets/img/gallery/gallery-2.jpg" class="img-fluid rounded" />
        </div>
        <div class="swiper-slide" data-aos="fade-up" data-aos-delay="300">
          <img src="./assets/img/gallery/gallery-3.jpg" class="img-fluid rounded" />
        </div>
        <div class="swiper-slide" data-aos="fade-up" data-aos-delay="450">
          <img src="./assets/img/gallery/gallery-4.jpg" class="img-fluid rounded" />
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </section>
  

<!-- TALLERES -->
<section id="talleres" class="container my-5">
  <h2 class="text-center mb-4" data-aos="fade-left">Talleres</h2>
  <div class="row g-4">
    <div class="col-md-4" data-aos="flip-right">
      <div class="card h-100">
        <img src="https://picsum.photos/300/200?8" class="card-img-top">
        <div class="card-body">
          <h5 class="card-title">Taller de Pintura</h5>
          <p class="card-text">Aprende técnicas básicas y avanzadas de pintura artística con profesionales.</p>
          <a href="#" class="btn btn-outline-primary">Inscribirse</a>
        </div>
      </div>
    </div>
    <div class="col-md-4" data-aos="flip-right" data-aos-delay="200">
      <div class="card h-100">
        <img src="https://picsum.photos/300/200?9" class="card-img-top">
        <div class="card-body">
          <h5 class="card-title">Taller de Escultura</h5>
          <p class="card-text">Explora las técnicas de modelado en arcilla y otros materiales escultóricos.</p>
          <a href="#" class="btn btn-outline-primary">Inscribirse</a>
        </div>
      </div>
    </div>
    <div class="col-md-4" data-aos="flip-right" data-aos-delay="400">
      <div class="card h-100">
        <img src="https://picsum.photos/300/200?10" class="card-img-top">
        <div class="card-body">
          <h5 class="card-title">Taller de Restauración</h5>
          <p class="card-text">Conoce las técnicas de conservación y restauración de obras de arte.</p>
          <a href="#" class="btn btn-outline-primary">Inscribirse</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- GUÍAS PROGRAMABLES -->
<section id="guias" class="container my-5">
  <h2 class="text-center mb-4" data-aos="slide-right">Guías Programables</h2>
  <div class="row g-4">
    <div class="col-md-6" data-aos="fade-left">
      <div class="p-4 bg-light rounded shadow-sm">
        <h5><i class="bi bi-person-walking"></i> Visita Escolar</h5>
        <p>Programa una visita educativa para grupos escolares, adaptada a la edad y nivel académico.</p>
        <a href="./View/visits/visit.php" class="btn btn-primary">Programar</a>
      </div>
    </div>
    <div class="col-md-6" data-aos="fade-right" data-aos-delay="150">
      <div class="p-4 bg-light rounded shadow-sm">
        <h5><i class="bi bi-people-fill"></i> Visita Privada</h5>
        <p>Organiza una visita guiada exclusiva para grupos reducidos o familiares.</p>
        <a href="./View/visits/visit.php" class="btn btn-primary">Solicitar</a>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIOS -->
<section class="container my-5">
  <h2 class="text-center mb-4" data-aos="flip-up">Comentarios de Visitantes</h2>
  <div class="swiper testimonialSwiper" data-aos="zoom-in">
    <div class="swiper-wrapper">
      <div class="swiper-slide text-center p-4 bg-light rounded" data-aos="fade-up">
        <p>"Una experiencia maravillosa. Aprendí mucho y disfruté cada exposición."</p>
        <strong>- María López</strong>
      </div>
      <div class="swiper-slide text-center p-4 bg-light rounded" data-aos="fade-up" data-aos-delay="150">
        <p>"El museo tiene actividades para toda la familia. Recomendado 100%."</p>
        <strong>- Jorge Pérez</strong>
      </div>
      <div class="swiper-slide text-center p-4 bg-light rounded" data-aos="fade-up" data-aos-delay="300">
        <p>"Volveré sin duda. Gran atención y contenido cultural."</p>
        <strong>- Ana Ruiz</strong>
      </div>
    </div>
    <div class="swiper-pagination"></div>
  </div>
</section>

<!-- EMPLEADOS -->
<section id="empleados" class="container my-5">
  <h2 class="text-center mb-4" data-aos="slide-down">Nuestro Equipo</h2>
  <div class="row g-4">
    <div class="col-md-3" data-aos="flip-left">
      <div class="card text-center h-100">
        <img src="https://i.pravatar.cc/150?1" class="card-img-top rounded-circle mx-auto mt-3" style="width: 100px;">
        <div class="card-body">
          <h5 class="card-title">Lucía Gómez</h5>
          <p class="card-text">Directora del museo</p>
        </div>
      </div>
    </div>
    <div class="col-md-3" data-aos="flip-left" data-aos-delay="100">
      <div class="card text-center h-100">
        <img src="https://i.pravatar.cc/150?2" class="card-img-top rounded-circle mx-auto mt-3" style="width: 100px;">
        <div class="card-body">
          <h5 class="card-title">Carlos Méndez</h5>
          <p class="card-text">Curador de arte</p>
        </div>
      </div>
    </div>
    <div class="col-md-3" data-aos="flip-left" data-aos-delay="200">
      <div class="card text-center h-100">
        <img src="https://i.pravatar.cc/150?3" class="card-img-top rounded-circle mx-auto mt-3" style="width: 100px;">
        <div class="card-body">
          <h5 class="card-title">Laura Torres</h5>
          <p class="card-text">Guía cultural</p>
        </div>
      </div>
    </div>
    <div class="col-md-3" data-aos="flip-left" data-aos-delay="300">
      <div class="card text-center h-100">
        <img src="https://i.pravatar.cc/150?4" class="card-img-top rounded-circle mx-auto mt-3" style="width: 100px;">
        <div class="card-body">
          <h5 class="card-title">Pedro Ramírez</h5>
          <p class="card-text">Restaurador</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- UBICACIÓN -->
<section id="ubicacion" class="container my-5">
  <h2 class="text-center mb-4" data-aos="zoom-in-left">Ubicación</h2>
  <div class="ratio ratio-16x9" data-aos="fade-up">
    <iframe 
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1722.3024939965837!2d-61.23558934628762!3d-30.305450861353897!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x944a79585c650f09%3A0xac8f5cfe4eb19f9e!2sLiceo%20Municipal%20%C3%81ngela%20Peralta%20Pino!5e0!3m2!1ses!2sar!4v1727321544657!5m2!1ses!2sar" 
    width="100%" 
    height="300" 
    style="border:0;" 
    allowfullscreen="" 
    loading="lazy"></iframe>
  </div>
</section>

<!-- FOOTER -->
<footer class="text-center mt-5" data-aos="fade-up">
  <p>&copy; 2025 Museo Demo | <a href="#">Política de privacidad</a></p>
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  AOS.init({
    once: true,
    duration: 1000
  });

  var swiper = new Swiper(".mySwiper", {
    pagination: { el: ".swiper-pagination" },
    loop: true
  });

  var testimonialSwiper = new Swiper(".testimonialSwiper", {
    pagination: { el: ".swiper-pagination" },
    autoplay: { delay: 4000 },
    loop: true
  });
</script>
</body>
</html>
