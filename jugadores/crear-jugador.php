<?php include '../includes/header.php'; ?>


    
      
    
    <div class="card-body">
      <form method="POST" action="guardar-jugador.php" class="formulario  d-flex flex-column g-3 needs-validation" novalidate>
<h2 class="card-header mb-4 text-primary">Registrar Nuevo Jugador</h2>

        <div class="col-md-6">
          <label for="nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" name="nombre" required>
          <div class="invalid-feedback">El nombre es obligatorio.</div>
        </div>

        <div class="col-md-6">
          <label for="apellido" class="form-label">Apellido</label>
          <input type="text" class="form-control" name="apellido" required>
          <div class="invalid-feedback">El apellido es obligatorio.</div>
        </div>

        <div class="col-md-12">
          <label for="categoria" class="form-label">Categoría</label>
          <select name="categoria" class="form-select" required>
            <option value="">Selecciona...</option>
            <option value="masculino">Masculino</option>
            <option value="femenino">Femenino</option>
            <option value="mixto">Mixto</option>
          </select>
          <div class="invalid-feedback">La categoría es obligatoria.</div>
        </div>

        <div class="col-md-12">
          <label for="mano_dominante" class="form-label">Mano dominante</label>
          <select name="mano_dominante" class="form-select" required>
            <option value="">Selecciona...</option>
            <option value="diestra">Diestra</option>
            <option value="zurda">Zurda</option>
          </select>
          <div class="invalid-feedback">La mano dominante es obligatoria.</div>
        </div>

        <div class="col-md-12">
          <label for="nivel" class="form-label">Nivel</label>
          <select name="nivel" class="form-select" required>
            <option value="">Selecciona...</option>
            <option value="principiante">Principiante</option>
            <option value="intermedio">Intermedio</option>
            <option value="avanzado">Avanzado</option>
          </select>
          <div class="invalid-feedback">El nivel es obligatorio.</div>
        </div>

        <div class="col-12 d-grid">
          <button type="submit" class="btn btn-primary">Guardar Jugador</button>
        </div>

      </form>


<?php include '../includes/footer.php'; ?>