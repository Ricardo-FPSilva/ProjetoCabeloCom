// A variável 'saidas' será preenchida com os dados da API.
let saidas = [];
let mostrarTudo = false;

// Caminho absoluto para a nossa API, que já corrigimos no passo anterior.
const API_URL = "../../api.php"; // Caminho para a nossa API a partir da pasta 'saidas'
const historicoCard = document.getElementById("historico-card");

/**
 * Busca o histórico de saídas na API e atualiza a tela.
 */
async function carregarSaidas() {
    try {
        const response = await fetch(`${API_URL}?action=get_saidas`);
        if (!response.ok) {
            throw new Error('A resposta da rede não foi bem-sucedida.');
        }
        saidas = await response.json();
        // Garante que o histórico seja exibido assim que os dados forem carregados.
        atualizarHistorico();
    } catch (error) {
        console.error("Falha ao carregar o histórico de saídas:", error);
        alert("Não foi possível carregar o histórico de saídas.");
    }
}

/**
 * Envia o registro de uma nova saída para a API.
 */
async function registrarSaida() {
    const valorInput = document.getElementById("valor");
    const descInput = document.getElementById("descricao");

    const valor = parseFloat(valorInput.value);
    const descricao = descInput.value.trim();

    if (isNaN(valor) || valor <= 0) {
        alert("Informe um valor válido!");
        return;
    }

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'add_saida',
                valor: valor,
                descricao: descricao
            })
        });

        const result = await response.json();

        if (result.success) {
            // Limpa os campos do formulário
            valorInput.value = "";
            descInput.value = "";
            // Recarrega os dados da API para garantir que a lista esteja 100% atualizada
            await carregarSaidas();
        } else {
            alert("Falha ao registrar a saída: " + result.message);
        }
    } catch (error) {
        console.error("Erro ao registrar saída:", error);
        alert("Ocorreu um erro de comunicação ao tentar registrar a saída.");
    }
}

/**
 * A função de renderização permanece a mesma.
 * Ela apenas desenha na tela o que estiver no array 'saidas'.
 */
function atualizarHistorico() {
    const lista = document.getElementById("lista-saidas");
    const totalDiv = document.getElementById("total-saidas");
    lista.innerHTML = "";

    let total = 0;
    // O array 'saidas' pode estar vazio se o json estiver vazio
    if (Array.isArray(saidas)) {
        saidas.forEach((s) => (total += s.valor));

        const saidasExibidas = mostrarTudo ? saidas : saidas.slice(-3);

        saidasExibidas.slice().reverse().forEach((saida) => {
            const item = document.createElement("div");
            item.className = "saida";
            item.innerHTML = `
                <strong>R$ ${saida.valor.toFixed(2)}</strong><br>
                ${saida.descricao || "<em>Sem descrição</em>"}
            `;
            lista.appendChild(item);
        });
    }

    totalDiv.innerText = `Total: R$ ${total.toFixed(2)}`;
}

// A função de expandir/recolher o card não precisa de alterações.
function alternarExibicao() {
    if (saidas.length <= 3) {
        return;
    }
    mostrarTudo = !mostrarTudo;
    historicoCard.classList.toggle("expandido");
    // Opcional: Adicionar a lógica de alinhamento do body se desejar
    // document.body.style.alignItems = mostrarTudo ? "flex-start" : "center";
    atualizarHistorico();
}

// Quando o conteúdo da página carregar, chamamos a função para buscar os dados da API.
document.addEventListener('DOMContentLoaded', carregarSaidas);