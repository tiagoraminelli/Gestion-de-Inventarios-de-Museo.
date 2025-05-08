  // Datos para clasificación
  const datosPieza = window.datosPieza;

  const clasificacionCount = {};
  datosPieza.forEach(d => {
    clasificacionCount[d.clasificacion] = (clasificacionCount[d.clasificacion] || 0) + 1;
  });
  
  crearGrafico(
    'chartClasificacion', 'bar',
    Object.keys(clasificacionCount),
    Object.values(clasificacionCount),
    'Cantidad por Clasificación',
    { background: 'rgba(75, 192, 192, 0.5)', border: 'rgba(75, 192, 192, 1)' }
  );
  
  // Datos para donante
  const donanteCount = {};
  datosPieza.forEach(d => {
    donanteCount[d.Donante_idDonante] = (donanteCount[d.Donante_idDonante] || 0) + 1;
  });
  crearGrafico(
    'chartDonante', 'bar',
    Object.keys(donanteCount),
    Object.values(donanteCount),
    'Cantidad por Donante',
    { background: 'rgba(255, 99, 132, 0.5)', border: 'rgba(255, 99, 132, 1)' }
  );
  
  // Datos para estado
  const estadoCount = {};
  datosPieza.forEach(d => {
    estadoCount[d.estado_conservacion] = (estadoCount[d.estado_conservacion] || 0) + 1;
  });
  crearGrafico(
    'chartEstado', 'pie',
    Object.keys(estadoCount),
    Object.values(estadoCount),
    'Cantidad por Estado',
    { background: ['#ff6384','#36a2eb','#ffce56'], border: ['#fff','#fff','#fff'] }
  );
  
  // Datos para fechas
  const fechaCount = {};
datosPieza.forEach(d => {
  fechaCount[d.fecha_ingreso] = (fechaCount[d.fecha_ingreso] || 0) + 1;
});
  crearGrafico(
    'chartFechas', 'line',
    Object.keys(fechaCount),
    Object.values(fechaCount),
    'Cantidad por Fecha de Ingreso',
    { background: 'rgba(153, 102, 255, 0.5)', border: 'rgba(153, 102, 255, 1)' }
  );
