// components/card.js

function renderPiezaStats(piezas) {
  const contenedor = document.getElementById('estadisticas-piezas');
  contenedor.innerHTML = ''; // Limpiar contenido previo

  const total = piezas.length;
  const clasificaciones = {};
  const recientes = piezas
    .slice()
    .sort((a, b) => new Date(b.fecha_ingreso) - new Date(a.fecha_ingreso))
    .slice(0, 3);

  piezas.forEach(p => {
    const clase = p.clasificacion || 'Sin clasificar';
    clasificaciones[clase] = (clasificaciones[clase] || 0) + 1;
  });

  // Tarjeta: Total de piezas
  contenedor.appendChild(createCard({
    color: 'primary',
    icon: 'box',
    title: 'Total de Piezas',
    value: total,
    description: 'Piezas registradas',
    progress: 100
  }));

  // Tarjeta: Clasificación por tipo
  contenedor.appendChild(createCard({
    color: 'info',
    icon: 'tags',
    title: 'Clasificación',
    list: clasificaciones
  }));

  // Tarjeta: Últimas piezas
  contenedor.appendChild(createCard({
    color: 'success',
    icon: 'clock',
    title: 'Recientes',
    recent: recientes.map(p => ({
      icon: 'cube',
      color: 'dark',
      title: `${p.num_inventario} - ${p.clasificacion}`,
      date: new Date(p.fecha_ingreso).toLocaleDateString()
    }))
  }));
}

function createCard({ color, icon, title, value, description, progress, list, recent }) {
  const col = document.createElement('div');
  col.className = 'col-md-6 col-lg-4';

  const card = document.createElement('div');
  card.className = `card border-${color} shadow-sm mb-4`;

  const header = document.createElement('div');
  header.className = `card-header bg-${color} text-white d-flex align-items-center`;
  header.innerHTML = `<i class="fas fa-${icon} me-2"></i><strong>${title}</strong>`;

  const body = document.createElement('div');
  body.className = 'card-body';

  if (list) {
    const ul = document.createElement('ul');
    ul.className = 'list-group list-group-flush';
    for (const key in list) {
      const li = document.createElement('li');
      li.className = 'list-group-item d-flex justify-content-between align-items-center';
      li.textContent = key;
      const span = document.createElement('span');
      span.className = `badge bg-${color} rounded-pill`;
      span.textContent = list[key];
      li.appendChild(span);
      ul.appendChild(li);
    }
    body.appendChild(ul);
  } else if (recent) {
    recent.forEach(item => {
      body.innerHTML += `
        <div class="d-flex mb-2">
          <div><i class="fas fa-${item.icon} text-${item.color} me-2"></i></div>
          <div>
            <div>${item.title}</div>
            <small class="text-muted">${item.date}</small>
          </div>
        </div>
      `;
    });
  } else {
    body.innerHTML = `
      <h2 class="card-title">${value}</h2>
      <p class="text-muted">${description}</p>
      <div class="progress mt-2">
        <div class="progress-bar bg-${color}" style="width: ${progress}%"></div>
      </div>
    `;
  }

  card.appendChild(header);
  card.appendChild(body);
  col.appendChild(card);
  return col;
}
