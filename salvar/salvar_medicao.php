<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once 'config.php'; // <-- Aqui também

  $sensor_id = $_POST['sensor_id'];
  $medicao = $_POST['medicao'];

  $conn = new mysqli($host, $user, $pass, $db);
  if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("INSERT INTO medicoes (sensor_id, medicao, timestamp_medicao) VALUES (?, ?, NOW())");
  $stmt->bind_param("id", $sensor_id, $medicao);

  if ($stmt->execute()) {
    echo "Medição salva com sucesso!";
    echo "<br><a href='nova_medicao.php'>Voltar</a>";
  } else {
    echo "Erro ao salvar: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
}
?>

