<?php
session_start();

// Usuário e senha fixos
$usuario_correto = 'atendente';
$senha_correta = 'senha123';

// Verifica login
if (isset($_POST['login'])) {
    if ($_POST['username'] === $usuario_correto && $_POST['password'] === $senha_correta) {
        $_SESSION['logado'] = true;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ?');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cabelo.com - Gerenciamento de Clientes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="corpo-pagina">
    <div class="area-principal">
    <?php if (!isset($_SESSION['logado'])): ?>
        <h2 class="titulo-login">Login do Atendente</h2>
        <?php if (!empty($erro)) echo "<p class='mensagem-erro'>$erro</p>"; ?>
        <form method="post" class="formulario-login">
            <label class="rotulo-campo">Usuário:</label>
            <input type="text" name="username" class="campo-texto" required>
            <label class="rotulo-campo">Senha:</label>
            <input type="password" name="password" class="campo-texto" required>
            <button type="submit" name="login" class="botao-principal">Entrar</button>
        </form>
    <?php else: ?>
    <header class="cabecalho-aplicacao">
        <button id="menu-alternar" class="botao-menu-mobile">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="titulo-aplicacao">cabelo.com</h1>

    </header>

    <nav id="menu-lateral-mobile" class="menu-lateral-mobile">
        <button id="fechar-menu" class="botao-fechar-menu">
            <i class="fas fa-times"></i>
        </button>
        <ul class="lista-menu-mobile">
            <li><a href="#" class="item-menu-mobile"><i class="fas fa-home icone-menu"></i> Início</a></li>
            <li><a href="#" class="item-menu-mobile"><i class="fas fa-users icone-menu"></i> Clientes</a></li>
            <li><a href="#" class="item-menu-mobile"><i class="fas fa-user-tie icone-menu"></i> Profissionais</a></li>
            <li><a href="#" class="item-menu-mobile"><i class="fas fa-cog icone-menu"></i> Configurações</a></li>
        </ul>
    </nav>

    <main class="area-conteudo-principal">
        <section class="coluna-lista">
            <h2 class="titulo-secao texto-centralizado">Clientes em Espera</h2>
            <div id="clientes-espera" class="lista-clientes-espera zona-soltar-item fila-profissional">
                <p class="mensagem-lista-vazia" id="mensagem-sem-clientes-espera">Nenhum cliente em espera.</p>
            </div>
        </section>

        <section class="coluna-lista">
            <h2 class="titulo-secao texto-centralizado">Profissionais do Dia</h2>
            <button id="botao-atribuir-proximo" class="botao-atribuir-proximo">
                <i class="fas fa-hand-point-right icone-botao"></i> Atribuir Próximo Cliente
            </button>
            <div id="lista-profissionais" class="grade-profissionais">
                <p class="mensagem-lista-vazia" id="mensagem-sem-profissionais">Nenhum profissional registrado.</p>
            </div>
        </section>
        <section class="coluna-registro">
            <div class="cartao-registro">
                <h2 class="titulo-secao">Registrar Cliente</h2>
                <form id="form-cliente" class="formulario-registro">
                    <div>
                        <label for="nome-cliente" class="rotulo-campo">Nome do Cliente</label>
                        <input type="text" id="nome-cliente" name="clientName" class="campo-entrada" placeholder="Nome Completo" required>
                    </div>
                    <div>
                        <label for="procedimento-cliente" class="rotulo-campo">Procedimento</label>
                        <input type="text" id="procedimento-cliente" name="clientProcedure" class="campo-entrada" placeholder="Corte, Manicure, etc." required>
                    </div>
                    <button type="submit" class="botao-acao">
                        <i class="fas fa-user-plus icone-botao"></i> Adicionar Cliente
                    </button>
                </form>
            </div>

            <div class="cartao-registro">
                <h2 class="titulo-secao">Registrar Profissional do Dia</h2>
                <form id="form-profissional" class="formulario-registro">
                    <div>
                        <label for="nome-profissional" class="rotulo-campo">Nome do Profissional</label>
                        <input type="text" id="nome-profissional" name="professionalName" class="campo-entrada" placeholder="Nome do Profissional" required>
                    </div>
                    <div>
                        <label for="funcao-profissional" class="rotulo-campo">Função</label>
                        <select id="funcao-profissional" name="professionalFunction" class="campo-entrada" required>
                            <option value="">Selecione a Função</option>
                            <option value="Cabelereiro">Cabelereiro</option>
                            <option value="Manicure">Manicure</option>
                        </select>
                    </div>
                    <button type="submit" class="botao-acao">
                        <i class="fas fa-user-tie icone-botao"></i> Adicionar Profissional
                    </button>
                </form>
            </div>
        </section>

        
    </main>

    <div id="caixa-mensagem" class="caixa-mensagem oculto">
        <div class="conteudo-caixa-mensagem">
            <p id="conteudo-mensagem" class="texto-mensagem"></p>
            <button id="botao-ok-mensagem" class="botao-confirmacao-mensagem">OK</button>
        </div>
    </div>
    <?php endif; ?>
    </div>

    <script>
        // Global variables for managing data
        let clients = [];
        let professionals = [];

        // DOM Elements
        const clientForm = document.getElementById('form-cliente');
        const professionalForm = document.getElementById('form-profissional');
        const waitingClientsContainer = document.getElementById('clientes-espera');
        const professionalsListContainer = document.getElementById('lista-profissionais');
        const noWaitingClientsMessage = document.getElementById('mensagem-sem-clientes-espera');
        const noProfessionalsMessage = document.getElementById('mensagem-sem-profissionais');
        const messageBox = document.getElementById('caixa-mensagem');
        const messageContent = document.getElementById('conteudo-mensagem');
        const messageOkButton = document.getElementById('botao-ok-mensagem');
        const assignNextClientBtn = document.getElementById('botao-atribuir-proximo');
        const menuToggle = document.getElementById('menu-alternar');
        const mobileSidebar = document.getElementById('menu-lateral-mobile');
        const closeMenu = document.getElementById('fechar-menu');

        // --- Utility Functions ---

        /**
         * Displays a custom message box instead of alert().
         * @param {string} message - The message to display.
         */
        function showMessageBox(message) {
            messageContent.textContent = message;
            messageBox.classList.remove('oculto');
        }

        /**
         * Hides the custom message box.
         */
        function hideMessageBox() {
            messageBox.classList.add('oculto');
        }

        // --- Data Persistence (using localStorage for prototype) ---

        /**
         * Loads clients and professionals from localStorage.
         */
        function loadData() {
            const storedClients = localStorage.getItem('clients');
            const storedProfessionals = localStorage.getItem('professionals');

            if (storedClients) {
                clients = JSON.parse(storedClients);
            }
            if (storedProfessionals) {
                professionals = JSON.parse(storedProfessionals);
            }
        }

        /**
         * Saves clients and professionals to localStorage.
         */
        function saveData() {
            localStorage.setItem('clients', JSON.stringify(clients));
            localStorage.setItem('professionals', JSON.stringify(professionals));
        }

        // --- UI Rendering Functions ---

        /**
         * Creates a client card HTML element.
         * @param {object} client - The client object.
         * @returns {HTMLElement} The client card element.
         */
        function createClientCard(client) {
            const clientCard = document.createElement('div');
            clientCard.id = `client-${client.id}`;
            clientCard.classList.add(
                'cartao-cliente', 'arrastavel', 'zona-soltar-item'
            );
            clientCard.setAttribute('draggable', 'true');
            clientCard.dataset.clientId = client.id;

            clientCard.innerHTML = `
                <div>
                    <p class="nome-cliente">${client.name}</p>
                    <p class="procedimento-cliente">${client.procedure}</p>
                </div>
                <button class="botao-remover-cliente" data-client-id="${client.id}">
                    <i class="fas fa-times-circle"></i>
                </button>
            `;

            // Add event listener for removing client
            clientCard.querySelector('.botao-remover-cliente').addEventListener('click', (e) => {
                const clientIdToRemove = e.currentTarget.dataset.clientId;
                removeClient(clientIdToRemove);
            });

            // Add drag event listeners
            clientCard.addEventListener('dragstart', handleDragStart);
            clientCard.addEventListener('dragend', handleDragEnd);

            return clientCard;
        }

        /**
         * Renders all clients in the waiting list.
         */
        function renderWaitingClients() {
            waitingClientsContainer.innerHTML = ''; // Clear existing cards
            const waitingClients = clients.filter(c => c.status === 'waiting');

            if (waitingClients.length === 0) {
                noWaitingClientsMessage.classList.remove('oculto');
                waitingClientsContainer.appendChild(noWaitingClientsMessage);
            } else {
                noWaitingClientsMessage.classList.add('oculto');
                waitingClients.forEach(client => {
                    waitingClientsContainer.appendChild(createClientCard(client));
                });
            }
        }

        /**
         * Creates a professional card HTML element.
         * @param {object} professional - The professional object.
         * @returns {HTMLElement} The professional card element.
         */
        function createProfessionalCard(professional) {
            const professionalCard = document.createElement('div');
            professionalCard.id = `professional-${professional.id}`;
            professionalCard.classList.add(
                'cartao-profissional'
            );
            professionalCard.dataset.professionalId = professional.id;

            let statusColor = '';
            let statusText = '';
            switch (professional.status) {
                case 'total-livre':
                    statusColor = 'status-livre';
                    statusText = 'Totalmente Livre';
                    break;
                case 'livre-parcial':
                    statusColor = 'status-parcial';
                    statusText = 'Livre Parcialmente';
                    break;
                case 'ocupado':
                    statusColor = 'status-ocupado';
                    statusText = 'Ocupado';
                    break;
            }

            professionalCard.innerHTML = `
                <div class="cabecalho-profissional">
                    <h3 class="nome-profissional">${professional.name}</h3>
                    <button class="botao-remover-profissional" data-professional-id="${professional.id}">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
                <p class="funcao-profissional">${professional.function}</p>
                <span class="emblema-status ${statusColor}">${statusText}</span>

                <div class="area-cliente-atual zona-soltar-item" data-professional-id="${professional.id}" data-queue-type="current">
                    ${professional.currentClient ? '' : '<span class="texto-soltar">Arraste o cliente para atender</span>'}
                    ${professional.currentClient ? `<button class="botao-finalizar-servico" data-professional-id="${professional.id}">Finalizar Atendimento</button>` : ''}
                </div>
                <div class="area-fila-espera zona-soltar-item fila-profissional" data-professional-id="${professional.id}" data-queue-type="queue">
                    <p class="texto-soltar ${professional.queue.length > 0 ? 'oculto' : ''}">Clientes na fila</p>
                </div>
            `;

            // Add event listener for removing professional
            professionalCard.querySelector('.botao-remover-profissional').addEventListener('click', (e) => {
                const professionalIdToRemove = e.currentTarget.dataset.professionalId;
                removeProfessional(professionalIdToRemove);
            });

            // Add event listener for finishing service
            const finishServiceBtn = professionalCard.querySelector('.botao-finalizar-servico');
            if (finishServiceBtn) {
                finishServiceBtn.addEventListener('click', (e) => {
                    const professionalId = e.currentTarget.dataset.professionalId;
                    finishClientService(professionalId);
                });
            }

            // Add drop event listeners to current client area
            const currentClientArea = professionalCard.querySelector('.area-cliente-atual');
            currentClientArea.addEventListener('dragover', handleDragOver);
            currentClientArea.addEventListener('dragleave', handleDragLeave);
            currentClientArea.addEventListener('drop', handleDrop);

            // Add drop event listeners to waiting queue area
            const waitingQueueArea = professionalCard.querySelector('.area-fila-espera');
            waitingQueueArea.addEventListener('dragover', handleDragOver);
            waitingQueueArea.addEventListener('dragleave', handleDragLeave);
            waitingQueueArea.addEventListener('drop', handleDrop);

            return professionalCard;
        }

        /**
         * Renders all professionals and their assigned clients.
         */
        function renderProfessionals() {
            professionalsListContainer.innerHTML = ''; // Clear existing cards
            if (professionals.length === 0) {
                noProfessionalsMessage.classList.remove('oculto');
                professionalsListContainer.appendChild(noProfessionalsMessage);
            } else {
                noProfessionalsMessage.classList.add('oculto');
                professionals.forEach(professional => {
                    const professionalCard = createProfessionalCard(professional);
                    professionalsListContainer.appendChild(professionalCard);

                    // Render current client
                    const currentClientArea = professionalCard.querySelector('.area-cliente-atual');
                    if (professional.currentClient) {
                        currentClientArea.innerHTML = ''; // Clear placeholder
                        const client = clients.find(c => c.id === professional.currentClient);
                        if (client) {
                            currentClientArea.appendChild(createClientCard(client));
                        }
                        // Re-add finish service button after client card is added
                        const finishServiceBtn = document.createElement('button');
                        finishServiceBtn.classList.add('botao-finalizar-servico');
                        finishServiceBtn.dataset.professionalId = professional.id;
                        finishServiceBtn.textContent = 'Finalizar Atendimento';
                        finishServiceBtn.addEventListener('click', () => finishClientService(professional.id));
                        currentClientArea.appendChild(finishServiceBtn);
                    }

                    // Render clients in queue
                    const waitingQueueArea = professionalCard.querySelector('.area-fila-espera');
                    if (professional.queue.length > 0) {
                        waitingQueueArea.querySelector('.texto-soltar').classList.add('oculto'); // Hide "Clientes na fila" message
                        professional.queue.forEach(clientId => {
                            const client = clients.find(c => c.id === clientId);
                            if (client) {
                                waitingQueueArea.appendChild(createClientCard(client));
                            }
                        });
                    }
                });
            }
        }

        /**
         * Updates the status badge of a professional.
         * @param {string} professionalId - The ID of the professional.
         */
        function updateProfessionalStatus(professionalId) {
            const professional = professionals.find(p => p.id === professionalId);
            if (!professional) return;

            let newStatus = 'total-livre';
            if (professional.currentClient) {
                newStatus = 'ocupado';
            } else if (professional.queue.length > 0) {
                newStatus = 'livre-parcial';
            }
            professional.status = newStatus;

            const professionalCardElement = document.getElementById(`professional-${professionalId}`);
            if (professionalCardElement) {
                const statusBadge = professionalCardElement.querySelector('.emblema-status');
                statusBadge.classList.remove('status-livre', 'status-parcial', 'status-ocupado'); // Remove old status classes
                let statusText = '';
                switch (newStatus) {
                    case 'total-livre':
                        statusBadge.classList.add('status-livre');
                        statusText = 'Totalmente Livre';
                        break;
                    case 'livre-parcial':
                        statusBadge.classList.add('status-parcial');
                        statusText = 'Livre Parcialmente';
                        break;
                    case 'ocupado':
                        statusBadge.classList.add('status-ocupado');
                        statusText = 'Ocupado';
                        break;
                }
                statusBadge.textContent = statusText;
            }
            saveData();
        }

        // --- Client Management Functions ---

        /**
         * Adds a new client.
         * @param {string} name - Client's name.
         * @param {string} procedure - Client's requested procedure.
         */
        function addClient(name, procedure) {
            const newClient = {
                id: Date.now().toString(), // Simple unique ID
                name: name,
                procedure: procedure,
                status: 'waiting' // 'waiting', 'in-service', 'done'
            };
            clients.push(newClient);
            saveData();
            renderWaitingClients();
            showMessageBox('Cliente "' + name + '" adicionado com sucesso!');
        }

        /**
         * Removes a client from the system.
         * @param {string} clientIdToRemove - The ID of the client to remove.
         */
        function removeClient(clientIdToRemove) {
            // Check if client is currently assigned to a professional
            const professionalWithClient = professionals.find(p => p.currentClient === clientIdToRemove);
            if (professionalWithClient) {
                showMessageBox('Não é possível remover o cliente enquanto ele está sendo atendido por um profissional. Finalize o atendimento primeiro.');
                return;
            }

            // Remove from waiting list or professional queue
            clients = clients.filter(c => c.id !== clientIdToRemove);

            // Remove from any professional's queue if present
            professionals.forEach(p => {
                p.queue = p.queue.filter(id => id !== clientIdToRemove);
                updateProfessionalStatus(p.id); // Update status after removing from queue
            });

            saveData();
            renderWaitingClients();
            renderProfessionals(); // Re-render professionals to update queues
            showMessageBox('Cliente removido com sucesso!');
        }

        // --- Professional Management Functions ---

        /**
         * Adds a new professional.
         * @param {string} name - Professional's name.
         * @param {string} func - Professional's function.
         */
        function addProfessional(name, func) {
            const newProfessional = {
                id: Date.now().toString(), // Simple unique ID
                name: name,
                function: func,
                status: 'total-livre', // 'total-livre', 'livre-parcial', 'ocupado'
                currentClient: null,
                queue: [],
                lastFinishedTime: Date.now() // Initialize with current time for turn order
            };
            professionals.push(newProfessional);
            saveData();
            renderProfessionals();
            showMessageBox('Profissional "' + name + '" adicionado com sucesso!');
        }

        /**
         * Removes a professional from the system.
         * @param {string} professionalIdToRemove - The ID of the professional to remove.
         */
        function removeProfessional(professionalIdToRemove) {
            const professional = professionals.find(p => p.id === professionalIdToRemove);
            if (!professional) return;

            if (professional.currentClient || professional.queue.length > 0) {
                showMessageBox('Não é possível remover o profissional enquanto ele tem clientes sendo atendidos ou na fila.');
                return;
            }

            professionals = professionals.filter(p => p.id !== professionalIdToRemove);
            saveData();
            renderProfessionals();
            showMessageBox('Profissional removido com sucesso!');
        }

        /**
         * Handles finishing a client's service.
         * @param {string} professionalId - The ID of the professional.
         */
        function finishClientService(professionalId) {
            const professional = professionals.find(p => p.id === professionalId);
            if (!professional || !professional.currentClient) {
                showMessageBox('Nenhum cliente em atendimento para finalizar.');
                return;
            }

            const clientFinishedId = professional.currentClient;

            // Remove client from the global clients array (marking as 'done' could be an alternative)
            clients = clients.filter(c => c.id !== clientFinishedId);

            professional.currentClient = null; // Clear current client
            professional.lastFinishedTime = Date.now(); // Update last finished time

            // If there's a client in the queue, move them to current client
            if (professional.queue.length > 0) {
                const nextClientId = professional.queue.shift(); // Remove first client from queue
                professional.currentClient = nextClientId;
                const nextClient = clients.find(c => c.id === nextClientId);
                if (nextClient) {
                    nextClient.status = 'in-service';
                    showMessageBox(`Atendimento de "${clients.find(c => c.id === clientFinishedId)?.name}" finalizado. "${nextClient.name}" agora está sendo atendido por "${professional.name}".`);
                }
            } else {
                showMessageBox(`Atendimento de "${clients.find(c => c.id === clientFinishedId)?.name}" finalizado. Profissional "${professional.name}" está livre.`);
            }

            saveData();
            renderAll();
        }

        /**
         * Finds the next available professional based on status and last finished time.
         * Prioritizes 'total-livre', then 'livre-parcial', then oldest lastFinishedTime.
         * @returns {object|null} The next available professional, or null if none.
         */
        function findNextAvailableProfessional() {
            // Filter professionals who are not 'ocupado'
            const availableProfessionals = professionals.filter(p => p.status !== 'ocupado');

            if (availableProfessionals.length === 0) {
                return null; // No available professionals
            }

            // Sort by status ('total-livre' first, then 'livre-parcial') and then by lastFinishedTime (oldest first)
            availableProfessionals.sort((a, b) => {
                if (a.status === 'total-livre' && b.status !== 'total-livre') return -1;
                if (b.status === 'total-livre' && a.status !== 'total-livre') return 1;
                // If both are same status or both not 'total-livre', sort by lastFinishedTime
                return a.lastFinishedTime - b.lastFinishedTime;
            });

            return availableProfessionals[0];
        }

        /**
         * Assigns the next waiting client to the next available professional.
         */
        function assignNextWaitingClient() {
            const waitingClients = clients.filter(c => c.status === 'waiting');
            if (waitingClients.length === 0) {
                showMessageBox('Não há clientes em espera para atribuir.');
                return;
            }

            const nextAvailableProfessional = findNextAvailableProfessional();
            if (!nextAvailableProfessional) {
                showMessageBox('Nenhum profissional disponível no momento.');
                return;
            }

            const clientToAssign = waitingClients[0]; // Get the first client in the waiting list

            // Remove client from waiting list
            clients = clients.filter(c => c.id !== clientToAssign.id);

            // Assign client to professional's current slot or queue
            if (!nextAvailableProfessional.currentClient) {
                nextAvailableProfessional.currentClient = clientToAssign.id;
                clientToAssign.status = 'in-service';
                showMessageBox(`Cliente "${clientToAssign.name}" atribuído a "${nextAvailableProfessional.name}" para atendimento.`);
            } else {
                nextAvailableProfessional.queue.push(clientToAssign.id);
                clientToAssign.status = 'waiting-professional';
                showMessageBox(`Cliente "${clientToAssign.name}" adicionado à fila de "${nextAvailableProfessional.name}".`);
            }
            saveData();
            renderAll();
        }


        // --- Drag and Drop Logic ---

        let draggedClientCard = null;

        /**
         * Handles the dragstart event for client cards.
         * @param {Event} e - The drag event.
         */
        function handleDragStart(e) {
            draggedClientCard = e.target;
            e.dataTransfer.setData('text/plain', e.target.dataset.clientId);
            e.target.classList.add('arrastando');
        }

        /**
         * Handles the dragend event for client cards.
         * @param {Event} e - The drag event.
         */
        function handleDragEnd(e) {
            e.target.classList.remove('arrastando');
            draggedClientCard = null;
        }

        /**
         * Handles the dragover event for drop targets.
         * @param {Event} e - The drag event.
         */
        function handleDragOver(e) {
            e.preventDefault(); // Necessary to allow dropping
            if (e.target.classList.contains('zona-soltar-item')) {
                e.target.classList.add('zona-arrasto-ativa');
            }
        }

        /**
         * Handles the dragleave event for drop targets.
         * @param {Event} e - The drag event.
         */
        function handleDragLeave(e) {
            if (e.target.classList.contains('zona-soltar-item')) {
                e.target.classList.remove('zona-arrasto-ativa');
            }
        }

        /**
         * Handles the drop event for drop targets.
         * @param {Event} e - The drag event.
         */
        function handleDrop(e) {
            e.preventDefault();
            const droppedOn = e.target.closest('.zona-soltar-item'); // Find the closest drop target
            if (!droppedOn) return;

            droppedOn.classList.remove('zona-arrasto-ativa');

            const clientId = e.dataTransfer.getData('text/plain');
            const client = clients.find(c => c.id === clientId);
            if (!client) return;

            const targetQueueType = droppedOn.dataset.queueType;
            const targetProfessionalId = droppedOn.dataset.professionalId;

            // Remove client from its current location (waiting list or any professional's queue/current)
            // This ensures a client is only in one place at a time.
            let clientMovedFromProfessional = null; // To track if client was moved from a professional
            professionals.forEach(p => {
                if (p.currentClient === clientId) {
                    p.currentClient = null;
                    clientMovedFromProfessional = p.id;
                    updateProfessionalStatus(p.id);
                }
                p.queue = p.queue.filter(id => id !== clientId);
                updateProfessionalStatus(p.id);
            });

            // Update client status in the global clients array
            const clientIndex = clients.findIndex(c => c.id === clientId);
            if (clientIndex !== -1) {
                // If client was moved from a professional and dropped on waiting list, set status to 'waiting'
                if (droppedOn.id === 'clientes-espera' && clientMovedFromProfessional) {
                    clients[clientIndex].status = 'waiting';
                } else if (targetQueueType === 'current') {
                    clients[clientIndex].status = 'in-service';
                } else if (targetQueueType === 'queue') {
                    clients[clientIndex].status = 'waiting-professional';
                }
            }


            if (targetQueueType === 'current') {
                const professional = professionals.find(p => p.id === targetProfessionalId);
                if (professional) {
                    if (professional.currentClient) {
                        // If the target professional is already serving a client, move that client back to waiting
                        const currentClientBeingServed = clients.find(c => c.id === professional.currentClient);
                        if (currentClientBeingServed) {
                            currentClientBeingServed.status = 'waiting';
                        }
                        professional.currentClient = null; // Clear current client
                    }
                    professional.currentClient = clientId;
                    client.status = 'in-service'; // Update client status
                    showMessageBox(`Cliente "${client.name}" atribuído a "${professional.name}" para atendimento.`);
                }
            } else if (targetQueueType === 'queue') {
                const professional = professionals.find(p => p.id === targetProfessionalId);
                if (professional) {
                    // Prevent adding client to queue if already in current client slot or in queue
                    if (professional.currentClient === clientId || professional.queue.includes(clientId)) {
                        showMessageBox('O cliente já está sendo atendido ou já está na fila deste profissional.');
                        renderAll(); // Re-render to revert UI changes from invalid drop
                        return;
                    }
                    professional.queue.push(clientId);
                    client.status = 'waiting-professional'; // Update client status
                    showMessageBox(`Cliente "${client.name}" adicionado à fila de "${professional.name}".`);
                }
            } else if (droppedOn.id === 'clientes-espera') {
                // Dropped back into waiting clients area
                client.status = 'waiting';
                showMessageBox(`Cliente "${client.name}" movido de volta para "Clientes em Espera".`);
            }

            saveData();
            renderAll(); // Re-render everything to reflect changes
        }

        /**
         * Renders all dynamic content on the page.
         */
        function renderAll() {
            renderWaitingClients();
            renderProfessionals();
            // Update all professional statuses after any client movement
            professionals.forEach(p => updateProfessionalStatus(p.id));
        }

        // --- Event Listeners ---

        // Client Form Submission
        clientForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const clientName = document.getElementById('nome-cliente').value.trim();
            const clientProcedure = document.getElementById('procedimento-cliente').value.trim();

            if (clientName && clientProcedure) {
                addClient(clientName, clientProcedure);
                clientForm.reset();
            } else {
                showMessageBox('Por favor, preencha todos os campos do cliente.');
            }
        });

        // Professional Form Submission
        professionalForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const professionalName = document.getElementById('nome-profissional').value.trim();
            const professionalFunction = document.getElementById('funcao-profissional').value;

            if (professionalName && professionalFunction) {
                addProfessional(professionalName, professionalFunction);
                professionalForm.reset();
            } else {
                showMessageBox('Por favor, preencha todos os campos do profissional.');
            }
        });

        // Drop target event listeners for the main waiting area
        waitingClientsContainer.addEventListener('dragover', handleDragOver);
        waitingClientsContainer.addEventListener('dragleave', handleDragLeave);
        waitingClientsContainer.addEventListener('drop', handleDrop);

        // Assign Next Client Button
        assignNextClientBtn.addEventListener('click', assignNextWaitingClient);

        // Message Box OK button
        messageOkButton.addEventListener('click', hideMessageBox);

        // Mobile Menu Toggle
        menuToggle.addEventListener('click', () => {
            mobileSidebar.classList.toggle('aberto');
        });

        // Close Mobile Menu
        closeMenu.addEventListener('click', () => {
            mobileSidebar.classList.remove('aberto');
        });

        // Close mobile menu if clicked outside (optional, but good UX)
        document.addEventListener('click', (e) => {
            if (!mobileSidebar.contains(e.target) && !menuToggle.contains(e.target) && mobileSidebar.classList.contains('aberto')) {
                mobileSidebar.classList.remove('aberto');
            }
        });


        // Initial load and render
        document.addEventListener('DOMContentLoaded', () => {
            loadData();
            renderAll();
        });
    </script>
</body>
</html> 