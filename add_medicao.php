<?php
$titulo = "Nova Medição";
include_once './include/header.php';
?>

<main style="max-width: 600px; margin: auto; padding: 2rem;">
  <h1>Adicionar Nova Medição</h1>
  <form id="formMedicao">
    <label for="sensor">Sensor:</label>
<select name="sensor_id" id="sensor" required>
  <option disabled selected>Carregando sensores...</option>
</select><br><br>

    <label for="medicao">Valor da Medição (em metros):</label>
    <input type="number" id="medicao" step="0.01" required><br><br>

    <button type="submit">Enviar via MQTT</button>
  </form>

  <p id="status"></p>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.1.0/mqttws31.min.js"></script>

<script>
window.onload = async () => {
  const select = document.getElementById("sensor");

  try {
    const resp = await fetch("http://" + window.location.hostname + ":1880/dados/sensores");
    const sensores = await resp.json();

    console.log("Sensores carregados:", sensores); // ← pra verificar no console
    
    select.innerHTML = ""; // limpa "Carregando..."

    sensores.forEach(s => {
      const option = document.createElement("option");
      option.value = s.id;
      option.textContent = s.nome || `Sensor ${s.id}`;
      select.appendChild(option);
    });

    if (sensores.length === 0) {
      select.innerHTML = "<option>Nenhum sensor disponível</option>";
    }
  } catch (err) {
    console.error("Erro ao carregar sensores:", err);
    select.innerHTML = "<option>Erro ao carregar sensores</option>";
  }
};
</script>

<!-- Biblioteca Paho (precisa estar carregada ANTES do uso) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.1.0/mqttws31.min.js"></script>

<script>
document.getElementById("formMedicao").addEventListener("submit", function (e) {
  e.preventDefault();

  const sensorId = document.getElementById("sensor").value;
  const medicao = document.getElementById("medicao").value;
  const status = document.getElementById("status");

  if (!sensorId || !medicao) {
    status.textContent = "Preencha todos os campos.";
    return;
  }

  const client = new Paho.MQTT.Client("broker.hivemq.com", 8000, "cliente_" + Math.random());

  client.connect({
    onSuccess: () => {
      const payload = JSON.stringify({
        sensor_id: parseInt(sensorId),
        medicao: parseFloat(medicao)
      });

      const message = new Paho.MQTT.Message(payload);
      message.destinationName = "isadora"; // mesmo tópico usado no Node-RED
      client.send(message);

      status.textContent = "Medição enviada com sucesso!";
    },
    onFailure: () => {
      status.textContent = "Erro ao conectar ao broker MQTT.";
    }
  });
});
</script>



<?php include_once './include/footer.php'; ?>
