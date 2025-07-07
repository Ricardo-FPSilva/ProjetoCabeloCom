let saidas = [];
let mostrarTudo = false;

// Get the history card element
const historicoCard = document.getElementById("historico-card");

function registrarSaida() {
  const valorInput = document.getElementById("valor");
  const descInput = document.getElementById("descricao");

  const valor = parseFloat(valorInput.value);
  const descricao = descInput.value.trim();

  if (isNaN(valor) || valor <= 0) {
    alert("Informe um valor válido!");
    return;
  }

  saidas.push({ valor, descricao });
  valorInput.value = "";
  descInput.value = "";
  atualizarHistorico();
}

function atualizarHistorico() {
  const lista = document.getElementById("lista-saidas");
  const totalDiv = document.getElementById("total-saidas");
  lista.innerHTML = "";

  let total = 0;
  saidas.forEach((s) => (total += s.valor));

  // Determine which items to display
  const saidasExibidas = mostrarTudo ? saidas : saidas.slice(-3);

  saidasExibidas
    .slice()
    .reverse()
    .forEach((saida) => {
      const item = document.createElement("div");
      item.className = "saida";
      item.innerHTML = `
                    <strong>R$ ${saida.valor.toFixed(2)}</strong><br>
                    ${saida.descricao || "<em>Sem descrição</em>"}
                `;
      lista.appendChild(item); // mais novo vai no topo
    });

  totalDiv.innerText = `Total: R$ ${total.toFixed(2)}`;
}

function alternarExibicao() {
  if (saidas.length <= 3) {
    return; // Impede a expansão se não houver mais de 3 saídas
  }

  if (mostrarTudo) {
    historicoCard.classList.add("colapsando");

    setTimeout(() => {
      historicoCard.classList.remove("expandido");
      historicoCard.classList.remove("colapsando");
      document.body.style.alignItems = "flex-start";
      mostrarTudo = false;
      atualizarHistorico();
    }, 300);
  } else {
    mostrarTudo = true;
    historicoCard.classList.add("expandido");
    document.body.style.alignItems = "center";
    atualizarHistorico();
  }
}

// Initial update when the page loads
atualizarHistorico();
