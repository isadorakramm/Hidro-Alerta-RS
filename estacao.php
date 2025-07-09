<?php
    $titulo = "Estação Meteorológica";
    include_once './include/header.php';
?>

<main class="estacao-main">
    <h1>Localização dos Sensores</h1>
    <div id="mapaSensores"></div>
    <h1 style="text-align: center;">Gráficos de Medições</h1>
    <div id="graficosContainer"></div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const apiHost = window.location.hostname;
window.onload = async function () {

    // MAPA

    const mapa = L.map('mapaSensores').setView([-30.0346, -51.2177], 12); // Centro POA

    // adicionando o mapa
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(mapa);

    // pegando as localizações dos sensores
    try {
        const respLocalizacao = await fetch(`http://${window.location.hostname}:1880/dados/localizacao`);
        const sensoresLocalizacao = await respLocalizacao.json();

        sensoresLocalizacao.forEach(sensor => {
            const marker = L.marker([sensor.latitude, sensor.longitude]).addTo(mapa);
            marker.bindPopup(`<strong>${sensor.nome || 'Sensor ' + sensor.id}</strong><br>ID: ${sensor.id}`);
        });
    } catch (erro) {
        console.error("Erro ao carregar localização dos sensores:", erro);
    }

    // GRAFICOS

    const container = document.getElementById("graficosContainer");

    try {
        // pega a lista de sensores
        const respostaSensores = await fetch(`http://${apiHost}:1880/dados/sensores`);
        const sensores = await respostaSensores.json();

        for (const sensor of sensores) {
            const id = sensor.id;

            // criando elementos HTML para cada gráfico
            const titulo = document.createElement("h2");
            titulo.innerText = `Sensor ${id}`;
            container.appendChild(titulo);

            const canvas = document.createElement("canvas");
            canvas.id = `graficoSensor${id}`;
            canvas.width = 800;
            canvas.height = 400;
            container.appendChild(canvas);

            // buscando os dados do sensor individualmente
            const respostaDados = await fetch(`http://${apiHost}:1880/dados/sensor/${id}`);
            const dados = await respostaDados.json();

            const labels = dados.map(d => new Date(d.timestamp_medicao).toLocaleString('pt-BR'));
            const valores = dados.map(d => parseFloat(d.medicao));

            // montando o gráfico com o chart
            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Medições (em metros)',
                        data: valores,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: { display: true, text: 'Horário da Medição' }
                        },
                        y: {
                            title: { display: true, text: 'Altura da água (m)' },
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    } catch (err) {
        console.error("Erro ao carregar sensores ou medições:", err);
        container.innerHTML = "<p>Erro ao carregar gráficos.</p>";
    }
};
</script>

<?php
    include_once './include/footer.php';
?>
