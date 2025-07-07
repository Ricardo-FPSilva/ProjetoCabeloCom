  let profissionais = []; // Início sem profissionais

        function registrarVale(input, index) {
            const valor = parseFloat(input.value);
            if (!isNaN(valor)) {
                profissionais[index].vales += valor;
                renderizar();
            }
        }

        function registrarValor(input, index) {
            const valor = parseFloat(input.value);
            if (!isNaN(valor)) {
                profissionais[index].valor += valor;
                renderizar();
            }
        }

        function editarProfissional(index) {
            const novoNome = prompt("Novo nome:", profissionais[index].nome);
            const novoServico = prompt("Novo serviço:", profissionais[index].servico);
            const novoStatus = prompt("Novo status (Livre, Disponível, Indisponível):", profissionais[index].status);
            if (novoNome && novoServico && novoStatus) {
                profissionais[index].nome = novoNome;
                profissionais[index].servico = novoServico;
                profissionais[index].status = novoStatus;
                renderizar();
            }
        }

        function adicionarProfissional() {
            const nome = prompt("Nome do profissional:");
            const servico = prompt("Serviço oferecido:");
            if (nome && servico) {
                profissionais.push({
                    nome,
                    servico,
                    status: "Livre",
                    valor: 0,
                    vales: 0
                });
                renderizar();
            }
        }

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
                        <div class="info">💰 Valor no dia: R$ <span class="valor-dia">${prof.valor}</span></div>
                        <div class="info">💳 Vales solicitados: R$ <span class="vales-dia">${prof.vales}</span></div>
                        <div class="info">
                            💵 Registrar Vale: <input type="number" onchange="registrarVale(this, ${i})" placeholder="R$" />
                        </div>
                        <div class="info">
                            🧮 Registrar Valor a Receber: <input type="number" onchange="registrarValor(this, ${i})" placeholder="R$" />
                        </div>
                        <div class="botoes">
                            <button class="editar" onclick="event.stopPropagation(); editarProfissional(${i})">✏️ Editar Dados</button>
                        </div>
                        </div>
                    `;
                    card.addEventListener("click", () => {
                        document.querySelectorAll(".card").forEach((c) => c.classList.remove("aberto"));
                        card.classList.add("aberto");
                        setTimeout(() => {
                            card.scrollIntoView({ behavior: "smooth", block: "center" });
                        }, 200);
                    });
                    lista.appendChild(card);
                });
            }
        }

        // Chamada inicial
        renderizar();