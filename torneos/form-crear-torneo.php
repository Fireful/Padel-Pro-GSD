
<section class="mb-5">
  
  <form id="form-crear-torneo" method="POST" action="torneos/guardar-torneo.php" class="d-flex row formulario needs-validation" novalidate>
    <h2 class="mb-4 text-primary">Crear Nuevo Torneo</h2>
    <div class="col-12">
      <label for="nombre" class="form-label">Nombre del Torneo</label>
      <input type="text" class="form-control" id="nombre" name="nombre" required>
      <div class="invalid-feedback">El nombre es obligatorio.</div>
    </div>

    <div class="col-12">
      <label for="fecha-inicio" class="form-label">Fecha de inicio</label>
      <input type="date" class="form-control" id="fecha-inicio" name="fecha-inicio" required>
      <div class="invalid-feedback">La fecha de inicio es obligatoria.</div>
    </div>

    <div class="col-12">
      <label for="fecha-fin" class="form-label">Fecha de fin</label>
      <input type="date" class="form-control" id="fecha-fin" name="fecha-fin" required>
      <div class="invalid-feedback">La fecha de fin es obligatoria.</div>
    </div>

    <div class="col-12">
      <label for="categoria" class="form-label">Categoría</label>
      <select id="categoria" name="categoria" class="form-select" required>
        <option value="">Selecciona...</option>
        <option value="masculino">Masculino</option>
        <option value="femenino">Femenino</option>
        <option value="mixto">Mixto</option>
      </select>
      <div class="invalid-feedback">La categoría es obligatoria.</div>
    </div>

    <div class="col-12">
      <label for="formato" class="form-label">Formato</label>
      <select id="formato" name="formato" class="form-select" required>
        <option value="">Selecciona...</option>
        <option value="liguilla">Liguilla</option>
        <option value="eliminatoria">Eliminatoria</option>
        <option value="round-robin">Round Robin</option>
      </select>
      <div class="invalid-feedback">El formato es obligatorio.</div>
    </div>

    <div class="col-12">
      <label for="max-participantes" class="form-label">Nº Máximo de Participantes</label>
      <input type="number" class="form-control" id="max-participantes" name="max-participantes" min="2" required>
      <div class="invalid-feedback">El número máximo de participantes es obligatorio.</div>
    </div>

    <div class="col-12">
      <button class="btn btn-primary" type="submit">Guardar Torneo</button>
    </div>
  </form>
</section>
