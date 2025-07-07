<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Saídas</title>
    <style>
        :root {
            --cor-card: #ffffff;
            --cor-botao: #8B2E4F;
            --cor-botao-hover: #4A2C3E;
            --cor-texto: #333;
            --cor-texto-claro: #FDF8F5; 
            --cor-borda: #ddd;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--cor-fundo);
            margin: 0;
            padding: 16px;
            color: var(--cor-texto);
            display: flex;
            /* Added for centering content */
            justify-content: center;
            /* Added for centering content */
            align-items: flex-start;
            /* Aligns to the top initially */
            min-height: 100vh;
            /* Ensures body takes full viewport height */
            transition: align-items 0.3s ease-out;
            /* Smooth transition for alignment */
        }

        .container {
            max-width: 600px;
            width: 100%;
            /* Ensures container takes full width up to max-width */
            margin: 0 auto;
        }

        .formulario,
        .historico {
            background-color: var(--cor-card);
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
            transition: all 0.3s ease-out;
            /* Smooth transition for general card changes */
        }

        label {
            font-weight: 600;
            margin-top: 12px;
            display: block;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: 1px solid var(--cor-borda);
            font-size: 16px;
        }

        button {
            display: block; 
            margin: 0 auto;
            width: fit-content;
            padding: 15px 30px;
            margin-top: 16px;
            font-size: 1.1rem;
            background-color: var(--cor-botao); 
            color: var(--cor-texto-claro);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            font-weight: 600;
        }

        button:hover {
            background-color: var(--cor-botao-hover); /* Marrom Escuro Avermelhado para o hover */
            transform: translateY(-2px);
        }

        .historico h2 {
            margin-top: 0;
            font-size: 20px;
            margin-bottom: 16px;
        }

        #lista-saidas {
            max-height: 150px;
            /* Initial max-height for 3 items + some padding */
            overflow: hidden;
            /* Hide overflow content */
            transition: max-height 0.3s ease-out;
            /* Smooth transition for max-height */
        }

        .historico.expandido #lista-saidas {
            max-height: 500px;
            /* Larger max-height when expanded (adjust as needed) */
            overflow-y: auto;
            /* Enable scroll if content exceeds max-height */
        }

        .historico #lista-saidas {
            transition: max-height 0.3s ease-out;
        }

        .historico.colapsando #lista-saidas {
            max-height: 150px;
            overflow: hidden;
        }


        .saida {
            border-bottom: 1px solid var(--cor-borda);
            padding: 10px 0;
        }

        .saida:last-child {
            border-bottom: none;
        }

        .saida strong {
            color: #000;
        }

        .saida em {
            color: #666;
            font-size: 14px;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 16px;
            font-size: 18px;
        }

        /* Styles for centering the history card when expanded */
        .historico.expandido {
            position: fixed;
            /* Fix its position */
            top: 50%;
            /* Start at 50% from the top */
            left: 50%;
            /* Start at 50% from the left */
            transform: translate(-50%, -50%);
            /* Center it precisely */
            width: min(calc(100% - 32px), 600px);
            /* Adjust width to match container, accounting for padding */
            z-index: 1000;
            /* Ensure it's above other content */
            margin-bottom: 0;
            /* Remove bottom margin when fixed */
        }

        @media (max-width: 480px) {
            body {
                padding: 12px;
            }

            button {
                font-size: 16px;
            }

            input,
            textarea {
                font-size: 15px;
            }

            .historico.expandido {
                width: calc(100% - 24px);
                /* Adjust width for smaller screens */
            }
        }

        .historico.clicavel {
            cursor: pointer;
            transition: box-shadow 0.2s ease;
        }

        .historico.clicavel:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="formulario">
            <label for="valor">Valor (R$):</label>
            <input type="number" id="valor" placeholder="Digite o valor da saída">

            <label for="descricao">Descrição (opcional):</label>
            <textarea id="descricao" rows="3" placeholder="Ex: Compra de material, vale..."></textarea>

            <button onclick="registrarSaida()">Registrar Saída</button>
        </div>

        <div class="historico clicavel" id="historico-card" onclick="alternarExibicao()">
            <h2>📃 Histórico de Saídas do Dia</h2>
            <div id="lista-saidas">
            </div>
            <div class="total" id="total-saidas">Total: R$ 0,00</div>
        </div>
    </div>

    <script>
        let saidas = [];
        let mostrarTudo = false;

        // Get the history card element
        const historicoCard = document.getElementById('historico-card');

        function registrarSaida() {
            const valorInput = document.getElementById('valor');
            const descInput = document.getElementById('descricao');

            const valor = parseFloat(valorInput.value);
            const descricao = descInput.value.trim();

            if (isNaN(valor) || valor <= 0) {
                alert("Informe um valor válido!");
                return;
            }

            saidas.push({ valor, descricao });
            valorInput.value = '';
            descInput.value = '';
            atualizarHistorico();
        }

        function atualizarHistorico() {
            const lista = document.getElementById('lista-saidas');
            const totalDiv = document.getElementById('total-saidas');
            lista.innerHTML = '';

            let total = 0;
            saidas.forEach(s => total += s.valor);

            // Determine which items to display
            const saidasExibidas = mostrarTudo ? saidas : saidas.slice(-3);

            saidasExibidas.forEach((saida) => {
                const item = document.createElement('div');
                item.className = 'saida';
                item.innerHTML = `
                    <strong>R$ ${saida.valor.toFixed(2)}</strong><br>
                    ${saida.descricao || "<em>Sem descrição</em>"}
                `;
                lista.appendChild(item);
            });

            totalDiv.innerText = `Total: R$ ${total.toFixed(2)}`;
        }

        function alternarExibicao() {
            if (mostrarTudo) {
                // Iniciar colapso
                historicoCard.classList.add('colapsando');

                // Esperar a transição terminar antes de remover 'expandido'
                setTimeout(() => {
                    historicoCard.classList.remove('expandido');
                    historicoCard.classList.remove('colapsando');
                    document.body.style.alignItems = 'flex-start';
                    mostrarTudo = false;
                    atualizarHistorico();
                }, 300); // tempo igual ao transition (0.3s)
            } else {
                mostrarTudo = true;
                historicoCard.classList.add('expandido');
                document.body.style.alignItems = 'center';
                atualizarHistorico();
            }
        }


        // Initial update when the page loads
        atualizarHistorico();
    </script>
</body>

</html>