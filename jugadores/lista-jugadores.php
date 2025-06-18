<?php
require '../db/db.php';
include '../includes/header.php';
$sql = "SELECT * FROM jugadores ORDER BY apellido ASC";
$result = $conn->query($sql);
?>
<a href="crear-jugador.php" class="btn btn-outline-secondary mt-4 mb-5 d-inline-block">➕ Registrar Jugador</a>
<section class="mt-5">
  <h2>Jugadores Registrados</h2>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead class="table-light">
          <tr>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Mano dominante</th>
            <th>Nivel</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
              <td><?= htmlspecialchars($row['categoria']) ?></td>
              <td><?= htmlspecialchars($row['mano_dominante']) ?></td>
              <td><?= htmlspecialchars($row['nivel']) ?></td>
              <td>
                <a href="editar-jugador.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1">Editar</a>
                <a href="borrar-jugador.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Eliminar jugador?')" class="btn btn-sm btn-outline-danger">Borrar</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-muted">No hay jugadores registrados aún.</p>
  <?php endif; ?>
</section>