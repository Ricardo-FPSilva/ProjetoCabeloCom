// Variáveis globais para armazenar dados de clientes e profissionais (persistidos via localStorage).
let clients = [];
let professionals = [];

// Referências aos elementos HTML (DOM) para manipulação via JavaScript.
const clientForm = document.getElementById("form-cliente");
const professionalForm = document.getElementById("form-profissional");
const waitingClientsContainer = document.getElementById("clientes-espera");
const professionalsListContainer = document.getElementById(
  "lista-profissionais"
);
const noWaitingClientsMessage = document.getElementById(
  "mensagem-sem-clientes-espera"
);
const noProfessionalsMessage = document.getElementById(
  "mensagem-sem-profissionais"
);
const messageBox = document.getElementById("caixa-mensagem");
const messageContent = document.getElementById("conteudo-mensagem");
const messageOkButton = document.getElementById("botao-ok-mensagem");
const assignNextClientBtn = document.getElementById("botao-atribuir-proximo");
const menuToggle = document.getElementById("menu-alternar");
const mobileSidebar = document.getElementById("menu-lateral-mobile");
const closeMenu = document.getElementById("fechar-menu");

// Referências para o novo modal de edição de cliente
const editClientModal = document.getElementById("modal-editar-cliente");
const editClientForm = document.getElementById("form-editar-cliente");
const editClientIdInput = document.getElementById("edit-client-id");
const editClientNameInput = document.getElementById("edit-nome-cliente");
const editClientProcedureInput = document.getElementById(
  "edit-procedimento-cliente"
);
const editClientProfessionalTypeInput = document.getElementById(
  "edit-tipo-profissional-cliente"
);
const saveEditButton = document.getElementById("botao-salvar-edicao");
const cancelEditButton = document.getElementById("botao-cancelar-edicao");

// --- Funções Utilitárias ---

/**
 * Exibe uma caixa de mensagem modal personalizada.
 * @param {string} message - A mensagem a ser exibida.
 */
function showMessageBox(message) {
  messageContent.textContent = message;
  messageBox.classList.remove("oculto");
}

/**
 * Oculta a caixa de mensagem modal.
 */
function hideMessageBox() {
  messageBox.classList.add("oculto");
}

// --- Persistência de Dados (usando localStorage para protótipo) ---

/**
 * Carrega os dados de clientes e profissionais do localStorage do navegador.
 */
function loadData() {
  const storedClients = localStorage.getItem("clients");
  const storedProfessionals = localStorage.getItem("professionals");

  if (storedClients) {
    clients = JSON.parse(storedClients);
  }
  if (storedProfessionals) {
    professionals = JSON.parse(storedProfessionals);
  }
}

/**
 * Salva os dados de clientes e profissionais no localStorage do navegador.
 */
function saveData() {
  localStorage.setItem("clients", JSON.stringify(clients));
  localStorage.setItem("professionals", JSON.stringify(professionals));
}

// --- Funções de Renderização da Interface do Usuário (UI) ---

/**
 * Cria e retorna um elemento HTML (card) para um cliente.
 * Inclui o nome, procedimento e botões de ação (atribuir, remover, editar).
 * Configura o card para ser arrastável (drag-and-drop).
 * @param {object} client - O objeto cliente.
 * @returns {HTMLElement} O elemento div do card do cliente.
 */
function createClientCard(client) {
  const clientCard = document.createElement("div");
  clientCard.id = `client-${client.id}`;
  clientCard.classList.add("cartao-cliente", "arrastavel");
  clientCard.setAttribute("draggable", "true");
  clientCard.dataset.clientId = client.id;

  clientCard.innerHTML = `
                <div>
                    <p class="nome-cliente">${client.name}</p>
                    <p class="procedimento-cliente">${client.procedure} (${client.professionalType})</p>
                </div>
                <div class="acoes-cliente">
                    <button class="botao-atribuir-cliente" data-client-id="${client.id}">
                        <i class="fas fa-user-plus"></i> Atribuir
                    </button>
                    <button class="botao-editar-cliente" data-client-id="${client.id}">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="botao-remover-cliente" data-client-id="${client.id}">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
            `;

  clientCard
    .querySelector(".botao-remover-cliente")
    .addEventListener("click", (e) => {
      const clientIdToRemove = e.currentTarget.dataset.clientId;
      removeClient(clientIdToRemove);
    });

  clientCard
    .querySelector(".botao-atribuir-cliente")
    .addEventListener("click", (e) => {
      const clientIdToAssign = e.currentTarget.dataset.clientId;
      assignClientToProfessional(clientIdToAssign);
    });

  clientCard
    .querySelector(".botao-editar-cliente")
    .addEventListener("click", (e) => {
      const clientIdToEdit = e.currentTarget.dataset.clientId;
      editClient(clientIdToEdit);
    });

  clientCard.addEventListener("dragstart", handleDragStart);
  clientCard.addEventListener("dragend", handleDragEnd);

  return clientCard;
}

/**
 * Renderiza todos os clientes na fila de espera, limpando o contêiner
 * e adicionando os cards dos clientes com status 'waiting'.
 */
function renderWaitingClients() {
  waitingClientsContainer.innerHTML = "";
  const waitingClients = clients.filter((c) => c.status === "waiting");

  if (waitingClients.length === 0) {
    noWaitingClientsMessage.classList.remove("oculto");
    waitingClientsContainer.appendChild(noWaitingClientsMessage);
  } else {
    noWaitingClientsMessage.classList.add("oculto");
    waitingClients.forEach((client) => {
      waitingClientsContainer.appendChild(createClientCard(client));
    });
  }
}

/**
 * Cria e retorna um elemento HTML (card) para um profissional.
 * Exibe nome, função, status (com cor e texto), e um botão para alternar pausa.
 * Inclui áreas para o cliente atual e a fila de clientes do profissional.
 * @param {object} professional - O objeto profissional.
 * @returns {HTMLElement} O elemento div do card do profissional.
 */
function createProfessionalCard(professional) {
  const professionalCard = document.createElement("div");
  professionalCard.id = `professional-${professional.id}`;
  professionalCard.classList.add("cartao-profissional");
  professionalCard.dataset.professionalId = professional.id;

  let statusColor = "";
  let statusText = "";
  switch (professional.status) {
    case "total-livre":
      statusColor = "status-livre";
      statusText = "Totalmente Livre";
      break;
    case "livre-parcial":
      statusColor = "status-parcial";
      statusText = "Livre Parcialmente";
      break;
    case "ocupado":
      statusColor = "status-ocupado";
      statusText = "Ocupado";
      break;
    case "em-pausa":
      statusColor = "status-pausa";
      statusText = "Em Pausa";
      break;
  }

  professionalCard.innerHTML = `
                <div class="cabecalho-profissional">
                    <h3 class="nome-profissional">${professional.name}</h3>
                    <button class="botao-remover-profissional" data-professional-id="${
                      professional.id
                    }">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
                <p class="funcao-profissional">${professional.function}</p>
                <div class="status-e-toggle">
                    <span class="emblema-status ${statusColor}">${statusText}</span>
                    <button class="botao-alternar-pausa" data-professional-id="${
                      professional.id
                    }">
                        <i class="fas ${
                          professional.manualPause
                            ? "fa-play-circle"
                            : "fa-pause-circle"
                        }"></i>
                        ${professional.manualPause ? "Retomar" : "Pausar"}
                    </button>
                </div>

                <div class="area-cliente-atual zona-soltar-item" data-professional-id="${
                  professional.id
                }" data-queue-type="current">
                    ${
                      professional.currentClient
                        ? ""
                        : '<span class="texto-soltar">Arraste o cliente para atender</span>'
                    }
                    ${
                      professional.currentClient
                        ? `<div class="cliente-em-atendimento" data-client-id="${professional.currentClient}"></div>`
                        : ""
                    }
                    ${
                      professional.currentClient
                        ? `<button class="botao-finalizar-servico" data-professional-id="${professional.id}">Finalizar Atendimento</button>`
                        : ""
                    }
                </div>
                <div class="area-fila-espera zona-soltar-item fila-profissional" data-professional-id="${
                  professional.id
                }" data-queue-type="queue">
                    <p class="texto-soltar ${
                      professional.queue.length > 0 ? "oculto" : ""
                    }">Clientes na fila</p>
                </div>
            `;

  professionalCard
    .querySelector(".botao-remover-profissional")
    .addEventListener("click", (e) => {
      const professionalIdToRemove = e.currentTarget.dataset.professionalId;
      removeProfessional(professionalIdToRemove);
    });

  const finishServiceBtn = professionalCard.querySelector(
    ".botao-finalizar-servico"
  );
  if (finishServiceBtn) {
    finishServiceBtn.addEventListener("click", (e) => {
      const professionalId = e.currentTarget.dataset.professionalId;
      finishClientService(professionalId);
    });
  }

  professionalCard
    .querySelector(".botao-alternar-pausa")
    .addEventListener("click", (e) => {
      const professionalId = e.currentTarget.dataset.professionalId;
      toggleProfessionalPause(professionalId);
    });

  const currentClientArea = professionalCard.querySelector(
    ".area-cliente-atual"
  );
  currentClientArea.addEventListener("dragover", handleDragOver);
  currentClientArea.addEventListener("dragleave", handleDragLeave);
  currentClientArea.addEventListener("drop", handleDrop);

  const waitingQueueArea = professionalCard.querySelector(".area-fila-espera");
  waitingQueueArea.addEventListener("dragover", handleDragOver);
  waitingQueueArea.addEventListener("dragleave", handleDragLeave);
  waitingQueueArea.addEventListener("drop", handleDrop);

  return professionalCard;
}

/**
 * Renderiza todos os profissionais e seus clientes atribuídos (atuais e em fila),
 * limpando o contêiner e recriando os cards dos profissionais.
 */
function renderProfessionals() {
  professionalsListContainer.innerHTML = "";
  if (professionals.length === 0) {
    noProfessionalsMessage.classList.remove("oculto");
    professionalsListContainer.appendChild(noProfessionalsMessage);
  } else {
    noProfessionalsMessage.classList.add("oculto");
    professionals.forEach((professional) => {
      const professionalCard = createProfessionalCard(professional);
      professionalsListContainer.appendChild(professionalCard);

      const currentClientDiv = professionalCard.querySelector(
        ".cliente-em-atendimento"
      );
      if (professional.currentClient && currentClientDiv) {
        const client = clients.find((c) => c.id === professional.currentClient);
        if (client) {
          currentClientDiv.appendChild(createClientCard(client));
        }
      }

      const waitingQueueArea =
        professionalCard.querySelector(".area-fila-espera");
      if (professional.queue.length > 0) {
        waitingQueueArea.querySelector(".texto-soltar").classList.add("oculto");
        professional.queue.forEach((clientId) => {
          const client = clients.find((c) => c.id === clientId);
          if (client) {
            waitingQueueArea.appendChild(createClientCard(client));
          }
        });
      }
    });
  }
}

/**
 * Atualiza o status lógico e visual de um profissional, considerando
 * se ele está em pausa manual, atendendo um cliente, ou tem clientes na fila.
 * @param {string} professionalId - O ID do profissional.
 */
function updateProfessionalStatus(professionalId) {
  const professional = professionals.find((p) => p.id === professionalId);
  if (!professional) return;

  let newStatus = "";
  if (professional.manualPause) {
    newStatus = "em-pausa";
  } else if (professional.currentClient) {
    newStatus = "ocupado";
  } else if (professional.queue.length > 0) {
    newStatus = "livre-parcial";
  } else {
    newStatus = "total-livre";
  }
  professional.status = newStatus;

  const professionalCardElement = document.getElementById(
    `professional-${professionalId}`
  );
  if (professionalCardElement) {
    const statusBadge =
      professionalCardElement.querySelector(".emblema-status");
    statusBadge.classList.remove(
      "status-livre",
      "status-parcial",
      "status-ocupado",
      "status-pausa"
    );
    let statusText = "";
    switch (newStatus) {
      case "total-livre":
        statusBadge.classList.add("status-livre");
        statusText = "Totalmente Livre";
        break;
      case "livre-parcial":
        statusBadge.classList.add("status-parcial");
        statusText = "Livre Parcialmente";
        break;
      case "ocupado":
        statusBadge.classList.add("status-ocupado");
        statusText = "Ocupado";
        break;
      case "em-pausa":
        statusBadge.classList.add("status-pausa");
        statusText = "Em Pausa";
        break;
    }
    statusBadge.textContent = statusText;
  }
  saveData();
}

// --- Funções de Gerenciamento de Clientes ---

/**
 * Adiciona um novo cliente à lista de espera, incluindo o tipo de profissional desejado.
 * @param {string} name - Nome do cliente.
 * @param {string} procedure - Procedimento solicitado.
 * @param {string} professionalType - Tipo de profissional desejado.
 */
function addClient(name, procedure, professionalType) {
  const newClient = {
    id: Date.now().toString(),
    name: name,
    procedure: procedure,
    professionalType: professionalType,
    status: "waiting",
  };
  clients.push(newClient);
  saveData();
  renderWaitingClients();
  showMessageBox('Cliente "' + name + '" adicionado com sucesso!');
}

/**
 * Remove um cliente do sistema. Impede a remoção se o cliente
 * estiver sendo atendido por um profissional.
 * @param {string} clientIdToRemove - O ID do cliente a ser removido.
 */
function removeClient(clientIdToRemove) {
  const professionalWithClient = professionals.find(
    (p) => p.currentClient === clientIdToRemove
  );
  if (professionalWithClient) {
    showMessageBox(
      "Não é possível remover o cliente enquanto ele está sendo atendido por um profissional. Finalize o atendimento primeiro."
    );
    return;
  }

  clients = clients.filter((c) => c.id !== clientIdToRemove);

  professionals.forEach((p) => {
    p.queue = p.queue.filter((id) => id !== clientIdToRemove);
    updateProfessionalStatus(p.id);
  });

  saveData();
  renderWaitingClients();
  renderProfessionals();
  showMessageBox("Cliente removido com sucesso!");
}

/**
 * Preenche o modal de edição com os dados do cliente e o exibe.
 * @param {string} clientId - O ID do cliente a ser editado.
 */
function editClient(clientId) {
  console.log("Iniciando edição para o cliente ID:", clientId);
  const client = clients.find((c) => c.id === clientId);
  if (!client) {
    showMessageBox("Cliente não encontrado para edição.");
    console.error("Erro: Cliente não encontrado para edição. ID:", clientId);
    return;
  }

  editClientIdInput.value = client.id;
  editClientNameInput.value = client.name;
  editClientProcedureInput.value = client.procedure;
  editClientProfessionalTypeInput.value = client.professionalType;
  console.log("Dados do cliente preenchidos no modal:", client);

  editClientModal.classList.remove("oculto");
}

/**
 * Salva as edições de um cliente.
 * Encontra o cliente pelo ID e atualiza suas propriedades.
 */
function saveEditedClient() {
  const clientId = editClientIdInput.value;
  const clientIndex = clients.findIndex((c) => c.id === clientId);
  console.log("Tentando salvar edição para o cliente ID:", clientId);

  if (clientIndex === -1) {
    showMessageBox("Erro: Cliente não encontrado para salvar edição.");
    console.error(
      "Erro: Cliente não encontrado no array para salvar edição. ID:",
      clientId
    );
    return;
  }

  const updatedName = editClientNameInput.value.trim();
  const updatedProcedure = editClientProcedureInput.value.trim();
  const updatedProfessionalType = editClientProfessionalTypeInput.value;

  if (!updatedName || !updatedProcedure || !updatedProfessionalType) {
    showMessageBox(
      "Por favor, preencha todos os campos para editar o cliente."
    );
    console.warn("Campos de edição vazios.");
    return;
  }

  clients[clientIndex].name = updatedName;
  clients[clientIndex].procedure = updatedProcedure;
  clients[clientIndex].professionalType = updatedProfessionalType;
  console.log("Cliente atualizado no array:", clients[clientIndex]);

  saveData();
  renderAll(); // Re-renderiza tudo para refletir as mudanças
  hideEditClientModal();
  showMessageBox("Cliente editado com sucesso!");
}

/**
 * Oculta o modal de edição de cliente.
 */
function hideEditClientModal() {
  editClientModal.classList.add("oculto");
  editClientForm.reset(); // Limpa o formulário do modal
  console.log("Modal de edição ocultado.");
}

// --- Funções de Gerenciamento de Profissionais ---

/**
 * Adiciona um novo profissional ao sistema.
 * @param {string} name - Nome do profissional.
 * @param {string} func - Função do profissional.
 */
function addProfessional(name, func) {
  const newProfessional = {
    id: Date.now().toString(),
    name: name,
    function: func,
    status: "total-livre",
    manualPause: false,
    currentClient: null,
    queue: [],
    lastFinishedTime: Date.now(),
  };
  professionals.push(newProfessional);
  saveData();
  renderProfessionals();
  showMessageBox('Profissional "' + name + '" adicionado com sucesso!');
}

/**
 * Remove um profissional do sistema. Impede a remoção se ele
 * tiver clientes em atendimento ou na fila.
 * @param {string} professionalIdToRemove - O ID do profissional.
 */
function removeProfessional(professionalIdToRemove) {
  const professional = professionals.find(
    (p) => p.id === professionalIdToRemove
  );
  if (!professional) return;

  if (professional.currentClient || professional.queue.length > 0) {
    showMessageBox(
      "Não é possível remover o profissional enquanto ele tem clientes sendo atendidos ou na fila."
    );
    return;
  }

  professionals = professionals.filter((p) => p.id !== professionalIdToRemove);
  saveData();
  renderProfessionals();
  showMessageBox("Profissional removido com sucesso!");
}

/**
 * Alterna o status de pausa manual de um profissional. Impede a pausa
 * se o profissional estiver atendendo ou tiver clientes na fila.
 * @param {string} professionalId - O ID do profissional.
 */
function toggleProfessionalPause(professionalId) {
  const professional = professionals.find((p) => p.id === professionalId);
  if (!professional) return;

  if (professional.currentClient || professional.queue.length > 0) {
    showMessageBox(
      "Não é possível colocar o profissional em pausa enquanto ele tem clientes sendo atendidos ou na fila."
    );
    return;
  }

  professional.manualPause = !professional.manualPause;
  updateProfessionalStatus(professional.id);
  renderProfessionals();
  showMessageBox(
    `Profissional "${professional.name}" está agora ${
      professional.manualPause ? "Em Pausa" : "Disponível"
    }.`
  );
  console.log(
    `Profissional ${professional.name} (ID: ${professional.id}) status de pausa alterado para: ${professional.manualPause}`
  );
}

/**
 * Finaliza o atendimento de um cliente por um profissional.
 * Move o próximo cliente da fila do profissional para o atendimento atual, se houver.
 * @param {string} professionalId - O ID do profissional.
 */
function finishClientService(professionalId) {
  const professional = professionals.find((p) => p.id === professionalId);
  if (!professional || !professional.currentClient) {
    showMessageBox("Nenhum cliente em atendimento para finalizar.");
    return;
  }

  const clientFinishedId = professional.currentClient;
  const clientFinished = clients.find((c) => c.id === clientFinishedId);

  clients = clients.filter((c) => c.id !== clientFinishedId);

  professional.currentClient = null;
  professional.lastFinishedTime = Date.now();

  if (professional.queue.length > 0) {
    const nextClientId = professional.queue.shift();
    professional.currentClient = nextClientId;
    const nextClient = clients.find((c) => c.id === nextClientId);
    if (nextClient) {
      nextClient.status = "in-service";
      showMessageBox(
        `Atendimento de "${clientFinished?.name || "Cliente"}" finalizado. "${
          nextClient.name
        }" agora está sendo atendido por "${professional.name}".`
      );
    }
  } else {
    showMessageBox(
      `Atendimento de "${
        clientFinished?.name || "Cliente"
      }" finalizado. Profissional "${professional.name}" está livre.`
    );
  }

  saveData();
  renderAll();
  console.log(
    `Serviço finalizado para o profissional ${
      professional.name
    }. Próximo cliente: ${
      professional.currentClient
        ? clients.find((c) => c.id === professional.currentClient)?.name
        : "Nenhum"
    }.`
  );
}

/**
 * Encontra o próximo profissional disponível, priorizando 'total-livre',
 * depois 'livre-parcial', e então o que finalizou o serviço há mais tempo.
 * Filtra por tipo de profissional desejado, se fornecido.
 * Exclui profissionais 'ocupado' e 'em-pausa'.
 * @param {string} [requiredType] - O tipo de profissional necessário (opcional).
 * @returns {object|null} O profissional mais disponível ou null.
 */
function findNextAvailableProfessional(requiredType = null) {
  let availableProfessionals = professionals.filter(
    (p) => p.status !== "ocupado" && p.status !== "em-pausa"
  );
  console.log(
    "Profissionais disponíveis (antes de filtrar por tipo):",
    availableProfessionals.map((p) => ({
      name: p.name,
      status: p.status,
      function: p.function,
    }))
  );

  if (requiredType) {
    availableProfessionals = availableProfessionals.filter(
      (p) => p.function === requiredType
    );
    console.log(
      `Profissionais disponíveis (filtrados por tipo '${requiredType}'):`,
      availableProfessionals.map((p) => ({
        name: p.name,
        status: p.status,
        function: p.function,
      }))
    );
  }

  if (availableProfessionals.length === 0) {
    console.log("Nenhum profissional disponível após filtros.");
    return null;
  }

  availableProfessionals.sort((a, b) => {
    if (a.status === "total-livre" && b.status !== "total-livre") return -1;
    if (b.status === "total-livre" && a.status !== "total-livre") return 1;
    return a.lastFinishedTime - b.lastFinishedTime;
  });
  console.log(
    "Próximo profissional disponível (após ordenação):",
    availableProfessionals[0]?.name,
    "(ID:",
    availableProfessionals[0]?.id,
    ")"
  );
  return availableProfessionals[0];
}

/**
 * Atribui um cliente específico ao próximo profissional disponível que corresponda ao seu tipo de serviço.
 * @param {string} clientId - O ID do cliente a ser atribuído.
 */
function assignClientToProfessional(clientId) {
  const clientToAssign = clients.find((c) => c.id === clientId);
  console.log("Tentando atribuir cliente:", clientToAssign);

  if (!clientToAssign) {
    showMessageBox("Cliente não encontrado.");
    console.error(
      "Erro: Cliente não encontrado para atribuição. ID:",
      clientId
    );
    return;
  }

  if (clientToAssign.status !== "waiting") {
    showMessageBox(
      "Este cliente já está em atendimento ou na fila de um profissional."
    );
    console.warn(
      "Cliente já atribuído ou em serviço. Status:",
      clientToAssign.status
    );
    return;
  }

  const nextAvailableProfessional = findNextAvailableProfessional(
    clientToAssign.professionalType
  );
  if (!nextAvailableProfessional) {
    showMessageBox(
      `Nenhum profissional '${clientToAssign.professionalType}' disponível no momento para atender "${clientToAssign.name}".`
    );
    console.warn(
      `Nenhum profissional '${clientToAssign.professionalType}' disponível para o cliente '${clientToAssign.name}'.`
    );
    return;
  }

  // Remove o cliente da lista de espera principal
  clients = clients.filter((c) => c.id !== clientToAssign.id);

  if (!nextAvailableProfessional.currentClient) {
    nextAvailableProfessional.currentClient = clientToAssign.id;
    clientToAssign.status = "in-service";
    showMessageBox(
      `Cliente "${clientToAssign.name}" atribuído a "${nextAvailableProfessional.name}" para atendimento.`
    );
    console.log(
      `Cliente ${clientToAssign.name} atribuído diretamente a ${nextAvailableProfessional.name}.`
    );
  } else {
    nextAvailableProfessional.queue.push(clientToAssign.id);
    clientToAssign.status = "waiting-professional";
    showMessageBox(
      `Cliente "${clientToAssign.name}" adicionado à fila de "${nextAvailableProfessional.name}".`
    );
    console.log(
      `Cliente ${clientToAssign.name} adicionado à fila de ${nextAvailableProfessional.name}.`
    );
  }
  saveData();
  renderAll();
}

/**
 * Atribui o próximo cliente da fila de espera ao profissional mais disponível
 * que corresponda ao tipo de serviço do cliente.
 */
function assignNextWaitingClient() {
  const waitingClients = clients.filter((c) => c.status === "waiting");
  console.log(
    "Clientes em espera para atribuição automática:",
    waitingClients.map((c) => c.name)
  );

  if (waitingClients.length === 0) {
    showMessageBox("Não há clientes em espera para atribuir.");
    return;
  }

  const nextClient = waitingClients[0];
  assignClientToProfessional(nextClient.id);
}

// --- Lógica de Arrastar e Soltar (Drag and Drop) ---

let draggedClientCard = null;

/**
 * Manipula o início do arraste de um card de cliente.
 * @param {Event} e - O evento de arraste.
 */
function handleDragStart(e) {
  draggedClientCard = e.target;
  e.dataTransfer.setData("text/plain", e.target.dataset.clientId);
  e.target.classList.add("arrastando");
  console.log("Drag iniciado para o cliente ID:", e.target.dataset.clientId);
}

/**
 * Manipula o fim do arraste de um card de cliente.
 * @param {Event} e - O evento de arraste.
 */
function handleDragEnd(e) {
  e.target.classList.remove("arrastando");
  draggedClientCard = null;
  console.log("Drag finalizado.");
}

/**
 * Permite que um elemento seja solto sobre uma zona de soltar.
 * Impede o arraste sobre profissionais em pausa ou tipo incompatível.
 * @param {Event} e - O evento de arraste.
 */
function handleDragOver(e) {
  e.preventDefault();
  const targetProfessionalId = e.target.closest(".cartao-profissional")?.dataset
    .professionalId;
  const professional = professionals.find((p) => p.id === targetProfessionalId);
  const clientId = e.dataTransfer.getData("text/plain");
  const client = clients.find((c) => c.id === clientId);

  // Log para depuração
  // console.log('DragOver - Cliente:', client?.name, 'Tipo Cliente:', client?.professionalType, 'Profissional Alvo:', professional?.name, 'Função Profissional:', professional?.function, 'Pausa Manual:', professional?.manualPause);

  if (professional && professional.manualPause) {
    e.dataTransfer.dropEffect = "none";
    // console.log('DragOver - Drop impedido: Profissional em pausa.');
    return;
  }

  if (
    professional &&
    client &&
    professional.function !== client.professionalType
  ) {
    e.dataTransfer.dropEffect = "none";
    // console.log('DragOver - Drop impedido: Tipo de profissional incompatível.');
    return;
  }

  if (e.target.classList.contains("zona-soltar-item")) {
    e.target.classList.add("zona-arrasto-ativa");
  }
}

/**
 * Remove o efeito visual quando o item arrastado sai de uma zona de soltar.
 * @param {Event} e - O evento de arraste.
 */
function handleDragLeave(e) {
  if (e.target.classList.contains("zona-soltar-item")) {
    e.target.classList.remove("zona-arrasto-ativa");
  }
}

/**
 * Processa a ação de soltar um cliente em uma zona de soltar.
 * Gerencia a movimentação do cliente entre a fila de espera e os profissionais.
 * Impede o drop sobre profissionais em pausa ou tipo incompatível.
 * @param {Event} e - O evento de arraste.
 */
function handleDrop(e) {
  e.preventDefault();
  const droppedOn = e.target.closest(".zona-soltar-item");
  if (!droppedOn) {
    console.warn("Drop realizado fora de uma zona de soltar válida.");
    return;
  }

  droppedOn.classList.remove("zona-arrasto-ativa");

  const clientId = e.dataTransfer.getData("text/plain");
  const client = clients.find((c) => c.id === clientId);
  console.log("Drop - Cliente ID:", clientId, "Cliente Obj:", client);

  if (!client) {
    console.error("Erro no Drop: Cliente não encontrado.");
    return;
  }

  const targetQueueType = droppedOn.dataset.queueType;
  const targetProfessionalId = droppedOn.dataset.professionalId;
  const professional = professionals.find((p) => p.id === targetProfessionalId);
  console.log(
    "Drop - Profissional Alvo:",
    professional?.name,
    "Tipo de Fila Alvo:",
    targetQueueType
  );

  if (professional && professional.manualPause) {
    showMessageBox(
      "Não é possível atribuir clientes a um profissional em pausa."
    );
    console.warn("Drop impedido: Profissional em pausa.");
    renderAll(); // Re-renderiza para garantir que o estado visual esteja correto
    return;
  }
  if (
    professional &&
    client &&
    professional.function !== client.professionalType
  ) {
    showMessageBox(
      `Este profissional (${professional.function}) não atende o tipo de serviço desejado pelo cliente (${client.professionalType}).`
    );
    console.warn(
      `Drop impedido: Tipo de profissional incompatível. Profissional: ${professional.function}, Cliente: ${client.professionalType}`
    );
    renderAll(); // Re-renderiza para garantir que o estado visual esteja correto
    return;
  }

  // Remove o cliente de sua localização atual (fila de espera ou de outro profissional)
  professionals.forEach((p) => {
    if (p.currentClient === clientId) {
      p.currentClient = null;
      updateProfessionalStatus(p.id);
      console.log(
        `Cliente ${client.name} removido do atendimento atual de ${p.name}.`
      );
    }
    const initialQueueLength = p.queue.length;
    p.queue = p.queue.filter((id) => id !== clientId);
    if (p.queue.length < initialQueueLength) {
      updateProfessionalStatus(p.id);
      console.log(`Cliente ${client.name} removido da fila de ${p.name}.`);
    }
  });

  if (droppedOn.id === "clientes-espera") {
    if (!clients.some((c) => c.id === clientId)) {
      client.status = "waiting";
      clients.push(client);
    } else {
      client.status = "waiting";
    }
    showMessageBox(
      `Cliente "${client.name}" movido de volta para "Clientes em Espera".`
    );
    console.log(`Cliente ${client.name} movido para clientes em espera.`);
  } else if (targetQueueType === "current" && professional) {
    if (professional.currentClient) {
      const currentClientBeingServed = clients.find(
        (c) => c.id === professional.currentClient
      );
      if (currentClientBeingServed) {
        currentClientBeingServed.status = "waiting";
        clients.push(currentClientBeingServed);
        console.log(
          `Cliente ${currentClientBeingServed.name} movido para clientes em espera (substituído).`
        );
      }
      professional.currentClient = null;
    }
    professional.currentClient = clientId;
    client.status = "in-service";
    showMessageBox(
      `Cliente "${client.name}" atribuído a "${professional.name}" para atendimento.`
    );
    console.log(
      `Cliente ${client.name} atribuído ao atendimento atual de ${professional.name}.`
    );
  } else if (targetQueueType === "queue" && professional) {
    if (
      professional.currentClient === clientId ||
      professional.queue.includes(clientId)
    ) {
      showMessageBox(
        "O cliente já está sendo atendido ou já está na fila deste profissional."
      );
      console.warn("Drop impedido: Cliente já na posição alvo.");
      renderAll();
      return;
    }
    professional.queue.push(clientId);
    client.status = "waiting-professional";
    showMessageBox(
      `Cliente "${client.name}" adicionado à fila de "${professional.name}".`
    );
    console.log(
      `Cliente ${client.name} adicionado à fila de ${professional.name}.`
    );
  }

  saveData();
  renderAll();
}

/**
 * Renderiza todo o conteúdo dinâmico da página (clientes em espera e profissionais),
 * garantindo que os status sejam atualizados.
 */
function renderAll() {
  console.log("Iniciando renderização completa da UI.");
  renderWaitingClients();
  renderProfessionals();
  professionals.forEach((p) => updateProfessionalStatus(p.id));
  console.log("Renderização completa da UI finalizada.");
}

// --- Listeners de Eventos ---

// Listener para o envio do formulário de registro de cliente.
clientForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const clientName = document.getElementById("nome-cliente").value.trim();
  const clientProcedure = document
    .getElementById("procedimento-cliente")
    .value.trim();
  const professionalType = document.getElementById(
    "tipo-profissional-cliente"
  ).value;
  console.log(
    "Formulário de cliente submetido. Nome:",
    clientName,
    "Procedimento:",
    clientProcedure,
    "Tipo Profissional:",
    professionalType
  );

  if (clientName && clientProcedure && professionalType) {
    addClient(clientName, clientProcedure, professionalType);
    clientForm.reset();
  } else {
    showMessageBox("Por favor, preencha todos os campos do cliente.");
    console.warn("Campos do formulário de cliente incompletos.");
  }
});

// Listener para o envio do formulário de registro de profissional.
professionalForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const professionalName = document
    .getElementById("nome-profissional")
    .value.trim();
  const professionalFunction = document.getElementById(
    "funcao-profissional"
  ).value;
  console.log(
    "Formulário de profissional submetido. Nome:",
    professionalName,
    "Função:",
    professionalFunction
  );

  if (professionalName && professionalFunction) {
    addProfessional(professionalName, professionalFunction);
    professionalForm.reset();
  } else {
    showMessageBox("Por favor, preencha todos os campos do profissional.");
    console.warn("Campos do formulário de profissional incompletos.");
  }
});

// Listeners de drag-and-drop para o contêiner de clientes em espera.
waitingClientsContainer.addEventListener("dragover", handleDragOver);
waitingClientsContainer.addEventListener("dragleave", handleDragLeave);
waitingClientsContainer.addEventListener("drop", handleDrop);

// Listener para o botão de atribuição automática do próximo cliente.
assignNextClientBtn.addEventListener("click", assignNextWaitingClient);

// Listener para o botão "OK" da caixa de mensagem modal.
messageOkButton.addEventListener("click", hideMessageBox);

// Listeners para abrir e fechar o menu lateral em dispositivos móveis.
menuToggle.addEventListener("click", () => {
  mobileSidebar.classList.toggle("aberto");
  console.log("Menu móvel alternado.");
});

closeMenu.addEventListener("click", () => {
  mobileSidebar.classList.remove("aberto");
  console.log("Menu móvel fechado.");
});

// Listener para fechar o menu móvel se o clique ocorrer fora dele.
document.addEventListener("click", (e) => {
  if (
    !mobileSidebar.contains(e.target) &&
    !menuToggle.contains(e.target) &&
    mobileSidebar.classList.contains("aberto")
  ) {
    mobileSidebar.classList.remove("aberto");
    console.log("Menu móvel fechado por clique externo.");
  }
});

// Listeners para o modal de edição de cliente
editClientForm.addEventListener("submit", (e) => {
  e.preventDefault(); // Previne o envio padrão do formulário
  saveEditedClient();
});

cancelEditButton.addEventListener("click", () => {
  hideEditClientModal();
});

// Carrega os dados e renderiza a interface inicial quando o DOM estiver pronto.
document.addEventListener("DOMContentLoaded", () => {
  console.log(
    "DOM completamente carregado. Carregando dados e renderizando UI inicial."
  );
  loadData();
  renderAll();
});
