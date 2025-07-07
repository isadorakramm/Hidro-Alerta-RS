<?php
$atualizado = date('YmdHis') . rand(0, 99999);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?t=<?php echo $atualizado; ?>">
    <title><?php echo isset($titulo) ? $titulo : 'Estação Meteorológica'; ?></title>
</head>
<body class="inicio">
    <header>
        <h1>Mini Estação Meteorológica</h1>
        <nav>
            <ul>
                <li><a href="index.php">Página Inicial</a></li>
                <li><a href="info.php">Informações</a></li>
                <li><a href="formulario.php">Formulário</a></li>
            </ul>
        </nav>
    </header>
