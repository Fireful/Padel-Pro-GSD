// ...
if (data.success) {
    mensajeDiv.textContent = 'Jugador añadido correctamente.';
    mensajeDiv.className = 'alert alert-success';
    mensajeDiv.classList.remove('d-none');

    const torneoId = formData.get('torneo_id');
    const selectJugador = form.querySelector('#select-jugador');

    // Recargar lista de jugadores en el modal
    const listaJugadores = form.closest('.modal-body').querySelector('.lista-jugadores');
    if (listaJugadores) {
        cargarJugadores(torneoId, listaJugadores, mensajeDiv);
    }

    // Recargar el select de jugadores disponibles
    if (selectJugador) {
        cargarJugadoresDisponibles(torneoId, selectJugador);
    }

    form.reset();
}
// ...