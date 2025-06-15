// js/main.js
console.log("Padel Pro GSD - ¡Bienvenido!");

// Registrar Service Worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/service-worker.js')
      .then(registration => {
        console.log('Service Worker registrado:', registration);
      })
      .catch(error => {
        console.log('Error registrando Service Worker:', error);
      });
  });
}

// Manejar envío del formulario
document.getElementById('form-crear-torneo').addEventListener('submit', function (e) {
  //e.preventDefault();

  // Obtener valores del formulario
  const nombre = document.getElementById('nombre').value.trim();
  const fechaInicio = document.getElementById('fecha-inicio').value;
  const fechaFin = document.getElementById('fecha-fin').value;
  const categoria = document.getElementById('categoria').value;
  const formato = document.getElementById('formato').value;
  const maxParticipantes = parseInt(document.getElementById('max-participantes').value);

  // Validación simple
  if (!nombre || !fechaInicio || !fechaFin || !categoria || !formato || isNaN(maxParticipantes)) {
    alert('Por favor completa todos los campos correctamente.');
    return;
  }

  // Aquí puedes guardar los datos localmente o enviarlos a una base de datos
  console.log({
    nombre,
    fechaInicio,
    fechaFin,
    categoria,
    formato,
    maxParticipantes
  });

  // Mostrar mensaje de confirmación
  const mensaje = document.getElementById('mensaje-confirmacion');
  mensaje.classList.remove('oculto');

  // Limpiar formulario
  this.reset();

  // Ocultar mensaje después de 3 segundos
  setTimeout(() => {
    mensaje.classList.add('oculto');
  }, 3000);
});