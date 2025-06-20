<?php
require_once 'config.php';  // Puxa as variáveis do config.php

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Receber os dados do formulário
    $nome = trim($_POST['nome'] ?? '');
    $latitude = trim($_POST['latitude'] ?? '');
    $longitude = trim($_POST['longitude'] ?? '');

    // Validar dados básicos
    if ($nome === '' || $latitude === '' || $longitude === '') {
        throw new Exception("Todos os campos são obrigatórios.");
    }
    if (!is_numeric($latitude) || !is_numeric($longitude)) {
        throw new Exception("Latitude e longitude devem ser números.");
    }
    $latitude = floatval($latitude);
    $longitude = floatval($longitude);

    if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
        throw new Exception("Latitude deve estar entre -90 e 90 e longitude entre -180 e 180.");
    }

    // Preparar e executar o INSERT
    $sql = "INSERT INTO sensores (nome, latitude, longitude) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $latitude, $longitude]);

    echo "<p>Sensor '$nome' cadastrado com sucesso!</p>";
    echo '<p><a href="formulario.php">Cadastrar outro sensor</a></p>';

} catch (Exception $e) {
    echo "<p>Erro ao cadastrar sensor: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo '<p><a href="formulario.php">Voltar</a></p>';
}
?>
