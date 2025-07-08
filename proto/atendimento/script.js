// atendimento/script.js

document.addEventListener('DOMContentLoaded', () => {
    // --- ELEMENTOS DO DOM ---
    const formCliente = document.getElementById('form-cliente');
    const secaoClientesEspera = document.getElementById('clientes-espera');
    const secaoProfissionais = document.getElementById('lista-profissionais');
    const selectProfissional = document.getElementById('selecao-profissional');
    const btnAtribuirCliente = document.getElementById('botao-atribuir-proximo');

    const msgSemClientes = document.getElementById('mensagem-sem-clientes-espera');
    const msgSemProfissionais = document.getElementById('mensagem-sem-profissionais');

    const API_URL = '../../api.php'; // Caminho relativo da API

    // --- FUNÇÕES DE RENDERIZAÇÃO ---

    // Cria e retorna o HTML para um card de cliente
    const criarCardCliente = (cliente) => {
        return `
            <div class="card-cliente" id="cliente-${cliente.id}">
                <p><strong>Nome:</strong> ${cliente.nome}</p>
                <p><strong>Procedimento:</strong> ${cliente.procedimento}</p>
                <p><strong>Deseja:</strong> ${cliente.tipo_profissional}</p>
            </div>
        `;
    };

    // Cria e retorna o HTML para um card de profissional em atendimento
    const criarCardProfissional = (atendimento) => {
        return `
            <div class="card-profissional" id="atendimento-${atendimento.id}" data-appointment-id="${atendimento.id}">
                <div class="info-profissional">
                    <p><strong>${atendimento.profissional.nome}</strong> (${atendimento.profissional.funcao})</p>
                </div>
                <div class="cliente-atendido">
                    <p class="titulo-cliente-atendido">Atendendo:</p>
                    ${criarCardCliente(atendimento.cliente)}
                </div>
                <button class="botao-finalizar">
                    <i class="fas fa-check"></i> Finalizar Atendimento
                </button>
            </div>
        `;
    };

    // Atualiza toda a tela com os dados mais recentes do servidor
    const atualizarTela = async () => {
        try {
            const response = await fetch(`${API_URL}?action=obter_estado_inicial`);
            const estado = await response.json();

            // 1. Renderizar a lista de espera
            secaoClientesEspera.innerHTML = ''; // Limpa a lista atual
            if (estado.clientes_espera && estado.clientes_espera.length > 0) {
                estado.clientes_espera.forEach(cliente => {
                    secaoClientesEspera.innerHTML += criarCardCliente(cliente);
                });
                msgSemClientes.style.display = 'none';
            } else {
                secaoClientesEspera.appendChild(msgSemClientes);
                msgSemClientes.style.display = 'block';
            }

            // 2. Renderizar a lista de profissionais em atendimento
            secaoProfissionais.innerHTML = ''; // Limpa a lista atual
            if (estado.atendimentos && estado.atendimentos.length > 0) {
                estado.atendimentos.forEach(atendimento => {
                    secaoProfissionais.innerHTML += criarCardProfissional(atendimento);
                });
                msgSemProfissionais.style.display = 'none';
            } else {
                secaoProfissionais.appendChild(msgSemProfissionais);
                msgSemProfissionais.style.display = 'block';
            }

            // 3. Popular o dropdown de profissionais
            selectProfissional.innerHTML = '<option value="">Selecione um profissional</option>';
            if (estado.profissionais && estado.profissionais.length > 0) {
                 // Filtra para mostrar apenas profissionais que não estão em atendimento
                const profissionaisOcupadosIds = new Set(estado.atendimentos.map(a => a.profissional.id));
                const profissionaisDisponiveis = estado.profissionais.filter(p => !profissionaisOcupadosIds.has(p.id));

                profissionaisDisponiveis.forEach(prof => {
                    const option = document.createElement('option');
                    option.value = prof.id;
                    option.textContent = `${prof.nome} - ${prof.funcao}`;
                    selectProfissional.appendChild(option);
                });
            }

        } catch (error) {
            console.error('Erro ao atualizar a tela:', error);
            alert('Não foi possível carregar os dados do servidor.');
        }
    };

    // --- EVENT LISTENERS ---

    // Envio do formulário de novo cliente
    formCliente.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(formCliente);
        
        try {
            const response = await fetch(`${API_URL}?action=adicionar_cliente`, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                formCliente.reset();
                atualizarTela(); // Recarrega tudo para garantir consistência
            } else {
                alert('Erro ao adicionar cliente: ' + (result.message || 'Erro desconhecido'));
            }
        } catch (error) {
            console.error('Erro na requisição:', error);
            alert('Erro de comunicação com o servidor.');
        }
    });

    // Clique no botão de atribuir cliente
    btnAtribuirCliente.addEventListener('click', async () => {
        const professionalId = selectProfissional.value;
        if (!professionalId) {
            alert('Por favor, selecione um profissional.');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('professionalId', professionalId);

            const response = await fetch(`${API_URL}?action=atribuir_cliente`, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                atualizarTela();
            } else {
                alert('Erro ao atribuir cliente: ' + (result.message || 'Verifique se há clientes na fila.'));
            }
        } catch (error) {
            console.error('Erro na requisição:', error);
            alert('Erro de comunicação com o servidor.');
        }
    });
    
    // Clique no botão de finalizar (usando delegação de evento)
    secaoProfissionais.addEventListener('click', async (e) => {
        const finalizarButton = e.target.closest('.botao-finalizar');
        if (finalizarButton) {
            const card = finalizarButton.closest('.card-profissional');
            const appointmentId = card.dataset.appointmentId;

            if (confirm('Deseja realmente finalizar este atendimento?')) {
                 try {
                    const formData = new FormData();
                    formData.append('appointmentId', appointmentId);
                    
                    const response = await fetch(`${API_URL}?action=finalizar_atendimento`, {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();

                    if(result.success) {
                        atualizarTela();
                    } else {
                        alert('Erro ao finalizar atendimento: ' + (result.message || 'Erro desconhecido'));
                    }
                } catch (error) {
                    console.error('Erro na requisição:', error);
                    alert('Erro de comunicação com o servidor.');
                }
            }
        }
    });


    // --- INICIALIZAÇÃO ---
    atualizarTela();
});