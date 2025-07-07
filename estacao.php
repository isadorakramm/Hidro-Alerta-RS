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
    window.onload = function () {
        fetch("http://estacao:1880/dados/sensor/1")
            .then(response => response.json())
            .then(data => {
                const labels = data.map(d => d.timestamp_medicao);
                const valores = data.map(d => parseFloat(d.medicao));

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
                            tension: 0.2
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
                        }
                    }
                });
            })
            .catch(err => {
                console.error("Erro ao carregar dados do sensor:", err);
            });
    };
    </script>

</body>
<?php
    include_once './include/footer.php';
?>
