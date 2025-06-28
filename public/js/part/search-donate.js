
document.addEventListener('DOMContentLoaded', function() {
  const toggleNuevoDonador = document.getElementById('toggleNuevoDonador');
  const nuevoDonadorContainer = document.getElementById('nuevoDonadorContainer');
  const donanteSelect = document.getElementById('donante');

  toggleNuevoDonador.addEventListener('change', function() {
    if (this.checked) {
      nuevoDonadorContainer.classList.remove('d-none');
      donanteSelect.disabled = true;
      donanteSelect.value = '';
      donanteSelect.removeAttribute('required');
    } else {
      nuevoDonadorContainer.classList.add('d-none');
      donanteSelect.disabled = false;
      donanteSelect.setAttribute('required', 'required');
    }
  });
});


