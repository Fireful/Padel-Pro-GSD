<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>Datos recibidos:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
}
?>

<form method="POST" action="">
  <input type="text" name="nombre" placeholder="Nombre torneo" required />
  <br><br>
  <input type="date" name="fecha-inicio" required />
  <br><br>
  <input type="date" name="fecha-fin" required />
  <br><br>
  <select name="categoria" required>
    <option value="">Selecciona categoría</option>
    <option value="masculino">Masculino</option>
    <option value="femenino">Femenino</option>
    <option value="mixto">Mixto</option>
  </select>
  <br><br>
  <select name="formato" required>
    <option value="">Selecciona formato</option>
    <option value="liguilla">Liguilla</option>
    <option value="eliminatoria">Eliminatoria</option>
    <option value="round-robin">Round Robin</option>
  </select>
  <br><br>
  <input type="number" name="max-participantes" min="2" required />
  <br><br>
  <button type="submit">Enviar</button>
</form>