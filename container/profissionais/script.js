// A variável 'profissionais' agora será preenchida com os dados vindos da API.
let profissionais = [];

const API_URL = '/ProjetoCabeloCom/api.php';

/**
 * Função principal que busca os dados na API e inicia a renderização.
 */
async function carregarProfissionais() {
    try {
        // Usamos 'fetch' para fazer uma requisição GET para a nossa API
        const response = await fetch(`${API_URL}?action=get_profissionais`);
        if (!response.ok) {
            throw new Error('Erro ao buscar dados da API.');
        }
        profissionais = await response.json(); // Armazena os dados recebidos na nossa variável global
        renderizar(); // Chama a função para desenhar os cards na tela
    } catch (error) {
        console.error("Falha ao carregar profissionais:", error);
        document.getElementById("lista-profissionais").innerHTML = "<p>Não foi possível carregar os dados. Verifique o console para mais detalhes.</p>";
    }
}

/**
 * Envia o registro de um novo vale para a API.
 */
async function registrarVale(input, index) {
    const valor = parseFloat(input.value);
    if (isNaN(valor) || valor <= 0) return;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'update_profissional_vale',
                index: index,
                vale: valor
            })
        });
        const result = await response.json();
        if (result.success) {
            // Se a API confirmar o sucesso, atualizamos os dados locais e a tela
            profissionais[index] = result.data;
            input.value = ''; // Limpa o campo de input
            renderizar();
        } else {
            alert('Falha ao registrar o vale: ' + result.message);
        }
    } catch (error) {
        console.error("Erro ao registrar vale:", error);
        alert('Ocorreu um erro de comunicação com o servidor.');
    }
}

/**
 * Envia o registro de um novo valor a receber para a API.
 */
async function registrarValor(input, index) {
    const valor = parseFloat(input.value);
    if (isNaN(valor) || valor <= 0) return;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'update_profissional_valor',
                index: index,
                valor: valor
            })
        });
        const result = await response.json();
        if (result.success) {
            profissionais[index] = result.data;
            input.value = ''; // Limpa o campo de input
            renderizar();
        } else {
            alert('Falha ao registrar o valor: ' + result.message);
        }
    } catch (error) {
        console.error("Erro ao registrar valor:", error);
        alert('Ocorreu um erro de comunicação com o servidor.');
    }
}

/**
 * Envia os dados editados de um profissional para a API.
 */
async function editarProfissional(index) {
    const prof = profissionais[index];
    const novoNome = prompt("Novo nome:", prof.nome);
    const novoServico = prompt("Novo serviço:", prof.servico);
    const novoStatus = prompt("Novo status (Ativo, Inativo):", prof.status);

    if (novoNome && novoServico && novoStatus) {
        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'update_profissional_dados',
                    index: index,
                    nome: novoNome,
                    servico: novoServico,
                    status: novoStatus
                })
            });
            const result = await response.json();
            if (result.success) {
                // Após editar, recarregamos todos os profissionais para garantir consistência
                carregarProfissionais();
            } else {
                alert('Falha ao editar profissional.');
            }
        } catch (error) {
            console.error("Erro ao editar profissional:", error);
            alert('Ocorreu um erro de comunicação com o servidor.');
        }
    }
}

/**
 * Envia um novo profissional para ser adicionado pela API.
 */
async function adicionarProfissional() {
    const nome = prompt("Nome do profissional:");
    const servico = prompt("Serviço oferecido:");
    if (nome && servico) {
        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'add_profissional',
                    nome: nome,
                    servico: servico
                })
            });
            const result = await response.json();
            if (result.success) {
                // Após adicionar, recarregamos a lista
                carregarProfissionais();
            } else {
                alert('Falha ao adicionar profissional.');
            }
        } catch (error) {
            console.error("Erro ao adicionar profissional:", error);
            alert('Ocorreu um erro de comunicação com o servidor.');
        }
    }
}


// A função renderizar permanece quase a mesma, apenas desenha o que está no array 'profissionais'
function renderizar() {
  const lista = document.getElementById("lista-profissionais");
  const msg = document.getElementById("mensagem-vazia");

  lista.innerHTML = "";

  if (profissionais.length === 0) {
    msg.style.display = "block";
  } else {
    msg.style.display = "none";
    profissionais.forEach((prof, i) => {
      const card = document.createElement("div");
      card.className = "card";
      card.innerHTML = `
                        <h2>👤 ${prof.nome}</h2>
                        <div class="info">🛠️ Serviços: ${prof.servico}</div>
                        <div class="detalhes">
                        <div class="info">📊 Status: <span class="status ${prof.status.toLowerCase()}">${prof.status}</span></div>
                        <div class="info">💰 Valor no dia: R$ <span class="valor-dia">${parseFloat(prof.valor).toFixed(2)}</span></div>
                        <div class="info">💳 Vales solicitados: R$ <span class="vales-dia">${parseFloat(prof.vales).toFixed(2)}</span></div>
                        <div class="info">
                            💵 Registrar Vale: <input type="number" onclick="event.stopPropagation()" onchange="registrarVale(this, ${i})" placeholder="R$" />
                        </div>
                        <div class="info">
                            🧮 Registrar Valor a Receber: <input type="number" onclick="event.stopPropagation()" onchange="registrarValor(this, ${i})" placeholder="R$" />
                        </div>
                        <div class="botoes">
                            <button class="editar" onclick="event.stopPropagation(); editarProfissional(${i})">✏️ Editar Dados</button>
                        </div>
                        </div>
                    `;
      card.addEventListener("click", () => {
        const jaAberto = card.classList.contains('aberto');
        document.querySelectorAll(".card").forEach((c) => c.classList.remove("aberto"));
        
        if(!jaAberto) {
            card.classList.add("aberto");
            setTimeout(() => {
                card.scrollIntoView({ behavior: "smooth", block: "center" });
            }, 200);
        }
      });
      lista.appendChild(card);
    });
  }
}

document.addEventListener('DOMContentLoaded', carregarProfissionais); 