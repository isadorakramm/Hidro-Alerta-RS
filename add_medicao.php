<?php
$titulo = "Nova Medição";
include_once './include/header.php';
?>

<main style="max-width: 600px; margin: auto; padding: 2rem;">
  <h1>Adicionar Nova Medição</h1>
  <form id="formMedicao">
    <label for="sensor">Sensor:</label>
    <select name="sensor_id" id="sensor" required>
      <!-- Preenchido via JS -->
    </select><br><br>

    <label for="medicao">Valor da Medição (em metros):</label>
    <input type="number" id="medicao" step="0.01" required><br><br>

    <button type="submit">Enviar via MQTT</button>
  </form>

  <p id="status"></p>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.1.0/mqttws31.min.js"></script>
<script>
const status = document.getElementById("status");

// Conectar ao broker HiveMQ via WebSocket
const client = new Paho.MQTT.Client("broker.hivemq.com", 8000, "clientId_" + parseInt(Math.random() * 10000));

client.connect({
  onSuccess: () => status.innerText = "Conectado ao broker MQTT!",
  onFailure: () => status.innerText = "Erro ao conectar ao broker MQTT."
});

// Carrega os sensores na dropdown
window.onload = async () => {
  const resp = await fetch("/dados/sensores");
  const sensores = await resp.json();
  const select = document.getElementById("sensor");

  sensores.forEach(s => {
    const nome = s.nome || `Sensor ${s.id}`;
    const option = document.createElement("option");
    option.value = s.id;
    option.innerText = nome;
    select.appendChild(option);
  });
};

// Quando o formulário for enviado
document.getElementById("formMedicao").onsubmit = function (e) {
  e.preventDefault();

  const sensorId = parseInt(document.getElementById("sensor").value);
  const medicao = parseFloat(document.getElementById("medicao").value);

  const payload = JSON.stringify({ sensor_id: sensorId, medicao: medicao });
  const message = new Paho.MQTT.Message(payload);
  message.destinationName = "isadora";

  client.send(message);
  status.innerText = "Medição enviada via MQTT!";
};
</script>

<?php include_once './include/footer.php'; ?>
