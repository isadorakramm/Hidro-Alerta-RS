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
    </select>
    <br><br>

    <label for="medicao">Valor da Medição (em metros):</label>
    <input type="number" id="medicao" step="0.01" required>
    <br><br>

    <button type="submit" id="btnEnviar">Enviar via MQTT</button>
  </form>

  <p id="status" style="margin-top: 1rem; font-weight: bold;"></p>
</main>

<!-- Biblioteca Paho MQTT -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.1.0/mqttws31.min.js"></script>

<script>
window.addEventListener("DOMContentLoaded", async () => {
  const select = document.getElementById("sensor");
  const form = document.getElementById("formMedicao");
  const status = document.getElementById("status");
  const btnEnviar = document.getElementById("btnEnviar");

  // Função para carregar sensores do servidor Node-RED
  async function carregarSensores() {
    try {
      const resp = await fetch("http://" + window.location.hostname + ":1880/dados/sensores");
      if (!resp.ok) throw new Error("Erro na resposta do servidor");

      const sensores = await resp.json();

      select.innerHTML = ""; // limpa o select

      if (sensores.length === 0) {
        select.innerHTML = "<option disabled>Nenhum sensor disponível</option>";
        return;
      }

      sensores.forEach(s => {
        const option = document.createElement("option");
        option.value = s.id;
        option.textContent = s.nome || `Sensor ${s.id}`;
        select.appendChild(option);
      });
    } catch (err) {
      console.error("Erro ao carregar sensores:", err);
      select.innerHTML = "<option disabled>Erro ao carregar sensores</option>";
    }
  }

  await carregarSensores();

  // Evento submit do formulário para enviar medição via MQTT
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const sensorId = select.value;
    const medicao = document.getElementById("medicao").value;

    if (!sensorId || !medicao) {
      status.textContent = "Por favor, preencha todos os campos.";
      status.style.color = "red";
      return;
    }

    btnEnviar.disabled = true;
    status.textContent = "Enviando medição...";
    status.style.color = "black";

    // Cria cliente MQTT
    const client = new Paho.MQTT.Client("broker.hivemq.com", 8000, "cliente_" + Math.floor(Math.random() * 10000));

    client.onConnectionLost = function (responseObject) {
      if (responseObject.errorCode !== 0) {
        console.error("Conexão MQTT perdida:", responseObject.errorMessage);
        status.textContent = "Conexão MQTT perdida: " + responseObject.errorMessage;
        status.style.color = "red";
        btnEnviar.disabled = false;
      }
    };

    client.connect({
      onSuccess: () => {
        const payload = JSON.stringify({
          sensor_id: parseInt(sensorId),
          medicao: parseFloat(medicao)
        });

        const message = new Paho.MQTT.Message(payload);
        message.destinationName = "isadora"; // tópico usado no Node-RED
        client.send(message);

        status.textContent = "Medição enviada com sucesso!";
        status.style.color = "green";
        btnEnviar.disabled = false;

        // Opcional: limpa o input após envio
        document.getElementById("medicao").value = "";
      },
      onFailure: (error) => {
        console.error("Erro ao conectar ao broker MQTT:", error.errorMessage);
        status.textContent = "Erro ao conectar ao broker MQTT.";
        status.style.color = "red";
        btnEnviar.disabled = false;
      }
    });
  });
});
</script>

<?php include_once './include/footer.php'; ?>
