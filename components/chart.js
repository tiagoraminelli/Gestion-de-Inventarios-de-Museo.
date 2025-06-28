function crearGrafico(idCanvas, tipo, labels, data, labelTitulo, color) {
    const ctx = document.getElementById(idCanvas).getContext('2d');
    new Chart(ctx, {
      type: tipo,
      data: {
        labels: labels,
        datasets: [{
          label: labelTitulo,
          data: data,
          backgroundColor: color.background,
          borderColor: color.border,
          borderWidth: 1,
          fill: false,
          tension: 0.3 // solo afecta line
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  }
