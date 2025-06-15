<!-- lista-torneos.php -->
<section id="lista-torneos">
  <h2>Torneos Creados</h2>
  <table>
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Fecha Inicio</th>
        <th>Fecha Fin</th>
        <th>Categoría</th>
        <th>Formato</th>
        <th>Participantes</th>
      </tr>
    </thead>
    <tbody>
      <?php
      require 'db.php';
      $result = $conn->query("SELECT * FROM torneos ORDER BY creado_en DESC");

      while ($row = $result->fetch_assoc()):
      ?>
        <tr>
          <td><?= htmlspecialchars($row['nombre']) ?></td>
          <td><?= $row['fecha_inicio'] ?></td>
          <td><?= $row['fecha_fin'] ?></td>
          <td><?= ucfirst($row['categoria']) ?></td>
          <td><?= ucfirst(str_replace('-', ' ', $row['formato'])) ?></td>
          <td><?= $row['max_participantes'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</section>