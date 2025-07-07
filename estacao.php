<?php
    $titulo = "Estação Meteorológica";
    include_once './include/header.php';
?>

<main style="padding: 2rem; max-width: 900px; margin: auto;">
    <h1 style="text-align: center;">Gráficos de Medições</h1>
    <div id="graficosContainer"></div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
window.onload = async function () {
    const container = document.getElementById("graficosContainer");

    try {
        // 1. Pega a lista de sensores
        const respostaSensores = await fetch("http://estacao:1880/dados/sensores");
        const sensores = await respostaSensores.json();

        for (const sensor of sensores) {
            const id = sensor.id;

            // 2. Cria elementos HTML para cada gráfico
            const titulo = document.createElement("h2");
            titulo.innerText = `Sensor ${id}`;
            container.appendChild(titulo);

            const canvas = document.createElement("canvas");
            canvas.id = `graficoSensor${id}`;
            canvas.width = 800;
            canvas.height = 400;
            container.appendChild(canvas);

            // 3. Busca os dados do sensor individualmente
            const respostaDados = await fetch(`http://estacao:1880/dados/sensor/${id}`);
            const dados = await respostaDados.json();

            const labels = dados.map(d => new Date(d.timestamp_medicao).toLocaleString('pt-BR'));
            const valores = dados.map(d => parseFloat(d.medicao));

            // 4. Monta o gráfico Chart.js
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
