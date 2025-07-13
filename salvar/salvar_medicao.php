<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php'; // cuidado com o caminho aqui!

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $sensor_id = intval($_POST['sensor_id'] ?? 0);
    $medicao = floatval($_POST['medicao'] ?? 0);

    if ($sensor_id <= 0 || $medicao <= 0) {
        throw new Exception("Preencha todos os campos corretamente.");
    }

    $sql = "INSERT INTO medicoes (sensor_id, medicao, timestamp_medicao) VALUES (?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$sensor_id, $medicao]);

    header("Location: ../nova_medicao.php?sucesso=1");
    exit();

} catch (Exception $e) {
    echo "<p>Erro ao salvar medição: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo '<p><a href="../nova_medicao.php">Voltar</a></p>';
}
?>

