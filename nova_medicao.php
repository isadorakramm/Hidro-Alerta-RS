<?php
$titulo = "Nova Medição";
include_once './include/header.php';
require_once 'config.php'; // <-- Aqui está a mudança

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>

<main style="max-width: 600px; margin: auto; padding: 2rem;">
  <h1>Adicionar Nova Medição</h1>
  <form action="salvar_medicao.php" method="post">
    <label for="sensor_id">Sensor:</label>
    <select name="sensor_id" id="sensor_id" required>
      <?php
      $result = $conn->query("SELECT id, nome FROM sensores");
      while ($row = $result->fetch_assoc()) {
        $nome = $row['nome'] ?: "Sensor " . $row['id'];
        echo "<option value='{$row['id']}'>{$nome}</option>";
      }
      $conn->close();
      ?>
    </select><br><br>

    <label for="medicao">Valor da Medição (em metros):</label>
    <input type="number" name="medicao" id="medicao" step="0.01" required><br><br>

    <button type="submit">Salvar Medição</button>
  </form>
</main>

<?php include_once './include/footer.php'; ?>
