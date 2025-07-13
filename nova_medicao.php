<?php
$titulo = "Nova Medição";
include_once './include/header.php';
require_once 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query("SELECT id, nome FROM sensores");
    $sensores = $stmt->fetchAll();
} catch (Exception $e) {
    die("Erro ao carregar sensores: " . htmlspecialchars($e->getMessage()));
}
?>

<body class="inicio">
<main>
    <form class="formulario" action="/Estacao-Meteorologica/salvar/salvar_medicao.php" method="post" novalidate>
        <h2>Adicionar Nova Medição</h2>

        <label for="sensor_id">Sensor:</label>
        <select name="sensor_id" id="sensor_id" required>
            <option value="">Selecione um sensor</option>
            <?php foreach ($sensores as $sensor): ?>
                <option value="<?= $sensor['id'] ?>">
                    <?= htmlspecialchars($sensor['nome']) ?: 'Sensor ' . $sensor['id'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="medicao">Valor da Medição (em metros):</label>
        <input type="number" name="medicao" id="medicao" step="0.01" required>

        <button class="botao" type="submit">Salvar Medição</button>
    </form>
</main>
</body>

<?php include_once './include/footer.php'; ?>
