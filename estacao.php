<?php
    $titulo = "Estação Meteorológica";
    include_once './include/header.php';
?>
<body class="inicio">
    <main style="padding: 2rem; max-width: 900px; margin: auto;">

        <h1 style="text-align: center;">Gráfico de Medições - Sensor 1</h1>

        <!-- Área onde o gráfico vai aparecer -->
        <canvas id="graficoSensor1" width="800" height="400"></canvas>

    </main>

    <!-- Importa o Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    // Buscar os dados da API do Node-RED
    fetch("http://estacao:1880/dados/sensor/1")
        .then(response => response.json())
        .then(data => {
            const labels = data.map(d => d.timestamp_medicao); // rótulos do eixo X
            const valores = data.map(d => parseFloat(d.medicao)); // valores do eixo Y

            // Criar o gráfico com Chart.js
            new Chart(document.getElementById('graficoSensor1'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Medições (em metros)',
                        data: valores,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.2,
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Horário da Medição'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Altura da água (m)'
                            },
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        })
        .catch(err => {
            console.error("Erro ao carregar dados do sensor:", err);
        });
    </script>
</body>
<?php
    include_once './include/footer.php';
?>
