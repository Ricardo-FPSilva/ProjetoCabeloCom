<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cabelo.com - Gerenciamento de Clientes</title>
    <!-- biblioteca Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-primary-dark p-4 shadow-md text-white flex items-center justify-between relative z-10">
        <button id="menu-toggle" class="lg:hidden text-white text-2xl p-2 rounded-md hover:bg-primary-medium transition duration-300">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="text-3xl font-bold flex-grow text-center lg:text-left">cabelo.com</h1>
        <p class="hidden lg:block text-lg">Gerenciamento Inteligente para seu Salão de Beleza</p>
    </header>

    <!-- Menu lateral mobile -->
    <nav id="mobile-sidebar" class="mobile-menu fixed top-0 left-0 h-full w-64 bg-primary-dark shadow-lg z-20 p-4 pt-16 lg:hidden">
        <button id="close-menu" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 transition duration-300">
            <i class="fas fa-times"></i>
        </button>
        <ul class="space-y-4 text-white text-lg font-semibold">
            <li><a href="#" class="block p-2 rounded-md hover:bg-primary-medium transition duration-300"><i class="fas fa-home mr-2"></i> Início</a></li>
            <li><a href="#" class="block p-2 rounded-md hover:bg-primary-medium transition duration-300"><i class="fas fa-users mr-2"></i> Clientes</a></li>
            <li><a href="#" class="block p-2 rounded-md hover:bg-primary-medium transition duration-300"><i class="fas fa-user-tie mr-2"></i> Profissionais</a></li>
            <li><a href="#" class="block p-2 rounded-md hover:bg-primary-medium transition duration-300"><i class="fas fa-cog mr-2"></i> Configurações</a></li>
        </ul>
    </nav>

    <main class="flex-grow container mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Coluna de Registro -->
        <section class="lg:col-span-1 flex flex-col gap-8">
            <!-- Registro de Clientes -->
            <div class="bg-white p-6 rounded-lg shadow-lg border border-primary-medium">
                <h2 class="text-2xl font-semibold mb-4 text-primary-dark">Registrar Cliente</h2>
                <form id="client-form" class="space-y-4">
                    <div>
                        <label for="client-name" class="block text-sm font-medium text-gray-700">Nome do Cliente</label>
                        <input type="text" id="client-name" name="clientName" class="mt-1 block w-full p-2 border border-primary-medium rounded-md shadow-sm focus:ring-accent focus:border-accent" placeholder="Nome Completo" required>
                    </div>
                    <div>
                        <label for="client-procedure" class="block text-sm font-medium text-gray-700">Procedimento</label>
                        <input type="text" id="client-procedure" name="clientProcedure" class="mt-1 block w-full p-2 border border-primary-medium rounded-md shadow-sm focus:ring-accent focus:border-accent" placeholder="Corte, Manicure, etc." required>
                    </div>
                    <button type="submit" class="w-full bg-accent text-white py-2 px-4 rounded-md hover:bg-primary-dark transition duration-300 ease-in-out shadow-md">
                        <i class="fas fa-user-plus mr-2"></i> Adicionar Cliente
                    </button>
                </form>
            </div>

            <!-- Registro de Profissionais -->
            <div class="bg-white p-6 rounded-lg shadow-lg border border-primary-medium">
                <h2 class="text-2xl font-semibold mb-4 text-primary-dark">Registrar Profissional do Dia</h2>
                <form id="professional-form" class="space-y-4">
                    <div>
                        <label for="professional-name" class="block text-sm font-medium text-gray-700">Nome do Profissional</label>
                        <input type="text" id="professional-name" name="professionalName" class="mt-1 block w-full p-2 border border-primary-medium rounded-md shadow-sm focus:ring-accent focus:border-accent" placeholder="Nome do Profissional" required>
                    </div>
                    <div>
                        <label for="professional-function" class="block text-sm font-medium text-gray-700">Função</label>
                        <select id="professional-function" name="professionalFunction" class="mt-1 block w-full p-2 border border-primary-medium rounded-md shadow-sm focus:ring-accent focus:border-accent" required>
                            <option value="">Selecione a Função</option>
                            <option value="Cabelereiro">Cabelereiro</option>
                            <option value="Manicure">Manicure</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-accent text-white py-2 px-4 rounded-md hover:bg-primary-dark transition duration-300 ease-in-out shadow-md">
                        <i class="fas fa-user-tie mr-2"></i> Adicionar Profissional
                    </button>
                </form>
            </div>
        </section>

        <!-- Coluna de Clientes em Espera -->
        <section class="lg:col-span-1 bg-primary-light p-6 rounded-lg shadow-lg border border-primary-medium flex flex-col">
            <h2 class="text-2xl font-semibold mb-4 text-primary-dark text-center">Clientes em Espera</h2>
            <div id="waiting-clients" class="flex-grow p-4 bg-white rounded-md border border-primary-medium drop-target space-y-3 professional-queue">
                <!-- Client cards will be added here -->
                <p class="text-center text-gray-500 italic" id="no-waiting-clients-message">Nenhum cliente em espera.</p>
            </div>
        </section>

        <!-- Coluna de Profissionais do Dia -->
        <section class="lg:col-span-1 bg-primary-light p-6 rounded-lg shadow-lg border border-primary-medium flex flex-col">
            <h2 class="text-2xl font-semibold mb-4 text-primary-dark text-center">Profissionais do Dia</h2>
            <button id="assign-next-client-btn" class="w-full bg-primary-dark text-white py-2 px-4 rounded-md hover:bg-accent transition duration-300 ease-in-out shadow-md mb-4">
                <i class="fas fa-hand-point-right mr-2"></i> Atribuir Próximo Cliente
            </button>
            <div id="professionals-list" class="flex-grow grid grid-cols-1 gap-6">
                <!-- Professional cards will be added here -->
                <p class="text-center text-gray-500 italic" id="no-professionals-message">Nenhum profissional registrado.</p>
            </div>
        </section>
    </main>

    <!-- Message Box for alerts -->
    <div id="message-box" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full text-center border border-primary-dark">
            <p id="message-content" class="text-lg font-semibold mb-4 text-gray-800"></p>
            <button id="message-ok-button" class="bg-accent text-white py-2 px-4 rounded-md hover:bg-primary-dark transition duration-300 ease-in-out shadow-md">OK</button>
        </div>
    </div>

    <script>
        // Global variables for managing data
        let clients = [];
        let professionals = [];

        // DOM Elements
        const clientForm = document.getElementById('client-form');
        const professionalForm = document.getElementById('professional-form');
        const waitingClientsContainer = document.getElementById('waiting-clients');
        const professionalsListContainer = document.getElementById('professionals-list');
        const noWaitingClientsMessage = document.getElementById('no-waiting-clients-message');
        const noProfessionalsMessage = document.getElementById('no-professionals-message');
        const messageBox = document.getElementById('message-box');
        const messageContent = document.getElementById('message-content');
        const messageOkButton = document.getElementById('message-ok-button');
        const assignNextClientBtn = document.getElementById('assign-next-client-btn');
        const menuToggle = document.getElementById('menu-toggle');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const closeMenu = document.getElementById('close-menu');

        // --- Utility Functions ---

        /**
         * Displays a custom message box instead of alert().
         * @param {string} message - The message to display.
         */
        function showMessageBox(message) {
            messageContent.textContent = message;
            messageBox.classList.remove('hidden');
        }

        /**
         * Hides the custom message box.
         */
        function hideMessageBox() {
            messageBox.classList.add('hidden');
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
                'client-card', 'draggable', 'bg-primary-light', 'p-3', 'rounded-md', 'shadow-sm',
                'border', 'border-primary-medium', 'flex', 'items-center', 'justify-between', 'text-sm'
            );
            clientCard.setAttribute('draggable', 'true');
            clientCard.dataset.clientId = client.id;

            clientCard.innerHTML = `
                <div>
                    <p class="font-medium">${client.name}</p>
                    <p class="text-gray-600 text-xs">${client.procedure}</p>
                </div>
                <button class="remove-client-btn text-red-500 hover:text-red-700 transition-colors duration-200" data-client-id="${client.id}">
                    <i class="fas fa-times-circle"></i>
                </button>
            `;

            // Add event listener for removing client
            clientCard.querySelector('.remove-client-btn').addEventListener('click', (e) => {
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
                noWaitingClientsMessage.classList.remove('hidden');
                waitingClientsContainer.appendChild(noWaitingClientsMessage);
            } else {
                noWaitingClientsMessage.classList.add('hidden');
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
                'bg-white', 'p-4', 'rounded-lg', 'shadow-md', 'border', 'border-primary-medium',
                'flex', 'flex-col', 'gap-3'
            );
            professionalCard.dataset.professionalId = professional.id;

            let statusColor = '';
            let statusText = '';
            switch (professional.status) {
                case 'total-livre':
                    statusColor = 'bg-green-200 text-green-800';
                    statusText = 'Totalmente Livre';
                    break;
                case 'livre-parcial':
                    statusColor = 'bg-yellow-200 text-yellow-800';
                    statusText = 'Livre Parcialmente';
                    break;
                case 'ocupado':
                    statusColor = 'bg-red-200 text-red-800';
                    statusText = 'Ocupado';
                    break;
            }

            professionalCard.innerHTML = `
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-primary-dark">${professional.name}</h3>
                    <button class="remove-professional-btn text-red-500 hover:text-red-700 transition-colors duration-200" data-professional-id="${professional.id}">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
                <p class="text-gray-700 text-sm">${professional.function}</p>
                <span class="status-badge text-xs font-medium px-2 py-1 rounded-full ${statusColor}">${statusText}</span>

                <div class="current-client-area bg-primary-light p-3 rounded-md border border-primary-medium drop-target min-h-[70px] flex flex-col items-center justify-center text-center text-gray-500 italic" data-professional-id="${professional.id}" data-queue-type="current">
                    ${professional.currentClient ? '' : 'Arraste o cliente para atender'}
                    ${professional.currentClient ? `<button class="finish-service-btn mt-2 bg-green-500 text-white py-1 px-3 rounded-md hover:bg-green-600 transition duration-300 ease-in-out shadow-sm text-xs" data-professional-id="${professional.id}">Finalizar Atendimento</button>` : ''}
                </div>
                <div class="waiting-queue-area bg-primary-light p-3 rounded-md border border-primary-medium drop-target professional-queue space-y-2" data-professional-id="${professional.id}" data-queue-type="queue">
                    <p class="text-center text-gray-500 italic text-sm ${professional.queue.length > 0 ? 'hidden' : ''}">Clientes na fila</p>
                </div>
            `;

            // Add event listener for removing professional
            professionalCard.querySelector('.remove-professional-btn').addEventListener('click', (e) => {
                const professionalIdToRemove = e.currentTarget.dataset.professionalId;
                removeProfessional(professionalIdToRemove);
            });

            // Add event listener for finishing service
            const finishServiceBtn = professionalCard.querySelector('.finish-service-btn');
            if (finishServiceBtn) {
                finishServiceBtn.addEventListener('click', (e) => {
                    const professionalId = e.currentTarget.dataset.professionalId;
                    finishClientService(professionalId);
                });
            }

            // Add drop event listeners to current client area
            const currentClientArea = professionalCard.querySelector('.current-client-area');
            currentClientArea.addEventListener('dragover', handleDragOver);
            currentClientArea.addEventListener('dragleave', handleDragLeave);
            currentClientArea.addEventListener('drop', handleDrop);

            // Add drop event listeners to waiting queue area
            const waitingQueueArea = professionalCard.querySelector('.waiting-queue-area');
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
                noProfessionalsMessage.classList.remove('hidden');
                professionalsListContainer.appendChild(noProfessionalsMessage);
            } else {
                noProfessionalsMessage.classList.add('hidden');
                professionals.forEach(professional => {
                    const professionalCard = createProfessionalCard(professional);
                    professionalsListContainer.appendChild(professionalCard);

                    // Render current client
                    const currentClientArea = professionalCard.querySelector('.current-client-area');
                    if (professional.currentClient) {
                        currentClientArea.innerHTML = ''; // Clear placeholder
                        const client = clients.find(c => c.id === professional.currentClient);
                        if (client) {
                            currentClientArea.appendChild(createClientCard(client));
                        }
                        // Re-add finish service button after client card is added
                        const finishServiceBtn = document.createElement('button');
                        finishServiceBtn.classList.add('finish-service-btn', 'mt-2', 'bg-green-500', 'text-white', 'py-1', 'px-3', 'rounded-md', 'hover:bg-green-600', 'transition', 'duration-300', 'ease-in-out', 'shadow-sm', 'text-xs');
                        finishServiceBtn.dataset.professionalId = professional.id;
                        finishServiceBtn.textContent = 'Finalizar Atendimento';
                        finishServiceBtn.addEventListener('click', () => finishClientService(professional.id));
                        currentClientArea.appendChild(finishServiceBtn);
                    }

                    // Render clients in queue
                    const waitingQueueArea = professionalCard.querySelector('.waiting-queue-area');
                    if (professional.queue.length > 0) {
                        waitingQueueArea.querySelector('p').classList.add('hidden'); // Hide "Clientes na fila" message
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
                const statusBadge = professionalCardElement.querySelector('.status-badge');
                let statusColor = '';
                let statusText = '';
                switch (newStatus) {
                    case 'total-livre':
                        statusColor = 'bg-green-200 text-green-800';
                        statusText = 'Totalmente Livre';
                        break;
                    case 'livre-parcial':
                        statusColor = 'bg-yellow-200 text-yellow-800';
                        statusText = 'Livre Parcialmente';
                        break;
                    case 'ocupado':
                        statusColor = 'bg-red-200 text-red-800';
                        statusText = 'Ocupado';
                        break;
                }
                statusBadge.className = `status-badge text-xs font-medium px-2 py-1 rounded-full ${statusColor}`;
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
            e.target.classList.add('dragging');
        }

        /**
         * Handles the dragend event for client cards.
         * @param {Event} e - The drag event.
         */
        function handleDragEnd(e) {
            e.target.classList.remove('dragging');
            draggedClientCard = null;
        }

        /**
         * Handles the dragover event for drop targets.
         * @param {Event} e - The drag event.
         */
        function handleDragOver(e) {
            e.preventDefault(); // Necessary to allow dropping
            if (e.target.classList.contains('drop-target')) {
                e.target.classList.add('drag-over');
            }
        }

        /**
         * Handles the dragleave event for drop targets.
         * @param {Event} e - The drag event.
         */
        function handleDragLeave(e) {
            if (e.target.classList.contains('drop-target')) {
                e.target.classList.remove('drag-over');
            }
        }

        /**
         * Handles the drop event for drop targets.
         * @param {Event} e - The drag event.
         */
        function handleDrop(e) {
            e.preventDefault();
            const droppedOn = e.target.closest('.drop-target'); // Find the closest drop target
            if (!droppedOn) return;

            droppedOn.classList.remove('drag-over');

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
                if (droppedOn.id === 'waiting-clients' && clientMovedFromProfessional) {
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
            } else if (droppedOn.id === 'waiting-clients') {
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
            const clientName = document.getElementById('client-name').value.trim();
            const clientProcedure = document.getElementById('client-procedure').value.trim();

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
            const professionalName = document.getElementById('professional-name').value.trim();
            const professionalFunction = document.getElementById('professional-function').value;

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
            mobileSidebar.classList.toggle('open');
        });

        // Close Mobile Menu
        closeMenu.addEventListener('click', () => {
            mobileSidebar.classList.remove('open');
        });

        // Close mobile menu if clicked outside (optional, but good UX)
        document.addEventListener('click', (e) => {
            if (!mobileSidebar.contains(e.target) && !menuToggle.contains(e.target) && mobileSidebar.classList.contains('open')) {
                mobileSidebar.classList.remove('open');
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
