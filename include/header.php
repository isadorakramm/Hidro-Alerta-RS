<?php
$atualizado = date('YmdHis') . rand(0, 99999);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="\imagens\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css?t=<?php echo $atualizado; ?>">

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>


    <title><?php echo isset($titulo) ? $titulo : 'HidroAlerta RS'; ?></title>
</head>
<body class="inicio">
    <header>
        <h1>HidroAlerta RS</h1>
        <nav>
            <ul>
                <li><a href="index.php">Página Inicial</a></li>
                <!-- <li><a href="formulario.php">Formulário</a></li> -->
                <li><a href="estacao.php">Mini Estação Meteorológica</a></li>
                <li><a href="info.php">Informações</a></li>
            </ul>
        </nav>
    </header>
