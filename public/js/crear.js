const form = document.getElementById("formCrearPieza");

form.addEventListener("submit", function (e) {
  e.preventDefault(); // Evita el submit normal

  const formData = new FormData(form);
  formData.append("accion", "crear"); // <-- Esto es clave

  fetch("../Controller/PiezaController.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        Swal.fire({
          icon: "success",
          title: "¡Pieza creada!",
          text: "La pieza se ha creado correctamente.",
        }).then(() => {
          window.location.reload(); // o actualizar el grid dinámicamente
        });

        // Limpiar todos los campos del formulario
        $("#formCrearPieza")[0].reset();
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: data.errors
            ? data.errors.join(", ")
            : "No se pudo crear la pieza",
        });
      }
    })
    .catch((err) => console.error(err));
});
