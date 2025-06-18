<section>
  <h2 class="mb-4 text-primary">Torneos Guardados</h2>

  <?php
  require 'db/db.php';

  $sql = "
  SELECT 
    t.id, 
    t.nombre, 
    t.categoria, 
    t.formato, 
    t.fecha_inicio,
    t.fecha_fin,
    t.max_participantes,
    COUNT(tj.jugador_id) AS num_jugadores
  FROM torneos t
  LEFT JOIN torneos_jugadores tj ON t.id = tj.torneo_id
  GROUP BY t.id
  ORDER BY t.creado_en DESC";

$result = $conn->query($sql);
  $result = $conn->query($sql);

  if ($result->num_rows > 0):
  ?>
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Formato</th>
            <th>Jugadores</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          
          <?php while ($row = $result->fetch_assoc()): ?>

            <tr>
  <td>
  <button type="button" class="btn btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalTorneo<?= $row['id'] ?>">
    <?= htmlspecialchars($row['nombre']) ?>
  </button>
</td>
  <td><?= ucfirst($row['categoria']) ?></td>
  <td><?= ucwords(str_replace('-', ' ', $row['formato'])) ?></td>
  <td>
    <?= $row['num_jugadores'] ?> / <?= $row['max_participantes'] ?>
  </td>
  <td>
    <a href="torneos/ver-torneo.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-2">Ver</a>
    <a href="torneos/editar-torneo.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning me-2">Editar</a>
    <a href="torneos/borrar-torneo.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Eliminar este torneo?')" class="btn btn-sm btn-outline-danger">Borrar</a>
  </td>
</tr>
            <?php include 'modales/modal-ver-torneo.php'; ?>

          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info">No hay torneos registrados aún.</div>
  <?php endif; ?>
</section>