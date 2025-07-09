let saidas = [];
let mostrarTudo = false;

const API_URL = "../../api.php";
const historicoCard = document.getElementById("historico-card");

async function carregarSaidas() {
    try {
        const response = await fetch(`${API_URL}?action=get_saidas`);
        if (!response.ok) {
            throw new Error('A resposta da rede não foi bem-sucedida.');
        }
        saidas = await response.json();
    
        atualizarHistorico();
    } catch (error) {
        console.error("Falha ao carregar o histórico de saídas:", error);
        alert("Não foi possível carregar o histórico de saídas.");
    }
}

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
            valorInput.value = "";
            descInput.value = "";
            await carregarSaidas();
        } else {
            alert("Falha ao registrar a saída: " + result.message);
        }
    } catch (error) {
        console.error("Erro ao registrar saída:", error);
        alert("Ocorreu um erro de comunicação ao tentar registrar a saída.");
    }
}

function atualizarHistorico() {
    const lista = document.getElementById("lista-saidas");
    const totalDiv = document.getElementById("total-saidas");
    lista.innerHTML = "";

    let total = 0;
    if (Array.isArray(saidas)) {
        saidas.forEach((s) => (total += s.valor));

        const saidasExibidas = mostrarTudo ? saidas : saidas.slice(0, 3); 

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

function alternarExibicao() {
    const historicoCard = document.getElementById("historico-card");
    const body = document.body;
    const isExpanded = historicoCard.classList.contains("expandido");

    if (isExpanded) {
        historicoCard.classList.remove("expandido");
        body.classList.remove("modal-aberto");

        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.style.opacity = '0';
            setTimeout(() => {
                backdrop.remove();
            }, 300);
        }

        mostrarTudo = false;
        atualizarHistorico(); 

    } else {

        mostrarTudo = true;
        atualizarHistorico(); 

        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop';
        backdrop.onclick = alternarExibicao;
        body.appendChild(backdrop);
        
        setTimeout(() => {
            backdrop.style.opacity = '1';
        }, 10);

        historicoCard.classList.add("expandido");
        body.classList.add("modal-aberto");
    }
}

document.addEventListener('DOMContentLoaded', carregarSaidas);