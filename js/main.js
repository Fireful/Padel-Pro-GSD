// Validación de formularios
(() => {
  'use strict'

  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()

document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('#form-add-jugador');

    forms.forEach(form => {
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // ✅ Evita el envío tradicional

            const formData = new FormData(this);
            const mensajeDiv = this.closest('.modal-body')?.querySelector('#mensaje-jugador');
            const listaJugadores = this.closest('.modal-body')?.querySelector('.lista-jugadores');
            const selectJugador = document.getElementById('select-jugador');
        });

        const torneoId = formData.get('torneo_id');
        const nombre = formData.get('nombre');
        const apellido = formData.get('apellido');
        const dni = formData.get('dni');
        const telefono = formData.get('telefono');
        const categoria = formData.get('categoria');

        // Verificar que todos los campos sean válidos
        if (!nombre || !apellido || !dni || !categoria) {
            if (mensajeDiv) {
                mensajeDiv.textContent = "Todos los campos son obligatorios.";
                mensajeDiv.className = 'alert alert-danger';
                mensajeDiv.classList.remove('d-none');
            }
            return;
        }

        // Enviar datos con fetch
        fetch('torneos/asociar-jugador.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (mensajeDiv) {
                    mensajeDiv.textContent = "Jugador añadido correctamente";
                    mensajeDiv.className = 'alert alert-success';
                    mensajeDiv.classList.remove('d-none');
                }

                if (listaJugadores) {
                    cargarJugadores(torneoId, listaJugadores, mensajeDiv);
                }

                if (selectJugador) {
                    cargarJugadoresDisponibles(torneoId, selectJugador);
                }

                form.reset();
            } else {
                if (mensajeDiv) {
                    mensajeDiv.textContent = data.message || "Error al guardar el jugador";
                    mensajeDiv.className = 'alert alert-danger';
                    mensajeDiv.classList.remove('d-none');
                }
            }
        })
        .catch(error => {
            console.error("Error:", error);
            if (mensajeDiv) {
                mensajeDiv.textContent = "Hubo un problema al añadir el jugador.";
                mensajeDiv.className = 'alert alert-danger';
                mensajeDiv.classList.remove('d-none');
            }
        });
    });
});

function cargarJugadores(torneo_id, contenedor, mensajeDiv = null) {
    fetch(`torneos/cargar-jugadores.php?torneo_id=${torneo_id}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('No se pudo cargar la lista de jugadores.');
        }
        return response.json();
    })
    .then(data => {
        contenedor.innerHTML = '';

        if (data.length === 0) {
            contenedor.innerHTML = '<div class="text-muted">No hay jugadores inscritos aún.</div>';
            return;
        }

        data.forEach(jugador => {
            const div = document.createElement('div');
            div.className = 'col-md-6';
            div.innerHTML = `
              <div class="card border rounded-3 h-100">
                <div class="card-body d-flex align-items-center">
                  <div class="flex-shrink-0 me-3">
                    <i class="bi bi-person-circle text-primary" style="font-size: 1.5rem;"></i>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="mb-0">${jugador.apellido}, ${jugador.nombre}</h6>
                  </div>
                </div>
              </div>`;
            contenedor.appendChild(div);
        });

        if (mensajeDiv) mensajeDiv.classList.add('d-none');
    })
    .catch(err => {
        console.error("Error al cargar jugadores:", err);
        contenedor.innerHTML = '<div class="text-danger">Error al actualizar la lista de jugadores.</div>';
    });
}

function cargarJugadoresDisponibles(torneo_id, selectElement) {
    fetch(`torneos/cargar-jugadores-disponibles.php?torneo_id=${torneo_id}`)
    .then(response => {
        if (!response.ok) throw new Error('Fallo al cargar jugadores disponibles');
        return response.json();
    })
    .then(jugadores => {
        // Limpiar el select
        selectElement.innerHTML = '<option value="">Selecciona un jugador...</option>';

        // Rellenar con nuevos jugadores
        jugadores.forEach(jugador => {
            const option = document.createElement('option');
            option.value = jugador.id;
            option.textContent = `${jugador.apellido}, ${jugador.nombre}`;
            selectElement.appendChild(option);
        });
    })
    .catch(err => {
        console.error("Error al cargar jugadores disponibles:", err);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const btnGenerarEquipos = document.getElementById('btn-generar-equipos');
    const contenedorEquipos = document.getElementById('contenedor-equipos');

    if (btnGenerarEquipos && contenedorEquipos) {
        btnGenerarEquipos.addEventListener('click', function () {
            const torneoId = this.getAttribute('data-torneo');

            fetch(`../torneos/generar-equipos.php?torneo_id=${torneoId}`)
                .then(response => {
                    if (!response.ok) throw new Error("Error en la respuesta del servidor");
                    return response.json();
                })
                .then(data => {
                    console.log("Datos recibidos:", data);

                    contenedorEquipos.innerHTML = '';

                    if (data.error) {
                        contenedorEquipos.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                        return;
                    }

                    // Mensaje de éxito
                    const alerta = document.createElement('div');
                    alerta.className = 'alert alert-success mb-4';
                    alerta.textContent = data.message;
                    contenedorEquipos.appendChild(alerta);

                    // Mostrar equipos en tarjetas
                    const titulo = document.createElement('h4');
                    titulo.className = 'mb-3';
                    titulo.textContent = 'Equipos Generados';
                    contenedorEquipos.appendChild(titulo);

                    const row = document.createElement('div');
                    row.className = 'row g-3';

                    data.equipos.forEach(equipo => {
                        const col = document.createElement('div');
                        col.className = 'col-md-6';

                        col.innerHTML = `
                          <div class="card border rounded shadow-sm p-3 mb-3">
                            <h6 class="mb-3">Equipo #${equipo.id}</h6>
                            <ul class="list-group list-group-flush">
                              <li class="list-group-item">${equipo.jugador1.nombre} ${equipo.jugador1.apellido}</li>
                              <li class="list-group-item">${equipo.jugador2.nombre} ${equipo.jugador2.apellido}</li>
                            </ul>
                          </div>
                        `;

                        row.appendChild(col);
                    });

                    contenedorEquipos.appendChild(row);
                })
                .catch(err => {
                    console.error("Error al generar equipos:", err);
                    contenedorEquipos.innerHTML = '<div class="alert alert-danger">Hubo un problema al generar los equipos.</div>';
                });
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const btnGenerarPartidos = document.getElementById('btn-generar-partidos');
    const contenedorPartidos = document.getElementById('contenedor-partidos');

    if (btnGenerarPartidos && contenedorPartidos) {
        btnGenerarPartidos.addEventListener('click', function () {
            const torneoId = this.getAttribute('data-torneo');

            fetch(`../torneos/generar-partidos.php?torneo_id=${torneoId}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Partidos generados:", data);

                    contenedorPartidos.innerHTML = '';

                    if (data.error) {
                        contenedorPartidos.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                        return;
                    }

                    const titulo = document.createElement('h4');
                    titulo.className = 'mb-3';
                    titulo.textContent = 'Partidos Generados';
                    contenedorPartidos.appendChild(titulo);

                    const row = document.createElement('div');
                    row.className = 'row g-3';

                    data.partidos.forEach((partido, index) => {
    let tipo = '';
    if (data.formato === 'eliminatoria') {
        if (index === 4) tipo = 'Semifinales';
        if (index === 5) tipo = 'Final';
    }

    const equipo1_jugadores = partido.equipo1?.jugadores?.map(j => j.nombre + ' ' + j.apellido).join(', ') || 'Por definir';
    const equipo2_jugadores = partido.equipo2?.jugadores?.map(j => j.nombre + ' ' + j.apellido).join(', ') || 'Por definir';

    const col = document.createElement('div');
    col.className = 'col-md-6';

    col.innerHTML = `
      <div class="card border rounded shadow-sm p-3 mb-3">
        ${tipo ? `<h6 class="mb-3">${tipo}</h6>` : ''}
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><strong>Equipo 1:</strong> ${equipo1_jugadores}</li>
          <li class="list-group-item"><strong>Equipo 2:</strong> ${equipo2_jugadores}</li>
        </ul>
      </div>
    `;

    row.appendChild(col);
});

                    contenedorPartidos.appendChild(row);
                })
                .catch(err => {
                    console.error("Error al generar partidos:", err);
                    contenedorPartidos.innerHTML = '<div class="alert alert-danger">Hubo un problema al generar los partidos.</div> ' + err;
                });
        });
    }
});