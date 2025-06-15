<!-- form-crear-torneo.php -->
<section id="crear-torneo">
  <h2>Crear Nuevo Torneo</h2>
  <form id="form-crear-torneo" method="POST" action="guardar-torneo.php">
    <label for="nombre">Nombre del Torneo:</label>
    <input type="text" id="nombre" name="nombre" required />

    <label for="fecha-inicio">Fecha de Inicio:</label>
    <input type="date" id="fecha-inicio" name="fecha-inicio" required />

    <label for="fecha-fin">Fecha de Fin:</label>
    <input type="date" id="fecha-fin" name="fecha-fin" required />

    <label for="categoria">Categoría:</label>
    <select id="categoria" name="categoria" required>
      <option value="">Selecciona...</option>
      <option value="masculino">Masculino</option>
      <option value="femenino">Femenino</option>
      <option value="mixto">Mixto</option>
    </select>

    <label for="formato">Formato del Torneo:</label>
    <select id="formato" name="formato" required>
      <option value="">Selecciona...</option>
      <option value="liguilla">Liguilla</option>
      <option value="eliminatoria">Eliminatoria</option>
      <option value="round-robin">Round Robin</option>
    </select>

    <label for="max-participantes">Número Máximo de Equipos/Jugadores:</label>
    <input type="number" id="max-participantes" name="max-participantes" min="2" required />

    <button type="submit">Guardar Torneo</button>
  </form>

  <div id="mensaje-confirmacion" class="oculto">
    ✅ ¡Torneo guardado correctamente!
  </div>
</section>