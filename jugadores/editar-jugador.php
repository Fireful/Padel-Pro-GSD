<?php
require '../db/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM jugadores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Jugador no encontrado.");
}

$jugador = $result->fetch_assoc();
?>

<?php include '../includes/header.php'; ?>

<div class="d-flex align-items-center justify-content-center vh-100">
  <div class="card shadow-sm w-100" style="max-width: 600px;">
    <div class="card-header bg-primary text-white text-center">
      <h4 class="mb-0">Editar Jugador</h4>
    </div>
    <div class="card-body">

      <form method="POST" action="actualizar-jugador.php" class="row g-3 needs-validation" novalidate>

        <input type="hidden" name="id" value="<?= $jugador['id'] ?>">

        <div class="col-md-6">
          <label for="nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" name="nombre" value="<?= $jugador['nombre'] ?>" required>
        </div>

        <div class="col-md-6">
          <label for="apellido" class="form-label">Apellido</label>
          <input type="text" class="form-control" name="apellido" value="<?= $jugador['apellido'] ?>" required>
        </div>

        <div class="col-md-12">
          <label for="categoria" class="form-label">Categoría</label>
          <select name="categoria" class="form-select" required>
            <option value="">Selecciona...</option>
            <option value="masculino" <?= $jugador['categoria'] === 'masculino' ? 'selected' : '' ?>>Masculino</option>
            <option value="femenino" <?= $jugador['categoria'] === 'femenino' ? 'selected' : '' ?>>Femenino</option>
            <option value="mixto" <?= $jugador['categoria'] === 'mixto' ? 'selected' : '' ?>>Mixto</option>
          </select>
        </div>

        <div class="col-12 d-grid">
          <button type="submit" class="btn btn-primary">Actualizar Jugador</button>
        </div>

      </form>

    </div>
  </div>
</div>

<?php include 'footer.php'; ?>