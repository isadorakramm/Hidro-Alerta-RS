<?php
    $titulo = "Formulário";
    include_once './include/header.php';
?>
<body class="inicio">
    <main>
        <form class="formulario" id="sensorForm">
            <h2>Cadastre seu sensor!</h2>
            <label for="nome">Nome do sensor:</label>
            <input type="text" id="nome" name="nome" placeholder="Nome" required>

        <label for="latitude">Localização:</label>
        <div class="coordenadas">
            <input type="number" id="latitude" name="latitude" placeholder="Latitude" step="0.000001" required>
            <input type="number" id="longitude" name="longitude" placeholder="Longitude" step="0.000001" required>
        </div>


            <button class="botao" type="submit">Enviar</button>
        </form>
    </main>
        <script>
    document.getElementById("sensorForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const lat = parseFloat(document.getElementById("latitude").value);
        const lng = parseFloat(document.getElementById("longitude").value);
        
        if (isNaN(lat) || isNaN(lng)) {
            alert("Por favor, insira valores numéricos válidos para as coordenadas!");
            return;
        }
        
        if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
            alert("Latitude deve estar entre -90 e 90, e Longitude entre -180 e 180!");
            return;
        }
        
        // Se a validação passar, envia o formulário
        this.submit();
    });
    </script>
</body>
<?php
    include_once './include/footer.php';
?>